<?php
App::uses('AppModel', 'Model');
/**
 * BatchMode Model
 *
 * @property Batch $Batch
 */
class BatchMode extends AppModel {

/**
 * Display field
 *
 * @var string
 */
	public $displayField = 'batch_mode';


	// The Associations below have been created with all possible keys, those that are not needed can be removed

/**
 * hasMany associations
 *
 * @var array
 */
	public $hasMany = array(
		'Batch' => array(
			'className' => 'Batch',
			'foreignKey' => 'batch_mode_id',
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
