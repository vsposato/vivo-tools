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
<li><?php echo $this->BootstrapHtml->link(__('SPARQL Listing'), array('plugin' => '', 'controller' => 'sparql_queries', 'action' => 'index'), array('class' => '')); ?></li>
<li><?php echo $this->BootstrapHtml->link(__('Add New SPARQL'), array('plugin' => '', 'controller' => 'sparql_queries', 'action' => 'add'), array('class' => '')); ?></li>
