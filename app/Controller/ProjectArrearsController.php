<?php
App::uses('AppController', 'Controller');
App::import('Controller', 'ContinuousAssessmentExams');
App::import('Vendor', 'PHPExcel', array('file' => 'PHPExcel'.DS.'IOFactory.php'));
App::import('Vendor', 'mPDF', array('file' => 'mPDF'.DS.'mPDF.php'));
include '../Controller/Constants.php';

class ProjectArrearsController extends AppController {
	//public $practical_or_studio = array("practical", "studio");
	public $project = "project";
	//public $project = array("project");
	
	public $resultsArray = array();
	public $marks_available = array();
	public $ese_project_id_array = array();
	
	public $components = array('Paginator', 'Flash', 'Session','mPDF');
	public $uses = array("Arrear", "CourseType", "User", "StudentMark", "PracticalAttendance", "Practical", "EsePractical", 
			"InternalPractical", "CaePractical", "CourseMapping", "CourseStudentMapping", "InternalProject", 
			"InternalProject", "ProjectViva", "Student"
	);

	public function project() {
		//pr($this->cType);
		$ContinuousAssessmentExamsController = new ContinuousAssessmentExamsController;
		$monthyears = $ContinuousAssessmentExamsController->findMonthYear();
		//pr($monthyears);
		
		$courseTypes = $this->CourseType->find('list', array(
				'conditions' => array("CourseType.course_type"=>$this->project),
		));
		//pr($courseTypes);
		//pr(array_keys($courseTypes));
		$this->set(compact('monthyears'));
	}
	
