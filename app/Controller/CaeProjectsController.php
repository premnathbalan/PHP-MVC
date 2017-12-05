<?php
App::uses('AppController', 'Controller');
App::import('Controller', 'ContinuousAssessmentExams');
/**
 * CaeProjects Controller
 *
 * @property CaeProject $CaeProject
 * @property PaginatorComponent $Paginator
 * @property FlashComponent $Flash
 * @property SessionComponent $Session
 */
class CaeProjectsController extends AppController {
	
	public $cType = "project";
	public $uses = array( "CaeProject", "EseProject", "ContinuousAssessmentExam", "Batch", "Lecturer", "EsePractical", "CourseStudentMapping",
			"Course", "User", "CourseFaculty", "Student", "Academic", "CaePractical", "Project",
			"GrossAttendance", "Cae", "CourseMode", "CourseMapping", "MonthYear", "Attendance",
			"InternalExam", "Program", "CourseType", "InternalExam", "InternalPractical", "ProjectReview", "InternalProject", 
			"StudentMark", "StudentAuthorizedBreak"
	);
/**
 * Components
 *
 * @var array
 */
	public $components = array('Paginator', 'Flash', 'Session');

/**
 * index method
 *
 * @return void
 */
	public function index() {
		//$this->CaeProject->recursive = 0;
		//$this->set('caeProjects', $this->Paginator->paginate());
		$access_result = $this->checkPathAccess($this->Auth->user('id'));
		if (!$access_result) {
			$academics = $this->Student->Academic->find('list');
			$batches = $this->Student->Batch->find('list', array('fields' => array('Batch.batch_period')));
			$monthYears = $this->MonthYear->getAllMonthYears();
			//pr($monthYears);
			
			$action = $this->action;
			$this->set(compact('batches', 'academics', 'programs', 'monthYears'));
		} else {
			$this->render('../Users/access_denied');
		}
	}

/**
 * view method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	/* public function view($id = null) {
		if (!$this->CaeProject->exists($id)) {
			throw new NotFoundException(__('Invalid cae project'));
		}
		$options = array('conditions' => array('CaeProject.' . $this->CaeProject->primaryKey => $id));
		$this->set('caeProject', $this->CaeProject->find('first', $options));
	} */

/**
 * add method
 *
 * @return void
 */
	public function add() {
		$academics = $this->Student->Academic->find('list');
		$batches = $this->Student->Batch->find('list', array('fields' => array('Batch.batch_period')));
		$ContinuousAssessmentExamsController = new ContinuousAssessmentExamsController;
		$monthyears = $ContinuousAssessmentExamsController->findMonthYear();
		$cType = $this->cType;
		$this->set(compact('batches', 'academics', 'monthyears', 'cType'));
		
		/* if ($this->request->is('post')) {
			$this->CaeProject->create();
			if ($this->CaeProject->save($this->request->data)) {
				$this->Flash->success(__('The cae project has been saved.'));
				return $this->redirect(array('action' => 'index'));
			} else {
				$this->Flash->error(__('The cae project could not be saved. Please, try again.'));
			}
		} */
		
		if ($this->request->is('post')) {
			//pr($this->data); 
			$cm_id = $this->data['course_mapping_id'];
			$result = $this->CourseMapping->find('first', array(
					'conditions'=>array('CourseMapping.id'=>$cm_id),
					'fields'=>array('CourseMapping.id', 'CourseMapping.month_year_id'),
					'contain'=>array(
							'Course'=>array(
									'fields' => array('Course.course_code', 'Course.min_cae_mark', 'Course.max_cae_mark')
							),
							'CaeProject'=>array(
									'conditions'=>array('CaeProject.indicator'=>0),
									'fields'=>array('CaeProject.id', 'CaeProject.marks')
							)
					)
			));
			$course_code = $result['Course']['course_code'];
			$cae_count = count($result['CaeProject']);
			//echo "Count : ".$cae_count;
			//$max_cae_mark = $result['Course']['max_cae_mark'];
			$month_year_id = $result['CourseMapping']['month_year_id'];
			if (isset($result['CaeProject']) && count($result['CaeProject'])>0) {
				$max_cae_mark = $result['CaeProject'][0]['marks'];
			}
			else {
				$max_cae_mark = $this->request->data['CaeProject']['marks'];
			}
			
			if ($cae_count <=2) {
				$data = array();
				$data['CaeProject']['month_year_id']=$month_year_id;
				$data['CaeProject']['course_mapping_id']=$this->request->data['course_mapping_id'];
				$data['CaeProject']['assessment_type']='Review';
				$data['CaeProject']['marks']=$max_cae_mark;
				$data['CaeProject']['approval_status']=0;
				$data['CaeProject']['indicator']=0;
				$data['CaeProject']['created_by']= $this->Auth->user('id');
				$this->CaeProject->create();
		
				if ($this->CaeProject->save($data)) {
					if ($cae_count == 0) {
						$data = array();
						$data['EseProject']['month_year_id']=$month_year_id;
						$data['EseProject']['course_mapping_id']=$this->request->data['course_mapping_id'];
						$data['EseProject']['assessment_type']='Viva';
						$data['EseProject']['marks']=$max_cae_mark;
						$data['EseProject']['approval_status']=0;
						$data['EseProject']['indicator']=0;
						$data['EseProject']['created_by']= $this->Auth->user('id');
						$this->EseProject->create();
						$this->EseProject->save($data);
					}
					$this->Flash->success(__('Project CAE has been created.'));
					return $this->redirect(array('action' => 'add'));
				} else {
					$this->Flash->error(__('The CAE cannot be created. Please, try again.'));
				}
				
			}
			else {
				$this->Flash->error(__('Maximum number of CAEs already created for '.$course_code));
			}
		}
		
		$courseMappings = $this->CaeProject->CourseMapping->find('list');
		//$semesters = $this->CaeProject->Semester->find('list');
		$lecturers = $this->CaeProject->Lecturer->find('list');
		$users = $this->CaeProject->User->find('list');
		$modifiedUsers = $this->CaeProject->ModifiedUser->find('list');
		$this->set('cType', $this->cType);
		$this->set(compact('courseMappings', 'semesters', 'lecturers', 'users', 'modifiedUsers'));
	}

/**
 * edit method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	/* public function edit($id = null) {
		if (!$this->CaeProject->exists($id)) {
			throw new NotFoundException(__('Invalid cae project'));
		}
		if ($this->request->is(array('post', 'put'))) {
			if ($this->CaeProject->save($this->request->data)) {
				$this->Flash->success(__('The cae project has been saved.'));
				return $this->redirect(array('action' => 'index'));
			} else {
				$this->Flash->error(__('The cae project could not be saved. Please, try again.'));
			}
		} else {
			$options = array('conditions' => array('CaeProject.' . $this->CaeProject->primaryKey => $id));
			$this->request->data = $this->CaeProject->find('first', $options);
		}
		$courseMappings = $this->CaeProject->CourseMapping->find('list');
		$semesters = $this->CaeProject->Semester->find('list');
		$lecturers = $this->CaeProject->Lecturer->find('list');
		$users = $this->CaeProject->User->find('list');
		$modifiedUsers = $this->CaeProject->ModifiedUser->find('list');
		$this->set(compact('courseMappings', 'semesters', 'lecturers', 'users', 'modifiedUsers'));
	} */

/**
 * delete method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	/* public function delete($id = null) {
		$this->CaeProject->id = $id;
		if (!$this->CaeProject->exists()) {
			throw new NotFoundException(__('Invalid cae project'));
		}
		$this->request->allowMethod('post', 'delete');
		if ($this->CaeProject->delete()) {
			$this->Flash->success(__('The cae project has been deleted.'));
		} else {
			$this->Flash->error(__('The cae project could not be deleted. Please, try again.'));
		}
		return $this->redirect(array('action' => 'index'));
	} */
	
