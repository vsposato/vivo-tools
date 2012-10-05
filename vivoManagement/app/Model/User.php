<?php
App::uses('AppModel', 'Model');
/**
 * User Model
 *
 * @property User $CreatedBy
 * @property User $ModifiedBy
 * @property LogAction $LogAction
 * @property User $UsersCreated
 * @property User $UsersModified
 */
class User extends AppModel {

/**
 * Virtual fields
 *
 * @var array
 */
 	public $virtualFields = array();

/**
 * Display name
 *
 * @var array
 */
 	public $displayField = 'full_name';

    public $actsAs = array('Acl' => array('type' => 'requester'));
/**
 * Validation rules
 *
 * @var array
 */
	public $validate = array(
		'last_name' => array(
			'notempty' => array(
				'rule' => array('notempty'),
				'message' => 'You must enter your last name!',
				'allowEmpty' => false,
				'required' => true,
				//'last' => false, // Stop validation after this rule
				//'on' => 'create', // Limit validation to 'create' or 'update' operations
			),
			'maxlength' => array(
				'rule' => array('maxlength',50),
				'message' => 'Your last name must be less than 50 characters in length!',
				'allowEmpty' => false,
				'required' => true,
				//'last' => false, // Stop validation after this rule
				//'on' => 'create', // Limit validation to 'create' or 'update' operations
			),
		),
		'first_name' => array(
			'notempty' => array(
				'rule' => array('notempty'),
				'message' => 'You must enter your first name!',
				'allowEmpty' => false,
				'required' => true,
				//'last' => false, // Stop validation after this rule
				//'on' => 'create', // Limit validation to 'create' or 'update' operations
			),
			'maxlength' => array(
				'rule' => array('maxlength',50),
				'message' => 'Your last name must be less than 50 characters in length!',
				'allowEmpty' => false,
				'required' => true,
				//'last' => false, // Stop validation after this rule
				//'on' => 'create', // Limit validation to 'create' or 'update' operations
			),
		),
		'username' => array(
			'notempty' => array(
				'rule' => array('notempty'),
				'message' => 'Your username must not be blank!',
				'allowEmpty' => false,
				'required' => true,
				//'last' => false, // Stop validation after this rule
				//'on' => 'create', // Limit validation to 'create' or 'update' operations
			),
			'isunique' => array(
				'rule' => array('isUnique'),
				'message' => 'Your username must be unique!',
				'allowEmpty' => false,
				'required' => true,
				//'last' => false, // Stop validation after this rule
				//'on' => 'create', // Limit validation to 'create' or 'update' operations
			),
			'maxlength' => array(
				'rule' => array('maxlength',50),
				'message' => 'You username must be no more than 50 characters in length!',
				'allowEmpty' => false,
				'required' => true,
				//'last' => false, // Stop validation after this rule
				//'on' => 'create', // Limit validation to 'create' or 'update' operations
			),
			'minlength' => array(
				'rule' => array('minlength',5),
				'message' => 'Your username must be at least 5 characters in legth!',
				'allowEmpty' => false,
				'required' => true,
				//'last' => false, // Stop validation after this rule
				//'on' => 'create', // Limit validation to 'create' or 'update' operations
			),
		),
		'email_address' => array(
			'notempty' => array(
				'rule' => array('notempty'),
				'message' => 'You must enter a valid email address!',
				'allowEmpty' => false,
				'required' => true,
				//'last' => false, // Stop validation after this rule
				//'on' => 'create', // Limit validation to 'create' or 'update' operations
			),
			'isunique' => array(
				'rule' => array('isUnique'),
				'message' => 'Your email address must be unique!',
				'allowEmpty' => false,
				'required' => true,
				//'last' => false, // Stop validation after this rule
				//'on' => 'create', // Limit validation to 'create' or 'update' operations
			),
			'maxlength' => array(
				'rule' => array('maxlength',100),
				'message' => 'Your email address can be no more than 100 characters in length!',
				'allowEmpty' => false,
				'required' => true,
				//'last' => false, // Stop validation after this rule
				//'on' => 'create', // Limit validation to 'create' or 'update' operations
			),
			'email' => array(
				'rule' => array('email'),
				'message' => 'You must provide a valid email address!',
				'allowEmpty' => false,
				'required' => true,
				//'last' => false, // Stop validation after this rule
				//'on' => 'create', // Limit validation to 'create' or 'update' operations
			),
		),
		'active' => array(
			'notempty' => array(
				'rule' => array('notempty'),
				'message' => 'You must have an active flag!',
				'allowEmpty' => false,
				'required' => true,
				//'last' => false, // Stop validation after this rule
				//'on' => 'create', // Limit validation to 'create' or 'update' operations
			),
			'boolean' => array(
				'rule' => array('boolean'),
				'message' => 'Your active flag can only be a true or false!',
				'allowEmpty' => false,
				'required' => true,
				//'last' => false, // Stop validation after this rule
				//'on' => 'create', // Limit validation to 'create' or 'update' operations
			),
		),
		'password' => array(
			'notempty' => array(
				'rule' => array('notempty'),
				'message' => 'You must provide a valid email address!',
				'allowEmpty' => false,
				'required' => true,
				//'last' => false, // Stop validation after this rule
				//'on' => 'create', // Limit validation to 'create' or 'update' operations
			),
		),
	);

