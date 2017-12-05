<?php
App::uses('AppModel', 'Model');
/**
 * Month Model
 *
 * @property MonthYear $MonthYear
 */
class Month extends AppModel {

/**
 * Display field
 *
 * @var string
 */
	public $displayField = 'month_name';


	// The Associations below have been created with all possible keys, those that are not needed can be removed

/**
 * hasMany associations
 *
 * @var array
 */
	public $hasMany = array(
		'MonthYear' => array(
			'className' => 'MonthYear',
			'foreignKey' => 'month_id',
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
