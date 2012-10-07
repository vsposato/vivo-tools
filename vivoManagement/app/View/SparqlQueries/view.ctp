<h2><?php  echo __('Sparql Query - ') . h($sparqlQuery['SparqlQuery']['name']) ;?></h2>
<div class="row-fluid">
    <div class="span12">
        <dl>
            <dt><?php echo __('Short Description'); ?></dt>
            <dd>
                <?php echo h($sparqlQuery['SparqlQuery']['short_description']); ?>
                &nbsp;
            </dd>
            <dt><?php echo __('Long Description'); ?></dt>
            <dd>
                <?php echo h($sparqlQuery['SparqlQuery']['long_description']); ?>
                &nbsp;
            </dd>
        </dl>
    </div>
</div>
<div class="row-fluid">
    <div class="span4">
        <dl>
            <dt><?php echo __('Created'); ?></dt>
            <dd>
                <?php echo h($sparqlQuery['SparqlQuery']['created']); ?>
                &nbsp;
            </dd>
            <dt><?php echo __('Modified'); ?></dt>
            <dd>
                <?php echo h($sparqlQuery['SparqlQuery']['modified']); ?>
                &nbsp;
            </dd>
        </dl>
    </div>
    <div class="span4">
        <dl>
            <dt><?php echo __('Query Created By'); ?></dt>
            <dd>
                <?php echo $this->Html->link($sparqlQuery['QueryUserCreated']['full_name'], array('controller' => 'users', 'action' => 'view', $sparqlQuery['QueryUserCreated']['id'])); ?>
                &nbsp;
            </dd>
            <dt><?php echo __('Query Modified By'); ?></dt>
            <dd>
                <?php echo $this->Html->link($sparqlQuery['QueryUserModified']['full_name'], array('controller' => 'users', 'action' => 'view', $sparqlQuery['QueryUserModified']['id'])); ?>
                &nbsp;
            </dd>
        </dl>
    </div>
</div>
<div class="row-fluid">
    <div class="span12">
        <dl>
            <dt><?php echo __('Sparql Query'); ?></dt>
            <dd>
                <pre>
                <?php echo h($sparqlQuery['SparqlQuery']['sparql_query']); ?>
                    &nbsp;
                </pre>
            </dd>
        </dl>
    </div>
</div>