	public function caeProjectDisplay($batch_id, $academic_id, $program_id, $month_year_id, $currentMethod=NULL) {
		//echo $currentMethod;
	
		$ContinuousAssessmentExamsController = new ContinuousAssessmentExamsController;
		# now you can reference your controller like any other PHP class
		$course_type_id = $this->listCourseTypeIdsBasedOnMethod($currentMethod, "-");
		//pr($course_type_id);
		$courseTypeIdArray = explode("-",$course_type_id);
		//pr($courseTypeIdArray);
		//$courseMappingArray = $this->courseMappingBasedOnCourseType($batch_id, $program_id, $courseTypeIdArray);
		$courseMappingArray = $this->CourseMapping->retrieveCourseMappingWithBatchProgramAndCourseType($batch_id, $program_id, $courseTypeIdArray, $month_year_id);
		//pr($courseMappingArray);
		$this->set(compact('batch_id', 'academic_id', 'program_id', 'month_year_id'));
		$this->set(compact('courseMappingArray'));
		$this->layout=false;
	}

	public function addMarks($batchId, $academicId, $programId, $caeId, $caeNumber) {
		//echo $caeId;
		$cntRecords = $this->ProjectReview->find("count", array(
			'conditions' => array('ProjectReview.cae_project_id'=>$caeId)
		));
		//pr($cntRecords);
		if ($cntRecords > 0) {
			$this->Flash->success(__('Marks already entered.'));
			return $this->redirect(array('controller' => 'CaeProjects', 'action' => 'index'));
		}
		else {
			$cm = $this->CaeProject->find('all', array(
					'conditions' => array('CaeProject.id'=>$caeId),
					'fields'=>array('CaeProject.course_mapping_id', 'CaeProject.marks'),
					'contain'=>array(
						'CourseMapping'=>array(
							'fields'=>array('CourseMapping.month_year_id')
						)
					)
			));
			//pr($cm);
			$cm_id = $cm[0]['CaeProject']['course_mapping_id'];
			$maxMarks = $cm[0]['CaeProject']['marks'];
			$month_year_id = $cm[0]['CourseMapping']['month_year_id'];
			
			$results = $this->CourseStudentMapping->find('all', array(
				'conditions' => array(
					'CourseStudentMapping.indicator'=>0, 'CourseStudentMapping.course_mapping_id'=>$cm_id
				),
				'fields' => array('CourseStudentMapping.student_id'),
				'contain' => array(
					'Student' => array(
						'conditions' => array('Student.batch_id'=>$batchId, 'Student.program_id'=>$programId,
							'Student.discontinued_status'=>0),
						'fields'=>array('Student.id', 'Student.name', 'Student.registration_number'),
						'ProjectReview' => array(
							'conditions' => array('ProjectReview.cae_project_id'=>$caeId),
							'fields'=>array('ProjectReview.marks'),
						)
					)
				)
			));
			//pr($results);
			$ContinuousAssessmentExamsController = new ContinuousAssessmentExamsController;
			# now you can reference your controller like any other PHP class
			$month_year = $ContinuousAssessmentExamsController->getMonthYear($cm_id);
			//pr($course_type_id);
			
			$this->set(compact('results', 'cm_id', 'batchId', 'academicId', 'programId', 'cmId', 'caeId', 'month_year', 'maxMarks', 
					'caeNumber', 'month_year_id'));
		}
	
	}
	
	public function editMarks($batchId, $academicId, $programId, $caeId, $caeNumber) {
		//echo $caeId;
		$cm = $this->CaeProject->find('all', array(
				'conditions' => array('CaeProject.id'=>$caeId),
				'fields'=>array('CaeProject.course_mapping_id', 'CaeProject.marks'),
				'contain'=>array(
						'CourseMapping'=>array(
								'fields'=>array('CourseMapping.month_year_id')
						)
				)
		));
		//pr($cm);
		$cm_id = $cm[0]['CaeProject']['course_mapping_id'];
		$maxMarks = $cm[0]['CaeProject']['marks'];
		$month_year_id = $cm[0]['CourseMapping']['month_year_id'];
		
		$results = $this->CourseStudentMapping->find('all', array(
				'conditions' => array(
						'CourseStudentMapping.indicator'=>0, 'CourseStudentMapping.course_mapping_id'=>$cm_id
				),
				'fields' => array('CourseStudentMapping.student_id'),
				'contain' => array(
						'Student' => array(
								'conditions' => array('Student.batch_id'=>$batchId, 'Student.program_id'=>$programId,
										'Student.discontinued_status'=>0),
								'fields'=>array('Student.id', 'Student.name', 'Student.registration_number'),
								'ProjectReview' => array(
										'conditions' => array('ProjectReview.cae_project_id'=>$caeId,
										'ProjectReview.month_year_id'=>$month_year_id
										),
										'fields'=>array('ProjectReview.marks'),
								)
						)
				)
		));
		//pr($results);
		$ContinuousAssessmentExamsController = new ContinuousAssessmentExamsController;
		# now you can reference your controller like any other PHP class
		$month_year = $ContinuousAssessmentExamsController->getMonthYear($cm_id);
		//pr($course_type_id);
		$publish_status = $this->StudentMark->find("count", array(
				'conditions'=>array('StudentMark.course_mapping_id'=>$cm_id),
		));
		//pr($publish_status);
		$this->set(compact('results', 'cm_id', 'batchId', 'academicId', 'programId', 'cmId', 'caeId', 'month_year', 'maxMarks',
				'caeNumber', 'month_year_id', 'publish_status'));
	
	}
	
