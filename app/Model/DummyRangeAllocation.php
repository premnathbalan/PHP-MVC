<?php
App::uses('AppModel', 'Model');
/**
 * DummyNumber Model
 *
 */
class DummyRangeAllocation extends AppModel {

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
		'Timetable' => array(
			'className' => 'Timetable',
			'foreignKey' => 'timetable_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		),
		'DummyNumber' => array(
			'className' => 'DummyNumber',
			'foreignKey' => 'dummy_number_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		)
	);
	/*
	public $hasMany = array(
		'DummyNumberAllocation' => array(
			'className' => 'DummyNumberAllocation',
			'foreignKey' => 'dummy_number_id',
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
		'DummyMark' => array(
			'className' => 'DummyMark',
			'foreignKey' => 'dummy_number_id',
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
		'DummyFinalMark' => array(
			'className' => 'DummyFinalMark',
			'foreignKey' => 'dummy_number_id',
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
	);*/
	

}
