<?php
App::uses('AppController', 'Controller');
App::import('Controller', 'ContinuousAssessmentExams');
/**
 * EseProjects Controller
 *
 * @property EseProject $EseProject
 * @property PaginatorComponent $Paginator
 * @property FlashComponent $Flash
 * @property SessionComponent $Session
 */
class EseProjectsController extends AppController {
	
	public $cType = "project";
	public $uses = array( "EseProject", "CaeProject", "ContinuousAssessmentExam", "Batch", "Lecturer", "EsePractical", "CourseStudentMapping",
			"Course", "User", "CourseFaculty", "Student", "Academic", "CaePractical", "StudentAuthorizedBreak",
			"GrossAttendance", "Cae", "CourseMode", "CourseMapping", "MonthYear", "Attendance", "StudentMark",
			"InternalExam", "Program", "CourseType", "InternalExam", "InternalPractical", "ProjectReview", "ProjectViva");
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
		$access_result = $this->checkPathAccess($this->Auth->user('id'));
		if (!$access_result) {
			/* $this->EseProject->recursive = 0;
			$this->set('eseProjects', $this->Paginator->paginate()); */
			$academics = $this->Student->Academic->find('list');
			$batches = $this->Student->Batch->find('list', array('fields' => array('Batch.batch_period')));
			
			$monthYears = $this->MonthYear->getAllMonthYears();
			
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
		if (!$this->EseProject->exists($id)) {
			throw new NotFoundException(__('Invalid ese project'));
		}
		$options = array('conditions' => array('EseProject.' . $this->EseProject->primaryKey => $id));
		$this->set('eseProject', $this->EseProject->find('first', $options));
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
			$this->EseProject->create();
			if ($this->EseProject->save($this->request->data)) {
				$this->Flash->success(__('The ese project has been saved.'));
				return $this->redirect(array('action' => 'index'));
			} else {
				$this->Flash->error(__('The ese project could not be saved. Please, try again.'));
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
							'EseProject'=>array(
									'conditions'=>array('EseProject.indicator'=>0),
									'fields'=>array('EseProject.id', 'EseProject.marks')
							)
					)
			));
			$course_code = $result['Course']['course_code'];
			$cae_count = count($result['EseProject']);
			//echo "Count : ".$cae_count;
			//$max_cae_mark = $result['Course']['max_cae_mark'];
			$month_year_id = $result['CourseMapping']['month_year_id'];
			if (isset($result['EseProject']) && count($result['EseProject'])>0) {
				$max_cae_mark = $result['EseProject'][0]['marks'];
			}
			else {
				$max_cae_mark = $this->request->data['EseProject']['marks'];
			}
			
			if ($cae_count <=2) {
				$data = array();
				$data['EseProject']['month_year_id']=$month_year_id;
				$data['EseProject']['course_mapping_id']=$this->request->data['course_mapping_id'];
				$data['EseProject']['assessment_type']='Review';
				$data['EseProject']['marks']=$max_cae_mark;
				$data['EseProject']['approval_status']=0;
				$data['EseProject']['indicator']=0;
				$data['EseProject']['created_by']= $this->Auth->user('id');
				$this->EseProject->create();
		
				if ($this->EseProject->save($data)) {
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
		
		$courseMappings = $this->EseProject->CourseMapping->find('list');
		//$semesters = $this->EseProject->Semester->find('list');
		$lecturers = $this->EseProject->Lecturer->find('list');
		$users = $this->EseProject->User->find('list');
		$modifiedUsers = $this->EseProject->ModifiedUser->find('list');
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
		if (!$this->EseProject->exists($id)) {
			throw new NotFoundException(__('Invalid ese project'));
		}
		if ($this->request->is(array('post', 'put'))) {
			if ($this->EseProject->save($this->request->data)) {
				$this->Flash->success(__('The ese project has been saved.'));
				return $this->redirect(array('action' => 'index'));
			} else {
				$this->Flash->error(__('The ese project could not be saved. Please, try again.'));
			}
		} else {
			$options = array('conditions' => array('EseProject.' . $this->EseProject->primaryKey => $id));
			$this->request->data = $this->EseProject->find('first', $options);
		}
		$monthYears = $this->EseProject->MonthYear->find('list');
		$courseMappings = $this->EseProject->CourseMapping->find('list');
		$lecturers = $this->EseProject->Lecturer->find('list');
		$this->set(compact('monthYears', 'courseMappings', 'lecturers'));
	} */

/**
 * delete method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	/* public function delete($id = null) {
		$this->EseProject->id = $id;
		if (!$this->EseProject->exists()) {
			throw new NotFoundException(__('Invalid ese project'));
		}
		$this->request->allowMethod('post', 'delete');
		if ($this->EseProject->delete()) {
			$this->Flash->success(__('The ese project has been deleted.'));
		} else {
			$this->Flash->error(__('The ese project could not be deleted. Please, try again.'));
		}
		return $this->redirect(array('action' => 'index'));
	} */
	
	public function eseProjectDisplay($batch_id, $academic_id, $program_id, $month_year_id, $currentMethod=NULL) {
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
	
	public function addMarks($batchId, $academicId, $programId, $eseId, $caeNumber) {
		//echo $caeId;
		$cntRecords = $this->ProjectReview->find("count", array(
				'conditions' => array('ProjectReview.cae_project_id'=>$eseId)
		));
		//pr($cntRecords);
		if ($cntRecords > 0) {
			$this->Flash->success(__('Marks already entered.'));
			return $this->redirect(array('controller' => 'EseProjects', 'action' => 'index'));
		}
		else {
			$cm = $this->EseProject->find('all', array(
					'conditions' => array('EseProject.id'=>$eseId),
					'fields'=>array('EseProject.course_mapping_id', 'EseProject.marks'),
					'contain'=>array(
							'CourseMapping'=>array(
									'fields'=>array('CourseMapping.month_year_id')
							)
					)
			));
			//pr($cm);
			$cm_id = $cm[0]['EseProject']['course_mapping_id'];
			$maxMarks = $cm[0]['EseProject']['marks'];
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
											'conditions' => array('ProjectReview.cae_project_id'=>$eseId),
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
				
			$this->set(compact('results', 'cm_id', 'batchId', 'academicId', 'programId', 'cmId', 'eseId', 'month_year', 'maxMarks',
					'caeNumber', 'month_year_id'));
		}
	
	}
	
	public function editMarks($batchId, $academicId, $programId, $eseId, $caeNumber) {
		//echo $caeId;
		$cm = $this->EseProject->find('all', array(
				'conditions' => array('EseProject.id'=>$eseId),
				'fields'=>array('EseProject.course_mapping_id', 'EseProject.marks'),
				'contain'=>array(
						'CourseMapping'=>array(
								'fields'=>array('CourseMapping.month_year_id')
						)
				)
		));
		//pr($cm);
		$cm_id = $cm[0]['EseProject']['course_mapping_id'];
		$maxMarks = $cm[0]['EseProject']['marks'];
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
								'ProjectViva' => array(
										'conditions' => array('ProjectViva.ese_project_id'=>$eseId,
												'ProjectViva.month_year_id'=>$month_year_id
										),
										'fields'=>array('ProjectViva.marks'),
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
		$this->set(compact('results', 'cm_id', 'batchId', 'academicId', 'programId', 'cmId', 'eseId', 'month_year', 'maxMarks',
				'caeNumber', 'month_year_id', 'publish_status'));
	
	}
	
	public function view($batchId, $academicId, $programId, $eseId, $caeNumber) {
		//echo $caeId;
		$cm = $this->EseProject->find('all', array(
				'conditions' => array('EseProject.id'=>$eseId),
				'fields'=>array('EseProject.course_mapping_id', 'EseProject.marks', 'EseProject.approval_status'),
				'contain'=>array(
						'CourseMapping'=>array(
								'fields'=>array('CourseMapping.month_year_id')
						)
				)
		));
		//pr($cm);
		$cm_id = $cm[0]['EseProject']['course_mapping_id'];
		$maxMarks = $cm[0]['EseProject']['marks'];
		$month_year_id = $cm[0]['CourseMapping']['month_year_id'];
		$approval_status = $cm[0]['EseProject']['approval_status'];
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
								'ProjectViva' => array(
										'conditions' => array('ProjectViva.ese_project_id'=>$eseId,
												'ProjectViva.month_year_id'=>$month_year_id
										),
										'fields'=>array('ProjectViva.marks'),
								)
						)
				)
		));
		//pr($results);
		$ContinuousAssessmentExamsController = new ContinuousAssessmentExamsController;
		# now you can reference your controller like any other PHP class
		$month_year = $ContinuousAssessmentExamsController->getMonthYear($cm_id);
		//pr($course_type_id);
			
		$this->set(compact('results', 'cm_id', 'batchId', 'academicId', 'programId', 'cmId', 'eseId', 'month_year', 'maxMarks',
				'caeNumber', 'month_year_id', 'approval_status', 'currentModel'));
	
	}
	public function saveESEProjectMarks($ese_project_id = null, $markEntry = null, $student_id = null, $month_year_id = null){
		$ese_data_exists=$this->ProjectViva->find('first', array(
				'conditions' => array(
						'ProjectViva.ese_project_id'=>$ese_project_id,
						'ProjectViva.month_year_id'=>$month_year_id,
						'ProjectViva.student_id'=>$student_id,
				),
				'fields' => array('ProjectViva.id'),
				'recursive' => 0
		));
		$data=array();
		$data['ProjectViva']['month_year_id'] = $month_year_id;
		$data['ProjectViva']['student_id'] = $student_id;
		$data['ProjectViva']['ese_project_id'] = $ese_project_id;
		$data['ProjectViva']['marks'] = $markEntry;
		if(isset($ese_data_exists['ProjectViva']['id']) && $ese_data_exists['ProjectViva']['id']>0) {
			$id = $ese_data_exists['ProjectViva']['id'];
			$data['ProjectViva']['id'] = $id;
			$data['ProjectViva']['modified_by'] = $this->Auth->user('id');
			$data['ProjectViva']['modified'] = date("Y-m-d H:i:s");
		}
		else {
			$this->ProjectViva->create($data);
			$data['ProjectViva']['created_by'] = $this->Auth->user('id');
		}
		if ($this->ProjectViva->save($data)) {
			$this->updateEseProject($ese_project_id);
			echo true;
		}
		else {
			echo false;
		}
		exit;
	}
	
	public function updateEseProject($ese_project_id) {
		$this->EseProject->updateAll(
				/* UPDATE FIELD */
				array(
						"EseProject.add_status" => 1,
						"EseProject.marks_status" => "'Entered'",
				),
				/* CONDITIONS */
				array(
						"EseProject.id" => $ese_project_id
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
			$batch_id = $this->data['EseProject']['batch_id'];
			$batch = $this->Batch->getBatch($batch_id);
	
			$academic_id = $this->data['EseProject']['academic_id'];
			$academicDetails = $this->Academic->getAcademic($academic_id);
	
			$program_id = $this->data['Student']['program_id'];
			$programDetails = $this->Program->getProgram($program_id);
	
			$month_year_id = $this->data['EseProject']['month_year_id'];
			$month_year = $this->MonthYear->getMonthYear($month_year_id);
	
			$studentArray = $this->Student->getStudentsWithBatchAndProgram($batch_id, $program_id, $month_year_id);
			//pr($studentArray);
			
			$ContinuousAssessmentExamsController = new ContinuousAssessmentExamsController;
			# now you can reference your controller like any other PHP class
			
			$processedStudentArray = $ContinuousAssessmentExamsController->processStudentArray($studentArray);
			
			$absResult = $this->StudentAuthorizedBreak->findAbsStudents();
			//pr($absResult);
			$currentBatchDuration = $this->Batch->getBatchDuration($batch_id);
				
			//echo $currentBatchDuration." ".$month_year_id;
			$absUsers = $this->processAbsResult($absResult, $currentBatchDuration, $program_id, $month_year_id);
			//pr($absUsers);
			
			
			$courseType = $this->listCourseTypeIdsBasedOnMethod($this->cType, "-");
				
			$courseTypeIdArray = explode("-",$courseType);
			
			$assessment_number = 1;
			$cmAll = $ContinuousAssessmentExamsController->courseCodeForASemester($batch_id, $program_id, $month_year_id, $courseTypeIdArray);
			$cm = $ContinuousAssessmentExamsController->truncateEmptyCmIds($cmAll, $processedStudentArray);
			//pr($cm);
			$this->download_template($batch, $academicDetails, $programDetails, $month_year, $studentArray, $cm, $assessment_number, "ESE", $this->cType, $month_year_id, $absUsers);
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
	
			if(!empty($this->request->data['EseProject']['csv']['name'])) {
				move_uploaded_file($this->request->data['EseProject']['csv']['tmp_name'], WWW_ROOT . '../sets_upload_received_file/' .$this->request->data['EseProject']['csv']['name']);
				$filename = WWW_ROOT . '../sets_upload_received_file/'.$this->request->data['EseProject']['csv']['name'];
			}
			$relook="";
			$objPHPExcel = PHPExcel_IOFactory::load(WWW_ROOT . '../sets_upload_received_file/'.$this->request->data['EseProject']['csv']['name']);
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
						
						//Mark reading starts here
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
						
							$cell = $worksheet->getCellByColumnAndRow($column, $row);
							$courseCode = $cell->getValue();
							$cmId = $courseCodeDetails[$courseCode]['cmId'];
							//echo " - ".$cmId."</br>";
							//$courseTypeId = $this->retrieveCourseTypeFromCourseWithCmId($cmId, 0);
							//pr($courseTypeId);
								
							//$dataToSave = $this->modelToSave($courseTypeId);
							//pr($dataToSave);
							//die;
								
							$caeExists = $this->EseProject->find('all', array(
									'conditions' => array('EseProject.course_mapping_id' => $cmId),
									'fields' => array('EseProject.id'),
									'order' => 'EseProject.id ASC',
									'recursive' => 0
							));
						
							if (isset($caeExists[0]['EseProject']['id'])) {
								$caeId = $caeExists[0]['EseProject']['id'];
							}
							else {
								$data=array();
								$data['EseProject']['month_year_id'] = $month_year_id;
								$data['EseProject']['course_mapping_id'] = $cmId;
								$data['EseProject']['assessment_type'] = "Viva";
								$data['EseProject']['marks'] = $cae_assessment_mark;
								$data['EseProject']['marks_status'] = "Not Entered";
								$data['EseProject']['add_status'] = 0;
								$data['EseProject']['approval_status'] = 0;
								$data['EseProject']['indicator'] = 0;
								$data['EseProject']['created_by'] = $this->Auth->user('id');
								$this->EseProject->create();
								$this->EseProject->save($data);
								$caeId = $this->EseProject->getLastInsertID();
							}
						
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
										$caeId = $this->CourseMapping->getCmIdCaeIdFromBatchIdProgramIdCourseCode($abs_batch_id, $abs_program_id, $courseCode, $assessment_number, "ESE", 4);
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
											'ProjectViva.ese_project_id' => $caeId,
											'ProjectViva.month_year_id' => $month_year_id,
											'ProjectViva.student_id' => $stuId
									);
									//pr($conditions);
									if ($this->ProjectViva->hasAny($conditions)){
										//echo "if";
										$this->ProjectViva->query("UPDATE project_vivas set
														marks='".$marks."', modified='".date("Y-m-d H:i:s")."',
														modified_by = ".$this->Auth->user('id')."
														WHERE ese_project_id=".$caeId." AND student_id=".$stuId."
														AND month_year_id=".$month_year_id);
											
									}
									else {
										//echo "else";
										$test = $this->ProjectViva->query("insert into
														project_vivas (student_id, month_year_id, ese_project_id, marks, created_by)
														values (".$stuId.", ".$month_year_id.", ".$caeId.", '".$marks."',
														".$this->Auth->user('id').")");
									}
								}
							}
							$test = $this->EseProject->query("
									update ese_projects set marks_status = 'Entered', add_status=1,
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
			if ($relook <> "") {
				$this->Flash->success('Successfully imported data except for : '.$relook);
			}
			else {
				$this->Flash->success('Successfully imported data');
			}
		}
		} else {
			$this->render('../Users/access_denied');
		}
	}
	
	public function calculate() {
		$access_result = $this->checkPathAccess($this->Auth->user('id'));
		if (!$access_result) {
		$academics = $this->Student->Academic->find('list');
		$batches = $this->Student->Batch->find('list', array('fields' => array('Batch.batch_period')));
		$monthYears = $this->MonthYear->getAllMonthYears();
		$this->set(compact('batches', 'academics', 'monthYears'));
		if ($this->request->is('post')) {
			//pr($this->data);
			$varAjax = 0;
			$batch_id = $this->request->data['Project']['batch_id'];
			$academic_id = $this->request->data['Project']['academic_id'];
			$program_id = $this->request->data['Student']['program_id'];
			$month_year_id = $this->request->data['Project']['month_year_id'];
			$option = "All";
				
			$result = $this->getStatus($batch_id, $academic_id, $program_id, $month_year_id, $option, $varAjax);
				
			$finalArray = $result['project'];
			$courseMappingArray = $result['courseMapping'];
			$studentArray = $result['student'];
			//pr($finalArray);
			//$this->storeFinalMarksinDatabase($finalArray, $studentArray, $month_year_id);
	
			$this->set(compact('finalArray', 'courseMappingArray', 'studentArray'));
				
		}
		} else {
			$this->render('../Users/access_denied');
		}
	}
	
	public function getStatus($batch_id, $academic_id, $program_id, $month_year_id, $option, $varAjax) {
		//echo $program_id." ".$batch_id." ".$academic_id." ".$month_year_id;
	
		$ContinuousAssessmentExamsController = new ContinuousAssessmentExamsController;
		# now you can reference your controller like any other PHP class
	
		$courseType = $this->listCourseTypeIdsBasedOnMethod($this->cType, "-");
		$courseTypeIdArray = explode("-",$courseType);
		//pr($courseTypeIdArray);
		
		$studentArray = $this->Student->getStudentsWithBatchAndProgram($batch_id, $program_id, $month_year_id);
		//pr($studentArray);
		
		$processedStudentArray = $ContinuousAssessmentExamsController->processStudentArray($studentArray);
		//pr($processedStudentArray);
		
		$assessment_number = 1;
	
		$cmAll = $ContinuousAssessmentExamsController->courseCodeForASemester($batch_id, $program_id, $month_year_id, $courseTypeIdArray);
		$courseMappingArray = $ContinuousAssessmentExamsController->truncateEmptyCmIds($cmAll, "-");
		//pr($courseMappingArray);
	
		$caeResult = $this->CaeProject->checkIfCaesExists($courseMappingArray);
		//pr($caeResult);
		
		$eseResult = $this->EseProject->checkIfEsesExists($courseMappingArray);
		//pr($eseResult);
		
		$caeMarksStatus = false;
		$caeApprovalStatus = false;
	
		if ($caeResult['assessment_count'] == $caeResult['marks_status']) {
			$caeMarksStatus = true;
		}
		if ($caeResult['assessment_count'] == $caeResult['approval_status']) {
			$caeApprovalStatus = true;
		}
	
		$eseMarksStatus = false;
		$eseApprovalStatus = false;
	
		if ($eseResult['assessment_count'] == $eseResult['marks_status']) {
			$eseMarksStatus = true;
		}
	
		if ($eseResult['assessment_count'] == $eseResult['approval_status']) {
			$eseApprovalStatus = true;
		}
	
		if ($eseMarksStatus && $caeMarksStatus) {
			if ($eseApprovalStatus && $caeApprovalStatus) {
	
				//$modelsToGet = array("0" => "CaePractical", "1" => "EsePractical");
				$projectResult = array();
				$sumArray = array();
				//foreach ($modelsToGet as $key => $model) {
				$result = $this->getStudentsMarksWithCmIdAndModel($courseMappingArray, $month_year_id, $option);
				//pr($result);
				$finalArray = array();
				$cae_assessment_mark = 0;
				foreach ($result as $key => $markResult) {
					//pr($markResult);
					
					$caeArray=array();
					$minCaeStatus = array();
					
					$internal = $markResult['InternalProject'];
					foreach ($internal as $internalKey => $internalArray) {
						$caeArray[$internalArray['student_id']] = $internalArray['marks'];
						$min_cae_pass_mark = round($markResult['Course']['max_cae_mark'] * $markResult['Course']['min_cae_mark'] / 100);
						if ($internalArray['marks'] >= $min_cae_pass_mark)
								$minCaeStatus[$internalArray['student_id']] = 1;
						else
							$minCaeStatus[$internalArray['student_id']] = 0;
					}
					
					$externalMark = $markResult['EseProject'][0]['marks'];
					$external = $markResult['EseProject'][0]['ProjectViva'];
					//pr($external);
					$eseConvetTo = $markResult['Course']['max_ese_mark'];
					//echo $externalMark." ".$eseConvetTo;
					$eseArray=array();
					$minEseStatus = array();
					foreach ($external as $externalKey => $externalArray) {
						$eseTmpArray[$externalArray['student_id']] = $externalArray['marks'];
						
						$stu_mark = $externalArray['marks'];
						$stu_converted_mark = round(($stu_mark * $eseConvetTo) / $externalMark);
						
						$eseArray[$externalArray['student_id']] = $stu_converted_mark;
						//echo $eseConvetTo." *** ".$markResult['Course']['min_ese_mark']." *** ".$stu_converted_mark;
						if ($stu_converted_mark >= round($eseConvetTo * $markResult['Course']['min_ese_mark'] /100))
							$minEseStatus[$externalArray['student_id']] = 1;
						else
							$minEseStatus[$externalArray['student_id']] = 0;
					}
						
					$courseMaxMarks = $markResult['Course']['course_max_marks'];
					$minPassPercentage = $markResult['Course']['total_min_pass'];
					
					//pr($eseTmpArray);
					//pr($eseArray);
					//pr($minEseStatus);
					
					
				}
				//pr($finalArray);
				if (isset($markResult['StudentMark']) && !empty($markResult['StudentMark']) && count($markResult['StudentMark'] > 0)) {
					
					$total = $markResult['StudentMark'];
					$totalArray=array();
					$totalStatus=array();
					foreach ($total as $totalKey => $totalValue) {
						$totalScore[$totalValue['student_id']] = $totalValue['marks'];
						if ($totalValue['status']=="Pass") $displayOption = 1; else $displayOption = 0;
						$totalStatus[$totalValue['student_id']] = $displayOption;
					}
					$finalArray[$markResult['CourseMapping']['id']]['total'] = $totalScore;
					$finalArray[$markResult['CourseMapping']['id']]['totalStatus'] = $totalStatus;
				}
				else { 
					
				$totalResult = $this->computeTotalOfCaeAndEse($caeArray, $eseArray, $option, $courseMaxMarks, $minPassPercentage, $minEseStatus, $minCaeStatus);
				//pr($total);
				
				$total = $totalResult['total'];
				$totalStatus = $totalResult['status'];
				
				$finalArray[$markResult['CourseMapping']['id']]['total'] = $totalResult['total'];
				$finalArray[$markResult['CourseMapping']['id']]['totalStatus'] = $totalResult['status'];
				//pr($total);
				}
				//pr($markResult);
				//pr($markResult['CourseMapping']);
				//$caeArray = array_intersect_key($array1, $array2)
				$finalArray[$markResult['CourseMapping']['id']]['courseDetails'] = $markResult['Course'];
				$finalArray[$markResult['CourseMapping']['id']]['CAE'] = array_intersect_key($caeArray, $totalStatus);
				$finalArray[$markResult['CourseMapping']['id']]['ESE'] = array_intersect_key($eseArray, $totalStatus);
				$finalArray[$markResult['CourseMapping']['id']]['minEseStatus'] = array_intersect_key($minEseStatus, $totalStatus);
				$finalArray[$markResult['CourseMapping']['id']]['minCaeStatus'] = array_intersect_key($minCaeStatus, $totalStatus);
				$finalArray[$markResult['CourseMapping']['id']]['total'] = array_intersect_key($finalArray[$markResult['CourseMapping']['id']]['total'], $totalStatus);
				$finalArray[$markResult['CourseMapping']['id']]['totalStatus'] = array_intersect_key($totalStatus, $finalArray[$markResult['CourseMapping']['id']]['total']);
				//$studentArray = $this->Student->getStudentsInfo($batch_id, $program_id);
	
				$studentArray = $this->CourseStudentMapping->getStudents($markResult['CourseMapping']['id'], $batch_id, $program_id);
				//pr($studentArray); 
				$projectResult['project'] = $finalArray;
				$projectResult['student'] = $studentArray;
				$projectResult['courseMapping'] = $courseMappingArray;
				//pr($projectResult); 
				if ($varAjax == 0) {
					return $projectResult;
				}
				else if ($varAjax == 1) {
					//pr($finalArray);
					$this->set(compact('finalArray', $finalArray));
					$this->set(compact('courseMappingArray', $courseMappingArray));
					$this->set(compact('studentArray', $studentArray));
					$this->layout=false;
				}
			}
			else {
				$this->Flash->error('Assessments Not approved');
			}
		}
		else {
			$this->Flash->error('Marks Not entered');
		}
		//$this->set(compact('caeResult', 'eseResult'));
		//$this->layout=false;
	}
	
	public function computeTotalOfCaeAndEse($caeMarks, $eseMarks, $option, $courseMaxMarks, $minPassPercentage, $minEseStatus, $minCaeStatus) {
		//pr($option);
		//pr($caeMarks);
		//pr($eseMarks);
		//pr($minEseStatus);
		$minPass = $courseMaxMarks * $minPassPercentage / 100;
		$finalResult = array();
		$sums = array();
		$result = array();
		foreach (array_keys($caeMarks + $eseMarks) as $key) { 
			$value = (isset($caeMarks[$key]) ? $caeMarks[$key] : 0) + (isset($eseMarks[$key]) ? $eseMarks[$key] : 0);
			if ($minEseStatus[$key] == 1 && $minCaeStatus[$key] == 1) {
				if($value >= $minPass && ($option=="All" || $option=="Pass")) {
					$result[$key] = 1;
				}
				else if($value < $minPass && ($option=="All" || $option=="Fail")) {
					$result[$key] = 0;
				}
			}
			else {
				$result[$key] = 0;
			}
			$sums[$key] = $value;
		}
		//pr($result);
		$finalResult['total'] = $sums;
		$finalResult['status'] = $result;
		//print_r($sums);
		return $finalResult;
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
	
	
	public function getStudentsMarksWithCmIdAndModel($courseMappingArray, $month_year_id, $option) {
	
		SWITCH ($option) {
			CASE "All":
				$filterCondition = array (
				'StudentMark.month_year_id' => $month_year_id,
				'OR' => array(
				array('StudentMark.status' => "Pass"),
				array('StudentMark.status' => "Fail"),
				));
				break;
			CASE "Pass":
				$filterCondition = array (
				'StudentMark.month_year_id' => $month_year_id,
				'StudentMark.status' => "Pass",
				);
				break;
			CASE "Fail":
				$filterCondition = array (
				'StudentMark.month_year_id' => $month_year_id,
				'StudentMark.status' => "Fail",
				);
				break;
		}
	
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
						'InternalProject'=>array('fields'=>array('InternalProject.student_id', 'InternalProject.marks',),
										'conditions'=>array('InternalProject.month_year_id'=>$month_year_id),
						),
						'StudentMark'=>array('fields' => array('StudentMark.month_year_id', 'StudentMark.student_id', 'StudentMark.course_mapping_id', 'StudentMark.marks', 'StudentMark.status'),
								'conditions'=>$filterCondition,
						),
				),
				'recursive' => 2
		));
		//pr($result);
		return $result;
	}
	
	public function report() {
		$academics = $this->Student->Academic->find('list');
		$batches = $this->Student->Batch->find('list', array('fields' => array('Batch.batch_period')));
		$monthYear = $this->MonthYear->find('all');
		$monthYears = array();
		for ($i=0; $i<count($monthYear);$i++) {
			$monthYears[$monthYear[$i]['MonthYear']['id']] = $monthYear[$i]['Month']['month_name']." - ".$monthYear[$i]['MonthYear']['year'];
		}
		$this->set(compact('batches', 'academics', 'monthYears'));
	}
	
	public function getMarks($batch_id = null, $academic_id = null, $program_id = null, $month_year_id = null, $option, $varAjax) {
	
		$ContinuousAssessmentExamsController = new ContinuousAssessmentExamsController;
		# now you can reference your controller like any other PHP class
	
		$courseType = $this->listCourseTypeIdsBasedOnMethod($this->cType, "-");
		$courseTypeIdArray = explode("-",$courseType);
		//pr($courseTypeIdArray);
	
		$studentArray = $this->Student->getStudentsWithBatchAndProgram($batch_id, $program_id, $month_year_id);
		//pr($studentArray);
		
		$processedStudentArray = $ContinuousAssessmentExamsController->processStudentArray($studentArray);
		//pr($processedStudentArray);
		
		$assessment_number = 1;
	
		$cmAll = $ContinuousAssessmentExamsController->courseCodeForASemester($batch_id, $program_id, $month_year_id, $courseTypeIdArray);
		$courseMappingArray = $ContinuousAssessmentExamsController->truncateEmptyCmIds($cmAll, $processedStudentArray);
	
		$result = $this->getStatus($batch_id, $academic_id, $program_id, $month_year_id, $option, $varAjax);
		//pr($result);
		$finalArray = $result['project'];
		$courseMappingArray = $result['courseMapping'];
		$studentArray = $result['student'];
	
		$this->set(compact('finalArray', 'courseMappingArray', 'studentArray', 'option'));
		$this->layout=false;
	
	}
	
	public function finalProject() {
		$access_result = $this->checkPathAccess($this->Auth->user('id'));
		if (!$access_result) {
		$academics = $this->Student->Academic->find('list');
		$batches = $this->Student->Batch->find('list', array('fields' => array('Batch.batch_period')));
	
		$monthYears = $this->MonthYear->getAllMonthYears();
		$this->set(compact('batches', 'academics', 'monthYears'));
		if(isset($_REQUEST['PRINT'])) {
			$batch_id = $this->request->data['Project']['batch_id'];
			$academic_id = $this->request->data['Project']['academic_id'];
			$program_id = $this->request->data['Student']['program_id'];
			$month_year_id = $this->request->data['Project']['month_year_id'];
			$option = "All";
			$varAjax=0;
			$result = $this->getStatus($batch_id, $academic_id, $program_id, $month_year_id, $option, $varAjax);
			//pr($result);
			$courseMappingArray = $result['courseMapping'];
			$studentArray = $this->Student->getStudentsInfo($batch_id, $program_id);
			$this->set(compact('month_year_id','batch_id','program_id','result', 'studentArray', 'courseMappingArray'));
			$this->layout = false;
			$this->layout = 'print';
			$this->render('finalProjectPrint');
			return false;
		}
		} else {
			$this->render('../Users/access_denied');
		}
	}
}