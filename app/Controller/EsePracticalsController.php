<?php

App::uses('AppController', 'Controller');
App::import('Controller', 'ContinuousAssessmentExams');
App::import('Vendor', 'PHPExcel', array('file' => 'PHPExcel'.DS.'IOFactory.php'));
include '../Controller/Constants.php';

/**
 * EsePracticals Controller
 *
 * @property EsePractical $EsePractical
 * @property PaginatorComponent $Paginator
 * @property FlashComponent $Flash
 * @property SessionComponent $Session
 */

class EsePracticalsController extends AppController {
	public $cType = "practical";
	public $cTypeValue = array(2, 6);
	public $uses = array("EsePractical", "ContinuousAssessmentExam", "StudentMark", "Batch", "Lecturer", "EsePractical", 
			"CourseStudentMapping", "User", "Student", "Academic", "CaePractical", "CourseMode", "CourseMapping", "MonthYear", 
			"Attendance", "InternalExam", "Program", "CourseType", "InternalExam", "InternalPractical", "Practical",
			"StudentAuthorizedBreak");

/**
 * Helpers
 *
 * @var array
 */
	var $helpers = array('Html', 'Form', 'PhpExcel.PhpExcel');
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
		$this->EsePractical->recursive = 0;
		$this->set('esePracticals', $this->Paginator->paginate());
	}



/**

 * view method

 *

 * @throws NotFoundException

 * @param string $id

 * @return void

 */

	public function view($id = null) {
		if (!$this->EsePractical->exists($id)) {
			throw new NotFoundException(__('Invalid ese practical'));
		}
		$options = array('conditions' => array('EsePractical.' . $this->EsePractical->primaryKey => $id));
		$this->set('esePractical', $this->EsePractical->find('first', $options));
	}



/**

 * add method

 *

 * @return void

 */

	public function add() {
		if ($this->request->is('post')) {
			$this->EsePractical->create();
			if ($this->EsePractical->save($this->request->data)) {
				$this->Flash->success(__('The ese practical has been saved.'));
				return $this->redirect(array('action' => 'index'));
			} else {
				$this->Flash->error(__('The ese practical could not be saved. Please, try again.'));
			}
		}
		$courseMappings = $this->EsePractical->CourseMapping->find('list');
		$semesters = $this->EsePractical->Semester->find('list');
		$lecturers = $this->EsePractical->Lecturer->find('list');
		$this->set(compact('courseMappings', 'semesters', 'lecturers'));
	}



/**

 * edit method

 *

 * @throws NotFoundException

 * @param string $id

 * @return void

 */

	public function edit($id = null) {
		if (!$this->EsePractical->exists($id)) {
			throw new NotFoundException(__('Invalid ese practical'));
		}
		if ($this->request->is(array('post', 'put'))) {
			if ($this->EsePractical->save($this->request->data)) {
				$this->Flash->success(__('The ese practical has been saved.'));
				return $this->redirect(array('action' => 'index'));
			} else {
				$this->Flash->error(__('The ese practical could not be saved. Please, try again.'));
			}
		} else {
			$options = array('conditions' => array('EsePractical.' . $this->EsePractical->primaryKey => $id));
			$this->request->data = $this->EsePractical->find('first', $options);
		}
		$courseMappings = $this->EsePractical->CourseMapping->find('list');
		$semesters = $this->EsePractical->Semester->find('list');
		$lecturers = $this->EsePractical->Lecturer->find('list');
		$this->set(compact('courseMappings', 'semesters', 'lecturers'));
	}



