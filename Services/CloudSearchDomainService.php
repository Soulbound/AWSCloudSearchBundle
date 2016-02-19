<?php

namespace Soulbound\AWSCloudSearchBundle\Services;

use Soulbound\AWSCloudSearchBundle\Clients\CloudSearchDomainClient;
use Soulbound\AWSCloudSearchBundle\Builders\UploadBuilder;
use Soulbound\AWSCloudSearchBundle\Handlers\SearchHandler;

class CloudSearchDomainService {

	private $container = null;

	public function __construct($container){
		$this->container = $container;
	}

	/*
	 * Scope: search functionalities
	 *
	 * Documentation reference: http://docs.aws.amazon.com/aws-sdk-php/v3/api/api-cloudsearchdomain-2013-01-01.html#search
	 */
	public function simpleSearch($query, $overrides = array()){
		return $this->search(array(
			'query' => $query."*"
		));
	}

	public function search($params, $overrides = array() ){

        return new SearchHandler($this->getClient($overrides)->search($params));
	}

	public function searchAsync($params, $overrides = array()){

		return new SearchHandler($this->getClient($overrides)->searchAsync($params));
	}

	/*
	 * Scope: suggest functionalities
	 *
	 * Documentation reference: http://docs.aws.amazon.com/aws-sdk-php/v3/api/api-cloudsearchdomain-2013-01-01.html#suggest
	 */
	public function suggest($params, $overrides = array()){

        $result = $this->getClient($overrides)->suggest($params);
        // var_dump($response->get("status"));
        // var_dump($response->get("suggest"));
        // var_dump($response->get("@metadata"));
        // exit();

        return $result;
	}

	public function suggestAsync($params, $overrides = array()){

		$promise = $this->getClient($overrides)->suggestAsync($params);

		return $promise;
	}

	/*
	 * Scope: upload functionalities
	 *
	 * Documentation reference: http://docs.aws.amazon.com/aws-sdk-php/v3/api/api-cloudsearchdomain-2013-01-01.html#uploaddocuments
	 */
	public function uploadDocuments($params, $overrides = array()) {

        $result = $this->getClient($overrides)->uploadDocuments($params);
        // var_dump($response->get("adds"));
        // var_dump($response->get("deletes"));
        // var_dump($response->get("status"));
        // var_dump($response->get("warnings"));
        // var_dump($response->get("@metadata"));
        // exit();

        return $result;
	}

	public function uploadDocumentsAsync($params, $overrides = array()){

		$promise = $this->getClient($overrides)->uploadDocumentsAsync($params);

		return $promise;
	}

	private function getClient($overrides){
		if( empty($overrides['endpoint']) ){
			return $this->container->get('aws.cloudsearchdomain');
		}else{
			return new CloudSearchDomainClient($this->container, $overrides);
		}
	}

	public function createUploadBuilder($settings = array()){
		return new UploadBuilder($this->getClient($settings), $settings);
	}
}
