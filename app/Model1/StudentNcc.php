<?php
App::uses('AppModel', 'Model');

class StudentNcc extends AppModel {
	/**
	 * belongsTo associations
	 *
	 * @var array
	 */
	public $belongsTo = array(
		'NonCreditCourse' => array(
				'className' => 'NonCreditCourse',
				'foreignKey' => 'ncc_id',
				'dependent' => false,
				'conditions' => '',
				'fields' => '',
				'order' => ''
		),
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
