<?php
App::uses('AppController', 'Controller');

class CourseStudentMappingsController extends AppController {

	public $components = array('Paginator', 'Flash', 'Acl',  'Session');
	
	public $uses = array("CourseStudentMapping", "Student", "CourseMapping", "ContinuousAssessmentExam", "BatchMode", "Batch", "Program", "MonthYear", "CourseMapping", "StudentType", "Semester");
	
	/* public function beforeFilter() {
		//parent::beforeFilter();										
		if (isset($this->Security) && $this->action == 'listStudents') {
					$this->Security->enabled = false;
					$this->Security->validatePost = false;
		}	
		
	} */
	
	public function index() {
		//$user = $this->Session->read("Auth.User");
		//$this->User->id = $user['id'];
		
		//$userData = $this->User->read();
		//$this->User->recursive = 0;
		//$this->set('students', $this->Paginator->paginate());
	}
	
	public function mapStudents() {
		$academics = $this->Student->Academic->find('list');
		$batches = $this->Student->Batch->find('list', array('fields' => array('Batch.batch_period')));
		//$monthyears = $this->ContinuousAssessmentExam->findMonthYear();
		$this->set(compact('batches', 'academics', 'monthyears'));
		if($this->request->is('post')) {
			//pr($this->data);
			
			$csmData = $this->request->data['CSM']['Course'];
			$cmId = $this->request->data['CSM']['CourseCode'];
			$courseNo = $this->request->data['CSM']['CourseNumber'];
			$courseId = $this->request->data['CSM']['CourseId'];
			$month_year = $this->CourseMapping->find('first', array(
				'conditions' => array('CourseMapping.id' => $cmId),
				'fields' => array('CourseMapping.month_year_id'),
				'contain' => false
			));
			//pr($month_year);
			$month_year_id = $month_year['CourseMapping']['month_year_id'];
			
			//pr($csmData);
			$bool = false;
			foreach ($csmData as $course_code => $csmCourseArray) {
				foreach ($csmCourseArray as $student_id => $csm_status) {
					if ($csm_status == 1) {
						$conditions = array(
								"CourseStudentMapping.course_mapping_id" => $cmId[trim($course_code)],
								"CourseStudentMapping.student_id" => $student_id,
								"CourseStudentMapping.course_id" => $courseId[trim($course_code)]
						);
						if ($csm_status==1) $indicator=0;
						else if ($csm_status==0) $indicator=1;
						if ($this->CourseStudentMapping->hasAny($conditions)){
							$this->CourseStudentMapping->query("UPDATE course_student_mappings set
									modified='".date("Y-m-d H:i:s")."',
									indicator=$indicator,
									modified_by = ".$this->Auth->user('id')."
									WHERE student_id = ".$student_id." AND
									course_mapping_id=".$cmId[trim($course_code)]." AND 
									course_id=".$courseId[trim($course_code)]
									);
							$bool = true;
						}
						else {
							$data=array();
							$data['CourseStudentMapping']['student_id'] = $student_id;
							$data['CourseStudentMapping']['course_mapping_id'] = $cmId[trim($course_code)];
							$data['CourseStudentMapping']['course_id'] = $courseId[trim($course_code)];
							$data['CourseStudentMapping']['month_year_id'] = $month_year_id;
							$data['CourseStudentMapping']['semester_id'] = $this->request->data['semester_id'];
							$data['CourseStudentMapping']['course_number'] = $courseNo[trim($course_code)];
							$data['CourseStudentMapping']['indicator'] = $indicator;
							$data['CourseStudentMapping']['created_by'] = $this->Auth->user('id');
							$this->CourseStudentMapping->create();
							$this->CourseStudentMapping->save($data);
							$bool = true;
						}
					}
					else if($csm_status == 0) {
						$conditions = array(
								"CourseStudentMapping.course_mapping_id" => $cmId[trim($course_code)],
								"CourseStudentMapping.course_id" => $courseId[trim($course_code)],
								"CourseStudentMapping.student_id" => $student_id,
								"CourseStudentMapping.indicator" => 0
						);
						if ($csm_status==1) $indicator=0;
						else if ($csm_status==0) $indicator=1;
						if ($this->CourseStudentMapping->hasAny($conditions)){
							$this->CourseStudentMapping->query("UPDATE course_student_mappings set
									modified='".date("Y-m-d H:i:s")."',
									indicator=$indicator,
									modified_by = ".$this->Auth->user('id')."
									WHERE student_id = ".$student_id." AND
									course_mapping_id=".$cmId[trim($course_code)]." AND 
									course_id=".$courseId[trim($course_code)]);
							$bool = true;
						}
					}
				}
			}
			
			if ($bool) {
				$this->Flash->success(__('Student Course mapped!!!'));
				return $this->redirect(array('action' => 'mapStudents'));
			}
		}
	}
	
	public function getStudentsWithBatchAndProgram($batch_id, $program_id) {
		$this->loadModel('Student');
		$studentOptions = array(
				'conditions' => array(
						array('Student.batch_id' => $batch_id,
								'Student.program_id' => $program_id,
								'Student.discontinued_status' => 0
						)
				),
				'fields' => array('Student.id', 'Student.registration_number',
						'Student.name'
				),
				'recursive' => 0
		);
		$students = $this->Student->find("all", $studentOptions);
		return $students;
	}
	
	public function mapCourseStudent($program_id, $batch_id, $academic_id) {
		//echo $program_id." ".$batch_id." ".$academic_id;
		$res = $this->CourseMapping->query("SELECT group_concat( c.course_code ) AS course_code
			FROM `course_mappings` cm
			JOIN courses c ON c.id = cm.course_id
			WHERE batch_id =4
			AND program_id =10
			GROUP BY semester_id");
		//pr($res);
		$this->layout=false;
	}
	
	public function listStudents($batch_id, $academic_id, $program_id, $semester_id) {
		
		$first_month_year = $this->getMonthYearFromSemesterId($batch_id, $program_id, $semester_id);
		$month_year_id = $first_month_year['CourseMapping']['month_year_id'];
		
		$courseMappingResult = $this->CourseMapping->find('all', array(
			'conditions' => array('CourseMapping.batch_id' => $batch_id, 'CourseMapping.program_id'=> $program_id,
							'CourseMapping.indicator' => 0, 'CourseMapping.semester_id ' => $semester_id
			),
			'fields' => array('CourseMapping.id', 'CourseMapping.course_number', 'CourseMapping.semester_id', 
					'CourseMapping.course_id',
					/* 'CourseMapping.month_year_id',
						 'CourseMapping.batch_id', 'CourseMapping.program_id' */),
			'contain' => array(
					'Course' => array('fields' => array('Course.course_code')),
					'CourseStudentMapping' => array('fields' => array(
							'CourseStudentMapping.course_mapping_id', 'CourseStudentMapping.student_id',
								'CourseStudentMapping.new_semester_id', 'CourseStudentMapping.type', 
							),
								'conditions' => array('CourseStudentMapping.indicator' => 0,
								)
					),
			),
		));
		$tmpArray = array();
		$courseIdArray = array();
		foreach ($courseMappingResult as $key => $cmArray) {
			//pr($cmArray);
			$csmArray = $cmArray['CourseStudentMapping'];
			$a = array(); $b=array(); $c=array();
			foreach ($csmArray as $csmKey => $csmValue) {
				if ($cmArray['CourseMapping']['id'] == $csmValue['course_mapping_id']) {
					$a[$csmValue['student_id']] = 1;
				}
				if ($csmValue['new_semester_id'] <> NULL || $csmValue['new_semester_id'] >0) {
					$b[$csmValue['student_id']] = $csmValue['new_semester_id'];
				}
			}
			$tmpArray[trim($cmArray['Course']['course_code'])] = $a;
			$newSemesterIdArray[trim($cmArray['Course']['course_code'])] = $b;
			//$courseIdArray[trim($cmArray['Course']['course_code'])] = $c;
		}
		//pr($tmpArray);
		$studentResult = $this->Student->getStudentsInfo($batch_id, $program_id);
		
		$this->set(compact('courseMappingResult', 'studentResult', 'tmpArray', 'newSemesterIdArray', 'month_year_id', 'courseIdArray'));
		$this->layout = false;
	}
	
}