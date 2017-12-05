<?php
App::uses('AppModel', 'Model');
/**
 * StudentAuditCourse Model
 *
 * @property Student $Student
 * @property AuditCourse $AuditCourse
 */
class StudentAuditCourse extends AppModel {

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
		'Student' => array(
			'className' => 'Student',
			'foreignKey' => 'student_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		),
		'AuditCourse' => array(
			'className' => 'AuditCourse',
			'foreignKey' => 'audit_course_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		)
	);
}
