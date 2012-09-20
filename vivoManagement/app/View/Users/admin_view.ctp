<div class="row-fluid">
	<div class="span9">
		<h2><?php  echo __('User - %s', $user['User']['full_name']);?></h2>
		<div class="row">
			<div class="span4">
				<dl>
					<dt><?php echo __('Last Name'); ?></dt>
					<dd>
						<?php echo h($user['User']['last_name']); ?>
						&nbsp;
					</dd>
					<dt><?php echo __('Username'); ?></dt>
					<dd>
						<?php echo h($user['User']['username']); ?>
						&nbsp;
					</dd>
					<dt><?php echo __('Member Since'); ?></dt>
					<dd>
						<?php echo $this->Time->nice(h($user['User']['created'])); ?>
						&nbsp;
					</dd>
				</dl>
			</div>
			<div class="offset1 span4">
				<dl>
					<dt><?php echo __('First Name'); ?></dt>
					<dd>
						<?php echo h($user['User']['first_name']); ?>
						&nbsp;
					</dd>
					<dt><?php echo __('Email Address'); ?></dt>
					<dd>
						<?php echo h($user['User']['email_address']); ?>
						&nbsp;
					</dd>
				</dl>
			</div>
		</div>
	</div>
</div>

