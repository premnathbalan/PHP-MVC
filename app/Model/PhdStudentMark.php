<?php
App::uses('AppModel', 'Model');
/**
 * PhdStudentMark Model
 *
 * @property PhdCourseStudentMapping $PhdCourseStudentMapping
 * @property MonthYear $MonthYear
 */
class PhdStudentMark extends AppModel {

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
		'PhdCourseStudentMapping' => array(
			'className' => 'PhdCourseStudentMapping',
			'foreignKey' => 'phd_course_student_mapping_id',
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
