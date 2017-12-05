<?php
App::uses('AppController', 'Controller');
App::import('Controller', 'DummyMarks');

/**
 * DummyFinalMarks Controller
 *
 * @property DummyFinalMark $DummyFinalMark
 * @property PaginatorComponent $Paginator
 */
class DummyFinalMarksController extends AppController {
	public $cType = "theory";
	public $uses = array("DummyFinalMark", "EndSemesterExam", "CourseStudentMapping", "DummyMark", "DummyNumberAllocation", "DummyNumber", "ExamAttendance", "ContinuousAssessmentExam", "Timetable", "EsePractical", "CourseStudentMapping", "Course", "User", "Batch", "CourseFaculty", "Student", "Academic", "CaePractical", "Project", "Practical", "CaeProject", "GrossAttendance", "Cae", "CourseMode", "CourseMapping", "MonthYear", "Attendance", "InternalExam", "Program", "CourseType", "InternalExam");
/**
 * Components
 *
 * @var array
 */
	public $components = array('RequestHandler');

/**
 * index method
 *
 * @return void
 */
	public function index() {
		$this->DummyFinalMark->recursive = 0;
		$this->set('dummyFinalMarks', $this->Paginator->paginate());
	}

/**
 * view method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function view($id = null) {
		if (!$this->DummyFinalMark->exists($id)) {
			throw new NotFoundException(__('Invalid dummy final mark'));
		}
		$options = array('conditions' => array('DummyFinalMark.' . $this->DummyFinalMark->primaryKey => $id));
		$this->set('dummyFinalMark', $this->DummyFinalMark->find('first', $options));
	}

/**
 * add method
 *
 * @return void
 */
	public function add() {
		if ($this->request->is('post')) {
			$this->DummyFinalMark->create();
			if ($this->DummyFinalMark->save($this->request->data)) {
				$this->Flash->success(__('The dummy final mark has been saved.'));
				return $this->redirect(array('action' => 'index'));
			} else {
				$this->Flash->error(__('The dummy final mark could not be saved. Please, try again.'));
			}
		}
	}

/**
 * edit method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function edit($id = null) {
		if (!$this->DummyFinalMark->exists($id)) {
			throw new NotFoundException(__('Invalid dummy final mark'));
		}
		if ($this->request->is(array('post', 'put'))) {
			if ($this->DummyFinalMark->save($this->request->data)) {
				$this->Flash->success(__('The dummy final mark has been saved.'));
				return $this->redirect(array('action' => 'index'));
			} else {
				$this->Flash->error(__('The dummy final mark could not be saved. Please, try again.'));
			}
		} else {
			$options = array('conditions' => array('DummyFinalMark.' . $this->DummyFinalMark->primaryKey => $id));
			$this->request->data = $this->DummyFinalMark->find('first', $options);
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
		$this->DummyFinalMark->id = $id;
		if (!$this->DummyFinalMark->exists()) {
			throw new NotFoundException(__('Invalid dummy final mark'));
		}
		$this->request->allowMethod('post', 'delete');
		if ($this->DummyFinalMark->delete()) {
			$this->Flash->success(__('The dummy final mark has been deleted.'));
		} else {
			$this->Flash->error(__('The dummy final mark could not be deleted. Please, try again.'));
		}
		return $this->redirect(array('action' => 'index'));
	}
	
	public function moderation() {
		$access_result = $this->checkPathAccess($this->Auth->user('id'));
		if (!$access_result) {
		$monthyears = $this->MonthYear->getAllMonthYears();
		$this->set(compact('monthYears'));
		if($this->request->is('post')) {
			//pr($this->data);
			//die();
			$bool = false;
			$dummyModMarks = $this->request->data['EndSemesterExam']['dummy_mod_marks'];
			$dummyMarks = $this->request->data['EndSemesterExam']['marks'];
			$startRange = $this->request->data['DummyFinalMark']['dummy_number'];
			$dummy_number_id = $this->request->data['DummyFinalMark']['dummy_number_id'];
			$month_year_id = $this->request->data['DummyFinalMark']['month_year_id'];
			//$maxQpMark = $this->request->data['DummyFinalMark']['max_qp_mark'];
			$maxEseMark = $this->request->data['DummyFinalMark']['max_ese_mark'];
			$from = $this->request->data['from'];
			$to = $this->request->data['to'];
			
			foreach ($dummyMarks as $id => $securedMarks) {
				$modMarks = $dummyModMarks[$id];
				$sign = $this->request->data['sign'];
				if ($sign=="plus") {
					$totalMarks = $securedMarks+$this->request->data['mark'];
					if ($totalMarks > $maxEseMark) {
						$newModMarks = $maxEseMark - $securedMarks;
						$marks = $maxEseMark;
					}
					else if ($totalMarks == $maxEseMark) {
						$newModMarks = $this->request->data['mark'];
						$marks = $maxEseMark;
					}
					else if ($totalMarks < $maxEseMark) {
						$newModMarks = $this->request->data['mark'];
						$marks = $securedMarks+$this->request->data['mark'];
					}
					else {
						$newModMarks = 0;
						$marks = $securedMarks;
					}
					$modMarks = $newModMarks+$modMarks;
				}
				else if ($sign=="minus") {
					$marks = $marks-$this->request->data['mark'];
				}
				$data = array();
				$data['EndSemesterExam']['id'] = $id;
				$data['EndSemesterExam']['marks'] = $marks;
				$data['EndSemesterExam']['dummy_mod_operator'] = $sign;
				$data['EndSemesterExam']['dummy_mod_marks'] = $modMarks;
				$data['EndSemesterExam']['modified_by'] = $this->Auth->user('id');
				$data['EndSemesterExam']['modified'] = date("Y-m-d H:i:s");
				$this->EndSemesterExam->save($data);
				$bool=true;
			}
			if($bool) {
				$this->Flash->success("Dummy moderation success for marks between ".$from." and ".$to." with dummy number beginning ".$startRange);
				$this->redirect('moderation');
			}
		}
		}
		else {
			$this->render('../Users/access_denied');
		}
	}
	
	public function getDummyNumberDetails($month_year_id, $start_range) {
		$result = $this->DummyNumber->find('first', array(
				'conditions' => array('DummyNumber.month_year_id' => $month_year_id,
						'DummyNumber.start_range LIKE' => "$start_range%"
				),
				'fields' => array(
									'DummyNumber.id', 'DummyNumber.month_year_id', 'DummyNumber.start_range', 
									'DummyNumber.end_range', 'DummyNumber.mode'
				),
				'contain' => array(
					'DummyRangeAllocation' => array(
							'fields' => array(
											'DummyRangeAllocation.dummy_number_id', 'DummyRangeAllocation.timetable_id'),
							'Timetable' => array(
									'fields' => array('Timetable.month_year_id', 'Timetable.course_mapping_id'),
									'CourseMapping' => array(
											'fields' => array('CourseMapping.course_id'),
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
											'Course' => array(
													'fields' => array('Course.course_code', 'Course.common_code', 
																		'Course.course_name', 'Course.course_max_marks',
																		'Course.max_ese_qp_mark', 'Course.max_ese_mark'
													)
											)
									)
							)
					),
				),
				'recursive' => 2
		));
		return $result;
	}
	
	public function displayCourseDetails($month_year_id, $start_range) {
		
		$dummy_result = $this->DummyNumber->getDummyNumberId($month_year_id, $start_range);
		$dummy_number_id = $dummy_result['DummyNumber']['id'];
		$sync_status = $dummy_result['DummyNumber']['sync_status'];
		/* if ($sync_status) {
			$msg = "No more synchronization allowed as Dummy Marks are synchronized!!!";
			if($this->RequestHandler->isAjax()) {
				return new CakeResponse(array('body'=> json_encode(array('val'=>$msg)),'status'=>500));
			}
		}
		else { */
			/* $DummyMarksController = new DummyMarksController;
			$dummyDiffMarks = $DummyMarksController->getDummyDiffMarks($month_year_id, $start_range, 0); */
			
