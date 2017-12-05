<?php
App::uses('AppModel', 'Model');
/**
 * StudentType Model
 *
 * @property Student $Student
 */
class StudentType extends AppModel {

/**
 * Display field
 *
 * @var string
 */
	public $displayField = 'type';


	// The Associations below have been created with all possible keys, those that are not needed can be removed

/**
 * hasMany associations
 *
 * @var array
 */
	public $hasMany = array(
		'Student' => array(
			'className' => 'Student',
			'foreignKey' => 'student_type_id',
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
