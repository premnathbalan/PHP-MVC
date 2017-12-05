<?php
App::uses('AppModel', 'Model');
/**
 * ProfessionalTraining Model
 *
 * @property MonthYear $MonthYear
 * @property Student $Student
 * @property CourseMapping $CourseMapping
 */
class ProfessionalTraining extends AppModel {

/**
 * Display field
 *
 * @var string
 */
	public $displayField = 'id';

/**
 * Validation rules
 *
 * @var array
 */
	public $validate = array(
		'month_year_id' => array(
			'numeric' => array(
				'rule' => array('numeric'),
			),
		),
	);

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
		'CaePt' => array(
			'className' => 'CaePt',
			'foreignKey' => 'cae_pt_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		)
	);
}