	//The Associations below have been created with all possible keys, those that are not needed can be removed
    /**
     * belongsTo associations
     *
     * @var array
     */
    public $belongsTo = array(
        'Group' => array(
            'className' => 'Group',
            'foreignKey' => 'group_id',
            'conditions' => '',
            'fields' => '',
            'order' => ''
        )
    );

/**
 * hasMany associations
 *
 * @var array
 */
	public $hasMany = array(
		'LogAction' => array(
			'className' => 'LogAction',
			'foreignKey' => 'user_id',
			'dependent' => false,
			'conditions' => '',
			'fields' => '',
			'order' => '',
			'limit' => '',
			'offset' => '',
			'exclusive' => '',
			'finderQuery' => '',
			'counterQuery' => ''
		)
	);

/**
 *
 * User Model constructor
 *
 */
 	public function __construct($id = false, $table = null, $ds = null) {
	 	    parent::__construct($id, $table, $ds);
	 	    $this->virtualFields['full_name'] = sprintf('CONCAT(%s.last_name, ", ", %s.first_name)', $this->alias, $this->alias);
 	}
    /**
     * beforeSave function.
     *
     * @access public
     * @return void
     */
    public function beforeSave() {

        // Run the parent's beforeSave
        parent::beforeSave();

        // We will need to hash the password here
        if (! empty($this->data['User']['password']) && isset($this->data['User']['password']) ) {
            // The password was submitted as part of the update, so we need to hash the password
            $this->data['User']['password'] = AuthComponent::password($this->data['User']['password']);
        }

        if (isset($this->data['User']['password'])) {
            // We need to set the expiration date of the user
            $expiration_date = date( "Y-m-d H:i",(time() + PASSWORD_EXPIRATION_DAYS));
            $this->data['User']['expiration_date'] = $expiration_date;
        }

        return true;
    }

    public function parentNode() {
        if (!$this->id && empty($this->data)) {
            return null;
        }
        if (isset($this->data['User']['group_id'])) {
            $groupId = $this->data['User']['group_id'];
        } else {
            $groupId = $this->field('group_id');
        }
        if (!$groupId) {
            return null;
        } else {
            return array('Group' => array('id' => $groupId));
        }
    }
    /**
     * validPassword function.
     *
     * @access public
     * @param mixed $password (default: null)
     * @return void
     */
    public function validPassword($password = null) {

        return preg_match("/(?=^.{8,}$)((?=.*\d)|(?=.*\W+))(?![.\n])(?=.*[A-Z])(?=.*[a-z]).*$/", $password);
    }

}
