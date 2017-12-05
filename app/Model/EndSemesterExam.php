<?php
App::uses('AppModel', 'Model');
/**
 * EndSemesterExam Model
 *
 * @property CourseMapping $CourseMapping
 * @property Student $Student
 * @property MonthYear $MonthYear
 */
class EndSemesterExam extends AppModel {

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
		),
		'MonthYear' => array(
			'className' => 'MonthYear',
			'foreignKey' => 'month_year_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		),
		'DummyNumber' => array(
			'className' => 'DummyNumber',
			'foreignKey' => 'dummy_number_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		)
	);
	
	public $hasOne = array(
			'Revaluation' => array(
					'className' => 'Revaluation',
					'foreignKey' => 'ese_id',
					'dependent'    => true,
			)
	);
}
