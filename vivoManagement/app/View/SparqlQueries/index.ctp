<div class="row-fluid">
	<div class="span9">
		<h2><?php echo __('List %s', __('Sparql Queries'));?></h2>

		<p>
			<?php echo $this->BootstrapPaginator->counter(array('format' => __('Page {:page} of {:pages}, showing {:current} records out of {:count} total, starting on record {:start}, ending on {:end}')));?>
		</p>

		<table class="table">
			<tr>
				<th><?php echo $this->BootstrapPaginator->sort('id');?></th>
				<th><?php echo $this->BootstrapPaginator->sort('short_description');?></th>
				<th><?php echo $this->BootstrapPaginator->sort('long_description');?></th>
				<th><?php echo $this->BootstrapPaginator->sort('sparql_query');?></th>
				<th><?php echo $this->BootstrapPaginator->sort('created');?></th>
				<th><?php echo $this->BootstrapPaginator->sort('created_by');?></th>
				<th><?php echo $this->BootstrapPaginator->sort('modified');?></th>
				<th><?php echo $this->BootstrapPaginator->sort('modified_by');?></th>
				<th class="actions"><?php echo __('Actions');?></th>
			</tr>
		<?php foreach ($sparqlQueries as $sparqlQuery): ?>
			<tr>
				<td><?php echo h($sparqlQuery['SparqlQuery']['id']); ?>&nbsp;</td>
				<td><?php echo h($sparqlQuery['SparqlQuery']['short_description']); ?>&nbsp;</td>
				<td><?php echo h($sparqlQuery['SparqlQuery']['long_description']); ?>&nbsp;</td>
				<td><?php echo h($sparqlQuery['SparqlQuery']['sparql_query']); ?>&nbsp;</td>
				<td><?php echo h($sparqlQuery['SparqlQuery']['created']); ?>&nbsp;</td>
				<td>
					<?php echo $this->Html->link($sparqlQuery['QueryUserCreated']['full_name'], array('controller' => 'users', 'action' => 'view', $sparqlQuery['QueryUserCreated']['id'])); ?>
				</td>
				<td><?php echo h($sparqlQuery['SparqlQuery']['modified']); ?>&nbsp;</td>
				<td>
					<?php echo $this->Html->link($sparqlQuery['QueryUserModified']['full_name'], array('controller' => 'users', 'action' => 'view', $sparqlQuery['QueryUserModified']['id'])); ?>
				</td>
				<td class="actions">
					<?php echo $this->Html->link(__('View'), array('action' => 'view', $sparqlQuery['SparqlQuery']['id'])); ?>
					<?php echo $this->Html->link(__('Edit'), array('action' => 'edit', $sparqlQuery['SparqlQuery']['id'])); ?>
					<?php echo $this->Form->postLink(__('Delete'), array('action' => 'delete', $sparqlQuery['SparqlQuery']['id']), null, __('Are you sure you want to delete # %s?', $sparqlQuery['SparqlQuery']['id'])); ?>
				</td>
			</tr>
		<?php endforeach; ?>
		</table>

		<?php echo $this->BootstrapPaginator->pagination(); ?>
	</div>
	<div class="span3">
		<div class="well" style="padding: 8px 0; margin-top:8px;">
		<ul class="nav nav-list">
			<li class="nav-header"><?php echo __('Actions'); ?></li>
			<li><?php echo $this->Html->link(__('New %s', __('Sparql Query')), array('action' => 'add')); ?></li>
			<li><?php echo $this->Html->link(__('List %s', __('Users')), array('controller' => 'users', 'action' => 'index')); ?> </li>
			<li><?php echo $this->Html->link(__('New %s', __('Query User Created')), array('controller' => 'users', 'action' => 'add')); ?> </li>
		</ul>
		</div>
	</div>
</div>