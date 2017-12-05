<?php
App::uses('AppModel', 'Model');
/**
 * Withheld Model
 *
 */
class StudentWithheld extends AppModel {

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
	);		public $belongsTo = array(		'Student' => array(			'className' => 'Student',			'foreignKey' => 'student_id',			'conditions' => '',			'fields' => '',			'order' => ''		),		'Withheld' => array(			'className' => 'Withheld',			'foreignKey' => 'withheld_id',			'conditions' => '',			'fields' => '',			'order' => ''		),		'MonthYear' => array(				'className' => 'MonthYear',				'foreignKey' => 'month_year_id',				'conditions' => '',				'fields' => '',				'order' => ''		),	);	
}
