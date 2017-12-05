<?php
App::uses('AppController', 'Controller');
App::import('Controller', 'ContinuousAssessmentExams');
/**
 * ProfessionalTrainings Controller
 *
 * @property ProfessionalTraining $ProfessionalTraining
 * @property PaginatorComponent $Paginator
 * @property FlashComponent $Flash
 * @property SessionComponent $Session
 */
class ProfessionalTrainingsController extends AppController {

/**
 * Components
 *
 * @var array
 */
	public $components = array('Flash', 'Session');
	public $uses = array("ProfessionalTraining", "CaePt", "Batch", "Academic", "Student", "MonthYear", "ContinuousAssessmentExam", 
			"CourseMapping", "Course", "Program", "CourseType");
	public $cType = 'PT';
/**
 * index method
 *
 * @return void
 */
	public function index() {
		$this->ProfessionalTraining->recursive = 0;
		$this->set('professionalTrainings');
	}

/**
 * view method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function view($id = null, $month_year_id=null) {
		$access_result = $this->checkPathAccess($this->Auth->user('id'));
		if (!$access_result) {
		$professionalTrainings = $this->CaePt->find('all', array(
				'conditions'=>array('CaePt.id'=>$id, 'CaePt.indicator'=>0
				),
				'fields'=>array('CaePt.*'),
				'contain'=>array(
						'CourseMapping'=>array(
								'fields'=>array('CourseMapping.id'),
								'Course'=>array(
										'fields'=>array('Course.id', 'Course.course_name', 'Course.course_code', 'Course.common_code'),
										'CourseType'=>array(
												'fields'=>array('CourseType.course_type'),
										)
								),
								'Batch'=>array(
										'fields'=>array('Batch.batch_from', 'Batch.batch_to', 'Batch.academic')
								),
								'Program'=>array(
										'fields'=>array('Program.program_name'),
										'Academic'=>array(
												'fields'=>array('Academic.academic_name'),
										)
								),
								'MonthYear'=>array(
										'fields'=>array('MonthYear.year'),
										'Month'=>array(
												'fields'=>array('Month.month_name')
										)
								)
						),
							
						'ProfessionalTraining'=>array(
								'fields'=>array('ProfessionalTraining.id', 'ProfessionalTraining.marks'),
								'Student'=>array(
										'fields'=>array('Student.registration_number', 'Student.name', 'Student.id'),
										'conditions'=>array('Student.discontinued_status'=>0)
								)
						)
		
				)
		));
		//pr($professionalTrainings);
			
		$this->set(compact('professionalTrainings', 'id'));
		
		} else {
			$this->render('../Users/access_denied');
		}
	}

/**
 * add method
 *
 * @return void
 */
	public function add($id=NULL, $month_year_id = NULL) {
		$access_result = $this->checkPathAccess($this->Auth->user('id'));
		if (!$access_result) {
		if ($this->request->is('post')) {
			//$this->request->data['ProfessionalTraining']['created_by'] = $this->Auth->user('id');
			//pr($this->data);
			$this->ProfessionalTraining->create();
			//pr($this->request->data['ProfessionalTraining']);
			$new_data = $this->request->data['ProfessionalTraining'];
			foreach ($new_data as $key => $value ) {
				$this->request->data['ProfessionalTraining'][$key]['created_by'] = $this->Auth->user('id');
				$this->request->data['ProfessionalTraining'][$key]['month_year_id'] = $month_year_id;
				$this->request->data['ProfessionalTraining'][$key]['cae_pt_id'] = $id;
			}
			
			if ($this->ProfessionalTraining->saveMany($this->request->data['ProfessionalTraining'])) {
					$this->request->data['CaePt']['id']=$id;
					$this->request->data['CaePt']['add_status']=1;
					$this->request->data['CaePt']['marks_status']="Entered";
					//$this->CaePt->create();
					$this->CaePt->save($this->request->data);
				$this->Flash->success(__('The professional training has been saved.'));
				return $this->redirect(array('action' => 'index'));
			} else {
				$this->Flash->error(__('The professional training could not be saved. Please, try again.'));
			}
		}
		$monthYears = $this->ProfessionalTraining->MonthYear->find('list'); 
		$students = $this->ProfessionalTraining->Student->find('list');
		
		$professionalTrainings = $this->CaePt->find('all', array(
				'conditions'=>array('CaePt.id'=>$id, 'CaePt.indicator'=>0
				),
				'fields'=>array('CaePt.*'),
				'contain'=>array(
						'CourseMapping'=>array(
								'fields'=>array('CourseMapping.id'),
									'Course'=>array(
											'fields'=>array('Course.id', 'Course.course_name', 'Course.course_code', 'Course.common_code'),
											'CourseType'=>array(
													'fields'=>array('CourseType.course_type'),
											)
									),
									'Batch'=>array(
											'fields'=>array('Batch.batch_from', 'Batch.batch_to', 'Batch.academic')
									),
									'Program'=>array(
											'fields'=>array('Program.program_name'),
											'Academic'=>array(
													'fields'=>array('Academic.academic_name'),
											)
									),
									'CourseStudentMapping'=>array(
											'fields'=>array('CourseStudentMapping.student_id'),
											'Student'=>array(
												'fields'=>array('Student.registration_number', 'Student.name', 'Student.id'),
												'conditions'=>array('Student.discontinued_status'=>0)
											)
									),
									'MonthYear'=>array(
										'fields'=>array('MonthYear.year'),
										'Month'=>array(
											'fields'=>array('Month.month_name')
										)
									)
							),
							
						'ProfessionalTraining'=>array(
							'fields'=>array('ProfessionalTraining.*')
						)
						
				)
		));
		//pr($professionalTrainings);
		$this->set(compact('month_year_id', 'students', 'courseMappings', 'professionalTrainings', 'id'));
		} else {
			$this->render('../Users/access_denied');
		}
	}

/**
 * edit method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function edit($id = NULL, $month_year_id=NULL) {
		$access_result = $this->checkPathAccess($this->Auth->user('id'));
		if (!$access_result) {
		$professionalTrainings = $this->CaePt->find('all', array(
				'conditions'=>array('CaePt.id'=>$id, 'CaePt.indicator'=>0
				),
				'fields'=>array('CaePt.*'),
				'contain'=>array(
						'CourseMapping'=>array(
								'fields'=>array('CourseMapping.id'),
									'Course'=>array(
											'fields'=>array('Course.id', 'Course.course_name', 'Course.course_code', 'Course.common_code'),
											'CourseType'=>array(
													'fields'=>array('CourseType.course_type'),
											)
									),
									'Batch'=>array(
											'fields'=>array('Batch.batch_from', 'Batch.batch_to', 'Batch.academic')
									),
									'Program'=>array(
											'fields'=>array('Program.program_name'),
											'Academic'=>array(
													'fields'=>array('Academic.academic_name'),
											)
									),
									'MonthYear'=>array(
										'fields'=>array('MonthYear.year'),
										'Month'=>array(
											'fields'=>array('Month.month_name')
										)
									)
							),
							
						'ProfessionalTraining'=>array(
							'fields'=>array('ProfessionalTraining.id', 'ProfessionalTraining.marks'),
								
								'Student'=>array(
										'fields'=>array('Student.registration_number', 'Student.name', 'Student.id'),
										'conditions'=>array('Student.discontinued_status'=>0)
								)
						)
						
				)
		));
		//pr($professionalTrainings);
		$this->set(compact('professionalTrainings', 'caeId'));
		
		
		if ($this->request->is(array('post', 'put'))) {  
			$new_data = $this->request->data['ProfessionalTraining'];
			$data=array();
			foreach ($new_data as $key => $value ) {
				$this->request->data = array();
				$this->request->data['ProfessionalTraining']['id'] = $value['id'];
				$this->request->data['ProfessionalTraining']['modified_by'] = $this->Auth->user('id');
				$this->request->data['ProfessionalTraining']['modified'] = date("Y-m-d H:i:s");
				//$this->request->data['ProfessionalTraining']['month_year_id'] = $month_year_id;
				//$this->request->data['ProfessionalTraining']['cae_pt_id'] = $id;
				//$this->request->data['ProfessionalTraining']['student_id'] = $value['student_id'];
				$this->request->data['ProfessionalTraining']['marks'] = $value['marks'];
				$this->ProfessionalTraining->save($this->request->data);
				//pr($this->request->data);
			}
			return $this->redirect(array('action' => 'edit',$id,$month_year_id));
			/* if ($this->ProfessionalTraining->saveMany($this->request->data['ProfessionalTraining'])) {
				$this->Flash->success(__('The professional training has been saved.'));
				r
			} else {
				$this->Flash->error(__('The professional training could not be saved. Please, try again.'));
			} */
		} else {
			$options = array('conditions' => array('ProfessionalTraining.' . $this->ProfessionalTraining->primaryKey => $id));
			$this->request->data = $this->ProfessionalTraining->find('first', $options);
		}
		} else {
			$this->render('../Users/access_denied');
		}
	}

