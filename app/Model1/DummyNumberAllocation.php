<?php
App::uses('AppModel', 'Model');
/**
 * DummyNumberAllocation Model
 *
 */
class DummyNumberAllocation extends AppModel {

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
	public $validate = array(
		'id' => array(
			'notBlank' => array(
				'rule' => array('notBlank'),
			),
		),
	);
	
	public $belongsTo = array(
		'DummyNumber' => array(
			'className' => 'DummyNumber',
			'foreignKey' => 'dummy_number_id',
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
