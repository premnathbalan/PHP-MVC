<?php
App::uses('AppModel', 'Model');
/**
 * Signature Model
 *
 */
class Signature extends AppModel {

/**
 * Display field
 *
 * @var string
 */
	public $displayField = 'signature';

/**
 * Validation rules
 *
 * @var array
 */
	public $validate = array(
		'name' => array(
			'nonEmpty' => array(
				'rule' => array('notBlank'),
				'message' => 'Name is required',
				'allowEmpty' => false
			)
		),		'tamil_name' => array(						'nonEmpty' => array(								'rule' => array('notBlank'),								'message' => 'Tamil name is required',								'allowEmpty' => false						)				),		'role' => array(						'nonEmpty' => array(								'rule' => array('notBlank'),								'message' => 'Role is required',								'allowEmpty' => false						)				),		'role_tamil' => array(						'nonEmpty' => array(								'rule' => array('notBlank'),								'message' => 'Role Tamil is required',								'allowEmpty' => false						)				),
		'signature' => array(
			'nonEmpty' => array(
                'rule' => array('notBlank'),
                'message' => 'Image is required',				
                'allowEmpty' => false
            ),'unique' => array(
				'rule' => 'isUnique',
				'allowEmpty' => false,		
				'message'=>"Image already exist!"
			)
		),
	);
	
	/**
	 * belongsTo associations
	 *
	 * @var array
	 */	
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