<?php
/**
 * Created by JetBrains PhpStorm.
 * User: vsposato
 * Date: 10/1/12
 * Time: 6:43 PM
 * To change this template use File | Settings | File Templates.
 */
?>
<li class="nav-header">SPARQL Tools</li>
<li><?php echo $this->BootstrapHtml->link(__('SPARQL Listing'), array('admin' => false, 'plugin' => '', 'controller' => 'sparql_queries', 'action' => 'index'), array('class' => '')); ?></li>
<li><?php echo $this->BootstrapHtml->link(__('Add New SPARQL'), array('admin' => false, 'plugin' => '', 'controller' => 'sparql_queries', 'action' => 'add'), array('class' => '')); ?></li>
<?php
// Check to see if this is the ACL controller - if so, process the items for that
if ( $this->params['controller'] == 'acl' ) {
    if ( $this->params['action'] == 'permissions')	{
        $aroModels = Configure::read("AclManager.aros");
        if ($aroModels > 1) { ?>
        <li class="divider"></li>
        <li class="nav-header"><?php echo __('Manage for'); ?></li>
        <?php foreach ($aroModels as $aroModel) { ?>
            <li><?php echo $this->BootstrapHtml->link($aroModel, array('aro' => $aroModel)); ?></li>
            <?php } ?>
        <?php }	?>
    <?php } ?>
<li class="divider"></li>
<li class="nav-header"><?php echo __('Access Control Lists'); ?></li>
<?php if ( $this->params['action'] == 'permissions') { ?>
    <li><?php echo $this->BootstrapHtml->link(__('< Back'), array('admin' => false, 'plugin' => 'acl_manager', 'controller' => 'acl','action' => 'index')); ?></li>
    <?php } ?>
<li><?php echo $this->BootstrapHtml->link(__('Manage permissions'), array('admin' => false, 'plugin' => 'acl_manager', 'controller' => 'acl','action' => 'permissions')); ?></li>
<li><?php echo $this->BootstrapHtml->link(__('Update ACOs'), array('admin' => false, 'plugin' => 'acl_manager', 'controller' => 'acl','action' => 'update_acos')); ?></li>
<li><?php echo $this->BootstrapHtml->link(__('Update AROs'), array('admin' => false, 'plugin' => 'acl_manager', 'controller' => 'acl','action' => 'update_aros')); ?></li>
<li><?php echo $this->BootstrapHtml->link(__('Drop ACOs/AROs'), array('admin' => false, 'plugin' => 'acl_manager', 'controller' => 'acl','action' => 'drop'), array(), __("Do you want to drop all ACOs and AROs?")); ?></li>
<li><?php echo $this->BootstrapHtml->link(__('Drop permissions'), array('admin' => false, 'plugin' => 'acl_manager', 'controller' => 'acl','action' => 'drop_perms'), array(), __("Do you want to drop all the permissions?")); ?></li>
<?php } ?>