/**
 * delete method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function delete($id = null) {
		$this->ProfessionalTraining->id = $id;
		if (!$this->ProfessionalTraining->exists()) {
			throw new NotFoundException(__('Invalid professional training'));
		}
		$this->request->allowMethod('post', 'delete');
		if ($this->ProfessionalTraining->delete()) {
			$this->Flash->success(__('The professional training has been deleted.'));
		} else {
			$this->Flash->error(__('The professional training could not be deleted. Please, try again.'));
		}
		return $this->redirect(array('action' => 'index'));
	}
	
	public function approveInternal($caeId, $postModel) {
		$data=array();
		$data[$postModel]['id'] = $caeId;
		$data[$postModel]['approval_status'] = 1;
		$this->$postModel->Save($data);
		exit;
	}
	
	public function download() {
		$access_result = $this->checkPathAccess($this->Auth->user('id'));
		if (!$access_result) {
		$academics = $this->Student->Academic->find('list');
		$batches = $this->Student->Batch->find('list', array('fields' => array('Batch.batch_period')));
		
		$monthYears = $this->MonthYear->getAllMonthYears();
		
		$action = $this->action;
		$this->set(compact('batches', 'academics', 'programs', 'monthYears'));
		
		if($this->request->is('post')) {
			//pr($this->data);
			$batch_id = $this->request->data['ProfessionalTraining']['batch_id'];
			$batch = $this->Batch->getBatch($batch_id);
				
			$academic_id = $this->request->data['ProfessionalTraining']['academic_id'];
			$academicDetails = $this->Academic->getAcademic($academic_id);
			//$academic = $academicDetails['name'];
			//$academic_short_code = $academicDetails['short_code'];
				
			$program_id = $this->request->data['Student']['program_id'];
			$programDetails = $this->Program->getProgram($program_id);
				
			$month_year_id = $this->request->data['ProfessionalTraining']['month_year_id'];
			$month_year = $this->MonthYear->getMonthYear($month_year_id);
				
			//$marks = $this->request->data['CaePractical']['marks'];
			
			$ContinuousAssessmentExamsController = new ContinuousAssessmentExamsController;
			# now you can reference your controller like any other PHP class
			
			$studentArray = $this->Student->getStudentsWithBatchAndProgram($batch_id, $program_id, $month_year_id);
			
			$processedStudentArray = $ContinuousAssessmentExamsController->processStudentArray($studentArray);
			
			$courseType = $this->listCourseTypeIdsBasedOnMethod($this->cType, "-");
			$courseTypeIdArray = explode("-",$courseType);
			//pr($courseTypeIdArray);
			$assessment_number = 1;
				
			$cmAll = $ContinuousAssessmentExamsController->courseCodeForASemester($batch_id, $program_id, $month_year_id, $courseTypeIdArray);
			$cm = $ContinuousAssessmentExamsController->truncateEmptyCmIds($cmAll, $processedStudentArray);
			//pr($cm);
				
			$this->download_template($batch, $academicDetails, $programDetails, $month_year, $studentArray, $cm, $assessment_number, "CAE", $this->cType, $month_year_id, '-');
				
		}
		} else {
			$this->render('../Users/access_denied');
		}
	}
	
	public function upload() {
		$access_result = $this->checkPathAccess($this->Auth->user('id'));
		if (!$access_result) {
		if($this->request->is('post')) {
			//pr($this->data);
			$relook = "";
			if(!empty($this->request->data['ProfessionalTraining']['csv']['name'])) {
				move_uploaded_file($this->request->data['ProfessionalTraining']['csv']['tmp_name'], WWW_ROOT . '../sets_upload_received_file/' .$this->request->data['ProfessionalTraining']['csv']['name']);
				$filename = WWW_ROOT . '../sets_upload_received_file/'.$this->request->data['ProfessionalTraining']['csv']['name'];
			}
			$objPHPExcel = PHPExcel_IOFactory::load(WWW_ROOT . '../sets_upload_received_file/'.$this->request->data['ProfessionalTraining']['csv']['name']);
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
				
			//Academic idfunction_name
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
							else {
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
									$caeExists = $this->CaePt->find('all', array(
											'conditions' => array('CaePt.course_mapping_id' => $cmId),
											'fields' => array('CaePt.id'),
											'order' => 'CaePt.id ASC',
											'limit' => 1,
											'recursive' => 0
									));
									//pr($caeExists);
									if (isset($caeExists[0]['CaePt']['id'])) {
										$caeId = $caeExists[0]['CaePt']['id'];
									}
									else {
										$data=array();
										$data['CaePt']['month_year_id'] = $month_year_id;
										$data['CaePt']['course_mapping_id'] = $cmId;
										$data['CaePt']['assessment_type'] = "CAE";
										$data['CaePt']['marks'] = $arrCourseCode['courseCode'][$course_code];
										$data['CaePt']['marks_status'] = "Not Entered";
										$data['CaePt']['add_status'] = 0;
										$data['CaePt']['approval_status'] = 0;
										$data['CaePt']['indicator'] = 0;
										$data['CaePt']['created_by'] = $this->Auth->user('id');
										$this->CaePt->create();
										$this->CaePt->save($data);
										$caeId = $this->CaePt->getLastInsertID();
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
										$stuId = $stu['Student']['id'];
							
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
							
										if ($marks > $arrCourseCode['courseCode'][$course_code]) {
											$relook.=$course_code."-".$regNumber.",";
											$mark_status = "Not Entered";
											$approval_status = 0;
										}
										else if ($marks != "NA") {
											$conditions = array(
													"ProfessionalTraining.cae_pt_id" => $caeId,
													"ProfessionalTraining.month_year_id" => $month_year_id,
													"ProfessionalTraining.student_id" => $stuId
											);
											//pr($conditions);
											if ($this->ProfessionalTraining->hasAny($conditions)){
												$this->ProfessionalTraining->query("UPDATE professional_trainings set
												marks='".$marks."', modified='".date("Y-m-d H:i:s")."',
														modified_by = ".$this->Auth->user('id').",
														month_year_id = ".$month_year_id."
												WHERE cae_pt_id=".$caeId." AND student_id=".$stuId."
														AND month_year_id=".$month_year_id);
													
											}
											else {
												$test = $this->ProfessionalTraining->query("
												insert into professional_trainings (month_year_id, student_id, cae_pt_id,
												marks, created_by) values
												(".$month_year_id.", ".$stuId.", ".$caeId.", '".$marks."',
												".$this->Auth->user('id').")");
											}
										}
									}
							
									$test = $this->CaePt->query("
									update cae_pts set marks_status='".$mark_status."', add_status=1,
											approval_status=$approval_status
											WHERE id=".$caeId);
									//$this->Flash->success('Successfully imported data');
								}
							}
						}
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
	
}