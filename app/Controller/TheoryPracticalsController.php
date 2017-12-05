<?php
App::uses('AppController', 'Controller');
App::import('Vendor', 'PHPExcel', array('file' => 'PHPExcel'.DS.'IOFactory.php'));
App::import('Vendor', 'mPDF', array('file' => 'mPDF'.DS.'mPDF.php'));
/**
 * TheoryPracticalsController Controller
 */
class TheoryPracticalsController extends AppController {
	public $cType = "theory";
	public $uses = array("TheoryPracticalsController", "ContinuousAssessmentExam", "EsePractical", "CourseStudentMapping", "Course", 
			"User", "Batch", "CourseFaculty", "Student", "Academic", "CaePractical", "Project", "Practical", "CaeProject", 
			"GrossAttendance", "Cae", "CourseMode", "CourseMapping", "MonthYear", "Attendance", "InternalExam", "Program", 
			"CourseType","EseProject", "StudentMark", "StudentAuthorizedBreak"
	);
	
/**
 * Components
 *
 * @var array
 */
	public $components = array('PhpExcel.PhpExcel','mPDF');
	var $helpers = array('Html', 'Form', 'PhpExcel.PhpExcel');

/**
 * index method
 *
 * @return void
 */
	public function tpModerate() {
		$academics = $this->Student->Academic->find('list');
		$batches = $this->Student->Batch->find('list', array('fields' => array('Batch.batch_period')));
		$monthYear = $this->MonthYear->find('all');
		$monthYears = array();
		for ($i=0; $i<count($monthYear);$i++) {
			$monthYears[$monthYear[$i]['MonthYear']['id']] = $monthYear[$i]['Month']['month_name']." - ".$monthYear[$i]['MonthYear']['year'];
		}
		$this->set(compact('batches', 'academics', 'monthYears'));
		//$this->render('../EsePracticals/moderate');
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
	}
	
}