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
        $userDirectory = new Folder($baseDirectory . $user['User']['username']);
        $userFiles = $userDirectory->find('.*', true);
    ?>
<!--    <table class="table table-hover table-condensed">
    <thead>
    <tr>
        <th>File Name</th>
        <th><?php echo $this->BootstrapPaginator->sort('username');?></th>
        <th><?php echo $this->BootstrapPaginator->sort('email_address');?></th>
        <th class="actions"><?php echo __('Actions');?></th>
    </tr>
    </thead>
    <tbody>-->
    <?php foreach ($userFiles as $userFile): ?>
    <?php
        $displayFile = new File($baseDirectory . $user['User']['username'] . '/' . $userFile);
        debug($displayFile); ?>
   <!-- <tr>
        <td><?php echo h($user['User']['full_name']); ?>&nbsp;</td>
        <td><?php echo h($user['User']['username']); ?>&nbsp;</td>
        <td><?php echo h($user['User']['email_address']); ?>&nbsp;</td>
        <td class="actions">
            <div class="btn-group">
                <?php
                echo $this->Html->link(__('View'), array('action' => 'view', $user['User']['id']), array('class' => 'btn btn-mini'));
                echo $this->Html->link(__('Edit'), array('action' => 'edit', $user['User']['id']), array('class' => 'btn btn-mini'));
                echo $this->Form->postLink(__('Delete'), array('action' => 'delete', $user['User']['id']), array('class' => 'btn btn-mini'), __('Are you sure you want to delete  %s?', $user['User']['username']));
                ?>
            </div>
        </td>
    </tr> -->
        <?php endforeach; ?>
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

    ?>
</div>

