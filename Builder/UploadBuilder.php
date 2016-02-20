<?php

namespace SAWSCS\Builder;

use Aws\AwsClient;

class UploadBuilder {

	private $client;

	private $contentType;
	private $operations;

	public function __construct(AwsClient $client){
		$this->client = $client;
		
		$this->contentType = "application/json";
		$this->operations = array();
	}

	public function add($type, $id, $fields = null){
		if( in_array($type, array('add', 'delete')) ){
			$operation = array(
				'type' => $type
			);

			if( !is_null($id) ){
				$operation['id'] = $id;
			}

			if( is_array($fields) ){
				$operation['fields'] = $fields;
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

		var_dump($this->generateJson());

		$this->client->uploadDocuments(array(
            'contentType' => $this->contentType,
            'documents' => $this->generateJson()
        ));
	}
}
