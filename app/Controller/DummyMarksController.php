<?php
App::uses('AppController', 'Controller');
App::import('Controller', 'ContinuousAssessmentExams');
//App::import('Vendor', 'PHPExcel', array('file' => 'PHPExcel'.DS.'IOFactory.php'));
/**
 * DummyMarks Controller
 *
 * @property DummyMark $DummyMark
 * @property PaginatorComponent $Paginator
 */
class DummyMarksController extends AppController {
	public $cType = "theory";
	public $uses = array("DummyMark", "DummyFinalMark", "DummyNumber","DummyNumberAllocation", "ExamAttendance", "ContinuousAssessmentExam", "Timetable", "EsePractical", "CourseStudentMapping", "Course", "User", "Batch", "CourseFaculty", "Student", "Academic", "CaePractical", "Project", "Practical", "CaeProject", "GrossAttendance", "Cae", "CourseMode", "CourseMapping", "MonthYear", "Attendance", "InternalExam", "Program", "CourseType", "InternalExam","EndSemesterExam");
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
		$access_result = $this->checkPathAccess($this->Auth->user('id'));
		if (!$access_result) {
			$monthyears = $this->MonthYear->getAllMonthYears();
			$this->set(compact('monthyears'));
		}
		else {
			$this->render('../Users/access_denied');
		}
	}
	
	public function upload() {
		$access_result = $this->checkPathAccess($this->Auth->user('id'));
		if (!$access_result) {
		if($this->request->is('post')) {
			if(isset($this->request->data['markEntry']['monthyears']) && isset($this->request->data['markEntry']['mark_entry'])){
				//Upload file - START
				$monthYear_id = $this->request->data['markEntry']['monthyears'];
				$markEntryLevel = $this->request->data['markEntry']['mark_entry'];
				$errorMsg=""; 
				if ($monthYear_id && $markEntryLevel) {
					if(!empty($this->request->data['markEntry']['marks'])) {
						move_uploaded_file($this->request->data['markEntry']['marks']['tmp_name'], WWW_ROOT . '../sets_upload_received_file/' .$this->request->data['markEntry']['marks']['name']);
						$filename = WWW_ROOT . '../sets_upload_received_file/'.$this->request->data['markEntry']['marks']['name'];
						//echo $filename;
						
						$objPHPExcel = PHPExcel_IOFactory::load(WWW_ROOT . '../sets_upload_received_file/'.$this->request->data['markEntry']['marks']['name']);
						$worksheet = $objPHPExcel->setActiveSheetIndex(0);
						//foreach ($objPHPExcel->getWorksheetIterator() as $worksheet) {
						$worksheetTitle     = $worksheet->getTitle();
						$highestRow         = $worksheet->getHighestRow(); // e.g. 10
						$highestColumn      = $worksheet->getHighestDataColumn(); // e.g 'F'
						$highestColumnIndex = PHPExcel_Cell::columnIndexFromString($highestColumn);
						$nrColumns = ord($highestColumn) - 64;
						
						//echo "<br>The worksheet ".$worksheetTitle." has ";
						//echo $nrColumns . ' columns (A-' . $highestColumn . ') ';
						//echo ' and ' . $highestRow . ' row.';
						$highestRow = $highestRow-1;
						$dataRow = 2;
						$DNColumn = 0;
						$markColumn = 1;
						$j = 1; 
						for ($i=$dataRow; $i<=$highestRow; $i++) {
							$cell = $worksheet->getCellByColumnAndRow($DNColumn, $i);
							//echo $cell;
							$dummyNo = $cell->getValue();
							//echo "DummyNumber : ".$dummyNo."</br>";
							$dummyNumber = substr($dummyNo,0,4);
							
							$cell = $worksheet->getCellByColumnAndRow($markColumn, $i);
							$mark = $cell->getValue();
							//echo "Marks : ".$mark."</br>";
							//echo $dummyNumber;
							
							//Mark Entry - START
							$dummyCondition = array();
							if($dummyNo && isset($mark)){
								$dummyCondition['DummyNumber.start_range LIKE'] = "$dummyNumber%";
							
								$res = array(
										'conditions' => array('DummyNumber.indicator' => 0,'DummyNumber.month_year_id'=>$monthYear_id,$dummyCondition,
										),
										'fields' =>array('DummyNumber.id','DummyNumber.start_range','DummyNumber.end_range'),
										/*'contain'=>array(
										 'DummyMark'=>array('fields' =>array('DummyMark.id','DummyMark.dummy_number_id','DummyMark.dummy_number','DummyMark.mark_entry1','DummyMark.mark_entry2')),
												'EndSemesterExam'=>array('fields' =>array('EndSemesterExam.id','EndSemesterExam.marks'),'limit'=>1),
										),*/
										'recursive' => 0
								);
								$dummyM = $this->DummyNumber->find("all", $res);
								//pr($dummyM); 
								if(isset($dummyM[0]['DummyNumber']['id'])){
									$data_exists=$this->DummyMark->find('first', array(
											'conditions' => array(
													'DummyMark.dummy_number_id'=>$dummyM[0]['DummyNumber']['id'],
													'DummyMark.dummy_number'=>$dummyNo,
											),
											'fields' => array('DummyMark.id'),
											'recursive' => 0
									));
									//pr($data_exists);
									
									$data=array();
									$data['DummyMark']['dummy_number_id'] = $dummyM[0]['DummyNumber']['id'];
									$data['DummyMark']['dummy_number'] = $dummyNo;
									$data['DummyMark']['mark_entry'.$markEntryLevel] = $mark;
									$data['DummyMark']['mark'.$markEntryLevel.'_created_by'] = $this->Auth->user('id');
										
									if(isset($data_exists['DummyMark']['id']) && $data_exists['DummyMark']['id']>0) {
										$id = $data_exists['DummyMark']['id'];
										$data['DummyMark']['id'] = $id;
										$data['DummyMark']['modified_by'] = $this->Auth->user('id');
										$data['DummyMark']['modified'] = date("Y-m-d H:i:s");
									}
									else {
										$this->DummyMark->create($data);
										if($markEntryLevel == 1){
											$data['DummyMark']['created']	= date('Y-m-d h:i:s');
										}else{
											$data['DummyMark']['created'.$markEntryLevel]	= date('Y-m-d h:i:s');
										}
									}
									if($this->DummyMark->save($data)) $j++;
									else {
										$errorMsg.=$dummyNo.",";
									}
								}
							}
							else {
								$errorMsg.=$dummyNo.", ";
							}
						}
						if ($highestRow == $j) $this->Flash->success(__('The dummy mark has been saved.'));
						else $this->Flash->success(__('The dummy mark has been saved except for dummy numbers : '.$errorMsg));
						//Mark Entry - END
					}
				}
			}
		}
		$monthyears = $this->MonthYear->getAllMonthYears();
		$this->set(compact('monthyears'));
		}
		else {
			$this->render('../Users/access_denied');
		} 
	}
	
	public function upload_old() { 
		if($this->request->is('post')) {
			if(isset($this->request->data['markEntry']['monthyears']) && isset($this->request->data['markEntry']['mark_entry'])){
				//Upload file - START
				if(!empty($this->request->data['markEntry']['marks'])) {
					move_uploaded_file($this->request->data['markEntry']['marks']['tmp_name'], WWW_ROOT . '../sets_upload_received_file/' .$this->request->data['markEntry']['marks']['name']);
					$filename = WWW_ROOT . '../sets_upload_received_file/'.$this->request->data['markEntry']['marks']['name'];
					
					$monthYear_id = $this->request->data['markEntry']['monthyears'];
					$markEntryLevel = $this->request->data['markEntry']['mark_entry'];
					$handle = fopen($filename, "r");
					
					// read the 1st row as headings
					$header = fgetcsv($handle);
					$i = 1;
					$row = 1;
					
					while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
						$num = count($data);
						$row++;
						$dummyNo = $data[0];
						$dummyNumber = substr($dummyNo,0,4);
						$mark = $data[1];
						
						//Mark Entry - START
						$dummyCondition = array();
						if($dummyNumber){
							$dummyCondition['DummyNumber.start_range LIKE'] = "$dummyNumber%";
						}
						
						$res = array(
								'conditions' => array('DummyNumber.indicator' => 0,'DummyNumber.month_year_id'=>$monthYear_id,$dummyCondition,
								),
								'fields' =>array('DummyNumber.id','DummyNumber.start_range','DummyNumber.end_range'),
								/*'contain'=>array(
										'DummyMark'=>array('fields' =>array('DummyMark.id','DummyMark.dummy_number_id','DummyMark.dummy_number','DummyMark.mark_entry1','DummyMark.mark_entry2')),
										'EndSemesterExam'=>array('fields' =>array('EndSemesterExam.id','EndSemesterExam.marks'),'limit'=>1),
								),*/
								'recursive' => 0
						);
						$dummyM = $this->DummyNumber->find("all", $res);
						if(isset($dummyM[0]['DummyNumber']['id'])){
							$data_exists=$this->DummyMark->find('first', array(
							 'conditions' => array(
								 'DummyMark.dummy_number_id'=>$dummyM[0]['DummyNumber']['id'],
								 'DummyMark.dummy_number'=>$dummyNo,
							 ),
							 'fields' => array('DummyMark.id'),
							 'recursive' => 0
							 ));
							
							$data=array();
							$data['DummyMark']['dummy_number_id'] = $dummyM[0]['DummyNumber']['id'];
							$data['DummyMark']['dummy_number'] = $dummyNo;
							$data['DummyMark']['mark_entry'.$markEntryLevel] = $mark;
							$data['DummyMark']['mark'.$markEntryLevel.'_created_by'] = $this->Auth->user('id');
							
							if(isset($data_exists['DummyMark']['id']) && $data_exists['DummyMark']['id']>0) {
								$id = $data_exists['DummyMark']['id'];
								$data['DummyMark']['id'] = $id;
								$data['DummyMark']['modified_by'] = $this->Auth->user('id');
								$data['DummyMark']['modified'] = date("Y-m-d H:i:s");
							}
							else {
								$this->DummyMark->create($data);								
								if($markEntryLevel == 1){
									$data['DummyMark']['created']	= date('Y-m-d h:i:s');
								}else{
									$data['DummyMark']['created'.$markEntryLevel]	= date('Y-m-d h:i:s');
								}
							}
							$this->DummyMark->save($data);
						}					
						$this->Flash->success(__('The dummy mark has been saved.'));
						//Mark Entry - END
						
						$i++;
					}
					
					fclose($handle);
				}
				//Upload file - END
			}
		}
		$monthYears = $this->MonthYear->find('all', array('fields' => array('MonthYear.id','MonthYear.year','MonthYear.month_id','Month.month_name'),'order' => array('MonthYear.id DESC')));
		$monthyears=array();
		foreach ($monthYears as $key => $value) {
			$monthyears[$value['MonthYear']['id']]=$value['Month']['month_name']."-".$value['MonthYear']['year'];
		}
		$this->set(compact('monthyears'));
	}
	
	public function searchIndex($examYear = null,$dummyNumber = null){	
		$dummyCondition = array();
		if($dummyNumber){
			$dummyCondition['DummyNumber.start_range LIKE'] = "$dummyNumber%";
		}
		$res = array(
			'conditions' => array('DummyNumber.indicator' => 0,'DummyNumber.month_year_id'=>$examYear,$dummyCondition,
			),
			'fields' =>array('DummyNumber.id','DummyNumber.start_range','DummyNumber.end_range','DummyNumber.sync_status'),				
			'contain'=>array(
				'DummyMark'=>array('fields' =>array('DummyMark.id','DummyMark.dummy_number_id','DummyMark.dummy_number','DummyMark.mark_entry1','DummyMark.mark_entry2')),
				'EndSemesterExam'=>array('fields' =>array('EndSemesterExam.id','EndSemesterExam.marks'),'limit'=>1),
			),		
			'recursive' => 1
		);
		$dummyM = $this->DummyNumber->find("all", $res);
		$this->set('dummyMarks', $dummyM);
		$this->layout=false;
	}

