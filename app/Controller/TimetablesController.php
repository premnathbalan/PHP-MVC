<?php
App::uses('AppController', 'Controller'); 
App::import('Vendor', 'mPDF', array('file' => 'mPDF'.DS.'mPDF.php'));
App::import('controller', 'ContinuousAssessmentExams'); 
App::import('controller', 'Arrears');

/**
 * Timetables Controller
 *
 * @property Timetable $Timetable
 * @property PaginatorComponent $Paginator
 * @property FlashComponent $Flash
 * @property SessionComponent $Session
 */
class TimetablesController extends AppController {
	public $timetableArray = array();
	public $datesAssigned = array();
	public $courseIdArray = array();
	public $lastDateAssigned = array();
	public $timetableDatesAssigned = array();
	public $commonDatesAssigned = array();
	public $arrearCommonDatesAssigned = array();
	public $allCoursesArray = array();
	public $anAllCoursesArray = array();
	public $arrearTimetableDatesAssigned = array();
	
	/**
	 * Components
	 *
	 * @var array
	 */
	public $components = array('Paginator', 'Flash', 'Session','mPDF');
	public $uses = array("Timetable","CourseMapping", "Batch", "Program", "MonthYear","Academic","ExamAttendance","StudentMark",
			"Student", "CourseStudentMapping", "Signature"
	);
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
	
