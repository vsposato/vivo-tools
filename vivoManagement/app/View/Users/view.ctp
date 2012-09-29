<h2><?php  echo __('User - %s', $user['User']['full_name']);?></h2>
<div class="row-fluid">
    <div class="span4">
        <dl>
            <dt><?php echo __('Last Name'); ?></dt>
            <dd>
                <?php echo h($user['User']['last_name']); ?>
                &nbsp;
            </dd>
            <dt><?php echo __('Username'); ?></dt>
            <dd>
                <?php echo h($user['User']['username']); ?>
                &nbsp;
            </dd>
            <dt><?php echo __('Member Since'); ?></dt>
            <dd>
                <?php echo $this->Time->nice(h($user['User']['created'])); ?>
                &nbsp;
            </dd>
        </dl>
    </div>
    <div class="offset1 span4">
        <dl>
            <dt><?php echo __('First Name'); ?></dt>
            <dd>
                <?php echo h($user['User']['first_name']); ?>
                &nbsp;
            </dd>
            <dt><?php echo __('Email Address'); ?></dt>
            <dd>
                <?php echo h($user['User']['email_address']); ?>
                &nbsp;
            </dd>
        </dl>
    </div>
</div>
<div class="row-fluid">
    <?php
    ?>
    <table class="table table-hover table-condensed">
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
        <?php foreach ($userFiles as $userFile){
            $displayFile = new File($baseDirectory . $user['User']['username'] . '/' . $userFile);
            //debug($displayFile->info());
            //debug($displayFile); ?>
        <tr>
            <td><?php echo $userFile['fileName']; ?>&nbsp;</td>
            <td><?php echo $this->Number->toReadableSize($userFile['fileSize']); ?>&nbsp;</td>
            <td><?php echo $userFile['fileType']; ?>&nbsp;</td>
            <td><?php echo $this->Time->format('m-d-Y H:i:s',$userFile['fileModified']); ?>&nbsp;</td>
            <td class="actions">
                <div class="btn-group">
                    <?php
                        echo $this->Html->link(__('Download'), array('controller' => 'sparql_queries', 'action' => 'sendFileDownload', '?' => array('filename' => $userFile['fileName'], 'directory' => $userFile['fileDir'], 'extension' => $userFile['fileExt'])), array('class' => 'btn btn-mini'));
                        echo $this->Form->postLink(__('Delete'), array('action' => 'deleteUserFile', 'deleteUserFile' => $userFile['filePath']), array('class' => 'btn btn-mini btn-danger'), __('Are you sure you want to delete  %s?', $userFile['fileName']));
                    ?>
                </div>
            </td>
        </tr>
        <?php } ?>
    </tbody>
    <tfoot>
    <tr>
        <td colspan="2"></td>
        <td colspan="2">
            <div class="btn-group">
                <?php
                echo $this->Html->link(__('Add New User'), array('action' => 'add'), array('class' => 'btn btn-medium btn-primary'));
                ?>
            </div>
        </td>
    </tr>
    </tfoot>
    </table>

</div>