	public function view($batchId, $academicId, $programId, $caeId, $caeNumber) {
		//echo $caeId;
		$cm = $this->CaeProject->find('all', array(
				'conditions' => array('CaeProject.id'=>$caeId),
				'fields'=>array('CaeProject.course_mapping_id', 'CaeProject.marks', 'CaeProject.approval_status'),
				'contain'=>array(
						'CourseMapping'=>array(
								'fields'=>array('CourseMapping.month_year_id')
						)
				)
		));
		//pr($cm);
		$cm_id = $cm[0]['CaeProject']['course_mapping_id'];
		$maxMarks = $cm[0]['CaeProject']['marks'];
		$month_year_id = $cm[0]['CourseMapping']['month_year_id'];
		$approval_status = $cm[0]['CaeProject']['approval_status'];
		$currentModel = $this->modelClass;
		$results = $this->CourseStudentMapping->find('all', array(
				'conditions' => array(
						'CourseStudentMapping.indicator'=>0, 'CourseStudentMapping.course_mapping_id'=>$cm_id
				),
				'fields' => array('CourseStudentMapping.student_id'),
				'contain' => array(
						'Student' => array(
								'conditions' => array('Student.batch_id'=>$batchId, 'Student.program_id'=>$programId,
										'Student.discontinued_status'=>0),
								'fields'=>array('Student.id', 'Student.name', 'Student.registration_number'),
								'ProjectReview' => array(
										'conditions' => array('ProjectReview.cae_project_id'=>$caeId,
												'ProjectReview.month_year_id'=>$month_year_id
										),
										'fields'=>array('ProjectReview.marks'),
								)
						)
				)
		));
		//pr($results);
		$ContinuousAssessmentExamsController = new ContinuousAssessmentExamsController;
		# now you can reference your controller like any other PHP class
		$month_year = $ContinuousAssessmentExamsController->getMonthYear($cm_id);
		//pr($course_type_id);
			
		$this->set(compact('results', 'cm_id', 'batchId', 'academicId', 'programId', 'cmId', 'caeId', 'month_year', 'maxMarks',
				'caeNumber', 'month_year_id', 'approval_status', 'currentModel'));
	
	}
	
	public function saveCAEProjectMarks($cae_project_id = null, $markEntry = null, $student_id = null, $month_year_id = null){
		$cae_data_exists=$this->ProjectReview->find('first', array(
				'conditions' => array(
						'ProjectReview.cae_project_id'=>$cae_project_id,
						'ProjectReview.month_year_id'=>$month_year_id,
						'ProjectReview.student_id'=>$student_id,
				),
				'fields' => array('ProjectReview.id'),
				'recursive' => 0
		));
		$data=array();
		$data['ProjectReview']['month_year_id'] = $month_year_id;
		$data['ProjectReview']['student_id'] = $student_id;
		$data['ProjectReview']['cae_project_id'] = $cae_project_id;
		$data['ProjectReview']['marks'] = $markEntry;
		if(isset($cae_data_exists['ProjectReview']['id']) && $cae_data_exists['ProjectReview']['id']>0) {
			$id = $cae_data_exists['ProjectReview']['id'];
			$data['ProjectReview']['id'] = $id;
			$data['ProjectReview']['modified_by'] = $this->Auth->user('id');
			$data['ProjectReview']['modified'] = date("Y-m-d H:i:s");
		}
		else {
			$this->ProjectReview->create($data);
			$data['ProjectReview']['created_by'] = $this->Auth->user('id');
		}
		if ($this->ProjectReview->save($data)) {
			$this->updateCaeProject($cae_project_id);
			echo true;
		}
		else {
			echo false;
		}
		exit;
	}
	
	public function updateCaeProject($cae_project_id) {
		$this->CaeProject->updateAll(
		    /* UPDATE FIELD */
		    array(
		        "CaeProject.add_status" => 1,
		    	"CaeProject.marks_status" => "'Entered'",
		    ),
		    /* CONDITIONS */
		    array(
		        "CaeProject.id" => $cae_project_id
		    )
		);
	}
	
	public function download() {
		$access_result = $this->checkPathAccess($this->Auth->user('id'));
		if (!$access_result) {
		$academics = $this->Student->Academic->find('list');
		$batches = $this->Student->Batch->find('list', array('fields' => array('Batch.batch_period')));
		
		$monthyears = $this->MonthYear->getAllMonthYears();
		$action = $this->action;
		$this->set(compact('batches', 'academics', 'programs', 'monthyears'));
		
		if($this->request->is('post')) {
			//pr($this->data);
			$batch_id = $this->data['CaeProject']['batch_id'];
			$batch = $this->Batch->getBatch($batch_id);
				
			$academic_id = $this->data['CaeProject']['academic_id'];
			$academicDetails = $this->Academic->getAcademic($academic_id);
				
			$program_id = $this->data['Student']['program_id'];
			$programDetails = $this->Program->getProgram($program_id);
				
			$assessment_number = $this->data['CaeProject']['assessment_number'];
			//$marks = $this->data['Student']['marks'];
			$month_year_id = $this->data['CaeProject']['month_year_id'];
			$month_year = $this->MonthYear->getMonthYear($month_year_id);
				
			$studentArray = $this->Student->getStudentsWithBatchAndProgram($batch_id, $program_id, $month_year_id);
			//pr($studentArray);
			
			$ContinuousAssessmentExamsController = new ContinuousAssessmentExamsController;
			
			$processedStudentArray = $ContinuousAssessmentExamsController->processStudentArray($studentArray);
				
			$absResult = $this->StudentAuthorizedBreak->findAbsStudents();
			//pr($absResult);
			$currentBatchDuration = $this->Batch->getBatchDuration($batch_id);
				
			//echo $currentBatchDuration." ".$month_year_id;
			$absUsers = $this->processAbsResult($absResult, $currentBatchDuration, $program_id, $month_year_id);
			//pr($absUsers);
			
			
			# now you can reference your controller like any other PHP class
			$courseType = $this->listCourseTypeIdsBasedOnMethod($this->cType, "-");
			
			$courseTypeIdArray = explode("-",$courseType);
				
			$cmAll = $ContinuousAssessmentExamsController->courseCodeForASemester($batch_id, $program_id, $month_year_id, $courseTypeIdArray);
			$cm = $ContinuousAssessmentExamsController->truncateEmptyCmIds($cmAll, $processedStudentArray);
			//pr($cm);
			$this->download_template($batch, $academicDetails, $programDetails, $month_year, $studentArray, $cm, $assessment_number, "CAE", $this->cType, $month_year_id, $absUsers);
		}
		} else {
			$this->render('../Users/access_denied');
		}
	}
	
