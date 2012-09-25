<div class="modal hide" id="executeSPARQLModal">
	<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal">×</button>
		<h3>Execute SPARQL Query</h3>
	</div>
	<div class="modal-body">
		<?php
			echo $this->BootstrapForm->create('User', array(
				'action' => 'change_password',
				'class' => 'form-horizontal',
				'default' => false
			));
		?>
			<div class="control-group">
			<?php
				echo $this->BootstrapForm->label('current_password', 'Current Password', array(
					'class' => 'control-label'
				));
				echo $this->BootstrapForm->password('current_password', array(
					'class' => 'controls'
				));
			echo $this->Form->unlockField('current_password');
			?>
			</div>

			<div class="control-group">
			<?php
				echo $this->BootstrapForm->label('new_password', 'New Password', array(
					'class' => 'control-label'
				));
				echo $this->BootstrapForm->password('new_password', array(
					'class' => 'controls'
				));
			?>
			</div>
			<div class="control-group">
			<?php
				echo $this->BootstrapForm->label('retype_password', 'Re-type Password', array(
					'class' => 'control-label'
				));
				echo $this->BootstrapForm->password('retype_password', array(
					'class' => 'controls'
				));
			echo $this->Form->unlockField('retype_password');
			echo $this->BootstrapForm->hidden('id');
			?>
			</div>
	</div>
	<div class="modal-footer">
		<div class="btn-group">
			<?php
				echo $this->BootstrapForm->submit(__('Submit'), array(
					'class' => 'btn btn-primary'
				));
				echo $this->BootstrapForm->end();
			?>
		</div>
	</div>
</div>
<script type=text/javascript>
	$('#UserChangePasswordForm').submit( function() {
			$.ajax({
				type: 'POST',
				url: '<?php echo $this->Html->url('/', true); ?>' + 'users/change_password',
				data: $('#UserChangePasswordForm').serialize(),
				beforeSend: function() {

				},
				success: function(data) {

					// Clear out the password fields
					$('#UserCurrentPassword').val("");
					$('#UserNewPassword').val("");
					$('#UserRetypePassword').val("");

					// Close the password modal dialog
					$('#changePasswordModal').modal('toggle');
					// Return the flash message that occurred
					$('#session_messages').html(data);
					location.reload();
					return false;
				},
				error: function(data) {
					$('#session_messages').html(data);
					return false;
				}
			});
			return false;
		});
</script>
