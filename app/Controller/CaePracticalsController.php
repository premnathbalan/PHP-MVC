<?php
App::uses('AppController', 'Controller');
App::import('Controller', 'ContinuousAssessmentExams');
App::import('Vendor', 'PHPExcel', array('file' => 'PHPExcel'.DS.'IOFactory.php'));
/**
 * 			$dbo = $this->CourseMapping->getDatasource();
			$logs = $dbo->getLog();
			$lastLog = end($logs['log']);
			echo $lastLog['query'];
			
 * CaePracticals Controller
 *
 * @property CaePractical $CaePractical
 * @property PaginatorComponent $Paginator
 * @property FlashComponent $Flash
 * @property yComponent $y
 * @property SessionComponent $Session
 */
class CaePracticalsController extends AppController {

	public $cType = "practical";
	public $uses = array("CaePractical", "EsePractical", "ContinuousAssessmentExam", "Batch", "Lecturer", "EsePractical", "CourseStudentMapping", 
			"Course", "User", "CourseFaculty", "Student", "Academic", "Project",  
			"CaeProject", "GrossAttendance", "Cae", "CourseMode", "CourseMapping", "MonthYear", "Attendance", 
			"InternalExam", "Program", "CourseType", "InternalExam", "InternalPractical", "StudentMark",
			"StudentAuthorizedBreak"
	);
/**
 * Components
 *
 * @var array
 */
	public $components = array( 'Flash', 'Session');
	var $helpers = array('Html', 'Form', 'PhpExcel.PhpExcel');
/**
 * index method
 *
 * @return void
 */
	public function index() {
		$this->CaePractical->recursive = 0;
		$this->set('caePracticals');
	}

/**
 * view method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function view($id = null) {
		if (!$this->CaePractical->exists($id)) {
			throw new NotFoundException(__('Invalid cae practical'));
		}
		$options = array('conditions' => array('CaePractical.' . $this->CaePractical->primaryKey => $id));
		$this->set('caePractical', $this->CaePractical->find('first', $options));
	}

/**
 * add method
 *
 * @return void
 */
	public function add() {
		if ($this->request->is('post')) {
			$this->CaePractical->create();
			if ($this->CaePractical->save($this->request->data)) {
				$this->Flash->success(__('The cae practical has been saved.'));
				return $this->redirect(array('action' => 'index'));
			} else {
				$this->Flash->error(__('The cae practical could not be saved. Please, try again.'));
			}
		}
		$courseMappings = $this->CaePractical->CourseMapping->find('list');
		$semesters = $this->CaePractical->Semester->find('list');
		$lecturers = $this->CaePractical->Lecturer->find('list');
		$users = $this->CaePractical->User->find('list');
		$modifiedUsers = $this->CaePractical->ModifiedUser->find('list');
		$this->set(compact('courseMappings', 'semesters', 'lecturers', 'users', 'modifiedUsers'));
	}

/**
 * edit method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function edit($id = null) {
		if (!$this->CaePractical->exists($id)) {
			throw new NotFoundException(__('Invalid cae practical'));
		}
		if ($this->request->is(array('post', 'put'))) {
			if ($this->CaePractical->save($this->request->data)) {
				$this->Flash->success(__('The cae practical has been saved.'));
				return $this->redirect(array('action' => 'index'));
			} else {
				$this->Flash->error(__('The cae practical could not be saved. Please, try again.'));
			}
		} else {
			$options = array('conditions' => array('CaePractical.' . $this->CaePractical->primaryKey => $id));
			$this->request->data = $this->CaePractical->find('first', $options);
		}
		$courseMappings = $this->CaePractical->CourseMapping->find('list');
		$semesters = $this->CaePractical->Semester->find('list');
		$lecturers = $this->CaePractical->Lecturer->find('list');
		$users = $this->CaePractical->User->find('list');
		$modifiedUsers = $this->CaePractical->ModifiedUser->find('list');
		$this->set(compact('courseMappings', 'semesters', 'lecturers', 'users', 'modifiedUsers'));
	}

/**
 * delete method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function delete($id = null) {
		$this->CaePractical->id = $id;
		if (!$this->CaePractical->exists()) {
			throw new NotFoundException(__('Invalid cae practical'));
		}
		$this->request->allowMethod('post', 'delete');
		if ($this->CaePractical->delete()) {
			$this->Flash->success(__('The cae practical has been deleted.'));
		} else {
			$this->Flash->error(__('The cae practical could not be deleted. Please, try again.'));
		}
		return $this->redirect(array('action' => 'index'));
	}
	
	public function practical() {
		$access_result = $this->checkPathAccess($this->Auth->user('id'));
		if (!$access_result) {
			$academics = $this->Student->Academic->find('list');
			$batches = $this->Student->Batch->find('list', array('fields' => array('Batch.batch_period')));
			$monthYears = $this->MonthYear->getAllMonthYears();
			
			$action = $this->action;
			$this->set(compact('batches', 'academics', 'programs', 'monthYears'/* , 'courseTypes' */, 'action'));
		} else {
			$this->render('../Users/access_denied');
		}
	}
	
	public function caePracticalDisplay($batch_id, $academic_id, $program_id, $month_year_id, $currentMethod=NULL) {
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
	
	/* public function courseMappingBasedOnCourseType($batch_id, $program_id, $courseTypeIdArray) {
		$tempArray = $this->CourseMapping->find('all', array(
				'conditions' => array(
						'CourseMapping.batch_id'=>$batch_id,
						'CourseMapping.program_id' => $program_id,
						'Course.course_type_id' => $courseTypeIdArray
				),
				'recursive' => 1
		));
		return $tempArray;
	} */
	
	public function practicalDownloadTemplate() {
		$access_result = $this->checkPathAccess($this->Auth->user('id'));
		if (!$access_result) {
		$academics = $this->Student->Academic->find('list');
		$batches = $this->Student->Batch->find('list', array('fields' => array('Batch.batch_period')));
		$monthYears = $this->MonthYear->getAllMonthYears();
		$action = $this->action;
		$this->set(compact('batches', 'academics', 'programs', 'monthYears'));
		
		if($this->request->is('post')) {
			//pr($this->data);
			$batch_id = $this->request->data['CaePractical']['batch_id'];
			$batch = $this->Batch->getBatch($batch_id);
			
			$academic_id = $this->request->data['CaePractical']['academic_id'];
			$academicDetails = $this->Academic->getAcademic($academic_id);
			//$academic = $academicDetails['name'];
			//$academic_short_code = $academicDetails['short_code'];
			
			$program_id = $this->request->data['Student']['program_id'];
			$programDetails = $this->Program->getProgram($program_id);
			
			$month_year_id = $this->request->data['month_year_id'];
			$month_year = $this->MonthYear->getMonthYear($month_year_id);
			
			//$marks = $this->request->data['CaePractical']['marks'];
			
			$ContinuousAssessmentExamsController = new ContinuousAssessmentExamsController;
			# now you can reference your controller like any other PHP class
			
			$studentArray = $this->Student->getStudentsWithBatchAndProgram($batch_id, $program_id, $month_year_id);
			//pr($studentArray);
			
			$processedStudentArray = $ContinuousAssessmentExamsController->processStudentArray($studentArray);
			
			$absResult = $this->StudentAuthorizedBreak->findAbsStudents();
			//pr($absResult);
			$currentBatchDuration = $this->Batch->getBatchDuration($batch_id);
			
			//echo $currentBatchDuration." ".$month_year_id;
			$absUsers = $this->processAbsResult($absResult, $currentBatchDuration, $program_id, $month_year_id);
			//pr($absUsers);
			
			
			$courseType = $this->listCourseTypeIdsBasedOnMethod($this->cType, "-");
			$courseTypeIdArray = explode("-",$courseType);
			//pr($courseTypeIdArray);
			$assessment_number = 1;
			
			$cmAll = $ContinuousAssessmentExamsController->courseCodeForASemester($batch_id, $program_id, $month_year_id, $courseTypeIdArray);
			$cm = $ContinuousAssessmentExamsController->truncateEmptyCmIds($cmAll, $processedStudentArray);
			//pr($cm);
			
			$this->download_template($batch, $academicDetails, $programDetails, $month_year, $studentArray, $cm, $assessment_number, "CAE", $this->cType, $month_year_id, $absUsers);
			
		}
		} else {
			$this->render('../Users/access_denied');
		}
	}
	
	public function practicalImport() {
		$access_result = $this->checkPathAccess($this->Auth->user('id'));
		if (!$access_result) {
		if($this->request->is('post')) {
			//pr($this->data);
			$relook = "";
			if(!empty($this->request->data['CaePractical']['csv']['name'])) {
				move_uploaded_file($this->request->data['CaePractical']['csv']['tmp_name'], WWW_ROOT . '../sets_upload_received_file/' .$this->request->data['CaePractical']['csv']['name']);
				$filename = WWW_ROOT . '../sets_upload_received_file/'.$this->request->data['CaePractical']['csv']['name'];
			}
			$objPHPExcel = PHPExcel_IOFactory::load(WWW_ROOT . '../sets_upload_received_file/'.$this->request->data['CaePractical']['csv']['name']);
			$worksheet = $objPHPExcel->setActiveSheetIndex(0);
			//foreach ($objPHPExcel->getWorksheetIterator() as $worksheet) {
			$worksheetTitle     = $worksheet->getTitle();
			$highestRow         = $worksheet->getHighestRow(); // e.g. 10
			$highestColumn      = $worksheet->getHighestDataColumn(); // e.g 'F'
			$highestColumnIndex = PHPExcel_Cell::columnIndexFromString($highestColumn);
			$nrColumns = ord($highestColumn) - 64;
			
			//Month year id
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
					$studentMasterEntry = $ContinuousAssessmentExamsController->getStudentsWithBatchAndProgram($batch_id, $program_id, $month_year_id);
					//echo count($studentMasterEntry)."</br>";
					//pr($studentMasterEntry);
					
					$studentId = $ContinuousAssessmentExamsController->listMasterStudents($studentMasterEntry, $month_year_id);
					//pr($studentId);
					
					$courseStudentArray = $ContinuousAssessmentExamsController->checkIfStudentsExistsInCSM($studentId, $courseCodeDetails, $month_year_id);
					
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
							//pr($arrCourseCode);
							$excelCourseCodeCells = $arrCourseCode['excelCellAddress'];
							foreach($excelCourseCodeCells as $course_code => $cellAddress) {
								//pr($cellAddress);
								$column = $cellAddress['col'];
								$row = $cellAddress['row'];
								$cell = $worksheet->getCellByColumnAndRow($column, $row);
								$courseCode = $cell->getValue();
							
								$cmId = $courseCodeDetails[$courseCode]['cmId'];
								//pr($cmId);
								$caeExists = $this->CaePractical->find('all', array(
										'conditions' => array('CaePractical.course_mapping_id' => $cmId),
										'fields' => array('CaePractical.id'),
										'order' => 'CaePractical.id ASC',
										'limit' => 1,
										'recursive' => 0
								));
								//pr($caeExists);
								if (isset($caeExists[0]['CaePractical']['id'])) {
									$caeId = $caeExists[0]['CaePractical']['id'];
								}
								else {
									$data=array();
									$data['CaePractical']['month_year_id'] = $month_year_id;
									$data['CaePractical']['course_mapping_id'] = $cmId;
									$data['CaePractical']['assessment_type'] = "CAE";
									$data['CaePractical']['marks'] = $arrCourseCode['courseCode'][$course_code];
									$data['CaePractical']['marks_status'] = "Not Entered";
									$data['CaePractical']['add_status'] = 0;
									$data['CaePractical']['approval_status'] = 0;
									$data['CaePractical']['indicator'] = 0;
									$data['CaePractical']['created_by'] = $this->Auth->user('id');
									$this->CaePractical->create();
									$this->CaePractical->save($data);
									$caeId = $this->CaePractical->getLastInsertID();
								}
								//echo $caeId." ".$column." ".$row." ".$highestRow;
								//die;
								$dataRow = $row+1;
								for ($i=$dataRow; $i<=$highestRow; $i++) {
										
									//echo $i." ".$column;
									$cell = $worksheet->getCellByColumnAndRow($column, $i);
									//echo $cell;
										
									$stuCell = $worksheet->getCellByColumnAndRow(1, $i);
									$regNumber = $stuCell->getFormattedValue();
										
									$stu=$this->Student->find('first', array(
											'conditions' => array('Student.registration_number' => $regNumber, 'Student.discontinued_status' => 0),
											'fields' => array('Student.id'),
											'recursive' => 0
									));
									//pr($stu);
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
											$caeId = $this->CourseMapping->getCmIdCaeIdFromBatchIdProgramIdCourseCode($abs_batch_id, $abs_program_id, $courseCode, $assessment_number, "CAE", 2);
										}
									}
										
									$marks = $cell->getValue();
									//pr($marks);
									
									if ($marks == "0") {
										//$marks = $marks;
										$marks = '0';
									}
									else if ($marks=='A' || $marks=='a') {
										$marks='A';
									}
									else if ($marks == "") {
										$marks='0';
									}
									//echo $marks;
									if ($marks > $arrCourseCode['courseCode'][$course_code]) {
										$relook.=$course_code."-".$regNumber.",";
										$mark_status = "Not Entered";
										$approval_status = 0;
									}
									else if ($marks != "NA") {
										$conditions = array(
												"InternalPractical.cae_practical_id" => $caeId,
												"InternalPractical.student_id" => $stuId
										);
										//pr($conditions);
										if ($this->InternalPractical->hasAny($conditions)){
											$this->InternalPractical->query("UPDATE internal_practicals set
												marks='".$marks."', modified='".date("Y-m-d H:i:s")."',
														modified_by = ".$this->Auth->user('id').",
														month_year_id = ".$month_year_id."
												WHERE cae_practical_id=".$caeId." AND student_id=".$stuId);
												
										}
										else {
											$test = $this->InternalPractical->query("
												insert into internal_practicals (month_year_id, student_id, cae_practical_id,
												marks, created_by) values
												(".$month_year_id.", ".$stuId.", ".$caeId.", '".$marks."',
												".$this->Auth->user('id').")");
										}
									}
								}
							
								$test = $this->CaePractical->query("
									update cae_practicals set marks_status='".$mark_status."', add_status=1,
										approval_status=$approval_status
										WHERE id=".$caeId);
								//$this->Flash->success('Successfully imported data');
							}
							//MArk reading ends here
					}
					
				}
			}
			if ($relook <> "") 
				$this->Flash->success('Successfully imported data except for : '.$relook);
			else 
				$this->Flash->success('Successfully imported data');
		}
		} else {
			$this->render('../Users/access_denied');
		}
	}

	public function viewCae($batch_id, $academic_id, $program_id, $caeId, $number, $month_year_id) {
		$result = $this->CaePractical->find('all', array(
			'conditions' => array('CaePractical.id' => $caeId)	
		));
		$caePracticalResult = $result[0]['InternalPractical'];
		//pr($caePracticalResult);
		$caeMarks = array();
		foreach ($caePracticalResult as $key => $value) {
			$caeMarks[$value['student_id']] = array('id' => $value['id'], 'marks'=>$value['marks']);
		}
		$studentArray = $this->Student->getStudentsWithBatchAndProgram($batch_id, $program_id, $month_year_id);
		$this->set(compact('batch_id', 'academic_id', 'program_id', 'caeId', 'number', 'result', 'studentArray', 'caeMarks'));
	}
	
	
	public function approveCaePractical($caeId) {
		echo $caeId;
		$data=array(); 
		$data['CaePractical']['id'] = $caeId;
		$data['CaePractical']['approval_status'] = 1;
		$this->CaePractical->Save($data);
		exit;
	}
	
	public function editCaePractical($batch_id, $academic_id, $program_id, $caeId, $number, $month_year_id) {
		/* $result = $this->CaePractical->find('all', array(
			'conditions' => array('CaePractical.id' => $caeId)	
		));
		//pr($result);
		$marks = $result[0]['CaePractical']['marks'];
		$cm_id = $result[0]['CaePractical']['course_mapping_id'];
		$caePracticalResult = $result[0]['InternalPractical']; */
		//pr($caePracticalResult);
		
		$cm = $this->CaePractical->find('all', array(
			'conditions' => array('CaePractical.id'=>$caeId),
			//'fields'=>array('CaePractical.course_mapping_id'),
			'contain'=>false
		));
		$marks = $cm[0]['CaePractical']['marks'];
		$cm_id = $cm[0]['CaePractical']['course_mapping_id'];
		//pr($cm);
		$caePracticalResult = $this->CourseStudentMapping->find('all', array(
			'conditions' => array(
				'CourseStudentMapping.indicator'=>0, 'CourseStudentMapping.course_mapping_id'=>$cm_id
			),
			'fields' => array('CourseStudentMapping.student_id', 'CourseStudentMapping.indicator'),
			'contain' => array(
				'Student' => array(
					'conditions' => array('Student.batch_id'=>$batch_id, 'Student.program_id'=>$program_id, 'Student.discontinued_status'=>0),
					'fields'=>array('Student.id', 'Student.name', 'Student.registration_number'),
					'InternalPractical' => array(
						'conditions' => array('InternalPractical.cae_practical_id'=>$caeId),
						'fields'=>array('InternalPractical.marks'),
					),
					'StudentMark' => array(
							'conditions' => array('StudentMark.course_mapping_id'=>$cm_id),
							'fields'=>array('StudentMark.marks'),
					),
				)
			)
		));
		
		//pr($caePracticalResult);
		
		$caeMarks = array();
		foreach ($caePracticalResult as $key => $value) {
			$caeMarks[$value['Student']['id']] = array('id' => $value['Student']['InternalPractical'][0]['id'], 'marks'=>$value['Student']['InternalPractical'][0]['marks']);
		}
		//$studentArray = $this->Student->getStudentsWithBatchAndProgram($batch_id, $program_id, $month_year_id);
		
		$publish_status = $this->StudentMark->find("count", array(
			'conditions'=>array('StudentMark.course_mapping_id'=>$cm_id),
		));
		//pr($publish_status);
		$this->set(compact('batch_id', 'academic_id', 'program_id', 'caeId', 'number', 'studentArray', 'month_year_id', 
				'caeMarks', 'marks', 'cm_id', 'publish_status'));
		$this->set('result', $caePracticalResult);
		$this->set('cmId', $cm_id);
		
		if ($this->request->is('post')) {
			//pr($this->data);
			
			$bool = false;
			//pr($this->data);
			$caeId = $this->request->data['CaePractical']['cae_id'];
			$studentDetails = $this->request->data['CaePractical']['student_id'];
			$auth_user = $this->Auth->user('id');
			
			foreach ($studentDetails as $key => $student_id) {
				$id = $this->request->data['CaePractical']['id'][$student_id];
				$marks = $this->request->data['CaePractical']['marks'][$student_id];
				$data = array();
				$data['InternalPractical']['id'] = $id;
				$data['InternalPractical']['marks'] = $marks;
				$data['InternalPractical']['modified_by'] = $auth_user;
				$data['InternalPractical']['modified'] = date("Y-m-d H:i:s");
				$this->InternalPractical->save($data);
				
			}
			$this->Flash->success('Cae Practical has been edited');
			$this->redirect(array('controller' => 'CaePracticals', 'action'=>'practical'));
		}
	}
	
	public function addCaePracticalMarks($batch_id, $academic_id, $program_id, $caeId, $number, $month_year_id) {
		/* $result = $this->CaePractical->find('all', array(
				'conditions' => array('CaePractical.id' => $caeId)
		));
		//pr($result);
		$cm_id = $result[0]['CourseMapping']['id'];
		
		$studentArray = $this->Student->getStudentsWithBatchAndProgram($batch_id, $program_id, $month_year_id);
		$totalStudentCount = count($studentArray);
		$this->set(compact('batch_id', 'academic_id', 'program_id', 'caeId', 'number', 'result', 'studentArray')); */
		//$modelToSave = $this->modelToSave($currentModelId);
		
		$res = $this->InternalPractical->query("select count(*) as cntRecords from internal_practicals 
				where cae_practical_id=$caeId");
		$cntRecords = $res[0][0]['cntRecords'];
		//echo $cntRecords; 
		if ($cntRecords > 0) {
			$this->Flash->success(__('Marks already entered.'));
			return $this->redirect(array('controller' => 'CaePracticals', 'action' => 'practical'));
		}
		else {
			$cm = $this->CaePractical->find('all', array(
					'conditions' => array('CaePractical.id'=>$caeId),
					'fields'=>array('CaePractical.course_mapping_id'),
					'contain'=>false
			));
			//pr($cm);
			 
			$cm_id = $cm[0]['CaePractical']['course_mapping_id'];
			$results = $this->CourseStudentMapping->find('all', array(
					'conditions' => array(
							'CourseStudentMapping.indicator'=>0, 'CourseStudentMapping.course_mapping_id'=>$cm_id
					),
					'fields' => array('CourseStudentMapping.student_id'),
					'contain' => array(
							'Student' => array(
									'conditions' => array('Student.batch_id'=>$batch_id, 'Student.program_id'=>$program_id,
											'Student.discontinued_status'=>0),
									'fields'=>array('Student.id', 'Student.name', 'Student.registration_number'),
									'InternalPractical' => array(
											'conditions' => array('InternalPractical.cae_practical_id'=>$caeId),
											'fields'=>array('InternalPractical.marks'),
									)
							)
					)
			));
			//pr($results);
			$totalStudentCount = count($results);
			$this->set(compact('batch_id', 'academic_id', 'program_id', 'caeId', 'number', 'result'));
			$this->set('result', $results);
			$this->set('cmId', $cm_id);
			$this->set('month_year_id', $month_year_id);
		}
		
		if ($this->request->is('post')) {
			//pr($this->data);
			$bool = false;
			//pr($this->data);
			$caeId = $this->request->data['CaePractical']['cae_id'];
			$studentDetails = $this->request->data['CaePractical']['student_id'];
			$auth_user = $this->Auth->user('id');
			$i = 0;
			foreach ($studentDetails as $key => $student_id) {
				$marks = $this->request->data['CaePractical']['marks'][$student_id];
				$data = array();
				$data['InternalPractical']['cae_practical_id'] = $caeId;
				$data['InternalPractical']['month_year_id'] = $month_year_id;
				$data['InternalPractical']['marks'] = $marks;
				$data['InternalPractical']['student_id'] = $student_id;
				$data['InternalPractical']['created_by'] = $auth_user;
				$this->InternalPractical->create();
				$this->InternalPractical->save($data);
				$i = $i+1;
			}
			
			$data = array();
			$data['CaePractical']['id']=$caeId;
			$data['CaePractical']['add_status'] = 1;
			$data['CaePractical']['modified'] = date("Y-m-d H:i:s");
			$data['CaePractical']['modified_by'] = $auth_user;
			
			if($i >= $totalStudentCount){
				$data['CaePractical']['marks_status']="Entered";
			}
			else {
				$data['CaePractical']['marks_status']="Not Entered";
			}
			$this->CaePractical->save($data);
			//pr($this->data);
			$this->Flash->success('Cae Practical has been added');
			$this->redirect(array('controller' => 'CaePracticals', 'action'=>'practical'));
		}
		
	}
	
	public function addCaePractical() {
		$academics = $this->Student->Academic->find('list');
		$batches = $this->Student->Batch->find('list', array('fields' => array('Batch.batch_period')));
		$ContinuousAssessmentExamsController = new ContinuousAssessmentExamsController;
		
		$monthyears = $ContinuousAssessmentExamsController->findMonthYear();
		$cType = $this->cType;
		$this->set(compact('batches', 'academics', 'monthyears', 'cType'));
		
		if ($this->request->is('post')) {
			//pr($this->data);
			$modelToSaveArray=array('CAE'=>'CaePractical', 'ESE'=>'EsePractical');
			$monthYear = $this->getMonthYearFromSemesterId($this->data['CaePractical']['batch_id'], $this->data['Student']['program_id'], $this->data['semester_id']);
			//pr($monthYear);
			 
			$cm = array();
			$cm[$this->request->data['course_mapping_id']] = $this->request->data['course_mapping_id'];
			
			$course_details = $this->CourseMapping->retrieveCourseDetails($cm, $monthYear['CourseMapping']['month_year_id']);
			//pr($course_details);
			
			$cae_mark = $course_details[$this->request->data['course_mapping_id']]['max_cae_mark'];
			$ese_mark = $course_details[$this->request->data['course_mapping_id']]['max_ese_mark'];
			
			//echo $cae_mark." ".$ese_mark;
			
			$bool = false;
			//$cae = $this->request->data['CaePractical'];
			//pr($cae);
			foreach ($modelToSaveArray as $assessment_type => $modelToSave) {
				$marks = ""; 
				$data = array();
				//$data['Cae']['number']=$caeNumber;
				$data[$modelToSave]['course_mapping_id']=$this->request->data['course_mapping_id'];
				$data[$modelToSave]['marks_status']="Not Entered";
				$data[$modelToSave]['add_status']=0;
				$data[$modelToSave]['month_year_id']=$monthYear['CourseMapping']['month_year_id'];
				$data[$modelToSave]['approval_status']=0;
				$data[$modelToSave]['indicator']=0;
				$data[$modelToSave]['created_by']= $this->Auth->user('id');
				
				if ($assessment_type == "CAE") $marks = $cae_mark;
				if ($assessment_type == "ESE") $marks = $ese_mark;
				
				$data[$modelToSave]['assessment_type']=$assessment_type;
				$data[$modelToSave]['marks']=$marks;
				$this->$modelToSave->create();
				$this->$modelToSave->save($data);
				$bool = true;
			}
			if ($bool) {
				$this->Flash->success(__('The CAE has been created.'));
				$this->redirect(array('controller' => 'CaePracticals', 'action'=>'practical'));
			} else {
				$this->Flash->error(__('The CAE Practical cannot be created. Please, try again.'));
			}
			
		}
	}
	
	public function findNoOfCaes($cmId, $template) {
		//pr($template);
		$renderView = "";
		SWITCH ($template) {
			case "theoryAndPracticalTemplate":
				//echo "theory and practical";
				$cae = $this->CaePractical->find('all', array(
				'conditions' => array('CaePractical.course_mapping_id' => $cmId, 'CaePractical.indicator' => 0,
				'CaePractical.assessment_type !=' => 'Theory'),
				'fields' => array('CaePractical.id', 'CaePractical.assessment_type', 'CaePractical.marks',
				'CaePractical.marks_status','CaePractical.approval_status', 'CaePractical.created_by',
				'CaePractical.course_mapping_id'),
				'recursive' => -1
				));
				$renderView = "theory_and_practical_template";
				$action = "practical";
				break;
			case "practicalTemplate":
				//echo "practical";
				$cae = $this->CaePractical->find('all', array(
				'conditions' => array('CaePractical.course_mapping_id' => $cmId, 'CaePractical.indicator' => 0,
				'CaePractical.assessment_type !=' => 'Theory'),
				'fields' => array('CaePractical.id', 'CaePractical.assessment_type', 'CaePractical.marks',
				'CaePractical.marks_status','CaePractical.approval_status', 'CaePractical.created_by',
				'CaePractical.course_mapping_id'),
				'recursive' => -1
				));
				$renderView = "practical_template";
				$action = "practical";
				//echo $renderView;
				break;
		}
		//pr(count($cae));
		//pr($cae);
		if (count($cae) > 0) {
			//echo "Available";
			$enableButton = 1;
			$this->set(compact('cae', 'template', 'action', 'enableButton'));
			//$this->render("project_template");
		}
		else {
			$enableButton = 0;
			$this->set(compact('enableButton'));
			$this->render($renderView, false);
		}
		$this->layout = false;
	}
	
}
