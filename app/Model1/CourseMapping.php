<?php
App::uses ( 'AppModel', 'Model' );
/**
 * CourseMapping Model
 *
 * @property Program $Program
 * @property Course $Course
 * @property CourseMode $CourseMode
 * @property Semester $Semester
 * @property $
 */
class CourseMapping extends AppModel {
	
	/**
	 * Display field
	 *
	 * @var string
	 */
	public $displayField = 'program_id';
	
	// The Associations below have been created with all possible keys, those that are not needed can be removed
	
	/**
	 * belongsTo associations
	 *
	 * @var array
	 */
	public $belongsTo = array (
			'Batch' => array (
					'className' => 'Batch',
					'foreignKey' => 'batch_id',
					'conditions' => '',
					'fields' => '',
					'order' => '' 
			),
			'Program' => array (
					'className' => 'Program',
					'foreignKey' => 'program_id',
					'conditions' => '',
					'fields' => '',
					'order' => '' 
			),
			'Course' => array (
					'className' => 'Course',
					'foreignKey' => 'course_id',
					'conditions' => '',
					'fields' => '',
					'order' => '' 
			),
			'CourseMode' => array (
					'className' => 'CourseMode',
					'foreignKey' => 'course_mode_id',
					'conditions' => '',
					'fields' => '',
					'order' => '' 
			),
			'Semester' => array (
					'className' => 'Semester',
					'foreignKey' => 'semester_id',
					'conditions' => '',
					'fields' => '',
					'order' => '' 
			),
			'MonthYear' => array (
					'className' => 'MonthYear',
					'foreignKey' => 'month_year_id',
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
	public $hasMany = array (
			
			'CourseStudentMapping' => array (
					'className' => 'CourseStudentMapping',
					'foreignKey' => 'course_mapping_id',
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
			'Attendance' => array (
					'className' => 'Attendance',
					'foreignKey' => 'course_mapping_id',
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
			'Cae' => array (
					'className' => 'Cae',
					'foreignKey' => 'course_mapping_id',
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
			'CaePractical' => array (
					'className' => 'CaePractical',
					'foreignKey' => 'course_mapping_id',
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
			'EsePractical' => array (
					'className' => 'EsePractical',
					'foreignKey' => 'course_mapping_id',
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
			'CaeProject' => array (
					'className' => 'CaeProject',
					'foreignKey' => 'course_mapping_id',
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
			'EseProject' => array (
					'className' => 'EseProject',
					'foreignKey' => 'course_mapping_id',
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
			'Timetable' => array (
					'className' => 'Timetable',
					'foreignKey' => 'course_mapping_id',
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
			
			'StudentMark' => array (
					'className' => 'StudentMark',
					'foreignKey' => 'course_mapping_id',
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
			'InternalExam' => array (
					'className' => 'InternalExam',
					'foreignKey' => 'course_mapping_id',
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
			'EndSemesterExam' => array (
					'className' => 'EndSemesterExam',
					'foreignKey' => 'course_mapping_id',
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
			'ProfessionalTraining' => array (
					'className' => 'ProfessionalTraining',
					'foreignKey' => 'cae_pt_id',
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
			'InternalProject' => array (
					'className' => 'InternalProject',
					'foreignKey' => 'course_mapping_id',
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
			'RevaluationExam' => array (
					'className' => 'RevaluationExam',
					'foreignKey' => 'course_mapping_id',
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
			'PracticalAttendance' => array (
					'className' => 'PracticalAttendance',
					'foreignKey' => 'course_mapping_id',
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
	public $hasAndBelongsToMany = array (
			'Student' => array (
					'className' => 'Student',
					'joinTable' => 'course_student_mappings',
					'foreignKey' => 'course_mapping_id',
					'associationForeignKey' => 'student_id',
					'unique' => 'keepExisting',
					'conditions' => '',
					'fields' => '',
					'order' => '',
					'limit' => '',
					'offset' => '',
					'finderQuery' => '' 
			) 
	);
	public $hasOne = array(
			'CaePt' => array (
					'className' => 'CaePt',
					'foreignKey' => 'course_mapping_id',
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
	public function retrieveCourseMappingWithBatchProgramAndCourseType($batch_id, $program_id, $courseTypeIdArray, $month_year_id) {
		$tempArray = $this->find ( 'all', array (
				'conditions' => array (
						'CourseMapping.batch_id' => $batch_id,
						'CourseMapping.program_id' => $program_id,
						'Course.course_type_id' => $courseTypeIdArray,
						'CourseMapping.month_year_id' => $month_year_id,
						'CourseMapping.indicator' => 0 
				),
				'recursive' => 1 
		) );
		return $tempArray;
	}
	public function getCourseMarks($cm) {
		$result = $this->find('all', array(
				'conditions' => array (
					'CourseMapping.id' => array_keys($cm) 
				),
				'fields' => array('CourseMapping.id'),
				'contain' => array(
					'Course' => array(
						'fields' => array('Course.course_code', 'Course.min_cae_mark', 'Course.min_ese_mark', 
								'Course.max_cae_mark', 'Course.max_ese_mark', 'Course.total_min_pass', 'Course.course_max_marks',
								'Course.max_cae_mark', 'Course.max_ese_qp_mark', 'Course.course_name', 'Course.course_type_id'
						)
					)
				),
				'recursive' => 2 
		) );
		$crseArray = array ();
		
		foreach ( $result as $key => $value ) {
			$crseArray [$value['CourseMapping']['id']] = array (
					'course_code' => $value['Course']['course_code'],
					'min_cae_mark' => $value['Course']['min_cae_mark'],
					'min_ese_mark' => $value['Course']['min_ese_mark'],
					'max_cae_mark' => $value['Course']['max_cae_mark'],
					'max_ese_mark' => $value['Course']['max_ese_mark'],
					'total_min_pass' => $value['Course']['total_min_pass'],
					'course_max_marks' => $value['Course']['course_max_marks'],
					'max_ese_qp_mark' => $value['Course']['max_ese_qp_mark'],
					'course_name' => $value['Course']['course_name'],
					'course_type_id' => $value['Course']['course_type_id'],
			);
		}
		return $crseArray;
	}
	public function getCoursesWithBatchAndMyOrProgram($batch_id, $program_id, $month_year_id, $courseTypeIdArray) {
		$courseMapping = $this->CourseMapping->find ( 'list', array (
				'fields' => array (
						'CourseMapping.id',
						'Course.course_code' 
				),
				'conditions' => array (
						'CourseMapping.batch_id' => $batch_id,
						'CourseMapping.program_id' => $program_id 
				),
				'contain' => array (
						'Course' => array (
								'conditions' => array (
										'Course.course_type_id' => $courseTypeIdArray 
								) 
						) 
				),
				'recursive' => 1 
		) );
		return $courseMapping;
	}
	public function listTheoryCourseMappingWithBatchOrProgram($month_year_id, $batch_id, $program_id) {
		$courseMapping = array ();
		$filterCondition = "";
		if ($batch_id > 0) {
			$filterCondition .= "`(CourseMapping`.`batch_id` = " . $batch_id . ") AND ";
		} else {
			$filterCondition .= "`(CourseMapping`.`batch_id` > 0)" . " AND ";
		}
		
		if ($program_id > 0) {
			$filterCondition .= "`(CourseMapping`.`program_id` = " . $program_id . ")" . " AND ";
		} else {
			$filterCondition .= "`(CourseMapping`.`program_id` > 0)" . " AND ";
		}
		
		$filterCondition .= "((`CourseMapping`.`indicator` = 0)";
		$filterCondition .= "((`Course`.`course_type_id` = 1) OR ";
		$filterCondition .= "((`Course`.`course_type_id` = 3)";
		$filterCondition .= ")";
		
		$courseMapping = $this->find ( 'all', array (
				'conditions' => array (
						$filterCondition 
				),
				'fields' => array (
						'CourseMapping.id',
						'CourseMapping.month_year_id' 
				),
				'order' => array (
						'CourseMapping.id ASC' 
				),
				'recursive' => 1 
		) );
		
		return $courseMapping;
	}
	
	public function retrieveCourseDetails($cm, $month_year_id) {
		//pr($cm);
		//echo $month_year_id;
		$result = $this->find('all', array(
				'conditions' => array (
						'CourseMapping.id' => array_keys($cm), 'CourseMapping.month_year_id <='=>$month_year_id
				),
				'fields' => array('CourseMapping.id'),
				'contain' => array(
						'Course' => array(
								'fields' => array('Course.course_code', 'Course.min_cae_mark', 'Course.min_ese_mark',
										'Course.max_cae_mark', 'Course.max_ese_mark', 'Course.total_min_pass', 'Course.course_max_marks',
										'Course.max_cae_mark', 'Course.max_ese_qp_mark', 'Course.course_name'
								)
						)
				),
				'recursive' => 2
		) );
		/* $dbo = $this->getDatasource();
		$logs = $dbo->getLog();
		$lastLog = end($logs['log']);
		echo $lastLog['query']; */
		$crseArray = array ();
		
		foreach ($result as $key => $value) {
			$min_cae_mark = round($value['Course']['max_cae_mark'] * $value['Course']['min_cae_mark'] / 100);
			$min_ese_mark = round($value['Course']['max_ese_mark'] * $value['Course']['min_ese_mark'] / 100);
			$min_pass_mark = round($value['Course']['course_max_marks'] * $value['Course']['total_min_pass'] / 100);
			$crseArray [$value['CourseMapping']['id']] = array (
					'course_code' => $value['Course']['course_code'],
					'min_cae_pass_percentage' => $value['Course']['min_cae_mark'],
					'min_ese_pass_percentage' => $value['Course']['min_ese_mark'],
					'max_cae_mark' => $value['Course']['max_cae_mark'],
					'max_ese_mark' => $value['Course']['max_ese_mark'],
					'total_min_pass_percentage' => $value['Course']['total_min_pass'],
					'course_max_marks' => $value['Course']['course_max_marks'],
					'max_ese_qp_mark' => $value['Course']['max_ese_qp_mark'],
					'course_name' => $value['Course']['course_name'],
					'min_cae_mark'=>$min_cae_mark,
					'min_ese_mark'=>$min_ese_mark,
					'min_pass_mark'=>$min_pass_mark
			);
		}
		return $crseArray;
		
	}
	
	public function getCmIdCaeIdFromBatchIdProgramIdCourseCode($batch_id, $program_id, $course_code, $assessment_number, $type, $c_type_id) {
		$results = $this->find('all', array(
				'conditions' => array (
						'CourseMapping.batch_id' => $batch_id, 'CourseMapping.program_id' => $program_id
				),
				'fields' => array('CourseMapping.id'),
				'contain' => array(
						'Course' => array(
								'conditions'=>array('Course.course_code'=>$course_code),
								'fields'=>array('Course.course_code')
						),
						'Cae'=>array(
								'fields'=>array('Cae.id'),
								'conditions'=>array('Cae.indicator'=>0),
								'order'=>array('Cae.id DESC'),
						),
						'CaePractical'=>array(
								'fields'=>array('CaePractical.id'),
								'conditions'=>array('CaePractical.indicator'=>0),
								'order'=>array('CaePractical.id DESC'),
						),
						'CaeProject'=>array(
								'fields'=>array('CaeProject.id'),
								'conditions'=>array('CaeProject.indicator'=>0),
								'order'=>array('CaeProject.id DESC'),
						),
						'EsePractical'=>array(
								'fields'=>array('EsePractical.id'),
								'conditions'=>array('EsePractical.indicator'=>0),
								'order'=>array('EsePractical.id DESC'),
						),
						'EseProject'=>array(
								'fields'=>array('EseProject.id'),
								'conditions'=>array('EseProject.indicator'=>0),
								'order'=>array('EseProject.id DESC'),
						)
				),
				'recursive' => 0
		) );
		//pr($results);
		foreach ($results as $key => $result) {
			if ($result['Course']['course_code']==$course_code) {
				if (($c_type_id ==1 || $c_type_id ==3) && $type == "CAE")
					$tmpArray = $result['Cae'];
				elseif ($c_type_id ==2 && $type == "CAE")
					$tmpArray = $result['CaePractical'];
				elseif ($c_type_id ==4 && $type == "CAE")
					$tmpArray = $result['CaeProject'];
				elseif ($c_type_id ==2 && $type == "ESE")
					$tmpArray = $result['EsePractical'];
				elseif ($c_type_id ==4 && $type == "ESE")
					$tmpArray = $result['EseProject'];
				//pr($tmpArray);
				$caeId = $tmpArray[$assessment_number-1]['id'];
				break;
			}
		}
		return $caeId;
	}
	
	public function getCmIdWithBatchAndMonthYearId($month_year_id, $courseTypeIdArray) {
		
		$cmCond=array();
		
		$cmCond['CourseMapping.indicator'] = 0;
		if ($month_year_id > 0) $cmCond['CourseMapping.month_year_id'] = $month_year_id;
		//if ($program_id > 0) $cmCond['CourseMapping.program_id'] = $program_id;
		//if ($batch_id > 0) $cmCond['CourseMapping.batch_id'] = $batch_id;
		
		$cmCond['Course.course_type_id'] = $courseTypeIdArray;
		
		$courseMapping = $this->find ('list', array (
				'fields' => array('CourseMapping.id'),
				'conditions' => $cmCond,
				'contain' => array (
						'Course' => array (
								'fields' => array('Course.id')
						) 
				),
				'recursive' => 1 
		));
		/* $dbo = $this->getDatasource();
		$logs = $dbo->getLog();
		$lastLog = end($logs['log']);
		echo $lastLog['query']; */
		//pr($courseMapping);
		return $courseMapping;
	}
	
	public function getBatchAcademicProgramFromCmId($cm_id) {
		$results = $this->find('all', array(
				'conditions' => array (
						'CourseMapping.id' => $cm_id, 'CourseMapping.indicator' => 0
				),
				'fields' => array('CourseMapping.id'),
				'contain' => array(
						'Course' => array(
								'fields'=>array('Course.course_code', 'Course.course_name')
						),
						'Program'=>array(
								'fields' =>array('Program.program_name', 'Program.short_code'),
								'Academic'=>array('fields' =>array('Academic.academic_name', 'Academic.short_code'))
						),
						'Batch'=>array(
								'fields' =>array('Batch.batch_from','Batch.batch_to','Batch.academic')
						),
				),
				'recursive' => 0
		) );
		return $results;
	}
}

