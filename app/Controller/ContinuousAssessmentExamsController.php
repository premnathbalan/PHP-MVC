<?php
App::uses('AppController', 'Controller');
App::import('Vendor', 'PHPExcel', array('file' => 'PHPExcel'.DS.'IOFactory.php'));
App::import('Vendor', 'mPDF', array('file' => 'mPDF'.DS.'mPDF.php'));
/**
 * ContinuousAssessmentExams Controller
 *
 * @property ContinuousAssessmentExam $ContinuousAssessmentExam
 * @property PaginatorComponent $Paginator
 */
class ContinuousAssessmentExamsController extends AppController {
	public $cType = "theory";
	public $uses = array( "ContinuousAssessmentExam", "EsePractical", "CourseStudentMapping", "Course", "User", "Batch", 
		"CourseFaculty", "Student", "Academic", "CaePractical", "Project", "Practical", "CaeProject", "GrossAttendance", 
		"Cae", "CourseMode", "CourseMapping", "MonthYear", "Attendance", "InternalExam", "Program", "CourseType","EseProject",
			"StudentMark", "StudentAuthorizedBreak", "CaePt", "ProfessionalTraining", "InternalPractical", "ProjectReview"
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
	public function index() {
		//$this->redirect("theory");
		return $this->redirect(array('action' => 'theory'));
	}
	public function getExamMonthYear($EMId = null){
		$conditions = array();
		$conditions['MonthYear.id'] = $EMId;
		
		$month_year = $this->MonthYear->find("all", array(
				'conditions'=>$conditions,
				'fields' => array('MonthYear.month_id','MonthYear.year'),
				'contain'=>array(				
						'Month'=>array('fields' =>array('Month.month_name'),)
				),
		));
		$monthYear = $month_year[0]['Month']['month_name']." - ".$month_year[0]['MonthYear']['year'];
		return $monthYear;
	}
	//public function index() {
	public function theory() {
		/* $this->ContinuousAssessmentExam->recursive = 0;
		$this->set('continuousAssessmentExams', $this->Paginator->paginate()); */
		
		$academics = $this->Student->Academic->find('list');
		$batches = $this->Student->Batch->find('list', array('fields' => array('Batch.batch_period'), 'order'=>'Batch.id DESC'));
		
		$monthYears = $this->MonthYear->getAllMonthYears();
		//pr($monthYears);
		
		$action = $this->action;
		$this->set(compact('batches', 'academics', 'programs', 'monthYears'));
	}
	
	/* public function listCourseTypeIdsBasedOnMethod($method_name, $option) {
		$filterCondition="";
		SWITCH ($method_name) {
			case "theory":
				$filterCondition.= "`(CourseType`.`course_type` LIKE '%theory%')";
				break;
			case "practical":
				if ($option == "both") {
					$filterCondition.= "`(CourseType`.`course_type` LIKE '%practical%') OR (CourseType`.`course_type` LIKE 'studio%')";
				}
				else {
					$filterCondition.= "`(CourseType`.`course_type` LIKE 'practical%') OR (CourseType`.`course_type` LIKE 'studio%')";
				}
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
		$explodeCourseType = $this->explodeCourseTypeId($courseTypes);
		return $explodeCourseType;
	}
	
	public function explodeCourseTypeId($courseType) {
		$explodeCourseType = "";
		foreach($courseType as $key => $value) {
			$explodeCourseType.=$key."-";
		}
		$explodeCourseType = substr($explodeCourseType,0,strlen($explodeCourseType)-1);
		return $explodeCourseType;
	} */
/**
 * view method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function view($id = null) {
		if (!$this->ContinuousAssessmentExam->exists($id)) {
			throw new NotFoundException(__('Invalid continuous assessment exam'));
		}
		$options = array('conditions' => array('ContinuousAssessmentExam.' . $this->ContinuousAssessmentExam->primaryKey => $id));
		$this->set('continuousAssessmentExam', $this->ContinuousAssessmentExam->find('first', $options));
	}

/**
 * add method
 *
 * @return void
 */
	public function add() {
		if ($this->request->is('post')) {
			$this->ContinuousAssessmentExam->create();
			if ($this->ContinuousAssessmentExam->save($this->request->data)) {
				$this->Flash->success(__('The continuous assessment exam has been saved.'));
				return $this->redirect(array('action' => 'index'));
			} else {
				$this->Flash->error(__('The continuous assessment exam could not be saved. Please, try again.'));
			}
		}
		$courses = $this->ContinuousAssessmentExam->Course->find('list');
		$students = $this->ContinuousAssessmentExam->Student->find('list');
		$this->set(compact('courses', 'students'));
	}

/**
 * edit method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function edit($id = null) {
		if (!$this->ContinuousAssessmentExam->exists($id)) {
			throw new NotFoundException(__('Invalid continuous assessment exam'));
		}
		if ($this->request->is(array('post', 'put'))) {
			if ($this->ContinuousAssessmentExam->save($this->request->data)) {
				$this->Flash->success(__('The continuous assessment exam has been saved.'));
				return $this->redirect(array('action' => 'index'));
			} else {
				$this->Flash->error(__('The continuous assessment exam could not be saved. Please, try again.'));
			}
		} else {
			$options = array('conditions' => array('ContinuousAssessmentExam.' . $this->ContinuousAssessmentExam->primaryKey => $id));
			$this->request->data = $this->ContinuousAssessmentExam->find('first', $options);
		}
		$courses = $this->ContinuousAssessmentExam->Course->find('list');
		$students = $this->ContinuousAssessmentExam->Student->find('list');
		$this->set(compact('courses', 'students'));
	}

/**
 * delete method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function delete($id = null) {
		$this->ContinuousAssessmentExam->id = $id;
		if (!$this->ContinuousAssessmentExam->exists()) {
			throw new NotFoundException(__('Invalid continuous assessment exam'));
		}
		$this->request->allowMethod('post', 'delete');
		if ($this->ContinuousAssessmentExam->delete()) {
			$this->Flash->success(__('The continuous assessment exam has been deleted.'));
		} else {
			$this->Flash->error(__('The continuous assessment exam could not be deleted. Please, try again.'));
		}
		return $this->redirect(array('action' => 'index'));
	}
	
	/* public function findNoOfCaesWithLinks($cmId, $batchId, $programId) {
		$results = $this->Cae->find('all', array('conditions' => array('Cae.course_mapping_id' => $cmId),
				'fields' => array('MAX(Cae.number) as cnt')
		));
		//pr($results);
		$caeCount = $results[0][0]['cnt'];
		//echo "Count : ".$caeCount;
		$cae=array();
		for($i=1; $i<=$caeCount; $i++) {
			$cae[$i] = "CAE ".$i;	
		}
		$this->set(compact('cae'));
		
		//doubt with jayashree
		$isElective = $this->CourseMapping->find('list', array(
				'fields' => array('CourseMapping.id', 'CourseMapping.course_mode_id'),
				'conditions' => array('CourseMapping.id' => $cmId)
		));
		//pr($isElective);
		$course_mode_id = $isElective[$cmId];
		if ($course_mode_id == 1) {
			//Select students from students table with batch_id and program_id
			//echo "Foundation</br>";
			$stuList = $this->Student->find('all', array(
					'conditions' => array('Student.batch_id' => $batchId, 'Student.program_id' => $programId)
			));
			//pr($stuList);
			//echo count($stuList);
			$this->set(compact('stuList'));
		} 
		else if($course_mode_id == 2) {
			//select students joining course_student_mapping with batch_id, program_id and course_student_mapping_id
			//echo "Elective";
			$options=array(
					'joins' =>
					array(
							array(
									'table' => 'course_student_mappings',
									'alias' => 'CSM',
									'type' => 'right',
									'foreignKey' => false,
									'conditions'=> array('CSM.student_id = Student.id')
							),
							array(
									'table' => 'students',
									'alias' => 'Stu',
									'type' => 'right',
									'foreignKey' => false,
									'conditions'=> array('Stu.id = CSM.student_id')
							),
							array(
									'table' => 'course_mappings',
									'alias' => 'CM',
									'type' => 'right',
									'foreignKey' => false,
									'conditions'=> array('CM.id = CSM.course_mapping_id')
							)
					),
					'conditions' => array(
							array('Student.program_id' => $programId, 'Student.batch_id' => $batchId,
									'CSM.course_mapping_id' => $cmId
							)
					)
			);
			
			$stuList = $this->Student->find('all', $options);
			$this->recursive = -1;
			$this->layout=false;
			//echo  " No of students : ".count($stuList);
			//pr($stuList);
		}
		
		$this->layout=false;
	} */
	
	/* public function findMonthYearByProgram($programId, $batchId, $academicId) {
		$course = array();
		$courseMapping = $this->CourseMapping->find('list', array(
				'fields' => array('CourseMapping.id'),
				'conditions' => array('CourseMapping.batch_id'=>$batchId, 'CourseMapping.program_id' => $programId), 
				'recursive' => 1
		));
		
		$monthYearFromCAE = $this->Cae->find('all', array(
				'fields' => array('DISTINCT MonthYear.id', 'MonthYear.*'),
				'conditions' => array('Cae.course_mapping_id'=>$courseMapping),
				'recursive' => 2
		));
		
		//pr($monthYearFromCAE);
		$monthYear = array();
		for($i=0; $i<count($monthYearFromCAE); $i++) {
			//echo $monthYearFromCAE[$i]['MonthYear']['Month']['month_name']." - ".$monthYearFromCAE[$i]['MonthYear']['year'];
			$monthYear[$monthYearFromCAE[$i]['MonthYear']['id']] = $monthYearFromCAE[$i]['MonthYear']['MonthYear']['Month']['month_name']." - ".$monthYearFromCAE[$i]['MonthYear']['year'];
			//$monthyears[$caeMonthYear['Cae']['month_year_id']] = $caeMonthYear['MonthYear']['Month']['month_name']." - ".$caeMonthYear['MonthYear']['year'];
		}
		//pr($monthYear);
		$this->set(compact('monthYear'));

		$this->layout=false;
	} */
	
	public function listCourseTypesBasedOnMethod($action) {
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
	
	public function caeDisplay($batch_id, $academic_id, $program_id, $month_year_id, $currentMethod=NULL) {
		if (is_null($currentMethod) || $currentMethod=="undefined") $currentMethod="index";
		//echo $currentMethod; 
		
		SWITCH($currentMethod) {
			CASE "practical":
			CASE "studio":
				$currentModel = "CaePractical";
				$currentModelId = 2;
				break;
			CASE "undefined":
			CASE "theory":
			CASE "index":
				$currentModel = "Cae";
				$currentModelId = 1;
				break;
			CASE "project":
				$currentModel = "CaeProject";
				$currentModelId = 3;
				break;
		}
		//echo $currentModel;
		
		$course_type_id =$this->listCourseTypeIdsBasedOnMethod($currentMethod, "-");
		$courseTypeIdArray = explode("-",$course_type_id);
		//pr($courseTypeIdArray);
		
		
			$programArray = array(); $arr=array();
			$courseMapping = $this->CourseMapping->find('all', array(
					'conditions' => array(
							'CourseMapping.program_id' => $program_id,
							'CourseMapping.batch_id' => $batch_id,
							'CourseMapping.month_year_id' => $month_year_id,
							'CourseMapping.indicator' => 0,
					),
					'contain'=>array(
						'Cae'=>array(
							'conditions'=>array('Cae.indicator'=>0),
							'fields'=>array('Cae.course_mapping_id',
									'Cae.marks',
									'Cae.assessment_type',
									'Cae.marks_status',
									'Cae.approval_status',
									'Cae.lecturer_id',
									'Cae.add_status',
									'Cae.id'
							)
						),
						'Batch' => array(
								'fields' => array('Batch.id', 'Batch.batch_period')
						),
						'Program' => array(
								'fields' => array('Program.id', 'Program.program_name', 'Program.short_code'),
								'Academic' => array(
										'fields' => array('Academic.id','Academic.academic_name', 'Academic.short_code')
								),
						),
						'Course'=>array(
							'conditions'=>array('Course.course_type_id' => $courseTypeIdArray,),
							'fields'=>array('Course.id', 'Course.course_name', 'Course.course_type_id', 'Course.course_code', 'Course.common_code')
						)
					),
			));
			//pr($courseMapping);

			$pgmArray = array();
			$arr=array();
				
			foreach ($courseMapping as $courseMapping) {
				if (isset($courseMapping['Cae']) && isset($courseMapping['Course']['course_type_id'])) {
					$caeArray = $courseMapping['Cae'];
					$i=1;
					foreach ($caeArray as $key => $cae) {
				//foreach ($caeDetails as $caeDetail) {
						$arr['batch_id'] = $courseMapping['Batch']['id'];
						$arr['batch'] = $courseMapping['Batch']['batch_period'];
						$arr['academic_id'] = $courseMapping['Program']['academic_id'];
						$arr['program_id'] = $courseMapping['Program']['id'];
						$arr['program'] = $courseMapping['Program']['program_name'];
						$arr['course'] = $courseMapping['Course']['course_name'];
						$arr['course_type_id'] = $courseMapping['Course']['course_type_id'];
						$arr['course_code'] = $courseMapping['Course']['course_code'];
						$arr['assessment_type'] = $cae['assessment_type'];
						$arr['common_code'] = $courseMapping['Course']['common_code'];
						$arr['semester_id'] = $courseMapping['CourseMapping']['semester_id'];
						$arr['caeId'] = $cae['id'];
						$arr['number'] = $i;
						$arr['marks'] = $cae['marks'];
						$arr['marks_status'] = $cae['marks_status'];
						$arr['add_status'] = $cae['add_status'];
						$arr['approval_status'] = $cae['approval_status'];
						$arr['lecturer_id'] = $cae['lecturer_id'];
						array_push($programArray, $arr);
						$i++;
					}
				}
			}
		//}
		$this->set(compact('programArray', 'currentMethod', 'courseMapping', 'currentModel', 'currentModelId'));
		$this->layout=false;
	}
	
	public function getMonthYear($cmId) {
		$my = $this->CourseMapping->find('all', array(
				'conditions' => array('CourseMapping.id' => $cmId),
				//'fields' => array('DISTINCT CourseMapping.month_year_id'),
				'contain'=>array(
						'MonthYear'=>array('fields' => array('MonthYear.year'),
								'Month' => array('fields' => array('Month.month_name'))
						)
				),
				'recursive' => 2
		));
		//pr($my);
		$month_year = $my[0]['MonthYear']['Month']['month_name']." ".$my[0]['MonthYear']['year'];
		return $month_year;
	}
	
	public function getStudents($batchId, $academicId, $programId, $caeId, $maxMarks, $caeNumber, $currentModelId) {
		$modelToSave = $this->modelToSave($currentModelId);
		
		$res = $this->ContinuousAssessmentExam->query("select count(*) as cntRecords from continuous_assessment_exams
				where cae_id=$caeId");
		$cntRecords = $res[0][0]['cntRecords'];
		
		if ($cntRecords > 0) {
			$this->Flash->success(__('Marks already entered.'));
			return $this->redirect(array('controller' => 'ContinuousAssessmentExams', 'action' => 'theory'));
		}
		else {
			$cm = $this->Cae->find('all', array(
					'conditions' => array('Cae.id'=>$caeId),
					'fields'=>array('Cae.course_mapping_id'),
					'contain'=>false
			));
			//pr($cm);
			$cm_id = $cm[0]['Cae']['course_mapping_id'];
			$results = $this->CourseStudentMapping->find('all', array(
					'conditions' => array(
							'CourseStudentMapping.indicator'=>0, 'CourseStudentMapping.course_mapping_id'=>$cm_id
					),
					'fields' => array('CourseStudentMapping.student_id'),
					'contain' => array(
							'Student' => array(
									'conditions' => array('Student.batch_id'=>$batchId, 'Student.program_id'=>$programId, 
											'Student.discontinued_status'=>0),
									'fields'=>array('Student.id', 'Student.name', 'Student.registration_number'),
									'ContinuousAssessmentExam' => array(
											'conditions' => array('ContinuousAssessmentExam.cae_id'=>$caeId),
											'fields'=>array('ContinuousAssessmentExam.marks'),
									)
							)
					)
			));
		/* $cmId = $isElective[0]['CourseMapping']['id'];
		
		$course_mode_id = $isElective[0]['CourseMapping']['course_mode_id'];
		
		$stuList = $this->listStudents($course_mode_id, $programId, $batchId, $cmId);
		
		$courseMappingStudentArray[$cmId] = count($stuList); */
		//echo count($stuList);
		
		$month_year = $this->getMonthYear($cm_id);
		
		//$month_year = $my[$month_year_id];
		
		$this->set(compact('results', 'cm_id', 'stuList', 'batchId', 'academicId', 'programId', 'cmId', 'course_mode_id', 'caeId', 'month_year_id'));
		
		$this->set(compact('stuList', 'batch_period', 'academic_name', 'program_name', 'course_name', 'course_code', 'course_type', 'month_year', 'caeNumber', 'caeOptional', 'maxMarks'));
		
		}
		
		if($this->request->is('post')) {
			//pr($this->data);
			$bool = false;
			$caeId = $this->request->data['CAE']['cae_id'];
			$cmId = $this->request->data['CAE']['course_mapping_id'];
			$totalRecords = count($this->request->data['CAE']['student_id']);
			$students = $this->request->data['CAE']['student_id'];
			//echo $totalRecords;
			$auth_user = $this->Auth->user('id');
			$cnt=0;
			for ($i=0; $i<$totalRecords;$i++) {
				$data = array();
				//echo "<br/>".$data[$postModel]['course_mapping_id']=$cmId;
				$data[$modelToSave['postModel']]['student_id']=$this->request->data['CAE']['student_id'][$i];
				$data[$modelToSave['postModel']][$modelToSave['currentField']]=$caeId;
				if ($this->request->data['CAE']['marks'][$i] <> "") {
					$data[$modelToSave['postModel']]['marks']= $this->request->data['CAE']['marks'][$i];
					$cnt++;
				}
				$data[$modelToSave['postModel']]['created_by']=$auth_user;
				$this->$modelToSave['postModel']->create();
				$this->$modelToSave['postModel']->save($data);
		
				$bool = true;
			}
			$data = array();
			$data[$modelToSave['currentModel']]['id']=$caeId;
			$data[$modelToSave['currentModel']]['add_status'] = 1;
			if($cnt == $totalRecords){
				$data[$modelToSave['currentModel']]['marks_status']="Entered";
			}
			else {
				$data[$modelToSave['currentModel']]['marks_status']="Not Entered";
			}
			$this->$modelToSave['currentModel']->save($data);
			if($modelToSave['currentAction'] == "index") {
				$currentAction = "theory";
			}
			if ($bool) {
				$this->Flash->success(__('The marks have been saved.'));
				return $this->redirect(array('controller' => 'ContinuousAssessmentExams', 'action' => $currentAction));
			} else {
				$this->Flash->error(__('The marks could not be saved. Please, try again.'));
			}
				
		}
	}
	
	public function editStudents($batchId, $academicId, $programId, $caeId, $maxMarks, $caeNumber, $currentModelId) {
				
		SWITCH ($currentModelId) {
			case 1:
				$currentModel = "Cae";
				$currentTable = "caes";
				$currentField = "cae_id";
				$currentAction = "index";
				$postModel = "ContinuousAssessmentExam";
				$postTable = "continuous_assessment_exams";
				break;
			/* case 2:
				$currentModel = "CaePractical";
				$currentTable = "cae_practicals";
				$currentField = "cae_practical_id";
				$currentAction = "practical";
				$postModel = "Practical";
				$postTable = "practicals";
				break;
			case 3:
				$currentModel = "CaeProject";
				$currentTable = "cae_projects";
				$currentField = "cae_project_id";
				$currentAction = "project";
				$postModel = "Project";
				$postTable = "projects";
				break; */
		}
		
		$cm = $this->Cae->find('all', array(
			'conditions' => array('Cae.id'=>$caeId),
			'fields'=>array('Cae.course_mapping_id'),
			'contain'=>false
		));
		//pr($cm);
		$cm_id = $cm[0]['Cae']['course_mapping_id'];
		$results = $this->CourseStudentMapping->find('all', array(
			'conditions' => array(
				'CourseStudentMapping.indicator'=>0, 'CourseStudentMapping.course_mapping_id'=>$cm_id
			),
			'fields' => array('CourseStudentMapping.student_id', 'CourseStudentMapping.indicator'),
			'contain' => array(
				'Student' => array(
					'conditions' => array('Student.batch_id'=>$batchId, 'Student.program_id'=>$programId, 'Student.discontinued_status'=>0),
					'fields'=>array('Student.id', 'Student.name', 'Student.registration_number'),
					'ContinuousAssessmentExam' => array(
						'conditions' => array('ContinuousAssessmentExam.cae_id'=>$caeId),
						'fields'=>array('ContinuousAssessmentExam.marks'),
					),
					'StudentMark' => array(
							'conditions' => array('StudentMark.course_mapping_id'=>$cm_id),
							'fields'=>array('StudentMark.marks'),
					),
				)
			)
		));
		
		$publish_status = $this->StudentMark->find("count", array(
				'conditions'=>array('StudentMark.course_mapping_id'=>$cm_id),
		));
		//pr($publish_status);
		
		$month_year = $this->getMonthYear($cm_id);
		
		$this->set(compact('results', 'stuList', 'batchId', 'academicId', 'programId', 'course_mode_id', 'month_year_id', 'cm_id', 'caeId', 'publish_status'));
		
		$this->set(compact('stuList', 'arrayDiff', 'batch_period', 'academic_name', 'program_name', 'course_name', 'course_code', 'course_type', 'month_year', 'caeNumber', 'maxMarks', 'postTable'));
		
		if($this->request->is('post')) {
			$bool = false;
			//pr($this->data);
			//die;
			$caeId = $this->request->data['CAE']['cae_id'];
			//$cmId = $this->request->data['CAE']['course_mapping_id'];
			$totalRecords = count($this->request->data['CAE']['marks']);
			$auth_user = $this->Auth->user('id');
		
			for ($i=0; $i<$totalRecords;$i++) {
				//echo $this->request->data['CAE']['student_id'][$i]." ".$this->request->data['CAE']['marks'][$i];
				$currentRecord = $this->$postModel->find('first', array(
						'conditions' => array("$postModel.".$currentField => $caeId,
								"$postModel.student_id" => $this->request->data['CAE']['student_id'][$i],
								/* "$postModel.marks" => $this->request->data['CAE']['marks'][$i] */
						),
						'fields' => array("$postModel.id", "$postModel.student_id", "$postModel.marks",
								"$postModel.$currentField"
						),
						'recursive' => -1
				));
				//pr($currentRecord);
				$marks = $this->request->data['CAE']['marks'][$i];
				//echo $currentRecord[$postModel]['marks']." ".$marks;
				if(isset($currentRecord) && count($currentRecord) > 0) {
					if ($currentRecord[$postModel]['marks'] <> $this->request->data['CAE']['marks'][$i]) {
						$this->$postModel->query("UPDATE $postTable set marks='".$this->request->data['CAE']['marks'][$i]."'
							, modified='".date("Y-m-d H:i:s")."' WHERE $currentField=".$caeId."
							AND student_id=".$this->request->data['CAE']['student_id'][$i]);
						//echo "UPDATED";
					}
				}
				else {
					$data = array();
					//echo "<br/>".$data[$postModel]['course_mapping_id']=$cmId;
					$data['ContinuousAssessmentExam']['student_id']=$this->request->data['CAE']['student_id'][$i];
					$data['ContinuousAssessmentExam']['cae_id']=$caeId;
					if ($this->request->data['CAE']['marks'][$i] <> "") {
						$marks = $this->request->data['CAE']['marks'][$i];
					}
					else {
						$marks = 0;
					}
					$data['ContinuousAssessmentExam']['marks'] = $marks;
					$data['ContinuousAssessmentExam']['created_by']=$auth_user;
					$this->ContinuousAssessmentExam->create();
					$this->ContinuousAssessmentExam->save($data);
					
				}
				$bool=true;
			}
			$cntCaeRecords = $this->$postModel->find('count', array(
					'conditions' => array("$postModel.".$currentField => $caeId,
							"$postModel.marks !=" => "",
							/* "$postModel.marks" => $this->request->data['CAE']['marks'][$i] */
					)
			));
				
			$data = array();
			$data[$currentModel]['id']=$caeId;
			$data[$currentModel]['approval_status']=0;
			if($cntCaeRecords == $totalRecords) {
				$data[$currentModel]['marks_status']="Entered";
			}
			/*else {
				$data[$currentModel]['marks_status']="Not Entered";
			}*/
			$this->$currentModel->save($data);
			if($currentAction == "index") {
				$currentAction = "theory";
			}
			if ($bool) {
				$this->Flash->success(__('The marks has been edited.'));
				return $this->redirect(array('controller' => 'ContinuousAssessmentExams', 'action' => $currentAction));
			} else {
				$this->Flash->error(__('The marks could not be edited. Please, try again.'));
			}
				
		}
		
	}
	
	public function getCourseMappingArray($res) {
		$courseMapping = array();
		foreach ($res as $result) {
			if(!in_array($result['CourseMapping']['id'], $courseMapping)) {
				$courseMapping[$result['CourseMapping']['id']]=$result['CourseMapping']['id'];
			}
		}
		//pr($courseMapping);
		return $courseMapping;
	}
	
	public function calculateCAEMarks() {
		$access_result = $this->checkPathAccess($this->Auth->user('id'));
		if (!$access_result) {
		$academics = $this->Student->Academic->find('list');
		$batches = $this->Student->Batch->find('list', array('fields' => array('Batch.batch_period')));
		$this->set(compact('batches', 'academics'/* , 'monthYears' */));
		
		if($this->request->is('post')) {
			//pr($this->data);
			
			$batch_id = $this->request->data['Student']['batch_id'];
			$academic_id = $this->request->data['Student']['academic_id'];
			$program_id = $this->request->data['Student']['program_id'];
			$month_year_id = $this->request->data['month_year_id'];
			
			/* $cmAll = $this->courseCodeForASemester($batch_id, $program_id, $month_year_id, $courseTypeIdArray);
			$cm = $this->truncateEmptyCmIds($cmAll);
			*/
			$models = array('Cae');
			$tables = array('caes');
			
			$courseType = $this->listCourseTypeIdsBasedOnMethod($this->cType, "-");
			$courseTypeIdArray = explode("-",$courseType);
			
			$finalArray = $this->CourseMapping->retrieveCourseMappingWithBatchProgramAndCourseType($batch_id, $program_id, $courseTypeIdArray, $month_year_id);
			//pr($finalArray);
			
			//$splitUpDetails = $this->splitUpCourseMapping($allCourseMapping);
			//$noCourseMappingArray = $splitUpDetails['noCaes'];
			
			//$finalArray = $this->getCourseMappingNew($program_id, $batch_id, $academic_id, $month_year_id, $models, $tables);
			//pr($finalArray);

			$courseMapping = $this->getCourseMappingArray($finalArray);
			
			$this->set(compact('courseMapping'));
			
			//Modified now.....
			//$finalArray = $this->getCaesWithCourseTypeId($res);
			
			$allStudents = $this->getStudentsWithBatchAndProgram($batch_id, $program_id, $month_year_id);
			//pr($allStudents);
			
			$this->set(compact('allStudents'));
			
			$studentInternalMarks = $this->calculateMaxMarks($finalArray, $allStudents, $month_year_id, $program_id, $batch_id);
			//pr($studentInternalMarks);
			
			$this->set(compact('studentInternalMarks', 'allStudents'));
			
			$programs = $this->Student->Program->find('list', array(
					'conditions' => array('Program.academic_id'=> $this->request->data['Student']['academic_id'])));
			$month_year_id = $this->request->data['month_year_id'];
			$this->set(compact('programs', 'month_year_id'));
			
			//$this->recursive = 1;
		//	pr($res);
		}
		} else {
			$this->render('../Users/access_denied');
		}
	}
	
	
	public function getCaesWithCourseTypeId($res) {
		//pr($res); 
		//die;
	}
	
	public function getStudentsWithBatchAndProgram($batch_id, $program_id, $month_year_id) {
		$this->loadModel('Student');
		$studentOptions = array(
				'conditions' => array(
						array('Student.batch_id' => $batch_id,
								'Student.program_id' => $program_id,
								'Student.discontinued_status' => 0
						)
				),
				'fields' => array('Student.id', 'Student.registration_number',
						'Student.name'
				),
				//'recursive' => 0,
				'contain'=>array(
						'CourseStudentMapping'=>array(
							'conditions'=>array('CourseStudentMapping.indicator'=>0),
							'fields'=>array('CourseStudentMapping.course_mapping_id', 'CourseStudentMapping.type',
								'CourseStudentMapping.new_semester_id'
							),
							'CourseMapping'=>array(
								'fields'=>array('CourseMapping.id', 'CourseMapping.month_year_id')
							)
						)
				)
		);
		$students = $this->Student->find("all", $studentOptions);
		$this->loadModel('ContinuousAssessmentExam');
		//pr($students);
		return $students;
	}
	
	
	
	
	
	public function getAttendance($cmId) {
		//echo $cmId;
		$options = array(
					'conditions' => array(
							array('Attendance.course_mapping_id' => $cmId
							)
					),
					'fields' => array('Attendance.student_id', 'Attendance.percentage')
			);
		$res = $this->Attendance->find('list', $options);
		return $res;
	}
	
	public function getGrossAttendance($month_year_id, $program_id) {
		//echo $cmId;
		$options = array(
				'conditions' => array(
						array('GrossAttendance.month_year_id' => $month_year_id,
								'GrossAttendance.program_id' => $program_id,
						)
				),
				'fields' => array('GrossAttendance.student_id', 'GrossAttendance.percentage')
		);
		$res = $this->GrossAttendance->find('list', $options);
		//pr($res);
		return $res;
	}
	
	public function retrieveStudentsWithCourseMappingId($program_id, $batch_id, $cmId) {
		$options=array(
				'joins' =>
				array(
						array(
								'table' => 'course_student_mappings',
								'alias' => 'CourseStudentMapping',
								'type' => 'right',
								'foreignKey' => false,
								'conditions'=> array('CourseStudentMapping.student_id = Student.id', 
								)
						),
				),
				'conditions' => array(
						array('Student.program_id' => $program_id, 'Student.batch_id' => $batch_id,
								'Student.discontinued_status' => 0,
								'CourseStudentMapping.course_mapping_id' => $cmId,
								'CourseStudentMapping.indicator' => 0,
						)
				), 'recursive' => 0
		);
		
		$stuList = $this->Student->find('list', $options);
		return $stuList;
	}
	
	public function computeCaeAndAttendance($result, $attendanceResult) {
		//pr($result);
		$caeTheoryResult = array();
		foreach ($result as $student_id => $marks) {
			$actualScore = $marks + $attendanceResult[$student_id];
			$caeTheoryResult[$student_id] = $marks + $attendanceResult[$student_id];
		}
		//echo "test";
		//pr($caeTheoryResult);
		return $caeTheoryResult;
	}
	
	public function calculateMaxMarks($finalArray, $allStudents, $month_year_id, $program_id, $batch_id) {
		//pr($finalArray);
				//$electives = $this->electives();
		//pr($electives);
		$students = array();
		$studentInternalMarks = array();
		for ($i=0; $i<count($finalArray); $i++) {
			//echo "CourseMapping ID: ".$finalArray[$i]['CourseMapping']['id'];
			$cmId = $finalArray[$i]['CourseMapping']['id'];
			
			$course_max_marks = $finalArray[$i]['Course']['course_max_marks'];
			$min_cae_mark = $finalArray[$i]['Course']['min_cae_mark'];
			$min_ese_mark = $finalArray[$i]['Course']['min_ese_mark'];
			$max_cae_mark = $finalArray[$i]['Course']['max_cae_mark'];
			$max_ese_mark = $finalArray[$i]['Course']['max_ese_mark'];
			$total_min_pass = $finalArray[$i]['Course']['total_min_pass'];
			
			//$course_mode_id = $cm[0]['CourseMapping']['course_mode_id'];
			$students = $this->retrieveStudentsWithCourseMappingId($program_id, $batch_id, $cmId);
			//pr($students);
			//die;
			$course_type_id = $finalArray[$i]['Course']['course_type_id'];
			//pr($course_type_id);
			//pr($this->courseTypeIds);
			
			$tmpStudents = array();
			SWITCH ($course_type_id) {
				CASE 1:
				CASE 3:
					$result = array();
					$caeArray = $finalArray[$i]['Cae'];
					$caeTheory = "";
					$caeArray = $this->Cae->find('all', array(
							'conditions' => array(
											'Cae.course_mapping_id' => $cmId, 'Cae.indicator' => 0
											),
							'recursive' => -1
					));
					foreach ($caeArray as $cArray) {
						$caeTheory.=$cArray['Cae']['id'].",";
					}
					$caeTheory = substr($caeTheory,0,strlen($caeTheory)-1);
					//echo $caeTheory;
					
					//$courseMaxMarks = 
					$convertTo = $max_cae_mark-5;
					
					$caeResult = $this->computeCae($students, $caeTheory, $convertTo, $max_cae_mark);
					//echo "Marks";
					//pr($caeResult);
					$attendance = $finalArray[$i]['Attendance'];
					//pr($attendance);
					
					$attendanceResult = $this->attendance($attendance, $month_year_id, $program_id);
					//echo "attendance result";
					//pr($attendanceResult);
					
					$result = $this->computeCaeAndAttendance($caeResult, $attendanceResult);
					//pr($result);
					break;
			} 
			//pr($result);
			$this->saveInternalExam($cmId, $result, $month_year_id);
			unset($result);
			$internalResult = $this->InternalExam->find('all', array(
					'conditions' => array("InternalExam.course_mapping_id" => $cmId),
					'fields' => array('InternalExam.student_id', 'InternalExam.marks'),
					'recursive' => 0,
					'order' => array('InternalExam.student_id ASC')
			));
			//pr($internalResult);
			$tmpArray = array();
			foreach ($internalResult as $intResult) {
				$tmpArray[$intResult['InternalExam']['student_id']]=$intResult['InternalExam']['marks'];
			}
			$studentInternalMarks[$cmId] = $tmpArray;
			//array_push($studentInternalMarks, $tmpStudentInternalMarks);
		}
		//pr($studentInternalMarks);
		
		return $studentInternalMarks;
	}
	
	public function moderateInternal($marks) {
		$modMarkArray = array(0, 1, 2, 3, 4, 5, 6, 10);
		$modOperator = "plus";
		
		if ($marks >=0 and $marks<=5) {
			$marks = $modMarkArray[7];
			$modMarks = $modMarkArray[7];
		}
		else if ($marks >=6 and $marks<=10) {
			$marks = $marks + $modMarkArray[6];
			$modMarks = $modMarkArray[6];
		}
		else if ($marks >=11 and $marks<=24) {
			$marks = $marks + $modMarkArray[5];
			$modMarks = $modMarkArray[5];
		}
		else if ($marks >=25 and $marks<=34) {
			$marks = $marks + $modMarkArray[4];
			$modMarks = $modMarkArray[4];
		}
		else if ($marks >=35 and $marks<=44) {
			$marks = $marks + $modMarkArray[3];
			$modMarks = $modMarkArray[3];
		}
		else if ($marks >=45 and $marks<=48) {
			$marks = $marks + $modMarkArray[2];
			$modMarks = $modMarkArray[2];
		}
		else if ($marks >=49 and $marks<=49) {
			$marks = $marks + $modMarkArray[1];
			$modMarks = $modMarkArray[1];
		}
		else if ($marks >=50 and $marks<=50) {
			$marks = $marks;
			$modMarks = $modMarkArray[0];
		}
		else {
			$marks = 0;
			$modMarks = $modMarkArray[0];
		}
		
		$modArray = array(
			"marks" => $marks,
			"modMark" => $modMarks,
			"modOperator" => $modOperator
		);
		return $modArray;
	}
	
	public function saveInternalExam($cmId, $result, $month_year_id) {
		//pr($result);
		if (isset($result)) {
			foreach ($result as $studentId => $marks) {
				$modResult = $this->moderateInternal($marks);
				//pr($modResult);
				$conditions = array(
						'InternalExam.course_mapping_id' => $cmId,
						'InternalExam.student_id' => $studentId
				);
				if ($this->InternalExam->hasAny($conditions)){
					$this->InternalExam->query("UPDATE internal_exams set marks=".$modResult['marks'].",
									month_year_id=$month_year_id,
									moderation_operator='".$modResult['modOperator']."',
									moderation_marks=".$modResult['modMark'].",
									moderation_date='',
									modified='".date("Y-m-d H:i:s")."' WHERE course_mapping_id=".$cmId."
									AND student_id=".$studentId);
				}
				else {
					$data=array();
					$data['InternalExam']['month_year_id'] = $month_year_id;
					$data['InternalExam']['course_mapping_id'] = $cmId;
					$data['InternalExam']['student_id'] = $studentId;
					$data['InternalExam']['marks'] = $modResult['marks']; 
					$data['InternalExam']['moderation_operator'] = $modResult['modOperator']; 
					$data['InternalExam']['moderation_marks'] = $modResult['modMark']; 
					$data['InternalExam']['moderation_date'] = '';
					$data['InternalExam']['created_by'] = $this->Auth->user('id');
					$this->InternalExam->create();
					$this->InternalExam->save($data);
				}
				/* $dbo = $this->InternalExam->getDatasource();
				$logs = $dbo->getLog();
				$lastLog = end($logs['log']);
				echo $lastLog['query']; */
			}
		}
		$this->Flash->success(__('The internal marks have been calculated.'));
	}
	
	public function attendance($attendance, $month_year_id, $program_id) {
		if (isset($attendance) && count($attendance)>0) {
			$attArray = array();
			for($i=0; $i<count($attendance); $i++) {
				$attArray[$attendance[$i]['student_id']] = $attendance[$i]['percentage'];
			}
			$attendanceArray = $this->computeAttendance($attArray);
		}
		else if (empty($attendance)) {
			$attendance = $this->getGrossAttendance($month_year_id, $program_id);
			//echo "gross";
			//pr($attendance);
			$attendanceArray = $this->computeAttendance($attendance);
			//pr($attendanceArray);
		}
		return $attendanceArray;
	}
	
	public function computeAttendance($attendance) {
		//pr($attendance);
		$attendanceArray = array();
		//for($i=1; $i<=count($attendance); $i++) {
		foreach($attendance as $student_id => $attendancePercent) {
			//$attendancePercent = $attendance[$student_id];
			//echo $attendancePercent;
			//echo "</br>";
			if ($attendancePercent >0 and $attendancePercent<=50) {
				$attendanceMark = 1;
			}
			else if ($attendancePercent >50 and $attendancePercent<=59) {
				$attendanceMark = 2;
			}
			else if ($attendancePercent >60 and $attendancePercent<=69) {
				$attendanceMark = 3;
			}
			else if ($attendancePercent >70 and $attendancePercent<=79) {
				$attendanceMark = 4;
			}
			else if ($attendancePercent >80) {
				$attendanceMark = 5;
			}
			else {
				$attendanceMark = 0;
			}
			$attendanceArray[$student_id] = $attendanceMark;
		}
		//pr($attendanceArray);
		return $attendanceArray;
	}
	
	public function computeCae($students, $caes, $convertTo, $marks) {
		//echo $convertTo." ".$marks;
		//pr($students);
		//pr($caes);
		$studentArray = array();
		//pr($students);
		//echo "test : ".$marks;
		$marksDouble = $marks*2;
		//echo "Convert : ".$convertTo;
		$caesArray = explode(",", $caes);
		//echo "CAE Count : ".count($caesArray)." ".$caes;
		//for($i=1; $i<=count($students); $i++) {
		foreach($students as $student_id => $student_name) {
			$studentId = $student_id;
			$absentResult = $this->ContinuousAssessmentExam->query("SELECT count(marks) as absent_count FROM continuous_assessment_exams
					where student_id=$studentId and cae_id in($caes) and (marks='A' or marks='a')");
			$absent_count = $absentResult[0][0]['absent_count'];
			//pr($absent_count);
			//echo "</br>";
			SWITCH ($absent_count) {
				CASE 2:
					//echo "2 absent";
					$stuResult = $this->ContinuousAssessmentExam->query("SELECT $convertTo * marks / $marks as  marks FROM
					continuous_assessment_exams where student_id=$studentId and (marks<>'A' or marks<>'a') and cae_id in($caes)");
					//$studentMark = $stuResult[0][0]['marks'];
					//break;	
					break;
				CASE 1:
					//echo "1 absent";
					$stuResult = $this->ContinuousAssessmentExam->query("SELECT $convertTo * sum(marks) / $marksDouble as marks FROM
					(SELECT marks FROM continuous_assessment_exams
					where student_id=$studentId and (marks<>'A' or marks<>'a') and cae_id in($caes)
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
							where student_id=$studentId and (marks<>'A' or marks<>'a') and cae_id in($caes)
							ORDER by marks DESC LIMIT 2) as marks");
					//$studentMark = $stuResult[0][0]['marks'];
					/* $dbo = $this->CourseMapping->getDatasource();
					$logs = $dbo->getLog();
					$lastLog = end($logs['log']);
					echo $lastLog['query']; */
					break;
			}
			$studentMark = 0;
			if(isset($stuResult[0][0]['marks'])) {
				$studentMark = round($stuResult[0][0]['marks']);
			}
			//echo "</br>".$studentId." ".$studentMark;
			$studentArray[$studentId] = $studentMark;
			//pr($studentArray);
			//echo "</br>".$studentId." ".$absent_count." ".$studentMark;
		}
		//pr($studentArray);
		return $studentArray;
	}
	
	/* public function computeProject($students, $project) {
		foreach($students as $student_id => $student_name) {
			$studentId = $student_id;
			$stuResult = $this->CaeProject->query("SELECT sum(marks) as marks FROM
					projects where student_id=$studentId and cae_project_id in ($project)"
					);
			$studentMark = $stuResult[0][0]['marks'];
			$studentArray[$studentId] = $studentMark;
		}
		return $studentArray;
	}
	
	public function computePractical($students, $practical, $convertTo, $marks) {
		//echo $practical;
		if ($convertTo > 0) {
			$varMarks = "round($convertTo * sum(marks) / $marks)";
		}
		else {
			$varMarks = "sum(marks)";
		}
		//echo $varMarks;
		foreach($students as $student_id => $student_name) {
			$studentId = $student_id;
			$stuResult = $this->CaePractical->query("SELECT $varMarks as marks FROM
					practicals where student_id=$studentId and cae_practical_id in ($practical)"
			);
			$studentMark = $stuResult[0][0]['marks'];
			$studentArray[$studentId] = $studentMark;
		}
		return $studentArray;
	} */
	
	public function getCourseStudentMapping($courseMapping, $batch_id, $program_id) {
		//$electives = $this->electives();
		$courseMappingStudentArray = array();
		foreach ($courseMapping as $cmId => $caeIdArray) {
			$checkElective = $this->CourseMapping->find('first', array(
					'conditions' => array('CourseMapping.id' => $cmId),
					'fields' => array('CourseMapping.course_mode_id'),
					'recursive' => 0
			));

				$options=array(
						'joins' =>
						array(
								array(
										'table' => 'course_student_mappings',
										'alias' => 'CourseStudentMapping',
										'type' => 'right',
										'foreignKey' => false,
										'conditions'=> array('CourseStudentMapping.student_id = Student.id')
								),
						),
						'conditions' => array(
								array('Student.program_id' => $program_id, 'Student.batch_id' => $batch_id,
										'CourseStudentMapping.course_mapping_id' => $cmId,
								)
						), 'recursive' => 0
				);
				$stuList = $this->Student->find('all', $options);
			/* }
			else {
				$stuList = $this->Student->find('all', array(
						'conditions' => array('Student.batch_id' => $batch_id, 'Student.program_id' => $program_id),
						'fields' => array('Student.id', 'Student.registration_number', 'Student.name'),
						'recursive' => 1
				));
			} */
			$courseMappingStudentArray[$cmId] = count($stuList);
			//echo count($stuList);
		}
		return $courseMappingStudentArray;
	}
	
	/* public function findSemesterForACourseMappingId($cmId) {
		$allCourseMapping = $this->CourseMapping->find('all', array(
								'conditions' => array('CourseMapping.id' => $cmId),
								'fields' => array('CourseMapping.semester_id'),
								'recursive' => -1
							));
		//pr($allCourseMapping);
		$semester_id = $allCourseMapping[0]['CourseMapping']['semester_id'];
		return $semester_id;
	} */
	
	/* public function retrieveAllCoursesFromBatchProgramAndFirstSemester($batch_id, $program_id, $firstSemester) {
		$allCourseMapping = $this->CourseMapping->find('list', array(
				'conditions' => array('CourseMapping.batch_id' => $batch_id,
										'CourseMapping.program_id' => $program_id,
											'CourseMapping.semester_id' => $firstSemester
				),
				'fields' => array('CourseMapping.id'),
				'recursive' => -1
		));
		return $allCourseMapping;
	} */
	
	public function splitUpCourseMapping($allCourseMapping) {
		//echo "</br>";
		//pr($allCourseMapping);
		$finalArray = array();
		$courseMappingArray = array();
		$noCourseMappingArray = array();
		foreach ($allCourseMapping as $key => $value) {
			if (count($value['Cae']) > 0 && isset($value['Cae'])) {
				$caeArray = $value['Cae'];
				foreach ($caeArray as $key => $caeDetails) {
					if ($caeDetails['indicator'] == 0) {
						$courseMappingArray[$value['CourseMapping']['id']] = $value['Cae'];
					}
					else {
						$noCourseMappingArray[$value['CourseMapping']['id']] = $value['Course']['course_code'];
					}
				}
			}
			else {
				$noCourseMappingArray[$value['CourseMapping']['id']] = $value['Course']['course_code'];
			}
		}
		//pr($courseMappingArray);
		//pr($noCourseMappingArray);
		$finalArray['caes'] = $courseMappingArray;
		$finalArray['noCaes'] = $noCourseMappingArray;
		return $finalArray;
	}
	
	public function getCaeStatus($program_id, $batch_id, $academic_id, $month_year_id) {
		//echo $program_id." ".$batch_id." ".$academic_id." ".$month_year_id;
		//pr($this->findCoursesByProgram($program_id, $batch_id, $academic_id)); die;
		$courseType = $this->listCourseTypeIdsBasedOnMethod($this->cType, "-");
		$courseTypeIdArray = explode("-",$courseType);
		
		$allCourseMapping = $this->CourseMapping->retrieveCourseMappingWithBatchProgramAndCourseType($batch_id, $program_id, $courseTypeIdArray, $month_year_id);
		//pr($allCourseMapping);
		
		$splitUpDetails = $this->splitUpCourseMapping($allCourseMapping);
		//echo "</br>";
		//pr($splitUpDetails);
		$noCourseMappingArray = $splitUpDetails['noCaes'];

		$totalAssessmentCount = 0;
		$assessmentCount = 0;
		$editCount = 0;
		$courseMapping = $splitUpDetails ['caes'];
		foreach ( $courseMapping as $cmId => $caeArray ) {
			foreach ( $caeArray as $key => $caeDetails ) {
				
				if ($caeDetails ['indicator'] == 0) {
					$totalAssessmentCount = $totalAssessmentCount + 1;
					if ($caeDetails ['marks_status'] == "Entered") {
						$assessmentCount += 1;
					}
					if ($caeDetails ['approval_status']) {
						$editCount += 1;
					}
				}
			}
		}
		
		$courseStudentMapping = $this->getCourseStudentMapping ( $courseMapping, $batch_id, $program_id );
		//pr($courseStudentMapping);
		
		$allStudents = $this->getStudentsWithBatchAndProgram ( $batch_id, $program_id, $month_year_id);
		
		$attendanceArray = $this->getAttendanceStatus ( $courseMapping, $month_year_id, $courseStudentMapping, $allStudents, $program_id );
		//pr($attendanceArray);
		
		$studentInternalMarks = $this->getInternalMarks ( $courseMapping, $allStudents );
		//pr($studentInternalMarks);
		
		$attendanceCount = $attendanceArray ['attendanceCount'];
		$numOfCourses = $attendanceArray ['numOfCourses'];
		//echo "Total Assessment : ".$totalAssessmentCount." Assessment Count : ".$assessmentCount." Edit Status : ".$editCount;
		
		$this->set ( compact ( 'assessmentCount', $assessmentCount ) );
		$this->set ( compact ( 'totalAssessmentCount', $totalAssessmentCount ) );
		$this->set ( compact ( 'attendanceCount', $attendanceCount ) );
		$this->set ( compact ( 'numOfCourses', $numOfCourses ) );
		$this->set ( compact ( 'allStudents', $allStudents ) );
		$this->set ( compact ( 'editCount', $editCount ) );
		$this->set ( compact ( 'courseMapping', $courseMapping ) );
		$this->set ( compact ( 'noCourseMappingArray', $noCourseMappingArray ) );
		$this->set ( compact ( 'studentInternalMarks', $studentInternalMarks ) );
		$this->layout=false;
	}
	
	/* public function electives() {
		$electivesArray = $this->CourseMode->find('all', array(
				'conditions' => array('CourseMode.course_mode LIKE' => '%Electives%'),
				'fields' => array('CourseMode.id'),
				'recursive' => -1
		));
		//pr($electivesArray);
		$electives=array();
		foreach ($electivesArray as $electivesArray) {
			$electives[]=$electivesArray['CourseMode']['id'];
		}
		return $electives;
	} */
	
	public function getInternalMarks($courseMapping, $students) {
		$studentMarks = array();
		foreach ($courseMapping as $cmId => $caeIdArray) {
			$tmpStudentMarks[$cmId]=array();
			//pr($cmId);
			$result = $this->InternalExam->find('all', array(
					'conditions' => array('InternalExam.course_mapping_id' => $cmId),
					'fields' => array('InternalExam.student_id', 'InternalExam.marks'),
					'order' => array('InternalExam.student_id ASC'),
					'recursive' => 0
			));
			$arr = array(); 
			foreach ($result as $key => $internalDetails) {
				if(isset($internalDetails) && count($internalDetails)>0) {
					$arr[$internalDetails['InternalExam']['student_id']] = $internalDetails['InternalExam']['marks'];
				}
			}
			$studentMarks[$cmId] = $arr;
		}
		return $studentMarks;
	}
	
	public function getAttendanceStatus($courseMapping, $month_year_id, $courseStudentMapping, $studentArray, $program_id) {
		$attendanceCount = 0;
		//echo $program_id;
		//echo "hello"." ".pr($caeArray);
		//pr($courseStudentMapping);
		$noOfCourses = count($courseMapping);
		foreach ($courseMapping as $cmId => $caeIdArray) {
			$courseAttendanceCount = $this->Attendance->find('count', array(
					'conditions' => array('Attendance.course_mapping_id' => $cmId,
											'Attendance.percentage !=' => ''),
					'recursive' => 0
			));
			if ($courseAttendanceCount == $courseStudentMapping[$cmId]) {
				$attendanceCount++;
			}
			else if ($courseAttendanceCount == 0) {
				$grossAttendanceCount = $this->GrossAttendance->find('count', array(
						'conditions' => array('GrossAttendance.program_id' => $program_id,
												'GrossAttendance.month_year_id' => $month_year_id,
											'GrossAttendance.percentage !=' => ''),
						'recursive' => 0
				));
				//pr(count($studentArray));
				//pr($grossAttendanceCount);
				if ($grossAttendanceCount >= count($studentArray)) {
					$attendanceCount++;
				}
			}
		}
		$attArray = array(
				"attendanceCount" => $attendanceCount,
				"numOfCourses" => $noOfCourses
		);
		//pr($attArray);
		return ($attArray);
	}
	
	/* public function getCourseMapping($program_id=NULL, $batch_id=NULL, $academic_id=NULL, $month_year_id=NULL, $models, $tables) {
		$models = array('Cae');
		$tables = array('caes');
		$finalArray = array();
		for ($i=0; $i<count($models); $i++) {
			$options=array(
					'joins' =>
					array(
							array(
									'table' => $tables[$i],
									'alias' => $models[$i],
									'type' => 'left',
									'foreignKey' => false,
									'conditions'=> array("$models[$i].course_mapping_id = CourseMapping.id",
											"$models[$i].indicator" => 0
									)
							)
					),
					'conditions' => array(
							array('CourseMapping.batch_id' => $batch_id,
									'CourseMapping.program_id' => $program_id,
									'CourseMapping.indicator' => 0,
									"$models[$i].indicator" => 0
							)
					),
					'fields' => array('DISTINCT CourseMapping.id'),
					'recursive' => 1
			);
			$res = $this->$models[$i]->CourseMapping->find('all', $options);
			foreach ($res as $res) {
				array_push($finalArray, $res);
			}
		}
		
		return $finalArray;
	} */
	
	/* public function getCourseMappingNew($program_id=NULL, $batch_id=NULL, $academic_id=NULL, $month_year_id=NULL, $models, $tables) {

		$finalArray = array();
		for ($i=0; $i<count($models); $i++) {
			$options=array(
					'joins' =>
					array(
							array(
									'table' => $tables[$i],
									'alias' => $models[$i],
									'type' => 'right',
									'foreignKey' => false,
									'conditions'=> array("$models[$i].course_mapping_id = CourseMapping.id",
											"$models[$i].indicator" => 0
									)
							)
					),
					'conditions' => array(
							array('CourseMapping.batch_id' => $batch_id,
									'CourseMapping.program_id' => $program_id,
									'CourseMapping.indicator' => 0,
									"$models[$i].indicator" => 0
							)
					),
					'fields' => array('DISTINCT CourseMapping.id'),
					'recursive' => 1
			);
			$res = $this->CourseMapping->find('all', $options);
			foreach ($res as $res) {
				array_push($finalArray, $res);
			}
		}
	
		return $finalArray;
	} */
	
	public function individualCaeMarks() {
		$academics = $this->Student->Academic->find('list');
		$batches = $this->Student->Batch->find('list', array('fields' => array('Batch.batch_period')));
		$this->set(compact('batches', 'academics'/* , 'monthYears' */));
	}
	
	public function approveCae() {
		$academics = $this->Student->Academic->find('list');
		$batches = $this->Student->Batch->find('list', array('fields' => array('Batch.batch_period')));
		$this->set(compact('batches', 'academics'));
	}
	
	public function approveCaeExam($program_id, $batch_id, $academic_id) {
		$courses = array();
		$courseCAEs = array();
		$courseMapping = $this->ContinuousAssessmentExam->Cae->CourseMapping->find('all', 
				array('conditions' => array('CourseMapping.Program_id' => $program_id,
				'CourseMapping.batch_id' => $batch_id,
				'CourseMapping.indicator' => 0)));
		//pr($courseMapping);
		foreach ($courseMapping as $key => $value) {
			//pr($value);
			//echo "CM id ".$value['CourseMapping']['id'];
			$course[$value['CourseMapping']['id']]=$value['Course']['course_name'];
			$cmId = $value['CourseMapping']['id'];
			$this->loadModel('Cae');

			$numOfCAEs = $this->Cae->find('all', array(
					'conditions' => array('Cae.course_mapping_id' => $cmId),
					'fields' => array('Cae.number', 'Cae.id', 'Cae.approval_status'),
					'recursive' => 0
			));
			//pr($numOfCAEs);
			$numCAEs = count($numOfCAEs);
			$courseCAEs[$value['CourseMapping']['id']]=$numOfCAEs;
			
			$crseAsstDetails[$cmId]=array();
			for($i=0; $i<count($numOfCAEs); $i++) {
				//$caeID = $numOfCAEs[$i];
				//pr($numOfCAEs);
				$caeId = $numOfCAEs[$i]['Cae']['id'];
				//echo $caeId." ";
				$res = $this->ContinuousAssessmentExam->query("select count(*) as cntRecords from continuous_assessment_exams where
													cae_id=$caeId");
				//pr($res);
				$cntRecords = $res[0][0]['cntRecords'];
				if($cntRecords > 0) {
					$crseAsstDetails[$cmId][$i]=$cntRecords;
				}
			}
			
		}
		//pr($courseCAEs);
		
		//pr($crseAsstDetails);
		//pr($course);
		//pr($courseCAEs);
		$this->set(compact('course' , 'courseCAEs', 'batch_id', 'program_id', 'academic_id', 'crseAsstDetails', 'numOfCAEs'));
		$this->layout=false;
	}
	
	public function viewCae($batchId, $academicId, $programId, $caeId, $caeNumber, $currentModelId) {
		//echo $currentModelId;
		SWITCH ($currentModelId) {
			case 1:
				$currentModel = "Cae";
				$currentTable = "caes";
				$currentField = "cae_id";
				$currentAction = "index";
				$postModel = "ContinuousAssessmentExam";
				$postTable = "continuous_assessment_exams";
				break;
			/* case 2:
				$currentModel = "CaePractical";
				$currentTable = "cae_practicals";
				$currentField = "cae_practical_id";
				$currentAction = "practical";
				$postModel = "Practical";
				$postTable = "practicals";
				break;
			case 3:
				$currentModel = "CaeProject";
				$currentTable = "cae_projects";
				$currentField = "cae_project_id";
				$currentAction = "project";
				$postModel = "Project";
				$postTable = "projects";
				break; */
		}
		//echo $postTable." ".$currentField;
		
		$entered = $this->$currentModel->find("count", array(
				'conditions' => array("$currentModel.marks_status" => "Entered",
									"$currentModel.id" => $caeId)
		));
		if ($entered) {
			$enteredStatus = TRUE;
		}
		
		
		/* $caeDetails = $this->$currentModel->query("select * from $postTable where
				$currentField=$caeId order by student_id"); */
		//pr($caeDetails); 
		
		$this->set(compact('cmId', 'caeId', 'caeDetails'));
		
		$cm_id_result = $this->Cae->find('all', array(
				/* 'fields' => array('CourseMapping.id', 'CourseMapping.course_mode_id'), */
				'conditions' => array('Cae.id' => $caeId)
		));
		//pr($cm_id_result);
		
		//$caeNumber = $isElective[0]['Cae']['number'];
		$cmId = $cm_id_result[0]['CourseMapping']['id'];
		$course_mode_id = $cm_id_result[0]['CourseMapping']['course_mode_id'];
		$approvalStatus = $cm_id_result[0]['Cae']['approval_status'];
		
		//echo $cmId." ".$course_mode_id;       
		//$stuList = $this->retrieveStudentsWithCourseMappingId($course_mode_id, $programId, $batchId, $cmId);
		$stuList = $this->listStudents($course_mode_id, $programId, $batchId, $cmId);
		//pr($stuList);
		
		/* $crseName = $this->CourseMapping->find('all', array('conditions' => array(
				array('CourseMapping.id' => $cmId)
		), 'recursive' => 0));
		pr($crseName); */
		

		/* $results = $this->Cae->find('all', array(
			'conditions' => array('Cae.id' => $caeId),
			'contain'=>array(
				'ContinuousAssessmentExam'=> array(
						'fields'=>array('ContinuousAssessmentExam.id', 'ContinuousAssessmentExam.marks',
								'ContinuousAssessmentExam.student_id'),
						'conditions'=>array('ContinuousAssessmentExam.cae_id'=>$caeId),
						'Student'=>array('fields'=>array('Student.name', 'Student.registration_number')),
				),
				
				'CourseMapping' => array(
					'fields' => array('CourseMapping.id'),
					'Course' => array(
							'fields' => array('Course.course_code', 'Course.course_name'),
							'CourseType' => array(
									'fields' => array('CourseType.course_type')
							)
					),
					'CourseStudentMapping'=>array(
							'conditions' => array(
									'CourseStudentMapping.indicator'=>0
							),
							'fields' => array('CourseStudentMapping.student_id', 'CourseStudentMapping.indicator'),
					)
				),
			),
		));
		//pr($results);
		
		$course_name = $results[0]['CourseMapping']['Course']['course_name'];
		$course_code = $results[0]['CourseMapping']['Course']['course_code']; */
		$results = $this->CourseStudentMapping->find('all', array(
				'conditions' => array(
						'CourseStudentMapping.indicator'=>0, 'CourseStudentMapping.course_mapping_id'=>$cmId
				),
				'fields' => array('CourseStudentMapping.student_id', 'CourseStudentMapping.indicator'),
				'contain' => array(
						'Student' => array(
								'conditions' => array('Student.batch_id'=>$batchId, 'Student.program_id'=>$programId, 'Student.discontinued_status'=>0),
								'fields'=>array('Student.id', 'Student.name', 'Student.registration_number'),
								'ContinuousAssessmentExam' => array(
										'conditions' => array('ContinuousAssessmentExam.cae_id'=>$caeId),
										'fields'=>array('ContinuousAssessmentExam.marks'),
								),
						)
				)
		));
		//$course_type = $results[0]['CourseMapping']['CourseMode']['course_mode'];
		
		/* $myId =$this->$currentModel->find('all', array('conditions' => array(
				array("$currentModel.id" => $caeId)
		), 'recursive' => 1)); */
		//pr($myId);
		
		//
		//echo $approvalStatus;
		//$month_year = $myId[0]['MonthYear']['month_year'];
		//$month_year_id = $myId[0]['MonthYear']['id'];
		
		$month_year = $this->getMonthYear($cmId);
		
		$this->set(compact('stuList', 'batchId', 'cmId', 'academicId', 'programId', 'course_mode_id', 'month_year_id'));
		
		$this->set(compact('results', 'stuList', 'approvalStatus', 'batch_period', 'academic_name', 'program_name', 'course_name', 
				'course_code', 'course_type', 'month_year', 'caeNumber', 'currentModel', 'postTable', 'enteredStatus'));
	}
	
	public function listStudents($course_mode_id, $programId, $batchId, $cmId) {
		//$electives = $this->electives();
		
		//if (in_array($course_mode_id, $electives)) {
			 $options=array(
					'joins' =>
					array(
							array(
									'table' => 'course_student_mappings',
									'alias' => 'CourseStudentMapping',
									'type' => 'right',
									'foreignKey' => false,
									'conditions'=> array('CourseStudentMapping.student_id = Student.id')
							),
					),
					'conditions' => array(
							array('Student.program_id' => $programId, 'Student.batch_id' => $batchId,
									'CourseStudentMapping.course_mapping_id' => $cmId,
									'CourseStudentMapping.indicator' => 0,
									'Student.discontinued_status' => 0
							)
					), 'recursive' => 0,
					'order' => array('Student.id'),
			);
			
			$stuList = $this->Student->find('all', $options);
		/* }
		else {*/
			/*$stuList = $this->Student->find('all', array(
					'conditions' => array('Student.batch_id' => $batchId, 'Student.program_id' => $programId,
							'Student.discontinued_status'=>0),
					'fields' => array('Student.id', 'Student.registration_number', 'Student.name'),
					'recursive' => 0,
					'order' => array('Student.id'),
			));
		/*} */
		return $stuList;
	}
	
	public function approveInternal($caeId, $postModel) {
		echo $caeId." ".$postModel;
		$data=array(); 
		$data[$postModel]['id'] = $caeId;
		$data[$postModel]['approval_status'] = 1;
		$this->$postModel->Save($data);
		exit;
	}
	
	public function caeAssignment() {
		//$coursetypeid = explode("-", $course_type_id);
		//pr($coursetypeid);
		$academics = $this->Student->Academic->find('list');
		$batches = $this->Student->Batch->find('list', array('fields' => array('Batch.batch_period')));
		//$lecturers = $this->Lecturer->find('list');
		//$monthyears = $this->MonthYear->find('all');
		
		$monthyears = $this->findMonthYear();
		//$coursetypes = $this->CourseType->find('list');
		/* $courses = $this->CourseMapping->find('list', array(
				'conditions' => array('CourseMapping.course_mode_id' => $course_type_id_array)
		));
		pr($courses); */
		$action = $this->cType;
		//$this->set(compact('action', $this->cType));
		$this->set(compact('batches', 'academics', 'monthyears', 'action'));
	
		if ($this->request->is('post')) {
			//pr($this->data);
			$cm_id = $this->data['course_mapping_id'];
			$result = $this->CourseMapping->find('first', array(
				'conditions'=>array('CourseMapping.id'=>$cm_id),
				'fields'=>array('CourseMapping.id'),
				'contain'=>array(
					'Course'=>array(
						'fields' => array('Course.course_code', 'Course.min_cae_mark', 'Course.max_cae_mark')
					),
					'Cae'=>array(
						'conditions'=>array('Cae.indicator'=>0),
						'fields'=>array('Cae.id')
					)
				)
			));
			$course_code = $result['Course']['course_code'];
			$cae_count = count($result['Cae']);
			//echo "Count : ".$cae_count;
			$max_cae_mark = $result['Course']['max_cae_mark'];
			
			//pr($result);
			
			if ($cae_count <=2) {
				$data = array();
				$data['Cae']['month_year_id']=$this->request->data['month_year_id'];
				$data['Cae']['course_mapping_id']=$this->request->data['course_mapping_id'];
				$data['Cae']['assessment_type']='Theory';
				$data['Cae']['semester_id']=$this->request->data['semester_id'];
				$data['Cae']['marks']=$max_cae_mark;
				$data['Cae']['approval_status']=0;
				$data['Cae']['indicator']=0;
				$data['Cae']['created_by']= $this->Auth->user('id');
				$this->Cae->create();
				
				$redirect = "theory";
				
				if ($this->Cae->save($data)) {
					$this->Flash->success(__('The CAE has been created.'));
					return $this->redirect(array('action' => 'caeAssignment'.'/'.$redirect));
				} else {
					$this->Flash->error(__('The CAE cannot be created. Please, try again.'));
				}
			}
			else {
				$this->Flash->error(__('Maximum number of CAEs already created for '.$course_code));
			}
		}
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
	
	public function findNoOfCaes($cmId, $template) {
		//pr($template);
		$this->loadModel ( 'Cae' );
		$renderView = "";
		SWITCH ($template) {
			case "index":
			case "theoryTemplate":
				//echo "theory";
				$cae = $this->Cae->find('all', array(
				'conditions' => array('Cae.course_mapping_id' => $cmId,
				'Cae.assessment_type' => 'Theory', 'Cae.indicator'=>0),
				'fields' => array('Cae.id', 'Cae.assessment_type', 'Cae.marks','Cae.marks_status','Cae.approval_status', 'Cae.created_by', 'Cae.course_mapping_id'),
				'recursive' => -1
				));
				//pr($cae);
				//$marks = $cae[0]['Cae']['marks'];
				$renderView = "theory_template";
				$action = "index";
				break;
			case "theoryAndPracticalTemplate":
				//echo "theory and practical";
				$cae = $this->CaePractical->find('all', array(
						'conditions' => array('CaePractical.course_mapping_id' => $cmId, 'CaePractical.indicator' => 0,
								'CaePractical.assessment_type !=' => 'Theory'),
						'fields' => array('CaePractical.id', 'CaePractical.assessment_type', 'CaePractical.marks',
										'CaePractical.marks_status','CaePractical.approval_status', 'CaePractical.created_by', 
										'CaePractical.course_mapping_id'),
						'recursive' => -1
				));
				$renderView = "theory_and_practical_template";
				$action = "practical";
				break;
			case "practicalTemplate":
				//echo "practical";
				$cae = $this->CaePractical->find('all', array(
						'conditions' => array('CaePractical.course_mapping_id' => $cmId, 'CaePractical.indicator' => 0,
								'CaePractical.assessment_type !=' => 'Theory'),
						'fields' => array('CaePractical.id', 'CaePractical.assessment_type', 'CaePractical.marks',
										'CaePractical.marks_status','CaePractical.approval_status', 'CaePractical.created_by', 
										'CaePractical.course_mapping_id'),
						'recursive' => -1
				));
				$renderView = "practical_template";
				$action = "practical";
				//echo $renderView;
				break;
			case "projectTemplate":
				$cae = $this->CaeProject->find('all', array(
						'conditions' => array('CaeProject.course_mapping_id' => $cmId, 'CaeProject.indicator' => 0,
								'CaeProject.assessment_type !=' => 'Theory'),
						'fields' => array('CaeProject.id', 'CaeProject.assessment_type', 'CaeProject.marks',
										'CaeProject.marks_status','CaeProject.approval_status', 
										'CaeProject.created_by', 'CaeProject.course_mapping_id'),
						'recursive' => -1
				));
				$renderView = "project_template";
				$action = "project";
				break;
		}
		if ($template == "theoryTemplate" || $template == "index") {
			$this->set(compact('cae', 'template', 'action', 'marks'));
			//$this->render($renderView, false);
			//pr($cae);
		}
		else {
			//pr(count($cae));
			//pr($cae);
			if (count($cae) > 0) {
				//echo "Available";
				$enableButton = 1;
				$this->set(compact('cae', 'template', 'action', 'enableButton'));
				//$this->render("project_template");
			}
			else {
				$enableButton = 0;
				$this->set(compact('enableButton'));
				$this->render($renderView, false);
			}
		}
		$this->layout = false;
	}
	
	public function addInternals($batchId, $academicId, $programId, $cmId, $semesterId, $monthYearId, $template, $courseTypeId) {
		$this->loadModel('Cae');
		
		$courseTypeId = $this->retrieveCourseTypeFromCourseWithCmId($cmId, false);
		//echo $courseTypeId." ".$action."</br>";
		
		SWITCH ($template) {
			case "1":
				//echo "Theory</br>";
				$caeCount = $this->Cae->find('count', array('conditions' => array(
						'Cae.course_mapping_id' => $cmId,
						'Cae.assessment_type' => 1
				),
				));
				//echo $caeCount;
				//$caeNewCounter = $caeCount+1;
				$caeArray = array(
						"course_mapping_id" => $cmId,
						"month_year_id" => $monthYearId,
						"semester_id" => $semesterId,
						"marks" => $marks
				);
				//pr($this->generalArray);
				$this->saveCaeData($caeArray, $this->generalArray, $caeCount);
				break;
			case "project":
				
				break;
		}
		
		$this->layout=false;
	}
	
	/* public function saveCaeData($caeArray, $array,$caeCount) {
		//pr($caeArray);
		//pr($array); 
		$auth_user = $this->Auth->user('id');
		$i = 1;
		foreach ($array as $assessment_type => $assessment_value) {
			$caeNumber = $caeCount+$i;
			//echo "CAE Number : ".$caeNumber;
			$data = array();
			$data['Cae']['number']=$caeNumber;
			$data['Cae']['course_mapping_id']=$caeArray['course_mapping_id'];
			$data['Cae']['assessment_type']=$assessment_type;
			$data['Cae']['month_year_id']=$caeArray['month_year_id'];
			$data['Cae']['semester_id']=$caeArray['semester_id'];
			$data['Cae']['marks']=$caeArray['marks'];
			$data['Cae']['approval_status']=0;
			$data['Cae']['indicator']=0;
			$data['Cae']['created_by']=$auth_user;
			$this->Cae->create();
			$this->Cae->save($data);
		}
	} */
	
	public function moderateCae(/* $batchId, $academicId, $programId, $caeId */) {
		$access_result = $this->checkPathAccess($this->Auth->user('id'));
		if (!$access_result) {
		//$academics = $this->Student->Academic->find('list');
		$batches = $this->Student->Batch->find('list', array('fields' => array('Batch.batch_period'),'order' => array('Batch.batch_to DESC')));
		$programs = $this->Student->Program->find('list',array('order' => array('Program.program_name ASC')));
		
		$monthYears = $this->MonthYear->getAllMonthYears();
		//pr($monthYears);
		$this->set(compact('batches', 'programs', 'monthYears'));
		//$this->layout=false;
		
		if($this->request->is('post')) {
		//	pr($this->data);
			$moderatedArray=array();
						
			$bool=false;
			$modOperator = $this->request->data['ContinuousAssessmentExam']['sign'];
			$modValue = $this->request->data['ContinuousAssessmentExam']['mark'];
			//echo $modOperator." ".$modValue;
			$cae = $this->request->data['InternalExam']['marks'];
			//pr($cae);
			//echo count($this->request->data['ContinuousAssessmentExam']['id']);
			for($i=0; $i<count($this->request->data['InternalExam']['id']); $i++) {
				$tmpArray = array();
				$tmpArray['student_id']=$this->request->data['InternalExam']['student_id'][$i];
				$tmpArray['course_mapping_id']=$this->request->data['InternalExam']['course_mapping_id'][$i];
				
				if($modOperator == "plus") {
					$modMarks = $this->request->data['InternalExam']['marks'][$i] + $modValue;
				}
				else if($modOperator == "minus") {
					$modMarks = $this->request->data['InternalExam']['marks'][$i] - $modValue;
				}
				$moderation_date =date("Y-m-d H:i:s");
				
				$tmpArray['marks'] = $modMarks;
				
				$data = array();
				$data['InternalExam']['id'] = $this->request->data['InternalExam']['id'][$i];
				$data['InternalExam']['marks'] = $modMarks;
				$data['InternalExam']['moderation_marks'] = $modValue;
				$data['InternalExam']['moderation_operator'] = $modOperator;
				$data['InternalExam']['moderation_date'] = $moderation_date;
				$this->InternalExam->save($data);
				$bool = true;
				array_push($moderatedArray, $tmpArray);
			}
			
			if ($bool) {
				//$this->Flash->success(__('The CAE exam is moderated.'));
				//pr($moderatedArray);
				$this->set(compact('moderatedArray'));
				$this->render("view_moderated_cae");
				//return $this->redirect(array('controller' => 'ContinuousAssessmentExams', 'action' => 'viewModeratedCae'));
			} else {
				$this->Flash->error(__('The CAE exam cannot be moderated. Please, try again.'));
			}
		}
		} else {
			$this->render('../Users/access_denied');
		}
	}
	
	//public function getCourseMappingWithMonthYearBatchProgram($month_year_id=NULL, $batch_id=NULL, $program_id=NULL) {
	public function moderateCaeSearch($month_year_id, $batch_id, $program_id, $from, $to) {
		
		$courseMapping = $this->getCourseMappingWithMonthYearBatchProgram($month_year_id, $batch_id, $program_id);
		//pr($courseMapping);
		$cmIdArray = $this->getCmId($courseMapping);
		//pr($cmIdArray);
		$marks = $this->getMarksFromInternalExam($cmIdArray, $from, $to);
		//echo count($marks);
		$this->set(compact('marks'));
		//pr($marks);
		$this->layout=false;
	}
	
	public function getCmId($courseMapping) {
		//pr($courseMapping); 
		$cmId =array();
		foreach($courseMapping as $courseMapping) {
			array_push($cmId, $courseMapping['CourseMapping']['id']);
		}
		return $cmId;
	}
	
	public function getMarksFromInternalExam($cmIdArray, $from, $to) {
		//echo "From : ".$from." To : ".$to;
		//pr($cmIdArray);
		if(empty($to)) {
			$to = $from;
		}
		$result = $this->InternalExam->find('all',
					array(
							'conditions' => array(
										'InternalExam.marks >= ' => $from,
										'InternalExam.marks <= ' => $to,
										'InternalExam.course_mapping_id' => $cmIdArray,
							),
							'fields' => array(
											'InternalExam.id',
											'InternalExam.course_mapping_id', 'InternalExam.marks',
											'InternalExam.student_id', 'Student.registration_number',
											'Student.name'
							),
							'recursive' => 0
					)
				);
		
		//pr($result);
		//echo "Count : ".count($result);
		return $result;
		
	}
	
	public function getCaeIdArray($cmCaeId) {
		$caeIdArray = array();
		foreach ($cmCaeId as $cmId => $caeArray) {
			//pr($caeArray);
			foreach($caeArray as $cArray) {
				array_push($caeIdArray, $cArray);
			}
		}
		return $caeIdArray;
	}
	
	 public function getCourseMappingWithMonthYearBatchProgram($month_year_id, $batch_id, $program_id) {
		 $courseMapping = array();
		 $filterCondition = "";
		 //echo $batch_id." ".$program_id." ".$month_year_id;
		 if ($batch_id > 0) {
		 	$filterCondition.= "`(CourseMapping`.`batch_id` = ".$batch_id.") AND ";
		 } else {
		 	$filterCondition.= "`(CourseMapping`.`batch_id` > 0)"." AND ";
		 }
		 	
		 if($program_id > 0) {
		 	$filterCondition.= "`(CourseMapping`.`program_id` = ".$program_id.")"." AND ";
		 } else {
		 	$filterCondition.= "`(CourseMapping`.`program_id` > 0)"." AND ";
		 }
		 
		 if($month_year_id > 0) {
		 	$filterCondition.= "`(CourseMapping`.`month_year_id` = ".$month_year_id.")"." AND ";
		 } else {
		 	$filterCondition.= "`(CourseMapping`.`month_year_id` > 0)"." AND ";
		 }
		 
		 $filterCondition.= "((`CourseMapping`.`indicator` = 0)";
		 //$filterCondition.= "(`$currentModel`.`indicator` = 0)";
		 $filterCondition.=")";
					
		$tmpCourseMapping=$this->CourseMapping->find('all', array(
				'conditions' => array($filterCondition),
				'fields' => array('CourseMapping.id'),
				'order' => array('CourseMapping.id ASC'),
				'recursive' => 0
		));
		//pr($tmpCourseMapping);
		if(is_array($tmpCourseMapping)) {
			$courseMapping = array_merge($courseMapping, $tmpCourseMapping);
		}
		
		//pr($courseMapping);
		
		//pr($courseMapping);
		//echo "Count : ".count($courseMapping);
		return $courseMapping;
	}
	
	
	/* public function getCourseMappingWithMonthYearBatchProgram($month_year_id, $batch_id, $program_id) {
		$caeTables = array("Cae" => "caes", "CaePractical" => "cae_practicals", "CaeProject" => "cae_projects");
		$courseMapping = array();
		foreach ($caeTables as $currentModel => $currentTable) {
			$filterCondition = "";
			
			if ($batch_id > 0) {
				$filterCondition.= "`(CourseMapping`.`batch_id` = ".$batch_id.") AND ";
			} else {
				$filterCondition.= "`(CourseMapping`.`batch_id` > 0)"." AND ";
			}
			
			if($program_id > 0) {
				$filterCondition.= "`(CourseMapping`.`program_id` = ".$program_id.")"." AND ";
			} else {
				$filterCondition.= "`(CourseMapping`.`program_id` > 0)"." AND ";
			}
			
			$filterCondition.= "((`CourseMapping`.`indicator` = 0)"." AND ";
			$filterCondition.= "(`$currentModel`.`indicator` = 0)";
			$filterCondition.=")";
			$options=array(
					'joins' =>
					array(
							array(
									'table' => $currentTable,
									'alias' => $currentModel,
									'type' => 'right',
									'foreignKey' => false,
									'conditions'=> array("$currentModel.course_mapping_id = CourseMapping.id",
											/* "$currentModel.month_year_id" => $month_year_id */
								/*	)
							)
					),
					'conditions' => array(
							array(
									(
											$filterCondition
											)
							)
					),
					'fields' => array('DISTINCT CourseMapping.id'),
					'recursive' => 0
			);
			
			$tmpCourseMapping=$this->$currentModel->CourseMapping->find('all', $options);
			//pr($tmpCourseMapping);
			if(is_array($tmpCourseMapping)) {
				$courseMapping = array_merge($courseMapping, $tmpCourseMapping);
			}			
		}
		
		//pr($courseMapping);
		
		//pr($courseMapping);
		//echo "Count : ".count($courseMapping);
		return $courseMapping;
	} */

	
	public function assignCae() {
		$academics = $this->Student->Academic->find('list');
		$batches = $this->Student->Batch->find('list', array('fields' => array('Batch.batch_period')));
		$this->set(compact('batches', 'academics'));
	}
	
	public function addCae() {
		//echo $batchId." ".$academicId." ".$programId." ".$monthYearId;
		/* $courseMapping = $this->getCourseMappingWithOutCaes($programId, $batchId, $academicId, $monthYearId);
		//pr($courseMapping);
		$cmId = $this->getCmId($courseMapping);
		//pr($cmId);
		$courses = $this->getCoursesByCmId($cmId);
		$this->set(compact('batchId', 'academicId', 'programId', 'monthYearId', 'courses'));*/
		
		$academics = $this->Student->Academic->find('list');
		$batches = $this->Student->Batch->find('list', array('fields' => array('Batch.batch_period')));
		$lecturers = $this->Lecturer->find('list');
		$this->set(compact('batches', 'academics', 'lecturers'));
		
		$this->layout=false; 
	}
	
	/* public function getCourseMappingWithOutCaes($program_id, $batch_id, $academic_id, $month_year_id) {
		$options=array(
				'joins' =>
				array(
						array(
								'table' => 'caes',
								'alias' => 'Cae',
								'type' => 'left',
								'foreignKey' => false,
								'conditions'=> array('Cae.course_mapping_id = CourseMapping.id',
										'Cae.month_year_id' => $month_year_id
								)
						)
				),
				'conditions' => array(
						array('CourseMapping.batch_id' => $batch_id,
								'CourseMapping.program_id' => $program_id,
								'CourseMapping.indicator' => 0,
								'Cae.indicator' => 0
						)
				),
				'fields' => array('DISTINCT CourseMapping.id', '*'),
				'recursive' => 1
		);
			
		$res = $this->Cae->CourseMapping->find('all', $options);
		//echo "Count : ".count($res);
		return $res;
	} */
	
	public function getCoursesByCmId($cmId) {
		$coursesArray = array();
		$courses = $this->Cae->CourseMapping->find('all', array(
				'conditions' => array('CourseMapping.id' => $cmId),
				'fields' => array('CourseMapping.id', 'Course.course_name'),
				'recursive' => 0
		));
		foreach ($courses as $courses) {
			$coursesArray[$courses['CourseMapping']['id']] = $courses['Course']['course_name'];
		}
		return $coursesArray;
	}
	
	public function getMarks($cmId) {
		$caeMarks = $this->Cae->find('first', array(
				'conditions' => array('Cae.course_mapping_id' => $cmId),
				'fields' => array('Cae.marks'),
				'recursive' => -1
		));
		$marks = $caeMarks['Cae']['marks'];
		$this->set(compact('marks'));
		$this->layout=false;
	}
	
	public function getAcademics($batch_id, $class_name) {
		$academics=array();
		$tempAcademics = $this->Student->find('all',array(
				'joins' =>
				array(
						array(
								'table' => 'academics',
								'alias' => 'Academic',
								'type' => 'right',
								'foreignKey' => false,
								'conditions'=> array('Student.academic_id = Academic.id',
								)
						)
				),
				'conditions'=>array('Student.batch_id' => $batch_id),
				'fields' => array('DISTINCT Student.academic_id', 'Academic.academic_name'),
				'order' => 'Student.academic_id',
				'recursive' => -1
		));
		//pr($tempAcademics);
		foreach ($tempAcademics as $tempAcademics) {
			$academics[$tempAcademics['Student']['academic_id']] = $tempAcademics['Academic']['academic_name'];
		}
		
		$this->set(compact('academics','class_name'));
		$this->layout=false;
	}
	
	public function findMonthYear() {
				
		$monthyears = array();
		$tmpMonthyears = $this->MonthYear->find('all', array(
				'fields' => array('MonthYear.id', 'Month.month_name', 'Month.id', 'MonthYear.year'),
				'order' => array('MonthYear.id DESC'),
				'recursive' => 0
		));
		//pr($tmpMonthyears);
		foreach ($tmpMonthyears as $tmpMonthyears) {
			$monthyears[$tmpMonthyears['MonthYear']['id']] = $tmpMonthyears['Month']['month_name']." - ".$tmpMonthyears['MonthYear']['year'];
		}
		//pr($monthyears);
		return $monthyears;
	}
	
	public function findMonthYearBySemester($batch_id, $program_id,$semester_id) {
		$results = $this->CourseMapping->find('first', array(
			'conditions'=>array('CourseMapping.batch_id' => $batch_id, 'CourseMapping.program_id' => $program_id,
					'CourseMapping.semester_id' => $semester_id, 'CourseMapping.indicator'=>0
			),
			'fields'=>array('CourseMapping.month_year_id'),
			'contain'=>array(
				'MonthYear'=>array('fields' => array('MonthYear.year'),
								'Month' => array('fields' => array('Month.month_name'))
						)
			)
		));
		//pr($results);
		$monthyears[$results['CourseMapping']['month_year_id']] = $results['MonthYear']['Month']['month_name']."-".$results['MonthYear']['year'];
		$this->set(compact('monthyears'));
		$this->layout=false;
	}
	
	
	public function listMonthYearByBatchAndProgram($batch_id, $program_id,$currentModel) {
		$monthYear = array();
		$tmpMonthyears = $this->CourseMapping->find('all', array(
				'conditions' => array('CourseMapping.batch_id' => $batch_id, 'CourseMapping.program_id' => $program_id),
				'fields' => array('CourseMapping.month_year_id AS month_year_id'),
				'contain'=>array(
						'MonthYear'=>array('fields' => array('MonthYear.year'),
								'Month' => array('fields' => array('Month.month_name'))
						)
				),
				'recursive' => 2
		));
		//pr($tmpMonthyears);
		foreach ($tmpMonthyears as $tmpMonthyears) {
			$monthYear[$tmpMonthyears['CourseMapping']['month_year_id']] = $tmpMonthyears['MonthYear']['Month']['month_name']." - ".$tmpMonthyears['MonthYear']['year'];
		}
		//pr($monthYear);
		krsort($monthYear);
		$this->set(compact('monthYear'));
		$this->set(compact('currentModel'));
		$this->layout=false;
		//return $monthYears;
	}
	
	public function findMonthYearByCmId($cmId=null, $template, $ajax) {
		//pr($template); 
		$monthyears = array();
		SWITCH ($template) {
			CASE "index":
			CASE "theoryTemplate":
				$model = "Cae";
				break;
			CASE "projectTemplate":
				$model = "CaeProject";
				break;
			CASE "practicalTemplate":
				$model = "CaePractical";
				break;
		}
		//pr($model);
		$caeMonthYear = $this->$model->find('first', array(
				'conditions' => array("$model.course_mapping_id" => $cmId, "$model.indicator"=>0),
				'fields' => array("$model.month_year_id"),
				'recursive' => 2
		));
		//pr($caeMonthYear);
		if(!empty($caeMonthYear) && count($caeMonthYear)>0) {
		//echo $caeMonthYear['MonthYear']['Month']['month_name']." - ".$caeMonthYear['MonthYear']['year']; die;
			$monthyears[$caeMonthYear[$model]['month_year_id']] = $caeMonthYear['MonthYear']['Month']['month_name']." - ".$caeMonthYear['MonthYear']['year'];
			$myAvailable = true;
		}
		else {
			$monthyears = $this->findMonthYear();
			$myAvailable = false;
		}
		//pr($monthyears);
		$this->set(compact('monthyears', 'myAvailable'));
		if ($ajax) {
			$this->layout=false;
		}
		else {
			return $monthyears;
		}
	}
	
	public function findMarksByCmId($cmId, $template) {
		/* SWITCH ($template) {
			case "theoryTemplate":
				//echo "theory";
				$cae = $this->Cae->find('all', array(
					'conditions' => array('Cae.course_mapping_id' => $cmId,
										'Cae.assessment_type' => 'Theory'),
					'fields' => array('Cae.id', 'Cae.assessment_type', 'Cae.marks','Cae.marks_status','Cae.approval_status', 'Cae.created_by', 'Cae.course_mapping_id'),
					'recursive' => -1
				));
				//pr($caeMonthYear);
				if(!empty($cae) && count($cae)>0) {
				$marks = $cae['Cae']['marks'];
				} else $marks="";
				//pr($marks);
				$this->set(compact('marks', 'template'));
				break;
			case "theoryAndPracticalTemplate":
				echo "theory and practical";
				$cae = $this->Cae->find('all', array(
						'conditions' => array('Cae.course_mapping_id' => $cmId,
								'Cae.assessment_type' => $this->practicals),
						'fields' => array('Cae.id', 'Cae.assessment_type', 'Cae.marks','Cae.marks_status','Cae.approval_status', 'Cae.created_by', 'Cae.course_mapping_id'),
						'recursive' => -1
				));
				pr($cae);
				break;
			case "practicalTemplate":
				echo "practical";
				$cae = $this->Cae->find('all', array(
						'conditions' => array('Cae.course_mapping_id' => $cmId,
								'Cae.assessment_type' => $this->practicals),
						'fields' => array('Cae.id', 'Cae.assessment_type', 'Cae.marks','Cae.marks_status','Cae.approval_status', 'Cae.created_by', 'Cae.course_mapping_id'),
						'recursive' => -1
				));
				pr($cae);
				break;
			case "projectTemplate":
				echo "project";
				$cae = $this->Cae->find('all', array(
						'conditions' => array('Cae.course_mapping_id' => $cmId,
								'Cae.assessment_type' => $this->project),
						'fields' => array('Cae.id', 'Cae.assessment_type', 'Cae.marks','Cae.marks_status','Cae.approval_status', 'Cae.created_by', 'Cae.course_mapping_id'),
						'recursive' => -1
				));
				pr($cae);
				$dbo = $this->CourseMapping->getDatasource();
				$logs = $dbo->getLog();
				$lastLog = end($logs['log']);
				echo $lastLog['query'];
				break;
		} */
		$this->layout=false;
		/* $caeMonthYear = $this->Cae->find('first', array(
				'conditions' => array('Cae.course_mapping_id' => $cmId),
				'fields' => array('Cae.marks'),
				'recursive' => -1
		));
		if(!empty($caeMonthYear) && count($caeMonthYear)>0) {
			$marks = $caeMonthYear['Cae']['marks'];
		} else $marks="";
		//pr($marks);
		$this->set(compact('marks')); */
	}
	
	public function delete_cae($id) {
		$this->Cae->id = $id;
		if (!$this->Cae->exists()) {
			throw new NotFoundException(__('Invalid CAE'));
		}
		//$this->request->allowMethod('post', 'delete');
		$result = $this->Cae->updateAll(
				/* UPDATE FIELD */
				array(
						"Cae.indicator" => 1,
				),
				/* CONDITIONS */
				array(
						"Cae.id" => $id
				)
				);
				
		if ($this->Student->delete()) {
			$this->Flash->success(__('The student has been deleted.'));
		} else {
			$this->Flash->error(__('The student could not be deleted. Please, try again.'));
		}
		return $this->redirect(array('action' => 'index'));
	}
	
	function exportCae($program_id, $batch_id, $academic_id, $month_year_id) {
		
		$stuList = $this->getStudentsWithBatchAndProgram($batch_id, $program_id, $month_year_id);
		//pr($stuList); 
		$cm = $this->getCourseMappingWithMonthYearBatchProgram($month_year_id, $batch_id, $program_id);
		
		//pr($cm);
		
		$this->set(compact('stuList', 'cmId'));
		
		$this->layout = null;
		$this->autoLayout = false;
		//Configure::write('debug','0');
	}
	
	function listMasterStudents($studentMasterEntry, $month_year_id) {
		$studentId = array();
		foreach ($studentMasterEntry as $key => $stuInfo) {
			//pr($stuInfo);
			$flag = 0;
			
			$csmTmpArray = $stuInfo['CourseStudentMapping'];
			$studentId[] = $stuInfo['Student']['id'];
		}
		//echo count($studentId);
		return $studentId;
	}

	function listCourseStudents($res) {
		$studentId = array();
		foreach ($res as $key => $stuInfo) {
			$studentId[] = $stuInfo['CourseStudentMapping']['student_id'];
		}
		return $studentId;
	}
	
	function checkIfStudentsExistsInCSM($studentId, $courseCodeDetails, $month_year_id) {
		
		$studentsNotMappedArray = array();
		$stuCount  = count($studentId);
		//echo $stuCount;
		foreach ($courseCodeDetails as $courseCode => $courseCodeDetail) { 
			$tmpArray = array();
			$cnt = $this->CourseStudentMapping->find('count', array(
					'conditions' => array('CourseStudentMapping.student_id' => $studentId,
							'CourseStudentMapping.course_mapping_id' => $courseCodeDetail['cmId'],
							'CourseStudentMapping.indicator'=>0,
					),
					'fields' => array('CourseStudentMapping.student_id', 'CourseStudentMapping.course_mapping_id'),
					'recursive' => 0
			));
			//pr($cnt);
			
			if ($cnt == 0) {
				$studentsNotMappedArray[$courseCodeDetail['cmId']] = $cnt;
			} else {
				$studentsNotMappedArray[$courseCodeDetail['cmId']] = 1;
			}
		}
		return $studentsNotMappedArray;
	}
	
	public function listCMIdArray($res) {
		$courseMapping = array();
		foreach ($res as $result) {
			$courseMapping[$result['CourseMapping']['id']]=$result['CourseMapping']['id'];
		}
		//pr($courseMapping);
		return $courseMapping;
	}
	
	public function modelToSave($currentModelId) {
		$dataToSave = array();
		SWITCH ($currentModelId) {
			case 1:
				$dataToSave['currentModel'] = "Cae";
				$dataToSave['currentTable'] = "caes";
				$dataToSave['currentField'] = "cae_id";
				$dataToSave['currentAction'] = "index";
				$dataToSave['postModel'] = "ContinuousAssessmentExam";
				$dataToSave['postTable'] = "continuous_assessment_exams";
				$dataToSave['template'] = "theoryTemplate";
				break;
			case 2:
				$dataToSave['currentModel'] = "CaePractical";
				$dataToSave['currentTable'] = "cae_practicals";
				$dataToSave['currentField'] = "cae_practical_id";
				$dataToSave['currentAction'] = "practical";
				$dataToSave['postModel'] = "Practical";
				$dataToSave['postTable'] = "practicals";
				$dataToSave['template'] = "practicalTemplate";
				break;
			case 3:
				$dataToSave['currentModel'] = "CaeProject";
				$dataToSave['currentTable'] = "cae_projects";
				$dataToSave['currentField'] = "cae_project_id";
				$dataToSave['currentAction'] = "project";
				$dataToSave['postModel'] = "Project";
				$dataToSave['postTable'] = "projects";
				$dataToSave['template'] = "projectTemplate";
				break;
		}
		return $dataToSave;
	}
	
	public function modelBasedCourseMapping($batch_id, $academic_id, $program_id, $month_year_id, $model) {
		$tmpCourseMapping=$this->CourseMapping->find('all', array(
				'conditions' => array('Course.course_type_id'),
				'fields' => array('CourseMapping.id'),
				'order' => array('CourseMapping.id ASC'),
				'recursive' => 0
		));
		if(is_array($tmpCourseMapping)) {
			$courseMapping = array_merge($courseMapping, $tmpCourseMapping);
		}
		return $courseMapping;
	}
	
	function readMarksFromExcel() {
		$access_result = $this->checkPathAccess($this->Auth->user('id'));
		if (!$access_result) {
		if($this->request->is('post')) {
			//echo getcwd() . "\n";
			//pr(is_readable("test.xlsx"));
			//pr($this->data);
				
			if(!empty($this->request->data['ContinuousAssessmentExam']['csv']['name'])) {
				move_uploaded_file($this->request->data['ContinuousAssessmentExam']['csv']['tmp_name'], WWW_ROOT . '../sets_upload_received_file/' .$this->request->data['ContinuousAssessmentExam']['csv']['name']);
				$filename = WWW_ROOT . '../sets_upload_received_file/'.$this->request->data['ContinuousAssessmentExam']['csv']['name'];
			}
			$relook="";
			$objPHPExcel = PHPExcel_IOFactory::load(WWW_ROOT . '../sets_upload_received_file/'.$this->request->data['ContinuousAssessmentExam']['csv']['name']);
			$worksheet = $objPHPExcel->setActiveSheetIndex(0);
			//foreach ($objPHPExcel->getWorksheetIterator() as $worksheet) {
			$worksheetTitle     = $worksheet->getTitle();
			$highestRow         = $worksheet->getHighestRow(); // e.g. 10
			$highestColumn      = $worksheet->getHighestDataColumn(); // e.g 'F'
			$highestColumnIndex = PHPExcel_Cell::columnIndexFromString($highestColumn);
			$nrColumns = ord($highestColumn) - 64;
			//	echo "<br>The worksheet ".$worksheetTitle." has ";
			//	echo $nrColumns . ' columns (A-' . $highestColumn . ') ';
			//	echo ' and ' . $highestRow . ' row.';
			//	echo '<br>Data: ';
			$monthYear = $objPHPExcel->getActiveSheet()->getCell('C1')->getValue();
			//pr($monthYear);
			$myArray = explode("-", strtoupper($monthYear));
			//pr($myArray);
			$monthName = trim($myArray[0]);
			$year = trim($myArray[1]);
	
			//$month_id = date('n', strtotime($monthName));
			
			$res_month_id = array_keys($this->months, $monthName); 
			$month_id = $res_month_id[0];
			//echo $month_id;
	
			$month_year_id = $this->MonthYear->retrieveMonthYearIdFromMonthIdAndYear($month_id, $year);
			//pr($month_year_id);

			//Academic id
			$academic = $objPHPExcel->getActiveSheet()->getCell('C2')->getValue();
			$academicDetails = $this->Academic->getAcademicDetailsFromAcademicName($academic);
			$academic_id = $academicDetails['id'];
			$academic_short_code = $academicDetails['short_code'];
			//pr($academic_id);
	
			//Program id
			$program = $objPHPExcel->getActiveSheet()->getCell('C3')->getValue();
			$programDetails = $this->Program->getProgramDetailsFromProgramName($program, $academic_id);
			$program_id = $programDetails['id'];
			$program_short_code = $programDetails['short_code'];
	
			//pr($program_id);
	
			$batch = $objPHPExcel->getActiveSheet()->getCell('G1')->getValue();
			$batch_id = $this->Batch->getBatchIdFromText($batch);
			//pr($batch_id);
	
			//pr($marks);
			$assessment_number = $objPHPExcel->getActiveSheet()->getCell('G2')->getValue();
			//pr($assessment_number);
	
			$row = 6;
			$col = 3;
			$arrCourseCode = $this->getCourseCodeFromExcel($col, $row, $highestColumnIndex, $filename);
			//pr($arrCourseCode);
	
			$courseCodeDetails = $this->cmGetCmIdfromCourseCode($arrCourseCode['courseCode'], $batch_id, $program_id, $academic_id, $month_year_id);
			//pr($courseCodeDetails);
	
			$coursesNotMapped = array();
			foreach ($courseCodeDetails as $courseCode => $courseCodeDetail) {
				if ($courseCodeDetail['cmId'] == "") {
					$coursesNotMapped[] = $courseCode;
				}
			}
			//pr($coursesNotMapped);
			$mark_status = "Entered";
			$approval_status = 0;
			if(!empty($coursesNotMapped) && count($coursesNotMapped) > 0) {
				//if(empty($coursesNotMapped)) {
				$this->Flash->error(__("Courses are yet to be mapped for Batch $batch and Program $program"));
				return false;
			}
			else {
				
				$courseType = $this->listCourseTypeIdsBasedOnMethod($this->cType, "-");
				//pr($courseType);
				$courseTypeIdArray = explode("-",$courseType);
				//pr($courseTypeIdArray);
				
				$studentArray = $this->Student->getStudentsWithBatchAndProgram($batch_id, $program_id, $month_year_id);
				//pr($studentArray);
					
				$processedStudentArray = $this->processStudentArray($studentArray);
				
				$cmAll = $this->courseCodeForASemester($batch_id, $program_id, $month_year_id, $courseTypeIdArray);
				//pr($cmAll);
				
				$cm = $this->truncateEmptyCmIds($cmAll, $processedStudentArray);
				//pr($cm);
				//pr($courseCodeDetails);
				
				//Check if courses are correct from excel and database
					
				$validateCourseCode = $this->validateIfCourseCodeExists($cm, $courseCodeDetails);
				//pr($validateCourseCode);
				
				if (is_array($validateCourseCode) && count($validateCourseCode) > 0) {
					$cCode = $this->CourseMapping->find('all', array(
							'conditions' => array('CourseMapping.id' => $validateCourseCode),
							'fields' => array('Course.course_code'),
							'recursive' => 0
					));
					
					$text = "";
					foreach ($cCode as $key => $notMapped) {
						$text.=$notMapped['Course']['course_code'].",";
					}
					$text = substr($text,0,strlen($text)-1);
					//pr($cCode);
					//echo $text;
					$this->Flash->error(__('Course Mapping not available for '.$text));
					return false;
				}
				else if($validateCourseCode == true) {
					//echo "welcome";
					
					//Check for Course Student Mapping
					$studentMasterEntry = $this->getStudentsWithBatchAndProgram($batch_id, $program_id, $month_year_id);
					//echo count($studentMasterEntry)."</br>";
					//pr($studentMasterEntry);
	
					$studentId = $this->listMasterStudents($studentMasterEntry, $month_year_id);
					//echo "data from student table";
					//pr($studentId);
					//pr($courseCodeDetails);
	
					$courseStudentArray = $this->checkIfStudentsExistsInCSM($studentId, $courseCodeDetails, $month_year_id);
					//pr($courseStudentArray);
					
					if (is_array($courseStudentArray) && count($courseStudentArray) > 0) {
						$studentsNotMapped="";
						foreach ($courseStudentArray as $cmId => $cnt) {
							if ($cnt == 0) {
								$cCode = $this->CourseMapping->find('all', array(
										'conditions' => array('CourseMapping.id' => $cmId),
										'fields' => array('Course.course_code'),
										'recursive' => 0
								));
								foreach ($cCode as $key => $notMapped) {
									$studentsNotMapped.=$notMapped['Course']['course_code'].",";
								}
								$studentsNotMapped = substr($studentsNotMapped,0,strlen($studentsNotMapped)-1);
								$this->Flash->error(__('Students not mapped for Courses '.$studentsNotMapped));
								return false;
							}
						}
						//Mark reading starts here
						//Identify course type
						//echo $assessment_number."</br>";
						$number=$assessment_number-1;
						$excelCourseCodeCells = $arrCourseCode['excelCellAddress'];
						//pr($excelCourseCodeCells);
							
						foreach($excelCourseCodeCells as $key => $cellAddress) {
							//pr($cellAddress);
							$column = $cellAddress['col'];
							$row = $cellAddress['row'];
						
							//echo $column." ".$row." ";
							$course_assessment_marks_row = $row-1;
						
							$cae_assessment_object = $worksheet->getCellByColumnAndRow($column, $course_assessment_marks_row);
							$cae_assessment_mark = $cae_assessment_object->getValue();
							//echo $cae_assessment_mark;
							//die;
							$cell = $worksheet->getCellByColumnAndRow($column, $row);
							$courseCode = $cell->getValue();
							$cmId = $courseCodeDetails[$courseCode]['cmId'];
							//echo " - ".$cmId."</br>";
							$courseTypeId = $this->retrieveCourseTypeFromCourseWithCmId($cmId, 0);
							//pr($courseTypeId);
						
							//$dataToSave = $this->modelToSave($courseTypeId);
							//pr($dataToSave);
							//die;
						
							$caeExists = $this->Cae->find('all', array(
									'conditions' => array('Cae.course_mapping_id' => $cmId),
									'fields' => array('Cae.id'),
									'order' => 'Cae.id ASC',
									'recursive' => 0
							));
							//pr($caeExists);
						
							$assessmentCount = count($caeExists);
							$tmp = $assessment_number-1;
							$num = $tmp-$assessmentCount;
						
							//if($assessmentCount >= $tmp) {
							//if ($courseTypeId == 1) {
							if($assessmentCount >= $tmp) {
								if (isset($caeExists[$assessment_number-1]['Cae']['id'])) {
									$caeId = $caeExists[$assessment_number-1]['Cae']['id'];
								}
								else {
									$data=array();
									$data['Cae']['month_year_id'] = $month_year_id;
									$data['Cae']['course_mapping_id'] = $cmId;
									$data['Cae']['assessment_type'] = "Theory";
									$data['Cae']['marks'] = $cae_assessment_mark;
									$data['Cae']['marks_status'] = "Not Entered";
									$data['Cae']['add_status'] = 0;
									$data['Cae']['approval_status'] = 0;
									$data['Cae']['indicator'] = 0;
									$data['Cae']['created_by'] = $this->Auth->user('id');
									$this->Cae->create();
									$this->Cae->save($data);
									$caeId = $this->Cae->getLastInsertID();
								}
							}
							else {
								$this->Flash->error(__('Import the previous '.$num.' caes for course code '.$courseCode));
								return false;
							}
							//}
						
							//echo $caeId." ".$column." ".$row." ".$highestRow;
								
							$dataRow = $row+1;
							for ($i=$dataRow; $i<=$highestRow; $i++) {
								//echo "</br>".$i." ".$column." ";
								$cell = $worksheet->getCellByColumnAndRow($column, $i);
								//echo $cell;
								$marks = $cell->getValue();
								//echo "Marks : ".$marks."</br>";
						
								if ($marks == 0) {
									$marks = $marks;
								}
								else if ($marks=='A' || $marks=='a') {
									$marks='A';
								}
								//pr($stu);
								//echo "Marks : ".$marks."</br>";
						
								$stuCell = $worksheet->getCellByColumnAndRow(1, $i);
								$regNumber = $stuCell->getFormattedValue();
						
								$stu=$this->Student->find('first', array(
										'conditions' => array('Student.registration_number' => $regNumber, 'Student.discontinued_status' => 0,
												'Student.batch_id'=>$batch_id, 'Student.program_id'=>$program_id
										),
										'fields' => array('Student.id'),
										'recursive' => 0
								));
								//pr($stu);
								if (isset($stu['Student']['id'])) {
									$stuId = $stu['Student']['id'];
								}
								else {
									$stuCell = $worksheet->getCellByColumnAndRow(0, $i);
									//pr($stuCell);
									//echo "</br>i ".$i;
									$ifAbs = $stuCell->getFormattedValue();
									//echo "</br>".$ifAbs;
						
									$absArray = explode("-", $ifAbs);
									if (!is_int($ifAbs) && $absArray[1]=="ABS") {
										//echo " *** "."ABS"." ** ".$absArray[2]." *** ".$absArray[3];
										$abs_batch_id = $absArray[2];
										$abs_program_id = $absArray[3];
										//echo " *** ".$regNumber;
										$stu=$this->Student->find('first', array(
												'conditions' => array('Student.registration_number' => $regNumber, 'Student.discontinued_status' => 0,
														'Student.batch_id'=>$abs_batch_id, 'Student.program_id'=>$abs_program_id
												),
												'fields' => array('Student.id'),
												'recursive' => 0
										));
										//pr($stu);
										if (isset($stu['Student']['id'])) {
											$stuId = $stu['Student']['id'];
										}
										//echo " *** ".$courseCode;
										$caeId = $this->CourseMapping->getCmIdCaeIdFromBatchIdProgramIdCourseCode($abs_batch_id, $abs_program_id, $courseCode, $assessment_number, "CAE", 1);
									}
								}
								//echo $marks." ".$cae_assessment_mark." ".$courseCode." ".$regNumber." ".$caeId." ".$stuId;
						
								if ($marks > $cae_assessment_mark) {
									$relook.=$courseCode."-".$regNumber.",";
									$mark_status = "Not Entered";
									$approval_status = 0;
								}
								else if ($marks != "NA" || $marks == 'A' || $marks == 'a') {
									$conditions = array(
											'ContinuousAssessmentExam.cae_id' => $caeId,
											'ContinuousAssessmentExam.student_id' => $stuId
									);
									//pr($conditions);
									if ($this->ContinuousAssessmentExam->hasAny($conditions)){
										//echo "if";
										$this->ContinuousAssessmentExam->query("UPDATE continuous_assessment_exams set
														marks='".$marks."', modified='".date("Y-m-d H:i:s")."',
														modified_by = ".$this->Auth->user('id')."
														WHERE cae_id=".$caeId." AND student_id=".$stuId);
						
									}
									else {
										$test = $this->ContinuousAssessmentExam->query("insert into
														continuous_assessment_exams (student_id, cae_id, marks, created_by)
														values (".$stuId.", ".$caeId.", '".$marks."',
														".$this->Auth->user('id').")");
						
									}
									/* $dbo = $this->CourseMapping->getDatasource();
									 $logs = $dbo->getLog();
									 $lastLog = end($logs['log']);
									 echo $lastLog['query']; */
								}
							}
							$test = $this->Cae->query("
												update caes set marks_status='".$mark_status."', add_status=1,
									approval_status=$approval_status,
									modified_by=".$this->Auth->user('id').", modified = '".date("Y-m-d H:i:s")."',
									marks = $cae_assessment_mark WHERE id=".$caeId
									);
							//$this->Flash->success('Proceed '.$caeId.' hi '.$column.' hi '.$row.' hi '.$highestRow.' Datarow '.$dataRow);
							//$this->Flash->success('Successfully imported data');
							//}
							/* else {
							$this->Flash->error(__('Import the previous '.$num.' caes for course code '.$courseCode));
							return false;
							} */
						}
						//Mark reading ends here
						
					}
					//else {
						
					//} nested if
				}
					
			}
			//}
			if ($relook <> "")
				$this->Flash->success('Successfully imported data except for : '.$relook);
				else
					$this->Flash->success('Successfully imported data');
		}
		} else {
			$this->render('../Users/access_denied');
		}
	}
	
	public function cmGetCmIdfromCourseCode($arrCourseCode, $batchId, $programId, $academicId, $month_year_id) {
		$cmIdCourseId = array();
		foreach ($arrCourseCode as $courseCode => $marks) {
			$course_id = $this->Course->find('all', array(
					'conditions' => array(
							'Course.course_code' => $courseCode,
					),
					'fields' => array('Course.id'),
					'recursive' => 0
			));
				
			//pr($course_id);
			if (!empty($course_id) && count($course_id)>0) {
				$courseId = $course_id[0]['Course']['id'];
				//echo $courseId;
				$cmId = $this->getCmIdFromCourseId($courseId, $batchId, $programId, $month_year_id);
				//echo "CM Id : ".$cmId; 
				//pr($cmId);
				$cmIdCourseId[$courseCode] = array('cmId'=>$cmId, 'courseId'=>$courseId);
				//pr($cmIdCourseId);
			}
			else {
				$cmIdCourseId[$courseCode] = array('cmId'=>'', 'courseId'=>'');
			}
			
		}	
		return $cmIdCourseId;
	}
	
	public function getCmIdFromCourseId($courseId, $batchId, $programId, $month_year_id) {
		//echo $courseId." ".$batchId." ".$programId." ";
		$courseMappingId = $this->CourseMapping->find('all', array(
				'conditions' => array('CourseMapping.batch_id' => $batchId,
						'CourseMapping.program_id' => $programId,
						'CourseMapping.course_id' => $courseId,
						'CourseMapping.month_year_id' => $month_year_id,
						'CourseMapping.indicator' => 0
				),
				'fields' => array('CourseMapping.id'),
				'recursive' => 0
		));
		
		//pr($courseMappingId);
		if (!empty($courseMappingId) && count($courseMappingId) > 0) {
			$cmId = $courseMappingId[0]['CourseMapping']['id'];
			return $cmId;
		}
	}
	
	public function validateIfCourseCodeExists($courseMappingWithBatchAndProgramArray, $courseCodeDetails) {
		//echo "sample";
		//pr($courseMappingWithBatchAndProgramArray);
		//pr($courseCodeDetails);
		
		$excelCmId = array();
		foreach ($courseCodeDetails as $courseCode => $courseCodeDetail) {
			$excelCmId[$courseCodeDetail['cmId']] = $courseCodeDetail['cmId']; 
		}
		//pr($excelCmId);
		$diffBetExcelAndDb = array_diff_key($courseMappingWithBatchAndProgramArray, $excelCmId);
		//pr($diffBetExcelAndDb);
		
		if(!empty($diffBetExcelAndDb) && $diffBetExcelAndDb > 0) { 
			//echo "Course code given in excel did not match with the course mapping in the database";
			return $diffBetExcelAndDb;
		}
		return true;
	}
	
	function updateCaes() {
		$data=array();
		$data['Cae']['marks_status'] = "Entered";
		$data['Cae']['add_status'] = 1;
		$data['Cae']['approval_status'] = 1;
		$data['Cae']['id'] = $caeId;
		$this->Cae->save($data);
	}
	
	function exportInternal($program_id, $batch_id, $academic_id, $month_year_id) {
	
		$stuList = $this->getStudentsWithBatchAndProgram($batch_id, $program_id, $month_year_id);
		//pr($stuList);
		$cm = $this->getCourseMappingWithMonthYearBatchProgram($month_year_id, $batch_id, $program_id);
	
		//pr($cm);
	
		$this->PhpExcel->createWorksheet();
		//	$this->PhpExcel->getActiveSheet()->mergeCells('A1:C1');
		$this->PhpExcel->setDefaultFont('Arial', 9);
	
		$cmCaeId = array();
		$table = array(
				array('label' => __('RegNumber'), 'filter' => true),
				array('label' => __('Name'), 'filter' => true),
		);
	
		foreach ($cm as $courseMapping) {
			$cmId[]=$courseMapping['CourseMapping']['id'];
			$arrVar = array('label' => __($courseMapping['CourseMapping']['id']), 'filter' => true);
			array_push($table, $arrVar);
		}
		$this->PhpExcel->addTableHeader($table, array('name' => 'Arial', 'size'=>11));
		//	pr($cmId);
		//pr($cmCaeId);
		$masterTblRow = array();
		for ($i=0; $i<count($stuList); $i++) {
		
			$tblRow = array(
					$stuList[$i]['Student']['registration_number'],
					$stuList[$i]['Student']['name']);
				
			$student_id = $stuList[$i]['Student']['id'];
			$stuList[$i]['Student']['CourseMapping']=array();
			foreach ($cmId as $cm_id) {
	
				//Code for InternalExam starts here
				$internalMarks = $this->InternalExam->find('all',  array(
						'conditions' => array('InternalExam.course_mapping_id' => $cm_id,
								'InternalExam.student_id' => $student_id
						),
						'fields' => array('InternalExam.marks'),
						'recursive' => -1
				));
				if(empty($internalMarks)) {
				 $intMarks = 0;
				 $stuList[$i]['Student']['CourseMapping']['Internals'][$cm_id] = 0;
				} else {
					$intMarks = $internalMarks[0]['InternalExam']['marks'];
					$stuList[$i]['Student']['CourseMapping']['Internals'][$cm_id] = $internalMarks[0]['InternalExam']['marks'];
				}
				array_push($tblRow, $intMarks);
			}
			//array_push($masterTblRow, $tblRow);
			$this->PhpExcel->addTableRow(
				$tblRow
			);
		}
		$this->PhpExcel->addTableFooter();
		$this->PhpExcel->output("CAE_Internal.xlsx");
		//pr($stuList);
	
		$this->set(compact('stuList', 'cmId'));
	
		$this->layout = null;
		$this->autoLayout = false;
		//Configure::write('debug','0');
	}
	
	public function facultyAssignment() {
		$academics = $this->Student->Academic->find('list');
		$monthYear = $this->MonthYear->find('all');
		$faculty = $this->User->find('list', array(
				'conditions'=>array('User.user_role_id'=>6)
		));
		$this->set(compact('academics', 'programs', 'faculty'));
	}
	
	public function searchFacultyAssignment($program_id, $academic_id, $user_id) {
		SWITCH (true) {
			case ($user_id > 0 && $academic_id > 0 && $program_id > 0):
				$conditions = array();
				$conditions['CourseFaculty.user_id']=$user_id;
				$conditions['CourseMapping.program_id']=$program_id;
				break;
			case ($program_id > 0 && $academic_id > 0):
				$conditions = array();
				$conditions['CourseMapping.program_id']=$program_id;
				break;
			case ($user_id > 0):
				$conditions = array();
				$conditions['CourseFaculty.user_id']=$user_id;
				break;
			//default:
				//echo "None";
				//break;
		}
		
		$result = $this->CourseFaculty->find('all', array(
							'conditions' => $conditions,
							'fields' => array('CourseFaculty.id', 'CourseFaculty.course_mapping_id',
										'CourseMapping.batch_id', 'CourseMapping.program_id', 'CourseMapping.course_id',
										'User.id', 'User.username'
							),
							'recursive' => 1
					));
		$this->set(compact('result'));
		$this->layout=false;
	}
	
	public function addFaculty() {
		$academics = $this->Student->Academic->find('list');
		$faculty = $this->User->find('list', array(
				'conditions'=>array('User.user_role_id' => 6)
		));
		$this->set(compact('academics'/*, 'programs'*/,  'faculty'));
		$bool = false;
		if($this->request->is('post')) {
			//pr($this->data);
			$user_id = $this->request->data['Add']['lecturer_id'];
			$cmArray = $this->request->data['course_mapping_id'];
			//pr($cmArray);
			foreach ($cmArray as $cmarray) {
				//echo $cmarray;
				$data = array();
				$data['CourseFaculty']['course_mapping_id'] = $cmarray;
				$data['CourseFaculty']['user_id'] = $user_id;
				$data['CourseFaculty']['created_by'] = $this->Auth->user('id');
				$this->CourseFaculty->create();
				$this->CourseFaculty->save($data);
				$bool = true;
			}
		}
		if ($bool) {
			$this->Flash->success(__('Faculty has been added.'));
			return $this->redirect(array('action' => 'facultyAssignment'));
		}
		$this->layout=false;
	}
	
	public function editFaculty($course_faculty_id, $program_id, $cmId, $faculty_id) {
		$facultyArray = $this->User->find('list', array(
				'conditions' => array('User.user_role_id' => 6)
		));
		//pr($facultyArray);
		$bool = false;
		//pr($faculty_id);
		$this->set(compact('facultyArray', 'faculty_id', 'program_id', 'cmId', 'course_faculty_id'));
		if($this->request->is('post')) {
			//pr($this->data);
			$data = array();
			$data['CourseFaculty']['id'] = $this->request->data['Edit']['id'];
			$data['CourseFaculty']['course_mapping_id'] = $this->request->data['Edit']['cm_id'];
			$data['CourseFaculty']['user_id'] = $this->request->data['Edit']['faculty_id'];
			$data['CourseFaculty']['modified_by'] = $this->Auth->user('id');
			$data['CourseFaculty']['modified_timestamp'] = date("Y-m-d H:i:s");
			$this->CourseFaculty->save($data);
			$bool = true;
		}
		if($bool) {
			$this->Flash->success(__('Faculty has been edited.'));
			return $this->redirect(array('action' => 'facultyAssignment'));
		}
	}
	
	public function findProgramByAcademic($id = null, $option) {
		$options = array('conditions' => array('Program.academic_id'=> $id), 'fields' => array('Program.program_name'));
		$programs = $this->set('programs', $this->Program->find('list', $options));
		//pr($programs);
		$this->layout=false;
	}
	
	public function findCourseMappingByProgram() {
		//pr($_REQUEST['data']); 
		$program = json_decode($_POST['data'], true);

		$options = array(); $tempArray = array();
		foreach ($program as $pgm) {
			//pr($pgm);
			$program_id = $pgm['name'];
			$program_name = $pgm['value'];
			$optg = array();
			$opt = $this->Cae->CourseMapping->find('all', array(
					'conditions' => array('CourseMapping.program_id' => $program_id, 'CourseMapping.indicator' => 0),
					'fields' => array('CourseMapping.id', 'CourseMapping.course_id', 'Course.course_name'),
					'recursive' => 0
			));
			//pr($opt);
			foreach ($opt as $optTemp) {
				$optg[$optTemp['CourseMapping']['id']] = $optTemp['Course']['course_name'];
			}
			//pr($optg); 
			$options[$program_name] = $optg;
			//array_push($options, $tempArray);
			//pr($options);
		}
		$this->set(compact('options'));
		$this->layout=false;
	}
	
	public function practical() {
		$academics = $this->Student->Academic->find('list');
		$batches = $this->Student->Batch->find('list', array('fields' => array('Batch.batch_period')));
		
		$monthYears = $this->MonthYear->getAllMonthYears();
		$action = $this->action;
		$this->set(compact('batches', 'academics', 'programs', 'monthYears'/* , 'courseTypes' */, 'action'));
	}
	
	public function project() {
		$academics = $this->Student->Academic->find('list');
		$batches = $this->Student->Batch->find('list', array('fields' => array('Batch.batch_period')));
		
		$monthYears = $this->MonthYear->getAllMonthYears();
		$action = $this->action;
		$this->set(compact('batches', 'academics', 'programs', 'monthYears'/* , 'courseTypes' */, 'action'));
	}
	
	public function practicalTemplate($template) {
		$this->set(compact('template'));
		$this->layout=false;
	}

	public function theoryAndPracticalTemplate($template) {
		$this->set(compact('template'));
		$this->layout=false;
	}
	
	public function projectTemplate() {
		$this->layout=false;
	}
	
	public function theoryTemplate() {
		$this->layout=false;
	}
	
	/* public function getCourseMaxMarksByCmId($cmId) {
		$courseMaxMarks = $this->CourseMapping->find('first', array(
				'conditions' => array('CourseMapping.id' => $cmId, 'CourseMapping.indicator' => 0),
				'fields' => array('CourseMapping.course_max_marks', 'CourseMapping.id'),
				'recursive' => 0
		));
		$marks = $courseMaxMarks['CourseMapping']['course_max_marks'];
		echo $marks;
		exit;
	} */
	
	public function retrieveCourseTypeFromCourseWithCmId($cmId, $ajax) {
		$course = $this->CourseMapping->find('list', array(
					'conditions' => array('CourseMapping.id' => $cmId, 'CourseMapping.indicator' => 0),
					'fields' => array('Course.course_type_id'),
					'recursive' => 1
			));
		//pr($course);
		if($ajax) {
			echo $course[$cmId]; exit;
		}
		else {
			return $course[$cmId];
		}
		
		$this->layout=false;
	}
	
	public function import() {
		$academics = $this->Student->Academic->find('list');
		$batches = $this->Student->Batch->find('list', array('fields' => array('Batch.batch_period')));
		
		$monthYear = $this->MonthYear->find('all');
		//pr($monthYear);
		$monthYears = array();
		
		for ($i=0; $i<count($monthYear);$i++) {
			$monthYears[$monthYear[$i]['MonthYear']['id']] = $monthYear[$i]['Month']['month_name']." - ".$monthYear[$i]['MonthYear']['year'];
		}
		$action = $this->action;
		$this->set(compact('batches', 'academics', 'programs', 'monthYears'/* , 'courseTypes' */, 'action'));
	}
	
	/* public function caeInfo($batch_id, $academic_id, $program_id, $month_year_id) {
		echo $batch_id." ".$academic_id." ".$program_id." ".$month_year_id;
		$courseMapping = $this->getCourseMapping($program_id, $batch_id, $academic_id, $month_year_id);
		//pr($courseMapping);
		$courseMappingArray = $this->getCourseMappingArray($courseMapping);
		//echo "second";
		//pr($courseMappingArray);
		$firstCourseMappingId = $courseMappingArray[0];
		
		$semester_id = $this->findSemesterForACourseMappingId($firstCourseMappingId);
		//echo "Semester";
		//pr($semester_id);
		$allCourseMappingArray = $this->retrieveAllCoursesFromBatchProgramAndFirstSemester($batch_id, $program_id, $semester_id);
		//pr($allCourseMappingArray);
		
		$caeArray = $this->getCaeId($courseMapping, $courseMappingArray);
		//pr("third");
		//pr($caeArray);
		
		// sort alphabetically by name
		$nArray = usort($caeArray, 'compare_model');
		//pr($nArray);
		
		$noCaes = array_diff_key($allCourseMappingArray,$caeArray);
		//print_r($noCaes);

		$this->layout=false;
	} */
	
	public function compare_model($a, $b) {
		return strnatcmp($a['model'], $b['model']);
	}
	
	public function displayButton($cmId, $template) {
		$disableButton = 0;
		//echo $cmId." ".$template;
		SWITCH ($template) {
			CASE "index":
			CASE "theoryTemplate":
				$model = "Cae";
				break;
			CASE "projectTemplate":
				$model = "CaeProject";
				break;
			CASE "practicalTemplate":
				$model = "CaePractical";
				break;
		}
		//pr($model);
		/* $result = $this->CaePractical->find('all', array(
			'conditions' => array('CaePractical.course_mapping_id'=>$cmId),
			'fields'=>array('CaePractical.id')
		));
		pr($result); */
		
		$caeCount = $this->$model->find('all', array(
				'conditions' => array("$model.course_mapping_id" => $cmId, "$model.indicator"=>0),
		));
		//pr($caeCount);
		
		if ($template == "projectTemplate" || $template == "practicalTemplate") {
			if (count($caeCount) > 0) {
				$disableButton = 1;
			}
			else {
				$disableButton = 0;
			}
		}
		echo $disableButton;
		exit;
	}
	
	public function findProgramByBatch($id = null) {
		$pgms = $this->Student->query("select distinct(program_id), Program.program_name 
				from students as Student 
				JOIN programs as Program ON Program.id = Student.program_id 
				where batch_id=".$id);
		$programs = array();
		foreach ($pgms as $pgm) {
			$programs[$pgm['Student']['program_id']] = $pgm['Program']['program_name'];
		}
		//pr($programs);
		$this->set('programs', $programs);
		$this->layout=false;
	}
	
	public function final_cae(){
		$access_result = $this->checkPathAccess($this->Auth->user('id'));
		if (!$access_result) {
		
		$batches = $this->Student->Batch->find('list', array('fields' => array('Batch.batch_period'),'order' => array('Batch.batch_to DESC')));
		$programs = $this->Student->Program->find('list',array('order' => array('Program.program_name ASC')));		
		
		$monthYears = $this->MonthYear->getAllMonthYears();
		//pr($monthYears);
		
		$this->set(compact('batches', 'programs', 'monthYears'));
		
		if(isset($_REQUEST['PRINT']) || isset($_REQUEST['DOWNLOAD'])) {
			$month_year_id = 0; $batch_id = ""; $program_id = 0;
			$month_year_id = $this->data['ContinuousAssessmentExam']['monthYear'];
			$batch_id = $this->data['ContinuousAssessmentExam']['batch_id'];
			if(isset($this->data['program_id'])){
				$program_id = $this->data['program_id'];
			}
			if(isset($this->data['ContinuousAssessmentExam']['program_id'])){
				$program_id = $this->data['ContinuousAssessmentExam']['program_id'];
			}
			
			$courseMapping = $this->getCourseMappingWithMonthYearBatchProgram($month_year_id, $batch_id, $program_id);
			
			$courseType = $this->listCourseTypeIdsBasedOnMethod($this->cType, "-");
			$courseTypeIdArray = explode("-",$courseType);
			
			$programId = array(); $batchId = array();
			if(isset($this->data['program_id']) && ($this->data['program_id'])){
				$programId['CourseMapping.program_id'] = $program_id;
			}
			if($this->data['ContinuousAssessmentExam']['batch_id']){
				$batchId['CourseMapping.batch_id'] = $batch_id;
			}			
			$courseMapping=$this->CourseMapping->find('all', array(
				'conditions' => array(
					$batchId, $programId, 
					'CourseMapping.month_year_id'=>$month_year_id, 'CourseMapping.indicator'=>0,
					'Course.course_type_id'=>$courseTypeIdArray
				),
				'fields' => array('CourseMapping.id'),				
				'order' => array('CourseMapping.id ASC'),
				'recursive'=>0
			));
			
			$cmIdArray = $this->getCmId($courseMapping);
			//pr($cmIdArray);
			$marks = $this->InternalExam->find('all',
					array(
						'conditions' => array(
							'InternalExam.course_mapping_id' => $cmIdArray,
						),
						'fields' => array(
							'InternalExam.id',
							'InternalExam.course_mapping_id', 'InternalExam.marks',
							'InternalExam.student_id'
						),
						'contain' =>array(
							'Student'=>array('fields'=>array('Student.registration_number','Student.name'),								
								'conditions'=>array('Student.discontinued_status' => 0,),
									'Program' => array(
											'fields' => array('Program.program_name', 'Program.short_code'),
											'Academic' => array(
													'fields' => array('Academic.academic_name', 'Academic.short_code')
											),
									),'order'=>array('Student.registration_number'),
							),
							'CourseMapping'=>array('fields'=>array('CourseMapping.id','CourseMapping.semester_id'),
								'Course' => array(
										'fields' => array('Course.id','Course.course_code','Course.course_name'),
								),
								'Batch'=>array('fields' =>array('Batch.batch_from','Batch.batch_to','Batch.academic')),
							),
						),
						'order' => array('InternalExam.course_mapping_id ASC'),
						'recursive' => 0
					)
				);


			if(isset($_REQUEST['DOWNLOAD'])) {
				$this->finalCAEExcel($marks);
			}
			$this->set(compact('marks','cmIdArray','month_year_id','batch_id','program_id'));
			$this->set("PRINT","PRINT");
			$this->layout=false;
			if(isset($_REQUEST['PRINT']) == 'PRINT') {
				$intStudentMark = array();  $ad=$std_name=$std_reg=array(); 
				foreach($marks as $res){ 
					$intStudentMark[$res['InternalExam']['student_id']][$res['InternalExam']['course_mapping_id']] = $res['InternalExam']['marks'];

						$std_name[]=$res['Student']['name'];
    				    $std_reg[]=$res['Student']['registration_number'];
				}

				$html ="";
				$txtExamMonthYear = $this->getExamMonthYear($this->request->data['ContinuousAssessmentExam']['monthYear']);
				$headerLogo = "<table border='0' align='center' cellpadding='0' cellspacing='0' style='font:14px Arial;'>
								<tr>
								<td rowspan='2'><img src='../webroot/img/user.jpg'></td>
									<td align='center'>SATHYABAMA UNIVERSITY<br/>
									<span class='slogan'></span></td>
								</tr>
								</table>";
					
					if(isset($marks)) {
						$pnum='';
						$pageNumber=0;
						$j=1;$pageSeq=0;$totSubject = count($cmIdArray);
						for($p=0;$p<count($intStudentMark);$p++) {
						if($marks[$p]['Student']['id']){
						if($j==1 || $pageSeq == 25){$pageSeq=0; $pageNumber++;
							if($j!=1){
								$pnum = $pageNumber-1;
								$html .= "</table>
										<table border='0' cellpadding='0' cellspacing='0' style='width:100%;
													border-collapse:collapse;border-bottom:0;'>
										<tr><td colspan='4' align='right'><b>Page No. ".$pnum."</div></b></td></tr></table>";
								$html .= "<div style='page-break-after:always'></div>";
							}							
							$html .= $headerLogo;
							$html .= "<table border='1' cellpadding='0' cellspacing='0' style='width:100%;'>
							<tr><td colspan='4' align='center'><b>FINAL CAE - OUT OF 50 MARKS</b></td></tr>
							<tr>
								<td style='height:24px;'><b>&nbsp;Batch</b></td>
								<td>&nbsp;".$marks[$p]['CourseMapping']['Batch']['batch_from']." - ".$marks[$p]['CourseMapping']['Batch']['batch_to']."</td>
								<td><b>&nbsp;Program</b></td>
								<td>&nbsp;".$marks[$p]['Student']['Program']['Academic']['short_code']."</td>
							</tr>
							<tr>
								<td style='height:24px;'><b>&nbsp;Month&Year of Exam</b></td>
								<td>&nbsp;".$txtExamMonthYear."</td>
								<td><b>&nbsp;Specialisation</b></td>
								<td>&nbsp;".$marks[$p]['Student']['Program']['program_name']."</td>
							</tr>
						</table><table border='1' cellpadding='0' cellspacing='0' style='width:100%;font:12px airal;'>";
							$html .="<tr>
									<th style='height:24px;'> Sl.&nbsp;No.</th>
									<th> Reg.No.</th>
									<th style='width:250px;'> Student&nbsp;Name</th>";
									if($cmIdArray){  for($i=0;$i<$totSubject;$i++){
										$html .="<th>".$this->getCourseCode($cmIdArray[$i])."</th>";
									
									}}
							$html .="<th> SIGNATURE</th></tr>";
						}$pageSeq++;
						/*
						$html .="<tr>
						<td style='height:24px;text-align:center;'>".$j."</td>
						<td style='text-align:center;'>".$marks[$p]['Student']['registration_number']."</td>
						<td style='text-indent:5px;'>&nbsp;".$marks[$p]['Student']['name']."</td>";
						*/
						$html .="<tr>
						<td style='height:24px;text-align:center;'>".$j."</td>
							<td style='text-align:center;'>".$std_name[$p]."</td>
						<td style='text-indent:5px;'>&nbsp;".$std_reg[$p]."</td>";
							if($cmIdArray){for($i=0;$i<$totSubject;$i++){
						      $html .="<td align='center'>";	
						      					        
						       /* if(isset($intStudentMark[$marks[$p]['Student']['id']][$cmIdArray[$i]])){ 
						        	$html .= $intStudentMark[$marks[$p]['Student']['id']][$cmIdArray[$i]];
						        } */

						       if(isset($intStudentMark[$ad[$p]][$cmIdArray[$i]])){ 
						        	$html .= $intStudentMark[$ad[$p]][$cmIdArray[$i]];
						        }
						      $html .="</td>";
						      }} 
							$html .="<td style='width:200px;'></td></tr>";$j++;
						}
						}
					} 
					$pnum++;
				$html.="</table>
							<table border='0' cellpadding='0' cellspacing='0' style='width:100%;
													border-collapse:collapse;border-bottom:0;'>
										<tr><td colspan='4' align='right'><b>Page No. ".$pnum."</div></b></td></tr></table>";
				$this->mPDF->init();
				$this->mPDF->setFilename("CAE_MARKS_".date('d_M_Y').'.pdf');
				$this->mPDF->setOutput('D');
				$this->mPDF->AddPage('L','', '', '', '',10,10,5,5,18,12);
				$this->mPDF->setFooter($footerFoilCard);
				$this->mPDF->WriteHTML($html);
				$this->mPDF->SetWatermarkText("Draft");
				$this->set("PRINT","PRINT");
			}
		}
		} else {
			$this->render('../Users/access_denied');
		} 
	}
	
	public function processStudentArray($studentArray) {
		$array = array();
		foreach ($studentArray as $key => $student) {
			$array[$student['Student']['id']] = $student['Student']['registration_number'];
		}
		return $array;
	}
	
	public function truncateEmptyCmIds($cmAll, $processedStudentArray) {
		$cm = array();
		foreach ($cmAll as $key => $array) {
			if (!empty($array['Course']['course_code'])) {

				$cm[$array['CourseMapping']['id']]['course_code'] = $array['Course']['course_code'];
				//$cm[$array['CourseMapping']['id']]['CourseStudentMapping'] = $array['CourseStudentMapping'];



				if ($processedStudentArray!="-") {   
					foreach ($array['CourseStudentMapping'] as $k => $val) {
						$cm[$array['CourseMapping']['id']]['csm'][$val['student_id']] = $val['indicator'];
					

					}
					//echo $array['CourseMapping']['id'];
				
				
					//pr(array_diff($processedStudentArray, $cm[$array['CourseMapping']['id']]['csm']));
					//pr($processedStudentArray);
					//echo count($processedStudentArray);
					//pr($cm[$array['CourseMapping']['id']]['csm']);
					//echo count($cm[$array['CourseMapping']['id']]['csm']);
					$difference = array_diff_key($processedStudentArray, $cm[$array['CourseMapping']['id']]['csm']);
					//pr($difference);
					foreach ($difference as $student_id => $reg_num) {
						$cm[$array['CourseMapping']['id']]['csm'][$student_id] = 1;
					}
					//echo count($difference);
				} else {
					$cm[$array['CourseMapping']['id']]['CourseStudentMapping'] = $array['CourseStudentMapping'];
				}
			}
		}
		return $cm;
	}
	
	public function createCaeDownloadTemplate() {
		$access_result = $this->checkPathAccess($this->Auth->user('id'));
		if (!$access_result) {
		$this->set('batches',$this->Batch->find('list', array('fields' => array('Batch.batch_period'))));
		$this->set('academics', $this->Student->Academic->find('list'));
		//$this->set('programs', $this->Student->Program->find('list'));
		$monthyears = $this->findMonthYear();
		$this->set(compact('batches', 'academics'/* , 'programs' */, 'monthyears'));
		if($this->request->is('post')) {
			//pr($this->data);
			
			//$batch_id = 4; $program_id=2; $academic_id=2; $month_year_id=5;
			$batch_id = $this->data['Student']['batch_id'];
			$batch = $this->Batch->getBatch($batch_id);
			
			$academic_id = $this->data['Student']['academic_id'];
			$academicDetails = $this->Academic->getAcademic($academic_id);
			
			$program_id = $this->data['Student']['program_id'];
			$programDetails = $this->Program->getProgram($program_id);
			
			$assessment_number = $this->data['Student']['assessment_number'];
			
			$month_year_id = $this->data['month_year_id'];
			$month_year = $this->MonthYear->getMonthYear($month_year_id);
			
			$studentArray = $this->Student->getStudentsWithBatchAndProgram($batch_id, $program_id, $month_year_id);
			// pr($studentArray);
			// exit;
			$processedStudentArray = $this->processStudentArray($studentArray);
			
			$absResult = $this->StudentAuthorizedBreak->findAbsStudents();
			//pr($absResult);
			$currentBatchDuration = $this->Batch->getBatchDuration($batch_id);

			//echo $currentBatchDuration." ".$month_year_id;
			$absUsers = $this->processAbsResult($absResult, $currentBatchDuration, $program_id, $month_year_id);
			//pr($absUsers);  
			$courseType = $this->listCourseTypeIdsBasedOnMethod($this->cType, "-");
			$courseTypeIdArray = explode("-",$courseType);
			
			$cmAll = $this->courseCodeForASemester($batch_id, $program_id, $month_year_id, $courseTypeIdArray);
			//pr($courseTypeIdArray);
			//exit;
			 // pr($cmAll);
			 // pr($processedStudentArray);
			 // exit;
			$cm = $this->truncateEmptyCmIds($cmAll, $processedStudentArray);
			 //pr($cm);
			 
			$this->download_template($batch, $academicDetails, $programDetails, $month_year, $studentArray, $cm, $assessment_number, "CAE", $this->cType, $month_year_id, $absUsers);
			
		}
		} else {
			$this->render('../Users/access_denied');
		}
	}
	
	public function courseCodeForASemester($batch_id, $program_id, $month_year_id, $courseTypeIdArray) {


		$courseMapping = $this->CourseMapping->find('all', array(
				'fields' => array('CourseMapping.id', 'Course.course_code'),
				'conditions' => array(
					'CourseMapping.batch_id'=>$batch_id, 
					'CourseMapping.program_id' => $program_id,
					'CourseMapping.month_year_id' => $month_year_id,
					'CourseMapping.indicator' => 0,
				),
				'contain'=>array(
						'Course'=>array('conditions'=>array('Course.course_type_id' => $courseTypeIdArray),
						),
						'CourseStudentMapping'=>array(
								'fields'=>array('CourseStudentMapping.id', 'CourseStudentMapping.student_id',
										 'CourseStudentMapping.indicator'),
								'Student'=>array(
										'fields'=>array('Student.registration_number')
								)
						)
				),
				'recursive' => 1
		));
		// pr($courseMapping); exit;
		return $courseMapping;
	}
	
	public function moderateIndividualCae() {
		if($this->request->is('post')) {
			//pr($this->data);
			$studentId = $this->data['CAE']['studentId'];
			$cmIdArray = $this->data['CAE']['cmId'];
			$marksArray = $this->data['CAE']['marks'];
			for($i=0; $i<count($cmIdArray); $i++) {
				echo $cmIdArray[$i]." ".$marksArray[$i]."</br>";
				$this->InternalExam->query("UPDATE internal_exams set marks=".$marksArray[$i].",
								/* moderation_operator='".$modResult['modOperator']."',
								moderation_marks=".$modResult['modMark'].", 
								moderation_date='', */
								modified='".date("Y-m-d H:i:s")."' WHERE course_mapping_id=".$cmIdArray[$i]."
								AND student_id=".$studentId);
				//$this->Flash->success(__('The internal marks have been edited.'));
			}
		}
	}
	
	public function retrieveStudentBatchAndProgram($regNumber) {
		$batchProgram = $this->Student->find('all', array(
				'conditions' => array('Student.registration_number' => $regNumber),
				'fields' => array('Student.batch_id', 'Student.program_id', 'Student.id'),
		));
		//pr($batchProgram);
		return $batchProgram;
	}
	
	public function getIndividualStudentData($regNumber) {
		$batchProgram=$this->retrieveStudentBatchAndProgram($regNumber);
		$internalDetails = $batchProgram[0]['InternalExam'];
		$studentId = $batchProgram[0]['Student']['id'];
		//pr($internalDetails);
		$this->set(compact('internalDetails', 'studentId'));
		$this->layout=false;
	}	
	
	public function caeReport() {
		$batches = $this->Batch->find('list', array('fields' => array('Batch.batch_period'),'order' => array('Batch.batch_to DESC')));
		
		$monthyears = $this->MonthYear->getAllMonthYears();
		
		$this->set(compact('monthyears', 'batches'));
		
		if($this->request->is('post')) {
			//pr($this->request->data);
			$cmIdArray = array();
			
			//$batchId = $this->request->data['Cae']['batch_id'];
			$monthYearId = $this->request->data['Cae']['month_year_id'];
			$caeNo = $this->request->data['Cae']['no'];
			
			$course_type_id =$this->listCourseTypeIdsBasedOnMethod("theory", "-");
			$courseTypeIdArray = explode("-",$course_type_id);
			
			$cmArray = $this->CourseMapping->getCmIdWithBatchAndMonthYearId($monthYearId, $courseTypeIdArray);
			//pr($cmArray);
			
			$caeArray = array();
			
			foreach ($cmArray as $cm_id => $val) {
				$caeResult = $this->Cae->find('all', array(
						'conditions' => array('Cae.course_mapping_id' => $cm_id),
						'fields' => array('Cae.id'),
						'order' => 'Cae.id ASC',
						'recursive' => 0
				));
				//pr($caeResult);
				$assessmentCount = count($caeResult);
				//echo $assessmentCount." ".$caeNo;
					
				$tmp = $caeNo-1;
				if (isset($caeResult[$tmp])) {
					$cae_id = $caeResult[$tmp]['Cae']['id'];
					//echo "CAE Id : ".$cae_id;
					$cmIdArray[$cm_id] = $cm_id;
					$cae = $this->ContinuousAssessmentExam->find('all', array(
							'conditions'=>array('cae_id'=>$cae_id),
							'fields'=>array('ContinuousAssessmentExam.student_id', 'ContinuousAssessmentExam.marks'),
							'contain'=>array(
									'Student'=>array(
											'conditions'=>array('Student.discontinued_status'=>0),
											//'fields'=>array('Student.name', 'Student.registration_number')
									)
							),
							'recursive'=>0
					));
					foreach ($cae as $key => $value) { 
						if (isset($value['Student']['id'])) {
							//$caeArray[$cm_id][$cae_id][$value['Student']['id']]=$value['ContinuousAssessmentExam']['marks'];
							$caeArray[$value['Student']['id']][$cm_id]=$value['ContinuousAssessmentExam']['marks'];
						}
					}
				}
			}
			//pr($caeArray);
			$this->caeReportExcel($caeArray, $cmIdArray, $monthYearId, $caeNo);
			
		}
	}
	
	public function caeReportSearch($batchId, $monthYearId, $caeNo, $printMode) {
		//echo $batchId." ".$monthYearId." ".$caeNo;
		
		
		$this->layout = false;
	}
	
	public function batchwiseCaeExport() {
		//
		$batches = $this->Batch->find('list', array('fields' => array('Batch.batch_period'),'order' => array('Batch.batch_to DESC')));
		$monthyears = $this->MonthYear->getAllMonthYears();
		$cae = array("1"=>"1","2"=>"2","3"=>"3");
		$this->set(compact('batches','monthyears','cae'));
		
		if($this->request->is("post")) {
			//pr($this->data);
			$batchId = $this->request->data['Cae']['batch_id'];
			$monthYearId = $this->request->data['Cae']['month_year_id'];
			$programId = 0;
			$caeNo = $this->request->data['Cae']['cae_id'];
			//$batchId = 0;
			
			$filterCondition = "";
			//echo $batch_id." ".$program_id." ".$month_year_id;
			if ($batchId != "" && $batchId > 0) {
				$filterCondition.= "`(CourseMapping`.`batch_id` = ".$batchId.") AND ";
			} else {
				$filterCondition.= "`(CourseMapping`.`batch_id` > 0)"." AND ";
			}
			
			if($programId > 0) {
				$filterCondition.= "`(CourseMapping`.`program_id` = ".$programId.")"." AND ";
			} else {
				$filterCondition.= "`(CourseMapping`.`program_id` > 0)"." AND ";
			}
				
			if($monthYearId > 0) {
				$filterCondition.= "`(CourseMapping`.`month_year_id` = ".$monthYearId.")"." AND ";
			} else {
				$filterCondition.= "`(CourseMapping`.`month_year_id` > 0)"." AND ";
			}
				
			// $filterCondition.= "`(CourseMapping`.`id` = 584)"." AND ";
				
			$filterCondition.= "((`CourseMapping`.`indicator` = 0)";
			$filterCondition.=")";
			
			$finalArray = array();
			
			$resCourseType=$this->CourseMapping->find('all', array(
					'conditions' => array($filterCondition),
					'fields' => array('CourseMapping.id', 'CourseMapping.batch_id', 'CourseMapping.program_id'),
					'order' => array('CourseMapping.id ASC'),
					'contain'=>array(
							/* 'CourseStudentMapping'=>array(
							 'fields'=>array('CourseStudentMapping.student_id', 'CourseStudentMapping.indicator')
							), */
							'Course'=>array(
									'fields'=>array('Course.course_type_id', 'Course.course_code', 'Course.course_name')
							)
					)
			));
			//pr($resCourseType);
			
			foreach ($resCourseType as $key => $array) {
				//pr($array);
				$cmId = $array['CourseMapping']['id'];
				$cmBatchId = $array['CourseMapping']['batch_id'];
				$cmProgramId = $array['CourseMapping']['program_id'];
				$courseCode = $array['Course']['course_code'];
				$courseName = $array['Course']['course_name'];
				
				$studentDetails = $this->Student->getStudentsInfo($cmBatchId, $cmProgramId);
				//pr($studentDetails);
				$finalArray[$cmId]['student'] = $studentDetails;
				$finalArray[$cmId]['details']['course_code'] = $courseCode;
				$finalArray[$cmId]['details']['course_name'] = $courseName;
				$finalArray[$cmId]['details']['batch_id'] = $cmBatchId;
				$finalArray[$cmId]['details']['program_id'] = $cmProgramId;
				
				$model = "";
				$subModel = "";
				SWITCH ($array['Course']['course_type_id']) {
					case 1:
					case 3:
						$model = "Cae";
						$subModel = "ContinuousAssessmentExam";
						break;
					case 2:
					case 6:
						$model = "CaePractical";
						$subModel = "InternalPractical";
						break;
					case 4:
						$model = "CaeProject";
						$subModel = "ProjectReview";
						break;
					case 5:
						$model = "CaePt";
						$subModel = "ProfessionalTraining";
						break;
				}
				$caeResults = $this->$model->find("all", array(
						'fields' => array("$model.id"),
						'conditions'=>array("$model.course_mapping_id"=>$array['CourseMapping']['id']),
						'contain'=>array(
								"$subModel"=>array(
										'fields'=>array(
												"$subModel.id", "$subModel.student_id", "$subModel.marks"
										),
								),
								'autoFields' => false
						),
						'recursive'=>0
				));
				//pr($caeResults);
					
				foreach ($caeResults as $k => $value) {
					if ($k == ($caeNo-1)) {
						$cae_id = $value[$model]['id'];
						$newArray = $value[$subModel];
							
						//pr($newArray);
						$result = Set::combine($newArray, '{n}.student_id', '{n}.marks');
						//pr($result);
						$finalArray[$cmId]['cae']=$result;
					}
				}
			}
			//echo count($finalArray);
			//pr($finalArray);
			$this->exportToExcel($finalArray, $monthYearId, $caeNo);
		}
	}
	
	public function exportToExcel($finalArray, $monthYearId, $caeNo) {
	
		$monthYears = $this->MonthYear->getMonthYear($monthYearId);
	
		$row = 1; // 1-based index
		$col = 0;
		$phpExcel = new PHPExcel();
		$phpExcel->setActiveSheetIndex(0);
		$sheet = $phpExcel->getActiveSheet();
		$sheet->getRowDimension('1')->setRowHeight('18');
		$sheet->setTitle("Cae");
		
		$sheet->setCellValueByColumnAndRow($col, $row, "REGISTER NUMBER");$col++;
		$sheet->setCellValueByColumnAndRow($col, $row, "STUDENT NAME");$col++;
		$sheet->setCellValueByColumnAndRow($col, $row, "BATCH");$col++;
		$sheet->setCellValueByColumnAndRow($col, $row, "PROGRAM");$col++;
		$sheet->setCellValueByColumnAndRow($col, $row, "SPECIALISATION");$col++;
		$sheet->setCellValueByColumnAndRow($col, $row, "COURSE CODE");$col++;
		$sheet->setCellValueByColumnAndRow($col, $row, "COURSE NAME");$col++;
		//$sheet->setCellValueByColumnAndRow($col, $row, "SEMESTER");$col++;
		$sheet->setCellValueByColumnAndRow($col, $row, "MONTH YEAR OF EXAM");$col++;
		$sheet->setCellValueByColumnAndRow($col, $row, "CAE");$col++;
		$row++;
		
		foreach ($finalArray as $cm_id => $array) {
			$sheet->getRowDimension($row)->setRowHeight('18');
			if (isset($array['cae']) && count($array['cae']>0)) {
				//echo $cm_id."</br>";
				//$count++;
				$stuArray = $array['student'];
				$detailsArray = $array['details'];
				$caeArray = $array['cae'];
				$pgmArray = $this->Program->getProgram($detailsArray['program_id']);
				foreach ($stuArray as $student_id => $stuDetails) {
					$col = 0;
					$sheet->getRowDimension($row)->setRowHeight('18');
					$sheet->setCellValueByColumnAndRow($col, $row, $stuDetails['reg_num']);$col++;
					$sheet->setCellValueByColumnAndRow($col, $row, $stuDetails['name']);$col++;
					$sheet->setCellValueByColumnAndRow($col, $row, $this->Batch->getBatch($detailsArray['batch_id']));$col++;
					$sheet->setCellValueByColumnAndRow($col, $row, $pgmArray['academic_short_code']);$col++;
					$sheet->setCellValueByColumnAndRow($col, $row, $pgmArray['short_code']);$col++;
					$sheet->setCellValueByColumnAndRow($col, $row, $detailsArray['course_code']);$col++;
					$sheet->setCellValueByColumnAndRow($col, $row, $detailsArray['course_name']);$col++;
					//$sheet->setCellValueByColumnAndRow($col, $row, $array['semester_id']);$col++;
					$sheet->setCellValueByColumnAndRow($col, $row, $monthYears);$col++;
					if (isset($caeArray[$student_id])) {
						$sheet->setCellValueByColumnAndRow($col, $row, $caeArray[$student_id]);$col++;
					}
					else {
						$sheet->setCellValueByColumnAndRow($col, $row, '');$col++;
					}
					$row++;
				}
			}
		}
		//echo $count;
		
		$download_filename=$monthYears."_CAE_".$caeNo."_".date('d-M-Y h:i:s');
		header("Content-Type: application/vnd.ms-excel");
		header("Content-Disposition: attachment; filename=\"$download_filename.xls\"");
		header("Cache-Control: max-age=0");
		$objWriter = PHPExcel_IOFactory::createWriter($phpExcel, "Excel5");
		$objWriter->save("php://output");
		exit;
	}
}