<?php
App::uses('AppModel', 'Model');
/**
 * RevaluationDummyMark Model
 *
 * @property DummyNumber $DummyNumber
 */
class RevaluationDummyMark extends AppModel {

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
		'DummyNumber' => array(
			'className' => 'DummyNumber',
			'foreignKey' => 'dummy_number_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		),
		'Revaluation' => array(
				'className' => 'Revaluation',
				'foreignKey' => 'revaluation_id',
				'conditions' => '',
				'fields' => '',
				'order' => ''
		),
			
	);
	
}
