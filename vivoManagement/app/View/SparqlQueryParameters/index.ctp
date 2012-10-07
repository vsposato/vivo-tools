<div class="row-fluid">
	<div class="span9">
		<h2><?php echo __('List %s', __('Sparql Query Parameters'));?></h2>

		<p>
			<?php echo $this->BootstrapPaginator->counter(array('format' => __('Page {:page} of {:pages}, showing {:current} records out of {:count} total, starting on record {:start}, ending on {:end}')));?>
		</p>

		<table class="table">
			<tr>
				<th><?php echo $this->BootstrapPaginator->sort('id');?></th>
				<th><?php echo $this->BootstrapPaginator->sort('sparql_query_id');?></th>
				<th><?php echo $this->BootstrapPaginator->sort('parameter');?></th>
				<th><?php echo $this->BootstrapPaginator->sort('parameter_type');?></th>
				<th><?php echo $this->BootstrapPaginator->sort('created');?></th>
				<th><?php echo $this->BootstrapPaginator->sort('created_by');?></th>
				<th class="actions"><?php echo __('Actions');?></th>
			</tr>
		<?php foreach ($sparqlQueryParameters as $sparqlQueryParameter): ?>
			<tr>
				<td><?php echo h($sparqlQueryParameter['SparqlQueryParameter']['id']); ?>&nbsp;</td>
				<td>
					<?php echo $this->Html->link($sparqlQueryParameter['SparqlQuery']['name'], array('controller' => 'sparql_queries', 'action' => 'view', $sparqlQueryParameter['SparqlQuery']['id'])); ?>
				</td>
				<td><?php echo h($sparqlQueryParameter['SparqlQueryParameter']['parameter']); ?>&nbsp;</td>
				<td><?php echo h($sparqlQueryParameter['SparqlQueryParameter']['parameter_type']); ?>&nbsp;</td>
				<td><?php echo h($sparqlQueryParameter['SparqlQueryParameter']['created']); ?>&nbsp;</td>
				<td>
					<?php echo $this->Html->link($sparqlQueryParameter['CreatedBy']['full_name'], array('controller' => 'users', 'action' => 'view', $sparqlQueryParameter['CreatedBy']['id'])); ?>
				</td>
				<td class="actions">
					<?php echo $this->Html->link(__('View'), array('action' => 'view', $sparqlQueryParameter['SparqlQueryParameter']['id'])); ?>
					<?php echo $this->Html->link(__('Edit'), array('action' => 'edit', $sparqlQueryParameter['SparqlQueryParameter']['id'])); ?>
					<?php echo $this->Form->postLink(__('Delete'), array('action' => 'delete', $sparqlQueryParameter['SparqlQueryParameter']['id']), null, __('Are you sure you want to delete # %s?', $sparqlQueryParameter['SparqlQueryParameter']['id'])); ?>
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
			<li><?php echo $this->Html->link(__('New %s', __('Sparql Query Parameter')), array('action' => 'add')); ?></li>
			<li><?php echo $this->Html->link(__('List %s', __('Sparql Queries')), array('controller' => 'sparql_queries', 'action' => 'index')); ?> </li>
			<li><?php echo $this->Html->link(__('New %s', __('Sparql Query')), array('controller' => 'sparql_queries', 'action' => 'add')); ?> </li>
			<li><?php echo $this->Html->link(__('List %s', __('Users')), array('controller' => 'users', 'action' => 'index')); ?> </li>
			<li><?php echo $this->Html->link(__('New %s', __('Created By')), array('controller' => 'users', 'action' => 'add')); ?> </li>
		</ul>
		</div>
	</div>
</div>