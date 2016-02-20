<?php

namespace SAWSCS\Controller;

// Annotations
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

// Extends
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

// Injections
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class AjaxController extends Controller
{
    /**
     * @Route("/search", name="sawscs_search")
     */
    public function searchAction(Request $request)
    {
    	$results = (!empty($request->query->get('term'))) ? $this->container->get('sawscsd')->simpleSearch($request->query->get('term'))->getAutocompleteResults() : array();

        $returnResponse = new Response(json_encode($results));
        $returnResponse->headers->set('Content-Type', 'application/json');
        return $returnResponse;
    }
}
