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

// Lets shift the header row off of the top
$headerRow = array_shift($returnedQuery);
?>
<div class="row-fluid">
    <div class="span12">
        <table class="table table-striped table-bordered table-condensed tablesorter" id="sortableTable">
            <thead>
            <tr>
                <?php
                foreach ($headerRow as $columnHeading) {
                    echo "<th>$columnHeading</th>";
                }
                ?>

            </tr>
            </thead>
            <tbody>
            <?php foreach ($returnedQuery as $rowData) {?>
            <tr>
                <?php foreach ($rowData as $columnData) { ?>
                <td><?php echo $columnData; ?></td>
                <?php } ?>
            </tr>
                <?php } ?>
            </tbody>

        </table>
    </div>
</div>
<div class="row-fluid">
    <div class="span12">
        <div id="pager">
            <form>
                <img src="<?php echo FULL_BASE_URL . '/' . IMAGES_URL . 'first.png';?>" class="first"/>
                <img src="<?php echo FULL_BASE_URL . '/' . IMAGES_URL . 'prev.png';?>" class="prev"/>
                <input type="text" class="pagedisplay"/>
                <img src="<?php echo FULL_BASE_URL . '/' . IMAGES_URL . 'next.png';?>" class="next"/>
                <img src="<?php echo FULL_BASE_URL . '/' . IMAGES_URL . 'last.png';?>" class="last"/>
                <select class="pagesize">
                    <option value="10">10</option>
                    <option selected="selected"  value="20">20</option>
                    <option value="30">30</option>
                    <option  value="40">40</option>
                    <option  value="50">50</option>
                    <option  value="60">60</option>
                    <option  value="70">70</option>
                    <option  value="80">80</option>
                    <option  value="90">90</option>
                    <option  value="100">100</option>
                </select>
            </form>

        </div>
    </div>
</div>
<script type="text/javascript">
    $(document).ready(function() {
        $("#sortableTable")
                .tablesorter()
                .tablesorterPager({
                    container: $("#pager"),
                    size: 20,
                    positionFixed: false
                });
        //$("table").tablesorter().tablesorterPager({container: $("#pager")});
    });
</script>
