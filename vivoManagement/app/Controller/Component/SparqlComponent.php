<?php

App::uses('Component', 'Controller');
App::uses('PhpReader', 'Configure');

class SparqlComponent extends Component {

	public $sparqlEndpoint;

	public $outputFormat;

	public $sparqlQuery;

	public $outputFilename;

	private $curlURL;

	private $rawResult;

	private $supportedOutputFormats = array('','','');

	public function __construct(ComponentCollection $collection, $settings = array()) {
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

	public function generateCSV($sparqlQuery = null, $outputFilename = null) {
		// Check to see if the SPARQL query came in
		if ($sparqlQuery == null || ! isset($sparqlQuery) ) {
			// No SPARQL submitted so return a false
			return false;
		} else {
			// We passed a SPARQL query in so lets set that
			$this->sparqlQuery = $sparqlQuery;
		}

		// Check to see if the output filename came in
		if ($outputFilename == null || ! isset($outputFilename) ) {
			// No output filename submitted so return a false
			return false;
		} else {
			// We passed an output filename so lets set it
			$this->outputFilename = $outputFilename;
		}

		// Set the output format to JSON as we need JSON to create a CSV
		$this->outputFormat = '&output=json';



	}

	public function generateRDF($sparqlQuery = null) {

	}

	private function generateArray($sparqlQuery = null) {

	}

	private function _performSPARQLQuery() {

		/*
		 * This function will take a full URL and a query to execute and
		 * it will perform the query using CURL. It will return the output
		 * as a string.
		 *
		 */


		// Iniitialize CURL for communication with SPARQL endpoint
		$curlInit = curl_init();

		// Set options for CURL
		// Set the URL that CURL will talk with to the $fullURL built earlier
		curl_setopt($curlInit, CURLOPT_URL, $this->curlURL);
		// Set CURL to 'return' the value to the variable so that it can be processed
		curl_setopt($curlInit, CURLOPT_RETURNTRANSFER, true);
		// Execute the CURL and pass response back to $curlReturn
		$this->rawResult = curl_exec($curlInit);

		// Close out the CURL
		curl_close($curlInit);

	/*	echo "<pre>";
		print_r($this->rawResult);
		echo "</pre>"; */

		return true;


	}

	private function _createFullURL() {

		// URL encode the query so that we can pass it as part of the URL
		$query = urlencode($this->sparqlQuery);
		// Return the full url to the calling function
		return ($this->sparqlEndpoint . $query . $this->outputFormat);

	}

	private function _csvDataRowBuilder() {

	}

	private function _csvHeaderRowBuilder() {

	}

	private function
}

?>