/**

 * delete method

 *

 * @throws NotFoundException

 * @param string $id

 * @return void

 */

	public function delete($id = null) {
		$this->EsePractical->id = $id;
		if (!$this->EsePractical->exists()) {
			throw new NotFoundException(__('Invalid ese practical'));
		}
		$this->request->allowMethod('post', 'delete');
		if ($this->EsePractical->delete()) {
			$this->Flash->success(__('The ese practical has been deleted.'));
		} else {
			$this->Flash->error(__('The ese practical could not be deleted. Please, try again.'));
		}
		return $this->redirect(array('action' => 'index'));
	}

	/* public function esePracticals() {
		$academics = $this->Student->Academic->find('list');
		$batches = $this->Student->Batch->find('list', array('fields' => array('Batch.batch_period')));
		$monthYear = $this->MonthYear->find('all');
		//pr($monthYear);
		$monthYears = array();
		for ($i=0; $i<count($monthYear);$i++) {
			$monthYears[$monthYear[$i]['MonthYear']['id']] = $monthYear[$i]['Month']['month_name']." - ".$monthYear[$i]['MonthYear']['year'];
		}
		//pr($monthYears);
		$this->set(compact('batches', 'academics', 'programs', 'monthYears'));
	}

	public function eseDisplay($batch_id, $academic_id, $program_id, $month_year_id) {
		$conditions = array(
				"EsePractical.indicator" => 0,
		);
		$tempArray = $this->EsePractical->find('all', array(
				'conditions' => $conditions,
				'recursive' => 2,
				'order' => array('CourseMapping.id', 'EsePractical.id DESC'),
		));
		$dbo = $this->CourseMapping->getDatasource();
		$logs = $dbo->getLog();
		$lastLog = end($logs['log']);
		echo $lastLog['query'];
		pr($tempArray);
		$this->layout=false;
	} */

	

	public function practical() {
		$access_result = $this->checkPathAccess($this->Auth->user('id'));
		if (!$access_result) {
			$academics = $this->Student->Academic->find('list');
			$batches = $this->Student->Batch->find('list', array('fields' => array('Batch.batch_period')));
			$monthYears = $this->MonthYear->getAllMonthYears();
			//pr($monthYears);
			$action = $this->action;
			$this->set(compact('batches', 'academics', 'programs', 'monthYears'/* , 'courseTypes' */, 'action'));
		} else {
			$this->render('../Users/access_denied');
		}
	}

	

	public function esePracticalDisplay($batch_id, $academic_id, $program_id, $month_year_id, $currentMethod=NULL) {
		//echo $currentMethod;
		$ContinuousAssessmentExamsController = new ContinuousAssessmentExamsController;
		# now you can reference your controller like any other PHP class
		
		$course_type_id = $this->listCourseTypeIdsBasedOnMethod($currentMethod, "both");
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
			
			

			$courseType = $this->listCourseTypeIdsBasedOnMethod($this->cType, "both");

			$courseTypeIdArray = explode("-",$courseType);
			//pr($courseTypeIdArray);

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

	public function practicalImport() {
		$access_result = $this->checkPathAccess($this->Auth->user('id'));
		if (!$access_result) {
		if($this->request->is('post')) {
			//pr($this->data);
			if(!empty($this->request->data['EsePractical']['csv']['name'])) {
				move_uploaded_file($this->request->data['EsePractical']['csv']['tmp_name'], WWW_ROOT . '../sets_upload_received_file/' .$this->request->data['EsePractical']['csv']['name']);
				$filename = WWW_ROOT . '../sets_upload_received_file/'.$this->request->data['EsePractical']['csv']['name'];
			}
			$relook="";
			$objPHPExcel = PHPExcel_IOFactory::load(WWW_ROOT . '../sets_upload_received_file/'.$this->request->data['EsePractical']['csv']['name']);
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

			//$caeMarks = $objPHPExcel->getActiveSheet()->getCell('G9')->getValue();
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
				$courseType = $this->listCourseTypeIdsBasedOnMethod($this->cType, "both");
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
						//else {
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
								$caeExists = $this->EsePractical->find('all', array(
										'conditions' => array('EsePractical.course_mapping_id' => $cmId),
										'fields' => array('EsePractical.id'),
										'order' => 'EsePractical.id ASC',
										'limit' => 1,
										'recursive' => 0
								));
								//pr($caeExists);
								if (isset($caeExists[0]['EsePractical']['id'])) {
									$caeId = $caeExists[0]['EsePractical']['id'];
								}
								else {
									$data=array();
									$data['EsePractical']['month_year_id'] = $month_year_id;
									$data['EsePractical']['course_mapping_id'] = $cmId;
									$data['EsePractical']['assessment_type'] = "ESE";
									$data['EsePractical']['marks'] = $arrCourseCode['courseCode'][$course_code];
									$data['EsePractical']['marks_status'] = "Not Entered";
									$data['EsePractical']['add_status'] = 0;
									$data['EsePractical']['approval_status'] = 0;
									$data['EsePractical']['indicator'] = 0;
									$data['EsePractical']['created_by'] = $this->Auth->user('id');
									$this->EsePractical->create();
									$this->EsePractical->save($data);
									$caeId = $this->EsePractical->getLastInsertID();
								}
								//echo $caeId." ".$column." ".$row." ".$highestRow;
									
								$dataRow = $row+1;
								for ($i=$dataRow; $i<=$highestRow; $i++) {
									//echo $i." ".$column;
									$cell = $worksheet->getCellByColumnAndRow($column, $i);
									//echo $cell;
										
									//echo $marks."</br>";
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
											$caeId = $this->CourseMapping->getCmIdCaeIdFromBatchIdProgramIdCourseCode($abs_batch_id, $abs_program_id, $courseCode, $assessment_number, "ESE", 2);
										}
									}
										
									//echo "student id : ".$stuId;
									//$this->loadModel('ContinuousAssessmentExam');
										
									$marks = $cell->getValue();
									//echo $marks." ** ".$arrCourseCode['courseCode'][$course_code];
										
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
									
									if ($marks > $arrCourseCode['courseCode'][$course_code]) {
										$relook.=$course_code."-".$regNumber.",";
										$mark_status = "Not Entered";
										$approval_status = 0;
									}
									else if ($marks != "NA") {
										$conditions = array(
												"Practical.ese_practical_id" => $caeId,
												"Practical.month_year_id" => $month_year_id,
												"Practical.student_id" => $stuId
										);
										//pr($conditions);
										if ($this->Practical->hasAny($conditions)){
											//echo "if";
											$this->Practical->query("UPDATE practicals set
												marks='".$marks."', modified='".date("Y-m-d H:i:s")."',
														modified_by = ".$this->Auth->user('id').",
														moderation_operator='',
														moderation_marks=0,
														ese_mod_operator='',
														ese_mod_marks=0
												WHERE ese_practical_id=".$caeId." AND student_id=".$stuId."
														AND month_year_id=".$month_year_id);
										}
										else {
											//echo "else";
											$test = $this->Practical->query("
												insert into practicals (month_year_id, student_id, ese_practical_id, marks,
												created_by,moderation_operator, moderation_marks, ese_mod_operator,
														ese_mod_marks) values
												(".$month_year_id.",".$stuId.", ".$caeId.", '".$marks."',
													".$this->Auth->user('id').",' ',0,' ',0)");
										}
									}
								}
								$test = $this->EsePractical->query("
									update ese_practicals set marks_status='".$mark_status."', add_status=1,
										approval_status=$approval_status
										WHERE id=".$caeId);
								//$this->Flash->success('Successfully imported data');
								$mark_status = "Entered";
							}
						//}
						//Mark reading ends here
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

	public function calculate() {
		$access_result = $this->checkPathAccess($this->Auth->user('id'));
		if (!$access_result) {
		$academics = $this->Student->Academic->find('list');
		$batches = $this->Student->Batch->find('list', array('fields' => array('Batch.batch_period')));
		$monthYears = $this->MonthYear->getAllMonthYears();
		$this->set(compact('batches', 'academics', 'monthYears'));
		if ($this->request->is('post')) {
			$varAjax = 0;
			$batch_id = $this->request->data['Practical']['batch_id'];
			$academic_id = $this->request->data['Practical']['academic_id'];
			$program_id = $this->request->data['Student']['program_id'];
			$month_year_id = $this->request->data['month_year_id'];
			$option = "All";
			
			$result = $this->getStatus($batch_id, $academic_id, $program_id, $month_year_id, $option, $varAjax);
			
			$finalArray = $result['practical'];
			$courseMappingArray = $result['courseMapping'];
			$studentArray = $result['student'];
			/* pr($finalArray);
			*/
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

		$courseType = $this->listCourseTypeIdsBasedOnMethod($this->cType, "both");
		$courseTypeIdArray = explode("-",$courseType);
		//pr($courseTypeIdArray);
		
		$assessment_number = 1;

		$cmAll = $ContinuousAssessmentExamsController->courseCodeForASemester($batch_id, $program_id, $month_year_id, $courseTypeIdArray);
		$courseMappingArray = $ContinuousAssessmentExamsController->truncateEmptyCmIds($cmAll, '-');
		//pr($courseMappingArray);
		
		$caeResult = $this->CaePractical->checkIfCaesExists($courseMappingArray);
		//pr($caeResult);

		$eseResult = $this->EsePractical->checkIfEsesExists($courseMappingArray);
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
//echo "ms ".$caeMarksStatus." ms ".$eseMarksStatus." as ".$caeApprovalStatus." as ".$eseApprovalStatus;

		if ($eseMarksStatus && $caeMarksStatus) {
			if ($eseApprovalStatus && $caeApprovalStatus) {
				
				//$modelsToGet = array("0" => "CaePractical", "1" => "EsePractical");
				$practicalResult = array();
				
				//foreach ($modelsToGet as $key => $model) {
				$result = $this->getStudentsMarksWithCmIdAndModel($courseMappingArray, $month_year_id, $option);
				//pr($result);
				
				$finalArray = array();
				foreach ($result as $key => $markResult) {
					//pr($markResult);
					$cae_model = "";
					 
					if ($markResult['Course']['course_type_id'] == 2 || $markResult['Course']['course_type_id'] == 6) {
						$cae_model = "CaePractical";
						$internalMark = $markResult['CaePractical'][0]['marks'];
						$internal = $markResult['CaePractical'][0]['InternalPractical'];
					} else if ($markResult['Course']['course_type_id'] == 3) {
						$cae_model = "InternalExam";
						$internalMark = $markResult['Course']['max_cae_mark'];
						$internal = $markResult['InternalExam'];
					} 
				//	echo $cae_model." ".$internalMark;
					//pr($internal);

					$finalArray[$markResult['CourseMapping']['id']] = array();
					
					$caeArray=array();
					foreach ($internal as $internalKey => $internalArray) {
						$caeArray[$internalArray['student_id']] = $internalArray['marks']; 
					}
					//pr($caeArray);
					
					$externalMark = $markResult['EsePractical'][0]['marks'];
					$external = $markResult['EsePractical'][0]['Practical'];
					$eseArray=array();
					$minEseStatus = array();
					foreach ($external as $externalKey => $externalArray) {
						$eseArray[$externalArray['student_id']] = $externalArray['marks'];
						$marks = $externalArray['marks'];
						if ($marks >= round($externalMark * $markResult['Course']['min_ese_mark'] /100))
							$minEseStatus[$externalArray['student_id']] = 1;
						else 
							$minEseStatus[$externalArray['student_id']] = 0;
					}
					
					$courseMaxMarks = $markResult['Course']['course_max_marks'];
					$minPassPercentage = $markResult['Course']['total_min_pass'];
					
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
						$totalResult = $this->computeTotalOfCaeAndEse($caeArray, $eseArray, $option, $courseMaxMarks, $minPassPercentage);
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
					$finalArray[$markResult['CourseMapping']['id']]['total'] = array_intersect_key($finalArray[$markResult['CourseMapping']['id']]['total'], $totalStatus);
					$finalArray[$markResult['CourseMapping']['id']]['totalStatus'] = array_intersect_key($totalStatus, $finalArray[$markResult['CourseMapping']['id']]['total']);
				}
				//pr($finalArray);
				
				//$studentArray = $this->Student->getStudentsInfo($batch_id, $program_id);
				
				$studentArray = $this->CourseStudentMapping->getStudents($markResult['CourseMapping']['id'], $batch_id, $program_id);
				//pr($studentArray);
				$practicalResult['practical'] = $finalArray;
				$practicalResult['student'] = $studentArray;
				$practicalResult['courseMapping'] = $courseMappingArray;
				if ($varAjax == 0) {
					return $practicalResult;
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

	public function report() {
		$access_result = $this->checkPathAccess($this->Auth->user('id'));
		if (!$access_result) {
		$academics = $this->Student->Academic->find('list');
		$batches = $this->Student->Batch->find('list', array('fields' => array('Batch.batch_period')));
		$monthYears = $this->MonthYear->getAllMonthYears();
		$this->set(compact('batches', 'academics', 'monthYears'));
		} else {
			$this->render('../Users/access_denied');
		}
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
							'Course.min_ese_mark', 'Course.max_ese_mark', 'Course.total_min_pass', 'Course.course_type_id'
					)
				),
				'InternalExam' => array('fields'=>array('InternalExam.student_id', 'InternalExam.marks',),
						'conditions'=>array('InternalExam.month_year_id'=>$month_year_id),
				),
				'CaePractical'=>array('fields' => array('CaePractical.*'),
					'conditions'=>array('CaePractical.indicator'=>0),
					'InternalPractical' => array('fields'=>array('InternalPractical.student_id', 'InternalPractical.marks',),
							'conditions'=>array('InternalPractical.month_year_id'=>$month_year_id),
					)
				),
				'EsePractical'=>array('fields' => array('EsePractical.*'),
					'conditions'=>array('EsePractical.indicator'=>0),
					'Practical' => array('fields'=>array('Practical.student_id', 'Practical.marks',),
							'conditions'=>array('Practical.month_year_id'=>$month_year_id),
					)
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

	public function storeFinalMarksinDatabase($practical, $studentArray, $month_year_id) {
		//pr($month_year_id);
		//pr($studentArray);
		//pr($practical);
		foreach ($practical as $cm_id => $practicalArray) {
			//pr($practicalArray);
			foreach ($studentArray as $student_id => $stuDetails) {
				
				//echo $student_id." ".$practicalArray['Total'][$student_id]."</br>";
			 	if ($practicalArray['totalStatus'][$student_id] && $practicalArray['minEseStatus'][$student_id])
					$result = ConstantsController::$pass;
				else 
					$result = ConstantsController::$fail;
				//echo "MY id : ".$month_year_id." CMID : ".$cm_id." StudentId : ".$student_id." Marks : ".$practicalArray['Total'][$student_id]." Result : ".$result."</br>";
				$conditions = array(
						"StudentMark.course_mapping_id" => $cm_id,
						"StudentMark.student_id" => $student_id,
						"StudentMark.month_year_id" => $month_year_id,
				);
				
				if ($this->StudentMark->hasAny($conditions)) {
					$this->StudentMark->query("UPDATE student_marks set
									marks=".$practicalArray['total'][$student_id].",
									modified='".date("Y-m-d H:i:s")."',
									modified_by = ".$this->Auth->user('id')."
									WHERE student_id = ".$student_id." AND
									course_mapping_id=".$cm_id." AND
									month_year_id=".$month_year_id
							);
					/* $dbo = $this->CourseMapping->getDatasource();
					$logs = $dbo->getLog();
					$lastLog = end($logs['log']);
					echo $lastLog['query']; */
						
					$bool = true;
				}
				else {
					$data=array();
					$data['StudentMark']['month_year_id'] = $month_year_id;
					$data['StudentMark']['student_id'] = $student_id;
					$data['StudentMark']['course_mapping_id'] = $cm_id;
					$data['StudentMark']['marks'] = $practicalArray['total'][$student_id];
					$data['StudentMark']['status'] = $result;
					$data['StudentMark']['created_by'] = $this->Auth->user('id');
					$this->StudentMark->create();
					$this->StudentMark->save($data);
				}
			}
		}
	}

	public function getCaeAndEseIdWithCmId($cmId, $modelsToGet) {
		/* $result = $this->CourseMapping->find('all', array(
				'conditions' => array(
									'CourseMapping.id' => $cmId,
								),
				'contain'=>array(
							'MonthYear'=>array('fields' =>array('MonthYear.year'),
								'Month'=>array('fields' =>array('Month.month_name'))
							),
							'Program'=>array('fields' =>array('Program.program_name'),
								'Academic'=>array('fields' =>array('Academic.academic_name'))
							),
							'Course'=>array('fields' =>array('Course.course_code','Course.course_name')),
							'Batch'=>array(	'fields' =>array('Batch.batch_from','Batch.batch_to','Batch.academic')),
							'CaePractical'=>array('fields' => array('CaePractical.*'),
													'conditions' => array('CaePractical.indicator'=>0,),
								'InternalPractical' => array('fields'=>array('InternalPractical.student_id', 'InternalPractical.marks',))
							),
							'EsePractical'=>array('fields' => array('EsePractical.*'),
													'conditions' => array('EsePractical.indicator'=>0
							),
								'Practical' => array('fields'=>array('Practical.student_id','Practical.marks'),
														'conditions' => array('Practical.month_year_id'=>$month_year_id)
									),
							),
							'StudentMark'=>array('fields' => array('StudentMark.month_year_id','StudentMark.student_id','StudentMark.marks','StudentMark.status'),
								'conditions' => array('StudentMark.month_year_id'=>$month_year_id,
													'StudentMark.status' => $option
								)),
				),
				'recursive' => 2
		));
		return $result; */
			$result = $this->CourseMapping->find('all', array(
					'conditions' => array(
							'CourseMapping.id' => $cmId,
					),
					'contain'=>array(
							'CaePractical'=>array('fields' => array('CaePractical.*'),
									'conditions'=>array('CaePractical.indicator'=>0)
							),
							'EsePractical'=>array('fields' => array('EsePractical.*'),
									'conditions'=>array('EsePractical.indicator'=>0)),
					),
					'recursive' => 2
			));
			return $result;
	}

	public function getMarksFromPracticalId($id, $model, $caeField, $option) {
		$result = $this->$model->find('all', array(
			'conditions' => array("$model.$caeField" => $id),	
		));
		$studentArray = array();
		foreach ($result as $key => $value) {
			$studentArray[$value['Student']['id']] = $value[$model]['marks'];
		}
		//pr($result);
		return $studentArray;
	}

	public function computeTotalOfCaeAndEse($caeMarks, $eseMarks, $option, $courseMaxMarks, $minPassPercentage) {
		//pr($option);
		//pr($caeMarks);
		//pr($eseMarks);
		$minPass = $courseMaxMarks * $minPassPercentage / 100;
		$finalResult = array();
		$sums = array();
		$result = array();
		foreach (array_keys($caeMarks + $eseMarks) as $key) {
			$value = (isset($caeMarks[$key]) ? $caeMarks[$key] : 0) + (isset($eseMarks[$key]) ? $eseMarks[$key] : 0);
			if($value >= $minPass && ($option=="All" || $option=="Pass")) {
				$result[$key] = 1;
			}
			else if($value < $minPass && ($option=="All" || $option=="Fail")) {
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

	public function finalPractical() {
		$access_result = $this->checkPathAccess($this->Auth->user('id'));
		if (!$access_result) {
		$academics = $this->Student->Academic->find('list');
		$batches = $this->Student->Batch->find('list', array('fields' => array('Batch.batch_period')));
		$monthYears = $this->MonthYear->getAllMonthYears();
		$this->set(compact('batches', 'academics', 'monthYears'));
		if(isset($_REQUEST['PRINT'])) {
			$batch_id = $this->request->data['Practical']['batch_id'];
			$academic_id = $this->request->data['Practical']['academic_id'];
			$program_id = $this->request->data['Student']['program_id'];
			$month_year_id = $this->request->data['Practical']['month_year_id'];
			$option = "All";
			$varAjax=0;
			$result = $this->getStatus($batch_id, $academic_id, $program_id, $month_year_id, $option, $varAjax);
			//pr($result);
			$courseMappingArray = $result['courseMapping'];
			//pr($courseMappingArray);
			$studentArray = $this->Student->getStudentsInfo($batch_id, $program_id);
			$this->set(compact('month_year_id','batch_id','program_id','result', 'studentArray', 'courseMappingArray'));
			$this->layout = false;
			$this->layout = 'print';
			$this->render('finalPracticalPrint');
			return false;
		}
		} else {
			$this->render('../Users/access_denied');
		}
	}

	public function viewEse($batch_id, $academic_id, $program_id, $caeId, $number, $month_year_id) {
		$result = $this->EsePractical->find('all', array(
				'conditions' => array('EsePractical.id' => $caeId)
		));
		$esePracticalResult = $result[0]['Practical'];
		//pr($caePracticalResult);
		$eseMarks = array();
		foreach ($esePracticalResult as $key => $value) {
			$eseMarks[$value['student_id']] = array('id' => $value['id'], 'marks'=>$value['marks']);
		}
		$studentArray = $this->Student->getStudentsWithBatchAndProgram($batch_id, $program_id, $month_year_id);
		$this->set(compact('batch_id', 'academic_id', 'program_id', 'caeId', 'number', 'result', 'studentArray', 'eseMarks'));
	}
	
	public function approveEsePractical($caeId) {
		echo $caeId;
		$data=array();
		$data['EsePractical']['id'] = $caeId;
		$data['EsePractical']['approval_status'] = 1;
		$this->EsePractical->Save($data);
		exit;
	}
	
	public function editEsePractical($batch_id, $academic_id, $program_id, $caeId, $number) {
		$result = $this->EsePractical->find('all', array(
				'conditions' => array('EsePractical.id' => $caeId)
		)); 
		$esePracticalResult = $result[0]['Practical'];
		$cm_id = $result[0]['EsePractical']['course_mapping_id'];
		//pr($caePracticalResult);
		$eseMarks = array();
		foreach ($esePracticalResult as $key => $value) {
			$eseMarks[$value['student_id']] = array('id' => $value['id'], 'marks'=>$value['marks']);
		}
		$studentArray = $this->Student->getStudentsWithBatchAndProgram($batch_id, $program_id, 0);
		
		$publish_status = $this->StudentMark->find("count", array(
				'conditions'=>array('StudentMark.course_mapping_id'=>$cm_id),
		));
		//pr($publish_status);
		
		$this->set(compact('batch_id', 'academic_id', 'program_id', 'caeId', 'number', 'result', 'studentArray', 'eseMarks', 'publish_status'));
	
		if ($this->request->is('post')) {
			//pr($this->data);
				
			$bool = false;
			//pr($this->data);
			$caeId = $this->request->data['EsePractical']['cae_id'];
			$studentDetails = $this->request->data['EsePractical']['student_id'];
			$auth_user = $this->Auth->user('id');
				
			foreach ($studentDetails as $key => $student_id) {
				$id = $this->request->data['EsePractical']['id'][$student_id];
				$marks = $this->request->data['EsePractical']['marks'][$student_id];
				$data = array();
				$data['Practical']['id'] = $id;
				$data['Practical']['marks'] = $marks;
				$data['Practical']['modified_by'] = $auth_user;
				$data['Practical']['modified'] = date("Y-m-d H:i:s");
				$this->Practical->save($data);
	
			}
			$this->Flash->success('Ese Practical has been edited');
			$this->redirect(array('controller' => 'EsePracticals', 'action'=>'practical'));
		}
	}
	
	public function addEsePracticalMarks($batch_id, $academic_id, $program_id, $caeId, $number, $month_year_id) {
		$result = $this->EsePractical->find('all', array(
				'conditions' => array('EsePractical.id' => $caeId)
		));
	
		$studentArray = $this->Student->getStudentsWithBatchAndProgram($batch_id, $program_id, $month_year_id);
		$totalStudentCount = count($studentArray);
		$this->set(compact('batch_id', 'academic_id', 'program_id', 'caeId', 'number', 'result', 'studentArray'));
	
		if ($this->request->is('post')) {
			//pr($this->data);
			$bool = false;
			//pr($this->data);
			$caeId = $this->request->data['EsePractical']['cae_id'];
			$month_year_id = $this->request->data['EsePractical']['month_year_id'];
			$studentDetails = $this->request->data['EsePractical']['student_id'];
			$auth_user = $this->Auth->user('id');
			$i = 0;
			foreach ($studentDetails as $key => $student_id) {
				$marks = $this->request->data['EsePractical']['marks'][$student_id];
				$data = array();
				$data['Practical']['month_year_id'] = $month_year_id;
				$data['Practical']['ese_practical_id'] = $caeId;
				$data['Practical']['marks'] = $marks;
				$data['Practical']['student_id'] = $student_id;
				$data['Practical']['created_by'] = $auth_user;
				$this->Practical->create();
				$this->Practical->save($data);
				$i = $i+1;
			}
				
			$data = array();
			$data['EsePractical']['id']=$caeId;
			$data['EsePractical']['add_status'] = 1;
			$data['EsePractical']['modified'] = date("Y-m-d H:i:s");
			$data['EsePractical']['modified_by'] = $auth_user;
				
			if($i >= $totalStudentCount){
				$data['EsePractical']['marks_status']="Entered";
			}
			else {
				$data['EsePractical']['marks_status']="Not Entered";
			}
			$this->EsePractical->save($data);
			//pr($this->data);
			$this->Flash->success('Ese Practical has been added');
			$this->redirect('practical');
		}
	
	}
	
	public function getMarks($batch_id = null, $academic_id = null, $program_id = null, $month_year_id = null, $option, $varAjax) {
		
		$ContinuousAssessmentExamsController = new ContinuousAssessmentExamsController;
		# now you can reference your controller like any other PHP class
		
		$courseType = $this->listCourseTypeIdsBasedOnMethod($this->cType, "both");
		$courseTypeIdArray = explode("-",$courseType);
		//pr($courseTypeIdArray);
		
		$studentArray = $this->Student->getStudentsWithBatchAndProgram($batch_id, $program_id, $month_year_id);
		//pr($studentArray);
		
		$processedStudentArray = $ContinuousAssessmentExamsController->processStudentArray($studentArray);
		
		$assessment_number = 1;
		
		$cmAll = $ContinuousAssessmentExamsController->courseCodeForASemester($batch_id, $program_id, $month_year_id, $courseTypeIdArray);
		$courseMappingArray = $ContinuousAssessmentExamsController->truncateEmptyCmIds($cmAll, $processedStudentArray);
		
		$result = $this->getStatus($batch_id, $academic_id, $program_id, $month_year_id, $option, $varAjax);
		//pr($result);
		$finalArray = $result['practical'];
		$courseMappingArray = $result['courseMapping'];
		$studentArray = $result['student'];

		$this->set(compact('finalArray', 'courseMappingArray', 'studentArray', 'option'));
		$this->layout=false;
		
	}
	
	public function moderate() {
		$access_result = $this->checkPathAccess($this->Auth->user('id'));
		if (!$access_result) {
		$academics = $this->Student->Academic->find('list');
		$batches = $this->Student->Batch->find('list', array('fields' => array('Batch.batch_period')));
		$monthYears = $this->MonthYear->getAllMonthYears();
		$this->set(compact('batches', 'academics', 'monthYears'));
		if($this->request->is('post')) {
			$bool = false;
			//pr($this->data);
			//die;
			$mod_option = $this->request->data['PracticalMod']['option'];
			if ($mod_option=="ese") {
				$mod_operator_field = "ese_mod_operator";
				$mod_marks_field = "ese_mod_marks";
			}
			else if ($mod_option=="both") {
				$mod_operator_field = "moderation_operator";
				$mod_marks_field = "moderation_marks";
			}
			else if ($mod_option=="total") {
				$mod_operator_field = "moderation_operator";
				$mod_marks_field = "moderation_marks";
			}
			$ese_id = $this->request->data['PracticalMod'][$mod_option];
			foreach ($ese_id as $key => $ese_id) {
				$data=array();
				$data['Practical']['id'] = $ese_id;
				//$modMarks = $this->request->data['modMarks'];
				//if ($this->request->data['modOperator']=='plus') {
				//$marks = $this->request->data['PracticalMod']['marks'][$key];
				//}
				if ($mod_option=="ese") {
					$marks = $this->request->data['PracticalMod']['marks'][$key];
					/* $mMarks = $this->request->data['PracticalMod']['mMarks'][$key]; */
					/* if ($marks > $this->request->data['PracticalMod']['min_ese_marks'][$key]) {
						$modMarks = $this->request->data['PracticalMod']['min_ese_marks'][$key] - $this->request->data['PracticalMod']['marks'][$key];
						$marks = $this->request->data['PracticalMod']['min_ese_marks'][$key];
					}
					else  */
					if ($marks == $this->request->data['PracticalMod']['min_ese_marks'][$key]) {
						$modMarks = 0;
						$marks = $this->request->data['PracticalMod']['min_ese_marks'][$key]; 
					}
					else if ($marks < $this->request->data['PracticalMod']['min_ese_marks'][$key]) {
						$modMarks = $this->request->data['PracticalMod']['min_ese_marks'][$key] - $marks; 
						$marks = $this->request->data['PracticalMod']['min_ese_marks'][$key]; 
					}
					else if ($marks > $this->request->data['PracticalMod']['min_ese_marks'][$key]) {
						$modMarks = 0;
						$marks = $marks;
					}
					//$modMarks = $modMarks + $mMarks;
				}
				if ($mod_option=="both") {
					$modMarks = 0;
					$marks = $this->request->data['PracticalMod']['ese_marks'][$key] + $this->request->data['PracticalMod']['cae_marks'][$key];
					/* else if (($marks == $this->request->data['PracticalMod']['min_pass_marks'][$key]) && 
					($this->request->data['PracticalMod']['ese_marks'][$key] >= $this->request->data['PracticalMod']['min_ese_marks'][$key])
					) {
						$modMarks = 0;
						$marks = $this->request->data['PracticalMod']['ese_marks'][$key]; 
					} */
					if (($marks == $this->request->data['PracticalMod']['min_pass_marks'][$key]) && 
					($this->request->data['PracticalMod']['ese_marks'][$key] < $this->request->data['PracticalMod']['min_ese_marks'][$key])
					) {
						$modMarks = $this->request->data['PracticalMod']['min_ese_marks'][$key] - $this->request->data['PracticalMod']['ese_marks'][$key];
						$marks = $this->request->data['PracticalMod']['min_ese_marks'][$key]; 
					}
					else if (($marks > $this->request->data['PracticalMod']['min_pass_marks'][$key]) && 
					($this->request->data['PracticalMod']['ese_marks'][$key] < $this->request->data['PracticalMod']['min_ese_marks'][$key])
					) {
						$modMarks = $this->request->data['PracticalMod']['min_ese_marks'][$key] - $this->request->data['PracticalMod']['ese_marks'][$key];
						//echo $modMarks;
						$marks = $this->request->data['PracticalMod']['ese_marks'][$key] + $modMarks; 
					}
					else if ($marks < $this->request->data['PracticalMod']['min_pass_marks'][$key]) {
						$modMarks = $this->request->data['PracticalMod']['min_pass_marks'][$key] - $marks; 
						$marks = $this->request->data['PracticalMod']['ese_marks'][$key] + $modMarks; 
					}
					else {
						$modMarks = 0;
						$marks = $this->request->data['PracticalMod']['ese_marks'][$key];
					}
					$mMarks = $this->request->data['PracticalMod']['mMarks'][$key];
					if (isset($mMarks) && $mMarks>0) {
						$modMarks=$modMarks + $mMarks;
					}
				}
				if ($mod_option=="total") {
					$modMarks = 0;
					$marks = $this->request->data['PracticalMod']['ese_marks'][$key] + $this->request->data['PracticalMod']['cae_marks'][$key];
					/* else if (($marks == $this->request->data['PracticalMod']['min_pass_marks'][$key]) &&
					 ($this->request->data['PracticalMod']['ese_marks'][$key] >= $this->request->data['PracticalMod']['min_ese_marks'][$key])
					 ) {
					 $modMarks = 0;
					 $marks = $this->request->data['PracticalMod']['ese_marks'][$key];
					 } */
					if (($marks == $this->request->data['PracticalMod']['min_pass_marks'][$key]) &&
						($this->request->data['PracticalMod']['ese_marks'][$key] <= $this->request->data['PracticalMod']['min_ese_marks'][$key])
						) {
						$modMarks = $this->request->data['PracticalMod']['min_ese_marks'][$key] - $this->request->data['PracticalMod']['ese_marks'][$key];
						$marks = $this->request->data['PracticalMod']['min_ese_marks'][$key];
					}
					else if (($marks > $this->request->data['PracticalMod']['min_pass_marks'][$key]) &&
							($this->request->data['PracticalMod']['ese_marks'][$key] <= $this->request->data['PracticalMod']['min_ese_marks'][$key])
							) {
							$modMarks = $this->request->data['PracticalMod']['min_ese_marks'][$key] - $this->request->data['PracticalMod']['ese_marks'][$key];
							//echo $modMarks;
							$marks = $this->request->data['PracticalMod']['ese_marks'][$key] + $modMarks;
					}
					else if (($marks < $this->request->data['PracticalMod']['min_pass_marks'][$key]) &&
							($this->request->data['PracticalMod']['ese_marks'][$key] <= $this->request->data['PracticalMod']['min_ese_marks'][$key])
							) {
								$tmp = $this->request->data['PracticalMod']['min_ese_marks'][$key] - $this->request->data['PracticalMod']['ese_marks'][$key];
								$tmpTotal = $marks+$tmp;
								if ($tmpTotal >= $this->request->data['PracticalMod']['min_pass_marks'][$key]) {
									$modMarks = $tmp;
								}
								else {
									$diff_to_total = $this->request->data['PracticalMod']['min_pass_marks'][$key] - $tmpTotal;
									$modMarks = $tmp+$diff_to_total;
								}
								$marks = $this->request->data['PracticalMod']['ese_marks'][$key] + $modMarks;
							}
					else if ($marks < $this->request->data['PracticalMod']['min_pass_marks'][$key] &&
							($this->request->data['PracticalMod']['ese_marks'][$key] > $this->request->data['PracticalMod']['min_ese_marks'][$key])) 
					{
						$modMarks = $this->request->data['PracticalMod']['min_pass_marks'][$key] - $marks;
						$marks = $this->request->data['PracticalMod']['ese_marks'][$key] + $modMarks;
					}
					else {  
						$modMarks = 0;
						$marks = $this->request->data['PracticalMod']['ese_marks'][$key];
					}
					$mMarks = $this->request->data['PracticalMod']['mMarks'][$key];
					if (isset($mMarks) && $mMarks>0) {
						$modMarks=$modMarks + $mMarks;
					}
				}
				//echo $modMarks." ".$marks."</br>";
				$data['Practical']['marks'] = $marks;		
				$data['Practical'][$mod_operator_field] = "plus";
				$data['Practical'][$mod_marks_field] = $modMarks;
				$data['Practical']['modified_by'] = $this->Auth->user('id');
				$data['Practical']['modified'] = date("Y-m-d H:i:s");
				$this->Practical->save($data);
				$bool=true;
				//pr($data);
			}
			//
			//return $this->redirect(array('action' => 'moderate'));
			if ($bool) {
				$this->Flash->success(__('The practicals has been moderated.'));
			}
		}
		} else {
			$this->render('../Users/access_denied');
		}
	}
	
	public function getModRecords($batch_id, $academic_id, $program_id, $month_year_id, $course_marks, $mod_option, $from, $to, $ese_greater_than, $ese_lesser_than) {
		$ContinuousAssessmentExamsController = new ContinuousAssessmentExamsController;
		$courseType = $this->listCourseTypeIdsBasedOnMethod($this->cType, "-");
		$courseTypeIdArray = explode("-",$courseType);
		$studentArray = $this->Student->getStudentsInfo($batch_id, $program_id);
		//pr($courseTypeIdArray);
		//$studentArray = $this->CourseStudentMapping->getStudents($markResult['CourseMapping']['id'], $batch_id, $program_id);
		//pr($studentArray);
		if ($mod_option == "ese") {
			$cmCond = array();
			if ($batch_id != "-") $cmCond['CourseMapping.batch_id'] = $batch_id;
			if ($program_id != "-") $cmCond['CourseMapping.program_id'] = $program_id;
			
			$cmCond['CourseMapping.indicator'] = 0;
			$cmCond['CourseMapping.month_year_id'] = $month_year_id;
			$cmCond['Course.course_type_id'] = $courseTypeIdArray;
			
			$result = $this->CourseMapping->find('all', array(
				/* 'conditions' => array('CourseMapping.batch_id' => $batch_id, 'CourseMapping.program_id' => $program_id,
									'CourseMapping.indicator' => 0, 'CourseMapping.month_year_id'=>$month_year_id,
									'Course.course_type_id' => $courseTypeIdArray,
				), */
					'conditions' => array($cmCond),
					'contain' => array(
							'Course' => array(
										'conditions'=>array('Course.course_max_marks'=>$course_marks),
										'fields' => array('Course.course_name','Course.course_code','Course.course_type_id',
												'Course.course_max_marks', 'Course.min_cae_mark', 'Course.max_cae_mark',
												'Course.min_ese_mark', 'Course.max_ese_mark', 'Course.total_min_pass',
										),
								),
							'EsePractical' => array(
									'fields' => array('EsePractical.id', 'EsePractical.marks'),
									'conditions'=>array('EsePractical.indicator'=>0),
										'Practical' => array(
												'fields' => array('Practical.month_year_id', 'Practical.student_id', 'Practical.marks',
												'Practical.ese_mod_operator', 'Practical.ese_mod_marks'
												),
												'conditions' => array('Practical.marks BETWEEN '.$from.' AND '.$to,
																	'Practical.month_year_id' => $month_year_id
												)),
							),
							/* 'CaePractical' => array(
									'fields' => array('CaePractical.id', 'CaePractical.marks'),
									'conditions'=>array('CaePractical.indicator'=>0),
									'InternalPractical' => array(
											'fields' => array('InternalPractical.student_id', 'InternalPractical.marks'),
									)
							), */
					),
					'recursive'=>2
			));
			/* $dbo = $this->CourseMapping->getDatasource();
			$logs = $dbo->getLog();
			$lastLog = end($logs['log']);
			echo $lastLog['query']; */
			
			//pr($result); 
			$manipuatedResult = $this->manipulateEseData($result, $ese_greater_than);
			//pr($manipuatedResult); 
			//die;
			$courseDetails = $this->retrieveCourseData($result);
			//pr($courseDetails);
			$this->layout=false;
			$this->set(compact('manipuatedResult', 'studentArray', 'mod_option', 'courseDetails'));
			$this->render('get_mod_records_ese');
		}
		else if ($mod_option == "both") {
			$result = $this->CourseMapping->find('all', array(
					'conditions' => array('CourseMapping.batch_id' => $batch_id, 'CourseMapping.program_id' => $program_id,
							'CourseMapping.indicator' => 0, 'CourseMapping.month_year_id'=>$month_year_id,
							'Course.course_type_id' => $courseTypeIdArray,
							
					),
					'contain' => array(
							'Course' => array(
									'conditions'=>array('Course.course_max_marks'=>$course_marks),
									'fields' => array('Course.course_name','Course.course_code',
												'Course.course_max_marks', 'Course.min_cae_mark', 'Course.max_cae_mark',
												'Course.min_ese_mark', 'Course.max_ese_mark', 'Course.total_min_pass',),
							),
							'EsePractical' => array(
									'fields' => array('EsePractical.id', 'EsePractical.marks'),
									'conditions'=>array('EsePractical.indicator'=>0),
										'Practical' => array(
												'fields' => array('Practical.month_year_id', 
														'Practical.student_id', 'Practical.marks', 'Practical.moderation_operator',
														'Practical.moderation_marks'),
												)
								),
							'CaePractical' => array(
									'fields' => array('CaePractical.id', 'CaePractical.marks'),
									'conditions'=>array('CaePractical.indicator'=>0),
									'InternalPractical' => array(
											'fields' => array('InternalPractical.student_id', 'InternalPractical.marks'),
											)
							),							
					),
					'recursive'=>2
			));
			//pr($result);
			$courseDetails = $this->retrieveCourseData($result);
			
			foreach ($result as $key => $resultArray) {
				$cae = $resultArray['CaePractical'][0]['InternalPractical'];
				$course_code = $resultArray['Course']['course_code'];
				
				$ese = $resultArray['EsePractical'][0]['Practical'];
				//pr($cae);
				$eseValue = array();
				foreach ($ese as $eseKey => $eseArray) {
					if ($eseArray['marks'] >= $ese_greater_than && $eseArray['marks'] <= $ese_lesser_than){
						$eseValue[$eseArray['student_id']] = array(
								'marks'=>$eseArray['marks'],
								'practical_id'=>$eseArray['id'],
								'moderation_operator'=>$eseArray['moderation_operator'],
								'moderation_marks'=>$eseArray['moderation_marks'],
						);
					}
				}
				//pr(count($eseValue));
				//pr($eseValue);
				
				$caeValue = array();
				foreach ($cae as $caeKey => $caeArray) {
					
					$tmpArray = array();
					if (($caeArray['marks']>=$from) && ($caeArray['marks']<=$to)) {
						$student_id = $caeArray['student_id'];
						//pr($eseValue[$student_id]);
						if (isset($eseValue[$student_id])) {
						$caeValue[$caeArray['student_id']] = array(
								'cae_marks'=>$caeArray['marks'],
								'cae_id'=>$caeArray['id'],
								'ese_marks' => $eseValue[$student_id]['marks'],
								'moderation_operator' => $eseValue[$student_id]['moderation_operator'],
								'mMarks' => $eseValue[$student_id]['moderation_marks'],
								'total' => $eseValue[$student_id]['marks'] + $caeArray['marks'],
								'practical_id' => $eseValue[$student_id]['practical_id']
						);
						}
					}
				}
				//pr($caeValue);
				$tmpTotal=array();
				$total[$course_code] = $caeValue;
			}
			//pr($total);
			$this->set(compact('result', 'total', 'studentArray', 'mod_option', 'courseDetails'));
			$this->layout=false;
			$this->render('get_mod_records_both');
		}
		else if ($mod_option == "total") {
			$result = $this->CourseMapping->find('all', array(
					'conditions' => array('CourseMapping.batch_id' => $batch_id, 'CourseMapping.program_id' => $program_id,
							'CourseMapping.indicator' => 0, 'CourseMapping.month_year_id'=>$month_year_id,
							'Course.course_type_id' => $courseTypeIdArray,
								
					),
					'contain' => array(
							'Course' => array(
									'conditions'=>array('Course.course_max_marks'=>$course_marks),
									'fields' => array('Course.course_name','Course.course_code',
											'Course.course_max_marks', 'Course.min_cae_mark', 'Course.max_cae_mark',
											'Course.min_ese_mark', 'Course.max_ese_mark', 'Course.total_min_pass',),
							),
							'EsePractical' => array(
									'fields' => array('EsePractical.id', 'EsePractical.marks'),
									'conditions'=>array('EsePractical.indicator'=>0),
									'Practical' => array(
											'conditions'=>array('Practical.marks BETWEEN '.$from.' AND '.$to),
											'fields' => array('Practical.month_year_id',
													'Practical.student_id', 'Practical.marks', 'Practical.moderation_operator',
													'Practical.moderation_marks'),
									)
							),
							'CaePractical' => array(
									'fields' => array('CaePractical.id', 'CaePractical.marks'),
									'conditions'=>array('CaePractical.indicator'=>0),
									'InternalPractical' => array(
											'fields' => array('InternalPractical.student_id', 'InternalPractical.marks'),
									)
							),
					),
					'recursive'=>2
			));
			//pr($result);
			
			$courseDetails = $this->retrieveCourseData($result);
			$eseValue = array();
			foreach ($result as $key => $resultArray) {
				$course_code = $resultArray['Course']['course_code'];
				//echo " *** ".$course_code;
				$cae = $resultArray['CaePractical'][0]['InternalPractical'];
				$ese = $resultArray['EsePractical'][0]['Practical'];
				//pr($cae);
				$eseValue = array();
				foreach ($ese as $eseKey => $eseArray) {
					foreach ($cae as $caeKey => $caeArray) {
						if ($caeArray['student_id'] == $eseArray['student_id']) {
							$totalOfCaeAndEse = $eseArray['marks'] + $caeArray['marks'];
							if ($totalOfCaeAndEse >= $ese_greater_than && $totalOfCaeAndEse <= $ese_lesser_than) {
								//echo " *** ".$totalOfCaeAndEse;
								$eseValue[$eseArray['student_id']] = array(
										'ese_marks'=>$eseArray['marks'],
										'practical_id'=>$eseArray['id'],
										'moderation_operator'=>$eseArray['moderation_operator'],
										'mMarks'=>$eseArray['moderation_marks'],
										'cae_marks'=>$caeArray['marks'],
										'total' => $totalOfCaeAndEse,
								);
							}
						}
					}
				}
				//pr($total);
				$tmpTotal=array();
				//echo " *** ".$course_code;
				$total[$course_code] = $eseValue;
			}
			//pr($total);
			$this->set(compact('result', 'total', 'studentArray', 'mod_option', 'courseDetails'));
			$this->layout=false;
			$this->render('get_mod_records_total');
		}
		
		$this->layout=false;
	}
	
	
	public function retrieveCourseData($result) {
		//pr($result);
		$resultArray = array();
		foreach ($result as $key => $rArray) {
			//pr($rArray);
			//$resultArray[$rArray['Course']['course_code']] = array();
			$tmpArray = array();
			$tmpArray['course_max_marks'] = $rArray['Course']['course_max_marks'];
			$tmpArray['min_cae_mark'] = $rArray['Course']['min_cae_mark'];
			$min_ese_mark = round($rArray['Course']['max_ese_mark'] * $rArray['Course']['min_ese_mark'] / 100);
			$min_pass_mark = round($rArray['Course']['course_max_marks'] * $rArray['Course']['total_min_pass'] / 100);
			$tmpArray['min_ese_mark'] = $min_ese_mark;
			$tmpArray['max_cae_mark'] = $rArray['Course']['max_cae_mark'];
			$tmpArray['max_ese_mark'] = $rArray['Course']['max_ese_mark'];
			$tmpArray['min_pass_mark'] = $min_pass_mark;
			$resultArray[$rArray['Course']['course_code']] = $tmpArray;
		}
		//pr($resultArray);
		return $resultArray;
	}
	
	public function manipulateEseData($result, $ese_greater_than) {
		$resultArray = array();
		foreach ($result as $key => $rArray) {
			$resultArray[$rArray['Course']['course_code']] = array();
			$tmpArray = array();
			$practical = $rArray['EsePractical'][0]['Practical'];
			foreach ($practical as $pKey => $pValue) {
				//pr($pValue);
				if ($pValue['marks'] > $ese_greater_than) {
					$tmpRarray['student_id'] = $pValue['student_id']; 
					$tmpRarray['marks'] = $pValue['marks'];
					$tmpRarray['ese_mod_operator'] = $pValue['ese_mod_operator'];
					$tmpRarray['mMarks'] = $pValue['ese_mod_marks'];
					$tmpRarray['id'] = $pValue['id'];
					array_push($tmpArray, $tmpRarray);
				}
			}
			$resultArray[$rArray['Course']['course_code']] = $tmpArray;
		}
		//pr($resultArray);
		return $resultArray;
	}
	
	public function displayOption($option) {
		$this->layout=false;
		if ($option == "ese") $this->render('ese');
		else if ($option == "both") $this->render('both');
		else if ($option == "total") $this->render('total');
		return false;
	}
	
	public function tmpModeration() {
		$monthYear = $this->MonthYear->find('all');
		$monthYears = array();
		for ($i=0; $i<count($monthYear);$i++) {
			$monthYears[$monthYear[$i]['MonthYear']['id']] = $monthYear[$i]['Month']['month_name']." - ".$monthYear[$i]['MonthYear']['year'];
		}
		//$cTypeValue = $this->cTypeValue;
		$this->set(compact('monthYears'/* , 'cTypeValue' */));
		if($this->request->is('post')) {
			
			$cm_id = $this->data['cm_id'];
			$internal_practical_id = $this->data['internal_practical_id'];
			$internal_practical_marks = $this->data['internal_practical_marks'];
			$internal_practical_new_mark = $this->data['internal_practical_new_mark'];
			
			//if ($internal_practical_new_mark > $internal_practical_marks) {
				$data=array();
				$data['InternalPractical']['id'] = $internal_practical_id;
				$data['InternalPractical']['marks'] = $internal_practical_new_mark;
				$data['InternalPractical']['modified_by'] = $this->Auth->user('id');
				$data['InternalPractical']['modified'] = date("Y-m-d H:i:s");
				$this->InternalPractical->save($data);
			//}
			
			$external_practical_id = $this->data['external_practical_id'];
			$external_practical_marks = $this->data['external_practical_marks'];
			$moderation_operator = $this->data['moderation_operator'];
			$moderation_marks = $this->data['moderation_marks'];
			$external_practical_new_mark = $this->data['external_practical_new_mark'];
			
			if ($external_practical_new_mark > $external_practical_marks) {
				if ($moderation_marks > 0) {
					$new_mod_mark = $external_practical_new_mark-$external_practical_marks;
					$moderation_operator = 'plus';
					$moderation_marks = $moderation_marks + $new_mod_mark;
				}
				else if ($moderation_marks == 0) {
					$moderation_operator = 'plus';
					$moderation_marks = $external_practical_new_mark-$external_practical_marks;
				}
			}
			else if ($external_practical_new_mark < $external_practical_marks) {
				if ($moderation_marks > 0) {
					$marks_to_reduce = $external_practical_marks - $external_practical_new_mark;
					if ($marks_to_reduce <= $moderation_marks) {
						$moderation_marks = $moderation_marks - $marks_to_reduce;
						$moderation_operator = 'plus';
					}
					else if ($marks_to_reduce > $moderation_marks) {
						$moderation_marks = $marks_to_reduce - $moderation_marks;
						$moderation_operator = 'minus';
					}
				}
				else if ($moderation_marks == 0) {
					$moderation_operator = 'minus';
					$moderation_marks = $external_practical_marks - $external_practical_new_mark;
				}
			}
			else {
				$moderation_marks = $moderation_marks;
				if($moderation_marks > 0) $moderation_operator = $moderation_operator;
				else $moderation_operator='';
			}
			$data=array();
			$data['Practical']['id'] = $external_practical_id;
			$data['Practical']['marks'] = $external_practical_new_mark;
			$data['Practical']['moderation_marks'] = $moderation_marks;
			$data['Practical']['moderation_operator'] = $moderation_operator;
			$data['Practical']['modified_by'] = $this->Auth->user('id');
			$data['Practical']['modified'] = date("Y-m-d H:i:s");
			$this->Practical->save($data);
			
			$student_mark_id = $this->data['student_mark_id'];
			$student_marks = $this->data['student_marks'];
			$student_new_mark = $this->data['student_new_mark'];
			$min_ese_pass_percent = $this->data['min_ese_pass_percent'];
			$min_total_pass_percent = $this->data['min_total_pass_percent'];
			$max_ese_mark = $this->data['max_ese_mark'];
			$course_max_marks = $this->data['course_max_marks'];
			//echo $max_ese_mark." ".$min_ese_pass_percent."</br>";
			$ese_pass_mark = $max_ese_mark * $min_ese_pass_percent / 100;
			$total_pass_mark = $course_max_marks * $min_total_pass_percent / 100;
			
			if ($student_new_mark >= $ese_pass_mark && $student_new_mark >= $total_pass_mark) $result_status = "Pass";
			else $result_status = "Fail";
			
			$grade = $this->getGP($cm_id, $student_new_mark, "GRADE");
			//pr($grade);
			
			$data=array();
			$data['StudentMark']['id'] = $student_mark_id;
			$data['StudentMark']['marks'] = $student_new_mark;
			$data['StudentMark']['status'] = $result_status;
			$data['StudentMark']['grade_point'] = $grade['grade_point'];
			$data['StudentMark']['grade'] = $grade['grade'];
			$data['StudentMark']['modified_by'] = $this->Auth->user('id');
			$data['StudentMark']['modified'] = date("Y-m-d H:i:s");
			//pr($this->data);
			
			if ($this->StudentMark->save($data)) {
				$this->Flash->success(__('The marks has been saved.'));
				return $this->redirect(array('action' => 'tmpModeration'));
			} else {
				$this->Flash->error(__('The marks could not be saved. Please, try again.'));
			}
			
		}
	}
	
	public function getCourses($month_year_id, $reg_no) {
		$results = $this->Student->find('all', array(
			'conditions' => array('Student.registration_number'=>$reg_no),
			'fields' => array('Student.id'),
			'contain'=>array(
				'CourseStudentMapping' => array(
					'conditions' => array('CourseStudentMapping.month_year_id'=>$month_year_id),
					'fields' => array('CourseStudentMapping.student_id', 'CourseStudentMapping.course_mapping_id'),
						'CourseMapping' => array(
							'fields' => array('CourseMapping.id'),
								'Course' => array(
									'conditions' => array('Course.course_type_id'=>$this->cTypeValue),
									'fields' => array('Course.course_code', 'Course.course_type_id'),
								)
						)
				)
			)
		));
		$this->set(compact('results'));
		$this->layout=false;
	}
	
	public function getCourseMarks($month_year_id, $student_id, $cm_id) {
				
		$results = $this->Student->find('all', array(
			'conditions' => array('Student.id'=>$student_id),
			'fields' => array('Student.id'),
			'contain'=>array(
				'InternalPractical' => array(
					'conditions'=>array('InternalPractical.student_id'=>$student_id),
					'fields'=>array('InternalPractical.student_id', 'InternalPractical.marks', 'InternalPractical.id'),
					'CaePractical' => array(
						'conditions'=>array('CaePractical.course_mapping_id'=>$cm_id),
						'fields'=>array('CaePractical.id', 'CaePractical.marks', 'CaePractical.course_mapping_id')
					)
				),
				'Practical' => array(
					'conditions'=>array('Practical.student_id'=>$student_id),
					'fields'=>array('Practical.student_id', 'Practical.marks', 'Practical.moderation_operator', 
						'Practical.moderation_marks', 'Practical.id'
					),
					'EsePractical' => array(
						'conditions'=>array('EsePractical.course_mapping_id'=>$cm_id),
						'fields'=>array('EsePractical.id', 'EsePractical.marks', 'EsePractical.course_mapping_id')
					)
				),
				'StudentMark' => array(
					'conditions'=>array('StudentMark.student_id'=>$student_id, 'StudentMark.course_mapping_id'=>$cm_id),
					'fields'=>array('StudentMark.student_id', 'StudentMark.marks', 'StudentMark.id'),
					'CourseMapping'=> array(
						'fields'=>array('CourseMapping.id', 'CourseMapping.month_year_id'),
							'Course'=>array(
								'fields'=>array('Course.course_max_marks', 'Course.min_cae_mark', 'Course.max_cae_mark',
									'Course.min_ese_mark', 'Course.max_ese_mark', 'Course.total_min_pass',
									'Course.course_type_id'
								)
							)
					)
				)
			)
		));
		//pr($results);
		$this->set(compact('results'));
		$this->layout=false;
	}
}