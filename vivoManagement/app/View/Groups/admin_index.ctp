<h2><?php echo __('List %s', __('Groups'));?></h2>
<table class="table table-hover table-condensed">
    <tr>
        <th><?php echo $this->BootstrapPaginator->sort('group_name');?></th>
        <th><?php echo $this->BootstrapPaginator->sort('created');?></th>
        <th><?php echo $this->BootstrapPaginator->sort('modified');?></th>
        <th><?php echo $this->BootstrapPaginator->sort('created_by');?></th>
        <th class="actions"><?php echo __('Actions');?></th>
    </tr>
<?php foreach ($groups as $group): ?>
    <tr>
        <td><?php echo h($group['Group']['group_name']); ?>&nbsp;</td>
        <td><?php echo h($this->Time->niceShort($group['Group']['created'])); ?>&nbsp;</td>
        <td><?php echo h($this->Time->niceShort($group['Group']['modified'])); ?>&nbsp;</td>
        <td>
            <?php echo $this->Html->link($group['GroupCreatedBy']['full_name'], array('controller' => 'users', 'action' => 'view', $group['GroupCreatedBy']['id'])); ?>
        </td>
        <td class="actions">
            <div class="btn-group">
                <?php echo $this->Html->link(__('View'), array('action' => 'view', $group['Group']['id']), array('class' => 'btn btn-mini')); ?>
                <?php echo $this->Html->link(__('Edit'), array('action' => 'edit', $group['Group']['id']), array('class' => 'btn btn-mini')); ?>
                <?php echo $this->Form->postLink(__('Delete'), array('action' => 'delete', $group['Group']['id']), array('class' => 'btn btn-mini btn-danger'), __('Are you sure you want to delete # %s?', $group['Group']['id'])); ?>
            </div>
        </td>
    </tr>
<?php endforeach; ?>
    <caption align="bottom"><?php echo $this->BootstrapPaginator->counter(array('format' => __('Page {:page} of {:pages}, showing {:current} records out of {:count} total, starting on record {:start}, ending on {:end}')));?></caption>
</table>

<?php echo $this->BootstrapPaginator->pagination(); ?>
