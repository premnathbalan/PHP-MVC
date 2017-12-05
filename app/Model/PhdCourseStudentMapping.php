<?php
App::uses('AppModel', 'Model');
/**
 * PhdCourseStudentMapping Model
 *
 * @property PhdStudent $PhdStudent
 * @property PhdCourse $PhdCourse
 */
class PhdCourseStudentMapping extends AppModel {

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
		'PhdStudent' => array(
			'className' => 'PhdStudent',
			'foreignKey' => 'phd_student_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		),
		'PhdCourse' => array(
			'className' => 'PhdCourse',
			'foreignKey' => 'phd_course_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		),
		'User' => array(
				'className' => 'User',
				'foreignKey' => 'created_by',
				'conditions' => '',
				'fields' => '',
				'order' => ''
		),
		'ModifiedUser' => array(
				'className' => 'ModifiedUser',
				'foreignKey' => 'modified_by',
				'conditions' => '',
				'fields' => '',
				'order' => ''
		)
	);
	
	public $hasMany = array(
			'PhdStudentMark' => array(
					'className' => 'PhdStudentMark',
					'foreignKey' => 'phd_course_student_mapping_id',
					'conditions' => '',
					'fields' => '',
					'order' => ''
			),
	);
	
}
