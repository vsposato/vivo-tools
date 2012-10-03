<div class="form-horizontal">
	<h3><?php echo sprintf(__("%s permissions"), $aroAlias); ?></h3>
	<p><?php echo $this->BootstrapPaginator->counter(array('format' => __('Page %page% of %pages%, showing %current% records out of %count% total, starting on record %start%, ending on %end%'))); ?></p>
	<?php echo $this->BootstrapPaginator->pagination(); ?>
	<?php echo $this->BootstrapForm->create('Perms'); ?>
	<table class="table table-striped table-bordered table-condensed">
		<tr>
			<th>Action</th>
			<?php foreach ($aros as $aro): ?>
			<?php $aro = array_shift($aro); ?>
			<th><?php echo h($aro[$aroDisplayField]); ?></th>
			<?php endforeach; ?>
		</tr>
		<?php
		$uglyIdent = Configure::read('AclManager.uglyIdent');
		$lastIdent = null;
		foreach ($acos as $id => $aco) {
			$action = $aco['Action'];
			$alias = $aco['Aco']['alias'];
			$ident = substr_count($action, '/');
			if ($ident <= $lastIdent && !is_null($lastIdent)) {
				for ($i = 0; $i <= ($lastIdent - $ident); $i++) {
					?></tr><?php
				}
			}
			if ($ident != $lastIdent) {
				?><tr class='aclmanager-ident-<?php echo $ident; ?>'><?php
			}
			?><td><?php echo ($ident == 1 ? "<strong>" : "" ) . ($uglyIdent ? str_repeat("&nbsp;&nbsp;", $ident) : "") . h($alias) . ($ident == 1 ? "</strong>" : "" ); ?></td>
			<?php foreach ($aros as $aro):
				$inherit = $this->BootstrapForm->value("Perms." . str_replace("/", ":", $action) . ".{$aroAlias}:{$aro[$aroAlias]['id']}-inherit");
				$allowed = $this->BootstrapForm->value("Perms." . str_replace("/", ":", $action) . ".{$aroAlias}:{$aro[$aroAlias]['id']}");
				$value = $inherit ? 'inherit' : null;
				$icon = $this->BootstrapHtml->image(($allowed ? 'test-pass-icon.png' : 'test-fail-icon.png')); ?>
				<td><?php echo $icon . " " . $this->BootstrapForm->select("Perms." . str_replace("/", ":", $action) . ".{$aroAlias}:{$aro[$aroAlias]['id']}", array(array('inherit' => __('Inherit'), 'allow' => __('Allow'), 'deny' => __('Deny'))), array('empty' => __('No change'), 'value' => $value)); ?></td>
			<?php endforeach; ?>
		<?php
			$lastIdent = $ident;
		}
		for ($i = 0; $i <= $lastIdent; $i++) {
			?></tr><?php
		}
		?>
	</table>
	<?php
	echo $this->BootstrapForm->end(__("Save"));
	?>
		<p><?php echo $this->BootstrapPaginator->counter(array('format' => __('Page %page% of %pages%, showing %current% records out of %count% total, starting on record %start%, ending on %end%'))); ?></p>
		<?php echo $this->BootstrapPaginator->pagination(); ?>
</div>
