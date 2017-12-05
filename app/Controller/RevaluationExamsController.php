<?php
App::uses('AppController', 'Controller');
App::import('Controller', 'EndSemesterExams');
App::import('Controller', 'ContinuousAssessmentExams');
/**
 * RevaluationExams Controller
 *
 * @property RevaluationExam $RevaluationExam
 * @property PaginatorComponent $Paginator
 * @property FlashComponent $Flash
 * @property SessionComponent $Session
 */
class RevaluationExamsController extends AppController {

/**
 * Components
 *
 * @var array
 */
	public $components = array('Paginator', 'Flash', 'Session');
	public $uses = array("RevaluationExam", "RevaluationDummyMark", "DummyNumber", "DummyRangeAllocation", "DummyNumberAllocation", "Revaluation",
			"MonthYear", "StudentMark", "Student", "EndSemesterExam", "CourseStudentMapping", "CourseMapping", "Batch", "Program",
			"InternalExam"
	);
/**
 * index method
 *
 * @return void
 */
	public function index() {
		$this->RevaluationExam->recursive = 0;
		$this->set('revaluationExams', $this->Paginator->paginate());
	}

/**
 * view method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function view($id = null) {
		if (!$this->RevaluationExam->exists($id)) {
			throw new NotFoundException(__('Invalid revaluation exam'));
		}
		$options = array('conditions' => array('RevaluationExam.' . $this->RevaluationExam->primaryKey => $id));
		$this->set('revaluationExam', $this->RevaluationExam->find('first', $options));
	}

/**
 * add method
 *
 * @return void
 */
	public function add() {
		if ($this->request->is('post')) {
			$this->RevaluationExam->create();
			if ($this->RevaluationExam->save($this->request->data)) {
				$this->Flash->success(__('The revaluation exam has been saved.'));
				return $this->redirect(array('action' => 'index'));
			} else {
				$this->Flash->error(__('The revaluation exam could not be saved. Please, try again.'));
			}
		}
		$courseMappings = $this->RevaluationExam->CourseMapping->find('list');
		$students = $this->RevaluationExam->Student->find('list');
		$monthYears = $this->RevaluationExam->MonthYear->find('list');
		$dummyNumbers = $this->RevaluationExam->DummyNumber->find('list');
		$this->set(compact('courseMappings', 'students', 'monthYears', 'dummyNumbers'));
	}

/**
 * edit method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function edit($id = null, $student_id=NULL, $cm_id=NULL, $exam_month_year_id, $course_code) {
		/* pr($id); pr($student_id); pr($cm_id); */
		
		$results = $this->RevaluationExam->find('all', array(
				'conditions' => array('RevaluationExam.id' => $id, 'RevaluationExam.course_mapping_id'=>$cm_id),
				/* 'fields' => array('RevaluationExam.month_year_id', 'RevaluationExam.marks') */
				'contain' => array(
						'Student' => array('fields'=>array('Student.id', 'Student.registration_number'),
								'conditions' => array('Student.id'=>$student_id),
						'CourseMapping' => array(
								'fields' => array('CourseMapping.id'),
								'conditions' => array('CourseMapping.id' => $cm_id),
								'Course' => array(
										'fields' => array('Course.course_code','Course.min_cae_mark', 
										'Course.min_ese_mark', 'Course.max_cae_mark', 
										'Course.max_ese_mark', 'Course.total_min_pass', 
										'Course.course_max_marks')
								),
						),
						'InternalExam' => array('fields'=>array('InternalExam.id', 'InternalExam.marks', 'InternalExam.course_mapping_id'),
												'conditions' => array('InternalExam.course_mapping_id'=>$cm_id, 'InternalExam.month_year_id'=>$exam_month_year_id)
						),
						'EndSemesterExam' => array('fields'=>array('EndSemesterExam.id', 'EndSemesterExam.marks', 'EndSemesterExam.course_mapping_id', 'EndSemesterExam.dummy_number_id', 'EndSemesterExam.dummy_number'),
								'conditions' => array('EndSemesterExam.course_mapping_id'=>$cm_id, 'EndSemesterExam.month_year_id'=>$exam_month_year_id)
						),
						'StudentMark' => array('fields'=>array('StudentMark.id', 'StudentMark.marks',
								'StudentMark.course_mapping_id', 'StudentMark.status'),
								'conditions' => array('StudentMark.course_mapping_id'=>$cm_id, 'StudentMark.month_year_id'=>$exam_month_year_id)
						)
						)
				)
		));
		//pr($results);
		$this->set(compact('results', 'id', 'student_id', 'exam_month_year_id', 'course_code'));
		//$this->layout=false;
		//$this->render('edit');
	}
	public function update($id = null, $student_id=NULL, $cm_id=NULL,$exam_month_year_id = null,$new_mark = null) {
		/* pr($id); pr($student_id); pr($cm_id); */
	
		$results = $this->RevaluationExam->find('all', array(
				'conditions' => array('RevaluationExam.id' => $id, 'RevaluationExam.course_mapping_id'=>$cm_id),
				/* 'fields' => array('RevaluationExam.month_year_id', 'RevaluationExam.marks') */
				'contain' => array(
						'Student' => array('fields'=>array('Student.id', 'Student.registration_number'),
								'conditions' => array('Student.id'=>$student_id),
								'CourseMapping' => array(
										'fields' => array('CourseMapping.id'),
										'conditions' => array('CourseMapping.id' => $cm_id),
										'Course' => array(
												'fields' => array('Course.course_code','Course.min_cae_mark',
														'Course.min_ese_mark', 'Course.max_cae_mark',
														'Course.max_ese_mark', 'Course.total_min_pass',
														'Course.course_max_marks')
										),
								),
								'InternalExam' => array('fields'=>array('InternalExam.id', 'InternalExam.marks', 'InternalExam.course_mapping_id'),
										'conditions' => array('InternalExam.course_mapping_id'=>$cm_id, 'InternalExam.month_year_id'=>$exam_month_year_id)
								),
								'EndSemesterExam' => array('fields'=>array('EndSemesterExam.id', 'EndSemesterExam.marks', 'EndSemesterExam.course_mapping_id', 'EndSemesterExam.dummy_number_id', 'EndSemesterExam.dummy_number'),
										'conditions' => array('EndSemesterExam.course_mapping_id'=>$cm_id, 'EndSemesterExam.month_year_id'=>$exam_month_year_id)
								),
								'StudentMark' => array('fields'=>array('StudentMark.id', 'StudentMark.marks',
										'StudentMark.course_mapping_id', 'StudentMark.status'),
										'conditions' => array('StudentMark.course_mapping_id'=>$cm_id, 'StudentMark.month_year_id'=>$exam_month_year_id)
								)
						)
				)
		));
		$msg = "";
		if($results){
			
			$old_mark =$results[0]['RevaluationExam']['revaluation_marks'];
			
			$internal_mark = $results[0]['Student']['InternalExam'][0]['marks'];
			
			$course_max_mark = $results[0]['Student']['CourseMapping'][0]['Course']['course_max_marks'];
			$max_ese_mark = $results[0]['Student']['CourseMapping'][0]['Course']['max_ese_mark'];
			$min_ese_pass_percent = $results[0]['Student']['CourseMapping'][0]['Course']['min_ese_mark'];
			$total_min_pass_percent = $results[0]['Student']['CourseMapping'][0]['Course']['total_min_pass'];
			$ese_pass_mark = round($max_ese_mark * $min_ese_pass_percent / 100);
			$total_pass_mark = round($course_max_mark * $total_min_pass_percent / 100);
			
			$total = $internal_mark + $new_mark;
			if ($total >= $total_pass_mark && $new_mark >= $ese_pass_mark) $resultStatus = "Pass";
			else $resultStatus = "Fail";
			$mod_mark = abs($new_mark-$old_mark);
			if ($new_mark > $old_mark) $mod_operator = "plus";
			else $mod_operator = "minus";
			
			//echo "Result : ".$resultStatus;
			$data = array();
			$data['RevaluationExam']['id'] = $id;
			$data['RevaluationExam']['reval_moderation_operator'] = $mod_operator;
			$data['RevaluationExam']['reval_moderation_marks'] = $mod_mark;
			$data['RevaluationExam']['revaluation_marks'] = $new_mark;
			$data['RevaluationExam']['total_marks'] = $total;
			$data['RevaluationExam']['status'] = $resultStatus;
			$data['RevaluationExam']['modified'] = date("Y-m-d H:i:s");
			$data['RevaluationExam']['modified_by'] = $this->Auth->user('id');	
			$this->RevaluationExam->save($data);
			$msg = "Successfully Completed.";
		}else{
			$msg = "Failure. Try Again";
		}
		echo  $msg;exit;
	}
