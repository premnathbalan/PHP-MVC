<?php
App::uses('AppModel', 'Model');
/**
 * Practical Model
 *
 * @property Student $Student
 * @property EsePractical $EsePractical
 */
class Practical extends AppModel {

/**
 * Display field
 *
 * @var string
 */
	//public $displayField = 'ese_practical_id';


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
		'EsePractical' => array(
			'className' => 'EsePractical',
			'foreignKey' => 'ese_practical_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		)
	);
}
