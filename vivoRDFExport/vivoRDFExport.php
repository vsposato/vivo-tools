#!/usr/bin/php

<?php

// Retrieve command line options from user
$commandOptions = getopt("u:s:o:p:e");

// If the user didn't send exactly 3 values show usage and exit
if (count($commandOptions) < 4) {
	printUsage();
	exit;
}

// Initialize variables that will be used throughout the program

// $outputFileName - the name of the CSV file that will be generated
$outputFileName = '';
$outputFileName = $commandOptions['o'];

// $baseURL - this is the URL of the SPARQL endpoint including query parameter string
$baseURL = '';
$baseURL = $commandOptions['u'];

// $uniqueDataOnly - this is to determine whether we clean duplicates out of the parameter array or not
// Since it doesn't take a value - we just need to make sure if the 'e' key isset
$uniqueDataOnly = false;
$uniqueDataOnly = isset($commandOptions['e']) ? true : false;

// $outputFormat - this is the output format that we will be receiving from the SPARQL endpoint
// TODO - We currently only support XML - may consider other options
$outputFormat = '&output=xml';

// $baseURL - this is the URL of the SPARQL endpoint including query parameter string
$parameterFile = '';
$parameterFile = $commandOptions['p'];

// $sparqlFileName - this is the name of the file that holds the SPARQL query
$sparqlFileName = '';
$sparqlFileName = $commandOptions['s'];

// Retrieve the query from the provided SPARQL filename
$query = readSparqlFile($sparqlFileName);

// Parse the parameter file and make an array that will hold the parameters
$parameterArray = readParameterFile($parameterFile);

// Create a parameter data only array to hold the replacement values to be used
$dataArray = removeHeaderRows($parameterArray, 2, $uniqueDataOnly);

// Create a header array that will contain the variable, data type, and index
$headerArray = createHeaderArray($parameterArray[0], $parameterArray[1]);

// Return the results of the queries to a string to be output to a file
$xmlOutput = createRDFfromQuery($headerArray, $dataArray, $query, $baseURL, $outputFormat);

// Retrieve the current microtime so that we can calulate length of time it took
$startTime = microtime_float();

// Determine if the file exists
if (file_exists($outputFileName)) {
	// If it does exist - attempt to rename it by adding the start timestamp to the end of the filename
	$newFile = $outputFileName . $startTime;
	if (rename($outputFileName, $newFile)) {
		// If the rename was successful - open the file
		$fileHandle = fopen($outputFileName,'x');
	} else {
		// If the rename failed - error out
		echo "Error - output file exists and it can't be renamed - {$outputFileName}! \n";
	}
} elseif (! file_exists($outputFileName)) {
	// If the file doesn't already exist - create it and open it for writing
	$fileHandle = fopen($outputFileName,'x');
}

// Determine if the file was opened correctly
if ($fileHandle) {
	// If the file was opened properly, then output the xml
	fputs($fileHandle, $xmlOutput);
	// Close the file handle
	fclose($fileHandle);
} else {
	// If the file was not opened properly - exit out
	echo "ERROR - Your file could not be opened! \n";
}

// Capture the current time in Unix timestamp
$endTime = microtime_float();
// Calculate number of seconds for execution and output it
$timeToComplete = $endTime - $startTime;
echo "It took " . $timeToComplete . " seconds to complete this operation! \n";

/**
 * printUsage function.
 *
 * @access public
 * @return void
 */
function printUsage() {
	// This function is only called if the user attempts to execute without correct parameters
	echo "\n";
	echo "vivoRDFExport tool usage: \n";
	echo "-u The URL to your SPARQL endpoint to include the query designtation - ie 'http://sparql.vivo.ufl.edu:3030/VIVO/query?query=' \n";
	echo "-s The path to the file containing your SPARQL query \n";
	echo "-o The path to the file you want to output to \n";
	echo "-p The path to the parameter CSV file you want to use to fill out your query \n";
	echo "-e A boolean as to whether you want to remove duplicate data elements from the parameter file input \n";
	echo "\n";
}

/**
 * readSparqlFile function.
 *
 * @access public
 * @param mixed $sparqlFile
 * @return void
 */
function readSparqlFile($sparqlFile) {

	// First check to see that the file exists
	if (! file_exists($sparqlFile)) {
		// If it doesn't exist then error out
		echo "SPARQL query file does not exists - $sparqlFile";
		return false;
	}
	try {
		// Open the SPARQL query file
		$sparqlFileHandle = fopen($sparqlFile, 'r');

		// Initialize a blanke SPARQL query string
		$sparqlQuery = '';

		while (! feof($sparqlFileHandle) ) {
			// Get a line of data from the file and append it it to the SPARQL query string
			$sparqlQuery .= fgets($sparqlFileHandle);
		}
		// Return the SPARQL query back to the calling function
		return $sparqlQuery;
	} catch (Exception $e) {
		// Something happened and we couldn't complete the SPARQL query string so display the exception and exit
		echo "Exception in readSparqlFile function - $e";
		print_r($e);
		exit;
	}
}

