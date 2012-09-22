<h2><?php echo __('List %s', __('Sparql Queries'));?></h2>

<p>
	<?php echo $this->BootstrapPaginator->counter(array('format' => __('Page {:page} of {:pages}, showing {:current} records out of {:count} total, starting on record {:start}, ending on {:end}')));?>
</p>

<table class="table">
	<tr>
		<th><?php echo $this->BootstrapPaginator->sort('id');?></th>
		<th><?php echo $this->BootstrapPaginator->sort('name');?></th>
		<th><?php echo $this->BootstrapPaginator->sort('short_description');?></th>
		<th><?php echo $this->BootstrapPaginator->sort('created');?></th>
		<th><?php echo $this->BootstrapPaginator->sort('created_by');?></th>
		<th class="actions"><?php echo __('Actions');?></th>
	</tr>
<?php foreach ($sparqlQueries as $sparqlQuery): ?>
	<tr>
		<td><?php echo h($sparqlQuery['SparqlQuery']['id']); ?>&nbsp;</td>
		<td><?php echo h($sparqlQuery['SparqlQuery']['name']); ?>&nbsp;</td>
		<td><?php echo h($sparqlQuery['SparqlQuery']['short_description']); ?>&nbsp;</td>
		<td><?php echo h($this->Time->niceShort($sparqlQuery['SparqlQuery']['created'])); ?>&nbsp;</td>
		<td>
			<?php echo $this->Html->link($sparqlQuery['QueryUserCreated']['full_name'], array('controller' => 'users', 'action' => 'view', $sparqlQuery['QueryUserCreated']['id'])); ?>
		</td>
		<td class="actions">
			<div class="btn-group">
				<?php echo $this->Html->link(__('View'), array('action' => 'view', $sparqlQuery['SparqlQuery']['id']), array('class' => 'btn btn-small')); ?>
				<?php
					 if ( $sparqlQuery['QueryUserCreated']['id'] == AuthComponent::user('id') || $this->Session->read('FULL_ACCESS_GRANTED') == true ) {
						 echo $this->Html->link(__('Edit'), array('action' => 'edit', $sparqlQuery['SparqlQuery']['id']), array('class' => 'btn btn-small'));
						 echo $this->Form->postLink(__('Delete'), array('action' => 'delete', $sparqlQuery['SparqlQuery']['id']), array('class' => 'btn btn-small btn-danger'), __('Are you sure you want to delete # %s?', $sparqlQuery['SparqlQuery']['id']));
					 }
				?>
			</div>
		</td>
	</tr>
<?php endforeach; ?>
</table>

<?php echo $this->BootstrapPaginator->pagination(); ?>
