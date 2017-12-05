<?php
App::uses('AppModel', 'Model');
/**
 * User Model
 *
 * @property Department $Department
 * @property Designation $Designation
 * @property UserRole $UserRole
 */
class User extends AppModel {

/**
 * Display field
 *
 * @var string
 */
	public $displayField = 'username';

/**
 * Validation rules
 *
 * @var array
 */
	public $validate = array(
		'username' => array(
            'nonEmpty' => array(
                'rule' => array('notBlank'),
                'message' => 'A username is required',
                'allowEmpty' => false
            ),'unique' => array(
				'rule' => 'isUnique',				'allowEmpty' => false,		
				'message'=>"Username already exist!"
			)
        ),
        'password' => array(
            'required' => array(
                'rule' => array('notBlank'),            	'on' => 'create',
                'message' => 'A current password is required'
            )
        ),
		'current_password' => array(
			'required' => array(
				'rule' => array('notBlank'),
				'message' => 'A Current Password is Required'
			)
		),
		'new_password' => array(
			'required' => array(
				'rule' => array('notBlank'),
				'message' => 'A New Password is Required'
			)
		),
		'confirm_password' => array(
			'required' => array(
				'rule' => array('notBlank'),
				'message' => 'A Confirm Password is Required'
			)
		)
	);

	// The Associations below have been created with all possible keys, those that are not needed can be removed

/**
 * belongsTo associations
 *
 * @var array
 */
	public $belongsTo = array(
		'Department' => array(
			'className' => 'Department',
			'foreignKey' => 'department_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		),
		'Designation' => array(
			'className' => 'Designation',
			'foreignKey' => 'designation_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		),
		'UserRole' => array(
			'className' => 'UserRole',
			'foreignKey' => 'user_role_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		)		
	);
	
	
	public function beforeSave($options = array()) {
		// hash our password
		if (isset($this->data[$this->alias]['password'])) {
			$this->data[$this->alias]['password'] = AuthComponent::password($this->data[$this->alias]['password']);
		}
			
		// if we get a new password, hash it
		if (isset($this->data[$this->alias]['new_password']) && !empty($this->data[$this->alias]['new_password'])) {
			$this->data[$this->alias]['password'] = AuthComponent::password($this->data[$this->alias]['new_password']);
		}
		
		// fallback to our parent
		return parent::beforeSave($options);
	}
	public $hasAndBelongsToMany = array(		'Path' => array(			'className' => 'Path',			'joinTable' => 'users_paths',			'foreignKey' => 'user_id',			'associationForeignKey' => 'path_id',			'unique' => 'keepExisting',			'conditions' => '',			'fields' => '',			'order' => '',			'limit' => '',			'offset' => '',			'finderQuery' => '',		)			
	);	
}
