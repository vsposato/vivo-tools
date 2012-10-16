<?php
/**
 * Created by JetBrains PhpStorm.
 * User: vsposato
 * Date: 10/15/12
 * Time: 7:41 PM
 * To change this template use File | Settings | File Templates.
 */

//die(debug($returnedQuery));
// how many records should be displayed on a page?
$records_per_page = 10;

// include the pagination class
//require_once(APP . 'Vendor/ZebraPagination/Zebra_Pagination.php');

// instantiate the pagination object
//$pagination = new Zebra_Pagination();

// Lets shift the header row off of the top
$headerRow = array_shift($returnedQuery);

// the number of total records is the number of records in the array
/*$pagination->records(count($returnedQuery));

// records per page
$pagination->records_per_page($records_per_page);*/

// here's the magick: we need to display *only* the records for the current page
/*$returnedQuery = array_slice(
    $returnedQuery,
    (($pagination->get_page() - 1) * $records_per_page),
    $records_per_page
);*/

?>

<table class="table table-striped table-bordered table-condensed">
    <tr>
        <?php
            foreach ($headerRow as $columnHeading) {
                echo "<th>$columnHeading</th>";
            }
        ?>

    </tr>
    <?php foreach ($returnedQuery as $rowData) {?>
        <tr>
            <?php foreach ($rowData as $columnData) { ?>
                <td><?php echo $columnData; ?></td>
            <?php } ?>
        </tr>
    <?php } ?>

</table>

<?php

// render the pagination links
//$pagination->render();
