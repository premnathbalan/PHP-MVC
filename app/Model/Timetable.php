<?php
App::uses('AppModel', 'Model');
/**
 * Timetable Model
 *
 */
class Timetable extends AppModel {

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
		'id' => array(
			'notBlank' => array(
				'rule' => array('notBlank'),
			),
		),
	);
	
	public $belongsTo = array(
		'MonthYear' => array(
			'className' => 'MonthYear',
			'foreignKey' => 'month_year_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		),
		'CourseMapping' => array(			
				'className' => 'CourseMapping',			
				'foreignKey' => 'course_mapping_id',			
				'dependent' => false,			
				'conditions' => '',			
				'fields' => '',			
				'order' => '',			
				'limit' => '',			
				'offset' => '',			
				'exclusive' => '',			
				'finderQuery' => '',			
				'counterQuery' => ''			
		),
			
	);	

	public $hasMany = array(
		'ExamAttendance' => array(
			'className' => 'ExamAttendance',
			'foreignKey' => 'timetable_id',
			'dependent' => false,
			'conditions' => '',
			'fields' => '',
			'order' => '',
			'limit' => '',
			'offset' => '',
			'exclusive' => '',
			'finderQuery' => '',
			'counterQuery' => ''
		),
		'DummyRangeAllocation' => array(
				'className' => 'DummyRangeAllocation',
				'foreignKey' => 'timetable_id',
				'dependent' => false,
				'conditions' => '',
				'fields' => '',
				'order' => '',
				'limit' => '',
				'offset' => '',
				'exclusive' => '',
				'finderQuery' => '',
				'counterQuery' => ''
		)
	);
	
}
