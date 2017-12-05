<?php
App::uses('AppModel', 'Model');
/**
 * Department Model
 *
 */
class CourseLecturer extends AppModel {

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
	
	public $belongsTo = array(
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
			'Lecturer' => array(
					'className' => 'Lecturer',
					'foreignKey' => 'lecturer_id',
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
}
