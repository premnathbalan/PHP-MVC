<?php
App::uses('AppController', 'Controller');
/**
 * DummyNumbers Controller
 *
 * @property DummyNumber $DummyNumber
 * @property PaginatorComponent $Paginator
 * @property FlashComponent $Flash
 * @property SessionComponent $Session
 */
class DummyNumbersController extends AppController {

/**
 * Components
 *
 * @var array
 */
	public $components = array('Paginator', 'Flash', 'Session');
	public $uses = array("DummyNumber","Timetable","Program","Batch","MonthYear","Course","DummyNumberAllocation","DummyRangeAllocation","CourseMapping");

/**
 * index method
 *
 * @return void
 */
	public function index() {
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
	
	public function searchIndex($examMonth = null,$batchId = null,$Academic = null,$programId = null,$exam_type = null) {			
		$conditions=array(); $batchCond=array(); $progCond = array();
		$conditions['Timetable.indicator'] = 0;
		if($examMonth != '-'){
			$conditions['Timetable.month_year_id'] = $examMonth;
			if($batchId != '-'){
				$conditions['CourseMapping.batch_id'] = $batchId;
			}
			if($programId != '-'){
				$conditions['CourseMapping.program_id'] = $programId;
			}
		}
		if($examMonth != '-'){			
			$conditions['Timetable.month_year_id'] = $examMonth;
		}
		if($exam_type != '-'){
			$conditions['Timetable.exam_type'] = $exam_type;
		}
		$res = array(
			'conditions' => $conditions,
			'fields' =>array('Timetable.id','Timetable.exam_date','Timetable.exam_session','Timetable.exam_type','Timetable.month_year_id'),
			'contain'=>array(
				'DummyRangeAllocation'=>array('fields' =>array('DummyRangeAllocation.id','DummyRangeAllocation.timetable_id'),
					'DummyNumber'=>array('fields' =>array('DummyNumber.id','DummyNumber.start_range','DummyNumber.end_range','DummyNumber.mode'))
				),		
				'MonthYear'=>array('fields' =>array('MonthYear.year'),
					'Month'=>array('fields' =>array('Month.month_name'))
				),
				'CourseMapping'=>array('fields'=>array('CourseMapping.id',  'CourseMapping.program_id', 'CourseMapping.course_number', 'CourseMapping.course_mode_id','CourseMapping.semester_id', 'CourseMapping.month_year_id'),
					'Program'=>array('fields' =>array('Program.program_name'),
						'Academic'=>array('fields' =>array('Academic.academic_name'))
					),
					'Course'=>array('fields' =>array('Course.course_code','Course.course_name','Course.common_code','Course.course_type_id')),
					'Batch'=>array('fields' =>array('Batch.batch_from','Batch.batch_to','Batch.academic')),
				),					
			),
				'order' => array('Timetable.exam_date'=>'desc'),
			'recursive' => 1
		);
		$dummyNo = $this->Timetable->find("all", $res);
		//pr($dummyNo);
		$this->set('dummyNos', $dummyNo);
		$this->layout= false;
	}
	

/**
 * view method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function view($id = null) {
		if (!$this->DummyNumber->exists($id)) {
			throw new NotFoundException(__('Invalid dummy number'));
		}
		$options = array('conditions' => array('DummyNumber.' . $this->DummyNumber->primaryKey => $id));
		$this->set('dummyNumber', $this->DummyNumber->find('first', $options));
	}

/**
 * add method
 *
 * @return void
 */
	public function add() {
		if($this->request->is('post')){
			if(isset($this->request->data['Confirm'])){
				for($i=1; $i<=$this->request->data['maxRow'];$i++) { if($this->request->data['dummy_number'.$i]){
					if (strpos($this->request->data['timetable_id'.$i], ',') !== false) { //FOR COMMON CODE  DUMMY NUMBER						
						$eachDummyTimetable = explode(",",$this->request->data['timetable_id'.$i]);
						for($p=0;$p<count($eachDummyTimetable);$p++){
							
							if($p==0){//common code - Save Dummy Numbers table				
								$data = array();							
								$data['DummyNumber']['month_year_id'] = $this->request->data['exam_month'.$i];
								$data['DummyNumber']['start_range']   = $this->request->data['dummy_number'.$i];								
								$data['DummyNumber']['end_range']     = (($this->request->data['dummy_number'.$i])+($this->request->data['totalPresent'.$i]))-1;
								$data['DummyNumber']['mode'] 		  = $this->request->data['mode'];
								$data['DummyNumber']['indicator'] 	  = 0;
								
								$chk =$this->DummyNumber->find('first',
										array('conditions' => array(
											'DummyNumber.id !=' => $this->request->data['autoGenId'.$i],
											'DummyNumber.month_year_id' => $data['DummyNumber']['month_year_id'],
											'DummyNumber.start_range' => $data['DummyNumber']['start_range']
										),'recursive'=>1));
								$record_id = "";
								if($chk){
									$this->Flash->success(__('The Dummy Number Already Exists.'));
								}else{
									if($this->request->data['autoGenId'.$i]){
										$data['DummyNumber']['modified_by'] 	= $this->Auth->user('id');
										$data['DummyNumber']['id'] 				= $this->request->data['autoGenId'.$i];
										if($this->DummyNumber->save($data)){
											$record_id = $this->request->data['autoGenId'.$i];
										}
										$this->Flash->success(__('The Dummy Number Already Exists.'));
									}else{
										$data['DummyNumber']['created_by'] 		= $this->Auth->user('id');
										$this->DummyNumber->create();
										if($this->DummyNumber->save($data)){
											$record_id = $this->DummyNumber->getLastInsertId();
										}
										$this->Flash->success(__('The Dummy Number has been saved.'));
									}
								}								
							}
							if($record_id){
								//Save Dummy Range Allocation table
								$data = array();
								$data['DummyRangeAllocation']['dummy_number_id'] 	= $record_id;
								$data['DummyRangeAllocation']['timetable_id'] 		= $eachDummyTimetable[$p];
								
								$chk =$this->DummyRangeAllocation->find('first',
										array('conditions' => array(
												'DummyRangeAllocation.timetable_id' => $data['DummyRangeAllocation']['timetable_id']
										),'recursive'=>1));
									
								if($chk){
									$data['DummyRangeAllocation']['modified_by'] 	= $this->Auth->user('id');
									$data['DummyRangeAllocation']['id'] 			= $chk['DummyRangeAllocation']['id'];
									$this->Flash->success(__('The Dummy Number has been updated.'));
								}else{
									$data['DummyRangeAllocation']['created_by'] 	= $this->Auth->user('id');
									$this->DummyRangeAllocation->create();
									$this->Flash->success(__('The Dummy Number has been saved.'));
								}
								$this->DummyRangeAllocation->save($data);		
							}
						}						
					
					}else{	//FOR EXAM DATE DUMMY NUMBER					
						$data = array();
						$data['DummyNumber']['month_year_id'] = $this->request->data['exam_month'.$i];
						$data['DummyNumber']['start_range']   = $this->request->data['dummy_number'.$i];
						$data['DummyNumber']['end_range']     = (($this->request->data['dummy_number'.$i])+($this->request->data['totalPresent'.$i]))-1;
						$data['DummyNumber']['mode'] 		  = $this->request->data['mode'];
						$data['DummyNumber']['indicator'] 	  = 0;								
	
						$chk =$this->DummyNumber->find('first',
							array('conditions' => array(
								'DummyNumber.id !=' => $this->request->data['autoGenId'.$i],
								'DummyNumber.month_year_id' => $data['DummyNumber']['month_year_id'],
								'DummyNumber.start_range' => $data['DummyNumber']['start_range']
							),'recursive'=>1));
						$record_id = "";
						if($chk){
							$this->Flash->success(__('The Dummy Number Already Exists.'));
						}else{
							if($this->request->data['autoGenId'.$i]){
								$data['DummyNumber']['modified_by'] 	= $this->Auth->user('id');
								$data['DummyNumber']['id'] 				= $this->request->data['autoGenId'.$i];
								if($this->DummyNumber->save($data)){
									$record_id = $this->request->data['autoGenId'.$i];
								}
								$this->Flash->success(__('The Dummy Number Already Exists4.'));
							}else{
								$data['DummyNumber']['created_by'] 		= $this->Auth->user('id');
								$this->DummyNumber->create();
								if($this->DummyNumber->save($data)){
									$record_id = $this->DummyNumber->getLastInsertId();
								}
								$this->Flash->success(__('The Dummy Number has been saved.'));
							}
						}
						
						
						if($record_id){
							//Save Dummy Range Allocation table
							$RAdata = array();
							$RAdata['DummyRangeAllocation']['dummy_number_id'] 	= $record_id;
							$RAdata['DummyRangeAllocation']['timetable_id'] 	= $this->request->data['timetable_id'.$i];
								
							$chk = $this->DummyRangeAllocation->find('first',
									array('conditions' => array(
											'DummyRangeAllocation.timetable_id' => $RAdata['DummyRangeAllocation']['timetable_id']
									),'recursive'=>1));
							
							if($chk){
								$RAdata['DummyRangeAllocation']['modified_by'] 	= $this->Auth->user('id');
								$RAdata['DummyRangeAllocation']['id'] 			= $chk['DummyRangeAllocation']['id'];
								$this->Flash->success(__('The Dummy Number has been updated.'));
							}else{
								$RAdata['DummyRangeAllocation']['created_by'] 	= $this->Auth->user('id');
								$this->DummyRangeAllocation->create();
								$this->Flash->success(__('The Dummy Number has been saved.'));
							}
							$this->DummyRangeAllocation->save($RAdata);
						}
					}
				}}
				//return $this->redirect(array('action' => 'add'));
			}
		}
		$examDates = $this->Timetable->find('list', array(
				'conditions' => array('Timetable.indicator' => 0,
				),				
				'fields' => array('Timetable.id','Timetable.exam_date'),
				'group' => 'Timetable.exam_date',
				'order' => array('Timetable.exam_date'=>'desc'),
		));
		$eachDates = array();
		if($examDates){
		foreach($examDates as $key => $value){
			list($year,$month,$date)= explode('-', $value);
			$eachDates[$date."-".$month."-".$year] = $date."-".$month."-".$year; 
		}}
		$this->set('timeTables', $eachDates);
		
		$monthyears = $this->MonthYear->getAllMonthYears();
		$this->set(compact('monthyears'));
	}
	
	public function eDCommonCode($examDate = null){
		if($examDate){ 
			list($date,$monthName,$year)= explode('-', $examDate);
			$monthName = strtoupper($monthName);
			$res_month_id = array_keys($this->months, $monthName); 
			$month = $res_month_id[0];
			$examDate = $year."-".$month."-".$date;
		}
		$res = array(
			'conditions' => array('Timetable.indicator' => 0,'Timetable.exam_date' =>$examDate
			),
			'fields' =>array('Timetable.id','Timetable.exam_date','Timetable.exam_session','Timetable.exam_type','Timetable.month_year_id'),
			'contain'=>array(
				'CourseMapping'=>array('fields'=>array('CourseMapping.id',  'CourseMapping.program_id', 'CourseMapping.course_number', 'CourseMapping.course_mode_id','CourseMapping.semester_id', 'CourseMapping.month_year_id'),
					'Course'=>array('fields' =>array('Course.common_code','Course.course_code','Course.course_name')),
				),
			),
			'group' => array('Timetable.id'),
			'order' => array('Timetable.exam_date'=>'desc'),
			'recursive' => 1
		);
		
		$totCommonCode = $this->Timetable->find("all", $res);
		
		$eachCommonCode = array();
		if($totCommonCode){
			foreach($totCommonCode as $commonCode){
				$eachCommonCode[$commonCode['CourseMapping']['Course']['common_code']] = $commonCode['CourseMapping']['Course']['common_code'];
			}	
		}			
		$this->set('eachCommonCode', $eachCommonCode);
		$this->layout= false;
	}
	
	public function getCommonCode($examMonthYear = null,$examDate = null,$common_code = null,$exam_session = null){
		$timeTablecond=array();$commonCodeCond=array();
		if($examMonthYear){
			$timeTablecond['Timetable.month_year_id'] = $examMonthYear;
		}
		if($examDate){ 
			/*list($date,$month,$year)= explode('-', $examDate);
			$examDate = $year."-".$month."-".$date;*/
			$timeTablecond['Timetable.exam_date'] = $examDate;
		}
		if($exam_session){
			$timeTablecond['Timetable.exam_session'] = $exam_session;
		} 
		if($common_code){
			$commonCodeCond['Course.common_code'] = $common_code;
			$res = array(
				'conditions' => $commonCodeCond,
				'fields' =>array('Course.common_code','Course.course_code','Course.course_name'),
				'contain'=>array(
					'CourseMapping'=>array('fields'=>array('CourseMapping.id'),
						'Timetable'=>array('fields' =>array('Timetable.id','Timetable.exam_date','Timetable.exam_session','Timetable.exam_type','Timetable.month_year_id'),
							'ExamAttendance'=>array('fields' =>array('ExamAttendance.attendance_status')),
							'DummyRangeAllocation'=>array('fields' =>array('DummyRangeAllocation.id','DummyRangeAllocation.timetable_id'),
								'DummyNumber'=>array('fields' =>array('DummyNumber.id','DummyNumber.start_range','DummyNumber.end_range','DummyNumber.mode'))
							),		
						 'conditions' => $timeTablecond),
					),
				),
				'recursive' => 1
			);			
			$totExam = $this->Course->find("all", $res);
		}else{ 
			$res = array(
				'conditions' => array('Timetable.indicator' => 0,'Timetable.exam_date' =>$examDate,'Timetable.month_year_id' =>$examMonthYear
				),
				'fields' =>array('Timetable.id','Timetable.exam_date','Timetable.exam_session','Timetable.exam_type','Timetable.month_year_id'),
				'contain'=>array(
					'ExamAttendance'=>array('fields' =>array('ExamAttendance.attendance_status')),
					'DummyRangeAllocation'=>array('fields' =>array('DummyRangeAllocation.id','DummyRangeAllocation.timetable_id'),
						'DummyNumber'=>array('fields' =>array('DummyNumber.id','DummyNumber.start_range','DummyNumber.end_range','DummyNumber.mode'))
					),		
					'MonthYear'=>array('fields' =>array('MonthYear.year'),
						'Month'=>array('fields' =>array('Month.month_name'))
					),
					'CourseMapping'=>array('fields'=>array('CourseMapping.id',  'CourseMapping.program_id', 'CourseMapping.course_number', 'CourseMapping.course_mode_id','CourseMapping.semester_id', 'CourseMapping.month_year_id'),
						'Program'=>array('fields' =>array('Program.program_name'),
								'Academic'=>array('fields' =>array('Academic.academic_name'))
						),
						'Course'=>array('fields' =>array('Course.common_code','Course.course_code','Course.course_name')),
						'Batch'=>array('fields' =>array('Batch.batch_from','Batch.batch_to','Batch.academic')),
					),
				),
				'group' => array('Timetable.id'),
				'order' => array('Timetable.exam_date'=>'desc'),
				'recursive' => 1
			);
			$totExam = $this->Timetable->find("all", $res);
		}
		
		$this->set('totExams', $totExam);
		$this->layout= false;
	}
/**
 * edit method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function edit($id = null) {
		if (!$this->DummyNumber->exists($id)) {
			throw new NotFoundException(__('Invalid dummy number'));
		}
		if ($this->request->is(array('post', 'put'))) {
			if ($this->DummyNumber->save($this->request->data)) {
				$this->Flash->success(__('The dummy number has been saved.'));
				return $this->redirect(array('action' => 'index'));
			} else {
				$this->Flash->error(__('The dummy number could not be saved. Please, try again.'));
			}
		} else {
			$options = array('conditions' => array('DummyNumber.' . $this->DummyNumber->primaryKey => $id));
			$this->request->data = $this->DummyNumber->find('first', $options);
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
		$this->DummyNumber->id = $id;
		if (!$this->DummyNumber->exists()) {
			throw new NotFoundException(__('Invalid dummy number'));
		}
		$this->request->allowMethod('post', 'delete');
		if ($this->DummyNumber->delete()) {
			$this->Flash->success(__('The dummy number has been deleted.'));
		} else {
			$this->Flash->error(__('The dummy number could not be deleted. Please, try again.'));
		}
		return $this->redirect(array('action' => 'index'));
	}
	
	public function chkDummyNumber($dummyNo = null, $exam_month = null,$autoGenId = null,$totPresent = null){		
		$defaultStatus = "Available";
		$endRange = ($dummyNo + $totPresent)-1;
		$autoGenIdCond = "0";
		if($autoGenId != '-'){
			$autoGenIdCond = $autoGenId;
		}
		if(($dummyNo) && ($exam_month)){ //CHECKING FOR DUMMY NUMBER TABLE
			$chk =$this->DummyNumber->find('first',
					array('conditions' => array(
							/*'DummyNumber.start_range <= '.$dummyNo.' AND  DummyNumber.end_range >= '.$dummyNo,*/
							'DummyNumber.start_range BETWEEN '.$dummyNo.' AND  '.$endRange,
							'DummyNumber.id !=' => $autoGenId,
							'DummyNumber.month_year_id' => $exam_month, 
					),'recursive'=>1));
			if($chk){
				$defaultStatus = "Unavailable";
			}			
		}
		echo $defaultStatus;exit;
	}
}