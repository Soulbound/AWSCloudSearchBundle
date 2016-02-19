<?php
namespace Soulbound\AWSCloudSearchBundle\Clients;

use Symfony\Component\DependencyInjection\ContainerAware;

use Aws\AwsClient;
use Aws\HandlerList;
use Aws\ClientResolver;
use GuzzleHttp\Psr7\Uri;
use Aws\CloudSearchDomain\CloudSearchDomainClient as Client;

/**
 * This client is used to search and upload documents to an **Amazon CloudSearch** Domain.
 *
 * @method \Aws\Result search(array $args = [])
 * @method \GuzzleHttp\Promise\Promise searchAsync(array $args = [])
 * @method \Aws\Result suggest(array $args = [])
 * @method \GuzzleHttp\Promise\Promise suggestAsync(array $args = [])
 * @method \Aws\Result uploadDocuments(array $args = [])
 * @method \GuzzleHttp\Promise\Promise uploadDocumentsAsync(array $args = [])
 */
class CloudSearchDomainClient extends Client
{
    private $container;

    public function __construct($container, $args = array()){
        $this->container = $container;

        // add credentials from parameters
        $args['credentials'] = array(
            'key' => $container->getParameter('aws_key'),
            'secret' => $container->getParameter('aws_secret')
        );

        // check for missing configuration in parameters
        if(empty($args['credentials'])){
            if($container->hasParameter('aws_key') && $container->hasParameter('aws_secret')){
                $args['credentials'] = array(
                    'key' => $container->getParameter('aws_key'),
                    'secret' => $container->getParameter('aws_secret')
                );
            }
        }
        if(empty($args['region'])){
            if($container->hasParameter('aws_region')){
                $args['region'] = $container->getParameter('aws_region');
            }
        }
        if(empty($args['version'])){
            if($container->hasParameter('aws_version')){
                $args['version'] = $container->getParameter('aws_version');
            }
        }

        parent::__construct($args);
    }
}
