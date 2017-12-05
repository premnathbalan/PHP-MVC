<?php
App::uses('AppModel', 'Model');
/**
 * Student Model
 *
 * @property Batch $Batch
 * @property Program $Program
 * @property Academic $Academic
 * @property Email $Email
 * @property ContinuousAssessmentExam $ContinuousAssessmentExam
 * @property CourseStudentMappingMgmt $CourseStudentMappingMgmt
 * @property DummyNumberAllocation $DummyNumberAllocation
 * @property EndSemesterExam $EndSemesterExam
 * @property ExamAttendance $ExamAttendance
 * @property Experiment $Experiment
 * @property GeneralAttendance $GeneralAttendance
 * @property InternalsExam $InternalsExam
 * @property ModelExam $ModelExam
 * @property Practical $Practical
 * @property ProjectReview $ProjectReview
 * @property ProjectViva $ProjectViva
 * @property ProjectWork $ProjectWork
 * @property StudentAb $StudentAb
 * @property StudentAttendance $StudentAttendance
 * @property StudentCredit $StudentCredit
 * @property StudentGrade $StudentGrade
 * @property StudentMalpractice $StudentMalpractice
 * @property StudentMark $StudentMark
 * @property StudentWithdrawal $StudentWithdrawal
 */
class Student extends AppModel {

/**
 * Display field
 *
 * @var string
 */
	public $displayField = 'name';


	// The Associations below have been created with all possible keys, those that are not needed can be removed

