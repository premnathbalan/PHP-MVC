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
		),			'Section' => array(								'className' => 'Section',								'foreignKey' => 'section_id',								'conditions' => '',								'fields' => '',								'order' => ''						),			'User' => array(								'className' => 'User',								'foreignKey' => 'created_by',								'conditions' => '',								'fields' => '',								'order' => ''						),						'ModifiedUser' => array(								'className' => 'ModifiedUser',								'foreignKey' => 'modified_by',								'conditions' => '',								'fields' => '',								'order' => ''						),			'ParentGroup' => array(					'className' => 'ParentGroup',					'foreignKey' => 'parent_id'			),				'UniversityReference' => array(					'className' => 'UniversityReference',					'foreignKey' => 'university_references_id',					'conditions' => '',					'fields' => '',					'order' => ''			),
	);

	
/**
 * hasMany associations
 *
 * @var array
 */
	public $hasMany = array(			'ChildGroup' => array(					'className' => 'Student',					'foreignKey' => 'parent_id'			),			'TransferStudent' => array(								'className' => 'TransferStudent',								'foreignKey' => 'student_id',								'dependent' => false,								'conditions' => '',								'fields' => '',								'order' => '',								'limit' => '',								'offset' => '',								'exclusive' => '',								'finderQuery' => '',								'counterQuery' => ''						),
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
			'className' => 'ExamAttendance',			'foreignKey' => 'student_id',			'dependent' => false,			'conditions' => '',			'fields' => '',			'order' => '',			'limit' => '',			'offset' => '',			'exclusive' => '',			'finderQuery' => '',			'counterQuery' => ''		),		'Attendance' => array(			'className' => 'Attendance',			'foreignKey' => 'student_id',			'dependent' => false,			'conditions' => '',			'fields' => '',			'order' => '',			'limit' => '',			'offset' => '',			'exclusive' => '',			'finderQuery' => '',			'counterQuery' => ''		),		'InternalExam' => array(			'className' => 'InternalExam',			'foreignKey' => 'student_id',			'dependent' => false,			'conditions' => '',			'fields' => '',			'order' => '',			'limit' => '',			'offset' => '',			'exclusive' => '',			'finderQuery' => '',			'counterQuery' => ''		),		'InternalPractical' => array(			'className' => 'InternalPractical',			'foreignKey' => 'student_id',			'dependent' => false,			'conditions' => '',			'fields' => '',			'order' => '',			'limit' => '',			'offset' => '',			'exclusive' => '',			'finderQuery' => '',			'counterQuery' => ''		),		'Practical' => array(			'className' => 'Practical',			'foreignKey' => 'student_id',			'dependent' => false,			'conditions' => '',			'fields' => '',			'order' => '',			'limit' => '',			'offset' => '',			'exclusive' => '',			'finderQuery' => '',			'counterQuery' => ''		),		'InternalProject' => array(						'className' => 'InternalProject',						'foreignKey' => 'student_id',						'dependent' => false,						'conditions' => '',						'fields' => '',						'order' => '',						'limit' => '',						'offset' => '',						'exclusive' => '',						'finderQuery' => '',						'counterQuery' => ''					),		'StudentAuthorizedBreak' => array(			'className' => 'StudentAuthorizedBreak',			'foreignKey' => 'student_id',			'dependent' => false,			'conditions' => '',			'fields' => '',			'order' => '',			'limit' => '',			'offset' => '',			'exclusive' => '',			'finderQuery' => '',			'counterQuery' => ''		) ,		'ProjectViva' => array(			'className' => 'ProjectViva',			'foreignKey' => 'student_id',			'dependent' => false,			'conditions' => '',			'fields' => '',			'order' => '',			'limit' => '',			'offset' => '',			'exclusive' => '',			'finderQuery' => '',			'counterQuery' => ''		),
		'ProjectReview' => array(	      'className' => 'ProjectReview',	      'foreignKey' => 'student_id',	      'dependent' => false,	      'conditions' => '',	      'fields' => '',	      'order' => '',	      'limit' => '',	      'offset' => '',	      'exclusive' => '',	      'finderQuery' => '',	      'counterQuery' => ''	    ),		'ProjectViva' => array(							'className' => 'ProjectViva',								'foreignKey' => 'student_id',								'dependent' => false,								'conditions' => '',								'fields' => '',								'order' => '',								'limit' => '',								'offset' => '',								'exclusive' => '',								'finderQuery' => '',								'counterQuery' => ''						),
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
		),			'GrossAttendance' => array(											'className' => 'GrossAttendance',											'foreignKey' => 'student_id',											'dependent' => false,											'conditions' => '',											'fields' => '',											'order' => '',											'limit' => '',											'offset' => '',											'exclusive' => '',											'finderQuery' => '',											'counterQuery' => ''					),					'StudentWithheld' => array(				'className' => 'StudentWithheld',				'foreignKey' => 'student_id',				'dependent' => false,				'conditions' => '',				'fields' => '',				'order' => '',				'limit' => '',				'offset' => '',				'exclusive' => '',				'finderQuery' => '',				'counterQuery' => ''		),		'ContinuousAssessmentExam' => array('className' =>'ContinuousAssessmentExam', 'foreignKey' => 'student_id',			'dependent' => false,      			'conditions' => '',  			'fields' => '',      			'order' => '',      			'limit' => '',      			'offset' => '',      			'exclusive' => '',      			'finderQuery' => '',      			'counterQuery' => ''		),		'PracticalAttendance' => array(				'className' => 'PracticalAttendance',				'foreignKey' => 'student_id',				'dependent' => false,				'conditions' => '',				'fields' => '',				'order' => '',				'limit' => '',				'offset' => '',				'exclusive' => '',				'finderQuery' => '',				'counterQuery' => ''				),		'ProfessionalTraining' => array (				'className' => 'ProfessionalTraining',				'foreignKey' => 'cae_pt_id',				'dependent' => false,				'conditions' => '',				'fields' => '',				'order' => '',				'limit' => '',				'offset' => '',				'exclusive' => '',				'finderQuery' => '',				'counterQuery' => ''		),		'DegreeCertificate' => array (				'className' => 'DegreeCertificate',				'foreignKey' => 'student_id',				'dependent' => false,				'conditions' => '',				'fields' => '',				'order' => '',				'limit' => '',				'offset' => '',				'exclusive' => '',				'finderQuery' => '',				'counterQuery' => ''		),		'StudentAuditCourse' => array (				'className' => 'StudentAuditCourse',				'foreignKey' => 'student_id',				'dependent' => false,				'conditions' => '',				'fields' => '',				'order' => '',				'limit' => '',				'offset' => '',				'exclusive' => '',				'finderQuery' => '',				'counterQuery' => ''		),	);
	
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
					),'unique' => array(					   'rule' => 'isUnique',							   'allowEmpty' => false,									   'message'=>"Registration Number Already Exist!"							)
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
			),			'admission_date' => array(								'nonEmpty' => array(										'rule' => array('notBlank'),										'message' => 'Enter Admission Date',										'allowEmpty' => false								),						),
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
	);		public function getStudentsWithBatchAndProgram($batch_id, $program_id, $month_year_id) {		$conditions = array();		$conditions['Student.batch_id'] = $batch_id;		$conditions['Student.discontinued_status'] = 0;		if ($program_id > 0) {			$conditions['Student.program_id'] = $program_id;		}		$options = array(				'conditions' => $conditions,				'fields' => array('Student.id', 'Student.registration_number',						'Student.name', 'Student.month_year_id'				),				'recursive' => 0,       			'order'=>array('Student.registration_number'),				'contain'=>array(						'CourseStudentMapping'=>array(								'conditions'=>array('CourseStudentMapping.new_semester_id !='=>$month_year_id),								'fields'=>array('CourseStudentMapping.course_mapping_id', 'CourseStudentMapping.type',									'CourseStudentMapping.new_semester_id', 'CourseStudentMapping.indicator'
								),								'CourseMapping'=>array(									'fields'=>array('CourseMapping.id', 'CourseMapping.month_year_id')
								)						)				)		);		$students = $this->find("all", $options);		return $students;	}		public function studentDetails($student_id) {		$options = array(				'conditions' => array('Student.id' => $student_id),				'fields' => array('Student.id', 'Student.registration_number', 'Student.name', 'Student.month_year_id',								'Student.birth_date', 'Student.section_id', 'Student.batch_id', 'Student.program_id', 'Student.picture'),				'recursive' => 0,				'order'=>array('Student.registration_number'),				'contain'=>array(						'Academic' => array(							'fields' => array('Academic.short_code','Academic.academic_name'/* ,'Academic.academic_name_tamil' */)						),						'Batch' => array(							'fields' => array('Batch.batch_from', 'Batch.batch_to', 'Batch.academic'/*,'Batch.consolidated_pub_date' */)						),						'Program' => array('fields' => array('Program.program_name','Program.short_code'/* ,'Program.credits','Program.program_name_tamil' */),							'Academic' => array('fields' => array('Academic.academic_name', 'Academic.short_code')),						),						'Section' => array(								'fields'=>array('Section.name')
						)				)		);		$students = $this->find("all", $options);		return $students;	}		public function getStudentsInfo($batch_id, $program_id) {		$options = array(				'conditions' => array(						array('Student.batch_id' => $batch_id,								'Student.program_id' => $program_id,								'Student.discontinued_status' => 0,						)				),				'fields' => array('Student.id', 'Student.registration_number',						'Student.name', 'Student.student_type_id', 'Student.month_year_id', 'Student.roll_number'				),				'recursive' => 0,				'order'=>array('Student.registration_number')		);		$students = $this->find("all", $options);		$studentArray = array();		foreach ($students as $key => $info) {			$studentArray[$info['Student']['id']] = array(					'reg_num' => $info['Student']['registration_number'],					'roll_number' => $info['Student']['roll_number'],					'name' => $info['Student']['name'],					'type' => $info['Student']['student_type_id'],					'month_year_id' => $info['Student']['month_year_id']			);		}		return $studentArray;	}		public function getStudentId($registration_number) {		$options = array(			'conditions' => array(				array('Student.registration_number' => $registration_number,						'Student.discontinued_status' => 0				)			),			'fields' => array('Student.id'),			'recursive' => 0		);		$students = $this->find("all", $options);		if(isset($students['0']['Student']['id'])){			return $students['0']['Student']['id'];		}	}		public function getStudentsInfoWithBatchId($batch_id) {		$options = array(				'conditions' => array(						array('Student.batch_id' => $batch_id,								'Student.discontinued_status' => 0						)				),				'fields' => array('Student.id', 'Student.registration_number',						'Student.name'				),				'recursive' => 0		);		$students = $this->find("all", $options);		$studentArray = array();		foreach ($students as $key => $info) {			$studentArray[$info['Student']['id']] = array(					'reg_num' => $info['Student']['registration_number'],					'name' => $info['Student']['name'],			);		}		return $studentArray;	}		public function getBatchAndProgramIdFromStudentRegNo($regNo) {		$options = array(				'conditions' => array(						array('Student.registration_number' => $regNo,								'Student.discontinued_status' => 0						)				),				'fields' => array('Student.id', 'Student.registration_number',						'Student.name', 'Student.batch_id', 'Student.program_id'				),				'recursive' => 0		);		$students = $this->find("all", $options);		//pr($students);		$studentArray = array();		foreach ($students as $key => $info) {			$studentArray[$regNo] = array(					'batch_id' => $info['Student']['batch_id'],					'program_id' => $info['Student']['program_id'],					'id' => $info['Student']['id'],			);		}		return $studentArray;	}		public function getActiveStudents($batch_id, $program_id, $id) {		$cond = array();		$cond['Student.batch_id'] = $batch_id;		$cond['Student.program_id'] = $program_id;		$cond['Student.discontinued_status'] = 0;				if ($id>0 && $id!='-') $cond['Student.id'] = $id;				$results = $this->find('all', array(				'conditions' => array($cond),				'fields' => array('Student.id','Student.registration_number','Student.name', 'Student.picture'),				'recursive'=>0		));		return $results;	}		public function getCount($batch_id, $program_id) {		$results = $this->find('count', array(					'conditions' => array('Student.batch_id' => $batch_id, 'Student.program_id'=>$program_id, 'Student.discontinued_status' => 0),					'fields' => array('Student.id'),		));		return $results;	}		public function enrolledCoursesForABatchAndProgram($batchId, $programId) {		$csmCond = array();		if($batchId != '-'){			$csmCond['Student.batch_id'] = $batchId;		}		if($programId != '-'){			$csmCond['Student.program_id'] = $programId;		}		//$stuCond['Student.month_year_id <='] = $examMonth;			$res1 = array(				'conditions' => array($csmCond,				),				'fields' =>array('Student.id','Student.parent_id','Student.university_references_id'),				'contain'=>array(						'CourseStudentMapping'=>array(								'fields'=>array('CourseStudentMapping.course_mapping_id',										'CourseStudentMapping.indicator',								),						),						'ParentGroup' => array(								'CourseStudentMapping'=>array(										'fields'=>array('CourseStudentMapping.course_mapping_id',												'CourseStudentMapping.indicator',										),								),						)				));		$csmResult = $this->find('all', $res1);		//pr($csmResult);		$csmArray = array();		foreach ($csmResult as $key => $csm) {			$innerArray = $csm['CourseStudentMapping'];			$student_id = $csm['Student']['id'];			$csmArray=$this->cm($innerArray, $csmArray, $student_id);			if(isset($csm['Student']['parent_id'])) {				$parent_id = $csm['Student']['parent_id'];				$pInnerArray = $csm['ParentGroup']['CourseStudentMapping'];				$csmArray=$this->cm($pInnerArray, $csmArray, $student_id);			}			}		pr($csmArray);		//$this->set('csmArray', $csmArray);	}		private function cm($innerArray, $csmArray, $student_id) {		//pr($innerArray);		foreach ($innerArray as $k => $v) {			$indicator = $v['indicator'];			if ($v['indicator'] == 0) {				$csmArray[$student_id][$v['course_mapping_id']]=$indicator;			}		}		return $csmArray;	}		public $hasAndBelongsToMany = array(		'CourseMapping' => array(			'className' => 'CourseMapping',			'joinTable' => 'course_student_mappings',			'foreignKey' => 'student_id',			'associationForeignKey' => 'course_mapping_id',			'unique' => 'keepExisting',			'conditions' => '',			'fields' => '',			'order' => '',			'limit' => '',			'offset' => '',			'finderQuery' => '',		)	);
}
