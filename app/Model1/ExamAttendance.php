<?php
App::uses('AppModel', 'Model');
/**
 * ExamAttendance Model
 *
 * @property Timetable $Timetable
 * @property Student $Student
 * @property DummyNumberAllocation $DummyNumberAllocation
 */
class ExamAttendance extends AppModel {

/**
 * Display field
 *
 * @var string
 */
	public $displayField = 'timetable_id';


	// The Associations below have been created with all possible keys, those that are not needed can be removed

/**
 * belongsTo associations
 *
 * @var array
 */
	public $belongsTo = array(
		'Timetable' => array(
			'className' => 'Timetable',
			'foreignKey' => 'timetable_id',
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
		)
	);
	
	
}

