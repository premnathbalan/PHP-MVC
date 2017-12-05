<?php
App::uses('AppController', 'Controller');
/**
 * Courses Controller
 *
 * @property Course $Course
 * @property PaginatorComponent $Paginator
 * @property FlashComponent $Flash
 * @property SessionComponent $Session
 */
class CoursesController extends AppController {

/**
 * Components
 *
 * @var array
 */
	public $components = array('Paginator', 'Flash', 'Session');
	public $uses = array( "Course", "CourseType");
/**
 * index method
 *
 * @return void
 */
	public function index() {
		$access_result = $this->checkPathAccess($this->Auth->user('id'));
		if (!$access_result) {
			if ($this->request->is('post')) {
				move_uploaded_file($this->request->data['Course']['csv']['tmp_name'], WWW_ROOT . '../sets_upload_received_file/' .$this->request->data['Course']['csv']['name']);
			
				$filename = WWW_ROOT . '../sets_upload_received_file/'.$this->request->data['Course']['csv']['name'];
				$handle = fopen($filename, "r");
			
				// read the 1st row as headings
				$header = fgetcsv($handle);
			
				// create a message container
				$return = array(
				'messages' => array(),
				'errors' => array(),
				);
				$msg = ""; 
				// read each data row in the file
				while (($row = fgetcsv($handle)) !== FALSE) { 
					//$i++;
					$data = array();
						
					// for each header field
					foreach ($header as $k=>$head) {
					// get the data field from Model.field
						if (strpos($head,'.')!==false) { 
							$h = explode('.',$head);
							$data[$h[0]][$h[1]]=(isset($row[$k])) ? $row[$k] : '';
						}
						// get the data field from field
						else{ 
							$head = strtolower(str_replace(' ','_',$head));
							$data['Course'][$head]=(isset($row[$k])) ? $row[$k] : '';
						}
					}
					$chk =$this->Course->find('first',
							array('conditions' => array(
									'Course.course_code' => $data['Course']['course_code']
							),'fields' => array('Course.id'),
									'recursive'=>1)
							);
					if($chk){
						$msg .= $data['Course']['course_code'].", ";
					}else{
						$this->Course->create();
						$courseTypeMode = strtolower($data['Course']['theory_/_practical_/_theory_and_practical_/_project']);
						$courseTypeId = $this->getCourseTypeId($courseTypeMode);
						$data['Course']['course_type_id'] 	= $courseTypeId;			
						$data['Course']['created_by']       = $this->Auth->user('id');
						$this->Course->save($data);							
					}				
					unset($data);
				}
				if($msg){
					$msg =rtrim($msg,', ').' Already Exists. Remaining';
				}
				$this->Flash->success(__($msg .' Course has been saved.'));
			}
		} else {
			$this->render('../Users/access_denied');
		}
		$results = $this->Course->find('all', array(
				//'fields'=>array('Course.*'),
				//'conditions'=>array('Course.id'=>1),
				'contain'=>array(
					'User'=>array('fields'=>array('User.*')),
					'ModifiedUser'=>array('fields'=>array('ModifiedUser.*')),
					'CourseType'=>array('fields'=>array('CourseType.course_type'))
				),
				//'contain'=>false,
				'recursive'=>-1
		));
		/* $dbo = $this->Course->getDatasource();
		$logs = $dbo->getLog();
		$lastLog = end($logs['log']);
		echo $lastLog['query']; */
		
		$this->set('courses', $results);
	}

/**
 * view method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function view($id = null) {
		$access_result = $this->checkPathAccess($this->Auth->user('id'));
		if (!$access_result) {
			if (!$this->Course->exists($id)) {
				throw new NotFoundException(__('Invalid course'));
			}
			$options = array('conditions' => array('Course.' . $this->Course->primaryKey => $id));
			$this->set('course', $this->Course->find('first', $options));
		} else {
			$this->render('../Users/access_denied');
		}
		$this->layout=false;
	}

/**
 * add method
 *
 * @return void
 */
	public function add() {
		$access_result = $this->checkPathAccess($this->Auth->user('id'));
		if (!$access_result) {
			if ($this->request->is('post')) {
				$this->Course->create();
				$this->request->data['Course']['created_by'] = $this->Auth->user('id');
				if ($this->Course->save($this->request->data)) {
					$this->Flash->success(__('The course has been saved.'));
					echo "success";exit;
				} else {
					$this->Flash->error(__('The course could not be saved. Please, try again.'));
				}
			}
			$courseTypes = $this->Course->CourseType->find('list');
			$this->set(compact('semesters', 'courseTypes'));
		} else {
			$this->render('../Users/access_denied');
		}
		$this->layout=false;
	}

/**
 * edit method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function edit($id = null) {
		$access_result = $this->checkPathAccess($this->Auth->user('id'));
		if (!$access_result) {
			if (!$this->Course->exists($id)) {
				throw new NotFoundException(__('Invalid course'));
			}
			if ($this->request->is(array('post', 'put'))) {
				$this->request->data['Course']['modified_by'] = $this->Auth->user('id');
				if ($this->Course->save($this->request->data)) {
					$this->Flash->success(__('The course has been saved.'));
					echo "success";exit;
				} else {
					$this->Flash->error(__('The course could not be saved. Please, try again.'));
				}
			} else {
				$options = array('conditions' => array('Course.' . $this->Course->primaryKey => $id));
				$this->request->data = $this->Course->find('first', $options);
			}
			$courseTypes = $this->Course->CourseType->find('list');
			$this->set(compact('semesters', 'courseTypes', 'courseModes', 'programs'));
		} else {
			$this->render('../Users/access_denied');
		}
		$this->layout=false;
	}

/**
 * delete method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function delete($id = null) {
		$access_result = $this->checkPathAccess($this->Auth->user('id'));
		if (!$access_result) {
			$this->Course->id = $id;
			if (!$this->Course->exists()) {
				throw new NotFoundException(__('Invalid course'));
			}
			$this->request->allowMethod('post', 'delete');
			if ($this->Course->delete()) {
				$this->Flash->success(__('The course has been deleted.'));
			} else {
				$this->Flash->error(__('The course could not be deleted. Please, try again.'));
			}
			return $this->redirect(array('action' => 'index'));
		} else {
			$this->render('../Users/access_denied');
		}
	}
	
	public function manageCourses() {
		$dataset = $this->Course->query("SELECT batches.batch_from, batches.batch_to, programs.program_name, programs.id FROM `students`
										JOIN batches ON batches.id=students.batch_id
										JOIN programs ON programs.id=students.program_id
										JOIN academics ON academics.id=students.academic_id
										GROUP BY students.program_id order by students.academic_id");
		$batchProgram=array();
		foreach ($dataset as $dataset) {
			$batchProgram[$dataset['programs']['id']]=$dataset['batches']['batch_from']."-".$dataset['batches']['batch_to']." ".$dataset['programs']['program_name'];
		}
		//pr($batchProgram);
		$this->set(compact('batchProgram'));
		
	}
	
	public function findNoOfSemesters($id = null) {
		$options = array('conditions' => array('Program.id'=> $id), 'fields' => array('Program.semester_id'));
		$semesters = $this->Course->Program->find('list', $options);
		//pr($semesters);
		$programSemesters = $semesters[$id];
		$courses = $this->Course->find('list');
		$this->set('courses', $courses);
		$this->set('semesters', $semesters);
		$this->set('programId', $id);
		$this->set(compact('semesters', 'programSemesters', 'courses'));		
		$this->layout=false;		
	}
	
	public function course_upload_template(){
		$filename = "courses_upload.csv";
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
	
	public function getCourseTypeId($CT){	
		$options = array('conditions' => array('CourseType.course_type'=> $CT), 'fields' => array('CourseType.id'),'recursive'=>0);
		$course_type = $this->Course->CourseType->find('first', $options);
		if($course_type){
	 		return $course_type['CourseType']['id'];
		}else{return "";}
	}
	
	public function downloadAsExcel() {
		$access_result = $this->checkPathAccess($this->Auth->user('id'));
		if (!$access_result) {
			$results = $this->Course->find('all', array(
					'fields'=>array('Course.course_name', 'Course.course_code', 'Course.common_code', 'Course.board', 
							'Course.course_type_id', 'Course.indicator', 'Course.credit_point', 'Course.course_max_marks',
							'Course.min_cae_mark', 'Course.max_cae_mark', 'Course.min_ese_mark', 'Course.max_ese_mark',
							'Course.total_min_pass', 'Course.max_ese_qp_mark'
					),
					'contain'=>array(
							'CourseType'=>array('fields'=>array('CourseType.course_type'))
					)
			));
			
			//pr($results);
			
			$phpExcel = new PHPExcel();
			$phpExcel->setActiveSheetIndex(0);
			$sheet = $phpExcel->getActiveSheet();
			$sheet->getRowDimension('1')->setRowHeight('18');
			$sheet->setTitle("Courses");
			
			$sheet->setCellValue("A1", strtoupper("course_name"));
			$sheet->setCellValue("B1", strtoupper("course_code"));
			$sheet->setCellValue("C1", strtoupper("common_code"));
			$sheet->setCellValue("D1", strtoupper("board"));
			$sheet->setCellValue("E1", strtoupper("course_type"));
			$sheet->setCellValue("F1", strtoupper("credit_point"));
			$sheet->setCellValue("G1", strtoupper("course_max_marks"));
			$sheet->setCellValue("H1", strtoupper("min_cae_percent"));
			$sheet->setCellValue("I1", strtoupper("max_cae_mark"));
			$sheet->setCellValue("J1", strtoupper("min_ese_percent"));
			$sheet->setCellValue("K1", strtoupper("max_ese_mark"));
			$sheet->setCellValue("L1", strtoupper("total_min_pass_percent"));
			$sheet->setCellValue("M1", strtoupper("max_ese_qp_mark"));
			
			$i=2;
			foreach ($results as $key => $value) {
				$sheet->getRowDimension($i)->setRowHeight('18');
				$sheet->setCellValue('A'.$i, $value['Course']['course_name']);
				$sheet->setCellValue('B'.$i, $value['Course']['course_code']);
				$sheet->setCellValue('C'.$i, $value['Course']['common_code']);
				$sheet->setCellValue('D'.$i, $value['Course']['board']);
				$sheet->setCellValue('E'.$i, $value['CourseType']['course_type']);
				$sheet->setCellValue('F'.$i, $value['Course']['credit_point']);
				$sheet->setCellValue('G'.$i, $value['Course']['course_max_marks']);
				$sheet->setCellValue('H'.$i, $value['Course']['min_cae_mark']);
				$sheet->setCellValue('I'.$i, $value['Course']['max_cae_mark']);
				$sheet->setCellValue('J'.$i, $value['Course']['min_ese_mark']);
				$sheet->setCellValue('K'.$i, $value['Course']['max_ese_mark']);
				$sheet->setCellValue('L'.$i, $value['Course']['total_min_pass']);
				$sheet->setCellValue('M'.$i, $value['Course']['max_ese_qp_mark']);
				$i++;
			}
			
			$download_filename="Courses_".date('d-M-Y h:i:s');
			header("Content-Type: application/vnd.ms-excel");
			header("Content-Disposition: attachment; filename=\"$download_filename.xls\"");
			header("Cache-Control: max-age=0");
			$objWriter = PHPExcel_IOFactory::createWriter($phpExcel, "Excel5");
			$objWriter->save("php://output");
			exit;
		} else {
			$this->render('../Users/access_denied');
		}
	}
}