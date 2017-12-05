<?php
App::uses('AppController', 'Controller');
App::import('Controller', 'ContinuousAssessmentExams');
/**
 * CaePts Controller
 *
 * @property CaePt $CaePt
 * @property PaginatorComponent $Paginator
 * @property FlashComponent $Flash
 * @property SessionComponent $Session
 */
class CaePtsController extends AppController {

/**
 * Components
 *
 * @var array
 */
	public $components = array('Paginator', 'Flash', 'Session');
	public $uses = array("CaePt", "Batch", "Academic", "Student", "MonthYear", "ContinuousAssessmentExam", "CourseMapping", 
	"Course", "CourseType", "ProfessionalTraining", 
			"Lecturer", "EsePractical", "CourseStudentMapping",
			"User", "CourseFaculty", "CaePractical", "Project",
			"CaeProject", "GrossAttendance", "Cae", "CourseMode", "CourseMapping", "Attendance",
			"InternalExam", "Program", "InternalExam", "InternalPractical", "StudentMark",
			"StudentAuthorizedBreak"
	);
	public $cType = 'PT';
/**
 * index method
 *
 * @return void
 */
	public function index() {
		$access_result = $this->checkPathAccess($this->Auth->user('id'));
		if (!$access_result) {
			$academics = $this->Student->Academic->find('list');
			$batches = $this->Student->Batch->find('list', array('fields' => array('Batch.batch_period')));
			
			$monthYears = $this->MonthYear->getAllMonthYears();
			//pr($monthYears);
			
			$action = $this->action;
			$this->set(compact('batches', 'academics', 'programs', 'monthYears'/* , 'courseTypes' */, 'action'));
			/* $this->CaePt->recursive = 0;
			$this->set('caePts', $this->Paginator->paginate()); */
		} else {
			$this->render('../Users/access_denied');
		}
	}

	public function listCourseType() {
		$ContinuousAssessmentExamsController = new ContinuousAssessmentExamsController;
		# now you can reference your controller like any other PHP class
		$course_type_id = $this->listCourseTypeIdsBasedOnMethod($this->cType, "-");
		//pr($course_type_id);
		$courseTypeIdArray = explode("-",$course_type_id);
		//pr($courseTypeIdArray);
		return $courseTypeIdArray;
	}
	
	public function indexSearch($batch_id, $program_id, $month_year_id) {
		$courseTypes = $this->listCourseType();
		$professionalTrainings = $this->CourseMapping->find('all', array(
				'conditions'=>array('CourseMapping.batch_id'=>$batch_id, 'CourseMapping.program_id'=>$program_id,
						'CourseMapping.indicator'=>0
				),
				'fields'=>array('CourseMapping.*'),
				'contain'=>array(
					'Course'=>array(
						'fields'=>array('Course.id', 'Course.course_name', 'Course.course_code', 'Course.common_code'),
						'conditions'=>array('Course.course_type_id'=>$courseTypes),
						'CourseType'=>array(
							'fields'=>array('CourseType.course_type'),
						)
					),
					'CaePt'=>array(
						'fields'=>array('CaePt.*'),
					),
					'Batch'=>array(
						'fields'=>array('Batch.batch_from', 'Batch.batch_to', 'Batch.academic')
					),
					'Program'=>array(
						'fields'=>array('Program.program_name'),
						'Academic'=>array(
								'fields'=>array('Academic.academic_name'),
						)
					)
				)
		)
				);
		//pr($professionalTrainings);
		$this->set(compact('batch_id', 'program_id', 'month_year_id'));
		$this->set(compact('professionalTrainings'));
		$this->layout=false;
		/* $professionalTrainings = $this->CourseMapping->find('all', array(
		 'conditions'=>array('CourseMapping.batch_id'=>$batch_id, 'CourseMapping.program_id'=>$program_id,
		 'CourseMapping.indicator'=>0
		 ),
		 'fields'=>array('CourseMapping.id')
			));
		$this->set(compact('professionalTrainings')); */
	}
/**
 * view method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function view($id = null) {
		if (!$this->CaePt->exists($id)) {
			throw new NotFoundException(__('Invalid cae pt'));
		}
		$options = array('conditions' => array('CaePt.' . $this->CaePt->primaryKey => $id));
		$this->set('caePt', $this->CaePt->find('first', $options));
	}

/**
 * add method
 *
 * @return void
 */
	public function add() {
		$academics = $this->Student->Academic->find('list');
		$batches = $this->Student->Batch->find('list', array('fields' => array('Batch.batch_period')));
		$ContinuousAssessmentExamsController = new ContinuousAssessmentExamsController;
		$monthyears = $ContinuousAssessmentExamsController->findMonthYear();
		$cType = $this->cType;
		$this->set(compact('batches', 'academics', 'monthyears', 'cType'));
		
		if ($this->request->is('post')) {
			//pr($this->data);
			
			$cm_id = $this->data['course_mapping_id'];
			$result = $this->CourseMapping->find('first', array(
					'conditions'=>array('CourseMapping.id'=>$cm_id),
					'fields'=>array('CourseMapping.id', 'CourseMapping.month_year_id'),
					'contain'=>array(
							'Course'=>array(
									'fields' => array('Course.course_code', 'Course.min_cae_mark', 'Course.max_cae_mark',
											'Course.max_ese_mark')
							),
							'CaePt'=>array(
									'conditions'=>array('CaePt.indicator'=>0),
									'fields'=>array('CaePt.id'),
									'ProfessionalTraining' => array(
											'fields'=>array('ProfessionalTraining.student_id')
									)
							),
					)
			));
			//pr($result);
			
			$month_year_id = $result['CourseMapping']['month_year_id'];
			$cae_count = count($result['CaePt']);
			if(isset($result['CaePt']['ProfessionalTraining'])) {
				$pt_count = count($result['CaePt']['ProfessionalTraining']);
			} else {
				$pt_count = 0;
			}
				
			if (isset($result['CaePt']['id'])) {
				$cae_id = $result['CaePt']['id'];
			} else {
				$cae_id=0;
			}
			$max_cae_mark = $result['Course']['max_cae_mark'];
				
			$cae_status = $this->save_cae_pt($cm_id, $month_year_id, "CAE", $max_cae_mark, "CaePt", $cae_count, $pt_count, $cae_id);
				
			if ($cae_status) {
				$this->Flash->success(__('The CAE has been created.'));
				$this->redirect(array('controller' => 'CaePts', 'action'=>'index'));
			} else {
				$this->Flash->error(__('The Practical cannot be created. Please, try again.'));
			}
		}
	}

