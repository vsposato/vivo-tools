<div class="row-fluid">
	<div class="span9">
		<h2><?php  echo __('Sparql Query');?></h2>
		<dl>
			<dt><?php echo __('Id'); ?></dt>
			<dd>
				<?php echo h($sparqlQuery['SparqlQuery']['id']); ?>
				&nbsp;
			</dd>
			<dt><?php echo __('Short Description'); ?></dt>
			<dd>
				<?php echo h($sparqlQuery['SparqlQuery']['short_description']); ?>
				&nbsp;
			</dd>
			<dt><?php echo __('Long Description'); ?></dt>
			<dd>
				<?php echo h($sparqlQuery['SparqlQuery']['long_description']); ?>
				&nbsp;
			</dd>
			<dt><?php echo __('Sparql Query'); ?></dt>
			<dd>
				<pre>
				<?php echo h($sparqlQuery['SparqlQuery']['sparql_query']); ?>
				&nbsp;
				</pre>
			</dd>
			<dt><?php echo __('Created'); ?></dt>
			<dd>
				<?php echo h($sparqlQuery['SparqlQuery']['created']); ?>
				&nbsp;
			</dd>
			<dt><?php echo __('Query User Created'); ?></dt>
			<dd>
				<?php echo $this->Html->link($sparqlQuery['QueryUserCreated']['full_name'], array('controller' => 'users', 'action' => 'view', $sparqlQuery['QueryUserCreated']['id'])); ?>
				&nbsp;
			</dd>
			<dt><?php echo __('Modified'); ?></dt>
			<dd>
				<?php echo h($sparqlQuery['SparqlQuery']['modified']); ?>
				&nbsp;
			</dd>
			<dt><?php echo __('Query User Modified'); ?></dt>
			<dd>
				<?php echo $this->Html->link($sparqlQuery['QueryUserModified']['full_name'], array('controller' => 'users', 'action' => 'view', $sparqlQuery['QueryUserModified']['id'])); ?>
				&nbsp;
			</dd>
		</dl>
	</div>
	<div class="span3">
		<div class="well" style="padding: 8px 0; margin-top:8px;">
		<ul class="nav nav-list">
			<li class="nav-header"><?php echo __('Actions'); ?></li>
			<li><?php echo $this->Html->link(__('Edit %s', __('Sparql Query')), array('action' => 'edit', $sparqlQuery['SparqlQuery']['id'])); ?> </li>
			<li><?php echo $this->Form->postLink(__('Delete %s', __('Sparql Query')), array('action' => 'delete', $sparqlQuery['SparqlQuery']['id']), null, __('Are you sure you want to delete # %s?', $sparqlQuery['SparqlQuery']['id'])); ?> </li>
			<li><?php echo $this->Html->link(__('List %s', __('Sparql Queries')), array('action' => 'index')); ?> </li>
			<li><?php echo $this->Html->link(__('New %s', __('Sparql Query')), array('action' => 'add')); ?> </li>
			<li><?php echo $this->Html->link(__('List %s', __('Users')), array('controller' => 'users', 'action' => 'index')); ?> </li>
			<li><?php echo $this->Html->link(__('New %s', __('Query User Created')), array('controller' => 'users', 'action' => 'add')); ?> </li>
		</ul>
		</div>
	</div>
</div>

