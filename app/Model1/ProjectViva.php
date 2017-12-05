<?php
App::uses('AppModel', 'Model');
/**
 * ProjectViva Model
 *
 * @property EseProject $EseProject
 * @property Student $Student
 * @property MonthYear $MonthYear
 */
class ProjectViva extends AppModel {

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
		'EseProject' => array(
			'className' => 'EseProject',
			'foreignKey' => 'ese_project_id',
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
		)
	);
}
