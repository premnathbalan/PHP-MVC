<?php
App::uses('AppModel', 'Model');
/**
 * Attendance Model
 *
 * @property Student $Student
 * @property Batch $Batch
 * @property MonthYear $MonthYear
 * @property Program $Program
 * @property Course $Course
 */
class GrossAttendance extends AppModel {

/**
 * Use table
 *
 * @var mixed False or table name
 */
	public $useTable = 'gross_attendances';

/**
 * Display field
 *
 * @var string
 */
	public $displayField = 'student_id';
	
	

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
		'Program' => array(
			'className' => 'Program',
			'foreignKey' => 'program_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		)
	);
}
