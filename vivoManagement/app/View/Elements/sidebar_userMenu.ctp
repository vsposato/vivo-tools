<?php
/**
 * Created by JetBrains PhpStorm.
 * User: vsposato
 * Date: 10/1/12
 * Time: 6:43 PM
 * To change this template use File | Settings | File Templates.
 */
?>
<?php if ($this->Session->read('FULL_ACCESS_GRANTED')) { ?>
    <li class="divider"></li>
<?php } ?>
<li class="nav-header">SPARQL Tools</li>
<li><?php echo $this->BootstrapHtml->link(__('SPARQL Listing'), array('admin' => false, 'plugin' => '', 'controller' => 'sparql_queries', 'action' => 'index'), array('class' => '')); ?></li>
<?php
    if ($this->request['action'] === 'edit' && $this->request['controller'] === 'sparql_queries') {
?>
    <li><?php echo $this->BootstrapHtml->link(__('Execute this SPARQL Query'), array('admin' => false, 'plugin' => '', 'controller' => 'sparql_queries', 'action' => 'execute', $this->request['data']['SparqlQuery']['id']), array('class' => '')); ?></li>
<?php } elseif ($this->request['action'] === 'view' && $this->request['controller'] === 'sparql_queries') { ?>
    <li><?php echo $this->BootstrapHtml->link(__('Execute this SPARQL Query'), array('admin' => false, 'plugin' => '', 'controller' => 'sparql_queries', 'action' => 'execute', $this->request['pass'][0]), array('class' => '')); ?></li>
    <?php if ( $sparqlQuery['QueryUserCreated']['id'] == AuthComponent::user('id') || $this->Session->read('FULL_ACCESS_GRANTED') == true ) { ?>
        <li><?php echo $this->BootstrapHtml->link(__('Delete this SPARQL Query'), array('admin' => false, 'plugin' => '', 'controller' => 'sparql_queries', 'action' => 'delete', $this->request['pass'][0]), array('class' => '')); ?></li>
        <li><?php echo $this->BootstrapHtml->link(__('Edit this SPARQL Query'), array('admin' => false, 'plugin' => '', 'controller' => 'sparql_queries', 'action' => 'view', $this->request['pass'][0]), array('class' => '')); ?></li>
    <?php } ?>
<?php } ?>
<li><?php echo $this->BootstrapHtml->link(__('Add New SPARQL'), array('admin' => false, 'plugin' => '', 'controller' => 'sparql_queries', 'action' => 'add'), array('class' => '')); ?></li>
