<?php
App::uses('AppModel', 'Model');
/**
 * UserRole Model
 *
 */
class UserRole extends AppModel {

/**
 * Display field
 *
 * @var string
 */
	public $displayField = 'user_role';

/**
 * Validation rules
 *
 * @var array
 */
	public $validate = array(
		'user_role' => array(
			'notBlank' => array(
				'rule' => array('notBlank'),
			),
		),
	);		public $hasAndBelongsToMany = array(			'Path' => array(					'className' => 'Path',					'joinTable' => 'user_roles_paths',					'foreignKey' => 'user_role_id',					'associationForeignKey' => 'path_id',					'unique' => 'keepExisting',					'conditions' => '',					'fields' => '',					'order' => '',					'limit' => '',					'offset' => '',					'finderQuery' => '',			)	);
}
