<?php
App::uses('AppModel', 'Model');
/**
 * UniversityReference Model
 *
 */
class UniversityReference extends AppModel {

/**
 * Display field
 *
 * @var string
 */
	public $displayField = 'university_name';

/**
 * Validation rules
 *
 * @var array
 */
	public $validate = array(
		'university_name' => array(
            'nonEmpty' => array(
                'rule' => array('notBlank'),
                'message' => 'A university name is required',				
                'allowEmpty' => false
            ),'unique' => array(
				'rule' => 'isUnique',
				'allowEmpty' => false,		
				'message'=>"University Name Already Exist!"
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
