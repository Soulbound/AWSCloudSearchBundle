<?php

namespace Soulbound\AWSCloudSearchBundle\Builders;

use Aws\AwsClient;

class UploadBuilder {

	private $client;

	private $contentType;
	private $operations;

	public function __construct(AwsClient $client, $settings = array()){
		$this->client = $client;

		if(!is_array($settings)){
			throw new \Exception('__construct requires the second parameter to be either an array or omitted entirely.');
		}

		$this->contentType = (isset($settings['contentType']) && is_string($settings['contentType'])) ? $settings['contentType'] : "application/json";
		$this->operations = array();
	}

	public function add($type, $data){
		if( in_array($type, array('add', 'delete')) ){
			$operation = array(
				"type" => $type,
				"fields" => array()
			);
			foreach($data as $property => $value){
				if( $property == "id" ){
					$operation['id'] = $value;
				}else{
					$operation['fields'][$property] = $value;
				}
			}
			$this->operations[] = $operation;
		}else{
			throw new \Exception('Operation type not supported.');
		}

		return $this;
	}

	public function generateJson(){

		$json = '[';

		foreach( $this->operations as $key => $operation ){
			$json .= json_encode($operation);
			if( $key < sizeof($this->operations)-1 ){
				$json .= ",";
			}
		}
		
		$json .= ']';

		return $json;
	}

	public function run(){
		if( empty($this->operations) ){
			throw new \Exception('You can`t run an empty update.');
		}

		$this->client->uploadDocuments(array(
            'contentType' => $this->contentType,
            'documents' => $this->generateJson()
        ));
	}
}
