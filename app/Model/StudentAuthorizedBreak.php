<?php
App::uses('AppModel', 'Model');
/**
 * StudentAuthorizedBreak Model
 *
 * @property Student $Student
 * @property Semester $Semester
 */
class StudentAuthorizedBreak extends AppModel {

/**
 * Display field
 *
 * @var string
 */
	public $displayField = 'student_id';


	// The Associations below have been created with all possible keys, those that are not needed can be removed

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
		'Semester' => array(
			'className' => 'Semester',
			'foreignKey' => 'semester_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		)
	);
	
	public function findAbsStudents() {
		$results = $this->find('all', array(
			'fields'=>array('StudentAuthorizedBreak.id', 'StudentAuthorizedBreak.student_id', 
					'StudentAuthorizedBreak.new_month_year_id'),
			'contain'=>array(
				'Student'=>array(
					'fields'=>array('Student.batch_id', 'Student.program_id', 'Student.registration_number', 'Student.name'),
					'Batch'=>array(
						'fields'=>array('Batch.batch_from', 'Batch.batch_to', 'Batch.batch_duration')
					)
				)
			)
		));
		return $results;
	}
}
