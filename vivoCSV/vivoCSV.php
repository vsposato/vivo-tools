#!/usr/bin/php

<?php

// Retrieve command line options from user
$commandOptions = getopt("u:s:o:");

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
// TODO - We currently only support JSON - may consider other options
$outputFormat = '&output=json';

// $sparqlFileName - this is the name of the file that holds the SPARQL query
$sparqlFileName = '';
$sparqlFileName = $commandOptions['s'];

// Retrieve the query from the provided SPARQL filename
$query = readSparqlFile($sparqlFileName);

// Retrieve the current microtime so that we can calulate length of time it took
$startTime = microtime(true);

// URL encode the query so that we can pass it as part of the URL
$query = urlencode($query);

// Build the full URL from the pieces received from the user
$fullURL = $baseURL . $query . $outputFormat;

// Iniitialize CURL for communication with SPARQL endpoint
$curlInit = curl_init();

// Set options for CURL
// Set the URL that CURL will talk with to the $fullURL built earlier
curl_setopt($curlInit, CURLOPT_URL, $fullURL);
// Set CURL to 'return' the value to the variable so that it can be processed
curl_setopt($curlInit, CURLOPT_RETURNTRANSFER, true);
// Execute the CURL and pass response back to $curlReturn
$curlReturn = curl_exec($curlInit);
// Convert the JSON received back to a standard PHP array
$sparqlArray = json_decode($curlReturn, true);

/*
echo "<pre>";
print_r($sparqlArray);
echo "</pre>";
*/

// Initialize a blank array for the final results to be placed in
$outputArray = array();
// Set the header row to appropriate column headers
$outputArray[] = csvHeaderRowBuilder($sparqlArray['head']['vars']);

// Parse each row of data to be turned into a row in the CSV
foreach ($sparqlArray['results']['bindings'] as $row) {
	// Append the next row in a well-formatted CSV file
	$outputArray[] = csvDataRowBuilder($sparqlArray['head']['vars'], $row);
}

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

// Function to build individual data rows based upon the headers provided
function csvDataRowBuilder($headerRow, $dataLine) {
	
	// Create fresh array to return to calling function
	$dataRow = array();

	// We have to take the headerRow and determine if the results data has all the appropriate fields
	foreach ( $headerRow as $key=>$value ) {
		// Check that a key from the header row exists in the data
		if (array_key_exists($value, $dataLine)) {
			// If it does exist - then output the value to an indexed array
			if ($dataLine[$value]['type'] == 'literal') {
				$dataRow[] = $dataLine[$value]['value'];
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
function csvHeaderRowBuilder($headerRow) {
	
	// Create fresh array to return to calling function
	$headerReturnRow = array();
	// We have to take the headerRow and determine if the results data has all the appropriate fields
	foreach ( $headerRow as $key=>$value ) {
		$headerReturnRow[] = $value;
	}
	// Output the well formed header row back to the calling function
	return $headerReturnRow;
}
function printUsage() {
	// This function is only called if the user attempts to execute without correct parameters
	echo "\n";
	echo "vivoCSV tool usage: \n";
	echo "-u The URL to your SPARQL endpoint to include the query designtation - ie 'http://sparql.vivo.ufl.edu:3030/VIVO/query?query=' \n";
	echo "-s The path to the file containing your SPARQL query \n";
	echo "-o The path to the file you want to output to \n";
	echo "\n";
}
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

?>