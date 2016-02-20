<?php

namespace SAWSCS\Handler;

class IndexFieldListHandler {

	private $container;
	private $domainName;
	private $indexFields = null;

	public function __construct($container, $domainName, $result){

		$this->container = $container;
		$this->domainName = $domainName;

		if( is_a($result, "Aws\Result") ){
			$this->indexFields = $result;
		}else{
			throw new \Exception('API returned an invalid object.');
		}
	}

	public function getDomain(){
		$this->container->get('sawscs')->describeDomain($this->domainName);
	}

	public function getIndexFieldsList(){
		return $this->indexFields->get('IndexFields');
	}

	public function getIndexFields(){
		$domains = array();
		foreach($this->indexFields->get('IndexFields') as $indexField){
			$domains[$indexField['Options']['IndexFieldName']] = new IndexFieldHandler($this->container, $this->domainName, $indexField);
		}
		return $domains;
	}

	public function getIndexField( $name ){
		$returnDomain = null;
		foreach($this->indexFields->get('IndexFields') as $indexField){
			if( $indexField['Options']['IndexFieldName'] == $name ){
				$returnDomain = new IndexFieldHandler($this->container, $this->domainName, $indexField);
			}
		}
		return $returnDomain;
	}

	public function getStatusCode(){
		return $this->indexFields->get('@metadata')['statusCode'];
	}

	public function getRequestUrl(){
		return $this->indexFields->get('@metadata')['effectiveUri'];
	}

	public function getHeaders(){
		return $this->indexFields->get('@metadata')['headers'];
	}
}
