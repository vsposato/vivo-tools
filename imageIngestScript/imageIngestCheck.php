<?php
/**
 * Created by JetBrains PhpStorm.
 * User: vsposato
 * Date: 11/5/12
 * Time: 6:01 PM
 * To change this template use File | Settings | File Templates.
 */

/*
 * Define variables that will be needed throughout the process
 */
$csvFileForImage = '/home/vsposato/scripts/imageIngestScript/missingPhotos.csv';
$imageDirectory = '/data/vivo/uploads/images/harvestedImages/fullImages/';
$missingImages = array();
$csvMissingUsers = '/home/vsposato/scripts/imageIngestScript/missingUsers.csv';

$csvFile = fopen($csvFileForImage,"r");

if ($csvFile) {
    /*
     * CSV File was opened correctly
     */
    while( $data = fgetcsv($csvFile) ) {
        $tempFilePath = explode("/",$data[0]);
        $initialFileName = explode(".", $tempFilePath[5]);
        $completeFileName = $imageDirectory . substr($initialFileName[0], 4) . ".jpeg";
        if (! file_exists($completeFileName)) {
            $missingImages[] = substr($initialFileName[0], 4);
        }
    }
} elseif (! $csvFile) {
    echo "CSV File was not opened properly - failing out!";
    exit;
}

fclose($csvFile);

$csvFileForMissingPeople = fopen($csvMissingUsers,"w");
fputcsv($csvFileForMissingPeople,array("?Ufid"));
fputcsv($csvFileForMissingPeople,array("string"));
foreach ($missingImages as $missingImage) {
    fputcsv($csvFileForMissingPeople,array($missingImage));
}
fclose($csvFileForMissingPeople);
