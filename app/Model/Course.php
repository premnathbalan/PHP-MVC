<?php
App::uses('AppModel', 'Model');
/**
 * Course Model
 *
 * @property Semester $Semester
 * @property CourseType $CourseType
 * @property CourseMode $CourseMode
 * @property Program $Program
 * @property ContinuousAssessmentExam $ContinuousAssessmentExam
 * @property CourseStudentMapping $CourseStudentMapping
 * @property DummyNumberAllocation $DummyNumberAllocation
 * @property DummyNumber $DummyNumber
 * @property EndSemesterExam $EndSemesterExam
 * @property ExamAttendance $ExamAttendance
 * @property Experiment $Experiment
 * @property InternalsExam $InternalsExam
 * @property ModelExam $ModelExam
 * @property PacketNumber $PacketNumber
 * @property Practical $Practical
 * @property ProjectReview $ProjectReview
 * @property ProjectViva $ProjectViva
 * @property ProjectWork $ProjectWork
 * @property StudentAb $StudentAb
 * @property StudentAttendance $StudentAttendance
 * @property StudentCredit $StudentCredit
 * @property StudentMalpractice $StudentMalpractice
 * @property StudentMark $StudentMark
 * @property StudentWithdrawal $StudentWithdrawal
 */
class Course extends AppModel {

/**
 * Display field
 *
 * @var string
 */
	public $displayField = 'course_name';
	
	public $virtualFields = array(

			'course_info' => 'CONCAT(Course.course_code, " - ", Course.course_name)'
			
	);

	// The Associations below have been created with all possible keys, those that are not needed can be removed

/**
 * belongsTo associations
 *
 * @var array
 */
	public $belongsTo = array(
		'CourseType' => array(
			'className' => 'CourseType',
			'foreignKey' => 'course_type_id',
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

/**
 * hasMany associations
 *
 * @var array
 */
	public $hasMany = array(
		'CourseMapping' => array(
			'className' => 'CourseMapping',
			'foreignKey' => 'course_id',
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
	);
	
	public $validate = array(
			'course_name' => array(
					'nonEmpty' => array(
							'rule' => array('notBlank'),
							'message' => 'Choose a name',
							'allowEmpty' => false
					),
			),
			'course_code' => array(
					'nonEmpty' => array(
							'rule' => array('notBlank'),
							'message' => 'Choose a name',
							'allowEmpty' => false
					),
			),
			'common_code' => array(
					'nonEmpty' => array(
							'rule' => array('notBlank'),
							'message' => 'Choose a name',
							'allowEmpty' => false
					),
			),
			'semester_id' => array(
					'nonEmpty' => array(
							'rule' => array('notBlank'),
							'message' => 'Choose a name',
							'allowEmpty' => false
					),
			),
			'board' => array(
					'nonEmpty' => array(
							'rule' => array('notBlank'),
							'message' => 'Choose a name',
							'allowEmpty' => false
					),
			),
			'course_type_id' => array(
					'nonEmpty' => array(
							'rule' => array('notBlank'),
							'message' => 'Choose a name',
							'allowEmpty' => false
					),
			),			
			'credit_point' => array(
					'nonEmpty' => array(
							'rule' => array('notBlank'),
							'message' => 'Choose a name',
							'allowEmpty' => false
					),
			),
	);
	
}
