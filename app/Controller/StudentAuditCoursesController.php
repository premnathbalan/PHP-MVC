<?php
App::uses('AppController', 'Controller');
/**
 * StudentAuditCourses Controller
 *
 * @property StudentAuditCourse $StudentAuditCourse
 * @property PaginatorComponent $Paginator
 * @property FlashComponent $Flash
 * @property SessionComponent $Session
 */
class StudentAuditCoursesController extends AppController {

/**
 * Components
 *
 * @var array
 */
	public $components = array('Flash', 'Session');
	public $uses = array('StudentAuditCourse', 'Student', 'CourseMapping', 'MonthYear');
/**
 * index method
 *
 * @return void
 */
	public function index() {
		/* $this->StudentAuditCourse->recursive = 0;
		$this->set('studentAuditCourses', $this->Paginator->paginate()); */
		if ($this->request->is('post')) {
			$regNo = $this->request->data['rgNo']['registration_number'];
			$result = $this->checkIfStudentDetails($regNo);
			if($result['Student']['id']){
				$this->redirect(array('controller' => 'StudentAuditCourses','action' => 'view',$regNo));
				//pr($result);
			}else{
				$this->Flash->error(__('Invalid Register Number. Please, try again.'));
				$this->redirect(array('controller' => 'Students','action' => 'regNoSearch'));
			}
		}
	}
	
	public function checkIfStudentDetails($regNo) {
		$result = $this->Student->find('first', array(
				'conditions' => array('Student.registration_number' =>  $regNo),
				'fields' => array('Student.id'),
				'contain'=>false 
				
		));
		return $result;
	}
	/*  */
/**
 * view method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function view($regNo = null) {
		$studentId = $this->Student->getStudentId($regNo);
		//echo $studentId;
		$results = $this->Student->find('first', array(
				'conditions' => array('Student.id' =>  $studentId),
				'fields' => array('Student.id', 'Student.name'),
				'contain'=>array(
					'Batch'=>array(
							'fields' => array('Batch.batch_from', 'Batch.batch_to', 'Batch.academic'),
					),
					'Program'=>array(
							'fields' => array('Program.program_name'),
					),
					'Academic'=>array(
							'fields' => array('Academic.academic_name'),
					),
					'StudentAuditCourse'=>array(
						'fields'=>array('StudentAuditCourse.id', 'StudentAuditCourse.month_year_id',
								'StudentAuditCourse.audit_course_id', 'StudentAuditCourse.marks'
						),
						'conditions'=>array('StudentAuditCourse.indicator'=>0),
						'AuditCourse'=>array(
								'fields'=>array('AuditCourse.id', 'AuditCourse.course_name', 'AuditCourse.course_code',
										'AuditCourse.common_code')
						)
					)
			),
		
		));
		//pr($results);
		
		$batch_id = $results['Batch']['id'];
		$academic_id = $results['Academic']['id'];
		$program_id = $results['Program']['id'];
		$my_details = $this->maxMonthYearAndSemesterId($batch_id, $program_id);
		//pr($my_details);
		$start_my_id = $my_details['start_month_year_id'];
		$end_my_id = $my_details['month_year_id'];
		
		$month_year = $this->monthYearBetweenIds($start_my_id, $end_my_id);
		//pr($month_year);
		//$monthYear = $month_year[0]['Month']['month_name']." - ".$month_year[0]['MonthYear']['year'];
		
		$this->set(compact('my_details',/*  'batch_id', 'program_id',  */'results', 'studentId', 'regNo', 'month_year'));
		
		/* if (!$this->StudentAuditCourse->exists($id)) {
			throw new NotFoundException(__('Invalid student audit course'));
		}
		$options = array('conditions' => array('StudentAuditCourse.' . $this->StudentAuditCourse->primaryKey => $id));
		$this->set('studentAuditCourse', $this->StudentAuditCourse->find('first', $options)); */
		if($this->request->is('post')) {
			//pr($this->data);
			
			$audit_course_id_array = $this->request->data['StudentAuditCourse']['audit_course_id'];
			$marks_array = $this->request->data['StudentAuditCourse']['marks'];
			$month_year_id = $this->request->data['StudentAuditCourse']['month_year_id'];
			
			foreach ($audit_course_id_array as $key => $audit_course_id) { 
				$data_exists=$this->StudentAuditCourse->find('first', array(
						'conditions' => array(
								'StudentAuditCourse.student_id'=>$studentId,
								'StudentAuditCourse.audit_course_id'=>$audit_course_id,
								'StudentAuditCourse.month_year_id'=>$month_year_id,
						),
						'fields' => array('StudentAuditCourse.id'),
						'recursive' => 0
				));
				$data = array();
				$data['StudentAuditCourse']['student_id'] = $studentId;
				$data['StudentAuditCourse']['audit_course_id'] = $audit_course_id;
				$data['StudentAuditCourse']['month_year_id'] = $month_year_id;
				$data['StudentAuditCourse']['marks'] = $marks_array[$key];
				$data['StudentAuditCourse']['indicator'] = 0;
				
				if(isset($data_exists['StudentAuditCourse']['id']) && $data_exists['StudentAuditCourse']['id']>0) {
					$id = $data_exists['StudentAuditCourse']['id'];
					$data['StudentAuditCourse']['id'] = $id;
					$data['StudentAuditCourse']['modified_by'] = $this->Auth->user('id');
					$data['StudentAuditCourse']['modified'] = date("Y-m-d H:i:s");
				}
				else {
					$this->StudentAuditCourse->create($data);
					$data['StudentAuditCourse']['created_by'] = $this->Auth->user('id');
				}
				$this->StudentAuditCourse->save($data);
			}
			//echo $studentId;
		}
	}

