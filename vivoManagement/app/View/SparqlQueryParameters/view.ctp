<div class="row-fluid">
	<div class="span9">
		<h2><?php  echo __('Sparql Query Parameter');?></h2>
		<dl>
			<dt><?php echo __('Id'); ?></dt>
			<dd>
				<?php echo h($sparqlQueryParameter['SparqlQueryParameter']['id']); ?>
				&nbsp;
			</dd>
			<dt><?php echo __('Sparql Query'); ?></dt>
			<dd>
				<?php echo $this->Html->link($sparqlQueryParameter['SparqlQuery']['name'], array('controller' => 'sparql_queries', 'action' => 'view', $sparqlQueryParameter['SparqlQuery']['id'])); ?>
				&nbsp;
			</dd>
			<dt><?php echo __('Parameter'); ?></dt>
			<dd>
				<?php echo h($sparqlQueryParameter['SparqlQueryParameter']['parameter']); ?>
				&nbsp;
			</dd>
			<dt><?php echo __('Parameter Type'); ?></dt>
			<dd>
				<?php echo h($sparqlQueryParameter['SparqlQueryParameter']['parameter_type']); ?>
				&nbsp;
			</dd>
			<dt><?php echo __('Created'); ?></dt>
			<dd>
				<?php echo h($sparqlQueryParameter['SparqlQueryParameter']['created']); ?>
				&nbsp;
			</dd>
			<dt><?php echo __('Created By'); ?></dt>
			<dd>
				<?php echo $this->Html->link($sparqlQueryParameter['CreatedBy']['full_name'], array('controller' => 'users', 'action' => 'view', $sparqlQueryParameter['CreatedBy']['id'])); ?>
				&nbsp;
			</dd>
		</dl>
	</div>
	<div class="span3">
		<div class="well" style="padding: 8px 0; margin-top:8px;">
		<ul class="nav nav-list">
			<li class="nav-header"><?php echo __('Actions'); ?></li>
			<li><?php echo $this->Html->link(__('Edit %s', __('Sparql Query Parameter')), array('action' => 'edit', $sparqlQueryParameter['SparqlQueryParameter']['id'])); ?> </li>
			<li><?php echo $this->Form->postLink(__('Delete %s', __('Sparql Query Parameter')), array('action' => 'delete', $sparqlQueryParameter['SparqlQueryParameter']['id']), null, __('Are you sure you want to delete # %s?', $sparqlQueryParameter['SparqlQueryParameter']['id'])); ?> </li>
			<li><?php echo $this->Html->link(__('List %s', __('Sparql Query Parameters')), array('action' => 'index')); ?> </li>
			<li><?php echo $this->Html->link(__('New %s', __('Sparql Query Parameter')), array('action' => 'add')); ?> </li>
			<li><?php echo $this->Html->link(__('List %s', __('Sparql Queries')), array('controller' => 'sparql_queries', 'action' => 'index')); ?> </li>
			<li><?php echo $this->Html->link(__('New %s', __('Sparql Query')), array('controller' => 'sparql_queries', 'action' => 'add')); ?> </li>
			<li><?php echo $this->Html->link(__('List %s', __('Users')), array('controller' => 'users', 'action' => 'index')); ?> </li>
			<li><?php echo $this->Html->link(__('New %s', __('Created By')), array('controller' => 'users', 'action' => 'add')); ?> </li>
		</ul>
		</div>
	</div>
</div>

