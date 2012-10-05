<fieldset>
	<?php
		echo $this->BootstrapForm->create('SparqlQueries', array(
            'action' => 'execute',
            'class' => 'form-horizontal',
            'enctype' => 'multipart/form-data'
            )
        );
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
		if ($parameterized) {
		    // This is a parameterized query so we need to create the items to capture a parameterized items
		    echo $this->BootstrapForm->input('Execute.parameter_file', array(
                    'type' => 'file',
                )
            );
            // This will hold the actual parameter value
            echo $this->BootstrapForm->hidden('SparqlQuery.parameter');
            // This will hold the type of parameter that will be replaced: String or Numeric
            echo $this->BootstrapForm->hidden('SparqlQuery.parameter_type');
		}
		echo $this->BootstrapForm->hidden('SparqlQuery.id');
		echo $this->BootstrapForm->hidden('SparqlQuery.sparql_query');
		echo $this->BootstrapForm->hidden('SparqlQuery.name');
        echo $this->BootstrapForm->hidden('SparqlQuery.parameterized');
		echo $this->BootstrapForm->submit('Execute Query', array('class' => 'btn btn-success btn-large'));
		echo $this->BootstrapForm->end();
	?>
</fieldset>
