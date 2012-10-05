<?php
/**
 * Created by JetBrains PhpStorm.
 * User: vsposato
 * Date: 10/1/12
 * Time: 6:43 PM
 * To change this template use File | Settings | File Templates.
 */
?>
<li class="nav-header">Administration</li>
<li><?php echo $this->BootstrapHtml->link(__('User Listing'), array('admin' => true, 'plugin' => '', 'controller' => 'users', 'action' => 'index'), array('class' => '')); ?></li>
<li><?php echo $this->BootstrapHtml->link(__('Group Listing'), array('admin' => true, 'plugin' => '', 'controller' => 'groups', 'action' => 'index'), array('class' => '')); ?></li>
<li>
    <?php
    if ($this->request['action'] === 'admin_index' && $this->request['controller'] === 'users') {
        echo $this->BootstrapHtml->link(__('Add New User'), array('admin' => true, 'plugin' => '', 'controller' => 'users', 'action' => 'add'), array('class' => ''));
    } elseif ($this->request['action'] === 'admin_index' && $this->request['controller'] === 'groups') {
        echo $this->BootstrapHtml->link(__('Add New Group'), array('admin' => true, 'plugin' => '', 'controller' => 'groups', 'action' => 'add'), array('class' => ''));
    }
    ?>
</li>
<li><?php echo $this->BootstrapHtml->link(__('Access Control Lists'), array('admin' => false, 'plugin' => 'acl_manager', 'controller' => 'acl', 'action' => 'index'), array('class' => '')); ?></li>
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