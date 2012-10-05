<div class="row-fluid">
	<div class="span9">
		<?php echo $this->BootstrapForm->create('SparqlQueryParameter', array('class' => 'form-horizontal'));?>
			<fieldset>
				<legend><?php echo __('Edit %s', __('Sparql Query Parameter')); ?></legend>
				<?php
				echo $this->BootstrapForm->input('sparql_query_id');
				echo $this->BootstrapForm->input('parameter', array(
					'required' => 'required',
					'helpInline' => '<span class="label label-important">' . __('Required') . '</span>&nbsp;')
				);
				echo $this->BootstrapForm->input('parameter_type', array(
					'required' => 'required',
					'helpInline' => '<span class="label label-important">' . __('Required') . '</span>&nbsp;')
				);
				echo $this->BootstrapForm->input('created_by');
				echo $this->BootstrapForm->hidden('id');
				?>
				<?php echo $this->BootstrapForm->submit(__('Submit'));?>
			</fieldset>
		<?php echo $this->BootstrapForm->end();?>
	</div>
	<div class="span3">
		<div class="well" style="padding: 8px 0; margin-top:8px;">
		<ul class="nav nav-list">
			<li class="nav-header"><?php echo __('Actions'); ?></li>
			<li><?php echo $this->Form->postLink(__('Delete'), array('action' => 'delete', $this->Form->value('SparqlQueryParameter.id')), null, __('Are you sure you want to delete # %s?', $this->Form->value('SparqlQueryParameter.id'))); ?></li>
			<li><?php echo $this->Html->link(__('List %s', __('Sparql Query Parameters')), array('action' => 'index'));?></li>
			<li><?php echo $this->Html->link(__('List %s', __('Sparql Queries')), array('controller' => 'sparql_queries', 'action' => 'index')); ?></li>
			<li><?php echo $this->Html->link(__('New %s', __('Sparql Query')), array('controller' => 'sparql_queries', 'action' => 'add')); ?></li>
			<li><?php echo $this->Html->link(__('List %s', __('Users')), array('controller' => 'users', 'action' => 'index')); ?></li>
			<li><?php echo $this->Html->link(__('New %s', __('Created By')), array('controller' => 'users', 'action' => 'add')); ?></li>
		</ul>
		</div>
	</div>
</div>