	public function save_cae_pt($cm_id, $month_year_id, $type, $marks, $model, $count, $user_count, $id) {
		$data = array();
		$data[$model]['month_year_id']=$month_year_id;
		$data[$model]['course_mapping_id']=$this->request->data['course_mapping_id'];
		$data[$model]['assessment_type']=$type;
		$data[$model]['marks']=$marks;
		if ($user_count > 0) {
			$data[$model]['marks_status']="Entered";
			$data[$model]['add_status']=1;
		}
		else {
			$data[$model]['marks_status']="Not Entered";
			$data[$model]['add_status']=0;
		}
	
		$data[$model]['approval_status']=0;
		$data[$model]['indicator']=0;
			
		if ($id>0) { 
			$data[$model]['id']=$id;
			$data[$model]['modified_by']= $this->Auth->user('id');
		}
		else { 
			$data[$model]['created_by']= $this->Auth->user('id');
			$this->$model->create();
		}
		//pr($data);
		if ($this->$model->save($data)) return true;
		else return false;
	}
/**
 * edit method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function edit($id = null) {
		if (!$this->CaePt->exists($id)) {
			throw new NotFoundException(__('Invalid cae pt'));
		}
		if ($this->request->is(array('post', 'put'))) {
			if ($this->CaePt->save($this->request->data)) {
				$this->Flash->success(__('The cae pt has been saved.'));
				return $this->redirect(array('action' => 'index'));
			} else {
				$this->Flash->error(__('The cae pt could not be saved. Please, try again.'));
			}
		} else {
			$options = array('conditions' => array('CaePt.' . $this->CaePt->primaryKey => $id));
			$this->request->data = $this->CaePt->find('first', $options);
		}
		$courseMappings = $this->CaePt->CourseMapping->find('list');
		$lecturers = $this->CaePt->Lecturer->find('list');
		$monthYears = $this->CaePt->MonthYear->find('list');
		$this->set(compact('courseMappings', 'lecturers', 'monthYears'));
	}

/**
 * delete method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function delete($id = null) {
		$this->CaePt->id = $id;
		if (!$this->CaePt->exists()) {
			throw new NotFoundException(__('Invalid cae pt'));
		}
		$this->request->allowMethod('post', 'delete');
		if ($this->CaePt->delete()) {
			$this->Flash->success(__('The cae pt has been deleted.'));
		} else {
			$this->Flash->error(__('The cae pt could not be deleted. Please, try again.'));
		}
		return $this->redirect(array('action' => 'index'));
	}
}