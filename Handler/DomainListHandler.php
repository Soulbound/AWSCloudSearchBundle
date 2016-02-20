<?php

namespace SAWSCS\Handler;

class DomainListHandler {

	private $container;
	private $domainList = null;

	public function __construct($container, $result){

		$this->container = $container;

		if( is_a($result, "Aws\Result") ){
			$this->domainList = $result;
		}else{
			throw new \Exception('API returned an invalid object.');
		}
	}

	public function getDomainStatusList(){
		return $this->domainList->get('DomainStatusList');
	}

	public function getDomainList(){
		$domains = array();
		foreach($this->domainList->get('DomainStatusList') as $domain){
			$domains[$domain['DomainName']] = new DomainHandler($this->container, $domain);
		}
		return $domains;
	}

	public function getDomain( $name ){
		$returnDomain = null;
		foreach($this->domainList->get('DomainStatusList') as $domain){
			if( $domain['DomainName'] == $name ){
				$returnDomain = new DomainHandler($this->container, $domain);
			}
		}
		return $returnDomain;
	}

	public function getStatusCode(){
		return $this->domainList->get('@metadata')['statusCode'];
	}

	public function getRequestUrl(){
		return $this->domainList->get('@metadata')['effectiveUri'];
	}

	public function getHeaders(){
		return $this->domainList->get('@metadata')['headers'];
	}
}
