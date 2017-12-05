<?php
App::uses('AppController', 'Controller');
App::import('Controller', 'ContinuousAssessmentExams');
App::import('Controller', 'Timetables');
App::import('Vendor', 'PHPExcel', array('file' => 'PHPExcel'.DS.'IOFactory.php'));
App::import('Vendor', 'mPDF', array('file' => 'mPDF'.DS.'mPDF.php'));

//include '../Controller/Constants.php';
/**
 * EndSemesterExams Controller
 *
 * @property EndSemesterExam $EndSemesterExam
 * @property PaginatorComponent $Paginator
 * @property FlashComponent $Flash
 * @property AclComponent $Acl
 * @property SecurityComponent $Security
 * @property RequestHandlerComponent $RequestHandler
 * @property SessionComponent $Session
 */
class EndSemesterExamsController extends AppController {
/**
 * Components
 *
 * @var array
 */
	//public $components = array('Flash', 'Acl', '', 'RequestHandler', 'Session');
	public $uses = array("EndSemesterExam", "Program","Course", "Batch", "Month", "MonthYear", "Timetable", "CourseMapping",
			"Academic", "Batch", "MonthYear", "Student", "StudentMark", "CourseStudentMapping", "InternalExam", "EsePractical",
			"InternalPractical", "CaePractical", "Practical", "PublishStatus", "ProjectViva", "ProjectReview", "InternalProject"
	);
	public $cType = "theory";
	
	public $components = array('mPDF');
	
	/* public function beforeFilter() {
		//parent::beforeFilter();
		if (isset($this->Security) && $this->action == 'index') {
			$this->Security->enabled = false;
			$this->Security->validatePost = false;
		}
	
	} */
/**
 * index method
 *
 * @return void
 */
	public function index() {
		//$this->EndSemesterExam->recursive = 0;
		//$this->set('endSemesterExams');
		$academics = $this->Program->Academic->find('list',array('order' => array('Academic.academic_name ASC')));
		$programs = $this->Program->find('list',array('order' => array('Program.program_name ASC')));
		$batches = $this->Batch->find('list', array('fields' => array('Batch.batch_period'),'order' => array('Batch.batch_to DESC')));
		$monthYears = $this->MonthYear->find('all', array('fields' => array('MonthYear.id','MonthYear.year','MonthYear.month_id','Month.month_name')));
		$monthyears=array();
		foreach ($monthYears as $key => $value) {
			$monthyears[$value['MonthYear']['id']]=$value['Month']['month_name']."-".$value['MonthYear']['year'];
		}
		$this->set(compact('academics','programs','batches','monthyears'));
		$results = $this->EndSemesterExam->find('all');
		
		$this->set('endSemesterExams', $results);
		
		if($this->request->is('post')) {
			/* pr($this->data);
			die; */
			$batch_id = $this->request->data['ESE']['batch_id'];
			$month_year_id = $this->request->data['ESE']['month_year_id'];
			$academic_id = $this->request->data['ESE']['academic_id'];
			if (isset($this->request->data['Student'])) {
				$program_id = $this->request->data['Student']['program_id'];
			}
			else {
				$program_id = 0;
			}
			$ContinuousAssessmentExamsController = new ContinuousAssessmentExamsController;
			$courseType = $this->listCourseTypeIdsBasedOnMethod($this->cType, "-");

			$courseTypeIdArray = explode("-",$courseType);
			//pr($courseTypeIdArray);
			
			$filterCondition = "";
			
			//echo $batch_id." ".$program_id." ".$month_year_id;
			if ($batch_id > 0) {
				$filterCondition.= "`(CourseMapping`.`batch_id` = ".$batch_id.") AND ";
			} else {
				$filterCondition.= "`(CourseMapping`.`batch_id` > 0)"." AND ";
			}
			 	
			if($program_id > 0) {
				$filterCondition.= "`(CourseMapping`.`program_id` = ".$program_id.")"." AND ";
			} else {
				$filterCondition.= "`(CourseMapping`.`program_id` > 0)"." AND ";
			}
		
		$filterCondition.= "((`CourseMapping`.`indicator` = 0)";
		//$filterCondition.= "(`$currentModel`.`indicator` = 0)";
		$filterCondition.=")";
				
		$conditions = array(
					'conditions' => array('Timetable.indicator' => 0,
							'Timetable.month_year_id'=>$month_year_id,
							$filterCondition,
					),
					'fields' =>array('Timetable.id','Timetable.exam_date','Timetable.exam_session',
							'Timetable.exam_type','Timetable.month_year_id',
					),
					'contain'=>array(
							'MonthYear'=>array('fields' =>array('MonthYear.year'),
									'Month'=>array('fields' =>array('Month.month_name'))
							),
							'CourseMapping'=>array(
									'fields'=>array('CourseMapping.id', 'CourseMapping.program_id', 
													'CourseMapping.course_number', 'CourseMapping.course_mode_id',
													'CourseMapping.semester_id', 'CourseMapping.month_year_id'),
									'Program'=>array(
											'fields' =>array('Program.program_name'),
												'Academic'=>array('fields' =>array('Academic.academic_name'))
									),
									'Course'=>array(
											'fields' =>array('Course.course_code','Course.course_name'),
											'conditions' => array('Course.course_type_id' => $courseTypeIdArray)
									),
									'Batch'=>array(
											'fields' =>array('Batch.batch_from','Batch.batch_to','Batch.academic')
									),
									'InternalExam'=>array(
											'fields'=>array('InternalExam.course_mapping_id', 'InternalExam.student_id',
															'InternalExam.marks'),  
									)
							),
							'ExamAttendance'=>array(
									'fields'=>array('ExamAttendance.id','ExamAttendance.timetable_id',
													'ExamAttendance.student_id','ExamAttendance.attendance_status'),
									'conditions'=>array('ExamAttendance.attendance_status'=>1)
							),
							'DummyNumber'=>array(
									'fields'=>array('DummyNumber.*') ,
									'DummyFinalMark' => array(
										'fields' => array(
											'DummyFinalMark.id','DummyFinalMark.dummy_number','DummyFinalMark.marks',
										)	
									),
									'DummyNumberAllocation' => array(
										'fields' => array(
											'DummyNumberAllocation.id','DummyNumberAllocation.dummy_number','
											DummyNumberAllocation.student_id'
										)
									),
							),
					),
					'recursive' => 2
			);
			$timeTableResult=$this->Timetable->find('all', $conditions);
			//pr(count($timeTableResult));
			//pr($timeTableResult);
			
			$finalArray = array();
			$strAtt = "";
			$strDm = "";
			$dmAllocation = "";
			$dmMarks = "";
			$dummyNumberAllocationCount = 0;
			foreach ($timeTableResult as $key => $timetableValue) {
				$internal = $timetableValue['CourseMapping']['InternalExam'];
				$examAttendanceCount = count($timetableValue['ExamAttendance']);
				//pr($examAttendanceCount);
				$courseCode = $timetableValue['CourseMapping']['Course']['course_code'];
				if ($examAttendanceCount <= 0) {
					$strAtt.=$courseCode." ";
					$this->Flash->error('Exam Attendance not entered for '.$strAtt);
				}
				if (isset($timetableValue['DummyNumber'])) {
					$dummyNumberCount = count($timetableValue['DummyNumber']);
				}
				else {
					$dummyNumberCount = 0;
				}
				if ($dummyNumberCount <= 0) {
					$strDm.=$courseCode." ";
					$this->Flash->error('Dummy Number Range incomplete for '.$strDm);
				}
				if (isset($timetableValue['DummyNumber'][0]['DummyNumberAllocation'])) {
					$dummyNumberAllocationCount = count($timetableValue['DummyNumber'][0]['DummyNumberAllocation']);
				}
				else {
					$dummyNumberAllocationCount = 0;
				}
				if ($dummyNumberAllocationCount <= 0) {
					$dmAllocation.=$courseCode." ";
					$this->Flash->error('Dummy Number not allocated for '.$dmAllocation);
				}
				if (isset($timetableValue['DummyNumber'][0]['DummyFinalMark'])) {
					$dummyMarksCount = count($timetableValue['DummyNumber'][0]['DummyFinalMark']);
				}
				else {
					$dummyMarksCount = 0;
				}
				if ($dummyMarksCount <= 0) {
					$dmMarks.=$courseCode." ";
					$this->Flash->error('Dummy Marks not entered for '.$dmMarks);
				}
				// Validation ends
				
				
			}
			
		}
	}

	public function getDummyFinalMarks($month_year_id, $batch_id, $academic_id, $program_id, $exam_type) {
		//echo $month_year_id." ".$batch_id." ".$academic_id." ".$program_id." ".$exam_type;
		if ($exam_type == "R") {
			
			$ContinuousAssessmentExamsController = new ContinuousAssessmentExamsController;
			$courseType = $this->listCourseTypeIdsBasedOnMethod($this->cType, "-");
			$courseTypeIdArray = explode("-",$courseType);
			$courseTypeId = implode(",",$courseTypeIdArray);
			//pr($courseTypeId);
			
			$filterCondition = "";
				
			//echo $batch_id." ".$program_id." ".$month_year_id;
			if ($batch_id > 0) {
				$filterCondition.= "`(CourseMapping`.`batch_id` = ".$batch_id.") AND ";
			}
				
			if($program_id > 0) {
				$filterCondition.= "`(CourseMapping`.`program_id` = ".$program_id.")"." AND ";
			} else {
				$filterCondition.= "`(CourseMapping`.`program_id` > 0)"." AND ";
			}
			
			if($month_year_id > 0) {
				$filterCondition.= "`(CourseMapping`.`month_year_id` = ".$month_year_id.")"." AND ";
			} else {
				$month_year_id.= "`(CourseMapping`.`month_year_id` > 0)"." AND ";
			}
			
			if($month_year_id > 0) {
				$filterCondition.= "`(Course`.`course_type_id` IN ($courseTypeId))"." AND ";
			} else {
				$month_year_id.= "`(Course`.`course_type_id` > 0)"." AND ";
			}
			
			$filterCondition.= "((`CourseMapping`.`indicator` = 0)";
			//$filterCondition.= "(`$currentModel`.`indicator` = 0)";
			$filterCondition.=")";
			
			$result = array(
					'conditions' => array(
							array($filterCondition
							)
					),
					'fields' =>array('CourseMapping.id',  'CourseMapping.program_id', 'CourseMapping.course_number', 'CourseMapping.course_mode_id','CourseMapping.semester_id', 'CourseMapping.month_year_id'),
					'contain'=>array(
							'Timetable' =>array('fields' =>array('Timetable.exam_date','Timetable.exam_session','Timetable.exam_type'),
									'ExamAttendance'=>array('fields' =>array('ExamAttendance.id')),
									'DummyRangeAllocation'=>array('fields' =>array('DummyRangeAllocation.dummy_number_id'),
											'DummyNumber' => array('fields' => array('DummyNumber.start_range', 'DummyNumber.end_range'))
									),
							),
							'Course'=>array('fields' =>array('Course.course_code','Course.course_name')),
							'Program'=>array('fields' =>array('Program.program_name'),
									'Academic'=>array('fields' =>array('Academic.academic_name')),
							),
					),
					'recursive' => 2
			);
			$searchResult = $this->Timetable->CourseMapping->find("all", $result);
			//pr($searchResult);
			$this->set('results', $searchResult);
			
			$this->set('exam_month', $month_year_id);
			$this->set('batch_id', $batch_id);
			$this->set('academic_id', $academic_id);
			$this->set('program_id', $program_id);
			$this->set('exam_type', $exam_type);
		}
		$this->layout=false;
		
	}
	
