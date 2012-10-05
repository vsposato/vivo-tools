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
			echo $this->BootstrapForm->input('parameterized', array(
				'label' => 'Accepts Parameters?',
				'id' => 'parameterCheckbox'
				)
			);
			$options = array('Numeric' => 'Numeric', 'String' => 'String');
			echo $this->BootstrapForm->input('parameter_type', array(
				'type' => 'select',
				'id' => 'parameterType',
				'options' => $options,
				'selected' => 'csv',
				'class' => 'input-xxlarge'
			));
			echo $this->BootstrapForm->input('parameter', array(
				'id' => 'parameter',
				'helpInline' => '<p class="parameterHelp">This should be in format ?ufid like you<br>would write in an actual query, in the query you should wrap the parameter with [].
				    For the parameter example it would be [?ufid] every place it needs to be replaced.</p>',
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

<script type="text/javascript">
	$(document).ready(function(){
        if($('#parameterCheckbox').is(':checked')) {
            $('#parameter').show();
            $('label[for="SparqlQueryParameter"]').show();
            $('span.help-inline p.parameterHelp').show();
            $('#parameterType').show();
            $('label[for="SparqlQueryParameterType"]').show();
        } else {
            $('#parameter').hide();
            $('label[for="SparqlQueryParameter"]').hide();
            $('span.help-inline p.parameterHelp').hide();
            $('#parameterType').hide();
            $('label[for="SparqlQueryParameterType"]').hide();
        }
	});
	$('input[name="data[SparqlQuery][parameterized]"]').click(function(){
		$('#parameter').toggle(this.checked);
		$('label[for="SparqlQueryParameter"]').toggle(this.checked);
		$('span.help-inline p.parameterHelp').toggle(this.checked);
		$('#parameterType').toggle(this.checked);
		$('label[for="SparqlQueryParameterType"]').toggle(this.checked);
	});
</script>