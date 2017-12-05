<?php
App::uses('AppModel', 'Model');
/**
 * InternalProject Model
 *
 */
class InternalProject extends AppModel {

/**
 * Validation rules
 *
 * @var array
 */
	public $validate = array(
		'id' => array(
			'notBlank' => array(
				'rule' => array('notBlank'),
			),
		),
	);
	
	public $belongsTo = array(	
		'Student' => array(	
			'className' => 'Student',	
			'foreignKey' => 'student_id',	
			'conditions' => '',	
			'fields' => '',	
			'order' => ''	
		),
		'CourseMapping' => array(
			'className' => 'CourseMapping',
			'foreignKey' => 'course_mapping_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		),
	);
}
