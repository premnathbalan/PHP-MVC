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
class Attendance extends AppModel {

/**
 * Use table
 *
 * @var mixed False or table name
 */
	public $useTable = 'attendances';

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
				//'message' => 'Your custom message here',
				//'allowEmpty' => false,
				//'required' => false,
				//'last' => false, // Stop validation after this rule
				//'on' => 'create', // Limit validation to 'create' or 'update' operations
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
