<?php
App::uses('AppModel', 'Model');
/**
 * ContinuousAssessmentExam Model
 *
 * @property Course $Course
 * @property Student $Student
 */
class ContinuousAssessmentExam extends AppModel {
/**
 * Display field
 *
 * @var string
 */
	//public $displayField = 'course_id';


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
		'Cae' => array(
			'className' => 'Cae',
			'foreignKey' => 'cae_id',
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

	
	public $validate = array(
	    'marks' => array(
	        'rule' => '^(?:[0-9]{1}|[0-9]{2}|A| )$^',
	        'message' => 'Only letters and integers'
	    )
	);
	
	public function validateMarks () {
		if ($this->data['CAE']['marks']=="AAA") {
			$errors[] = "Please enter your RN Number.";
		}
	
		if (!empty($errors))
			return implode("\n", $errors);
	
			return true;
	}
	
	 	
}