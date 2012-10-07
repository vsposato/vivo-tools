<?php $loggedUser = $this->Session->read('Auth.User');?>
<h2><?php echo __('List %s', __('Sparql Queries'));?></h2>

<table class="table">
	<tr>
		<th><?php echo $this->BootstrapPaginator->sort('name');?></th>
		<th><?php echo $this->BootstrapPaginator->sort('short_description');?></th>
		<th><?php echo $this->BootstrapPaginator->sort('created');?></th>
		<th><?php echo $this->BootstrapPaginator->sort('created_by');?></th>
		<th class="actions"><?php echo __('Actions');?></th>
	</tr>
<?php foreach ($sparqlQueries as $sparqlQuery): ?>
	<tr>
		<td><?php echo h($sparqlQuery['SparqlQuery']['name']); ?>&nbsp;</td>
		<td><?php echo h($sparqlQuery['SparqlQuery']['short_description']); ?>&nbsp;</td>
		<td><?php echo h($this->Time->niceShort($sparqlQuery['SparqlQuery']['created'])); ?>&nbsp;</td>
		<td>
			<?php echo $this->Html->link($sparqlQuery['QueryUserCreated']['full_name'], array('controller' => 'users', 'action' => 'view', $sparqlQuery['QueryUserCreated']['id'])); ?>
		</td>
		<td class="actions">
			<div class="btn-group">
				<?php
					echo $this->Html->link(__('View'), array('action' => 'view', $sparqlQuery['SparqlQuery']['id']), array('class' => 'btn btn-small'));
					//echo $this->BootstrapHtml->link(__('Execute'), '#executeSPARQLModal', array('class'=>'btn btn-small btn-success', 'data-toggle' => 'modal'));
					echo $this->BootstrapHtml->link(__('Execute'), array('action' => 'execute', $sparqlQuery['SparqlQuery']['id']), array('class' => 'btn btn-small btn-success'));
				?>
				<?php
					 if ( $sparqlQuery['QueryUserCreated']['id'] == AuthComponent::user('id') || $this->Session->read('FULL_ACCESS_GRANTED') == true ) {
						 echo $this->BootstrapHtml->link(__('Edit'), array('action' => 'edit', $sparqlQuery['SparqlQuery']['id']), array('class' => 'btn btn-small'));
						 echo $this->Form->postLink(__('Delete'), array('action' => 'delete', $sparqlQuery['SparqlQuery']['id']), array('class' => 'btn btn-small btn-danger'), __('Are you sure you want to delete # %s?', $sparqlQuery['SparqlQuery']['name']));
					 }
				?>
			</div>
		</td>
	</tr>
<?php endforeach; ?>
    <tr>
        <td colspan='5'>
            <?php
            echo $this->Html->link(__('My Queries Only'), array('controller'=>'sparql_queries', 'action' => 'index', $loggedUser['id']), array('class' => 'btn btn-medium'));
            ?>
        </td>
    </tr>
    <caption align="bottom"><?php echo $this->BootstrapPaginator->counter(array('format' => __('Page {:page} of {:pages}, showing {:current} records out of {:count} total, starting on record {:start}, ending on {:end}')));?></caption>
</table>

<?php echo $this->BootstrapPaginator->pagination(); ?>
