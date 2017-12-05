<?php
App::uses('AppModel', 'Model');
/**
 * Path Model
 *
 */
class Path extends AppModel {

/**
 * Display field
 *
 * @var string
 */
	public $displayField = 'name';

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
		'path' => array(
			'notEmpty' => array(
					'rule' => array('notEmpty'),
			),
		)
	);
	
	public $hasAndBelongsToMany = array(
		'User' => array(
			'className' => 'User',
			'joinTable' => 'users_paths',
			'foreignKey' => 'path_id',
			'associationForeignKey' => 'user_id',
			'unique' => 'keepExisting',
			'conditions' => '',
			'fields' => '',
			'order' => '',
			'limit' => '',
			'offset' => '',
			'finderQuery' => '',
		)
	);
}
