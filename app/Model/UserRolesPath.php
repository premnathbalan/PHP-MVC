<?php
App::uses('AppModel', 'Model');
/**
 * UserRolesPath Model
 *
 */
class UserRolesPath extends AppModel {

/**
 * Display field
 *
 * @var string
 */
	public $displayField = 'id';

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
}
