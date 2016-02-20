<?php

namespace SAWSCS\Handler;

class SearchHandler {

	private $result = null;

	public function __construct($result){

		if( is_a($result, "Aws\Result") ){
			$this->result = $result;
		}else{
			throw new \Exception('API returned an invalid object.');
		}
	}

	public function getTimeMS(){
		return $this->result->get('status')['timems'];
	}

	public function getRequestId(){
		return $this->result->get('status')['rid'];
	}

	public function getCount(){
		return $this->result->get('hits')['found'];
	}

	public function getStart(){
		return $this->result->get('hits')['start'];
	}

	public function getResults(){
		return $this->result->get('hits')['hit'];
	}

// [{"id":"Nycticorax nycticorax","label":"Black-crowned Night Heron","value":"Black-crowned Night Heron"}]
	public function getAutocompleteResults(){
		$results = array();
		foreach( $this->result->get('hits')['hit'] as $hit ){
			$results[] = array(
				"id" => $hit['id'],
				"label" => $hit['fields']['title'][0],
				"value" => "/".$hit['fields']['title'][0],
			);
		}
		return $results;
	}

	public function getStatusCode(){
		return $this->result->get('@metadata')['statusCode'];
	}

	public function getRequestUrl(){
		return $this->result->get('@metadata')['effectiveUri'];
	}

	public function getHeaders(){
		return $this->result->get('@metadata')['headers'];
	}

}