	public function import() {
		$access_result = $this->checkPathAccess($this->Auth->user('id'));
		if (!$access_result) {
		$academics = $this->Student->Academic->find('list');
		$batches = $this->Student->Batch->find('list', array('fields' => array('Batch.batch_period')));
		
		$monthYear = $this->MonthYear->find('all', array(
				'fields' => array('MonthYear.id','MonthYear.year','MonthYear.month_id','Month.month_name'),
				'order' => array('MonthYear.id DESC'),
				'recursive' => 0
		));
		//pr($monthYears);
		$monthyears=array();
		foreach($monthYear as $key => $value){
			$monthyears[$value['MonthYear']['id']] = $value['Month']['month_name']."-".$value['MonthYear']['year'];
		}
		$this->set(compact('batches', 'academics', 'programs', 'monthyears'));
		
		if($this->request->is('post')) {
			//echo getcwd() . "\n";
			//pr(is_readable("test.xlsx"));
			//pr($this->data);
				
			if(!empty($this->request->data['CaeProject']['csv']['name'])) {
				move_uploaded_file($this->request->data['CaeProject']['csv']['tmp_name'], WWW_ROOT . '../sets_upload_received_file/' .$this->request->data['CaeProject']['csv']['name']);
				$filename = WWW_ROOT . '../sets_upload_received_file/'.$this->request->data['CaeProject']['csv']['name'];
			}
			$relook="";
			$objPHPExcel = PHPExcel_IOFactory::load(WWW_ROOT . '../sets_upload_received_file/'.$this->request->data['CaeProject']['csv']['name']);
			$worksheet = $objPHPExcel->setActiveSheetIndex(0);
			//foreach ($objPHPExcel->getWorksheetIterator() as $worksheet) {
			$worksheetTitle     = $worksheet->getTitle();
			$highestRow         = $worksheet->getHighestRow(); // e.g. 10
			$highestColumn      = $worksheet->getHighestDataColumn(); // e.g 'F'
			$highestColumnIndex = PHPExcel_Cell::columnIndexFromString($highestColumn);
			$nrColumns = ord($highestColumn) - 64;
			//	echo "<br>The worksheet ".$worksheetTitle." has ";
			//	echo $nrColumns . ' columns (A-' . $highestColumn . ') ';
			//	echo ' and ' . $highestRow . ' row.';
			//	echo '<br>Data: ';
			$monthYear = $objPHPExcel->getActiveSheet()->getCell('C1')->getValue();
			//pr($monthYear);
			$myArray = explode("-", strtoupper($monthYear));
			//pr($myArray);
			$monthName = trim($myArray[0]);
			$year = trim($myArray[1]);
	
			//$month_id = date('n', strtotime($monthName));
			
			$res_month_id = array_keys($this->months, $monthName); 
			$month_id = $res_month_id[0];
			//echo $month_id;
		
			$month_year_id = $this->MonthYear->retrieveMonthYearIdFromMonthIdAndYear($month_id, $year);
			//pr($month_year_id);
			
			//Academic id
			$academic = $objPHPExcel->getActiveSheet()->getCell('C2')->getValue();
			$academicDetails = $this->Academic->getAcademicDetailsFromAcademicName($academic);
			$academic_id = $academicDetails['id'];
			$academic_short_code = $academicDetails['short_code'];
			//pr($academic_id);
			
			//Program id
			$program = $objPHPExcel->getActiveSheet()->getCell('C3')->getValue();
			$programDetails = $this->Program->getProgramDetailsFromProgramName($program, $academic_id);
			$program_id = $programDetails['id'];
			$program_short_code = $programDetails['short_code'];
		
			//pr($program_id);
			
			$batch = $objPHPExcel->getActiveSheet()->getCell('G1')->getValue();
			$batch_id = $this->Batch->getBatchIdFromText($batch);
			//pr($batch_id);
			
			//pr($marks);
			$assessment_number = $objPHPExcel->getActiveSheet()->getCell('G2')->getValue();
			//pr($assessment_number);
			
			$row = 6;
			$col = 3;
			$arrCourseCode = $this->getCourseCodeFromExcel($col, $row, $highestColumnIndex, $filename);
			//pr($arrCourseCode);
			
			$ContinuousAssessmentExamsController = new ContinuousAssessmentExamsController;
			$courseCodeDetails = $ContinuousAssessmentExamsController->cmGetCmIdfromCourseCode($arrCourseCode['courseCode'], $batch_id, $program_id, $academic_id, $month_year_id);
			//pr($courseCodeDetails);
		
			$coursesNotMapped = array();
			foreach ($courseCodeDetails as $courseCode => $courseCodeDetail) {
				if ($courseCodeDetail['cmId'] == "") {
					$coursesNotMapped[] = $courseCode;
				}
			}
			//pr($coursesNotMapped);
			$mark_status = "Entered";
			$approval_status = 0;
			if(!empty($coursesNotMapped) && count($coursesNotMapped) > 0) {
				//if(empty($coursesNotMapped)) {
				$this->Flash->error(__("Courses are yet to be mapped for Batch $batch and Program $program"));
				return false;
			}
			else {
				$courseType = $this->listCourseTypeIdsBasedOnMethod($this->cType, "-");
				//pr($courseType);
				$courseTypeIdArray = explode("-",$courseType);
				//pr($courseTypeIdArray);
				
				$studentArray = $this->Student->getStudentsWithBatchAndProgram($batch_id, $program_id, $month_year_id);
				//pr($studentArray);
				
				$processedStudentArray = $ContinuousAssessmentExamsController->processStudentArray($studentArray);
				//pr($processedStudentArray);
				
				$cmAll = $ContinuousAssessmentExamsController->courseCodeForASemester($batch_id, $program_id, $month_year_id, $courseTypeIdArray);
				$cm = $ContinuousAssessmentExamsController->truncateEmptyCmIds($cmAll, $processedStudentArray);
				//pr($cm);
					
				//Check if courses are correct from excel and database
					
				$validateCourseCode = $ContinuousAssessmentExamsController->validateIfCourseCodeExists($cm, $courseCodeDetails);
				//pr($validateCourseCode);
					
				if (is_array($validateCourseCode) && count($validateCourseCode) > 0) {
					$cCode = $this->CourseMapping->find('all', array(
							'conditions' => array('CourseMapping.id' => $validateCourseCode),
							'fields' => array('Course.course_code'),
							'recursive' => 0
					));
					$text = "";
					foreach ($cCode as $key => $notMapped) {
						$text.=$notMapped['Course']['course_code'].",";
					}
					$text = substr($text,0,strlen($text)-1);
					//pr($cCode);
					//echo $text;
					$this->Flash->error(__('Course Mapping not available for '.$text));
					return false;
				}
				else if($validateCourseCode == true) {
					//echo "welcome";
					//Check for Course Student Mapping
					$studentMasterEntry = $ContinuousAssessmentExamsController->getStudentsWithBatchAndProgram($batch_id, $program_id, $month_year_id);
					//echo count($studentMasterEntry)."</br>";
					//pr($studentMasterEntry);
		
					$studentId = $ContinuousAssessmentExamsController->listMasterStudents($studentMasterEntry, $month_year_id);
					//echo "data from student table";
					//pr($studentId);
					//pr($courseCodeDetails);
		
					$courseStudentArray = $ContinuousAssessmentExamsController->checkIfStudentsExistsInCSM($studentId, $courseCodeDetails, $month_year_id);
					//pr($courseStudentArray);
		
					if (is_array($courseStudentArray) && count($courseStudentArray) > 0) {
						$studentsNotMapped="";
						foreach ($courseStudentArray as $cmId => $cnt) {
							if ($cnt == 0) {
								$cCode = $this->CourseMapping->find('all', array(
										'conditions' => array('CourseMapping.id' => $cmId),
										'fields' => array('Course.course_code'),
										'recursive' => 0
								));
								foreach ($cCode as $key => $notMapped) {
									$studentsNotMapped.=$notMapped['Course']['course_code'].",";
								}
								$studentsNotMapped = substr($studentsNotMapped,0,strlen($studentsNotMapped)-1);
								$this->Flash->error(__('Students not mapped for Courses '.$studentsNotMapped));
								return false;
							}
						}
						
						//MArk reading starts here
						//Identify course type
						//echo $assessment_number."</br>";
						$number=$assessment_number-1;
						$excelCourseCodeCells = $arrCourseCode['excelCellAddress'];
						//pr($excelCourseCodeCells);
							
						foreach($excelCourseCodeCells as $key => $cellAddress) {
							//pr($cellAddress);
							$column = $cellAddress['col'];
							$row = $cellAddress['row'];
								
							//echo $column." ".$row." ";
							$course_assessment_marks_row = $row-1;
								
							$cae_assessment_object = $worksheet->getCellByColumnAndRow($column, $course_assessment_marks_row);
							$cae_assessment_mark = $cae_assessment_object->getValue();
							//echo $cae_assessment_mark;
							//die;
							$cell = $worksheet->getCellByColumnAndRow($column, $row);
							$courseCode = $cell->getValue();
							$cmId = $courseCodeDetails[$courseCode]['cmId'];
							//echo " - ".$cmId."</br>";
							//$courseTypeId = $this->retrieveCourseTypeFromCourseWithCmId($cmId, 0);
							//pr($courseTypeId);
								
							//$dataToSave = $this->modelToSave($courseTypeId);
							//pr($dataToSave);
							//die;
								
							$caeExists = $this->CaeProject->find('all', array(
									'conditions' => array('CaeProject.course_mapping_id' => $cmId),
									'fields' => array('CaeProject.id'),
									'order' => 'CaeProject.id ASC',
									'recursive' => 0
							));
							//pr($caeExists);
								
							$assessmentCount = count($caeExists);
							$tmp = $assessment_number-1;
							$num = $tmp-$assessmentCount;
								
							//if($assessmentCount >= $tmp) {
							//if ($courseTypeId == 1) {
							if($assessmentCount >= $tmp) {
								if (isset($caeExists[$assessment_number-1]['CaeProject']['id'])) {
									$caeId = $caeExists[$assessment_number-1]['CaeProject']['id'];
								}
								else {
									$data=array();
									$data['CaeProject']['month_year_id'] = $month_year_id;
									$data['CaeProject']['course_mapping_id'] = $cmId;
									$data['CaeProject']['assessment_type'] = "Review";
									$data['CaeProject']['marks'] = $cae_assessment_mark;
									$data['CaeProject']['marks_status'] = "Not Entered";
									$data['CaeProject']['add_status'] = 0;
									$data['CaeProject']['approval_status'] = 0;
									$data['CaeProject']['indicator'] = 0;
									$data['CaeProject']['created_by'] = $this->Auth->user('id');
									$this->CaeProject->create();
									$this->CaeProject->save($data);
									$caeId = $this->CaeProject->getLastInsertID();
								}
							}
							else {
								$this->Flash->error(__('Import the previous '.$num.' caes for course code '.$courseCode));
								return false;
							}
							//}
								
							//	echo $caeId." ".$column." ".$row." ".$highestRow;
								
							$dataRow = $row+1;
							for ($i=$dataRow; $i<=$highestRow; $i++) {
								//echo $i." ".$column;
								$cell = $worksheet->getCellByColumnAndRow($column, $i);
								//echo $cell;
								$marks = $cell->getValue();
									
								if ($marks == 0) {
									$marks = $marks;
								}
								else if ($marks=='A' || $marks=='a') {
									$marks='A';
								}
								else if ($marks == "") {
									$marks=0;
								}
									
								//pr($stu);
									
									
								//echo $marks."</br>";
								$stuCell = $worksheet->getCellByColumnAndRow(1, $i);
								$regNumber = $stuCell->getFormattedValue();
									
								$stu=$this->Student->find('first', array(
										'conditions' => array('Student.registration_number' => $regNumber, 'Student.discontinued_status' => 0),
										'fields' => array('Student.id'),
										'recursive' => 0
								));
								//$stuId = $stu['Student']['id'];
									
								if (isset($stu['Student']['id'])) {
									$stuId = $stu['Student']['id'];
								}
								else {
									$stuCell = $worksheet->getCellByColumnAndRow(0, $i);
									//pr($stuCell);
									//echo "</br>i ".$i;
									$ifAbs = $stuCell->getFormattedValue();
									//echo "</br>".$ifAbs;
										
									$absArray = explode("-", $ifAbs);
									if (!is_int($ifAbs) && $absArray[1]=="ABS") {
										//echo " *** "."ABS"." ** ".$absArray[2]." *** ".$absArray[3];
										$abs_batch_id = $absArray[2];
										$abs_program_id = $absArray[3];
										//echo " *** ".$regNumber;
										$stu=$this->Student->find('first', array(
												'conditions' => array('Student.registration_number' => $regNumber, 'Student.discontinued_status' => 0,
														'Student.batch_id'=>$abs_batch_id, 'Student.program_id'=>$abs_program_id
												),
												'fields' => array('Student.id'),
												'recursive' => 0
										));
										//pr($stu);
										if (isset($stu['Student']['id'])) {
											$stuId = $stu['Student']['id'];
										}
										//echo " *** ".$courseCode;
										$caeId = $this->CourseMapping->getCmIdCaeIdFromBatchIdProgramIdCourseCode($abs_batch_id, $abs_program_id, $courseCode, $assessment_number, "CAE", 4);
									}
								}
								//echo "</br>".$marks." ".$cae_assessment_mark." ".$courseCode." ".$regNumber." ".$caeId." ".$stuId;
									
								if ($marks > $cae_assessment_mark) {
									$relook.=$courseCode."-".$regNumber.",";
									$mark_status = "Not Entered";
									$approval_status = 0;
								}
								else if ($marks != "NA") {
									$conditions = array(
											'ProjectReview.cae_project_id' => $caeId,
											'ProjectReview.month_year_id' => $month_year_id,
											'ProjectReview.student_id' => $stuId
									);
									//pr($conditions);
									if ($this->ProjectReview->hasAny($conditions)){
										//echo "if";
										$this->ProjectReview->query("UPDATE project_reviews set
														marks='".$marks."', modified='".date("Y-m-d H:i:s")."',
														modified_by = ".$this->Auth->user('id')."
														WHERE cae_project_id=".$caeId." AND student_id=".$stuId."
														AND month_year_id=".$month_year_id);
											
									}
									else {
										//echo "else";
										$test = $this->ProjectReview->query("insert into
														project_reviews (student_id, month_year_id, cae_project_id, marks, created_by)
														values (".$stuId.", ".$month_year_id.", ".$caeId.", '".$marks."',
														".$this->Auth->user('id').")");
									}
								}
							}
							$test = $this->CaeProject->query("
												update cae_projects set marks_status='".$mark_status."', add_status=1,
									approval_status=$approval_status,
									modified_by=".$this->Auth->user('id').", modified = '".date("Y-m-d H:i:s")."',
									marks = $cae_assessment_mark WHERE id=".$caeId
									);
							//$this->Flash->success('Proceed '.$caeId.' hi '.$column.' hi '.$row.' hi '.$highestRow.' Datarow '.$dataRow);
							//$this->Flash->success('Successfully imported data');
							//}
							/* else {
							$this->Flash->error(__('Import the previous '.$num.' caes for course code '.$courseCode));
							return false;
							} */
						}
						//Mark reading ends here
					}
				}
					
			}
			//}
			if ($relook <> "")
				$this->Flash->success('Successfully imported data except for : '.$relook);
				else
					$this->Flash->success('Successfully imported data');
		}
		} else {
			$this->render('../Users/access_denied');
		}	
	}
	
	public function calculateCae() {
		$access_result = $this->checkPathAccess($this->Auth->user('id'));
		if (!$access_result) {
		$academics = $this->Student->Academic->find('list');
		$batches = $this->Student->Batch->find('list', array('fields' => array('Batch.batch_period')));
		$this->set(compact('batches', 'academics'/* , 'monthYears' */));
		
		if($this->request->is('post')) {
			//pr($this->data);
				
			$batch_id = $this->request->data['Student']['batch_id'];
			$academic_id = $this->request->data['Student']['academic_id'];
			$program_id = $this->request->data['Student']['program_id'];
			$month_year_id = $this->request->data['month_year_id'];
				
			$ContinuousAssessmentExamsController = new ContinuousAssessmentExamsController;
			# now you can reference your controller like any other PHP class
			$courseType = $this->listCourseTypeIdsBasedOnMethod($this->cType, "-");
			$courseTypeIdArray = explode("-",$courseType);
			//pr($courseTypeIdArray);
			
			$cmAll = $ContinuousAssessmentExamsController->courseCodeForASemester($batch_id, $program_id, $month_year_id, $courseTypeIdArray);
			$courseMappingArray = $ContinuousAssessmentExamsController->truncateEmptyCmIds($cmAll, "-");
			//pr($courseMappingArray);
			
			$caeResult = $this->CaeProject->checkIfCaesExists($courseMappingArray);
			//pr($caeResult);
			
			$caeMarksStatus = false;
			$caeApprovalStatus = false;
		
			if ($caeResult['assessment_count'] == $caeResult['marks_status']) {
				$caeMarksStatus = true;
			}
			if ($caeResult['assessment_count'] == $caeResult['approval_status']) {
				$caeApprovalStatus = true;
			}
			
			if ($caeMarksStatus) {
				if ($caeApprovalStatus) {
				$result = $this->CourseMapping->find('all', array(
						'conditions' => array('CourseMapping.id' => array_keys($courseMappingArray)),
						'fields'=>array('CourseMapping.*'),
						'contain'=>array(
								'Course'=>array(
										'fields'=>array('Course.course_max_marks', 'Course.min_cae_mark', 'Course.max_cae_mark',
												'Course.min_ese_mark', 'Course.max_ese_mark', 'Course.total_min_pass'
										)
								),
								'CaeProject'=>array('fields' => array('CaeProject.*'),
										'conditions'=>array('CaeProject.indicator'=>0, 'CaeProject.month_year_id'=>$month_year_id),
										'ProjectReview' => array('fields'=>array('ProjectReview.student_id', 'ProjectReview.marks',),
												'conditions'=>array('ProjectReview.month_year_id'=>$month_year_id),
										)
								),
								'EseProject'=>array('fields' => array('EseProject.*'),
										'conditions'=>array('EseProject.indicator'=>0, 'EseProject.month_year_id'=>$month_year_id),
										'ProjectViva' => array('fields'=>array('ProjectViva.student_id', 'ProjectViva.marks',),
												'conditions'=>array('ProjectViva.month_year_id'=>$month_year_id),
										)
								),
						),
						'recursive' => 2
				));
				//pr($result);
				$finalArray = array();
				$course_details = array();
				$tmp_cmid_array = array();
				foreach ($result as $key => $markResult) {
					//pr($markResult);
					$cae_assessment_mark = $markResult['CaeProject'][0]['marks'];
					$max_cae_mark = $markResult['Course']['max_cae_mark'];
					//$max_ese_mark = $markResult['Course']['max_ese_mark'];
					$caeProjectArray = array();
					$finalArray[$markResult['CourseMapping']['id']] = array();
					$tmp_cmid_array[$markResult['CourseMapping']['id']]=$markResult['CourseMapping']['id'];
					$course_details = $this->CourseMapping->retrieveCourseDetails($tmp_cmid_array, $month_year_id);
					
					$tmpCae = $markResult['CaeProject'];
					$total_cae_marks = 0;
					foreach ($tmpCae as $key => $caeProjectValue) {
						//pr($caeProjectValue);
						$total_cae_marks+=$caeProjectValue['marks'];
						$tmpArray = array(); $array = array();
						$tmpArray = $caeProjectValue['ProjectReview'];
						//pr($tmpArray);
						foreach ($tmpArray as $k=>$internalArray) {
							$array[$internalArray['student_id']] = $internalArray['marks'];
						}
						$caeProjectArray[] = $array;
					}
						
					$caeArray = $this->convertProject($caeProjectArray, $max_cae_mark, $total_cae_marks);
					//pr($caeArray);
					$finalArray[$markResult['CourseMapping']['id']] = $caeArray;
					//pr($finalArray);
					
					$this->storeMarks($markResult['CourseMapping']['id'], $caeArray, $month_year_id);
					
					$allStudents = $this->Student->getStudentsWithBatchAndProgram($batch_id, $program_id, $month_year_id);
					//pr($allStudents);
					
					$studentInternalMarks = array();
					$internalResult = $this->InternalProject->find('all', array(
							'conditions' => array("InternalProject.course_mapping_id" => $markResult['CourseMapping']['id'],
											"InternalProject.month_year_id"=>$month_year_id
							),
							'fields' => array('InternalProject.student_id', 'InternalProject.marks'),
							'recursive' => 0,
							'order' => array('InternalProject.student_id ASC')
					));
					//pr($internalResult);
					$tmpArray = array();
					foreach ($internalResult as $intResult) {
						$tmpArray[$intResult['InternalProject']['student_id']]=$intResult['InternalProject']['marks'];
					}
					$studentInternalMarks[$markResult['CourseMapping']['id']] = $tmpArray;
					//pr($studentInternalMarks);
					$this->set(compact('allStudents', 'studentInternalMarks', 'course_details'));
					
				}
				
				}
				else {
					$this->Flash->error("CAEs not approved!!!");
				}
			}
			else {
				$this->Flash->error("CAE Marks not approved!!!");
			}
		}
		} else {
			$this->render('../Users/access_denied');
		}
	}
	
	public function getProjectData($program_id, $batch_id, $academic_id, $month_year_id) {

		$ContinuousAssessmentExamsController = new ContinuousAssessmentExamsController;
		# now you can reference your controller like any other PHP class
		$courseType = $this->listCourseTypeIdsBasedOnMethod($this->cType, "-");
		$courseTypeIdArray = explode("-",$courseType);
		//pr($courseTypeIdArray);
		
		$studentArray = $this->Student->getStudentsWithBatchAndProgram($batch_id, $program_id, $month_year_id);
		//pr($studentArray);
		
		$processedStudentArray = $ContinuousAssessmentExamsController->processStudentArray($studentArray);
		//pr($processedStudentArray);
		
		$cmAll = $ContinuousAssessmentExamsController->courseCodeForASemester($batch_id, $program_id, $month_year_id, $courseTypeIdArray);
		$courseMappingArray = $ContinuousAssessmentExamsController->truncateEmptyCmIds($cmAll, $processedStudentArray);
		//pr($courseMappingArray);
		
		$caeResult = $this->CaeProject->checkIfCaesExists($courseMappingArray);
		//pr($caeResult);
		
		$caeMarksStatus = false;
		$caeApprovalStatus = false;
		
		if ($caeResult['assessment_count'] == $caeResult['marks_status']) {
			$caeMarksStatus = true;
		}
		if ($caeResult['assessment_count'] == $caeResult['approval_status']) {
			$caeApprovalStatus = true;
		}
		
		if ($caeMarksStatus) {
			if ($caeApprovalStatus) {
			$result = $this->CourseMapping->find('all', array(
					'conditions' => array('CourseMapping.id' => array_keys($courseMappingArray)),
					'fields'=>array('CourseMapping.*'),
					'contain'=>array(
							'Course'=>array(
									'fields'=>array('Course.course_max_marks', 'Course.min_cae_mark', 'Course.max_cae_mark',
											'Course.min_ese_mark', 'Course.max_ese_mark', 'Course.total_min_pass'
									)
							),
							'CaeProject'=>array('fields' => array('CaeProject.*'),
									'conditions'=>array('CaeProject.indicator'=>0, 'CaeProject.month_year_id'=>$month_year_id),
									'ProjectReview' => array('fields'=>array('ProjectReview.student_id', 'ProjectReview.marks',),
											'conditions'=>array('ProjectReview.month_year_id'=>$month_year_id),
									)
							),
							'EseProject'=>array('fields' => array('EseProject.*'),
									'conditions'=>array('EseProject.indicator'=>0, 'EseProject.month_year_id'=>$month_year_id),
									'ProjectViva' => array('fields'=>array('ProjectViva.student_id', 'ProjectViva.marks',),
											'conditions'=>array('ProjectViva.month_year_id'=>$month_year_id),
									)
							),
					),
					'recursive' => 2
			));
			//pr($result);
			$finalArray = array();
			$course_details = array();
			$tmp_cmid_array = array();
			foreach ($result as $key => $markResult) {
				//pr($markResult);
				$cae_assessment_mark = $markResult['CaeProject'][0]['marks'];
				$max_cae_mark = $markResult['Course']['max_cae_mark'];
				//$max_ese_mark = $markResult['Course']['max_ese_mark'];
				$caeProjectArray = array();
				$finalArray[$markResult['CourseMapping']['id']] = array();
				$tmp_cmid_array[$markResult['CourseMapping']['id']]=$markResult['CourseMapping']['id'];
				$course_details = $this->CourseMapping->retrieveCourseDetails($tmp_cmid_array, $month_year_id);
				
				$tmpCae = $markResult['CaeProject'];
				$total_cae_marks = 0;
				foreach ($tmpCae as $key => $caeProjectValue) {
					//pr($caeProjectValue);
					$total_cae_marks+=$caeProjectValue['marks'];
					$tmpArray = array(); $array = array();
					$tmpArray = $caeProjectValue['ProjectReview'];
					//pr($tmpArray);
					foreach ($tmpArray as $k=>$internalArray) {
						$array[$internalArray['student_id']] = $internalArray['marks'];
					}
					$caeProjectArray[] = $array;
				}
					
				$caeArray = $this->convertProject($caeProjectArray, $max_cae_mark, $total_cae_marks);
				//pr($caeArray);
				$finalArray[$markResult['CourseMapping']['id']] = $caeArray;
				
				$allStudents = $this->Student->getStudentsWithBatchAndProgram($batch_id, $program_id, $month_year_id);
				//pr($allStudents);
				
				$studentInternalMarks = array();
				$internalResult = $this->InternalProject->find('all', array(
						'conditions' => array("InternalProject.course_mapping_id" => $markResult['CourseMapping']['id']),
						'fields' => array('InternalProject.student_id', 'InternalProject.marks'),
						'recursive' => 0,
						'order' => array('InternalProject.student_id ASC')
				));
				//pr($internalResult);
				$tmpArray = array();
				foreach ($internalResult as $intResult) {
					$tmpArray[$intResult['InternalProject']['student_id']]=$intResult['InternalProject']['marks'];
				}
				$studentInternalMarks[$markResult['CourseMapping']['id']] = $tmpArray;
				//pr($studentInternalMarks);
				$this->set(compact('allStudents', 'studentInternalMarks', 'course_details'));
				
			}
			
			}
			else {
				$this->Flash->error("CAEs not approved!!!");
			}
		}
		else {
			$this->Flash->error("CAE Marks not approved!!!");
		}
		//pr($finalArray);
		$this->layout=false;
	}
	
	public function convertProject($caeProjectArray, $max_cae_mark, $total_cae_marks) {
		$caeArray = array();
		$caeTotalArray = $this->array_mesh($caeProjectArray);
		foreach ($caeTotalArray as $student_id => $mark) {
			$final_mark = round($mark * $max_cae_mark / $total_cae_marks);
			$caeArray[$student_id] = $final_mark;
		}
		
		return $caeArray;
	}
	
	public function storeMarks($cm_id, $caeArray, $month_year_id) {
		foreach ($caeArray as $student_id => $marks) {
			$data_exists=$this->InternalProject->find('first', array(
					'conditions' => array(
							'InternalProject.course_mapping_id'=>$cm_id,
							'InternalProject.month_year_id'=>$month_year_id,
							'InternalProject.student_id'=>$student_id,
					),
					'fields' => array('InternalProject.id'),
					'recursive' => 0
			));
			//pr($arrear_data_exists);
			$data=array();
			$data['InternalProject']['course_mapping_id'] = $cm_id;
			$data['InternalProject']['month_year_id'] = $month_year_id;
			$data['InternalProject']['student_id'] = $student_id;
			$data['InternalProject']['marks'] = $marks;
				
			if(isset($data_exists['InternalProject']['id']) && $data_exists['InternalProject']['id']>0) {
				$afs_id = $data_exists['InternalProject']['id'];
				$data['InternalProject']['id'] = $afs_id;
				$data['InternalProject']['modified_by'] = $this->Auth->user('id');
				$data['InternalProject']['modified'] = date("Y-m-d H:i:s");
			}
			else {
				$this->InternalProject->create($data);
				$data['InternalProject']['created_by'] = $this->Auth->user('id');
			}
			$this->InternalProject->save($data);
		}
	}
	
	function array_mesh($caeProjectArray) {
		// Combine multiple associative arrays and sum the values for any common keys
		// The function can accept any number of arrays as arguments
		// The values must be numeric or the summed value will be 0
	
		// Get the number of arguments being passed
		//$numargs = func_num_args();
		$numargs = count($caeProjectArray);
		// Save the arguments to an array
		//$arg_list = func_get_args();
	
		// Create an array to hold the combined data
		$out = array();
	
		// Loop through each of the arguments
		//for ($i = 0; $i < $numargs; $i++) {
		foreach ($caeProjectArray as $key => $caeArray) {
			//$in = $arg_list[$i]; // This will be equal to each array passed as an argument
			$in = $caeArray;
	
			// Loop through each of the arrays passed as arguments
			foreach($in as $key => $value) {
				// If the same key exists in the $out array
				if(array_key_exists($key, $out)) {
					// Sum the values of the common key
					$sum = $in[$key] + $out[$key];
					// Add the key => value pair to array $out
					$out[$key] = $sum;
				}else{
					// Add to $out any key => value pairs in the $in array that did not have a match in $out
					$out[$key] = $in[$key];
				}
			}
		}
	
		return $out;
		}
}
