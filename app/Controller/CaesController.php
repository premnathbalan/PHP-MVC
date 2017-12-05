<?php
App::uses('AppController', 'Controller');
/**
 * Caes Controller
 *
 * @property Cae $Cae
 * @property PaginatorComponent $Paginator
 * @property FlashComponent $Flash
 * @property SessionComponent $Session
 */
class CaesController extends AppController {

/**
 * Components
 *
 * @var array
 */
	public $components = array('Paginator', 'Flash', 'Session');
	public $uses = array("Cae","ContinuousAssessmentExam", "Student", "BatchMode", "Batch", "Program", "MonthYear", "CourseMapping", "StudentType", "Semester", "CourseType");

/**
 * index method
 *
 * @return void
 */
	public function index() {
		//$this->Cae->recursive = 0;
		//$this->set('caes', $this->Paginator->paginate());
		
		//pr($this->data);
		if($this->request->is('post')){
			if($this->request->data['submit']=="add") {
				//echo "ADD";
			}
			/* echo $batch=$this->request->data['Student']['batch_id'];
			 echo $academic=$this->request->data['Student']['academic_id'];
			 echo $program=$this->request->data['Student']['program_id']; */
			
			if (isset($this->data)) {
				$this->loadModel('Cae');
				/* pr($this->data);
				echo $batch_id = $this->request->data['Student']['batch_id'];
				echo $academic_id = $this->request->data['Student']['academic_id'];
				echo $program_id = $this->request->data['program_id'];
				echo $cm_id = $this->request->data['course_mapping_id'];
				echo $marks = $this->request->data['marks']; */
				/* $results = $this->CourseMapping->find('all', array('conditions' => array('CourseMapping.id'=> $cm_id),
						'recursive'=>1)); */
				$this->Cae->recursive = 0;
				$results = $this->Cae->find('all', array('conditions' => array('Cae.course_mapping_id' => $cm_id), 
						'fields' => array('MAX(Cae.number) as cnt')
				));
				//pr($results);
				$caeCount = $results[0][0]['cnt'];
				//echo "Count : ".$cnt;
				$this->set(compact('caeCount'));
				$programs = $this->Student->Program->find('list', array(
						'conditions' => array('Program.academic_id'=> $this->request->data['Student']['academic_id'])));
				$courses = $this->Cae->CourseMapping->find('list', array(
						'conditions' => array('CourseMapping.id'=> $this->request->data['course_mapping_id'])));
				$this->set(compact('programs'/* , 'courses' */));
				/* $programs = $this->Cae->Program->find('list', array(
						'conditions' => array('Program.academic_id'=> $this->request->data['Caes']['academic_id'])));
				$this->set(compact('programs')); */
		
			}
		}
		$academics = $this->Student->Academic->find('list');
		$batches = $this->Student->Batch->find('list', array('fields' => array('Batch.batch_period')));
		$this->set(compact('batches', 'academics'));
		
	}
	
	public function findSemestersByProgram($id) {
		$semesters = $this->Program->find("list", array('conditions' => array('Program.id'=>$id), 'fields' => 'Program.semester_id'));
		//pr($semesters);
		$nSemesters = $semesters[$id];
		$numsemesters = array();
		for($i=1; $i<=$nSemesters; $i++) {
			$numSemesters[$i] = $i;
		}
		$this->set(compact('numSemesters'));
		$this->layout=false;
	}
	
	/* public function listCourseTypeIdsBasedOnMethod($method_name) {
		$filterCondition="";
		SWITCH ($method_name) {
			case "theory":
				$filterCondition.= "`(CourseType`.`course_type` LIKE '%theory%')";
				break;
			case "practical":
				$filterCondition.= "`(CourseType`.`course_type` LIKE '%practical%') OR (CourseType`.`course_type` LIKE '%studio%')";
				break;
			case "project":
				$filterCondition.= "`(CourseType`.`course_type` LIKE '%project%')";
				break;
			case "PT":
				$filterCondition.= "`(CourseType`.`course_type` LIKE '%PT%')";
				break;
		}
		$courseTypes = $this->CourseType->find('list', array(
				'conditions' => array($filterCondition),
		));
		//pr($courseTypes); 
		$explodeCourseType = $this->explodeCourseTypeId($courseTypes);
		//pr($explodeCourseType);
		return $explodeCourseType;
	} */
	
