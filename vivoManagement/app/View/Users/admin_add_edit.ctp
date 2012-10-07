<?php echo $this->BootstrapForm->create('User', array('class' => 'form-horizontal'));?>
    <fieldset>
        <legend><?php
        if ($this->request['action'] === 'admin_edit') {
            echo __('Edit %s - %s', __('User'), $this->request->data['User']['full_name']);
        } else {
            echo __('Add a New %s', __('User'));
        }
        ?>
        </legend>
        <?php
        echo $this->BootstrapForm->input('username', array(
                'required' => 'required',
                'helpInline' => '<span class="label label-important">' . __('Required') . '</span>&nbsp;')
        );
        echo $this->BootstrapForm->input('last_name', array(
            'required' => 'required',
            'helpInline' => '<span class="label label-important">' . __('Required') . '</span>&nbsp;')
        );
        echo $this->BootstrapForm->input('first_name', array(
            'required' => 'required',
            'helpInline' => '<span class="label label-important">' . __('Required') . '</span>&nbsp;')
        );
        echo $this->BootstrapForm->input('email_address', array(
            'required' => 'required',
            'helpInline' => '<span class="label label-important">' . __('Required') . '</span>&nbsp;')
        );
        echo $this->BootstrapForm->input('password', array(
            'required' => 'required',
            'helpInline' => '<span class="label label-important">' . __('Required') . '</span>&nbsp;')
        );
        echo $this->BootstrapForm->input('retype_password', array(
                'required' => 'required',
                'helpInline' => '<span class="label label-important">' . __('Required') . '</span>&nbsp;')
        );
        echo $this->BootstrapForm->input('active', array(
                'required' => 'required',
                'options' => array( 0 => 'Inactive', 1 => 'Active'),
                'empty' => '(choose one)',
                'helpInline' => '<span class="label label-important">' . __('Required') . '</span>&nbsp;')
        );
        echo $this->BootstrapForm->input('group_id', array(
                'required' => 'required',
                'empty' => '(choose one)',
                'options' => $groups,
                'helpInline' => '<span class="label label-important">' . __('Required') . '</span>&nbsp;')
        );
        if ($this->request['action'] === 'admin_edit') {
            echo $this->BootstrapForm->hidden('id');
        }
        ?>
        <?php echo $this->BootstrapForm->submit(__('Submit'));?>
    </fieldset>
<?php echo $this->BootstrapForm->end();?>
