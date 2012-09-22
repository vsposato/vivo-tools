<?php $formTitle = $this->request->params['action'] == 'add' ? __('Create New %s', __('Sparql Query')) : __('Modify %s - %s', __('Sparql Query'),$this->request->data['SparqlQuery']['name']); ?>
<?php echo $this->BootstrapForm->create('SparqlQuery', array('class' => 'form-horizontal'));?>
	<fieldset>
		<legend><?php echo $formTitle; ?></legend>
		<?php
			echo $this->BootstrapForm->input('name', array(
				'required' => 'required',
				'helpInline' => '<span class="label label-important">' . __('Required') . '</span>&nbsp;',
				'class' => 'input-xxlarge'
				)
			);
			echo $this->BootstrapForm->input('short_description', array(
				'required' => 'required',
				'helpInline' => '<span class="label label-important">' . __('Required') . '</span>&nbsp;',
				'class' => 'input-xxlarge'
				)
			);
			echo $this->BootstrapForm->input('long_description', array(
				'rows' => '5',
				'class' => 'input-xxlarge'
			));
			echo $this->BootstrapForm->input('sparql_query', array(
				'required' => 'required',
				'helpInline' => '<span class="label label-important">' . __('Required') . '</span>&nbsp;',
				'rows' => '20',
				'class' => 'input-xxlarge'
				)
			);
			if ($this->request->params['action'] == 'edit') {
				echo $this->BootstrapForm->hidden('SparqlQuery.id');
			}
		?>
		<?php echo $this->BootstrapForm->submit(__('Submit'));?>
	</fieldset>
<?php echo $this->BootstrapForm->end();?>