	public function getStudentInternalMarks($internal) {
		$internalArray = array();
		foreach ($internal as $internalKey => $internalValue) {
			$interalArray[$internalValue['student_id']] = $internalValue['marks'];
		}
		return $internalArray;
	}
/**
 * view method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function view($id = null) {
		if (!$this->EndSemesterExam->exists($id)) {
			throw new NotFoundException(__('Invalid end semester exam'));
		}
		$options = array('conditions' => array('EndSemesterExam.' . $this->EndSemesterExam->primaryKey => $id));
		$this->set('endSemesterExam', $this->EndSemesterExam->find('first', $options));
	}

/**
 * add method
 *
 * @return void
 */
	public function add() {
		if ($this->request->is('post')) {
			$this->EndSemesterExam->create();
			if ($this->EndSemesterExam->save($this->request->data)) {
				$this->Flash->success(__('The end semester exam has been saved.'));
				return $this->redirect(array('action' => 'index'));
			} else {
				$this->Flash->error(__('The end semester exam could not be saved. Please, try again.'));
			}
		}
		$courseMappings = $this->EndSemesterExam->CourseMapping->find('list');
		$students = $this->EndSemesterExam->Student->find('list');
		$monthYears = $this->EndSemesterExam->MonthYear->find('list');
		$this->set(compact('courseMappings', 'students', 'monthYears'));
	}

/**
 * edit method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function edit($id = null) {
		if (!$this->EndSemesterExam->exists($id)) {
			throw new NotFoundException(__('Invalid end semester exam'));
		}
		if ($this->request->is(array('post', 'put'))) {
			if ($this->EndSemesterExam->save($this->request->data)) {
				$this->Flash->success(__('The end semester exam has been saved.'));
				return $this->redirect(array('action' => 'index'));
			} else {
				$this->Flash->error(__('The end semester exam could not be saved. Please, try again.'));
			}
		} else {
			$options = array('conditions' => array('EndSemesterExam.' . $this->EndSemesterExam->primaryKey => $id));
			$this->request->data = $this->EndSemesterExam->find('first', $options);
		}
		$courseMappings = $this->EndSemesterExam->CourseMapping->find('list');
		$students = $this->EndSemesterExam->Student->find('list');
		$monthYears = $this->EndSemesterExam->MonthYear->find('list');
		$this->set(compact('courseMappings', 'students', 'monthYears'));
	}

/**
 * delete method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function delete($id = null) {
		$this->EndSemesterExam->id = $id;
		if (!$this->EndSemesterExam->exists()) {
			throw new NotFoundException(__('Invalid end semester exam'));
		}
		$this->request->allowMethod('post', 'delete');
		if ($this->EndSemesterExam->delete()) {
			$this->Flash->success(__('The end semester exam has been deleted.'));
		} else {
			$this->Flash->error(__('The end semester exam could not be deleted. Please, try again.'));
		}
		return $this->redirect(array('action' => 'index'));
	}
	
	public function viewEseMarks($batch_id, $program_id, $cm_id, $time_table_id, $month_year_id) {
		$studentArray = $this->Student->getStudentsInfo($batch_id, $program_id);
		$results = $this->CourseMapping->find('all', array(
				'conditions' => array('CourseMapping.month_year_id' => $month_year_id, 
					'CourseMapping.id' => $cm_id, 'CourseMapping.indicator' => 0, 
				),
				'contain' => array(
					'EndSemesterExam' => array('fields' => array(
							'EndSemesterExam.student_id', 'EndSemesterExam.marks'
					)),
					'Course' => array('fields' => array(
							'Course.course_code', 'Course.common_code',
							'Course.course_name'
					)),
					'Batch' => array('fields' => array(
							'Batch.batch_from', 'Batch.batch_to', 'Batch.academic'
					)),
					'Program' => array('fields' => array(
							'Program.program_name'
					),
						'Academic' => array('fields' => array(
							'Academic.academic_name'
						))
					),
				)
		));
		//echo $eseCount = count($result[0]['EndSemesterExam']);
		//pr($results);
		$this->set(compact('results', 'studentArray', 'month_year_id'));
	}
	
	public function moderation() {
		$access_result = $this->checkPathAccess($this->Auth->user('id'));
		if (!$access_result) {
		$academics = $this->Academic->find('list');
		$batches = $this->Batch->find('list', array('fields' => array('Batch.batch_period')));
		$monthYears = $this->MonthYear->getAllMonthYears();
		$this->set(compact('batches', 'academics', 'monthYears'));
		if($this->request->is('post')) {
			$this->fnModeration($this->request->data, "EseMod");
		}
		}
		else {
			$this->render('../Users/access_denied');
		}
	}
	
	public function fnModeration($data, $modType) {
		//echo $modType;
		//pr($this->request->data); 
		
		$this->request->data=$data;
		//die;
		$mod_option = $this->request->data['EseMod']['option'];
		if (isset($this->request->data['course_id'])) {
			$courseId = $this->request->data['course_id'];
		}
		//echo $courseId;
		//pr($this->request->data);
		$ese_array = $this->request->data['EseMod']['ese'];
		foreach ($ese_array as $key => $ese_id) {
			if ($mod_option == 'ese') {
				$ese_marks = $this->request->data['EseMod']['actual_mark'];
				foreach ($ese_marks as $ese_id => $actual_mark) {
					if ($actual_mark == $this->request->data['EseMod']['min_ese_pass_mark'][$ese_id]) {
						$modMarks = 0;
						$marks = $this->request->data['EseMod']['actual_mark'][$ese_id];
					}
					else if ($actual_mark < $this->request->data['EseMod']['min_ese_pass_mark'][$ese_id]) {
						$modMarks = $this->request->data['EseMod']['min_ese_pass_mark'][$ese_id] - $actual_mark;
						$marks = $this->request->data['EseMod']['min_ese_pass_mark'][$ese_id];
					}
					else if ($actual_mark > $this->request->data['EseMod']['min_ese_pass_mark'][$ese_id]) {
						$modMarks = 0;
						$marks = $actual_mark;
					}
					$data['EndSemesterExam']['id'] = $ese_id;
					$data['EndSemesterExam']['marks'] = $marks;
					$data['EndSemesterExam']['moderation_operator'] = "plus";
					$data['EndSemesterExam']['moderation_marks'] = $modMarks;
					$data['EndSemesterExam']['modified_by'] = $this->Auth->user('id');
					$data['EndSemesterExam']['modified'] = date("Y-m-d H:i:s");
					$this->EndSemesterExam->save($data);
					//$this->Flash->success(__('The practicals has been moderated.'));
				}
			}
			else if ($mod_option == 'both') {
				$modMarks = 0;
				$marks = $this->request->data['EseMod']['ese_marks'][$key] + $this->request->data['EseMod']['cae_marks'][$key];
		
				if (($marks == $this->request->data['EseMod']['min_pass_marks'][$key]) &&
						($this->request->data['EseMod']['ese_marks'][$key] < $this->request->data['EseMod']['min_ese_marks'][$key])
						) {
							$modMarks = $this->request->data['EseMod']['min_ese_marks'][$key] - $this->request->data['EseMod']['ese_marks'][$key];
							$marks = $this->request->data['EseMod']['min_ese_marks'][$key];
						}
						else if (($marks > $this->request->data['EseMod']['min_pass_marks'][$key]) &&
								($this->request->data['EseMod']['ese_marks'][$key] < $this->request->data['EseMod']['min_ese_marks'][$key])
								) {
									$modMarks = $this->request->data['EseMod']['min_ese_marks'][$key] - $this->request->data['EseMod']['ese_marks'][$key];
									$marks = $this->request->data['EseMod']['ese_marks'][$key] + $modMarks;
								}
								else if ($marks < $this->request->data['EseMod']['min_pass_marks'][$key]) {
									$modMarks = $this->request->data['EseMod']['min_pass_marks'][$key] - $marks;
									$marks = $this->request->data['EseMod']['ese_marks'][$key] + $modMarks;
								}
								else {
									$modMarks = 0;
									$marks = $this->request->data['EseMod']['ese_marks'][$key];
								}
								$mMarks = $this->request->data['EseMod']['mMarks'][$key];
								if (isset($mMarks) && $mMarks>0) {
									$modMarks=$modMarks + $mMarks;
								}
								//echo $modMarks." ".$marks."</br>";
								$data['EndSemesterExam']['id'] = $ese_id;
								$data['EndSemesterExam']['marks'] = $marks;
								$data['EndSemesterExam']['moderation_operator'] = "plus";
								$data['EndSemesterExam']['moderation_marks'] = $modMarks;
								$data['EndSemesterExam']['modified_by'] = $this->Auth->user('id');
								$data['EndSemesterExam']['modified'] = date("Y-m-d H:i:s");
								$this->EndSemesterExam->save($data);
			}
			else if ($mod_option=="total") {
				//pr($this->data); die;
				$modMarks = 0;
				$marks = $this->request->data['EseMod']['ese_marks'][$key] + $this->request->data['EseMod']['cae_marks'][$key];
				/* else if (($marks == $this->request->data['PracticalMod']['min_pass_marks'][$key]) &&
				 ($this->request->data['PracticalMod']['ese_marks'][$key] >= $this->request->data['PracticalMod']['min_ese_marks'][$key])
				 ) {
				 $modMarks = 0;
				 $marks = $this->request->data['PracticalMod']['ese_marks'][$key];
				 } */
				if (($marks == $this->request->data['EseMod']['min_pass_marks'][$key]) &&
						($this->request->data['EseMod']['ese_marks'][$key] <= $this->request->data['EseMod']['min_ese_marks'][$key])
						) {
							$modMarks = $this->request->data['EseMod']['min_ese_marks'][$key] - $this->request->data['EseMod']['ese_marks'][$key];
							$marks = $this->request->data['EseMod']['min_ese_marks'][$key];
						}
						else if (($marks > $this->request->data['EseMod']['min_pass_marks'][$key]) &&
								($this->request->data['EseMod']['ese_marks'][$key] <= $this->request->data['EseMod']['min_ese_marks'][$key])
								) {
									$modMarks = $this->request->data['EseMod']['min_ese_marks'][$key] - $this->request->data['EseMod']['ese_marks'][$key];
									//echo $modMarks;
									$marks = $this->request->data['EseMod']['ese_marks'][$key] + $modMarks;
								}
								else if (($marks < $this->request->data['EseMod']['min_pass_marks'][$key]) &&
										($this->request->data['EseMod']['ese_marks'][$key] <= $this->request->data['EseMod']['min_ese_marks'][$key])
										) {
											$tmp = $this->request->data['EseMod']['min_ese_marks'][$key] - $this->request->data['EseMod']['ese_marks'][$key];
											$tmpTotal = $marks+$tmp;
											if ($tmpTotal >= $this->request->data['EseMod']['min_pass_marks'][$key]) {
												$modMarks = $tmp;
											}
											else {
												$diff_to_total = $this->request->data['EseMod']['min_pass_marks'][$key] - $tmpTotal;
												$modMarks = $tmp+$diff_to_total;
											}
											$marks = $this->request->data['EseMod']['ese_marks'][$key] + $modMarks;
										}
										else if ($marks < $this->request->data['EseMod']['min_pass_marks'][$key] &&
												($this->request->data['EseMod']['ese_marks'][$key] > $this->request->data['EseMod']['min_ese_marks'][$key]))
										{
											$modMarks = $this->request->data['EseMod']['min_pass_marks'][$key] - $marks;
											$marks = $this->request->data['EseMod']['ese_marks'][$key] + $modMarks;
										}
										else {
											$modMarks = 0;
											$marks = $this->request->data['EseMod']['ese_marks'][$key];
										}
										$mMarks = $this->request->data['EseMod']['mMarks'][$key];
										if (isset($mMarks) && $mMarks>0) {
											$modMarks=$modMarks + $mMarks;
										}
										$data['EndSemesterExam']['id'] = $ese_id;
										$data['EndSemesterExam']['marks'] = $marks;
										$data['EndSemesterExam']['moderation_operator'] = "plus";
										$data['EndSemesterExam']['moderation_marks'] = $modMarks;
										$data['EndSemesterExam']['modified_by'] = $this->Auth->user('id');
										$data['EndSemesterExam']['modified'] = date("Y-m-d H:i:s");
										$this->EndSemesterExam->save($data);
			}
		}
		$this->Flash->success("Moderation Success");		
		$this->redirect("moderation");
	}
	
	public function displayOption($option) {
		$this->layout=false;
		if ($option == "ese") $this->render('ese');
		else if ($option == "both") $this->render('both');
		else if ($option == "total") $this->render('total');
		return false;
	}
	
	public function getModRecordsEse($batch_id, $academic_id, $program_id, $month_year_id, $ese_greater_than, $ese_lesser_than, $mod_option, $course_id=NULL) {
		
		$ContinuousAssessmentExamsController = new ContinuousAssessmentExamsController;
		$courseType = $this->listCourseTypeIdsBasedOnMethod($this->cType, "-");
		$courseTypeIdArray = explode("-",$courseType);
		if ($program_id <> 0) $studentArray = $this->Student->getStudentsInfo($batch_id, $program_id);

		$conditions = array();
		if ($batch_id > 0 || $batch_id!="undefined") {
			$conditions['CourseMapping.batch_id'] = $batch_id;
		}
		if ($program_id > 0) {
			$conditions['CourseMapping.program_id'] = $program_id;
		}
		if ($course_id > 0) {
			$conditions['Course.id'] = $course_id;
		}
		$conditions['Course.course_type_id'] = $courseTypeIdArray;
		$conditions['CourseMapping.indicator'] = 0;
		//$conditions['CourseMapping.month_year_id'] = $month_year_id;
		/* if ($program_id > 0) {
			$conditions['CourseMapping.program_id'] = $program_id;
		} */
		$cm_id = $this->CourseMapping->find('list', array(
				'conditions' => $conditions,
				'fields'=>array('CourseMapping.id'),
				'contain' => array(
						'Course' => array(
								'fields' => array('Course.id',
								),
						),
				),
				'recursive'=>2
		));
		//pr($cm_id);
		$results = $this->EndSemesterExam->find('all', array(
			'conditions' => array('EndSemesterExam.month_year_id'=>$month_year_id, 'EndSemesterExam.course_mapping_id'=>$cm_id,
					'EndSemesterExam.marks BETWEEN '.$ese_greater_than.' AND '.$ese_lesser_than
			),
			'fields'=>array('EndSemesterExam.id', 'EndSemesterExam.course_mapping_id', 'EndSemesterExam.student_id', 
					'EndSemesterExam.marks'
			),
			'contain'=>array(
					'Student'=>array(
							'fields'=>array('Student.id', 'Student.batch_id', 'Student.program_id',
									'Student.registration_number', 'Student.name', ),
							'Batch'=>array(
									'fields'=>array('Batch.batch_period')
							),
							'Program'=>array(
									'fields'=>array('Program.program_name'),
									'Academic'=>array(
											'fields'=>array('Academic.academic_name'),
									)
							)
					)
			),
			'recursive'=>2
		));
		//echo count($results);
		//pr($results);
		
		$courseDetails = $this->CourseMapping->getCourseMarks($cm_id);
		//pr($courseDetails);
		
		$this->layout=false;
		$this->set(compact('results', 'studentArray', 'mod_option', 'courseDetails', 'batch_id', 'program_id'));
			//$this->render('get_mod_records_ese');
	
		$this->layout=false;
	}
	
	public function getModRecordsBoth($batch_id, $academic_id, $program_id, $month_year_id, $cae_from, $cae_to, $ese_greater_than, $ese_lesser_than, $mod_option, $course_id=NULL) {
		$ContinuousAssessmentExamsController = new ContinuousAssessmentExamsController;
		$courseType = $this->listCourseTypeIdsBasedOnMethod($this->cType, "-");
		$courseTypeIdArray = explode("-",$courseType);
		if ($program_id <> 0) $studentArray = $this->Student->getStudentsInfo($batch_id, $program_id);
		//else if ($program_id == 0) $studentArray = $this->Student->getStudentsInfoWithBatchId($batch_id);
		//pr($courseTypeIdArray);
		//$studentArray = $this->CourseStudentMapping->getStudents($markResult['CourseMapping']['id'], $batch_id, $program_id);
		//pr($studentArray);
		$conditions = array();
		if ($batch_id > 0) {
			$conditions['CourseMapping.batch_id'] = $batch_id;
		}
		if ($program_id > 0) {
			$conditions['CourseMapping.program_id'] = $program_id;
		}
		if ($course_id > 0) {
			$conditions['Course.id'] = $course_id;
		}
		$conditions['Course.course_type_id'] = $courseTypeIdArray;
		$conditions['CourseMapping.indicator'] = 0;
		
		//$conditions['CourseMapping.month_year_id'] = $month_year_id;
		/* if ($program_id > 0) {
			$conditions['CourseMapping.program_id'] = $program_id;
		} */
	
		$cm_id = $this->CourseMapping->find('list', array(
				'conditions' => $conditions,
				'fields'=>array('CourseMapping.id'),
				'contain' => array(
						'Course' => array(
								'fields' => array('Course.id',),
						),
				),
				'recursive'=>2
		));
		//pr($cm_id);
		
		$results = $this->EndSemesterExam->find('all', array(
				'conditions' => array('EndSemesterExam.month_year_id'=>$month_year_id, 'EndSemesterExam.course_mapping_id'=>$cm_id,
						'EndSemesterExam.marks BETWEEN '.$ese_greater_than.' AND '.$ese_lesser_than
				),
				'fields'=>array('EndSemesterExam.id', 'EndSemesterExam.course_mapping_id', 'EndSemesterExam.student_id',
						'EndSemesterExam.marks', 'EndSemesterExam.moderation_operator', 'EndSemesterExam.moderation_marks'
				),
				'contain'=>array(
								'Student'=>array(
										'fields'=>array('Student.id', 'Student.batch_id', 'Student.program_id',
												'Student.registration_number', 'Student.name', ),
										'Batch'=>array(
												'fields'=>array('Batch.batch_period')
										),
										'Program'=>array(
												'fields'=>array('Program.program_name'),
												'Academic'=>array(
														'fields'=>array('Academic.academic_name'),
												)
										)
								)
						), 
		));
		//echo count($results);
		//pr($results);
		
		$courseDetails = $this->CourseMapping->retrieveCourseDetails($cm_id, $month_year_id);
		//pr($courseDetails);
		
		$finalArray = array();
		
		//$finalArray[$courseMappingId][$course_code] = $caeValue;
		foreach ($results as $key => $result) { //pr($result);
			$course_mapping_id = $result['EndSemesterExam']['course_mapping_id'];
			$caeArray = $this->InternalExam->find('all', array(
				'conditions'=>array('InternalExam.course_mapping_id'=>$course_mapping_id,
						'InternalExam.marks BETWEEN '.$cae_from.' AND '.$cae_to,
						'InternalExam.student_id'=>$result['EndSemesterExam']['student_id']
				),
				'fields'=>array('InternalExam.id', 'InternalExam.course_mapping_id', 'InternalExam.marks', 
									'InternalExam.student_id', 'InternalExam.month_year_id'),
				'order'=>array('InternalExam.id DESC'),
				'limit'=>1
			));
		//	pr($caeArray);
			if (isset($caeArray[0]['InternalExam']) && count($caeArray[0]['InternalExam'])>0) {
				$finalArray[$course_mapping_id][$result['EndSemesterExam']['student_id']] = array(
						'cm_id'=>$course_mapping_id,
						'cae_marks'=>$caeArray[0]['InternalExam']['marks'],
						'cae_id'=>$caeArray[0]['InternalExam']['id'],
						'ese_marks' => $result['EndSemesterExam']['marks'],
						'moderation_operator' => $result['EndSemesterExam']['moderation_operator'],
						'mMarks' => $result['EndSemesterExam']['moderation_marks'],
						'total' => $result['EndSemesterExam']['marks'] + $caeArray[0]['InternalExam']['marks'],
						'ese_id' => $result['EndSemesterExam']['id'],
						'reg_num' => $result['Student']['registration_number'],
						'name' => $result['Student']['name'],
						'batch_period' => $result['Student']['Batch']['batch_period'],
						'academic' => $result['Student']['Program']['Academic']['academic_name'],
						'program' => $result['Student']['Program']['program_name'],
				);
			}
			
		}
		//pr($finalArray);
		
		$this->layout=false;
		$this->set(compact('finalArray', 'studentArray', 'mod_option', 'courseDetails', 'batch_id', 'program_id', 'course_id'));
		//$this->render('get_mod_records_ese');
	}
	
	public function getModRecordsTotal($batch_id, $academic_id, $program_id, $month_year_id, $ese_from, $ese_to, $ese_greater_than, $ese_lesser_than, $mod_option, $course_id=NULL) {
		$ContinuousAssessmentExamsController = new ContinuousAssessmentExamsController;
		$courseType = $this->listCourseTypeIdsBasedOnMethod($this->cType, "-");
		$courseTypeIdArray = explode("-",$courseType);
		//pr($courseTypeIdArray);
		if ($program_id <> 0) $studentArray = $this->Student->getStudentsInfo($batch_id, $program_id);
		//else if ($program_id == 0) $studentArray = $this->Student->getStudentsInfoWithBatchId($batch_id);
		//pr($courseTypeIdArray);
		//$studentArray = $this->CourseStudentMapping->getStudents($markResult['CourseMapping']['id'], $batch_id, $program_id);
		//pr($studentArray);
		$conditions = array();
		if ($batch_id > 0) {
			$conditions['CourseMapping.batch_id'] = $batch_id;
		}
		if ($program_id > 0) {
			$conditions['CourseMapping.program_id'] = $program_id;
		}
		if ($course_id > 0) {
			$conditions['Course.id'] = $course_id;
		}
		$conditions['Course.course_type_id'] = $courseTypeIdArray;
		$conditions['CourseMapping.indicator'] = 0;
		//$conditions['CourseMapping.month_year_id'] = $month_year_id;
		/* if ($program_id > 0) {
			$conditions['CourseMapping.program_id'] = $program_id;
		} */
	
		$cm_id = $this->CourseMapping->find('list', array(
				'conditions' => $conditions,
				'fields'=>array('CourseMapping.id'),
				'contain' => array(
						'Course' => array(
								'fields' => array('Course.id',),
						),
				),
				'recursive'=>2
		));
		//pr($cm_id);
		$results = $this->EndSemesterExam->find('all', array(
				'conditions' => array('EndSemesterExam.month_year_id'=>$month_year_id, 'EndSemesterExam.course_mapping_id'=>$cm_id,
						'EndSemesterExam.marks BETWEEN '.$ese_from.' AND '.$ese_to
				),
				'fields'=>array('EndSemesterExam.id', 'EndSemesterExam.course_mapping_id', 'EndSemesterExam.student_id',
						'EndSemesterExam.marks', 'EndSemesterExam.moderation_operator', 'EndSemesterExam.moderation_marks'
				),
				'contain'=>array(
								'Student'=>array(
										'fields'=>array('Student.id', 'Student.batch_id', 'Student.program_id',
												'Student.registration_number', 'Student.name', ),
										'Batch'=>array(
												'fields'=>array('Batch.batch_period')
										),
										'Program'=>array(
												'fields'=>array('Program.program_name'),
												'Academic'=>array(
														'fields'=>array('Academic.academic_name'),
												)
										)
								)
						),  
		));
		//echo count($results);
		//pr($results);
		
		$courseDetails = $this->CourseMapping->retrieveCourseDetails($cm_id, $month_year_id);
		//pr($courseDetails);
	
		$finalArray = array();
		
		//$finalArray[$courseMappingId][$course_code] = $caeValue;
		foreach ($results as $key => $result) {
			$course_mapping_id = $result['EndSemesterExam']['course_mapping_id'];
			$caeArray = $this->InternalExam->find('all', array(
					'conditions'=>array('InternalExam.course_mapping_id'=>$course_mapping_id,
							'InternalExam.student_id'=>$result['EndSemesterExam']['student_id']
					),
					'fields'=>array('InternalExam.id', 'InternalExam.course_mapping_id', 'InternalExam.marks',
							'InternalExam.student_id', 'InternalExam.month_year_id'),
					'order'=>array('InternalExam.id DESC'),
					'limit'=>1
			));
			//	pr($caeArray);
			if (isset($caeArray[0]['InternalExam']) && count($caeArray[0]['InternalExam'])>0) {
				$totalOfCaeAndEse = $result['EndSemesterExam']['marks'] + $caeArray[0]['InternalExam']['marks'];
				if ($totalOfCaeAndEse >= $ese_greater_than && $totalOfCaeAndEse <= $ese_lesser_than) {
					$finalArray[$course_mapping_id][$result['EndSemesterExam']['student_id']] = array(
							'cm_id'=>$course_mapping_id,
							'cae_marks'=>$caeArray[0]['InternalExam']['marks'],
							'cae_id'=>$caeArray[0]['InternalExam']['id'],
							'ese_marks' => $result['EndSemesterExam']['marks'],
							'moderation_operator' => $result['EndSemesterExam']['moderation_operator'],
							'mMarks' => $result['EndSemesterExam']['moderation_marks'],
							'total' => $result['EndSemesterExam']['marks'] + $caeArray[0]['InternalExam']['marks'],
							'ese_id' => $result['EndSemesterExam']['id'],
							'reg_num' => $result['Student']['registration_number'],
							'name' => $result['Student']['name'],
							'batch_period' => $result['Student']['Batch']['batch_period'],
							'academic' => $result['Student']['Program']['Academic']['academic_name'],
							'program' => $result['Student']['Program']['program_name'],
					);
				}
			}
				
		}
		//pr($finalArray);

		$this->layout=false;
		$this->set(compact('finalArray', 'studentArray', 'mod_option', 'courseDetails', 'batch_id', 'program_id'));
		//$this->render('get_mod_records_ese');
	}
	
	public function retrieveCourseData($results) {
		$resultArray = array();
		foreach ($results as $key => $rArray) {
			//$resultArray[$rArray['Course']['course_code']] = array();
			$tmpArray = array();
			$tmpArray['course_name'] = $rArray['Course']['course_name'];
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
	
	public function retrieveBasicDetails($results) {
		$basicDetails = array();
		foreach ($results as $key => $resultArray) {
			if (isset($resultArray['Batch']['academic'])=="Jun") {
				$tmp = "A";
			}
			$courseMappingId = $resultArray['CourseMapping']['id'];
			$basicDetails[$courseMappingId] = array(
					'batch' => $resultArray['Batch']['batch_from']."-".$resultArray['Batch']['batch_to']." ".$tmp,
					'academic' => $resultArray['Program']['Academic']['academic_name'],
					'program' => $resultArray['Program']['program_name'],
			);
		}
		return $basicDetails;
	}
	
	public function batchwise() {
		$access_result = $this->checkPathAccess($this->Auth->user('id'));
		if (!$access_result) {
			$academics = $this->Program->Academic->find('list',array('order' => array('Academic.academic_name ASC')));
			$programs = $this->Program->find('list',array('order' => array('Program.program_name ASC')));
			$batches = $this->Batch->find('list', array('fields' => array('Batch.batch_period'),'order' => array('Batch.batch_to DESC')));
			$monthyears = $this->MonthYear->getAllMonthYears();
			$this->set(compact('academics','programs','batches','monthyears'));
		}
		else {
			$this->render('../Users/access_denied');
		}
	}
	
	public function pgmwisePublishSearch() {
		$access_result = $this->checkPathAccess($this->Auth->user('id'));
		//pr($access_result);
		if (!$access_result) {
			$academics = $this->Program->Academic->find('list',array('order' => array('Academic.academic_name ASC')));
			$programs = $this->Program->find('list',array('order' => array('Program.program_name ASC')));
			$batches = $this->Batch->find('list', array('fields' => array('Batch.batch_period'),'order' => array('Batch.batch_to DESC')));
			$monthyears = $this->MonthYear->getAllMonthYears();
			$this->set(compact('academics','programs','batches','monthyears'));
		}
		else {
			$this->render('../Users/access_denied');
		}
	}
	
	public function batchwiseReport($batch_id, $month_year_id, $print=NULL, $program_id, $cm_id) {
		//echo $batch_id." ".$month_year_id." ".$program_id;
	//	die;
		
		$academic_id=0;
		if ($program_id == '-' || $program_id=='undefined') $program_id = 0;
		
		$this->set(compact('batch_id', 'month_year_id', 'print'));
		
		$cmaCond = array();
		$cmaCond['CourseMapping.batch_id'] = $batch_id;
		$cmaCond['CourseMapping.indicator'] = 0;
		$cmaCond['CourseMapping.month_year_id <='] = $month_year_id;
		
		if ($program_id!='-') $cmaCond['CourseMapping.program_id'] = $program_id;
		if ($cm_id!='-') $cmaCond['CourseMapping.id'] = $cm_id;
			
		$course_mapping_array = $this->CourseMapping->find('list', array(
			'conditions'=>array( $cmaCond )
		));
		//echo count($course_mapping_array); 
		//ksort($course_mapping_array);
		//pr($course_mapping_array);

		$course_details = $this->CourseMapping->retrieveCourseDetails($course_mapping_array, $month_year_id);
		//pr($course_details);
		//pr($course_mapping_array);
		
		$course_types = $this->course_types();
		//pr($course_types);
		//uncomment course types other than 2
		$finalArray = array(); 
		foreach ($course_types as $course_type_id => $course_type_value) {
			switch ($course_type_id) {
				CASE 1:
					$filterCondition="";
					$filterCondition.= "`(CourseType`.`course_type` LIKE '%theory%')";
					$courseTypes = $this->CourseType->find('list', array(
							'conditions' => array($filterCondition),
					));
					$explodeCourseType = $this->explodeCourseTypeId($courseTypes);
					$courseTypeIdArray = explode("-",$course_type_id);

					$cm_results = $this->theoryCourseMappingArray($batch_id, $courseTypeIdArray, $program_id, $month_year_id, $course_mapping_array);
					//pr($cm_results);
					
					$ese_results = $this->endSemesterExam($month_year_id, $cm_results);
					//pr($ese_results);
					
					$finalArray = $this->processESEresults($ese_results, $course_details, $month_year_id);
					//pr($finalArray);
					$published_status = $this->moveToStudentMark($finalArray, $month_year_id); 
					break;
				CASE 2:
					//echo "Practical</br>";
					$filterCondition="";
					$filterCondition.= "`(CourseType`.`course_type` LIKE '%practical%')";
					$courseTypes = $this->CourseType->find('list', array(
							'conditions' => array($filterCondition),
					));
					$explodeCourseType = $this->explodeCourseTypeId($courseTypes);
					$courseTypeIdArray = explode("-",$course_type_id);

					$cm_results = $this->practicalCourseMappingArray($batch_id, $courseTypeIdArray, $program_id, $month_year_id, $course_mapping_array);
					//pr($cm_results);
					
					$finalArray = $this->processPracticalResults($cm_results, $course_details, $month_year_id, $courseTypeIdArray);
					//pr($finalArray); 
					
					$published_status = $this->moveToStudentMark($finalArray, $month_year_id);
					
					break;
					
				CASE 3:
					//echo "Theory & Practical</br>";
					$filterCondition="";
					$filterCondition.= "`(CourseType`.`course_type` LIKE '%practical%')";
					$courseTypes = $this->CourseType->find('list', array(
							'conditions' => array($filterCondition),
					));
					$explodeCourseType = $this->explodeCourseTypeId($courseTypes);
					$courseTypeIdArray = explode("-",$course_type_id);
					
					$cm_results = $this->practicalCourseMappingArray($batch_id, $courseTypeIdArray, $program_id, $month_year_id, $course_mapping_array);
					$finalArray = $this->processPracticalResults($cm_results, $course_details, $month_year_id, $courseTypeIdArray);
					//pr($finalArray);
					$published_status = $this->moveToStudentMark($finalArray, $month_year_id);
					break;
				CASE 4:
					//echo "Project</br>";
					$filterCondition="";
					$filterCondition.= "`(CourseType`.`course_type` LIKE '%project%')";
					$courseTypes = $this->CourseType->find('list', array(
							'conditions' => array($filterCondition),
					));
					$explodeCourseType = $this->explodeCourseTypeId($courseTypes);
					$courseTypeIdArray = explode("-",$course_type_id);

					$cm_results = $this->projectCourseMappingArray($batch_id, $courseTypeIdArray, $program_id, $month_year_id, $course_mapping_array);
					//echo count($cm_results);
					//pr($cm_results);
					
					$finalArray = $this->processProjectResults($cm_results, $course_details, $month_year_id, $courseTypeIdArray);

					$published_status = $this->moveToStudentMark($finalArray, $month_year_id);
					break;
				CASE 5:
					//echo "PT</br>";
					$filterCondition="";
					$filterCondition.= "`(CourseType`.`course_type` LIKE '%PT%')";
					$courseTypes = $this->CourseType->find('list', array(
							'conditions' => array($filterCondition),
					));
					$explodeCourseType = $this->explodeCourseTypeId($courseTypes);
					$courseTypeIdArray = explode("-",$course_type_id);
					
					$cm_results = $this->profTrainingCourseMappingArray($batch_id, $courseTypeIdArray, $program_id, $month_year_id, $course_mapping_array);
					//pr($cm_results);
					
					$finalArray = $this->processProfTrainingResults($cm_results, $course_details, $month_year_id);
					//pr($finalArray);
					//die;
					$published_status = $this->moveToStudentMark($finalArray, $month_year_id);
					
					break;
				CASE 6:
					//echo "Studio</br>";
					$filterCondition="";
					$filterCondition.= "`(CourseType`.`course_type` LIKE '%studio%')";
					$courseTypes = $this->CourseType->find('list', array(
							'conditions' => array($filterCondition),
					));
					$explodeCourseType = $this->explodeCourseTypeId($courseTypes);
					$courseTypeIdArray = explode("-",$course_type_id);
					
					$cm_results = $this->practicalCourseMappingArray($batch_id, $courseTypeIdArray, $program_id, $month_year_id, $course_mapping_array);
					
					$finalArray = $this->processPracticalResults($cm_results, $course_details, $month_year_id, $courseTypeIdArray);
					
					$published_status = $this->moveToStudentMark($finalArray, $month_year_id);
					break; 
			}
		}
		
		$this->withdrawalAbs($month_year_id, $batch_id, $course_mapping_array);
		
		//pr($finalArray);
		
		//$published_status = $this->moveToStudentMark($finalArray, $month_year_id);
		//echo $published_status;
		if ($published_status) {
			
			$data_exists=$this->PublishStatus->find('first', array(
					'conditions' => array(
							'PublishStatus.batch_id'=>$batch_id,
							//'PublishStatus.program_id'=>$program_id,
							'PublishStatus.month_year_id'=>$month_year_id,
					),
					'fields' => array('PublishStatus.id'),
					'recursive' => 0
			));
			
			$data = array();
			$data['PublishStatus']['batch_id'] = $batch_id;
			//$data['PublishStatus']['program_id'] = $program_id;
			$data['PublishStatus']['month_year_id'] = $month_year_id;
			$data['PublishStatus']['status'] = 1;
			
			if(isset($data_exists['PublishStatus']['id']) && $data_exists['PublishStatus']['id']>0) {
				$id = $data_exists['PublishStatus']['id'];
				$data['PublishStatus']['id'] = $id;
				$data['PublishStatus']['modified_by'] = $this->Auth->user('id');
				$data['PublishStatus']['modified'] = date("Y-m-d H:i:s");
			}
			else {
				$this->PublishStatus->create($data);
				$data['PublishStatus']['created_by'] = $this->Auth->user('id');
			}
			$this->PublishStatus->save($data);
			echo "Success";
			$this->layout=false;
			//$this->Flash->success(__("Result published for batch : ".$this->Batch->getBatch($batch_id)));
			//$this->redirect("batchwise");
			
			
		}
		$this->autoRender=false;
	}
	
	public function withdrawalAbs($month_year_id, $batch_id, $course_mapping_array) {
		$results = $this->CourseStudentMapping->find('all', array(
				'conditions'=>array('CourseStudentMapping.type'=>array('W','ABS'),
						'CourseStudentMapping.course_mapping_id'=>array_keys($course_mapping_array)
				),
				'fields'=>array('CourseStudentMapping.student_id', 'CourseStudentMapping.course_mapping_id',
						'CourseStudentMapping.type'
				),
				'contain'=>array(
						'CourseMapping'=>array(
							'conditions'=>array('CourseMapping.batch_id'=>$batch_id, 'CourseMapping.month_year_id'=>$month_year_id, 
												'CourseMapping.indicator'=>0
							),
							'fields'=>array('CourseMapping.id')
						)
				)
		));
		//pr($results);
		foreach ($results as $key=>$result) {
			if (isset($result['CourseMapping']['id'])) {
				$student_id = $result['CourseStudentMapping']['student_id'];
				$course_mapping_id = $result['CourseStudentMapping']['course_mapping_id'];
				$type = $result['CourseStudentMapping']['type'];
				$smArray = $this->StudentMark->find('first', array(
						'conditions'=>array('StudentMark.course_mapping_id'=>$course_mapping_id,
								'StudentMark.student_id'=>$student_id,
								'StudentMark.month_year_id'=>$month_year_id
						),
						'fields'=>array('StudentMark.id'),
						'contain'=>false
				));
				if (isset($smArray['StudentMark']['id'])) {
					$student_mark_id = $smArray['StudentMark']['id'];
					$data = array();
					$data['StudentMark']['id']=$student_mark_id;
					$data['StudentMark']['grade_point']=0;
					$data['StudentMark']['grade']=$type;
					$this->StudentMark->save($data);
				}
			}
		}	
	}
	
	public function pgmwiseReport($batch_id, $program_id, $cm_id, $month_year_id) {
		//echo $batch_id." ".$month_year_id." ".$program_id." ".$cm_id;
	
		$academic_id=0;
		if ($program_id == '-' || $program_id=='undefined') $program_id = 0;
	
		$this->set(compact('batch_id', 'month_year_id', 'print'));
	
		$cmaCond = array();
		$cmaCond['CourseMapping.batch_id'] = $batch_id;
		$cmaCond['CourseMapping.indicator'] = 0;
		$cmaCond['CourseMapping.month_year_id <='] = $month_year_id;
	
		if ($program_id!='-' || $program_id > 0) $cmaCond['CourseMapping.program_id'] = $program_id;
		if ($cm_id!='-' || $cm_id > 0) $cmaCond['CourseMapping.id'] = $cm_id;
			
		$course_mapping_array = $this->CourseMapping->find('list', array(
				'conditions'=>array( $cmaCond )
		));
		//echo count($course_mapping_array);
		//ksort($course_mapping_array);
		//pr($course_mapping_array);
		
		$course_details = $this->CourseMapping->retrieveCourseDetails($course_mapping_array, $month_year_id);
		//pr($course_details);
		//pr($course_mapping_array);
		
		$course_types = $this->course_types();
		//pr($course_types);
		
		//uncomment course types other than 2
		$finalArray = array();
		foreach ($course_types as $course_type_id => $course_type_value) {
			switch ($course_type_id) {
				CASE 1:
					$filterCondition="";
					$filterCondition.= "`(CourseType`.`course_type` LIKE '%theory%')";
					$courseTypes = $this->CourseType->find('list', array(
							'conditions' => array($filterCondition),
					));
					$explodeCourseType = $this->explodeCourseTypeId($courseTypes);
					$courseTypeIdArray = explode("-",$course_type_id);
	
					$cm_results = $this->theoryCourseMappingArray($batch_id, $courseTypeIdArray, $program_id, $month_year_id, $course_mapping_array);
					//pr($cm_results);
					if (isset($cm_results) && count($cm_results) > 0) {
						$ese_results = $this->endSemesterExam($month_year_id, $cm_results);
						//pr($ese_results);
						
						$finalArray = $this->processESEresults($ese_results, $course_details, $month_year_id);
						//pr($finalArray);
						$published_status = $this->moveToStudentMark($finalArray, $month_year_id);
					}
					break;
				CASE 2:
					//echo "Practical</br>";
					$filterCondition="";
					$filterCondition.= "`(CourseType`.`course_type` LIKE '%practical%')";
					$courseTypes = $this->CourseType->find('list', array(
							'conditions' => array($filterCondition),
					));
					$explodeCourseType = $this->explodeCourseTypeId($courseTypes);
					$courseTypeIdArray = explode("-",$course_type_id);
	
					$cm_results = $this->practicalCourseMappingArray($batch_id, $courseTypeIdArray, $program_id, $month_year_id, $course_mapping_array);
					//pr($cm_results);
					if (isset($cm_results) && count($cm_results) > 0) {
						$finalArray = $this->processPracticalResults($cm_results, $course_details, $month_year_id, $courseTypeIdArray);
						//pr($finalArray);
							
						$published_status = $this->moveToStudentMark($finalArray, $month_year_id);
					}
					break;
						
				CASE 3:
					//echo "Theory & Practical</br>";
					$filterCondition="";
					$filterCondition.= "`(CourseType`.`course_type` LIKE '%practical%')";
					$courseTypes = $this->CourseType->find('list', array(
							'conditions' => array($filterCondition),
					));
					$explodeCourseType = $this->explodeCourseTypeId($courseTypes);
					$courseTypeIdArray = explode("-",$course_type_id);
						
					$cm_results = $this->practicalCourseMappingArray($batch_id, $courseTypeIdArray, $program_id, $month_year_id, $course_mapping_array);
					if (isset($cm_results) && count($cm_results) > 0) {
						$finalArray = $this->processPracticalResults($cm_results, $course_details, $month_year_id, $courseTypeIdArray);
						//pr($finalArray);
						$published_status = $this->moveToStudentMark($finalArray, $month_year_id);
					}
					break;
				CASE 4:
					//echo "Project</br>";
					$filterCondition="";
					$filterCondition.= "`(CourseType`.`course_type` LIKE '%project%')";
					$courseTypes = $this->CourseType->find('list', array(
							'conditions' => array($filterCondition),
					));
					$explodeCourseType = $this->explodeCourseTypeId($courseTypes);
					$courseTypeIdArray = explode("-",$course_type_id);
	
					$cm_results = $this->projectCourseMappingArray($batch_id, $courseTypeIdArray, $program_id, $month_year_id, $course_mapping_array);
					//echo count($cm_results);
					//pr($cm_results);
					if (isset($cm_results) && count($cm_results) > 0) {
						$finalArray = $this->processProjectResults($cm_results, $course_details, $month_year_id, $courseTypeIdArray);
		
						$published_status = $this->moveToStudentMark($finalArray, $month_year_id);
					}
					break;
				CASE 5:
					//echo "PT</br>";
					$filterCondition="";
					$filterCondition.= "`(CourseType`.`course_type` LIKE '%PT%')";
					$courseTypes = $this->CourseType->find('list', array(
							'conditions' => array($filterCondition),
					));
					$explodeCourseType = $this->explodeCourseTypeId($courseTypes);
					$courseTypeIdArray = explode("-",$course_type_id);
						
					$cm_results = $this->profTrainingCourseMappingArray($batch_id, $courseTypeIdArray, $program_id, $month_year_id, $course_mapping_array);
					//pr($cm_results);
					if (isset($cm_results) && count($cm_results) > 0) {
						$finalArray = $this->processProfTrainingResults($cm_results, $course_details, $month_year_id);
						//pr($finalArray);
						$published_status = $this->moveToStudentMark($finalArray, $month_year_id);
					}
						
					break;
				CASE 6:
					//echo "Studio</br>";
					$filterCondition="";
					$filterCondition.= "`(CourseType`.`course_type` LIKE '%studio%')";
					$courseTypes = $this->CourseType->find('list', array(
							'conditions' => array($filterCondition),
					));
					$explodeCourseType = $this->explodeCourseTypeId($courseTypes);
					$courseTypeIdArray = explode("-",$course_type_id);
						
					$cm_results = $this->practicalCourseMappingArray($batch_id, $courseTypeIdArray, $program_id, $month_year_id, $course_mapping_array);
					if (isset($cm_results) && count($cm_results) > 0) {	
						$finalArray = $this->processPracticalResults($cm_results, $course_details, $month_year_id, $courseTypeIdArray);
						$published_status = $this->moveToStudentMark($finalArray, $month_year_id);
					}
					break;
			}
		}
	
		$this->withdrawalAbs($month_year_id, $batch_id, $course_mapping_array);
	
		//pr($finalArray);
	
		//$published_status = $this->moveToStudentMark($finalArray, $month_year_id);
		//echo $published_status;
		if ($published_status) {
			//	echo $batch_id." ".$month_year_id; 
			$data_exists=$this->PublishStatus->find('first', array(
					'conditions' => array(
							'PublishStatus.batch_id'=>$batch_id,
							//'PublishStatus.program_id'=>$program_id,
							'PublishStatus.month_year_id'=>$month_year_id,
					),
					'fields' => array('PublishStatus.id'),
					'recursive' => 0
			));
				
			$data = array();
			$data['PublishStatus']['batch_id'] = $batch_id;
			//$data['PublishStatus']['program_id'] = $program_id;
			$data['PublishStatus']['month_year_id'] = $month_year_id;
			$data['PublishStatus']['status'] = 1;
				
			if(isset($data_exists['PublishStatus']['id']) && $data_exists['PublishStatus']['id']>0) {
				$id = $data_exists['PublishStatus']['id'];
				$data['PublishStatus']['id'] = $id;
				$data['PublishStatus']['modified_by'] = $this->Auth->user('id');
				$data['PublishStatus']['modified'] = date("Y-m-d H:i:s");
			}
			else {
				$this->PublishStatus->create($data);
				$data['PublishStatus']['created_by'] = $this->Auth->user('id');
			}
			$this->PublishStatus->save($data);
			echo "Success";
			$this->layout=false;
			//$this->Flash->success(__("Result published for batch : ".$this->Batch->getBatch($batch_id)));
			//$this->redirect("batchwise");
				
				
		}
		$this->autoRender=false;
	}
	
	public function explodeCourseTypeId($courseType) {
		$explodeCourseType = "";
		foreach($courseType as $key => $value) {
			$explodeCourseType.=$key."-";
		}
		$explodeCourseType = substr($explodeCourseType,0,strlen($explodeCourseType)-1);
		return $explodeCourseType;
	}
	
	public function report() {
		$academics = $this->Program->Academic->find('list',array('order' => array('Academic.academic_name ASC')));
		$programs = $this->Program->find('list',array('order' => array('Program.program_name ASC')));
		$batches = $this->Batch->find('list', array('fields' => array('Batch.batch_period'),'order' => array('Batch.batch_to DESC')));
		$monthyears = $this->MonthYear->getAllMonthYears();
		$this->set(compact('academics','programs','batches','monthyears'));
	}
	
	public function getReport($month_year_id = null, $batch_id = null, $print=NULL) {
		$batchesArray = array();
		$reportResult = array();
		
		//$batch_id = 4;
		
		//foreach ($batchesArray as $batch_id) {
		$filterCondition="";
		$filterCondition.= "`(CourseMapping`.`batch_id` = $batch_id) AND ";
		//$filterCondition.= "`(CourseMapping`.`program_id` = 19) AND ";
		$filterCondition.= "`(CourseMapping`.`indicator` = 0)";
		if ($month_year_id>0 && $month_year_id != '-') {
			$filterCondition.= "` AND (CourseMapping`.`month_year_id` = $month_year_id)";
		}
		
		$programResult = $this->CourseMapping->find('all', array(
				'conditions' => array($filterCondition),
				'fields' => array('DISTINCT CourseMapping.program_id'),
				'contain' => array(
						'Batch' => array('fields' => array('Batch.batch_from', 'Batch.batch_to', 'Batch.academic')),
						'Program' => array('fields' => array('Program.short_code'),
								'Academic' => array('fields' => array('Academic.short_code', 'Academic.id'))
						)
				)
		));
		//pr($programResult);
		
		foreach ($programResult as $key => $pgmResult) {
			$academic = "";
			if (isset($pgmResult['Batch']['academic']) && $pgmResult['Batch']['academic'] == "Jun") {
				$academic = "A";
			}
			$batch_period = $pgmResult['Batch']['batch_from']."-".$pgmResult['Batch']['batch_to']." ".$academic;
			$this->set(compact('batch_period'));
			$program_id = $pgmResult['CourseMapping']['program_id'];
			//echo "</br> Program ID : ".$program_id."</br>";
			$pgm_short_code = $pgmResult['Program']['short_code'];
			$academic_short_code = $pgmResult['Program']['Academic']['short_code'];
			$academic_id = $pgmResult['Program']['Academic']['id'];
			$csmResults = $this->Student->find('list', array(
					'conditions' => array('Student.batch_id' => $batch_id, 'Student.program_id'=>$program_id, 
							'Student.discontinued_status' => 0,
							//'Student.id' => 3736
					),
					'fields' => array('Student.id'),
			));
			$totalStrength = count($csmResults);
			
			$filterCondition="";
			$filterCondition.= "`(CourseMapping`.`batch_id` = $batch_id) AND ";
			$filterCondition.= "`(CourseMapping`.`indicator` = 0)";
			if ($month_year_id>0) {
				$filterCondition.= "` AND (CourseMapping`.`month_year_id` = $month_year_id)";
			} else if ($month_year_id == '-') {
				$filterCondition.= "` AND (CourseMapping`.`month_year_id` > 0)";
			}
			$filterCondition.= "` AND (CourseMapping`.`program_id` = $program_id)";
			
			$cmIdList = $this->CourseMapping->find('list', array(
					'conditions' => array($filterCondition),
					'fields' => array('CourseMapping.id'),
					'contain' => false
			));
			
			
			$allPass = 0;
			$oneArrear = 0;
			$twoArrear = 0;
			$threeArrear = 0;
			$moreThanThreeArrear = 0;
			$i = 1;
			foreach ($csmResults as $student_id => $stuid) {
				$csmCount = $this->CourseStudentMapping->find('count', array(
						'conditions' => array(
								'CourseStudentMapping.student_id'=>$student_id,
								'CourseStudentMapping.indicator'=>0,
								'CourseStudentMapping.course_mapping_id'=>$cmIdList
						)
				));
				//if ($student_id == 3736) pr($cmIdList);
				/* $passCount = $this->StudentMark->find('count', array(
					'conditions'=>array('StudentMark.student_id'=>$student_id, 'StudentMark.month_year_id <='=>$month_year_id,
							'((StudentMark.status = "Pass" AND StudentMark.revaluation_status=0) OR 
							(StudentMark.revaluation_status=1 AND StudentMark.final_status = "Pass"))',
							'StudentMark.course_mapping_id'=>$cmIdList
					)
				)); */
				$passSql = "";
				$passSql = "SELECT count(*) as pass_count
				FROM student_marks sm JOIN students s ON sm.student_id=s.id
				WHERE sm.student_id=$student_id
				AND sm.id IN (SELECT max( id ) FROM `student_marks` WHERE student_id =$student_id 
				AND course_mapping_id in (".implode(',', array_keys($cmIdList)).")";
				if ($month_year_id > 0) {
					$passSql.=" AND month_year_id = $month_year_id "; 		
				}
				$passSql.= "GROUP BY course_mapping_id ORDER BY id DESC)
				AND ((STATUS='Pass' AND revaluation_status=0)
				OR (final_status='Pass' AND revaluation_status=1)) ";
				if ($month_year_id > 0) {
					$passSql.=" AND sm.month_year_id = $month_year_id ";
				}
				//$passSql.=" AND sm.month_year_id = $month_year_id ";
				
				$passCount = $this->StudentMark->query($passSql);
				
				$passCount = $passCount[0][0]['pass_count'];
				
				if ($passCount >= $csmCount) {
					$allPass++;
					/* $t = $this->Student->studentDetails($student_id);
					//pr($t);
					if ($student_id == 2043) {
						echo "</br>".$student_id." ".$t[0]['Student']['registration_number'];
						pr($cmIdList);
						echo $csmCount;
					} */
				}
				
				$failSql = "";
				$failSql = "SELECT count(*) as fail_count
				FROM student_marks sm JOIN students s ON sm.student_id=s.id
				WHERE sm.student_id=$student_id
				AND sm.id IN (SELECT max( id ) FROM `student_marks` WHERE student_id =$student_id 
				AND course_mapping_id in (".implode(',', array_keys($cmIdList)).")";
				if ($month_year_id > 0) {
					$failSql.=" AND month_year_id = $month_year_id "; 		
				}
				$failSql.= "GROUP BY course_mapping_id ORDER BY id DESC)
				AND ((STATUS='Fail' AND revaluation_status=0)
				OR (final_status='Fail' AND revaluation_status=1)) ";
				if ($month_year_id > 0) {
					$failSql.=" AND sm.month_year_id = $month_year_id ";
				}
				
				/* $failCount = $this->StudentMark->query("SELECT count(*) as fail_count
				FROM student_marks sm JOIN students s ON sm.student_id=s.id
				WHERE student_id=$student_id
				AND sm.id IN (SELECT max( id ) FROM `student_marks` WHERE student_id =$student_id 
				AND sm.course_mapping_id in (".implode(',', array_keys($cmIdList)).") 
				GROUP BY course_mapping_id ORDER BY id DESC)
				AND ((STATUS='Fail' AND revaluation_status=0)
				OR (final_status='Fail' AND revaluation_status=1))"
				); */
				
				$failCount = $this->StudentMark->query($failSql);
				
				$failCount = $failCount[0][0]['fail_count'];
				
				if ($failCount == 1) {
					$oneArrear++;
					//echo $program_id." ".$student_id." ".$failCount."</br>";
				}
				else if ($failCount == 2) {
					$twoArrear++;
					//echo $program_id." ".$student_id." ".$failCount."</br>";
				}
				else if ($failCount == 3) {
					$threeArrear++;
					//echo $program_id." ".$student_id." ".$failCount."</br>";
				}
				else if ($failCount > 3)  {
					$moreThanThreeArrear++;
					//echo $program_id." ".$student_id." ".$failCount."</br>";
				}
			}
			$passPercent = round(100*$allPass/$totalStrength, 2);
			$reportResult[$batch_id][$program_id]['batch'] = $batch_period;
			$reportResult[$batch_id][$program_id]['academic_id'] = $academic_id;
			$reportResult[$batch_id][$program_id]['academic'] = $academic_short_code;
			$reportResult[$batch_id][$program_id]['program'] = $pgm_short_code;
			$reportResult[$batch_id][$program_id]['totalStrength'] = $totalStrength;
			$reportResult[$batch_id][$program_id]['allPass'] = $allPass;
			$reportResult[$batch_id][$program_id]['oneArrear'] = $oneArrear;
			$reportResult[$batch_id][$program_id]['twoArrear'] = $twoArrear;
			$reportResult[$batch_id][$program_id]['threeArrear'] = $threeArrear;
			$reportResult[$batch_id][$program_id]['moreThanThreeArrear'] = $moreThanThreeArrear;
			$reportResult[$batch_id][$program_id]['totalPercent'] = $passPercent;
			//pr($reportResult);
			
		}
		//}
		//pr($reportResult);
		//echo count($reportResult);
		if($print == "0") {
			$this->layout= false;
			$this->set(compact('print', 'reportResult', 'batch_id', 'month_year_id'));
		}
		else if ($print=="PRINT") {
			$this->reportResultPrint($reportResult, $batch_id, $month_year_id);
		}
		else if ($print=="Excel") {
			$this->reportResultExcel($reportResult, $batch_id, $month_year_id);
		}
	}
	
	public function reportResultExcel ($reportResult, $batch_id, $month_year_id) {
		$phpExcel = new PHPExcel();
		$phpExcel->setActiveSheetIndex(0);
		$sheet = $phpExcel->getActiveSheet();
		$sheet->getRowDimension('1')->setRowHeight('18');
		$sheet->setTitle("Result_Analysis");
			
		$sheet->setCellValue("A1", "BATCH");
		$sheet->setCellValue("B1", "ACADEMIC");
		$sheet->setCellValue("C1", "SPECIALISATION");
		$sheet->setCellValue("D1", "TOTAL STRENGTH");
		$sheet->setCellValue("E1", "ALL PASS");
		$sheet->setCellValue("F1", "ONE ARREAR");
		$sheet->setCellValue("G1", "TWO ARREAR");
		$sheet->setCellValue("H1", "THREE ARREAR");
		$sheet->setCellValue("I1", "MORE THAN THREE ARREAR");
		$sheet->setCellValue("J1", "PASS PERCENTAGE");
		$i=2;
		foreach ($reportResult as $batch_id => $results) {
			foreach ($results as $program_id => $result) {
				$sheet->getRowDimension($i)->setRowHeight('18');
				$sheet->setCellValue('A'.$i, $result['batch']);
				$sheet->setCellValue('B'.$i, $result['academic']);
				$sheet->setCellValue('C'.$i, $result['program']);
				$sheet->setCellValue('D'.$i, $result['totalStrength']);
				$sheet->setCellValue('E'.$i, $result['allPass']);
				$sheet->setCellValue('F'.$i, $result['oneArrear']);
				$sheet->setCellValue('G'.$i, $result['twoArrear']);
				$sheet->setCellValue('H'.$i, $result['threeArrear']);
				$sheet->setCellValue('I'.$i, $result['moreThanThreeArrear']);
				$sheet->setCellValue('J'.$i, $result['totalPercent']);
				$i++;
			}
		}
		$download_filename="Result_Analysis-".date('d-M-Y h:i:s');
		header("Content-Type: application/vnd.ms-excel");
		header("Content-Disposition: attachment; filename=\"$download_filename.xls\"");
		header("Cache-Control: max-age=0");
		$objWriter = PHPExcel_IOFactory::createWriter($phpExcel, "Excel5");
		$objWriter->save("php://output");
		exit;
	}
	
	public function reportResultPrint($reportResult, $batch_id, $month_year_id) {
		$this->layout= 'print';
		//$this->set(compact('print', 'reportResult', 'batch_id', 'month_year_id'));
		$html="";
		$head = "<table class='cmainhead2' border='0' align='center'  style='font-family:Arial !important;font-size:16px !important;'>
							 <tr>
							 <td rowspan='2'><img src='../webroot/img/user.jpg'></td>
							 <td align='center'>SATHYABAMA UNIVERSITY<br/>
							 <span class='slogan'>RESULT ANALYSIS</span></td>
							 </tr>
							 </table>";
		
		$head .= "<table class='attendanceHeadTblP' cellpadding='0' cellspacing='0' border='1' style='font-family:Arial !important;font-size:12px !important;text-indent:10px;width:100%;'>
							  <tr>
								<td style='height:30px;width:30%;' align='left'>&nbsp;Batch</td>
								<td align='left' style='width:50%;'>&nbsp;".$this->Batch->getBatch($batch_id)."</td>
								<td style='width:20%;' align='left'>&nbsp;Month Year</td>
								<td align='left' style='height:30px;width:20%;'>&nbsp;".$this->MonthYear->getMonthYear($month_year_id)."</td>
							  </tr>
				            </table>";
		$html .= $head;
		$html .= "<br/><table class='attendanceHeadTblP' cellpadding='0' cellspacing='0' border='1' style='font-family:Arial !important;font-size:12px !important;text-indent:10px;width:100%;'>
							<tr><td align='center' style='height:30px;'>&nbsp;Batch&nbsp;</td>
							<td align='center'>&nbsp;Program&nbsp;</td>
							<td align='center'>&nbsp;Specialisation&nbsp;</td>
							<td align='center'>&nbsp;Total </br>Strength&nbsp;</td>
							<td align='center'>&nbsp;All </br>Pass&nbsp;</td>
							<td align='center'>&nbsp;One </br>Arrear&nbsp;</td>
							<td align='center'>&nbsp;Two </br>Arrear&nbsp;</td>
							<td align='center'>&nbsp;Three </br>Arrear&nbsp;</td>
							<td align='center'>&nbsp;More than </br>Three Arrear&nbsp;</td>
							<td align='center'>&nbsp;Pass </br>Percentage&nbsp;</td></tr>";
		foreach ($reportResult as $batch_id => $results) {
			foreach ($results as $program_id => $result) {
				$html .= "<tr><td>&nbsp;".$result['batch']."&nbsp;</td>";
				$html .= "<td>&nbsp;".$result['academic']."</td>";
				$html .= "<td>&nbsp;".$result['program']."</td>";
				$html .= "<td align='center'>&nbsp;".$result['totalStrength']."</td>";
				$html .= "<td align='center'>&nbsp;".$result['allPass']."</td>";
				$html .= "<td align='center'>&nbsp;".$result['oneArrear']."</td>";
				$html .= "<td align='center'>&nbsp;".$result['twoArrear']."</td>";
				$html .= "<td align='center'>&nbsp;".$result['threeArrear']."</td>";
				$html .= "<td align='center'>&nbsp;".$result['moreThanThreeArrear']."</td>";
				$html .= "<td align='center'>&nbsp;".$result['totalPercent']."</td></tr>";
			}
		}
		$html .= "</table>";
		$html .= "</table><div style='page-break-after:always'></div>";
			
		if ($html) {
			$html = substr($html,0,-43);
			$this->mPDF->init();
			// setting filename of output pdf file
			$this->mPDF->setFilename('Result_Analysis_'.date('d_M_Y').'.pdf');
			// setting output to I, D, F, S
			$this->mPDF->setOutput('D');
			//$this->mPDF->AddPage('L','', '', '', '',30,30,30,30,18,12);
			$this->mPDF->WriteHTML($html);
			// you can call any mPDF method via component, for example:
			$this->mPDF->SetWatermarkText("Draft");
			$this->autoRender=false;
		}
		$this->autoRender = false;
	}
	
	public function processMarks($result) {
		$finalArray = array();
		foreach ($result as $key => $value) {
			$cm_id = $value['CourseMapping']['id'];
			$total_min_pass = $value['Course']['total_min_pass'];
			
			$min_cae_mark = $value['Course']['min_cae_mark'];
			$max_cae_mark = $value['Course']['max_cae_mark'];
			$min_ese_mark = $value['Course']['min_ese_mark'];
			$max_ese_mark = $value['Course']['max_ese_mark'];
			$course_max_marks = $value['Course']['course_max_marks'];
			$course_type_id = $value['Course']['course_type_id'];
			switch ($course_type_id) {
				CASE 1:
					$cae = $value['InternalExam'];
					$caeArray = $this->cleanData($cae);

					$ese = $value['EndSemesterExam'];
					$eseArray = $this->cleanData($ese);
					break;
				CASE 2:
				CASE 6:
					if (isset($value['CaePractical'][0]['InternalPractical'])) {
						$cae = $value['CaePractical'][0]['InternalPractical'];
						$caeArray = $this->cleanData($cae);
					}
					if (isset($value['EsePractical'][0]['Practical'])) {
						$ese = $value['EsePractical'][0]['Practical'];
						$eseArray = $this->cleanData($ese);
					}
					break;
			}
			//if (isset($caeArray) && isset($eseArray)) {
				$finalArray[$cm_id]['Cae']=$caeArray;
				$finalArray[$cm_id]['Ese']=$eseArray;
				$result = $this->totalCaeAndEse($caeArray, $eseArray, $total_min_pass, $course_max_marks, $min_ese_mark, $max_ese_mark, $min_cae_mark, $max_cae_mark, $cm_id);
				//pr($result);
				if(isset($result['total'])) {
					$finalArray[$cm_id]['Total'] = $result['total'];
				}
				if(isset($result['result'])) {
					$finalArray[$cm_id]['Result'] = $result['result'];
				}
			//}
			//else {
				//return false;
			//}
			//pr($finalArray);
		}
		//
		return $finalArray;
	}
	
	public function moveToStudentMark($finalArray, $month_year_id) {
		$bool = false;
		//pr($finalArray);
		foreach ($finalArray as $cm_id => $student_details) {
			if (isset($student_details['total']) && count($student_details['total']) > 0) {
			$totalArray = $student_details['total'];
			//pr($total);
			$statusArray = $student_details['status'];
				foreach ($totalArray as $student_id => $marks) {
					
					$data_exists=$this->StudentMark->find('first', array(
							'conditions' => array(
									'StudentMark.course_mapping_id'=>$cm_id,
									'StudentMark.month_year_id'=>$month_year_id,
									'StudentMark.student_id'=>$student_id,
							),
							'fields' => array('StudentMark.id'),
							'recursive' => 0
					));
					$data=array();
					$data['StudentMark']['month_year_id'] = $month_year_id;
					$data['StudentMark']['student_id'] = $student_id;
					$data['StudentMark']['course_mapping_id'] = $cm_id;
					$data['StudentMark']['marks'] = $marks;
					$data['StudentMark']['status'] = $statusArray[$student_id];
					$data['StudentMark']['grade_point'] = $student_details['grade_point'][$student_id];
					$data['StudentMark']['grade'] = $student_details['grade'][$student_id];
					if(isset($data_exists['StudentMark']['id']) && $data_exists['StudentMark']['id']>0) {
						$id = $data_exists['StudentMark']['id'];
						$data['StudentMark']['id'] = $id;
						$data['StudentMark']['modified_by'] = $this->Auth->user('id');
						$data['StudentMark']['modified'] = date("Y-m-d H:i:s");
					}
					else {
						$this->StudentMark->create($data);
						$data['StudentMark']['created_by'] = $this->Auth->user('id');
					}
					//pr($this->data);
					$this->StudentMark->save($data);
					$bool = true;
				}
			}
		}
		return true;
	}
	
	public function totalCaeAndEse($caeArray, $eseArray, $total_min_pass, $course_max_marks, $min_ese_mark, $max_ese_mark, $min_cae_mark, $max_cae_mark, $cm_id) {
		$total = array();
		$resultArray = array();
		foreach ($caeArray as $student_id => $caeMark) {
			if ($caeMark == "" || $caeMark == 'A' || $caeMark == "a") $caeMark = 0;
			if (isset($eseArray[$student_id])) {
				$eseMark = $eseArray[$student_id];
			}
			else {
				$eseMark = 0;
			}
			
			$total = $caeMark + $eseMark;
			$resultArray['total'][$student_id] = $total;
			$total_pass_mark = round($course_max_marks * $total_min_pass / 100);
			$ese_pass_mark = round($max_ese_mark * $min_ese_mark / 100);
			
			$cae_pass_mark = round($max_cae_mark * $min_cae_mark / 100);
			
			//echo $cm_id." ".$cae_pass_mark."</br>";
			if ($total >= $total_pass_mark && $eseMark >= $ese_pass_mark && $caeMark >= $cae_pass_mark) {
				$result = "Pass";
				$gradeDetails = $this->getGP($cm_id, $total, "GRADE");
				$grade_point = $gradeDetails['grade_point'];
				$grade = $gradeDetails['grade'];
			}
			else {
				$result = "Fail";
				$grade_point = 0;
				$grade = "RA";
			}
			$resultArray['result'][$student_id] = array(
					"status" => $result,
					"grade_point" => $grade_point,
					"grade" => $grade
			);
			//echo "</br>".$cm_id." ".$total_pass_mark." ".$total." ".$ese_pass_mark." ".$eseMark." ".$result;
		}
		return $resultArray;
	}
	
	public function cleanData($tmp) {
		$array = array();
		foreach ($tmp as $key => $tmpArray) {
			$array[$tmpArray['student_id']] = $tmpArray['marks'];
		}
		return $array;
	}
	
	public function filterCriteria($batch_id, $academic_id, $program_id, $month_year_id) {
		
		$filterCondition = "";
		
		if ($batch_id > 0) {
			$filterCondition.= "`(CourseMapping`.`batch_id` = ".$batch_id.") AND ";
		}
		
		if($program_id > 0) {
			$filterCondition.= "`(CourseMapping`.`program_id` = ".$program_id.")"." AND ";
		} else {
			$filterCondition.= "`(CourseMapping`.`program_id` > 0)"." AND ";
		}
			
		if($month_year_id > 0) {
			$filterCondition.= "`(CourseMapping`.`month_year_id` = ".$month_year_id.")"." AND ";
		} else {
			$month_year_id.= "`(CourseMapping`.`month_year_id` > 0)"." AND ";
		}
			
		$filterCondition.= "((`CourseMapping`.`indicator` = 0)";
		//$filterCondition.= "(`$currentModel`.`indicator` = 0)";
		$filterCondition.=")";
		
		//pr($filterCondition);
		return $filterCondition;
	}
	
	public function arrear() {
		$academics = $this->Program->Academic->find('list',array('order' => array('Academic.academic_name ASC')));
		$programs = $this->Program->find('list',array('order' => array('Program.program_name ASC')));
		$batches = $this->Batch->find('list', array('fields' => array('Batch.batch_period'),'order' => array('Batch.batch_to DESC')));
		$monthYears = $this->MonthYear->find('all', array('fields' => array('MonthYear.id','MonthYear.year','MonthYear.month_id','Month.month_name')));
		$monthyears=array();
		foreach ($monthYears as $key => $value) {
			$monthyears[$value['MonthYear']['id']]=$value['Month']['month_name']."-".$value['MonthYear']['year'];
		}
		$this->set(compact('academics','programs','batches','monthyears'));
		
		if($this->request->is('post')) {
			//pr($this->data);
			$ajax = 0;
			$month_year_id = $this->request->data['Arrear']['month_year_id'];
			if ($month_year_id <> "") {
				if($this->request->data['option'] == "PRINT") {
					$arrResults = $this->arrearReport($month_year_id, $ajax);
					$results = $arrResults['results'];
					$exam_month_year_id = $arrResults['exam_month_year_id'];
					$month_year = $arrResults['month_year'];
					$this->set(compact('results', 'exam_month_year_id', 'month_year'));
					$this->layout= false;
					$this->layout= 'print';
					$this->render('arrear_print');
				}
				else if($this->request->data['option'] == "DOWNLOAD") {
					//pr($this->data);
					$arrResults = $this->arrearReport($month_year_id, $ajax);
					$results = $arrResults['results'];
					$exam_month_year_id = $arrResults['exam_month_year_id'];
					$month_year = $arrResults['month_year'];
					$this->arrearData($results, $month_year_id, $month_year);
				}
			}
			else {
				$this->Flash->error("Choose Month and Year of Examination");
				$this->redirect("arrear");
			}
		}
	}

	public function arrearReport($month_year_id, $ajax) {
		$results = $this->getStudentMarksWithMonthYearId($month_year_id);
		//pr($results);
		$my = $this->MonthYear->find('first', array(
				'fields'=>array('MonthYear.id', 'MonthYear.year'),
				'conditions' => array('MonthYear.id' => $month_year_id),
				'contain' => array(
						'Month' => array(
								'fields' => array('Month.month_name')
						)
				)
		));
		//pr($my);
		$month_year = $my['Month']['month_name']."-".$my['MonthYear']['year'];
		if ($ajax) {
			$this->set(compact('results', 'exam_month_year_id', 'month_year'));
		}
		else {
			$arr = array();
			$arr['results'] = $results;
			$arr['exam_month_year_id'] = $month_year_id;
			$arr['month_year'] = $month_year;
			return $arr;
		}
		$this->layout = false;
	}
	
	public function getStudentMarksWithMonthYearId($month_year_id) {
		$results = $this->StudentMark->find('all', array(
				'conditions' => array('StudentMark.month_year_id' => $month_year_id, 'StudentMark.status'=>'Fail'),
				'fields' => array('StudentMark.student_id', 'StudentMark.month_year_id', 'StudentMark.course_mapping_id',
						'StudentMark.marks'
						/* 'GROUP_CONCAT(StudentMark.course_mapping_id) as cm_id',
						'GROUP_CONCAT(StudentMark.marks) as marks',
						'GROUP_CONCAT(StudentMark.status) as status' */
				),
				'contain' => array(
						'CourseMapping' => array(
								'fields' => array('CourseMapping.id'),
								'Course' => array(
										'fields' => array('Course.course_code', 'Course.course_name'),
										'CourseType' => array(
												'fields' => array('CourseType.course_type')
										)
								),
						),
						'Student' => array(
								'fields' => array('Student.id', 'Student.registration_number', 'Student.name'),
								'Batch' => array(
										'fields' => array('Batch.id', 'Batch.batch_period'
										),
								),
								'Program' => array(
										'fields' => array('Program.id', 'Program.program_name'),
										'Academic' => array(
												'fields' => array('Academic.id', 'Academic.academic_name'),
										),
								),
						)
				),
				'order' => array('Student.registration_number')
				/* 'group' => array('StudentMark.student_id') */
		));
		return $results;
	}
	
	public function cmSearch() {
		$access_result = $this->checkPathAccess($this->Auth->user('id'));
		if (!$access_result) {
			$academics = $this->Academic->find('list');
			$batches = $this->Batch->find('list', array('fields' => array('Batch.batch_period'), 'order'=>array('Batch.id DESC')));
			$monthYears = $this->MonthYear->getAllMonthYears();
			
			$TimetablesController = new TimetablesController;
			//$TimetablesController->course_search();
		//	$this->
			$this->set(compact('batches', 'academics', 'monthYears'));
			if($this->request->is('post')) {
				//pr($this->request->data); 
				$this->fnModeration($this->request->data, "CourseMod");
				//$this->redirect('cmSearch');
			}
		}
		else {
			$this->render('../Users/access_denied');
		}
	}
	
	public function listStudents($batch_id, $program_id, $option, $month_year_id) {
		$results = $this->StudentMark->query("
				SELECT DISTINCT (sm.student_id), count( * ) AS arrear_count, s.registration_number, s.name, 
				GROUP_CONCAT( sm.course_mapping_id ) AS cm_id_group, 
				GROUP_CONCAT( c.course_code ) AS course_code,
				GROUP_CONCAT( c.course_name ) AS course_name
				FROM student_marks sm
				JOIN students s ON sm.student_id = s.id
				JOIN course_mappings cm ON sm.course_mapping_id = cm.id
				JOIN courses c ON cm.course_id = c.id
				WHERE sm.id	IN (SELECT max(sm1.id) FROM `student_marks` sm1 JOIN students s1 ON sm1.student_id = s1.id
								WHERE s1.batch_id =$batch_id AND s1.program_id =$program_id
								GROUP BY sm1.student_id, sm1.course_mapping_id
								ORDER BY sm1.student_id, sm1.course_mapping_id DESC
								)
				AND ((sm.status = 'Fail' AND sm.revaluation_status =0) OR (sm.final_status = 'Fail'	AND sm.revaluation_status =1))
				AND s.batch_id =$batch_id 
				AND s.program_id =$program_id
				AND s.discontinued_status =0 
				GROUP BY sm.student_id
				");
			//pr($results); 
			$finalArray = array();
			foreach ($results as $key => $value) {
				if (($option > 3 && $value[0]['arrear_count'] >= $option) || ($option <= 3 && $value[0]['arrear_count'] == $option)) {  
					$finalArray[] = $value;
				}
			}
			//pr($finalArray);
		$this->set(compact('batch_id', 'program_id', 'finalArray', 'option', 'month_year_id'));
	} 
}