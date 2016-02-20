<?php

namespace SAWSCS\EventListener;

use Doctrine\ORM\Event\LifecycleEventArgs;
use Doctrine\ORM\Event\PostFlushEventArgs;

use Doctrine\Common\Annotations\AnnotationReader;

class DomainListener
{
    private $container;
    private $queue;

    public function __construct($container){
        $this->container = $container;

        $this->queue = array();
    }

    /*
     * Add insert to the queue
     */
    public function postPersist(LifecycleEventArgs $args)
    {
        $this->addToQueue("insert", $args->getEntity());
    }

    /*
     * Add update to the queue
     */
    public function postUpdate(LifecycleEventArgs $args)
    {
        $this->addToQueue("update", $args->getEntity());
    }

    /*
     * Add delete to the queue
     */
    public function preRemove(LifecycleEventArgs $args)
    {
        $this->addToQueue("delete", $args->getEntity());
    }

    /*
     * Flush queue
     */
    public function postFlush(PostFlushEventArgs $args)
    {
        $this->flush();
    }

    /*
     * Add to queue
     */
    private function addToQueue( $type, $entity ){

        $reflectionClass = new \ReflectionClass($entity);

        $reader = new AnnotationReader();
        $domainAnnotation = $reader->getClassAnnotation($reflectionClass, 'SAWSCS\\Annotation\\SAWSCS\\Domain');
        if(is_a($domainAnnotation, 'SAWSCS\Annotation\SAWSCS\Domain')) {

            if(!isset($this->queue[$domainAnnotation->getName()])){
                $this->queue[$domainAnnotation->getName()] = array();
            }

            switch($type){
                case "insert":
                case "update":
                    $action = "add";
                    break;
                case "delete":
                    $action = "delete";
                    break;
            }

            $document = array(
                'action' => $action,
                'fields' => array(

                )
            );

            foreach($reflectionClass->getProperties() as $reflectionProperty){
                $indexAnnotations = $reader->getPropertyAnnotation($reflectionProperty, 'SAWSCS\\Annotation\\SAWSCS\\Index');

                if(is_a($indexAnnotations, 'SAWSCS\Annotation\SAWSCS\Index')){
                    if(!empty($indexAnnotations->getName())){
                        $document['fields'][$indexAnnotations->getName()] = $entity->{"get".ucwords($reflectionProperty->name)}();
                    }
                }
            }

            if( sizeof($document['fields']) > 0 ){
                $this->queue[$domainAnnotation->getName()][] = $document;
            }
        }
        // Do something
    }

    private function flush(){
        if( !empty($this->queue) ){

            foreach( $this->queue as $domainName => $documents ){
                if( $this->container->get('sawscs')->domainExists($domainName) ){
                    $ub = $this->container->get('sawscsd')->createUploadBuilder($domainName);
                    foreach( $documents as $document ){
                        switch ($document['action']) {
                            case 'add':
                                if( isset($document['fields']['id']) ){
                                    $documentId = $document['fields']['id'];
                                    unset($document['fields']['id']);
                                }else{
                                    $documentId = null;
                                }
                                $ub->add("add",$documentId, $document['fields']);
                                break;
                            case 'delete':
                                if( isset($document['fields']['id']) ){
                                    $ub->add("delete", $document['fields']['id']);
                                }
                                break;
                        }
                    }
                    $ub->run();
                }
            }
        }
    }
}