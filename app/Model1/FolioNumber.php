<?php
App::uses('AppModel', 'Model');
/**
 * FolioNumber Model
 *
 */
class FolioNumber extends AppModel {

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
		'dates' => array(
			'notBlank' => array(
				'rule' => array('notBlank'),
			),
		),
		'type_of_certification_id' => array(
			'notBlank' => array(
				'rule' => array('notBlank'),
			),
		),
		'serial_number' => array(
			'notBlank' => array(
				'rule' => array('notBlank'),
			),
		),
	);
}
