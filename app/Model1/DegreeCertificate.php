<?php
App::uses('AppModel', 'Model');
/**
 * DegreeCertificate Model
 *
 * @property Student $Student
 */
class DegreeCertificate extends AppModel {

/**
 * Display field
 *
 * @var string
 */
	public $displayField = 'student_id';


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
		)
	);
}