/**
 * readParameterFile function.
 *
 * @access public
 * @param mixed $parameterFile
 * @return void
 */
function readParameterFile($parameterFile) {

	// First check to see if the file exists
	if (! file_exists($parameterFile)) {
		// If it doesn't exist then error out
		echo "Parameter file does not exists - $$parameterFile";
		return false;
	}
	try {
		// Open the Parameter file
		$parameterFileHandle = fopen($parameterFile, 'r');

		// Initialize a blanke SPARQL query string
		$parameterArray = array();

		while (! feof($parameterFileHandle) ) {
			// Get a row of data from the CSV parameter file and add it to the numeric indexed array
			$parameterArray[] = fgetcsv($parameterFileHandle);
		}
		// Return the SPARQL query back to the calling function
		return $parameterArray;

	} catch (Exception $e) {
		// Something happened and we couldn't complete the read of the parameter CSV file
		echo "Exception in readParameterFile function - $e";
		print_r($e);
		exit;
	}
}

/**
 * createRDFfromQuery function.
 *
 * @access public
 * @param array $parameterArray
 * @param string $query
 * @param string $baseURL
 * @param string $outputFormat
 * @return string
 */
function createRDFfromQuery($headerArray, $dataOnly, $query, $baseURL, $outputFormat) {

	// Create the master XML string
	$resultRDF = new DOMDocument('1.0');

	// Initialize a counter variable
	$rowCounter = 0;

	// Loop through each of the rows of data provided
	foreach ($dataOnly as $row) {
		// Replace parameters within query with values from data array
		$tempQuery = parameterizeQuery($headerArray, $query, $row);
		// Temporary variable to hold the RDF response from SPARQL
		$tempRDF = performSPARQLQuery(createFullURL($baseURL, $tempQuery, $outputFormat));
		if ($rowCounter == 0) {
			// If this is the first time through (or first result) then we need to keep the entire document as it has important namespace information
			try {
				// Attempt to run the processRDF and get results back
				$returnedXML = processRDF($tempRDF, false);
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
				$returnedXML = processRDF($tempRDF, true);
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
	return $resultRDF->saveXML();
}


/**
 * processRDF function.
 *
 * @access public
 * @param xml $inputRDF
 * @param boolean $stripHeaders
 * @return DOMNode or string
 */
function processRDF($inputRDF, $stripHeaders) {
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
/**
 * createFullURL function.
 *
 * @access public
 * @param string $baseURL
 * @param string $query
 * @param string $outputFormat
 * @return string URL
 */
function createFullURL($baseURL, $query, $outputFormat) {

	// URL encode the query so that we can pass it as part of the URL
	$query = urlencode($query);
	// Return the full url to the calling function
	return ($baseURL . $query . $outputFormat);
}

/**
 * performSPARQLQuery function.
 *
 * @access public
 * @param string $fullURL
 * @param string $query
 * @return xml $curlReturn
 */
function performSPARQLQuery($fullURL) {
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
	curl_setopt($curlInit, CURLOPT_URL, $fullURL);
	// Set CURL to 'return' the value to the variable so that it can be processed
	curl_setopt($curlInit, CURLOPT_RETURNTRANSFER, true);
	// Execute the CURL and pass response back to $curlReturn
	$curlReturn = curl_exec($curlInit);

	// Close out the CURL
	curl_close($curlInit);

/*	echo "<pre>";
	print_r($$curlReturn);
	echo "</pre>"; */

	return $curlReturn;

}

/**
 * createHeaderArray function.
 *
 * @access public
 * @param array $headerRow
 * @param array $typeRow
 * @return array $headerArray
 */
function createHeaderArray($headerRow, $typeRow) {

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
/**
 * parameterizeQuery function.
 *
 * @access public
 * @param array $headerRow
 * @param string $query
 * @param array $dataRow
 * @return string $parameterizedQuery
 */
function parameterizeQuery($headerRow, $query, $dataRow) {

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
				// If this is a string value then we need to wrap it in double quotes
				$replacement = "\"$dataRow[$index]\"";
				break;
			case "numeric":
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

/**
 * removeHeaderRows function.
 *
 * @access public
 * @param array $parameterArray
 * @param int $numOfHeaderRows
 * @return array $dataOnly
 */
function removeHeaderRows($parameterArray, $numOfHeaderRows, $uniqueDataOnly) {
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

/**
 * removeDuplicatesFromDataParameters function.
 *
 * @access public
 * @param array $dataArray
 * @return array
 */
function removeDuplicatesFromDataParameters($dataArray) {
	// Clean out duplicate values from our array
	//return array_unique($dataArray, SORT_STRING);
}
function microtime_float(){
	list($usec, $sec) = explode(" ", microtime());
	return ((float)$usec + (float)$sec);
}
?>