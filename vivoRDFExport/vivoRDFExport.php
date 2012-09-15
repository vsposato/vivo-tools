#!/usr/bin/php

<?php
/*
// Retrieve command line options from user
$commandOptions = getopt("u:s:o:p:");

// If the user didn't send exactly 3 values show usage and exit
if (count($commandOptions) != 3) {
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

// $outputFormat - this is the output format that we will be receiving from the SPARQL endpoint
// TODO - We currently only support XML - may consider other options
$outputFormat = '&output=xml';

// $baseURL - this is the URL of the SPARQL endpoint including query parameter string
$parameterFile = '';
$parameterFile = $commandOptions['p'];

// $sparqlFileName - this is the name of the file that holds the SPARQL query
$sparqlFileName = '';
$sparqlFileName = $commandOptions['s']; */

$sparqlFileName = '/Users/vsposato/personRDF.sparql';

// Retrieve the query from the provided SPARQL filename
$query = readSparqlFile($sparqlFileName);

$outputFileName = '/Users/vsposato/testRDF.xml';
$outputFormat = '&output=xml';
$baseURL = 'http://sparql.vivo.ufl.edu:3030/VIVO/query?query=';
$parameterFile = '/Users/vsposato/testParameter.csv';
$parameterArray = readParameterFile($parameterFile);
$dataArray = removeHeaderRows($parameterArray, 2);
$headerArray = createHeaderArray($parameterArray[0], $parameterArray[1]);

createRDFfromQuery($headerArray, $dataArray, $query, $baseURL, $outputFormat);

// Retrieve the current microtime so that we can calulate length of time it took
$startTime = microtime(true);

/*
echo "<pre>";
print_r($outputArray);
echo "</pre>";
*/

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
	// If the file was opened properly, then output the data row by row
	foreach ($outputArray as $row) {
		fputcsv($fileHandle, $row);
	}
	// Close the file handle
	fclose($fileHandle);
} else {
	// If the file was not opened properly - exit out
	echo "ERROR - Your file could not be opened! \n";
}

// Capture the current time in Unix timestamp
$endTime = microtime(true);
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
 * @param mixed $parameterArray
 * @param mixed $query
 * @param mixed $baseURL
 * @param mixed $outputFormat
 * @return void
 */
function createRDFfromQuery($headerArray, $dataOnly, $query, $baseURL, $outputFormat) {

	// Create the master XML string
	$resultRDF = '';
	
	// Initialize a counter variable
	$rowCounter = 0;
	
	foreach ($dataOnly as $row) {
		$tempQuery = parameterizeQuery($headerArray, $query, $row);
		$tempRDF = performSPARQLQuery(createFullURL($baseURL, $tempQuery, $outputFormat));
		if ($rowCounter == 0) {
			$resultRDF .= processRDF($tempRDF, false);
		} elseif ($rowCounter >= 1) {
			$resultRDF .= processRDF($tempRDF, true);
		}
		/*echo "<pre>";
		print_r($tempQuery);
		echo "</pre>";
		echo "<pre>";
		print_r($tempRDF);
		echo "</pre>";*/
				
	}
	exit;
}


function processRDF($inputRDF, $stripHeaders) {
/*	$rdfResult = new XMLReader();
	$rdfResult->XML($inputRDF, "UTF-8");
	$rdfResult->read(); */

	$rdfResult = simplexml_load_string($inputRDF);
	foreach ($rdfResult->children() as $child) {
		var_dump($child);
	}
}

/**
 * createFullURL function.
 *
 * @access public
 * @param mixed $baseURL
 * @param mixed $query
 * @param mixed $outputFormat
 * @return void
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
/*	echo "<pre>";
	print_r($parameterizedQuery);
	echo "</pre>"; */

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
function removeHeaderRows($parameterArray, $numOfHeaderRows) {
	// Set up a new array to hold the cleaned data
	$dataOnly = array();

	// Get the count of the elements in the array - 
	$arrayElementCount = (count($parameterArray) - 1);

	// Loop through the elements starting with 2 to get rid of the header rows
	for ($i = $numOfHeaderRows; $i <= $arrayElementCount; $i++) {
		// Add the row to the new data only array
		$dataOnly[] = $parameterArray[$i];
	}
	
	// Return the new data only array to the calling function
	return $dataOnly;
}
?>