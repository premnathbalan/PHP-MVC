<?php
App::uses('AppModel', 'Model');
/**
 * DummyMark Model
 *
 * @property DummyNumber $DummyNumber
 */
class DummyMark extends AppModel {

	//public $displayField = 'dummy_number';
	// The Associations below have been created with all possible keys, those that are not needed can be removed

/**
 * belongsTo associations
 *
 * @var array
 */
	 public $belongsTo = array(
		'DummyNumber' => array(
			'className' => 'DummyNumber',
			'foreignKey' => 'dummy_number_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		)
	);
}
