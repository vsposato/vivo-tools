<?php

App::uses('Component', 'Controller');
App::uses('PhpReader', 'Configure');
/**
 * Sparql Component
 *
 * @property SparqlComponent $Sparql
 */
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

	public function generateResults($sparqlQuery = null, $outputFilename = null, $outputFormat = null, $parameterized = false, $parameters = array()) {
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
                if ($parameterized) {
                    $this->_generateParameterizedArray($this->_createHeaderArray($parameters[0], $parameters[1]),$this->_removeHeaderRows($parameters, 2, false));
                } else {
                    // We need to generate the array to output to CSV
                    $this->_generateArray();
                }
				// We need to output to a file
				if ( ! $this->_outputToCSV(false) ) {
					// We didn't return a file so we failed
					return false;
				}
				return $this->outputFilename;
				break;
			case 'tsv':
                if ($parameterized) {
                    $this->_generateParameterizedArray($this->_createHeaderArray($parameters[0], $parameters[1]),$this->_removeHeaderRows($parameters, 2, false));
                } else {
                    // We need to generate the array to output to CSV
                    $this->_generateArray();
                }
				// We need to output to a file
				if ( ! $this->_outputToCSV(true) ) {
					// We didn't return a file so we failed
					return false;
				}
				return $this->outputFilename;
				break;
            case 'array':
                if ($parameterized) {
                    if (! $this->_generateParameterizedArray($this->_createHeaderArray($parameters[0], $parameters[1]),$this->_removeHeaderRows($parameters, 2, false))) {
                        // We didn't get results back so return false
                        return false;
                    }
                } else {
                    // We need to generate the array to return to calling function
                    if (! $this->_generateArray()) {
                        // We didn't get results back so return false
                        return false;
                    }
                }
				// We need to return the created array
				return $this->sparqlArrayReturn;
				break;
			case 'rdf':
                if ($parameterized) {
                    //debug($parameters);
                    $this->_createRDFfromQuery($this->_createHeaderArray($parameters[0], $parameters[1]),$this->_removeHeaderRows($parameters, 2, false));
                } else {
                    $this->_generateRDF();
                }
                // We need to output to a file
                if ( ! $this->_outputToFile() ) {
                    // We didn't return a file so we failed
                    return false;
                }
                return $this->outputFilename;
				break;
			default:
                // We need to generate the array to output to CSV
                $this->_generateArray();
                // We need to output to a file
                if ( ! $this->_outputToCSV(false) ) {
                    // We didn't return a file so we failed
                    return false;
                }
                return $this->outputFilename;
                break;
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
		} elseif (! file_exists($this->outputFilename)) {
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

    private function _outputToFile() {
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
        } elseif (! file_exists($this->outputFilename)) {
            // If the file doesn't already exist - create it and open it for writing
            $fileHandle = fopen($this->outputFilename,'x');
        }
        // Determine if the file was opened correctly
        if ($fileHandle) {
            // If the file was opened properly, then output the xml
            fputs($fileHandle, $this->rawResult);
            // Close the file handle
            fclose($fileHandle);
        } else {
            // If the file was not opened properly - exit out
            echo "ERROR - Your file could not be opened! \n";
            return false;
        }
        return true;
    }

    private function _generateParameterizedArray($headerArray, $dataOnly) {
        // Set the output format to JSON as we need JSON to create a CSV
        $this->outputFormat = '&output=json';
        // Create the master XML string
        $resultArray = array();
        // Initialize a counter variable
        $rowCounter = 0;
        // Loop through each of the rows of data provided
        foreach ($dataOnly as $row) {
            // Replace parameters within query with values from data array
            $tempQuery = $this->_parameterizeQuery($headerArray, $this->sparqlQuery, $row);
            // Check to make sure that the class has appropriate variables already defined
            if (! isset($this->sparqlEndpoint) || ! isset($this->sparqlQuery)) {
                // We don't have things setup like they should be return a false
                return false;
            }
            // Run the generate array command with the parameterized query
            $this->_generateArray($tempQuery);
            // Temporary variable to hold the RDF response from SPARQL
            //$tempResult = $this->_jsonReturnToArray() $this->rawResult;
            if ($rowCounter == 0) {
                // If this is the first time through (or first result) then we need to keep the entire array as it has the header information in it
                $resultArray = array_merge($resultArray, $this->sparqlArrayReturn);
            } elseif ($rowCounter >= 1) {
                // If this is not our first time throught (or first result) then we need only the data results
                // Shift the first row off of the array as this contains header data
                array_shift($this->sparqlArrayReturn);
                // Now merge the remaining items back into the resulting array
                $resultArray = array_merge($resultArray, $this->sparqlArrayReturn);
            }
            // Incrememnt our counter to keep track of loops through
            $rowCounter++;
        }
        // Return the resultant array back to the main sparqlArrayReturn
        // Clear sparqlArrayReturn first
        unset($this->sparqlArrayReturn);
        // Set the results
        $this->sparqlArrayReturn = array_merge($resultArray);
        debug($this->sparqlArrayReturn);
    }

	private function _generateArray($altQuery = null) {
		// Set the output format to JSON as we need JSON to create a CSV
		$this->outputFormat = '&output=json';
		// Check to make sure that the class has appropriate variables already defined
		if (! isset($this->sparqlEndpoint) || ! isset($this->sparqlQuery)) {
			// We don't have things setup like they should be return a false
			return false;
		}
        if ($altQuery) {
            // We need to create the SPARQL URL that will execute the query
            $this->curlURL = $this->_createFullURL($altQuery);
        } elseif (!$altQuery) {
            // We need to create the SPARQL URL that will execute the query
            $this->curlURL = $this->_createFullURL();
        }
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

    private function _processRDF($inputRDF, $stripHeaders) {
        // Instantiate the XML reader
        $rdfResult = new XMLReader();
        // Load the RDF passed in to the XML reader
        $rdfResult->XML($inputRDF, "UTF-8");
        // Check to see if we are supposed to remove the header information
        if ($stripHeaders) {
            // We are removing all the beginning header information
            // Loop through the XML tree to find what we are looking for
            while ($rdfResult->read()) {
                // Check to see if this is an ELEMENT node as opposed to a TEXT or attribute
                if ($rdfResult->nodeType == XMLReader::ELEMENT) {
                    // Check to see if it is a description node - as this will hold the guts of the data
                    if ($rdfResult->localName === "Description") {
                        // Return the node back to the calling function
                        return $rdfResult->expand();
                    }
                }
            }
        } elseif (! $stripHeaders) {
            // We are not supposed to remove headers so send the entire XML document back
            $rdfResult->read();
            return $rdfResult->readOuterXML();
        }
    }

    private function _generateRDF() {
        // Set the output format to XML as we need XML to output to a file
        $this->outputFormat = '&output=xml';

        // Check to make sure that the class has appropriate variables already defined
        if (! isset($this->sparqlEndpoint) || ! isset($this->sparqlQuery)) {
            // We don't have things setup like they should be return a false
            return false;
        }

        // We need to create the SPARQL URL that will execute the query
        $this->curlURL = $this->_createFullURL();

        // Perform the SPARQL query at hand
        if ( ! $this->_performSPARQLQuery() ) {
            // We received a false back, so something must have happened return false result
            return false;
        }

        return true;

    }
	private function _performSPARQLQuery($counter = null) {
		// Iniitialize CURL for communication with SPARQL endpoint
		$curlInit = curl_init();
		// Set options for CURL
		// Set the URL that CURL will talk with to the $fullURL built earlier
		curl_setopt($curlInit, CURLOPT_URL, $this->curlURL);
		// Set CURL to 'return' the value to the variable so that it can be processed
		curl_setopt($curlInit, CURLOPT_RETURNTRANSFER, true);
		// Execute the CURL and pass response back to $curlReturn
		$this->rawResult = curl_exec($curlInit);
        // Determine the response from the SPARQL server
        $curlResponseCode = curl_getinfo($curlInit, CURLINFO_HTTP_CODE);
        // If the response was not 200 (success) and our counter is less than 3 times
        if (($curlResponseCode !== 200) && ($counter < 3)) {
            // We need to call the query again to make sure it wasn't a temporary connection issue
            $this->_performSPARQLQuery(++$counter);
        } elseif (($curlResponseCode !== 200) && ($counter === 3)) {
            // This is our 3rd time through so we need to return a false back to the calling function
            return false;
        }
		// Close out the CURL
		curl_close($curlInit);
		//debug($this->rawResult);
		return true;
	}

	private function _jsonReturnToArray() {
		return json_decode($this->rawResult, true);
	}

	private function _createFullURL($sparqlQuery = null) {

        if ($sparqlQuery) {
            // URL encode the query so that we can pass it as part of the URL
            $query = urlencode($sparqlQuery);
        } elseif (!$sparqlQuery) {
            // URL encode the query so that we can pass it as part of the URL
            $query = urlencode($this->sparqlQuery);
        }

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

    private function _createHeaderArray($headerRow, $typeRow) {

        // Create an array to hold the completed header array
        $headerArray = array();

        foreach ($headerRow as $index=>$value) {
            // Initialize a blank array to perform our work on
            $tempArray = array();

            // The index of this column in the header will be carried over to the index key
            $tempArray['index'] = $index;
            // Get the value type from the corresponding index of the typeRow
            $tempArray['valueType'] = $typeRow[$index];
            // Get the variable value from the header row value
            $tempArray['key'] = $value;
            // Place this in the next numeric index available in the array
            $headerArray[] = $tempArray;
        }
        /*	echo "<pre>";
        print_r($headerArray);
        echo "</pre>";*/

        // Return the array to the calling function
        return $headerArray;
    }

    private function _parameterizeQuery($headerRow, $query, $dataRow) {

        // Initialize a query that can search and replaced
        $parameterizedQuery = $query;

        // Loop through the headerRow to determine which index is which variable
        foreach ($headerRow as $key=>$value) {
            // Create the needle that will be searched - this is the parameter we set in the query
            $needle = "[" . $value['key'] . "]";
            $index = $value['index'];
            // Check to see what type of parameter this is supposed to be so we handle the value correctly
            switch ($value['valueType']) {
                case "string":
                case "String":
                    // If this is a string value then we need to wrap it in double quotes
                    $replacement = "\"$dataRow[$index]\"";
                    break;
                case "numeric":
                case "Numeric":
                    // If this is a numeric value then we don't need to do anything special
                    $replacement = $dataRow[$index];
                    break;
                default:
                    // If nothing else treat it as a string
                    $replacement = "\"$dataRow[$index]\"";
                    break;
            }
            // Run the string replace using the needle and replacement created
            $parameterizedQuery = str_replace($needle, $replacement, $parameterizedQuery);
        }
        /*echo "<pre>";
      print_r($parameterizedQuery);
      echo "</pre>";*/

        // Return the query to the calling function
        return $parameterizedQuery;
    }

    private function _removeHeaderRows($parameterArray, $numOfHeaderRows, $uniqueDataOnly) {
        // Set up a new array to hold the cleaned data
        $dataOnly = array();

        // Get the count of the elements in the array -
        $arrayElementCount = (count($parameterArray) - 1);

        // Loop through the elements starting with 2 to get rid of the header rows
        for ($i = $numOfHeaderRows; $i < $arrayElementCount; $i++) {
            // Add the row to the new data only array
            $dataOnly[] = $parameterArray[$i];
        }
        if ($uniqueDataOnly) {
            // Return a unique value only array
            return $dataOnly;
        } elseif (! $uniqueDataOnly) {
            // Return the new data only array to the calling function
            return $dataOnly;
        }
    }

    private function _createRDFfromQuery($headerArray, $dataOnly) {

        // Set the output format to XML as we need XML to create RDF
        $this->outputFormat = '&output=xml';

        // Create the master XML string
        $resultRDF = new DOMDocument('1.0');

        // Initialize a counter variable
        $rowCounter = 0;

        // Loop through each of the rows of data provided
        foreach ($dataOnly as $row) {
            //debug($headerArray);
            // Replace parameters within query with values from data array
            $tempQuery = $this->_parameterizeQuery($headerArray, $this->sparqlQuery, $row);

            // Check to make sure that the class has appropriate variables already defined
            if (! isset($this->sparqlEndpoint) || ! isset($this->sparqlQuery)) {
                // We don't have things setup like they should be return a false
                return false;
            }

            // We need to create the SPARQL URL that will execute the query
            $this->curlURL = $this->_createFullURL($tempQuery);

            $this->_performSPARQLQuery();
            // Temporary variable to hold the RDF response from SPARQL
            $tempRDF = $this->rawResult;
            if ($rowCounter == 0) {
                // If this is the first time through (or first result) then we need to keep the entire document as it has important namespace information
                try {
                    // Attempt to run the processRDF and get results back
                    $returnedXML = $this->_processRDF($tempRDF, false);
                    if (is_string($returnedXML)) {
                        // If it returns a String then this is good so process away
                        $resultRDF->loadXML($returnedXML);
                        // Increment the rowCounter so we can keep track of how many times through
                        $rowCounter++;
                    }
                } catch (Exception $e) {
                    // Catch any exceptions that may come through - although we need to probably make this a little more robust
                    echo "Returned a null result from processRDF - probably didn't find a match - $e";
                    continue;
                }
            } elseif ($rowCounter >= 1) {
                // If this is not our first time throught (or first result) then we need only the child node of the results
                try {
                    // Attempt to run the processRDF and get results back
                    $returnedXML = $this->_processRDF($tempRDF, true);
                    if (is_object($returnedXML)) {
                        // If the processRDF returned an object - then we are good to continue processing - append this child to overall document
                        $resultRDF->documentElement->appendChild($returnedXML);
                        // Increment the rowCounter so we can keep track of how many times through
                        $rowCounter++;
                    }
                } catch (Exception $e) {
                    // Catch any exceptions that may come through - although we need to probably make this a little more robust
                    echo "Returned a null result from processRDF - probably didn't find a match - $e";
                    continue;
                }
            }
        }
        // Return the resultant DOMDocument as XML so we can save it to a file
        $this->rawResult = $resultRDF->saveXML();
        //debug($this->rawResult);
    }

}

?>