<div class="modal hide" id="addQueryParameter">
	<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal">X</button>
		<h3>Add a Parameter Query</h3>
	</div>
	<?php
		echo $this->BootstrapHtml->create('SparqlQueryParameters', array(
			'id' => 'SparqlQueryParameterAddForm',
			'controller' => 'SparqlQueryParameters',
			'action' => 'add',
			'class' => 'form-horizontal'
			)
		);
	?>
	<div class="modal-body">
		<div class="control-group">
		<?php
			echo $this->BootstrapHtml->input('parameter', array(
				'id' => 'parameterInput',
				'required' => 'required',
				'helpInline' => '<span class="label label-important">' . __('Required') . '</span>&nbsp;',
				)
			);
		?>
		</div>
	</div>
</div>