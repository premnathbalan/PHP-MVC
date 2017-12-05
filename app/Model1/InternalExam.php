<?php
App::uses('AppModel', 'Model');
/**
 * InternalExam Model
 *
 * @property CourseMapping $CourseMapping
 * @property Student $Student
 */
class InternalExam extends AppModel {

/**
 * Display field
 *
 * @var string
 */
	public $displayField = 'course_mapping_id';


	// The Associations below have been created with all possible keys, those that are not needed can be removed

/**
 * belongsTo associations
 *
 * @var array
 */
	public $belongsTo = array(
		'CourseMapping' => array(
			'className' => 'CourseMapping',
			'foreignKey' => 'course_mapping_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		),
		'Student' => array(
			'className' => 'Student',
			'foreignKey' => 'student_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		)
	);
}