			//echo $moderate_status;
			if ($sync_status == 1) {
				
				$result = $this->getDummyNumberDetails($month_year_id, $start_range);
			
				/* $tFrom = (int)$result['DummyNumber']['end_range'];
				$tTo = (int)$result['DummyNumber']['start_range'];
				$total = $tFrom-$tTo+1; */
				
				/* $dmaCount = $this->DummyNumberAllocation->find('count', array(
						'conditions' => array('DummyNumberAllocation.dummy_number_id' => $dummy_number_id,
								'DummyNumberAllocation.indicator' => 0
						)
				));
				$dmMarks = $this->DummyMark->find('count', array(
						'conditions' => array('DummyMark.dummy_number_id' => $dummy_number_id,
								'DummyMark.indicator' => 0
						)
				));
				$dmSync = $this->EndSemesterExam->find('count', array(
						'conditions' => array('DummyFinalMark.dummy_number_id' => $dummy_number_id,
						)
				)); */
				
				/* if(count($dummyDiffMarks) > 0) {
					$msg = "Synchronize the marks for the Dummy Numbers starting from $start_range!!!";
					if($this->RequestHandler->isAjax()) {
						return new CakeResponse(array('body'=> json_encode(array('val'=>$msg)),'status'=>500));
					}
				}
				else  
				if ($total <> $dmaCount) {
					$msg = "Registration number not allocated properly for dummy number beginning with $start_range!!!";
					echo "<script>alert('".$msg."');</script>";
				}
				else if ($total <> $dmMarks) {
					$msg = "Marks not allocated properly for dummy number beginning with $start_range!!!";
					echo "<script>alert('".$msg."');</script>";
				}
				else if ($total <> $dmSync) {
					$msg = "Marks not synchronized for dummy number beginning with $start_range!!!";
					echo "<script>alert('".$msg."');</script>";
				}
				else {
					echo "true";
					die;
				}*/
				$this->set(compact('result', 'dummy_number_id'));
			}
			else {
				$msg = "Synchronize the marks for Dummy Number starting ".$start_range;
				echo "<script>alert('".$msg."');</script>";
			}
			
