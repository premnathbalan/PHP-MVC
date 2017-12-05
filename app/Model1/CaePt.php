<?php
App::uses('AppModel', 'Model');
/**
 * CaePt Model
 *
 * @property CourseMapping $CourseMapping
 * @property Lecturer $Lecturer
 * @property MonthYear $MonthYear
 */
class CaePt extends AppModel {

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
		),
		'MonthYear' => array(
			'className' => 'MonthYear',
			'foreignKey' => 'month_year_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		)
	);
	
	public $hasMany = array(
			'ProfessionalTraining' => array(
					'className' => 'ProfessionalTraining',
					'foreignKey' => 'cae_pt_id',
					'conditions' => '',
					'fields' => '',
					'order' => ''
			),
	);
}
