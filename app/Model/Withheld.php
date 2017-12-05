<?php
App::uses('AppModel', 'Model');
/**
 * Withheld Model
 *
 */
class Withheld extends AppModel {

/**
 * Display field
 *
 * @var string
 */
	public $displayField = 'withheld_type';

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
	);	public $hasMany = array(		'StudentWithheld' => array(			'className' => 'StudentWithheld',			'foreignKey' => 'withheld_id',			'dependent' => false,			'conditions' => '',			'fields' => '',			'order' => '',			'limit' => '',			'offset' => '',			'exclusive' => '',			'finderQuery' => '',			'counterQuery' => ''		),	);	
}
