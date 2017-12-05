<?php
App::uses('AppModel', 'Model');
/**
 * Department Model
 *
 */
class Department extends AppModel {

/**
 * Display field
 *
 * @var string
 */
	public $displayField = 'department_name';

/**
 * Validation rules
 *
 * @var array
 */
	public $validate = array(
		'department_name' => array(
            'nonEmpty' => array(
                'rule' => array('notBlank'),
                'message' => 'Department is required',				
                'allowEmpty' => false
            ),'unique' => array(
				'rule' => 'isUnique',
				'allowEmpty' => false,		
				'message'=>"Department already exist!"
			)
        ),
	);
	
	public $belongsTo = array(
		'User' => array(
			'className' => 'User',
			'foreignKey' => 'created_by',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		),
		'ModifiedUser' => array(
			'className' => 'ModifiedUser',
			'foreignKey' => 'modified_by',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		)
	);
	
}
