<?php
App::uses('AppModel', 'Model');
/**
 * Cae Model
 *
 * @property CourseMapping $CourseMapping
 * @property Semester $Semester
 * @property MonthYear $MonthYear
 */
class Cae extends AppModel {

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
		'Lecturer' => array(
			'className' => 'Lecturer',
			'foreignKey' => 'lecturer_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		)
	);
	
	public $hasMany = array(		
			'ContinuousAssessmentExam' => array(
					'className' => 'ContinuousAssessmentExam',
					'foreignKey' => 'cae_id',
					'conditions' => '',
					'fields' => '',
					'order' => ''
			),
	);

	
}
