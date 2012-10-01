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
<li><?php echo $this->BootstrapHtml->link(__('User Listing'), array('plugin' => '', 'controller' => 'users', 'action' => 'index'), array('class' => '')); ?></li>
<li><?php echo $this->BootstrapHtml->link(__('Add New User'), array('plugin' => '', 'controller' => 'users', 'action' => 'add'), array('class' => '')); ?></li>
<li><?php echo $this->BootstrapHtml->link(__('Group Listing'), array('plugin' => '', 'controller' => 'groups', 'action' => 'index'), array('class' => '')); ?></li>
<li><?php echo $this->BootstrapHtml->link(__('Access Control Lists'), array('plugin' => 'acl_manager', 'controller' => 'acl', 'action' => 'index'), array('class' => '')); ?></li>
