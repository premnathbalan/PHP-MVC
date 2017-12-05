<?php
App::uses('AppModel', 'Model');
/**
 * PracticalAttendance Model
 *
 * @property MonthYear $MonthYear
 * @property Student $Student
 * @property CourseMapping $CourseMapping
 */
class PracticalAttendance extends AppModel {

/**
 * Display field
 *
 * @var string
 */
	public $displayField = 'student_id';


	// The Associations below have been created with all possible keys, those that are not needed can be removed

/**
 * belongsTo associations
 *
 * @var array
 */
	public $belongsTo = array(
		'MonthYear' => array(
			'className' => 'MonthYear',
			'foreignKey' => 'month_year_id',
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
		),
		'CourseMapping' => array(
			'className' => 'CourseMapping',
			'foreignKey' => 'course_mapping_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		)
	);
}