	/* public $hasAndBelongsToMany = array(
			'ContinuousAssessmentExam' => array(
					'className' => 'ContinuousAssessmentExam',
					'joinTable' => 'Cae',
					'foreignKey' => 'id',
					'associationForeignKey' => 'id',
					'unique' => true
			),
	);	 */
	
/**
 * belongsTo associations
 *
 * @var array
 */
	public $belongsTo = array(
		'Batch' => array(
			'className' => 'Batch',
			'foreignKey' => 'batch_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		),
		'Program' => array(
			'className' => 'Program',
			'foreignKey' => 'program_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		),
		'Academic' => array(
			'className' => 'Academic',
			'foreignKey' => 'academic_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		),
		'StudentType' => array(
			'className' => 'StudentType',
			'foreignKey' => 'student_type_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		),
	);

	
/**
 * hasMany associations
 *
 * @var array
 */
	public $hasMany = array(
		'RevaluationExam' => array(
			'className' => 'RevaluationExam',
			'foreignKey' => 'student_id',
			'dependent' => false,
			'conditions' => '',
			'fields' => '',
			'order' => '',
			'limit' => '',
			'offset' => '',
			'exclusive' => '',
			'finderQuery' => '',
			'counterQuery' => ''
		), 
		'CourseStudentMapping' => array(
			'className' => 'CourseStudentMapping',
			'foreignKey' => 'student_id',
			'dependent' => false,
			'conditions' => '',
			'fields' => '',
			'order' => '',
			'limit' => '',
			'offset' => '',
			'exclusive' => '',
			'finderQuery' => '',
			'counterQuery' => ''
		),
		'DummyNumberAllocation' => array(
			'className' => 'DummyNumberAllocation',
			'foreignKey' => 'student_id',
			'dependent' => false,
			'conditions' => '',
			'fields' => '',
			'order' => '',
			'limit' => '',
			'offset' => '',
			'exclusive' => '',
			'finderQuery' => '',
			'counterQuery' => ''
		),
		'EndSemesterExam' => array(
			'className' => 'EndSemesterExam',
			'foreignKey' => 'student_id',
			'dependent' => false,
			'conditions' => '',
			'fields' => '',
			'order' => '',
			'limit' => '',
			'offset' => '',
			'exclusive' => '',
			'finderQuery' => '',
			'counterQuery' => ''
		),
		'ExamAttendance' => array(
			'className' => 'ExamAttendance',
		'ProjectReview' => array(
		'StudentMalpractice' => array(
			'className' => 'StudentMalpractice',
			'foreignKey' => 'student_id',
			'dependent' => false,
			'conditions' => '',
			'fields' => '',
			'order' => '',
			'limit' => '',
			'offset' => '',
			'exclusive' => '',
			'finderQuery' => '',
			'counterQuery' => ''
		),
		'StudentMark' => array(
			'className' => 'StudentMark',
			'foreignKey' => 'student_id',
			'dependent' => false,
			'conditions' => '',
			'fields' => '',
			'order' => '',
			'limit' => '',
			'offset' => '',
			'exclusive' => '',
			'finderQuery' => '',
			'counterQuery' => ''
		),
		'StudentWithdrawal' => array(
			'className' => 'StudentWithdrawal',
			'foreignKey' => 'student_id',
			'dependent' => false,
			'conditions' => '',
			'fields' => '',
			'order' => '',
			'limit' => '',
			'offset' => '',
			'exclusive' => '',
			'finderQuery' => '',
			'counterQuery' => ''
		),
	
/* 	function test($data) {
		if($this->data['Student']['student_type_id'] == 3) {
		
			if($this->data['Student']['university_references_id']){
				return false;
			}
			if($this->data['Student']['semester_id']){
				return false;
			}
		}
		
	}*/
	public $validate = array(
			'student_type_id' => array(
					'nonEmpty' => array(
							'rule' => array('notBlank'),
							'message' => 'Choose student type',
							'allowEmpty' => false
					),
			),
			'registration_number' => array(
					'nonEmpty' => array(
							'rule' => array('notBlank'),
							'message' => 'Enter Student Registration Number',
							'allowEmpty' => false
					),'unique' => array(
			),
			'roll_number' => array(
					'nonEmpty' => array(
							'rule' => array('notBlank'),
							'message' => 'Enter student roll number',
							'allowEmpty' => false
					)
			),
			'name' => array(
					'nonEmpty' => array(
							'rule' => array('notBlank'),
							'message' => 'Enter student name',
							'allowEmpty' => false
					),
			),	
			'tamil_name' => array(
					'nonEmpty' => array(
							'rule' => array('notBlank'),
							'message' => 'Enter student name in tamil',
							'allowEmpty' => false
					),
			),
			'tamil_initial' => array(
					'nonEmpty' => array(
							'rule' => array('notBlank'),
							'message' => 'Enter initial in tamil',
							'allowEmpty' => false
					),
			),
			'father_name' => array(
					'nonEmpty' => array(
							'rule' => array('notBlank'),
							'message' => 'Enter father\'s name',
							'allowEmpty' => false
					),
			),
			'mother_name' => array(
					'nonEmpty' => array(
							'rule' => array('notBlank'),
							'message' => 'Enter father\'s name',
							'allowEmpty' => false
					),
			),
			'batch_id' => array(
					'nonEmpty' => array(
							'rule' => array('notBlank'),
							'message' => 'Choose a batch',
							'allowEmpty' => false
					),
			),
			'academic_id' => array(
					'nonEmpty' => array(
							'rule' => array('notBlank'),
							'message' => 'Choose an academic',
							'allowEmpty' => false
					),
			),
			'program_id' => array(
					'nonEmpty' => array(
							'rule' => array('notBlank'),
							'message' => 'Choose a program',
							'allowEmpty' => false
					),
			),
			'program_type' => array(
					'nonEmpty' => array(
							'rule' => array('notBlank'),
							'message' => 'Choose program type',
							'allowEmpty' => false
					),
			),
			'birth_date' => array(
					'nonEmpty' => array(
							'rule' => array('notBlank'),
							'message' => 'Choose Date of Birth',
							'allowEmpty' => false
					),
			),
			'gender' => array(
					'nonEmpty' => array(
							'rule' => array('notBlank'),
							'message' => 'Choose a gender',
							'allowEmpty' => false
					),
			),
			'nationality' => array(
					'nonEmpty' => array(
							'rule' => array('notBlank'),
							'message' => 'Enter nationality',
							'allowEmpty' => false
					),
			),
			'community' => array(
					'nonEmpty' => array(
							'rule' => array('notBlank'),
							'message' => 'Enter Community',
							'allowEmpty' => false
					),
			),
			'address' => array(
					'nonEmpty' => array(
							'rule' => array('notBlank'),
							'message' => 'Enter address',
							'allowEmpty' => false
					),
			),
			'city' => array(
					'nonEmpty' => array(
							'rule' => array('notBlank'),
							'message' => 'Enter City',
							'allowEmpty' => false
					),
			),
			'stat' => array(
					'nonEmpty' => array(
							'rule' => array('notBlank'),
							'message' => 'Enter State',
							'allowEmpty' => false
					),
			),
			'country' => array(
					'nonEmpty' => array(
							'rule' => array('notBlank'),
							'message' => 'Enter Country',
							'allowEmpty' => false
					),
			),
			'pincode' => array(
					'nonEmpty' => array(
							'rule' => array('notBlank'),
							'message' => 'Enter Pincode',
							'allowEmpty' => false
					),
			),
	);
								),
								)
						)
}