	public function allArrearData($exam_month_year_id) {
		$cm_result = $this->CourseMapping->find('all', array(
			'conditions'=>array('CourseMapping.indicator'=>0, 'CourseMapping.month_year_id <' => $exam_month_year_id),
			'fields'=>array('CourseMapping.id'),
			'contain'=>array(
				'Course'=>array(
					'conditions'=>array('Course.course_type_id' => array(4)),
					'fields'=>array('Course.course_type_id')
				)
			)
		));
		//echo count($cm_result)." *** ";
		//pr($cm_result);
		if (!empty($cm_result)) {		
			$course_mapping_string="";
			$final_array=array();
			foreach ($cm_result as $key => $arr) {
				if (isset($arr['Course']['id']) && $arr['Course']['id'] > 0) {
					$final_array[$arr['CourseMapping']['id']]=$arr['CourseMapping']['id'];
					$course_mapping_string.=$arr['CourseMapping']['id'].",";
				}
			}
			$course_mapping_string = substr($course_mapping_string,0,strlen($course_mapping_string)-1);
			//echo count($final_array)." *** ";
			//pr($final_array);
			
			$results = $this->StudentMark->query("SELECT distinct(StudentMark.course_mapping_id) 
					FROM student_marks StudentMark join students Student on StudentMark.student_id=Student.id
					WHERE Student.discontinued_status=0 and StudentMark.id IN
					(SELECT max( id ) FROM `student_marks` where student_marks.course_mapping_id in ($course_mapping_string)
					GROUP BY student_id, course_mapping_id ORDER BY id DESC)
					AND ((STATUS='Fail' AND revaluation_status=0)
					OR (final_status='Fail' AND revaluation_status=1))");
			//pr($results);
			
			$cm_id_array = array();
			foreach ($results as $key => $value) {
				$cm_id_array[$value['StudentMark']['course_mapping_id']] = $value['StudentMark']['course_mapping_id'];
			}
			//echo count($cm_id_array);
			//pr($cm_id_array);
			return $cm_id_array;
		}
		else return false;
	}
	
	public function getNonArrearData($exam_month_year_id) {
		$csm_array = $this->CourseStudentMapping->find('all', array(
				'conditions'=>array('CourseStudentMapping.new_semester_id'=>$exam_month_year_id,
				),
				'fields'=>array('CourseStudentMapping.id', 'CourseStudentMapping.course_mapping_id',
						'CourseStudentMapping.student_id'
				),
				'contain'=>array(
						'CourseMapping'=>array('fields'=>array('CourseMapping.id'),
								'Course'=>array(
										'fields'=>array('Course.course_type_id', 'Course.course_code', 'Course.course_name', 'Course.common_code'),
								),
								'Batch' => array(
										'fields' => array('Batch.batch_period')
								),
								'Program' => array(
										'fields' => array('Program.program_name', 'Program.short_code'),
										'Academic' => array(
												'fields' => array('Academic.academic_name', 'Academic.short_code')
										),
								),
						),
						'Student'=>array(
								'fields' => array('Student.registration_number', 'Student.name'),
								'conditions' => array('Student.discontinued_status' => 0),
						)
				)
		));
		return $csm_array;
	}
	
	public function filterBasedOnCourseType($results, $course_type_id, $model) {
		//pr($results);
		$final_result=array();
		$i=0;
		foreach ($results as $key => $result) {
			if (in_array($result['CourseMapping']['Course']['course_type_id'], array_keys($course_type_id))) {
				$final_result[$result['CourseMapping']['id']] = $result['CourseMapping']['id'];
			}
		}
		return $final_result;
	}
	
	public function arrearData($exam_month_year_id) {
		
		$course_type_id = $this->listCourseTypeIdsBasedOnMethod($this->project, "-");
		//pr($course_type_id);
		//Case 1: From Student Mark
		$model = "StudentMark";
		$results[$model] = $this->allArrearData($exam_month_year_id);
		//pr($results);
		$arrear_csm = $this->getNonArrearData($exam_month_year_id);
		//pr($arrear_csm);
		$model = "CourseStudentMapping";
		$results[$model] = $this->filterBasedOnCourseType($arrear_csm, $course_type_id, $model);
		//pr($results);
		$this->set(compact('exam_month_year_id', 'results'));
		$this->layout=false;
	}
		
	public function getStudentIds($results, $modelArray) {
		$student_id_array=array();
		foreach ($results as $model => $modelArray){
			foreach ($modelArray as $key => $value) {
				if (isset($value[$model]['student_id'])) {
					$student_id_array[$value[$model]['student_id']] = array(
						'registration_number'=>$value['Student']['registration_number'],
						'name'=>$value['Student']['name'],
					);
				}
			}
		}
		return $student_id_array;
	}
	
	public function getPracticalCaeMarks($cm_id, $exam_month_year_id, $student_id_array) {
		$cae_array = array();
		foreach ($student_id_array as $student_id => $value) {
			$results = $this->CaeProject->find('first', array(
			'conditions'=>array('CaeProject.course_mapping_id'=>$cm_id, 'CaeProject.indicator'=>0),
			'fields'=>array('CaeProject.id'),
			'contain' => array(
				'InternalProject' => array(
						'conditions' => array('InternalProject.student_id'=>$student_id,
								'InternalProject.month_year_id <=' => $exam_month_year_id,
						),
						'fields'=>array('InternalProject.id', 'InternalProject.student_id', 'InternalProject.month_year_id',
								'InternalProject.marks'
						),
						'limit'=>1,
						'order'=>array('InternalProject.month_year_id DESC', 'InternalProject.id DESC')
				)
			)
			));
			//pr($results);
			$cae_array[$student_id]='';
			if (isset($results['InternalProject'][0])) {
				if (($results['InternalProject'][0]['marks']=='A') || ($results['InternalProject'][0]['marks']=='a')) {
					$cae_array[$student_id]['marks'] = '';
				}
				else {
					$cae_array[$student_id]['marks'] = $results['InternalProject'][0]['marks'];
				}
				$cae_array[$student_id]['cae_project_id'] = $results['InternalProject'][0]['cae_project_id'];
			}
		}
		return $cae_array;
	}
	
	public function saveTheoryCAEMarks($CaeOldMark = null, $cm_id = null, $markEntry = null, $student_id = null, $month_year_id = null){
		if ($CaeOldMark <> $markEntry) {
			$cae_data_exists=$this->InternalProject->find('first', array(
					'conditions' => array(
							'InternalProject.course_mapping_id'=>$cm_id,
							'InternalProject.month_year_id'=>$month_year_id,
							'InternalProject.student_id'=>$student_id,
					),
					'fields' => array('InternalProject.id'),
					'recursive' => 0
			));
			$data=array();
			$data['InternalProject']['month_year_id'] = $month_year_id;
			$data['InternalProject']['student_id'] = $student_id;
			$data['InternalProject']['course_mapping_id'] = $cm_id;
			$data['InternalProject']['marks'] = $markEntry;
			if(isset($cae_data_exists['InternalProject']['id']) && $cae_data_exists['InternalProject']['id']>0) {
				$id = $cae_data_exists['InternalProject']['id'];
				$data['InternalProject']['id'] = $id;
				$data['InternalProject']['modified_by'] = $this->Auth->user('id');
				$data['InternalProject']['modified'] = date("Y-m-d H:i:s");
			}
			else {
				$this->InternalProject->create($data);
				$data['InternalProject']['created_by'] = $this->Auth->user('id');
			}
			$this->InternalProject->save($data);
		}
	}
	
	public function editMarks($cm_id, $exam_month_year_id, $model) {
		//echo $cm_id." ".$exam_month_year_id." ".$model;
		//Get arrear students as on date for this cm_id
		$arr[$cm_id]=$cm_id;
		$courseMarks = $this->CourseMapping->getCourseMarks($arr);
		
		$ese_detail = $this->CourseMapping->find('first', array(
			'fields'=>array('CourseMapping.id'),
			'conditions'=>array('CourseMapping.indicator'=>0, 'CourseMapping.id'=>$cm_id),
			'contain'=>array(
				'EseProject'=>array('fields'=>array('EseProject.id'), 'conditions'=>array('EseProject.indicator'=>0)),
			)
		));
		$ese_project_id = $ese_detail['EseProject'][0]['id'];
		SWITCH ($model) {
			CASE "StudentMark":
				$results = $this->studentArrearDataForACourse($cm_id);
				foreach ($results as $key => $result) {
					$student_id = $result['StudentMark']['student_id'];
					$stu_result = $this->getCaeAndEse($student_id, $cm_id, $ese_project_id, $exam_month_year_id);
					//pr($stu_result);
					/* $caeArray = $this->InternalProject->find('all', array(
							'conditions'=>array('InternalProject.course_mapping_id'=>$cm_id,
									'InternalProject.student_id'=>$student_id
							),
							'fields'=>array('InternalProject.id', 'InternalProject.course_mapping_id', 'InternalProject.marks',
									'InternalProject.student_id', 'InternalProject.month_year_id'),
							'order'=>array('InternalProject.id DESC'),
							'limit'=>1
					)); */
					$results[$key]['StudentMark']['InternalProject']['cae_mark'] = $stu_result[0]['InternalProject'][0]['marks'];
					$results[$key]['StudentMark']['InternalProject']['month_year_id'] = $stu_result[0]['InternalProject'][0]['month_year_id'];
					$results[$key]['StudentMark']['InternalProject']['id'] = $stu_result[0]['InternalProject'][0]['id'];
					if (!empty($stu_result[0]['ProjectViva'])) {
						$results[$key]['StudentMark']['ProjectViva']['ese_mark'] = $stu_result[0]['ProjectViva'][0]['marks'];
					}
					else {
						$results[$key]['StudentMark']['ProjectViva']['ese_mark'] = '';
					}
					
				}
				//pr($results);
				$this->set(compact('exam_month_year_id', 'results', 'cm_id', 'courseMarks', 'model', 'ese_project_id'));
				break;
			CASE "CourseStudentMapping":
				$results = $this->getNonArrearData($exam_month_year_id, $cm_id);
				foreach ($results as $key => $result) {
					$student_id = $result['CourseStudentMapping']['student_id'];
					$stu_result = $this->getCaeAndEse($student_id, $cm_id, $ese_project_id, $exam_month_year_id);
					/* $caeArray = $this->InternalProject->find('all', array(
							'conditions'=>array('InternalProject.course_mapping_id'=>$cm_id,
									'InternalProject.student_id'=>$student_id
							),
							'fields'=>array('InternalProject.id', 'InternalProject.course_mapping_id', 'InternalProject.marks',
									'InternalProject.student_id', 'InternalProject.month_year_id'),
							'order'=>array('InternalProject.id DESC'),
							'limit'=>1
					)); */
					$results[$key]['CourseStudentMapping']['InternalProject']['cae_mark'] = $stu_result[0]['InternalProject'][0]['marks'];
					$results[$key]['CourseStudentMapping']['InternalProject']['month_year_id'] = $stu_result[0]['InternalProject'][0]['month_year_id'];
					$results[$key]['CourseStudentMapping']['InternalProject']['id'] = $stu_result[0]['InternalProject'][0]['id'];
					$results[$key]['CourseStudentMapping']['ProjectViva']['ese_mark'] = $stu_result[0]['ProjectViva'][0]['marks'];
				}
				//pr($results);
				$this->set(compact('exam_month_year_id', 'results', 'cm_id', 'courseMarks', 'model', 'ese_project_id'));
				break;
		}
		
	}
	
	public function getCaeAndEse($student_id, $cm_id, $ese_project_id, $exam_month_year_id) {
		$results = $this->Student->find('all', array(
				'fields'=>array('Student.id'),
				'conditions'=>array('Student.discontinued_status'=>0, 'Student.id'=>$student_id),
				'contain'=>array(
						'InternalProject'=>array(
								'conditions'=>array('InternalProject.course_mapping_id'=>$cm_id,
										'InternalProject.student_id'=>$student_id
								),
								'fields'=>array('InternalProject.id', 'InternalProject.course_mapping_id', 'InternalProject.marks',
										'InternalProject.student_id', 'InternalProject.month_year_id'),
								'order'=>array('InternalProject.id DESC'),
								'limit'=>1
						),
						'ProjectViva'=>array(
								'conditions'=>array('ProjectViva.ese_project_id'=>$ese_project_id,
										'ProjectViva.student_id'=>$student_id,
										'ProjectViva.month_year_id'=>$exam_month_year_id
								),
								'fields'=>array('ProjectViva.id', 'ProjectViva.ese_project_id', 'ProjectViva.marks',
										'ProjectViva.student_id', 'ProjectViva.month_year_id')
									
						)
				)));
		return $results;
	}
	
	public function studentArrearDataForACourse($cm_id) {
		//max( `status` ) AS status, max( revaluation_status ) AS revaluation_status, max( final_status ) AS final_status
		$result = $this->StudentMark->query("SELECT max( id ) AS id, student_id, course_mapping_id, max( month_year_id ) as month_year_id  
						FROM student_marks StudentMark
						WHERE StudentMark.course_mapping_id =$cm_id
						AND StudentMark.id
						IN (
						
						SELECT sm.id
						FROM student_marks sm
						WHERE sm.course_mapping_id =$cm_id
						AND (
						(
						sm.STATUS = 'Fail'
						AND sm.revaluation_status =0
							)
							OR (
							sm.final_status = 'Fail'
							AND sm.revaluation_status =1
							)
							)
							)
							GROUP BY 2 , 3
							HAVING max( `status` ) = 'Fail'
				");
		
		/* $dbo = $this->CourseMapping->getDatasource();
		$logs = $dbo->getLog();
		$lastLog = end($logs['log']);
		echo $lastLog['query']; */
		
		return $result;
	}
	
	public function listAllArrearCoursesFromStudentMark($exam_month_year_id) {
		pr(Configure::read('CourseType.theory'));
		
		
		pr($results); die;
		$cm_result = $this->CourseMapping->find('all', array(
				'conditions'=>array('CourseMapping.indicator'=>0, 'CourseMapping.month_year_id <=' => $exam_month_year_id),
				'fields'=>array('CourseMapping.id'),
				'contain'=>array(
				'Course'=>array(
					//'conditions'=>array('Course.course_type_id' => array(4)),
					'fields'=>array('Course.course_type_id')
				)
			)
		));
		//echo count($cm_result)." *** ";
		//pr($cm_result);
	
		//echo "test";
		$course_mapping_string="";
		$final_array=array();
		foreach ($cm_result as $key => $arr) {
			//if (isset($arr['Course']['id']) && $arr['Course']['id'] > 0) {
			//$final_array[$arr['CourseMapping']['id']]=$arr['CourseMapping']['id'];
			$final_array[$arr['CourseMapping']['id']]=array('cm_id'=>$arr['CourseMapping']['id'],
					'course_type_id'=>$arr['Course']['id']);
			$course_mapping_string.=$arr['CourseMapping']['id'].",";
			//}
		}
		$course_mapping_string = substr($course_mapping_string,0,strlen($course_mapping_string)-1);
		//echo count($final_array)." *** ";
		//pr($final_array);
	
		$results = $this->StudentMark->query("SELECT distinct(StudentMark.course_mapping_id), Course.course_type_id 
				FROM student_marks StudentMark join students Student on StudentMark.student_id=Student.id JOIN course_mappings 
				cm ON cm.id=StudentMark.course_mapping_id JOIN courses Course ON Course.id=cm.course_id 
				WHERE Student.discontinued_status=0 and StudentMark.id IN
				(SELECT max( id ) FROM `student_marks` where student_marks.course_mapping_id in ($course_mapping_string)
				GROUP BY student_id, course_mapping_id ORDER BY id DESC)
				AND ((STATUS='Fail' AND revaluation_status=0)
				OR (final_status='Fail' AND revaluation_status=1))");
		//pr($results);
	
		$cm_id_array = array();
		foreach ($results as $key => $value) {
			$cm_id_array[$value['StudentMark']['course_mapping_id']] = array('cm_id'=>$value['StudentMark']['course_mapping_id'],
					'course_type_id'=>$value['Course']['course_type_id']); 
			
			//$value['StudentMark']['course_mapping_id'];
		}
		//echo count($cm_id_array);
		asort($cm_id_array);
		pr($cm_id_array);
		
		return $cm_id_array;
	}
	
	public function saveCAEProjectMarks($CaeOldMark, $cm_id = null, $markEntry = null, $student_id = null, $month_year_id = null){
		if ($CaeOldMark <> $markEntry) {
			$cae_data_exists=$this->InternalProject->find('first', array(
					'conditions' => array(
							'InternalProject.course_mapping_id'=>$cm_id,
							'InternalProject.month_year_id'=>$month_year_id,
							'InternalProject.student_id'=>$student_id,
					),
					'fields' => array('InternalProject.id'),
					'recursive' => 0
			));
			$data=array();
			$data['InternalProject']['month_year_id'] = $month_year_id;
			$data['InternalProject']['student_id'] = $student_id;
			$data['InternalProject']['course_mapping_id'] = $cm_id;
			$data['InternalProject']['marks'] = $markEntry;
			if(isset($cae_data_exists['InternalProject']['id']) && $cae_data_exists['InternalProject']['id']>0) {
				$id = $cae_data_exists['InternalProject']['id'];
				$data['InternalProject']['id'] = $id;
				$data['InternalProject']['modified_by'] = $this->Auth->user('id');
				$data['InternalProject']['modified'] = date("Y-m-d H:i:s");
			}
			else {
				$this->InternalProject->create($data);
				$data['InternalProject']['created_by'] = $this->Auth->user('id');
			}
			if ($this->InternalProject->save($data)) {
				echo true;
			}
			else {
				echo false;
			}
			exit;
		}
	}
	
	public function saveESEProjectMarks($ese_project_id = null, $cm_id = null, $markEntry = null, $student_id = null, $month_year_id = null){
		
			$data_exists=$this->ProjectViva->find('first', array(
					'conditions' => array(
							'ProjectViva.ese_project_id'=>$ese_project_id,
							'ProjectViva.month_year_id'=>$month_year_id,
							'ProjectViva.student_id'=>$student_id,
					),
					'fields' => array('ProjectViva.id'),
					'recursive' => 0
			));
			$data=array();
			$data['ProjectViva']['month_year_id'] = $month_year_id;
			$data['ProjectViva']['student_id'] = $student_id;
			$data['ProjectViva']['ese_project_id'] = $ese_project_id;
			$data['ProjectViva']['marks'] = $markEntry;
			if(isset($data_exists['ProjectViva']['id']) && $data_exists['ProjectViva']['id']>0) {
				$id = $data_exists['ProjectViva']['id'];
				$data['ProjectViva']['id'] = $id;
				$data['ProjectViva']['modified_by'] = $this->Auth->user('id');
				$data['ProjectViva']['modified'] = date("Y-m-d H:i:s");
			}
			else {
				$this->ProjectViva->create($data);
				$data['ProjectViva']['created_by'] = $this->Auth->user('id');
			}
			if ($this->ProjectViva->save($data)) {
				echo true;
			}
			else {
				echo false;
			}
			exit;
	}
}
