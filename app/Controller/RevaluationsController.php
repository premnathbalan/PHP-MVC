<?php
App::uses('AppController', 'Controller');
App::import('Vendor', 'PHPExcel', array('file' => 'PHPExcel'.DS.'IOFactory.php'));
App::import('Vendor', 'mPDF', array('file' => 'mPDF'.DS.'mPDF.php'));
App::import('Helper', 'AppHelper');

/**
 * Revaluations Controller
 *
 * @property Revaluation $Revaluation
 * @property PaginatorComponent $Paginator
 * @property FlashComponent $Flash
 * @property SessionComponent $Session
 */
class RevaluationsController extends AppController {
	public $uses = array("Revaluation", "MonthYear", "StudentMark", "Student", "EndSemesterExam", "CourseMapping","Course",
	"Batch", "Program");

/**
 * Components
 *
 * @var array
 */
	public $components = array('Paginator', 'Flash', 'Session','mPDF');

/**
 * index method
 *
 * @return void
 */
	public function index() {
		$this->Revaluation->recursive = 0;
		$this->set('revaluations', $this->Paginator->paginate());
	}

/**
 * view method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function view($id = null) {
		if (!$this->Revaluation->exists($id)) {
			throw new NotFoundException(__('Invalid revaluation'));
		}
		$options = array('conditions' => array('Revaluation.' . $this->Revaluation->primaryKey => $id));
		$this->set('revaluation', $this->Revaluation->find('first', $options));
	}

/**
 * add method
 *
 * @return void
 */
	public function add() {
		if ($this->request->is('post')) {
			$this->Revaluation->create();
			if ($this->Revaluation->save($this->request->data)) {
				$this->Flash->success(__('The revaluation has been saved.'));
				return $this->redirect(array('action' => 'index'));
			} else {
				$this->Flash->error(__('The revaluation could not be saved. Please, try again.'));
			}
		}
		$courseMappings = $this->Revaluation->CourseMapping->find('list');
		$students = $this->Revaluation->Student->find('list');
		$monthYears = $this->Revaluation->MonthYear->find('list');
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
		if (!$this->Revaluation->exists($id)) {
			throw new NotFoundException(__('Invalid revaluation'));
		}
		if ($this->request->is(array('post', 'put'))) {
			if ($this->Revaluation->save($this->request->data)) {
				$this->Flash->success(__('The revaluation has been saved.'));
				return $this->redirect(array('action' => 'index'));
			} else {
				$this->Flash->error(__('The revaluation could not be saved. Please, try again.'));
			}
		} else {
			$options = array('conditions' => array('Revaluation.' . $this->Revaluation->primaryKey => $id));
			$this->request->data = $this->Revaluation->find('first', $options);
		}
		$courseMappings = $this->Revaluation->CourseMapping->find('list');
		$students = $this->Revaluation->Student->find('list');
		$monthYears = $this->Revaluation->MonthYear->find('list');
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
		$this->Revaluation->id = $id;
		if (!$this->Revaluation->exists()) {
			throw new NotFoundException(__('Invalid revaluation'));
		}
		$this->request->allowMethod('post', 'delete');
		if ($this->Revaluation->delete()) {
			$this->Flash->success(__('The revaluation has been deleted.'));
		} else {
			$this->Flash->error(__('The revaluation could not be deleted. Please, try again.'));
		}
		return $this->redirect(array('action' => 'index'));
	}
	
