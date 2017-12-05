<?php
App::uses('AppController', 'Controller');
App::import('Controller', 'ContinuousAssessmentExams');
App::import('Vendor', 'PHPExcel', array('file' => 'PHPExcel'.DS.'IOFactory.php'));
App::import('Vendor', 'mPDF', array('file' => 'mPDF'.DS.'mPDF.php'));

class StudentsController extends AppController {
	public $components = array('Paginator', 'Flash', 'Acl', 'Security',  'Session','mPDF');
	public $userId;
	public $myid; 
	
	public $uses = array("Student", "BatchMode", "Batch", "Program", "MonthYear", "CourseMapping", "StudentType", "Semester",
			"Lecturer","CourseStudentMapping","UniversityReference","StudentRemark","NonCreditCourse","StudentNcc",
			"StudentMark","Timetable","InternalExam","StudentWithheld","Withheld","MonthYear","Month","CaePractical",
			"EsePractical","InternalPractical","Practical","EndSemesterExam","RevaluationExam","TransferStudent",
			"TypeOfCertification","FolioNumber","Signature","ContinuousAssessmentExam", "Cae", "StudentWithdrawal","Section",
			"InternalProject", "ProjectReview", "ProjectViva", "CaeProject", "ProfessionalTraining"
	);
	
	public function beforeFilter() {
		parent::beforeFilter();
		if (isset($this->Security)) { //&& isset($this->Auth)) {
			$this->Security->validatePost = false;
			$this->Security->enabled = false;
			$this->Security->csrfCheck = false;
		}
	}
	
	public function photoSynchronize(){
		$academics = $this->Student->Academic->find('list',array('order' => array('Academic.academic_name ASC')));
		$programs = $this->Program->find('list',array('order' => array('Program.program_name ASC')));
		$batches = $this->Student->Batch->find('list', array('fields' => array('Batch.batch_period'),'order' => array('Batch.batch_to DESC')));
		$this->set(compact('batches', 'academics','programs'));
		
		if ($this->request->is('post')) {
			$reg_num_not_updated = "";
			//pr($this->request->data);
			$batchId = $this->request->data['Student']['batch_id'];
			$academicId = $this->request->data['Student']['academic_id'];
			$programId = $this->request->data['Student']['program_id'];
			$results = $this->Student->getStudentsInfo($batchId, $programId);
			//pr($results);
			foreach($results as $studentId => $val){
				$reg_num = $val['reg_num'];
				//echo APP;
				//$files = glob(dirname(__FILE__)."/../webroot/img/students/".$val." *.{jpg,jpeg,JPG,JPEG}",GLOB_BRACE);
				$files = glob(APP."webroot/img/students/".$reg_num."*.{jpg,jpeg,JPG,JPEG}",GLOB_BRACE);
				//pr($files);
			
				if($files){
					$file_name = str_replace("'", "\\'", basename($files[0]));
					$test = $this->Student->updateAll(
						array("Student.picture" => "'".$file_name."'"),
						array("Student.registration_number" => $reg_num)
					);
				}
				else {
					$reg_num_not_updated.=$reg_num." ";
				}
				if ($reg_num_not_updated == "") 
					$this->Flash->success(__('Successfully Completed.'));
				else 
					$this->Flash->error(__('Successfully Completed except...'.$reg_num_not_updated));
			}
		}
	}
	
	public function signatureSynchronize() {
		$academics = $this->Student->Academic->find('list',array('order' => array('Academic.academic_name ASC')));
		$programs = $this->Program->find('list',array('order' => array('Program.program_name ASC')));
		$batches = $this->Student->Batch->find('list', array('fields' => array('Batch.batch_period'),'order' => array('Batch.batch_to DESC')));
		$this->set(compact('batches', 'academics','programs'));
		if ($this->request->is('post')) {
			$reg_num_not_updated = "";
			//pr($this->request->data);
			$batchId = $this->request->data['Student']['batch_id'];
			$academicId = $this->request->data['Student']['academic_id'];
			$programId = $this->request->data['Student']['program_id'];
			$results = $this->Student->getStudentsInfo($batchId, $programId);
			//pr($results);
			foreach($results as $studentId => $val){
				$roll_number = $val['roll_number'];
				//echo APP;
				//$files = glob(dirname(__FILE__)."/../webroot/img/students/".$val." *.{jpg,jpeg,JPG,JPEG}",GLOB_BRACE);
				$files = glob(APP."webroot/img/signatures/".$roll_number."*.{jpg,jpeg,JPG,JPEG}",GLOB_BRACE);
				//pr($files);
					
				if($files){
					$file_name = str_replace("'", "\\'", basename($files[0]));
					$test = $this->Student->updateAll(
							array("Student.signature" => "'".$file_name."'"),
							array("Student.roll_number" => $roll_number)
							);
				}
				else {
					$reg_num_not_updated.=$val['reg_num']." ";
				}
				if ($reg_num_not_updated == "")
					$this->Flash->success(__('Successfully Completed.'));
					else
						$this->Flash->error(__('Successfully Completed except...'.$reg_num_not_updated));
			}
		}
		/* $result = $this->Student->find('all', array(
				'conditions' => array('Student.discontinued_status' => 0,'Student.indicator' => 0,'Student.signature' =>''),
				'fields' =>array('Student.registration_number', 'Student.roll_number',),
				'recursive'=>0));
		//pr($result);
		foreach($result as $key =>$val){ 
			$files = glob(dirname(__FILE__)."/../webroot/img/signatures/".$val['Student']['registration_number']." *.{jpg,jpeg,JPG,JPEG}",GLOB_BRACE);
			//echo "files ".$files[0];
			if($files){ 
				$this->Student->updateAll(
					array(
						"Student.signature" => "'".basename($files[0])."'"
					),
					array(
						"Student.registration_number" => $val
					)
				);
			} */
	}
	
	public function regNoSearch($regNo = null){
		 
		if ($this->request->is('post')) {	
			$regNo = $this->request->data['rgNo']['registration_number'];
			$result = $this->Student->find('first', array('conditions' => array('Student.registration_number' =>  $regNo),'fields' =>'Student.id'));
			
			if($result['Student']['id']){
				$this->redirect(array('controller' => 'Students','action' => 'view',$regNo));
			}else{
				$this->Flash->error(__('Invalid Register Number. Please, try again.'));
				$this->redirect(array('controller' => 'Students','action' => 'regNoSearch'));
			}			
		}
	}	
	
	public function index() {
	
	}
	
	public function delete_user($id = null) {
		$this->Student->id = $id;
		if (!$this->Student->exists()) {
			throw new NotFoundException(__('Invalid student'));
		}
		//$this->request->allowMethod('post', 'delete');
		$this->Student->updateAll(
		    /* UPDATE FIELD */
		    array(
		        "Student.discontinued_status" => 1,
		    ),
		    /* CONDITIONS */
		    array(
		        "Student.id" => $id
		    )
		);
		/* if ($this->Student->delete()) {
			$this->Flash->success(__('The student has been deleted.'));
		} else {
			$this->Flash->error(__('The student could not be deleted. Please, try again.'));
		} */
		return $this->redirect(array('action' => 'studentSearch'));
	}
	
	public function add(){ 
		if ($this->request->is('post')) {
			$batch_id = $this->request->data['Student']['batch_id'];
			$program_id = $this->request->data['Student']['program_id'];
			$month_year_id = $this->request->data['Student']['month_year_id'];
			
			$semester_result = $this->getSemesterIdFromMonthYear($batch_id, $program_id, $month_year_id);
			$semester_id = $semester_result['CourseMapping']['semester_id'];
			$this->request->data['Student']['month_year_id'] = $semester_id;
			
			if(!empty($this->request->data['Student']['picture']['name'])) {
				$file = $this->request->data['Student']['picture']; //put the data into a var for easy use
				$ext = substr(strtolower(strrchr($file['name'], '.')), 1); //get the extension
				$arr_ext = array('jpg', 'jpeg', 'gif', 'png', 'bmp'); //set allowed extensions
				//only process if the extension is valid
				if(in_array($ext, $arr_ext)) {
					move_uploaded_file($file['tmp_name'], WWW_ROOT . 'img/students/' .$this->request->data['Student']['registration_number'].".".$ext);
					$this->request->data['Student']['picture'] = $this->request->data['Student']['registration_number'].".".$ext;
				}
			}else {
	            unset($this->request->data['Student']['picture']);
	        }
			
			$this->Student->create();
			$this->request->data['Student']['created_by'] = $this->Auth->user('id');
			$this->request->data['Student']['birth_date'] 		= date("Y-m-d", strtotime($this->request->data['Student']['birth_date']));
			$this->request->data['Student']['admission_date'] 	= date("Y-m-d", strtotime($this->request->data['Student']['admission_date']));
			
			if($this->request->data['Student']['old_regNo']){
				$oldStudentId = $this->getIdFromRegNo($this->request->data['Student']['old_regNo']);
				if (isset($oldStudentId)) $this->request->data['Student']['parent_id'] 	= $oldStudentId;
				if($oldStudentId > 0) $this->Student->updateAll(array("Student.discontinued_status" => 1,),array("Student.id" => $oldStudentId));
			}
			//pr($this->request->data);
			if ($this->Student->save($this->request->data)) {
				$this->Flash->success(__('The student has been saved.'));
			} else {
				$this->Flash->error(__('The student could not be saved. Please, try again.'));
			}
		}
		$this->set('sections',$this->Section->find('list', array('fields' => array('Section.name'),'order'=>array('Section.name ASC'))));
		$this->set('University',$this->UniversityReference->find('list', array('fields' => array('UniversityReference.university_name'))));
		$this->set('batches',$this->Batch->find('list', array('fields' => array('Batch.batch_period'))));
		$this->set('academics', $this->Student->Academic->find('list'));
		$this->set('programs', $this->Student->Program->find('list'));
		$this->set('studenttypes', $this->Student->StudentType->find('list'));
		$monthYears = $this->MonthYear->find('all', array(
				'fields' => array('MonthYear.id','MonthYear.year','MonthYear.month_id','Month.month_name'),
				'order' => array('MonthYear.id DESC'),
				'recursive' => 0
		));
		//pr($monthYears);
	    $monthyears=array();
	    foreach($monthYears as $key => $value){
	      $monthyears[$value['MonthYear']['id']] = $value['Month']['month_name']."-".$value['MonthYear']['year'];
	    }    
        $this->set(compact('batches', 'academics', 'programs', 'studenttypes', 'monthyears','sections'));
      
	}
	
	public function student_upload_template(){
		//$this->response->file("sets_upload_file_template" . DS ."student_upload.xls", array('downlaod'=>true, 'name'=>"student_upload",'extension' => '.xls','mimeType' => 'text/xls xls'));
		//return $this->response;
		$filename = "student_upload.csv";
		$filename = WWW_ROOT ."../sets_upload_file_template" . DS . $filename;
		if (file_exists($filename)) {
			header("Pragma: public");
			header("Expires: 0");
			header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
			header("Cache-Control: private", false);
			header("Content-Type: application/force-download");
			//header("Content-Type: application/zip");
			//header("Content-Type: application/pdf");
			//header("Content-Type: application/octet-stream");
			//header("Content-Type: image/png");
			//header("Content-Type: image/gif");
			//header("Content-Type: image/jpg");
			header("Content-Type: application/vnd.ms-excel");
			//header("Content-Type: application/vnd.ms-powerpoint");
			header("Content-type: application/x-msexcel");
			//header("Content-type: application/msword");
			header("Content-Description: File Transfer");
			header("Content-Disposition: attachment; filename=" . basename($filename) . ";");
			header("Content-Transfer-Encoding: binary");
			header("Content-Length: " . filesize($filename));
			readfile($filename) or die("Errors");
			exit(0);
		}
	}
	public function studentUpload(){
		if(!empty($this->request->data['Student']['csv']['name'])) {
			move_uploaded_file($this->request->data['Student']['csv']['tmp_name'], WWW_ROOT . '../sets_upload_received_file/' .$this->request->data['Student']['csv']['name']);
		
			$filename = WWW_ROOT . '../sets_upload_received_file/'.$this->request->data['Student']['csv']['name']; 
			$handle = fopen($filename, "r");
			
			// read the 1st row as headings
			$header = fgetcsv($handle);
			
			// create a message container
			$return = array(
					'messages' => array(),
					'errors' => array(),
			);
			
			// read each data row in the file
			while (($row = fgetcsv($handle)) !== FALSE) {
				//$i++;
				$data = array();
			
				// for each header field
				foreach ($header as $k=>$head) { //echo "<br/>".$head;
					// get the data field from Model.field
					if (strpos($head,'.')!==false) { 
						$h = explode('.',$head);
						$data[$h[0]][$h[1]]=(isset($row[$k])) ? $row[$k] : '';
					}
					// get the data field from field
					else{
						if($head == 'section'){ 
							if(isset($row[$k])){
								$data['Student']['section_id'] = $this->getSectionId(strtoupper($row[$k]));
							}
						}else{
							$data['Student'][$head]=(isset($row[$k])) ? $row[$k] : '';
						}
					}
				}				
				$this->Student->create();
				$data['Student']['student_type_id']		= 1;
				$data['Student']['discontinued_status']	= 0;
				$data['Student']['indicator']			= 0;
				$data['Student']['month_year_id']		= 0;
				$data['Student']['batch_id']       		= $this->request->data['Student']['batch_id'];
				$data['Student']['academic_id']    		= $this->request->data['Student']['academic_id'];
				$data['Student']['program_id']     		= $this->request->data['Student']['program_id'];				
				$data['Student']['created_by']     		= $this->Auth->user('id');
				
				if(isset($data['Student']['dd'])){ 
					$DOB_dd = $data['Student']['dd'];
					if(strlen($DOB_dd) == 1){$DOB_dd = "0".$DOB_dd;}
					$DOB_mm = $data['Student']['mm'];
					if(strlen($DOB_mm) == 1){$DOB_mm = "0".$DOB_mm;}
					$DOB_yy = $data['Student']['yyyy'];
					$data['Student']['birth_date'] 		= $DOB_yy."-".$DOB_mm."-".$DOB_dd;
				}
				if(isset($data['Student']['admission_date'])){
					list($AD,$AM,$AY)= explode("/",$data['Student']['admission_date']);
					if(strlen($AD) == 1){$AD = "0".$AD;}
					if(strlen($AM) == 1){$AM = "0".$AM;}
					$data['Student']['admission_date'] 	= $AY."-".$AM."-".$AD;
				}
				
				if ($this->Student->save($data)) {
					$this->Flash->success(__('The student has been saved.'));					
				}else{
					$this->Flash->error(__('The Student Could not be Saved.'));
				}
			}
			return $this->redirect(array('action' => 'studentUpload'));
		}
		if ($this->request->is('post')) {
			if(!empty($this->request->data['Student']['picture']['name'])) {
				$file = $this->request->data['Student']['picture']; //put the data into a var for easy use
				$ext = substr(strtolower(strrchr($file['name'], '.')), 1); //get the extension
				$arr_ext = array('jpg', 'jpeg', 'gif', 'png', 'bmp'); //set allowed extensions
				//only process if the extension is valid
				if(in_array($ext, $arr_ext)) {
					move_uploaded_file($file['tmp_name'], WWW_ROOT . 'img/students/' .$this->request->data['Student']['registration_number'].".".$ext);
					$this->request->data['Student']['picture'] = $this->request->data['Student']['registration_number'].".".$ext;
				}
			}else {
				unset($this->request->data['Student']['picture']);
			}
				
			$this->Student->create();
			$this->request->data['Student']['created_by'] = $this->Auth->user('id');
			if(isset($this->request->data['Student']['birth_date'])){
				$this->request->data['Student']['birth_date'] 		= date("Y-m-d", strtotime($this->request->data['Student']['birth_date']));
			}
			if(isset($this->request->data['Student']['admission_date'])){
				$this->request->data['Student']['admission_date'] 	= date("Y-m-d", strtotime($this->request->data['Student']['admission_date']));
			}
	
			if ($this->Student->save($this->request->data)) {
				$this->Flash->success(__('The student has been saved.'));
			} else {
				$this->Flash->error(__('The student could not be saved. Please, try again.'));
			}
		}
		$this->set('University',$this->UniversityReference->find('list', array('fields' => array('UniversityReference.university_name'))));
		$this->set('batches',$this->Batch->find('list', array('fields' => array('Batch.batch_period'))));
		$this->set('academics', $this->Student->Academic->find('list'));
		$this->set('programs', $this->Student->Program->find('list'));
		$this->set('studenttypes', $this->Student->StudentType->find('list'));
		$this->set('semesters', $this->Semester->find('list'));
		$this->set(compact('batches', 'academics', 'programs', 'studenttypes', 'semesters'));
	
	}
	public function edit($regNo = null){
		$id = $this->getIdFromRegNo($regNo);
		
		if (!$this->Student->exists($id)) {
			throw new NotFoundException(__('Invalid student'));
		}
		if ($this->request->is(array('post', 'put'))) { 
			if($this->request->data['Student']['picture']['name']) {
				$file = $this->request->data['Student']['picture']; //put the data into a var for easy use
				$ext = substr(strtolower(strrchr($file['name'], '.')), 1); //get the extension
				$arr_ext = array('jpg', 'jpeg', 'gif', 'png', 'bmp'); //set allowed extensions
				//only process if the extension is valid
				if(in_array($ext, $arr_ext)) {
					move_uploaded_file($file['tmp_name'], WWW_ROOT . 'img/students/' .$this->request->data['Student']['registration_number'].".".$ext);
					$this->request->data['Student']['picture'] = $this->request->data['Student']['registration_number'].".".$ext;
				}
			}else {
	           $this->request->data['Student']['picture'] = $this->request->data['old_picture'];
	        }
			
			$this->Student->create();
			$this->request->data['Student']['modified_by'] = $this->Auth->user('id');
			$this->request->data['Student']['birth_date'] 		= date("Y-m-d", strtotime($this->request->data['Student']['birth_date']));
			$this->request->data['Student']['admission_date'] 	= date("Y-m-d", strtotime($this->request->data['Student']['admission_date']));
			
			if($this->Student->save($this->request->data)) {
				$this->Flash->success(__('The student has been updated.'));
			}else {
				$this->Flash->error(__('The student could not be updated. Please, try again.'));
			}
		}else{
			$options = array('conditions' => array('Student.' . $this->Student->primaryKey => $id));
			$this->request->data = $this->Student->find('first', $options);
			$this->request->data['Student']['birth_date'] 		= date("d-m-Y", strtotime($this->request->data['Student']['birth_date']));
			$this->request->data['Student']['admission_date'] 	= date("d-m-Y", strtotime($this->request->data['Student']['admission_date']));				
		}
		
		$monthYears = $this->MonthYear->find('all', array(
				'fields' => array('MonthYear.id','MonthYear.year','MonthYear.month_id','Month.month_name'),
				'order' => array('MonthYear.id DESC'),
				'recursive' => 0
		));
		$monthyears=array();
		foreach($monthYears as $key => $value){
			$monthyears[$value['MonthYear']['id']] = $value['Month']['month_name']."-".$value['MonthYear']['year'];
		}
		$this->set('sections',$this->Section->find('list', array('fields' => array('Section.name'),'order'=>array('Section.name ASC'))));
		$this->set('University',$this->UniversityReference->find('list', array('fields' => array('UniversityReference.university_name'))));
		$this->set('batches',$this->Batch->find('list', array('fields' => array('Batch.batch_period'))));
		$this->set('academics', $this->Student->Academic->find('list'));
		$this->set('programs', $this->Student->Program->find('list'));
		$this->set('studenttypes', $this->Student->StudentType->find('list'));
		$this->set('semesters', $this->Semester->find('list'));
		
		$this->set(compact('batches', 'academics', 'programs', 'studenttypes','monthyears','sections'));
        $this->set('studentId', $regNo);
        
	}
	public function getIdFromRegNo($regNo = null){
		$result = $this->Student->find('first', array('conditions' => array('Student.registration_number' => $regNo),'fields' =>'Student.id'));
		if(isset($result['Student']['id'])){
			return $result['Student']['id'];
		}else{	
			//return $this->redirect(array('action' => 'studentSearch'));
			return false;
		}
	}
	
	public function view($regNo = null) {
		$this->info($regNo);
		//$this->layout=false;
	}
	
	public function info($regNo = null) {
		$id = $this->getIdFromRegNo($regNo); 
		if (!$this->Student->exists($id)) {
			throw new NotFoundException(__('Invalid Student'));
		}
		$month_years = $this->MonthYear->getAllMonthYears();
		//pr($month_years);
		$options = array('conditions' => array('Student.' . $this->Student->primaryKey => $id), 'recursive'=>0);
		$this->set('studentId', $regNo);
		$this->set('month_years', $month_years);
		$this->set('student', $this->Student->find('first', $options));	
	}
	
	public function student_marks($regNo = null) {
		$this->info($regNo);
		$results = $this->student_details($regNo);
		$this->set('results', $results);
	}
	
	public function student_details($regNo = null) {
		$stuCond=array();
		$stuCond['Student.registration_number'] = array($regNo);
		
		$res = array(
				'conditions' => array($stuCond,
				),
				'fields' =>array('Student.id','Student.registration_number','Student.name','Student.birth_date',
						'Student.batch_id', 'Student.program_id'
				),
				'contain'=>array(
						'StudentMark' => array(
								'fields' => array('StudentMark.id','StudentMark.month_year_id', 'StudentMark.marks', 'StudentMark.student_id', 'StudentMark.course_mapping_id','StudentMark.status','StudentMark.revaluation_status','StudentMark.final_marks','StudentMark.final_status'),
								'conditions' => array(),
								'MonthYear' => array(
										'fields'=>array('MonthYear.year', 'MonthYear.id'),
										'Month'=>array(
												'fields'=>array('Month.month_name')
										)
								),
								'CourseMapping'=>array('fields'=>array('CourseMapping.id','CourseMapping.semester_id'),
										'conditions'=>array('CourseMapping.indicator'=>0,
										),
										'Course' => array(
												'fields' => array('Course.id','Course.course_code','Course.course_name',
												'Course.course_max_marks', 'Course.credit_point'),
												'CourseType' => array('fields' => array('CourseType.course_type'))
										),'order'=>'CourseMapping.semester_id ASC',
								),'order'=>'StudentMark.month_year_id ASC',
						),
				),
		);
		$results = $this->Student->find("all", $res);
		return $results;
	}
	
	public function credits($regNo = null) {
		$this->info($regNo);
		$results = $this->student_details($regNo);
		//pr($results);
		$this->set('results', $results);
	}
	public function attendance($regNo = null) {
		$this->info($regNo);
		$studentId = $this->getIdFromRegNo($regNo);
		//echo $studentId;
		$attendance = $this->Student->find('all', array(
				'conditions' => array('Student.id'=> $studentId),
				'contain'=>array(
						'GrossAttendance'=>array(
							'fields'=>array('GrossAttendance.*')
						),
						'Attendance'=>array(
							'fields'=>array('Attendance.*')
						)
				)
		));
		$this->set('attendance', $attendance);
	}
	
	public function remarks($regNo = null) { 
		
		if(empty($regNo)){$regNo = $this->request->data['studentId'];}		
		
		$id = $this->getIdFromRegNo($regNo);
		
		if ($this->request->is('post')) {
			$this->Student->create();
			$this->request->data['StudentRemark']['student_id'] = $id;
			$this->request->data['StudentRemark']['created_by'] = $this->Auth->user('id');
		
			if ($this->StudentRemark->save($this->request->data)) {
				$this->Flash->success(__('The Remarks has been Saved.'));
			} else {
				$this->Flash->error(__('The Remarks could not be saved. Please, try again.'));
			}
		}
		$stuRemarks = $this->StudentRemark->find('all', array('conditions' => array('StudentRemark.student_id'=> $id),'recursive'=>0,'order'=>'StudentRemark.id DESC' ));
		$this->set('StudentRemark',$stuRemarks);
	
		$this->info($regNo);
	}
	public function chgStatus($regNo = null) {
	
		if(empty($regNo)){$regNo = $this->request->data['Student']['studentId'];}
		$id = $this->getIdFromRegNo($regNo);
		
		if ($this->request->is('post')) {
			
			$this->Student->updateAll(
					/* UPDATE FIELD */
					array("Student.discontinued_status" => $this->request->data['Student']['discontinued_status'],
						  "Student.reason" => "'".$this->request->data['Student']['reason']."'"
					),
					/* CONDITIONS */
					array("Student.id" => $id)
			);
		}
		$students = $this->Student->find('all', 
				array('fields' => array('Student.id','Student.discontinued_status','Student.reason',),
				'conditions' => array('Student.id'=> $id),'recursive'=>0,'order'=>'Student.id DESC' ));
		$this->set('students',$students);
	    $this->info($regNo);
	}
	public function ncc($regNo = null) { 
		if(empty($regNo)){$regNo = $this->request->data['studentId'];}
		$this->info($regNo);
		$id = $this->getIdFromRegNo($regNo);
		if ($this->request->is('post')) {
			$this->Student->create();
			$this->request->data['StudentNcc']['student_id'] = $id;
			$this->request->data['StudentNcc']['created_by'] = $this->Auth->user('id');
			$this->request->data['StudentNcc']['joined_on'] = date("Y-m-d", strtotime($this->request->data['StudentNcc']['Joined_on']));
		
			if ($this->StudentNcc->save($this->request->data)) {
				$this->Flash->success(__('The Course has been Saved.'));
			} else {
				$this->Flash->error(__('The Course could not be saved. Please, try again.'));
			}
		}
		$this->set('StudentNonCreditCourse', $this->StudentNcc->find('all', array('conditions' => array('StudentNcc.student_id'=> $id),'recursive'=>0,'order'=>'StudentNcc.id DESC' )));
		$this->set('allNonCreditCourse', $this->NonCreditCourse->find('list', array('fields' => 'NonCreditCourse.non_credit_course_name')));
	}
	
	public function withheldAll($regNo = null) {
		if(empty($regNo)){$regNo = $this->request->data['studentId'];}
		$this->info($regNo);
		$id = $this->getIdFromRegNo($regNo);
		
		$getWithheldStu = array(
			'conditions' => array('StudentWithheld.student_id' =>$id),
			'fields' =>array('StudentWithheld.id','StudentWithheld.student_id','StudentWithheld.indicator','StudentWithheld.created','StudentWithheld.modified','StudentWithheld.remarks','StudentWithheld.remarks_date'),
			'contain'=>array(
				'Withheld'=>array('fields' =>array('Withheld.withheld_type'),'order'=>'month_year_id asc'),
				'MonthYear'=>array('fields' =>array('MonthYear.year'),
						'Month'=>array('fields' =>array('Month.month_name'))
				),
			),
		);
		$this->set('getWithheldStuRes',$this->StudentWithheld->find("all", $getWithheldStu));
	}
	
	public function withheldRemarks($select_date = null,$remarks = null,$id = null) {
		if(($select_date) && ($remarks) && ($id)){
			list($date,$month,$year)= explode('-', $select_date);
			$data =  array();
			$data['StudentWithheld']['id'] 					= $id;
			$data['StudentWithheld']['remarks']  			= $remarks;
			$data['StudentWithheld']['remarks_date'] 		= $year.'-'.$month.'-'.$date;
			$data['StudentWithheld']['modified_by'] 	    = $this->Auth->user('id');
			$this->StudentWithheld->save($data);
			echo 'The Remarks Updated Successfully.';exit;
		}
	}
	
	public function deleteWithheldAll($delNccId = null, $regNo = null){
		$this->StudentWithheld->id = $delNccId;		
		if (!$this->StudentWithheld->exists()) {		
			throw new NotFoundException(__('Invalid Data'));		
		}
		$this->request->allowMethod('post', 'delete');		
		if ($this->StudentWithheld->delete()) {		
			$this->Flash->success(__('The Withheld has been Deleted.'));		
		} else {		
			$this->Flash->error(__('The Withheld not be Deleted. Please, try again.'));		
		}
		return $this->redirect(array('action' => 'withheldAll',$regNo));
	}
	
	public function deleteNcc($delNccId = null, $regNo = null){
		$this->StudentNcc->id = $delNccId;		
		if (!$this->StudentNcc->exists()) {		
			throw new NotFoundException(__('Invalid Data'));		
		}
		$this->request->allowMethod('post', 'delete');		
		if ($this->StudentNcc->delete()) {		
			$this->Flash->success(__('The Course has been Deleted.'));		
		} else {	
			$this->Flash->error(__('The Course could not be Deleted. Please, try again.'));		
		}
		return $this->redirect(array('action' => 'ncc',$regNo));
	}
	
	public function markSheetSearch() {
		$access_result = $this->checkPathAccess($this->Auth->user('id'));
		if (!$access_result) {
		if($this->request->is('post')) {
			if(isset($this->request->data['submit']) == 'PRINT'){
				$examMonth = "";$type_of_cert = "";$programId="";$frm_register_no ="";$to_register_no ="";
				$stuCond=array(); $markCond=array();  $attenCond=array();$tTCond=array();$iExam=array();$eExam=array();$SWMYE = array();
	
				if(isset($this->request->data['markSheet']['batch_id'])){	$batchId = $this->request->data['markSheet']['batch_id'];}
				if(isset($this->request->data['markSheet']['academic_id'])){$Academic = $this->request->data['markSheet']['academic_id'];}
				if(isset($this->request->data['markSheet']['frm_register_no'])){$frm_register_no = $this->request->data['markSheet']['frm_register_no'];}
				if(isset($this->request->data['markSheet']['to_register_no'])){$to_register_no = $this->request->data['markSheet']['to_register_no'];}
				if($this->request->data['markSheet']['type_of_cert']){
					$type_of_cert = $this->request->data['markSheet']['type_of_cert'];
					//Get Certification Short Code
					$getCertificationId = $this->TypeOfCertification->find('first', array(
							'conditions' => array('TypeOfCertification.id' => $type_of_cert),'recursive' => 0));
					$type_of_cert_code = $getCertificationId['TypeOfCertification']['short_code'];
					$type_of_cert_id = $getCertificationId['TypeOfCertification']['id'];
						
					$getFolioLastNumber = $this->FolioNumber->find('first', array(
							'conditions' => array('FolioNumber.type_of_certification_id' => $type_of_cert_id,'FolioNumber.dates' =>  date('dmy')),'recursive' => 0,'order'=>'FolioNumber.id DESC' ));
					$seqFolioNo = "";
						
					if($getFolioLastNumber){
						$seqFolioNo = (int)$getFolioLastNumber['FolioNumber']['serial_number'];
					}
					if($seqFolioNo){
						$seqFolioNo = $seqFolioNo;
					}else{
						$seqFolioNo = 0;
					}
						
					$type_of_cert = $type_of_cert_code;
	
				}
				if(isset($this->request->data['Student']['program_id'])){$programId = $this->request->data['Student']['program_id'];}
				if(isset($this->request->data['markSheet']['monthyears'])){$examMonth = $this->request->data['markSheet']['monthyears'];}
	
				if($examMonth != '-'){
					$markCond['StudentMark.month_year_id'] = $examMonth;
					$tTCond['Timetable.month_year_id'] = $examMonth;
					$iExam['InternalExam.month_year_id'] = $examMonth;
					$eExam['EndSemesterExam.month_year_id'] = $examMonth;
					$SWMYE['StudentWithheld.month_year_id'] = $examMonth;
				}
	
				//Start
				$attenCond['ExamAttendance.attendance_status'] = 0;
				$tTCond['Timetable.indicator'] = 0;
				$stuCond['Student.discontinued_status'] = 0;
				//$stuCond['Student.picture !='] = '';
				//$stuCond['Student.registration_number'] = '3541183';
				//$stuCond['Student.registration_number'] = '3531007';
	
				if(($frm_register_no) && ($frm_register_no !='-')){
					$stuCond['Student.registration_number >='] = $frm_register_no;
				}
				if(($to_register_no) && ($to_register_no !='-')){
					$stuCond['Student.registration_number <='] = $to_register_no;
				}
				if($batchId != '-'){
					$stuCond['Student.batch_id'] = $batchId;
				}
				if($programId != '-'){
					$stuCond['Student.program_id'] = $programId;
				}
				//$stuCond['Student.month_year_id <='] = $examMonth;
				
				$res = array(
						'conditions' => array($stuCond,
						),
						'fields' =>array('Student.id','Student.registration_number','Student.name','Student.birth_date','Student.gender','Student.picture','Student.parent_id','Student.university_references_id'),
						'contain'=>array(
								'Academic' => array(
										'fields' => array('Academic.short_code','Academic.academic_name')
								),
								'Batch' => array(
										'fields' => array('Batch.batch_from', 'Batch.batch_to', 'Batch.academic')
								),
								'Program' => array('fields' => array('Program.program_name','Program.short_code'),
										'Academic' => array('fields' => array('Academic.academic_name'))
								),
								'CourseStudentMapping'=>array(
									'fields' => array('CourseStudentMapping.course_mapping_id','CourseStudentMapping.type'),
								),
								'StudentAuditCourse'=>array(
									'fields'=>array('StudentAuditCourse.id', 'StudentAuditCourse.student_id', 
													'StudentAuditCourse.audit_course_id', 'StudentAuditCourse.marks',
											'StudentAuditCourse.month_year_id'
									),
									'conditions'=>array(
										'StudentAuditCourse.month_year_id'=>$examMonth, 'StudentAuditCourse.indicator'=>0
									),
										'AuditCourse'=>array(
												'fields' => array('AuditCourse.id','AuditCourse.course_name',
												'AuditCourse.course_code', 'AuditCourse.total_min_pass_mark'),
										), 
								),
								'Practical' => array(
										'fields' => array('Practical.month_year_id', 'Practical.marks', 'Practical.student_id'),
										'conditions' => array('Practical.month_year_id <=' => $examMonth),
										'EsePractical' => array(
												'fields' => array('EsePractical.course_mapping_id')
										)
								),
								'EndSemesterExam' => array(
										'fields' => array('EndSemesterExam.month_year_id', 'EndSemesterExam.marks', 'EndSemesterExam.student_id', 'EndSemesterExam.course_mapping_id', 'EndSemesterExam.dummy_number','EndSemesterExam.revaluation_status'),
										'conditions' => array('EndSemesterExam.month_year_id <=' => $examMonth)
								),
								'ProjectViva' => array(
										'fields' => array('ProjectViva.month_year_id', 'ProjectViva.marks', 'ProjectViva.student_id'),
										'conditions' => array('ProjectViva.month_year_id <=' => $examMonth),
										'EseProject' => array(
												'fields' => array('EseProject.course_mapping_id')
										)
								),
								'StudentMark' => array(
										'fields' => array('StudentMark.id','StudentMark.month_year_id', 'StudentMark.marks', 'StudentMark.student_id', 'StudentMark.course_mapping_id','StudentMark.status','StudentMark.revaluation_status','StudentMark.final_marks','StudentMark.final_status','StudentMark.grade_point','StudentMark.grade'),
										'conditions' => array('StudentMark.month_year_id <=' => $examMonth),
										'CourseMapping'=>array(
												'fields'=>array(
														//'CourseMapping.id',
														'CourseMapping.semester_id',
														'CourseMapping.month_year_id',
												),
												'conditions'=>array('CourseMapping.indicator'=>0,
												),
												'Course' => array(
														'fields' => array('Course.id','Course.course_code','Course.course_name','Course.course_max_marks','Course.credit_point','Course.course_type_id'),
														'CourseType' => array('fields' => array('CourseType.course_type'))
												),
												'MonthYear'=>array('fields' =>array('MonthYear.year','MonthYear.publishing_date'),
														'Month'=>array('fields' =>array('Month.month_name'))
												),
												'order'=>'CourseMapping.semester_id ASC',
										),/* 'order'=>'StudentMark.month_year_id ASC', */
										'MonthYear'=>array('fields' =>array('MonthYear.year','MonthYear.publishing_date'),
												'Month'=>array('fields' =>array('Month.month_name'))
										),
								),
								'RevaluationExam' =>array(
										'fields' => array('RevaluationExam.id','RevaluationExam.course_mapping_id','RevaluationExam.revaluation_marks','RevaluationExam.status'),
										'conditions' => array('RevaluationExam.month_year_id <=' => $examMonth),
										'order'=>'RevaluationExam.month_year_id ASC',
										'MonthYear'=>array('fields' =>array('MonthYear.year'),
												'Month'=>array('fields' =>array('Month.month_name'))
										),
								),
								/* 'ParentGroup' => array(
										'fields' => array('ParentGroup.batch_id', 'ParentGroup.academic_id', 'ParentGroup.program_id'),
										'Batch' => array(
												'fields' => array('Batch.batch_from', 'Batch.batch_to', 'Batch.academic')
										),
										'Program' => array('fields' => array('Program.program_name','Program.short_code'),
												'Academic' => array('fields' => array('Academic.academic_name'))
										),
	
										'Practical' => array(
												'fields' => array('Practical.month_year_id', 'Practical.marks', 'Practical.student_id'),
												'conditions' => array('Practical.month_year_id <=' => $examMonth),
												'EsePractical' => array(
														'fields' => array('EsePractical.course_mapping_id')
												)
										),
	
										'EndSemesterExam' => array(
												'fields' => array('EndSemesterExam.month_year_id', 'EndSemesterExam.marks', 'EndSemesterExam.student_id', 'EndSemesterExam.course_mapping_id', 'EndSemesterExam.dummy_number','EndSemesterExam.revaluation_status'),
												'conditions' => array('EndSemesterExam.month_year_id <=' => $examMonth)
										),
										'ProjectViva' => array(
												'fields' => array('ProjectViva.month_year_id', 'ProjectViva.marks', 'ProjectViva.student_id'),
												'conditions' => array('ProjectViva.month_year_id <=' => $examMonth),
												'EseProject' => array(
														'fields' => array('EseProject.course_mapping_id')
												)
										),
										'StudentMark' => array(
												'fields' => array('StudentMark.id','StudentMark.month_year_id', 'StudentMark.marks', 'StudentMark.student_id', 'StudentMark.course_mapping_id','StudentMark.status','StudentMark.revaluation_status','StudentMark.final_marks','StudentMark.final_status','StudentMark.grade_point','StudentMark.grade'),
												'conditions' => array('StudentMark.month_year_id <=' => $examMonth),
												'CourseMapping'=>array('fields'=>array('CourseMapping.id','CourseMapping.semester_id'),
														'conditions'=>array('CourseMapping.indicator'=>0,
														),
														'Course' => array(
																'fields' => array('Course.id','Course.course_code','Course.course_name','Course.course_max_marks','Course.credit_point'),
																'CourseType' => array('fields' => array('CourseType.course_type'))
														),
														'MonthYear'=>array('fields' =>array('MonthYear.year','MonthYear.publishing_date'),
																'Month'=>array('fields' =>array('Month.month_name'))
														),
														'order'=>'CourseMapping.semester_id ASC',
												),'order'=>'StudentMark.month_year_id ASC',
												'MonthYear'=>array('fields' =>array('MonthYear.year','MonthYear.publishing_date'),
														'Month'=>array('fields' =>array('Month.month_name'))
												),
										),
										'RevaluationExam' =>array(
												'fields' => array('RevaluationExam.id','RevaluationExam.course_mapping_id','RevaluationExam.revaluation_marks','RevaluationExam.status'),
												'conditions' => array('RevaluationExam.month_year_id <=' => $examMonth),'order'=>'RevaluationExam.month_year_id ASC',
												'MonthYear'=>array('fields' =>array('MonthYear.year'),
														'Month'=>array('fields' =>array('Month.month_name'))
												),
										),
								) */
						),
						'recursive'=>0
				);
				
				$csmCond = array();
				if(($frm_register_no) && ($frm_register_no !='-')){
					$csmCond['Student.registration_number >='] = $frm_register_no;
				}
				if(($to_register_no) && ($to_register_no !='-')){
					$csmCond['Student.registration_number <='] = $to_register_no;
				}
				if($batchId != '-'){
					$csmCond['Student.batch_id'] = $batchId;
				}
				if($programId != '-'){
					$csmCond['Student.program_id'] = $programId;
				}
				//$stuCond['Student.month_year_id <='] = $examMonth;
				
				$res1 = array(
						'conditions' => array($csmCond,
						),
						'fields' =>array('Student.id','Student.parent_id','Student.university_references_id'),
						'contain'=>array(
								'CourseStudentMapping'=>array(
										'fields'=>array('CourseStudentMapping.course_mapping_id',
												'CourseStudentMapping.indicator',
										),
								),
								'ParentGroup' => array(
										'CourseStudentMapping'=>array(
												'fields'=>array('CourseStudentMapping.course_mapping_id',
														'CourseStudentMapping.indicator',
												),
										),
								)
						));
				$csmResult = $this->Student->find('all', $res1);
				//pr($csmResult);
				$csmArray = array();
				foreach ($csmResult as $key => $csm) {
					$innerArray = $csm['CourseStudentMapping'];
					$student_id = $csm['Student']['id'];
					$csmArray=$this->cm($innerArray, $csmArray, $student_id);
					if(isset($csm['Student']['parent_id'])) {
						$parent_id = $csm['Student']['parent_id'];
						$pInnerArray = $csm['ParentGroup']['CourseStudentMapping'];
						$csmArray=$this->cm($pInnerArray, $csmArray, $student_id);
					}
					
				}
				//pr($csmArray);
				
				//End
				//CEO signature and name
				$getSignature = $this->Signature->find("all", array('conditions' => array('Signature.id' => 1)));
				$this->set('getSignature', $getSignature);
	
				$results = $this->Student->find("all", $res);
				//pr($results); 
				$this->set('results', $results);
	
				$this->set('examMonth', $examMonth);
				$this->set('type_of_cert', $type_of_cert);
				$this->set('seqFolioNo', $seqFolioNo);
	
				$this->set('csmArray', $csmArray);
				
				$this->FolioNumber->create();
				$data['FolioNumber']['dates']  = date('dmy');
				$data['FolioNumber']['type_of_certification_id'] = $type_of_cert_id;
				$data['FolioNumber']['serial_number'] = ($seqFolioNo + count($results));
				$this->FolioNumber->save($data);
	
				$this->layout= false;
				$this->layout= 'print';
				//$this->render('markSheet');
				$this->render('markSheet_wa');
				return false;
			}
		}
		$typeOfCert = $this->TypeOfCertification->find('list',array('order' => array('TypeOfCertification.certification ASC')));
		$academics = $this->Program->Academic->find('list',array('order' => array('Academic.academic_name ASC')));
		$programs = $this->Program->find('list',array('order' => array('Program.program_name ASC')));
		$batches = $this->Batch->find('list', array('fields' => array('Batch.batch_period'),'order' => array('Batch.batch_to DESC')));
		$monthyears = $this->MonthYear->getAllMonthYears();
		$this->set(compact('academics','programs','batches','monthyears','typeOfCert'));
		}
		else {
			$this->render('../Users/access_denied');
		}
	}
	
	private function cm($innerArray, $csmArray, $student_id) {
		//pr($innerArray);
		foreach ($innerArray as $k => $v) {
			$indicator = $v['indicator'];
			if ($v['indicator'] == 0) {
				$csmArray[$student_id][$v['course_mapping_id']]=$indicator;
			}
		}
		return $csmArray;
	}
	
	public function migrationSearch() {
		$access_result = $this->checkPathAccess($this->Auth->user('id'));
		if (!$access_result) {
		if($this->request->is('post')) {
			if(isset($this->request->data['submit']) == 'PRINT'){
				
				$examMonth = "";$type_of_cert = "";$programId="";$frm_register_no ="";$to_register_no ="";
				$stuCond=array(); $markCond=array();  $attenCond=array();$tTCond=array();$iExam=array();$eExam=array();$SWMYE = array();
	
				if(isset($this->request->data['markSheet']['batch_id'])){	$batchId = $this->request->data['markSheet']['batch_id'];}
				if(isset($this->request->data['markSheet']['academic_id'])){$Academic = $this->request->data['markSheet']['academic_id'];}
				if(isset($this->request->data['markSheet']['frm_register_no'])){$frm_register_no = $this->request->data['markSheet']['frm_register_no'];}
				if(isset($this->request->data['markSheet']['to_register_no'])){$to_register_no = $this->request->data['markSheet']['to_register_no'];}
				if(isset($this->request->data['markSheet']['convocation_date'])){$convocation_date = $this->request->data['markSheet']['convocation_date'];}
				 
				if(isset($this->request->data['Student']['program_id'])){$programId = $this->request->data['Student']['program_id'];}
				if(isset($this->request->data['markSheet']['monthyears'])){$examMonth = $this->request->data['markSheet']['monthyears'];}
				$this->set(compact('convocation_date'));
				$user_id = $this->Auth->user('id');
				$this->set('logged_in_user', $user_id);
				//Start
				$stuCond['Student.picture !='] = '';
				$stuCond['Student.registration_number'] = '3627001';
				if(($frm_register_no) && ($frm_register_no !='-')){
					$stuCond['Student.registration_number >='] = $frm_register_no;
				}
				if(($to_register_no) && ($to_register_no !='-')){
					$stuCond['Student.registration_number <='] = $to_register_no;
				}
				if($batchId != '-'){
					$stuCond['Student.batch_id'] = $batchId;
				}
				if($programId != '-'){
					$stuCond['Student.program_id'] = $programId;
				}
					
				$res = array(
						'conditions' => array($stuCond,
						),
						'fields' =>array('Student.id','Student.registration_number','Student.father_name','Student.name',
								'Student.birth_date','Student.gender','Student.picture','Student.parent_id',
								'Student.university_references_id','Student.tamil_name', 'Student.student_type_id',
								'Student.discontinued_status'
						),
						'contain'=>array(
								'StudentAuthorizedBreak' => array(
										'fields' => array('StudentAuthorizedBreak.student_id')
								),
								'StudentWithdrawal' => array(
										'fields' => array('StudentWithdrawal.student_id')
								),
								'Academic' => array(
										'fields' => array('Academic.short_code','Academic.academic_name','Academic.academic_name_tamil')
								),
								'Batch' => array(
										'fields' => array('Batch.batch_from', 'Batch.batch_to', 'Batch.academic')
								),
								'Program' => array('fields' => array('Program.program_name','Program.short_code','Program.credits','Program.program_name_tamil'),
										'Academic' => array('fields' => array('Academic.academic_name')),
										'Faculty' => array('fields' => array('Faculty.faculty_name','Faculty.faculty_name_tamil'))
								),
								'CourseStudentMapping'=>array(
									'fields'=>array('CourseStudentMapping.id', 'CourseStudentMapping.course_mapping_id',
											'CourseStudentMapping.indicator'
									),
									'conditions'=>array('CourseStudentMapping.indicator'=>0)
								),
								'StudentMark' => array(
										'fields' => array('StudentMark.id','StudentMark.month_year_id', 'StudentMark.marks', 'StudentMark.student_id', 'StudentMark.course_mapping_id','StudentMark.status','StudentMark.revaluation_status','StudentMark.final_marks','StudentMark.final_status','StudentMark.grade_point','StudentMark.grade'),
										'CourseMapping'=>array('fields'=>array('CourseMapping.id','CourseMapping.semester_id'),
												'conditions'=>array('CourseMapping.indicator'=>0,
												),
												'Course' => array(
														'fields' => array('Course.id','Course.course_code','Course.course_name','Course.course_max_marks','Course.credit_point'),
														'CourseType' => array('fields' => array('CourseType.course_type'))
												),'order'=>'CourseMapping.semester_id ASC',
										),'order'=>'StudentMark.month_year_id ASC',
										'MonthYear'=>array('fields' =>array('MonthYear.year','MonthYear.publishing_date'),
												'Month'=>array('fields' =>array('Month.month_name'))
										),
								),
								'RevaluationExam' =>array(
										'fields' => array('RevaluationExam.id','RevaluationExam.course_mapping_id','RevaluationExam.revaluation_marks','RevaluationExam.status'),
										'order'=>'RevaluationExam.month_year_id ASC',
										'MonthYear'=>array('fields' =>array('MonthYear.year'),
												'Month'=>array('fields' =>array('Month.month_name'))
										),
								),
								'ParentGroup' => array(
										'fields' => array('ParentGroup.batch_id', 'ParentGroup.academic_id', 'ParentGroup.program_id'),
										'Batch' => array(
												'fields' => array('Batch.batch_from', 'Batch.batch_to', 'Batch.academic')
										),
										'Program' => array('fields' => array('Program.program_name','Program.short_code'),
												'Academic' => array('fields' => array('Academic.academic_name'))
										),
										'StudentMark' => array(
												'fields' => array('StudentMark.id','StudentMark.month_year_id', 'StudentMark.marks', 'StudentMark.student_id', 'StudentMark.course_mapping_id','StudentMark.status','StudentMark.revaluation_status','StudentMark.final_marks','StudentMark.final_status','StudentMark.grade_point','StudentMark.grade'),
												'CourseMapping'=>array('fields'=>array('CourseMapping.id','CourseMapping.semester_id'),
														'conditions'=>array('CourseMapping.indicator'=>0,
														),
														'Course' => array(
																'fields' => array('Course.id','Course.course_code','Course.course_name','Course.course_max_marks','Course.credit_point'),
																'CourseType' => array('fields' => array('CourseType.course_type'))
														),'order'=>'CourseMapping.semester_id ASC',
												),'order'=>'StudentMark.month_year_id ASC',
												'MonthYear'=>array('fields' =>array('MonthYear.year','MonthYear.publishing_date'),
														'Month'=>array('fields' =>array('Month.month_name'))
												),
										),
								)
						),'recursive'=>0
				);
				//End
				$results = $this->Student->find("all", $res);
				$this->set('results', $results);
				//pr($results);
				
				$csmArray = $results[0]['CourseStudentMapping'];
				$csm_cm_id = array();
				foreach ($csmArray as $key => $csmDetails) {
					$csm_cm_id[$csmDetails['course_mapping_id']] = $csmDetails['student_id'];
				}
				$this->set(compact('csm_cm_id'));
				
				$courseMapping = $this->CourseMapping->find('all', array(
					'conditions'=>array('CourseMapping.batch_id'=>$batchId, 'CourseMapping.program_id'=>$programId,
							'CourseMapping.indicator'=>0
					),
					'fields'=>array('CourseMapping.id', 'CourseMapping.mandatory'),
					'contain'=>array(
						'Course'=>array(
							'fields'=>array('Course.credit_point')
						)
					)
				));
				//pr($courseMapping);
				
				foreach ($courseMapping as $key => $details) {
					$cm_id_credit_point_array[$details['CourseMapping']['id']] = $details['Course']['credit_point']; 
				}
				$this->set('courseMappingCreditPoint', $cm_id_credit_point_array);
				
				foreach ($courseMapping as $key => $details) {
					$cm_id_mandatory_array[$details['CourseMapping']['id']] = $details['CourseMapping']['mandatory'];
				}
				$this->set('courseMappingMandatory', $cm_id_mandatory_array);
				
				$programArray = $this->Program->find('list', array(
						'conditions'=>array('Program.id'=>$programId,
						),
						'fields'=>array('Program.credits')
				));
				$this->set('programArray', $programArray);
				$this->set('programId', $programId);
				
				$this->layout= false;
				$this->layout= 'print';
				$this->render('migration');
				return false;
			}
		}
		$typeOfCert = $this->TypeOfCertification->find('list',array('order' => array('TypeOfCertification.certification ASC')));
		$academics = $this->Program->Academic->find('list',array('order' => array('Academic.academic_name ASC')));
		$programs = $this->Program->find('list',array('order' => array('Program.program_name ASC')));
		$batches = $this->Batch->find('list', array('fields' => array('Batch.batch_period'),'order' => array('Batch.batch_to DESC')));
		$this->set(compact('academics','programs','batches','typeOfCert'));
		}
		else {
			$this->render('../Users/access_denied');
		}
	}
	
	public function dcSearch(){
		$access_result = $this->checkPathAccess($this->Auth->user('id'));
		if (!$access_result) {
		if($this->request->is('post')) {
			if(isset($this->request->data['submit']) == 'PRINT'){
				
				$examMonth = "";$type_of_cert = "";$programId="";$frm_register_no ="";$to_register_no ="";
				$stuCond=array(); $markCond=array();  $attenCond=array();$tTCond=array();$iExam=array();$eExam=array();$SWMYE = array();
	
				if(isset($this->request->data['markSheet']['batch_id'])){	$batchId = $this->request->data['markSheet']['batch_id'];}
				if(isset($this->request->data['markSheet']['academic_id'])){$Academic = $this->request->data['markSheet']['academic_id'];}
				if(isset($this->request->data['markSheet']['frm_register_no'])){$frm_register_no = $this->request->data['markSheet']['frm_register_no'];}
				if(isset($this->request->data['markSheet']['to_register_no'])){$to_register_no = $this->request->data['markSheet']['to_register_no'];}
				if(isset($this->request->data['markSheet']['convocation_date'])){$convocation_date = $this->request->data['markSheet']['convocation_date'];}
				 
				if(isset($this->request->data['Student']['program_id'])){$programId = $this->request->data['Student']['program_id'];}
				if(isset($this->request->data['markSheet']['monthyears'])){$examMonth = $this->request->data['markSheet']['monthyears'];}
				$this->set(compact('convocation_date'));
				$user_id = $this->Auth->user('id');
				$this->set('logged_in_user', $user_id);
				//Start
				$stuCond['Student.discontinued_status'] = 0;
				$stuCond['Student.picture !='] = '';
				//$stuCond['Student.registration_number'] = '3596001';
				if(($frm_register_no) && ($frm_register_no !='-')){
					$stuCond['Student.registration_number >='] = $frm_register_no;
				}
				if(($to_register_no) && ($to_register_no !='-')){
					$stuCond['Student.registration_number <='] = $to_register_no;
				}
				if($batchId != '-'){
					$stuCond['Student.batch_id'] = $batchId;
				}
				if($programId != '-'){
					$stuCond['Student.program_id'] = $programId;
				}
					
				$res = array(
						'conditions' => array($stuCond,
						),
						'fields' =>array('Student.id','Student.registration_number','Student.name','Student.birth_date','Student.gender','Student.picture','Student.parent_id','Student.university_references_id','Student.tamil_name', 'Student.student_type_id'),
						'contain'=>array(
								'StudentAuthorizedBreak' => array(
										'fields' => array('StudentAuthorizedBreak.student_id')
								),
								'StudentWithdrawal' => array(
										'fields' => array('StudentWithdrawal.student_id')
								),
								'Academic' => array(
										'fields' => array('Academic.short_code','Academic.academic_name','Academic.academic_name_tamil')
								),
								'Batch' => array(
										'fields' => array('Batch.batch_from', 'Batch.batch_to', 'Batch.academic')
								),
								'Program' => array('fields' => array('Program.program_name','Program.short_code','Program.credits','Program.program_name_tamil'),
										'Academic' => array('fields' => array('Academic.academic_name')),
										'Faculty' => array('fields' => array('Faculty.faculty_name','Faculty.faculty_name_tamil'))
								),
								'CourseStudentMapping'=>array(
									'fields'=>array('CourseStudentMapping.id', 'CourseStudentMapping.course_mapping_id'),
									'conditions'=>array('CourseStudentMapping.indicator'=>0)
								),
								'Practical' => array(
										'fields' => array('Practical.month_year_id', 'Practical.marks', 'Practical.student_id'),
										'EsePractical' => array(
												'fields' => array('EsePractical.course_mapping_id')
										)
								),
								'EndSemesterExam' => array(
										'fields' => array('EndSemesterExam.month_year_id', 'EndSemesterExam.marks', 'EndSemesterExam.student_id', 'EndSemesterExam.course_mapping_id', 'EndSemesterExam.dummy_number','EndSemesterExam.revaluation_status'),										
								),
								'ProjectViva' => array(
										'fields' => array('ProjectViva.month_year_id', 'ProjectViva.marks', 'ProjectViva.student_id'),
										'EseProject' => array(
												'fields' => array('EseProject.course_mapping_id')
										)
								),
								'StudentMark' => array(
										'fields' => array('StudentMark.id','StudentMark.month_year_id', 'StudentMark.marks', 'StudentMark.student_id', 'StudentMark.course_mapping_id','StudentMark.status','StudentMark.revaluation_status','StudentMark.final_marks','StudentMark.final_status','StudentMark.grade_point','StudentMark.grade'),
										'CourseMapping'=>array('fields'=>array('CourseMapping.id','CourseMapping.semester_id'),
												'conditions'=>array('CourseMapping.indicator'=>0,
												),
												'Course' => array(
														'fields' => array('Course.id','Course.course_code','Course.course_name','Course.course_max_marks','Course.credit_point'),
														'CourseType' => array('fields' => array('CourseType.course_type'))
												),'order'=>'CourseMapping.semester_id ASC',
										),'order'=>'StudentMark.month_year_id ASC',
										'MonthYear'=>array('fields' =>array('MonthYear.year','MonthYear.publishing_date'),
												'Month'=>array('fields' =>array('Month.month_name'))
										),
								),
								'RevaluationExam' =>array(
										'fields' => array('RevaluationExam.id','RevaluationExam.course_mapping_id','RevaluationExam.revaluation_marks','RevaluationExam.status'),
										'order'=>'RevaluationExam.month_year_id ASC',
										'MonthYear'=>array('fields' =>array('MonthYear.year'),
												'Month'=>array('fields' =>array('Month.month_name'))
										),
								),
								'ParentGroup' => array(
										'fields' => array('ParentGroup.batch_id', 'ParentGroup.academic_id', 'ParentGroup.program_id'),
										'Batch' => array(
												'fields' => array('Batch.batch_from', 'Batch.batch_to', 'Batch.academic')
										),
										'Program' => array('fields' => array('Program.program_name','Program.short_code'),
												'Academic' => array('fields' => array('Academic.academic_name'))
										),
	
										'Practical' => array(
												'fields' => array('Practical.month_year_id', 'Practical.marks', 'Practical.student_id'),
												'EsePractical' => array(
														'fields' => array('EsePractical.course_mapping_id')
												)
										),
										
										'EndSemesterExam' => array(
												'fields' => array('EndSemesterExam.month_year_id', 'EndSemesterExam.marks', 'EndSemesterExam.student_id', 'EndSemesterExam.course_mapping_id', 'EndSemesterExam.dummy_number','EndSemesterExam.revaluation_status'),												
										),
										'ProjectViva' => array(
												'fields' => array('ProjectViva.month_year_id', 'ProjectViva.marks', 'ProjectViva.student_id'),
												'EseProject' => array(
														'fields' => array('EseProject.course_mapping_id')
												)
										),
										'StudentMark' => array(
												'fields' => array('StudentMark.id','StudentMark.month_year_id', 'StudentMark.marks', 'StudentMark.student_id', 'StudentMark.course_mapping_id','StudentMark.status','StudentMark.revaluation_status','StudentMark.final_marks','StudentMark.final_status','StudentMark.grade_point','StudentMark.grade'),
												'CourseMapping'=>array('fields'=>array('CourseMapping.id','CourseMapping.semester_id'),
														'conditions'=>array('CourseMapping.indicator'=>0,
														),
														'Course' => array(
																'fields' => array('Course.id','Course.course_code','Course.course_name','Course.course_max_marks','Course.credit_point'),
																'CourseType' => array('fields' => array('CourseType.course_type'))
														),'order'=>'CourseMapping.semester_id ASC',
												),'order'=>'StudentMark.month_year_id ASC',
												'MonthYear'=>array('fields' =>array('MonthYear.year','MonthYear.publishing_date'),
														'Month'=>array('fields' =>array('Month.month_name'))
												),
										),
										'RevaluationExam' =>array(
												'fields' => array('RevaluationExam.id','RevaluationExam.course_mapping_id','RevaluationExam.revaluation_marks','RevaluationExam.status'),
												'order'=>'RevaluationExam.month_year_id ASC',
												'MonthYear'=>array('fields' =>array('MonthYear.year'),
														'Month'=>array('fields' =>array('Month.month_name'))
												),
										),
								)
						),'recursive'=>0
				);
				//End
				$results = $this->Student->find("all", $res);
				$this->set('results', $results);
				//pr($results);
				
				$courseMapping = $this->CourseMapping->find('all', array(
					'conditions'=>array('CourseMapping.batch_id'=>$batchId, 'CourseMapping.program_id'=>$programId,
							'CourseMapping.indicator'=>0
					),
					'fields'=>array('CourseMapping.id', 'CourseMapping.mandatory'),
					'contain'=>array(
						'Course'=>array(
							'fields'=>array('Course.credit_point')
						)
					)
				));
				//pr($courseMapping);
				
				foreach ($courseMapping as $key => $details) {
					$cm_id_credit_point_array[$details['CourseMapping']['id']] = $details['Course']['credit_point']; 
				}
				$this->set('courseMappingCreditPoint', $cm_id_credit_point_array);
				
				foreach ($courseMapping as $key => $details) {
					$cm_id_mandatory_array[$details['CourseMapping']['id']] = $details['CourseMapping']['mandatory'];
				}
				$this->set('courseMappingMandatory', $cm_id_mandatory_array);
				
				$programArray = $this->Program->find('list', array(
						'conditions'=>array('Program.id'=>$programId,
						),
						'fields'=>array('Program.credits')
				));
				$this->set('programArray', $programArray);
				$this->set('programId', $programId);
				//CEO signature and name
				$getSignature = $this->Signature->find("all", array(
						'conditions' => array('Signature.role LIKE' => "%Chancellor%")
				));
				$this->set('getSignature', $getSignature);				
				
				$this->layout= false;
				$this->layout= 'print';
				$this->render('dc');
				return false;
			}
		}
		$academics = $this->Program->Academic->find('list',array('order' => array('Academic.academic_name ASC')));
		$programs = $this->Program->find('list',array('order' => array('Program.program_name ASC')));
		$batches = $this->Batch->find('list', array('fields' => array('Batch.batch_period'),'order' => array('Batch.batch_to DESC')));
		$this->set(compact('academics','programs','batches'));
		}
		else {
			$this->render('../Users/access_denied');
		}
	}
	public function tcSearch(){
		$access_result = $this->checkPathAccess($this->Auth->user('id'));
		if (!$access_result) {
		if($this->request->is('post')) {
			//pr($this->data);
			$discontinued_date = "Not applicable";
			if(isset($this->request->data['submit']) == 'PRINT'){
				$examMonth = "";$type_of_cert = "";$programId="";$frm_register_no ="";$to_register_no ="";
				$stuCond=array(); $markCond=array();  $attenCond=array();$tTCond=array();$iExam=array();$eExam=array();$SWMYE = array();
	
				if(isset($this->request->data['markSheet']['batch_id'])){	$batchId = $this->request->data['markSheet']['batch_id'];}
				if(isset($this->request->data['markSheet']['academic_id'])){$Academic = $this->request->data['markSheet']['academic_id'];}
				if(isset($this->request->data['markSheet']['frm_register_no'])){$frm_register_no = $this->request->data['markSheet']['frm_register_no'];}
				if(isset($this->request->data['markSheet']['to_register_no'])){$to_register_no = $this->request->data['markSheet']['to_register_no'];}
				if($this->request->data['markSheet']['type_of_cert']){
					$type_of_cert = $this->request->data['markSheet']['type_of_cert'];
					$con_date = $this->request->data['markSheet']['convocation_date'];
					$pgm_completion_status = $this->request->data['markSheet']['pgm_status'];
					if (isset($this->request->data['discontinued_date'])) {
						$discontinued_date = $this->request->data['discontinued_date'];
					}
					//Get Certification Short Code
					$getCertificationId = $this->TypeOfCertification->find('first', array(
							'conditions' => array('TypeOfCertification.id' => $type_of_cert),'recursive' => 0));
					$type_of_cert_code = $getCertificationId['TypeOfCertification']['short_code'];
					$type_of_cert_id = $getCertificationId['TypeOfCertification']['id'];
						
					$getFolioLastNumber = $this->FolioNumber->find('first', array(
							'conditions' => array('FolioNumber.type_of_certification_id' => $type_of_cert_id,'FolioNumber.dates' =>  date('dmy')),'recursive' => 0,'order'=>'FolioNumber.id DESC' ));
					$seqFolioNo = "";
						
					if($getFolioLastNumber){
						$seqFolioNo = (int)$getFolioLastNumber['FolioNumber']['serial_number'];
					}
					if($seqFolioNo){
						$seqFolioNo = $seqFolioNo;
					}else{
						$seqFolioNo = 0;
					}
						
					$type_of_cert = $type_of_cert_code;
				
				}
				if(isset($this->request->data['Student']['program_id'])){$programId = $this->request->data['Student']['program_id'];}
				if(isset($this->request->data['markSheet']['monthyears'])){$examMonth = $this->request->data['markSheet']['monthyears'];}
	
				//Start
				$stuCond['Student.discontinued_status'] = 0;
				$stuCond['Student.picture !='] = '';
				//$stuCond['Student.registration_number'] = '3596001';
				if(($frm_register_no) && ($frm_register_no !='-')){
					$stuCond['Student.registration_number >='] = $frm_register_no;
				}
				if(($to_register_no) && ($to_register_no !='-')){
					$stuCond['Student.registration_number <='] = $to_register_no;
				}
				if($batchId != '-'){
					$stuCond['Student.batch_id'] = $batchId;
				}
				if($programId != '-'){
					$stuCond['Student.program_id'] = $programId;
				}
					
				$res = array(
						'conditions' => array($stuCond,
						),
						'fields' =>array('Student.id','Student.registration_number','Student.name','Student.birth_date','Student.gender','Student.picture','Student.parent_id','Student.university_references_id','Student.tamil_name','Student.father_name','Student.nationality','Student.admission_date'),
						'contain'=>array(
								'Academic' => array(
										'fields' => array('Academic.short_code','Academic.academic_name','Academic.academic_name_tamil')
								),
								'Batch' => array(
										'fields' => array('Batch.batch_from', 'Batch.batch_to', 'Batch.academic','Batch.consolidated_pub_date')
								),
								'Program' => array('fields' => array('Program.program_name','Program.short_code','Program.credits','Program.program_name_tamil'),
										'Academic' => array('fields' => array('Academic.academic_name')),
										'Faculty' => array('fields' => array('Faculty.faculty_name','Faculty.faculty_name_tamil'))
								),								
						),'recursive'=>0
				);
				//End
				$results = $this->Student->find("all", $res);
				$this->set('results', $results);
				$this->set('type_of_cert', $type_of_cert);
				$this->set('seqFolioNo', $seqFolioNo);
				$this->set('pgm_completion_status', $pgm_completion_status);
				$this->set('discontinued_date', $discontinued_date);
				
				$this->FolioNumber->create();
				$data['FolioNumber']['dates']  = date('dmy');
				$data['FolioNumber']['type_of_certification_id'] = $type_of_cert_id;
				$data['FolioNumber']['serial_number'] = ($seqFolioNo + count($results));
				$this->FolioNumber->save($data);
				
				//Register signature and name
				$getSignature = $this->Signature->find("all", array('conditions' => array('Signature.id' => array(1,2))));
				$this->set('getSignature', $getSignature);
				$this->set('con_date', $con_date);
				
				$this->layout= false;
				$this->layout= 'print';
				$this->render('tc');
				return false;
			}
		}
		$typeOfCert = $this->TypeOfCertification->find('list',array('order' => array('TypeOfCertification.certification ASC')));
		$academics = $this->Program->Academic->find('list',array('order' => array('Academic.academic_name ASC')));
		$programs = $this->Program->find('list',array('order' => array('Program.program_name ASC')));
		$batches = $this->Batch->find('list', array('fields' => array('Batch.batch_period'),'order' => array('Batch.batch_to DESC')));
		$this->set(compact('academics','programs','batches','typeOfCert'));
		}
		else {
			$this->render('../Users/access_denied');
		}
	}
	public function cMSearch(){
		$access_result = $this->checkPathAccess($this->Auth->user('id'));
		if (!$access_result) {
		if($this->request->is('post')) { 
			if(isset($this->request->data['submit']) == 'PRINT'){
				$examMonth = "";$type_of_cert = "";$programId="";$frm_register_no ="";$to_register_no ="";
				$stuCond=array(); $markCond=array();  $attenCond=array();$tTCond=array();$iExam=array();$eExam=array();$SWMYE = array();
				$reg_num="-";
				
				if(isset($this->request->data['markSheet']['batch_id'])){	$batchId = $this->request->data['markSheet']['batch_id'];}
				if(isset($this->request->data['markSheet']['academic_id'])){$Academic = $this->request->data['markSheet']['academic_id'];}
				if(isset($this->request->data['markSheet']['frm_register_no'])){$frm_register_no = $this->request->data['markSheet']['frm_register_no'];}
				if(isset($this->request->data['markSheet']['to_register_no'])){$to_register_no = $this->request->data['markSheet']['to_register_no'];}
				if($this->request->data['markSheet']['type_of_cert']){
					$type_of_cert = $this->request->data['markSheet']['type_of_cert'];
					//Get Certification Short Code
					$getCertificationId = $this->TypeOfCertification->find('first', array(
							'conditions' => array('TypeOfCertification.id' => $type_of_cert),'recursive' => 0));
					$type_of_cert_code = $getCertificationId['TypeOfCertification']['short_code'];
					$type_of_cert_id = $getCertificationId['TypeOfCertification']['id'];
						
					$getFolioLastNumber = $this->FolioNumber->find('first', array(
							'conditions' => array('FolioNumber.type_of_certification_id' => $type_of_cert_id,'FolioNumber.dates' =>  date('dmy')),'recursive' => 0,'order'=>'FolioNumber.id DESC' ));
					$seqFolioNo = "";
						
					if($getFolioLastNumber){
						$seqFolioNo = (int)$getFolioLastNumber['FolioNumber']['serial_number'];
					}
					if($seqFolioNo){
						$seqFolioNo = $seqFolioNo;
					}else{
						$seqFolioNo = 0;
					}
						
					$type_of_cert = $type_of_cert_code;
				
				}
				if(isset($this->request->data['Student']['program_id'])){$programId = $this->request->data['Student']['program_id'];}
				if(isset($this->request->data['markSheet']['monthyears'])){$examMonth = $this->request->data['markSheet']['monthyears'];}
	
				//Start
				
				$results = $this->getStudentData($batchId, $programId, $examMonth, $frm_register_no, $to_register_no, $reg_num);
				//pr($results); 
				//End
				
				$this->set('results', $results);
				$this->set('type_of_cert', $type_of_cert);
				
				//CSMArray START
				$csmArray = $this->csmStudentData($batchId, $programId, $examMonth, $frm_register_no, $to_register_no, $reg_num);
				//pr($csmArray); 
				//echo count($csmArray['3141']); die; 
				//csmArray END
				
				//CEO signature and name
				$getSignature = $this->Signature->find("all", array('conditions' => array('Signature.id' => array(1,2))));
				$this->set('getSignature', $getSignature);
				$this->set('seqFolioNo', $seqFolioNo);
				$this->set('csmArray', $csmArray);
				
				$this->set('examMonth', $examMonth);
				
				$this->FolioNumber->create();
				$data['FolioNumber']['dates']  = date('dmy');
				$data['FolioNumber']['type_of_certification_id'] = $type_of_cert_id;
				$data['FolioNumber']['serial_number'] = ($seqFolioNo + count($results));
				$this->FolioNumber->save($data);
				
				$this->layout= false;
				$this->layout= 'print';
				$this->render('cM');
				return false;
			}
		}
		$typeOfCert = $this->TypeOfCertification->find('list',array('order' => array('TypeOfCertification.certification ASC')));
		$academics = $this->Program->Academic->find('list',array('order' => array('Academic.academic_name ASC')));
		$programs = $this->Program->find('list',array('order' => array('Program.program_name ASC')));
		$batches = $this->Batch->find('list', array('fields' => array('Batch.batch_period'),'order' => array('Batch.batch_to DESC')));
		$this->set(compact('academics','programs','batches','typeOfCert'));
		}
		else {
			$this->render('../Users/access_denied');
		} 
	}
	
	public function csmStudentData($batchId, $programId, $examMonth, $frm_register_no, $to_register_no, $reg_num) {
		$csmCond = array();
		if ($reg_num != '-') {
			$stuCond['Student.registration_number'] = $reg_num;
		}
		if(($frm_register_no) && ($frm_register_no !='-')){
			$csmCond['Student.registration_number >='] = $frm_register_no;
		}
		if(($to_register_no) && ($to_register_no !='-')){
			$csmCond['Student.registration_number <='] = $to_register_no;
		}
		if($batchId != '-'){
			$csmCond['Student.batch_id'] = $batchId;
		}
		if($programId != '-'){
			$csmCond['Student.program_id'] = $programId;
		}
		
		$res1 = array(
				'conditions' => array($csmCond,
				),
				'fields' =>array('Student.id','Student.parent_id','Student.university_references_id'),
				'contain'=>array(
						'CourseStudentMapping'=>array(
								'fields'=>array('CourseStudentMapping.course_mapping_id',
										'CourseStudentMapping.indicator',
								),
								'CourseMapping'=>array(
										'fields'=>array('CourseMapping.semester_id'),
										'Course' => array(
												'fields' => array('Course.id', 'Course.credit_point'),
										),
								)
						),
						'ParentGroup' => array(
								'CourseStudentMapping'=>array(
										'fields'=>array('CourseStudentMapping.course_mapping_id',
												'CourseStudentMapping.indicator',
										),
								),
								'CourseMapping'=>array(
										'fields'=>array('CourseMapping.semester_id'),
										'Course' => array(
												'fields' => array('Course.id', 'Course.credit_point'),
										),
								)
						)
				));
		$csmResult = $this->Student->find('all', $res1);
		//pr($csmResult);
		$csmArray = array();
		foreach ($csmResult as $key => $csm) {
			$innerArray = $csm['CourseStudentMapping'];
			$student_id = $csm['Student']['id'];
			$csmArray=$this->cm($innerArray, $csmArray, $student_id);
			if(isset($csm['Student']['parent_id'])) {
				$parent_id = $csm['Student']['parent_id'];
				$pInnerArray = $csm['ParentGroup']['CourseStudentMapping'];
				$csmArray=$this->cm($pInnerArray, $csmArray, $student_id);
			}
		
		}
		return $csmArray;
	}
	
	public function getStudentData($batchId, $programId, $examMonth, $frm_register_no, $to_register_no, $reg_num) {
		$stuCond=array();
		$stuCond['Student.discontinued_status'] = 0;
		$stuCond['Student.picture !='] = '';
		
		if ($reg_num != '-') {
			$stuCond['Student.registration_number'] = $reg_num;
		}
		if(($frm_register_no) && ($frm_register_no !='-')){
			$stuCond['Student.registration_number >='] = $frm_register_no;
		}
		if(($to_register_no) && ($to_register_no !='-')){
			$stuCond['Student.registration_number <='] = $to_register_no;
		}
		if($batchId != '-'){
			$stuCond['Student.batch_id'] = $batchId;
		}
		if($programId != '-'){
			$stuCond['Student.program_id'] = $programId;
		}
			
		$res = array(
				'conditions' => array($stuCond,
				),
				'fields' =>array('Student.id','Student.registration_number','Student.name','Student.birth_date',
						'Student.gender','Student.picture','Student.parent_id','Student.university_references_id',
						'Student.tamil_name', 'Student.signature', 'Student.aadhar'),
				'contain'=>array(
						'StudentAuthorizedBreak' => array(
								'fields' => array('StudentAuthorizedBreak.student_id')
						),
						'StudentWithdrawal' => array(
								'fields' => array('StudentWithdrawal.student_id')
						),
						'Academic' => array(
								'fields' => array('Academic.short_code','Academic.academic_name','Academic.academic_name_tamil')
						),
						'Batch' => array(
								'fields' => array('Batch.batch_from', 'Batch.batch_to', 'Batch.academic',
										'Batch.consolidated_pub_date')
						),
						'Program' => array('fields' => array('Program.program_name','Program.short_code','Program.credits','Program.program_name_tamil'),
								'Academic' => array('fields' => array('Academic.academic_name')),
								'Faculty' => array('fields' => array('Faculty.faculty_name','Faculty.faculty_name_tamil'))
						),
						'CourseStudentMapping'=>array(
								'fields' => array('CourseStudentMapping.course_mapping_id','CourseStudentMapping.type',
								'CourseStudentMapping.indicator', 'CourseStudentMapping.new_semester_id'),
								'CourseMapping'=>array('fields'=>array('CourseMapping.id','CourseMapping.semester_id',
										'CourseMapping.month_year_id'),
										'conditions'=>array('CourseMapping.indicator'=>0,
										),
										'Course' => array(
												'fields' => array('Course.id','Course','Course.credit_point'),
												'CourseType' => array('fields' => array('CourseType.course_type'))
										),'order'=>array('CourseMapping.semester_id ASC','CourseMapping.course_number ASC')
								),
						),
						'Practical' => array(
								'fields' => array('Practical.month_year_id', 'Practical.marks', 'Practical.student_id'),
								'EsePractical' => array(
										'fields' => array('EsePractical.course_mapping_id')
								)
						),
						'EndSemesterExam' => array(
								'fields' => array('EndSemesterExam.month_year_id', 'EndSemesterExam.marks', 'EndSemesterExam.student_id', 'EndSemesterExam.course_mapping_id', 'EndSemesterExam.dummy_number','EndSemesterExam.revaluation_status'),
						),
						'ProjectViva' => array(
								'fields' => array('ProjectViva.month_year_id', 'ProjectViva.marks', 'ProjectViva.student_id'),
								'conditions' => array('ProjectViva.month_year_id' => $examMonth),
								'EseProject' => array(
										'fields' => array('EseProject.course_mapping_id')
								)
						),
						'StudentMark' => array(
								'fields' => array('StudentMark.id','StudentMark.month_year_id', 'StudentMark.marks', 'StudentMark.student_id',
										'StudentMark.course_mapping_id','StudentMark.status','StudentMark.revaluation_status',
										'StudentMark.final_marks','StudentMark.final_status','StudentMark.grade_point','StudentMark.grade'),
								'CourseMapping'=>array('fields'=>array('CourseMapping.id','CourseMapping.semester_id',
										'CourseMapping.month_year_id'),
										'conditions'=>array('CourseMapping.indicator'=>0,
										),
										'Course' => array(
												'fields' => array('Course.id','Course.course_code','Course.course_name',
														'Course.course_max_marks','Course.total_min_pass','Course.credit_point'),
												'CourseType' => array('fields' => array('CourseType.course_type'))
										),'order'=>array('CourseMapping.semester_id ASC','CourseMapping.course_number ASC')
								),'order'=>array('StudentMark.month_year_id ASC','StudentMark.course_mapping_id ASC'),
								'MonthYear'=>array('fields' =>array('MonthYear.year','MonthYear.publishing_date'),
										'Month'=>array('fields' =>array('Month.month_name'))
								),
						),
						'RevaluationExam' =>array(
								'fields' => array('RevaluationExam.id','RevaluationExam.course_mapping_id','RevaluationExam.revaluation_marks',
										'RevaluationExam.status'),
								'order'=>'RevaluationExam.month_year_id ASC',
								'MonthYear'=>array('fields' =>array('MonthYear.year'),
										'Month'=>array('fields' =>array('Month.month_name'))
								),
						),
				),'recursive'=>0
		);
		$results = $this->Student->find("all", $res);
		return $results; 
	}
	
	public function mSearch() {
		if ($this->request->is('post')) {
			//pr($this->data);
			$finalArray = array();
			
			$reg_num = $this->request->data['Student']['registration_number'];
			$student = $this->Student->getBatchAndProgramIdFromStudentRegNo($reg_num);
			//pr($student);
			$batchId = $student[$reg_num]['batch_id'];
			$programId = $student[$reg_num]['program_id'];
			$studentId = $student[$reg_num]['id'];
			
			$examMonth = "-";
			$frm_register_no = "-";
			$to_register_no = "-";
			
			$results = $this->getStudentData($batchId, $programId, $examMonth, $frm_register_no, $to_register_no, $reg_num);
			//pr($results);
			$csmArray = $this->csmStudentData($batchId, $programId, $examMonth, $frm_register_no, $to_register_no, $reg_num);
			
			$stuResult = $this->processStudentData($results, $csmArray, $examMonth);
			//pr($stuResult);
			$finalArray[$studentId] = $stuResult[$studentId];
			//pr($finalArray);
			if (isset($finalArray)) {
				$this->printMarkdata($finalArray);
			} else {
				echo "No Record Found";
			}
		}
	}
	
	public function stuMarkSearch() {
		if($this->request->is('post')) {
			//pr($this->data);
			$finalArray = array();
			if(!empty($this->request->data['Student']['file']['name'])) {
				move_uploaded_file($this->request->data['Student']['file']['tmp_name'], WWW_ROOT . '../sets_upload_received_file/' .$this->request->data['Student']['file']['name']);
				$filename = WWW_ROOT . '../sets_upload_received_file/'.$this->request->data['Student']['file']['name'];
				//echo $filename;
				$objPHPExcel = PHPExcel_IOFactory::load(WWW_ROOT . '../sets_upload_received_file/'.$this->request->data['Student']['file']['name']);
				$worksheet = $objPHPExcel->setActiveSheetIndex(0);
				//foreach ($objPHPExcel->getWorksheetIterator() as $worksheet) {
				$worksheetTitle     = $worksheet->getTitle();
				$highestRow         = $worksheet->getHighestRow(); // e.g. 10
				$highestColumn      = $worksheet->getHighestDataColumn(); // e.g 'F'
				$highestColumnIndex = PHPExcel_Cell::columnIndexFromString($highestColumn);
				$nrColumns = ord($highestColumn) - 64;
				for ($i=2; $i<=$highestRow; $i++) {
					$cell = $worksheet->getCellByColumnAndRow(0, $i);
					$regNum = $cell->getValue();
					//echo $regNum; 
					//$reg_num = $this->request->data['Student']['registration_number'];
					$student = $this->Student->getBatchAndProgramIdFromStudentRegNo($regNum);
					//pr($student);
					$batchId = $student[$regNum]['batch_id'];
					$programId = $student[$regNum]['program_id'];
					$studentId = $student[$regNum]['id'];
					$finalArray[$studentId] = array();
					
					$examMonth = "-";
					$frm_register_no = "-";
					$to_register_no = "-";
						
					$results = $this->getStudentData($batchId, $programId, $examMonth, $frm_register_no, $to_register_no, $regNum);
					//pr($results);
					
					$csmArray = $this->csmStudentData($batchId, $programId, $examMonth, $frm_register_no, $to_register_no, $regNum);
					//pr($csmArray);
					
					$stuResult = $this->processStudentData($results, $csmArray, $examMonth);
					$finalArray[$studentId] = $stuResult[$studentId]; 
				}
				//pr($finalArray);
				if (isset($finalArray)) {
					$this->printMarkdata($finalArray);
				} else {
					echo "No Record Found";
				}
			} else {
				echo "File Upload Error";
			}
			
			
		}
	}
	
	public function printMarkdata($finalArray) {
		$html = ""; $stuhead = "";
		$head = "<table class='cmainhead2' border='0' align='center'  style='font-family:Arial !important;font-size:16px !important;'>
			<tr>
			<td rowspan='2'><img src='../webroot/img/user.jpg'></td>
			<td align='center'>SATHYABAMA UNIVERSITY<br/>
			<span class='slogan'>CONSOLIDATED MARK VIEW</span></td>
			</tr>
			</table>";
		foreach ($finalArray as $studentId => $array) { 
			//pr($array['res_count']); 
			$cnt = 1;
			$stuhead = ""; $pass_count = ""; $fail_count = "";
			$total_courses = count($array['marks']);
			if (isset($array['res_count']['Pass'])) $pass_count = $array['res_count']['Pass'];
			if (isset($array['res_count']['Fail'])) $fail_count = $array['res_count']['Fail'];
			
			$stuhead .= "<table class='attendanceHeadTblP' cellpadding='0' cellspacing='0' border='1' 
					style='font-family:Arial !important;font-size:12px !important;text-indent:10px;width:100%;'>
			<tr>
			<td style='height:30px;width:30%;' align='left'>&nbsp;Name</td>
			<td align='left' style='width:50%;'>&nbsp;".$array['name']."</td>
			<td style='width:20%;' align='left'>&nbsp;Register No.</td>
			<td align='left' style='height:30px;width:20%;'>&nbsp;".$array['reg_number']."</td>
			</tr>
			<tr>
			<td style='height:30px;' align='left'>&nbsp;Programme & Branch</td>
			<td align='left'>&nbsp;".$array['program']."</td>
			<td align='left'>&nbsp;Batch</td>
			<td align='left'>&nbsp;".$array['batch']."</td>
			</tr>
			</table>";
			$stuhead .= "</br><table class='attendanceHeadTblP' cellpadding='0' cellspacing='0' border='1'
					style='font-family:Arial !important;font-size:12px !important;text-indent:10px;width:100%;margin-top:20px;'>
			<tr>
			<td style='height:30px;width:25%;' align='left'>&nbsp;No. of Passed Courses</td>
			<td align='left' style='width:25%;'>&nbsp;".$pass_count."</td>
			<td style='width:25%;' align='left'>&nbsp;No. of Failed Courses</td>
			<td align='left' style='height:30px;width:25%;'>&nbsp;".$fail_count."</td>
			</tr>
			</table>";
			$html .= $head.$stuhead;
			$html .= "<br/><table class='attendanceHeadTblP' cellpadding='0' cellspacing='0' border='1' style='font-family:Arial !important;font-size:12px !important;text-indent:3px;'>";
			//if (isset($array['no_of_arrears']) && count($array['no_of_arrears'] > 0)) {
			$html .= "<tr>
			<th style='width:80px;height:26px;' align='center'>S. No.</th>
			<th style='width:80px;' align='center'>Semester</th>
			<th style='width:110px;' align='center'>Course Code</th>
			<th style='width:250px;' align='center'>Course Name</th>
			<th style='width:170px;' align='center'>Course Type</th>
			<th style='width:250px;' align='center'>Month Year of Exam</th>
			<th style='width:80px;' align='center'>CAE</th>
			<th style='width:80px;' align='center'>ESE</th>
			<th style='width:80px;' align='center'>TOTAL</th>
			<th style='width:80px;' align='center'>STATUS</th>
			</tr>";
			
			$allCourses = $array['marks'];
			foreach ($allCourses as $cm_id => $value) { 
				$html .= "<tr>
				<td style='width:80px;height:26px;' align='center'>".$cnt."</td>
				<td style='width:80px;' align='center'>".$value['semester_id']."</td>
				<td style='width:110px;' align='center'>".$value['course_code']."</td>
				<td style='width:250px;' align='center'>".$value['course_name']."</td>
				<td style='width:170px;' align='center'>".$value['course_type']."</td>
				<td style='width:250px;' align='center'>".$value['month_year']."</td>
				<td style='width:50px;' align='center'>".$value['cae']."</td>
				<td style='width:50px;' align='center'>".$value['ese']."</td>
				<td style='width:50px;' align='center'>".$value['total']."</td>
				<td style='width:50px;' align='center'>".$value['status']."</td>
				</tr>";
				$cnt++;
			}
			$html .= "</table><div style='page-break-after:always'></div>";
		}
		if ($html) {
			$html = substr($html,0,-43);
			$this->mPDF->init();
			// setting filename of output pdf file
			$this->mPDF->setFilename('CONSOLIDATE_MARK_STATEMENT_'.date('d_M_Y').'.pdf');
			// setting output to I, D, F, S
			$this->mPDF->setOutput('D');
			//$this->mPDF->AddPage('L','', '', '', '',30,30,30,30,18,12);
			$this->mPDF->WriteHTML($html);
			// you can call any mPDF method via component, for example:
			$this->mPDF->SetWatermarkText("Draft");
		}
		$this->autoLayout = false;
		$this->autoRender = false;
	}
	
	public function pdcSearch(){
		$access_result = $this->checkPathAccess($this->Auth->user('id'));
		if (!$access_result) {
		if($this->request->is('post')) {
			if(isset($this->request->data['submit']) == 'PRINT'){
				
				$examMonth = "";$type_of_cert = "";$programId="";$frm_register_no ="";$to_register_no ="";
				$stuCond=array(); $markCond=array();  $attenCond=array();$tTCond=array();$iExam=array();$eExam=array();$SWMYE = array();
	
				if(isset($this->request->data['markSheet']['batch_id'])){	$batchId = $this->request->data['markSheet']['batch_id'];}
				if(isset($this->request->data['markSheet']['academic_id'])){$Academic = $this->request->data['markSheet']['academic_id'];}
				if(isset($this->request->data['markSheet']['frm_register_no'])){$frm_register_no = $this->request->data['markSheet']['frm_register_no'];}
				if(isset($this->request->data['markSheet']['to_register_no'])){$to_register_no = $this->request->data['markSheet']['to_register_no'];}
				if(isset($this->request->data['markSheet']['convocation_date'])){$convocation_date = $this->request->data['markSheet']['convocation_date'];}
				 
				if(isset($this->request->data['Student']['program_id'])){$programId = $this->request->data['Student']['program_id'];}
				if(isset($this->request->data['markSheet']['monthyears'])){$examMonth = $this->request->data['markSheet']['monthyears'];}
				$this->set(compact('convocation_date'));
				$user_id = $this->Auth->user('id');
				$this->set('logged_in_user', $user_id);
				//Start
				$stuCond['Student.discontinued_status'] = 0;
				$stuCond['Student.picture !='] = '';
				//$stuCond['Student.registration_number'] = '3596001';
				if(($frm_register_no) && ($frm_register_no !='-')){
					$stuCond['Student.registration_number >='] = $frm_register_no;
				}
				if(($to_register_no) && ($to_register_no !='-')){
					$stuCond['Student.registration_number <='] = $to_register_no;
				}
				if($batchId != '-'){
					$stuCond['Student.batch_id'] = $batchId;
				}
				if($programId != '-'){
					$stuCond['Student.program_id'] = $programId;
				}
					
				$res = array(
						'conditions' => array($stuCond,
						),
						'fields' =>array('Student.id','Student.registration_number','Student.name','Student.birth_date','Student.gender','Student.picture','Student.parent_id','Student.university_references_id','Student.tamil_name', 'Student.student_type_id'),
						'contain'=>array(
								'StudentAuthorizedBreak' => array(
										'fields' => array('StudentAuthorizedBreak.student_id')
								),
								'StudentWithdrawal' => array(
										'fields' => array('StudentWithdrawal.student_id')
								),
								'Academic' => array(
										'fields' => array('Academic.short_code','Academic.academic_name','Academic.academic_name_tamil')
								),
								'Batch' => array(
										'fields' => array('Batch.batch_from', 'Batch.batch_to', 'Batch.academic')
								),
								'Program' => array('fields' => array('Program.program_name','Program.short_code','Program.credits','Program.program_name_tamil'),
										'Academic' => array('fields' => array('Academic.academic_name')),
										'Faculty' => array('fields' => array('Faculty.faculty_name','Faculty.faculty_name_tamil'))
								),
								'CourseStudentMapping'=>array(
									'fields'=>array('CourseStudentMapping.id', 'CourseStudentMapping.course_mapping_id',
											'CourseStudentMapping.indicator', 'CourseStudentMapping.new_semester_id'
									),
								),
								'StudentMark' => array(
										'fields' => array('StudentMark.id','StudentMark.month_year_id', 'StudentMark.marks', 'StudentMark.student_id', 'StudentMark.course_mapping_id','StudentMark.status','StudentMark.revaluation_status','StudentMark.final_marks','StudentMark.final_status','StudentMark.grade_point','StudentMark.grade'),
										'CourseMapping'=>array('fields'=>array('CourseMapping.id','CourseMapping.semester_id'),
												'conditions'=>array('CourseMapping.indicator'=>0,
												),
												'Course' => array(
														'fields' => array('Course.id','Course.course_code','Course.course_name','Course.course_max_marks','Course.credit_point'),
														'CourseType' => array('fields' => array('CourseType.course_type'))
												),'order'=>'CourseMapping.semester_id ASC',
										),'order'=>'StudentMark.month_year_id ASC',
										'MonthYear'=>array('fields' =>array('MonthYear.year','MonthYear.publishing_date'),
												'Month'=>array('fields' =>array('Month.month_name'))
										),
								),
								'RevaluationExam' =>array(
										'fields' => array('RevaluationExam.id','RevaluationExam.course_mapping_id','RevaluationExam.revaluation_marks','RevaluationExam.status'),
										'order'=>'RevaluationExam.month_year_id ASC',
										'MonthYear'=>array('fields' =>array('MonthYear.year'),
												'Month'=>array('fields' =>array('Month.month_name'))
										),
								),
								/* 'ParentGroup' => array(
										'fields' => array('ParentGroup.batch_id', 'ParentGroup.academic_id', 'ParentGroup.program_id'),
										'Batch' => array(
												'fields' => array('Batch.batch_from', 'Batch.batch_to', 'Batch.academic')
										),
										'Program' => array('fields' => array('Program.program_name','Program.short_code'),
												'Academic' => array('fields' => array('Academic.academic_name'))
										),
										'StudentMark' => array(
												'fields' => array('StudentMark.id','StudentMark.month_year_id', 'StudentMark.marks', 'StudentMark.student_id', 'StudentMark.course_mapping_id','StudentMark.status','StudentMark.revaluation_status','StudentMark.final_marks','StudentMark.final_status','StudentMark.grade_point','StudentMark.grade'),
												'CourseMapping'=>array('fields'=>array('CourseMapping.id','CourseMapping.semester_id'),
														'conditions'=>array('CourseMapping.indicator'=>0,
														),
														'Course' => array(
																'fields' => array('Course.id','Course.course_code','Course.course_name','Course.course_max_marks','Course.credit_point'),
																'CourseType' => array('fields' => array('CourseType.course_type'))
														),'order'=>'CourseMapping.semester_id ASC',
												),'order'=>'StudentMark.month_year_id ASC',
												'MonthYear'=>array('fields' =>array('MonthYear.year','MonthYear.publishing_date'),
														'Month'=>array('fields' =>array('Month.month_name'))
												),
										),
								) */
						),'recursive'=>0
				);
				//End
				$results = $this->Student->find("all", $res);
				$this->set('results', $results);
				//pr($results);
				
				$csmArray = $results[0]['CourseStudentMapping'];
				$csm_cm_id = array();
				foreach ($csmArray as $key => $csmDetails) {
					$csm_cm_id[$csmDetails['course_mapping_id']] = $csmDetails['student_id'];
				}
				$this->set(compact('csm_cm_id'));
				
				$courseMapping = $this->CourseMapping->find('all', array(
					'conditions'=>array('CourseMapping.batch_id'=>$batchId, 'CourseMapping.program_id'=>$programId,
							'CourseMapping.indicator'=>0
					),
					'fields'=>array('CourseMapping.id', 'CourseMapping.mandatory'),
					'contain'=>array(
						'Course'=>array(
							'fields'=>array('Course.credit_point')
						)
					)
				));
				//pr($courseMapping);
				
				foreach ($courseMapping as $key => $details) {
					$cm_id_credit_point_array[$details['CourseMapping']['id']] = $details['Course']['credit_point']; 
				}
				$this->set('courseMappingCreditPoint', $cm_id_credit_point_array);
				
				foreach ($courseMapping as $key => $details) {
					$cm_id_mandatory_array[$details['CourseMapping']['id']] = $details['CourseMapping']['mandatory'];
				}
				$this->set('courseMappingMandatory', $cm_id_mandatory_array);
				
				$programArray = $this->Program->find('list', array(
						'conditions'=>array('Program.id'=>$programId,
						),
						'fields'=>array('Program.credits')
				));
				$this->set('programArray', $programArray);
				$this->set('programId', $programId);
				
				$this->layout= false;
				$this->layout= 'print';
				$this->render('pdc');
				return false;
			}
		}
		$typeOfCert = $this->TypeOfCertification->find('list',array('order' => array('TypeOfCertification.certification ASC')));
		$academics = $this->Program->Academic->find('list',array('order' => array('Academic.academic_name ASC')));
		$programs = $this->Program->find('list',array('order' => array('Program.program_name ASC')));
		$batches = $this->Batch->find('list', array('fields' => array('Batch.batch_period'),'order' => array('Batch.batch_to DESC')));
		$this->set(compact('academics','programs','batches','typeOfCert'));
		}
		else {
			$this->render('../Users/access_denied');
		}
	}
	
	public function consolidate_mark_sheet($regNo = null) {		
		$this->layout=false;
	}	
	public function studentSearch() {		
		if($this->request->is('post')){	
			
			if (isset($this->data)) {
				$conditions = "";
				SWITCH (true) {
					case (($this->data['Student']['batch_id'] > 0 ) && ($this->data['Student']['academic_id'] > 0) && ($this->data['Student']['program_id'] > 0)):
						$conditions= array('Student.batch_id'=> $this->data['Student']['batch_id'],
								'Student.academic_id'=> $this->data['Student']['academic_id'],
								'Student.program_id'=> $this->data['Student']['program_id'],
								'Student.discontinued_status'=> 0,);
						break;
					case (($this->data['Student']['batch_id'] > 0 ) && ($this->data['Student']['program_id'] > 0)):
						$conditions= array('Student.batch_id'=> $this->data['Student']['batch_id'],
						'Student.academic_id'=> $this->data['Student']['program_id'],
						'Student.discontinued_status'=> 0,);
						break;
					case (($this->data['Student']['batch_id'] > 0 ) && ($this->data['Student']['academic_id'] > 0)):
						$conditions= array('Student.batch_id'=> $this->data['Student']['batch_id'],
								'Student.academic_id'=> $this->data['Student']['academic_id'],
								'Student.discontinued_status'=> 0,);
						break;
					case ($this->data['Student']['academic_id'] > 0  && $this->data['Student']['program_id'] > 0):
						$conditions= array('Student.academic_id'=> $this->data['Student']['academic_id'],
								'Student.program_id'=> $this->data['Student']['program_id'],
								'Student.discontinued_status'=> 0,); 
						break;
					case ($this->data['Student']['batch_id'] > 0):
						$conditions= array('Student.batch_id'=> $this->data['Student']['batch_id'],
								'Student.discontinued_status'=> 0,); 
						break;
					case ($this->data['Student']['academic_id'] > 0 ):
						$conditions= array('Student.academic_id'=> $this->data['Student']['academic_id'],
						'Student.discontinued_status'=> 0,);
						break;					
				}
	
				$results = $this->Student->find('all', array(
						'conditions' => $conditions,
						'fields' =>array('Student.id','Student.name','Student.user_initial','Student.tamil_name',
								'Student.tamil_initial','Student.father_name','Student.mother_name','Student.registration_number',
								'Student.roll_number','Student.specialisation','Student.batch_id','Student.program_id','Student.academic_id',
								'Student.student_type_id','Student.university_references_id','Student.month_year_id','Student.birth_date',
								'Student.gender','Student.nationality','Student.religion','Student.community','Student.address',
								'Student.city','Student.stat','Student.country','Student.pincode','Student.phone_number','Student.email',
								'Student.mobile_number','Student.signature','Student.picture','Student.admission_date','Student.discontinued_status',
								'Student.reason','Student.prior_batch','Student.addlfield3','Student.addlfield4','Student.aadhar',
								'Student.indicator','Student.created_by','Student.modified_by','Student.created','Student.modified'),
						
						'contain'=>array(
								'Batch'=>array('fields' =>array('Batch.batch_from','Batch.batch_to','Batch.academic')),
								'Program'=>array('fields' =>array('Program.program_name')),
								'Academic'=>array('fields' =>array('Academic.academic_name')),
								'StudentType'=>array('fields' =>array('StudentType.type'))
						),						
						'recursive'=>0
				));
				
				$this->set('stuList', $results);
				
				$programs = $this->Student->Program->find('list', array(
						'conditions' => array('Program.academic_id'=> $this->request->data['Student']['academic_id'])));
				//pr($programs);
				$this->set(compact('programs'));
								
			}
		}
		$academics = $this->Student->Academic->find('list',array('order' => array('Academic.academic_name ASC')));
		$programs = $this->Program->find('list',array('order' => array('Program.program_name ASC')));
		$batches = $this->Student->Batch->find('list', array('fields' => array('Batch.batch_period'),'order' => array('Batch.batch_to DESC')));
		$this->set(compact('batches', 'academics','programs'));

	}
	
	public function photo_list(){
		if(isset($this->request->data['submit']) == 'PDF') { 
			$conditions = "";			
			SWITCH (true) {				
			case (($this->data['Student']['batch_id'] > 0 ) && ($this->data['Student']['academic_id'] > 0) && ($this->data['Student']['program_id'] > 0)):
			$conditions= array('Student.batch_id'=> $this->data['Student']['batch_id'],
			'Student.academic_id'=> $this->data['Student']['academic_id'],
			'Student.program_id'=> $this->data['Student']['program_id'],
			'Student.discontinued_status'=> 0,);
			break;
			case (($this->data['Student']['batch_id'] > 0 ) && ($this->data['Student']['academic_id'] > 0)):
			$conditions= array('Student.batch_id'=> $this->data['Student']['batch_id'],
			'Student.academic_id'=> $this->data['Student']['academic_id'],
			'Student.discontinued_status'=> 0,);
			break;
			case (($this->data['Student']['academic_id'] > 0 ) && ($this->data['Student']['program_id'] > 0)):
			$conditions= array('Student.academic_id'=> $this->data['Student']['academic_id'],
			'Student.program_id'=> $this->data['Student']['program_id'],
			'Student.discontinued_status'=> 0,);
			break;
			case ($this->data['Student']['batch_id'] > 0):
			$conditions= array('Student.batch_id'=> $this->data['Student']['batch_id'],
			'Student.discontinued_status'=> 0,);
			break;
			case ($this->data['Student']['academic_id'] > 0 ):
			$conditions= array('Student.academic_id'=> $this->data['Student']['academic_id'],
			'Student.discontinued_status'=> 0,);
			break;
			}
			$results = $this->Student->find('all', array(
					'conditions' => $conditions,
					'fields' =>array('Student.id','Student.name','Student.user_initial','Student.tamil_name',
							'Student.father_name','Student.registration_number',
							'Student.roll_number','Student.signature','Student.picture','Student.birth_date','Student.gender','Student.mobile_number','Student.gender'),			
					'contain'=>array(
								'Batch'=>array('fields' =>array('Batch.batch_from','Batch.batch_to','Batch.academic')),
								'Program'=>array('fields' =>array('Program.program_name')),
								'Academic'=>array('fields' =>array('Academic.academic_name')),
								'StudentType'=>array('fields' =>array('StudentType.type'))
					),
					'recursive'=>0,
			));
			
			 $html = "";
			 //$html.='<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />';
			 if($results){
			 $head = "<table class='cmainhead2' border='0' align='center'>
			 <tr>
			 <td rowspan='2'><img src='../webroot/img/user.jpg'></td>
			 <td align='center'>SATHYABAMA UNIVERSITY<br/>
			 <span class='slogan'>STUDENT PHOTO LIST </span></td>
			 </tr>
			 </table>";
			 $html .=$head;
			 $html .="<table class='attendanceHeadTblP' cellpadding='0' cellspacing='0' border='1' style='font-family:'Open sans' !important;font-size:12px !important;text-indent:3px;'>
			 <tr>
			 <th>S.No.</th>
			 <th style='width:80px;' align='center'>REG NO.</th>
			 <th style='width:100px;' align='center'>ROLL NO.</th>
			 <th style='width:150px;' align='center'>NAME</th>
			 <th style='width:100px;' align='center'>TAMIL NAME</th>
			 <th style='width:100px;' align='center'>FATHER NAME</th>
			 <th style='width:100px;' align='center'>DOB</th>
			 <th style='width:80px;' align='center'>GENDER</th>
			 <th style='width:100px;' align='center'>PHOTO</th>
			 <th style='width:120px;' align='center'>SIGNATURE</th>
			 </tr>";
			 $i=1;
			 for($p=0;$p<count($results);$p++){
				 $html .="<tr>";
				 $html .="<td style='height:27px;' align='center'>".$i."</td>";
				 $html .="<td align='center'>";
				 $html .= $results[$p]['Student']['registration_number'];
				 $html .="</td>";
				 $html .="<td align='center'>";
				 $html .= $results[$p]['Student']['roll_number'];
				 $html .="</td>";
				 $html .="<td align='left'>";
				 $html .= $results[$p]['Student']['name'];
				 $html .="</td>";
				 
				 $html .="<td align='left' style='font-family:baamini;'>";
				 if($results[$p]['Student']['tamil_name']){
				 	$html .= $results[$p]['Student']['tamil_name'];
				 }
				 $html .="</td>";
				 $html .="<td align='left'>";
				 if($results[$p]['Student']['father_name']){
				 	$html .= $results[$p]['Student']['father_name'];
				 }
				 $html .="</td>";
				 $html .="<td align='center'>";
				 if($results[$p]['Student']['birth_date']){
				 	$html .= date( "d-M-Y", strtotime(h($results[$p]['Student']['birth_date'])));
				 }	
				 $html .="</td>";
				 $html .="<td align='center'>";
				 if($results[$p]['Student']['birth_date']){$gender ="";
				 	if($results[$p]['Student']['gender'] == "M"){
				 		$gender = "Male";
				 	}else if($results[$p]['Student']['gender'] == "F"){
				 		$gender = "Female";
				 	}
				 	$html .= $gender;
				 }
				 $html .="</td>";
				 $html .="<td align='center'>";
				 if($results[$p]['Student']['picture']){
				 	$html .="<img src='../webroot/img/students/".str_replace("  "," ",str_replace("   "," ",$results[$p]['Student']['picture']))."' style='width:50px;height:50px;'>";
				 }
				 $html .="</td>";
				 $html .="<td align='center'>";
				 if($results[$p]['Student']['signature']){
				 	$html .="<img src='../webroot/img/signatures/".str_replace("  "," ",str_replace("   "," ",$results[$p]['Student']['signature']))."' style='width:150px;height:50px;'>";
				 }
				 $html .="</td>";
				 
				$html .="</tr>";
				 $i++;
			 }
			 $html .= "</table>";
			 }
			$this->mPDF->init();
			// setting filename of output pdf file
			$this->mPDF->setFilename('STUDENT_PHOTO_LIST_'.date('d_M_Y').'.pdf');
			
			/* $this->mPDF->allow_charset_conversion=true;
			$this->mPDF->charset_in='UTF-8'; */
			
			// setting output to I, D, F, S
			$this->mPDF->setOutput('D');
			$this->mPDF->AddPage('L','', '', '', '',30,30,30,30,18,12);
			$this->mPDF->WriteHTML($html);
			// you can call any mPDF method via component, for example:
			$this->mPDF->SetWatermarkText("Draft");		
		}
		$academics = $this->Student->Academic->find('list');
		$batches = $this->Student->Batch->find('list', array('fields' => array('Batch.batch_period')));
		$this->set(compact('batches', 'academics'));
	}
	public function signature($regNo = null) {
		$id = $this->getIdFromRegNo($regNo);
						
		if(!empty($this->request->data['Student']['signature']['name'])) {
			$file = $this->request->data['Student']['signature']; //put the data into a var for easy use
			$ext = substr(strtolower(strrchr($file['name'], '.')), 1); //get the extension
			$arr_ext = array('jpg', 'jpeg', 'gif', 'png', 'bmp'); //set allowed extensions
			//only process if the extension is valid
			if(in_array($ext, $arr_ext)) {
				//do the actual uploading of the file. First arg is the tmp name, second arg is
				//where we are putting it
				
				move_uploaded_file($file['tmp_name'], WWW_ROOT . 'img/signatures/' .$regNo.".".$ext);
				//prepare the filename for database entry
				$this->request->data['Student']['signature'] = $regNo.".".$ext;
			}
		}
		else {
			$this->request->data['Student']['signature'] = "";
		}
		
		$this->Student->id = $id;
		if (!$this->Student->exists()) {
			throw new NotFoundException(__('Invalid student'));
		}
		
		if ($this->request->is('post')) { //echo "Student found ".$this->request->data['Student']['signature'];			
			$this->Student->updateAll(
				array(
					"Student.signature" => "'".$this->request->data['Student']['signature']."'"
				),
				array(
					"Student.id" => $id
				)
			);
			return $this->redirect(array('action' => 'signature',$regNo));
		}
		$this->info($regNo);
	}
	
	public function manage_courses($regNo = null) {
		$id = $this->getIdFromRegNo($regNo);
		
		$this->Student->id = $id;
		//Identify the Batch and the Program
		$results = $this->Student->find('first', array(
				'conditions' => array('Student.id'=> $id,
						'Student.discontinued_status'=> 0
				),
				'fields' => array(
					'Student.batch_id',
					'Student.program_id'
				),
				'recursive'=>1
		));		
		
		$program_id = $results['Student']['program_id'];		
		$semesterCourses = $this->CourseMapping->find('all', array(
				'conditions' => array('CourseMapping.batch_id' => $results['Student']['batch_id'],
						'CourseMapping.program_id' => $results['Student']['program_id'],
				),
				'fields' => array('MAX(CourseMapping.semester_id) AS semesters',
							'MAX(CourseMapping.course_number) AS courses'),
				'group' => 'CourseMapping.semester_id',
				'order' => 'CourseMapping.semester_id'
				));
		
		$options = array('conditions' => array('Program.id'=> $program_id), 'fields' => array('Program.semester_id'));
		$semesters = $this->CourseMapping->Program->find('list', $options);
		foreach($semesters as $key =>$val){
			$totalSemesters = $val;
		}
		
		$allCourses = $this->CourseMapping->Course->find('list');
		
		$cnt = count($semesterCourses);
		$arrValues = array(); 
		$semArray = array();
		for($i=0; $i<$cnt; $i++) {
			$semesters = $semesterCourses[$i][0]['semesters'];
			$courses = $semesterCourses[$i][0]['courses'];
			$courseMapping = $this->CourseMapping->query("SELECT CM.id,  CM.program_id, CM.course_number, CM.course_mode_id, 
			CM.semester_id, GROUP_CONCAT(DISTINCT(CM.id)) AS course, GROUP_CONCAT(DISTINCT(Courses.course_name)) AS courseName, 
			CAES.id AS CAES_ID, CModes.course_mode, Courses.course_name  FROM course_mappings CM
					LEFT JOIN caes AS CAES
						ON CM.id = CAES.course_mapping_id
					LEFT JOIN course_modes AS CModes
						ON CM.course_mode_id = CModes.id
					LEFT JOIN courses AS Courses
						ON CM.course_id = Courses.id
					WHERE CM.program_id = ".$program_id." AND CM.semester_id= ".$semesters." AND CM.indicator=0 
						GROUP BY CM.program_id, CM.semester_id, CM.course_number 
						ORDER BY CM.semester_id, CM.course_number ASC");
			$semArray[$semesters] = $courseMapping;
			
		}
		array_push($arrValues, $semArray);
		$arrValues = reset($arrValues);
		$this->set(compact('arrValues', 'totalSemesters', 'allCourses'));
		
		//Get All Lecturer
		$lecturerArray = $this->Lecturer->find('list');
		$this->set('facultyArray', $lecturerArray);
				
		$options = array('conditions' => array('Student.' . $this->Student->primaryKey => $id));
		$this->set('student', $this->Student->find('first', $options));
		$this->set('studentId', $regNo);
		$this->set('stuId', $id);
	}	
	
	public function student_course_edit($regNo = null) {
		$id = $this->getIdFromRegNo($regNo);
	
		$this->Student->id = $id;
		//Identify the Batch and the Program
		$results = $this->Student->find('first', array(
				'conditions' => array('Student.id'=> $id,
						'Student.discontinued_status'=> 0
				),
				'fields' => array(
						'Student.batch_id',
						'Student.program_id'
				),
				'recursive'=>1
		));
	
		$program_id = $results['Student']['program_id'];
		$semesterCourses = $this->CourseMapping->find('all', array(
				'conditions' => array('CourseMapping.batch_id' => $results['Student']['batch_id'],
						'CourseMapping.program_id' => $results['Student']['program_id'],
				),
				'fields' => array('MAX(CourseMapping.semester_id) AS semesters',
						'MAX(CourseMapping.course_number) AS courses'),
				'group' => 'CourseMapping.semester_id',
				'order' => 'CourseMapping.semester_id'
		));
	
		$options = array('conditions' => array('Program.id'=> $program_id), 'fields' => array('Program.semester_id'));
		$semesters = $this->CourseMapping->Program->find('list', $options);
		foreach($semesters as $key =>$val){
			$totalSemesters = $val;
		}
	
		$allCourses = $this->CourseMapping->Course->find('list');
	
		$cnt = count($semesterCourses);
		$arrValues = array();
		$semArray = array();
		for($i=0; $i<$cnt; $i++) {
			$semesters = $semesterCourses[$i][0]['semesters'];
			$courses = $semesterCourses[$i][0]['courses'];
			$courseMapping = $this->CourseMapping->query("SELECT CM.id,  CM.program_id, CM.course_number, CM.course_mode_id, 
			CM.semester_id, GROUP_CONCAT(DISTINCT(CM.id)) AS course, GROUP_CONCAT(DISTINCT(Courses.course_name)) AS courseName, 
			CAES.id AS CAES_ID, CModes.course_mode, Courses.course_name,CSM.id AS CSM_ID, CSM.user_id AS CSM_lectureId,
			CSM.course_mapping_id AS CSM_courseMId,CSM.student_id AS CSM_studentId  FROM course_mappings CM
					LEFT JOIN caes AS CAES
						ON CM.id = CAES.course_mapping_id
					LEFT JOIN course_modes AS CModes
						ON CM.course_mode_id = CModes.id
					LEFT JOIN courses AS Courses
						ON CM.course_id = Courses.id
					LEFT JOIN course_student_mappings AS CSM
						ON CM.id = CSM.course_mapping_id
					WHERE CM.program_id = ".$program_id." AND CM.semester_id= ".$semesters." AND CM.indicator=0 
						GROUP BY CM.program_id, CM.semester_id, CM.course_number 
						ORDER BY CM.semester_id, CM.course_number ASC");
			$semArray[$semesters] = $courseMapping;
				
		}
		array_push($arrValues, $semArray);
		$arrValues = reset($arrValues);
		$this->set(compact('arrValues', 'totalSemesters', 'allCourses'));
	
		//Get All Lecturer
		$lecturerArray = $this->Lecturer->find('list');
		$this->set('facultyArray', $lecturerArray);
	
		$options = array('conditions' => array('Student.' . $this->Student->primaryKey => $id));
		$this->set('student', $this->Student->find('first', $options));
		$this->set('studentId', $regNo);
		$this->set('stuId', $id);
	}
	
	public function manage_course_add(){ 
		$id = $this->getIdFromRegNo($_REQUEST['studentId']);			
		$options = array('conditions' => array('CourseStudentMapping.student_id'=> $id,
				'CourseStudentMapping.course_mapping_id'=> $_REQUEST['CMId'],'CourseStudentMapping.semester_id'=> $_REQUEST['semesterId'],'CourseStudentMapping.course_number'=> $_REQUEST['courseNo']),
				'fields' => array('CourseStudentMapping.id'));
		$ExistingRecordQuery = $this->Student->CourseStudentMapping->find('list', $options);
		
		$data['CourseStudentMapping']['student_id'] 		= $id;
		$data['CourseStudentMapping']['course_mapping_id']  = $_REQUEST['CMId'];
		$data['CourseStudentMapping']['lecturer_id'] 		= $_REQUEST['lectureId'];
		$data['CourseStudentMapping']['course_number'] 		= $_REQUEST['courseNo'];
		$data['CourseStudentMapping']['semester_id'] 		= $_REQUEST['semesterId'];
		
		if($ExistingRecordQuery){
			foreach ($ExistingRecordQuery as $key=>$value){
				$key = $value;
			}
			$data['CourseStudentMapping']['modified_by'] 		= $this->Auth->user('id');
			$data['CourseStudentMapping']['id'] 				= $key;
		}else{
			$data['CourseStudentMapping']['created_by'] = $this->Auth->user('id');
			$this->CourseStudentMapping->create();
		}
		$this->CourseStudentMapping->save($data);unset($data);exit;
	}
	
	public function beforeRevaluation() {
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
	
	public function beforeRevaluationSearch($examMonth = null,$batchId = null,$Academic = null,$programId = null,$withheldType = null,$withheldVal = null,$printMode = null, $revalMode = null) {
		$this->set('examMonth', $examMonth);
		$this->set('batchId', $batchId);
		$this->set('Academic', $Academic);
		$this->set('programId', $programId);
		$this->set('withheldType', $withheldType);
		$this->set('withheldVal', $withheldVal);
		$this->set('printMode', $printMode);
		
		//echo " examMonth=> ".$examMonth." BatchId=> ".$batchId." Academic=> ".$Academic." programId=> ".$programId." With held Type=> ".$withheldType." With held Val=> ".$withheldVal." printMode=>".$printMode;
		
		$stuCond=array(); $markCond=array();  $attenCond=array();$tTCond=array();$iExam=array();$eExam=array();$SWMYE = array();
		
		//$attenCond['ExamAttendance.attendance_status'] = 0;
		$tTCond['Timetable.indicator'] = 0;
		$stuCond['Student.discontinued_status'] = 0;
		//$stuCond['Student.registration_number'] = array(3613436);
		
		if($examMonth != '-'){
			$markCond['StudentMark.month_year_id'] = $examMonth;
			$tTCond['Timetable.month_year_id'] = $examMonth;
			$iExam['InternalExam.month_year_id'] = $examMonth;
			$eExam['EndSemesterExam.month_year_id'] = $examMonth;
			$SWMYE['StudentWithheld.month_year_id'] = $examMonth;
		}
		if($batchId != '-'){
			$stuCond['Student.batch_id'] = $batchId;
		}
		if($programId != '-'){
			$stuCond['Student.program_id'] = $programId;
		}		
		if($withheldType != "-"){
			if($withheldVal != '-'){
				$SWMYE['StudentWithheld.withheld_id'] = $withheldVal;
			}
			
			$getWithheldStu = array(
					'conditions' => array('StudentWithheld.indicator' => 0,$SWMYE
					),
					'fields' =>array('StudentWithheld.student_id'),
					'contain'=>array(
						'Withheld'=>array(
							'fields'=>array('Withheld.withheld_type')
						),
						'Student'=>array(
							'conditions'=>$stuCond
						)
					),
			);
			
			$getWithheldStuRes = $this->StudentWithheld->find("all", $getWithheldStu);
			//pr($getWithheldStuRes); 
			
			$totWHStuId = array();$w=0;
			foreach($getWithheldStuRes as $key => $wArray){
				if (isset($wArray['Student']['id'])) {
					$totWHStuId[$wArray['StudentWithheld']['student_id']] = $wArray['Withheld']['withheld_type'];
				}
			}
			//pr($totWHStuId);
		}
		else {
			$getWithheldStu = array(
					'conditions' => array('StudentWithheld.indicator' => 0,$SWMYE
					),
					'fields' =>array('StudentWithheld.student_id'),
					'contain'=>array(
							'Withheld'=>array(
									'fields'=>array('Withheld.withheld_type')
							),
							'Student'=>array(
									'conditions'=>$stuCond
							)
					),
			);
				
			$getWithheldStuRes = $this->StudentWithheld->find("all", $getWithheldStu);
			//pr($getWithheldStuRes);
				
			$totWHStuId = array();$w=0;
			foreach($getWithheldStuRes as $key => $wArray){
				if (isset($wArray['Student']['id'])) {
					$totWHStuId[$wArray['StudentWithheld']['student_id']] = $wArray['Withheld']['withheld_type'];
				}
			}
			//pr($totWHStuId);
		}
		if (count($totWHStuId) <= 0) { $this->set('withheldType','All'); } 
		if($withheldType == "-") $this->set('withheldType','All');
			
			//if($totWHStuId){
				//$stuCond['Student.id IN'] = array_keys($totWHStuId);
				//$this->set('withheldType','All');
			//}
			/*else{
				$this->layout= false;
				$this->set('results', '');
				return false;
			} */
		//}
		$this->set('withheldStudents', $totWHStuId);
	//	pr($totWHStuId);
	
		if ($withheldType!="-" && $withheldVal!="-") {
			$stuCond['Student.id IN'] = array_keys($totWHStuId);
		}
		
		/* if(empty($totWHStuId)){
			$this->set('withheldType','All');
			$this->layout= false;
			//$this->set('results', '');
			return false;
		} */
			
		$finalArray = array();
		$stuid = array();
		
		//if(!empty($totWHStuId)) {
		$stuid = $this->StudentMark->find('all', array(
				'fields'=>array('DISTINCT StudentMark.student_id'),
				'conditions'=>array('StudentMark.month_year_id' => $examMonth,
						//'StudentMark.student_id'=>8482
				),
				'contain'=>array(
						'Student'=>array(
								'conditions'=>$stuCond,
						)
				)
		));
		//}
		//pr($stuCond);
		//pr($stuid);
		
		
		$stuIdArray = array();
		foreach ($stuid as $key => $stuArray) {
			if (isset($stuArray['Student']['id'])) {
				$stuIdArray[$stuArray['StudentMark']['student_id']]=$stuArray['StudentMark']['student_id'];
			}
		}
		//echo count($stuIdArray);
		//pr($stuIdArray); 
		//echo count($stuIdArray);
		
		
		$results = array();
				
		foreach ($stuIdArray as $student_id => $value) {
			//echo $student_id." ".$examMonth;
			$csmResults = $this->CourseStudentMapping->getEnrolledCourses($student_id); 
			//pr($csmResults);
			$tmpStudentResults = $this->getUpToDateDataOFAStudent($student_id, $examMonth);
			//pr($tmpStudentResults);
		
			if (isset($tmpStudentResults) && !empty($tmpStudentResults )) {
				foreach ($tmpStudentResults as $tKey => $tValue) {
					if ($csmResults[$student_id][$tValue['StudentMark']['course_mapping_id']] == 0) {
						if ($tValue['StudentMark']['revaluation_status']) $tStatus = $tValue['StudentMark']['final_status'];
						else $tStatus = $tValue['StudentMark']['status'];
						//echo "</br>".$tStatus;
						if ($tValue['StudentMark']['month_year_id'] < $examMonth && $tStatus == 'Fail') { 
							$results[$student_id][$tValue['StudentMark']['course_mapping_id']] = $tValue;
						}
						else if ($tValue['StudentMark']['month_year_id'] == $examMonth && ($tStatus == 'Fail' || $tStatus == 'Pass')) {  
							$results[$student_id][$tValue['StudentMark']['course_mapping_id']] = $tValue;
						}
					}
				}
			}
		}
		//echo " count ".count($results[$student_id]);
		//pr($results);
		
		$courseMappingArray = array();
		
		$tTCond['Timetable.indicator'] = 0;
		$tTCond['Timetable.month_year_id'] = $examMonth;
		
		$array = array();
		foreach ($results as $student_id => $stuArray) { //pr($stuArray);
			$student_details = $this->Student->studentDetails($student_id);
			//pr($student_details);
			$array['student_id'] = $student_id;
			$array['registration_number'] = $student_details[0]['Student']['registration_number'];
			$reg_num = $student_details[0]['Student']['registration_number'];
			$array['name'] = $student_details[0]['Student']['name'];
			$array['birth_date'] = $student_details[0]['Student']['birth_date'];
			$array['section'] = $student_details[0]['Section']['name'];
			$batch_id = $student_details[0]['Student']['batch_id'];
			$program_id = $student_details[0]['Student']['program_id'];
		
			//$smArray = $stuArray['StudentMark'];
		
			$finalArray[$reg_num] = array();
		
			//foreach ($smArray as $key => $value) { 
			foreach ($stuArray as $key => $value) {
				//pr($value);
				$array['attendance'] = '';
				$array['cae'] = '';
				$array['ese'] = '';
				$array['status'] = '';
				$array['total'] = '';
				$array['sm_id'] = '';
				$array['sm_month_year_id'] = '';
				$array['course_code'] = '';
				$array['course_name'] = '';
				$array['course_max_marks'] = '';
				$array['cm_id'] = '';
				$array['course_type'] = '';
				$array['dummy_number'] = '';
				$array['actual_month_year_id'] = '';
				$array['type'] = '';
				
				$ctype = '';
				$marks = '';
				$status = '';
				$final_marks = '';
				$final_status = '';
				$cm_id = '';
				$revaluation_status = '';
				$type= '';
				
				$array['sm_id'] = $value['StudentMark']['id'];
				
				$marks = $value['StudentMark']['marks'];
				$status = $value['StudentMark']['status'];
				$revaluation_status = $value['StudentMark']['revaluation_status'];
				$final_marks = $value['StudentMark']['final_marks'];
				$final_status = $value['StudentMark']['final_status'];
				$cm_id = $value['StudentMark']['course_mapping_id'];
				
				if ($value['StudentMark']['month_year_id'] == $value['CourseMapping']['month_year_id']) $array['type'] = "R";
				else $array['type'] = "A";
				
				$course_type_array = $this->getCourseTypeIdFromCmId($cm_id);
				$course_array = $this->getCourseNameCrseCodeFromCMId($cm_id);
				
				//pr($course_array);
				$array['actual_month_year_id'] = $value['CourseMapping']['month_year_id'];
				$array['actual_semester_id'] = $value['CourseMapping']['semester_id'];
				$array['sm_month_year_id'] = $value['StudentMark']['month_year_id'];
				
				$array['course_code'] = $course_array[0]['Course']['course_code'];
				$array['course_name'] = $course_array[0]['Course']['course_name'];
				$array['course_max_marks'] = $course_array[0]['Course']['course_max_marks'];
				$course_type_id = $course_type_array[0]['Course']['course_type_id'];
				$array['course_type_id'] = $course_type_id;
				
				$courseMappingArray[$cm_id]=$course_type_id;
				
				$array['cm_id'] = $cm_id;
				//pr($array);
				//echo "</br>".$course_type_id." ".$cm_id." ".$array['actual_month_year_id']." ".$array['course_code']." ".$array['course_max_marks']." ";
				
				SWITCH ($course_type_id) {
					CASE 1:
						$attendance_status = '';
						$array['course_type'] = 'Theory';
						$ttResult = $this->Timetable->query('SELECT ea.attendance_status FROM timetables tt JOIN
									exam_attendances ea ON tt.id = ea.timetable_id
									WHERE tt.month_year_id='.$examMonth.' and tt.course_mapping_id='.$cm_id.'
											and ea.student_id='.$student_id);
						//pr($ttResult);
						if (isset($ttResult[0]['ea']['attendance_status'])) {
							$attendance_status = $ttResult[0]['ea']['attendance_status'];
						}
						$stu_results = $this->theoryResults($examMonth, $cm_id, $student_id);
						//pr($stu_results);
						if (isset($stu_results[0]['InternalExam'][0]['marks'])) {
							$array['cae'] = $stu_results[0]['InternalExam'][0]['marks'];
						} else {
							$array['cae'] = "-";
						}
						$array['attendance'] = $attendance_status;
						//pr($array);
						//$ese =
						if ($revaluation_status) {
							$tmp_ese = 'AAA';
							if (isset($stu_results[0]['EndSemesterExam']) && !empty($stu_results[0]['EndSemesterExam'])) {
								$tmp_ese = $stu_results[0]['EndSemesterExam'][0]['marks'];
							}
							$tmp_reval = 'AAA';
							if (isset($stu_results[0]['RevaluationExam']) && !empty($stu_results[0]['RevaluationExam'])) {
								$tmp_reval = $stu_results[0]['RevaluationExam'][0]['revaluation_marks'];
							}
							$array['ese'] = ($tmp_ese > $tmp_reval ? $tmp_ese : $tmp_reval);
							$array['status'] = $final_status;
						}
						else {
							if (isset($stu_results[0]['EndSemesterExam'][0]['marks'])) {
								$array['ese'] = $stu_results[0]['EndSemesterExam'][0]['marks'];
								$array['dummy_number'] = $stu_results[0]['EndSemesterExam'][0]['dummy_number'];
							}
							else {
								$array['ese'] = '';
							}
							$array['status'] = $status;
						}
						
						if ($attendance_status == 0) { //echo "hi";
							$array['ese'] = "AAA";
						}
						if ($array['ese'] == "AAA") $array['total'] = "AAA";
						else $array['total'] = $array['cae'] + $array['ese'];
						//echo " cae: ".$array['cae']. " ese: ".$array['ese']." attendance : ".$array['attendance']." total: ".$array['total']." status: ".$array['status'];
						//pr($array);
						break;
					CASE 2:
					CASE 6:
						if ($course_type_id == 2) $array['course_type'] = 'Practical';
						if ($course_type_id == 6) $array['course_type'] = 'Studio';
						$stu_results = $this->practicalResults($examMonth, $cm_id, $student_id);
						//if ($student_id == 561) pr($stu_results);
						
						$array['cae'] = $stu_results[0]['CaePractical'][0]['InternalPractical'][0]['marks'];
						if (isset($stu_results[0]['EsePractical'][0]['Practical'][0]['marks'])) {
							$array['ese'] = $stu_results[0]['EsePractical'][0]['Practical'][0]['marks'];
						}
						else {
							$array['ese'] = 0;
						}
						if ($array['ese']=='a' || $array['ese']=='aaa' || $array['ese']=='A' || $array['ese']=='AAA') {
							$array['ese'] = "AAA";
							$array['total'] = "AAA";
						} else {
							$array['total'] = $array['cae'] + $array['ese'];
						}
						
						if ($array['cae']=='a' || $array['cae']=='aaa' || $array['cae']=='A' || $array['cae']=='AAA') {
							$array['cae'] = "AAA";
						}
						$array['status'] = $status;
						
						if ($array['ese']>0) $array['attendance']=0;
						//echo $cm_id." cae: ".$array['cae']. " ese: ".$array['ese']." total: ".$array['total']." status: ".$array['status'];
						break;
					CASE 3:
						$array['course_type'] = 'Theory and Practical';
						
						$result_theory = $this->theoryResults($examMonth, $cm_id, $student_id);
						$array['cae'] = $result_theory[0]['InternalExam'][0]['marks'];
						
						$result_practical = $this->practicalResults($examMonth, $cm_id, $student_id);
						$array['ese'] = $result_practical[0]['EsePractical'][0]['Practical'][0]['marks'];
						
						$array['status'] = $status;
						$array['total'] = $array['cae'] + $array['ese'];
						//echo " cae: ".$array['cae']. " ese: ".$array['ese']." total: ".$array['total']." status: ".$array['status'];
						break;
					CASE 4:
						$array['course_type'] = 'Project';
						$stu_results = $this->projectResults($examMonth, $cm_id, $student_id);
						//pr($stu_results);
						$array['cae'] = $stu_results[0]['InternalProject'][0]['marks'];
						$array['ese'] = $stu_results[0]['EseProject'][0]['ProjectViva'][0]['marks'];
						$array['status'] = $status;
						$array['total'] = $array['cae'] + $array['ese'];
						//echo " cae: ".$array['cae']. " ese: ".$array['ese']." total: ".$array['total']." status: ".$array['status'];
						break;
					CASE 5:
						$array['course_type'] = 'Professional Training';
						$stu_results = $this->profTrainingResults($examMonth, $cm_id, $student_id);
						//pr($stu_results);
						$array['cae'] = $stu_results[0]['CaePt']['ProfessionalTraining'][0]['marks'];
						$array['ese'] = "-";
						$array['status'] = $status;
						$array['total'] = $array['cae'];
						//echo " cae: ".$array['cae']. "total: ".$array['total']." status: ".$array['status'];
						break;
				}
				//	pr($array);
				array_push($finalArray[$reg_num], $array);
			}
			ksort($finalArray);
			//array_push($finalArray, $tmpArray);
			//pr($finalArray); 
		}
		
		//$stuCond['Student.registration_number'] = array(3541183);
		//pr($finalArray);
		
		$this->set('results', $finalArray);
		$this->set('revalMode', $revalMode);
		$this->layout= false;
		
		if($printMode == 'Dept Excel'){
			$this->PublishResultMarkDataAfterRevaluation($finalArray,$withheldType);
		}
		if($printMode == 'Excel'){
			return $finalArray;
		}
		if($printMode == 'PRINT'){
			$this->set('revalMode', $revalMode);
			$this->layout= 'print';
			return false;
		}
		
		/* $res = array(
		 'conditions' => array($stuCond,
		 ),
		 'fields' =>array('Student.id','Student.registration_number','Student.name','Student.birth_date'),
		 'contain'=>array(
		 'Section'=>array('fields'=>array('Section.name'),
		 ),
		 'ExamAttendance'=>array('fields'=>array('ExamAttendance.id','ExamAttendance.attendance_status'),
		 'Timetable'=>array('fields' =>array('Timetable.id', 'Timetable.course_mapping_id'),'conditions' => $tTCond,
		 ),'conditions'=>$attenCond
		 ),
		 'StudentWithheld'=>array('fields' =>array('StudentWithheld.student_id', 'StudentWithheld.withheld_id'),
		 'Withheld'=>array('fields'=>array('Withheld.withheld_type'),
		 ),'conditions' => array('StudentWithheld.indicator' => 0,$SWMYE),
		 ),
		 'InternalPractical' => array(
		 'fields' => array('InternalPractical.month_year_id', 'InternalPractical.marks', 'InternalPractical.student_id'),
		 'CaePractical' => array(
		 'fields' => array('CaePractical.course_mapping_id')
		 )
		 ),
		 'Practical' => array(
		 'fields' => array('Practical.month_year_id', 'Practical.marks', 'Practical.student_id'),
		 'conditions' => array('Practical.month_year_id' => $examMonth),
		 'EsePractical' => array(
		 'fields' => array('EsePractical.course_mapping_id')
		 )
		 ),
		 'InternalExam' => array(
		 'fields' => array('InternalExam.month_year_id', 'InternalExam.marks', 'InternalExam.student_id', 'InternalExam.course_mapping_id'),
		 ),
		 'EndSemesterExam' => array(
		 'fields' => array('EndSemesterExam.month_year_id', 'EndSemesterExam.marks', 'EndSemesterExam.student_id', 'EndSemesterExam.course_mapping_id', 'EndSemesterExam.dummy_number'),
		 'conditions' => array('EndSemesterExam.month_year_id' => $examMonth)
		 ),
		 'InternalProject' => array(
		 'fields' => array('InternalProject.month_year_id', 'InternalProject.marks', 'InternalProject.student_id', 'InternalProject.course_mapping_id'),
		 ),
		 'ProjectViva' => array(
		 'fields' => array('ProjectViva.month_year_id', 'ProjectViva.marks', 'ProjectViva.student_id'),
		 'conditions' => array('ProjectViva.month_year_id' => $examMonth),
		 'EseProject' => array(
		 'fields' => array('EseProject.course_mapping_id')
		 )
		 ),
		 'StudentMark' => array(
		 'fields' => array('StudentMark.id','StudentMark.month_year_id', 'StudentMark.marks', 'StudentMark.student_id', 'StudentMark.course_mapping_id','StudentMark.status'),
		 'conditions' => array('StudentMark.month_year_id' => $examMonth),
		 'CourseMapping'=>array('fields'=>array('CourseMapping.id'),
		 'conditions'=>array('CourseMapping.indicator'=>0,
		 ),
		 'Course' => array(
		 'fields' => array('Course.id','Course.course_code','Course.course_name','Course.course_max_marks'),
		 'CourseType' => array('fields' => array('CourseType.course_type'))
		 ),
		 )
		 )
		 ),
		 );	 */
		//$results = $this->Student->find("all", $res);
		//pr($results);
		
		/* $this->set('results', $results);			
		$this->layout= false;
		
		if($printMode == 'Dept Excel'){
			$this->PublishResultMarkData($results,$withheldType);
		}
		if($printMode == 'Excel'){
			return $results;
		}
		if($printMode == 'PRINT'){
			$this->layout= 'print';
			//$this->render('Students/beforeRevaluationSearch/'.$examMonth.'/'.$batchId.'/'.$Academic.'/'.$programId.'/'.$exam_type.'/PRINT/');
			return false;
		} */
	}
	
	public function studentWithHeld() {
		if($this->request->is('post')) {
			if(isset($this->request->data['submit'])){
				for($i=1; $i<=$this->request->data['maxRow'];$i++) {
					if(isset($this->request->data['student'.$i])){
						for($j=1; $j<=$this->request->data['maxCol'];$j++) {
							$data = array();
							
							$studentId = $this->request->data['student'.$i];
							$WHOriginalVal = $this->request->data['WHOrginal'.$j];
							$data['StudentWithheld']['withheld_id']  = $this->request->data[$studentId.'withheld'.$j];
							$data['StudentWithheld']['student_id'] 	 = $studentId;
							$data['StudentWithheld']['month_year_id']= $this->request->data['examMonth'];
							$this->request->data['examMonth'];
							$chkWithheld =$this->StudentWithheld->find('first',
									array('conditions' => array(	
											'StudentWithheld.withheld_id' => $WHOriginalVal,
											'StudentWithheld.student_id' => $studentId,
											'StudentWithheld.month_year_id' => $this->request->data['examMonth']
											),'fields' => array('StudentWithheld.id'),
											'recursive'=>1)
									);
							
							if($chkWithheld){
								if($data['StudentWithheld']['withheld_id'] == 0){
									$data['StudentWithheld']['withheld_id'] = $WHOriginalVal;
									$data['StudentWithheld']['indicator'] = 1;
								}else{
									$data['StudentWithheld']['indicator'] = 0;								
								}
								$data['StudentWithheld']['indicator']."**";
								$data['StudentWithheld']['modified_by'] = $this->Auth->user('id');
								$data['StudentWithheld']['id'] 			= $chkWithheld['StudentWithheld']['id'];
								$this->Flash->success(__('The Withheld has been updated.'));
								$this->StudentWithheld->save($data);
							}else{
								if($data['StudentWithheld']['withheld_id'] != 0){
									$data['StudentWithheld']['indicator'] = 0;
									$data['StudentWithheld']['created_by'] 	 = $this->Auth->user('id');
									$this->StudentWithheld->create();
									$this->Flash->success(__('The Withheld data has been saved.'));
									$this->StudentWithheld->save($data);
								}
							}
						}
					}
				}
			}
		}
		$academics = $this->Program->Academic->find('list',array('order' => array('Academic.academic_name ASC')));
		$programs = $this->Program->find('list',array('order' => array('Program.program_name ASC')));
		$batches = $this->Batch->find('list', array('fields' => array('Batch.batch_period'),'order' => array('Batch.batch_to DESC')));
		$monthYears = $this->MonthYear->find('all', array(
				'fields' => array('MonthYear.id','MonthYear.year','MonthYear.month_id','Month.month_name'),
				'order' => array('MonthYear.id DESC'),
				'recursive' => 0
		));
		//pr($monthYears);
		$monthyears=array();
		foreach($monthYears as $key => $value){
			$monthyears[$value['MonthYear']['id']] = $value['Month']['month_name']."-".$value['MonthYear']['year'];
		}
		$this->set(compact('academics','programs','batches','monthyears'));
	}
	
	public function studentWithHeldSearch($examMonth = null,$batchId = null,$Academic = null,$programId = null,$exam_type = null) {			
			$baseCond = array();
			$baseCond['Student.discontinued_status'] = 0;
			$baseCond['Student.indicator'] = 0;
			$conditions = "";
				SWITCH (true) {
					case (($batchId) && ($Academic) && ($programId)):
						$conditions= array('Student.batch_id'=> $batchId,
								'Student.academic_id'=> $Academic,
								'Student.program_id'=> $programId);
						break;
					case (($batchId) && ($programId)):
						$conditions= array('Student.batch_id'=> $batchId,
						'Student.academic_id'=> $programId);
						break;
					case (($batchId) && ($Academic)):
						$conditions= array('Student.batch_id'=> $batchId,
								'Student.academic_id'=> $Academic);
						break;
					case (($Academic)  && ($programId)):
						$conditions= array('Student.academic_id'=> $Academic,
								'Student.program_id'=> $programId); 
						break;
					case ($batchId):
						$conditions= array('Student.batch_id'=> $batchId); 
						break;
					case ($Academic):
						$conditions= array('Student.academic_id'=> $Academic);
						break;					
				}
	
				$results = $this->Student->find('all', array(
						'conditions' => $conditions,
						'fields' =>array('Student.id','Student.name','Student.user_initial','Student.registration_number'),						
						'contain'=>array(
								'StudentWithheld'=>array('fields' =>array('StudentWithheld.withheld_id'),'conditions'=>array('StudentWithheld.indicator'=>0)),
								'Batch'=>array('fields' =>array('Batch.batch_from','Batch.batch_to','Batch.academic')),
								'Program'=>array('fields' =>array('Program.program_name')),
								'Academic'=>array('fields' =>array('Academic.academic_name')),
								'StudentType'=>array('fields' =>array('StudentType.type')),
						),						
						'recursive'=>0
				));				
				$this->set('stuList', $results);
				
				$Withhelds = $this->Withheld->find('all', array(
						'fields' =>array('Withheld.id','Withheld.Withheld_type'),
						'contain'=>array(
						),
						'recursive'=>0
				));
				$this->set('Withhelds', $Withhelds);
				
				$this->set('examMonth', $examMonth);				
		
		$this->layout=false;
	}
	public function clearAllWithheld($examMonthYear = null) {
		$this->StudentWithheld->updateAll(
				/* UPDATE FIELD */
				array(
						"StudentWithheld.indicator" => 1,
				),
				/* CONDITIONS */
				array(
						"StudentWithheld.month_year_id" => $examMonthYear
				)
				);
		echo  "Successfully Completed";exit;
	}
	public function publishWebsiteStudents(){
		$access_result = $this->checkPathAccess($this->Auth->user('id'));
		if (!$access_result) {
		$studentArray = $this->Student->find('all', array(
			'conditions' => array('Student.discontinued_status' =>0,'Student.indicator' =>0),
			'fields' =>array('Student.id','Student.name','Student.user_initial','Student.registration_number',
							'Student.birth_date'),
			'contain'=>array(
				'Batch'=>array('fields' =>array('Batch.batch_from','Batch.batch_to','Batch.academic')),
				'Program'=>array('fields' =>array('Program.program_name')),
				'Academic'=>array('fields' =>array('Academic.academic_name')),
				'StudentType'=>array('fields' =>array('StudentType.type'))
			),
			'recursive'=>0
		));
		//pr($studentArray);
		$this->publishWebsiteStudentData($studentArray);
		}
		else {
			$this->render('../Users/access_denied');
		}
	}
	public function resultsToWebsiteMark($examMonthYear = null) {
		$access_result = $this->checkPathAccess($this->Auth->user('id'));
		if (!$access_result) {
		$reval_mode = "BR";
		if ($this->request->is('post')) {
			if($this->request->data['Student']['monthyears']){
				//pr($this->data);
				
				$examMonth = $this->request->data['Student']['monthyears'];
				//echo $examMonth;
				//$results = $this->beforeRevaluationSearch('4','7','3','8','-','-','Excel');
				$results = $this->beforeRevaluationSearch($examMonth,'-','','-','-','-','Excel');
				//pr($results);
				//die;
				//echo count($results);
				$this->resultsToWebsiteMarkData($results, $examMonth, $reval_mode);
			}
		}
	
		$monthyears = $this->MonthYear->getAllMonthYears();
		$this->set(compact('monthyears'));
		}
		else {
			$this->render('../Users/access_denied');
		}
	}
	
	public function resultsToWebsiteMarkAfterRevaluation($examMonthYear = null) {
		$access_result = $this->checkPathAccess($this->Auth->user('id'));
		if (!$access_result) {
		$reval_mode = "AR";
		if ($this->request->is('post')) {
			if($this->request->data['Student']['monthyears']){
				$examMonth = $this->request->data['Student']['monthyears'];
				//$results = $this->beforeRevaluationSearch('1','3','6','37','-','-','Excel');
				$results = $this->beforeRevaluationSearch($examMonth,'-','','-','-','-','Excel');
				$this->resultsToWebsiteMarkData($results, $examMonthYear, $reval_mode);
			}
		}
	
		$monthyears = $this->MonthYear->getAllMonthYears();
		$this->set(compact('monthyears'));
		}
		else {
			$this->render('../Users/access_denied');
		}
	}
	
	public function publishWebsiteMark($examMonthYear = null) {
		$access_result = $this->checkPathAccess($this->Auth->user('id'));
		if (!$access_result) {
		if ($this->request->is('post')) {
			if($this->request->data['Student']['monthyears']){
				$examMonth = $this->request->data['Student']['monthyears'];	
				//$results = $this->beforeRevaluationSearch('3','2','5','30','-','-','Excel');
				$results = $this->beforeRevaluationSearch($examMonth,'-','','-','-','-','Excel');
				//$this->publishWebsiteMarkData($results, "BR");
				//$this->publishWebsiteMarkDataAfterRevaluation($results, "BR");
				$this->markDataCoE($results, "BR");
			}
		}
	
		$monthyears = $this->MonthYear->getAllMonthYears();
		$this->set(compact('monthyears'));
		}
		else {
			$this->render('../Users/access_denied');
		}
	}
	
	public function publishWebsiteMarkAfterRevaluation($examMonthYear = null) {
		$access_result = $this->checkPathAccess($this->Auth->user('id'));
		if (!$access_result) {
		$access_result = $this->checkPathAccess($this->Auth->user('id'));
		if (!$access_result) {
			if ($this->request->is('post')) {
				if($this->request->data['Student']['monthyears']) {
					$examMonth = $this->request->data['Student']['monthyears'];
					//$results = $this->afterRevaluationSearch('1','3','6','37','-','-','Excel');
					$results = $this->beforeRevaluationSearch($examMonth,'-','','-','-','-','Excel', 'after');
					//$this->publishWebsiteMarkDataAfterRevaluation($results, "AR");
					$this->markDataCoE($results, "AR");
				}
			}
		
			$monthyears = $this->MonthYear->getAllMonthYears();
			$this->set(compact('monthyears'));
		}
		else {
			$this->render('../Users/access_denied');
		}
		}
		else {
			$this->render('../Users/access_denied');
		}
	}
	
	public function coe() {
		$academics = $this->Program->Academic->find('list',array('order' => array('Academic.academic_name ASC')));
		$programs = $this->Program->find('list',array('order' => array('Program.program_name ASC')));
		$batches = $this->Batch->find('list', array('fields' => array('Batch.batch_period'),'order' => array('Batch.batch_to DESC')));
		$monthYears = $this->MonthYear->find('all', array('fields' => array('MonthYear.id','MonthYear.year','MonthYear.month_id','Month.month_name'),'order' => array('MonthYear.id DESC')));
		$monthyears=array();
	
		foreach ($monthYears as $key => $value) {
			$monthyears[$value['MonthYear']['id']]=$value['Month']['month_name']."-".$value['MonthYear']['year'];
		}
		$this->set(compact('academics','programs','batches','monthyears'));
	}
	
	public function coeSearch($exam_month_year_id = null, $batch_id = null, $academic_id = null, $program_id = null, $printMode = null) {
		$studentResults = $this->Student->getStudentsInfo($batch_id, $program_id);
		//$results = $this->retrieveStudentData($exam_month_year_id, $batch_id, $academic_id, $program_id);
		//pr($studentResults);
		//die;
		$this->set(compact('studentResults', 'batch_id', 'academic_id', 'program_id', 'exam_month_year_id'));
		$this->layout=false;
	
	}
	
	public function viewStudentData($exam_month_year_id, $batch_id, $academic_id, $program_id, $student_id) {
		$res = array(
				'conditions' => array('Student.batch_id'=>$batch_id, 'Student.program_id'=>$program_id,
						'Student.discontinued_status'=>0, 'Student.id'=>$student_id
				),
				'fields' =>array('Student.id','Student.registration_number','Student.name'),
				'contain'=>array(
						'Batch' => array(
								'fields' => array('Batch.batch_from', 'Batch.batch_to', 'Batch.academic')
						),
						'Program' => array('fields' => array('Program.program_name'),
								'Academic' => array('fields' => array('Academic.academic_name')
								)
						),
						'InternalPractical' => array(
								'fields' => array('InternalPractical.month_year_id', 'InternalPractical.marks', 'InternalPractical.student_id'),
								'conditions' => array('InternalPractical.month_year_id' => $exam_month_year_id),
								'CaePractical' => array(
										'fields' => array('CaePractical.course_mapping_id')
								)
						),
						'Practical' => array(
								'fields' => array('Practical.month_year_id', 'Practical.marks', 'Practical.student_id',
										'Practical.id', 'Practical.ese_mod_marks', 'Practical.ese_practical_id'
								),
								'conditions' => array('Practical.month_year_id' => $exam_month_year_id),
								'EsePractical' => array(
										'fields' => array('EsePractical.course_mapping_id')
								)
						),
						'InternalExam' => array(
								'fields' => array('InternalExam.month_year_id', 'InternalExam.marks', 'InternalExam.student_id', 'InternalExam.course_mapping_id'),
								'conditions' => array('InternalExam.month_year_id' => $exam_month_year_id)
						),
						'EndSemesterExam' => array(
								'fields' => array('EndSemesterExam.month_year_id', 'EndSemesterExam.marks',
										'EndSemesterExam.student_id', 'EndSemesterExam.course_mapping_id',
										'EndSemesterExam.id', 'EndSemesterExam.moderation_marks',
										'EndSemesterExam.moderation_operator'
								),
								'conditions' => array('EndSemesterExam.month_year_id' => $exam_month_year_id)
						),
						'StudentMark' => array(
								'fields' => array('StudentMark.month_year_id', 'StudentMark.marks', 'StudentMark.student_id',
										'StudentMark.course_mapping_id', 'StudentMark.status', 'StudentMark.id'),
								'conditions' => array('StudentMark.month_year_id' => $exam_month_year_id),
								'CourseMapping'=>array('fields'=>array('CourseMapping.id'),
										'conditions'=>array('CourseMapping.indicator'=>0,
										),
										'Course' => array(
												'fields' => array('Course.course_code','Course.course_max_marks', 
														'Course.max_ese_mark', 'Course.min_ese_mark',
														'Course.total_min_pass'
												),
												'CourseType' => array('fields' => array('CourseType.course_type'))
										),
								),
						)
				),
		);
		$results = $this->Student->find('all', $res);
	
		$my = $this->MonthYear->find('first', array(
				'fields'=>array('MonthYear.id', 'MonthYear.year'),
				'conditions' => array('MonthYear.id' => $exam_month_year_id),
				'contain' => array(
						'Month' => array(
								'fields' => array('Month.month_name')
						)
				)
		));
		//pr($my);
		$month_year = $my['Month']['month_name']."-".$my['MonthYear']['year'];
		$this->set(compact('results', 'batch_id', 'academic_id', 'program_id', 'exam_month_year_id', 'student_id', 'month_year', 'exam_month_year_id'));
		//return $results;
		$bool = false;
		$smBool = false;
	
		$courses="";
		if($this->request->is('post')) {
			//pr($this->data);
			//die;
			$reg_num = $this->request->data['reg_num'];
			$allData = $this->request->data['Mark'];
			$student_id = $this->request->data['student_id'];
			$month_year_id = $this->request->data['month_year_id'];
				
			//$theoryNewMarks = $theory['New'];
			foreach ($allData as $model => $modelArray) {
				foreach ($modelArray['New'] as $th_pr => $newEseMark) {
					//echo "New : ".$newEseMark." Old : ".$theory['Old'][$cm_id]." ese Id : ".$theory['id'][$cm_id]." Mod marks : ".$theory['mod_marks'][$cm_id];
					$oldEseMark = $modelArray['Old'][$th_pr];
					$id = $modelArray['id'][$th_pr];
					$mod_marks = $modelArray['mod_marks'][$th_pr];
						
					if ($model == "Practical") {
						$cm_id = $modelArray['EsePracticalCmId'][$th_pr];
						//pr($cm_id);
					}
					else if ($model == "EndSemesterExam") {
						$cm_id = $th_pr;
					}
						
					$oldStudentMark = $modelArray['StudentMark'][$cm_id];
					//pr($oldStudentMark);
					//die;
					$sm_id = $modelArray['StudentMarkId'][$cm_id];
						
					$max_ese_mark = $modelArray['MaxEseMark'][$cm_id];
					$course_max_mark = $modelArray['CourseMaxMark'][$cm_id];
					$min_ese_mark = $modelArray['MinEseMark'][$cm_id];
					$total_min_pass = $modelArray['TotalMinPass'][$cm_id];
						
					if ($newEseMark > $oldEseMark) {
						$tmp = $newEseMark-$oldEseMark;
						$mod_marks = $mod_marks + $tmp;
	
						$newStudentMark = $oldStudentMark+$tmp;
	
						$ese_pass_mark = round($max_ese_mark * $min_ese_mark/100);
						$total_pass_mark = round($course_max_mark * $total_min_pass/100);
	
						if ($newEseMark>=$ese_pass_mark && $newStudentMark>=$total_pass_mark)
							$status = "Pass";
							else
								$status = "Fail";
	
								if ($model == "EndSemesterExam") {
									$table = "end_semester_exams";
									$mod_marks_field = "moderation_marks";
									$mod_marks_operator = "moderation_operator";
									$checkField = "course_mapping_id";
								}
								else if ($model == "Practical") {
									$table = "practicals";
									$mod_marks_field = "ese_mod_marks";
									$mod_marks_operator = "ese_mod_operator";
									$checkField = "ese_practical_id";
								}
								$modOperator = "plus";
	
								$conditions = array("$model.".".$checkField" => $th_pr,
										"$model.month_year_id" => $month_year_id,
										"$model.student_id" => $student_id
								);
								//pr($conditions);
								if ($this->$model->hasAny($conditions)) {
									$this->$model->query("UPDATE $table set
											marks=".$newEseMark.",
											$mod_marks_field=".$mod_marks.",
											$mod_marks_operator='".$modOperator."',
									modified = '".date("Y-m-d H:i:s")."',
									modified_by = ".$this->Auth->user('id')."
											WHERE $checkField = ".$th_pr." AND
									month_year_id = ".$month_year_id." AND
									student_id = ".$student_id." And
									id = ".$id
											);
									$bool = true;
								}
	
								$conditions = array('StudentMark.course_mapping_id' => $cm_id,
										'StudentMark.month_year_id' => $month_year_id,
										'StudentMark.student_id' => $student_id
								);
								//pr($conditions);
								if ($this->StudentMark->hasAny($conditions)) {
									$this->StudentMark->query("UPDATE student_marks set
									marks=".$newStudentMark.",
									status='".$status."',
									modified = '".date("Y-m-d H:i:s")."',
									modified_by = ".$this->Auth->user('id')."
									WHERE course_mapping_id = ".$cm_id." AND
									month_year_id = ".$month_year_id." AND
									student_id = ".$student_id." And
									id = ".$sm_id
											);
									$smBool = true;
								}
								if ($bool && $smBool) {
									$courses.=$modelArray['CourseCode'][$cm_id].",";
								}
					}
				}
			}
			$courses = substr($courses,0,strlen($courses)-1);
			if ($courses <> "")
				$this->Flash->success(__($courses." have been modified."));
				else
					$this->Flash->success(__("No changes made to registration number : ".$reg_num));
					$this->redirect("coe");
		}
	}
	
	public function afterRevaluation() {
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
	
	/* public function afterRevaluationSearch($examMonth = null,$batchId = null,$Academic = null,$programId = null,$withheldType = null,$withheldVal = null,$printMode = null) {
		
		$this->set('examMonth', $examMonth);
		$this->set('batchId', $batchId);
		$this->set('Academic', $Academic);
		$this->set('programId', $programId);
		$this->set('withheldType', $withheldType);
		$this->set('withheldVal', $withheldVal);
		$this->set('printMode', $printMode);
	
		//echo " examMonth=> ".$examMonth." BatchId=> ".$batchId." Academic=> ".$Academic." programId=> ".$programId." With held Type=> ".$withheldType." With held Val=> ".$withheldVal." printMode=>".$printMode;exit;
		
		$stuCond=array(); $markCond=array();  $attenCond=array();$tTCond=array();$iExam=array();$eExam=array();$SWMYE = array();
	
		//$attenCond['ExamAttendance.attendance_status'] = 0;
		$tTCond['Timetable.indicator'] = 0;
		$stuCond['Student.discontinued_status'] = 0;		
		//$stuCond['Student.registration_number'] = array(3541183);
		if($examMonth != '-'){
			$markCond['StudentMark.month_year_id'] = $examMonth;
			$tTCond['Timetable.month_year_id'] = $examMonth;
			$iExam['InternalExam.month_year_id'] = $examMonth;
			$eExam['EndSemesterExam.month_year_id'] = $examMonth;
			$SWMYE['StudentWithheld.month_year_id'] = $examMonth;
			
			
		}
		if($batchId != '-'){
			$stuCond['Student.batch_id'] = $batchId;
		}
		if($programId != '-'){
			$stuCond['Student.program_id'] = $programId;
		}
		if($withheldType != '-'){
			if($withheldVal != '-'){
				$SWMYE['StudentWithheld.withheld_id'] = $withheldVal;
			}
				
			$getWithheldStu = array(
					'conditions' => array('StudentWithheld.indicator' => 0,$SWMYE
					),
					'fields' =>array('StudentWithheld.student_id'),
					'contain'=>array(
								
					),
			);
			$getWithheldStuRes = $this->StudentWithheld->find("all", $getWithheldStu);
				
			$totWHStuId = array();$w=0;
			foreach($getWithheldStuRes as $eachStuId){
				$totWHStuId[$w] = $eachStuId['StudentWithheld']['student_id'];
				$w++;
			}
			if($totWHStuId){
				$stuCond['Student.id IN'] = $totWHStuId;
				$this->set('withheldType','All');
			}else{
				$this->layout= false;
				$this->set('results', '');
				return false;
			}
		}
		$finalArray = array();
		$stuid = $this->StudentMark->find('all', array(
				'fields'=>array('DISTINCT StudentMark.student_id'),
				'conditions'=>array('StudentMark.month_year_id' => $examMonth,
						//'StudentMark.student_id'=>2569
				),
				'contain'=>false
		));
		//pr($stuid);
		
		$stuIdArray = array();
		foreach ($stuid as $key => $stuArray) {
			$stuIdArray[$stuArray['StudentMark']['student_id']]=$stuArray['StudentMark']['student_id'];
		}
		//echo count($stuIdArray);
		//pr($stuIdArray);
		
		$results = array();
	foreach ($stuIdArray as $student_id => $value) {
			//echo $student_id." ".$examMonth;
			$tmpStudentResults = $this->getUpToDateDataOFAStudent($student_id, $examMonth);
			//pr($tmpStudentResults);
		
			if (isset($tmpStudentResults) && !empty($tmpStudentResults )) {
				foreach ($tmpStudentResults as $tKey => $tValue) {
					
					if ($tValue['StudentMark']['revaluation_status']) $tStatus = $tValue['StudentMark']['final_status'];
					else $tStatus = $tValue['StudentMark']['status'];
					//echo "</br>".$tStatus;
					if ($tValue['StudentMark']['month_year_id'] < $examMonth && $tStatus == 'Fail') { 
						$results[$student_id][$tValue['StudentMark']['course_mapping_id']] = $tValue;
					}
					else if ($tValue['StudentMark']['month_year_id'] == $examMonth && ($tStatus == 'Fail' || $tStatus == 'Pass')) {  
						$results[$student_id][$tValue['StudentMark']['course_mapping_id']] = $tValue;
					}
				}
			}
		}
		//echo " count ".count($results[$student_id]);
		//pr($results);
		
		$courseMappingArray = array();
		
		$tTCond['Timetable.indicator'] = 0;
		$tTCond['Timetable.month_year_id'] = $examMonth;
		
		//$stuCond['Student.registration_number'] = array(3541183);
		
		
		$array = array();
		foreach ($results as $student_id => $stuArray) { //pr($stuArray);
			$student_details = $this->Student->studentDetails($student_id);
			//pr($student_details);
			
			$array['registration_number'] = $student_details[0]['Student']['registration_number'];
			$array['name'] = $student_details[0]['Student']['name'];
			$array['birth_date'] = $student_details[0]['Student']['birth_date'];
			$array['section'] = $student_details[0]['Section']['name'];
			$batch_id = $student_details[0]['Student']['batch_id'];
			$program_id = $student_details[0]['Student']['program_id'];
			
			//$smArray = $stuArray['StudentMark'];
			
			$finalArray[$student_id] = array();
			
			foreach ($stuArray as $key => $value) { 
			//	pr($value);
				
				$array['attendance'] = '';
				$array['cae'] = '';
				$array['ese'] = '';
				$array['status'] = '';
				$array['total'] = '';
				$array['sm_id'] = '';
				$array['course_code'] = '';
				$array['course_name'] = '';
				$array['course_max_marks'] = '';
				$array['cm_id'] = '';
				$array['course_type'] = '';
				$array['dummy_number'] = '';
				$array['actual_month_year_id'] = '';
				
				$ctype = '';
				$marks = '';
				$status = '';
				$final_marks = '';
				$final_status = '';
				$cm_id = '';
				$revaluation_status = '';
				
				$array['sm_id'] = $value['StudentMark']['id'];
				
				$marks = $value['StudentMark']['marks'];
				$status = $value['StudentMark']['status'];
				$revaluation_status = $value['StudentMark']['revaluation_status'];
				$final_marks = $value['StudentMark']['final_marks'];
				$final_status = $value['StudentMark']['final_status'];
				$cm_id = $value['StudentMark']['course_mapping_id'];
				
				$course_type_array = $this->getCourseTypeIdFromCmId($cm_id);
				$course_array = $this->getCourseNameCrseCodeFromCMId($cm_id);
				//pr($course_array);
				
				$array['actual_month_year_id'] = $value['CourseMapping']['month_year_id'];
				
				$array['course_code'] = $course_array[0]['Course']['course_code'];
				$array['course_name'] = $course_array[0]['Course']['course_name'];
				$array['course_max_marks'] = $course_array[0]['Course']['course_max_marks'];
				$course_type_id = $course_type_array[0]['Course']['course_type_id'];
				$array['course_type_id'] = $course_type_id;
				
				$courseMappingArray[$cm_id]=$course_type_id;
				
				$array['cm_id'] = $cm_id;
				
				SWITCH ($course_type_id) {
					CASE 1:
						$attendance_status = '';
						$array['course_type'] = 'Theory';
						$ttResult = $this->Timetable->query('SELECT ea.attendance_status FROM timetables tt JOIN 
								exam_attendances ea ON tt.id = ea.timetable_id 
								WHERE tt.month_year_id='.$examMonth.' and tt.course_mapping_id='.$cm_id.' 
										and ea.student_id='.$student_id);
						//pr($ttResult);
						if (isset($ttResult[0]['ea']['attendance_status'])) {
							$attendance_status = $ttResult[0]['ea']['attendance_status'];
						}
						$stu_results = $this->theoryResults($examMonth, $cm_id, $student_id);
						//pr($stu_results);
						$array['cae'] = $stu_results[0]['InternalExam'][0]['marks'];
						$array['attendance'] = $attendance_status;
						//echo "</br>".$revaluation_status." ".$student_id." ".$cm_id." ".$revaluation_status;
						if ($revaluation_status) {
							$tmp_ese = 'A';
							if (isset($stu_results[0]['EndSemesterExam']) && !empty($stu_results[0]['EndSemesterExam'])) {
								$tmp_ese = $stu_results[0]['EndSemesterExam'][0]['marks'];
							}
							$tmp_reval = 'A';
							if (isset($stu_results[0]['RevaluationExam']) && !empty($stu_results[0]['RevaluationExam'])) {
								$tmp_reval = $stu_results[0]['RevaluationExam'][0]['revaluation_marks'];
							}
							$array['ese'] = ($tmp_ese > $tmp_reval ? $tmp_ese : $tmp_reval);
							$array['status'] = $final_status;
						}
						else {
							if (isset($stu_results[0]['EndSemesterExam'][0]['marks'])) {
								$array['ese'] = $stu_results[0]['EndSemesterExam'][0]['marks'];
								$array['dummy_number'] = $stu_results[0]['EndSemesterExam'][0]['dummy_number'];
							}
							else {
								$array['ese'] = '';
							}
							$array['status'] = $status;
						}
						if ($attendance_status == 0) $array['ese'] = "A";
						$array['total'] = $array['cae'] + $array['ese'];
						//echo "cae: ".$cae. " ese: ".$ese." total: ".$total." status: ".$status;
						break;
					CASE 2:
					CASE 6:
						if ($course_type_id == 2) $array['course_type'] = 'Practical';
						if ($course_type_id == 6) $array['course_type'] = 'Studio';
						
						$stu_results = $this->practicalResults($examMonth, $cm_id, $student_id);
						//pr($stu_results);
						//pr($array);
						//echo "Status : ".$array['status'];
						$array['cae'] = $stu_results[0]['CaePractical'][0]['InternalPractical'][0]['marks'];
						$array['ese'] = $stu_results[0]['EsePractical'][0]['Practical'][0]['marks'];
						$array['status'] = $status;
						$array['total'] = $array['cae'] + $array['ese'];
						if ($array['ese']>=0) $array['attendance']=1;
						//echo "cae: ".$cae. " ese: ".$ese." total: ".$total." status: ".$status;
						break;
					CASE 3:
						$array['course_type'] = 'Theory and Practical';
						$ttResult = $this->Timetable->query('SELECT ea.attendance_status FROM timetables tt JOIN
								exam_attendances ea ON tt.id = ea.timetable_id
								WHERE tt.month_year_id='.$examMonth.' and tt.course_mapping_id='.$cm_id.'
										and ea.student_id='.$student_id); */
						//pr($ttResult);
						/* $result_theory = $this->theoryResults($examMonth, $cm_id, $student_id);
						$result_practical = $this->practicalResults($examMonth, $cm_id, $student_id);
						//pr($result_theory);
						//pr($result_practical);
						
						$result_theory = $this->theoryResults($examMonth, $cm_id, $student_id);
						$array['cae'] = $result_theory[0]['InternalExam'][0]['marks'];
						
						$result_practical = $this->practicalResults($examMonth, $cm_id, $student_id);
						$array['ese'] = $result_practical[0]['EsePractical'][0]['Practical'][0]['marks'];
						
						$array['status'] = $status;
						$array['total'] = $array['cae'] + $array['ese'];
						//echo " cae: ".$array['cae']. " ese: ".$array['ese']." total: ".$array['total']." status: ".$array['status'];
						
						break;
					CASE 4:
						$array['course_type'] = 'Project';
						$stu_results = $this->projectResults($examMonth, $cm_id, $student_id);
						//pr($stu_results);
						$array['cae'] = $stu_results[0]['InternalProject'][0]['marks'];
						$array['ese'] = $stu_results[0]['EseProject'][0]['ProjectViva'][0]['marks'];
						$array['status'] = $status;
						$array['total'] = $array['cae'] + $array['ese'];
						//echo "cae: ".$cae. " ese: ".$ese." total: ".$total." status: ".$status;
						break;
					CASE 5:
						$array['course_type'] = 'Professional Training';
						$stu_results = $this->profTrainingResults($examMonth, $cm_id, $student_id);
						//pr($stu_results);
						$array['cae'] = $stu_results[0]['CaePt']['ProfessionalTraining'][0]['marks'];
						$array['ese'] = "-";
						$array['status'] = $status;
						$array['total'] = $array['cae'];
						//echo " cae: ".$array['cae']. "total: ".$array['total']." status: ".$array['status'];
						break;
				}
			//	pr($array);
				array_push($finalArray[$student_id], $array);
			}
			//array_push($finalArray, $tmpArray);
		}
		//pr($finalArray);

		$this->set('results', $finalArray);	
		$this->layout= false;
	
		if($printMode == 'Dept Excel'){
			$this->PublishResultMarkDataAfterRevaluation($results,$withheldType);
		}
		if($printMode == 'Excel'){
			return $finalArray;
		}
		if($printMode == 'PRINT'){
			$this->layout= 'print';			
			return false;			
		} 
	}*/
	
	
	public function theoryResults($examMonth, $cm_id, $student_id) {
		$results = $this->Student->find('all', array(
				'fields'=>array('Student.id'),
				'conditions'=>array('Student.id'=>$student_id),
				'contain'=>array(
						'InternalExam' => array(
								'fields' => array('InternalExam.id', 'InternalExam.month_year_id', 'InternalExam.marks', 'InternalExam.student_id', 'InternalExam.course_mapping_id'),
								'conditions' =>array('InternalExam.course_mapping_id' => $cm_id, 'InternalExam.student_id' => $student_id),
								'order'=>array('InternalExam.month_year_id DESC'),
								'limit'=>1
						),
						'EndSemesterExam' => array(
								'fields' => array('EndSemesterExam.id', 'EndSemesterExam.month_year_id', 'EndSemesterExam.marks', 'EndSemesterExam.student_id', 'EndSemesterExam.course_mapping_id', 'EndSemesterExam.dummy_number','EndSemesterExam.revaluation_status'),
								'conditions' => array('EndSemesterExam.month_year_id' => $examMonth, 'EndSemesterExam.course_mapping_id'=>$cm_id,
										'EndSemesterExam.student_id' => $student_id
								)
						),
						'RevaluationExam' => array(
								'fields' => array('RevaluationExam.id','RevaluationExam.course_mapping_id',
								'RevaluationExam.revaluation_marks'),
								'conditions' => array('RevaluationExam.month_year_id' => $examMonth, 'RevaluationExam.course_mapping_id'=>$cm_id,
										'RevaluationExam.student_id' => $student_id)
						),
						'StudentMark' => array(
								'fields' => array('StudentMark.id','StudentMark.month_year_id', 'StudentMark.marks', 'StudentMark.student_id', 'StudentMark.course_mapping_id','StudentMark.status','StudentMark.revaluation_status','StudentMark.final_marks','StudentMark.final_status', 'StudentMark.grade'),
								'conditions' => array('StudentMark.course_mapping_id' => $cm_id, 'StudentMark.month_year_id'=>$examMonth),
						),
				),
		));
		return $results;
	}
	
	public function practicalResults($examMonth, $cm_id, $student_id) {
		$results = $this->CourseMapping->find('all', array(
				'fields'=>array('CourseMapping.id'),
				'conditions'=>array('CourseMapping.id'=>$cm_id),
				'contain'=>array(
						'CaePractical' => array(
								'fields' => array('CaePractical.course_mapping_id'),
								'conditions' => array('CaePractical.course_mapping_id'=>$cm_id),
								'InternalPractical' => array(
										'conditions'=>array('InternalPractical.student_id'=>$student_id),
										'fields'=>array('InternalPractical.id', 'InternalPractical.cae_practical_id',
												'InternalPractical.marks', 'InternalPractical.student_id', 'InternalPractical.month_year_id'),
										'order'=>array('InternalPractical.id DESC'),
										'limit'=>1,
		
								),
						),
						'EsePractical' => array(
								'fields' => array('EsePractical.course_mapping_id'),
								'conditions' => array('EsePractical.course_mapping_id'=>$cm_id),
								'Practical' => array(
										'fields' => array('Practical.month_year_id', 'Practical.marks', 'Practical.student_id'),
										'conditions' => array('Practical.month_year_id' => $examMonth, 'Practical.student_id'=>$student_id
										),
		
								),
						),
						'PracticalAttendance' => array(
							'fields' => array('PracticalAttendance.id','PracticalAttendance.month_year_id', 'PracticalAttendance.attendance_status','PracticalAttendance.course_mapping_id'),
							'conditions' => array('PracticalAttendance.month_year_id' => $examMonth, 'PracticalAttendance.student_id'=>$student_id, 'PracticalAttendance.course_mapping_id'=>$cm_id)
						),
						/* 'StudentMark' => array(
								'fields' => array('StudentMark.id','StudentMark.month_year_id', 'StudentMark.marks', 'StudentMark.student_id', 'StudentMark.course_mapping_id','StudentMark.status'),
								'conditions' => array('StudentMark.course_mapping_id' => $cm_id, 'StudentMark.student_id'=>$student_id),
						), */
				),
		));
		return $results;
	}
	
	public function projectResults($examMonth, $cm_id, $student_id) {
		$results = $this->CourseMapping->find('all', array(
				'fields'=>array('CourseMapping.id'),
				'conditions'=>array('CourseMapping.id'=>$cm_id),
				'contain'=>array(
						/* 'CaeProject' => array(
								'fields' => array('CaeProject.course_mapping_id'),
								'conditions' => array('CaeProject.course_mapping_id'=>$cm_id),
						), */
						'InternalProject' => array(
								'conditions'=>array('InternalProject.student_id'=>$student_id),
								'fields'=>array('InternalProject.id', 'InternalProject.marks', 
										'InternalProject.student_id', 'InternalProject.month_year_id'),
								'order'=>array('InternalProject.id DESC'),
								'limit'=>1,
						
						),
						'EseProject' => array(
								'fields' => array('EseProject.course_mapping_id'),
								'conditions' => array('EseProject.course_mapping_id'=>$cm_id),
								'ProjectViva' => array(
										'fields' => array('ProjectViva.month_year_id', 'ProjectViva.marks', 'ProjectViva.student_id'),
										'conditions' => array('ProjectViva.month_year_id' => $examMonth, 'ProjectViva.student_id'=>$student_id
										),
	
								),
						),
						/* 'StudentMark' => array(
								'fields' => array('StudentMark.id','StudentMark.month_year_id', 'StudentMark.marks', 'StudentMark.student_id', 'StudentMark.course_mapping_id','StudentMark.status'),
								'conditions' => array('StudentMark.course_mapping_id' => $cm_id, 'StudentMark.student_id'=>$student_id),
						), */
				),
		));
		return $results;
	}
	
	public function profTrainingResults($examMonth, $cm_id, $student_id) {
		$results = $this->CourseMapping->find('all', array(
				'fields'=>array('CourseMapping.id'),
				'conditions'=>array('CourseMapping.id'=>$cm_id),
				'contain'=>array(
						'CaePt' => array(
								'fields' => array('CaePt.course_mapping_id'),
								'conditions' => array('CaePt.course_mapping_id'=>$cm_id),
								'ProfessionalTraining' => array(
										'conditions'=>array('ProfessionalTraining.student_id'=>$student_id),
										'fields'=>array('ProfessionalTraining.id', 'ProfessionalTraining.cae_pt_id',
												'ProfessionalTraining.marks', 'ProfessionalTraining.student_id', 
												'ProfessionalTraining.month_year_id'),
										'order'=>array('ProfessionalTraining.id DESC'),
										'limit'=>1,
								),
						),
				),
		));
		return $results;
	}
	
	/* public function afterRevaluationSearch($examMonth = null,$batchId = null,$Academic = null,$programId = null,$withheldType = null,$withheldVal = null,$printMode = null) {
		$this->set('examMonth', $examMonth);
		$this->set('batchId', $batchId);
		$this->set('Academic', $Academic);
		$this->set('programId', $programId);
		$this->set('withheldType', $withheldType);
		$this->set('withheldVal', $withheldVal);
		$this->set('printMode', $printMode);
	
		//echo " examMonth=> ".$examMonth." BatchId=> ".$batchId." Academic=> ".$Academic." programId=> ".$programId." With held Type=> ".$withheldType." With held Val=> ".$withheldVal." printMode=>".$printMode;exit;
		$stuCond=array(); $markCond=array();  $attenCond=array();$tTCond=array();$iExam=array();$eExam=array();$SWMYE = array();
	
		$attenCond['ExamAttendance.attendance_status'] = 0;
		$tTCond['Timetable.indicator'] = 0;
		$stuCond['Student.discontinued_status'] = 0;
		//$stuCond['Student.registration_number'] = array(3541183);
		if($examMonth != '-'){
			$markCond['StudentMark.month_year_id'] = $examMonth;
			$tTCond['Timetable.month_year_id'] = $examMonth;
			$iExam['InternalExam.month_year_id'] = $examMonth;
			$eExam['EndSemesterExam.month_year_id'] = $examMonth;
			$SWMYE['StudentWithheld.month_year_id'] = $examMonth;
				
				
		}
		if($batchId != '-'){
			$stuCond['Student.batch_id'] = $batchId;
		}
		if($programId != '-'){
			$stuCond['Student.program_id'] = $programId;
		}
		if($withheldType != '-'){
			if($withheldVal != '-'){
				$SWMYE['StudentWithheld.withheld_id'] = $withheldVal;
			}
	
			$getWithheldStu = array(
					'conditions' => array('StudentWithheld.indicator' => 0,$SWMYE
					),
					'fields' =>array('StudentWithheld.student_id'),
					'contain'=>array(
	
					),
			);
			$getWithheldStuRes = $this->StudentWithheld->find("all", $getWithheldStu);
	
			$totWHStuId = array();$w=0;
			foreach($getWithheldStuRes as $eachStuId){
				$totWHStuId[$w] = $eachStuId['StudentWithheld']['student_id'];
				$w++;
			}
			if($totWHStuId){
				$stuCond['Student.id IN'] = $totWHStuId;
				$this->set('withheldType','All');
			}else{
				$this->layout= false;
				$this->set('results', '');
				return false;
			}
		}
	
		$res = array(
				'conditions' => array($stuCond,
				),
				'fields' =>array('Student.id','Student.registration_number','Student.name','Student.birth_date'),
				'contain'=>array(
						'Section'=>array('fields'=>array('Section.name'),
						),
						'Batch' => array(
								'fields' => array('Batch.batch_from', 'Batch.batch_to', 'Batch.academic')
						),
						'Program' => array('fields' => array('Program.program_name','Program.short_code'),
								'Academic' => array('fields' => array('Academic.academic_name'))
						),
						'ExamAttendance'=>array('fields'=>array('ExamAttendance.id','ExamAttendance.attendance_status'),
								'Timetable'=>array('fields' =>array('Timetable.id', 'Timetable.course_mapping_id'),'conditions' => $tTCond,
								),'conditions'=>$attenCond
						),
						'StudentWithheld'=>array('fields' =>array('StudentWithheld.student_id', 'StudentWithheld.withheld_id'),
								'Withheld'=>array('fields'=>array('Withheld.withheld_type'),
								),'conditions' => array('StudentWithheld.indicator' => 0,$SWMYE),
						),
						'InternalPractical' => array(
								'fields' => array('InternalPractical.month_year_id', 'InternalPractical.marks', 'InternalPractical.student_id'),
								//'conditions' => array('InternalPractical.month_year_id' => $examMonth),
								'CaePractical' => array(
										'fields' => array('CaePractical.course_mapping_id')
								)
						),
						'Practical' => array(
								'fields' => array('Practical.month_year_id', 'Practical.marks', 'Practical.student_id'),
								'conditions' => array('Practical.month_year_id' => $examMonth),
								'EsePractical' => array(
										'fields' => array('EsePractical.course_mapping_id')
								)
						),
						'InternalExam' => array(
								'fields' => array('InternalExam.month_year_id', 'InternalExam.marks', 'InternalExam.student_id', 'InternalExam.course_mapping_id'),
								//'conditions' => array('InternalExam.month_year_id' => $examMonth)
						),
						'EndSemesterExam' => array(
								'fields' => array('EndSemesterExam.month_year_id', 'EndSemesterExam.marks', 'EndSemesterExam.student_id', 'EndSemesterExam.course_mapping_id', 'EndSemesterExam.dummy_number','EndSemesterExam.revaluation_status'),
								'conditions' => array('EndSemesterExam.month_year_id' => $examMonth)
						),
						'InternalProject' => array(
								'fields' => array('InternalProject.month_year_id', 'InternalProject.marks', 'InternalProject.student_id', 'InternalProject.course_mapping_id'),
						),
						'ProjectViva' => array(
								'fields' => array('ProjectViva.month_year_id', 'ProjectViva.marks', 'ProjectViva.student_id'),
								'conditions' => array('ProjectViva.month_year_id' => $examMonth),
								'EseProject' => array(
										'fields' => array('EseProject.course_mapping_id')
								)
						),
						'StudentMark' => array(
								'fields' => array('StudentMark.id','StudentMark.month_year_id', 'StudentMark.marks', 'StudentMark.student_id', 'StudentMark.course_mapping_id','StudentMark.status','StudentMark.revaluation_status','StudentMark.final_marks','StudentMark.final_status'),
								'conditions' => array('StudentMark.month_year_id' => $examMonth),
								'CourseMapping'=>array('fields'=>array('CourseMapping.id','CourseMapping.semester_id'),
										'conditions'=>array('CourseMapping.indicator'=>0,
										),
										'Course' => array(
												'fields' => array('Course.id','Course.course_code','Course.course_name','Course.course_max_marks'),
												'CourseType' => array('fields' => array('CourseType.course_type'))
										),
								)
						),
						'RevaluationExam' =>array(
								'fields' => array('RevaluationExam.id','RevaluationExam.course_mapping_id','RevaluationExam.revaluation_marks','RevaluationExam.final_marks','RevaluationExam.status'),
								'conditions' => array('RevaluationExam.month_year_id' => $examMonth)
						)
				),
		);
	
		$results = $this->Student->find("all", $res);
		$this->set('results', $results);
		$this->layout= false;
	
		if($printMode == 'Dept Excel'){
			$this->PublishResultMarkDataAfterRevaluation($results,$withheldType);
		}
		if($printMode == 'Excel'){
			return $results;
		}
		if($printMode == 'PRINT'){
			$this->layout= 'print';
			return false;
		}
	} */
	
	public function arrearFeesReport() {
		$access_result = $this->checkPathAccess($this->Auth->user('id'));
		if (!$access_result) {
		if($this->request->is('post')) {
			if($this->request->data['Student']['monthyears']){
				$examMonth = $this->request->data['Student']['monthyears'];
				$res = array('conditions' => array('Student.discontinued_status'=>0,),
						'fields' =>array('Student.id','Student.registration_number','Student.name', 'Student.birth_date'),
						'contain'=>array(
								'Section'=>array('fields'=>array('Section.name'),
								),
								'Batch' => array(
										'fields' => array('Batch.batch_from', 'Batch.batch_to', 'Batch.academic')
								),
								'Program' => array('fields' => array('Program.program_name','Program.short_code'),
										'Academic' => array('fields' => array('Academic.academic_name'))
								),
						),		
						'order' =>array('Student.id ASC'),
						'recursive'=>0
				);				
				$results = $this->Student->find("all", $res);
				if($results){
				foreach($results as $result){
						$stuId = $result['Student']['id'];
						$query = $this->StudentMark->query("SELECT sm.id, sm.course_mapping_id, sm.month_year_id,
								cm.semester_id, cs.course_name, cs.course_code,ct.course_type
						FROM student_marks sm 
								JOIN students stu ON sm.student_id=stu.id
								JOIN course_mappings cm ON sm.course_mapping_id = cm.id
								JOIN courses cs ON cm.course_id=cs.id
								JOIN course_types ct ON cs.course_type_id=ct.id
						WHERE student_id = $stuId AND sm.month_year_id <= $examMonth
						AND sm.id IN (SELECT max( id ) FROM `student_marks` WHERE student_id = $stuId
						GROUP BY course_mapping_id ORDER BY id DESC)
						AND ((STATUS='Fail' AND revaluation_status=0)
						OR (final_status='Fail' AND revaluation_status=1))
						ORDER BY course_mapping_id, month_year_id"
						);
						foreach($query as $row){
							$resultsArray[] =
							array(
								"STUDENT_ID"      =>$result['Student']['id'],
								"REGISTER_NUMBER" =>$result['Student']['registration_number'],
								"STUDENT_NAME"    =>$result['Student']['name'],
								"DATE_OF_BIRTH"   =>$result['Student']['birth_date'],
								"SECTION"         =>$result['Section']['name'],
								"SHORT_CODE"      =>$result['Program']['short_code'],
								"BATCH"           =>$result['Batch']['batch_from'].'-'.$result['Batch']['batch_to'],
								"PROGRAM"         =>$result['Program']['Academic']['academic_name'],
								"SPECIALISATION"  =>$result['Program']['program_name'],
								"SEMESTER"        =>$row['cm']['semester_id'],
								"COURSE_CODE"     =>$row['cs']['course_code'],
								"COURSE_NAME"     =>$row['cs']['course_name'],
								"PUBLISHED_RESULT"=>'RA',
								"COURSE_TYPE"     =>$row['ct']['course_type']
							);
						}
				}}
				$this->arrearFeesReportMarkData($resultsArray);
			}
		}
	
		$monthyears = $this->MonthYear->getAllMonthYears();
		$this->set(compact('monthyears'));
		}
		else {
			$this->render('../Users/access_denied');
		}
	}
	
	public function cgpa() {
		$academics = $this->Program->Academic->find('list',array('order' => array('Academic.academic_name ASC')));
		$programs = $this->Program->find('list',array('order' => array('Program.program_name ASC')));
		$batches = $this->Batch->find('list', array('fields' => array('Batch.batch_period'),'order' => array('Batch.batch_to DESC')));
		$monthyears = $this->MonthYear->getAllMonthYears();
		$this->set(compact('academics','programs','batches','monthyears'));
	
	}
	
	public function topRankingData($examMonth = null,$batchId = null,$Academic = null,$programId = null,$withheldType = null,$withheldVal = null,$printMode = null) {
		$this->set('examMonth', $examMonth);
		$this->set('batchId', $batchId);
		$this->set('Academic', $Academic);
		$this->set('programId', $programId);
		$this->set('withheldType', $withheldType);
		$this->set('withheldVal', $withheldVal);
		$this->set('printMode', $printMode);
	
		//echo " examMonth=> ".$examMonth." BatchId=> ".$batchId." Academic=> ".$Academic." programId=> ".$programId." With held Type=> ".$withheldType." With held Val=> ".$withheldVal." printMode=>".$printMode;exit;
		$stuCond=array(); $markCond=array();  $attenCond=array();$tTCond=array();$iExam=array();$eExam=array();$SWMYE = array();
	
		$attenCond['ExamAttendance.attendance_status'] = 0;
		$tTCond['Timetable.indicator'] = 0;
		$stuCond['Student.discontinued_status'] = 0;
	
		if($examMonth != '-'){
			$markCond['StudentMark.month_year_id'] = $examMonth;
			$tTCond['Timetable.month_year_id'] = $examMonth;
			$iExam['InternalExam.month_year_id'] = $examMonth;
			$eExam['EndSemesterExam.month_year_id'] = $examMonth;
			$SWMYE['StudentWithheld.month_year_id'] = $examMonth;
		}
		if($batchId != '-'){
			$stuCond['Student.batch_id'] = $batchId;
		}
		if($programId != '-'){
			$stuCond['Student.program_id'] = $programId;
		}
		if($withheldType != '-'){
			if($withheldVal != '-'){
				$SWMYE['StudentWithheld.withheld_id'] = $withheldVal;
			}
	
			$getWithheldStu = array(
					'conditions' => array('StudentWithheld.indicator' => 0,$SWMYE
					),
					'fields' =>array('StudentWithheld.student_id'),
					'contain'=>array(
	
					),
			);
			$getWithheldStuRes = $this->StudentWithheld->find("all", $getWithheldStu);
	
			$totWHStuId = array();$w=0;
			foreach($getWithheldStuRes as $eachStuId){
				$totWHStuId[$w] = $eachStuId['StudentWithheld']['student_id'];
				$w++;
			}
			if($totWHStuId){
				$stuCond['Student.id IN'] = $totWHStuId;
				$this->set('withheldType','All');
			}else{
				$this->layout= false;
				$this->set('results', '');
				return false;
			}
		}
	
		$res = array(
				'conditions' => array($stuCond,
				),
				'fields' =>array('Student.id','Student.registration_number','Student.name','Student.birth_date'),
				'contain'=>array(
						'Batch' => array(
								'fields' => array('Batch.batch_from', 'Batch.batch_to', 'Batch.academic')
						),
						'Program' => array('fields' => array('Program.program_name','Program.short_code'),
								'Academic' => array('fields' => array('Academic.academic_name'))
						),
						'ExamAttendance'=>array('fields'=>array('ExamAttendance.id','ExamAttendance.attendance_status'),
								'Timetable'=>array('fields' =>array('Timetable.id', 'Timetable.course_mapping_id'),'conditions' => $tTCond,
								),'conditions'=>$attenCond
						),
						'StudentWithheld'=>array('fields' =>array('StudentWithheld.student_id', 'StudentWithheld.withheld_id'),
								'Withheld'=>array('fields'=>array('Withheld.withheld_type'),
								),'conditions' => array('StudentWithheld.indicator' => 0,$SWMYE),
						),
						'InternalPractical' => array(
								'fields' => array('InternalPractical.month_year_id', 'InternalPractical.marks', 'InternalPractical.student_id'),
								'conditions' => array('InternalPractical.month_year_id' => $examMonth),
								'CaePractical' => array(
										'fields' => array('CaePractical.course_mapping_id')
								)
						),
						'Practical' => array(
								'fields' => array('Practical.month_year_id', 'Practical.marks', 'Practical.student_id'),
								'conditions' => array('Practical.month_year_id' => $examMonth),
								'EsePractical' => array(
										'fields' => array('EsePractical.course_mapping_id')
								)
						),
						'InternalExam' => array(
								'fields' => array('InternalExam.month_year_id', 'InternalExam.marks', 'InternalExam.student_id', 'InternalExam.course_mapping_id'),
								'conditions' => array('InternalExam.month_year_id' => $examMonth)
						),
						'EndSemesterExam' => array(
								'fields' => array('EndSemesterExam.month_year_id', 'EndSemesterExam.marks', 'EndSemesterExam.student_id', 'EndSemesterExam.course_mapping_id', 'EndSemesterExam.dummy_number','EndSemesterExam.revaluation_status'),
								'conditions' => array('EndSemesterExam.month_year_id' => $examMonth)
						),
						'StudentMark' => array(
								'fields' => array('StudentMark.id','StudentMark.month_year_id', 'StudentMark.marks', 'StudentMark.student_id', 'StudentMark.course_mapping_id','StudentMark.status','StudentMark.revaluation_status','StudentMark.final_marks','StudentMark.final_status'),
								'conditions' => array('StudentMark.month_year_id' => $examMonth),
								'CourseMapping'=>array('fields'=>array('CourseMapping.id','CourseMapping.semester_id'),
										'conditions'=>array('CourseMapping.indicator'=>0,
										),
										'Course' => array(
												'fields' => array('Course.id','Course.course_code','Course.course_name','Course.course_max_marks'),
												'CourseType' => array('fields' => array('CourseType.course_type'))
										),
								)
						),
						'RevaluationExam' =>array(
								'fields' => array('RevaluationExam.id','RevaluationExam.course_mapping_id','RevaluationExam.revaluation_marks','RevaluationExam.final_marks','RevaluationExam.status'),
								'conditions' => array('RevaluationExam.month_year_id' => $examMonth)
						)
				),
		);
	
		$results = $this->Student->find("all", $res);
	
		$this->set('results', $results);
	
		$this->layout= false;
	
		if($printMode == 'Dept Excel'){
			$this->PublishResultMarkDataAfterRevaluation($results,$withheldType);
		}
		if($printMode == 'Excel'){
			return $results;
		}
		if($printMode == 'PRINT'){
			$this->layout= 'print';
			return false;
				
		}
	}
	
	public function getCourseMappingResult($examMonth, $batchId, $programId) {
		$cond = array();
		if ($batchId > 0 && $batchId!='-') $cond['CourseMapping.batch_id'] = $batchId;
		if ($programId > 0 && $programId!='-') $cond['CourseMapping.program_id'] = $programId;
		if ($examMonth > 0 && $examMonth!='-') $cond['CourseMapping.month_year_id <='] = $examMonth;
		
		$cond['CourseMapping.indicator'] = 0;
		
		$programResult = $this->CourseMapping->find('all', array(
				'conditions' => array($cond
				),
				'fields' => array('DISTINCT CourseMapping.program_id', 'CourseMapping.batch_id',
						'GROUP_CONCAT(CourseMapping.id) AS CMId', 'MAX(CourseMapping.semester_id) AS semester_id'),
				'contain' => array(
						'Batch' => array(
								'fields' => array('Batch.batch_from', 'Batch.batch_to', 'Batch.academic')
						),
						'Course' => array(
								'fields' => array('SUM(Course.course_max_marks) as max_marks'),
						),
						'Program' => array(
								'fields' => array('Program.short_code','Program.program_name'),
								'Academic' => array(
										'fields' => array('Academic.short_code','Academic.academic_name')
								)
						),
				),
				'group'=>array('CourseMapping.batch_id','CourseMapping.program_id')
		));
		//pr($programResult);
		return $programResult;
	}
	
	public function processTopRankingResults($stuResults, $cm_id, $array) {
		$topTotal = array();
		foreach ($stuResults as $key => $stuArray) {
			//echo "</br>".$i++." ".$student_id;
			$student_id = $stuArray['Student']['id'];
			$tmpCsmCount = $this->CourseStudentMapping->query("select count(*) as csm from course_student_mappings where
					student_id = $student_id and course_mapping_id IN($cm_id)  and indicator=0");
			//pr($tmpCsmCount);die;
			$csmCount = $tmpCsmCount[0][0]['csm'];
				
			$tmpPassResults = $this->StudentMark->query("SELECT count( * ) AS pass, student_id, 
					sum( IF( revaluation_status = '1', final_marks, marks ) ) AS total
					FROM student_marks
					WHERE ((STATUS = 'Pass' AND revaluation_status =0) OR (revaluation_status =1 AND final_status = 'Pass'))
					AND student_id = ".$student_id);

			$tmpFailResults = $this->StudentMark->query("SELECT count( * ) AS fail, student_id
					FROM student_marks
					WHERE ((STATUS = 'Fail' AND revaluation_status =0) OR (revaluation_status =1 AND final_status = 'Fail')) 
					AND student_id = ".$student_id);
			
			$passCount = $tmpPassResults[0][0]['pass'];
			$failCount = $tmpFailResults[0][0]['fail'];
			
			if ($csmCount == $passCount && $failCount == 0) {
				$topTotal[$tmpPassResults[0]['student_marks']['student_id']] =
				array(
						"id" => $stuArray['Student']['id'],
						"batch_id" => $array['batch_id'],
						"program_id" => $array['program_id'],
						"name" => $stuArray['Student']['name'],
						"picture" => $stuArray['Student']['picture'],
						"registration_number" => $stuArray['Student']['registration_number'],
						"short_code" => $array['short_code'],
						"batch" => $array['batch'],
						"program" => $array['program'],
						"specialisation" => $array['specialisation'],
						"semester" => $array['semester'],
						"max_marks" =>	$array['max_marks'],
						"total" => $tmpPassResults[0][0]['total'],
				);
			}
		}
		return $topTotal;
	}
	
	public function topRanking($examMonthYear = null) {
	
		if ($this->request->is('post')) {
			if($this->request->data['Student']['monthyears']){
				$examMonth = $this->request->data['Student']['monthyears'];
				//start
				$reportResult = array();
				$programResult = $this->getCourseMappingResult($examMonth, '-', '-');

				foreach ($programResult as $key => $pgmResult) { //pr($pgmResult);
					$academic = "";
					if (isset($pgmResult['Batch']['academic']) && $pgmResult['Batch']['academic'] == "Jun") {
						$academic = "A";
					}
					$batch_period = $pgmResult['Batch']['batch_from']."-".$pgmResult['Batch']['batch_to']." ".$academic;
					//$this->set(compact('batch_period'));
					$program_id = $pgmResult['CourseMapping']['program_id'];
					$batch_id = $pgmResult['CourseMapping']['batch_id'];
					$cm_id = $pgmResult[0]['CMId'];
					$pgm_short_code = $pgmResult['Program']['short_code'];
					$academic_short_code = $pgmResult['Program']['Academic']['short_code'];
					
					$stuResults = $this->Student->getActiveStudents($batch_id, $program_id, '-');
					//pr($stuResults);
					
					$totalStrength = $this->Student->getCount($batch_id, $program_id);
					
					$array = array();
					$array['batch_id'] = $batch_id;
					$array['program_id'] = $program_id;
					$array['short_code'] = $pgm_short_code;
					$array['batch'] = $batch_period;
					$array['program'] = $pgmResult['Program']['Academic']['academic_name'];
					$array['specialisation'] = $pgmResult['Program']['program_name'];
					$array['semester'] = $pgmResult[0]['semester_id'];
					$array['max_marks'] = $pgmResult[0]['max_marks'];

					$i = 1; $topTotal = array();
					$topTotal = $this->processTopRankingResults($stuResults, $cm_id, $array);
					//pr($topTotal);
					
					uasort($topTotal, array($this,'compare_total'));
					$reportResult[] = $topTotal;
				}
				//pr($reportResult);
				$result = array();
				foreach ($reportResult as $key => $val) {
					//pr($val);
					$result[$key] = array_slice($val, 0, 10);
				}
				//pr($result);
				
				$this->topRankingList($result);
				//End
			}
		}
	
		$monthyears = $this->MonthYear->getAllMonthYears();
		$this->set(compact('monthyears'));
	}	
	
	public function compare_total($a, $b)
	{
		return strnatcmp($b['total'], $a['total']);
	}
	
	public function cgpaList($batchId = null,$Academic = null,$programId = null,$withheldType = null,$withheldVal = null,$printMode = null) {
		
		$this->set('batchId', $batchId);
		$this->set('Academic', $Academic);
		$this->set('programId', $programId);
		$this->set('withheldType', $withheldType);
		$this->set('withheldVal', $withheldVal);
		$this->set('printMode', $printMode);
		
		//echo " BatchId=> ".$batchId." Academic=> ".$Academic." programId=> ".$programId." With held Type=> ".$withheldType." With held Val=> ".$withheldVal." printMode=>".$printMode;exit;
		$stuCond=array(); $markCond=array();  $attenCond=array();$tTCond=array();$iExam=array();$eExam=array();$SWMYE = array();
		
		$attenCond['ExamAttendance.attendance_status'] = 0;
		$tTCond['Timetable.indicator'] = 0;
		$stuCond['Student.discontinued_status'] = 0;
		//$stuCond['Student.registration_number'] = array(3625901, 3645001);
		
		if($batchId != '-'){
			$stuCond['Student.batch_id'] = $batchId;
		}
		if($programId != '-'){
			$stuCond['Student.program_id'] = $programId;
		}		
		
		//$stuCond['Student.registration_number'] = '3625901';
		
		$res = array(
				'conditions' => array($stuCond,
				),
				'fields' =>array('Student.id','Student.registration_number','Student.name','Student.birth_date'),
				'contain'=>array(
						'Batch' => array(
								'fields' => array('Batch.batch_from', 'Batch.batch_to', 'Batch.academic')
						),
						'Program' => array('fields' => array('Program.id','Program.program_name','Program.short_code','Program.credits'),
								'Academic' => array('fields' => array('Academic.academic_name'))
						),						
						'StudentMark' => array(
								'fields' => array('StudentMark.id','StudentMark.month_year_id', 'StudentMark.marks', 
										'StudentMark.student_id', 'StudentMark.course_mapping_id','StudentMark.status',
										'StudentMark.revaluation_status','StudentMark.final_marks','StudentMark.final_status',
										'StudentMark.grade_point','StudentMark.grade'),
								'conditions' => array(),
								'CourseMapping'=>array('fields'=>array('CourseMapping.id','CourseMapping.semester_id','CourseMapping.month_year_id'),
									'conditions'=>array('CourseMapping.indicator'=>0,
									),
									'Course' => array(
										'fields' => array('Course.id','Course.course_code','Course.course_name','Course.course_max_marks','Course.course_max_marks','Course.credit_point'),
										'CourseType' => array('fields' => array('CourseType.course_type'))
									),'order'=>'CourseMapping.semester_id ASC',
								),'order'=>'StudentMark.month_year_id ASC',
						),
						'RevaluationExam' =>array(
								'fields' => array('RevaluationExam.id','RevaluationExam.course_mapping_id','RevaluationExam.revaluation_marks','RevaluationExam.status'),
								'conditions' => array(),'order'=>'RevaluationExam.month_year_id ASC',
						)					
				),
				'order'=>array('Student.registration_number')
		);
		
		$results = $this->Student->find("all", $res);
		//pr($results);
		$this->set('results', $results);
		$sm_results = $this->StudentMark->query("select DISTINCT StudentMark.course_mapping_id FROM student_marks StudentMark 
				JOIN course_mappings CourseMapping
				ON StudentMark.course_mapping_id = CourseMapping.id 
				WHERE CourseMapping.batch_id=$batchId and CourseMapping.program_id=$programId and CourseMapping.indicator=0");
		//pr($sm_results);
		$totalCourses = count($sm_results); 
		$this->set('totalCourses', $totalCourses);
		
		$this->layout= false;
		if($printMode == 'Dept Excel'){
			$this->excelCGPA($results,$withheldType, $totalCourses);
		}		
		if($printMode == 'Excel'){
			return $results;
		}
		if($printMode == 'PRINT'){
			$this->layout= 'print';
			return false;
				
		}
		$this->layout= false;
	}	
	
	public function totalList($batchId = null,$Academic = null,$programId = null,$withheldType = null,$withheldVal = null,$printMode = null) {
	
		$this->set('batchId', $batchId);
		$this->set('Academic', $Academic);
		$this->set('programId', $programId);
		$this->set('withheldType', $withheldType);
		$this->set('withheldVal', $withheldVal);
		$this->set('printMode', $printMode);
	
		//echo " BatchId=> ".$batchId." Academic=> ".$Academic." programId=> ".$programId." With held Type=> ".$withheldType." With held Val=> ".$withheldVal." printMode=>".$printMode;exit;
		$stuCond=array(); $markCond=array();  $attenCond=array();$tTCond=array();$iExam=array();$eExam=array();$SWMYE = array();
	
		$attenCond['ExamAttendance.attendance_status'] = 0;
		$tTCond['Timetable.indicator'] = 0;
		$stuCond['Student.discontinued_status'] = 0;
		//$stuCond['Student.registration_number'] = array(3531016);
	
		if($batchId != '-'){
			$stuCond['Student.batch_id'] = $batchId;
		}
		if($programId != '-'){
			$stuCond['Student.program_id'] = $programId;
		}
	
		//$stuCond['Student.registration_number'] = '3527002';
	
		$my_results = $this->StudentMark->query("select max(StudentMark.month_year_id) as mx_my_id FROM student_marks StudentMark
				JOIN course_mappings CourseMapping
				ON StudentMark.course_mapping_id = CourseMapping.id
				WHERE CourseMapping.batch_id=$batchId and CourseMapping.program_id=$programId and CourseMapping.indicator=0");
		//pr($my_results);
		$max_my_id = $my_results[0][0]['mx_my_id'];
		
		$res = array(
				'conditions' => array($stuCond,
				),
				'fields' =>array('Student.id','Student.registration_number','Student.name','Student.birth_date'),
				'contain'=>array(
						'StudentAuthorizedBreak' => array(
								'fields' => array('StudentAuthorizedBreak.student_id')
						),
						'StudentWithdrawal' => array(
								'fields' => array('StudentWithdrawal.student_id')
						),
						'Batch' => array(
								'fields' => array('Batch.batch_from', 'Batch.batch_to', 'Batch.academic')
						),
						'Program' => array('fields' => array('Program.id','Program.program_name','Program.short_code','Program.credits'),
								'Academic' => array('fields' => array('Academic.academic_name'))
						),
						'StudentMark' => array(
								'fields' => array('StudentMark.id','StudentMark.month_year_id', 'StudentMark.marks', 'StudentMark.student_id', 'StudentMark.course_mapping_id','StudentMark.status','StudentMark.revaluation_status','StudentMark.final_marks','StudentMark.final_status','StudentMark.grade_point','StudentMark.grade'),
								'conditions' => array(),
								'CourseMapping'=>array('fields'=>array('CourseMapping.id','CourseMapping.semester_id',
										'CourseMapping.month_year_id'),
										'conditions'=>array('CourseMapping.indicator'=>0, 'CourseMapping.month_year_id <='=>$max_my_id
										),
										'Course' => array(
												'fields' => array('Course.id','Course.course_code','Course.course_name','Course.course_max_marks','Course.course_max_marks','Course.credit_point'),
												'CourseType' => array('fields' => array('CourseType.course_type'))
										),'order'=>'CourseMapping.semester_id ASC',
								),'order'=>'StudentMark.month_year_id ASC',
						),
						'RevaluationExam' =>array(
								'fields' => array('RevaluationExam.id','RevaluationExam.course_mapping_id','RevaluationExam.revaluation_marks','RevaluationExam.status'),
								'conditions' => array(),'order'=>'RevaluationExam.month_year_id ASC',
						),
						'CourseStudentMapping'=>array(
								'fields'=>array('CourseStudentMapping.id', 'CourseStudentMapping.course_mapping_id',
										'CourseStudentMapping.new_semester_id'
								),
								'conditions'=>array('CourseStudentMapping.indicator'=>0)
						),
				),
				'order'=>array('Student.registration_number')
		);
	
		$results = $this->Student->find("all", $res);
		//echo count($results[0]['StudentMark']);
		//pr($results);
		//die;
		$this->set('results', $results);

		$csmCond = array();
		if($batchId != '-'){
			$csmCond['Student.batch_id'] = $batchId;
		}
		if($programId != '-'){
			$csmCond['Student.program_id'] = $programId;
		}
		//$stuCond['Student.month_year_id <='] = $examMonth;
		
		$res1 = array(
				'conditions' => array($csmCond,
				),
				'fields' =>array('Student.id','Student.parent_id','Student.university_references_id'),
				'contain'=>array(
						'CourseStudentMapping'=>array(
								'fields'=>array('CourseStudentMapping.course_mapping_id',
										'CourseStudentMapping.indicator',
								),
						),
						'ParentGroup' => array(
								'CourseStudentMapping'=>array(
										'fields'=>array('CourseStudentMapping.course_mapping_id',
												'CourseStudentMapping.indicator',
										),
								),
						)
				));
		$csmResult = $this->Student->find('all', $res1);
		//pr($csmResult);
		$csmArray = array();
		foreach ($csmResult as $key => $csm) {
			$innerArray = $csm['CourseStudentMapping'];
			$student_id = $csm['Student']['id'];
			$csmArray=$this->cm($innerArray, $csmArray, $student_id);
			if(isset($csm['Student']['parent_id'])) {
				$parent_id = $csm['Student']['parent_id'];
				$pInnerArray = $csm['ParentGroup']['CourseStudentMapping'];
				$csmArray=$this->cm($pInnerArray, $csmArray, $student_id);
			}
				
		}
		//pr($csmArray);
		$this->set('csmArray', $csmArray);
		
		$sm_results = $this->StudentMark->query("select DISTINCT StudentMark.course_mapping_id FROM student_marks StudentMark
				JOIN course_mappings CourseMapping
				ON StudentMark.course_mapping_id = CourseMapping.id
				WHERE CourseMapping.batch_id=$batchId and CourseMapping.program_id=$programId and CourseMapping.indicator=0");
		//pr($sm_results);
		$totalCourses = count($sm_results);
		$this->set('totalCourses', $totalCourses);

		$finalArray = $this->processTotalReport($results, $csmArray);
		//pr($finalArray);
		$this->set('finalArray', $finalArray);
		$this->layout= false;
		if($printMode == 'Dept Excel'){
			$this->totalReport($finalArray);
		}
		if($printMode == 'Excel'){
			return $results;
		}
		if($printMode == 'PRINT'){
			$this->layout= 'print';
			return false;
	
		}
		$this->layout= false;
	}
	
	public function consolidatedMarkView(){
		$academics = $this->Program->Academic->find('list',array('order' => array('Academic.academic_name ASC')));
		$programs = $this->Program->find('list',array('order' => array('Program.program_name ASC')));
		$batches = $this->Batch->find('list', array('fields' => array('Batch.batch_period'),'order' => array('Batch.batch_to DESC')));
		$monthyears = $this->MonthYear->getAllMonthYears();
		$this->set(compact('academics','programs','batches','monthyears'));
	}
	
	public function sort_month_year_id($a, $b){
		return array_search(trim($a['month_year_id']), $this->myid) - array_search(trim($b['month_year_id']), $this->myid);
	}
	
	public function group_assoc($array, $key) {
		$return = array();
		foreach($array as $v) {
			$return[$v[$key]][] = $v;
		}
		return $return;
	}
	
	public function consolidatedMarkViewList($batchId = null,$Academic = null,$programId = null,$register_no = null,$month_year_id = null,$printMode = null) {
		$this->set('batchId', $batchId);
		$this->set('Academic', $Academic);
		$this->set('programId', $programId);		
		$this->set('printMode', $printMode);
		$this->set('month_year_id', $month_year_id);
		
		//echo " examMonth=> ".$examMonth." BatchId=> ".$batchId." Academic=> ".$Academic." programId=> ".$programId." With held Type=> ".$withheldType." With held Val=> ".$withheldVal." month_year_id =>".$month_year_id." printMode=>".$printMode;exit;
		$stuCond=array(); $markCond=array();  $attenCond=array();$tTCond=array();$iExam=array();$eExam=array();$SWMYE = array();
		$internalPractical = array();$mYPractical = array();$mYIE = array();$mYESE = array();$mYSM = array();$mYRE= array();
		$internalProject=array();
		$myProject=array();
		$internalPt = array();
		
		$attenCond['ExamAttendance.attendance_status'] = 0;
		$tTCond['Timetable.indicator'] = 0;
		$stuCond['Student.discontinued_status'] = 0;
		//$stuCond['Student.id'] = array(107,108,615);
		//$stuCond['Student.id'] = array(562);
		if(($register_no) && ($register_no !='-')){
			$stuCond['Student.registration_number'] = $register_no;
		}
		if($batchId != '-'){
			$stuCond['Student.batch_id'] = $batchId;
		}
		if($programId != '-'){
			$stuCond['Student.program_id'] = $programId;
		}		
		if($month_year_id != '-'){
			//$internalPractical['InternalPractical.month_year_id'] 	= $month_year_id;
			$mYPractical['Practical.month_year_id'] 				= $month_year_id;
			//$mYIE['InternalExam.month_year_id'] 					= $month_year_id;
			$mYESE['EndSemesterExam.month_year_id'] 				= $month_year_id;
			$mYSM['StudentMark.month_year_id'] 						= $month_year_id;
			$mYRE['RevaluationExam.month_year_id'] 		            = $month_year_id;
			//$internalProject['InternalProject.month_year_id'] 		= $month_year_id;
			$mYProject['ProjectViva.month_year_id'] 				= $month_year_id;
			$internalPt['ProfessionalTraining.month_year_id'] 		= $month_year_id;
		}
		$res = array(
				'conditions' => array($stuCond,
				),
				'fields' =>array('Student.id','Student.registration_number','Student.name','Student.birth_date','Student.parent_id','Student.university_references_id'),
				'contain'=>array(
						'Batch' => array(
								'fields' => array('Batch.batch_from', 'Batch.batch_to', 'Batch.academic')
						),
						'Program' => array('fields' => array('Program.program_name','Program.short_code'),
								'Academic' => array('fields' => array('Academic.academic_name','Academic.short_code'))
						),
						'ExamAttendance'=>array('fields'=>array('ExamAttendance.id','ExamAttendance.attendance_status'),
								'Timetable'=>array('fields' =>array('Timetable.id', 'Timetable.course_mapping_id'),'conditions' => $tTCond,
								),'conditions'=>$attenCond
						),						
						'InternalPractical' => array(
								'fields' => array('InternalPractical.month_year_id', 'InternalPractical.marks', 'InternalPractical.student_id'),
								'conditions' => array(),
								'CaePractical' => array(
										'fields' => array('CaePractical.course_mapping_id')
								),'conditions'=>$internalPractical
						),
						'Practical' => array(
								'fields' => array('Practical.month_year_id', 'Practical.marks', 'Practical.student_id'),
								'conditions' => array(),
								'EsePractical' => array(
										'fields' => array('EsePractical.course_mapping_id')
								),'conditions'=>$mYPractical
						),
						'InternalExam' => array(
								'fields' => array('InternalExam.month_year_id', 'InternalExam.marks', 'InternalExam.student_id', 
								'InternalExam.course_mapping_id'),
								'conditions'=>$mYIE
						),
						'EndSemesterExam' => array(
								'fields' => array('EndSemesterExam.month_year_id', 'EndSemesterExam.marks', 'EndSemesterExam.student_id', 'EndSemesterExam.course_mapping_id', 'EndSemesterExam.dummy_number','EndSemesterExam.revaluation_status'),
								'conditions'=>$mYESE
						),
						'InternalProject' => array(
										'fields' => array('InternalProject.month_year_id', 'InternalProject.marks', 
										'InternalProject.student_id', 'InternalProject.course_mapping_id'),
										'conditions' => $internalProject,
								),
						'ProjectViva' => array(
								'fields' => array('ProjectViva.month_year_id', 'ProjectViva.marks', 'ProjectViva.student_id'),
								'conditions' => $myProject,
								'EseProject' => array(
										'fields' => array('EseProject.course_mapping_id')
								)
						),
						'ProfessionalTraining' => array(
								'fields' => array('ProfessionalTraining.month_year_id', 'ProfessionalTraining.marks', 
								'ProfessionalTraining.student_id'),
								'conditions' => array(),
								'CaePt' => array(
										'fields' => array('CaePt.course_mapping_id')
								),'conditions'=>$internalPt
						),
						'StudentMark' => array(
								'fields' => array('StudentMark.id','StudentMark.month_year_id', 'StudentMark.marks', 'StudentMark.student_id', 'StudentMark.course_mapping_id','StudentMark.status','StudentMark.revaluation_status','StudentMark.final_marks','StudentMark.final_status'),
								'conditions' => $mYSM,
								'CourseMapping'=>array('fields'=>array('CourseMapping.id','CourseMapping.semester_id'),
										'conditions'=>array('CourseMapping.indicator'=>0,
										),
										'Course' => array(
												'fields' => array('Course.id','Course.course_code','Course.course_name','Course.course_max_marks'),
												'CourseType' => array('fields' => array('CourseType.course_type'))
										),'order'=>'CourseMapping.semester_id ASC',
								),'order'=>'StudentMark.month_year_id ASC',
								'MonthYear'=>array('fields' =>array('MonthYear.year'),
										'Month'=>array('fields' =>array('Month.month_name'))
								),
						),
						'RevaluationExam' =>array(
								'fields' => array('RevaluationExam.id','RevaluationExam.course_mapping_id',
										'RevaluationExam.revaluation_marks','RevaluationExam.status'),
								'conditions' => $mYRE,'order'=>'RevaluationExam.month_year_id ASC',
								'MonthYear'=>array('fields' =>array('MonthYear.year'),
										'Month'=>array('fields' =>array('Month.month_name'))
								),
						),
						'ParentGroup' => array(
							'fields' => array('ParentGroup.batch_id', 'ParentGroup.academic_id', 'ParentGroup.program_id'),
								'Batch' => array(
										'fields' => array('Batch.batch_from', 'Batch.batch_to', 'Batch.academic')
								),
								'Program' => array('fields' => array('Program.program_name','Program.short_code'),
										'Academic' => array('fields' => array('Academic.academic_name','Academic.short_code'))
								),
								'ExamAttendance'=>array('fields'=>array('ExamAttendance.id','ExamAttendance.attendance_status'),
										'Timetable'=>array('fields' =>array('Timetable.id', 'Timetable.course_mapping_id'),'conditions' => $tTCond,
										),'conditions'=>$attenCond
								),
								'InternalPractical' => array(
										'fields' => array('InternalPractical.month_year_id', 'InternalPractical.marks', 'InternalPractical.student_id'),
										'conditions' => array(),
										'CaePractical' => array(
												'fields' => array('CaePractical.course_mapping_id')
										),'conditions'=>$internalPractical
								),
								'Practical' => array(
										'fields' => array('Practical.month_year_id', 'Practical.marks', 'Practical.student_id'),
										'conditions' => array(),
										'EsePractical' => array(
												'fields' => array('EsePractical.course_mapping_id')
										),'conditions'=>$mYPractical
								),
								'InternalExam' => array(
										'fields' => array('InternalExam.month_year_id', 'InternalExam.marks', 'InternalExam.student_id', 'InternalExam.course_mapping_id'),
										'conditions'=>$mYIE
								),
								'EndSemesterExam' => array(
										'fields' => array('EndSemesterExam.month_year_id', 'EndSemesterExam.marks', 'EndSemesterExam.student_id', 'EndSemesterExam.course_mapping_id', 'EndSemesterExam.dummy_number','EndSemesterExam.revaluation_status'),
										'conditions'=>$mYESE
								),
								'InternalProject' => array(
										'fields' => array('InternalProject.month_year_id', 'InternalProject.marks', 'InternalProject.student_id'),
										'conditions' => $internalProject,
								),
								'ProjectViva' => array(
										'fields' => array('ProjectViva.month_year_id', 'ProjectViva.marks', 'ProjectViva.student_id'),
										'conditions' => $myProject,
										'EseProject' => array(
												'fields' => array('EseProject.course_mapping_id')
										)
								),
								'ProfessionalTraining' => array(
										'fields' => array('ProfessionalTraining.month_year_id', 'ProfessionalTraining.marks',
												'ProfessionalTraining.student_id'),
										'conditions' => array(),
										'CaePt' => array(
												'fields' => array('CaePt.course_mapping_id')
										),'conditions'=>$internalPt
								),
								'StudentMark' => array(
										'fields' => array('StudentMark.id','StudentMark.month_year_id', 'StudentMark.marks', 'StudentMark.student_id', 'StudentMark.course_mapping_id','StudentMark.status','StudentMark.revaluation_status','StudentMark.final_marks','StudentMark.final_status'),
										'conditions' => $mYSM,
										'CourseMapping'=>array('fields'=>array('CourseMapping.id','CourseMapping.semester_id'),
												'conditions'=>array('CourseMapping.indicator'=>0,
												),
												'Course' => array(
														'fields' => array('Course.id','Course.course_code','Course.course_name','Course.course_max_marks'),
														'CourseType' => array('fields' => array('CourseType.course_type'))
												),'order'=>'CourseMapping.semester_id ASC',
										),'order'=>'StudentMark.month_year_id ASC',
										'MonthYear'=>array('fields' =>array('MonthYear.year'),
												'Month'=>array('fields' =>array('Month.month_name'))
										),
								),
								'RevaluationExam' =>array(
										'fields' => array('RevaluationExam.id','RevaluationExam.course_mapping_id','RevaluationExam.revaluation_marks','RevaluationExam.status'),
										'conditions' => $mYRE,'order'=>'RevaluationExam.month_year_id ASC',
										'MonthYear'=>array('fields' =>array('MonthYear.year'),
												'Month'=>array('fields' =>array('Month.month_name'))
										),
								)
						)
				),
		);
		if(empty($register_no)){
			$register_no = "-";
		}
		$this->set('register_no', $register_no);
		$results = $this->Student->find("all", $res);
		//pr($results);
		$this->set('results', $results);
		$this->layout= false;
		
		$processedResult = $this->processConsolidatedData($results);
		
		if($printMode == 'Dept Excel') {
			//pr($processedResult);
			$this->excelConsolidatedMarkView($processedResult);
		}		
		if($printMode == 'PRINT') {
			$this->layout= 'print';
			return false;				
		}
		if($printMode == 'PDF1' || $printMode == 'PDF2') {
			$this->pdfConsolidatedMarkView($processedResult, $printMode);
		}
	}
	
	public function processConsolidatedData($results) {
		$a=1;  
		$finalArray = array();
		
		if($results){ 
			foreach($results as $result){ 
				//pr($result); 
				$studentId = $result['Student']['id'];
				$finalArray[$studentId] = array();
				$courseMark = ""; $cntFail = "0"; $cntAttendance = "0";
				$cntAttendance = count($result['ExamAttendance']);
				
				$stuInternalArray = array();
				$stuESArray = array();
				$stuFinalMark = array();
				$stuMarkStatus = array();
				$examMonthYear = array();
				
				//For Theory Revaluation
				for($p=0;$p<count($result['RevaluationExam']);$p++){
					$examMonthYear[$result['RevaluationExam'][$p]['month_year_id']][$result['RevaluationExam'][$p]['course_mapping_id']] = $result['RevaluationExam'][$p]['MonthYear']['Month']['month_name']."-".$result['RevaluationExam'][$p]['MonthYear']['year'];
					$stuESArray[$result['RevaluationExam'][$p]['month_year_id']][$result['RevaluationExam'][$p]['course_mapping_id']] = $result['RevaluationExam'][$p]['revaluation_marks'];
				}
				if($result['Student']['university_references_id'] == 1 && $result['Student']['parent_id'] != 0){
					for($p=0;$p<count($result['ParentGroup']['RevaluationExam']);$p++){
						$examMonthYear[$result['ParentGroup']['RevaluationExam'][$p]['month_year_id']][$result['ParentGroup']['RevaluationExam'][$p]['course_mapping_id']] = $result['ParentGroup']['RevaluationExam'][$p]['MonthYear']['Month']['month_name']."-".$result['ParentGroup']['RevaluationExam'][$p]['MonthYear']['year'];
						$stuESArray[$examMonthYear[$result['ParentGroup']['RevaluationExam'][$p]['month_year_id']]][$result['ParentGroup']['RevaluationExam'][$p]['course_mapping_id']] = $result['ParentGroup']['RevaluationExam'][$p]['revaluation_marks'];
					}
				}
				
				//For Theory Internal
				for($p=0;$p<count($result['InternalExam']);$p++){
					$stuInternalArray[$result['InternalExam'][$p]['month_year_id']][$result['InternalExam'][$p]['course_mapping_id']] = $result['InternalExam'][$p]['marks'];
				}
				if($result['Student']['university_references_id'] == 1 && $result['Student']['parent_id'] != 0 && isset($result['ParentGroup']['InternalExam'][$p]['marks'])){
					$stuInternalArray[$result['ParentGroup']['InternalExam'][$p]['month_year_id']][$result['ParentGroup']['InternalExam'][$p]['course_mapping_id']] = $result['ParentGroup']['InternalExam'][$p]['marks'];
				}
				
				//For Theory External
				for($p=0;$p<count($result['EndSemesterExam']);$p++){
					if($result['EndSemesterExam'][$p]['revaluation_status'] == 0){
						$stuESArray[$result['EndSemesterExam'][$p]['month_year_id']][$result['EndSemesterExam'][$p]['course_mapping_id']] = $result['EndSemesterExam'][$p]['marks'];
					}else{
						//echo $result['EndSemesterExam'][$p]['course_mapping_id']." ".$result['EndSemesterExam'][$p]['marks']." ";
						$revalArray = $result['RevaluationExam'];
						foreach ($revalArray as $key => $revalValue) {
							if ($revalValue['course_mapping_id'] == $result['EndSemesterExam'][$p]['course_mapping_id']) {
								//echo $revalValue['revaluation_marks']."</br>";
								break;
							}
						}
						/*if(isset($stuESArray[$result['EndSemesterExam'][$p]['course_mapping_id']]) && $stuESArray[$result['EndSemesterExam'][$p]['course_mapping_id']] < $result['EndSemesterExam'][$p]['marks']){
						 $stuESArray[$result['EndSemesterExam'][$p]['month_year_id']][$result['EndSemesterExam'][$p]['course_mapping_id']] = $result['EndSemesterExam'][$p]['marks'];
						 }*/
						if ($revalValue['revaluation_marks'] > $result['EndSemesterExam'][$p]['marks']) {
							$stuESArray[$result['EndSemesterExam'][$p]['month_year_id']][$result['EndSemesterExam'][$p]['course_mapping_id']]=$revalValue['revaluation_marks'];
						} else {
							$stuESArray[$result['EndSemesterExam'][$p]['month_year_id']][$result['EndSemesterExam'][$p]['course_mapping_id']]=$result['EndSemesterExam'][$p]['marks'];
						}
						//pr($result);
					}
				}
				
				if($result['Student']['university_references_id'] == 1 && $result['Student']['parent_id'] != 0){
					for($p=0;$p<count($result['ParentGroup']['EndSemesterExam']);$p++){
						if($result['ParentGroup']['EndSemesterExam'][$p]['revaluation_status'] == 0){
							$stuESArray[$result['ParentGroup']['EndSemesterExam'][$p]['month_year_id']][$result['ParentGroup']['EndSemesterExam'][$p]['course_mapping_id']] = $result['ParentGroup']['EndSemesterExam'][$p]['marks'];
						}else{
							if($stuESArray[$result['ParentGroup']['EndSemesterExam'][$p]['course_mapping_id']] < $result['ParentGroup']['EndSemesterExam'][$p]['marks']){
								$stuESArray[$result['ParentGroup']['EndSemesterExam'][$p]['month_year_id']][$result['ParentGroup']['EndSemesterExam'][$p]['course_mapping_id']] = $result['ParentGroup']['EndSemesterExam'][$p]['marks'];
							}
						}
					}
				}
				
				
				//For Practical	Internal
				for($q=0;$q<count($result['InternalPractical']);$q++){
					$stuInternalArray[$result['InternalPractical'][$q]['month_year_id']][$result['InternalPractical'][$q]['CaePractical']['course_mapping_id']] = $result['InternalPractical'][$q]['marks'];
				}
				if($result['Student']['university_references_id'] == 1 && $result['Student']['parent_id'] != 0){
					for($q=0;$q<count($result['ParentGroup']['InternalPractical']);$q++){
						$stuInternalArray[$result['ParentGroup']['InternalPractical'][$q]['month_year_id']][$result['ParentGroup']['InternalPractical'][$q]['CaePractical']['course_mapping_id']] = $result['ParentGroup']['InternalPractical'][$q]['marks'];
					}
				}
				
				//For Practical	External
				for($q=0;$q<count($result['Practical']);$q++){
					$practicalExternalMarks = $result['Practical'][$q]['marks'];
					if($practicalExternalMarks == '0'){$practicalExternalMarks = "&nbsp;0";}
					$stuESArray[$result['Practical'][$q]['month_year_id']][$result['Practical'][$q]['EsePractical']['course_mapping_id']] = $practicalExternalMarks;
					$practicalExternalMarks = "";
				}
				
				//For Project Internal
				for($p=0;$p<count($result['InternalProject']);$p++){			
					$stuInternalArray[$result['InternalProject'][$p]['month_year_id']][$result['InternalProject'][$p]['course_mapping_id']] = $result['InternalProject'][$p]['marks'];			
				}
				if($result['Student']['university_references_id'] == 1 && $result['Student']['parent_id'] != 0 && isset($result['ParentGroup']['InternalProject'][$p]['marks'])){
					$stuInternalArray[$result['ParentGroup']['InternalProject'][$p]['month_year_id']][$result['ParentGroup']['InternalProject'][$p]['course_mapping_id']] = $result['ParentGroup']['InternalProject'][$p]['marks'];
				}
				
				//For Project External
				for($q=0;$q<count($result['ProjectViva']);$q++){
					$projectExternalMarks = $result['ProjectViva'][$q]['marks'];
					if($projectExternalMarks == '0'){$projectExternalMarks = "&nbsp;0";}
					$stuESArray[$result['ProjectViva'][$q]['month_year_id']][$result['ProjectViva'][$q]['EseProject']['course_mapping_id']] = $projectExternalMarks;
					$practicalExternalMarks = "";
				}
				//pr($stuInternalArray);
				if($result['Student']['university_references_id'] == 1 && $result['Student']['parent_id'] != 0 && isset($result['ParentGroup']['Practical'][$q]['marks'])){
					$projectExternalMarks = $result['ParentGroup']['Practical'][$q]['marks'];
					if($projectExternalMarks == '0'){$projectExternalMarks = "&nbsp;0";}
					$stuESArray[$result['ParentGroup']['ProjectViva'][$q]['month_year_id']][$result['ParentGroup']['ProjectViva'][$q]['EseProject']['course_mapping_id']] = $practicalExternalMarks;
					$projectExternalMarks = "";
				}
				
				//For Professional Training Internal
				for($q=0;$q<count($result['ProfessionalTraining']);$q++){
					$stuInternalArray[$result['ProfessionalTraining'][$q]['month_year_id']][$result['ProfessionalTraining'][$q]['CaePt']['course_mapping_id']] = $result['ProfessionalTraining'][$q]['marks'];
					//For Professional Training External (No external for PT)
					$stuESArray[$result['ProfessionalTraining'][$q]['month_year_id']][$result['ProfessionalTraining'][$q]['CaePt']['course_mapping_id']] = "-";
				}
				if($result['Student']['university_references_id'] == 1 && $result['Student']['parent_id'] != 0){
					for($q=0;$q<count($result['ParentGroup']['ProfessionalTraining']);$q++){
						$stuInternalArray[$result['ParentGroup']['ProfessionalTraining'][$q]['month_year_id']][$result['ParentGroup']['ProfessionalTraining'][$q]['CaePt']['course_mapping_id']] = $result['ParentGroup']['ProfessionalTraining'][$q]['marks'];
						//For Professional Training External (No external for PT)
						$stuESArray[$result['ParentGroup']['ProfessionalTraining'][$q]['month_year_id']][$result['ParentGroup']['ProfessionalTraining'][$q]['CaePt']['course_mapping_id']] = "-";
					}
				}
				
				
				
				if($result['Student']['university_references_id'] == 1 && $result['Student']['parent_id'] != 0 && count($result['ParentGroup']['StudentMark'])>0){
					$result['StudentMark'] = $result['ParentGroup']['StudentMark'];
				}
				//pr($stuESArray);
				
				$monthYear = array();
				//pr($stuInternalArray);
				for($p=0;$p<count($result['StudentMark']);$p++)	{
					$examMonthYearId = $result['StudentMark'][$p]['month_year_id'];
					$monthYear[$examMonthYearId] = $examMonthYearId;
				}
				//echo max($monthYear);
				
				//pr($monthYear);
				$tmpArray = array();
				for($p=0;$p<count($result['StudentMark']);$p++){
						
					//pr($result['StudentMark']);
						
					$IEV = ""; $ESV = "";
					$courseMId = $result['StudentMark'][$p]['course_mapping_id'];
					//echo "*** ".$courseMId;
					$examMonthYearId = $result['StudentMark'][$p]['month_year_id'];
						
						
					if($result['StudentMark'][$p]['revaluation_status'] == 0){
						$stuFinalMark[$examMonthYearId][$courseMId] = $result['StudentMark'][$p]['marks'];
						$stuMarkStatus[$examMonthYearId][$courseMId] = $result['StudentMark'][$p]['status'];
						$examMonthYear[$examMonthYearId][$courseMId] = $result['StudentMark'][$p]['MonthYear']['Month']['month_name']."-".$result['StudentMark'][$p]['MonthYear']['year'];
					}else{
						$stuFinalMark[$examMonthYearId][$courseMId] = $result['StudentMark'][$p]['final_marks'];
						$stuMarkStatus[$examMonthYearId][$courseMId] = $result['StudentMark'][$p]['final_status'];
					}
						
					if($stuMarkStatus[$examMonthYearId][$courseMId] == 'Fail'){
						$cntFail = $cntFail + 1;
					}
						
					if(isset($stuInternalArray[$examMonthYearId][$courseMId])){
						$IEV = $stuInternalArray[$examMonthYearId][$courseMId];
					}
					if(isset($stuESArray[$examMonthYearId][$courseMId])){
						$ESV = $stuESArray[$examMonthYearId][$courseMId];
					}
					//echo " - ".$IEV." - ".$ESV." - ".$stuFinalMark[$examMonthYearId][$courseMId];
					if ($IEV == 'A' || $IEV == '' || is_null($IEV)) {
						//echo " if ";
						$maxMonthYear = max($monthYear);
						for($mx=$maxMonthYear; $mx>0; $mx--) {
							//echo "</br>".$mx."</br>";
							if (isset($stuInternalArray[$mx][$courseMId]) && $stuInternalArray[$mx][$courseMId] >= 0) {
								$IEV = $stuInternalArray[$mx][$courseMId];
								break;
							}
						}
					}
					//echo "</br>";
					//
					$array = array();
					$tmpArray[$p]['reg_num']=$result['Student']['registration_number'];
					$tmpArray[$p]['name']=$result['Student']['name'];
					$tmpArray[$p]['cm_id'] = $courseMId;
					$tmpArray[$p]['month_year_id'] = $examMonthYearId;
					$academic = "";
					if ($result['Batch']['academic'] == "JUN") $academic = "A";
					$tmpArray[$p]['batch'] = $result['Batch']['batch_from']."-".$result['Batch']['batch_to']." ".$academic;
					$tmpArray[$p]['academic'] = $result['Program']['Academic']['short_code'];
					$tmpArray[$p]['program'] = $result['Program']['short_code'];
					$tmpArray[$p]['course_code']=$result['StudentMark'][$p]['CourseMapping']['Course']['course_code'];
					$tmpArray[$p]['course_name']=$result['StudentMark'][$p]['CourseMapping']['Course']['course_name'];
					$tmpArray[$p]['semester_id']=$result['StudentMark'][$p]['CourseMapping']['semester_id'];
					$tmpArray[$p]['month_year']=$examMonthYear[$examMonthYearId][$courseMId];
					if($IEV){
						$tmpArray[$p]['cae']=$IEV;
					}else{
						$tmpArray[$p]['cae']="A";
					}
					if($ESV){
						$tmpArray[$p]['ese']=$ESV;
					}else{
						$tmpArray[$p]['ese']="A";
					}
					if($stuFinalMark[$examMonthYearId][$courseMId]){
						$tmpArray[$p]['final_mark']=$stuFinalMark[$examMonthYearId][$courseMId];
					}else{
						$tmpArray[$p]['final_mark']="A";
					}
					if($stuMarkStatus[$examMonthYearId][$courseMId] == 'Fail'){
						$tmpArray[$p]['final_status']="RA";
					}else {
						$tmpArray[$p]['final_status']=$stuMarkStatus[$examMonthYearId][$courseMId];
					}
				}
				//array_push($finalArray[$studentId], $tmpArray);
				$finalArray[$studentId] = $tmpArray;
			}
			//pr($finalArray);
		}
		return $finalArray;
	}
	
	public function transferCourses($reg_number=NULL) {
		//pr($reg_number);
		$results = $this->retrieveStudentInfo($reg_number);
		//pr($results);
		//die;
		$student_id = $results[0]['Student']['id'];
		
		$student_type_id = $results[0]['Student']['student_type_id'];
		//echo $student_id." ".$student_type_id;
		$this->set(compact('reg_number', 'results'));
		if (isset($results[0]['Student']['parent_id']) && ($results[0]['Student']['parent_id']) > 0) {
			//echo "same university";
			foreach ($results as $key => $result) {
				//pr($result);
				$new_batch_id = $result['Student']['batch_id'];
				$new_program_id = $result['Student']['program_id'];
				$new_academic_id = $result['Student']['academic_id'];
				$new_month_year_id = $result['Student']['month_year_id'];
	
				$parent_id = $result['Student']['parent_id'];
	
				$student_type_id = $result['Student']['student_type_id'];
				$university_references_id = $result['Student']['university_references_id'];
	
				$old_batch_id = $result['ParentGroup']['batch_id'];
				$old_program_id = $result['ParentGroup']['program_id'];
				$old_academic_id = $result['ParentGroup']['academic_id'];
					
				$oldCourseStudentMapping = $result['ParentGroup']['CourseStudentMapping'];
				//pr($oldCourseStudentMapping);
	
			}
		
			$myResult = $this->MonthYear->find("all", array(
					'fields'=>array('MonthYear.year', 'MonthYear.id'),
					'contain'=>array(
							'Month'=>array(
									'fields'=>array('Month.month_name')
							)
					)
			));
			$month_years = array();
			foreach ($myResult as $key => $value) {
				$month_years[$value['MonthYear']['id']] = $value['Month']['month_name']." ".$value['MonthYear']['year'];
			}
			//pr($month_years);
				
			foreach ($oldCourseStudentMapping as $key => $oldCSMArray) {
				if ($oldCSMArray['type'] == "UCP") {
					$ucp[$oldCSMArray['course_mapping_id']] = $oldCSMArray['CourseMapping']['Course']['course_code'];
				}
				else if ($oldCSMArray['type'] == "UCF") {
					$ucf[$oldCSMArray['course_mapping_id']] = $oldCSMArray['CourseMapping']['Course']['course_code'];
				}
				else if (isset($oldCSMArray['type']) && $oldCSMArray['type']=="UCPEQ") {
					$ucpeq[$oldCSMArray['course_mapping_id']] = $oldCSMArray['eq_course_mapping_id'];
				}
				else if (isset($oldCSMArray['type']) && $oldCSMArray['type']=="UCFEQ") {
					$ucfeq[$oldCSMArray['course_mapping_id']] = $oldCSMArray['eq_course_mapping_id'];
				}
			}
			//	pr($ucpeq);
			//	pr($ucfeq);
			/*pr($eq); */
			$this->set(compact('ucp', 'ucf', 'ucpeq', 'ucfeq'));
				
			$transferredFromCMArray = array();
			$transferredFromCMListArray = array();
			$oldCourseMappingFailArray = array();
			foreach ($oldCourseStudentMapping as $key => $value) {
				//pr($value);
				$semester_id = $value['CourseMapping']['semester_id'];
				
				if ($semester_id < $new_month_year_id) {
				$cm_id = $value['CourseMapping']['id'];
				$stu_mark = $this->StudentMark->find('first', array(
						'conditions'=>array('StudentMark.course_mapping_id'=>$cm_id, 'StudentMark.student_id'=>$parent_id),
						'fields'=>array('StudentMark.status', 'StudentMark.revaluation_status', 'StudentMark.final_status')
				));
				//pr($stu_mark);
			/* 	$dbo = $this->CourseMapping->getDatasource();
				$logs = $dbo->getLog();
				$lastLog = end($logs['log']);
				echo $lastLog['query']; */
				//echo $cm_id." ** ";
				if (isset($stu_mark['StudentMark'])) {
					if ($stu_mark['StudentMark']['revaluation_status'] == 1) $status = $stu_mark['StudentMark']['final_status'];
					else $status = $stu_mark['StudentMark']['status'];
						$course_code = $value['CourseMapping']['Course']['course_code'];
						$tmp = array(
								'cm_id' => $cm_id,
								'course_code'=>$course_code,
								'status'=>$status,
								'eq_cm_id'=>$value['eq_course_mapping_id'],
								'type' => $value['type']
						);
						$transferredFromCMArray[$semester_id][] = $tmp;
						$transferredFromCMListArray[$semester_id][$cm_id] = $course_code;
				}
				}
			}
			//pr($transferredFromCMListArray);
			//echo $new_semester_id;
			//echo "new_my_id:".$new_month_year_id;
			//echo "semester_id : ".$semester_id;
			$semester_to_process_data = $semester_id-1;
			//echo "semester_to_process_data".$semester_to_process_data;
			//pr($transferredFromCMArray);
			//pr($oldCourseMappingFailArray);
			
			$new_course_mapping_id = array();
			$transferredToCourseMapping = $this->CourseMapping->find('all', array(
					'conditions' => array('CourseMapping.indicator' => 0, 'CourseMapping.batch_id'=>$new_batch_id,
							'CourseMapping.program_id' => $new_program_id
					),
					'fields' => array('CourseMapping.id', 'CourseMapping.semester_id'),
					'contain' => array(
							'Course' => array('fields' => array('Course.course_code'))
					),
					'order' => array('CourseMapping.semester_id'),
			));
			//pr($transferredToCourseMapping);
				
			$previous_month_years = $new_month_year_id-1;
			$tmpArray = array();
			$transferredToCMArray=array();
			$transferredToCMListArray=array();
				
			foreach ($transferredToCourseMapping as $key => $value) {
				$semester_id = $value['CourseMapping']['semester_id'];
				//$transferredToCMArray[$semester_id] = array();
				$cm_id = $value['CourseMapping']['id'];
				$course_code = $value['Course']['course_code'];
				$tmp = array(
						'cm_id' => $cm_id,
						'course_code'=>$course_code,
				);
				$transferredToCMArray[$semester_id][] = $tmp;
				$transferredToCMListArray[$semester_id][$cm_id] = $course_code;
			}
				
			//pr($transferredToCMArray);
			//pr($transferredToCMListArray); 
			//echo $semester_to_process_data;
			
			$commonPassArray = array();
			$commonFailArray = array();
			//echo "data manipulation starts here....";
			//echo $semester_to_process_data;
			
			
			//pr($transferredToCMListArray);
			//pr($transferredFromCMListArray);
			
			for($i=1; $i<=$semester_to_process_data; $i++) {
				if (isset($transferredToCMListArray[$i]) && isset($transferredFromCMListArray[$i])) { 
					$transferToUnCommonArray[$i] = array_diff($transferredToCMListArray[$i], $transferredFromCMListArray[$i]);
					foreach ($transferredFromCMArray[$i] as $key => $cmArray) {
						foreach ($transferredToCMArray[$i] as $keyTo => $cmArrayTo) {
							if ($cmArray['status'] == "Pass" && $cmArray['course_code'] == $cmArrayTo['course_code']) {
								$commonPassMappingArray[$cmArray['cm_id']]=$cmArrayTo['cm_id'];
								$commonPassArray[$i][$cmArray['cm_id']] = $cmArray['course_code'];
							}
							else if ($cmArray['status'] == "Fail" && $cmArray['course_code'] == $cmArrayTo['course_code']) {
								$commonFailMappingArray[$cmArray['cm_id']]=$cmArrayTo['cm_id'];
								//echo "common fail ".$cmArray['cm_id']." ".$cmArrayTo['cm_id'];
								$commonFailArray[$i][$cmArray['cm_id']] = $cmArray['course_code'];
							}
						}
					}
					$failCount=0;
					$passCount=0;
					if (isset($commonFailArray[$i])) { 
						$failCount = count($commonFailArray[$i]); 
						//echo $failCount; 
					}
					if (isset($commonPassArray[$i])) {
						$passCount = count($commonPassArray[$i]); 
						//echo $passCount;
					}
					if (isset($commonFailArray[$i]) && isset($commonPassArray[$i])) {
						$unCommonArray[$i]=array_diff_assoc($transferredFromCMListArray[$i],$commonPassArray[$i],$commonFailArray[$i]);
					}
					else if ($failCount==0 && count($commonPassArray[$i])>0) {
						$unCommonArray[$i]=array_diff_assoc($transferredFromCMListArray[$i],$commonPassArray[$i]);
					}
					else if (count($commonFailArray[$i])>0 && $passCount==0) {
						$unCommonArray[$i]=array_diff_assoc($transferredFromCMListArray[$i],$commonFailArray[$i]);
					}
					/* else {
						if (isset($commonPassArray[$i])) {
							$unCommonArray[$i]=array_diff_assoc($transferredFromCMListArray[$i],$commonPassArray[$i]);
						}
					} */
				}
	
				//pr($transferredFromCMListArray[$i]);
				//pr($transferredToCMListArray[$i]);
			}
			//pr($commonPassMappingArray);
			$unCommonPassArray = array();
			$unCommonFailArray = array();
			for($i=1; $i<=$semester_to_process_data; $i++) {
				if (isset($unCommonArray[$i])) {
					foreach ($unCommonArray[$i] as $un_cm_id => $un_course_code) {
						//echo "</br>".$un_cm_id;
						foreach ($transferredFromCMArray[$i] as $key => $cmArray) {
							if ($un_cm_id==$cmArray['cm_id'] && $cmArray['status']=="Fail") {
								//pr($cmArray);
								$unCommonFailArray[$i][$cmArray['cm_id']] = $cmArray['course_code'];
							}
							else if ($un_cm_id==$cmArray['cm_id'] && $cmArray['status']=="Pass") {
								$unCommonPassArray[$i][$cmArray['cm_id']] = $cmArray['course_code'];
							}
						}
					}
				}
			}
				
			$monthYears = $this->MonthYear->find('all', array('fields' => array('MonthYear.id','MonthYear.year','MonthYear.month_id','Month.month_name'),'order' => array('MonthYear.id DESC')));
			$monthyears=array();
				
			foreach ($monthYears as $key => $value) {
				$monthyears[$value['MonthYear']['id']]=$value['Month']['month_name']."-".$value['MonthYear']['year'];
			}
	
			$this->set(compact('results', 'transferredFromCMListArray', 'transferredToCMListArray', 'month_years'));
			$this->set(compact('commonPassArray', 'commonFailArray'));
			$this->set(compact('unCommonPassArray', 'unCommonFailArray'));
			$this->set(compact('commonPassMappingArray', 'commonFailMappingArray'));
			$this->set(compact('transferToUnCommonArray', 'semester_to_process_data', 'parent_id', 'monthyears', 'new_month_year_id'));
	
			if($this->request->is('post')) {
				//pr($this->request->data);
				
				//pr($commonPassArray);
				//pr($commonPassMappingArray);
				$old_student_id = $this->request->data['MapStudents']['parent_id'];
				$student_type_id = $this->request->data['MapStudents']['student_type_id'];
	
				if ($student_type_id == 3 && $results[0]['Student']['parent_id']>0) {
					//pr($this->data); 
					if (isset($this->request->data['MapStudents']['CommonPass'])) {
						$tmpArray = array();
						$tmpArray = $this->request->data['MapStudents']['CommonPass'];
							foreach ($tmpArray as $semId => $common_courses_passed) {
								//$month_year_to_transfer = $this->request->data['MapStudents']['cp_my_id'];
								$month_year_to_transfer = $this->request->data['MapStudents']['cp_my_id'][$semId];
								foreach ($common_courses_passed as $old_cm_id => $new_cm_id) {
									$csmResult = $this->CourseStudentMapping->find('first', array(
											'conditions' => array('CourseStudentMapping.course_mapping_id'=>$old_cm_id,
													'CourseStudentMapping.student_id'=>$old_student_id,
											),
											'fields' => array('CourseStudentMapping.id')
									));
									$csm_id = $csmResult['CourseStudentMapping']['id'];
									$data=array();
									$data['CourseStudentMapping']['id']=$csm_id;
									$data['CourseStudentMapping']['type']="CP";
									$this->CourseStudentMapping->save($data);
									$this->moveStudentData($old_cm_id, $old_student_id, "cp", $student_id, $common_courses_passed, $month_year_to_transfer);
								}
								
							}
					}
					if (isset($this->request->data['MapStudents']['CommonFail'])) {
						//pr($commonFailMappingArray);
						$tmpArray = array();
						$tmpArray = $this->request->data['MapStudents']['CommonFail'];
						//$common_courses_failed = $this->request->data['MapStudents']['CommonFail'];
						//pr($common_courses_failed);
						//$month_year_to_transfer = $this->request->data['MapStudents']['cf_my_id'];
						foreach ($tmpArray as $semId => $common_courses_failed) {
							//pr($common_courses_failed);
							$month_year_to_transfer = $this->request->data['MapStudents']['cf_my_id'][$semId];
							foreach ($common_courses_failed as $cf_cm_id => $course_code) {
								$csmResult = $this->CourseStudentMapping->find('first', array(
										'conditions' => array('CourseStudentMapping.course_mapping_id'=>$cf_cm_id,
												'CourseStudentMapping.student_id'=>$old_student_id,
										),
										'fields' => array('CourseStudentMapping.id')
								));
								//pr($csmResult);
								$csm_id = $csmResult['CourseStudentMapping']['id'];
								$data=array();
								$data['CourseStudentMapping']['id']=$csm_id;
								$data['CourseStudentMapping']['type']="CF";
								$this->CourseStudentMapping->save($data);
								
								$uncommonFailedCourses[$cf_cm_id]=$commonFailMappingArray[$cf_cm_id];
								
								$this->moveStudentData($cf_cm_id, $old_student_id, "cf", $student_id, $uncommonFailedCourses, $month_year_to_transfer);
								
								//echo $commonFailMappingArray[$cf_cm_id]."**";
							}
						}
					}
					
					if (isset($this->request->data['MapStudents']['UnCommonPassCheckbox'])) {
						$tmpArray = array();
						$tmpArray = $this->request->data['MapStudents']['UnCommonPassCheckbox'];
						
						//$month_year_to_transfer = $this->request->data['MapStudents']['ucp_my_id'];
						//pr($uncommon_courses_passed);
						//echo $new_batch_id." ".$new_program_id." * ";
						foreach ($tmpArray as $semId => $uncommon_courses_passed) {
							$month_year_to_transfer = $this->request->data['MapStudents']['ucp_my_id'][$semId];
							foreach ($uncommon_courses_passed as $ucp_cm_id => $course_selection_status) {
								$courseResult = $this->getCourseIdFromCmId($ucp_cm_id);
								//pr($courseResult);
								$courseId = $courseResult[0]['Course']['id'];
								//echo $courseId;
								//Add uncommon passsed courses to Transferred to program starts here
								$semResults = $this->getSemesterIdFromMonthYear($new_batch_id, $new_program_id, $month_year_to_transfer);
								//pr($semResults);
								$semesterIdToTransfer = $semResults['CourseMapping']['semester_id'];
								$cmCrseNumber = $this->CourseMapping->find('first', array(
										'conditions' => array('CourseMapping.batch_id'=>$new_batch_id,
												'CourseMapping.program_id'=>$new_program_id,
										),
										'fields' => array('CourseMapping.id', 'CourseMapping.course_number'),
										'order'=>array('CourseMapping.course_number DESC'),
										'contain'=>false
								));
								//pr($cmCrseNumber);
								$nxtCrseNumber = $cmCrseNumber['CourseMapping']['course_number']+1;
								
								$csmResult = $this->CourseStudentMapping->find('first', array(
										'conditions' => array('CourseStudentMapping.course_mapping_id'=>$ucp_cm_id,
												'CourseStudentMapping.student_id'=>$old_student_id,
										),
										'fields' => array('CourseStudentMapping.id', 
												//'CourseStudentMapping.month_year_id',
												//'CourseStudentMapping.semester_id'
										)
								));
								//pr($csmResult);
								$csm_id = $csmResult['CourseStudentMapping']['id'];
									
								if ($course_selection_status == 1) {
									$data=array();
									$data['CourseStudentMapping']['id']=$csm_id;
									$data['CourseStudentMapping']['type']="UCP";
									$data['CourseStudentMapping']['eq_course_mapping_id']=0;
									$this->CourseStudentMapping->save($data);
									
									$array['new_batch_id']=$new_batch_id;
									$array['new_program_id']=$new_program_id;
									$array['course_id']=$courseId;
									$array['month_year_id']=$month_year_to_transfer;
									$array['semester_id']=$semesterIdToTransfer;
									$array['course_number']=$nxtCrseNumber;
									
									$id = $this->addToCourseMapping($array);
									
									$uncommonPassedCourses[$ucp_cm_id]=$id;
									
									//Add uncommon passsed courses to Transferred to program ends here
									//echo $courseId;
									
									//$cf_cm_id, $old_student_id, "cf", $student_id, $cmArray, $month_year_to_transfer
									$this->moveStudentData($ucp_cm_id, $old_student_id, "ucp", $student_id, $uncommonPassedCourses, $month_year_to_transfer);
		
								}
							}
						}
					}
					/* if (isset($this->request->data['MapStudents']['New'])) {
						$yet_to_learn_courses = $this->request->data['MapStudents']['New'];
						$month_year_to_transfer = $this->request->data['MapStudents']['new_my_id'];
						
						foreach ($yet_to_learn_courses as $yet_cm_id => $course_selection_status) {
							$courseResult = $this->getCourseIdFromCmId($yet_cm_id);
							//pr($courseResult);
							$courseId = $courseResult[0]['Course']['id'];
							echo $courseId;
							//Add uncommon passsed courses to Transferred to program starts here
							$semResults = $this->getSemesterIdFromMonthYear($new_batch_id, $new_program_id, $month_year_to_transfer);
							pr($semResults);
							$semesterIdToTransfer = $semResults['CourseMapping']['semester_id'];
							$cmCrseNumber = $this->CourseMapping->find('first', array(
									'conditions' => array('CourseMapping.batch_id'=>$new_batch_id,
											'CourseMapping.program_id'=>$new_program_id,
									),
									'fields' => array('CourseMapping.id', 'CourseMapping.course_number'),
									'order'=>array('CourseMapping.course_number DESC'),
									'contain'=>false
							));
							pr($cmCrseNumber);
							$nxtCrseNumber = $cmCrseNumber['CourseMapping']['course_number']+1;
								
							if ($course_selection_status == 1) {					
								
								$courseResult = $this->getCourseIdFromCmId($yet_cm_id);
								//pr($courseResult);
								$courseId = $courseResult[0]['Course']['id'];
								echo $courseId;
								//Add uncommon passsed courses to Transferred to program starts here
								$semResults = $this->getSemesterIdFromMonthYear($new_batch_id, $new_program_id, $month_year_to_transfer);
								pr($semResults);
								$semesterIdToTransfer = $semResults['CourseMapping']['semester_id'];
								$cmCrseNumber = $this->CourseMapping->find('first', array(
										'conditions' => array('CourseMapping.batch_id'=>$new_batch_id,
												'CourseMapping.program_id'=>$new_program_id,
										),
										'fields' => array('CourseMapping.id', 'CourseMapping.course_number'),
										'order'=>array('CourseMapping.course_number DESC'),
										'contain'=>false
								));
								pr($cmCrseNumber);
								$nxtCrseNumber = $cmCrseNumber['CourseMapping']['course_number']+1;
								
								$array['new_batch_id']=$new_batch_id;
								$array['new_program_id']=$new_program_id;
								$array['course_id']=$courseId;
								$array['month_year_id']=$month_year_to_transfer;
								$array['semester_id']=$semesterIdToTransfer;
								$array['course_number']=$nxtCrseNumber;
								
								$id = $this->addToCourseMapping($array);
								
								$newCourses[$ucp_cm_id]=$id;
								//Add uncommon passsed courses to Transferred to program ends here
								echo $courseId;
								$this->moveStudentData($ucp_cm_id, $old_student_id, "new", $student_id, $newCourses, $month_year_to_transfer);
								$this->moveStudentData($yet_cm_id, $old_student_id, "ucf", $student_id);
							}
							
						}
					} */
				}
				//$this->Flash->success("Transfer Courses updated");
				//$this->redirect(array('controller' => 'Students','action' => 'batchTransfer'));
			}
		}
		else if ($student_type_id == 3 && $results[0]['Student']['parent_id']==NULL) {
			
			$results = $this->otherUniversity($reg_number);
			$student_id = $results[0]['Student']['id'];
			$joining_month_year_id = $results[0]['Student']['month_year_id'];
			
			$courseMapping = $this->CourseMapping->find('all', array(
				'conditions'=>array('CourseMapping.batch_id'=>$results[0]['Student']['batch_id'],
						'CourseMapping.program_id'=>$results[0]['Student']['program_id'],
						'CourseMapping.month_year_id <'=>$results[0]['Student']['month_year_id'],
				),
				'fields'=>array('CourseMapping.id'),
				'contain'=>array(
					'Course' => array('fields' => array('Course.course_code', 'Course.course_name', 'Course.course_type_id'),
					'CourseType'=>array(
								'fields'=>array('CourseType.course_type')
						)
					),
				)
			));
			//pr($courseMapping);
			
			$courseMappingArray = $this->CourseMapping->find('all', array(
					'conditions'=>array('CourseMapping.batch_id'=>$results[0]['Student']['batch_id'], 
							'CourseMapping.program_id'=>$results[0]['Student']['program_id'],
							'CourseMapping.month_year_id <'=>$joining_month_year_id, 'CourseMapping.indicator'=>0,
					),
					'fields'=>array('CourseMapping.id'),
					'contain'=>array(
							'Course'=>array(
									'fields' => array('Course.id', 'Course.course_type_id', 'Course.course_name', 'Course.course_code',
											'Course.max_cae_mark'),
									'CourseType'=>array(
											'fields'=>array('CourseType.course_type')
									)
							),
							'CourseStudentMapping'=>array(
									'conditions' => array('CourseStudentMapping.student_id' => $results[0]['Student']['id']),
									'fields' => array('CourseStudentMapping.student_id', 'CourseStudentMapping.course_mapping_id',
											'CourseStudentMapping.new_semester_id', 'CourseStudentMapping.type'
									),
									'Student'=>array(
											'fields'=>array('Student.id'),
											'conditions'=>array('Student.id'=>$results[0]['Student']['id']),
											'StudentMark'=>array(
													'fields'=>array('StudentMark.marks', 'StudentMark.course_mapping_id', 'StudentMark.status')
											),
											'InternalExam'=>array(
													'conditions'=>array('InternalExam.student_id'=>$results[0]['Student']['id']),
													'fields'=>array('InternalExam.id', 'InternalExam.course_mapping_id', 'InternalExam.marks',),
											),
											'EndSemesterExam'=>array(
													'conditions'=>array('EndSemesterExam.student_id'=>$results[0]['Student']['id']),
													'fields'=>array('EndSemesterExam.id', 'EndSemesterExam.course_mapping_id', 'EndSemesterExam.marks',),
											),
											'InternalPractical'=>array(
													'fields'=>array('InternalPractical.cae_practical_id', 'InternalPractical.marks'),
													'CaePractical'=>array(
															'conditions'=>array('CaePractical.course_mapping_id')
													)
											),
											'Practical'=>array(
													'fields'=>array('Practical.ese_practical_id', 'Practical.marks'),
													'EsePractical'=>array(
															'conditions'=>array('EsePractical.course_mapping_id')
													)
											)
									)
			
							)
					)
			));
			$this->set(compact('reg_number', 'results', 'student_id', 'courseMapping', 'joining_month_year_id', 'courseMappingArray'));
			$this->render('batch_transfer_from_other_university');
		}
		else if ($student_type_id == 2 && $results[0]['Student']['parent_id']==NULL){
			$this->Flash->error("No data required");
			$this->redirect('regNoSearch');
		}
		else if ($student_type_id == 5 && $results[0]['Student']['parent_id']==0) {
			//echo "*** ".$results[0]['Student']['id']; 
			$this->set(compact('student_id', $results[0]['Student']['id']));
			if ($results[0]['Student']['parent_id']==0) {
				$batch_id = $results[0]['Student']['batch_id'];
				$program_id = $results[0]['Student']['program_id'];
				$university_references_id = $results[0]['Student']['university_references_id'];
				$joining_month_year_id = $results[0]['Student']['month_year_id'];
			}
			$courseMappingArray = $this->CourseMapping->find('all', array(
				'conditions'=>array('CourseMapping.batch_id'=>$batch_id, 'CourseMapping.program_id'=>$program_id,
						'CourseMapping.month_year_id <'=>$joining_month_year_id, 'CourseMapping.indicator'=>0,
				),
				'fields'=>array('CourseMapping.id'),
				'contain'=>array(
					'Course'=>array(
						'fields' => array('Course.id', 'Course.course_type_id', 'Course.course_name', 'Course.course_code',
						'Course.max_cae_mark'),
							'CourseType'=>array(
									'fields'=>array('CourseType.course_type')
							)
					),
					'CourseStudentMapping'=>array(
							'conditions' => array('CourseStudentMapping.student_id' => $results[0]['Student']['id']),
							'fields' => array('CourseStudentMapping.student_id', 'CourseStudentMapping.course_mapping_id',
									'CourseStudentMapping.new_semester_id'
							),
							'Student'=>array(
								'fields'=>array('Student.id'),
								'conditions'=>array('Student.id'=>$results[0]['Student']['id']),
								'StudentMark'=>array(
									'fields'=>array('StudentMark.marks', 'StudentMark.course_mapping_id', 'StudentMark.status')
								),
								'InternalExam'=>array(
									'conditions'=>array('InternalExam.student_id'=>$results[0]['Student']['id']),
									'fields'=>array('InternalExam.id', 'InternalExam.course_mapping_id', 'InternalExam.marks',),
								),
								'EndSemesterExam'=>array(
									'conditions'=>array('EndSemesterExam.student_id'=>$results[0]['Student']['id']),
									'fields'=>array('EndSemesterExam.id', 'EndSemesterExam.course_mapping_id', 'EndSemesterExam.marks',),
								),
								'InternalPractical'=>array(
									'fields'=>array('InternalPractical.cae_practical_id', 'InternalPractical.marks'),
										'CaePractical'=>array(
											'conditions'=>array('CaePractical.course_mapping_id')
										)
								),
								'Practical'=>array(
									'fields'=>array('Practical.ese_practical_id', 'Practical.marks'),
										'EsePractical'=>array(
											'conditions'=>array('EsePractical.course_mapping_id')
										)
								)
							)
								
					)
				)
			));
			//pr($courseMappingArray);
			
			$monthYears = $this->MonthYear->find('all', array(
					'fields' => array('MonthYear.id','MonthYear.year','MonthYear.month_id','Month.month_name'),
					'order' => array('MonthYear.id DESC'),
					'conditions'=>array('MonthYear.id <'=>$joining_month_year_id)
			));
			$monthyears=array();
			
			foreach ($monthYears as $key => $value) {
				$monthyears[$value['MonthYear']['id']]=$value['Month']['month_name']."-".$value['MonthYear']['year'];
			}
			
			$this->set(compact('results', 'courseMappingArray', 'monthyears', 'joining_month_year_id'));
			$this->autoRender=false;
			$this->render("old_batch_transfer");
			
		}
	}
	public function addToCourseMapping($array) {
		$cmResult = $this->CourseMapping->find('first', array(
				'conditions' => array('CourseMapping.batch_id'=>$array['new_batch_id'],
						'CourseMapping.program_id'=>$array['new_program_id'],
						'CourseMapping.semester_id'=>$array['semester_id'],
						'CourseMapping.course_id'=>$array['course_id'],
				),
				'fields' => array('CourseMapping.id')
		));
		//pr($cmResult);
		$data1=array();
		$data1['CourseMapping']['batch_id']=$array['new_batch_id'];
		$data1['CourseMapping']['program_id']=$array['new_program_id'];
		$data1['CourseMapping']['course_mode_id']=2;
		$data1['CourseMapping']['course_number']=$array['course_number'];
		$data1['CourseMapping']['semester_id']=$array['semester_id'];
		$data1['CourseMapping']['month_year_id']=$array['month_year_id'];
		$data1['CourseMapping']['course_id']=$array['course_id'];
			
		if (isset($cmResult) && count($cmResult)>0) {
			$id = $cmResult['CourseMapping']['id'];
			$data1['CourseMapping']['id']=$id;
			$data1['CourseMapping']['modified_by']=$this->Auth->user('id');
			$data1['CourseMapping']['modified']=date("Y-m-d H:i:s");
		}
		else {
			$data1['CourseMapping']['created_by']=$this->Auth->user('id');
			$this->CourseMapping->create();
				
		}
		$this->CourseMapping->save($data1);
		if (isset($cmResult) && count($cmResult)>0) {
			$id = $cmResult['CourseMapping']['id'];
		} else {
			$id = $this->CourseMapping->getLastInsertID();
		}
		return $id;
	}
	
	public function moveStudentData($cm_id, $old_student_id, $action, $new_student_id, $cmArray, $month_year_to_transfer) {
		//pr($cmArray);
		$crseResults = $this->Student->find('all', array(
				'conditions' => array('Student.id'=>$old_student_id),
				'fields'=>array('Student.id'),
				'contain'=>array(
						'StudentMark' => array(
								'fields' => array('StudentMark.id','StudentMark.month_year_id', 'StudentMark.marks', 
										'StudentMark.student_id', 'StudentMark.course_mapping_id','StudentMark.status',
										'StudentMark.revaluation_status','StudentMark.final_marks','StudentMark.final_status',
										'StudentMark.grade', 'StudentMark.grade_point'),
								'conditions' => array('StudentMark.course_mapping_id'=>$cm_id),
								'CourseMapping'=>array('fields'=>array('CourseMapping.id','CourseMapping.semester_id',
										'CourseMapping.month_year_id'),
										'conditions'=>array('CourseMapping.indicator'=>0,
										),
										'Course' => array(
												'fields' => array('Course.id','Course.course_code','Course.course_name','Course.course_max_marks'),
												'CourseType' => array('fields' => array('CourseType.course_type'))
										),'order'=>'CourseMapping.semester_id ASC',
										'CaePractical'=>array(
												'fields' => array('CaePractical.id','CaePractical.course_mapping_id'),
												'InternalPractical' =>array(
														'fields' => array('InternalPractical.id','InternalPractical.cae_practical_id','InternalPractical.month_year_id','InternalPractical.marks','InternalPractical.student_id'),
														'conditions' => array('InternalPractical.student_id'=>$old_student_id)
												),
										),
										'EsePractical'=>array(
												'fields' => array('EsePractical.id','EsePractical.course_mapping_id'),
												'Practical' =>array(
														'fields' => array('Practical.id','Practical.ese_practical_id','Practical.month_year_id','Practical.marks','Practical.student_id'),
														'conditions' => array('Practical.student_id'=>$old_student_id)
												),
										),
										'InternalExam' =>array(
												'fields' => array('InternalExam.id','InternalExam.course_mapping_id','InternalExam.month_year_id','InternalExam.marks','InternalExam.student_id'),
												'conditions' => array('InternalExam.student_id'=>$old_student_id)
										),
										'EndSemesterExam' =>array(
												'fields' => array('EndSemesterExam.id','EndSemesterExam.course_mapping_id','EndSemesterExam.month_year_id','EndSemesterExam.marks','EndSemesterExam.student_id',
														'EndSemesterExam.dummy_number_id', 'EndSemesterExam.dummy_number', 'EndSemesterExam.marks'
												),
												'conditions' => array('EndSemesterExam.student_id'=>$old_student_id)
										),
										'RevaluationExam' =>array(
												'fields' => array('RevaluationExam.id','RevaluationExam.course_mapping_id','RevaluationExam.month_year_id','RevaluationExam.revaluation_marks','RevaluationExam.student_id'),
												'conditions' => array('RevaluationExam.student_id'=>$old_student_id)
										),
								),'order'=>'StudentMark.month_year_id ASC',
						),
				),
		));
		
		//pr($crseResults);
		$student_mark_id = $crseResults[0]['StudentMark'][0]['id'];
		$course_type_id = $crseResults[0]['StudentMark'][0]['CourseMapping']['Course']['course_type_id'];
		$course_code = $crseResults[0]['StudentMark'][0]['CourseMapping']['Course']['course_code'];
		$course_name = $crseResults[0]['StudentMark'][0]['CourseMapping']['Course']['course_name'];
		$course_max_marks = $crseResults[0]['StudentMark'][0]['CourseMapping']['Course']['course_max_marks'];
		$actual_month_year_id = $crseResults[0]['StudentMark'][0]['CourseMapping']['month_year_id'];
		$actual_semester_id = $crseResults[0]['StudentMark'][0]['CourseMapping']['semester_id'];
		if ($crseResults[0]['StudentMark'][0]['revaluation_status']==1) {
			$marks = $crseResults[0]['StudentMark'][0]['final_marks'];
			$status = $crseResults[0]['StudentMark'][0]['final_status'];
		}
		else if ($crseResults[0]['StudentMark'][0]['revaluation_status']==0) {
			$marks = $crseResults[0]['StudentMark'][0]['marks'];
			$status = $crseResults[0]['StudentMark'][0]['status'];
		}
		$grade = $crseResults[0]['StudentMark'][0]['grade'];
		$grade_point = $crseResults[0]['StudentMark'][0]['grade_point'];
		//pr($cmArray);
		//Course student mapping
		//echo $cmArray[$cm_id];
		$csmExists = $this->CourseStudentMapping->find('first', array(
				'conditions' => array('CourseStudentMapping.course_mapping_id'=>$cmArray[$cm_id],
						'CourseStudentMapping.student_id'=>$new_student_id,
				),
				'fields'=>array('CourseStudentMapping.id')
		));
		
		$cId = $this->getCourseIdFromCmId($cm_id);
		$course_id = $cId[0]['Course']['id'];
		$data=array();
		$data['CourseStudentMapping']['course_id']=$course_id;
		$data['CourseStudentMapping']['course_mapping_id']=$cmArray[$cm_id];
		$data['CourseStudentMapping']['student_id']=$new_student_id;
		$data['CourseStudentMapping']['type']=strtoupper($action);
		
		if (isset($csmExists) && count($csmExists)>0) {
			$id = $csmExists['CourseStudentMapping']['id'];
			$data['CourseStudentMapping']['id']=$id;
			$data['CourseStudentMapping']['modified_by']=$this->Auth->user('id');
			$data['CourseStudentMapping']['modified']=date("Y-m-d H:i:s");
		}
		else {
			$data['CourseStudentMapping']['created_by']=$this->Auth->user('id');
			$this->CourseStudentMapping->create();
		}
		$this->CourseStudentMapping->save($data);
		//cours student mapping ends
		
		switch ($course_type_id) {
			CASE 1:
				$cae = $crseResults[0]['StudentMark'][0]['CourseMapping']['InternalExam'][0]['marks'];
				if (isset($crseResults[0]['StudentMark'][0]['CourseMapping']['EndSemesterExam'][0]['marks']))
					$ese = $crseResults[0]['StudentMark'][0]['CourseMapping']['EndSemesterExam'][0]['marks'];
				else
					$ese = 'AAA';
				
				//Code to insert cae and ese to new course mapping id
				//echo $cae." *** ".$ese." ** ".$cmArray[$cm_id]." ** ".$month_year_to_transfer;
				//pr($cmArray);
				
				$ieExists = $this->InternalExam->find('first', array(
						'conditions' => array('InternalExam.course_mapping_id'=>$cmArray[$cm_id], 
								'InternalExam.student_id'=>$new_student_id,
								'InternalExam.month_year_id'=>$month_year_to_transfer
						),
						'fields'=>array('InternalExam.id')
				));
				//pr($ieExists);
				
				$data=array();
				$data['InternalExam']['month_year_id']=$month_year_to_transfer;
				$data['InternalExam']['course_mapping_id']=$cmArray[$cm_id];
				$data['InternalExam']['student_id']=$new_student_id;
				$data['InternalExam']['marks']=$cae;
				
				if (isset($ieExists) && count($ieExists)>0) {
					$id = $ieExists['InternalExam']['id'];
					$data['InternalExam']['id']=$id;
					$data['InternalExam']['modified_by']=$this->Auth->user('id');
					$data['InternalExam']['modified']=date("Y-m-d H:i:s");
				}
				else {
					$data['InternalExam']['created_by']=$this->Auth->user('id');
					$this->InternalExam->create();
				}
				$this->InternalExam->save($data);
				
				if ($ese !='AAA') {
					//echo $ese;
					$course_mapping_id = $crseResults[0]['StudentMark'][0]['CourseMapping']['EndSemesterExam'][0]['course_mapping_id'];
					$month_year_id = $crseResults[0]['StudentMark'][0]['CourseMapping']['EndSemesterExam'][0]['month_year_id'];
					$dummy_number_id = $crseResults[0]['StudentMark'][0]['CourseMapping']['EndSemesterExam'][0]['dummy_number_id'];
					$dummy_number = $crseResults[0]['StudentMark'][0]['CourseMapping']['EndSemesterExam'][0]['dummy_number'];
					$eseExists = $this->EndSemesterExam->find('first', array(
							'conditions' => array('EndSemesterExam.course_mapping_id'=>$cmArray[$cm_id],
									'EndSemesterExam.student_id'=>$new_student_id,
									'EndSemesterExam.month_year_id'=>$month_year_to_transfer
							),
							'fields'=>array('EndSemesterExam.id')
					));
					//pr($eseExists);
					$data=array();
					$data['EndSemesterExam']['month_year_id']=$month_year_to_transfer;
					$data['EndSemesterExam']['course_mapping_id']=$cmArray[$cm_id];
					$data['EndSemesterExam']['student_id']=$new_student_id;
					$data['EndSemesterExam']['dummy_number_id']=$dummy_number_id;
					$data['EndSemesterExam']['dummy_number']=$dummy_number;
					$data['EndSemesterExam']['marks']=$ese;
					
					if (isset($eseExists) && count($eseExists)>0) {
						$id = $eseExists['EndSemesterExam']['id'];
						$data['EndSemesterExam']['id']=$id;
						$data['EndSemesterExam']['modified_by']=$this->Auth->user('id');
						$data['EndSemesterExam']['modified']=date("Y-m-d H:i:s");
					}
					else {
						$data['EndSemesterExam']['created_by']=$this->Auth->user('id');
						$this->EndSemesterExam->create();
					}
					$this->EndSemesterExam->save($data);
				}
				//if($action=="ucp") die;
				break;
			CASE 2:
				$cae = $crseResults[0]['StudentMark'][0]['CourseMapping']['CaePractical'][0]['InternalPractical'][0]['marks'];
				$ese = $crseResults[0]['StudentMark'][0]['CourseMapping']['EsePractical'][0]['Practical'][0]['marks'];
				//echo $cmArray[$cm_id];
				$ieExists = $this->CaePractical->find('all', array(
						'conditions' => array('CaePractical.course_mapping_id'=>$cmArray[$cm_id],
						),
						'fields' => array('CaePractical.id','CaePractical.course_mapping_id'),
						'contain'=>false
				));
				if (isset($ieExists) && count($ieExists)>0) {
					$cae_id = $ieExists[0]['CaePractical']['id'];
				}
				else {
					$data=array();
					$data['CaePractical']['month_year_id'] = $month_year_to_transfer;
					$data['CaePractical']['course_mapping_id'] = $cmArray[$cm_id];
					$data['CaePractical']['assessment_type'] = "CAE";
					$data['CaePractical']['marks'] = 50;
					$data['CaePractical']['marks_status'] = "Entered";
					$data['CaePractical']['add_status'] = 1;
					$data['CaePractical']['approval_status'] = 1;
					$data['CaePractical']['indicator'] = 0;
					$data['CaePractical']['created_by'] = $this->Auth->user('id');
					$this->CaePractical->create();
					$this->CaePractical->save($data);
					$cae_id = $this->CaePractical->getLastInsertID();
				}
				//$cae_id = $ieExists[0]['CaePractical']['id'];
				$caeExists = $this->InternalPractical->find('all', array(
						'conditions' => array('InternalPractical.cae_practical_id'=>$cae_id,
								'InternalPractical.student_id'=>$new_student_id
						),
						'fields' => array('InternalPractical.marks', 'InternalPractical.id'),
						'contain'=>false
				));
				//pr($caeExists);
				
				$data=array();
				$data['InternalPractical']['month_year_id']=$month_year_to_transfer;
				$data['InternalPractical']['cae_practical_id']=$cae_id;
				$data['InternalPractical']['student_id']=$new_student_id;
				$data['InternalPractical']['marks']=$cae;
				
				if (isset($caeExists) && count($caeExists)>0) {
					$id = $caeExists[0]['InternalPractical']['id'];
					$data['InternalPractical']['id']=$id;
					$data['InternalPractical']['modified_by']=$this->Auth->user('id');
					$data['InternalPractical']['modified']=date("Y-m-d H:i:s");
				}
				else {
					$data['InternalPractical']['created_by']=$this->Auth->user('id');
					$this->InternalPractical->create();
				}
				$this->InternalPractical->save($data);
				
				$exExists = $this->EsePractical->find('all', array(
						'conditions' => array('EsePractical.course_mapping_id'=>$cmArray[$cm_id],
						),
						'fields' => array('EsePractical.id','EsePractical.course_mapping_id'),
						'contain'=>false
				));
				//pr($exExists);
				if (isset($exExists) && count($exExists)>0) {
					$ese_id = $exExists[0]['EsePractical']['id'];
				}
				else {
					$data=array();
					$data['EsePractical']['month_year_id'] = $month_year_to_transfer;
					$data['EsePractical']['course_mapping_id'] = $cmArray[$cm_id];
					$data['EsePractical']['assessment_type'] = "CAE";
					$data['EsePractical']['marks'] = 50;
					$data['EsePractical']['marks_status'] = "Entered";
					$data['EsePractical']['add_status'] = 1;
					$data['EsePractical']['approval_status'] = 1;
					$data['EsePractical']['indicator'] = 0;
					$data['EsePractical']['created_by'] = $this->Auth->user('id');
					$this->EsePractical->create();
					$this->EsePractical->save($data);
					$ese_id = $this->EsePractical->getLastInsertID();
				}
				
				$eseExists = $this->Practical->find('all', array(
						'conditions' => array('Practical.ese_practical_id'=>$ese_id,
								'Practical.student_id'=>$new_student_id
						),
						'fields' => array('Practical.marks', 'Practical.id'),
						'contain'=>false
				));
				//pr($eseExists);
				
				$data=array();
				$data['Practical']['month_year_id']=$month_year_to_transfer;
				$data['Practical']['ese_practical_id']=$ese_id;
				$data['Practical']['student_id']=$new_student_id;
				$data['Practical']['marks']=$ese;
				
				if (isset($eseExists) && count($eseExists)>0) {
					$id = $eseExists[0]['Practical']['id'];
					$data['Practical']['id']=$id;
					$data['Practical']['modified_by']=$this->Auth->user('id');
					$data['Practical']['modified']=date("Y-m-d H:i:s");
				}
				else {
					$data['Practical']['created_by']=$this->Auth->user('id');
					$this->Practical->create();
				}
				$this->Practical->save($data);
				BREAK;
			CASE 3:
				BREAK;
		}
		$tsExists = $this->TransferStudent->find('first', array(
				'conditions' => array('TransferStudent.course_code'=>$course_code, 'TransferStudent.student_id'=>$old_student_id,
						'TransferStudent.month_year_id'=>$actual_month_year_id, 'TransferStudent.semester_id'=>$actual_semester_id
				),
				'fields'=>array('TransferStudent.id')
		));
	
		$data=array();
		$data['TransferStudent']['student_id']=$old_student_id;
		$data['TransferStudent']['course_mapping_id']=$cm_id;
		$data['TransferStudent']['course_type_id']=$course_type_id;
		$data['TransferStudent']['course_code']=$course_code;
		$data['TransferStudent']['course_name']=$course_name;
		$data['TransferStudent']['course_max_marks']=$course_max_marks;
		$data['TransferStudent']['marks']=$marks;
		$data['TransferStudent']['cae']=$cae;
		$data['TransferStudent']['ese']=$ese;
		$data['TransferStudent']['status']=$status;
		$data['TransferStudent']['semester_id']=$actual_semester_id;
		$data['TransferStudent']['month_year_id']=$actual_month_year_id;
	
		//pr($tsExists);
		if (isset($tsExists) && count($tsExists)>0) {
			$ts_id = $tsExists['TransferStudent']['id'];
			$data['TransferStudent']['id']=$ts_id;
			$data['TransferStudent']['modified_by']=$this->Auth->user('id');
			$data['TransferStudent']['modified']=date("Y-m-d H:i:s");
		}
		else {
			$data['TransferStudent']['created_by']=$this->Auth->user('id');
			$this->TransferStudent->create();
		}
		$this->TransferStudent->save($data);
	
		//if ($status == "Fail" && ($action=="cf" || $action=="ucf")) {
			//echo $this->new_month_year_id;
			//pr($this->commonFailMappingArray);
			//echo $this->commonFailMappingArray[$cm_id];
			SWITCH ($action) {
				CASE "cf":
				CASE "cp":
					$new_cm_id = $cm_id;
					break;
				CASE "ucf":
				CASE "ucp":
					$new_cm_id = $cm_id;
					break;
				
			}
				
			$smExists = $this->StudentMark->find("first", array(
					'conditions' => array('StudentMark.course_mapping_id'=>$cmArray[$cm_id],
							'StudentMark.month_year_id'=>$month_year_to_transfer,
							'StudentMark.student_id'=>$new_student_id,
					),
					'fields' => array('StudentMark.id')
			));
			//echo "student";
			//pr($smExists);
			
			//echo "new_cm_id: ".$new_cm_id;
			$course_mapping_array = array();
			$course_mapping_array[$new_cm_id]=$new_cm_id;
			
			$course_details = $this->CourseMapping->retrieveCourseDetails($course_mapping_array, $actual_month_year_id);
			//pr($course_details);
			
			$data=array();
			$data['StudentMark']['month_year_id']=$month_year_to_transfer;
			$data['StudentMark']['student_id']=$new_student_id;
			$data['StudentMark']['course_mapping_id']=$cmArray[$cm_id];
			$data['StudentMark']['parent_id']=$student_mark_id;
			$data['StudentMark']['marks']=$marks;
			$data['StudentMark']['status']=$status;
			$data['StudentMark']['grade']=$grade;
			$data['StudentMark']['grade_point']=$grade_point;
			
			if (isset($smExists) && count($smExists)>0) {
				$sm_id = $smExists['StudentMark']['id'];
				$data['StudentMark']['id']=$sm_id;
				$data['StudentMark']['modified_by']=$this->Auth->user('id');
				$data['StudentMark']['modified']=date("Y-m-d H:i:s");
			}
			else {
				$data['StudentMark']['created_by']=$this->Auth->user('id');
				$this->StudentMark->create();
			}
			//pr($data);
			$this->StudentMark->save($data);
		
		//}
	}
	
	public function otherUniversity($reg_number) {
		$results = $this->retrieveStudentInfo($reg_number);
	
		$monthYears = $this->MonthYear->find('all', array('fields' => array('MonthYear.id','MonthYear.year','MonthYear.month_id','Month.month_name'),'order' => array('MonthYear.id DESC')));
		$monthyears=array();
			
		foreach ($monthYears as $key => $value) {
			$monthyears[$value['MonthYear']['id']]=$value['Month']['month_name']."-".$value['MonthYear']['year'];
		}
	
		$semester_joined = $results[0]['Student']['month_year_id'];
		//echo $semester_joined;
		$this->set(compact('semester_joined', 'monthyears'));
		//pr($results);
		return $results;
	}
	
	public function retrieveStudentInfo($reg_number=NULL) {
	
		$results = $this->Student->find('all', array(
				'fields' => array('Student.id','Student.name','Student.batch_id','Student.program_id',
						'Student.academic_id','Student.student_type_id','Student.university_references_id',
						'Student.month_year_id','Student.parent_id', 'Student.prior_batch'
				),
				'conditions' => array('Student.registration_number' => $reg_number),
				'contain' => array(
						'CourseMapping' => array('conditions'=>array('CourseMapping.indicator'=>0),
								'fields' => array('CourseMapping.id'),
								'Course' => array('fields' => array('Course.course_code', 'Course.course_type_id'))
						),
						'Batch' => array('fields' => array(
								'Batch.batch_from', 'Batch.batch_to', 'Batch.academic'
						)),
						'Program' => array('fields' => array(
								'Program.program_name', 'Program.short_code'
						),
								'Academic' => array('fields' => array(
										'Academic.academic_name', 'Academic.short_code'
								))
						),
						'UniversityReference'=>array(
							'fields'=>array('UniversityReference.id', 'UniversityReference.university_name')	
						),
						'CourseStudentMapping' => array(
								'conditions' => array('CourseStudentMapping.indicator' => 0),
								'fields' => array('CourseStudentMapping.course_mapping_id',
										'CourseStudentMapping.eq_course_mapping_id',
										'CourseStudentMapping.course_number', 'CourseStudentMapping.new_semester_id',
										/* 'CourseStudentMapping.month_year_id', 'CourseStudentMapping.semester_id' */
								),
								'CourseMapping' => array('fields' => array('CourseMapping.id'),
										'Course' => array('fields' => array('Course.course_code'))
								)
						),
						'ParentGroup' => array(
								'fields' => array('ParentGroup.batch_id', 'ParentGroup.academic_id', 'ParentGroup.program_id'),
								'StudentMark' => array('fields' => array(
										'StudentMark.course_mapping_id', 'StudentMark.month_year_id', 'StudentMark.status'
								)),
								'Batch' => array('fields' => array(
										'Batch.batch_from', 'Batch.batch_to', 'Batch.academic'
								)),
								'Program' => array('fields' => array(
										'Program.program_name', 'Program.short_code'
								),
										'Academic' => array('fields' => array(
												'Academic.academic_name', 'Academic.short_code'
										))
								),
								'CourseStudentMapping' => array(
										'fields' => array('CourseStudentMapping.id','CourseStudentMapping.course_mapping_id',
												/* 'CourseStudentMapping.semester_id', */
												'CourseStudentMapping.type',
												'CourseStudentMapping.eq_course_mapping_id',
												'CourseStudentMapping.course_number', 'CourseStudentMapping.new_semester_id',
										),
										'conditions' => array(/*'CourseStudentMapping.indicator' => 0,
										'CourseStudentMapping.type' => 'TI' */
										),
										'CourseMapping' => array('fields' => array('CourseMapping.id',
												'CourseMapping.month_year_id', 'CourseMapping.semester_id'),
												'Course' => array('fields' => array('Course.course_code'))
										)
								),
								'TransferStudent' => array(
										'conditions' => array('TransferStudent.indicator' => 0),
										'fields' => array('TransferStudent.id')),
						)
				),
				'recursive'=>1
		));
		//pr($results);
		return $results;
	}
	
	public function retrieveBatchTransferStudentInfo($reg_number=NULL) {
	
		$results = $this->Student->find('all', array(
				'fields' => array('Student.id','Student.name','Student.batch_id','Student.program_id',
						'Student.academic_id','Student.student_type_id','Student.university_references_id',
						'Student.month_year_id','Student.parent_id', 'Student.prior_batch'
				),
				'conditions' => array('Student.registration_number' => $reg_number),
				'contain' => array(
						'CourseMapping' => array('conditions'=>array('CourseMapping.indicator'=>0),
								'fields' => array('CourseMapping.id'),
								'Course' => array('fields' => array('Course.course_code', 'Course.course_type_id'))
						),
						'Batch' => array('fields' => array(
								'Batch.batch_from', 'Batch.batch_to', 'Batch.academic'
						)),
						'Program' => array('fields' => array(
								'Program.program_name', 'Program.short_code'
						),
								'Academic' => array('fields' => array(
										'Academic.academic_name', 'Academic.short_code'
								))
						),
						'UniversityReference'=>array(
								'fields'=>array('UniversityReference.id', 'UniversityReference.university_name')
						),
						'CourseStudentMapping' => array(
								'conditions' => array('CourseStudentMapping.indicator' => 0),
								'fields' => array('CourseStudentMapping.course_mapping_id',
										'CourseStudentMapping.eq_course_mapping_id',
										'CourseStudentMapping.course_number', 'CourseStudentMapping.new_semester_id',
								),
								'CourseMapping' => array('fields' => array('CourseMapping.id'),
										'Course' => array('fields' => array('Course.course_code'))
								)
						),
				),
				'recursive'=>1
		));
		//pr($results);
		return $results;
	}
	public function deactivateTransferStudent($id) {
		$data = array();
		$data['TransferStudent']['id'] = $id;
		$data['TransferStudent']['indicator'] = 1;
		$this->TransferStudent->save($data);
	}
	
	public function lateJoiner() {
		if ($this->request->is('post')) {
			$regNo = $this->request->data['lateJoiner']['registration_number'];
			$result = $this->Student->find('first', array('conditions' => array('Student.registration_number' =>  $regNo),'fields' =>'Student.id'));
	
			if($result['Student']['id']){
				$this->redirect(array('controller' => 'Students','action' => 'lateJoinerSearch',$regNo));
			}else{
				$this->Flash->error(__('Invalid Register Number. Please, try again.'));
				$this->redirect(array('controller' => 'Students','action' => 'lateJoiner'));
			}
		}
	}
	
	public function lateJoinerSearch($regNo) {
		$results = $this->retrieveStudentInfo($regNo);
		//pr($results);
		$student_id = $results[0]['Student']['id'];
		$batch_id = $results[0]['Student']['batch_id'];
		$program_id = $results[0]['Student']['program_id'];
		$month_year_id = $results[0]['Student']['month_year_id'];
		$student_type_id = $results[0]['Student']['student_type_id'];
		$this->set(compact('results', 'batch_id', 'program_id', 'student_id', 'regNo', 'student_type_id'));
	
		$cm_sem_my_id= $this->CourseMapping->find('list', array(
				'conditions'=>array('CourseMapping.batch_id'=>$batch_id, 'CourseMapping.program_id'=>$program_id),
				'fields'=>array('CourseMapping.semester_id', 'CourseMapping.month_year_id'),
				'contain'=>false
		));
	//pr($cm_sem_my_id);
		$month_year_joined = $month_year_id;
		//echo $month_year_joined;
		$monthYears = $this->MonthYear->find('all', array(
				'fields' => array('MonthYear.id','MonthYear.year','MonthYear.month_id','Month.month_name'),
				'conditions' => array('MonthYear.id <' => $month_year_joined),
				'order' => array('MonthYear.id DESC'),
				'contain'=>array(
					'Month'=>array('fields'=>array('Month.month_name'))
				)
				
		));
		//pr($monthYears);
		
		$monthyears=array();
		
		foreach ($monthYears as $key => $value) {
			$monthyears[$value['MonthYear']['id']]=$value['Month']['month_name']."-".$value['MonthYear']['year'];
		}
		//pr($monthyears);
		
		$this->set(compact('month_year_joined', 'monthyears'));
		if ($this->request->is('post')) {
			//pr($this->data);
			$courseArray = $this->data['Student']['Course'];
			foreach ($courseArray as $cm_id => $status) {
				if ($status == "1") {
					//echo $cm_id." ";
					$courseResult = $this->getCourseIdFromCmId($cm_id);
					//pr($courseResult);
					$courseId = $courseResult[0]['Course']['id'];
					$data=array();
					$data['CourseStudentMapping']['student_id']=$this->data['Student']['student_id'];
					$data['CourseStudentMapping']['course_mapping_id']=$cm_id;
					//$data['CourseStudentMapping']['month_year_id']=$this->data['Student']['my_id'][$cm_id];
					//$data['CourseStudentMapping']['semester_id']=$this->data['Student']['semester_id'];
					$data['CourseStudentMapping']['new_semester_id']=$this->data['Student']['my_id'][$cm_id];
					$data['CourseStudentMapping']['course_id']=$courseId;
					if ($student_type_id<>1) $data['CourseStudentMapping']['type']="LJ";
					else $data['CourseStudentMapping']['type']="NLJ";
						
					if ($this->data['Student']['csm_id'][$cm_id] > 0) {
						$data['CourseStudentMapping']['id']=$this->data['Student']['csm_id'][$cm_id];
						$data['CourseStudentMapping']['modified_by'] = $this->Auth->user('id');
						$data['CourseStudentMapping']['modified'] = date("Y-m-d H:i:s");
					}
					else {
						$data['CourseStudentMapping']['created_by']=$this->Auth->user('id');
						$this->CourseStudentMapping->create($data);
					}
					$this->CourseStudentMapping->save($data);
				//	pr($this->data);
					$month_year_id = $this->data['Student']['my_id'][$cm_id];
					$course_type_id = $this->data['Student']['course_type_id'][$cm_id];
					//echo "CT : ".$course_type_id."</br>";
					switch ($course_type_id) {
						CASE 1:
							$model = "InternalExam";
							$field = "course_mapping_id";
							break;
						case 2:
						case 6:
							$model = "InternalPractical";
							$field = "cae_practical_id";
		 					break;
						case 3:
							break;
						case 4:
							$model = "ProjectReview";
							$field = "cae_project_id";
							break;
								
					}
					//echo $model;
					$data=array();
					$data[$model]['student_id']=$this->data['Student']['student_id'];
					$data[$model][$field]=$this->data['Student']['cae_id'][$cm_id];
					$data[$model]['month_year_id']=$month_year_id;
					$data[$model]['marks']=$this->data['Student']['cae_mark'][$cm_id];
						
					if ($this->data['Student']['internal_exam_id'][$cm_id] > 0) {
						$data[$model]['id']=$this->data['Student']['internal_exam_id'][$cm_id];
						$data[$model]['modified_by'] = $this->Auth->user('id');
						$data[$model]['modified'] = date("Y-m-d H:i:s");
						//pr($data);
					}
					else {
						$data[$model]['created_by']=$this->Auth->user('id');
						$this->$model->create($data);
					}
					//pr($data);
					$this->$model->save($data);
				}
			}
			$this->Flash->success(__('Data saved.'));
			$this->redirect(array('controller' => 'Students','action' => 'lateJoiner'));
		}
	
	}
	
	public function batchTransfer() {
		if ($this->request->is('post')) {
			$regNo = $this->request->data['batchTransfer']['registration_number'];
			$result = $this->Student->find('first', array('conditions' => array('Student.registration_number' =>  $regNo),'fields' =>'Student.id'));
	
			if($result['Student']['id']){
				$this->redirect(array('controller' => 'Students','action' => 'transferCourses',$regNo));
			}else{
				$this->Flash->error(__('Invalid Register Number. Please, try again.'));
				$this->redirect(array('controller' => 'Students','action' => 'batchTransfer'));
			}
		}
	}
	
	public function batchTransferSearch($regNo) {
		$results = $this->retrieveStudentInfo($regNo);
		//pr($results);
	
		$student_id = $results[0]['Student']['id'];
		$batch_id = $results[0]['Student']['batch_id'];
		$program_id = $results[0]['Student']['program_id'];
		
		$semester_id = $results[0]['Student']['semester_id'];
	
		$this->set(compact('results', 'batch_id', 'program_id', 'student_id', 'regNo'));
	
		$semester_joined = $semester_id;
		$this->set(compact('semester_joined'));
	
		$this->set(compact('reg_number', 'results'));
		if (isset($results[0]['Student']['parent_id']) && ($results[0]['Student']['parent_id']) > 0) {
			foreach ($results as $key => $result) {
				//pr($result);
				$new_batch_id = $result['Student']['batch_id'];
				$new_program_id = $result['Student']['program_id'];
				$new_academic_id = $result['Student']['academic_id'];
				$new_semester_id = $result['Student']['semester_id'];
	
				$parent_id = $result['Student']['parent_id'];
	
				$student_type_id = $result['Student']['student_type_id'];
				$university_references_id = $result['Student']['university_references_id'];
					
				$old_batch_id = $result['ParentGroup']['batch_id'];
				$old_program_id = $result['ParentGroup']['program_id'];
				$old_academic_id = $result['ParentGroup']['academic_id'];
					
				$oldCourseStudentMapping = $result['ParentGroup']['CourseStudentMapping'];
				//pr($oldCourseStudentMapping);
	
			}
			$oldCourseMappingArray = array();
			$oldCourseMappingFailArray = array();
			foreach ($oldCourseStudentMapping as $key => $value) {
				$semester_id = $value['semester_id'];
				$cm_id = $value['CourseMapping']['id'];
				$stu_mark = $this->StudentMark->find('first', array(
						'conditions'=>array('StudentMark.course_mapping_id'=>$cm_id, 'StudentMark.student_id'=>$parent_id),
						'fields'=>array('StudentMark.status')
				));
				$status = $stu_mark['StudentMark']['status'];
				$course_code = $value['CourseMapping']['Course']['course_code'];
				if ($status == "Pass") {
					//echo "pass";
					$oldCourseMappingArray[$semester_id][$cm_id] = $course_code;
					$oldCourseMappingDetailsArray[$semester_id][$cm_id] = array(
							'course_code' => $course_code,
							'eq_cm_id' => $value['eq_course_mapping_id'],
							'type' => $value['type']
					);
				}
				else {
					$oldCourseMappingFailArray[$semester_id][$cm_id] = $course_code;
				}
			}
			//pr($oldCourseMappingArray);
			//pr($oldCourseMappingFailArray);
			$new_course_mapping_id = array();
			$cmArray = $this->CourseMapping->find('all', array(
					'conditions' => array('CourseMapping.indicator' => 0, 'CourseMapping.batch_id'=>$new_batch_id,
							'CourseMapping.program_id' => $new_program_id, 'CourseMapping.semester_id <=' => $new_semester_id
					),
					'fields' => array('CourseMapping.id', 'CourseMapping.semester_id'),
					'contain' => array(
							'Course' => array('fields' => array('Course.course_code'))
					),
					'order' => array('CourseMapping.semester_id'),
			));
			//pr($cmArray);
			//die;
	
			$previous_semesters = $new_semester_id-1;
			$tmpArray = array();
	
			foreach ($cmArray as $key => $value) {
				$semester_id = $value['CourseMapping']['semester_id'];
				$cm_id = $value['CourseMapping']['id'];
				$course_code = $value['Course']['course_code'];
				$newCourseMappingArray[$semester_id][$cm_id] = $course_code;
	
			}
			//pr($newCourseMappingArray);
			//
	
			$monthYears = $this->MonthYear->find('all', array('fields' => array('MonthYear.id','MonthYear.year','MonthYear.month_id','Month.month_name'),'order' => array('MonthYear.id DESC')));
			$monthyears=array();
	
			foreach ($monthYears as $key => $value) {
				$monthyears[$value['MonthYear']['id']]=$value['Month']['month_name']."-".$value['MonthYear']['year'];
			}
	
			$this->set(compact('results', 'newCourseMappingArray', 'oldCourseMappingDetailsArray', 'oldCourseMappingArray', 'oldCourseMappingFailArray', 'previous_semesters', 'parent_id', 'monthyears'));
		}
	}
	public function getCoursesForASemester($batch_id, $program_id, $month_year_id, $student_id, $month_year_joined) {
		$cmResults = $this->CourseMapping->find('all', array(
				'conditions' => array('CourseMapping.month_year_id <=' => $month_year_id,
						'CourseMapping.batch_id' => $batch_id, 'CourseMapping.program_id' => $program_id,
						'CourseMapping.indicator' => 0
				),
				'fields' => array('CourseMapping.id', 'CourseMapping.month_year_id', 'CourseMapping.semester_id',
						'CourseMapping.course_number'
				),
				'contain' => array(
						'Course' => array('fields'=>array('Course.course_code', 'Course.course_type_id')),
						'CourseStudentMapping'=> array('conditions' => array('CourseStudentMapping.student_id' => $student_id),
								'fields' => array('CourseStudentMapping.*')
						),
						'InternalExam'=> array('conditions' => array('InternalExam.student_id' => $student_id),
								'fields' => array('InternalExam.marks', 'InternalExam.id')
						),
						'CaePractical'=>array('fields' => array('CaePractical.course_mapping_id', 'CaePractical.marks'),
								'conditions'=>array('CaePractical.indicator'=>0),
								'InternalPractical' => array('fields'=>array('InternalPractical.student_id', 'InternalPractical.marks',),
										'conditions'=>array('InternalPractical.student_id'=>$student_id)
								)
						),
				),
		));
		//pr($cmResults);
		$new_month_year = $month_year_joined + $month_year_id - 1;
	
		$myResults = $this->getMonthYears($batch_id, $program_id, $month_year_joined);
		$semResults = $this->getSemesters($batch_id, $program_id, $month_year_joined);
		$this->set(compact('cmResults', 'myResults', 'semResults', 'new_month_year'));
		$this->layout=false;
	
	}
	
	public function getMonthYears($batch_id, $program_id, $month_year_joined, $cm_id=NULL, $regNum=NULL) {
		//echo $semester_joined;
		$maxMYResults = $this->CourseMapping->find('all', array(
				'conditions' => array('CourseMapping.month_year_id <' => $month_year_joined,
						'CourseMapping.batch_id' => $batch_id, 'CourseMapping.program_id' => $program_id, 'CourseMapping.indicator' => 0
				),
				'fields' => array('MAX(CourseMapping.month_year_id) AS max_my_id', 'MAX(CourseMapping.semester_id) AS max_sem_id'),
				'contain' => false
		));
		//pr($maxMYResults);
		$max_month_year_id = $maxMYResults[0][0]['max_my_id'];
		$max_semester_id = $maxMYResults[0][0]['max_sem_id'];
	
		//$my = $this->MonthYear->find('all')
		$monthYears = $this->MonthYear->find('all', array(
				'fields' => array('MonthYear.id','MonthYear.year','MonthYear.month_id','Month.month_name'),
				'conditions' => array('MonthYear.id >'=>$max_month_year_id),
				'order' => array('MonthYear.id')));
		$monthyears=array();
			
		foreach ($monthYears as $key => $value) {
			$monthyears[$value['MonthYear']['id']]=$value['Month']['month_name']."-".$value['MonthYear']['year'];
		}
		return $monthyears;
		/* $stuResults = $this->retrieveStudentInfo($regNum);
			$this->layout=false;
			$this->set(compact('max_month_year_id', 'max_semester_id', 'monthyears', 'stuResults')); */
	
	}
	
	public function getSemesters($batch_id, $program_id, $semester_joined, $semester_to_add=NULL) {
	
		$semesters = $this->Semester->find('list', array(
				'fields' => array('Semester.id'),
				'conditions' => array('Semester.id >='=>$semester_joined),
				'order' => array('Semester.id'))
				);
		return $semesters;
		/* $new_semester = $semester_joined + $semester_to_add - 1;
			$this->layout=false;
			$this->set(compact('semesters', 'new_semester')); */
	
	}
	
	public function arrearIndividualUser($month_year_id, $reg_no) {
		$this->set('month_year_id', $month_year_id);
		$student_id = $this->Student->getStudentId($reg_no);
		if (!empty($student_id)) {
			$result = $this->StudentMark->query("
			SELECT sm.id, sm.course_mapping_id, sm.month_year_id, c.course_code, s.registration_number, c.course_type_id, 
			cm.month_year_id AS cm_month_year_id, cm.semester_id, c.id, cm.batch_id, cm.program_id
			FROM student_marks sm
			JOIN students s ON sm.student_id = s.id
			JOIN course_mappings cm ON sm.course_mapping_id = cm.id
			JOIN courses c ON cm.course_id = c.id
			JOIN batches b ON b.id = cm.batch_id
			WHERE sm.id IN (
			SELECT max( id ) FROM student_marks sm1 WHERE sm.student_id = sm1.student_id AND sm1.month_year_id <=$month_year_id
			GROUP BY course_mapping_id, sm1.student_id ORDER BY id DESC)
			AND ((sm.status = 'Fail' AND sm.revaluation_status =0) OR (sm.final_status = 'Fail' AND sm.revaluation_status =1))
			AND sm.month_year_id <=$month_year_id AND s.id =$student_id AND s.discontinued_status =0 
			ORDER BY sm.course_mapping_id ASC");
			//pr($result);
			//AND (((sm.status = 'Fail' AND sm.revaluation_status =0) OR (sm.final_status = 'Fail' AND sm.revaluation_status =1))
			//OR ((sm.status = 'Pass' AND sm.revaluation_status =0) OR (sm.final_status = 'Pass' AND sm.revaluation_status =1)))
			$cm_id_array = array();
			
			foreach ($result as $key => $value) {
				$batch_id = $value['cm']['batch_id'];
				$program_id = $value['cm']['program_id'];
				//echo $batch_id." ".$program_id;
				$cm_month_year_id = $value['cm']['cm_month_year_id'];
				$monthYearAndSemId = $this->maxMonthYearAndSemesterId($batch_id, $program_id);
				//pr($monthYearAndSemId);
				//echo $cm_month_year_id." ".$monthYearAndSemId['month_year_id'];
				if ($cm_month_year_id != $monthYearAndSemId['month_year_id'] && $cm_month_year_id!=$month_year_id) {
					$cm_id_array[$value['sm']['course_mapping_id']] = $value['c']['course_type_id'];
				}
				else if ($month_year_id > $monthYearAndSemId['month_year_id']) {
					//echo $cm_month_year_id." ".$monthYearAndSemId['month_year_id'];
					$cm_id_array[$value['sm']['course_mapping_id']] = $value['c']['course_type_id'];
				}
			}
			//pr($cm_id_array);
			
			$results = $this->Student->find('all', array(
					'fields' => array('Student.id'),
					'conditions' => array('Student.id'=>$student_id, 'Student.discontinued_status' => 0),
					'contain'=>array(
							'GrossAttendance' => array(
									'fields'=>array('GrossAttendance.id', 'GrossAttendance.percentage', 'GrossAttendance.month_year_id'),
									'conditions'=>array('GrossAttendance.student_id'=>$student_id, 'GrossAttendance.month_year_id'=>$month_year_id),
							),
							'CourseStudentMapping'=> array(
									'conditions'=>array('CourseStudentMapping.indicator'=>0, 'CourseStudentMapping.student_id'=>$student_id,
											'CourseStudentMapping.course_mapping_id'=>array_keys($cm_id_array)
									),
									'fields'=>array('CourseStudentMapping.id', 'CourseStudentMapping.type', 'CourseStudentMapping.new_semester_id'),
									'CourseMapping'=> array(
											'conditions'=>array('CourseMapping.indicator'=>0,
													'NOT' => array('CourseMapping.id' => NULL)
											),
											'fields'=>array('CourseMapping.id', 'CourseMapping.month_year_id'),
											'Course'=>array(
													'fields'=>array('Course.course_max_marks', 'Course.min_cae_mark', 'Course.max_cae_mark',
															'Course.min_ese_mark', 'Course.max_ese_mark', 'Course.total_min_pass',
															'Course.course_type_id', 'Course.course_code'
													)
											),
											'Cae'=> array(
													'conditions'=>array('Cae.indicator'=>0,),
													'fields'=>array('Cae.id'),
													'ContinuousAssessmentExam'=> array(
															'fields'=>array('ContinuousAssessmentExam.id', 'ContinuousAssessmentExam.marks',
																	'ContinuousAssessmentExam.student_id'
															),
															'conditions'=>array('ContinuousAssessmentExam.student_id'=>$student_id),
													)
											),
											'InternalExam'=> array(
													'conditions'=>array('InternalExam.student_id'=>$student_id,),
													'fields'=>array('InternalExam.id', 'InternalExam.marks', 'InternalExam.moderation_operator',
															'InternalExam.moderation_marks'
													),
													'order'=>array('InternalExam.month_year_id DESC'),
													'limit'=>1
											),
											'EndSemesterExam'=> array(
													'conditions'=>array('EndSemesterExam.student_id'=>$student_id, ), //'EndSemesterExam.month_year_id'=>$month_year_id
													'fields'=>array('EndSemesterExam.id', 'EndSemesterExam.marks',
															'EndSemesterExam.moderation_operator', 'EndSemesterExam.moderation_marks'),
													'order'=>array('EndSemesterExam.month_year_id DESC'),
													'limit'=>1
											),
											'RevaluationExam'=> array(
													'conditions'=>array('RevaluationExam.student_id'=>$student_id, 'RevaluationExam.month_year_id'=>$month_year_id),
													'fields'=>array('RevaluationExam.id', 'RevaluationExam.marks',
															'RevaluationExam.reval_moderation_operator', 'RevaluationExam.reval_moderation_marks'),
													'order'=>array('RevaluationExam.month_year_id DESC'),
													'limit'=>1
											),
											'CaePractical'=> array(
													'conditions'=>array('CaePractical.indicator'=>0,),
													'fields'=>array('CaePractical.id'),
													'InternalPractical'=> array(
															'fields'=>array('InternalPractical.id', 'InternalPractical.marks'),
															'conditions'=>array('InternalPractical.student_id'=>$student_id),
															'order'=>array('InternalPractical.month_year_id DESC'),
															'limit'=>1
													)
											),
											'EsePractical'=> array(
													'conditions'=>array('EsePractical.indicator'=>0,),
													'fields'=>array('EsePractical.id'),
													'Practical'=> array(
															'fields'=>array('Practical.id', 'Practical.marks', 'Practical.moderation_operator',
																	'Practical.moderation_marks'),
															'conditions'=>array('Practical.student_id'=>$student_id, ), //'Practical.month_year_id'=>$month_year_id
															'order'=>array('Practical.month_year_id DESC'),
															'limit'=>1
													)
											),
											'CaeProject'=> array(
													'conditions'=>array('CaeProject.indicator'=>0,),
													'fields'=>array('CaeProject.id', 'CaeProject.marks'),
													'ProjectReview'=> array(
															'fields'=>array('ProjectReview.id', 'ProjectReview.marks'),
															'conditions'=>array('ProjectReview.student_id'=>$student_id),
													)
											),
											'EseProject'=> array(
													'conditions'=>array('EseProject.indicator'=>0,),
													'fields'=>array('EseProject.id'),
													'ProjectViva'=> array(
															'fields'=>array('ProjectViva.id', 'ProjectViva.marks'),
															'conditions'=>array('ProjectViva.student_id'=>$student_id,), // 'ProjectViva.month_year_id'=>$month_year_id
													)
											),
											'InternalProject'=> array(
													'conditions'=>array('InternalProject.student_id'=>$student_id),
													'fields'=>array('InternalProject.id', 'InternalProject.marks', 'InternalProject.moderation_operator',
															'InternalProject.moderation_marks'
													),
													'order'=>array('InternalProject.month_year_id DESC'),
													'limit'=>1
											),
											'CaePt'=> array(
													'conditions'=>array('CaePt.indicator'=>0),
													'fields'=>array('CaePt.id'),
													'ProfessionalTraining'=> array(
															'fields'=>array('ProfessionalTraining.id', 'ProfessionalTraining.marks',
																	'ProfessionalTraining.student_id', 'ProfessionalTraining.month_year_id'
															),
															'conditions'=>array('ProfessionalTraining.student_id'=>$student_id),
													)
											),
											'StudentMark'=> array(
													'conditions'=>array('StudentMark.student_id'=>$student_id),
													'fields'=>array('StudentMark.id', 'StudentMark.marks', 'StudentMark.status',
															'StudentMark.revaluation_status', 'StudentMark.final_marks', 'StudentMark.final_status',
															'StudentMark.month_year_id'
													),
													'order'=>array('StudentMark.month_year_id DESC'),
													'limit'=>1
											),
									)
							)
					)
			));
			//pr($results);
			$this->set(compact('results', 'student_id'));
			$this->layout=false;
		}
	}
	
	public function modifyIndividualUser($month_year_id, $reg_no) {
    $student_id = $this->Student->getStudentId($reg_no);
    if (!empty($student_id)) {
      $results = $this->Student->find('all', array(
          'fields' => array('Student.id'),
          'conditions' => array('Student.id'=>$student_id, 'Student.discontinued_status' => 0),
          'contain'=>array(
              'GrossAttendance' => array(
                  'fields'=>array('GrossAttendance.id', 'GrossAttendance.percentage', 'GrossAttendance.month_year_id'),
                  'conditions'=>array('GrossAttendance.student_id'=>$student_id, 'GrossAttendance.month_year_id'=>$month_year_id),
              ),
              'CourseStudentMapping'=> array(
                'conditions'=>array('CourseStudentMapping.indicator'=>0, 'CourseStudentMapping.student_id'=>$student_id),
                'fields'=>array('CourseStudentMapping.id', 'CourseStudentMapping.type', 'CourseStudentMapping.new_semester_id'),
                'CourseMapping'=> array(
                  'conditions'=>array('CourseMapping.indicator'=>0, 'CourseMapping.month_year_id'=>$month_year_id,
                    'NOT' => array('CourseMapping.id' => NULL)
                  ),
                  'fields'=>array('CourseMapping.id', 'CourseMapping.month_year_id'),
                  'Course'=>array(
                    'fields'=>array('Course.course_max_marks', 'Course.min_cae_mark', 'Course.max_cae_mark',
                        'Course.min_ese_mark', 'Course.max_ese_mark', 'Course.total_min_pass',
                        'Course.course_type_id', 'Course.course_code'
                    )
                  ),
                  'Cae'=> array(
                    'conditions'=>array('Cae.indicator'=>0, 'Cae.month_year_id'=>$month_year_id),
                    'fields'=>array('Cae.id'),
                    'ContinuousAssessmentExam'=> array(
                        'fields'=>array('ContinuousAssessmentExam.id', 'ContinuousAssessmentExam.marks',
                            'ContinuousAssessmentExam.student_id'
                        ),
                        'conditions'=>array('ContinuousAssessmentExam.student_id'=>$student_id),
                    )
                  ),
                  'InternalExam'=> array(
                    'conditions'=>array('InternalExam.student_id'=>$student_id, 'InternalExam.month_year_id'=>$month_year_id),
                    'fields'=>array('InternalExam.id', 'InternalExam.marks', 'InternalExam.moderation_operator',
                        'InternalExam.moderation_marks'
                    ),
                  ),
                  'EndSemesterExam'=> array(
                    'conditions'=>array('EndSemesterExam.student_id'=>$student_id, 'EndSemesterExam.month_year_id'=>$month_year_id),
                    'fields'=>array('EndSemesterExam.id', 'EndSemesterExam.marks',
                        'EndSemesterExam.moderation_operator', 'EndSemesterExam.moderation_marks'),
                  ),
                		'RevaluationExam'=> array(
                				'conditions'=>array('RevaluationExam.student_id'=>$student_id, 'RevaluationExam.month_year_id'=>$month_year_id),
                				'fields'=>array('RevaluationExam.id', 'RevaluationExam.marks', 'RevaluationExam.revaluation_marks',  
                						'RevaluationExam.reval_moderation_operator', 'RevaluationExam.reval_moderation_marks'),
                		),
                  'CaePractical'=> array(
                    'conditions'=>array('CaePractical.indicator'=>0, 'CaePractical.month_year_id'=>$month_year_id),
                    'fields'=>array('CaePractical.id'),
                    'InternalPractical'=> array(
                        'fields'=>array('InternalPractical.id', 'InternalPractical.marks'),
                        'conditions'=>array('InternalPractical.student_id'=>$student_id),
                    )
                  ),
                  'EsePractical'=> array(
                    'conditions'=>array('EsePractical.indicator'=>0, 'EsePractical.month_year_id'=>$month_year_id),
					'fields'=>array('EsePractical.id'),
                    'Practical'=> array(
                        'fields'=>array('Practical.id', 'Practical.marks', 'Practical.moderation_operator',
                            'Practical.moderation_marks'),
                        'conditions'=>array('Practical.student_id'=>$student_id),
                    )
                  ),
                	'CaeProject'=> array(
                		'conditions'=>array('CaeProject.indicator'=>0, 'CaeProject.month_year_id'=>$month_year_id),
                			'fields'=>array('CaeProject.id', 'CaeProject.marks'),
                			'ProjectReview'=> array(
                				'fields'=>array('ProjectReview.id', 'ProjectReview.marks'),
                				'conditions'=>array('ProjectReview.student_id'=>$student_id),
                			)
                	),
                	'EseProject'=> array(
                		'conditions'=>array('EseProject.indicator'=>0, 'EseProject.month_year_id'=>$month_year_id),
                			'fields'=>array('EseProject.id'),
                			'ProjectViva'=> array(
                				'fields'=>array('ProjectViva.id', 'ProjectViva.marks'),
                				'conditions'=>array('ProjectViva.student_id'=>$student_id),
                			)
                	),
                	'InternalProject'=> array(
                		'conditions'=>array('InternalProject.student_id'=>$student_id, 'InternalProject.month_year_id'=>$month_year_id),
                		'fields'=>array('InternalProject.id', 'InternalProject.marks', 'InternalProject.moderation_operator',
                				'InternalProject.moderation_marks'
               			),
               		),
                	'CaePt'=> array(
                		'conditions'=>array('CaePt.indicator'=>0),
                		'fields'=>array('CaePt.id'),
                		'ProfessionalTraining'=> array(
                				'fields'=>array('ProfessionalTraining.id', 'ProfessionalTraining.marks',
                						'ProfessionalTraining.student_id'
                				),
                				'conditions'=>array('ProfessionalTraining.student_id'=>$student_id),
                		)
                	),
                  'StudentMark'=> array(
                    'conditions'=>array('StudentMark.student_id'=>$student_id, 'StudentMark.month_year_id'=>$month_year_id),
                    'fields'=>array('StudentMark.id', 'StudentMark.marks', 'StudentMark.status',
                        'StudentMark.revaluation_status', 'StudentMark.final_marks', 'StudentMark.final_status'
                    ),
                  ),
                )
              )
          )
      ));
      $this->set(compact('results', 'student_id'));
    }
    $this->layout=false;
  }	
	
	public function editCaeMarks() {
		$this->layout = 'ajax';
		$arr = array();
	
		$cm_id = $_POST["cm_id"];
		//$course_type_id = $_POST["course_type_id"];
		
		$cm_id_array = array();
		$cm_id_array[$cm_id] = $cm_id;
		$month_year_id = $_POST["month_year_id"];
	
		$course_details = $this->CourseMapping->retrieveCourseDetails($cm_id_array, $month_year_id);
		$max_cae_mark = $course_details[$cm_id]['max_cae_mark'];
		
		$course_type_id = $_POST['data']['course_type_id'];
		
		SWITCH ($course_type_id) {
			CASE 1:
				$cae_model = "InternalExam";
				$ese_model = $_POST['data']['model'][$cm_id];
				
				if($ese_model == "EndSemesterExam") $reval_status = 0;
				else if($ese_model == "RevaluationExam") $reval_status = 1;
				
				$assessment_type = $_POST["ass_option"];
				$student_id = $_POST["student_id"];
				
				$student_mark_id = $_POST['data']['student_mark_id'][$cm_id];
				
				$new_cae_mark = $_POST['data']['internal_exam_marks'][$cm_id];
				
				$ese_marks = $_POST['data']['ese_marks'][$cm_id];
				$new_ese_mark = $_POST['data']['ese_new_marks'][$cm_id];
				
				if ($assessment_type == 'cae') {
					$new_cae_mark = $this->manipulateTheoryCae($_POST, $cae_model);
				}
				if ($assessment_type == 'ese') {
					$new_ese_mark = $this->manipulateTheoryEse($_POST, $ese_model);
				} 
				$total = $new_cae_mark+$new_ese_mark;
				
				$result = $this->statusAndGradeCalculation($_POST, $new_cae_mark, $new_ese_mark, $total);
				
				$student_mark_update = $this->updateStudentMark($student_mark_id, $total, $result['status'], $result['grade_point'], $result['grade'], $reval_status);
				
				break;
			CASE 2:
			CASE 6:
				$cae_model = "InternalPractical";
				$ese_model = "Practical";
				$reval_status = 0;
				$assessment_type = $_POST["ass_option"];
				
				$student_mark_id = $_POST['data']['student_mark_id'][$cm_id];
				
				$new_cae_mark = $_POST['data']['cae'][$cm_id];
				$new_ese_mark = $_POST['data']['ese'][$cm_id];
				
				if ($assessment_type == 'cae') {
					$cae_id = $_POST['data']['cae_id'][$cm_id];
					$old_cae_mark = $_POST['data']['cae_old'][$cm_id];
					$new_cae_mark = $_POST['data']['cae'][$cm_id];
					$internal_update = $this->updateMark($cae_id, $new_cae_mark, $cae_model);
				}
				if ($assessment_type == 'ese') {
					$ese_id = $_POST['data']['ese_id'][$cm_id];
					$old_ese_mark = $_POST['data']['ese_old'][$cm_id];
					$new_ese_mark = $_POST['data']['ese'][$cm_id];
					$ese_update = $this->updateMark($ese_id, $new_ese_mark, $ese_model);
				}
				
				$total = $new_cae_mark+$new_ese_mark;
				
				$result = $this->statusAndGradeCalculation($_POST, $new_cae_mark, $new_ese_mark, $total);
				
				$student_mark_update = $this->updateStudentMark($student_mark_id, $total, $result['status'], $result['grade_point'], $result['grade'], $reval_status);
				break;
			CASE 3:
				$cae_model = "InternalExam";
				$ese_model = "Practical";
				$assessment_type = $_POST["ass_option"];
				$reval_status = 0;
				
				$student_mark_id = $_POST['data']['student_mark_id'][$cm_id];
				
				$new_cae_mark = $_POST['data']['internal_exam_marks'][$cm_id];
				$new_ese_mark = $_POST['data']['ese'][$cm_id];
				if ($assessment_type == 'cae') {
					$new_cae_mark = $this->manipulateTheoryCae($_POST, $cae_model);
				}
				if ($assessment_type == 'ese') {
					$ese_id = $_POST['data']['ese_id'][$cm_id];
					$old_ese_mark = $_POST['data']['ese_old'][$cm_id];
					$new_ese_mark = $_POST['data']['ese'][$cm_id];
					$ese_update = $this->updateMark($ese_id, $new_ese_mark, $ese_model);
				}
				
				$total = $new_cae_mark+$new_ese_mark;
				
				$result = $this->statusAndGradeCalculation($_POST, $new_cae_mark, $new_ese_mark, $total);
				
				$student_mark_update = $this->updateStudentMark($student_mark_id, $total, $result['status'], $result['grade_point'], $result['grade'], $reval_status);
				break;
			CASE 4:
				$cae_model = "InternalProject";
				$ese_model = "ProjectViva";
				
				$reval_status = 0;
				$assessment_type = $_POST["ass_option"];
				
				$new_cae_mark = $_POST['data']['internal_exam_marks'][$cm_id];
				$new_ese_mark = $_POST['data']['ese'][$cm_id];
				//echo $new_ese_mark;
				$student_mark_id = $_POST['data']['student_mark_id'][$cm_id];
				$cae_id = $_POST['data']['internal_exam_id'][$cm_id];
				$ese_id = $_POST['data']['ese_id'][$cm_id];
				
				if ($assessment_type == 'cae') {
					$new_cae_mark = 0;
					$no_of_caes = $_POST['data']['no_of_caes'][$cm_id];
					//echo "caes : ".$no_of_caes;
					$cae_details = $_POST['data']['cae_details'];
					//pr($cae_details);
					for ($i=0; $i<$no_of_caes; $i++) {
						$new_cae_mark += $cae_details['cont_ass_marks_new'][$cm_id][$i];
						$internal_update = $this->updateMark($cae_details['cont_ass_id'][$cm_id][$i], $cae_details['cont_ass_marks_new'][$cm_id][$i], "ProjectReview");
					}
					//echo "Updated value : ".$updated_cae_mark;
					$internal_update = $this->updateMark($cae_id, $new_cae_mark, $cae_model);
				
				}
				if ($assessment_type == 'ese') {
					$ese_update = $this->updateMark($ese_id, $new_ese_mark, $ese_model);
				}
				
				$total = $new_cae_mark+$new_ese_mark;
				
				$result = $this->statusAndGradeCalculation($_POST, $new_cae_mark, $new_ese_mark, $total);
				
				$student_mark_update = $this->updateStudentMark($student_mark_id, $total, $result['status'], $result['grade_point'], $result['grade'], $reval_status);
				break;
			case 5:
				//pr($_POST);
				
				$cae_model = "ProfessionalTraining";
				$ese_model = "";
				
				$reval_status = 0;
				$assessment_type = $_POST["ass_option"];
				
				$new_cae_mark = $_POST['data']['cae'][$cm_id];
				$new_ese_mark = "";
				
				$student_mark_id = $_POST['data']['student_mark_id'][$cm_id];
				//$cae_id = $_POST['data']['internal_exam_id'][$cm_id];
				//$ese_id = "";
				
				if ($assessment_type == 'cae') {
					$cae_id = $_POST['data']['cae_id'][$cm_id];
					$old_cae_mark = $_POST['data']['cae_old'][$cm_id];
					$new_cae_mark = $_POST['data']['cae'][$cm_id];
					$internal_update = $this->updateMark($cae_id, $new_cae_mark, $cae_model);
				
				}
				/* if ($assessment_type == 'ese') {
					$ese_update = $this->updateMark($ese_id, $new_ese_mark, $ese_model);
				} */
				
				$total = $new_cae_mark+$new_ese_mark;
				//echo $total;
				$result = $this->statusAndGradeCalculation($_POST, $new_cae_mark, $new_ese_mark, $total);
				//pr($result);
				
				$student_mark_update = $this->updateStudentMark($student_mark_id, $total, $result['status'], $result['grade_point'], $result['grade'], $reval_status);
				break;
		}
	
		$arr['internal']=$new_cae_mark;
		$arr['ese']=$new_ese_mark;
		$arr['total']=$total;
		$arr['result']=$result['status'];
		$arr['cm_id']=$cm_id;
	
		$this->set(compact('arr'));
	
	}
	
	public function arrearEditIndividualMarks() {
		$this->layout = 'ajax';
		$arr = array();
		$error_msg = "";
		$cm_id = $_POST["cm_id"];
		//$course_type_id = $_POST["course_type_id"];
	
		$cm_id_array = array();
		$cm_id_array[$cm_id] = $cm_id;
		$month_year_id = $_POST["month_year_id"];
		$exam_type = $_POST["exam_type"];
		
		$course_details = $this->CourseMapping->retrieveCourseDetails($cm_id_array, $month_year_id);
		$max_cae_mark = $course_details[$cm_id]['max_cae_mark'];
	
		$course_type_id = $_POST['data']['course_type_id'];
	
		SWITCH ($course_type_id) {
			CASE 1:
				$cae_model = "InternalExam";
				$ese_model = $_POST['data']['model'][$cm_id];
	
				if($ese_model == "EndSemesterExam") $reval_status = 0;
				else if($ese_model == "RevaluationExam") $reval_status = 1;
	
				$assessment_type = $_POST["ass_option"];
				$student_id = $_POST["student_id"];
	
				$student_mark_id = $_POST['data']['student_mark_id'][$cm_id];
	
				$new_cae_mark = $_POST['data']['internal_exam_marks'][$cm_id];
	 
				$ese_marks = $_POST['data']['ese_marks'][$cm_id];
				$old_ese_mark = $_POST['data']['ese_marks'][$cm_id];
				$new_ese_mark = $_POST['data']['ese_new_marks'][$cm_id];
				//echo $old_ese_mark;
				if ($assessment_type == 'cae') {
					$new_cae_mark = $this->manipulateTheoryCae($_POST, $cae_model);
				}
				if ($assessment_type == 'ese' && $exam_type=='R') {
					$new_ese_mark = $this->manipulateTheoryEse($_POST, $ese_model);
				}
				else if ($assessment_type == 'ese' && $exam_type=='A' && $old_ese_mark!='A') {
					//echo "condition two";
					$new_ese_mark = $this->manipulateTheoryEse($_POST, $ese_model);
				}
				else if ($assessment_type == 'ese' && $exam_type=='A' && $old_ese_mark=='A') {
					//echo "condition three";
					$new_ese_mark = $old_ese_mark;
					$error_msg = "Can\\'t enter marks for Absentee";
				}
				if($old_ese_mark == 'A' || $old_ese_mark == 'aaa' || $old_ese_mark == 'AAA' || $old_ese_mark == 'a') {
					$total = $new_cae_mark;
				} else if ($old_ese_mark > 0) {
					$total = $new_cae_mark+$new_ese_mark;
				}
	//echo $total;
				$result = $this->statusAndGradeCalculation($_POST, $new_cae_mark, $new_ese_mark, $total);
	//pr($result);
	//echo $student_mark_id;
				$student_mark_update = $this->updateStudentMark($student_mark_id, $total, $result['status'], $result['grade_point'], $result['grade'], $reval_status);
	
				break;
			CASE 2:
			CASE 6:
				$cae_model = "InternalPractical";
				$ese_model = "Practical";
				$reval_status = 0;
				$assessment_type = $_POST["ass_option"];
	
				$student_mark_id = $_POST['data']['student_mark_id'][$cm_id];
	
				$old_ese_mark = $_POST['data']['ese_marks'][$cm_id];
				
				$new_cae_mark = $_POST['data']['cae'][$cm_id];
				$new_ese_mark = $_POST['data']['ese_new_marks'][$cm_id];
	
				$student_id = $_POST['student_id'];
				
				if ($assessment_type == 'cae') { 
					
					$caeExists = $this->findPracticalCaesExistsForCurrentMonthYear($cm_id, $month_year_id, $student_id);
					if (!empty($caeExists['InternalPractical'])) {
						$cae_id = $_POST['data']['cae_id'][$cm_id];
						$old_cae_mark = $_POST['data']['cae_old'][$cm_id];
						$new_cae_mark = $_POST['data']['cae'][$cm_id];
						$internal_update = $this->updateMark($cae_id, $new_cae_mark, $cae_model);
					} else {
						$cae_practical_id = $caeExists['CaePractical']['id'];
						$internal_update = $this->insertIntoInternal($new_cae_mark, $cae_model, $month_year_id, $student_id, $cae_practical_id);
					}
				}
				/* if ($assessment_type == 'ese') {
					$ese_id = $_POST['data']['ese_id'][$cm_id];
					$new_ese_mark = $_POST['data']['ese_new_marks'][$cm_id];
					$ese_update = $this->updateMark($ese_id, $new_ese_mark, $ese_model);
				} */
				if ($assessment_type == 'ese' && $exam_type=='R') {
					$new_ese_mark = $this->manipulateTheoryEse($_POST, $ese_model);
				}
				else if ($assessment_type == 'ese' && $exam_type=='A' && $old_ese_mark != 'A'){
					//echo "condition two";
					$new_ese_mark = $this->manipulateTheoryEse($_POST, $ese_model);
				}
				else if ($assessment_type == 'ese' && $exam_type=='A' && $old_ese_mark=='A') {
					//echo "condition three";
					$error_msg = "Can\\'t enter marks for Absentee";
				}
				if($old_ese_mark == 'A' || $old_ese_mark == 'aaa' || $old_ese_mark == 'AAA' || $old_ese_mark == 'a') {
					$total = $new_cae_mark;
				} else if ($old_ese_mark > 0) {
					$total = $new_cae_mark+$new_ese_mark;
				} 
				//$total = $new_cae_mark+$new_ese_mark;

				$result = $this->statusAndGradeCalculation($_POST, $new_cae_mark, $new_ese_mark, $total);
	
				$student_mark_update = $this->updateStudentMark($student_mark_id, $total, $result['status'], $result['grade_point'], $result['grade'], $reval_status);
				break;
			CASE 3:
				$cae_model = "InternalExam";
				$ese_model = "Practical";
				$assessment_type = $_POST["ass_option"];
				$reval_status = 0;
	
				$student_mark_id = $_POST['data']['student_mark_id'][$cm_id];
	
				$ese_id = $_POST['data']['ese_id'][$cm_id];
				$old_ese_mark = $_POST['data']['ese_marks'][$cm_id];
				
				$new_cae_mark = $_POST['data']['internal_exam_marks'][$cm_id];
				$new_ese_mark = $_POST['data']['ese_new_marks'][$cm_id];
				if ($assessment_type == 'cae') {
					$new_cae_mark = $this->manipulateTheoryCae($_POST, $cae_model);
				}
				if ($assessment_type == 'ese' && $exam_type=='R') {
					$new_ese_mark = $_POST['data']['ese'][$cm_id];
					$ese_update = $this->updateMark($ese_id, $new_ese_mark, $ese_model);
				}
				else if ($assessment_type == 'ese' && $exam_type=='A' && $old_ese_mark != 'A'){
					//echo "condition two";
					$new_ese_mark = $this->manipulateTheoryEse($_POST, $ese_model);
				}
				else if ($assessment_type == 'ese' && $exam_type=='A' && $old_ese_mark=='A') {
					//echo "condition three";
					$error_msg = "Can\'t enter marks for Absentee";
				}
				
				if($old_ese_mark == 'A' || $old_ese_mark == 'aaa' || $old_ese_mark == 'AAA' || $old_ese_mark == 'a') {
					$total = $new_cae_mark;
				} else if ($old_ese_mark > 0) {
					$total = $new_cae_mark+$new_ese_mark;
				} 
	//echo $total;
				$result = $this->statusAndGradeCalculation($_POST, $new_cae_mark, $new_ese_mark, $total);
				
				if ($old_ese_mark != 'A') {
					$student_mark_update = $this->updateStudentMark($student_mark_id, $total, $result['status'], $result['grade_point'], $result['grade'], $reval_status);
				}
				break;
			CASE 4:
				$cae_model = "InternalProject";
				$ese_model = "ProjectViva";
				$cae_individual_model = "ProjectReview";
				
				$reval_status = 0;
				$assessment_type = $_POST["ass_option"];
	
				$new_cae_mark = $_POST['data']['internal_exam_marks'][$cm_id];
				$new_ese_mark = $_POST['data']['ese_new_marks'][$cm_id];
				$old_ese_mark = $_POST['data']['ese_marks'][$cm_id];
				
				$student_mark_id = $_POST['data']['student_mark_id'][$cm_id];
				$cae_id = $_POST['data']['internal_exam_id'][$cm_id];
				$ese_id = $_POST['data']['ese_id'][$cm_id];
				$student_id = $_POST['student_id'];
				
				if ($assessment_type == 'cae') {
					
					$new_cae_mark = 0;
					$no_of_caes = $_POST['data']['no_of_caes'][$cm_id];
					//echo "caes : ".$no_of_caes;
					$cae_details = $_POST['data']['cae_details'];
					//pr($cae_details);
					for ($i=0; $i<$no_of_caes; $i++) {
						$new_cae_mark += $cae_details['cont_ass_marks_new'][$cm_id][$i];
						$internal_update = $this->updateMark($cae_details['cont_ass_id'][$cm_id][$i], $cae_details['cont_ass_marks_new'][$cm_id][$i], "ProjectReview");
					}
					//echo "Updated value : ".$updated_cae_mark;
					$caeExists = $this->findProjectCaesExistsForCurrentMonthYear($cm_id, $month_year_id, $student_id);
					//pr($caeExists);
					
					if (!empty($caeExists)) {
						$internal_update = $this->updateMark($cae_id, $new_cae_mark, $cae_model);
					} else {
						$internal_update = $this->insertIntoInternal($new_cae_mark, $cae_model, $month_year_id, $student_id, $cm_id);
					}
					
					//$internal_update = $this->updateMark($cae_id, $new_cae_mark, $cae_model);
	
				}
				if ($assessment_type == 'ese') {
					$ese_update = $this->updateMark($ese_id, $new_ese_mark, $ese_model);
				}
				if ($assessment_type == 'ese' && $exam_type=='R') {
					//$new_ese_mark = $_POST['data']['ese'][$cm_id];
					$ese_update = $this->updateMark($ese_id, $new_ese_mark, $ese_model);
				}
				else if ($assessment_type == 'ese' && $exam_type=='A' && $old_ese_mark=='A') {
					$error_msg = "Can\'t enter marks for Absentee";
				}
				
				if($old_ese_mark == 'A' || $old_ese_mark == 'aaa' || $old_ese_mark == 'AAA' || $old_ese_mark == 'a') {
					$total = $new_cae_mark;
				} else if ($old_ese_mark > 0) {
					$total = $new_cae_mark+$new_ese_mark;
				}
				
			//	$total = $new_cae_mark+$new_ese_mark;
	
				$result = $this->statusAndGradeCalculation($_POST, $new_cae_mark, $new_ese_mark, $total);
	
				$student_mark_update = $this->updateStudentMark($student_mark_id, $total, $result['status'], $result['grade_point'], $result['grade'], $reval_status);
				break;
		}
	
		$arr['internal']=$new_cae_mark;
		$arr['ese']=$new_ese_mark;
		$arr['total']=$total;
		$arr['result']=$result['status'];
		$arr['cm_id']=$cm_id;
		$arr['error']=$error_msg;
		$arr['old_ese_mark']=$old_ese_mark;
	//pr($arr);
	
		$this->set(compact('arr'));
		$this->render('edit_cae_marks'); 
	}
	
	public function statusAndGradeCalculation($postData, $new_cae_mark, $new_ese_mark, $total) {
		
		$result = array();
		$cm_id = $postData["cm_id"];
		//$course_type_id = $_POST["course_type_id"];
		
		$cm_id_array = array();
		$cm_id_array[$cm_id] = $cm_id;
		
		$month_year_id = $postData["month_year_id"];
		$course_details = $this->CourseMapping->retrieveCourseDetails($cm_id_array, $month_year_id);
		$max_cae_mark = $course_details[$cm_id]['max_cae_mark'];
		
		$min_cae_mark = $course_details[$cm_id]['min_cae_mark'];
		$min_ese_mark = $course_details[$cm_id]['min_ese_mark'];
		$min_pass_mark = $course_details[$cm_id]['min_pass_mark'];
		
		if ($new_cae_mark >= $min_cae_mark) {
			if ($new_ese_mark >= $min_ese_mark) {
				if ($total >= $min_pass_mark) {
					$status = "Pass";
					$computed_mark = round(($total/$course_details[$cm_id]['course_max_marks'])*100);
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
		$result['status'] = $status;
		$result['grade_point'] = $grade_point;
		$result['grade'] = $grade;
		return $result;
	}
	
	public function manipulateTheoryEse($postData, $ese_model) {
		$cm_id = $postData["cm_id"];
		$ese_id = $postData['data']['ese_id'][$cm_id];

		$new_ese_mark = $postData['data']['ese_new_marks'][$cm_id];
		$ese_update = $this->updateMark($ese_id, $new_ese_mark, $ese_model);
		return $new_ese_mark;
	}
	
	public function findProjectCaesExistsForCurrentMonthYear($cm_id, $month_year_id, $student_id){
		$result = $this->InternalProject->find("first", array(
				'conditions'=>array('InternalProject.month_year_id'=>$month_year_id, 'InternalProject.course_mapping_id'=>$cm_id,
						'InternalProject.student_id'=>$student_id
				),
				'fields'=>array('InternalProject.id'),
				'recursive'=>0
		));
		return $result;
	}
	
	public function findPracticalCaesExistsForCurrentMonthYear($cm_id, $month_year_id, $student_id) {
		$result = $this->CaePractical->find("first", array(
				'conditions'=>array('CaePractical.course_mapping_id'=>$cm_id,),
				'fields'=>array('CaePractical.id'),
				'contain'=>array(
						'InternalPractical'=> array(
								'fields'=>array('InternalPractical.id', 'InternalPractical.marks'),
								'conditions'=>array('InternalPractical.student_id'=>$student_id, 
												'InternalPractical.month_year_id'=>$month_year_id),
						)
				)
		));
		return $result;
	}
	
	public function findIfCaesExistsForCurrentMonthYear($cm_id, $month_year_id, $student_id){
		$result = $this->InternalExam->find("first", array(
				'conditions'=>array('InternalExam.month_year_id'=>$month_year_id, 'InternalExam.course_mapping_id'=>$cm_id,
						'InternalExam.student_id'=>$student_id
				),
				'fields'=>array('InternalExam.id'),
				'recursive'=>0
		));
		if (isset($result) && count($result)>0) {
			return true;
		} else {
			return false;
		}
	}
	
	public function manipulateTheoryCae($postData, $cae_model) {
		//pr($postData);
		
		$cm_id = $postData["cm_id"];
		//$course_type_id = $_POST["course_type_id"];
		
		$cm_id_array = array();
		$cm_id_array[$cm_id] = $cm_id;
		
		$month_year_id = $postData["month_year_id"];
		
		$course_details = $this->CourseMapping->retrieveCourseDetails($cm_id_array, $month_year_id);
		$max_cae_mark = $course_details[$cm_id]['max_cae_mark'];
		$no_of_caes = $postData['data']['no_of_caes'][$cm_id];
		//echo "caes : ".$no_of_caes;
		$cae_details = $postData['data']['cae_details'];
		//pr($cae_details);
		
		$student_id = $_POST["student_id"];
		
		$caeExists = $this->findIfCaesExistsForCurrentMonthYear($cm_id, $month_year_id, $student_id);
		
		$cae_id = $_POST['data']['internal_exam_id'][$cm_id];
		
		$cae = $this->updateCae($no_of_caes, $cae_details, $cm_id);
		$tmp_cae_mark = $this->computeCae($cm_id, $student_id, $max_cae_mark);
		$attendance_data = $this->attendanceData($student_id, $cm_id, $month_year_id);
		$attendance_percent = $this->getAttendancePercentage($attendance_data);
		$attendance_mark = $this->calculateAttendance($attendance_percent);
		$new_cae_mark = $tmp_cae_mark + $attendance_mark;
		//echo $new_cae_mark;
		$ContinuousAssessmentExamsController = new ContinuousAssessmentExamsController;
		$modResult = $ContinuousAssessmentExamsController->moderateInternal($new_cae_mark);
		//pr($modResult);
		$new_cae_mark = $modResult['marks'];
		//echo $new_cae_mark;
		
		if ($caeExists) {
			$internal_update = $this->updateMark($cae_id, $new_cae_mark, $cae_model);
		} else {
			$internal_update = $this->insertIntoInternal($new_cae_mark, $cae_model, $month_year_id, $student_id, $cm_id);
		}
		
		return $new_cae_mark;
	}
	
	/* public function findProjectCaesExistsForCurrentMonthYear($cm_id, $month_year_id, $student_id) {
		$result = $this->CaeProject->find("first", array(
				'conditions'=>array('CaeProject.course_mapping_id'=>$cm_id,),
				'fields'=>array('CaeProject.id'),
				'contain'=>array(
						'ProjectReview'=> array(
								'fields'=>array('ProjectReview.id', 'ProjectReview.marks'),
								'conditions'=>array('ProjectReview.student_id'=>$student_id,
										'ProjectReview.month_year_id'=>$month_year_id),
						)
				)
		));
		return $result;
	} */
	
	public function batchTransferEditCaeMarks() {
		$this->layout = 'ajax';
		//$data = json_decode(file_get_contents('php://input'));
		//pr($data);
		//$postData = $this->request->input('json_decode', true);
		//pr($postData);
		$arr = array();
	//pr($_POST);
		$cm_id = $_POST["cm_id"];
		$cm_id_array = array();
		//echo $cm_id;
		
		$month_year_id = $_POST['data']['StudentMark']['month_year_id'][$cm_id];
		
		$cm_id_array[$cm_id] = $cm_id;
		//$course_details = $this->CourseMapping->retrieveCourseDetails($cm_id_array, $month_year_id);
		$course_details = $this->CourseMapping->query("
				SELECT CourseMapping.id , Course.course_code, Course.min_cae_mark, Course.min_ese_mark, Course.max_cae_mark, 
				Course.max_ese_mark, Course.total_min_pass, Course.course_max_marks, Course.max_ese_qp_mark, Course.course_name,
				Course.id FROM course_mappings AS CourseMapping LEFT JOIN courses AS Course ON (CourseMapping.course_id = Course.id)
				WHERE CourseMapping.id = $cm_id");
		
		//pr($course_details);
		
		
		$max_cae_mark = $course_details[0]['Course']['max_cae_mark'];
	
		$assessment_type = $_POST["ass_option"];
		$student_id = $_POST["student_id"];
		
		if (isset($_POST["btfou"]) && isset($_POST["uid"])) {
			if($_POST['data']['StudentMark']['btfou']==1) $type="BTFOU"; 
			else $type="N";
		}
		else {
			$type = "OBT";
		}
		$cae_marks = $_POST['data']['StudentMark']['cae_marks'][$cm_id];
		$ese_marks = $_POST['data']['StudentMark']['ese_marks'][$cm_id];
		
		$course_type_id = $_POST['data']['StudentMark']['course_type_id'][$cm_id];
		$caeId='-';
		$eseId='-';
		
		if ($course_type_id == 1) {
			$cae_model = "InternalExam";
			$ese_model = "EndSemesterExam";
		}
		else if ($course_type_id == 2) {
			$cae_model = "InternalPractical";
			$ese_model = "Practical";
			$caeExists = $this->CaePractical->find('all', array(
					'conditions' => array('CaePractical.course_mapping_id' => $cm_id, 'CaePractical.indicator'=>0),
					'fields' => array('CaePractical.id'),
					'order' => 'CaePractical.id ASC',
					'limit' => 1,
					'recursive' => 0
			));
			//pr($caeExists);
			if (isset($caeExists[0]['CaePractical']['id'])) {
				$caeId = $caeExists[0]['CaePractical']['id'];
			} else {
				$data=array();
				$data['CaePractical']['month_year_id'] = $month_year_id;
				$data['CaePractical']['course_mapping_id'] = $cm_id;
				$data['CaePractical']['assessment_type'] = "CAE";
				$data['CaePractical']['marks'] = $max_cae_mark;
				$data['CaePractical']['marks_status'] = "Not Entered";
				$data['CaePractical']['add_status'] = 1;
				$data['CaePractical']['approval_status'] = 1;
				$data['CaePractical']['indicator'] = 0;
				$data['CaePractical']['created_by'] = $this->Auth->user('id');
				$this->CaePractical->create();
				$this->CaePractical->save($data);
				$caeId = $this->CaePractical->getLastInsertID();
			}
			$eseExists = $this->EsePractical->find('all', array(
					'conditions' => array('EsePractical.course_mapping_id' => $cm_id, 'EsePractical.indicator'=>0),
					'fields' => array('EsePractical.id'),
					'order' => 'EsePractical.id ASC',
					'limit' => 1,
					'recursive' => 0
			));
			
			if (isset($eseExists[0]['EsePractical']['id'])) {
				$eseId = $eseExists[0]['EsePractical']['id'];
			} else {
				$data=array();
				$data['EsePractical']['month_year_id'] = $month_year_id;
				$data['EsePractical']['course_mapping_id'] = $cm_id;
				$data['EsePractical']['assessment_type'] = "ESE";
				$data['EsePractical']['marks'] = $ese_marks;
				$data['EsePractical']['marks_status'] = "Not Entered";
				$data['EsePractical']['add_status'] = 1;
				$data['EsePractical']['approval_status'] = 1;
				$data['EsePractical']['indicator'] = 0;
				$data['EsePractical']['created_by'] = $this->Auth->user('id');
				$this->EsePractical->create();
				$this->EsePractical->save($data);
				$eseId = $this->EsePractical->getLastInsertID();
			}
		}
		
		$marks = $_POST['total'];
		$status = $_POST['status'];
		
		if ($assessment_type == 'cae') {
			$internal_create = $this->insertMark($cae_marks, $cae_model, $cm_id, $student_id, $month_year_id, '', '', '', $course_type_id, $caeId, $assessment_type, $type);
			$total = $cae_marks+$ese_marks;
		}
		else if ($assessment_type == 'ese') {
			$internal_create = $this->insertMark($ese_marks, $ese_model, $cm_id, $student_id, $month_year_id, '', '', '', $course_type_id, $eseId, $assessment_type, $type);
			$total = $cae_marks+$ese_marks;
		}
		$total = $cae_marks+$ese_marks;
		
		$min_cae_mark = round($course_details[0]['Course']['max_cae_mark'] * $course_details[0]['Course']['min_cae_mark'] / 100);
		$min_ese_mark = round($course_details[0]['Course']['max_ese_mark'] * $course_details[0]['Course']['min_ese_mark'] / 100);
		$min_pass_mark = round($course_details[0]['Course']['course_max_marks'] * $course_details[0]['Course']['total_min_pass'] / 100);
		
/* 		$min_cae_mark = $course_details[0]['Course']['min_cae_mark'];
		$min_ese_mark = $course_details[0]['Course']['min_ese_mark'];
		$min_pass_mark = $course_details[0]['Course']['min_pass_mark'];
		 */
		if ($cae_marks >= $min_cae_mark) {
			if ($ese_marks >= $min_ese_mark) {
				if ($total >= $min_pass_mark) {
					$status = "Pass";
					$computed_mark = round(($total/$course_details[0]['Course']['course_max_marks'])*100);
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
	
		//$internal_create = $this->insertMark($total, "StudentMark", $cm_id, $student_id, $month_year_id, $status, $grade, $grade_point, $course_type_id, $eseId, $assessment_type);
		$studentMarkModel = "StudentMark";
		$internal_create = $this->insertIntoStudentMark($total, $cm_id, $student_id, $month_year_id, $status, $grade, $grade_point, $studentMarkModel);
		$arr['cae']=$cae_marks;
		$arr['ese']=$ese_marks;
		$arr['total']=$total;
		$arr['result']=$status;
		$arr['cm_id']=$cm_id;
		$this->set(compact('arr'));
	}
	
	public function updateStudentMark($student_mark_id, $total, $status, $grade_point, $grade, $reval_status) {
		//echo $student_mark_id." ".$total." ". $status." ".$grade_point." ".$grade." ".$reval_status;
		if ($reval_status) {
			$final_marks = "final_marks";
			$final_status = "final_status";
		} else {
			$final_marks = "marks";
			$final_status = "status";
		}
		
		$result = $this->StudentMark->updateAll(
			array(
					"StudentMark.$final_marks" => $total,
					"StudentMark.$final_status" => "'".$status."'",
					"StudentMark.grade_point" => $grade_point,
					"StudentMark.grade" => "'".$grade."'",
					"StudentMark.modified_by" => $this->Auth->user('id'),
					"StudentMark.modified" => "'".date("Y-m-d H:i:s")."'"
			),
			array(
					"StudentMark.id" => $student_mark_id
			)
		);
		return $result;
	}
	
	public function insertMark ($mark, $model, $cm_id, $student_id, $month_year_id, $status, $grade, $grade_point, $course_type_id, $cid, $assessment_type, $type) {
		//echo $cm_id." ".$student_id." ".$month_year_id;
		//echo $course_type_id." ".$cid;
		$exists=$this->CourseStudentMapping->find('first', array(
				'conditions' => array(
						'CourseStudentMapping.student_id'=>$student_id,
						'CourseStudentMapping.course_mapping_id'=>$cm_id
				),
				'fields' => array('CourseStudentMapping.id'),
				'recursive' => 0
		));
		$data1=array();
		$cResult = $this->getCourseIdFromCmId($cm_id);
		//pr($cResult);
		$cId = $cResult[0]['Course']['id'];
		$data1['CourseStudentMapping']['course_id']=$cId;
		$data1['CourseStudentMapping']['course_mapping_id']=$cm_id;
		$data1['CourseStudentMapping']['student_id']=$student_id;
		$data1['CourseStudentMapping']['new_semester_id']=$month_year_id;
		$data1['CourseStudentMapping']['type']=$type;
		
		if(isset($exists['CourseStudentMapping']['id']) && $exists['CourseStudentMapping']['id']>0) {
			$csm_id = $exists['CourseStudentMapping']['id'];
			$data1['CourseStudentMapping']['id'] = $csm_id;
		}
		else {
			$this->CourseStudentMapping->create($data1);
		}
		$this->CourseStudentMapping->save($data1);
		
		$conditions = array();
		if ($course_type_id == 2 && $assessment_type=='cae') {
			$conditions["$model".".cae_practical_id"]=$cid;
		} else if ($course_type_id == 2 && $assessment_type=='ese') {
			$conditions["$model".".ese_practical_id"]=$cid;
		} else if ($course_type_id == 1) {
			$conditions["$model".".course_mapping_id"]=$cm_id;
		}
		$conditions["$model".".month_year_id"]=$month_year_id;
		$conditions["$model".".student_id"]=$student_id;
		
		$data_exists=$this->$model->find('first', array(
				'conditions' => $conditions,
				'fields' => array("$model".".id"),
				'recursive' => 0
		));
		//pr($data_exists);
		$data=array();
		if ($model == "StudentMark") {
			$data[$model]['status'] = $status;
			$data[$model]['grade_point'] = $grade_point;
			$data[$model]['grade'] = $grade;
		}
		if ($model=="EndSemesterExam") {
			$data[$model]['dummy_number_id'] = 1;
		}
		if ($course_type_id == 1) {
			$data[$model]['course_mapping_id'] = $cm_id;
		}
		else if ($course_type_id == 2 && $assessment_type=='cae') {
			$data[$model]['cae_practical_id'] = $cid;
		}
		else if ($course_type_id == 2 && $assessment_type=='ese') {
			$data[$model]['ese_practical_id'] = $cid;
		}
		$data[$model]['student_id'] = $student_id;
		$data[$model]['month_year_id'] = $month_year_id;
		$data[$model]['marks'] = $mark;
		
		if(isset($data_exists[$model]['id']) && $data_exists[$model]['id']>0) {
			$id = $data_exists[$model]['id'];
			$data[$model]['id'] = $id;
			$data[$model]['modified_by'] = $this->Auth->user('id');
			$data[$model]['modified'] = date("Y-m-d H:i:s");
		}
		else {
			$this->$model->create($data);
			$data[$model]['created_by'] = $this->Auth->user('id');
		}
		$this->$model->save($data);
	}
	
	public function insertIntoStudentMark ($mark, $cm_id, $student_id, $month_year_id, $status, $grade, $grade_point, $model) {
		//echo $cm_id." ".$student_id." ".$month_year_id;
		//echo $grade." ".$grade_point;
		
		$conditions=array();
		$conditions["$model".".course_mapping_id"]=$cm_id;
		$conditions["$model".".month_year_id"]=$month_year_id;
		$conditions["$model".".student_id"]=$student_id;
	
		$data_exists=$this->$model->find('first', array(
				'conditions' => $conditions,
				'fields' => array("$model".".id"),
				'recursive' => 0
		));
		//pr($data_exists);
		$data=array();
		$data[$model]['course_mapping_id'] = $cm_id;
		$data[$model]['status'] = $status;
		$data[$model]['grade_point'] = $grade_point;
		$data[$model]['grade'] = $grade;
		$data[$model]['student_id'] = $student_id;
		$data[$model]['month_year_id'] = $month_year_id;
		$data[$model]['marks'] = $mark;
	
		if(isset($data_exists[$model]['id']) && $data_exists[$model]['id']>0) {
			$id = $data_exists[$model]['id'];
			$data[$model]['id'] = $id;
			$data[$model]['modified_by'] = $this->Auth->user('id');
			$data[$model]['modified'] = date("Y-m-d H:i:s");
		}
		else {
			$this->$model->create($data);
			$data[$model]['created_by'] = $this->Auth->user('id');
		}
		//pr($data);
		$this->$model->save($data);
	}
	
	public function insertIntoInternal($mark, $model, $month_year_id, $student_id, $cm_id) {
		SWITCH ($model) {
			CASE "InternalExam":
				$field = "course_mapping_id";
				break;
			CASE "InternalPractical":
				$field = "cae_practical_id";
				break;
			CASE "InternalProject":
				$field = "course_mapping_id";
				break;
		}
		//echo "model ".$model." ".$id;
		$data=array();
		$data[$model][$field]=$cm_id;
		$data[$model]['student_id']=$student_id;
		$data[$model]['month_year_id']=$month_year_id;
		$data[$model]['marks']=$mark;
		$data[$model]['created_by']=$this->Auth->user('id');
		$this->$model->create($data);
		$this->$model->save($data);
	}
	
	public function updateMark($id, $mark, $model) {
		//echo "model ".$model." ".$id; 
		if ($model=="RevaluationExam") $markField = "revaluation_marks";
		else $markField = "marks";
		if ($model=="ProfessionalTraining" && $mark=='A') $mark="'".$mark."'";
		if ($model=="ProjectViva" && ($mark=='A'|| $mark=='a' || $mark=='AAA' || $mark=='aaa')) $mark="'".$mark."'";
		$result = $this->$model->updateAll(
				/* UPDATE FIELD */
				array(
						"$model.".$markField => $mark,
						"$model.modified_by" => $this->Auth->user('id'),
						"$model.modified" => "'".date("Y-m-d H:i:s")."'"
				),
				/* CONDITIONS */
				array(
						"$model.id" => $id
				)
				);
		return $result;
	}
	
	public function getAttendancePercentage($att) {
		if (isset($att['Attendance']) && count($att['Attendance'])>0) {
			$attendance_percent = $att['Attendance'][0]['percentage'];
		}
		else if (isset($att['GrossAttendance']) && count($att['GrossAttendance'])>0) {
			$attendance_percent = $att['GrossAttendance'][0]['percentage'];
		}
		else {
			$attendance_percent = 0;
		}
		return $attendance_percent;
	}
	
	public function attendanceData($student_id, $cm_id, $month_year_id) {
		
		$program = $this->findProgramByStudentId($student_id);
		$program_id = $program['Student']['program_id'];
		
		$result = $this->Student->find('first', array(
				'conditions' => array(
						'Student.id' => $student_id, 'Student.discontinued_status' => 0
				),
				'fields'=>array('Student.program_id'),
				'contain'=>array(
						'Attendance' => array(
								'conditions'=>array('Attendance.student_id'=>$student_id, 'Attendance.course_mapping_id'=>$cm_id),
								'fields'=>array('Attendance.percentage')
						),
						'GrossAttendance' => array(
								'conditions'=>array('GrossAttendance.student_id'=>$student_id, 'GrossAttendance.month_year_id'=>$month_year_id,
										'GrossAttendance.program_id'=>$program_id
								),
								'fields'=>array('GrossAttendance.percentage')
						),
				)
		));
		return $result;
	}

	public function updateCae($no_of_caes, $cae_details, $cm_id) {
		$marks = array();
		for ($i=0; $i<$no_of_caes; $i++) {
			$cae_id = $cae_details['cont_ass_id'][$cm_id][$i];
			$old_mark = $cae_details['cont_ass_marks'][$cm_id][$i];
			$new_marks = $cae_details['cont_ass_marks_new'][$cm_id][$i];
			//$total+=$new_marks;
			$marks[] = $new_marks;
			//echo $i." ".$new_marks." ";
			if ($new_marks != $old_mark) {
				//echo "hello";
				$result = $this->ContinuousAssessmentExam->updateAll(
						/* UPDATE FIELD */
						array(
								"ContinuousAssessmentExam.marks" => $new_marks,
								"ContinuousAssessmentExam.modified_by" => $this->Auth->user('id'),
								"ContinuousAssessmentExam.modified" => "'".date("Y-m-d H:i:s")."'"
						),
						/* CONDITIONS */
						array(
								"ContinuousAssessmentExam.id" => $cae_id
						)
						);
			} else {
				$cae_marks = $old_mark;
			}
		}
		return true;
	}
	
	public function computeCae($cm_id, $student_id, $marks) {
		
		$caeTheory = "";
		$caeArray = $this->Cae->find('all', array(
				'conditions' => array(
						'Cae.course_mapping_id' => $cm_id, 'Cae.indicator' => 0
				),
				'recursive' => -1
		));
		foreach ($caeArray as $cArray) {
			$caeTheory.=$cArray['Cae']['id'].",";
		}
		$caes = substr($caeTheory,0,strlen($caeTheory)-1);
		//echo "caes ".$caes;
		
		$convertTo = $marks-5;
		$marksDouble = $marks*2;
		$caesArray = explode(",", $caes);
		
		$absentResult = $this->ContinuousAssessmentExam->query("SELECT count(marks) as absent_count FROM continuous_assessment_exams
				where student_id=$student_id and cae_id in($caes) and (marks='A' or marks='a')");
		$absent_count = $absentResult[0][0]['absent_count'];
		//echo "ABSENT ".$absent_count;
		
		SWITCH ($absent_count) {
			CASE 2:
				//echo "2 absent";
				$stuResult = $this->ContinuousAssessmentExam->query("SELECT $convertTo * marks / $marks as  marks FROM
				continuous_assessment_exams where student_id=$student_id and (marks<>'A' or marks<>'a') and cae_id in($caes)");
				//$studentMark = $stuResult[0][0]['marks'];
				//break;
				break;
			CASE 1:
				//echo "1 absent";
				$stuResult = $this->ContinuousAssessmentExam->query("SELECT $convertTo * sum(marks) / $marksDouble as marks FROM
				(SELECT marks FROM continuous_assessment_exams
				where student_id=$student_id and (marks<>'A' or marks<>'a') and cae_id in($caes)
				ORDER by marks DESC LIMIT 2) as marks");
				//$studentMark = $stuResult[0][0]['marks'];
				break;
			CASE 0:
				//echo "0 absent";
				if (count($caesArray) > 1) {
					$tempMarks = $marksDouble;
				}
				else {
					$tempMarks = $marks;
				}
				//echo "  temp : ".$tempMarks;
				$stuResult = $this->ContinuousAssessmentExam->query("SELECT $convertTo * sum(marks) / $tempMarks as marks FROM
						(SELECT marks FROM continuous_assessment_exams
						where student_id=$student_id and (marks<>'A' or marks<>'a') and cae_id in($caes)
						ORDER by marks DESC LIMIT 2) as marks");
				//$studentMark = $stuResult[0][0]['marks'];
				break;
		}
		$studentMark = 0;
		if(isset($stuResult[0][0]['marks'])) {
			$studentMark = round($stuResult[0][0]['marks']);
		}
		//echo "</br>".$student_id." ".$studentMark;
		return $studentMark;
	}
	
 	public function individualUser() {
 	$access_result = $this->checkPathAccess($this->Auth->user('id'));
 	if (!$access_result) {
	    $monthYears = $this->MonthYear->getAllMonthYears();
	    $this->set(compact('monthYears'));
    }
    else {
    	$this->render('../Users/access_denied');
    }
  }
	
  public function individual() {
  	$access_result = $this->checkPathAccess($this->Auth->user('id'));
  	if (!$access_result) {
  		$monthYears = $this->MonthYear->getAllMonthYears();
  		$this->set(compact('monthYears'));
  	}
  	else {
  		$this->render('../Users/access_denied');
  	}
  }
  
  public function withdrawal() {
  	
  	$monthYears = $this->MonthYear->getAllMonthYears();
  	$this->set(compact('monthYears'));
  	if ($this->request->is('post')) {
  		//pr($this->data);
  		$bool = false;
  		$cm = $this->request->data['Withdrawal']['CourseMapping'];
  		$semester = $this->request->data['Withdrawal']['Semester'];
  		$student_id = $this->request->data['Withdrawal']['student_id'];
  		$month_year_id = $this->request->data['Withdrawal']['month_year_id'];
  		//pr($cm);
  		//pr($semester);
  		foreach ($cm as $cm_id => $withdrawal) {
  			if ($withdrawal) {
  				//echo $cm_id." ".$semester[$cm_id]." ".$student_id."</br>";
  				$result = $this->CourseStudentMapping->find('first', array(
  						'conditions'=>array('CourseStudentMapping.course_mapping_id'=>$cm_id,
  								'CourseStudentMapping.student_id'=>$student_id,
  								'CourseStudentMapping.indicator'=>0
  						),
  						'fields'=>array('CourseStudentMapping.id', 'CourseStudentMapping.type', 'CourseStudentMapping.new_semester_id'),
  						'contain'=>false
  				));
  				//pr($result);
  				//if ($result['CourseStudentMapping']['type'])
  				$data=array();
  				$data['CourseStudentMapping']['id']=$result['CourseStudentMapping']['id'];
  				$data['CourseStudentMapping']['type']="W";
  				$data['CourseStudentMapping']['new_semester_id']=$semester[$cm_id];
  				$data['CourseStudentMapping']['modified_by']=$this->Auth->user('id');
  				$data['CourseStudentMapping']['modified'] = date("Y-m-d H:i:s");
  				$this->CourseStudentMapping->save($data);
  					
  				$result = $this->StudentWithdrawal->find('first', array(
  						'conditions'=>array('StudentWithdrawal.course_mapping_id'=>$cm_id,
  								'StudentWithdrawal.student_id'=>$student_id,
  						),
  						'fields'=>array('StudentWithdrawal.id'),
  						'contain'=>false
  				));
  					
  				$data=array();
  				if (isset($result) && count($result)>0) {
  					$withdrawal_id = $result['StudentWithdrawal']['id'];
  					$data['StudentWithdrawal']['id']=$withdrawal_id;
  					$data['StudentWithdrawal']['modified_by']=$this->Auth->user('id');
  					$data['StudentWithdrawal']['modified'] = date("Y-m-d H:i:s");
  				}
  				else {
  					$data['StudentWithdrawal']['created_by']=$this->Auth->user('id');
  					$this->StudentWithdrawal->create();
  				}
  				$data['StudentWithdrawal']['course_mapping_id']=$cm_id;
  				$data['StudentWithdrawal']['student_id']=$student_id;
  				$data['StudentWithdrawal']['month_year_id']=$month_year_id;
  				if ($this->StudentWithdrawal->save($data)) $bool=true;
  					
  			}
  		}
  		//$this->redirect(array('action' => 'withdrawal'));
  		if ($bool) $this->Flash->success('Withdrawal saved');
  	}
  }
  
  public function withdrawalSearch($month_year_id, $reg_no) {
  	$student_id = $this->Student->getStudentId($reg_no);
  	//echo $student_id;
  	$this->layout = false;
  	$smResults = $this->StudentMark->find('all', array(
  			'conditions' => array('StudentMark.student_id' => $student_id,
  					'StudentMark.month_year_id'=>$month_year_id
  			),
  			'fields' => array('StudentMark.id'),
  			'contain' => false
  	));
  
  	$cnt = count($smResults);
  	/* if ($cnt > 0) {
  		$errorMsg = "Data already published for this MonthYear. Hence withdrawal cannot be availed of.";
  		$this->set(compact('errorMsg'));
  	}
  	else { */
  		$csmResults = $this->CourseStudentMapping->find('all', array(
  				'conditions' => array('CourseStudentMapping.student_id' => $student_id, 'CourseStudentMapping.indicator'=>0
  				),
  				'fields' => array('CourseStudentMapping.id', 'CourseStudentMapping.student_id',
  						'CourseStudentMapping.course_mapping_id', 'CourseStudentMapping.type', 'CourseStudentMapping.new_semester_id',
  						'CourseStudentMapping.indicator'
  				),
  				'contain' => array(
  						'CourseMapping' => array(
  								'conditions' => array('CourseMapping.month_year_id'=>$month_year_id, 'CourseMapping.indicator'=>0
  								),
  								'fields' => array('CourseMapping.id', 'CourseMapping.month_year_id', 'CourseMapping.semester_id'),
  								'Course' => array(
  										'fields' => array('Course.course_code', 'Course.course_name', 'Course.id'),
  										'CourseType'=>array(
  												'fields'=>array('CourseType.course_type')
  										)
  								),
  								'Program' => array('fields' => array('Program.id', 'Program.semester_id')),
  						)
  				)
  		)
  				);
  		//pr($csmResults);
  		$details = $this->getSemesterFromMonthYear($month_year_id, $student_id);
  		//pr($details);
  			
  		$batch_id = $details[0]['Batch']['id'];
  		$program_id = $details[0]['Program']['id'];
  		$this->set(compact('batch_id', 'program_id', 'student_id'));
  		$cm = $this->CourseMapping->find('first', array(
  				'conditions' => array('CourseMapping.month_year_id'=>$month_year_id,
  					'CourseMapping.batch_id'=>$batch_id, 'CourseMapping.program_id'=>$program_id
  				),
  				'fields' => array('CourseMapping.id', 'CourseMapping.month_year_id', 'CourseMapping.semester_id'),
  				'contain'=>array(
  						'Course' => array(
  								'fields' => array('Course.course_code', 'Course.course_name'),
  						),
  						'Program' => array('fields' => array('Program.id', 'Program.semester_id')),
  				)
  		));
  		
  		//pr($cm);
  		$semester_id = $cm['CourseMapping']['semester_id']+1;
  			
  		$myResult = $this->MonthYear->find("all", array(
  				'conditions'=>array('MonthYear.id >'=>$month_year_id,
  				),
  				'fields'=>array('MonthYear.year', 'MonthYear.id'),
  				'contain'=>array(
  						'Month'=>array(
  								'fields'=>array('Month.month_name')
  						)
  				)
  		));
  		//pr($myResult);
  		if (isset($myResult) && count($myResult)>0)  {
  			$num_of_semesters = $this->getNumberOfSemestersForABatch($batch_id);
  			$start_month_year_id = $month_year_id+1;
  			$end_month_year_id = $month_year_id + ($num_of_semesters - 1);
  
  			$myResult = $this->MonthYear->find("all", array(
  					'conditions'=>array(/* 'MonthYear.id >'=>$month_year_id, */
  							'MonthYear.id BETWEEN '.$start_month_year_id.' AND '.$end_month_year_id
  					),
  					'fields'=>array('MonthYear.year', 'MonthYear.id'),
  					'contain'=>array(
  							'Month'=>array(
  									'fields'=>array('Month.month_name')
  							)
  					)
  			));
  			$month_years = array();
  			foreach ($myResult as $key => $value) {
  				$month_years[$value['MonthYear']['id']] = $value['Month']['month_name']." ".$value['MonthYear']['year'];
  			}
  
  			//echo $start_month_year_id." ".$end_month_year_id;
  			//echo "Semesters : ".$num_of_semesters;
  			$total_semesters = $cm['Program']['semester_id'];
  			$this->set(compact('csmResults', 'student_id', 'start_month_year_id', 'end_month_year_id', 'myResult', 'month_years'));
  		}
  		else {
  			$errorMsg = "No sufficient Month Years";
  			$this->set(compact('errorMsg'));
  		}
  			
  	//}
  }
  
  public function getSemesterFromMonthYear($month_year_id, $student_id) {
  	$result = $this->Student->find('all', array(
  			'conditions' => array(
  					'Student.id' => $student_id
  			),
  			'fields' => array('Student.id'),
  			'contain' => array(
  					'Batch' => array('fields' => array('Batch.id')),
  					'Program' => array('fields' => array('Program.id')),
  			)
  	));
  	//pr($result);
  	return $result;
  }
  
  public function findProgramByStudentId($student_id) {
  	$program = $this->Student->find('first', array(
  			'conditions' => array(
  					'Student.id' => $student_id, 'Student.discontinued_status' => 0
  			),
  			'fields'=>array('Student.program_id'),
  			'recursive' => -1,
  			'contain'=>false
  	));
  	return $program;
  }
  
  public function getSectionId($sectionName){
  	$result = $this->Section->find('first', array(
  			'conditions' => array(
  					'Section.name' => $sectionName
  			),
  			'fields'=>array('Section.id'),
  			'recursive' => 0,
  			'contain'=>false
  	));
  	if($result){return $result['Section']['id'];}
  }
  
  public function label() {
  	$access_result = $this->checkPathAccess($this->Auth->user('id'));
  	if (!$access_result) {
  	$academics = $this->Student->Academic->find('list');
  	$batches = $this->Student->Batch->find('list', array('fields' => array('Batch.batch_period')));
  	
  	$monthYears = $this->MonthYear->getAllMonthYears();
  	
  	$this->set(compact('batches', 'academics', 'programs', 'monthYears'));
  	
  	if(isset($this->request->data['print']) == 'print'){
  		$batch_id = $this->request->data['Label']['batch_id'];
  		$academic_id = $this->request->data['Label']['academic_id'];
  		$program_id = $this->request->data['Student']['program_id'];
  		$results = $this->Student->getStudentsInfo($batch_id, $program_id);
  		//pr($results);
  		$batch = $this->Batch->getBatch($batch_id);
  		$program = $this->Program->getProgram($program_id);
  		$pgm = $program['short_code']; 
  		$academic = $program['academic_short_code']; 
  		$item = 0;
  		$html="";
  		$html .= "<table cellpadding='0' cellspacing='0' border='0' width='100%' height='100%'>";
  		$cnt = 0;
  		$html.="<tr><td colspan='3'>&nbsp;</td></tr>"; 
  		$html.="<tr>";
  		foreach ($results as $student_id => $value) {
  			$item++;
			$html.="<td style='height:134px;' valign='middle' align='center'><span style='font-face:Arial;font-size:45px;'>
					<strong>".$value['reg_num']."</strong></span></td>";
			$cnt++;
			if ($cnt%3==0) {
				$html.="</tr><tr>";
			}
			if ($item%24==0) {
				$html.="</table>";
				$html .= "<div style='page-break-after:always'></div>";
				$html.="<table cellpadding='0' cellspacing='0' border='0' width='100%' height='100%'>
						<tr><td colspan='3'>&nbsp;</td></tr><tr>";
			}
		}
		$html.="</table>";
		$this->mPDF->init();
		$this->mPDF->setFilename('Label_'.$batch."_".$academic."_".$pgm."_".date('d_M_Y').'.pdf');
		$this->mPDF->setOutput('D');
		$this->mPDF->AddPage('P','', '', '', '',0,0,0,0,0,0);
		$this->mPDF->WriteHTML($html);
		$this->mPDF->SetWatermarkText("Draft");
		$this->autoLayout=false;
		$this->autoRender=false; 
  	}
  	}
  	else {
  		$this->render('../Users/access_denied');
  	}
  }
  
  public function dcReport() {
  		$academics = $this->Program->Academic->find('list',array('order' => array('Academic.academic_name ASC')));
		$programs = $this->Program->find('list',array('order' => array('Program.program_name ASC')));
		$batches = $this->Batch->find('list', array('fields' => array('Batch.batch_period'),'order' => array('Batch.batch_to DESC')));
		$this->set(compact('academics','programs','batches'));
  }
  
  public function dcReportSearch($batch_id, $academic_id, $program_id, $mode) {
  		$results = $this->studentDetailsWithBatchAndProgram($batch_id, $program_id);
  		$degreeResult = $this->processDegreeCertificateReport($results);
  		$this->set(compact('degreeResult', 'batch_id', 'academic_id', 'program_id', 'mode'));
  		
  		if($mode == 'EXCEL'){
  			$this->excelDegreeCertificateReport($degreeResult);
  		}
  		if($mode == 'PRINT'){
  			$this->layout= 'print';
  			return false;
  		}
  		$this->layout = false;
  }
  
  public function total() {
  	$academics = $this->Program->Academic->find('list',array('order' => array('Academic.academic_name ASC')));
  	$programs = $this->Program->find('list',array('order' => array('Program.program_name ASC')));
  	$batches = $this->Batch->find('list', array('fields' => array('Batch.batch_period'),'order' => array('Batch.batch_to DESC')));
  	$monthyears = $this->MonthYear->getAllMonthYears();
  	$this->set(compact('academics','programs','batches','monthyears'));
  
  }
  
  private function distinct($items) {
  	$result = array();
  	for($i = 0; $i < sizeof($items); $i++)
  		if (!array_key_exists($items[$i]['lessonid'], $result) || $result[$items[$i]['lessonid']]['classid'] > $items[$i]['classid'])
  			$result[$items[$i]['lessonid']] = $items[$i];
  		return $result;
  }
  
  public function dispOption($option) {
  	$this->layout=false;
  	$this->render('tc_date');
  	return false;
  }
  
  public function stuInfo($regNo, $option) {
  	//echo $option;
  	$student = array();
  	$conditions= array();
  	$conditions['Student.registration_number']=$regNo;
  	$student = $this->Student->find("first", array(
  			'conditions'=>$conditions,
  			'fields'=>array('Student.registration_number', 'Student.name', 'Student.batch_id', 'Student.id'),
  			'contain' => array(
  					'Program'=>array(
  							'fields'=>array('Program.semester_id')
  					)
  			)
  	));
  	//	pr($student);
  	SWITCH ($option) {
  		CASE "name":
  			$stuName = $student['Student']['name'];
  			$this->set('stuName', $stuName);
  			$this->autoLayout=false;
  			$this->render("stu_name");
  			break;
  		CASE "semester":
  			$semesters = $student['Program']['semester_id'];
  			$this->set('semesters', $semesters);
  			$this->autoLayout=false;
  			$this->render("stu_semester");
  			break;
  	}
  //	return $student;
  }
 
  public function course_search($regNo, $semester, $monthYearId) {
  	$cmArray = array();
  	$results = $this->CourseMapping->listCoursesWithRegNoAndSemesterId($regNo, $semester);
  	foreach ($results as $key => $value) {
  		if (isset($value['CourseStudentMapping']) && count($value['CourseStudentMapping'])>0) {
  			$cmArray[$value['CourseMapping']['id'].",".$value['Course']['course_type_id']]=$value['Course']['course_code'];
  		}
  	}
  	//pr($cmArray);
  	$this->autoLayout=false;
  	$this->set('cmArray', $cmArray);
  }
  
  public function get_course_details($regNo, $semester, $cm_ct_id, $examMonth) {
  	$student_id = $this->getIdFromRegNo($regNo);
  	$array = explode(",", $cm_ct_id);
  	
  	list($cm_id, $course_type_id) = $array;
  	$cm_id_array = array();
  	$cm_id_array[$cm_id]=$cm_id;
  	//echo $cm_id." ".$course_type_id." ".$examMonth." ".$student_id;
  	$results = $this->theoryResults($examMonth, $cm_id, $student_id);
  	//pr($results);
  	$array=array();
  	foreach ($results as $key => $value) {
  		pr($value);
  		$cae = $value['InternalExam'][0]['marks'];
  		$cae = $value['InternalExam'][0]['id'];
  		if (isset($value['EndSemesterExam'][0]['marks'])) {
  			$ese = $value['EndSemesterExam'][0]['marks'];
  			$ese_id = $value['EndSemesterExam'][0]['id'];
  		} else {
  			$ese = "AAA";
  			$ese_id = "";
  		}
  		if (isset($value['StudentMark'][0]['marks'])) {
  			$total = $value['StudentMark'][0]['marks'];
  			$total_id = $value['StudentMark'][0]['id'];
  		} else {
  			$total = "AAA";
  			$total_id = "";
  		}
  		$grade = $value['StudentMark'][0]['grade'];
  	}
  	$course_details = $this->CourseMapping->retrieveCourseDetails($cm_id_array, $examMonth);
  	//pr($course_details);
  	$array['cae']=$cae;
  	$array['ese']=$ese;
  	$array['total']=$total;
  	$array['cae_id']=$cae_id;
  	$array['ese_id']=$ese_id;
  	$array['total_id']=$total_id;
  	$array['grade']=$grade;
  	$this->set(compact('array', 'course_details', 'cm_id')); 
  	
  	$this->autoLayout=false;
  }
  
  public function rankSearch() {
  	if ($this->request->is('post')) {
  		//pr($this->data);
  		$finalArray = array();
  			
  		$reg_num = $this->request->data['Student']['registration_number'];
  		$student = $this->Student->getBatchAndProgramIdFromStudentRegNo($reg_num);
  		//pr($student); 
  		$batch_id = $student[$reg_num]['batch_id'];
  		$program_id = $student[$reg_num]['program_id'];
  		$studentId = $student[$reg_num]['id'];
  		$examMonth = "-";
  		
  		$reportResult = array();
		$programResult = $this->getCourseMappingResult($examMonth, $batch_id, $program_id);

		foreach ($programResult as $key => $pgmResult) { //pr($pgmResult);
			$academic = "";
			if (isset($pgmResult['Batch']['academic']) && $pgmResult['Batch']['academic'] == "Jun") {
				$academic = "A";
			}
			$batch_period = $pgmResult['Batch']['batch_from']."-".$pgmResult['Batch']['batch_to']." ".$academic;
			//$this->set(compact('batch_period'));
			$program_id = $pgmResult['CourseMapping']['program_id'];
			$batch_id = $pgmResult['CourseMapping']['batch_id'];
			$cm_id = $pgmResult[0]['CMId'];
			$pgm_short_code = $pgmResult['Program']['short_code'];
			$academic_short_code = $pgmResult['Program']['Academic']['short_code'];
			
			$stuResults = $this->Student->getActiveStudents($batch_id, $program_id, '-');
			//pr($stuResults);
			
			$totalStrength = $this->Student->getCount($batch_id, $program_id);
			
			$array = array();
			$array['batch_id'] = $batch_id;
			$array['program_id'] = $program_id;
			$array['short_code'] = $pgm_short_code;
			$array['batch'] = $batch_period;
			$array['program'] = $pgmResult['Program']['Academic']['academic_name'];
			$array['specialisation'] = $pgmResult['Program']['program_name'];
			$array['semester'] = $pgmResult[0]['semester_id'];
			$array['max_marks'] = $pgmResult[0]['max_marks'];

			$i = 1; $topTotal = array();
			$topTotal = $this->processTopRankingResults($stuResults, $cm_id, $array);
			//pr($topTotal);
			
			uasort($topTotal, array($this,'compare_total'));
			$reportResult[] = $topTotal;
		}
		//pr($reportResult);
		$result = array();
		foreach ($reportResult as $key => $val) {
			//pr($val);
			$result[$key] = array_slice($val, 0, 10);
		}
		//pr($result);
		$rankResult = $result[0];
		$rankIndex = array_search($reg_num, array_column($rankResult, 'registration_number'));
		$rank = $rankIndex+1; 
		//echo $rank;
		$rankArray = $result[0][$rankIndex];
		//pr($rankArray); 
		
		for($i=0;$i<count($reportResult);$i++){
			$seqRanking = 0;$lastTotal = 0; $cnt = 0;
			foreach ($reportResult[$i] as $key => $result) {
				$bool = false;
				//pr($studentArray[$i]);
				if(isset($result['total'])){
					if(($lastTotal != $result['total']) || ($seqRanking == 0) && $cnt<=9){
						$seqRanking++;
					}else{$seqRanking=$lastRanking++; $lastRanking++; $bool = true;}
					if ($result['registration_number'] == $reg_num) $seqRank = $seqRanking;
					$lastTotal = $result['total'];
					$lastRanking = $seqRanking;
					if ($bool) $seqRanking++;
					//echo "</br>".$lastTotal." ".$seqRanking." ".$lastRanking." ".$t;
				}
			}
		}
		$this->set(compact('seqRank', 'rankArray'));
		$this->layout= false;
		$this->layout= 'print';
		$this->render('rank');
		return false;
  	}
  }
}