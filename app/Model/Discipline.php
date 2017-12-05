<?php
App::uses('AppModel', 'Model');
/**
 * Discipline Model
 *
 * @property PhdStudent $PhdStudent
 */
class Discipline extends AppModel {

/**
 * Display field
 *
 * @var string
 */
	public $displayField = 'id';

	public $validate = array(
	
			'discipline_name' => array(
	
					'nonEmpty' => array(
	
							'rule' => array('notBlank'),
	
							'message' => 'Discipline is required',
	
							'allowEmpty' => false
	
					),'unique' => array(
	
							'rule' => 'isUnique',
	
							'allowEmpty' => false,
	
							'message'=>"Discipline already exist!"
	
					)
	
			),
	
	);
	// The Associations below have been created with all possible keys, those that are not needed can be removed

/**
 * hasMany associations
 *
 * @var array
 */
	public $hasMany = array(
		'PhdStudent' => array(
			'className' => 'PhdStudent',
			'foreignKey' => 'discipline_id',
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
