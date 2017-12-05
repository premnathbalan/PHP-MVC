<?php
App::uses('AppModel', 'Model');

class StudentRemark extends AppModel {
	/**
	 * belongsTo associations
	 *
	 * @var array
	 */
	public $belongsTo = array(
		'Student' => array(
			'className' => 'Student',
			'foreignKey' => 'student_id',
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