/**
 * delete method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function delete($id = null) {
		$this->RevaluationExam->id = $id;
		if (!$this->RevaluationExam->exists()) {
			throw new NotFoundException(__('Invalid revaluation exam'));
		}
		$this->request->allowMethod('post', 'delete');
		if ($this->RevaluationExam->delete()) {
			$this->Flash->success(__('The revaluation exam has been deleted.'));
		} else {
			$this->Flash->error(__('The revaluation exam could not be deleted. Please, try again.'));
		}
		return $this->redirect(array('action' => 'index'));
	}
	
	public function moderation() {
		$access_result = $this->checkPathAccess($this->Auth->user('id'));
		if (!$access_result) {
		$monthYears = $this->MonthYear->getAllMonthYears();
		$this->set(compact('monthYears'));
		$bool = false;
		if ($this->request->is('post') && isset($this->request->data['reval_submit']) && $this->request->data['reval_submit']!="") {
			//echo "1"; die;
			//pr($this->request->data); die;
			$new_mark = $this->request->data['RevaluationExam']['new_revaluation_mark'];
			$old_mark = $this->request->data['RevaluationExam']['old_revaluation_mark'];
			$internal_mark = $this->request->data['RevaluationExam']['internal'];
			$total_pass_mark = $this->request->data['RevaluationExam']['total_min_pass_mark'];
			$ese_pass_mark = $this->request->data['RevaluationExam']['min_ese_mark'];
			$total = $internal_mark + $new_mark;
			if ($total >= $total_pass_mark && $new_mark >= $ese_pass_mark) $resultStatus = "Pass";
			else $resultStatus = "Fail";
			$mod_mark = abs($new_mark-$old_mark);
			if ($new_mark > $old_mark) $mod_operator = "plus";
			else $mod_operator = "minus";
				
			//echo "Result : ".$resultStatus;
			$data = array();
			$data['RevaluationExam']['id'] = $this->request->data['RevaluationExam']['id'];
			$data['RevaluationExam']['reval_moderation_operator'] = $mod_operator;
			$data['RevaluationExam']['reval_moderation_marks'] = $mod_mark;
			$data['RevaluationExam']['revaluation_marks'] = $new_mark;
			$data['RevaluationExam']['total_marks'] = $total;
			$data['RevaluationExam']['status'] = $resultStatus;
			$data['RevaluationExam']['modified'] = date("Y-m-d H:i:s");
			$data['RevaluationExam']['modified_by'] = $this->Auth->user('id');
			//pr($data);
			$this->RevaluationExam->save($data);
			$bool=true;
		}
		else if ($this->request->is('post') && isset($this->request->data['reval_apply'])) {
			//echo "2. Applied for Impreovement. Passed after revaluation. Diff from to"; die;
			//pr($this->request->data);
			$diff = $this->request->data['RevaluationExam']['diff'];
			$diff_from = $this->request->data['diff_from'];
			$diff_to = $this->request->data['diff_to'];
			$adjust_to = $this->request->data['adjust_to'];
			
			$revIdArray = $this->request->data['RevaluationExam']['rev_id'];
			$studentIdArray = $this->request->data['RevaluationExam']['student_id'];
			$caeMarkArray = $this->request->data['RevaluationExam']['caeMark'];
			$revaluationMarkArray = $this->request->data['RevaluationExam']['revaluationMark'];
			$revaluationModMarkArray = $this->request->data['RevaluationExam']['reval_mod_mark'];
			$revaluationModOperatorArray = $this->request->data['RevaluationExam']['reval_mod_operator'];
			$totalMarkArray = $this->request->data['RevaluationExam']['total'];
			$oldEseMarkArray = $this->request->data['RevaluationExam']['oldEseMark'];
			$totalPassMarkArray = $this->request->data['RevaluationExam']['total_min_pass_mark'];
			$esePassMarkArray = $this->request->data['RevaluationExam']['min_ese_pass_mark'];
			
			foreach ($diff as $key => $diffValue) {
				if ($diffValue>=$diff_from && $diffValue<=$diff_to) {
					//echo $key." ".$diffValue."</br>";
					$rev_id = $revIdArray[$key];
					$student_id = $studentIdArray[$key];
					$cae_mark = $caeMarkArray[$key];
					$reval_mark = $revaluationMarkArray[$key];
					$reval_mod_mark = $revaluationModMarkArray[$key];
					$total_mark = $totalMarkArray[$key];
					$old_ese_mark = $oldEseMarkArray[$key];
					$ese_pass_mark = $esePassMarkArray[$key];
					$total_pass_mark = $totalPassMarkArray[$key];
					
					$new_reval_mark = $adjust_to+$old_ese_mark;
					$total = $new_reval_mark + $cae_mark;
					$mod_mark = $reval_mark - $new_reval_mark;
					
					if ($new_reval_mark > $reval_mark) {
						$mod_operator = "plus";
					}
					else {
						$mod_operator = "minus";
					}
					
					if ($total >= $total_pass_mark && $new_reval_mark >= $ese_pass_mark) {
						$resultStatus = "Pass";
						$data = array();
						$data['RevaluationExam']['id'] = $rev_id;
						$data['RevaluationExam']['reval_moderation_operator'] = $mod_operator;
						$data['RevaluationExam']['reval_moderation_marks'] = $mod_mark;
						$data['RevaluationExam']['revaluation_marks'] = $new_reval_mark;
						$data['RevaluationExam']['total_marks'] = $total;
						$data['RevaluationExam']['status'] = $resultStatus;
						$data['RevaluationExam']['modified'] = date("Y-m-d H:i:s");
						$data['RevaluationExam']['modified_by'] = $this->Auth->user('id');
						//pr($data);
						$this->RevaluationExam->save($data);
						$bool=true;
					}
					
/* 					echo "New Reval Mark".$new_reval_mark."</br>";
					echo "total".$total."</br>";
					echo "status".$resultStatus."</br>";
					echo "mod mark".$mod_mark."</br>";
					echo "mod ope".$mod_operator."</br>"; */
					//die;
					
				}
			}
			//echo "Result : ".$resultStatus;
		}
		else if ($this->request->is('post') && isset($this->request->data['reval_pass']) && $this->request->data['reval_pass']=='fail_pass') {
			//echo "3. Applied for Pass and Passed after revaluation"; die;
			$found = false;
			//pr($this->request->data);
			
			//$diff = $this->request->data['RevaluationExam']['diff'];
			$ese_from = $this->request->data['ese_greater_than'];
			$ese_to = $this->request->data['ese_lesser_than'];
			$total_from = $this->request->data['total_greater_than'];
			$total_to = $this->request->data['total_lesser_than'];
			//$adjust_to = $this->request->data['RevaluationExam']['adjust_to'];
			
			$revIdArray = $this->request->data['RevaluationExam']['rev_id'];
			$studentIdArray = $this->request->data['RevaluationExam']['student_id'];
			$caeMarkArray = $this->request->data['RevaluationExam']['caeMark'];
			$revaluationMarkArray = $this->request->data['RevaluationExam']['revaluationMark'];
			$revaluationModMarkArray = $this->request->data['RevaluationExam']['reval_mod_mark'];
			$revaluationModOperatorArray = $this->request->data['RevaluationExam']['reval_mod_operator'];
			$totalMarkArray = $this->request->data['RevaluationExam']['total'];
			$oldEseMarkArray = $this->request->data['RevaluationExam']['oldEseMark'];
			$totalPassMarkArray = $this->request->data['RevaluationExam']['total_min_pass_mark'];
			$esePassMarkArray = $this->request->data['RevaluationExam']['min_ese_pass_mark'];
			
			foreach ($revaluationMarkArray as $key => $revalMark) { 
				if ($revalMark >= $ese_from && $revalMark <= $ese_to) {
					if ($totalMarkArray[$key]>=$total_from && $totalMarkArray[$key]<=$total_to) {
						//echo "</br>".$key." ".$revIdArray[$key]." ".$totalMarkArray[$key]." ".$studentIdArray[$key];
						$found=true;
						$rev_id = $revIdArray[$key];
						$student_id = $studentIdArray[$key];
						$cae_mark = $caeMarkArray[$key];
						$reval_mark = $revaluationMarkArray[$key];
						//$reval_mod_mark = $revaluationModMarkArray[$key];
						$total_mark = $totalMarkArray[$key];
						$old_ese_mark = $oldEseMarkArray[$key];
						$ese_pass_mark = $esePassMarkArray[$key];
						$total_pass_mark = $totalPassMarkArray[$key];
							
						$new_reval_mark = $total_pass_mark-$cae_mark;
						$total = $new_reval_mark + $cae_mark;
						$mod_mark = $reval_mark - $new_reval_mark;
							
						if ($new_reval_mark > $reval_mark) {
							$mod_operator = "plus";
						}
						else {
							$mod_operator = "minus";
						}
							
						if ($total >= $total_pass_mark && $new_reval_mark >= $ese_pass_mark) {
							$resultStatus = "Pass";
						}
						else {
							$resultStatus = "Fail";
						}
						/* echo "New Reval Mark".$new_reval_mark."</br>";
						echo "total".$total."</br>";
						echo "status".$resultStatus."</br>";
						echo "mod mark".$mod_mark."</br>";
						echo "mod ope".$mod_operator."</br>"; */
						
						$data = array();
						$data['RevaluationExam']['id'] = $rev_id;
						$data['RevaluationExam']['reval_moderation_operator'] = $mod_operator;
						$data['RevaluationExam']['reval_moderation_marks'] = $mod_mark;
						$data['RevaluationExam']['revaluation_marks'] = $new_reval_mark;
						$data['RevaluationExam']['total_marks'] = $total;
						$data['RevaluationExam']['status'] = $resultStatus;
						$data['RevaluationExam']['modified'] = date("Y-m-d H:i:s");
						$data['RevaluationExam']['modified_by'] = $this->Auth->user('id');
						//pr($data);
						$this->RevaluationExam->save($data);
						$bool=true;
					} 
				} 
			}
			}
			else if ($this->request->is('post') && isset($this->request->data['after_reval_fail']) && $this->request->data['after_reval_fail']!="") {
				//echo "4 Appllied for Pass and Failed after revaluation"; die;
				//pr($this->data);
				
				$revIdArray = $this->request->data['RevaluationExam']['rev_id'];
				$studentIdArray = $this->request->data['RevaluationExam']['student_id'];
				$caeMarkArray = $this->request->data['RevaluationExam']['caeMark'];
				$revaluationMarkArray = $this->request->data['RevaluationExam']['revaluationMark'];
				$revaluationModMarkArray = $this->request->data['RevaluationExam']['reval_mod_mark'];
				$revaluationModOperatorArray = $this->request->data['RevaluationExam']['reval_mod_operator'];
				$totalMarkArray = $this->request->data['RevaluationExam']['total'];
				$oldEseMarkArray = $this->request->data['RevaluationExam']['oldEseMark'];
				$totalPassMarkArray = $this->request->data['RevaluationExam']['total_min_pass_mark'];
				$esePassMarkArray = $this->request->data['RevaluationExam']['min_ese_pass_mark'];
					
				foreach ($revIdArray as $key => $value) {
						//echo $key." ".$value."</br>";
						$rev_id = $value;
						$student_id = $studentIdArray[$key];
						$cae_mark = $caeMarkArray[$key];
						$reval_mark = $revaluationMarkArray[$key];
						$reval_mod_mark = $revaluationModMarkArray[$key];
						$total_mark = $totalMarkArray[$key];
						$old_ese_mark = $oldEseMarkArray[$key];
						$ese_pass_mark = $esePassMarkArray[$key];
						$total_pass_mark = $totalPassMarkArray[$key];
						
						//$new_reval_mark = $total_pass_mark-$cae_mark;
						$mod_mark = $ese_pass_mark-$reval_mark;
						$new_reval_mark = $reval_mark + $mod_mark;
						$total = $new_reval_mark + $cae_mark;
						//echo $cae_mark." ".$new_reval_mark." ".$reval_mark." ".$mod_mark." ".$total."</br>";
						if ($total <= $total_pass_mark) { echo "test";
							$total_mod_mark = $total_pass_mark-$total;
							$mod_mark = $mod_mark + $total_mod_mark;
							$new_reval_mark = $new_reval_mark+$total_mod_mark;
							$total = $new_reval_mark + $cae_mark;
						}
						if ($new_reval_mark > $reval_mark) {
							$mod_operator = "plus";
						}
						else {
							$mod_operator = "minus";
						}
							
						if ($total >= $total_pass_mark && $new_reval_mark >= $ese_pass_mark) {
							$resultStatus = "Pass";
						}
						else {
							$resultStatus = "Fail";
						}
						//echo $cae_mark." ".$new_reval_mark." ".$reval_mark." ".$mod_operator." ".$resultStatus." ".$mod_mark;
						//echo "**".$total_mod_mark." ".$total;
						/* echo "New Reval Mark".$new_reval_mark."</br>";
						echo "total".$total."</br>";
						echo "status".$resultStatus."</br>";
						echo "mod mark".$mod_mark."</br>";
						echo "mod ope".$mod_operator."</br>"; */
						//die;
						$data = array();
						$data['RevaluationExam']['id'] = $rev_id;
						$data['RevaluationExam']['reval_moderation_operator'] = $mod_operator;
						$data['RevaluationExam']['reval_moderation_marks'] = $mod_mark;
						$data['RevaluationExam']['revaluation_marks'] = $new_reval_mark;
						$data['RevaluationExam']['total_marks'] = $total;
						$data['RevaluationExam']['status'] = $resultStatus;
						$data['RevaluationExam']['modified'] = date("Y-m-d H:i:s");
						$data['RevaluationExam']['modified_by'] = $this->Auth->user('id');
						//pr($data);
						$this->RevaluationExam->save($data);
						$bool=true;
				}
			}
			//$this->Flash->success('Moderated successfully');
			//if ($bool) return $this->redirect(array('action' => 'moderation'));
			}
			else {
				$this->render('../Users/access_denied');
			}
		}
		
		
	
	public function displayDetails($month_year_id, $option) {
		$result = $this->Revaluation->find('all', array(
				'conditions' => array('Revaluation.month_year_id' => $month_year_id,
						'Revaluation.previous_status' => $option
				),
				'fields' => array(
						'Revaluation.id',/*  'DummyNumber.month_year_id', 'DummyNumber.start_range',
						'DummyNumber.end_range', 'DummyNumber.mode' */
				),
			)
		);
		//pr($result);
	}
	
	/* public function displayCourseDetails($month_year_id, $start_range) {
		$dummy_result = $this->DummyNumber->getDummyNumberId($month_year_id, $start_range);
		$dummy_number_id = $dummy_result['DummyNumber']['id'];
		$reval_sync_status = $dummy_result['DummyNumber']['revaluation_sync_status'];
		
		if ($reval_sync_status == 1) {
			$result = $this->getDummyNumberDetails($month_year_id, $start_range);
			//pr($result);
			$this->set(compact('result', 'dummy_number_id'));
		}
		else {
			$msg = "Synchronize the marks for Dummy Number starting ".$start_range;
			echo "<script>alert('".$msg."');</script>";
		}
			
		$this->layout=false;
	} */
	
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
						'RevaluationDummyMark' => array('fields' => array(
								'RevaluationDummyMark.dummy_number_id', 'RevaluationDummyMark.dummy_number',
								'RevaluationDummyMark.mark_entry1'
						)),
				),
				'recursive' => 2
		));
		return $result;
	}
	
	/* public function listMarksForRange($month_year_id, $start_range, $from, $to) {
		$dummy_result = $this->DummyNumber->getDummyNumberId($month_year_id, $start_range);
		$dummy_number_id = $dummy_result['DummyNumber']['id'];
	
		$result = $this->RevaluationExam->find('all',
				array(
						'conditions' => array('RevaluationExam.final_marks between ? and ?' => array($from, $to),
								'RevaluationExam.dummy_number_id' => $dummy_number_id
						),
						'fields' => array('RevaluationExam.student_id', 'RevaluationExam.reval_dummy_mod_operator', 
								'RevaluationExam.id', 'RevaluationExam.reval_dummy_mod_marks', 
								'RevaluationExam.final_marks','RevaluationExam.dummy_number',)
				)
				);
		//pr($result);
		$this->set(compact('result', $result));
		$this->layout=false;
	} */
	
	public function getRevaluationResult($exam_month_year_id, $option, $failed_option, $revaluation_type=null, $ese_from=null, $ese_to=null, $total_from=null, $total_to=null) {
		//echo $exam_month_year_id." ".$option." ".$failed_option;
		$print=null;
		$results = $this->getResult($exam_month_year_id, $option, $failed_option, $print, $ese_from, $ese_to, $total_from, $total_to);
		//pr($results);
		//echo $exam_month_year_id;
		$this->set(compact('results', 'option', 'failed_option', 'revaluation_type', 'ese_from', 
				'ese_to', 'total_from', 'total_to', 'print'));
		$this->set(compact('exam_month_year_id', $exam_month_year_id));
		$this->layout=false;
		if ($revaluation_type == "ar") $this->render('get_after_revaluation_result');
		else if ($revaluation_type == "br") $this->render('get_before_revaluation_result');
		else if ($revaluation_type == null) $this->render('get_revaluation_result');
		
	}
	
	public function getResult($exam_month_year_id, $option, $failed_option, $print, $ese_from=null, $ese_to=null, $total_from=null, $total_to=null) {
		//echo "hello";
		$results = array();
		$course_mapping_array = array();
		
		$conditions= array('RevaluationExam.month_year_id'=>$exam_month_year_id,
				'RevaluationExam.status'=>$failed_option,
				//'RevaluationExam.course_mapping_id'=>946,
				//'RevaluationExam.student_id'=>6089,
		);
		
		$rev_results = $this->RevaluationExam->find('all', array(
				'conditions' => $conditions,
				'fields'=>array('RevaluationExam.revaluation_id', 'RevaluationExam.dummy_number_id', 'RevaluationExam.student_id',
						'RevaluationExam.dummy_number', 'RevaluationExam.marks', 'RevaluationExam.total_marks',
						'RevaluationExam.id', 'RevaluationExam.reval_moderation_marks', 'RevaluationExam.course_mapping_id',
						'RevaluationExam.reval_moderation_operator', 'RevaluationExam.revaluation_marks',
						'RevaluationExam.status'
				),
				'contain'=> array(
						'Revaluation' => array(
								'conditions' => array('Revaluation.previous_status' => $option, 'Revaluation.indicator'=>0,
										'Revaluation.month_year_id'=>$exam_month_year_id),
								'fields'=>array('Revaluation.course_mapping_id', 'Revaluation.student_id', 'Revaluation.month_year_id',
										'Revaluation.ese_marks', 'Revaluation.student_marks', 'Revaluation.previous_status',
										'Revaluation.id'
								),
						),
				)
			)
		);
		/* $dbo = $this->CourseMapping->getDatasource();
		$logs = $dbo->getLog();
		$lastLog = end($logs['log']);
		echo $lastLog['query']; */
		//pr($rev_results);
		
		foreach ($rev_results as  $key => $revArray) {
			//pr($revArray);
			//die;
			if (isset($revArray['Revaluation']['previous_status']) && $revArray['Revaluation']['previous_status'] == $option) { 
				$cm_id = $revArray['RevaluationExam']['course_mapping_id'];
				$course_mapping_array = array();
				$course_mapping_array[$cm_id]=$cm_id;
				$caeArray = $this->RevaluationExam->Student->InternalExam->find('all', array(
					'fields'=>array('DISTINCT InternalExam.course_mapping_id', 'InternalExam.student_id',
							'InternalExam.marks', 'InternalExam.month_year_id'
					),
					'conditions' => array('InternalExam.month_year_id <='=>$exam_month_year_id,
							'InternalExam.student_id'=>$revArray['RevaluationExam']['student_id'],
							'InternalExam.course_mapping_id'=>$cm_id
					),
					'order'=>array('InternalExam.month_year_id DESC'),
					'limit'=>1
				));
				//pr($caeArray);
				$eseArray = $this->RevaluationExam->Student->EndSemesterExam->find('all', array(
						'fields'=>array('EndSemesterExam.course_mapping_id', 'EndSemesterExam.student_id',
								'EndSemesterExam.marks', 'EndSemesterExam.moderation_marks',
								'EndSemesterExam.moderation_operator', 'EndSemesterExam.id',
								'EndSemesterExam.month_year_id'
						),
						'conditions' => array('EndSemesterExam.month_year_id'=>$exam_month_year_id,
								'EndSemesterExam.student_id'=>$revArray['RevaluationExam']['student_id'],
								'EndSemesterExam.course_mapping_id'=>$cm_id,
						)
				));
				//pr($eseArray);
				$smArray = $this->RevaluationExam->Student->StudentMark->find('all', array(
						'fields'=>array('StudentMark.course_mapping_id', 'StudentMark.student_id',
								'StudentMark.marks', 'StudentMark.month_year_id'
						),
						'conditions' => array('StudentMark.month_year_id'=>$exam_month_year_id,
								'StudentMark.course_mapping_id'=>$cm_id,
								'StudentMark.student_id'=>$revArray['RevaluationExam']['student_id']
						),
				));
				//pr($smArray);
				
				$courseDetails = $this->CourseMapping->retrieveCourseDetails($course_mapping_array, $exam_month_year_id);
				//pr($courseDetails);
				
				$rev_id = $revArray['RevaluationExam']['id'];
				$results[$rev_id]['student_id'] = $revArray['RevaluationExam']['student_id'];
				$stuInfo = $this->Student->studentDetails($revArray['RevaluationExam']['student_id']);
				//pr($stuInfo); 
				$results[$rev_id]['regNum'] = $stuInfo[0]['Student']['registration_number'];
				$results[$rev_id]['name'] = $stuInfo[0]['Student']['name'];
				$results[$rev_id]['cm_id'] = $cm_id;
				
				$results[$rev_id]['internalMark'] = $caeArray[0]['InternalExam']['marks'];
				$results[$rev_id]['oldEseMark'] = $eseArray[0]['EndSemesterExam']['marks'];
				
				$results[$rev_id]['dummy_number'] = $revArray['RevaluationExam']['dummy_number'];
				$results[$rev_id]['total_marks'] = $revArray['RevaluationExam']['total_marks'];
				$results[$rev_id]['reval_mod_mark'] = $revArray['RevaluationExam']['reval_moderation_marks'];
				$results[$rev_id]['reval_mod_operator'] = $revArray['RevaluationExam']['reval_moderation_operator'];
				$results[$rev_id]['revaluation_mark'] = $revArray['RevaluationExam']['revaluation_marks'];
				$results[$rev_id]['total'] = $results[$rev_id]['internalMark'] + $results[$rev_id]['revaluation_mark'];
				
				$results[$rev_id]['course_code'] = $courseDetails[$cm_id]['course_code'];
				$results[$rev_id]['min_cae_mark'] = $courseDetails[$cm_id]['min_cae_mark'];
				$results[$rev_id]['max_cae_mark'] = $courseDetails[$cm_id]['max_cae_mark'];
				$results[$rev_id]['max_ese_mark'] = $courseDetails[$cm_id]['max_ese_mark'];
				$results[$rev_id]['min_ese_pass_mark'] = $courseDetails[$cm_id]['min_ese_mark'];
				$results[$rev_id]['course_max_mark'] = $courseDetails[$cm_id]['course_max_marks'];
				$results[$rev_id]['total_min_pass_mark'] = $courseDetails[$cm_id]['min_pass_mark'];
				//pr($results);
				
				if ($results[$rev_id]['revaluation_mark'] >= $ese_from && $results[$rev_id]['revaluation_mark'] <= $ese_to) {
					$ese_bool = true;
				}
				else {
					$ese_bool = false;
				}
				if ($results[$rev_id]['total_marks'] >= $total_from && $results[$rev_id]['total_marks'] <= $total_to) {
					$total_bool = true;
				}
				else {
					$total_bool = false;
				}
				
				if ($ese_bool && $total_bool) $results[$rev_id]['range'] = 1;
				else $results[$rev_id]['range'] = 0;
				//pr($results);
			}
		}
		//pr($results);
		//echo count($results);
		if ($print==null) {
			return $results;
		}
		else if ($print=='PRINT') {
			$this->set(compact('results', 'exam_month_year_id', 'option', 'failed_option', 'print'));
			$this->layout='print';
			$this->render('get_revaluation_result');
			return false;
		}
		else if ($print=='DOWNLOAD') {
			$this->set(compact('results', 'exam_month_year_id', 'option', 'failed_option', 'print'));
			$this->layout='false';
			//$this->
			$this->download($results, $exam_month_year_id);
			$this->layout='false';
			return false;
		}
	}
	
	public function download($results, $exam_month_year_id) {
		//echo count($results);
		//pr($results);
		$row = 1; // 1-based index
		$col = 0;
		$phpExcel = new PHPExcel();
		$phpExcel->setActiveSheetIndex(0);
		$sheet = $phpExcel->getActiveSheet();
		$sheet->getRowDimension('1')->setRowHeight('18');
		$sheet->setTitle("Publish_Result_Mark_Data");
		
		$sheet->setCellValueByColumnAndRow($col, $row, "REGISTER NUMBER");$col++;
		$sheet->setCellValueByColumnAndRow($col, $row, "STUDENT NAME");$col++;
		$sheet->setCellValueByColumnAndRow($col, $row, "COURSE CODE");$col++;
		$sheet->setCellValueByColumnAndRow($col, $row, "CAE");$col++;
		$sheet->setCellValueByColumnAndRow($col, $row, "NEW ESE");$col++;
		$sheet->setCellValueByColumnAndRow($col, $row, "NEW TOTAL");$col++;
		$sheet->setCellValueByColumnAndRow($col, $row, "OLD ESE");$col++;
		$sheet->setCellValueByColumnAndRow($col, $row, "DIFFERENCE");$col++;
		$sheet->setCellValueByColumnAndRow($col, $row, "DUMMY NUMBER");$col++;
		$sheet->setCellValueByColumnAndRow($col, $row, "MIN ESE PASS MARK");$col++;
		$sheet->setCellValueByColumnAndRow($col, $row, "MIN TOTAL PASS MARK");$col++;
		$row++;
		
		foreach ($results as $rev_id => $result) {
			$col = 0;
			$sheet->getRowDimension($row)->setRowHeight('18');
			
			$cm_id = $result['cm_id'];
			//echo $cm_id."</br>";
			$internalMark=$result['internalMark'];
			$oldEseMark=$result['oldEseMark'];
		
			$reval_mod_mark = $result['reval_mod_mark'];
			$reval_mod_operator = $result['reval_mod_operator'];
			$revaluation_mark = $result['revaluation_mark'];
			
			$total = $internalMark + $revaluation_mark;
			
			$max_ese_mark = $result['max_ese_mark'];
			$min_ese_pass_mark = $result['min_ese_pass_mark'];
		
			$course_max_mark = $result['course_max_mark'];
			$total_min_pass_mark = $result['total_min_pass_mark'];
		
			$student_id = $result['student_id'];
			$course_code = $result['course_code'];
			$diff = $result['revaluation_mark']-$result['oldEseMark'];
			
			$sheet->setCellValueByColumnAndRow($col, $row, $result['regNum']);$col++;
			$sheet->setCellValueByColumnAndRow($col, $row, $result['name']);$col++;
			$sheet->setCellValueByColumnAndRow($col, $row, $course_code);$col++;
			$sheet->setCellValueByColumnAndRow($col, $row, $internalMark);$col++;
			$sheet->setCellValueByColumnAndRow($col, $row, $revaluation_mark);$col++;
			$sheet->setCellValueByColumnAndRow($col, $row, $result['total']);$col++;
			$sheet->setCellValueByColumnAndRow($col, $row, $result['oldEseMark']);$col++;
			$sheet->setCellValueByColumnAndRow($col, $row, $diff);$col++;
			$sheet->setCellValueByColumnAndRow($col, $row, $result['dummy_number']);$col++;
			$sheet->setCellValueByColumnAndRow($col, $row, $min_ese_pass_mark);$col++;
			$sheet->setCellValueByColumnAndRow($col, $row, $total_min_pass_mark);$col++;
			$row++;
		}	
		$download_filename="Data_After_RV-".date('d-M-Y h:i:s');
		header("Content-Type: application/vnd.ms-excel");
		header("Content-Disposition: attachment; filename=\"$download_filename.xls\"");
		header("Cache-Control: max-age=0");
		$objWriter = PHPExcel_IOFactory::createWriter($phpExcel, "Excel5");
		$objWriter->save("php://output");
		exit;
	}
	
	public function failedOption($option) {
		if ($option == "Fail") {
			$this->layout=false;
		}
	}
	
	public function diffOption($option) {
		if ($option == "Pass") {
			$this->layout=false;
		}
	}
	
	public function revalPassModeration() {
		//if ($option == "Pass") {
			$this->layout=false;
		//}
	}
	
	public function revalFailModeration() {
		//if ($option == "Pass") {
		$this->layout=false;
		//}
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
	
	public function cleanData($revaluation_cm_id) {
		//pr($revaluation_cm_id);
		$revaluationCMArray = array();
		foreach ($revaluation_cm_id as $key => $revArray) {
			$revaluationCMArray[] = array("student_id" => $revArray['RevaluationExam']['student_id'],
					"cm_id" => $revArray['RevaluationExam']['course_mapping_id']);
		}
		return $revaluationCMArray;
	}
	
	public function batchwiseReport($batch_id, $month_year_id, $print=NULL) {
		$academic_id=0;
		$program_id=0;
		
		$this->set(compact('batch_id', 'month_year_id', 'print'));
	
		$course_mapping_array = $this->CourseMapping->find('list', array(
				'conditions'=>array('CourseMapping.batch_id'=>$batch_id, 'CourseMapping.indicator'=>0,
						'CourseMapping.month_year_id <='=>$month_year_id
				)
		));
		ksort($course_mapping_array);
		//pr($course_mapping_array);
		
		//echo "count : ".count($course_mapping_array);
		$course_details = $this->CourseMapping->retrieveCourseDetails($course_mapping_array, $month_year_id);
		//pr($course_details);
		//echo "count : ".count($course_details);
		$this->set(compact('course_details'));
		
		$results = $this->RevaluationExam->find('all', array(
				'conditions' => array(
						'RevaluationExam.month_year_id' => $month_year_id,
						//'RevaluationExam.course_mapping_id' => 266,
						//'RevaluationExam.student_id' => 3015,
						'RevaluationExam.course_mapping_id'=> array_keys($course_mapping_array)
				),
				'fields' => array('RevaluationExam.id', 'RevaluationExam.course_mapping_id',
						'RevaluationExam.revaluation_marks', 'RevaluationExam.total_marks', 
						'RevaluationExam.student_id', 'RevaluationExam.status'
				),
		));
		/* $dbo = $this->CourseMapping->getDatasource();
		$logs = $dbo->getLog();
		$lastLog = end($logs['log']);
		echo $lastLog['query']; */
		//echo count($results);
		//pr($results);
		
		
		foreach ($results as $key => $revArray) {
			//pr($revArray);
			$status = "";
			$grade_point = 0;
			$grade = "";
			
			$rev_id = $revArray['RevaluationExam']['id'];
			$cm_id = $revArray['RevaluationExam']['course_mapping_id'];
			//echo "cm_id ".$cm_id;
			$rev_status = $revArray['RevaluationExam']['status'];
			$student_id = $revArray['RevaluationExam']['student_id'];
			$internalResult = $this->InternalExam->find('first', array(
				'fields' => array('InternalExam.marks'),
				'conditions' =>array('InternalExam.course_mapping_id' => $cm_id, 'InternalExam.student_id' => $student_id),
				'order'=>array('InternalExam.month_year_id DESC'),
				'limit'=>1
			));
			//pr($internalResult);
			if (isset($internalResult['InternalExam']['marks'])) {
				$cae_marks = $internalResult['InternalExam']['marks'];
			}
			else $cae_marks = 0;
			
			$eseResult = $this->EndSemesterExam->find('first', array(
					'fields' => array('EndSemesterExam.marks'),
					'conditions' =>array('EndSemesterExam.month_year_id' => $month_year_id,
							'EndSemesterExam.course_mapping_id' => $cm_id, 'EndSemesterExam.student_id' => $student_id
					)
			));
			$ese_marks = $eseResult['EndSemesterExam']['marks'];
			
			$smResult = $this->StudentMark->find('first', array(
					'fields' => array('StudentMark.id', 'StudentMark.marks', 'StudentMark.status'),
					'conditions' =>array('StudentMark.month_year_id' => $month_year_id,
							'StudentMark.course_mapping_id' => $cm_id, 'StudentMark.student_id' => $student_id
					)
			));
			$sm_id = $smResult['StudentMark']['id'];
			$sm_marks = $smResult['StudentMark']['marks'];
			$sm_status = $smResult['StudentMark']['status'];
			//pr($smResult);
			$revaluation_marks = $revArray['RevaluationExam']['revaluation_marks'];
			$new_total_marks = $cae_marks + $revaluation_marks;
			//echo "</br>REVID :".$rev_id." Internal : ".$cae_marks." ESE : ".$ese_marks." Total : ".$sm_marks." RevaluationMarks : ".$revaluation_marks." NEW total : ".$new_total_marks;
			//echo "</br>".$cae_marks." ".$ese_marks." ".$revaluation_marks;
			if ($ese_marks > $revaluation_marks) {
				$new_ese_mark = $ese_marks;
				$final_marks = $sm_marks;
				//$final_status = $sm_status;
			}
			else {
				$new_ese_mark = $revaluation_marks;
				$final_marks = $new_total_marks;
				//$final_status = $rev_status;
			}
			$data = array();
			$data['StudentMark']['id'] = $sm_id;
			$data['StudentMark']['revaluation_status'] = 1;
			$data['StudentMark']['final_marks'] = $final_marks;
			$data['StudentMark']['modified'] = date("Y-m-d H:i:s");
			$data['StudentMark']['modified_by'] = $this->Auth->user('id');
			
			if ($cae_marks >= $course_details[$cm_id]['min_cae_mark']) { 
				if ($new_ese_mark >= $course_details[$cm_id]['min_ese_mark']) { 
					if ($final_marks >= $course_details[$cm_id]['min_pass_mark']) { 
						$status = "Pass";
						$computed_mark = round(($final_marks/$course_details[$cm_id]['course_max_marks'])*100);
						$grade_point = $this->grade_point($computed_mark);
						$grade = $this->grade($computed_mark);
					}
					else {
						$status = "Fail";
						$grade_point = 0;
						$grade = "RA";
					}
				}
				else {
					$status = "Fail";
					$grade_point = 0;
					$grade = "RA";
				}
			}
			else {
				$status = "Fail";
				$grade_point = 0;
				$grade = "RA";
			}
			$data['StudentMark']['final_status'] = $status;
			$data['StudentMark']['grade_point'] = $grade_point;
			$data['StudentMark']['grade'] = $grade;
			$this->StudentMark->save($data);
			//pr($data);
			
			//echo " SM Mark ID : ".$sm_id;
		}
		$EndSemesterExamsController = new EndSemesterExamsController;
		# now you can reference your controller like any other PHP class
		$EndSemesterExamsController->withdrawalAbs($month_year_id, $batch_id, $course_mapping_array);
				
		echo "Success";
		$this->autoRender = false;
		
	}
	
	public function getReport($batch_id, $month_year_id, $print=NULL) {
		//echo $batch_id." ".$month_year_id;
		$reportResult = array();
	
		$programResult = $this->CourseMapping->find('all', array(
				'conditions' => array('CourseMapping.batch_id' => $batch_id, 'CourseMapping.month_year_id' => $month_year_id,
						'CourseMapping.indicator' => 0
				),
				'fields' => array('DISTINCT CourseMapping.program_id'),
				'contain' => array(
						'Batch' => array(
								'fields' => array('Batch.batch_from', 'Batch.batch_to', 'Batch.academic')
						),
						'Program' => array(
								'fields' => array('Program.short_code'),
								'Academic' => array(
										'fields' => array('Academic.short_code')
								)
						),
						'Course' => array(
								'fields' => array('Course.course_code', 'Course.common_code',
										'Course.course_name', 'Course.course_max_marks', 'Course.total_min_pass',
										'Course.max_ese_qp_mark', 'Course.max_cae_mark', 
										'Course.max_ese_mark', 'Course.min_ese_mark'
								),
								'CourseType' => array(
										'fields' => array('CourseType.course_type')
								)
						),
						'InternalExam'=>array(
								'fields' => array('InternalExam.month_year_id', 'InternalExam.student_id',
										'InternalExam.marks'
								),
								'conditions' => array('InternalExam.month_year_id' => $month_year_id)
						),
						'EndSemesterExam'=>array(
								'fields' => array('EndSemesterExam.month_year_id', 'EndSemesterExam.student_id',
										'EndSemesterExam.marks', 'EndSemesterExam.revaluation_status'
								),
								'conditions' => array('EndSemesterExam.month_year_id' => $month_year_id)
						),
						'CaePractical'=>array('fields' => array('CaePractical.course_mapping_id', 'CaePractical.marks'),
								'conditions'=>array('CaePractical.indicator'=>0),
								'InternalPractical' => array('fields'=>array('InternalPractical.student_id', 'InternalPractical.marks',))
						),
						'EsePractical'=>array('fields' => array('EsePractical.course_mapping_id', 'EsePractical.marks'),
								'conditions'=>array('EsePractical.indicator'=>0),
								'Practical' => array('fields'=>array('Practical.student_id', 'Practical.marks',))
						),
						'StudentMark'=>array(
								'fields' => array('StudentMark.month_year_id', 'StudentMark.student_id',
										'StudentMark.marks', 'StudentMark.revaluation_status', 'StudentMark.final_marks',
										'StudentMark.final_status'
								),
								'conditions' => array('StudentMark.month_year_id' => $month_year_id)
						),
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
			$pgm_short_code = $pgmResult['Program']['short_code'];
			$academic_short_code = $pgmResult['Program']['Academic']['short_code'];
			$csmResults = $this->Student->find('list', array(
					'conditions' => array('Student.batch_id' => $batch_id, 'Student.program_id'=>$program_id, 'Student.discontinued_status' => 0),
					'fields' => array('Student.id'),
			));
			//pr($csmResults);
	
			$totalStrength = $this->Student->find('count', array(
					'conditions' => array('Student.batch_id' => $batch_id, 'Student.program_id'=>$program_id, 'Student.discontinued_status' => 0),
					'fields' => array('Student.id'),
			));
			//pr($totalStrength);
	
			$allPass = 0;
			$oneArrear = 0;
			$twoArrear = 0;
			$threeArrear = 0;
			$moreThanThreeArrear = 0;
			$i = 1;
			foreach ($csmResults as $student_id => $stuid) {
				//echo "</br>".$i++." ".$student_id;
					
				$tmpCsmCount = $this->CourseStudentMapping->query("select count(*) as csm from course_student_mappings where
						student_id = $student_id and indicator=0");
				//pr($tmpCsmCount);
				$csmCount = $tmpCsmCount[0][0]['csm'];
					
				$tmpPassResults = $this->StudentMark->query("select count(*) as pass from student_marks where 
						((status='Pass' and revaluation_status=0) or (revaluation_status=1 and final_status='Pass')) 
						and student_id = ".$student_id);
				/* $tmpPassResults = $this->StudentMark->query("select count(*) as pass from student_marks where
						status='Pass' and student_id = ".$student_id); */
				
				//pr($tmpPassResults);
				$passCount = $tmpPassResults[0][0]['pass'];
					
				if ($csmCount == $passCount) {
					$allPass++;
				}
					
				$tmpFailResults = $this->StudentMark->query("select count(*) as fail from student_marks where
						((status='Fail' and revaluation_status=0) or (revaluation_status=1 and final_status='Fail')) 
						and student_id = ".$student_id);
				/* $tmpFailResults = $this->StudentMark->query("select count(*) as fail from student_marks where
						status='Fail' and student_id = ".$student_id); */
				//pr($tmpFailResults);
				$failCount = $tmpFailResults[0][0]['fail'];
				//echo " CSM : ".$csmCount." Pass : ".$passCount." Fail : ".$failCount."</br>";
					
				if ($failCount == 1) $oneArrear++;
				else if ($failCount == 2) $twoArrear++;
				else if ($failCount == 3) $threeArrear++;
				else if ($failCount > 3) $moreThanThreeArrear++;
					
			}
			$passPercent = round(100*$allPass/$totalStrength);
			$reportResult[$program_id]['batch'] = $batch_period;
			$reportResult[$program_id]['academic'] = $academic_short_code;
			$reportResult[$program_id]['program'] = $pgm_short_code;
			$reportResult[$program_id]['totalStrength'] = $totalStrength;
			$reportResult[$program_id]['allPass'] = $allPass;
			$reportResult[$program_id]['oneArrear'] = $oneArrear;
			$reportResult[$program_id]['twoArrear'] = $twoArrear;
			$reportResult[$program_id]['threeArrear'] = $threeArrear;
			$reportResult[$program_id]['moreThanThreeArrear'] = $moreThanThreeArrear;
			$reportResult[$program_id]['totalPercent'] = $passPercent;
	
		}
		//pr($reportResult);
		if ($print==1) {
			//$this->layout= false;
			$this->layout= 'print';
			$this->set(compact('print', 'reportResult', 'batch_id', 'month_year_id'));
			$this->render('get_report');
		}
		else {
			return $reportResult;
		}
		//$this->set(compact('print', 'reportResult', 'batch_id', 'month_year_id'));
		//echo "TotalStrength : ".$totalStrength." AllPass : ".$allPass." 1_Arrear : ".$oneArrear." 2_Arrear : ".$twoArrear." 3_Arrear : ".$threeArrear." >3 Arrear : ".$moreThanThreeArrear." PassPercentage: ".$passPercent."</br>";
		//$this->render("batchwise_report");
	}
	
	/* public function revaluationFailedRecords($exam_month_year_id) {
		$revaluation_cm_id = $this->RevaluationExam->find("all", array(
				'fields' => array('RevaluationExam.course_mapping_id', 'RevaluationExam.student_id', 'RevaluationExam.id',
						'RevaluationExam.revaluation_id'
				),
				'conditions' => array('RevaluationExam.status'=>'Fail'),
				'contain' => array(
					'CourseMapping' => array(
						'fields' => array('CourseMapping.id'),
						'Course' => array(
							'fields' => array('Course.course_code','Course.min_cae_mark', 
											'Course.min_ese_mark as min_ese_pass_percent', 
											'Course.max_cae_mark', 'Course.max_ese_mark',
											'Course.total_min_pass as total_min_pass_percent', 
											'Course.course_max_marks')
						),
					),
				)
		));
		return $revaluation_cm_id;
	} */
	
	/* public function beforeRevaluation($exam_month_year_id, $exam_option, $failed_option, $diff_from, $diff_to, $revaluation_type) {
		$revaluation_cm_id = $this->revaluationFailedRecords($exam_month_year_id);
		//pr($revaluation_cm_id);
		$failedCount = count($revaluation_cm_id);
		$tmpArray = array();
		$cnt=0;
		foreach ($revaluation_cm_id as $key => $revArray) {
			$cm_id = $revArray['RevaluationExam']['course_mapping_id'];
			$student_id = $revArray['RevaluationExam']['student_id'];
			$rev_id = $revArray['RevaluationExam']['revaluation_id'];
			$eseResult = $this->EndSemesterExam->find("first", array(
				'fields' => array('EndSemesterExam.course_mapping_id', 'EndSemesterExam.student_id', 'EndSemesterExam.marks',
					'EndSemesterExam.id', 'EndSemesterExam.moderation_operator', 'EndSemesterExam.moderation_marks'
				),
				'conditions' => array('EndSemesterExam.course_mapping_id'=>$cm_id, 'EndSemesterExam.student_id'=>$student_id,
						'EndSemesterExam.month_year_id'=>$exam_month_year_id
				)
			));
			//pr($eseResult);
			
			$ese_marks = $eseResult['EndSemesterExam']['marks'];
			$ese_id = $eseResult['EndSemesterExam']['id'];
			$moderation_operator = $eseResult['EndSemesterExam']['moderation_operator'];
			$moderation_marks = $eseResult['EndSemesterExam']['moderation_marks'];
			$course_max_marks = $revArray['Course']['course_max_marks'];
			$total_min_pass_mark = round($course_max_marks * $revArray['Course']['total_min_pass_percent'] / 100);
			//echo "CM ID ".$cm_id." student_id ".$student_id." ese_id ".$ese_id." ese_marks ".$ese_marks;
			
			$smResult = $this->StudentMark->find('first', array(
					'fields'=>array('StudentMark.marks', 'StudentMark.id'),
					'conditions' => array('StudentMark.course_mapping_id'=>$cm_id, 'StudentMark.student_id'=>$student_id,
										'StudentMark.month_year_id'=>$exam_month_year_id
					)
			));
			//pr($smResult);
			
			$br_mark = $smResult['StudentMark']['marks'];
			$upper_limit = $total_min_pass_mark - $diff_from;
			$lower_limit = $total_min_pass_mark - $diff_to;
			//echo "LM ".$lower_limit." UP ".$upper_limit."</br>";
			
			$ieResult = $this->InternalExam->find('first', array(
					'fields'=>array('InternalExam.marks'),
					'conditions' => array('InternalExam.course_mapping_id'=>$cm_id, 'InternalExam.student_id'=>$student_id,
							'InternalExam.month_year_id'=>$exam_month_year_id
					)
			));
			//pr($ieResult);
			$cae_mark = $ieResult['InternalExam']['marks'];
			
			if($br_mark >=$lower_limit && $br_mark <= $upper_limit && $br_mark<$total_min_pass_mark) {
				$cnt=$cnt+1;
				//$tmpArray[] = 
				//echo " Available ";
				$new_ese_mark = $total_min_pass_mark - $cae_mark;
				$new_moderation_marks = $new_ese_mark - $ese_marks + $moderation_marks;
				//echo "mod marks ".$new_moderation_marks;
				$new_total = $new_ese_mark+$cae_mark;
				$data=array();
				$data['EndSemesterExam']['id']=$ese_id;
				$data['EndSemesterExam']['moderation_marks']=$new_moderation_marks;
				$data['EndSemesterExam']['moderation_operator']="plus";
				$data['EndSemesterExam']['marks']=$new_ese_mark;
				$data['EndSemesterExam']['modified_by']=$this->Auth->user('id');
				$data['EndSemesterExam']['modified']='".date("Y-m-d H:i:s")."';
				$this->EndSemesterExam->save($data);
				
				$data=array();
				$data['StudentMark']['id']=$smResult['StudentMark']['id'];
				$data['StudentMark']['marks']=$new_total;
				$data['StudentMark']['status']="Pass";
				$data['StudentMark']['modified_by']=$this->Auth->user('id');
				$data['StudentMark']['modified']='".date("Y-m-d H:i:s")."';
				$this->StudentMark->save($data);
				
			}
		}
		
		$this->layout=false;
		if ($cnt > 0) {
			echo $cnt." records moderatred";
		}
		else {
			echo "No records matching the criteria!!!";
		}
		
		$this->render("br_fail_moderation");
		
	} */
	
	/* public function revaluationFailedRecordsWithDifference($exam_month_year_id, $diff_from, $diff_to) {
		$revaluation_cm_id = $this->RevaluationExam->find("all", array(
				'fields' => array('RevaluationExam.id', 'RevaluationExam.student_id', 'RevaluationExam.course_mapping_id',
						'RevaluationExam.revaluation_id', 'RevaluationExam.marks', 'RevaluationExam.reval_moderation_marks',
						'RevaluationExam.reval_moderation_operator', 'RevaluationExam.total_marks'
				),
				'conditions' => array('RevaluationExam.status'=>'Fail', 
						'RevaluationExam.total_marks BETWEEN '.$diff_from.' AND '.$diff_to,
				),
				'contain' => array(
						'CourseMapping' => array(
								'fields' => array('CourseMapping.id'),
								'Course' => array(
										'fields' => array('Course.course_code','Course.min_cae_mark', 
												'Course.min_ese_mark as min_ese_pass_percent', 
												'Course.max_cae_mark', 'Course.max_ese_mark',
												'Course.total_min_pass as total_min_pass_percent', 
												'Course.course_max_marks')
								),
						),
				)
		));
		return $revaluation_cm_id;
	} */
	
	/* public function afterRevaluation($exam_month_year_id, $exam_option, $failed_option, $diff_from, $diff_to, $revaluation_type) {
		// $from = 50-$diff_to;
		//$to = 50-$diff_from; 
		$revaluation_cm_id = $this->revaluationFailedRecordsWithDifference($exam_month_year_id, $from, $to);
		//pr($revaluation_cm_id);
		$failedCount = count($revaluation_cm_id);
		$tmpArray = array();
		$cnt=0;
		foreach ($revaluation_cm_id as $key => $revArray) {
			$id = $revArray['RevaluationExam']['id'];
			$rev_id = $revArray['RevaluationExam']['revaluation_id'];
			$cm_id = $revArray['RevaluationExam']['course_mapping_id'];
			$student_id = $revArray['RevaluationExam']['student_id'];
			$rev_ese_marks = $revArray['RevaluationExam']['marks'];
			$total_marks = $revArray['RevaluationExam']['total_marks'];
			$moderation_marks = $revArray['RevaluationExam']['reval_moderation_marks'];
			//echo "Total Marks : ".$total_marks;

			$course_max_marks = $revArray['Course']['course_max_marks'];
			$total_min_pass_mark = round($course_max_marks * $revArray['Course']['total_min_pass_percent'] / 100);
			
			$max_ese_mark = $revArray['Course']['max_ese_mark'];
			$ese_pass_percent = $revArray['Course']['min_ese_pass_percent'];
			$min_ese_pass_mark = round($max_ese_mark * $ese_pass_percent / 100);
			
			$ieResult = $this->InternalExam->find('first', array(
					'fields'=>array('InternalExam.marks'),
					'conditions' => array('InternalExam.course_mapping_id'=>$cm_id, 'InternalExam.student_id'=>$student_id,
							'InternalExam.month_year_id'=>$exam_month_year_id
					)
			));
			//pr($ieResult);
			$cae_mark = $ieResult['InternalExam']['marks'];
				
			if($total_marks >=$from && $total_marks <= $to && $total_marks < $total_min_pass_mark) {
				$cnt=$cnt+1;
				//echo " Available ";
				$new_ese_mark = $total_min_pass_mark - $cae_mark;
				$new_moderation_marks = $new_ese_mark - $rev_ese_marks + $moderation_marks;
				$new_total = $new_ese_mark+$cae_mark;
				$data=array();
				$data['RevaluationExam']['id']=$id;
				$data['RevaluationExam']['reval_moderation_marks']=$new_moderation_marks;
				$data['RevaluationExam']['reval_moderation_operator']="plus";
				$data['RevaluationExam']['revaluation_marks']=$new_ese_mark;
				$data['RevaluationExam']['total_marks']=$total_min_pass_mark;
				
				if ($new_ese_mark>=$min_ese_pass_mark) {
					$resultStatus = "Pass";
				}
				else {
					$resultStatus = "Fail";
				}
				$data['RevaluationExam']['status']=$resultStatus;
				$data['RevaluationExam']['modified_by']=$this->Auth->user('id');
				$data['RevaluationExam']['modified']=date("Y-m-d H:i:s");
				//pr($data);
				$this->RevaluationExam->save($data);
	
			}
		}
	
		$this->layout=false;
		if ($cnt > 0) {
			echo $cnt." records moderatred";
		}
		else {
			echo "No records matching the criteria!!!";
		}
	
		$this->render("br_fail_moderation");
	
	} */
	
	/* public function displayResult($exam_month_year_id, $exam_option, $failed_option, $diff_from, $diff_to, $revaluation_type) {
		$revaluation_cm_id = $this->revaluationFailedRecords($exam_month_year_id);
		pr($revaluation_cm_id);
		$failedCount = count($revaluation_cm_id);
	} */
	
	public function revaluationPrint($exam_month_year_id, $option, $failed_option, $print) {
		echo $print;
		$results = $this->getResult($exam_month_year_id, $option, $failed_option, $print);
		pr($results);
		$failedCount = count($results);
		echo $failedCount;
	}
}