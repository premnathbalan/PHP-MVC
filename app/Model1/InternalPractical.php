<?php
App::uses('AppModel', 'Model');
/**
 * InternalPractical Model
 *
 * @property Student $Student
 * @property CaePractical $CaePractical
 */
class InternalPractical extends AppModel {

/**
 * Display field
 *
 * @var string
 */
	//public $displayField = 'cae_practical_id';


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
		'CaePractical' => array(
			'className' => 'CaePractical',
			'foreignKey' => 'cae_practical_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		)
	);
}
