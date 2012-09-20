<div class="row-fluid">
	<div class="span12">
		<h2><?php echo __('%s Listing', __('User'));?></h2>

		<p>
			<?php echo $this->BootstrapPaginator->counter(array('format' => __('Page {:page} of {:pages}, showing {:current} records out of {:count} total, starting on record {:start}, ending on {:end}')));?>
		</p>

		<table class="table table-hover table-condensed">
			<thead>
				<tr>
					<th><?php echo $this->BootstrapPaginator->sort('full_name');?></th>
					<th><?php echo $this->BootstrapPaginator->sort('username');?></th>
					<th><?php echo $this->BootstrapPaginator->sort('email_address');?></th>
					<th class="actions"><?php echo __('Actions');?></th>
				</tr>
			</thead>
			<tbody>
				<?php foreach ($users as $user): ?>
					<tr>
						<td><?php echo h($user['User']['full_name']); ?>&nbsp;</td>
						<td><?php echo h($user['User']['username']); ?>&nbsp;</td>
						<td><?php echo h($user['User']['email_address']); ?>&nbsp;</td>
						<td class="actions">
							<div class="btn-group">
								<?php
									echo $this->Html->link(__('View'), array('action' => 'view', $user['User']['id']), array('class' => 'btn btn-mini'));
									echo $this->Html->link(__('Edit'), array('action' => 'edit', $user['User']['id']), array('class' => 'btn btn-mini'));
									echo $this->Form->postLink(__('Delete'), array('action' => 'delete', $user['User']['id']), array('class' => 'btn btn-mini'), __('Are you sure you want to delete  %s?', $user['User']['username']));
								?>
							</div>
						</td>
					</tr>
				<?php endforeach; ?>
			</tbody>
			<tfoot>
				<tr>
					<td colspan="2"></td>
					<td colspan="2">
						<div class="btn-group">
							<?php
								echo $this->Html->link(__('Add New User'), array('action' => 'add'), array('class' => 'btn btn-medium btn-primary'));
							?>
						</div>
					</td>
				</tr>
			</tfoot>
		</table>

		<?php echo $this->BootstrapPaginator->pagination(); ?>
	</div>
</div>