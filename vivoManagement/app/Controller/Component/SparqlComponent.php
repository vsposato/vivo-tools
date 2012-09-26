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

	private $sparqlArrayReturn = array();

	private $supportedOutputFormats = array('array' => '&output=json', 'csv' => '&output=json', 'rdf' => '&output=xml', 'tsv' => '&output=json');

	public function __construct(ComponentCollection $collection, $settings = array()) {
		// Initialize the parent constructor
		parent::__construct($collection, $settings);

		// Setup configuration reader
		Configure::config('default', new PhpReader());

		// Now we need to load a configuration file for SPARQL
		Configure::load('sparql', 'default', false);

		// Set the SPARQL endpoint that we are supposed to use
		$this->sparqlEndpoint = Configure::read('sparqlEndpoint');

	}

	public function generateDownload($sparqlQuery = null, $outputFilename = null, $outputFormat = null) {
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

		// Check to see if the output format came in
		if ($outputFormat == null || ! isset($outputFormat) ) {
			// No output format submitted so return a false
			return false;
		} else {
			// We passed an output format so lets set it
			$this->outputFormat = $this->_setOutputFormat($outputFormat);
			// Check to make sure we got a valid format returned
			if ( ! $this->outputFormat ) {
				return false;
			}
		}

		switch ($outputFormat) {
			case 'csv':
				// We need to generate the array to output to CSV
				$this->_generateArray();

				// We need to output to a file
				if ( ! $this->_outputToCSV(false) ) {
					// We didn't return a file so we failed
					return false;
				}

				return $this->outputFilename;
				break;
			case 'tsv':
				// We need to generate the array to output to CSV
				$this->_generateArray();

				// We need to output to a file
				if ( ! $this->_outputToCSV(true) ) {
					// We didn't return a file so we failed
					return false;
				}

				return $this->outputFilename;
				break;
			case 'rdf':

				break;
			default:

				break;
		}

	}

	public function generateDisplay($sparqlQuery = null, $outputFormat = null) {
		// Check to see if the SPARQL query came in
		if ($sparqlQuery == null || ! isset($sparqlQuery) ) {
			// No SPARQL submitted so return a false
			return false;
		} else {
			// We passed a SPARQL query in so lets set that
			$this->sparqlQuery = $sparqlQuery;
		}
		// Check to see if the output format came in
		if ($outputFormat == null || ! isset($outputFormat) ) {
			// No output format submitted so return a false
			return false;
		} else {
			// We passed an output format so lets set it
			$this->outputFormat = $this->_setOutputFormat($outputFormat);
			// Check to make sure we got a valid format returned
			if ( ! $this->outputFormat ) {
				return false;
			}
		}

	}

	private function _setOutputFormat($outputFormat) {
		// Determine if output format is valid
		if (! array_key_exists($outputFormat, $this->supportedOutputFormats) ) {
			// The output format provided doesn't exist
			return false;
		}
		// Return the output format value from the supported formats
		return $this->supportedOutputFormats[$outputFormat];
	}

	private function _outputToCSV($tabSeparate = false) {
		// We need to make sure that we have a valid filename
		if ( ! isset($this->outputFilename) || empty($this->outputFilename) ) {
			return false;
		}
		// Determine if the file exists
		if (file_exists($this->outputFilename)) {
			// If it does exist - attempt to rename it by adding the start timestamp to the end of the filename
			$startTime = microtime();
			$newFile = $this->outputFilename . $startTime;
			if (rename($this->outputFilename, $newFile)) {
				// If the rename was successful - open the file
				$fileHandle = fopen($this->outputFilename,'x');
			} else {
				// If the rename failed - error out
				echo "Error - output file exists and it can't be renamed - {$this->outputFilename}! \n";
				return false;
			}
		} elseif (! file_exists($outputFileName)) {
			debug($this->outputFilename);
			// If the file doesn't already exist - create it and open it for writing
			$fileHandle = fopen($this->outputFilename,'x');
		}
		// Determine if the file was opened correctly
		if ($fileHandle) {
			// If the file was opened properly, then output the data row by row
			foreach ($this->sparqlArrayReturn as $row) {
				if ($tabSeparate) {
					fputcsv($fileHandle, $row, "\t");
				} elseif (! $tabSeparate) {
					fputcsv($fileHandle, $row);
				}
			}
			// Close the file handle
			fclose($fileHandle);
		} else {
			// If the file was not opened properly - exit out
			echo "ERROR - Your file could not be opened! \n";
			return false;
		}
		return true;
	}

	private function _generateArray() {
		// Set the output format to JSON as we need JSON to create a CSV
		$this->outputFormat = '&output=json';

		// Check to make sure that the class has appropriate variables already defined
		if (! isset($this->sparqlEndpoint) || ! isset($this->sparqlQuery)) {
			// We don't have things setup like they should be return a false
			return false;
		}

		// We need to create the SPARQL URL that will execute the query
		$this->curlURL = $this->_createFullURL();

		// Reset the SPARQL array return so that we know it is clean
		$this->sparqlArrayReturn = array();

		// Perform the SPARQL query at hand
		if ( ! $this->_performSPARQLQuery() ) {
			// We received a false back, so something must have happened return false result
			return false;
		}

		// Set raw result to an array as it comes back in JSON format
		$rawResultArray = $this->_jsonReturnToArray($this->rawResult);
		//debug($rawResultArray);

		// Set the header row to appropriate column headers
		$this->sparqlArrayReturn[] = $this->_csvHeaderRowBuilder($rawResultArray['head']['vars']);

		// Parse each row of data to be turned into a row in the CSV
		foreach ($rawResultArray['results']['bindings'] as $row) {
			// Append the next row in a well-formatted array
			$this->sparqlArrayReturn[] = $this->_csvDataRowBuilder($rawResultArray['head']['vars'], $row);
		}

		return true;
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

		//debug($this->rawResult);

		return true;

	}

	private function _jsonReturnToArray() {
		return json_decode($this->rawResult, true);
	}

	private function _createFullURL() {

		// URL encode the query so that we can pass it as part of the URL
		$query = urlencode($this->sparqlQuery);
		// Return the full url to the calling function
		return ($this->sparqlEndpoint . $query . $this->outputFormat);

	}

	private function _csvDataRowBuilder($headerRow, $dataLine) {
		// Create fresh array to return to calling function
		$dataRow = array();

		// We have to take the headerRow and determine if the results data has all the appropriate fields
		foreach ( $headerRow as $key=>$value ) {
			// Check that a key from the header row exists in the data
			if (array_key_exists($value, $dataLine)) {
				// If it does exist - then output the value to an indexed array
				if ($dataLine[$value]['type'] == 'literal') {
					$dataRow[] = "\"" . $dataLine[$value]['value'] . "\"";
				} else {
					$dataRow[] = $dataLine[$value]['value'];
				}

			} elseif (! array_key_exists($value, $dataLine)) {
				// If it does not - then output a null value so that we keep the same number of columns on every row
				$dataRow[] = null;
			}
		}
		// Output the well formed row back to the calling function
		return $dataRow;
	}

	private function _csvHeaderRowBuilder($headerRow) {
		//debug($headerRow);
		// Create fresh array to return to calling function
		$headerReturnRow = array();
		// We have to take the headerRow and determine if the results data has all the appropriate fields
		foreach ( $headerRow as $key=>$value ) {
			$headerReturnRow[] = $value;
		}
		// Output the well formed header row back to the calling function
		return $headerReturnRow;

	}
}

?>