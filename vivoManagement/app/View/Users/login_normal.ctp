<div class="row-fluid">
	<div class="span12">
		<div class="span4 offset4">
			<fieldset>
				<?php
					echo $this->BootstrapForm->create('User', array('action' => 'login', 'class' => 'form-horizontal'));
				?>
				<legend>User Login</legend>
				<?php

					echo $this->BootstrapForm->input('username');
					echo $this->BootstrapForm->input('password');
					echo $this->BootstrapForm->submit('Submit', array('class' => 'btn btn-primary btn-large'));
					echo $this->BootstrapForm->end();
				?>
			</fieldset>
		</div>
	</div>
</div>