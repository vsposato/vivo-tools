<?php

App::uses('Component', 'Controller');
App::uses('PhpReader', 'Configure');

class SparqlComponent extends Component {

	public $sparqlEndpoint;

	public $outputFormat;

	public $sparqlQuery;

	public $outputFilename;

	private $supportedOutputFormats = array('','','');

	public function __construct() {
		// Initialize the parent constructor
		parent::__construct();

		// Setup configuration reader
		Configure::config('default', new PhpReader());

		// Now we need to load a configuration file for SPARQL
		Configure::load('sparql', 'default', false);

		// Set the SPARQL endpoint that we are supposed to use
		$this->sparqlEndpoint = Configure::read('sparqlEndpoint');

	}

	public function __destruct() {
		parent::__destruct();
	}

	public function generateCSV() {

	}

	public function generateRDF() {

	}

	private function _performSPARQLQuery() {

	}

	private function _createFullURL() {

	}

}

?>