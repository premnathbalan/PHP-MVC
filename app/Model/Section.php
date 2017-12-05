<?php
App::uses('AppModel', 'Model');
/**
 * Section Model
 *
 */
class Section extends AppModel {

/**
 * Display field
 *
 * @var string
 */
	public $displayField = 'name';
	
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
	
	public $validate = array(
		'name' => array(
			'nonEmpty' => array(
					'rule' => array('notBlank'),
					'message' => 'Choose a batch mode',
					'allowEmpty' => false
			),

		),	
	);
	
}