		$conditions=array();$batchCond=array();$progCond = array();
		$conditions['Timetable.indicator'] = 0;
		if($examMonth !='-'){
			$conditions['Timetable.month_year_id'] = $examMonth;
		}
		if($batchId !='-'){
			$batchCond['Batch.id'] = $batchId;
		}
		if($programId !='-'){
			$progCond['Program.id'] = $programId;
		}
		if($exam_type !='-'){
			$conditions['Timetable.exam_type'] = $exam_type;
		}
		$result = array(
				'conditions' => $conditions,
				'fields' =>array('Timetable.id','Timetable.exam_date','Timetable.exam_session','Timetable.exam_type','Timetable.month_year_id'),
				'contain'=>array(
						'MonthYear'=>array('fields' =>array('MonthYear.year'),
								'Month'=>array('fields' =>array('Month.month_name'))
						),
						'CourseMapping'=>array('fields'=>array('CourseMapping.id',  'CourseMapping.program_id', 'CourseMapping.course_number', 'CourseMapping.course_mode_id','CourseMapping.semester_id', 'CourseMapping.month_year_id'),
								'MonthYear'=>array('fields' =>array('MonthYear.year'),
										'Month'=>array('fields' =>array('Month.month_name'))
								),
								'Program'=>array('fields' =>array('Program.program_name'),
										'Academic'=>array('fields' =>array('Academic.academic_name')),'conditions' => $progCond
								),
								'Course'=>array('fields' =>array('Course.course_code','Course.course_name')),
								'Batch'=>array('fields' =>array('Batch.batch_from','Batch.batch_to','Batch.academic'),'conditions' => $batchCond),
						),
						'ExamAttendance'=>array('fields' =>array('ExamAttendance.id')),
				),
				'recursive' => 1
		);
		$results = $this->Timetable->find("all", $result);
		$this->set('timetables', $results);
		$this->set('batchId', $batchId);
		$this->set('programId', $programId);
		$this->set('Academic', $Academic);
		$this->set('exam_type', $exam_type);
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
		if (!$this->Timetable->exists($id)) {
			throw new NotFoundException(__('Invalid timetable'));
		}
		$options = array('conditions' => array('Timetable.' . $this->Timetable->primaryKey => $id));
		$this->set('timetable', $this->Timetable->find('first', $options));
		//$this->layout=false;
	}
	
	/**
	 * add method
	 *
	 * @return void
	 */
	public function add() {
		$academics = $this->Program->Academic->find('list',array('order' => array('Academic.academic_name ASC')));
		$programs = $this->Program->find('list',array('order' => array('Program.program_name ASC')));
		$batches = $this->Batch->find('list', array('fields' => array('Batch.batch_period'),'order' => array('Batch.batch_to DESC')));
		$monthYears = $this->MonthYear->find('all', array(
				'fields' => array('MonthYear.id','MonthYear.year','MonthYear.month_id','Month.month_name'),
				'order' => array('MonthYear.id DESC'),
				'recursive'=>0
		));
		$monthyears=array();
	
		foreach ($monthYears as $key => $value) {
			$monthyears[$value['MonthYear']['id']]=$value['Month']['month_name']."-".$value['MonthYear']['year'];
		}
		$this->set(compact('academics','programs','batches','monthyears'));
	}
	
	/**
	 * edit method
	 *
	 * @throws NotFoundException
	 * @param string $id
	 * @return void
	 */
	public function edit($id = null) {
		if (!$this->Timetable->exists($id)) {
			throw new NotFoundException(__('Invalid timetable'));
		}
		if ($this->request->is(array('post', 'put'))) {
			if ($this->Timetable->save($this->request->data)) {
				$this->Flash->success(__('The timetable has been saved.'));
				return $this->redirect(array('action' => 'index'));
			} else {
				$this->Flash->error(__('The timetable could not be saved. Please, try again.'));
			}
		} else {
			$options = array('conditions' => array('Timetable.' . $this->Timetable->primaryKey => $id));
			$this->request->data = $this->Timetable->find('first', $options);
		}
		//$this->layout=false;
	}
	
	/**
	 * delete method
	 *
	 * @throws NotFoundException
	 * @param string $id
	 * @return void
	 */
	public function delete($id = null) {
		$this->Timetable->id = $id;
		if (!$this->Timetable->exists()) {
			throw new NotFoundException(__('Invalid timetable'));
		}
		$this->request->allowMethod('post', 'delete');
		if ($this->Timetable->delete()) {
			$this->Flash->success(__('The timetable has been deleted.'));
		} else {
			$this->Flash->error(__('The timetable could not be deleted. Please, try again.'));
		}
		return $this->redirect(array('action' => 'index'));
	}
	
	public function search($examMonth = null, $batchId = null, $academic_id = null, $programId = null, $semesterId = null,$exam_type = null) {
		$examMYId =  array();$batchCond=array();$progCond = array();$ttEMId = array();
		$stuMId = array();
		if($examMonth !='-'){
			$examMYId['CourseMapping.month_year_id !='] = $examMonth;
			$ttEMId['Timetable.month_year_id'] = $examMonth;
			/*$stuMId['StudentMark.month_year_id'] = $examMonth;*/
		}
		if($batchId !='-'){
			$batchCond['CourseMapping.batch_id'] = $batchId;
		}
		if($programId !='-'){
			$progCond['CourseMapping.program_id'] = $programId;
		}
	
		if($this->request->is('post')) {
			if(isset($this->request->data['Confirm'])){
				for($i=1; $i<=$this->request->data['maxRow'];$i++) {
					if(isset($this->request->data['Timetable']['exam_date'.$i])){
						$data = array();
						$data['Timetable']['month_year_id']		= $this->request->data['month_year_id'];
						$data['Timetable']['course_mapping_id'] = $this->request->data['Timetable']['CM'.$i];
						$data['Timetable']['exam_date'] 		= date("Y-m-d", strtotime($this->request->data['Timetable']['exam_date'.$i]));
						$data['Timetable']['exam_session'] 		= $this->request->data['Timetable']['exam_session'.$i];
						$data['Timetable']['exam_type'] 		= $this->request->data['exam_type'];
	
						$chk =$this->Timetable->find('first',
								array('conditions' => array(
										'Timetable.month_year_id' => $data['Timetable']['month_year_id'],
										'Timetable.course_mapping_id' => $data['Timetable']['course_mapping_id']
								),'recursive'=>1));
							
						if($chk){
							$data['Timetable']['modified_by'] 		= $this->Auth->user('id');
							$data['Timetable']['id'] 				= $chk['Timetable']['id'];
							$this->Flash->success(__('The Time Table has been updated.'));
						}else{
							$data['Timetable']['created_by'] 		= $this->Auth->user('id');
							$this->Timetable->create();
							$this->Flash->success(__('The Time Table has been saved.'));
						}
						$this->Timetable->save($data);
					}
				}
				return $this->redirect(array('action' => 'index'));
			}
		}
		if($exam_type == 'A'){
			$searchResult = array();
			//Get Other Data(Arrear, Late join...) Start
		 $qryResult =  $this->CourseMapping->find('all',  array(
		 		'conditions' => array('CourseMapping.indicator' => 0, $examMYId,$batchCond, $progCond,'Course.course_type_id ' =>  array(1, 3)),
		 		'fields' => array('CourseMapping.id', 'CourseMapping.batch_id', 'CourseMapping.program_id',
		 				'CourseMapping.month_year_id', 'CourseMapping.semester_id'),
		 		'contain' => array(
		 				'CourseStudentMapping' =>array('fields' =>array('CourseStudentMapping.new_semester_id','CourseStudentMapping.student_id'),
		 						'conditions'=>array('CourseStudentMapping.new_semester_id' => $semesterId),
		 						'CourseMapping' => array('fields' =>array('CourseMapping.id'),
		 								'Course' => array(
		 										'fields' => array('Course.course_code', 'Course.course_name'),
		 								),
		 								'Timetable' =>array('fields' =>array('Timetable.exam_date','Timetable.exam_session','Timetable.exam_type'),
		 										'ExamAttendance'=>array('fields' =>array('ExamAttendance.id')),
		 										'conditions'=>$ttEMId,
		 								),
		 						),
		 				),
		 				'Course' => array(
		 						'fields' => array('Course.course_code', 'Course.course_name'),
		 						'CourseType' => array(
		 								'fields' => array('CourseType.course_type')
		 						)
		 				),
		 				'Timetable' =>array('fields' =>array('Timetable.exam_date','Timetable.exam_session','Timetable.exam_type'),
		 						'ExamAttendance'=>array('fields' =>array('ExamAttendance.id')),
		 						'conditions'=>$ttEMId,
		 				),
		 				'StudentMark'=>array(
		 						'conditions' => array(
		 								'OR' => array(
		 										array('StudentMark.revaluation_status' => "0", 'StudentMark.status' => 'Fail'),
		 										array('StudentMark.revaluation_status' => "1", 'StudentMark.final_status'=>'Fail'),
		 								),
		 								'StudentMark.indicator' => 0
		 						),
		 						'fields'=>array(
		 								'StudentMark.id', 'StudentMark.student_id'
		 						)
		 				)
		 		),'group'=>array('CourseMapping.id')
			));
	
		 foreach($qryResult as $key=>$result){
		 	$csm = $result['CourseStudentMapping'];
		 	$sm = $result['StudentMark'];
		 	if (count($csm)>0 || count($sm)>0) {
			 	if(isset($result['Timetable'][0]['exam_session'])){
			 		$searchResult[$result['CourseMapping']['id']]['exam_session'] = $result['Timetable'][0]['exam_session'];
			 	}
			 	if(isset($result['Timetable'][0]['exam_date'])){
			 		$searchResult[$result['CourseMapping']['id']]['exam_date'] = $result['Timetable'][0]['exam_date'];
			 	}
			 	if(isset($result['Timetable'][0]['ExamAttendance']) && count($result['Timetable'][0]['ExamAttendance'])>0) {
			 		$searchResult[$result['CourseMapping']['id']]['attendance'] = "Y";
			 	} else {
			 		$searchResult[$result['CourseMapping']['id']]['attendance'] = "N";
			 	}
			 	$searchResult[$result['CourseMapping']['id']]['course_code'] = $result['Course']['course_code'];
			 	$searchResult[$result['CourseMapping']['id']]['course_name'] = $result['Course']['course_name'];
		 	}
		 }
	
			//Get Other data(Arrear, Late join...) end
		}else{
			$result = array(
					'conditions' => array(
							array('CourseMapping.batch_id' => $batchId,
									'CourseMapping.program_id' => $programId,
									'CourseMapping.indicator' => 0,
									'CourseMapping.semester_id' => $semesterId,
									'Course.course_type_id' =>  array(1, 3)
							)
					),
					'fields' =>array('CourseMapping.id',  'CourseMapping.program_id', 'CourseMapping.course_number', 'CourseMapping.course_mode_id','CourseMapping.semester_id', 'CourseMapping.month_year_id'),
	
					'contain'=>array(
							'Timetable' =>array('fields' =>array('Timetable.exam_date','Timetable.exam_session','Timetable.exam_type'),
									'ExamAttendance'=>array('fields' =>array('ExamAttendance.id')),
							),
							'Course'=>array('fields' =>array('Course.course_code','Course.course_name')),
							'Program'=>array('fields' =>array('Program.program_name'),
									'Academic'=>array('fields' =>array('Academic.academic_name')),
							),
					),
	
					'recursive' => 2
			);
			$searchResult = $this->Timetable->CourseMapping->find("all", $result);
		}
		$this->set('results', $searchResult);//pr( $searchResult);
	
		$this->set('examMonth', $examMonth);
		$this->set('batchId', $batchId);
		$this->set('academic_id', $academic_id);
		$this->set('programId', $programId);
		$this->set('semesterId', $semesterId);
		$this->set('exam_type', $exam_type);
	
		$this->layout=false;
	}
	
	public function listCourses($batchId = null, $academic_id = null, $programId = null, $month_year_id = null) {
	
		$result = array(
				'conditions' => array(
						array('CourseMapping.batch_id' => $batchId,
								'CourseMapping.program_id' => $programId,
								'CourseMapping.indicator' => 0,
								'CourseMapping.month_year_id' => $month_year_id
						)
				),
				'fields' =>array('Timetable.id','Timetable.exam_date','Timetable.exam_session','Timetable.exam_type'),
				'contain'=>array(
						'CourseMapping'=>array(
								'fields' =>array('CourseMapping.id',  'CourseMapping.program_id', 'CourseMapping.course_number', 'CourseMapping.course_mode_id','CourseMapping.semester_id', 'CourseMapping.month_year_id'),
								'Course'=>array('fields' =>array('Course.course_code','Course.course_name')),
								'Program'=>array('fields' =>array('Program.program_name'),
										'Academic'=>array('fields' =>array('Academic.academic_name')),
								),
						),
				),
					
				'recursive' => 2
		);
	
		$listCourses = array();
		$searchResult = $this->Timetable->find("all", $result);
		foreach ($searchResult as $searchResults) {
			$listCourses[$searchResults['Timetable']['id']] = $searchResults['CourseMapping']['Course']['course_code']." - ".$searchResults['CourseMapping']['Course']['course_name'];
		}
		$this->set('listCourses', $listCourses);
		$this->layout=false;
	}
	
	public function timetableReport() {
		$monthyears = $this->MonthYear->getAllMonthYears();
		$this->set(compact('monthyears'));
	}
	
	public function timetableReportSearch($examMonth, $exam_type, $opt) {
		$conditions=array();
		$conditions['Timetable.indicator'] = 0;
		if($examMonth !='-'){
			$conditions['Timetable.month_year_id'] = $examMonth;
			//$conditions['Timetable.id'] = 220;
		}
		if($exam_type !='-'){
			if ($exam_type == 'B') $conditions['Timetable.exam_type'] = array('R', 'A');
			else $conditions['Timetable.exam_type'] = $exam_type;
		}
		$result = array(
				'conditions' => $conditions,
				'fields' =>array('Timetable.id','Timetable.exam_date','Timetable.exam_session','Timetable.exam_type',
						'Timetable.month_year_id'
				),
				'contain'=>array(
						'CourseMapping'=>array(
								'fields'=>array('CourseMapping.id',  'CourseMapping.program_id', 'CourseMapping.batch_id',
										'CourseMapping.course_number',  'CourseMapping.semester_id', 'CourseMapping.month_year_id'
								),
						),
						'ExamAttendance'=>array('fields' =>array('ExamAttendance.id', 'ExamAttendance.attendance_status')),
				),
				'order'=>array('Timetable.id'),
				'recursive' => 1
		);
		$results = $this->Timetable->find("all", $result);
		//pr($results);
		$final_array = $this->processTimetableReport($results);
		//pr($final_array);
		$timetable_month_year = $this->getMonthYearName($examMonth);
		if ($opt == 'search') {
			$this->set(compact('final_array', 'examMonth', 'timetable_month_year', 'exam_type'));
		}
		else if ($opt=='excel') {
			$results = $this->array_MultiOrderBy($final_array,'exam_date', SORT_ASC,'exam_session', SORT_DESC, 'course_code', SORT_ASC);
			//echo count($results);
			//pr($results);
			$this->timetableReportExcel($results, $examMonth, $exam_type);
			$this->autoRender=false;
		}
		$this->layout=false;
	}
	
	public function processTimetableReport($results) {
		$final_array=array();
	
		foreach ($results as $key => $value) {
			$exam_attendance = $value['ExamAttendance'];
			$total=0;
			$present=0;
			$absent=0;
			$exam_date='';
			$total = count($exam_attendance);
				
			//$exam_date = date( "d-M-Y", strtotime($value['Timetable']['exam_date']));
				
			$final_array[$key]['timetable_id'] = $value['Timetable']['id'];
			$final_array[$key]['exam_date'] = $value['Timetable']['exam_date'];
			$final_array[$key]['exam_session'] = $value['Timetable']['exam_session'];
				
			if ($value['Timetable']['exam_type'] == 'R') $exam_type = "Regular";
			if ($value['Timetable']['exam_type'] == 'A') $exam_type = "Arrear";
				
			$final_array[$key]['exam_type'] = $exam_type;
				
			$final_array[$key]['timetable_month_year_id'] = $value['Timetable']['month_year_id'];
			$final_array[$key]['course_mapping_id'] = $value['CourseMapping']['id'];
			$final_array[$key]['batch_id'] = $value['CourseMapping']['batch_id'];
			$final_array[$key]['program_id'] = $value['CourseMapping']['program_id'];
			$final_array[$key]['month_year_id'] = $value['CourseMapping']['month_year_id'];
				
			$txt_month_year = $this->getMonthYearName($value['CourseMapping']['month_year_id']);
			//pr($txt_month_year);
			$final_array[$key]['txt_month_year'] = $txt_month_year;
			$basic_details = $this->getCourseNameCrseCodeCmnCodeFromCMId($value['CourseMapping']['id']);
				
			$b_academic = $basic_details[0]['Batch']['academic'];
			if ($b_academic == "JUN") $b_academic='[A]';
			$batch = $basic_details[0]['Batch']['batch_from']." - ".$basic_details[0]['Batch']['batch_to']." ".$b_academic;
				
			$academic = $basic_details[0]['Program']['Academic']['short_code'];
			$program = $basic_details[0]['Program']['short_code'];
			$course_code = $basic_details[0]['Course']['course_code'];
			$common_code = $basic_details[0]['Course']['common_code'];
			$course_name = $basic_details[0]['Course']['course_name'];
				
			//pr($basic_details);
			foreach ($exam_attendance as $k => $attValue) {
				if ($attValue['attendance_status']==0) $absent++;
				else if ($attValue['attendance_status']==1) $present++;
			}
				
			$final_array[$key]['batch'] = $batch;
			$final_array[$key]['academic'] = $academic;
			$final_array[$key]['program'] = $program;
			$final_array[$key]['course_code'] = $course_code;
			$final_array[$key]['common_code'] = $common_code;
			$final_array[$key]['course_name'] = $course_name;
			$final_array[$key]['total_strength'] = $total;
			$final_array[$key]['present'] = $present;
			$final_array[$key]['absent'] = $absent;
				
		}
		return $final_array;
	}
	
	private function getDateForSpecificDayBetweenDates($startDate, $endDate, $weekdayNumber) {
		$startDate = strtotime($startDate);
		$endDate = strtotime($endDate);
	
		$dateArr = array();
	
		do
		{
			if(date("w", $startDate) != $weekdayNumber)
			{
				$startDate += (24 * 3600); // add 1 day
			}
		} while(date("w", $startDate) != $weekdayNumber);
	
	
		while($startDate <= $endDate)
		{
			$dateArr[] = date('Y-m-d', $startDate);
			$startDate += (7 * 24 * 3600); // add 7 days
		}
	
		return($dateArr);
	}
	
	public function timetable() {
		$access_result = $this->checkPathAccess($this->Auth->user('id'));
		if (!$access_result) {
			$monthYears = $this->MonthYear->find('all', array(
					'fields' => array('MonthYear.id','MonthYear.year','MonthYear.month_id','Month.month_name'),
					'recursive' => 0
			));
			$monthyears=array();
			foreach ($monthYears as $key => $value) {
				$monthyears[$value['MonthYear']['id']]=$value['Month']['month_name']."-".$value['MonthYear']['year'];
			}
			krsort($monthyears);
			$this->set(compact('monthyears'));
		
			if($this->request->is('post')) {
				//pr($this->data);
				$exam_month_year_id = $this->request->data['Timetable']['month_year_id'];
				$startDate = $this->request->data['Timetable']['start_date'];
				$endDate = $this->request->data['Timetable']['end_date'];
					
				$isSundayHoliday = $this->request->data['sunday'];
				$daysDiff = $this->request->data['days_diff'];
				$exceptionalDates = $this->request->data['exceptional'];
				$option = "EXCEL";
				$this->ttGeneration($exam_month_year_id, $startDate, $endDate, $isSundayHoliday, $daysDiff, $exceptionalDates, $option);
			}
		}
		else {
			$this->render('../Users/access_denied');
		}
	}
	
	public function test ($exam_month_year_id) {
		$array = array();
	
		//Step 1: GET UP-TO-DATE ARREAR DATA FROM STUDENTMARK TABLE
		$arrear_results = $this->getArrearDataBasedOnCourseType($exam_month_year_id, Configure::read('CourseType.theory'));
		//RE-ARRANGE THE RESULTSET BASED ON COURSE_MAPPING_ID. THIS IS NOT NECESSARY
		//$cm_id_course_code_array = $this->retriveCmIdCourseCodeFromArrearResults($results);
		/*RE-ARRANGE THE RESULTSET BASED ON COURSE_CODE. USEFUL AS TIMETABLE IS GENERATED BASED ON COMMON COURSE CODE */
		$arrear_course_code_array = $this->retriveCourseCodeCmIdFromArrearResults($arrear_results);
		//pr($arrear_course_code_array);
		$array['StudentMark'] = $arrear_course_code_array;
		//$array['cm_id']['StudentMark'] = $cm_id_course_code_array;
	
		//Step 2: GET ARREAR DATA FROM COURSE_STUDENT_MAPPING TABLE
		$non_arrear_results = $this->getNonArrearDataBasedOnCourseType($exam_month_year_id, Configure::read('CourseType.theory'));
		//RE-ARRANGE THE RESULTSET BASED ON COURSE_MAPPING_ID. THIS IS NOT NECESSARY
		//$cm_id_non_arrear_course_code_array = $this->retriveCmIdCourseCodeFromNonArrearResults($non_arrear_results);
		/*RE-ARRANGE THE RESULTSET BASED ON COURSE_CODE. USEFUL AS TIMETABLE IS GENERATED BASED ON COMMON COURSE CODE */
		$non_arrear_course_code_array = $this->retriveCourseCodeCmIdFromNonArrearResults($non_arrear_results);
		//pr($non_arrear_course_code_array);
		$array['CourseStudentMapping'] = $non_arrear_course_code_array;
		//$array['cm_id']['CourseStudentMapping'] = $cm_id_non_arrear_course_code_array;
	
		//Step 3: GET REGULAR DATA FROM COURSE_MAPPING TABLE
		$regular_results = $this->getRegularDataBasedOnCourseType($exam_month_year_id, Configure::read('CourseType.theory'));
		//pr($regular_results);
		//RE-ARRANGE THE RESULTSET BASED ON COURSE_MAPPING_ID. THIS IS NOT NECESSARY
		//$cm_id_regular_course_code_array = $this->retriveCmIdCourseCodeFromRegularResults($regular_results);
		$regular_course_code_array = $this->retriveCourseCodeCmIdFromRegularResults($regular_results);
		//pr($regular_course_code_array);
		$array['CourseMapping'] = $regular_course_code_array;
		//$array['cm_id']['CourseMapping'] = $cm_id_regular_course_code_array;
	
		$details = array();
	
		//pr($array);
		/* echo "<table border='1'>";
		echo "<tr>";
		echo "<td>Model</td><td>Course_code</td><td>cm_id</td><td>Batch_id</td><td>Program_id</td><td>Course_id</td><td>MonthYearId</td>";
		echo "</tr>"; */
		foreach ($array as $model => $arr) {
			$course_code_details = $arr['course_code_details'];
			foreach ($course_code_details as $course_code => $tmpval) { //pr($tmpval);
				foreach ($tmpval as $cm_id => $value) {
					/* echo "<tr>";
					 echo "<td>".$model."</td><td>".$course_code."</td><td>".$cm_id."</td><td>".$value['batch_id']."</td>
					 <td>".$value['program_id']."</td><td>".$value['course_id']."</td><td>".$value['month_year_id']."</td>";
					 echo "</tr>"; */
					if ($value['month_year_id'] == $exam_month_year_id) $type="R"; else $type="A";
					/* if ($value['month_year_id'] == $exam_month_year_id) {
					 $details['Regular'][$value['batch_duration']][$value['program_id']][$value['course_id']] = $cm_id.",".$value['semester_id'];
					 } else {
					 $details['Arrear'][$value['batch_duration']][$value['program_id']][$value['course_id']] = $cm_id.",".$value['semester_id'];
					 } */
					//$details[$value['batch_duration']][$value['program_id']][$value['course_id']] = $cm_id.",".$value['semester_id'].",".$type;
					//working code
					$details[$value['batch_duration']][$value['course_id']][$cm_id] = $value['semester_id'].",".$type.",".$value['program_id'].",".$value['batch_id'];
				}
			}
		}
		//pr($details);
	}
	
	public function ttGeneration ($exam_month_year_id, $startDate, $endDate, $isSundayHoliday, $daysDiff, $exceptionalDates, $printOption) {
		//echo $exam_month_year_id." ".$startDate." ".$endDate." ".$isSundayHoliday." ".$daysDiff." ".$exceptionalDates." ".$printOption;
		$array = array();
	
		//Step 1: GET UP-TO-DATE ARREAR DATA FROM STUDENTMARK TABLE
		$arrear_results = $this->getArrearDataBasedOnCourseType($exam_month_year_id, Configure::read('CourseType.theory'));
		//RE-ARRANGE THE RESULTSET BASED ON COURSE_MAPPING_ID. THIS IS NOT NECESSARY
		//$cm_id_course_code_array = $this->retriveCmIdCourseCodeFromArrearResults($results);
		/*RE-ARRANGE THE RESULTSET BASED ON COURSE_CODE. USEFUL AS TIMETABLE IS GENERATED BASED ON COMMON COURSE CODE */
		$arrear_course_code_array = $this->retriveCourseCodeCmIdFromArrearResults($arrear_results);
		//pr($arrear_course_code_array);
		$array['StudentMark'] = $arrear_course_code_array;
		//$array['cm_id']['StudentMark'] = $cm_id_course_code_array;
	
		//Step 2: GET ARREAR DATA FROM COURSE_STUDENT_MAPPING TABLE
		$non_arrear_results = $this->getNonArrearDataBasedOnCourseType($exam_month_year_id, Configure::read('CourseType.theory'));
		//RE-ARRANGE THE RESULTSET BASED ON COURSE_MAPPING_ID. THIS IS NOT NECESSARY
		//$cm_id_non_arrear_course_code_array = $this->retriveCmIdCourseCodeFromNonArrearResults($non_arrear_results);
		/*RE-ARRANGE THE RESULTSET BASED ON COURSE_CODE. USEFUL AS TIMETABLE IS GENERATED BASED ON COMMON COURSE CODE */
		$non_arrear_course_code_array = $this->retriveCourseCodeCmIdFromNonArrearResults($non_arrear_results);
		//pr($non_arrear_course_code_array);
		$array['CourseStudentMapping'] = $non_arrear_course_code_array;
		//$array['cm_id']['CourseStudentMapping'] = $cm_id_non_arrear_course_code_array;
	
		//Step 3: GET REGULAR DATA FROM COURSE_MAPPING TABLE
		$regular_results = $this->getRegularDataBasedOnCourseType($exam_month_year_id, Configure::read('CourseType.theory'));
		//pr($regular_results);
		//RE-ARRANGE THE RESULTSET BASED ON COURSE_MAPPING_ID. THIS IS NOT NECESSARY
		//$cm_id_regular_course_code_array = $this->retriveCmIdCourseCodeFromRegularResults($regular_results);
		$regular_course_code_array = $this->retriveCourseCodeCmIdFromRegularResults($regular_results);
		//pr($regular_course_code_array);
		$array['CourseMapping'] = $regular_course_code_array;
		//$array['cm_id']['CourseMapping'] = $cm_id_regular_course_code_array;
	
		$details = array();
		$arrDetails = array();
		//pr($array);
		/* echo "<table border='1'>";
		echo "<tr>";
		echo "<td>Model</td><td>Course_code</td><td>cm_id</td><td>Batch_id</td><td>Program_id</td><td>Course_id</td><td>MonthYearId</td>";
		echo "</tr>"; */
		foreach ($array as $model => $arr) {
			$course_code_details = $arr['course_code_details'];
			foreach ($course_code_details as $course_code => $tmpval) { //pr($tmpval);
				foreach ($tmpval as $cm_id => $value) {
					/* echo "<tr>";
						echo "<td>".$model."</td><td>".$course_code."</td><td>".$cm_id."</td><td>".$value['batch_id']."</td>
						<td>".$value['program_id']."</td><td>".$value['course_id']."</td><td>".$value['month_year_id']."</td>";
						echo "</tr>"; */
					if ($value['month_year_id'] == $exam_month_year_id) $type="R"; else $type="A";
					/* if ($value['month_year_id'] == $exam_month_year_id) {
					 $details['Regular'][$value['batch_duration']][$value['program_id']][$value['course_id']] = $cm_id.",".$value['semester_id'];
						} else {
						$details['Arrear'][$value['batch_duration']][$value['program_id']][$value['course_id']] = $cm_id.",".$value['semester_id'];
						} */
					//$details[$value['batch_duration']][$value['program_id']][$value['course_id']] = $cm_id.",".$value['semester_id'].",".$type;
					//working code
					//$details[$value['batch_duration']][$value['course_id']][$cm_id] = $value['semester_id'].",".$type.",".$value['program_id'].",".$value['batch_id'];
					$semResult = $this->getSemesterIdFromMonthYear($value['batch_id'], $value['program_id'], $exam_month_year_id);
					//pr($semResult);
					$actualSemester = 0;
					if (isset($semResult['CourseMapping']['semester_id'])) {
						$actualSemester = $semResult['CourseMapping']['semester_id'];
					}
					/* $details[$value['batch_duration']][$value['course_id']][$cm_id] = array(
					 'semester_id'=>$value['semester_id'],
					 'current_semester'=>$actualSemester,
					 'type' => $type,
					 'program_id' => $value['program_id'],
					 'batch_id' => $value['batch_id']
						); */
					if ($type == 'R') {
						$details[$type][$value['batch_duration']][$actualSemester][$value['course_id']][$cm_id] = array(
								'semester_id'=>$value['semester_id'],
								'current_semester'=>$actualSemester,
								'batch_duration'=>$value['batch_duration'],
								'type' => $type,
								'program_id' => $value['program_id'],
								'batch_id' => $value['batch_id']
						);
					}
					else if ($type == 'A') {
						$arrDetails[$type][$value['batch_duration']][$value['course_id']][$cm_id] = array(
								'semester_id'=>$value['semester_id'],
								'current_semester'=>$actualSemester,
								'batch_duration'=>$value['batch_duration'],
								'type' => $type,
								'program_id' => $value['program_id'],
								'batch_id' => $value['batch_id']
						);
					}
				}
			}
		}
		//pr($details);
	
		//echo count($details['R']);
		//krsort($details['R']);
		//pr($details['R']);
	
		//$arrearArray = $details['A'];
		//pr($arrearArray); 
		 
		$regularArray = $details['R'];
		krsort($regularArray);
		//echo count($regularArray);
		$bdArray = array(5, 4, 3, 2, 1);
		//pr($bdArray);
		foreach ($bdArray as $key => $value) {
			//echo $value;
			//pr($regularArray[$value]);
			if (isset($regularArray[$value])) {
				krsort($regularArray[$value]);
			}
		}
		//pr($regularArray);
	
		//echo "</table>";
		$tmpDates = explode(",", $exceptionalDates);
		//pr($tmpDates);
	
		$newExceptionaldates = array();
		foreach ($tmpDates as $tmpDate) {
			if(isset($tmpDate)) {
				$tdate = date('Y-m-d', strtotime(trim($tmpDate)));
				$newExceptionaldates[]=$tdate;
			}
		}
		//pr($newExceptionaldates);
		$startDate = date("Y-m-d", strtotime($startDate));
		//	echo $startDate;
		$endDate = date("Y-m-d", strtotime($endDate));
		
		$dateArr = $this->getDateForSpecificDayBetweenDates($startDate, $endDate, 0);
		//pr($dateArr);
	
		$exclude = array_merge($dateArr, array_diff($newExceptionaldates, array_intersect($dateArr, $newExceptionaldates)));
		//pr($exclude);
	
		$allTimetableDates = $this->dateRange($startDate, $endDate);
		//pr($allTimetableDates);
	
		$timetableDates = array();
		$timetableDatesAssigned = array();
	
		$timetableDates = array_values(array_diff($allTimetableDates, $exclude));
		//pr($timetableDates);
	
		$i=1;
		foreach ($timetableDates as $tkey => $tvalue) {
			$timetableDates[$i]=$tvalue;
			$i++;
		}
		unset($timetableDates[0]);
		//pr($timetableDates);
	
		foreach ($timetableDates as $tkey => $tvalue) {
			$timetableDatesAssigned[$tvalue]=0;
		}
		//pr($timetableDatesAssigned);
	
		//echo "total days : ".count($timetableDates);
	
		//echo "**".count($details[1])."**".count($details[2])."**".count($details[3])."**".count($details[4])."**".count($details[5]);
	
		$newGroupedArray = array();
		$newNonGroupedArray = array();
	
		//New timetable logic starts here
		$newTimetableDatesAssigned = array();
		$j = 1;
		//pr($details);
		
		//$tmpRegularArray = $regularArray['4'];
		foreach ($details as $exam_type => $batchDurationArray) {
			foreach ($batchDurationArray as $batch_duration => $tmpRegularArray) {
				foreach ($tmpRegularArray as $actualSemester => $crseArray) {
					foreach ($crseArray as $courseId => $cmArray) {
						if(count($cmArray)>1) {
							$newGroupedArray[$exam_type][$batch_duration][$actualSemester][$courseId]=$cmArray;
						}
						else {
							$newNonGroupedArray[$exam_type][$batch_duration][$actualSemester][$courseId]=$cmArray;
						}
					}
				}
			}
		}
		//pr($newTimetableDatesAssigned);
	
		//pr($newGroupedArray['R']);
		//echo "test";
		//pr($newNonGroupedArray['A']);
	
		//New timetable logic ends here
		//Timetable logic starts here
		$batchArray = array();
		//$programArray = array("1"=>"2017-02-01");
		$programArray = array();
		$cmIdArray = array();
		$courseIdArray = array();
		$programExamDateArray = array();
		$pgmToCheck = array();
	
		$lastDate = array();
	
		/* echo $ttLastDate;
		 if (empty($ttLastDate)) echo "if";
		 else echo "else";
		 echo date('Y-m-d', strtotime($ttLastDate .' +1 day')); */
	
		//$newArray = array();
		$batches = array();
		//pr($details);
		
		//$details = $newGroupedArray['R'];
		
		//Code for Common code
		//foreach ($examTypeArray as $key => $exam_type) { echo $exam_type; 
		//pr($newGroupedArray);
		
		$tmpDetails = $newGroupedArray['R'];
			//pr($tmpDetails['R']);  
		foreach ($tmpDetails as $batch_duration => $batchDetails) {
			//if ($batch_duration == 4) {
				//pr($timetableDates);
				//pr($timetableDatesAssigned);
				$tmpTimetableDates = $timetableDates;
				$tmpTimetableDatesAssigned = $timetableDatesAssigned;
	
				$batches[$batch_duration] = $batch_duration;
	
				foreach ($batchDetails as $actualSemester => $tempArray) {
					//pr($details[4]);
					$tmpArray = array();
	
					$tmpArray = $tempArray;
					//pr($tmpArray);
					//echo "Batch duration : ".$batch_duration;
					$varBool = 0; $groupedArray = array();
						
					foreach ($tmpArray as $courseId => $cmDetails) {
						$varBool = 0;
						$pgmToCheck = $this->loopProgramId($cmDetails);
						//pr($pgmToCheck);
						if (empty($groupedArray)) {
							$groupedArray[0][$courseId] = $cmDetails;
							unset($tmpArray[$courseId]);
						}
						else {
							$cnt = count($groupedArray);
							for ($j=0; $j<count($groupedArray); $j++) {
								$pgmInArray[$j] = array();
								$value = $groupedArray[$j];
								foreach ($value as $c_id => $innerValue) {
									foreach ($innerValue as $cm_id => $array) {
										$pgmInArray[$j][$array['program_id']]=$array['program_id'];
									}
								}
							}
							//pr($pgmInArray);
							$notFound = array();
							for ($j=0; $j<count($groupedArray); $j++) {
								if (isset($pgmInArray[$j]) && !array_intersect_key($pgmToCheck, $pgmInArray[$j])) {
									$groupedArray[$j][$courseId] = $cmDetails;
									$notFound[$j] = 1;
									$varBool = 0;
									unset($tmpArray[$courseId]);
									break;
								} else {
									$notFound[$j] = 0;
									$varBool = 1;
								}
							}
							if ($varBool==1 && !in_array("1", $notFound)) {
								$groupedArray[$cnt][$courseId] = $cmDetails;
								unset($tmpArray[$courseId]);
							}
						}
							
					}
					$cnt = 0;
					//pr($groupedArray);
					if ($exam_type == "R") {
						$this->storeGroupedTimetableDates($groupedArray, $tmpTimetableDates, $daysDiff, $batch_duration, $exam_month_year_id, 'Y', $exam_type);
					}
				}
			//}
		}
		//}
		//Code for nonGroupedArray
		
		//pr($newNonGroupedArray['R']); 
		if(isset($newNonGroupedArray['R'])) {
			foreach ($newNonGroupedArray['R'] as $batch_duration => $semArray) {
				$this->storeUnGroupedTimetableDates($semArray, $tmpTimetableDates, $daysDiff, $batch_duration, $exam_month_year_id, 'N', 'R');
			}
		}
		/*
		 * Grouped Arrear Array
		 */
		
		//pr($newGroupedArray['A']);
		//$this->storeGroupedArrearTimetableDates($newGroupedArray['A'], $tmpTimetableDates, $daysDiff, $batch_duration, $exam_month_year_id, 'Y', $exam_type);
		
		/*
		 * Grouped Arrear Array ends here
		 */
		
		//pr($this->datesAssigned);
		//pr($this->datesAssignedWithCourseId); 
		
		//echo $option;
		//pr($this->timetableArray);
		
		//pr($this->courseIdArray);
		
		//pr($this->datesAssigned);
		//pr($this->datesAssignedWithCourseId);
		//echo count($this->datesAssignedWithCourseId);
		//echo count($timetableDates);
		//pr($this->datesAssigned);
		
		//echo "Grouped Regular Array";
		//pr($newGroupedArray['R']);
		//echo "UnGrouped Regular Array";
		//pr($newNonGroupedArray['R']);
		
		//echo "Timetable Dates for Regular Array";
		//pr($this->timetableDatesAssigned);
		
		//echo "allCoursesArray";
		//pr($arrDetails);
		
		//$arrearArray = $arrDetails['A'];
		
		foreach ($arrDetails['A'] as $batch_duration => $courseArray) {
			foreach ($courseArray as $courseId => $courseInnerArray) {
				// Check if course id exists in global course array
				$option = 0;
				if (array_key_exists($courseId, $this->allCoursesArray)) {
					//echo "YES";
					$tmpRegArray = $this->allCoursesArray[$courseId];
					$examDate = $tmpRegArray[0]['exam_date'];
					//pr($timetableDates);
					$bKey = array_keys($timetableDates, $examDate);
					//echo "bKey ";
					//pr($bKey);
					
					if (isset($bKey[0])) $nextKey = $bKey[0] + 1;
					if (array_key_exists($nextKey, $timetableDates)) $examDate = $timetableDates[$nextKey];
					//echo $examDate." * ";
					//if (in_array($examDate, ))
					$option = 1;
					//pr($courseInnerArray);
					$pgmBatchArray = array(); 
					$exam_session = 'FN';
				} else {
					//echo "*** ";
					foreach ($courseInnerArray as $cm_id => $tmpVal1) {
						$pgmBatchArray[$tmpVal1['program_id']] = $tmpVal1['batch_id'];
						$unique_values = array_values($this->timetableDatesAssigned[$tmpVal1['batch_id']][$tmpVal1['program_id']]);
						//pr($unique_values);
					}
					$exam_session = 'FN';
					$remainingDates = array_diff($timetableDates, $unique_values);
					//echo count($remainingDates);
					//pr($remainingDates);
					$examDate = current($remainingDates);
					$option = 1;
					
				}
				if ($examDate == "") { 
					//echo "NULL";
					$option = 0;
					//echo "<script>if (confirm('Dates unavailable!!!') == true) alert('hi');</script>";
					$exam_session = "AN";
					$examDate = current($timetableDates);
					foreach ($courseInnerArray as $cm_id => $tmpVal1) {
						$pgmBatchArray[$tmpVal1['program_id']] = $tmpVal1['batch_id'];
						if (isset($this->arrearTimetableDatesAssigned[$tmpVal1['batch_id']][$tmpVal1['program_id']])) {
							$unique_values = array_values($this->arrearTimetableDatesAssigned[$tmpVal1['batch_id']][$tmpVal1['program_id']]);
							//pr($unique_values);
							$remainingDates = array_diff($timetableDates, $unique_values);
							//echo count($remainingDates);
							//pr($remainingDates);
							$examDate = current($remainingDates);
						}
						//echo "@@".$examDate." ".$cm_id;
					}
				}
				foreach ($courseInnerArray as $cm_id => $tmpVal1) {
					if ($option == 1) {
						$this->timetableDatesAssigned[$tmpVal1['batch_id']][$tmpVal1['program_id']][$cm_id] = $examDate;
					}
					else {
						$this->arrearTimetableDatesAssigned[$tmpVal1['batch_id']][$tmpVal1['program_id']][$cm_id] = $examDate;
					}
					$this->allCoursesArray[$courseId][]= array(
							'batch_id'=>$tmpVal1['batch_id'],
							'program_id'=>$tmpVal1['program_id'],
							'cm_id' => $cm_id,
							'exam_date' => $examDate,
							'exam_session' => $exam_session,
							'month_year_id' => $exam_month_year_id,
							'exam_type' => 'A'
					);
				}
				//pr($this->timetableDatesAssigned);
				//pr($this->arrearTimetableDatesAssigned);
			}
			
		}
		//pr($this->allCoursesArray);
		
		//pr($this->timetableDatesAssigned);
		//pr($this->arrearTimetableDatesAssigned);
		//pr($this->allCoursesArray);
 
		if ($printOption == "EXCEL") { 
			
			$this->autoLayout = false;
			$this->layout = false;
			$this->autoRender = false;
			
			//$tmpFinalArray = $this->timetableArray;
			$tmpFinalArray = $this->allCoursesArray;
			//unset($this->allCoursesArray);
			$this->downloadTimetableAsExcel($tmpFinalArray, $exam_month_year_id);
				
		}
	}
	
	public function storeGroupedArrearTimetableDates($groupedArray, $timetableDates, $daysDiff, $batch_duration, $exam_month_year_id, $grpValue, $exam_type) {
		//pr($timetableDates);
		//pr($groupedArray);
		//echo "hiiiiiiiii";
		//pr($this->courseIdArray);
		//echo "hiiiiiiiii";

		if ($exam_type == "A") {
			echo $exam_type;
			//pr($groupedArray);
			foreach ($groupedArray as $batch_duration => $batchDetails) {
				foreach ($batchDetails as $actualSemester => $courseDetails) {
					foreach ($courseDetails as $courseId => $cmArray) {
						if (array_key_exists($courseId, $this->courseIdArray['R']['GR']) || array_key_exists($courseId, $this->courseIdArray['R']['UR'])) {
							echo "YES";
						} else {
							echo "</br>NO";
							//pr($cmArray);
							$commaValue = ""; 
							//echo "***";
							//pr($this->timetableDatesAssigned[4][10]);
							//echo "@@@";
							$found = 0;
							foreach ($cmArray as $cm_id => $cmValue) {
								echo $cmValue['batch_id']." ".$cmValue['program_id']." * ";
								//pr($this->timetableDatesAssigned[$cmValue['batch_id']][$cmValue['program_id']]);
								
								if ($commaValue == "") $commaValue = $cmValue['program_id'];
								else $commaValue.= ",".$cmValue['program_id'];
								
								if (isset($this->arrearCommonDatesAssigned['AN'][$cmValue['batch_id']][$cmValue['program_id']])) {
									$found = 1;
								}
								else {
									if (isset($this->timetableDatesAssigned[$cmValue['batch_id']][$cmValue['program_id']])) {
										$tmpDateArray = $this->timetableDatesAssigned[$cmValue['batch_id']][$cmValue['program_id']];
										sort($tmpDateArray);
										//pr($tmpDateArray);
										$endDate = end($tmpDateArray);
										$bKey = array_keys($timetableDates, $endDate);
										if (isset($bKey[0])) $nextKey = $bKey[0] + $daysDiff;
										if (array_key_exists($nextKey, $timetableDates)) $examDate = $timetableDates[$nextKey];
										else {
											$examDate = "NA";
										}
									}
								}
								if ($found == 1) {
									//pr($this->timetableDatesAssigned[$cmValue['batch_id']][$cmValue['program_id']]);
									$tmpDateArray = $this->arrearCommonDatesAssigned['AN'][$cmValue['batch_id']][$cmValue['program_id']];
									sort($tmpDateArray);
									//pr($tmpDateArray);
									$endDate = end($tmpDateArray);
									$bKey = array_keys($timetableDates, $endDate);
									//echo "bKey ";
									//pr($bKey);
									
									if (isset($bKey[0])) $nextKey = $bKey[0] + $daysDiff;
									if (array_key_exists($nextKey, $timetableDates)) $examDate = $timetableDates[$nextKey];
									else {
										$examDate = "NA";
									}
									
								}
								else {
									if ($batch_duration % 5 == 0 || $batch_duration % 4 == 0) $examDate = $timetableDates[1];
									else if ($batch_duration % 3 == 0) $examDate = $timetableDates[4];
									else if ($batch_duration % 2 == 0) $examDate = $timetableDates[10];
									else if ($batch_duration % 1 == 0) $examDate = $timetableDates[15];
								}
								// Code to download as excel to be written here
								
							}
							foreach ($cmArray as $cm_id => $cmValue) {
								$exam_session = "AN";
								$this->timetableDatesAssigned[$cmValue['batch_id']][$cmValue['program_id']][$cm_id] = $examDate;
								$this->arrearCommonDatesAssigned['AN'][$cmValue['batch_id']][$cmValue['program_id']][] = $examDate;
							}
							//pr($this->arrearCommonDatesAssigned);
							//pr($this->timetableDatesAssigned);
							
						}
					}
				}
			}
			
		}
	}
	
	public function storeGroupedTimetableDates($groupedArray, $timetableDates, $daysDiff, $batch_duration, $exam_month_year_id, $grpValue, $exam_type) {
		//pr($timetableDates);
		//pr($groupedArray);
		//echo "hiiiiiiiii";
		$daysDiff = $daysDiff + 1;
		//$lastDateAssigned = array();
		foreach ($groupedArray as $k => $crseIdArray) {
			foreach ($crseIdArray as $courseId => $cmArray) {
				foreach ($cmArray as $cm_id => $cmValue) {
					//$this->lastDateAssigned[$cmValue['batch_id']][$cmValue['program_id']]=$examDate;
					//$this->timetableDatesAssigned[$cmValue['batch_id']][$cmValue['program_id']][$examDate]=1;
					//$this->datesAssigned[$cmValue['batch_id']][$cmValue['program_id']][$cm_id] = $examDate;
					/* $this->datesAssignedWithCourseId[$courseId][$cm_id]=array(
							'batch_id' => $cmValue['batch_id'],
							'program_id' => $cmValue['program_id'],
							'exam_date' => $examDate
					); */
					if ($grpValue == "Y") $grpVar = "GR"; else $grpVar = "UR";
					$this->courseIdArray[$exam_type][$grpVar][$courseId][$cm_id]=$batch_duration.",".$cmValue['batch_id'].",".$cmValue['program_id'].",".$cmValue['semester_id'];
				}
			}
		}
		//pr($this->courseIdArray);
		
		$found = 0;
		$commaValue = "";
		
		foreach ($groupedArray as $k => $crseIdArray) {
			foreach ($crseIdArray as $courseId => $cmArray) {
				if (empty($this->datesAssigned[$batch_duration])) {
					if ($batch_duration % 5 == 0 || $batch_duration % 4 == 0) $examDate = $timetableDates[1];
					else if ($batch_duration % 3 == 0) $examDate = $timetableDates[4];
					else if ($batch_duration % 2 == 0) $examDate = $timetableDates[10];
					else if ($batch_duration % 1 == 0) $examDate = $timetableDates[15];
					//echo "First entry ".$examDate;
				}
				else {
					//pr($this->commonDatesAssigned);
					//pr($cmArray);
					$tmp_pgm_id = array_column($cmArray, 'program_id');
					//pr($tmp_pgm_id);
					
					$tmpDateArray = $this->commonDatesAssigned[$batch_duration];
					sort($tmpDateArray);
					//pr($tmpDateArray);
					$endDate = end($tmpDateArray);
					//echo $endDate;
					
					$pgmIdOnGivenDate = $this->datesAssigned[$batch_duration][$endDate];
					//pr($pgmIdOnGivenDate);
					
					$arrPgmIdOnGivenDate = explode(",",$pgmIdOnGivenDate);
				//	pr($arrPgmIdOnGivenDate);
					
					// Get values from arr2 which exist in arr1
					$overlap = array_intersect($arrPgmIdOnGivenDate, $tmp_pgm_id);
					//echo "overlap ";
					//pr($overlap);
					
					if (count($overlap)==0) {
						$examDate = $endDate;
						//echo "EEEEE ".$examDate;
					}
					else {
						$found=1;
						$tmpDateArray = $this->commonDatesAssigned[$batch_duration];
						sort($tmpDateArray);
						//pr($tmpDateArray);
						$endDate = end($tmpDateArray);
						
						$output = end($tmpDateArray); 
						//echo $output; 
						
						$bKey = array_keys($timetableDates, $output);
						//echo "bKey ";
						//pr($bKey);
						
						if (isset($bKey[0])) $nextKey = $bKey[0] + $daysDiff;
						if (array_key_exists($nextKey, $timetableDates)) $examDate = $timetableDates[$nextKey];
						else {
							$examDate = "NA";
						}
						//echo "</br>Next entry ".$examDate;
						$commaValue = "";
						
					}
				}
				
				foreach ($cmArray as $cm_id => $cmValue) {
					
					$this->timetableDatesAssigned[$cmValue['batch_id']][$cmValue['program_id']][$cm_id] = $examDate;
					$this->allCoursesArray[$courseId][]= array(
							'batch_id'=>$cmValue['batch_id'],
							'program_id'=>$cmValue['program_id'],
							'cm_id' => $cm_id,
							'exam_date' => $examDate,
							'exam_session' => 'FN',
							'month_year_id' => $exam_month_year_id,
							'exam_type' => $exam_type
					);
					
					if ($commaValue == "") $commaValue = $cmValue['program_id'];
					else {
						$commaValue.= ",".$cmValue['program_id'];
					}
					
					/*
					 * Code to place timetable starts here
					 */
					/* $array = array();
					$array['month_year_id'] = $exam_month_year_id;
					$array['semester_id'] = $cmValue['semester_id'];
					$array['course_id'] = $courseId;
					$array['course_mapping_id'] = $cm_id;
					$array['exam_type'] = $cmValue['type'];
					$array['exam_date'] = $examDate;
					$exam_session = "";
					if ($cmValue['type'] == "R") $exam_session = 'FN';
					else $exam_session = 'AN';
					
					$array['exam_session'] = $exam_session;
					$crseDetails = $this->CourseMapping->getBatchAcademicProgramFromCmId($cm_id);
					//pr($crseDetails); die;
					$array['course_code'] = $crseDetails[0]['Course']['course_code'];
					$array['course_name'] = $crseDetails[0]['Course']['course_name'];
					
					$bAcademic = "";
					if ($crseDetails[0]['Batch']['academic'] == "JUN") $bAcademic = " [A]";
					
					$array['batch'] = $crseDetails[0]['Batch']['batch_from']."-".$crseDetails[0]['Batch']['batch_to'].$bAcademic;
					$array['program'] = $crseDetails[0]['Program']['Academic']['short_code'];
					$array['specialization'] = $crseDetails[0]['Program']['short_code'];
					if ($cmValue['type'] == "A") {
						$noOfUsers = $this->fail_count($cm_id, $exam_month_year_id);
					}
					else if ($cmValue['type'] == "R") {
						$arrQuery = "SELECT count( DISTINCT CourseStudentMapping.student_id ) AS NoOfUsers
							FROM course_student_mappings CourseStudentMapping
							JOIN students Student ON Student.discontinued_status =0
							JOIN course_mappings CourseMapping ON CourseMapping.id = CourseStudentMapping.course_mapping_id
							WHERE Student.discontinued_status =0
							AND CourseStudentMapping.indicator = 0
							AND CourseMapping.id =".$cm_id;
						$cnt = $this->StudentMark->query($arrQuery);
						$noOfUsers = $cnt[0][0]['NoOfUsers'];
					}
					$array['count'] = $noOfUsers;
					//if ($courseId == 1048) die;
					
					array_push($this->timetableArray, $array); */
					/*
					 * Code place timetable ends here
					 */
				}
				$this->datesAssigned[$batch_duration][$examDate] = $commaValue;
				$this->commonDatesAssigned[$batch_duration][] = $examDate;
				
				//pr($this->datesAssigned);
				//pr($this->commonDatesAssigned);
				
			}
		}
		//pr($timetableDatesAssigned);
	}
	
	public function storeUnGroupedTimetableDates($groupedArray, $timetableDates, $daysDiff, $batch_duration, $exam_month_year_id, $grpValue, $exam_type) {
		//echo "UnGrouped Regular Array";  
		$daysDiff = $daysDiff + 1;
		//$lastDateAssigned = array();
		foreach ($groupedArray as $k => $crseIdArray) {
			foreach ($crseIdArray as $courseId => $cmArray) {
				foreach ($cmArray as $cm_id => $cmValue) {
					if ($grpValue == "Y") $grpVar = "GR"; else $grpVar = "UR";
					$this->courseIdArray[$exam_type][$grpVar][$courseId][$cm_id]=$cmValue['program_id'];
				}
			}
		}
		
		$found = 0;
		$commaValue = "";
		
		foreach ($groupedArray as $k => $crseIdArray) {
			foreach ($crseIdArray as $courseId => $cmArray) {
				foreach ($cmArray as $cm_id => $cmValue) {
					//Date selection starts here
					if (isset($this->timetableDatesAssigned[$cmValue['batch_id']][$cmValue['program_id']])) {
						if (count($this->timetableDatesAssigned[$cmValue['batch_id']][$cmValue['program_id']]) > 0) {
							//echo "</br>Data already available";
							$tmpDateArray = $this->timetableDatesAssigned[$cmValue['batch_id']][$cmValue['program_id']];
							//pr($tmpDateArray);
								
							sort($tmpDateArray);
							//pr($tmpDateArray);
							$endDate = end($tmpDateArray);
							//echo $endDate; 
							$bKey = array_keys($timetableDates, $endDate);
							//echo "bKey ";
							//pr($bKey);
							$nextKey = $bKey[0] + $daysDiff;
							//echo "Prev Key ".$prevKey;
							//pr($timetableDates);
							if (isset($timetableDates[$nextKey])) {
								$examDate = $timetableDates[$nextKey];
								$tmpdatediff = strtotime($examDate) - strtotime($endDate);
								$datediff = (int)floor($tmpdatediff / (60 * 60 * 24));
								//echo "</br>".$examDate;
								//echo "Diff : ".$datediff;
								if ($datediff >= 3) {
									$tKey = $nextKey - 1;
									$dat=$timetableDates[$tKey];
									if(in_array($dat, $timetableDates) && $examDate!=$dat) {
										$examDate = $dat;
									}
								}
							}
							else {
								//echo "OOOOOOPS ".$cmValue['batch_id']." ".$cmValue['program_id']." ".$cm_id." ".$examDate;
								$tmpDateArray = $this->timetableDatesAssigned[$cmValue['batch_id']][$cmValue['program_id']];
								//pr($tmpDateArray);
								
								sort($tmpDateArray);
								
								reset($tmpDateArray);
								$first = current($tmpDateArray);
								//pr($first);
								$startDate = current($tmpDateArray);
								//echo $endDate;
								//pr($timetableDates);
								$cKey = array_keys($timetableDates, $startDate);
								$prevKey = $cKey[0] - $daysDiff;
								//echo "Prev Key ".$prevKey;
								$examDate = $timetableDates[$prevKey];
							}
						}
					}
					else {
						if ($batch_duration % 5 == 0 || $batch_duration % 4 == 0) $examDate = $timetableDates[1];
						else if ($batch_duration % 3 == 0) $examDate = $timetableDates[4];
						else if ($batch_duration % 2 == 0) $examDate = $timetableDates[10];
						else if ($batch_duration % 1 == 0) $examDate = $timetableDates[15];
					}
					$this->timetableDatesAssigned[$cmValue['batch_id']][$cmValue['program_id']][$cm_id] = $examDate;
					$this->allCoursesArray[$courseId][]= array(
							'batch_id'=>$cmValue['batch_id'],
							'program_id'=>$cmValue['program_id'],
							'cm_id' => $cm_id,
							'exam_date' => $examDate,
							'exam_session' => 'FN',
							'month_year_id' => $exam_month_year_id,
							'exam_type' => $exam_type
					);
					//Date selection ends here
				}
				$this->datesAssigned[$batch_duration][$examDate] = $commaValue;
				$this->commonDatesAssigned[$batch_duration][] = $examDate;
		
			}
		}
		
	}
	
	public function downloadTimetableAsExcel($results, $exam_month_year_id) { 
		$phpExcel = new PHPExcel();
		$phpExcel->setActiveSheetIndex(0);
		$sheet = $phpExcel->getActiveSheet();
		$sheet->getRowDimension('1')->setRowHeight('18');
		$sheet->setTitle("TIMETABLE_".$this->MonthYear->getMonthYear($exam_month_year_id));
	
		$i=0;
		$array = array();
		foreach ($results as $courseId => $resArray) {
			foreach ($resArray as $key => $result) {
				$cm_id = $result['cm_id'];
				
				$array[$i]['month_year_id'] = $exam_month_year_id;
				$array[$i]['course_id'] = $courseId;
				$array[$i]['course_mapping_id'] = $cm_id;
				$array[$i]['exam_type'] = $result['exam_type'];
				$array[$i]['exam_date'] = $result['exam_date'];
				$array[$i]['exam_session'] = $result['exam_session'];
				$crseDetails = $this->CourseMapping->getBatchAcademicProgramFromCmId($cm_id);
				$array[$i]['course_code'] = $crseDetails[0]['Course']['course_code'];
				$array[$i]['course_name'] = $crseDetails[0]['Course']['course_name'];
				$array[$i]['semester_id'] = $crseDetails[0]['CourseMapping']['semester_id'];
				
				$bAcademic = "";
				if ($crseDetails[0]['Batch']['academic'] == "JUN") $bAcademic = " [A]";
				
				$array[$i]['batch'] = $crseDetails[0]['Batch']['batch_from']."-".$crseDetails[0]['Batch']['batch_to'].$bAcademic;
				$array[$i]['program'] = $crseDetails[0]['Program']['Academic']['short_code'];
				$array[$i]['specialization'] = $crseDetails[0]['Program']['short_code'];
				
				$noOfUsers = 0;
				if ($result['exam_type'] == "A") {
					$noOfUsers = $this->fail_count($cm_id, $exam_month_year_id);
				}
				else if ($result['exam_type'] == "R") {
					$arrQuery = "SELECT count( DISTINCT CourseStudentMapping.student_id ) AS NoOfUsers
						FROM course_student_mappings CourseStudentMapping
						JOIN students Student ON Student.discontinued_status =0
						JOIN course_mappings CourseMapping ON CourseMapping.id = CourseStudentMapping.course_mapping_id
						WHERE Student.discontinued_status =0
						AND CourseStudentMapping.indicator = 0
						AND CourseMapping.id =".$cm_id;
					$cnt = $this->StudentMark->query($arrQuery);
					$noOfUsers = $cnt[0][0]['NoOfUsers'];
				}
				$array[$i]['count'] = $noOfUsers;
				$i++;
			}
		}
		//pr($array);
		
		$sheet->setCellValue("A1", "EXAM DATE");
		$sheet->setCellValue("B1", "BATCH");
		$sheet->setCellValue("C1", "PROGRAM");
		$sheet->setCellValue("D1", "SPECIALISATION");
		$sheet->setCellValue("E1", "COURSE CODE");
		$sheet->setCellValue("F1", "COURSE NAME");
		$sheet->setCellValue("G1", "SEMESTER");
		$sheet->setCellValue("H1", "EXAM SESSION");
		$sheet->setCellValue("I1", "EXAM TYPE");
		$sheet->setCellValue("J1", "COUNT");
		$sheet->setCellValue("K1", "CM_ID");
		$i=2;
		foreach ($array as $key => $result) {
			//foreach ($resArray as $key => $result) {
				$sheet->getRowDimension($i)->setRowHeight('18');
				$sheet->setCellValue('A'.$i, $result['exam_date']);
					
				$sheet->setCellValue('B'.$i, $result['batch']);
				$sheet->setCellValue('C'.$i, $result['program']);
				$sheet->setCellValue('D'.$i, $result['specialization']);
				$sheet->setCellValue('E'.$i, $result['course_code']);
				$sheet->setCellValue('F'.$i, $result['course_name']);
				$sheet->setCellValue('G'.$i, $result['semester_id']);
				$sheet->setCellValue('H'.$i, $result['exam_session']);
				$sheet->setCellValue('I'.$i, $result['exam_type']);
				$sheet->setCellValue('J'.$i, $result['count']);
				$sheet->setCellValue('K'.$i, $result['course_mapping_id']);
			$i++;
			//}
		}
	
		$download_filename="TIMETABLE_".$this->MonthYear->getMonthYear($exam_month_year_id)."_".date( "Y-m-d H:i:s", strtotime(date("Y-m-d H:i:s"))+(3*60*60)+(30*60));
		header("Content-Type: application/vnd.ms-excel");
		header("Content-Disposition: attachment; filename=\"$download_filename.xls\"");
		header("Cache-Control: max-age=0");
		$objWriter = PHPExcel_IOFactory::createWriter($phpExcel, "Excel5");
		$objWriter->save("php://output");
		exit;
	}
	
	public function examDateSelection($timetableDates, $timetableDatesAssigned, $daysDiff, $batch_duration) {
		//pr($timetableDates);
		if (empty($this->lastDateAssigned)) {
			if ($batch_duration % 2 == 0) {
				$examDate = $timetableDates[1];
			}
			else {
				$examDate = $timetableDates[2];
			}
			$this->lastDateAssigned[]=$examDate;
			return $examDate;
		}
		else {
			//pr($this->lastDateAssigned);
			$prvDate = $this->lastDateAssigned[count($this->lastDateAssigned)-1];
			$aKey = array_keys($timetableDates, $prvDate);
			//pr($aKey);
			$nextKey = $aKey[0] + $daysDiff;
			$nextDate = $timetableDates[$nextKey];
			$this->lastDateAssigned[]=$nextDate;
			//echo $nextDate;
			return $nextDate;
		}
	}
	
	public function dateRange($startDate, $endDate) {
		$array = array();
		$interval = new DateInterval('P1D');
	
		$realEnd = new DateTime($endDate);
		$realEnd->add($interval);
	
		$period = new DatePeriod(new DateTime($startDate), $interval, $realEnd);
	
		foreach($period as $date) {
			$array[] = $date->format('Y-m-d');
		}
		//pr($array);
		return $array;
	}
	
	public function loopProgramId($val) { //pr($val);
		$pgmToCheck = array();
		foreach ($val as $cmId => $innerArray) { //pr($innerArray);
			//list($semesterId, $examType, $programId, $batchId) = explode(",", $innerValue);
			$pgmToCheck[$innerArray['program_id']]=$innerArray['program_id'];
		}
		return $pgmToCheck;
	}
	
	/* public function checkIfExamDateIsAvailable ($programArray, $pgmToCheck, $examDate, $daysDiff) {
	 //pr($pgmToCheck);
	 if(empty($programArray)) return 0;
	 foreach ($pgmToCheck as $programId) {
	 foreach ($programArray as $pId => $previousExamDate) {
	 if ($programId == $pId && $examDate == $previousExamDate) {
	 return 1;
	 }
	 else {
	 return 0;
	 }
	 }
	 }
	 } */
	
	/* public function checkDaysDiff($programArray, $pgmToCheck, $examDate, $daysDiff) {
	 $bool = 1;
	 if (empty($programArray)) {
	 return $bool;
	 }
	 foreach ($pgmToCheck as $programId) {
	 foreach ($programArray as $pId => $previousExamDate) {
	 if ($programId == $pId) {
	 $date1 = new DateTime($previousExamDate);
	 $date2 = new DateTime($examDate);
	 $interval = $date1->diff($date2);
	 $diff = $interval->d;
	 if ($diff > $daysDiff) {
	 return $bool;
	 }
	 else {
	 $bool = 0;
	 return $bool;
	 }
	 }
	 else {
	 return $bool;
	 }
	 }
	 }
	 } */
	
	public function common_code() {
		$ContinuousAssessmentExamsController = new ContinuousAssessmentExamsController;
		$monthyears = $ContinuousAssessmentExamsController->findMonthYear();
	
		$this->set(compact('monthyears'));
	}
	
	public function getTimetableData($exam_month_year_id) {
		$result = array(
				'conditions'=>array('Timetable.month_year_id'=>$exam_month_year_id),
				'fields' =>array('Timetable.id', 'Timetable.exam_type'),
				'contain'=>array(
						'CourseMapping'=>array('fields'=>array('CourseMapping.id',  'CourseMapping.program_id',
								'CourseMapping.batch_id'),
								'Course'=>array('fields' =>array('Course.course_code', 'Course.course_name', 'Course.course_type_id')),
								'Batch'=>array('fields' =>array('Batch.batch_from', 'Batch.batch_to')),
						),
				),
				'recursive' => 1
		);
		$results = $this->Timetable->find("all", $result);
		return $results;
	}
	
	public function processTimetableData($results) {
		$commonCodeArray = array();
		//	echo count($results);
	
		foreach ($results as $key => $result) {
			$tmpArray = array();
			$tmpArray['timetable_id'] = $result['Timetable']['id'];
			$tmpArray['exam_type'] = $result['Timetable']['exam_type'];
			$tmpArray['cm_id'] = $result['CourseMapping']['id'];
			$tmpArray['batch_id'] = $result['CourseMapping']['batch_id'];
			$tmpArray['batch_duration'] = $result['CourseMapping']['Batch']['batch_to']-$result['CourseMapping']['Batch']['batch_from'];
			$tmpArray['program_id'] = $result['CourseMapping']['program_id'];
			$tmpArray['course_id'] = $result['CourseMapping']['course_id'];
			$tmpArray['course_code'] = $result['CourseMapping']['Course']['course_code'];
			$tmpArray['course_name'] = $result['CourseMapping']['Course']['course_name'];
			$tmpArray['course_type_id'] = $result['CourseMapping']['Course']['course_type_id'];
			array_push($commonCodeArray, $tmpArray);
		}
		return $commonCodeArray;
	}
	
	public function common_code_report($exam_month_year_id) {
		$results = $this->getTimetableData($exam_month_year_id);
		//		pr($results);
	
		$commonCodeArray = $this->processTimetableData($results);
		//echo count($commonCodeArray);
		//pr($commonCodeArray);
	
		$groupedCommonCodeArray = $this->array_MultiOrderBy($commonCodeArray, 'course_code', SORT_ASC);
		//echo count($groupedCommonCodeArray);
	
		$groupedArray = $this->group_assoc($groupedCommonCodeArray, 'course_code');
	
		foreach ($groupedArray as $course_code => $tmpValue) {
			$batchGroupArray[$course_code] = $this->group_assoc($tmpValue, 'batch_duration');
		}
		//pr($batchGroupArray);
		$finalArray = $this->noAppeared($batchGroupArray, $exam_month_year_id);
		//pr($finalArray);
		$this->layout = false;
		$this->set(compact('finalArray'));
	}
	
	public function noAppeared($groupedArray, $exam_month_year_id) {
		$finalArray = array();
		foreach ($groupedArray as $course_code => $value) {
			foreach ($value as $batch_duration => $val) {
				$total = 0;
				$totalAppeared = 0;
				$pass_count = 0;
				$start_range = '';
				$end_range = '';
				foreach ($val as $key => $array) {
					//pr($array);
					$tmpTotal = $this->ExamAttendance->find('count', array(
							'conditions'=>array('ExamAttendance.timetable_id'=>$array['timetable_id'])
					));
					$total = $total+$tmpTotal;
						
					$tmpTotalAppeared = $this->ExamAttendance->find('count', array(
							'conditions'=>array('ExamAttendance.timetable_id'=>$array['timetable_id'],
									'ExamAttendance.attendance_status'=>1
							)
					));
					$totalAppeared = $totalAppeared+$tmpTotalAppeared;
						
					$draResult = $this->Timetable->DummyRangeAllocation->find('all', array(
							'conditions'=>array('DummyRangeAllocation.timetable_id'=>$array['timetable_id']),
							'fields'=>array('DummyRangeAllocation.dummy_number_id'),
							'contain'=>array(
									'DummyNumber'=>array(
											'fields'=>array('DummyNumber.start_range', 'DummyNumber.end_range')
									)
							)
					));
					//pr($draResult);
					if(isset($draResult) && !empty($draResult)) {
						$start_range = $draResult[0]['DummyNumber']['start_range'];
						$end_range = $draResult[0]['DummyNumber']['end_range'];
					}
						
					$tmp_pass_count = $this->pass_count($array['cm_id'], $exam_month_year_id);
						
					$pass_count = $pass_count+$tmp_pass_count;
					//if ($array['course_code'] == 'SCH1101') {
					//echo "</br>".$array['exam_type']." ".$array['timetable_id']." ".$array['batch_id']." ".$array['program_id']." ".$array['cm_id']." ".$course_code." <b>".$tmpTotal."</b> ".$total." <b>".$tmpTotalAppeared."</b> ".$totalAppeared." <b>".$tmp_pass_count."</b> ".$pass_count;
					//}
				}
				$finalArray[$array['course_code']][$batch_duration] = array(
						'totalStrength'=>$total,
						'totalAppeared'=>$totalAppeared,
						'totalPass'=>$pass_count,
						'course_name'=>$array['course_name'],
						'start_range'=>$start_range,
						'end_range'=>$end_range,
				);
				//echo "</br>";
			}
		}
		//echo count($finalArray);
		return $finalArray;
	}
	
	public function pass_count($cmId, $monthYearId) {
		$pass_result = $this->StudentMark->query("
				SELECT count(*) as pass_count FROM student_marks StudentMark JOIN students Student ON StudentMark.student_id = Student.id WHERE StudentMark.id
				IN (SELECT max( id ) FROM student_marks sm1 WHERE StudentMark.student_id = sm1.student_id AND
				sm1.month_year_id = $monthYearId GROUP BY sm1.course_mapping_id ORDER BY sm1.id DESC)
				AND ((StudentMark.status = 'Pass' AND StudentMark.revaluation_status =0) OR
				(StudentMark.final_status = 'Pass' AND StudentMark.revaluation_status =1))
				AND StudentMark.month_year_id = $monthYearId
				AND StudentMark.course_mapping_id = ".$cmId."
				AND Student.discontinued_status=0
				ORDER BY StudentMark.student_id ASC
		");
		$tmp_pass_count = $pass_result[0][0]['pass_count'];
		return $tmp_pass_count;
	}
	
	public function fail_count($cmId, $monthYearId) {
		$pass_result = $this->StudentMark->query("
				SELECT count(*) as fail_count FROM student_marks StudentMark JOIN students Student ON StudentMark.student_id = Student.id WHERE StudentMark.id
				IN (SELECT max( id ) FROM student_marks sm1 WHERE StudentMark.student_id = sm1.student_id AND
				sm1.month_year_id < $monthYearId GROUP BY sm1.course_mapping_id ORDER BY sm1.id DESC)
				AND ((StudentMark.status = 'Fail' AND StudentMark.revaluation_status =0) OR
				(StudentMark.final_status = 'Fail' AND StudentMark.revaluation_status =1))
				AND StudentMark.month_year_id < $monthYearId
				AND StudentMark.course_mapping_id = ".$cmId."
				AND Student.discontinued_status=0
				ORDER BY StudentMark.student_id ASC
		");
		$tmp_fail_count = $pass_result[0][0]['fail_count'];
		return $tmp_fail_count;
	}
	
	public function group_assoc($array, $key) {
		$return = array();
		foreach($array as $v) {
			$return[$v[$key]][] = $v;
		}
		return $return;
	}
	
	public function programWise() {
		$academics = $this->Student->Academic->find('list');
		$batches = $this->Student->Batch->find('list', array('fields' => array('Batch.batch_period'), 'order'=>'Batch.id DESC'));
	
		$monthYears = $this->MonthYear->getAllMonthYears();
	
		$action = $this->action;
		$this->set(compact('batches', 'academics', 'programs', 'monthYears'));
	}
	
	public function program_wise_report($batchId, $academicId, $programId, $monthYearId) {
		$res = array(
				'conditions'=>array('CourseMapping.month_year_id'=>$monthYearId, 'CourseMapping.batch_id'=>$batchId,
						'CourseMapping.program_id'=>$programId,
						'CourseMapping.indicator'=>0
				),
				'fields' =>array('CourseMapping.id', 'CourseMapping.month_year_id'),
				'contain'=>array(
						'Timetable'=>array(
								'fields'=>array('Timetable.id'),
								'conditions'=>array('Timetable.month_year_id'=>$monthYearId),
								'ExamAttendance'=>array(
										'fields'=>array('ExamAttendance.id', 'ExamAttendance.attendance_status')
								),
						),
						'MonthYear'=>array(
								'fields' => array('MonthYear.year'),
								'Month' => array('fields' => array('Month.month_name'))
						),
						'EsePractical'=> array(
								'conditions'=>array('EsePractical.indicator'=>0,),
								'fields'=>array('EsePractical.id'),
								'Practical'=> array(
										'fields'=>array('Practical.id', 'Practical.marks'),
								)
						),
						'EseProject'=> array(
								'conditions'=>array('EseProject.indicator'=>0,),
								'fields'=>array('EseProject.id'),
								'ProjectViva'=> array(
										'fields'=>array('ProjectViva.id', 'ProjectViva.marks'),
								)
						),
						'CaePt'=> array(
								'conditions'=>array('CaePt.indicator'=>0,),
								'fields'=>array('CaePt.id'),
								'ProfessionalTraining'=> array(
										'fields'=>array('ProfessionalTraining.id', 'ProfessionalTraining.marks'),
										'conditions'=>array('ProfessionalTraining.month_year_id'=>$monthYearId)
								)
						),
				),
		);
		$results = $this->CourseMapping->find("all", $res);
		//pr($results); die;
	
		$finalArray = array();
		foreach ($results as $key => $result) {
			//pr($result);
				
			$course_details = $this->getCourseNameCrseCodeFromCMId($result['CourseMapping']['id']);
			//pr($course_details);
			$course_type_id = $course_details[0]['Course']['course_type_id'];
			$attArray = array();
				
			SWITCH ($course_type_id) {
				case 1:
					$totalStrength = count($result['Timetable'][0]['ExamAttendance']);
					$attArray = $result['Timetable'][0]['ExamAttendance'];
					//pr($attArray);
					$totAppeared = array_filter($attArray, array($this, 'present'));
					$totalAppeared = count($totAppeared);
					break;
				case 2:
				case 3:
				case 6:
					$absentee = 0;
					$totalStrength = count($result['EsePractical'][0]['Practical']);
					$attArray = $result['EsePractical'][0]['Practical'];
					foreach ($attArray as $key => $value) {
						if ($value['marks']=='A' || $value['marks']=='a' || $value['marks']=='AAA' || $value['marks']=='aaa') {
							$absentee++;
						}
					}
					$totalAppeared = $totalStrength - $absentee;
					break;
				case 4:
					$absentee = 0;
					$totalStrength = count($result['EseProject'][0]['ProjectViva']);
					$attArray = $result['EseProject'][0]['ProjectViva'];
					foreach ($attArray as $key => $value) {
						if ($value['marks']=='A' || $value['marks']=='a' || $value['marks']=='AAA' || $value['marks']=='aaa') {
							$absentee++;
						}
					}
					$totalAppeared = $totalStrength - $absentee;
					break;
				case 5:
					$absentee = 0;
					$totalStrength = count($result['CaePt']['ProfessionalTraining']);
					$attArray = $result['CaePt']['ProfessionalTraining'];
					foreach ($attArray as $key => $value) {
						if ($value['marks']=='A' || $value['marks']=='a' || $value['marks']=='AAA' || $value['marks']=='aaa') {
							$absentee++;
						}
					}
					$totalAppeared = $totalStrength - $absentee;
					break;
			}
			$tmp_pass_count = $this->pass_count($result['CourseMapping']['id'], $monthYearId);
			$passPercentage = round((100 * $tmp_pass_count) / $totalAppeared, 0);
			$finalArray[$result['CourseMapping']['id']] = array(
					'totalStrength'=>$totalStrength,
					'totalAppeared'=>$totalAppeared,
					'totalPass'=>$tmp_pass_count,
					'passPercent'=>$passPercentage,
					'course_code'=>$course_details[0]['Course']['course_code'],
					'course_name'=>$course_details[0]['Course']['course_name'],
					'month_year'=>$result['MonthYear']['Month']['month_name']."-".$result['MonthYear']['year'],
					'course_type_id'=>$course_type_id
			);
		}
		//pr($finalArray);
		$this->set(compact('finalArray'));
		$this->layout = false;
	}
	
	public function present($var) {
		return (is_array($var) && $var['attendance_status'] == 1);
	}
	
	public function course_search($examMonthYear=NULL) {
		$results = $this->getTimetableData($examMonthYear);
		//pr($results);
	
		$unOrderedArray = $this->processTimetableData($results);
		//echo count($commonCodeArray);
		//pr($unOrderedArray);
	
		$orderedArray = $this->array_MultiOrderBy($unOrderedArray, 'course_code', SORT_ASC);
		//echo count($orderedArray);
		//pr($orderedArray);
	
		$groupedArray = $this->group_assoc($orderedArray, 'course_code');
		//echo count($groupedArray);
		//pr($groupedArray);
		//pr($groupedArray);
	
		$courseArray = array();
		foreach ($groupedArray as $course_code => $array) {
			$courseArray[$array[0]['course_id']]=$course_code;
		}
		//echo count($courseArray)."*";
		/* $results = $this->StudentMark->query("
		SELECT sm.id, sm.course_mapping_id, sm.month_year_id, sm.student_id, s.registration_number, c.course_code,
		c.course_type_id, s.batch_id, s.program_id, cm.batch_id, cm.program_id, cm.month_year_id, cm.semester_id, c.id,
		b.batch_from, b.batch_to
		FROM student_marks sm
		JOIN students s ON sm.student_id=s.id
		JOIN course_mappings cm ON sm.course_mapping_id=cm.id
		JOIN courses c ON cm.course_id=c.id
		JOIN batches b ON b.id=cm.batch_id
		WHERE sm.id IN
		(SELECT max( id ) FROM student_marks sm1 WHERE sm.student_id =sm1.student_id
		AND sm1.month_year_id <= $examMonthYear
		GROUP BY course_mapping_id, sm1.student_id ORDER BY id DESC)
		AND ((sm.status='Fail' AND sm.revaluation_status=0) OR (sm.final_status='Fail' AND sm.revaluation_status=1))
		AND c.course_type_id in (2,3,4,6)
		AND sm.month_year_id <= $examMonthYear
		AND s.discontinued_status = 0
		ORDER BY sm.course_mapping_id  ASC");
		//pr($results);
		foreach ($results as $key => $array) {
		//echo $array['c']['course_code']."*".$array['c']['id']."@";
		$courseArray[$array['c']['id']]=$array['c']['course_code']." ".$array['c']['course_type_id'];
		} */
		asort($courseArray);
		//echo count($courseArray);
		$this->set('courseArray', $courseArray);
		$this->layout=false;
	}
	
	public function daywiseStrengthReport() {
		$monthyears = $this->MonthYear->getAllMonthYears();
		$this->set(compact('monthyears'));
		if($this->request->is('post')) {
			//pr($this->data);
			$month_year_id = $this->request->data['Timetable']['month_year_id'];
			$this->daywiseReport($month_year_id);
		}
	}
	
	public function daywiseReport($month_year_id) {
		$query = $this->Timetable->query("
				SELECT Timetable.id, Course.course_code, Batch.id as batch_id,
				Timetable.month_year_id, Timetable.course_mapping_id, Timetable.exam_date, Timetable.exam_session,
				Timetable.exam_type
				FROM `timetables` Timetable
				JOIN course_mappings CourseMapping ON Timetable.course_mapping_id = CourseMapping.id
				JOIN courses Course ON CourseMapping.course_id = Course.id
				JOIN batches Batch ON Batch.id = CourseMapping.batch_id
				WHERE Timetable.month_year_id = $month_year_id AND Timetable.indicator = 0  
				ORDER BY Timetable.exam_date ASC, Timetable.exam_date DESC, Course.course_code ASC
				");
		/* echo "SELECT Course.course_code, Batch.id as batch_id,
		 Timetable.month_year_id, Timetable.course_mapping_id, Timetable.exam_date, Timetable.exam_session,
		 Timetable.exam_type, Timetable.id
		 FROM `timetables` Timetable
		 JOIN course_mappings CourseMapping ON Timetable.course_mapping_id = CourseMapping.id
		 JOIN courses Course ON CourseMapping.course_id = Course.id
		 JOIN batches Batch ON Batch.id = CourseMapping.batch_id
		 WHERE Timetable.month_year_id = $month_year_id AND Timetable.indicator = 0
		 ORDER BY Timetable.exam_date, Course.course_code"; */
	
		//pr($query);
		$array = array();
		foreach ($query as $key => $value) {
			$array[$key]['course_code'] = $value['Course']['course_code'];
			$array[$key]['month_year_id'] = $value['Timetable']['month_year_id'];
			$array[$key]['cm_id'] = $value['Timetable']['course_mapping_id'];
			$array[$key]['exam_date'] = $value['Timetable']['exam_date'];
			$array[$key]['exam_session'] = $value['Timetable']['exam_session'];
			$array[$key]['exam_type'] = $value['Timetable']['exam_type'];
			$array[$key]['batch'] = $this->Batch->getBatch($value['Batch']['batch_id']);
			$details = $this->CourseMapping->getBatchAcademicProgramFromCmId($value['Timetable']['course_mapping_id']);
			//pr($details);
			$academic = "";
			$program = "";
				
			if (isset($details[0])) {
				$academic = $details[0]['Program']['Academic']['short_code'];
				$program = $details[0]['Program']['short_code'];
				$course_name = $details[0]['Course']['course_name'];
			}
			$array[$key]['academic'] = $academic;
			$array[$key]['program'] = $program;
			$array[$key]['course_name'] = $course_name;
				
			//echo $value['Timetable']['id']." ".$value['Timetable']['exam_type'];
			if ($value['Timetable']['exam_type'] == "A") {
				$arrQuery = "SELECT count(StudentMark.student_id) AS NoOfUsers
					FROM student_marks StudentMark join students Student on StudentMark.student_id=Student.id
					WHERE Student.discontinued_status=0 and StudentMark.id IN
					(SELECT max( id ) FROM `student_marks` where student_marks.course_mapping_id = StudentMark.course_mapping_id
					GROUP BY student_marks.course_mapping_id, student_marks.student_id ORDER BY student_marks.id DESC)
					AND ((StudentMark.status='Fail' AND StudentMark.revaluation_status=0)
					OR (StudentMark.final_status='Fail' AND StudentMark.revaluation_status=1))
					AND StudentMark.course_mapping_id=".$value['Timetable']['course_mapping_id'];
				$cnt = $this->StudentMark->query($arrQuery);
			}
			else if ($value['Timetable']['exam_type'] == "R") {
				$arrQuery = "SELECT count( DISTINCT CourseStudentMapping.student_id ) AS NoOfUsers
							FROM course_student_mappings CourseStudentMapping
							JOIN students Student ON Student.id = CourseStudentMapping.student_id 
							JOIN course_mappings CourseMapping ON CourseMapping.id = CourseStudentMapping.course_mapping_id
							WHERE Student.discontinued_status =0
							AND CourseStudentMapping.indicator = 0
							AND CourseMapping.id =".$value['Timetable']['course_mapping_id'];
				$cnt = $this->StudentMark->query($arrQuery);
			}
			$noOfUsers = $cnt[0][0]['NoOfUsers'];
			$array[$key]['count'] = $noOfUsers;
		}
		//pr($array);
		$this->displayDaywiseReport($array);
	}
	
	public function displayDaywiseReport($array) {
		//pr($array);
		$phpExcel = new PHPExcel();
		$phpExcel->setActiveSheetIndex(0);
		$sheet = $phpExcel->getActiveSheet();
			
		$row = 1; // 1-based index
		$col = 0;
			
		$sheet->getRowDimension('1')->setRowHeight('18');
		$sheet->getColumnDimension('B')->setWidth(12);
		$sheet->getColumnDimension('C')->setWidth(12);
		$sheet->getColumnDimension('D')->setWidth(30);
		$sheet->getColumnDimension('E')->setWidth(10);
		$sheet->getColumnDimension('F')->setWidth(10);
		$sheet->getColumnDimension('G')->setWidth(10);
		$sheet->getColumnDimension('H')->setWidth(10);
		$sheet->getColumnDimension('I')->setWidth(10);
		$sheet->getColumnDimension('J')->setWidth(10);
		$sheet->getColumnDimension('K')->setWidth(10);
	
		$sheet->setTitle("TimetableDayWiseReport");
			
		$sheet->setCellValueByColumnAndRow($col, $row, "EXAM DATE");$col++;
		$sheet->setCellValueByColumnAndRow($col, $row, "SESSION");$col++;
		$sheet->setCellValueByColumnAndRow($col, $row, "COURSE CODE");$col++;
		$sheet->setCellValueByColumnAndRow($col, $row, "COURSE NAME");$col++;
		$sheet->setCellValueByColumnAndRow($col, $row, "BATCH");$col++;
		$sheet->setCellValueByColumnAndRow($col, $row, "PROGRAMME");$col++;
		$sheet->setCellValueByColumnAndRow($col, $row, "SPECIALISATION");$col++;
		$sheet->setCellValueByColumnAndRow($col, $row, "STRENGTH");$col++;
		$sheet->setCellValueByColumnAndRow($col, $row, "CODE STRENGTH");$col++;
		$sheet->setCellValueByColumnAndRow($col, $row, "DAY STRENGTH");$col++;
			
		$row++;
		$row = 2;
	
		$cmIdArray = array();
		$courseCodeArray = array();
		$commonCodeArray = array();
		$dayArray = array();
		$courseCodeAllocatedArray = array();
		$dayAllocatedArray = array();
	
		foreach ($array as $key => $result) {
			$courseCodeArray[$result['exam_date']][$result['course_code']][] = $result['course_code'];
			$courseCodeAllocatedArray[$result['exam_date']][$result['course_code']] = 0;
			$commonCodeArray[$result['exam_date']][$result['course_code']][] = $result['count'];
			$dayArray[$result['exam_date']][]= $result['count'];
			$dayAllocatedArray[$result['exam_date']] = 0;
		}
	
		foreach ($array as $key => $result) {
			$sheet->getRowDimension($row)->setRowHeight('18');
			$col = 0;
			$rowTo = "";
			$dayTo = "";
				
			$sheet->setCellValueByColumnAndRow($col, $row, $result['exam_date']);$col++;
			$sheet->setCellValueByColumnAndRow($col, $row, $result['exam_session']);$col++;
			$sheet->setCellValueByColumnAndRow($col, $row, $result['course_code']);$col++;
			$sheet->setCellValueByColumnAndRow($col, $row, $result['course_name']);$col++;
			$sheet->setCellValueByColumnAndRow($col, $row, $result['batch']);$col++;
			$sheet->setCellValueByColumnAndRow($col, $row, $result['academic']);$col++;
			$sheet->setCellValueByColumnAndRow($col, $row, $result['program']);$col++;
			$sheet->setCellValueByColumnAndRow($col, $row, $result['count']);$col++;
	
			//Code for Common code wise strength count
			$commonCodeColumnToMerge = count($courseCodeArray[$result['exam_date']][$result['course_code']])-1;
			//echo array_count_values($dayArray[$result['exam_date']][$result['course_code']])."</br>";
			if ($commonCodeColumnToMerge >= 1 && !$courseCodeAllocatedArray[$result['exam_date']][$result['course_code']]) {
				$courseCodeSum = array_sum($commonCodeArray[$result['exam_date']][$result['course_code']]);
				$courseCodeAllocatedArray[$result['exam_date']][$result['course_code']] = 1;
				$rowTo = $row+$commonCodeColumnToMerge;
				$sheet->mergeCells(PHPExcel_Cell::stringFromColumnIndex($col).$row.":".PHPExcel_Cell::stringFromColumnIndex($col).$rowTo);
				$sheet->setCellValue(PHPExcel_Cell::stringFromColumnIndex($col).($row), $courseCodeSum);
			}
			else {
				$sheet->setCellValueByColumnAndRow($col, $row, $result['count']);
			}
			$col++;
				
			//Code for day count
			$dayColumnToMerge = count($dayArray[$result['exam_date']])-1;
			$rowTo = "";
			
			if ($dayColumnToMerge >= 1 && !$dayAllocatedArray[$result['exam_date']]) {
				$daySum = array_sum($dayArray[$result['exam_date']]);
				$dayAllocatedArray[$result['exam_date']] = 1;
				$rowTo = $row+$dayColumnToMerge;
				$sheet->mergeCells(PHPExcel_Cell::stringFromColumnIndex($col).$row.":".PHPExcel_Cell::stringFromColumnIndex($col).$rowTo);
				$sheet->setCellValue(PHPExcel_Cell::stringFromColumnIndex($col).($row), $daySum);
			}
			else {
				$sheet->setCellValueByColumnAndRow($col, $row, $result['count']);
			}
			$col++;
			$row++;
		}
		
		$download_filename="TimetableDayWiseReport_".date('d-M-Y h:i:s');
		header("Content-Type: application/vnd.ms-excel");
		header("Content-Disposition: attachment; filename=\"$download_filename.xls\"");
		header("Cache-Control: max-age=0");
		$objWriter = PHPExcel_IOFactory::createWriter($phpExcel, "Excel5");
		$objWriter->save("php://output");
		exit;
	}
	
	public function arrearCount() {
		$monthyears = $this->MonthYear->getAllMonthYears();
		$this->set(compact('monthyears'));
		
		if ($this->request->is('post')) {
			//pr($this->data); 
			$exam_month_year_id = $this->request->data['Arrear']['month_year_id'];
			
		$arrear_results = $this->getArrearDataBasedOnCourseType($exam_month_year_id, Configure::read('CourseType.theory'));
		//RE-ARRANGE THE RESULTSET BASED ON COURSE_MAPPING_ID. THIS IS NOT NECESSARY
		//$cm_id_course_code_array = $this->retriveCmIdCourseCodeFromArrearResults($results);
		/*RE-ARRANGE THE RESULTSET BASED ON COURSE_CODE. USEFUL AS TIMETABLE IS GENERATED BASED ON COMMON COURSE CODE */
		$arrear_course_code_array = $this->retriveCourseCodeCmIdFromArrearResults($arrear_results);
		//pr($arrear_course_code_array);
		$array['StudentMark'] = $arrear_course_code_array;
		//$array['cm_id']['StudentMark'] = $cm_id_course_code_array;
		
		//Step 2: GET ARREAR DATA FROM COURSE_STUDENT_MAPPING TABLE
		$non_arrear_results = $this->getNonArrearDataBasedOnCourseType($exam_month_year_id, Configure::read('CourseType.theory'));
		//RE-ARRANGE THE RESULTSET BASED ON COURSE_MAPPING_ID. THIS IS NOT NECESSARY
		//$cm_id_non_arrear_course_code_array = $this->retriveCmIdCourseCodeFromNonArrearResults($non_arrear_results);
		/*RE-ARRANGE THE RESULTSET BASED ON COURSE_CODE. USEFUL AS TIMETABLE IS GENERATED BASED ON COMMON COURSE CODE */
		$non_arrear_course_code_array = $this->retriveCourseCodeCmIdFromNonArrearResults($non_arrear_results);
		//pr($non_arrear_course_code_array);
		$array['CourseStudentMapping'] = $non_arrear_course_code_array;
		
		foreach ($array as $model => $arr) {
			$course_code_details = $arr['course_code_details'];
			foreach ($course_code_details as $course_code => $tmpval) { //pr($tmpval);
				foreach ($tmpval as $cm_id => $value) {
					/* echo "<tr>";
					 echo "<td>".$model."</td><td>".$course_code."</td><td>".$cm_id."</td><td>".$value['batch_id']."</td>
					 <td>".$value['program_id']."</td><td>".$value['course_id']."</td><td>".$value['month_year_id']."</td>";
					 echo "</tr>"; */
					if ($value['month_year_id'] == $exam_month_year_id) $type="R"; else $type="A";
					/* if ($value['month_year_id'] == $exam_month_year_id) {
					 $details['Regular'][$value['batch_duration']][$value['program_id']][$value['course_id']] = $cm_id.",".$value['semester_id'];
					 } else {
					 $details['Arrear'][$value['batch_duration']][$value['program_id']][$value['course_id']] = $cm_id.",".$value['semester_id'];
					 } */
					//$details[$value['batch_duration']][$value['program_id']][$value['course_id']] = $cm_id.",".$value['semester_id'].",".$type;
					//working code
					//$details[$value['batch_duration']][$value['course_id']][$cm_id] = $value['semester_id'].",".$type.",".$value['program_id'].",".$value['batch_id'];
					$semResult = $this->getSemesterIdFromMonthYear($value['batch_id'], $value['program_id'], $exam_month_year_id);
					//pr($semResult);
					$actualSemester = 0;
					if (isset($semResult['CourseMapping']['semester_id'])) {
						$actualSemester = $semResult['CourseMapping']['semester_id'];
					}
					/* $details[$value['batch_duration']][$value['course_id']][$cm_id] = array(
					 'semester_id'=>$value['semester_id'],
					 'current_semester'=>$actualSemester,
					 'type' => $type,
					 'program_id' => $value['program_id'],
					 'batch_id' => $value['batch_id']
					 ); */
					if ($type == 'R') {
						$details[$type][$value['batch_duration']][$actualSemester][$value['course_id']][$cm_id] = array(
								'semester_id'=>$value['semester_id'],
								'current_semester'=>$actualSemester,
								'batch_duration'=>$value['batch_duration'],
								'type' => $type,
								'program_id' => $value['program_id'],
								'batch_id' => $value['batch_id']
						);
					}
					else if ($type == 'A') {
						$arrDetails[$type][$value['batch_duration']][$value['course_id']][$cm_id] = array(
								'semester_id'=>$value['semester_id'],
								'current_semester'=>$actualSemester,
								'batch_duration'=>$value['batch_duration'],
								'type' => $type,
								'program_id' => $value['program_id'],
								'batch_id' => $value['batch_id']
						);
					}
				}
			}
		}
	//	pr($arrDetails);
		$array = array();
		$i=0;
		
		//Code for Practicals
		$Arrears = new ArrearsController();
		$course_mapping_array = $Arrears->practicalArrearData($exam_month_year_id);
		//pr($course_mapping_array);
		
		foreach ($course_mapping_array as $cm_id => $cmValue) { //pr($cmArray);
			$details = $this->CourseMapping->getBatchAcademicProgramFromCmId($cm_id);
			//pr($details);
			$noOfUsers = $this->failureCount($cm_id, $exam_month_year_id);
			
			$array[$i]['course_code'] = $details[0]['Course']['course_code'];
			$array[$i]['course_name'] = $details[0]['Course']['course_name'];
			$array[$i]['course_type'] = $details[0]['Course']['CourseType']['course_type'];
			$array[$i]['batch'] = $details[0]['Batch']['batch_from']."-".$details[0]['Batch']['batch_to'];
			$array[$i]['program'] = $details[0]['Program']['Academic']['short_code'];
			$array[$i]['specialization'] = $details[0]['Program']['short_code'];
			if ($details[0]['Batch']['academic'] == "JUN") $array[$i]['academic'] = "TRUE";
			else $array[$i]['academic'] = "FALSE";
			$array[$i]['semester'] = $details[0]['CourseMapping']['semester_id'];
			$array[$i]['strength'] = $noOfUsers;
			$array[$i]['cm_id'] = $cm_id;
			$i++;
			
		}
		//pr($array);
		
		foreach ($arrDetails as $exam_type => $batch_duration_array) {
			foreach ($batch_duration_array as $batch_duration => $crseArray) {
				foreach ($crseArray as $courseId => $cmArray) {
					foreach ($cmArray as $cm_id => $cmValue) {
						//echo $cmValue['batch_id']." ".$cmValue['program_id']." ".$cm_id." * ";
						
						$noOfUsers = $this->failureCount($cm_id, $exam_month_year_id);
						//$array[$key]['count'] = $noOfUsers;
						
						$details = $this->CourseMapping->getBatchAcademicProgramFromCmId($cm_id);
						//pr($details);
						
						$array[$i]['course_code'] = $details[0]['Course']['course_code'];
						$array[$i]['course_name'] = $details[0]['Course']['course_name'];
						$array[$i]['course_type'] = $details[0]['Course']['CourseType']['course_type'];
						$array[$i]['batch'] = $details[0]['Batch']['batch_from']."-".$details[0]['Batch']['batch_to'];
						$array[$i]['program'] = $details[0]['Program']['Academic']['short_code'];
						$array[$i]['specialization'] = $details[0]['Program']['short_code'];
						if ($details[0]['Batch']['academic'] == "JUN") $array[$i]['academic'] = "TRUE";
						else $array[$i]['academic'] = "FALSE";
						$array[$i]['semester'] = $cmValue['semester_id'];
						$array[$i]['strength'] = $noOfUsers;
						$array[$i]['cm_id'] = $cm_id;
						$array[$i]['course_type'] = "Theory";
						$i++;
					}
				}
			}
		}
		//pr($array);
		$this->downloadArrearCountReport($array);
		}
	}
	
	public function failureCount($cm_id, $exam_month_year_id) {
		$noOfUsers = 0;
		$results = $this->StudentMark->query("
				SELECT count(*) as NoOfUsers   
				FROM student_marks StudentMark JOIN students Student ON StudentMark.student_id = Student.id
				JOIN course_mappings CourseMapping ON CourseMapping.id=StudentMark.course_mapping_id 
				JOIN courses Course ON Course.id=CourseMapping.course_id 
				JOIN batches Batch ON Batch.id=Student.batch_id 
				JOIN programs Program ON Program.id = Student.program_id
				JOIN academics Academic ON Academic.id=Program.academic_id 
				WHERE StudentMark.id
				IN (SELECT max( id ) FROM student_marks sm1 WHERE StudentMark.student_id = sm1.student_id AND
				sm1.month_year_id < $exam_month_year_id	GROUP BY sm1.student_id, sm1.course_mapping_id ORDER BY sm1.id DESC)
				AND ((StudentMark.status = 'Fail' AND StudentMark.revaluation_status =0) OR
				(StudentMark.final_status = 'Fail'AND StudentMark.revaluation_status =1))
				AND StudentMark.month_year_id < $exam_month_year_id AND StudentMark.course_mapping_id = $cm_id
				AND Student.discontinued_status=0 
				ORDER BY StudentMark.student_id ASC
				");
		//pr($results);
		$noOfUsers = $results[0][0]['NoOfUsers'];
		return $noOfUsers;
	}
	
	public function downloadArrearCountReport($array) {
		//pr($array);
		$phpExcel = new PHPExcel();
		$phpExcel->setActiveSheetIndex(0);
		$sheet = $phpExcel->getActiveSheet();
			
		$row = 1; // 1-based index
		$col = 0;
			
		$sheet->getRowDimension('1')->setRowHeight('18');
		$sheet->getColumnDimension('B')->setWidth(30);
		$sheet->getColumnDimension('C')->setWidth(12);
		$sheet->getColumnDimension('D')->setWidth(10);
		$sheet->getColumnDimension('E')->setWidth(10);
		$sheet->getColumnDimension('F')->setWidth(10);
		$sheet->getColumnDimension('G')->setWidth(10);
		$sheet->getColumnDimension('H')->setWidth(10);
	
		$sheet->setTitle("TimetableDayWiseReport");
			
		$sheet->setCellValueByColumnAndRow($col, $row, "COURSE CODE");$col++;
		$sheet->setCellValueByColumnAndRow($col, $row, "COURSE NAME");$col++;
		$sheet->setCellValueByColumnAndRow($col, $row, "COURSE TYPE");$col++;
		$sheet->setCellValueByColumnAndRow($col, $row, "BATCH");$col++;
		$sheet->setCellValueByColumnAndRow($col, $row, "PROGRAMME");$col++;
		$sheet->setCellValueByColumnAndRow($col, $row, "SPECIALISATION");$col++;
		$sheet->setCellValueByColumnAndRow($col, $row, "ACADEMIC");$col++;
		$sheet->setCellValueByColumnAndRow($col, $row, "SEMESTER");$col++;
		$sheet->setCellValueByColumnAndRow($col, $row, "STRENGTH");$col++;
		
		$row = 2;
		
		foreach ($array as $key => $result) {
			$sheet->getRowDimension($row)->setRowHeight('18');
			$col = 0;
			$sheet->setCellValueByColumnAndRow($col, $row, $result['course_code']);$col++;
			$sheet->setCellValueByColumnAndRow($col, $row, $result['course_name']);$col++;
			$sheet->setCellValueByColumnAndRow($col, $row, $result['course_type']);$col++;
			$sheet->setCellValueByColumnAndRow($col, $row, $result['batch']);$col++;
			$sheet->setCellValueByColumnAndRow($col, $row, $result['program']);$col++;
			$sheet->setCellValueByColumnAndRow($col, $row, $result['specialization']);$col++;
			$sheet->setCellValueByColumnAndRow($col, $row, $result['academic']);$col++;
			$sheet->setCellValueByColumnAndRow($col, $row, $result['semester']);$col++;
			$sheet->setCellValueByColumnAndRow($col, $row, $result['strength']);$col++;
			$row++;
		}
	
		$download_filename="ArrearCountReport_".date('d_M_Y_h:i:s');
		header("Content-Type: application/vnd.ms-excel");
		header("Content-Disposition: attachment; filename=\"$download_filename.xls\"");
		header("Cache-Control: max-age=0");
		$objWriter = PHPExcel_IOFactory::createWriter($phpExcel, "Excel5");
		$objWriter->save("php://output");
		exit;
	}
	
	public function hallTicketSearch() {
		$monthyears = $this->MonthYear->getAllMonthYears();
		$this->set(compact('monthyears'));
		if($this->request->is('post')) {
			//pr($this->data);
			$reg_num = $this->request->data['Student']['registration_number'];
			$exam_type = $this->request->data['Student']['exam_type'];
			$exam_month_year_id = $this->request->data['Student']['month_year_id'];
			$studentId = $this->Student->getStudentId($reg_num);
			//echo $studentId;
			
			$studentDetails = $this->Student->studentDetails($studentId);
			//pr($studentDetails);
			$hallTicketArray = array();
			$hallTicketMatrixArray = array();
			
			if ($exam_type == 'A') {
				$result = $this->StudentMark->query("
						SELECT sm.id, sm.course_mapping_id, sm.month_year_id, c.course_code, s.registration_number, c.course_type_id,
						cm.month_year_id AS cm_month_year_id, cm.semester_id, c.id, cm.batch_id, cm.program_id
						FROM student_marks sm
						JOIN students s ON sm.student_id = s.id
						JOIN course_mappings cm ON sm.course_mapping_id = cm.id
						JOIN courses c ON cm.course_id = c.id
						JOIN batches b ON b.id = cm.batch_id
						WHERE sm.id IN (
						SELECT max( id ) FROM student_marks sm1 WHERE sm.student_id = sm1.student_id AND sm1.month_year_id <=$exam_month_year_id
						GROUP BY course_mapping_id, sm1.student_id ORDER BY id DESC)
						AND ((sm.status = 'Fail' AND sm.revaluation_status =0) OR (sm.final_status = 'Fail' AND sm.revaluation_status =1))
						AND sm.month_year_id <=$exam_month_year_id AND s.id =$studentId AND s.discontinued_status =0
						ORDER BY sm.course_mapping_id ASC");
				//pr($result);
				
				if (isset($result) && count($result)>0) {
					foreach ($result as $key => $value) {
						if ($value['c']['course_type_id'] == 1) {
							$hallTicketArray[$value['sm']['course_mapping_id']] = $value['c']['course_code'];
						}
					}
					//pr($hallTicketArray);
				}
				else {
					"No data available";
				}
				
				
				//pr($hallTicketMatrixArray);
				/* $results=$this->CourseStudentMapping->query("
						SELECT CourseStudentMapping.course_mapping_id, CourseStudentMapping.student_id,
						Student.registration_number, Student.name, Course.course_code, cm.batch_id, cm.program_id,
						cm.month_year_id, cm.semester_id, cm.course_id, b.batch_from, b.batch_to
						FROM `course_student_mappings` CourseStudentMapping
						JOIN students Student ON CourseStudentMapping.student_id = Student.id
						JOIN course_mappings cm ON cm.id=CourseStudentMapping.course_mapping_id
						JOIN courses Course ON Course.id=cm.course_id
						JOIN batches b ON b.id=cm.batch_id
						WHERE Student.discontinued_status =0
						AND CourseStudentMapping.student_id =$studentId
						AND CourseStudentMapping.new_semester_id =$exam_month_year_id
						AND Course.course_type_id in (".implode(Configure::read('CourseType.theory'), ',').")
					");
				pr($results); */
			}
			else if ($exam_type == 'R') {
				$batch_id = $studentDetails[0]['Student']['batch_id'];
				$program_id = $studentDetails[0]['Student']['program_id'];
				//echo $batch_id." ".$program_id;
				$regular = $this->CourseMapping->find('all', array(
				'conditions'=>array(
						'CourseMapping.indicator'=>0, 'CourseMapping.month_year_id'=>$exam_month_year_id,
						'CourseMapping.batch_id' => $batch_id,
						'CourseMapping.program_id' => $program_id
				),
				'fields'=>array('CourseMapping.id', 'CourseMapping.month_year_id', 'CourseMapping.batch_id',
						'CourseMapping.program_id', 'CourseMapping.semester_id'
				),
				'contain'=>array(
						'Course'=>array(
								'conditions'=>array('Course.course_type_id'=>Configure::read('CourseType.theory')),
								'fields'=>array('Course.course_code', 'Course.id', 'Course.course_type_id')
						),
				)
				));
				//pr($regular);
				
				if (isset($regular) && count($regular)>0) {
					$hallTicketArray = array();
					foreach ($regular as $key => $value) {
						if (isset($value['Course']['course_type_id'])) {
							$hallTicketArray[$value['CourseMapping']['id']] = $value['Course']['course_code'];
						}
					}
					pr($hallTicketArray);
				}
				else {
					"No data available";
				}
			}
			
			$i=1;
			$j=1;
			$k=1; $m=1;
			foreach ($hallTicketArray as $cm_id => $course_code) {
				//echo $i." ".$j." * ";
				//$j = ($k%7)+1;
				$hallTicketMatrixArray[$i][$k] = $course_code;
				//if ($k%7 == 0) $i++;
				//$k++;
				$k++; $m++;
				if($k%8 == 0) {
					$j++; $k=1;
				}
				if ($m%8 == 0) {
					$i++;
				}
			}
			
			$getSignature = $this->Signature->find("all", array('conditions' => array('Signature.id' => 1)));
			//pr($getSignature);
			if (count($hallTicketArray) > 0) {
				if ($exam_type == 'A') $txtExamType = "Arrear";
				if ($exam_type == 'R') $txtExamType = "Regular";
				$bodyHtml = '';
				$headerHtml = '
						<table border="0" align="center" cellpadding="0" cellspacing="0" style="font:14px Arial;width:100%;background-color:#ffffff;">
										<tr>
										<td rowspan="2" align="right" width="35%"><img src="../webroot/img/user.jpg"></td>
											<td align="left" width="65%">&nbsp;&nbsp;SATHYABAMA UNIVERSITY<br/>
											<span class="slogan"></span></td>
										</tr>
										</table>
						<table border="1" cellpadding="0" cellspacing="0" style="font:12px Arial;top:0;width:100%;background-color:#ffffff;">
							<tr>
								<td style="height:33px;"><b>&nbsp;Programme</b></td>
								<td>&nbsp;'.$studentDetails[0]['Academic']['short_code'].'</td>
								<td><b>&nbsp;Month & Year of Examination </b></td>
								<td>&nbsp;'.$this->MonthYear->getMonthYear($exam_month_year_id).'</td>
							</tr>
							<tr>
								<td style="height:33px;"><b>&nbsp;Specialisation</b></td>
								<td colspan="3">&nbsp;'.$studentDetails[0]['Program']['program_name'].'</td>
							</tr>
							<tr style="height:50px;">
								<td colspan="4" align="center" style="padding:10px 0;"><b>HALL TICKET&nbsp; - '.$txtExamType.'</b></td>
							</tr>';
				$bodyHtml .=$headerHtml;
				$pageNo = 1;
				
				$bodyHtml .='<tr class="gradeX">
									<td align="center" valign="middle">Name of the Student</td>
									<td colspan="2" align="center" style="height:30px;">'.$studentDetails[0]['Student']['name'].'</td>
									<td rowspan="2" style="width:120px;text-align:center;">';
											if($studentDetails[0]['Student']['picture']){ $imgPicture = str_replace("  "," ",str_replace("   "," ",$studentDetails[0]['Student']['picture']));}else{$imgPicture = 'profile.jpg';} 
				  			
				$bodyHtml .='<img src="../webroot/img/students/'.$imgPicture.'" style="width:100px;height:110px;">';
											
				$bodyHtml .='							</td>
									</tr>';
				$bodyHtml .='<tr class="gradeX">
									<td align="center" valign="middle">Registration Number</td>
									<td colspan="2" align="center" style="height:30px;">'.$studentDetails[0]['Student']['registration_number'].'</td>
									</tr>';
				$bodyHtml .='<tr class="gradeX">
									<td align="center" valign="top" colspan="4" style="padding:5px 0;">Note: Only a maximum  of 30 courses can be displayed in this Hall Ticket.</td>
									</tr>';
				$bodyHtml .='<tr class="gradeX">
									<td align="center" valign="top" colspan="4" style="height:30px;">&nbsp;';
									
				$bodyHtml .="<table class='attendanceHeadTblP' border='1' cellpadding='0' cellspacing='0' style='height:40px;width:90%;margin-bottom:12px;background-color:#ffffff;font:10px Arial;border-collapse:collapse;'>";
				$cnt = 1;
				
				for($i=1;$i<=4;$i++) {
					$bodyHtml.="<tr>";
					for ($j=1; $j<=7; $j++) {
						if (isset($hallTicketMatrixArray[$i][$j])) {
							$bodyHtml.="<td style='text-align:center;width:100px;'><strong>".$hallTicketMatrixArray[$i][$j]."</strong></td>";
						}
						else {
							$bodyHtml.="<td style='text-align:center;width:100px;'><strong>&nbsp;</strong></td>";
						}
					}
					$bodyHtml.="</tr>";
				}
				
				$bodyHtml .="</table>";
				if($getSignature[0]['Signature']['signature']){ 
					//echo $getSignature[0]['Signature']['signature'];
					//$signature1 = str_replace("  "," ",str_replace("   "," ",$getSignature[0]['Signature']['signature']));}else{$signature1 = 'profile.jpg';}
					$signature1 = $getSignature[0]['Signature']['signature'];
				} else{$signature1 = 'profile.jpg';}
				$bodyHtml .='</td></tr>
						<tr>
							<td colspan="2" style="text-align:center;vertical-align:bottom;">Signature of the Candidate</td>
							<td colspan="2" style="text-align:center;">';
				
				$bodyHtml .='<img src="../webroot/img/certificate_signature/'.$signature1.'" style="width:80px;height:50px;">';
				$bodyHtml .='<br>
								Controller of Examinations<br>(with seal) 
							</td>
						</tr>';
				if ($exam_type == 'A') {
				$bodyHtml .= '<tr>
							<td colspan="4" style="text-align:left;vertical-align:bottom;">
							Note: This hall ticket is not valid for regular exam
							</td>
						</tr>';
				}
				$bodyHtml .= '</br></br>';
				//$bodyHtml .='<tr><td colspan="4">&nbsp;</td></tr>';
				$bodyHtml .='</table>';
				$bodyHtml .='</td></tr>
						<tr>
							<td colspan="4">
						<h3 style="text-align:center;">Instructions to Candidates</h3>
	<ul class="hallticket-ins" style="text-align:justify;">
	<li>All candidates should bring their Hall ticket and valid Identity card for every examination for verifying their identity in the examination hall, failing which they will not be permitted to write the examination.</li>
	<li>Passed Out students should bring their Original Photo Identity Proof (Eg. PAN Card, Driving License etc.) as Identity when they come for Theory Examinations.</li>
	<li>The students are advised to view their seating arrangements for Regular Examinations in the University Website www.sathyabamauniversity.ac.in well in advance. For Arrear Exams, please look at the Exam Office Notice Board on the day of the Exam.</li>
	<li>The students are strictly not permitted to possess Cell Phones / Programmable Calculators inside the examination hall. Any violation of this will be viewed very seriously and it will be confiscated.</li>
	<li>Students will not be allowed to write the University Examination for Engineering Graphics, Machine Drawing and other Drawing related subjects , if they do not possess a Mini Drafter.</li>
	<li>Data books/lS codes/ Graph Sheets/ Charts / Tables will be issued by the University only.</li>
	<li>The students are advised to enter the examination hall 10 minutes before the commencement of examination. They should come In the official dress code.</li>
	<li>Students will be allowed to leave the Exam halls only at the end of three hours.</li>
	<li>Exam Timings : Forenoon Session - 8.30 A.M to 11.30 A.M.</br>
	               <p style="margin: 0 !important; padding-bottom: 0 !important; padding-left: 108px !important; padding-right: 0 !important; padding-top: 0 !important;">Afternoon Session 12.30P.M. to 3.30 P.M.</p></li>
	<li>MALPRACTICE DURING EXAMINATIONS:<br>
	If a student has been caught indulging in any malpractice, during any of the University Theory or Practical Examinations, severe action will be taken, as per the    University rules and regulations.</li>
							</ul></td>
						</tr>
						</table>';
				
				//echo $bodyHtml;
			 	
				$PdfFileName = "Hall_Ticket_".$this->MonthYear->getMonthYear($exam_month_year_id)."_".$studentDetails[0]['Student']['registration_number'];
				$this->layout=false;
				$this->autoRender = false;
				$this->mPDF->init();
				$this->mPDF->setFilename($PdfFileName.'.pdf');
				$this->mPDF->setOutput('D');
				$this->mPDF->WriteHTML($bodyHtml);
				$this->mPDF->SetWatermarkText("Draft");
				return false;
			}
			else {
				$this->Flash->error(__('No Arrear.'));
				return $this->redirect(array('action' => 'hallTicketSearch'));
			}
		}
	}
} 