/**
 * add method
 *
 * @return void
 */
	public function add() {
		if ($this->request->is('post')) {
			$this->StudentAuditCourse->create();
			if ($this->StudentAuditCourse->save($this->request->data)) {
				$this->Flash->success(__('The student audit course has been saved.'));
				return $this->redirect(array('action' => 'index'));
			} else {
				$this->Flash->error(__('The student audit course could not be saved. Please, try again.'));
			}
		}
		$students = $this->StudentAuditCourse->Student->find('list');
		$auditCourses = $this->StudentAuditCourse->AuditCourse->find('list');
		$this->set(compact('students', 'auditCourses'));
	}

/**
 * edit method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function edit($id = null, $regNo) {
		if (!$this->StudentAuditCourse->exists($id)) {
			throw new NotFoundException(__('Invalid student audit course'));
		}
		if ($this->request->is(array('post', 'put'))) {
			if ($this->StudentAuditCourse->save($this->request->data)) {
				$this->Flash->success(__('The student audit course has been saved.'));
				return $this->redirect(array('action' => 'view', $regNo));
			} else {
				$this->Flash->error(__('The student audit course could not be saved. Please, try again.'));
			}
		} else {
			$options = array('conditions' => array('StudentAuditCourse.' . $this->StudentAuditCourse->primaryKey => $id));
			$this->request->data = $this->StudentAuditCourse->find('first', $options);
		}
		$students = $this->StudentAuditCourse->Student->find('list');
		$auditCourses = $this->StudentAuditCourse->AuditCourse->find('list');
		$this->set(compact('students', 'auditCourses'));
	}

/**
 * delete method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function delete($id = null, $student_id) {
		/* $this->StudentAuditCourse->id = $id;
		if (!$this->StudentAuditCourse->exists()) {
			throw new NotFoundException(__('Invalid student audit course'));
		}
		$this->request->allowMethod('post', 'delete');
		if ($this->StudentAuditCourse->delete()) {
			$this->Flash->success(__('The student audit course has been deleted.'));
		} else {
			$this->Flash->error(__('The student audit course could not be deleted. Please, try again.'));
		} */
		$this->StudentAuditCourse->updateAll(
				array(
						"StudentAuditCourse.indicator" =>1
				),
				array(
						"StudentAuditCourse.id" => $id
				)
				);
		return $this->redirect(array('action' => 'index'));
	}
	
	public function getAuditCourseDetails($month_year_id, $studentId, $isAjax) {
		$results = $this->Student->find('first', array(
				'conditions' => array('Student.id' =>  $studentId),
				'fields' => array('Student.id', 'Student.name'),
				'contain'=>array(
					'StudentAuditCourse'=>array(
						'fields'=>array('StudentAuditCourse.id', 'StudentAuditCourse.month_year_id',
								'StudentAuditCourse.audit_course_id', 'StudentAuditCourse.marks'
						),
						'conditions'=>array('StudentAuditCourse.indicator'=>0, 'StudentAuditCourse.month_year_id'=>$month_year_id),
							'AuditCourse'=>array(
								'fields'=>array('AuditCourse.id', 'AuditCourse.course_name', 'AuditCourse.course_code', 
								'AuditCourse.common_code')
							)
					)
				),
		
		));
		
		$courses = array();
		$audit_courses = $this->StudentAuditCourse->AuditCourse->find('all', array(
				'conditions'=>array('AuditCourse.indicator'=>0),
				'fields'=>array('AuditCourse.id', 'AuditCourse.course_name', 'AuditCourse.course_code',
						'AuditCourse.common_code')
		));
		foreach ($audit_courses as $key => $value) {
			$courses[$value['AuditCourse']['id']] = $value['AuditCourse']['course_code']." - ".$value['AuditCourse']['course_name'];
		}
		
		//pr($results);
		//if ($isAjax) { echo "if";
		$this->set(compact('results', 'month_year_id', 'studentId', 'courses'));
		/* }
		else {
			return $results;
		} */
		
		$sac = array();
		$sacs = $this->StudentAuditCourse->query("select DISTINCT audit_course_id
				from student_audit_courses
				 where student_audit_courses.student_id=".$studentId." and student_audit_courses.indicator=0");
		foreach ($sacs as $key => $val) {
			$sac[$val['student_audit_courses']['audit_course_id']] = $val['student_audit_courses']['audit_course_id'];
		}
		$this->set(compact('sac'));
		
		$this->layout = false;
	}
	
	public function get_audit_courses($month_year_id, $studentId) {
		$courses = array();
		$audit_courses = $this->StudentAuditCourse->AuditCourse->find('all', array(
				'conditions'=>array('AuditCourse.indicator'=>0),
				'fields'=>array('AuditCourse.id', 'AuditCourse.course_name', 'AuditCourse.course_code', 
				'AuditCourse.common_code')
		));
		foreach ($audit_courses as $key => $value) {
			$courses[$value['AuditCourse']['id']] = $value['AuditCourse']['course_code']." - ".$value['AuditCourse']['course_name'];
		}
		
		//$sac = $this->getAuditCourseDetails($month_year_id, $studentId, false);
		
		$sacs = $this->Student->find('first', array(
				'conditions' => array('Student.id' =>  $studentId),
				'fields' => array('Student.id', 'Student.name'),
				'contain'=>array(
						'StudentAuditCourse'=>array(
								'fields'=>array(
										'StudentAuditCourse.audit_course_id'
								),
								'conditions'=>array('StudentAuditCourse.indicator'=>0, 'StudentAuditCourse.month_year_id'=>$month_year_id),
						)
				),
		
		));
		$sac = array();
		$sacs = $this->StudentAuditCourse->query("select DISTINCT audit_course_id 
				from student_audit_courses
				 where student_audit_courses.student_id=".$studentId);
		foreach ($sacs as $key => $val) {
			$sac[$val['student_audit_courses']['audit_course_id']] = $val['student_audit_courses']['audit_course_id'];
		}
		$this->set(compact('courses', 'sac'));
		$this->layout=false;
	}
	
	public function deactivateAuditCourse($id) {
		$data = array();
		$data['StudentAuditCourse']['id'] = $id;
		$data['StudentAuditCourse']['indicator'] = 1;
		$this->StudentAuditCourse->save($data);
	}
}
