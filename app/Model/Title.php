<?php
App::uses('AppModel', 'Model');
/**
 * Thesi Model
 *
 * @property PhdStudent $PhdStudent
 */
class Title extends AppModel {

/**
 * Display field
 *
 * @var string
 */
	public $displayField = 'id';


	// The Associations below have been created with all possible keys, those that are not needed can be removed

/**
 * hasMany associations
 *
 * @var array
 */
	public $hasMany = array(
		'PhdStudent' => array(
			'className' => 'PhdStudent',
			'foreignKey' => 'title_id',
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

	public $belongsTo = array(
	
			'User' => array(
	
					'className' => 'User',
	
					'foreignKey' => 'created_by',
	
					'conditions' => '',
	
					'fields' => '',
	
					'order' => ''
	
			),
	
			'ModifiedUser' => array(
	
					'className' => 'ModifiedUser',
	
					'foreignKey' => 'modified_by',
	
					'conditions' => '',
	
					'fields' => '',
	
					'order' => ''
	
			)
	
	);
	
}