	public function explodeCourseTypeId($courseType) {
		$explodeCourseType = "";
		foreach($courseType as $key => $value) {
			$explodeCourseType.=$key.",";
		}
		$explodeCourseType = substr($explodeCourseType,0,strlen($explodeCourseType)-1);
		return $explodeCourseType;
	}
	
	public function findCoursesByProgram($id = null, $batch_id, $semester_id=null, $course_type=null) {
		
		$this->loadModel('Cae');
		
		$course_type_id = $this->listCourseTypeIdsBasedOnMethod($course_type, "-");
		//pr($course_type_id);
		$course = array();
		
		if(count($course_type_id) > 0) {
			$courseTypeFilter = "`Course`.`course_type_id` IN (".$course_type_id.")";
		}
		else {
			$courseTypeFilter = "`Course`.`course_type_id` > 0";
		}
		if($semester_id > 0) {
			$myFilter = "`CourseMapping`.`semester_id` = ".$semester_id;
		}
		else {
			$myFilter = "`CourseMapping`.`semester_id` > 0";
		}
		$courseMapping = $this->CourseMapping->find('all', 
				array('conditions' => array(
										'CourseMapping.Program_id' => $id, 'CourseMapping.batch_id' => $batch_id, 
										$myFilter,
										'CourseMapping.indicator' => 0,
										/* 'OR' => array(
									        array('Course.course_type_id' => 1),
									        array('Course.course_type_id' => 3)
									    ), */
										$courseTypeFilter
									),
						'fields' => array('CourseMapping.id', 'Course.course_name', 'Course.course_type_id'),
						'recursive' => 0
				));
		if(count($courseMapping) > 0) {
			foreach ($courseMapping as $key => $value) {
				$course[$value['CourseMapping']['id']]=$value['Course']['course_name'];
			}
		}
		else {
			$course['0'] = "No courses";
		}
		
		$semesters = $this->Cae->CourseMapping->Program->find('list', array('conditions' => array('Program.id' => $id), 'fields' => 'Program.Semester_id'));
		
		$nSemesters = $semesters[$id];
		$numsemesters = array();
		for($i=1; $i<=$nSemesters; $i++) {
			$numSemesters[$i] = $i;
		}
		
		/* $monthYears = $this->Cae->MonthYear->find('all');
		$monthyears=array();
		foreach ($monthYears as $key => $value) {
			$monthyears[$value['MonthYear']['id']]=$value['Month']['month_name']."-".$value['MonthYear']['year'];
		} */

		//Check if already month year and marks are entered for this semester for any course
		
		/* $cmCnt = $this->Cae->find('all', array('conditions' => array('Cae.semester_id' => $semester_id),
				 'fields' => array('*') 
		)); */
		
		//pr($cmCnt);
		/* $cmCnt = count($cmCnt);
		if (isset($cmCnt[0]['Cae']['month_year_id'])) {
			$monthYearId = $cmCnt[0]['Cae']['month_year_id'];
			$marks = $cmCnt[0]['Cae']['marks'];
		}
		else {
			echo "hello";
			$monthYearId = "";
			$marks = "";
		} */
		$this->set(compact('course', 'numSemesters', 'monthyears', 'monthYearId', 'marks'));
		$this->layout=false;
	}
	
