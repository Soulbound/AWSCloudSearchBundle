<?php

namespace SAWSCS\Handler;

class IndexFieldHandler {

	private $container;
	private $domainName;
	private $indexField = null;

	public function __construct($container, $domainName, $indexField){

		$this->container = $container;
		$this->domainName = $domainName;

		if( is_array($indexField) ){
			$this->indexField = $indexField;
		}
	}

	public function getDomain(){
		$this->container->get('sawscs')->describeDomain($this->domainName);
	}

	/*
	 * Get all basic domain details
	 */

	public function getDetails(){
		return $this->indexField;
	}

	/*
	 * Get single domain details from data array
	 */

	public function getName(){
		return $this->indexField['Options']['IndexFieldName'];
	}

	public function getType(){
		return $this->indexField['Options']['IndexFieldType'];
	}

	public function getOptions(){
		return $this->indexField['Options'][ucwords($this->getType())."Options"];
	}

	public function getCreationDate(){
		return $this->indexField['Status']['CreationDate'];
	}

	public function getUpdateDate(){
		return $this->indexField['Status']['UpdateDate'];
	}

	public function getUpdateVersion(){
		return $this->indexField['Status']['UpdateVersion'];
	}

	public function getState(){
		return $this->indexField['Status']['State'];
	}

	public function getPendingDeletion(){
		return $this->indexField['Status']['PendingDeletion'];
	}
}
