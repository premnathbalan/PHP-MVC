<?php
App::uses('AppModel', 'Model');
/**
 * RevaluationExam Model
 *
 * @property CourseMapping $CourseMapping
 * @property Student $Student
 * @property MonthYear $MonthYear
 * @property DummyNumber $DummyNumber
 */
class RevaluationExam extends AppModel {

/**
 * Display field
 *
 * @var string
 */
	public $displayField = 'id';


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
		),
		'Revaluation' => array(
				'className' => 'Revaluation',
				'foreignKey' => 'revaluation_id',
				'conditions' => '',
				'fields' => '',
				'order' => ''
		)
	);
	public $hasMany = array(
			
	);
}
