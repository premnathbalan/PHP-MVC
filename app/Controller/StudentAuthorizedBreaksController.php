<?php
App::uses('AppController', 'Controller');
/**
 * StudentAuthorizedBreaks Controller
 *
 * @property StudentAuthorizedBreak $StudentAuthorizedBreak
 * @property PaginatorComponent $Paginator
 * @property FlashComponent $Flash
 * @property SessionComponent $Session
 */
class StudentAuthorizedBreaksController extends AppController {
	public $uses = array("StudentAuthorizedBreak", "Student", "CourseMapping", "StudentMark", "MonthYear", "CourseStudentMapping");
/**
 * Components
 *
 * @var array
 */
	public $components = array('Paginator', 'Flash', 'Session');

/**
 * index method
 *
 * @return void
 */
	public function index() {
		$this->StudentAuthorizedBreak->recursive = 0;
		$this->set('studentAuthorizedBreaks');
	}

/**
 * view method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function view($id = null) {
		if (!$this->StudentAuthorizedBreak->exists($id)) {
			throw new NotFoundException(__('Invalid student authorized break'));
		}
		$options = array('conditions' => array('StudentAuthorizedBreak.' . $this->StudentAuthorizedBreak->primaryKey => $id));
		$this->set('studentAuthorizedBreak', $this->StudentAuthorizedBreak->find('first', $options));
	}

/**
 * add method
 *
 * @return void
 */
	public function add() {
		if ($this->request->is('post')) {
			$this->StudentAuthorizedBreak->create();
			if ($this->StudentAuthorizedBreak->save($this->request->data)) {
				$this->Flash->success(__('The student authorized break has been saved.'));
				return $this->redirect(array('action' => 'index'));
			} else {
				$this->Flash->error(__('The student authorized break could not be saved. Please, try again.'));
			}
		}
		$students = $this->StudentAuthorizedBreak->Student->find('list');
		$semesters = $this->StudentAuthorizedBreak->Semester->find('list');
		$this->set(compact('students', 'semesters'));
	}

/**
 * edit method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function edit($id = null) {
		if (!$this->StudentAuthorizedBreak->exists($id)) {
			throw new NotFoundException(__('Invalid student authorized break'));
		}
		if ($this->request->is(array('post', 'put'))) {
			if ($this->StudentAuthorizedBreak->save($this->request->data)) {
				$this->Flash->success(__('The student authorized break has been saved.'));
				return $this->redirect(array('action' => 'index'));
			} else {
				$this->Flash->error(__('The student authorized break could not be saved. Please, try again.'));
			}
		} else {
			$options = array('conditions' => array('StudentAuthorizedBreak.' . $this->StudentAuthorizedBreak->primaryKey => $id));
			$this->request->data = $this->StudentAuthorizedBreak->find('first', $options);
		}
		$students = $this->StudentAuthorizedBreak->Student->find('list');
		$semesters = $this->StudentAuthorizedBreak->Semester->find('list');
		$this->set(compact('students', 'semesters'));
	}

/**
 * delete method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function delete($id = null) {
		$this->StudentAuthorizedBreak->id = $id;
		if (!$this->StudentAuthorizedBreak->exists()) {
			throw new NotFoundException(__('Invalid student authorized break'));
		}
		$this->request->allowMethod('post', 'delete');
		if ($this->StudentAuthorizedBreak->delete()) {
			$this->Flash->success(__('The student authorized break has been deleted.'));
		} else {
			$this->Flash->error(__('The student authorized break could not be deleted. Please, try again.'));
		}
		return $this->redirect(array('action' => 'index'));
	}
	
	public function abs() {
		if ($this->request->is('post')) {
			//pr($this->data);
			$student_id = $this->request->data['ABS']['student_id'];
			$reg_number = $this->request->data['ABS']['registration_number'];
			$semesters = $this->request->data['ABS']['Semester'];
			$month_year_array = $this->request->data['ABS']['month_year_id'];
			foreach ($semesters as $sem => $semester_id) {
				if ($semester_id > 0) {
					$new_month_year_id = $month_year_array[$sem];
					
					$stuResults = $this->Student->find('first', array(
							'conditions'=>array('Student.discontinued_status'=>0, 'Student.id'=>$student_id),
							'fields'=>array('Student.batch_id', 'Student.program_id'),
							'recursive'=>0
					));
					$batch_id = $stuResults['Student']['batch_id'];
					$program_id = $stuResults['Student']['program_id'];
					//pr($stuResults);
					$cm_id_array = array();
					$cm_id_results = $this->CourseMapping->find('all', array(
							'conditions'=>array('CourseMapping.indicator'=>0, 'CourseMapping.semester_id'=>$semester_id,
									'CourseMapping.batch_id'=>$batch_id, 'CourseMapping.program_id'=>$program_id
							),
							'fields'=>array('CourseMapping.id', 'CourseMapping.month_year_id'),
							'recursive'=>0
					));
					//pr($cm_id_results);
					foreach ($cm_id_results as $k => $cResult) {
						$cm_id_array[$cResult['CourseMapping']['id']]=$cResult['CourseMapping']['month_year_id'];
					}
					
					foreach ($cm_id_array as $cm_id => $cm_month_year_id) {
						if ($cm_id) {
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
							$data['CourseStudentMapping']['type']="ABS";
							$data['CourseStudentMapping']['new_semester_id']=$new_month_year_id;
							$data['CourseStudentMapping']['modified_by']=$this->Auth->user('id');
							$data['CourseStudentMapping']['modified'] = date("Y-m-d H:i:s");
							$this->CourseStudentMapping->save($data);
								
							$result = $this->StudentAuthorizedBreak->find('first', array(
									'conditions'=>array('StudentAuthorizedBreak.month_year_id'=>$cm_month_year_id,
											'StudentAuthorizedBreak.student_id'=>$student_id,
									),
									'fields'=>array('StudentAuthorizedBreak.id'),
									'contain'=>false
							));
								
							$data=array();
							if (isset($result) && count($result)>0) {
								$id = $result['StudentAuthorizedBreak']['id'];
								$data['StudentAuthorizedBreak']['id']=$id;
								$data['StudentAuthorizedBreak']['modified_by']=$this->Auth->user('id');
								$data['StudentAuthorizedBreak']['modified'] = date("Y-m-d H:i:s");
							}
							else {
								$data['StudentAuthorizedBreak']['created_by']=$this->Auth->user('id');
								$this->StudentAuthorizedBreak->create();
							}
							$data['StudentAuthorizedBreak']['semester_id']=$semester_id;
							$data['StudentAuthorizedBreak']['student_id']=$student_id;
							$data['StudentAuthorizedBreak']['month_year_id']=$cm_month_year_id;
							$data['StudentAuthorizedBreak']['new_month_year_id']=$new_month_year_id;
							if ($this->StudentAuthorizedBreak->save($data)) $bool=true;
								
						}
					}
				}
			}
		}
	}
	
	public function absSearch($reg_no) {
		$student_id = $this->Student->getStudentId($reg_no);
		$this->layout = false;
		$results = $this->getStudentBatchAndProgramFromStudentId($student_id);
		//pr($results);
		if (is_array($results) && isset($results['Student']['id'])) {
			$batch_id = $results['Batch']['id'];
			$program_id = $results['Program']['id'];
			$total_semesters = $results['Program']['semester_id'];
			
			$array = $this->maxMonthYearAndSemesterId($batch_id, $program_id);
			$month_year_id = $array['month_year_id'];

			$month_years = $this->retrieveMonthYearBeyondAMonthYear($month_year_id);
		}
		
		$this->set(compact('results', 'student_id', 'month_years'));
	}
	
	public function getStudentBatchAndProgramFromStudentId($student_id) {
		$results = $this->Student->find('first', array(
				'conditions' => array('Student.id' => $student_id, 'Student.discontinued_status'=>0
				),
				'fields' => array('Student.id'),
				'contain' => array(
						'Batch' => array(
								'fields' => array('Batch.id'),
						),
						'Program' => array('fields' => array('Program.id', 'Program.semester_id'),
								'Semester'=>array('fields' => array('Semester.id', 'Semester.semester_name'))
						),
						'StudentAuthorizedBreak'=>array('fields' => array('StudentAuthorizedBreak.id', 
								'StudentAuthorizedBreak.month_year_id', 'StudentAuthorizedBreak.new_month_year_id',
								'StudentAuthorizedBreak.semester_id')
						)
							
				))
				);
		return $results;
	}
	
	public function chkPublishedData($student_id, $semester_id) {
		$this->layout=false;
		$results = $this->getStudentBatchAndProgramFromStudentId($student_id);
		if (is_array($results) && isset($results['Student']['id'])) {
			$batch_id = $results['Batch']['id'];
			$program_id = $results['Program']['id'];
			$myResults = $this->getMonthYearFromSemesterId($batch_id, $program_id, $semester_id);
		//	pr($myResults); die;
			if (isset($myResults['CourseMapping']['month_year_id'])) {
				$month_year_id = $myResults['CourseMapping']['month_year_id'];
			}
			else {
				$month_year_id = 0;
			}
	
			$courseMappingNotDone = 0;
			if ($month_year_id) {
				$smResults = $this->StudentMark->find('all', array(
						'conditions' => array('StudentMark.student_id' => $student_id,
								'StudentMark.month_year_id'=>$month_year_id
						),
						'fields' => array('StudentMark.id', 'StudentMark.month_year_id'),
						'contain' => false
				));
				//pr($smResults);
				$cnt = count($smResults);
				if ($cnt == 0) $published_status = 0;
			}
			else {
				//echo "else";
				$courseMappingNotDone = 1;
				$cnt=0;
			}
			//echo $cnt." ".$courseMappingNotDone;
			if ($month_year_id > 0) {
				if ($cnt > 0 && isset($month_year_id)) {
					//echo "<span style='color:red;'>Data already published for this Semester. Hence ABS cannot be availed of.</span>";
					echo "1";
				}
				else if ($cnt == 0 && $courseMappingNotDone == 1 || $published_status==0) {
					echo "1";
				}
			}
			else {
				echo "<span style='color:red;'>Yet to add Month Year!!!</span>";
			}
		}
		else {
			echo "Invalid registration number. Please try again.";
		}
	
		$this->autoRender = false;
	}
	
}
