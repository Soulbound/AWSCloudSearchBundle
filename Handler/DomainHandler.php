<?php

namespace SAWSCS\Handler;

class DomainHandler {

	private $container;
	private $domainDetails = null;

	public function __construct($container, $domainDetails){

		$this->container = $container;

		if( is_array($domainDetails) ){
			$this->domainDetails = $domainDetails;
		}
	}

	/*
	 * Get domain indexes
	 */

	public function getIndexFieldsList(){
		return $this->container->get('sawscs')->describeIndexFields($this->getName())->getIndexFieldsList();
	}

	public function getIndexFields(){
		return $this->container->get('sawscs')->describeIndexFields($this->getName())->getIndexFields();
	}

	public function getIndexField($name){
		return $this->container->get('sawscs')->describeIndexFields($this->getName())->getIndexField($name);
	}

	/*
	 * Get all basic domain details
	 */

	public function getDetails(){
		return $this->domainDetails;
	}

	/*
	 * Get single domain details from data array
	 */

	public function getId(){
		return $this->domainDetails['DomainId'];
	}

	public function getName(){
		return $this->domainDetails['DomainName'];
	}

	public function getARN(){
		return $this->domainDetails['ARN'];
	}

	public function getCreated(){
		return $this->domainDetails['Created'];
	}

	public function getDeleted(){
		return $this->domainDetails['Deleted'];
	}

	public function getDocEndpoint(){
		return $this->domainDetails['DocService']['Endpoint'];
	}
	
	public function getSearchEndpoint(){
		return $this->domainDetails['SearchService']['Endpoint'];
	}
	
	public function getRequiresIndex(){
		return $this->domainDetails['RequiresIndexDocuments'];
	}

	public function getProcessing(){
		return $this->domainDetails['Processing'];
	}

	public function getInstanceType(){
		return $this->domainDetails['SearchInstanceType'];
	}

	public function getPartitionCount(){
		return $this->domainDetails['SearchPartitionCount'];
	}

	public function getInstanceCount(){
		return $this->domainDetails['SearchInstanceCount'];
	}

	public function getLimits(){
		return $this->domainDetails['Limits'];
	}
}
