<?php
App::uses('AppModel', 'Model');
/**
 * CollegeReference Model
 *
 */
class CollegeReference extends AppModel {

/**
 * Display field
 *
 * @var string
 */
	public $displayField = 'college_name';

/**
 * Validation rules
 *
 * @var array
 */
	public $validate = array(
		'college_name' => array(
            'nonEmpty' => array(
                'rule' => array('notBlank'),
                'message' => 'A college name is required',				
                'allowEmpty' => false
            ),'unique' => array(
				'rule' => 'isUnique',
				'allowEmpty' => false,		
				'message'=>"College name already exist!"
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