		//}
		$this->layout=false;
	}
	
	public function listMarksForRange($month_year_id, $start_range, $from, $to) {
		$dummy_result = $this->DummyNumber->getDummyNumberId($month_year_id, $start_range);
		$dummy_number_id = $dummy_result['DummyNumber']['id'];
		
		$result = $this->EndSemesterExam->find('all',
			array(
			 'conditions' => array('EndSemesterExam.marks between ? and ?' => array($from, $to),
			 						'EndSemesterExam.dummy_number_id' => $dummy_number_id
			 ),
			'fields' => array('EndSemesterExam.student_id', 'EndSemesterExam.dummy_mod_operator', 'EndSemesterExam.id',
							'EndSemesterExam.dummy_mod_marks', 'EndSemesterExam.marks','EndSemesterExam.dummy_number',)
			)
		);
		//pr($result);
		$this->set(compact('result', $result));
		$this->layout=false;
	}
	
	public function individualModeration() {
		if($this->request->is('post')) {
			//pr($this->data);
			//die;
			$bool = false;
			$id = $this->request->data['Dummy']['id'];
			$dummy_number = $this->request->data['Dummy']['number'];
			$oldValue = $this->request->data['Dummy']['Old'];
			$newValue = $this->request->data['Dummy']['New'];
			$oldModMarks = $this->request->data['Dummy']['mMarks'];
			if ($newValue > $oldValue) {
				$sign = "plus";
				$modMarks = $newValue - $oldValue;
				$modMarks = $modMarks+$oldModMarks;
			}
			$data = array();
			$data['DummyFinalMark']['id'] = $id;
			$data['DummyFinalMark']['marks'] = $newValue;
			$data['DummyFinalMark']['moderation_operator'] = $sign;
			$data['DummyFinalMark']['moderation_marks'] = $modMarks;
			$data['DummyFinalMark']['modified_by'] = $this->Auth->user('id');
			$data['DummyFinalMark']['modified'] = date("Y-m-d H:i:s");
			$this->DummyFinalMark->save($data);
			$bool=true;
			if($bool) {
				$this->Flash->success("Dummy moderation success for dummy number ".$dummy_number);
				$this->redirect('individualModeration');
			}
		}
	}
	
	public function getIndividualMark($dummy_number, $ajax) {
		$result = $this->DummyFinalMark->find('all', array(
						'conditions' => array('DummyFinalMark.dummy_number' => $dummy_number)
					));
		//pr($result);
		$this->set(compact('result'));
		$this->layout=false;
	}
	
	public function flagModeration($month_year_id, $start_range) {
		$dummy_result = $this->DummyNumber->getDummyNumberId($month_year_id, $start_range);
		$dummy_number_id = $dummy_result['DummyNumber']['id'];
		
		$data = array();
		$data['DummyNumber']['id'] = $dummy_number_id;
		$data['DummyNumber']['moderate_status'] = 1;
		$data['DummyNumber']['modified_by'] = $this->Auth->user('id');
		$data['DummyNumber']['modified'] = date("Y-m-d H:i:s");
		$this->DummyNumber->save($data);
		
		if($this->RequestHandler->isAjax()){
			if ($this->DummyNumber->save($data)) {
				$this->moveDummyFinalMarksToEse($month_year_id, $start_range);
				$msg = "No more moderation or synchronization can happen!!!";
				return new CakeResponse(array('body'=> json_encode(array('val'=>$msg)),'status'=>200));
			} else {
				return new CakeResponse(array('body'=> json_encode(array('val'=>'Error')),'status'=>500));
			}
		}
		
	}
	
	public function moveDummyFinalMarksToEse($month_year_id, $start_range) {
		
		$result = $this->getDummyNumberDetails($month_year_id, $start_range);
		$dummy_number_id = $result['DummyNumber']['id'];
		$mode = $result['DummyNumber']['mode'];
		
		//move to ese table
		
		
		/* $dmResults = $this->DummyNumber->find('all', array(
				'conditions' => array('DummyNumber.id' => $dummy_number_id),
				'fields' => array('DummyNumber.month_year_id', 'DummyNumber.start_range', 'DummyNumber.end_range',
									'DummyNumber.mode'
				),
				'contain' => array(
						'DummyNumberAllocation' => array('fields' => array(
								'DummyNumberAllocation.dummy_number_id', 'DummyNumberAllocation.student_id',
								'DummyNumberAllocation.dummy_number'
						)),
						'DummyFinalMark' => array('fields' => array(
								'DummyFinalMark.dummy_number_id', 'DummyFinalMark.marks', 'DummyFinalMark.dummy_number'
						)),
						'DummyRangeAllocation' => array(
								'fields' => array(
										'DummyRangeAllocation.dummy_number_id', 'DummyRangeAllocation.timetable_id'),
								'Timetable' => array(
										'fields' => array('Timetable.month_year_id', 'Timetable.course_mapping_id'),
										'CourseMapping' => array(
												'fields' => array('CourseMapping.course_id'),
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
												'Course' => array(
														'fields' => array('Course.course_code', 'Course.common_code',
																'Course.course_name', 'Course.course_max_marks',
																'Course.max_ese_qp_mark'
														)
												)
										)
								)
						),
				)
		));
		//pr($dmResults);
		
		if (isset($dmResults) && !empty($dmResults) && count($dmResults) > 0) {
			foreach ($dmResults as $key => $result) {
				$mode = $result['DummyNumber']['mode'];
				$drAllocation = $result['DummyRangeAllocation'];
				if ($mode == 'D') {
					$courseMappingArray[] = $drAllocation[0]['Timetable']['course_mapping_id'];
				}
				else if ($mode == 'C') {
					$courseMappingArray = array();
					$cmQpMark = array();
					foreach($drAllocation as $key => $drValue) {
						$courseMappingArray[] = $drValue['Timetable']['course_mapping_id'];
						$cmQpMark[$drValue['Timetable']['course_mapping_id']] = $drValue['Timetable']['CourseMapping']['Course']['max_ese_qp_mark'];
					}
				}
				else {
					
				}
			}
			pr($courseMappingArray);
			pr($cmQpMark);
			$dnAllocation = $result['DummyNumberAllocation'];
			$dna = array();
			foreach ($dnAllocation as $key => $dnaValue) {
				$dna[$dnaValue['dummy_number']] = $dnaValue['student_id'];
			}
			pr($dna);
				
			$dfMark = $result['DummyFinalMark'];
			$dfn = array();
			foreach ($dfMark as $key => $dfMarkValue) {
				$dfn[$dfMarkValue['dummy_number']] = $dfMarkValue['marks'];
			}
			pr($dfn);
			
		}
		
		$dummyCourseMappingArray = $this->getCourseMappingId($dna, $courseMappingArray);
		pr($dummyCourseMappingArray);
		//move ends here
		
		$eseResult = $this->saveToEndSemesterExam($courseMappingArray, $cmQpMark, $dna, $dfm, $dummyCourseMappingArray); */
		
		//echo count($result['DummyRangeAllocation']);
		//pr($result);
	}
	
	/* public function saveToEndSemesterExam($dummy_number_id, $month_year_id) {
		$dmResults = $this->DummyNumber->find('all', array(
				'conditions' => array('DummyNumber.id' => $dummy_number_id),
				'fields' => array('DummyNumber.month_year_id', 'DummyNumber.start_range', 'DummyNumber.end_range',
						'DummyNumber.mode'
				),
				'contain' => array(
						'DummyFinalMark' => array('fields' => array(
								'DummyFinalMark.dummy_number_id', 'DummyFinalMark.dummy_number', 'DummyFinalMark.marks'
						)),
						'DummyNumberAllocation' => array(
								'fields' => array(
										'DummyNumberAllocation.dummy_number_id', 'DummyNumberAllocation.student_id',
										'DummyNumberAllocation.dummy_number'
								),
						),
						'DummyRangeAllocation' => array(
								'fields' => array(
										'DummyRangeAllocation.dummy_number_id', 'DummyRangeAllocation.timetable_id'),
								'Timetable' => array(
										'fields' => array('Timetable.month_year_id', 'Timetable.course_mapping_id'),
								)
						),
				)
		));
		//pr($dmResults);
		
		$courseMappingArray = $this->getCourseMapping($dmResults[0]['DummyRangeAllocation']);
		$dna = $this->getDnaArray($dmResults[0]['DummyNumberAllocation']);
		$dfm = $this->getDfmArray($dmResults[0]['DummyFinalMark']);
		$dummyCourseMappingArray = $this->getCourseMappingId($dna, $courseMappingArray);
		pr($dna);
		pr($dfm);
		pr($courseMappingArray);
		
		//pr($dummyCourseMappingArray);
		//echo "Allocation ".count($dna);
		//echo "Mark ".count($dfm);
		//echo "CMAllocation ".count($dummyCourseMappingArray);
		
		$this->moveToEse($courseMappingArray, $dna, $dfm, $dummyCourseMappingArray, $dummy_number_id, $month_year_id);
		//return $dmResults;
	} */
	
	/* public function moveToEse($courseMappingArray, $dna, $dfm, $dummyCourseMappingArray, $dummy_number_id, $month_year_id) {
		//pr($dna);
		pr($dummyCourseMappingArray);
		
		foreach ($dna as $dummy_number => $student_id) {
			$conditions = array('EndSemesterExam.course_mapping_id' => $dummyCourseMappingArray[$dummy_number],
					'EndSemesterExam.month_year_id' => $month_year_id,
					'EndSemesterExam.dummy_number_id' => $dummy_number_id,
					'EndSemesterExam.student_id' => $student_id
			);
			//pr($conditions); 
			if ($this->EndSemesterExam->hasAny($conditions)){
				$this->EndSemesterExam->query("UPDATE end_semester_exams set
									actual_marks='".$dfm[$dummy_number]."',
									modified = '".date("Y-m-d H:i:s")."',
									modified_by = ".$this->Auth->user('id').",
									moderation_operator = '',
									moderation_marks = 0
									WHERE course_mapping_id = ".$dummyCourseMappingArray[$dummy_number]." AND
									dummy_number_id = ".$dummy_number_id." AND
									student_id = ".$student_id." AND
									month_year_id = ".$month_year_id
						);
				$bool = true;
			}
			else {
				$data=array();
				$data['EndSemesterExam']['course_mapping_id'] = $dummyCourseMappingArray[$dummy_number];
				$data['EndSemesterExam']['student_id'] = $student_id;
				$data['EndSemesterExam']['month_year_id'] = $month_year_id;
				$data['EndSemesterExam']['dummy_number_id'] = $dummy_number_id;
				$data['EndSemesterExam']['actual_marks'] = $dfm[$dummy_number];
				$data['EndSemesterExam']['moderation_marks'] = "";
				$data['EndSemesterExam']['moderation_operator'] = '';
				$data['EndSemesterExam']['created_by'] = $this->Auth->user('id');
				$this->EndSemesterExam->create();
				$this->EndSemesterExam->save($data);
			}
		}
	} */
	
	public function getDnaArray($dmResults) {
		$dmaArray = array();
		foreach ($dmResults as $key => $dm_array) {
			$dmaArray[$dm_array['dummy_number']] = $dm_array['student_id'];
		}
		return $dmaArray;
	}
	
	public function getDfmArray($dmResults) {
		$dfmArray = array();
		foreach ($dmResults as $key => $dfm_array) {
			$dfmArray[$dfm_array['dummy_number']] = $dfm_array['marks'];
		}
		return $dfmArray;
	}
	
	public function getCourseMapping($dra) {
		foreach($dra as $key => $drValue) {
			$courseMappingArray[$drValue['Timetable']['course_mapping_id']] = $drValue['Timetable']['course_mapping_id'];
		}
		return $courseMappingArray;
	}
	
	public function getCourseMappingId($dna, $courseMappingArray) {
		$cmArray = array();
		foreach ($dna as $dummy_number => $student_id) {
			$cmid = $this->CourseStudentMapping->find('all', array(
					'conditions' => array('CourseStudentMapping.student_id' => $student_id,
										'CourseStudentMapping.course_mapping_id' => $courseMappingArray
					),
					'fields' => array('CourseStudentMapping.course_mapping_id')
			));
			$cmArray[$dummy_number] = $cmid[0]['CourseStudentMapping']['course_mapping_id'];
		}
		return $cmArray;
	}
	
}
