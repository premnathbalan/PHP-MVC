<?php
App::uses('AppModel', 'Model');
/**
 * UsersPath Model
 *
 */
class UsersPath extends AppModel {

/**
 * Display field
 *
 * @var string
 */
	public $displayField = 'user_id';

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
	);
	
	public $belongsTo = array(
			'User' => array(
					'className' => 'User',
					'foreignKey' => 'user_id',
					'conditions' => '',
					'fields' => '',
					'order' => ''
			),
			'Path' => array(
					'className' => 'Path',
					'foreignKey' => 'path_id',
					'conditions' => '',
					'fields' => '',
					'order' => ''
			)
	);
}
