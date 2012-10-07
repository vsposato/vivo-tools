<?php
/**
 * Created by JetBrains PhpStorm.
 * User: vsposato
 * Date: 10/1/12
 * Time: 6:40 PM
 * To change this template use File | Settings | File Templates.
 */
$user = $this->Session->read('Auth.User');
if ($user) {
?>
<div class="well" style="padding: 8px 0; margin-top:8px;">
    <ul class="nav nav-list">
        <?php
                if ($this->Session->read('FULL_ACCESS_GRANTED') || $this->Session->read('ADMIN_USER')) {
                    echo $this->element('sidebar_administratorMenu');
                }
                echo $this->element('sidebar_userMenu');
        ?>
    </ul>
</div>
<?php } ?>