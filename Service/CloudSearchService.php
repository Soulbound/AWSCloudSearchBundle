<?php

namespace SAWSCS\Service;

use Aws\CloudSearch\CloudSearchClient;
use SAWSCS\Handler\DomainListHandler;
use SAWSCS\Handler\IndexFieldListHandler;

/*
 * Documentation reference: http://docs.aws.amazon.com/aws-sdk-php/v3/api/api-cloudsearch-2013-01-01.html
 */

class CloudSearchService {

	private $container;
	private $config;

	private $domainList;

	public function __construct($container, $config){
		$this->container = $container;
		$this->config = $config;
	}

	public function describeDomain( $name, $overrides = array() ){

        return $this->describeDomains( array( $name ) )->getDomain( $name );
	}

	public function describeDomains( $names = array(), $overrides = array() ){

		if(sizeof($names) > 0){
			$params = array(
				'DomainNames' => $names
			);
		}else{
			$params = array();
		}

        return new DomainListHandler($this->container, $this->getClient($this->getValidClientConfiguration())->describeDomains($params));
	}

	public function describeIndexFields( $domain, $fields = null, $deployed = null, $overrides = array() ){
		$params = array(
			'DomainName' => $domain
		);

		if(is_array($fields) && sizeof($fields) > 0){
			$params['FieldNames'] = $fields;
		}

		if(is_bool($deployed)){
			$params['Deployed'] = $deployed;
		}

        return new IndexFieldListHandler($this->container, $domain, $this->getClient($this->getValidClientConfiguration())->describeIndexFields($params));
	}

	public function domainExists($name){
		if( is_null($this->domainList) ){
			$this->domainList = $this->describeDomains();
		}

		return !empty($this->domainList->getDomain($name));
	}

	/*
	 * Scope: domain CRUD functionalities @todo
	 */


	/*
	 * Generics
	 */

	private function getValidClientConfiguration(){

		$conf = array(
			'credentials' => array(
				'key' => (empty($this->config['credentials']['aws_key'])) ? "" : $this->config['credentials']['aws_key'],
				'secret' => (empty($this->config['credentials']['aws_secret'])) ? "" : $this->config['credentials']['aws_secret']
			),
			'region' => (empty($this->config['aws_region'])) ? "" : $this->config['aws_region'],
			'version' => (empty($this->config['aws_version'])) ? "" : $this->config['aws_version']
		);

		if(
			empty($conf['credentials'])
			|| empty($conf['credentials']['key'])
			|| empty($conf['credentials']['secret'])
			|| empty($conf['region'])
			|| empty($conf['version'])
		){
			throw new \Exception('No valid SAWSCS client configuration was found.');
		}

		return $conf;
	}

	private function getClient($conf){

		return new CloudSearchClient($conf);
	}
}
