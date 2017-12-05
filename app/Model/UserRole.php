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
	);
}