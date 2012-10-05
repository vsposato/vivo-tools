<h2><?php echo __('%s Listing', __('User'));?></h2>
<table class="table table-hover table-condensed">
    <thead>
        <tr>
            <th><?php echo $this->BootstrapPaginator->sort('full_name');?></th>
            <th><?php echo $this->BootstrapPaginator->sort('username');?></th>
            <th><?php echo $this->BootstrapPaginator->sort('email_address');?></th>
            <th class="actions"><?php echo __('Actions');?></th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($users as $user): ?>
            <tr>
                <td><?php echo h($user['User']['full_name']); ?>&nbsp;</td>
                <td><?php echo h($user['User']['username']); ?>&nbsp;</td>
                <td><?php echo h($user['User']['email_address']); ?>&nbsp;</td>
                <td class="actions">
                    <div class="btn-group">
                        <?php
                            echo $this->Html->link(__('View'), array('action' => 'view', $user['User']['id']), array('class' => 'btn btn-mini'));
                            echo $this->Html->link(__('Edit'), array('action' => 'edit', $user['User']['id']), array('class' => 'btn btn-mini'));
                            echo $this->Form->postLink(__('Delete'), array('action' => 'delete', $user['User']['id']), array('class' => 'btn btn-mini btn-danger'), __('Are you sure you want to delete  %s?', $user['User']['username']));
                        ?>
                    </div>
                </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
    <caption align="bottom"><?php echo $this->BootstrapPaginator->counter(array('format' => __('Page {:page} of {:pages}, showing {:current} records out of {:count} total, starting on record {:start}, ending on {:end}')));?></caption>
</table>

<?php echo $this->BootstrapPaginator->pagination(); ?>
