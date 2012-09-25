<fieldset>
	<?php
		echo $this->BootstrapForm->create('SparqlQueries', array('action' => 'execute', 'class' => 'form-horizontal'));
	?>
	<legend>Execute Query - <?php echo $sparqlQuery['SparqlQuery']['name']; ?></legend>
	<?php
		if ($construct) {
			// This is a construct statement - therefore we only allow XML
			$options = array('rdf' => 'RDF');
			echo $this->BootstrapForm->input('Execute.outputFormat', array(
				'type' => 'select',
				'label' => 'Output Format',
				'options' => $options,
				'selected' => 'rdf'
			));
		} elseif (! $construct) {
			// This is not a construct statement - therefore we only allow table, csv, or tsv
			$options = array('array' => 'Table', 'csv' => 'CSV', 'tsv' => 'TSV');
			echo $this->BootstrapForm->input('Execute.outputFormat', array(
				'type' => 'select',
				'label' => 'Output Format',
				'options' => $options,
				'selected' => 'csv'
			));
		}
		echo $this->BootstrapForm->hidden('SparqlQuery.id');
		echo $this->BootstrapForm->hidden('SparqlQuery.sparql_query');
		echo $this->BootstrapForm->hidden('SparqlQuery.name');
		echo $this->BootstrapForm->submit('Execute Query', array('class' => 'btn btn-success btn-large'));
		echo $this->BootstrapForm->end();
	?>
</fieldset>
