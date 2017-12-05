<?php
App::uses('AppModel', 'Model');
/**
 * PublishStatus Model
 *
 * @property Batch $Batch
 * @property MonthYear $MonthYear
 */
class PublishStatus extends AppModel {

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
		'Batch' => array(
			'className' => 'Batch',
			'foreignKey' => 'batch_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		),
		'MonthYear' => array(
			'className' => 'MonthYear',
			'foreignKey' => 'month_year_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		)
	);
}
