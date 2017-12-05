<?php
App::uses('AppModel', 'Model');
/**
 * PhdStudent Model
 *
 * @property Faculty $Faculty
 * @property Thesi $Thesi
 * @property Discipline $Discipline
 * @property Supervisor $Supervisor
 * @property Month $Month
 * @property MonthYear $MonthYear
 */
class PhdStudent extends AppModel {

/**
 * Display field
 *
 * @var string
 */
	public $displayField = 'name';


	// The Associations below have been created with all possible keys, those that are not needed can be removed

/**
 * belongsTo associations
 *
 * @var array
 */
	public $belongsTo = array(
		/* 'Faculty' => array(
			'className' => 'Faculty',
			'foreignKey' => 'faculty_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		),
		'Thesi' => array(
			'className' => 'Thesi',
			'foreignKey' => 'thesi_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		),
		'Discipline' => array(
			'className' => 'Discipline',
			'foreignKey' => 'discipline_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		),
		'Supervisor' => array(
			'className' => 'Supervisor',
			'foreignKey' => 'supervisor_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		), */
		'Month' => array(
			'className' => 'Month',
			'foreignKey' => 'month_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		),
		'MonthYear' => array(
			'className' => 'MonthYear',
			'foreignKey' => 'month_year_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		)
	);
}
