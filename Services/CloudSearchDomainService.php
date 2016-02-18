<?php

namespace Soulbound\AWSCloudSearchBundle\Services;

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
	public function search($params){

        $result = $this->container->get('aws.cloudsearchdomain')->search($params);
        // var_dump($response->get("status"));
        // var_dump($response->get("hits"));
        // var_dump($response->get("@metadata"));
        // exit();

        return $result;
	}

	public function searchAsync($params){

		$promise = $this->container->get('aws.cloudsearchdomain')->searchAsync($params);

		return $promise;
	}

	/*
	 * Scope: suggest functionalities
	 *
	 * Documentation reference: http://docs.aws.amazon.com/aws-sdk-php/v3/api/api-cloudsearchdomain-2013-01-01.html#suggest
	 */
	public function suggest($params){

        $result = $this->container->get('aws.cloudsearchdomain')->suggest($params);
        // var_dump($response->get("status"));
        // var_dump($response->get("suggest"));
        // var_dump($response->get("@metadata"));
        // exit();

        return $result;
	}

	public function suggestAsync($params){

		$promise = $this->container->get('aws.cloudsearchdomain')->suggestAsync($params);

		return $promise;
	}

	/*
	 * Scope: upload functionalities
	 *
	 * Documentation reference: http://docs.aws.amazon.com/aws-sdk-php/v3/api/api-cloudsearchdomain-2013-01-01.html#uploaddocuments
	 */
	public function uploadDocuments($params) {

        $result = $this->container->get('aws.cloudsearchdomain')->uploadDocuments($params);
        // var_dump($response->get("adds"));
        // var_dump($response->get("deletes"));
        // var_dump($response->get("status"));
        // var_dump($response->get("warnings"));
        // var_dump($response->get("@metadata"));
        // exit();

        return $result;
	}

	public function uploadDocumentsAsync($params){

		$promise = $this->container->get('aws.cloudsearchdomain')->uploadDocumentsAsync($params);

		return $promise;
	}
}
