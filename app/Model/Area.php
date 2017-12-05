<?php
App::uses('AppModel', 'Model');
/**
 * Area Model
 *
 * @property PhdStudent $PhdStudent
 */
class Area extends AppModel {

/**
 * Display field
 *
 * @var string
 */
	public $displayField = 'name';


	// The Associations below have been created with all possible keys, those that are not needed can be removed

/**
 * hasMany associations
 *
 * @var array
 */
	public $hasMany = array(
		'PhdStudent' => array(
			'className' => 'PhdStudent',
			'foreignKey' => 'area_id',
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
