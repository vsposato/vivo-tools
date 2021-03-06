<div class="row-fluid">
	<div class="span9">
		<?php echo $this->BootstrapForm->create('Group', array('class' => 'form-horizontal'));?>
			<fieldset>
				<legend>
                    <?php
                        if ($this->request['action'] === 'admin_edit') {
                            echo __('Edit %s - %s', __('Group'), $this->request->data['Group']['group_name']);
                        } else {
                            echo __('Add a New %s', __('Group'));
                        }
                    ?>
                </legend>
				<?php
				echo $this->BootstrapForm->input('group_name', array(
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
	<div class="span3">
		<div class="well" style="padding: 8px 0; margin-top:8px;">
		<ul class="nav nav-list">
			<li class="nav-header"><?php echo __('Actions'); ?></li>
			<li><?php echo $this->Html->link(__('List %s', __('Groups')), array('action' => 'index'));?></li>
			<li><?php echo $this->Html->link(__('List %s', __('Users')), array('controller' => 'users', 'action' => 'index')); ?></li>
			<li><?php echo $this->Html->link(__('New %s', __('Group Created By')), array('controller' => 'users', 'action' => 'add')); ?></li>
		</ul>
		</div>
	</div>
</div>