<?php
    $loggedUser = $this->Session->read('Auth.User');
    $userFiles = $this->requestAction(array('controller' => 'users', 'action' => 'homePageFileOutput', $loggedUser['id']));
    // Setup configuration reader
    Configure::config('default', new PhpReader());
    // Now we need to load a configuration file for SPARQL
    Configure::load('sparql', 'default', false);
    // Load the base save directory into memory
    $baseDirectory = Configure::read('sparqlBaseDir');
?>
<div class="hero-unit">
    <h2>Welcome to the VIVO SPARQL Repository, <?php echo $loggedUser['first_name']; ?></h2>
    <p>This will house all of your team's SPARQL queries, and allow you to output them to usable formats.</p>
</div>
<div>

</div>

<div class="row-fluid">
    <?php
    ?>
    <table class="table table-hover">
        <caption align="top"><h3>Here are the files you have been working on:</h3></caption>
        <thead>
        <tr>
            <th>File Name</th>
            <th>File Size</th>
            <th>File Type</th>
            <th>File Modified</th>
            <th class="actions"><?php echo __('Actions');?></th>
        </tr>
        </thead>
        <tbody>
        <?php if (count($userFiles) > 0) {
            foreach ($userFiles as $userFile){
            $displayFile = new File($baseDirectory . $loggedUser['username'] . '/' . $userFile);
        ?><tr>
            <td><?php echo $userFile['fileName']; ?>&nbsp;</td>
            <td><?php echo $this->Number->toReadableSize($userFile['fileSize']); ?>&nbsp;</td>
            <td><?php echo $userFile['fileType']; ?>&nbsp;</td>
            <td><?php echo $this->Time->format('m-d-Y H:i:s',$userFile['fileModified']); ?>&nbsp;</td>
            <td class="actions">
                <div class="btn-group">
                    <?php
                    echo $this->Html->link(__('Download'), array('controller' => 'sparql_queries', 'action' => 'sendFileDownload', '?' => array('filename' => $userFile['fileName'], 'directory' => $userFile['fileDir'], 'extension' => $userFile['fileExt'])), array('class' => 'btn btn-mini'));
                    echo $this->Html->link(__('Delete'), array('action' => 'deleteUserFile', '?' => array('deleteUserFile' => $userFile['filePath'])), array('class' => 'btn btn-mini btn-danger'), __('Are you sure you want to delete  %s?', $userFile['fileName']));
                    ?>
                </div>
            </td>
        </tr>
            <?php
            }
        } else { ?>
            <tr>
                <td colspan='5'>You have no files currently in your directory! Go do some SPARQL!</td>
            </tr>
        <?php }

        ?>
        </tbody>
        <tfoot>
        </tfoot>
    </table>

</div>