/**
 * view method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function view($id = null) {
		if (!$this->DummyMark->exists($id)) {
			throw new NotFoundException(__('Invalid dummy mark'));
		}
		$options = array('conditions' => array('DummyMark.' . $this->DummyMark->primaryKey => $id));
		$this->set('dummyMark', $this->DummyMark->find('first', $options));
	}

/**
 * add method
 *
 * @return void
 */
	public function addMark1($dummy_number_id = null) {
		if($dummy_number_id) {			
			$result = array('conditions' => array('DummyNumber.id' => $dummy_number_id,),
				'fields' =>array('DummyNumber.id','DummyNumber.start_range','DummyNumber.end_range'),
				'contain'=>array(
					'DummyMark'=>array('fields' =>array('DummyMark.id','DummyMark.dummy_number','DummyMark.mark_entry1','DummyMark.mark_entry2'),
						'conditions' => array('DummyMark.dummy_number_id' => $dummy_number_id,),					
					),
					'DummyRangeAllocation'=>array('fields' =>array('DummyRangeAllocation.id','DummyRangeAllocation.timetable_id'),
						'Timetable'=>array('fields' =>array('Timetable.id'),
							'CourseMapping'=>array('fields'=>array('CourseMapping.id'),
								'Program'=>array('fields' =>array('Program.program_name'),
									'Academic'=>array('fields' =>array('Academic.academic_name'))
								),
								'Course'=>array('fields' =>array('Course.course_code','Course.course_name','Course.max_ese_qp_mark')),
								'Batch'=>array('fields' =>array('Batch.batch_from','Batch.batch_to','Batch.academic')),
							),
						),'conditions' => array('DummyRangeAllocation.dummy_number_id' => $dummy_number_id),
					),
				),					
			);		
			$results = $this->DummyNumber->find("all", $result);			
				
			$DNMassignedValue = array();
			if(isset($results[0]['DummyMark'])){
				foreach($results[0]['DummyMark'] as $key=>$value){ //echo "===>Key".$key." ***".$value['dummy_number'];				
					$DNMassignedValue[$value['dummy_number']] = $value;			
				}
			}	
			$this->set(compact('results',$results));
			$this->set(compact('DNMassignedValue',$DNMassignedValue));
			$this->set(compact('results',$results));
			$this->set('DNId',$dummy_number_id);
		}
	}
	
	public function addMark2($dummy_number_id = null) {
		if($dummy_number_id) {
			$result = array('conditions' => array('DummyNumber.id' => $dummy_number_id,),
				'fields' =>array('DummyNumber.id','DummyNumber.start_range','DummyNumber.end_range'),
				'contain'=>array(
					'DummyMark'=>array('fields' =>array('DummyMark.id','DummyMark.dummy_number','DummyMark.mark_entry1','DummyMark.mark_entry2'),
						'conditions' => array('DummyMark.dummy_number_id' => $dummy_number_id,),					
					),
					'DummyRangeAllocation'=>array('fields' =>array('DummyRangeAllocation.id','DummyRangeAllocation.timetable_id'),
						'Timetable'=>array('fields' =>array('Timetable.id'),
							'CourseMapping'=>array('fields'=>array('CourseMapping.id'),
								'Program'=>array('fields' =>array('Program.program_name'),
									'Academic'=>array('fields' =>array('Academic.academic_name'))
								),
								'Course'=>array('fields' =>array('Course.course_code','Course.course_name','Course.max_ese_qp_mark')),
								'Batch'=>array('fields' =>array('Batch.batch_from','Batch.batch_to','Batch.academic')),
							),
						),'conditions' => array('DummyRangeAllocation.dummy_number_id' => $dummy_number_id),
					),
				),					
			);		
			$results = $this->DummyNumber->find("all", $result);			
				
			$DNMassignedValue = array();
			if(isset($results[0]['DummyMark'])){
				foreach($results[0]['DummyMark'] as $key=>$value){ //echo "===>Key".$key." ***".$value['dummy_number'];				
					$DNMassignedValue[$value['dummy_number']] = $value;			
				}
			}	
			$this->set(compact('results',$results));
			$this->set(compact('DNMassignedValue',$DNMassignedValue));
			$this->set(compact('results',$results));
			$this->set('DNId',$dummy_number_id);
		}
	}

	public function editMark1($dummy_number_id = null) {
		if($dummy_number_id) {
			$result = array('conditions' => array('DummyNumber.id' => $dummy_number_id,),
				'fields' =>array('DummyNumber.id','DummyNumber.start_range','DummyNumber.end_range'),
				'contain'=>array(
					'DummyMark'=>array('fields' =>array('DummyMark.id','DummyMark.dummy_number','DummyMark.mark_entry1','DummyMark.mark_entry2'),
						'conditions' => array('DummyMark.dummy_number_id' => $dummy_number_id,),					
					),
					'DummyRangeAllocation'=>array('fields' =>array('DummyRangeAllocation.id','DummyRangeAllocation.timetable_id'),
						'Timetable'=>array('fields' =>array('Timetable.id'),
							'CourseMapping'=>array('fields'=>array('CourseMapping.id'),
								'Program'=>array('fields' =>array('Program.program_name'),
									'Academic'=>array('fields' =>array('Academic.academic_name'))
								),
								'Course'=>array('fields' =>array('Course.course_code','Course.course_name','Course.max_ese_qp_mark')),
								'Batch'=>array('fields' =>array('Batch.batch_from','Batch.batch_to','Batch.academic')),
								'StudentMark'=>array('fields'=>array('StudentMark.marks', 'StudentMark.student_id'))
							),
						),'conditions' => array('DummyRangeAllocation.dummy_number_id' => $dummy_number_id),
					),
				),					
			);		
			$results = $this->DummyNumber->find("all", $result);			
			//pr($results);	
			$DNMassignedValue = array();
			if(isset($results[0]['DummyMark'])){
				foreach($results[0]['DummyMark'] as $key=>$value){ //echo "===>Key".$key." ***".$value['dummy_number'];				
					$DNMassignedValue[$value['dummy_number']] = $value;			
				}
			}	
			$this->set(compact('results',$results));
			$this->set(compact('DNMassignedValue',$DNMassignedValue));
			$this->set(compact('results',$results));
			$this->set('DNId',$dummy_number_id);
		}
	}
	
	public function editMark2($dummy_number_id = null) {
		if($dummy_number_id) {
			$result = array('conditions' => array('DummyNumber.id' => $dummy_number_id,),
				'fields' =>array('DummyNumber.id','DummyNumber.start_range','DummyNumber.end_range'),
				'contain'=>array(
					'DummyMark'=>array('fields' =>array('DummyMark.id','DummyMark.dummy_number','DummyMark.mark_entry1','DummyMark.mark_entry2'),
						'conditions' => array('DummyMark.dummy_number_id' => $dummy_number_id,),					
					),
					'DummyRangeAllocation'=>array('fields' =>array('DummyRangeAllocation.id','DummyRangeAllocation.timetable_id'),
						'Timetable'=>array('fields' =>array('Timetable.id'),
							'CourseMapping'=>array('fields'=>array('CourseMapping.id'),
								'Program'=>array('fields' =>array('Program.program_name'),
									'Academic'=>array('fields' =>array('Academic.academic_name'))
								),
								'Course'=>array('fields' =>array('Course.course_code','Course.course_name','Course.max_ese_qp_mark')),
								'Batch'=>array('fields' =>array('Batch.batch_from','Batch.batch_to','Batch.academic')),
								'StudentMark'=>array('fields'=>array('StudentMark.marks', 'StudentMark.student_id'))
							),
						),'conditions' => array('DummyRangeAllocation.dummy_number_id' => $dummy_number_id),
					),
				),					
			);		
			$results = $this->DummyNumber->find("all", $result);			
				
			$DNMassignedValue = array();
			if(isset($results[0]['DummyMark'])){
				foreach($results[0]['DummyMark'] as $key=>$value){ //echo "===>Key".$key." ***".$value['dummy_number'];				
					$DNMassignedValue[$value['dummy_number']] = $value;			
				}
			}	
			$this->set(compact('results',$results));
			$this->set(compact('DNMassignedValue',$DNMassignedValue));
			$this->set(compact('results',$results));
			$this->set('DNId',$dummy_number_id);
		}
	}

	
	public function dnToM($level = null, $DNId = null, $DN = null, $marks = null, $autoId = null){
		if(($level) && ($DNId) && ($DN)){
			$autoGenId = array();
			if($autoId != '-'){
				$autoGenId['DummyMark.id'] = $autoId;
			}
			
			$data = array();
			$data['DummyMark']['dummy_number_id']	= $DNId;
			$data['DummyMark']['dummy_number']	  	= $DN;
			$data['DummyMark']['mark_entry'.$level]	= $marks;
			
			if($level == 1){
				unset($data['DummyMark']['mark_entry2']);
				$data['DummyMark']['created']	= date('Y-m-d h:i:s');
				$data['DummyMark']['mark'.$level.'_created_by'] = $this->Auth->user('id');
			}
			if($level == 2){
				unset($data['DummyMark']['mark_entry1']);
				$data['DummyMark']['created2']	= date('Y-m-d h:i:s');
				$data['DummyMark']['mark'.$level.'_created_by'] = $this->Auth->user('id');
			}			
			$chk =$this->DummyMark->find('first',
					array('conditions' => array(							
							'DummyMark.dummy_number_id' => $DNId,
							'DummyMark.dummy_number' =>$DN,$autoGenId
					),'recursive'=>1));
			
			if($chk){
				$data['DummyMark']['id'] 			= $chk['DummyMark']['id'];
			}else{
				$this->DummyMark->create();
			}
			try{
				if($this->DummyMark->save($data)){
				//echo "Record saved";
					$this->DummyNumber->updateAll(array("DummyNumber.sync_status" => 0,),array("DummyNumber.id" => $DNId));
				}
			}
			catch(Exception $e){
				echo "Invalid Mark Entry";die;//$e->getMessage();
			}
		}else{
			echo "Invalid Mark Entry Process.";die;
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
		if (!$this->DummyMark->exists($id)) {
			throw new NotFoundException(__('Invalid dummy mark'));
		}
		if ($this->request->is(array('post', 'put'))) {
			if ($this->DummyMark->save($this->request->data)) {
				$this->Flash->success(__('The dummy mark has been saved.'));
				return $this->redirect(array('action' => 'index'));
			} else {
				$this->Flash->error(__('The dummy mark could not be saved. Please, try again.'));
			}
		} else {
			$options = array('conditions' => array('DummyMark.' . $this->DummyMark->primaryKey => $id));
			$this->request->data = $this->DummyMark->find('first', $options);
		}
		$dummyNumbers = $this->DummyMark->DummyNumber->find('list');
		$this->set(compact('dummyNumbers'));
	}

/**
 * delete method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function delete($id = null) {
		$this->DummyMark->id = $id;
		if (!$this->DummyMark->exists()) {
			throw new NotFoundException(__('Invalid dummy mark'));
		}
		$this->request->allowMethod('post', 'delete');
		if ($this->DummyMark->delete()) {
			$this->Flash->success(__('The dummy mark has been deleted.'));
		} else {
			$this->Flash->error(__('The dummy mark could not be deleted. Please, try again.'));
		}
		return $this->redirect(array('action' => 'index'));
	}

	public function approval() {
		$access_result = $this->checkPathAccess($this->Auth->user('id'));
		if (!$access_result) {
		$monthYears = $this->MonthYear->getAllMonthYears();
		$this->set(compact('monthYears'));
		if($this->request->is('post')) {
			//pr($this->data);
			
			if ($this->request->data['DummyApproval']['dummy_number']=="") {
				$this->Flash->error('Please enter the dummy number start range!');
			}
			else {
				//pr($this->data);
				$bool = false;
				$dummyDetails = $this->request->data['DummyApproval']['mark'];
				$dummy_number_id = $this->request->data['dummy_number_id'];
				foreach ($dummyDetails as $id => $marks) {
					$data = array();
					$data['DummyMark']['id'] = $id;
					$data['DummyMark']['mark_entry1'] = $marks;
					$data['DummyMark']['mark_entry2'] = $marks;
					$data['DummyMark']['modified_by'] = $this->Auth->user('id');
					$data['DummyMark']['modified'] = date("Y-m-d H:i:s");
					$this->DummyMark->save($data);
					$bool=true;
				}
				if($bool) {
					$start_range = $this->request->data['DummyApproval']['dummy_number'];
					$month_year_id = $this->request->data['DummyApproval']['month_year_id'];
					$this->moveDummyMarks($dummy_number_id);
					$this->Flash->success('Marks synchronized for dummy numbers!');
				}
			}
			
		}
		}
		else {
			$this->render('../Users/access_denied');
		}
	}
	
	public function getDummyDiffMarks($month_year_id, $start_range, $ajax) {
		
		$dummy_result = $this->DummyNumber->getDummyNumberId($month_year_id, $start_range);
		$sync_status = $dummy_result['DummyNumber']['sync_status'];
		$dummy_number_id = $dummy_result['DummyNumber']['id'];

			$filterCondition= "`(DummyNumber`.`id` = ".$dummy_number_id.") AND ";
			$filterCondition.="(DummyMark.mark_entry1 <> DummyMark.mark_entry2)";
			$result = $this->DummyNumber->DummyMark->find('all', array(
					'conditions' => array($filterCondition),
					'fields' => array(
							'DummyMark.id', 'DummyMark.dummy_number_id', 'DummyMark.mark_entry1', 'DummyMark.mark_entry2',
							'DummyMark.dummy_number', 'DummyMark.mark1_created_by', 'DummyMark.mark2_created_by',
							'DummyMark.modified_by', 'DummyMark.created', 'DummyMark.created2', 'DummyMark.modified'
					)
			));
			//pr($result);
			//die;
			if ($ajax) {
				//Store the mark_entry1 from dummy_marks table to DMA for the dummy range starting with $start_range
				if (count($result) == 0 && $sync_status == 0) {
					$this->moveDummyMarks($dummy_number_id);
				}
				$this->set(compact('result'));
			}
			else {
				if (isset($result) && count($result) > 0) {
					return $result;
				}
			}
		$this->layout=false;
	}
	
	public function moveDummyMarks($dummy_number_id) {
		
		$dmResults = $this->DummyNumber->find('all', array(
				'conditions' => array('DummyNumber.id' => $dummy_number_id),
				'fields' => array('DummyNumber.month_year_id', 'DummyNumber.start_range', 'DummyNumber.end_range',
						'DummyNumber.mode'
				),
				'contain' => array(
						'DummyNumberAllocation' => array('fields' => array(
								'DummyNumberAllocation.dummy_number_id', 'DummyNumberAllocation.student_id',
								'DummyNumberAllocation.dummy_number'
						)),
						'DummyMark' => array('fields' => array(
								'DummyMark.dummy_number_id', 'DummyMark.dummy_number', 'DummyMark.mark_entry1'
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
																'Course.max_ese_qp_mark', 'Course.max_ese_mark'
														)
												)
										)
								)
						),
				)
		));
		//pr($dmResults);
		$month_year_id = $dmResults[0]['DummyNumber']['month_year_id'];
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
					foreach($drAllocation as $key => $drValue){
						$courseMappingArray[] = $drValue['Timetable']['course_mapping_id'];
						$cmQpMark[$drValue['Timetable']['course_mapping_id']] = array(
								'max_qp_mark' => $drValue['Timetable']['CourseMapping']['Course']['max_ese_qp_mark'],
								'max_ese_mark' => $drValue['Timetable']['CourseMapping']['Course']['max_ese_mark']
						);
					}
				}
				else {
						
				}
			}
			//pr($courseMappingArray);
			//pr($cmQpMark);
			$dnAllocation = $result['DummyNumberAllocation'];
			$dna = array();
			foreach ($dnAllocation as $key => $dnaValue) {
				$dna[$dnaValue['dummy_number']] = $dnaValue['student_id'];
			}
			//pr($dna);
		
			$dMark = $result['DummyMark'];
			$dm = array();
			foreach ($dMark as $key => $dMarkValue) {
				$dm[$dMarkValue['dummy_number']] = $dMarkValue['mark_entry1'];
			}
			//pr($dm);
				
		}
		
		$dummyCourseMappingArray = $this->getCourseMappingId($dna, $courseMappingArray);
		//pr($dummyCourseMappingArray);
		
		$convertedDmArray = array();
		foreach ($dummyCourseMappingArray as $dummy_number => $cm_id) {
			$maxQpMark = $cmQpMark[$cm_id]['max_qp_mark'];
			$maxEseMark = $cmQpMark[$cm_id]['max_ese_mark'];
			$dummyMark = $dm[$dummy_number];
			$convertedMark = round($maxEseMark * $dummyMark / $maxQpMark);
			//echo "</br>".$maxQpMark." == ".$maxEseMark." == ".$dummyMark;
			//echo "</br>".$dummy_number." == ".$convertedMark;
			$convertedDmArray[$dummy_number] = $convertedMark;
		}
		//pr($convertedDmArray);
		
		foreach ($convertedDmArray as $dummy_number => $cMark) {
			$conditions = array('EndSemesterExam.course_mapping_id' => $dummyCourseMappingArray[$dummy_number],
				'EndSemesterExam.dummy_number_id' => $dummy_number_id,
				'EndSemesterExam.student_id' => $dna[$dummy_number],
				'EndSemesterExam.month_year_id' => $month_year_id,
			);
			//Changing DFM to ESE
			if ($this->EndSemesterExam->hasAny($conditions)){
				$this->EndSemesterExam->query("UPDATE end_semester_exams set
									marks='".$cMark."',									
									modified = '".date("Y-m-d H:i:s")."',
									month_year_id = $month_year_id,
									dummy_number = '".$dummy_number."',
									dummy_number_id = ".$dummy_number_id.",
									modified_by = ".$this->Auth->user('id').",
									dummy_mod_operator = '',
									dummy_mod_marks = 0
									WHERE dummy_number = ".$dummy_number." AND 
									dummy_number_id = ".$dummy_number_id
						);
				$bool = true;
			}
			else {
				$data=array();
				$data['EndSemesterExam']['dummy_number_id'] = $dummy_number_id;
				$data['EndSemesterExam']['month_year_id'] = $month_year_id;
				$data['EndSemesterExam']['dummy_number'] = $dummy_number;
				$data['EndSemesterExam']['student_id'] = $dna[$dummy_number];
				$data['EndSemesterExam']['course_mapping_id'] = $dummyCourseMappingArray[$dummy_number];
				$data['EndSemesterExam']['marks'] = $cMark;
				$data['EndSemesterExam']['created_by'] = $this->Auth->user('id');
				$this->EndSemesterExam->create();
				$this->EndSemesterExam->save($data);
				$bool = true;
			}
		}
		
		$data=array();
		$data['DummyNumber']['id'] = $dummy_number_id;
		$data['DummyNumber']['sync_status'] = 1;
		$data['DummyNumber']['modified_by'] = $this->Auth->user('id');
		$data['DummyNumber']['modified'] = '".date("Y-m-d H:i:s")."';
		$this->DummyNumber->save($data);
		
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
			//pr($cmid);
			$cmArray[$dummy_number] = $cmid[0]['CourseStudentMapping']['course_mapping_id'];
		}
		return $cmArray;
	}
	
	public function mark_upload_template(){
		$filename = "DummyNumber.xlsx";
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
}
