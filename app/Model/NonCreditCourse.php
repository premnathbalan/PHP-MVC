<?php
App::uses('AppModel', 'Model');
/**
 * NonCreditCourse Model
 *
 */
class NonCreditCourse extends AppModel {

/**
 * Display field
 *
 * @var string
 */
	public $displayField = 'non_credit_course_name';

/**
 * Validation rules
 *
 * @var array
 */
	public $validate = array(
		'non_credit_course_name' => array(
			'nonEmpty' => array(
                'rule' => array('notBlank'),
                'message' => 'Non Credit Course Name is required',				
                'allowEmpty' => false
            ),'unique' => array(
				'rule' => 'isUnique',
				'allowEmpty' => false,		
				'message'=>"Non Credit Course Name Already Exist!"
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
