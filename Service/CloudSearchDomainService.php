<?php

namespace SAWSCS\Service;

use Aws\CloudSearchDomain\CloudSearchDomainClient;
use SAWSCS\Builder\UploadBuilder;
use SAWSCS\Handler\SearchHandler;

/*
 * Documentation reference: http://docs.aws.amazon.com/aws-sdk-php/v3/api/api-cloudsearchdomain-2013-01-01.html
 */

class CloudSearchDomainService {

	private $container;
	private $config;

	public function __construct($container, $config){
		$this->container = $container;
		$this->config = $config;
	}

	/*
	 * Scope: search functionalities
	 */
	public function simpleSearch($query, $domain = null){
		return $this->search(array(
			'query' => $query."*"
		), $domain);
	}

	public function search($params, $domain = null){

        return new SearchHandler($this->getClient($this->getValidClientConfiguration($domain, "search"))->search($params));
	}

	public function searchAsync($params, $domain = null){

		return new SearchHandler($this->getClient($this->getValidClientConfiguration($domain, "search"))->searchAsync($params));
	}

	/*
	 * Scope: suggest functionalities
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


	/*
	 * Generics
	 */

	private function getValidClientConfiguration( $domainName = null, $scope = "search" ){

		$domain = $domainName;

		if( empty($domainName) ){
			if( !empty($this->config['default_domain']) ){

				$domain = $this->config['default_domain'];
				
				if( !isset($this->config['domains'][$this->config['default_domain']]) ){
					$conf = array(
						'credentials' => array(
							'key' => (empty($this->config['credentials']['aws_key'])) ? "" : $this->config['credentials']['aws_key'],
							'secret' => (empty($this->config['credentials']['aws_secret'])) ? "" : $this->config['credentials']['aws_secret']
						),
						'region' => (empty($this->config['aws_region'])) ? "" : $this->config['aws_region'],
						'version' => (empty($this->config['aws_version'])) ? "" : $this->config['aws_version']
					);
				}else{

					$main_conf = array(
						'credentials' => array(
							'key' => (empty($this->config['credentials']['aws_key'])) ? "" : $this->config['credentials']['aws_key'],
							'secret' => (empty($this->config['credentials']['aws_secret'])) ? "" : $this->config['credentials']['aws_secret']
						),
						'region' => (empty($this->config['aws_region'])) ? "" : $this->config['aws_region'],
						'version' => (empty($this->config['aws_version'])) ? "" : $this->config['aws_version']
					);

					$conf = array(
						'credentials' => array(
							'aws_key' => (empty($this->config['domains'][$this->config['default_domain']]['credentials']['aws_key'])) ?
											$main_conf['credentials']['key'] :
											$this->config['domains'][$this->config['default_domain']]['credentials']['aws_key'],
							'aws_secret' => (empty($this->config['domains'][$this->config['default_domain']]['credentials']['aws_secret'])) ?
											$main_conf['credentials']['secret'] :
											$this->config['domains'][$this->config['default_domain']]['credentials']['aws_secret']
						),
						'region' => (empty($this->config['domains'][$this->config['default_domain']]['aws_region'])) ?
											$main_conf['region'] :
											$this->config['domains'][$this->config['default_domain']]['aws_region'],
						'version' => (empty($this->config['domains'][$this->config['default_domain']]['aws_version'])) ?
											$main_conf['version'] :
											$this->config['domains'][$this->config['default_domain']]['aws_version']
					);
				}
			}else{
				throw new \Exception('No domain has been specified and default_domain is configured.');
			}
		}else{
			if( empty($this->config['domains'][$domainName]) ){
				$conf = array(
					'credentials' => array(
						'key' => (empty($this->config['credentials']['aws_key'])) ? "" : $this->config['credentials']['aws_key'],
						'secret' => (empty($this->config['credentials']['aws_secret'])) ? "" : $this->config['credentials']['aws_secret']
					),
					'region' => (empty($this->config['aws_region'])) ? "" : $this->config['aws_region'],
					'version' => (empty($this->config['aws_version'])) ? "" : $this->config['aws_version']
				);
			}else{
				$main_conf = array(
						'credentials' => array(
							'key' => (empty($this->config['credentials']['aws_key'])) ? "" : $this->config['credentials']['aws_key'],
							'secret' => (empty($this->config['credentials']['aws_secret'])) ? "" : $this->config['credentials']['aws_secret']
						),
						'region' => (empty($this->config['aws_region'])) ? "" : $this->config['aws_region'],
						'version' => (empty($this->config['aws_version'])) ? "" : $this->config['aws_version']
					);

					$conf = array(
						'credentials' => array(
							'aws_key' => (empty($this->config['domains'][$domainName]['credentials']['aws_key'])) ?
											$main_conf['credentials']['key'] :
											$this->config['domains'][$domainName]['credentials']['aws_key'],
							'aws_secret' => (empty($this->config['domains'][$domainName]['credentials']['aws_secret'])) ?
											$main_conf['credentials']['secret'] :
											$this->config['domains'][$domainName]['credentials']['aws_secret']
						),
						'region' => (empty($this->config['domains'][$domainName]['aws_region'])) ?
											$main_conf['region'] :
											$this->config['domains'][$domainName]['aws_region'],
						'version' => (empty($this->config['domains'][$domainName]['aws_version'])) ?
											$main_conf['version'] :
											$this->config['domains'][$domainName]['aws_version']
					);
			}
		}

		if(
			empty($conf['credentials'])
			|| empty($conf['credentials']['key'])
			|| empty($conf['credentials']['secret'])
			|| empty($conf['region'])
			|| empty($conf['version'])
		){
			throw new \Exception('No valid SAWSCSD client configuration was found.');
		}else{
			if( !$this->container->get('sawscs')->domainExists($domain) ){
				throw new \Exception('Domain '.$domain.' doesn\'t exist or is not available.');
			}else{
				$conf['endpoint'] = $this->container->get('sawscs')->describeDomain($domain)->{"get".ucwords($scope)."Endpoint"}();
			}
		}

		return $conf;

	}

	private function getClient($conf){
		return new CloudSearchDomainClient($conf);
	}

	public function createUploadBuilder( $domainName ){
		
		return new UploadBuilder($this->getClient($this->getValidClientConfiguration( $domainName , "doc")));
	}
}
