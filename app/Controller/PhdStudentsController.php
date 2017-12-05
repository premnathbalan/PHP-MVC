<?php
App::uses('AppController', 'Controller');
/**
 * PhdStudents Controller
 *
 * @property PhdStudent $PhdStudent
 * @property PaginatorComponent $Paginator
 */
class PhdStudentsController extends AppController {

/**
 * Components
 *
 * @var array
 */
	public $uses = array("PhdStudent", "Supervisor", "MonthYear", "Title", "Area", "PhdCourse", "PhdCourseStudentMapping", 
	"PhdStudentMark", "Signature");
/**
 * index method
 *
 * @return void
 */
	public function index() {
		$results = $this->PhdStudent->find('all', array(
				'conditions'=>array('PhdStudent.discontinued_status'=>0)
		));
		
		foreach ($results as $key => $value) {
			//pr($value);
			//$status = "";
			$regNo = $value['PhdStudent']['registration_number'];
			$studentDetails = $this->studentDetails($regNo);
			//pr($studentDetails);
			//$status = $this->getStatus($studentDetails);
			$results[$key]['details'] = $studentDetails;
			if (true) $results[$key]['details']['status'] = "Completed";
			else $results[$key]['details']['status'] = "Not Completed";
		}
		$this->set('phdStudents', $results);
	}

/**
 * view method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function view($regNo = null) {
		$results = $this->PhdStudent->getStudentId($regNo);
		//pr($results);
		$id = $results[0]['PhdStudent']['id'];
		
		if (!$this->PhdStudent->exists($id)) {
			throw new NotFoundException(__('Invalid phd student'));
		}
		$options = array('conditions' => array('PhdStudent.' . $this->PhdStudent->primaryKey => $id));
		$this->set('phdStudent', $this->PhdStudent->find('first', $options));
		$this->set(compact('regNo'));
		$this->set(compact('id'));
	}

/**
 * add method
 *
 * @return void
 */
	public function add() {
		if ($this->request->is('post')) {
			pr($this->data); //die; 
			$title = $this->request->data['PhdStudent']['title'];
			$titleArray = $this->Title->find('first', array('conditions'=>array('Title.name'=>$title),'recursive'=>0));
			//pr($titleArray);
			$titleId = $titleArray['Title']['id'];
			
			$area = $this->request->data['PhdStudent']['area'];
			$areaArray = $this->Area->find('first', array('conditions'=>array('Area.name'=>$area),'recursive'=>0));
			//pr($areaArray);
			$areaId = $areaArray['Area']['id'];
			
			$this->request->data['PhdStudent']['title_id'] = $titleId;
			$this->request->data['PhdStudent']['area_id'] = $areaId;
			
			$this->request->data['PhdStudent']['birth_date'] 		= date("Y-m-d", strtotime($this->request->data['PhdStudent']['birth_date']));
			$this->request->data['PhdStudent']['date_of_register'] 	= date("Y-m-d", strtotime($this->request->data['PhdStudent']['date_of_register']));
			
			if(!empty($this->request->data['PhdStudent']['picture']['name'])) {
				$file = $this->request->data['PhdStudent']['picture']; //put the data into a var for easy use
				$ext = substr(strtolower(strrchr($file['name'], '.')), 1); //get the extension
				$arr_ext = array('jpg', 'jpeg', 'gif', 'png', 'bmp'); //set allowed extensions
				//only process if the extension is valid
				if(in_array($ext, $arr_ext)) {
					move_uploaded_file($file['tmp_name'], WWW_ROOT . 'img/students/' .$this->request->data['PhdStudent']['registration_number'].".".$ext);
					$this->request->data['PhdStudent']['picture'] = $this->request->data['PhdStudent']['registration_number'].".".$ext;
				}
			}else {
				unset($this->request->data['PhdStudent']['picture']);
			}
			$this->PhdStudent->create();
			if ($this->PhdStudent->save($this->request->data)) {
				$this->Flash->success(__('The phd student has been saved.'));
				return $this->redirect(array('action' => 'index'));
			} else {
				$this->Flash->error(__('The phd student could not be saved. Please, try again.'));
			}
		}
//		$faculties = $this->PhdStudent->Faculty->find('list');
//		$thesis = $this->PhdStudent->Thesi->find('list');
//		$disciplines = $this->PhdStudent->Discipline->find('list');
		//$supervisors = $this->PhdStudent->Supervisor->find('list');
		//$months = $this->PhdStudent->Month->find('list');
		//$monthYears = $this->PhdStudent->MonthYear->find('list');
		
		$monthyears = $this->MonthYear->getAllMonthYears();
		$this->set('supervisors',$this->Supervisor->find('list', array('fields' => array('Supervisor.name'),'order'=>array('Supervisor.name ASC'))));
		$this->set(compact('faculties', 'thesis', 'disciplines', 'supervisors', 'months', 'monthyears'));
	}

/**
 * edit method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function edit($id = null) {
		if (!$this->PhdStudent->exists($id)) {
			throw new NotFoundException(__('Invalid phd student'));
		}
		if ($this->request->is(array('post', 'put'))) {
			if ($this->PhdStudent->save($this->request->data)) {
				$this->Flash->success(__('The phd student has been saved.'));
				return $this->redirect(array('action' => 'index'));
			} else {
				$this->Flash->error(__('The phd student could not be saved. Please, try again.'));
			}
		} else {
			$options = array('conditions' => array('PhdStudent.' . $this->PhdStudent->primaryKey => $id));
			$this->request->data = $this->PhdStudent->find('first', $options);
		}
		//$faculties = $this->PhdStudent->Faculty->find('list');
		//$thesis = $this->PhdStudent->Thesi->find('list');
		//$disciplines = $this->PhdStudent->Discipline->find('list');
		$supervisors = $this->PhdStudent->Supervisor->find('list');
		//$months = $this->PhdStudent->Month->find('list');
		$monthYears = $this->PhdStudent->MonthYear->find('list');
		$this->set(compact('faculties', 'thesis', 'disciplines', 'supervisors', 'monthYears'));
	}

/**
 * delete method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function delete($id = null) {
		$this->PhdStudent->id = $id;
		if (!$this->PhdStudent->exists()) {
			throw new NotFoundException(__('Invalid phd student'));
		}
		/* $this->request->allowMethod('post', 'delete');
		if ($this->PhdStudent->delete()) {
			$this->Flash->success(__('The phd student has been deleted.'));
		} else {
			$this->Flash->error(__('The phd student could not be deleted. Please, try again.'));
		} */
		$this->PhdStudent->updateAll(
				/* UPDATE FIELD */
				array(
						"PhdStudent.discontinued_status" => 1,
				),
				/* CONDITIONS */
				array(
						"PhdStudent.id" => $id
				)
				);
		return $this->redirect(array('controller'=>'PhdStudents', 'action' => 'index'));
	}
	
	public function findTitle() {
		$keyword = $_GET['term'];
		//echo $keyword; die; 
		if(!empty($keyword)) {
			$titles = $this->Title->find('list', array(
					'conditions'=>array('Title.name LIKE'=>"$keyword%"),
					'fields'=>array('Title.name'),
					'recursive'=>0
			));
			//pr($titles);
			
			$data = array();
			foreach ($titles as $key => $val) {
				array_push($data, $val);
			}
			echo json_encode($data);
			$this->layout=false;
		}
	}
	
	public function findArea() {
		$keyword = $_GET['term'];
		//echo $keyword; die;
		if(!empty($keyword)) {
			$titles = $this->Area->find('list', array(
					'conditions'=>array('Area.name LIKE'=>"$keyword%"),
					'fields'=>array('Area.name'),
					'recursive'=>0
			));
			//pr($titles);
				
			$data = array();
			foreach ($titles as $key => $val) {
				array_push($data, $val);
			}
			echo json_encode($data);
			$this->layout=false;
		}
	}
	
	public function findCourse() {
		$allCourses = $this->PhdCourse->find('list',array(
				'fields' => array('PhdCourse.id','PhdCourse.course_name')
		));
		$this->set(compact('allCourses'));	
	
	}
	public function mapCourses($regNo) {
		//echo $regNo;
		$results = $this->PhdStudent->getStudentId($regNo);
		//pr($results);
		$studentId = $results[0]['PhdStudent']['id'];
		
		$monthyears = $this->MonthYear->getAllMonthYears();
		$this->set('monthyears', $monthyears);
		$this->set('results', $results);
		$this->set('regNo', $regNo);
		
		if (!$studentId) {
			throw new NotFoundException(__('Invalid phd student'));
		} else {
			$allCourses = $this->PhdCourse->find('list',array(
					'fields' => array('PhdCourse.id','PhdCourse.course_name')
			));
			$this->set(compact('allCourses'));
			$this->set('regNo', $regNo);
			$this->set('studentId', $studentId);
			
			$mappedCourses = $this->PhdCourseStudentMapping->find('all', array(
					'conditions'=>array('PhdCourseStudentMapping.phd_student_id'=>$studentId,
							'PhdCourseStudentMapping.indicator'=>0
					),
					'recursive'=>1
			));
			//pr($mappedCourses);
			$this->set(compact('mappedCourses'));
			if ($this->request->is('post')) {
				pr($this->data); 
				if ($this->request->data['Save']=="Course") {
					 
					$courses = $this->request->data['Course'];
					$monthYearArray = $this->request->data['MonthYear'];
					
					foreach ($courses as $key=>$course_id) {
						$csmExists = $this->PhdCourseStudentMapping->find('first', array(
								'conditions' => array('PhdCourseStudentMapping.phd_course_id'=>$course_id,
										'PhdCourseStudentMapping.phd_student_id'=>$this->request->data['phd_student_id'],
								),
								'fields'=>array('PhdCourseStudentMapping.id')
						));
						$data=array();
						$data['PhdCourseStudentMapping']['phd_course_id']=$course_id;
						$data['PhdCourseStudentMapping']['phd_student_id']=$this->request->data['phd_student_id'];
						$data['PhdCourseStudentMapping']['month_year_id']=$monthYearArray[$key];
						//$data['PhdCourseStudentMapping']['marks']=$marksArray[$key];
						//$data['PhdCourseStudentMapping']['status'] = $this->getCourseResult($course_id, $marksArray[$key]);
						if (isset($csmExists) && count($csmExists)>0) {
							$id = $csmExists['PhdCourseStudentMapping']['id'];
							$data['PhdCourseStudentMapping']['id']=$id;
							$data['PhdCourseStudentMapping']['modified_by']=$this->Auth->user('id');
							$data['PhdCourseStudentMapping']['modified']=date("Y-m-d H:i:s");
						}
						else {
							$data['PhdCourseStudentMapping']['created_by']=$this->Auth->user('id');
							$this->PhdCourseStudentMapping->create();
						}
						//pr($data);
						$this->PhdCourseStudentMapping->save($data);
					}
					return $this->redirect(array('action' => 'index'));
				}
				if ($this->request->data['Save']=="Viva") {
					$id = $this->request->data['PhdStudent']['id'];
					
					if (!$this->PhdStudent->exists($id)) {
						throw new NotFoundException(__('Invalid phd student'));
					}
					if ($this->request->is(array('post', 'put'))) {
						$this->request->data['PhdStudent']['comprehensive_viva_date'] = date("Y-m-d", strtotime($this->request->data['PhdStudent']['comprehensive_viva_date']));
						$this->request->data['PhdStudent']['synopsis_date'] = date("Y-m-d", strtotime($this->request->data['PhdStudent']['synopsis_date']));
						
						if ($this->PhdStudent->save($this->request->data)) {
							$this->Flash->success(__('The phd student has been saved.'));
							return $this->redirect(array('action' => 'mapCourses',$regNo));
						} else {
							$this->Flash->error(__('The phd student could not be saved. Please, try again.'));
						}
					}
				}
			}
		} 
	}
	
	public function searchIndex() {
		if ($this->request->is('post')) {  
			$regNo = $this->request->data['rgNo']['registration_number'];
			$result = $this->PhdStudent->find('first', array('conditions' => array('PhdStudent.registration_number' =>  $regNo),'fields' =>'PhdStudent.id'));
				
			if($result['PhdStudent']['id']){
				$this->redirect(array('controller' => 'PhdStudents','action' => 'view',$regNo));
				//$this->redirect(array('controller' => 'PhdStudents','action' => 'mapCourses',$regNo));
			}else{
				$this->Flash->error(__('Invalid Register Number. Please, try again.'));
				$this->redirect(array('controller' => 'PhdStudent','action' => 'searchIndex'));
			}
		}
	}
	
	public function editMarks($studentId=null, $course_id=null, $new_marks=null, $old_marks=null, $month_year_id=null, $csmId=null, $smId=null) {
		$this->layout = 'ajax';
		//echo $studentId." ".$course_id." ".$new_marks." ".$old_marks." ".$month_year_id." ".$csmId." ".$smId;
		
		$arr = array();
		
		$arr['result'] = $this->getCourseResult($course_id, $new_marks);
		
		$data['PhdStudentMark']['phd_course_student_mapping_id']=$csmId;
		$data['PhdStudentMark']['month_year_id']=$month_year_id;
		$data['PhdStudentMark']['marks']=$new_marks;
		$data['PhdStudentMark']['status']=$arr['result'];
		if(is_null($smId) || ($smId=="-")) {
			//echo "if";
			if ($smId == "-") {
				$smExists = $this->PhdStudentMark->find('first', array(
						'conditions' => array('PhdStudentMark.phd_course_student_mapping_id'=>$csmId,
								'PhdStudentMark.month_year_id'=>$month_year_id,
						),
						'fields'=>array('PhdStudentMark.id')
				));
				//pr($smExists);
				if (isset($smExists) && count($smExists)>0) {
					$data['PhdStudentMark']['id']=$smExists['PhdStudentMark']['id'];
					$data['PhdStudentMark']['modified_by']=$this->Auth->user('id');
					$data['PhdStudentMark']['modified']=date("Y-m-d H:i:s");
				}
				else {
					$data['PhdStudentMark']['created_by']=$this->Auth->user('id');
					$this->PhdStudentMark->create();
				}
			} else {
				$data['PhdStudentMark']['created_by']=$this->Auth->user('id');
				$this->PhdStudentMark->create();
			}
		}
		else {
			$data['PhdStudentMark']['id']=$smId;
			$data['PhdStudentMark']['modified_by']=$this->Auth->user('id');
			$data['PhdStudentMark']['modified']=date("Y-m-d H:i:s");
		}
		//pr($data);
		
		$this->PhdStudentMark->save($data);
		$this->set(compact('arr'));
		//}
	}
	
	public function getCourseResult($course_id, $marks) {
		$courseDetails = $this->PhdCourse->retrievePhdCourseDetails($course_id);
		$course_pass_percent = $courseDetails[0]['PhdCourse']['total_min_pass_percent'];
		$course_max_marks = $courseDetails[0]['PhdCourse']['course_max_marks'];
		$course_pass_mark = $course_max_marks * $course_pass_percent / 100;
		if ($marks >= $course_pass_mark) $result = "Pass";
		else $result = "Fail";
		return $result;
	}
	
	public function studentDetails($regNo) {
		$results = $this->PhdStudent->getStudentId($regNo);
		//pr($results);
		$array = array();
		$course_count = 0; $pass_count = 0;
		
		$courses = $results[0]['PhdCourseStudentMapping'];
		$course_count = count($results[0]['PhdCourseStudentMapping']);
		$i=0;
		
		foreach ($courses as $key => $value) { 
			if (isset($value['PhdStudentMark'][0]) && count($value['PhdStudentMark'] > 0)) {
				$array[$regNo]['courses'][$i]['course_name'] = $value['PhdCourse']['course_name'];
				$array[$regNo]['courses'][$i]['course_code'] = $value['PhdCourse']['course_code'];
				$array[$regNo]['courses'][$i]['marks'] = $value['PhdStudentMark'][0]['marks'];
				$array[$regNo]['courses'][$i]['status'] = $value['PhdStudentMark'][0]['status'];
				$array[$regNo]['courses'][$i]['month_year_id'] = $value['PhdStudentMark'][0]['month_year_id'];
				if($value['PhdStudentMark'][0]['status'] == "Pass") $pass_count++;
				$i++;
			}
			
		}
		$comprehensive_viva = $results[0]['PhdStudent']['comprehensive_viva'];
		$synopsis = $results[0]['PhdStudent']['synopsis'];
		$viva = $results[0]['PhdStudent']['viva'];
		$array[$regNo]['registration_number'] = $results[0]['PhdStudent']['name'];
		$array[$regNo]['picture'] = $results[0]['PhdStudent']['picture'];
		$array[$regNo]['name'] = $regNo;
		$array[$regNo]['title'] = $results[0]['Title']['name'];
		$array[$regNo]['area'] = $results[0]['Area']['name'];
		$array[$regNo]['course_count'] = $course_count;
		$array[$regNo]['pass_count'] = $pass_count;
		$array[$regNo]['comprehensive_viva'] = $comprehensive_viva;
		$array[$regNo]['synopsis'] = $synopsis;
		$array[$regNo]['viva'] = $viva;
		//pr($array);
		return $array;
	}
	
	public function getStatus($array, $regNo) {
		$bool = 0;
		if ($array[$regNo]['comprehensive_viva'] == 'Y' && $array[$regNo]['synopsis'] == 'Y' && $array[$regNo]['viva'] == 'Y' 
				&& ($array[$regNo]['pass_count'] == $array[$regNo]['course_count'])) {
			$bool = 1;
		} 
		return $bool;
	}
	
	public function pdc($regNo) {
		
	}
	
	public function marks ($regNo) {
		$results = $this->PhdStudent->getStudentId($regNo);
		//pr($results);
		$studentId = $results[0]['PhdStudent']['id'];
		
		$csmArray = $results[0]['PhdCourseStudentMapping'];
		$monthyears = $this->MonthYear->getAllMonthYears();
		$this->set('monthyears', $monthyears);
		
		$this->set(compact('csmArray'));
		$this->set(compact('regNo'));
		$this->set(compact('studentId'));
		
		if($this->request->is('post')) {
			pr($this->data);
		}
	}
	
	public function certificate($regNo) {
		/* $results = $this->PhdStudent->find('all', array(
			'conditions'=>array('PhdStudent.discontinued_status'=>0, 'PhdStudent.registration_number'=>$regNo)
		)); */
		
		$studentDetails = $this->studentDetails($regNo);
		//pr($studentDetails);
		
		$status = $this->getStatus($studentDetails, $regNo);
		//echo $status;
	//	$results[0]['details'] = $studentDetails;
		if ($status) {
			$studentDetails[$regNo]['status'] = "Completed";
			//pr($studentDetails);
			$this->set('result', $studentDetails);
			$this->set('regNo', $regNo);
			
			$getSignature = $this->Signature->find("all", array('conditions' => array('Signature.id' => 1)));
			$this->set('getSignature', $getSignature);
			$this->layout="print";
			$this->render('certificate');
		}
		else {
			$results[$regNo]['details']['status'] = "Not Completed";
			$this->Flash->error(__('Course yet to be completed.'));
			$this->redirect(array('controller' => 'PhdStudents','action' => 'view',$regNo));
		}
	}
	
} 