	public function revaluation() {
		$access_result = $this->checkPathAccess($this->Auth->user('id'));
		if (!$access_result) {
		/* $monthYears = $this->MonthYear->find('all', array(
				'fields' => array('MonthYear.id','MonthYear.year','MonthYear.month_id','Month.month_name'),
				'order' => array('MonthYear.id DESC'),
				'recursive'=>0
		));
		$monthyears=array();
		foreach ($monthYears as $key => $value) {
			$monthyears[$value['MonthYear']['id']]=$value['Month']['month_name']."-".$value['MonthYear']['year'];
		} */
		$monthyears = $this->MonthYear->getAllMonthYears();
		$this->set(compact('monthyears'));
	
		if($this->request->is('post')) {
			//pr($this->data);
				
			$cmArray = $this->request->data['Revaluation']['CourseMapping'];
			$previousStatus = $this->request->data['Revaluation']['Status'];
			$revalArray = $this->request->data['Revaluation']['Revaluation'];
			$student_id = $this->request->data['Revaluation']['StudentId'];
			foreach ($revalArray as $cm_id => $revalBoolean) {
				if($revalBoolean == 1) {
					$eseResult = $this->EndSemesterExam->find('first', array(
							'conditions' => array(
									'EndSemesterExam.course_mapping_id' => $cm_id,
									'EndSemesterExam.month_year_id' => $this->request->data['Revaluation']['month_year_id'],
									'EndSemesterExam.student_id' => $this->request->data['Revaluation']['StudentId'],
							),
							'fields' => array(
									'EndSemesterExam.id', 'EndSemesterExam.marks', 'EndSemesterExam.dummy_number', 'EndSemesterExam.dummy_number_id',
							)
					));
					//pr($eseResult);
						
					$eseId = $eseResult['EndSemesterExam']['id'];
					$ese_marks = $eseResult['EndSemesterExam']['marks'];
					$dummy_number = $eseResult['EndSemesterExam']['dummy_number'];
					$dummy_number_id = $eseResult['EndSemesterExam']['dummy_number_id'];
						
					$courseDetails = $this->CourseMapping->find('first', array(
							'conditions' => array('CourseMapping.id'=>$cm_id),
							'fields' => array('CourseMapping.id'),
							'contain' => array(
									'Course' => array('fields'=>array('Course.id', 'Course.board', 'Course.course_code'),
									)
							)
					));
					//pr($courseDetails);
						
					$course_code = $courseDetails['Course']['course_code'];
					$board = $courseDetails['Course']['board'];
						
					$smResult = $this->StudentMark->find('first', array(
							'conditions' => array(
									'StudentMark.course_mapping_id' => $cm_id,
									'StudentMark.month_year_id' => $this->request->data['Revaluation']['month_year_id'],
									'StudentMark.student_id' => $this->request->data['Revaluation']['StudentId'],
							),
							'fields' => array(
									'StudentMark.id', 'StudentMark.marks'
							),
							'contain' => array()
					));
					//pr($smResult);
					$smId = $smResult['StudentMark']['id'];
					$student_marks = $smResult['StudentMark']['marks'];
						
					$revResult = $this->Revaluation->find('first', array(
							'conditions' => array('Revaluation.course_mapping_id' => $cm_id,
									'Revaluation.month_year_id' => $this->request->data['Revaluation']['month_year_id'],
									'Revaluation.student_id' => $this->request->data['Revaluation']['StudentId'],
									'Revaluation.ese_id' => $eseId
							),
							'fields' => array(
									'Revaluation.id'
							)
					));
					//pr($revResult);
						
					if(isset($revResult) && count($revResult)>0 && !empty($revResult)) {
						$revaluationId = $revResult['Revaluation']['id'];
					}
					else {
						$revaluationId = 0;
					}
						
					$data = array();
					$data['Revaluation']['course_mapping_id']=$cm_id;
					$data['Revaluation']['month_year_id']= $this->request->data['Revaluation']['month_year_id'];
					$data['Revaluation']['student_id']=$this->request->data['Revaluation']['StudentId'];
					$data['Revaluation']['board']=$board;
					$data['Revaluation']['course_code']=$course_code;
					$data['Revaluation']['dummy_number']=$dummy_number;
					$data['Revaluation']['ese_id']=$eseId;
					$data['Revaluation']['ese_marks']=$ese_marks;
					$data['Revaluation']['student_mark_id']=$smId;
					$data['Revaluation']['student_marks']=$student_marks;
					$data['Revaluation']['previous_status']=$previousStatus[$cm_id];
					$data['Revaluation']['indicator']=0;
						
					if ($revaluationId > 0) {
						$data['Revaluation']['id']=$revaluationId;
						$data['Revaluation']['modified']= date("Y-m-d H:i:s");
						$data['Revaluation']['modified_by'] = $this->Auth->user('id');
						$this->Revaluation->save($data);
					}
					else {
						$data['Revaluation']['created_by']=$this->Auth->user('id');
						$this->Revaluation->create();
						$this->Revaluation->save($data);
					}
					$data = array();
					$data['EndSemesterExam']['id']=$eseId;
					$data['EndSemesterExam']['revaluation_status']= 1;
					$this->EndSemesterExam->save($data);
	
					$data=array();
					$data['StudentMark']['id']=$smId;
					$data['StudentMark']['revaluation_status']= 1;
					$this->StudentMark->save($data);
				}
				else {
					//echo $cm_id;
					$eseResult = $this->EndSemesterExam->find('first', array(
							'conditions' => array(
									'EndSemesterExam.course_mapping_id' => $cm_id,
									'EndSemesterExam.month_year_id' => $this->request->data['Revaluation']['month_year_id'],
									'EndSemesterExam.student_id' => $this->request->data['Revaluation']['StudentId'],
							),
							'fields' => array(
									'EndSemesterExam.id', 'EndSemesterExam.marks', 'EndSemesterExam.revaluation_status'
							)
					));
					//pr($smResult);
					$eseId = $eseResult['EndSemesterExam']['id'];
					$ese_marks = $eseResult['EndSemesterExam']['marks'];
					$revaluation_status = $eseResult['EndSemesterExam']['revaluation_status'];
						
					$smResult = $this->StudentMark->find('first', array(
							'conditions' => array(
									'StudentMark.course_mapping_id' => $cm_id,
									'StudentMark.month_year_id' => $this->request->data['Revaluation']['month_year_id'],
									'StudentMark.student_id' => $this->request->data['Revaluation']['StudentId'],
							),
							'fields' => array(
									'StudentMark.id', 'StudentMark.marks'
							)
					));
					//pr($smResult);
					$smId = $smResult['StudentMark']['id'];
					$student_marks = $smResult['StudentMark']['marks'];
						
					if ($revaluation_status == 1) {
						$data = array();
						$data['EndSemesterExam']['id']=$eseId;
						$data['EndSemesterExam']['revaluation_status']=0;
						$this->EndSemesterExam->save($data);
	
						$data = array();
						$data['StudentMark']['id']=$smId;
						$data['StudentMark']['revaluation_status']=0;
						$this->StudentMark->save($data);
	
						$this->Revaluation->query("UPDATE revaluations set indicator=1,
								modified_by = ".$this->Auth->User('id').", modified='".date("Y-m-d H:i:s")."'
								where ese_id=$eseId");
	
					}
				}
			}
		}
		}
		else {
			$this->render('../Users/access_denied');
		}
	}
	
	public function upload() {
		$access_result = $this->checkPathAccess($this->Auth->user('id'));
		if (!$access_result) {
		/* $monthYears = $this->MonthYear->find('all', array(
				'fields' => array('MonthYear.id','MonthYear.year','MonthYear.month_id','Month.month_name'),
				'order' => array('MonthYear.id DESC'),
				'recursive'=>0
		));
		$monthyears=array();
		foreach ($monthYears as $key => $value) {
			$monthyears[$value['MonthYear']['id']]=$value['Month']['month_name']."-".$value['MonthYear']['year'];
		} */
		$monthyears = $this->MonthYear->getAllMonthYears();
		$this->set(compact('monthyears'));
		if($this->request->is('post')) {
			//pr($this->data); 
			$notProcessed = "";
			if(!empty($this->request->data['Revaluation']['revaluation']['name'])) {
				move_uploaded_file($this->request->data['Revaluation']['revaluation']['tmp_name'], WWW_ROOT . '../sets_upload_received_file/' .$this->request->data['Revaluation']['revaluation']['name']);
				$filename = WWW_ROOT . '../sets_upload_received_file/'.$this->request->data['Revaluation']['revaluation']['name'];
			}
			$exam_month_year_id = $this->request->data['Revaluation']['month_year_id'];
				
			$objPHPExcel = PHPExcel_IOFactory::load(WWW_ROOT . '../sets_upload_received_file/'.$this->request->data['Revaluation']['revaluation']['name']);
			$worksheet = $objPHPExcel->setActiveSheetIndex(0);
			$worksheetTitle     = $worksheet->getTitle();
			$highestRow         = $worksheet->getHighestRow(); // e.g. 10
			$highestColumn      = $worksheet->getHighestDataColumn(); // e.g 'F'
			$highestColumnIndex = PHPExcel_Cell::columnIndexFromString($highestColumn);
			$nrColumns = ord($highestColumn) - 64;
				
			//echo $highestRow." ".$highestColumn." ".$highestColumnIndex." ".$nrColumns;
			
			$reg_num_column = 1;
			$course_code_column = 2;
			$column = 2;
			$count=1;
			for ($i=2; $i<=$highestRow; $i++) {
				//echo $reg_num_column." ".$i;
				$cell = $worksheet->getCellByColumnAndRow($reg_num_column, $i);
				$reg_number = $cell->getValue();
				$cell = $worksheet->getCellByColumnAndRow($course_code_column, $i);
				$course_code = $cell->getValue();
				echo "</br>".$i." Reg Number : ".$reg_number." Course Code : ".$course_code; die;
				//Get Batch, Academic & Program from register number
	
				$studentDetails = $this->Student->find('first', array(
						'conditions'=>array('Student.registration_number'=>$reg_number, 'Student.discontinued_status'=>0),
						'fields' => array('Student.id', 'Student.batch_id', 'Student.academic_id', 'Student.program_id'),
						'contain' => array(
								'CourseStudentMapping' => array('fields'=>array('CourseStudentMapping.course_mapping_id')),
								'EndSemesterExam' => array('fields'=>array('EndSemesterExam.id', 'EndSemesterExam.marks', 'EndSemesterExam.course_mapping_id', 'EndSemesterExam.dummy_number_id', 'EndSemesterExam.dummy_number'),
									'conditions'=>array('EndSemesterExam.month_year_id'=>$exam_month_year_id),				
								),
								'StudentMark' => array('fields'=>array('StudentMark.id', 'StudentMark.marks',
									'StudentMark.course_mapping_id', 'StudentMark.status'),
									'conditions'=>array('StudentMark.month_year_id'=>$exam_month_year_id),
								)
						)
				));
				//pr($studentDetails);
				
				if (isset($studentDetails['Student']) && $studentDetails['Student']['id'] > 0) {
					
					$student_id = $studentDetails['Student']['id'];
					$batch_id = $studentDetails['Student']['batch_id'];
					$program_id = $studentDetails['Student']['program_id'];
					//echo "Batch : ".$batch_id." Program : ".$program_id." CourseCode : ".$course_code." Student ID : ".$student_id;
		
					$courseDetails = $this->Course->find('first', array(
							'conditions' => array('Course.course_code'=>$course_code),
							'fields' => array('Course.id', 'Course.board'),
							'contain' => array(
									'CourseMapping' => array('fields'=>array('CourseMapping.id'),
											'conditions'=>array('CourseMapping.batch_id'=>$batch_id,
													'CourseMapping.program_id'=>$program_id
											)
									)
							)
					));
					//pr($courseDetails);
					
					$cm_id = $courseDetails['CourseMapping'][0]['id'];
					$board = $courseDetails['Course']['board'];
					//echo " CM Id : ".$cm_id;
					$csmArray = $studentDetails['CourseStudentMapping'];
					$eseArray = $studentDetails['EndSemesterExam'];
					$smArray = $studentDetails['StudentMark'];
					//pr($csmArray);
		
					foreach ($csmArray as $key => $value) {
						if ($value['course_mapping_id'] == $cm_id) {
							$csmAvailability = true;
							foreach ($eseArray as $esekey => $eseValue) {
								if ($eseValue['course_mapping_id'] == $cm_id) {
									$ese_id = $eseValue['id'];
									$ese_marks = $eseValue['marks'];
									$dummy_number = $eseValue['dummy_number'];
									$dummy_number_id = $eseValue['dummy_number_id'];
									break;
								}
							}
							foreach ($smArray as $smkey => $smValue) {
								if ($smValue['course_mapping_id'] == $cm_id) {
									$sm_id = $smValue['id'];
									$sm_marks = $smValue['marks'];
									$previous_status = $smValue['status'];
									break;
								}
							}
							break;
						}
					}
					if (!$csmAvailability) {
						//echo "Not Available";
					}
					else {
						//echo " found ".$count." ";
						//echo "Available";
						//echo " EseID :".$ese_id." ESE MArk : ".$ese_marks." SmID : ".$sm_id." Sm MArks : ".$sm_marks." Sm Status : ".$previous_status."</br>";
						$revResult = $this->Revaluation->find('first', array(
								'conditions' => array('Revaluation.course_mapping_id' => $cm_id,
										'Revaluation.month_year_id' => $this->request->data['Revaluation']['month_year_id'],
										'Revaluation.student_id' => $student_id,
										//'Revaluation.ese_id' => $ese_id
								),
								'fields' => array(
										'Revaluation.id'
								)
						));
						//pr($revResult);
		
						if(isset($revResult) && count($revResult)>0 && !empty($revResult)) {
							$revaluationId = $revResult['Revaluation']['id'];
						}
						else {
							$revaluationId = 0;
						}
						$data = array();
						$data['Revaluation']['course_mapping_id']=$cm_id;
						$data['Revaluation']['month_year_id']= $exam_month_year_id;
						$data['Revaluation']['student_id']=$student_id;
						$data['Revaluation']['board']=$board;
						$data['Revaluation']['course_code']=$course_code;
						$data['Revaluation']['dummy_number']=$dummy_number;
						$data['Revaluation']['ese_id']=$ese_id;
						$data['Revaluation']['ese_marks']=$ese_marks;
						$data['Revaluation']['student_mark_id']=$sm_id;
						$data['Revaluation']['student_marks']=$sm_marks;
						$data['Revaluation']['previous_status']=$previous_status;
						$data['Revaluation']['indicator']=0;
		
						if ($revaluationId > 0) {
							$data['Revaluation']['id']=$revaluationId;
							$data['Revaluation']['modified']= date("Y-m-d H:i:s");
							$data['Revaluation']['modified_by'] = $this->Auth->user('id');
							$this->Revaluation->save($data);
						}
						else {
							$data['Revaluation']['created_by']=$this->Auth->user('id');
							$this->Revaluation->create();
							$this->Revaluation->save($data);
						}
						$data = array();
						$data['EndSemesterExam']['id']=$ese_id;
						$data['EndSemesterExam']['revaluation_status']= 1;
						$this->EndSemesterExam->save($data);
						//echo "</br>ESE ID : ".$ese_id;
							
						$data=array();
						$data['StudentMark']['id']=$sm_id;
						$data['StudentMark']['revaluation_status']= 1;
						$this->StudentMark->save($data);
						
					}
					$count++;
				}
				else {
					$notProcessed.=$reg_number.",";
				}
			}
			if ($notProcessed=="") $this->Flash->success(__('Successfully Completed.'));
			else $this->Flash->success(__('Successfully Completed except for : '.$notProcessed));
		}
		}
		else {
			$this->render('../Users/access_denied');
		}
	}
	
	public function revaluationSearch($month_year_id=NULL, $reg_number) {
		$monthYears = $this->MonthYear->find('all', array('fields' => array('MonthYear.id','MonthYear.year','MonthYear.month_id','Month.month_name')));
		$monthyears=array();
		foreach ($monthYears as $key => $value) {
			$monthyears[$value['MonthYear']['id']]=$value['Month']['month_name']."-".$value['MonthYear']['year'];
		}
		$this->set(compact('monthyears'));
		
		$filterCondition = "";
		//echo $batch_id." ".$program_id." ".$month_year_id;
		if ($month_year_id > 0) {
			$filterCondition= "`(EndSemesterExam`.`month_year_id` = ".$month_year_id.")";
		} else {
			$filterCondition= "`(EndSemesterExam`.`month_year_id` > 0)";
		}
		
		$results = $this->Student->find('all', array(
			'conditions' => array('Student.registration_number' => $reg_number,
			),
			'fields' => array('Student.name'),
			'contain' => array(
					'EndSemesterExam'=>array(
						'fields' => array('EndSemesterExam.month_year_id', 'EndSemesterExam.course_mapping_id',
								'EndSemesterExam.marks', 'EndSemesterExam.revaluation_status'),
						'conditions' => array($filterCondition),
							'CourseMapping'=>array('fields'=>array('CourseMapping.id'),
									'conditions'=>array('CourseMapping.indicator'=>0,
									),
									'fields'=>array('CourseMapping.id'),
									'Course' => array(
											'fields' => array('Course.id','Course.course_code',
											'Course.course_name','Course.course_max_marks'),
											'CourseType' => array('fields' => array('CourseType.course_type'))
									),
							),
							'Revaluation'=>array(
									'fields' => array('Revaluation.id', 'Revaluation.course_mapping_id',
											'Revaluation.student_marks', 'Revaluation.moderation_marks',
											'Revaluation.marks', 'Revaluation.final_marks', 'Revaluation.indicator'
									)
							)
					),
					'Batch'=>array('fields' =>array('Batch.batch_from','Batch.batch_to','Batch.academic')),
					'Program'=>array('fields' =>array('Program.program_name')),
					'Academic'=>array('fields' =>array('Academic.academic_name')),
					'StudentMark'=>array('fields' =>array('StudentMark.status', 'StudentMark.course_mapping_id', 'StudentMark.marks'))
			),
		));
		//pr($results);
		$this->set(compact('results'));
		$this->layout=false;
	}
	
	public function revaluationsDummyNoReport(){
		$access_result = $this->checkPathAccess($this->Auth->user('id'));
		if (!$access_result) {
		$results = "";
		$this->set(compact('results'));
		if ($this->request->is('post')) {
			$results = $this->Revaluation->find('all', array( 
				'conditions' => array('Revaluation.month_year_id' => $this->request->data['Revaluation']['month_year_id'],
				),
				'fields' => array('Revaluation.board'),
				'contain' => array(
					'CourseMapping'=>array('fields'=>array('CourseMapping.id'),
						'conditions'=>array('CourseMapping.indicator'=>0,
						),
						'Course' => array(
							'fields' => array('Course.id','Course.course_code','Course.course_name','Course.board'),							
						),						
					),
					'EndSemesterExam' => array(
							'fields' => array('EndSemesterExam.id','EndSemesterExam.dummy_number','EndSemesterExam.student_id'),	
							'conditions' => array('EndSemesterExam.month_year_id' => $this->request->data['Revaluation']['month_year_id']),
							'order'=>'EndSemesterExam.dummy_number ASC'
					),
				),
				'order'=>'Revaluation.board ASC','Revaluation.course_code ASC','Revaluation.dummy_number ASC'	
			));
			$this->set(compact('results'));
			if($this->request->data['Submit'] == 'PDF') {				
				$html = "";
			if($results){
				$geMonthYear = $this->request->data['Revaluation']['month_year_id'];
				$examMonthYear = $this->MonthYear->getMonthYear($geMonthYear);
				$head = "<table class='cmainhead2' border='0' align='center'>
							<tr>
							<td rowspan='2'><img src='../webroot/img/user.jpg'></td>
								<td align='center'>SATHYABAMA UNIVERSITY<br/>
								<span class='slogan'>REVALUATION DUMMY NUMBER REPORT (".$examMonthYear.")</span></td>
							</tr>
							</table>";
				$html .= $head;
				$html .= "<table class='attendanceHeadTblP' cellpadding='0' cellspacing='0' border='1' style='font-family:'Open sans' !important;font-size:13px !important;text-indent:3px;'>
							<tr>
								<th>S.No.</th>
								<th>SUBJECT CODE</th>
								<th>COURSE NAME</th>
								<th>DUMMY NO.</th>
								<th>BOARD</th>
								<th>SIGNATURE</th>
							</tr>";
					$i=1;$CCSeq = 1;$seqno = 1;
				for($p=0;$p<count($results);$p++){
					$html .= "<tr>";
							$html .="<td style='height:27px;' align='center'>".$seqno."</td>";
							$html .="<td align='center'>";
							$html .= $results[$p]['CourseMapping']['Course']['course_code'];
							$html .="</td>";  
							$html .="<td>";
							$html .= $results[$p]['CourseMapping']['Course']['course_name'];
							$html .="</td>";
							$html .="<td align='center'>";
							$html .= $results[$p]['EndSemesterExam']['dummy_number'];
							$html .="</td>";
							$html .="<td align='center'>";
							$html .= $results[$p]['CourseMapping']['Course']['board'];
							$html .="</td>";
							$html .="<td style='width:180px;'></td>";
					$html .="</tr>";
					$i++;$seqno++;
					if((isset($results[$p+1]['CourseMapping']['Course']['course_code'])) && ($results[$p]['CourseMapping']['Course']['course_code'] == $results[$p+1]['CourseMapping']['Course']['course_code'])){
						$CCSeq++;
					}else{
						$CCSeq = 1;
					}
					if((isset($results[$p+1]['Revaluation']['board']) && ($results[$p]['Revaluation']['board'] != $results[$p+1]['Revaluation']['board']))){
							$seqno = 1;
							$html .= "</table><div style='page-break-after:always'></div>";
							$html .= $head;
							$html .= "<table class='attendanceHeadTblP' cellpadding='0' cellspacing='0' border='1' style='font-family:'Open sans' !important;font-size:13px !important;text-indent:3px;'>
										<tr>
											<th>S.No.</th>
											<th>SUBJECT CODE</th>
											<th>COURSE NAME</th>
											<th>DUMMY NO.</th>
											<th>BOARD</th>
											<th>SIGNATURE</th>
										</tr>";
							$i=1;
					}
				}
				$html .= "</table>";
				}				
				
				$this->mPDF->init();
				// setting filename of output pdf file
				$this->mPDF->setFilename('REVAL_DUMMY_NUM_REPORT_'.date('d_M_Y').'.pdf');
				// setting output to I, D, F, S
				$this->mPDF->setOutput('D');
				
				$this->mPDF->WriteHTML($html);
				// you can call any mPDF method via component, for example:
				$this->mPDF->SetWatermarkText("Draft");
				
			}
		}		
		/* $monthYears = $this->MonthYear->find('all', array(
				'fields' => array('MonthYear.id','MonthYear.year','MonthYear.month_id','Month.month_name'),
				'order' => array('MonthYear.id DESC'),
				'recursive'=>0
		));
		$monthyears=array();
		foreach ($monthYears as $key => $value) {
			$monthyears[$value['MonthYear']['id']]=$value['Month']['month_name']."-".$value['MonthYear']['year'];
		} */
		$monthyears = $this->MonthYear->getAllMonthYears();
		$this->set(compact('monthyears'));
		}
		else {
			$this->render('../Users/access_denied');
		}
	}
	
	public function revaluationsExaminerList(){
		$access_result = $this->checkPathAccess($this->Auth->user('id'));
		if (!$access_result) {
		$results = "";
		$this->set(compact('results'));
		if ($this->request->is('post')) {
			$results = $this->Revaluation->find('all', array(
				'conditions' => array('Revaluation.month_year_id' => $this->request->data['Revaluation']['month_year_id'],
				),
				'fields' => array('Revaluation.board'),
				'contain' => array(
					'CourseMapping'=>array('fields'=>array('CourseMapping.id'),
						'conditions'=>array('CourseMapping.indicator'=>0,
						),
						'Course' => array(
							'fields' => array('Course.id','Course.course_code','Course.course_name','Course.board'),							
						),						
					),
					'EndSemesterExam' => array(
							'fields' => array('EndSemesterExam.id','EndSemesterExam.dummy_number'),
							'order'=>'EndSemesterExam.dummy_number ASC'
					),
				),
				'order'=>array('Revaluation.board'=>'ASC','Revaluation.course_code'=> 'ASC','Revaluation.dummy_number'=> 'ASC')
			));
			$this->set(compact('results'));
			if($this->request->data['Submit'] == 'PDF') {				
				$html = "";
			if($results){
				$geMonthYear = $this->request->data['Revaluation']['month_year_id'];
				$examMonthYear = $this->MonthYear->getMonthYear($geMonthYear);
				$head = "<table class='cmainhead2' border='0' align='center'>
							<tr>
							<td rowspan='2'><img src='../webroot/img/user.jpg'></td>
								<td align='center'>SATHYABAMA UNIVERSITY<br/>
								<span class='slogan'>REVALUATION EXAMINER LIST (".$examMonthYear.")</span></td>
							</tr>
							</table>";
				$html .= $head;
				$html .= "<table class='attendanceHeadTblP' cellpadding='0' cellspacing='0' border='1' style='font-family:'Open sans' !important;font-size:12px !important;text-indent:3px;'>
							<tr>
								<th>S.No.</th>
								<th>SUBJECT CODE</th>
								<th>COURSE NAME</th>
								<th>DUMMY NO. START</th>
								<th>DUMMY NO. END</th>
								<th>BOARD</th>
								<th>TOTAL</th>
								<th>SIGNATURE</th>
							</tr>";
				$serialNo = 1;$packetCnt = 20;$i=1;$CCSeq = 1;$startDummyNumber = "";for($p=0;$p<count($results);$p++){	
			$dummyNo4Digit = substr($results[$p]['EndSemesterExam']['dummy_number'],0,4);
			if(empty($startDummyNumber)){
				$totalCnt = 0;
				for($z=$p;$z<count($results);$z++){ 
					if(@($results[$p]['CourseMapping']['Course']['course_code'] == $results[$z]['CourseMapping']['Course']['course_code']) && ($dummyNo4Digit == substr($results[$z]['EndSemesterExam']['dummy_number'],0,4))){
						$totalCnt++;						
					}				
				}
			}			
			
			if(empty($startDummyNumber)){
				$startDummyNumber = $results[$p]['EndSemesterExam']['dummy_number'];
			}
			$endDummyNumber = $results[$p]['EndSemesterExam']['dummy_number'];
			$endDNFlag = 1;
			
			for($z=$p;$z<count($results);$z++){ 
				if((isset($results[$z+1]['CourseMapping']['Course']['course_code'])) && ($results[$z]['CourseMapping']['Course']['course_code'] == $results[$z+1]['CourseMapping']['Course']['course_code']) && ($endDNFlag == 1) && ($dummyNo4Digit == substr($results[$z+1]['EndSemesterExam']['dummy_number'],0,4))){
					if(($CCSeq < $packetCnt )){ 
						$endDNFlag = 2; 
						$endDummyNumber = "";
					}else if(($CCSeq % $packetCnt)){
						$endDummyNumber = "";$results[$z+1]['EndSemesterExam']['dummy_number'];
					}else{
						$endDNFlag = 2; 
						$endDummyNumber = $results[$z]['EndSemesterExam']['dummy_number'];
					}					
				}else{
					$endDNFlag = 2;
				}
				
			} if($endDummyNumber){
					$html .= "<tr>";
							$html .="<td style='height:27px;' align='center'>".$serialNo."</td>";
							$html .="<td align='center'>";
							$html .= $results[$p]['CourseMapping']['Course']['course_code'];
							$html .="</td>";  
							$html .="<td>";
							$html .= $results[$p]['CourseMapping']['Course']['course_name'];
							$html .="</td>";
							$html .="<td align='center'>";
							$html .= $startDummyNumber;
							$html .="</td>";
							$html .="<td align='center'>";
							$html .= $endDummyNumber;
							$html .="</td>";
							$html .="<td align='center'>";
							$html .= $results[$p]['CourseMapping']['Course']['board'];
							$html .="</td>";
							$html .="<td align='center'>";
								if($packetCnt < $totalCnt){  
									$html .= $packetCnt;
								}else{  
									$html .= $totalCnt;
								}						
							$html .="</td>";
							$html .="<td style='width:180px;'></td>";
					$html .="</tr>";
					$serialNo++;$totalCnt=0;$startDummyNumber = "";
			}
			$i++;
		if((isset($results[$p+1]['CourseMapping']['Course']['course_code'])) && ($results[$p]['CourseMapping']['Course']['course_code'] == $results[$p+1]['CourseMapping']['Course']['course_code']) && ($dummyNo4Digit == substr($results[$p+1]['EndSemesterExam']['dummy_number'],0,4))){
			$CCSeq++;
		}else{
			$CCSeq = 1;
		}
		
		if((isset($results[$p+1]['CourseMapping']['Course']['course_code']) && ($results[$p]['CourseMapping']['Course']['course_code'] != $results[$p+1]['CourseMapping']['Course']['course_code']))){
		
		if((isset($results[$p+1]['Revaluation']['board']) && ($results[$p]['Revaluation']['board'] != $results[$p+1]['Revaluation']['board']))){
						$html .= "</table><div style='page-break-after:always'></div>";
						$html .= $head;
						$html .= "<table class='attendanceHeadTblP' cellpadding='0' cellspacing='0' border='1' style='font-family:'Open sans' !important;font-size:12px !important;text-indent:3px;'>
									<tr>
										<th>S.No.</th>
										<th>SUBJECT CODE</th>
										<th>COURSE NAME</th>
										<th>DUMMY NO. START</th>
										<th>DUMMY NO. END</th>
										<th>BOARD</th>
										<th>TOTAL</th>
										<th>SIGNATURE</th>
									</tr>";
		}
						$i=1;
					}
				}
				$html .= "</table>";
				}				
				
				$this->mPDF->init();
				// setting filename of output pdf file
				$this->mPDF->setFilename('REVAL_EXAMINER_LIST_'.date('d_M_Y').'.pdf');
				// setting output to I, D, F, S
				$this->mPDF->setOutput('D');
				// L - landscape, '','','','',P - portrait, marginleft, margin right, margin top, margin bottom, margin header, margin footer
				$this->mPDF->AddPage('L','', '', '', '',30,30,30,30,18,12);
				$this->mPDF->WriteHTML($html);
				// you can call any mPDF method via component, for example:
				$this->mPDF->SetWatermarkText("Draft");
				
			}
		}		
		/* $monthYears = $this->MonthYear->find('all', array(
				'fields' => array('MonthYear.id','MonthYear.year','MonthYear.month_id','Month.month_name'),
				'order' => array('MonthYear.id DESC'),
				'recursive'=>0
		));
		$monthyears=array();
		foreach ($monthYears as $key => $value) {
			$monthyears[$value['MonthYear']['id']]=$value['Month']['month_name']."-".$value['MonthYear']['year'];
		} */
		$monthyears = $this->MonthYear->getAllMonthYears();
		$this->set(compact('monthyears'));
		}
		else {
			$this->render('../Users/access_denied');
		}
	}	
	
	public function revaluationsStrengthReport(){
		$access_result = $this->checkPathAccess($this->Auth->user('id'));
		if (!$access_result) {
		$results = "";
		$this->set(compact('results'));
		if ($this->request->is('post')) {
			$results = $this->Revaluation->find('all', array(
					'conditions' => array('Revaluation.month_year_id' => $this->request->data['Revaluation']['month_year_id'],
					),
					'fields' => array('count(Revaluation.board) as cntboard','Revaluation.board'),
					'contain' => array(
							'CourseMapping'=>array('fields'=>array('CourseMapping.id'),
									'conditions'=>array('CourseMapping.indicator'=>0,
									),
									'Course' => array(
											'fields' => array('Course.id','Course.course_code','Course.course_name','Course.board'),
									),
							),
							'EndSemesterExam' => array(
									'fields' => array('EndSemesterExam.id','EndSemesterExam.dummy_number'),
									'order'=>'EndSemesterExam.dummy_number ASC'
							),
					),
					'group'=>'Revaluation.board',
					'order'=>array('Revaluation.board'=>'ASC','Revaluation.course_code'=> 'ASC','Revaluation.dummy_number'=> 'ASC')
			));
			$this->set(compact('results'));
			if($this->request->data['Submit'] == 'PDF') {
				$html = "";
				if($results){
					$head = "<table class='cmainhead2' border='0' align='center'>
							<tr>
							<td rowspan='2'><img src='../webroot/img/user.jpg'></td>
								<td align='center'>SATHYABAMA UNIVERSITY<br/>
								<span class='slogan'>REVALUATION EXAMINER LIST (".$examMonthYear.")</span></td>
							</tr>
							</table>";
					$html .= $head;
					$html .= "<table class='attendanceHeadTblP' cellpadding='0' cellspacing='0' border='1' style='font-family:'Open sans' !important;font-size:12px !important;text-indent:3px;'>
							<tr>
								<th>S.No.</th>
								<th style='width:100px;'>BOARD</th>
								<th style='width:100px;'>TOTAL</th>
							</tr>";
					$i=1;
					for($p=0;$p<count($results);$p++){
						$html .= "<tr>";
						$html .="<td style='height:27px;' align='center'>".$i."</td>";
						$html .="<td align='center'>";
						$html .= $results[$p]['CourseMapping']['Course']['board'];
						$html .="</td>";
						$html .="<td align='center'>";
						$html .= $results[$p][0]['cntboard'];
						$html .="</td>";
						$html .="</tr>";
						$i++;						
					}
					$html .= "</table>";
				}
	
				$this->mPDF->init();
				// setting filename of output pdf file
				$this->mPDF->setFilename('REVAL_STRENGTH_REPORT_'.date('d_M_Y').'.pdf');
				// setting output to I, D, F, S
				$this->mPDF->setOutput('D');
	
				$this->mPDF->WriteHTML($html);
				// you can call any mPDF method via component, for example:
				$this->mPDF->SetWatermarkText("Draft");
	
			}
		}
		/* $monthYears = $this->MonthYear->find('all', array('fields' => array('MonthYear.id','MonthYear.year','MonthYear.month_id','Month.month_name'),'order' => array('MonthYear.id DESC')));
		$monthyears=array();
		foreach ($monthYears as $key => $value) {
			$monthyears[$value['MonthYear']['id']]=$value['Month']['month_name']."-".$value['MonthYear']['year'];
		} */
		$monthyears = $this->MonthYear->getAllMonthYears();
		$this->set(compact('monthyears'));
		}
		else {
			$this->render('../Users/access_denied');
		}
	}
	
	public function revaluationsCoverPageReport(){
		$access_result = $this->checkPathAccess($this->Auth->user('id'));
		if (!$access_result) {
		$results = "";
		$this->set(compact('results'));
		if ($this->request->is('post')) {
			$results = $this->Revaluation->find('all', array(
				'conditions' => array('Revaluation.month_year_id' => $this->request->data['Revaluation']['month_year_id']
				),
				'fields' => array('Revaluation.id','Revaluation.board'),
				'contain' => array(
						'CourseMapping'=>array('fields'=>array('CourseMapping.id'),
								'conditions'=>array('CourseMapping.indicator'=>0,
								),
								'Course' => array(
										'fields' => array('Course.id','Course.course_code','Course.course_name','Course.board'),
								),
						),
						'EndSemesterExam' => array(
								'fields' => array('EndSemesterExam.id','EndSemesterExam.dummy_number'),
								'order'=>'EndSemesterExam.dummy_number ASC'
						),
				),
				'order'=>array('Revaluation.board'=>'ASC','Revaluation.course_code'=> 'ASC','Revaluation.dummy_number'=> 'ASC')
		));
			$this->set(compact('results'));
			if($this->request->data['Submit'] == 'PDF') {
				$html = "";
				if($results){
					$pageBreak = 0;$serialNo = 1;$packetCnt = 20;$i=1;$CCSeq = 1;$startDummyNumber = "";for($p=0;$p<count($results);$p++){	
			$dummyNo4Digit = substr($results[$p]['EndSemesterExam']['dummy_number'],0,4);
			if(empty($startDummyNumber)){
				$totalCnt = 0;
				for($z=$p;$z<count($results);$z++){ 
					if(@($results[$p]['CourseMapping']['Course']['course_code'] == $results[$z]['CourseMapping']['Course']['course_code']) && ($dummyNo4Digit == substr($results[$z]['EndSemesterExam']['dummy_number'],0,4))){
						$totalCnt++;
					}				
				}
			}		
			
			if(empty($startDummyNumber)){
				$startDummyNumber = $results[$p]['EndSemesterExam']['dummy_number'];
			}
			$endDummyNumber = $results[$p]['EndSemesterExam']['dummy_number'];
			$endDNFlag = 1;		
			
			for($z=$p;$z<count($results);$z++){ 
				if((isset($results[$z+1]['CourseMapping']['Course']['course_code'])) && ($results[$z]['CourseMapping']['Course']['course_code'] == $results[$z+1]['CourseMapping']['Course']['course_code']) && ($endDNFlag == 1) && ($dummyNo4Digit == substr($results[$z+1]['EndSemesterExam']['dummy_number'],0,4))){
					if(($CCSeq < $packetCnt )){ 
						$endDNFlag = 2; 
						$endDummyNumber = "";
					}else if(($CCSeq % $packetCnt)){
						$endDummyNumber = "";$results[$z+1]['EndSemesterExam']['dummy_number'];
					}else{
						$endDNFlag = 2; 
						$endDummyNumber = $results[$z]['EndSemesterExam']['dummy_number'];
					}					
				}else{
					$endDNFlag = 2;
				}
				
			} if($endDummyNumber){
						//table
						$html .= "<table border='0' style='width:90%;font:28px;font-weight:bold;'>";
						$html .="<tr><td colspan='2' styl='width:50%'></td><td style='height:27px;' align='center'>Board:</td><td>";
								$html .= $results[$p]['CourseMapping']['Course']['board'];
						$html .="</td></tr>";
						$html .="<tr><td style='height:45px;width:200px;'>Dummy Range:</td><td align='left'>".$startDummyNumber." - ".$endDummyNumber."</td><td colspan='2'></td></tr>";
						$html .="<tr><td style='height:45px;width:200px;'>Subject Code:</td><td align='left'>";
								$html .= $results[$p]['CourseMapping']['Course']['course_code'];
						$html .="</td><td colspan='2'></td></tr>";
						$html .="<tr><td style='height:45px;width:200px;'>Subject Name:</td><td align='left' colspan='3'>";
								$html .= $results[$p]['CourseMapping']['Course']['course_name'];
						$html .="</td></tr>";
						$html .="<tr><td style='height:50px;width:200px;'>Count:</td><td align='left'>";						
							if($totalCnt == 0){ 
								$html .= 1;
							}else if($packetCnt < $totalCnt){  
								$html .= $packetCnt;
							}else{  
								$html .= $totalCnt;
							}
						$html .="</td><td colspan='2'></td></tr>";
						$html .="<tr><td colspan='4'><hr/></td></tr>";
						$html .="</table>";
								
							$pageBreak++;$serialNo++;$startDummyNumber = "";}$i++;
							if((isset($results[$p+1]['CourseMapping']['Course']['course_code'])) && ($results[$p]['CourseMapping']['Course']['course_code'] == $results[$p+1]['CourseMapping']['Course']['course_code']) && ($dummyNo4Digit == substr($results[$p+1]['EndSemesterExam']['dummy_number'],0,4))){
								$CCSeq++;
							}else{
								$CCSeq = 1;
							}
							if(isset($results[$p+1]['CourseMapping']['Course']['course_code']) && ($results[$p]['CourseMapping']['Course']['course_code'] != $results[$p+1]['CourseMapping']['Course']['course_code']) || $i == 21){
								$endDNFlag = 1;
								$i=1;
							}
							if($pageBreak == 4){$pageBreak = 0;
							//page Break
							$html .= "<div style='page-break-after:always'></div>";
							}
						}
				}
				$this->mPDF->init();
				// setting filename of output pdf file
				$this->mPDF->setFilename('REVAL_COVER_PAGE_REPORT_'.date('d_M_Y').'.pdf');
				// setting output to I, D, F, S
				$this->mPDF->setOutput('D');
			
				$this->mPDF->WriteHTML($html);
				// you can call any mPDF method via component, for example:
				$this->mPDF->SetWatermarkText("Draft");			
			}
		}
		/* $monthYears = $this->MonthYear->find('all', array('fields' => array('MonthYear.id','MonthYear.year','MonthYear.month_id','Month.month_name'),'order' => array('MonthYear.id DESC')));
		$monthyears=array();
		foreach ($monthYears as $key => $value) {
			$monthyears[$value['MonthYear']['id']]=$value['Month']['month_name']."-".$value['MonthYear']['year'];
		} */
		$monthyears = $this->MonthYear->getAllMonthYears();
		$this->set(compact('monthyears'));
		}
		else {
			$this->render('../Users/access_denied');
		}
	}
	
	public function revaluationsFoilCard(){ 
		$access_result = $this->checkPathAccess($this->Auth->user('id'));
		if (!$access_result) {
		$results = "";
		$this->set(compact('results'));
		if ($this->request->is('post')) {
			$results = $this->Revaluation->find('all', array(
				'conditions' => array('Revaluation.month_year_id' => $this->request->data['Revaluation']['month_year_id'],
				),
				'fields' => array('Revaluation.board'),
				'contain' => array(
					'CourseMapping'=>array('fields'=>array('CourseMapping.id'),
						'conditions'=>array('CourseMapping.indicator'=>0,
						),
						'Course' => array(
							'fields' => array('Course.id','Course.course_code','Course.course_name','Course.board','Course.max_ese_qp_mark'),							
						),
						'Batch' => array(
								'fields' => array('Batch.batch_from','Batch.batch_to'),
						),
					),
					'EndSemesterExam' => array(
							'fields' => array('EndSemesterExam.id','EndSemesterExam.dummy_number'),
							'order'=>'EndSemesterExam.dummy_number ASC'
					),
				),
				'order'=>array('Revaluation.board'=>'ASC','Revaluation.course_code'=> 'ASC','Revaluation.dummy_number'=> 'ASC')
			));
			$this->set(compact('results'));
			if($this->request->data['Submit'] == 'PDF') {				
				$html = "";
			if($results){			
				
				$html .= "<table class='attendanceHeadTblP' cellpadding='0' cellspacing='0' border='1' style='font-family:'Open sans' !important;font-size:12px !important;text-indent:5px;width:600px;text-align:center;'>";

		   $serialNo = 1;$packetCnt = 20;$i=1;$CCSeq = 1;$startDummyNumber = "";for($p=0;$p<count($results);$p++){	
		   $dummyNo4Digit = substr($results[$p]['EndSemesterExam']['dummy_number'],0,4);
		   if(empty($startDummyNumber)){
				$totalCnt = 0;
				for($z=$p;$z<count($results);$z++){ 
					if(@($results[$p]['CourseMapping']['Course']['course_code'] == $results[$z]['CourseMapping']['Course']['course_code']) && ($dummyNo4Digit == substr($results[$z]['EndSemesterExam']['dummy_number'],0,4))){
						$totalCnt++;						
					}				
				}
			}			
			
			if(empty($startDummyNumber)){
				$startDummyNumber = $results[$p]['EndSemesterExam']['dummy_number'];
			}
			$endDummyNumber = $results[$p]['EndSemesterExam']['dummy_number'];
			
			$endDNFlag = 1;		
			
			for($z=$p;$z<count($results);$z++){ 
				if((isset($results[$z+1]['CourseMapping']['Course']['course_code'])) && ($results[$z]['CourseMapping']['Course']['course_code'] == $results[$z+1]['CourseMapping']['Course']['course_code']) && ($endDNFlag == 1) && ($dummyNo4Digit == substr($results[$z+1]['EndSemesterExam']['dummy_number'],0,4))){
					if(($CCSeq < $packetCnt )){ 
						$endDNFlag = 2; 
						$endDummyNumber = "";
					}else if(($CCSeq % $packetCnt)){
						$endDummyNumber = "";$results[$z+1]['EndSemesterExam']['dummy_number'];
					}else{
						$endDNFlag = 2; 
						$endDummyNumber = $results[$z]['EndSemesterExam']['dummy_number'];
					}					
				}else{
					$endDNFlag = 2;
				}
				
			} 
			if($serialNo == 1){
				$geMonthYear = $this->request->data['Revaluation']['month_year_id'];
				$examMonthYear = $this->MonthYear->getMonthYear($geMonthYear);
				$foilCardCC = $results[$p]['CourseMapping']['Course']['course_code'];
				$foilCardCN = $results[$p]['CourseMapping']['Course']['course_name'];
				$batch = $results[$p]['CourseMapping']['Batch']['batch_from']." - ".$results[$p]['CourseMapping']['Batch']['batch_to'];
				$max_ese_qp_mark = $results[$p]['CourseMapping']['Course']['max_ese_qp_mark'];
				$html .=  "<tr>
							<td align='right' style='border-right:none;'><img src='../webroot/img/user.jpg'></td>
								<td align='left' colspan='2' width='500' style='border-left:none;'><b>SATHYABAMA UNIVERSITY</b><br/>
								<span class='slogan'>REVALUATION FOIL CARD (".$examMonthYear.") - Page ".ceil($CCSeq / 20)." </span></td>
							</tr>							
								<tr>
									<td colspan='3' height='33'>Month&Year of Exam : <b>".$examMonthYear."</b> &nbsp; Max Marks. : <b>".$max_ese_qp_mark."</b></td>
								</tr>
								<tr>
									<td colspan='3' height='33'>Batch : <b>".$batch."</b></td>
								</tr>
								<tr>
									<td colspan='3' height='33'>Subject : <b>".$foilCardCN."</b></td>
								</tr>
								<tr>
									<td colspan='3' height='33'>Subject Code : <b>".$foilCardCC."</b></td>
								</tr>
							  <tr>
							   <td rowspan='2' style='width:100px;' align='center'>DUMMY <br/>NUMBER</td>
								<td colspan='2' align='center'>MARKS OBTAINED</td>
							  </tr>
							  <tr>
								<td align='center' style='width:20px;'>IN <br/>FIGURES</td>
								<td align='center'>IN WORDS</td>
							  </tr>";
				 
			}
			
			$html .= "<tr>";
					//$html .="<td style='height:27px;' align='center'>".$serialNo."</td>";
					
					$html .="<td align='center' height='30'>";
					$html .= $results[$p]['EndSemesterExam']['dummy_number'];
					$html .="</td>";
					$html .="<td align='center'>";
					$html .="</td>";
					$html .="<td style='width:180px;'></td>";
			$html .="</tr>";$i++;$serialNo++;
		if((isset($results[$p+1]['CourseMapping']['Course']['course_code'])) && ($results[$p]['CourseMapping']['Course']['course_code'] == $results[$p+1]['CourseMapping']['Course']['course_code']) && ($dummyNo4Digit == substr($results[$p+1]['EndSemesterExam']['dummy_number'],0,4))){
			$CCSeq++;
		}else{
			$CCSeq = 1;
		}
		if((isset($results[$p+1]['CourseMapping']['Course']['course_code']) && ($results[$p]['CourseMapping']['Course']['course_code'] != $results[$p+1]['CourseMapping']['Course']['course_code'])) || $i == 21 || (isset($results[$p+1]['EndSemesterExam']['dummy_number']) && ($dummyNo4Digit != substr($results[$p+1]['EndSemesterExam']['dummy_number'],0,4)))){
		$endDNFlag = 1;$totalCnt=0;$startDummyNumber = "";$serialNo =1;$i=1;
		$html .= "<tr><td height='30' align='center'>TOTAL</td><td></td><td></td></tr>";
		$html .= "<tr><td colspan='3' style='height:60px;vertical-align: text-bottom;'>Signature & Name of Examiner in Capitals</td></tr>";
		$html .= "<tr><td colspan='3' style='height:60px;font-weight:12px;' align='left'><u>Instruction to Examiners</u><br/>1. Totalling of marks is mandatory <br/>2. Mark column should not be left blank. <br/>3. Avoid omission, alteration of Register/Dummy Number/mark.</td></tr>";
			
						$html .= "</table><div style='page-break-after:always'></div>";
						$html .= "<table class='attendanceHeadTblP' cellpadding='0' cellspacing='0' border='1' style='font-family:'Open sans' !important;font-size:12px !important;text-indent:5px;width:600px;text-align:center;'>";								
						$i=1;
					}
				}
				$html .= "<tr><td height='30' align='center'>TOTAL</td><td></td><td></td></tr>";
				$html .= "<tr><td colspan='3' style='height:60px;vertical-align: text-bottom;'>Signature & Name of Examiner in Capitals</td></tr>";
				$html .= "<tr><td colspan='3' style='height:60px;font-weight:12px;' align='left'><u>Instruction to Examiners</u><br/>1. Totalling of marks is mandatory <br/>2. Mark column should not be left blank. <br/>3. Avoid omission, alteration of Register/Dummy Number/mark.</td></tr>";
				$html .= "</table>";
				}				
				
				$this->mPDF->init();
				// setting filename of output pdf file
				$this->mPDF->setFilename('REVAL_FOIL_CARD_'.date('d_M_Y').'.pdf');
				// setting output to I, D, F, S
				$this->mPDF->setOutput('D');
				$this->mPDF->WriteHTML($html);
				// you can call any mPDF method via component, for example:
				$this->mPDF->SetWatermarkText("Draft");				
			}
		}		
		/* $monthYears = $this->MonthYear->find('all', array('fields' => array('MonthYear.id','MonthYear.year','MonthYear.month_id','Month.month_name'),'order' => array('MonthYear.id DESC')));
		$monthyears=array();
		foreach ($monthYears as $key => $value) {
			$monthyears[$value['MonthYear']['id']]=$value['Month']['month_name']."-".$value['MonthYear']['year'];
		} */
		$monthyears = $this->MonthYear->getAllMonthYears();
		$this->set(compact('monthyears'));
		}
		else {
			$this->render('../Users/access_denied');
		}
	}	
	
	public function revaluation_upload_template(){
		$filename = "Revaluations_Bulk_Upload.xls";
		$filename = WWW_ROOT ."../sets_upload_file_template" . DS . $filename;
		if (file_exists($filename)) {
			header("Pragma: public");
			header("Expires: 0");
			header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
			header("Cache-Control: private", false);
			header("Content-Type: application/force-download");
			header("Content-Type: application/vnd.ms-excel");
			header("Content-type: application/x-msexcel");
			header("Content-Description: File Transfer");
			header("Content-Disposition: attachment; filename=" . basename($filename) . ";");
			header("Content-Transfer-Encoding: binary");
			header("Content-Length: " . filesize($filename));
			readfile($filename) or die("Errors");
			exit(0);
		}
	}
	
	public function report() {
		$academics = $this->Program->Academic->find('list',array('order' => array('Academic.academic_name ASC')));
		$programs = $this->Program->find('list',array('order' => array('Program.program_name ASC')));
		$batches = $this->Batch->find('list', array('fields' => array('Batch.batch_period'),'order' => array('Batch.batch_to DESC')));
		$monthyears = $this->MonthYear->getAllMonthYears();
		$this->set(compact('academics','programs','batches','monthyears'));
	}
	
	public function applied($exam_month_year_id, $batch_id, $option=null) {
		
		$passCount = 0;
		$failCount = 0;
		
		$passResults = array();
		$failResults = array();
		$rev_results = $this->Revaluation->query("SELECT r.id, r.course_mapping_id, r.previous_status, re.status, 
				r.student_marks, re.total_marks, s.batch_id 
				FROM revaluations r
				JOIN students s ON s.id = r.student_id
				JOIN revaluation_exams re ON re.revaluation_id = r.id
				WHERE r.month_year_id =$exam_month_year_id
				AND r.previous_status='Pass'");
		//pr($rev_results);
		//echo count($rev_results);
		foreach ($rev_results as $key => $tmpArray) {
			if ($tmpArray['s']['batch_id'] == $batch_id && $tmpArray['r']['previous_status'] = 'Pass') {
				$passResults[] = $tmpArray;
			}
		}
		//pr($results);
		
		$impGreater = 0;
		$impNoChange = 0;
		$impLesser = 0;
		$impFail = 0;
		
		$brPassCnt = 0;
		
		foreach ($passResults as $key => $revArray) {
			if ($revArray['r']['previous_status']=="Pass") $brPassCnt++;
			/*else if ($revArray['r']['previous_status']=="Fail") $brFailCnt++;
			
			if ($revArray['re']['status']=="Pass") $arPassCnt++;
			else if ($revArray['re']['status']=="Fail") $arFailCnt++; */
			
			if ($revArray['re']['status']=="Pass" && $revArray['r']['previous_status']=="Pass" &&
					$revArray['re']['total_marks'] > $revArray['r']['student_marks']) {
						$impGreater++;
			} else if ($revArray['re']['status']=="Pass" && $revArray['r']['previous_status']=="Pass" &&
					$revArray['re']['total_marks'] == $revArray['r']['student_marks']) {
						$impNoChange++;
			} else if ($revArray['re']['status']=="Pass" && $revArray['r']['previous_status']=="Pass" &&
					$revArray['re']['total_marks'] < $revArray['r']['student_marks']) {
						$impLesser++;
			} else if ($revArray['re']['status']=="Fail" && $revArray['r']['previous_status']=="Pass") {
				$impFail++;
			}
		}
		//echo "**".count($passResults);
		//pr($results);
		
		$rev_results = $this->Revaluation->query("SELECT r.id, r.course_mapping_id, r.previous_status, re.status,
				r.student_marks, re.total_marks, s.batch_id
				FROM revaluations r
				JOIN students s ON s.id = r.student_id
				JOIN revaluation_exams re ON re.revaluation_id = r.id
				WHERE r.month_year_id =$exam_month_year_id
				AND r.previous_status='Fail'");
		//pr($rev_results);
		//echo count($rev_results);
		foreach ($rev_results as $key => $tmpArray) {
			if ($tmpArray['s']['batch_id'] == $batch_id && $tmpArray['r']['previous_status'] = 'Fail') {
				$failResults[] = $tmpArray;
			}
		}
		
		$brFailCnt = 0;
		$passedAfterRevaluation = 0;
		$failedAfterRevaluation = 0;
		
		foreach ($failResults as $key => $revArray) {
			
			if ($revArray['r']['previous_status']=="Fail") $brFailCnt++;
				
			if ($revArray['re']['status']=="Pass") $passedAfterRevaluation++;
			else if ($revArray['re']['status']=="Fail") $failedAfterRevaluation++;
		}
		$results['brPassCnt'] = $brPassCnt;
		$results['brFailCnt'] = $brFailCnt;
		$results['total'] = $brPassCnt+$brFailCnt;
		$results['passedAfterRevaluation'] = $passedAfterRevaluation;
		$results['failedAfterRevaluation'] = $failedAfterRevaluation;
		$results['impGreater'] = $impGreater;
		$results['impNoChange'] = $impNoChange;
		$results['impLesser'] = $impLesser;
		$results['impFail'] = $impFail;
		$this->set(compact('results', 'exam_month_year_id', 'batch_id'));
		$this->layout = false;
		
		if($option=="PRINT") {
			$html="";
			$head = "<table class='cmainhead2' border='0' align='center'  style='font-family:Arial !important;font-size:16px !important;'>
							 <tr>
							 <td rowspan='2'><img src='../webroot/img/user.jpg'></td>
							 <td align='center'>SATHYABAMA UNIVERSITY<br/>
							 <span class='slogan'>REVALUATION REPORT</span></td>
							 </tr>
							 </table>";
			
				$head .= "<table class='attendanceHeadTblP' cellpadding='0' cellspacing='0' border='1' style='font-family:Arial !important;font-size:12px !important;text-indent:10px;width:100%;'>
							  <tr>
								<td style='height:30px;width:30%;' align='left'>&nbsp;Batch</td>
								<td align='left' style='width:50%;'>&nbsp;".$this->Batch->getBatch($batch_id)."</td>
								<td style='width:20%;' align='left'>&nbsp;Month Year</td>
								<td align='left' style='height:30px;width:20%;'>&nbsp;".$this->MonthYear->getMonthYear($exam_month_year_id)."</td>
							  </tr>
				            </table>";
				$html .= $head;
				$html .= "<br/><table class='attendanceHeadTblP' cellpadding='0' cellspacing='0' border='1' style='font-family:Arial !important;font-size:12px !important;text-indent:10px;width:100%;'>
							<tr>
								<td style='height:30px;width:30%;' align='left'>&nbsp;Total applied</td>
								<td align='left' style='width:50%;'>&nbsp;".$results['total']."</td>
							  </tr>
							  <tr>
								<td style='width:20%;' align='left'>&nbsp;Applied for improvement</td>
								<td align='left' style='height:30px;width:20%;'>&nbsp;".$results['brPassCnt']."</td>
							  </tr>
							  <tr>
								<td style='height:30px;' align='left'>&nbsp;Applied for Pass</td>
								<td align='left'>&nbsp;".$results['brFailCnt']."</td>
							  </tr>
				            </table>";
				
				$html .= "<br/><table class='attendanceHeadTblP' cellpadding='0' cellspacing='0' border='1' style='font-family:Arial !important;font-size:12px !important;text-indent:10px;width:100%;'>
						<tr><td colspan='2' style='height:30px;'>Applied for Improvement</td></tr>	 
							<tr>
								<td style='height:30px;width:30%;' align='left'>&nbsp;Total applied</td>
								<td align='left' style='width:50%;'>&nbsp;".$results['brPassCnt']."</td>
							  </tr>
							  <tr>
								<td style='width:20%;' align='left'>&nbsp;Improvement after Revaluation</td>
								<td align='left' style='height:30px;width:20%;'>&nbsp;".$results['impGreater']."</td>
							  </tr>
							  <tr>
								<td style='height:30px;' align='left'>&nbsp;No change in improvement</td>
								<td align='left'>&nbsp;".$results['impNoChange']."</td>
							  </tr>
							  <tr>
								<td style='width:20%;' align='left'>&nbsp;Decrement in improvement</td>
								<td align='left' style='height:30px;width:20%;'>&nbsp;".$results['impLesser']."</td>
							  </tr>
							  <tr>
								<td style='height:30px;' align='left'>&nbsp;Failed in improvement</td>
								<td align='left'>&nbsp;".$results['impFail']."</td>
							  </tr>
				            </table>";
				
				$html .= "<br/><table class='attendanceHeadTblP' cellpadding='0' cellspacing='0' border='1' style='font-family:Arial !important;font-size:12px !important;text-indent:10px;width:100%;'>
						<tr><td colspan='2' style='height:30px;align:center;'>Applied for Pass</td></tr>  
							  <tr>
								<td style='height:30px;width:30%;' align='left'>&nbsp;Total applied</td>
								<td align='left' style='width:50%;'>&nbsp;".$results['brFailCnt']."</td>
							  </tr>
							  <tr>
								<td style='width:20%;' align='left'>&nbsp;Passed After Revaluation</td>
								<td align='left' style='height:30px;width:20%;'>&nbsp;".$results['passedAfterRevaluation']."</td>
							  </tr>
							  <tr>
								<td style='height:30px;' align='left'>&nbsp;No change After Revaluation</td>
								<td align='left'>&nbsp;".$results['failedAfterRevaluation']."</td>
							  </tr>
				            </table>";
				
				$html .= "</table><div style='page-break-after:always'></div>";
				
				if ($html) {
					$html = substr($html,0,-43);
					$this->mPDF->init();
					// setting filename of output pdf file
					$this->mPDF->setFilename('REVALUATION_REPORT_'.date('d_M_Y').'.pdf');
					// setting output to I, D, F, S
					$this->mPDF->setOutput('D');
					//$this->mPDF->AddPage('L','', '', '', '',30,30,30,30,18,12);
					$this->mPDF->WriteHTML($html);
					// you can call any mPDF method via component, for example:
					$this->mPDF->SetWatermarkText("Draft");
					$this->autoRender=false;
				}
		}
	}
	
	public function mark() {
		$academics = $this->Program->Academic->find('list',array('order' => array('Academic.academic_name ASC')));
		$programs = $this->Program->find('list',array('order' => array('Program.program_name ASC')));
		$batches = $this->Batch->find('list', array('fields' => array('Batch.batch_period'),'order' => array('Batch.batch_to DESC')));
		$monthyears = $this->MonthYear->getAllMonthYears();
		$this->set(compact('academics','programs','batches','monthyears'));
		if($this->request->is('post')) {
			//pr($this->data);
			$monthYearId = $this->request->data['Revaluation']['month_year_id'];
			$batchId = $this->request->data['Revaluation']['batch_id'];
			//echo $monthYearId." ".$batchId; 
			$revResult = $this->Revaluation->getRevaluationData($monthYearId, $batchId);
			//pr($revResult);

			$revArray = array(); $i=0;
			if ($batchId == "-") $batchId = 0;
			foreach ($revResult as $key => $value) {
				if ($batchId > 0) {
					if ($value['CourseMapping']['batch_id'] == $batchId) {
						$revArray[$i]['revId'] = $value['Revaluation']['id'];
						$revArray[$i]['cmId'] = $value['CourseMapping']['id'];
						$revArray[$i]['batchId'] = $value['CourseMapping']['batch_id'];
						$revArray[$i]['studentId'] = $value['Revaluation']['student_id'];
					}
				} else {
					$revArray[$i]['revId'] = $value['Revaluation']['id'];
					$revArray[$i]['cmId'] = $value['CourseMapping']['id'];
					$revArray[$i]['batchId'] = $value['CourseMapping']['batch_id'];
					$revArray[$i]['studentId'] = $value['Revaluation']['student_id'];
				}
				$i++;
			}
			//echo count($revArray)." *** ";
			//pr($revArray);
			
			$array = array();
			
			foreach ($revArray as $key => $val) {
				$revaluation_status = 1;
				$actualMYId = $monthYearId;
				$student_id = $val['studentId'];
				$cm_id = $val['cmId'];
				$courseTypeId = 1;
				//$compare = 0;
				$eseMarkArray = $this->theoryCaeAndEse($courseTypeId, $cm_id, $student_id, $actualMYId, 0, $monthYearId, 0);
				$revMarkArray = $this->theoryCaeAndEse($courseTypeId, $cm_id, $student_id, $actualMYId, 1, $monthYearId, 0);
				//pr($eseMarkArray);
				//pr($revMarkArray);
					
				$array[$val['studentId']][$val['cmId']] = array(
						'cm_id' => $val['cmId'],
						'cae' => $eseMarkArray['cae'],
						'ese' => $eseMarkArray['ese'],
						'rev' => $revMarkArray['ese'],
						'ese_tot' => $eseMarkArray['total'],
						'rev_tot' => $revMarkArray['total'],
				);
			}
			//echo count($array);
			//pr($array);
		 
			$phpExcel = new PHPExcel();
			$phpExcel->setActiveSheetIndex(0);
			$sheet = $phpExcel->getActiveSheet();
			
			$sheet->getRowDimension('1')->setRowHeight('18');
			$sheet->mergeCells('E1:G1');
			$sheet->setCellValue('E1','BEFORE REVALUATION');
			
			$sheet->mergeCells('H1:J1');
			$sheet->setCellValue('H1','AFTER REVALUATION');
			
			$row = 2; // 1-based index
			$col = 0;
			
			$sheet->getRowDimension('2')->setRowHeight('18');
			$sheet->getColumnDimension('B')->setWidth(12);
			$sheet->getColumnDimension('C')->setWidth(30);
			$sheet->getColumnDimension('D')->setWidth(16);
			$sheet->getColumnDimension('E')->setWidth(6);
			$sheet->getColumnDimension('F')->setWidth(6);
			$sheet->getColumnDimension('G')->setWidth(6);
			$sheet->getColumnDimension('H')->setWidth(6);
			$sheet->getColumnDimension('I')->setWidth(6);
			$sheet->getColumnDimension('J')->setWidth(6);
			
			$sheet->setTitle("Revaluation_Analysis");
			
			$sheet->setCellValueByColumnAndRow($col, $row, "REGISTER NUMBER");$col++;
			$sheet->setCellValueByColumnAndRow($col, $row, "BATCH");$col++;
			$sheet->setCellValueByColumnAndRow($col, $row, "STUDENT NAME");$col++;
			$sheet->setCellValueByColumnAndRow($col, $row, "COURSE CODE");$col++;
			$sheet->setCellValueByColumnAndRow($col, $row, "CAE");$col++;
			$sheet->setCellValueByColumnAndRow($col, $row, "ESE");$col++;
			$sheet->setCellValueByColumnAndRow($col, $row, "TOTAL");$col++;
			$sheet->setCellValueByColumnAndRow($col, $row, "CAE");$col++;
			$sheet->setCellValueByColumnAndRow($col, $row, "ESE");$col++;
			$sheet->setCellValueByColumnAndRow($col, $row, "TOTAL");$col++;
			
			
			$row++;
			$row = 3;
			
			foreach ($array as $student_id => $innerArray) {
				foreach ($innerArray as $cm_id => $result) {
					$sheet->getRowDimension($row)->setRowHeight('18');
					$cmArray = array();
					$cmArray[$cm_id] = $cm_id;
					$courseArray = $this->CourseMapping->retrieveCourseDetails($cmArray, $monthYearId);
					$courseCode = $courseArray[$cm_id]['course_code'];
					$studentArray = $this->Student->studentDetails($student_id);
					$batch = $studentArray[0]['Batch']['batch_from']."-".$studentArray[0]['Batch']['batch_to'];
					//pr($studentArray);
					$col = 0;
					
					$sheet->setCellValueByColumnAndRow($col, $row, $studentArray[0]['Student']['registration_number']);$col++;
					$sheet->setCellValueByColumnAndRow($col, $row, $batch);$col++;
					$sheet->setCellValueByColumnAndRow($col, $row, $studentArray[0]['Student']['name']);$col++;
					$sheet->setCellValueByColumnAndRow($col, $row, $courseCode);$col++;
					$sheet->setCellValueByColumnAndRow($col, $row, $result['cae']);$col++;
					$sheet->setCellValueByColumnAndRow($col, $row, $result['ese']);$col++;
					$sheet->setCellValueByColumnAndRow($col, $row, $result['ese_tot']);$col++;
					$sheet->setCellValueByColumnAndRow($col, $row, $result['cae']);$col++;
					$sheet->setCellValueByColumnAndRow($col, $row, $result['rev']);$col++;
					$sheet->setCellValueByColumnAndRow($col, $row, $result['rev_tot']);$col++;
					$row++;
				}
			}
			$download_filename="Revaluation_Analysis_".date('d-M-Y h:i:s');
			header("Content-Type: application/vnd.ms-excel");
			header("Content-Disposition: attachment; filename=\"$download_filename.xls\"");
			header("Cache-Control: max-age=0");
			$objWriter = PHPExcel_IOFactory::createWriter($phpExcel, "Excel5");
			$objWriter->save("php://output");
			exit;
		}
	}
	
	public function searchMark($monthYearId=NULL, $batchId=NULL, $printOption=NULL) {
		//echo $monthYearId;
	}
}