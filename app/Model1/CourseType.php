<?php
App::uses('AppModel', 'Model');
/**
 * CourseType Model
 *
 * @property Course $Course
 */
class CourseType extends AppModel {

/**
 * Display field
 *
 * @var string
 */
	public $displayField = 'course_type';

		
/**
 * hasMany associations
 *
 * @var array
 */
	public $hasMany = array(
		'Course' => array(
			'className' => 'Course',
			'foreignKey' => 'course_type_id',
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
	public function checkAvailability($data){
}