	public function addInternals($batchId, $academicId, $programId, $cmId, $semesterId, $monthYearId, $marks) {
		$this->loadModel('Cae');
		$auth_user = $this->Auth->user('id');
		//echo $batchId." ".$academicId." ".$programId." ".$cmId." ".$semesterId." ".$monthYearId;
		$cmCnt = $this->Cae->find('all', array('conditions' => array('Cae.course_mapping_id' => $cmId),
				'fields' => array('MAX(Cae.number) as cnt')));
		//pr($cmCnt);
		$caeCount = $cmCnt[0][0]['cnt'];
		$caeNewCounter = $caeCount+1;
		//echo $caeNewCounter;
		$data = array();	
		$data['Cae']['number']=$caeNewCounter;
		$data['Cae']['course_mapping_id']=$cmId;
		$data['Cae']['month_year_id']=$monthYearId;
		$data['Cae']['semester_id']=$semesterId;
		$data['Cae']['marks']=$marks;
		$data['Cae']['created_by']=$auth_user;
		$this->Cae->create();
		$this->Cae->save($data);
							
		$this->set(compact('caeNewCounter'));
		$this->layout=false;
	}
	
	public function findNoOfCAEs($id) {
		$this->loadModel('Cae');
		$this->Cae->recursive = 0;
		$results = $this->Cae->find('all', array('conditions' => array('Cae.course_mapping_id' => $id),
				'fields' => array('MAX(Cae.number) as cnt')
		));
		//pr($results);
		$caeCount = $results[0][0]['cnt'];
		//echo "Count : ".$cnt;
		$this->set(compact('caeCount'));
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
		if (!$this->Cae->exists($id)) {
			throw new NotFoundException(__('Invalid cae'));
		}
		$options = array('conditions' => array('Cae.' . $this->Cae->primaryKey => $id));
		$this->set('cae', $this->Cae->find('first', $options));
	}

/**
 * add method
 *
 * @return void
 */
	public function add() {
		$this->loadModel('Cae');
		if ($this->request->is('post')) {
			$this->Cae->create();
			if ($this->Cae->save($this->request->data)) {
				$this->Flash->success(__('The cae has been saved.'));
				return $this->redirect(array('action' => 'index'));
			} else {
				$this->Flash->error(__('The cae could not be saved. Please, try again.'));
			}
		}
		$courseMappings = $this->Cae->CourseMapping->find('list');
		$semesters = $this->Cae->Semester->find('list');
		$monthYears = $this->Cae->MonthYear->find('list');
		$this->set(compact('courseMappings', 'semesters', 'monthYears'));
	}

/**
 * edit method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function edit($id = null) {
		if (!$this->Cae->exists($id)) {
			throw new NotFoundException(__('Invalid cae'));
		}
		if ($this->request->is(array('post', 'put'))) {
			if ($this->Cae->save($this->request->data)) {
				$this->Flash->success(__('The cae has been saved.'));
				return $this->redirect(array('action' => 'index'));
			} else {
				$this->Flash->error(__('The cae could not be saved. Please, try again.'));
			}
		} else {
			$options = array('conditions' => array('Cae.' . $this->Cae->primaryKey => $id));
			$this->request->data = $this->Cae->find('first', $options);
		}
		$courseMappings = $this->Cae->CourseMapping->find('list');
		$semesters = $this->Cae->Semester->find('list');
		$monthYears = $this->Cae->MonthYear->find('list');
		$this->set(compact('courseMappings', 'semesters', 'monthYears'));
	}

/**
 * delete method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function delete($id = null) {
		$this->Cae->id = $id;
		if (!$this->Cae->exists()) {
			throw new NotFoundException(__('Invalid cae'));
		}
		$this->request->allowMethod('post', 'delete');
		if ($this->Cae->delete()) {
			$this->Flash->success(__('The cae has been deleted.'));
		} else {
			$this->Flash->error(__('The cae could not be deleted. Please, try again.'));
		}
		return $this->redirect(array('action' => 'index'));
	}
	
	public function getCourseTypes($course_type_id) {
		$result = $this->CourseType->find('list', array(
			'conditions' => array('CourseType.id' => $course_type_id),
			'fields' => array('CourseType.course_type'),
			'recursive' => 0
		));
		$course_type = $result[$course_type_id];
		$course_type = array_values(array_filter(explode(" ", trim(str_replace("and", "", $course_type)))));
		//pr($course_type);
		$course_types = array();
		for ($i=0; $i<count($course_type); $i++) {
			$course_types[$course_type[$i]] = $course_type[$i];
		}
		//pr($course_types);
		$this->set(compact('course_types'));
		$this->layout = false;
	}
}