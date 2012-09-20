<div class="row-fluid">
	<div class="span2">
		<div class="well" style="padding: 0 0; margin-top:2;">
		<ul class="nav nav-pills nav-stacked">
			<li class="nav-header"><?php echo __('Actions'); ?></li>
			<li><?php echo $this->Html->link(__('List %s', __('Users')), array('action' => 'index'));?></li>
			<li><?php echo $this->Html->link(__('List %s', __('Users')), array('controller' => 'users', 'action' => 'index')); ?></li>
			<li><?php echo $this->Html->link(__('New %s', __('Created By')), array('controller' => 'users', 'action' => 'add')); ?></li>
			<li><?php echo $this->Html->link(__('List %s', __('Log Actions')), array('controller' => 'log_actions', 'action' => 'index')); ?></li>
			<li><?php echo $this->Html->link(__('New %s', __('Log Action')), array('controller' => 'log_actions', 'action' => 'add')); ?></li>
		</ul>
		</div>
	</div>
	<div class="span10">
		<?php echo $this->BootstrapForm->create('User', array('class' => 'form-horizontal'));?>
			<fieldset>
				<legend><?php 
				if ($this->request['action'] === 'admin_edit') {
					echo __('Edit %s - %s', __('User'), $this->request->data['User']['full_name']);
				} else {
					echo __('Add a New %s', __('User'));
				}
				?>
				</legend>
				<?php
				echo $this->BootstrapForm->input('last_name', array(
					'required' => 'required',
					'helpInline' => '<span class="label label-important">' . __('Required') . '</span>&nbsp;')
				);
				echo $this->BootstrapForm->input('first_name', array(
					'required' => 'required',
					'helpInline' => '<span class="label label-important">' . __('Required') . '</span>&nbsp;')
				);
				echo $this->BootstrapForm->input('username', array(
					'required' => 'required',
					'helpInline' => '<span class="label label-important">' . __('Required') . '</span>&nbsp;')
				);
				echo $this->BootstrapForm->input('email_address', array(
					'required' => 'required',
					'helpInline' => '<span class="label label-important">' . __('Required') . '</span>&nbsp;')
				);
				echo $this->BootstrapForm->input('password', array(
					'required' => 'required',
					'helpInline' => '<span class="label label-important">' . __('Required') . '</span>&nbsp;')
				);
				if ($this->request['action'] === 'admin_edit') {
					echo $this->BootstrapForm->hidden('id');
				}
				?>
				<?php echo $this->BootstrapForm->submit(__('Submit'));?>
			</fieldset>
		<?php echo $this->BootstrapForm->end();?>
	</div>
</div>