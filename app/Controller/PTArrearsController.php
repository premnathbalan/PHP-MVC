<?php
App::uses('AppController', 'Controller');
App::import('Controller', 'ContinuousAssessmentExams');
App::import('Vendor', 'PHPExcel', array('file' => 'PHPExcel'.DS.'IOFactory.php'));
App::import('Vendor', 'mPDF', array('file' => 'mPDF'.DS.'mPDF.php'));
include '../Controller/Constants.php';

class PTArrearsController extends AppController {
	//public $practical_or_studio = array("practical", "studio");
	public $pt = "PT";
	//public $project = array("project");
	
	public $resultsArray = array();
	public $marks_available = array();
	public $ese_practical_id_array = array();
	
	public $components = array('Paginator', 'Flash', 'Session','mPDF');
	public $uses = array("Arrear", "CourseType", "User", "StudentMark", "PracticalAttendance", "Practical", "EsePractical", 
			"InternalPractical", "CaePractical", "CourseMapping", "CaePt", "CourseStudentMapping", "ProfessionalTraining", "MonthYear"
	);

	public function index() {
		$access_result = $this->checkPathAccess($this->Auth->user('id'));
		if (!$access_result) {
			//pr($this->cType);
			//$ContinuousAssessmentExamsController = new ContinuousAssessmentExamsController;
			//$monthyears = $ContinuousAssessmentExamsController->findMonthYear();
			$monthyears = $this->MonthYear->getAllMonthYears();
			//pr($monthyears);
			
			$courseTypes = $this->CourseType->find('list', array(
					'conditions' => array("CourseType.course_type"=>$this->pt),
			));
			//pr($courseTypes);
			//pr(array_keys($courseTypes));
			$this->set(compact('monthyears'));
		}
		else {
			$this->render('../Users/access_denied');
		}
	}
	
	public function getAllArrearData($exam_month_year_id) {
		$cm_result = $this->CourseMapping->find('all', array(
			'conditions'=>array('CourseMapping.indicator'=>0, 'CourseMapping.month_year_id <' => $exam_month_year_id),
			'fields'=>array('CourseMapping.id'),
			'contain'=>array(
				'Course'=>array(
					'conditions'=>array('Course.course_type_id'=> Configure::read('CourseType.pt')),
					'fields'=>array('Course.course_type_id')
				),
			)
		));
		//pr($cm_result);
		//echo count($cm_result)." *** ";
		
		$cm_id_array = array();
		foreach ($cm_result as $key => $arr) {
			if (isset($arr['Course']['id']) && $arr['Course']['id'] > 0) {
				$cm_id = $arr['CourseMapping']['id'];
				$results = $this->checkIfArrearExistsInStudentMarkForACourse($cm_id);
				if (!empty($results) && isset($results[0]['StudentMark']['course_mapping_id'])) {
					$cm_id_array[$results[0]['StudentMark']['course_mapping_id']] = $results[0]['StudentMark']['course_mapping_id'];
				}
			}
		}
		return $cm_id_array; 
	}
	
	public function getAllNonArrearData($exam_month_year_id) {
		$results = $this->checkIfArrearExistsinCSM($exam_month_year_id, Configure::read('CourseType.pt'));
		return $results;
	}
	
	public function arrearData($exam_month_year_id) {
		
		$course_type_id = $this->listCourseTypeIdsBasedOnMethod($this->pt, "-");
		//pr($course_type_id);
		
		//Case 1: From Student Mark
		$model = "StudentMark";
		$results[$model] = $this->getAllArrearData($exam_month_year_id); 
		
		$model = "CourseStudentMapping";
		$results[$model] = $this->getAllNonArrearData($exam_month_year_id);
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
	
	
	public function savePTCAEMarks($CaeOldMark = null, $cm_id = null, $markEntry = null, $student_id = null, $month_year_id = null, $cae_pt_id = null){
		if ($CaeOldMark <> $markEntry) {
			$cae_data_exists=$this->ProfessionalTraining->find('first', array(
					'conditions' => array(
							'ProfessionalTraining.cae_pt_id'=>$cae_pt_id,
							'ProfessionalTraining.month_year_id'=>$month_year_id,
							'ProfessionalTraining.student_id'=>$student_id,
					),
					'fields' => array('ProfessionalTraining.id'),
					'recursive' => 0
			));
			//pr($cae_data_exists);
			$data=array();
			$data['ProfessionalTraining']['month_year_id'] = $month_year_id;
			$data['ProfessionalTraining']['student_id'] = $student_id;
			$data['ProfessionalTraining']['cae_pt_id'] = $cae_pt_id;
			$data['ProfessionalTraining']['marks'] = $markEntry;
			if(isset($cae_data_exists['ProfessionalTraining']['id']) && $cae_data_exists['ProfessionalTraining']['id']>0) {
				$id = $cae_data_exists['ProfessionalTraining']['id'];
				$data['ProfessionalTraining']['id'] = $id;
				$data['ProfessionalTraining']['modified_by'] = $this->Auth->user('id');
				$data['ProfessionalTraining']['modified'] = date("Y-m-d H:i:s");
			}
			else {
				//pr($data);
				$this->ProfessionalTraining->create($data);
				$data['ProfessionalTraining']['created_by'] = $this->Auth->user('id');
			}
			$this->ProfessionalTraining->save($data);
		}
	}
	
	public function editMarks($cm_id, $exam_month_year_id, $model, $caePtId) {
		//echo $cm_id." ".$exam_month_year_id." ".$model;
		//Get arrear students as on date for this cm_id
		//$exam_month_year_id = $exam_month_year_id + 1;
		$arr[$cm_id]=$cm_id;
		$courseMarks = $this->CourseMapping->getCourseMarks($arr);
		//pr($courseMarks);
		
		SWITCH ($model) {
			CASE "StudentMark":
				$results = $this->listArrearStudents($exam_month_year_id, $cm_id);
				//pr($results);
				$caePtArray = $this->CaePt->find("first", array(
					'conditions'=>array('CaePt.course_mapping_id'=>$cm_id, 'CaePt.indicator'=>0,),
					'fields' => array('CaePt.id'),
					'contain'=>false
				));
				$caePtId = $caePtArray['CaePt']['id'];
				//pr($caePtArray); 
				foreach ($results as $key => $result) {
					$student_id = $result['StudentMark']['student_id'];
					$caeArray = $this->ProfessionalTraining->find('all', array(
							'conditions'=>array('ProfessionalTraining.cae_pt_id'=>$caePtId,
									'ProfessionalTraining.student_id'=>$student_id
							),
							'fields'=>array('ProfessionalTraining.id', 'ProfessionalTraining.cae_pt_id', 'ProfessionalTraining.marks',
									'ProfessionalTraining.student_id', 'ProfessionalTraining.month_year_id'),
							'order'=>array('ProfessionalTraining.id DESC'),
							'limit'=>1
					));
					$results[$key]['StudentMark']['ProfessionalTraining']['cae_mark'] = $caeArray[0]['ProfessionalTraining']['marks'];
					$results[$key]['StudentMark']['ProfessionalTraining']['month_year_id'] = $caeArray[0]['ProfessionalTraining']['month_year_id'];
					$results[$key]['StudentMark']['ProfessionalTraining']['id'] = $caeArray[0]['ProfessionalTraining']['id'];
				}
				//pr($results);
				$this->set(compact('exam_month_year_id', 'results', 'cm_id', 'courseMarks', 'model'));
				break;
			CASE "CourseStudentMapping":
				$results = $this->listNonArrearStudents($exam_month_year_id, $cm_id);
				foreach ($results as $key => $result) {
					$student_id = $result['CourseStudentMapping']['student_id'];
					$caeArray = $this->ProfessionalTraining->find('all', array(
							'conditions'=>array('ProfessionalTraining.course_mapping_id'=>$cm_id,
									'ProfessionalTraining.student_id'=>$student_id
							),
							'fields'=>array('ProfessionalTraining.id', 'ProfessionalTraining.course_mapping_id', 'ProfessionalTraining.marks',
									'ProfessionalTraining.student_id', 'ProfessionalTraining.month_year_id'),
							'order'=>array('ProfessionalTraining.id DESC'),
							'limit'=>1
					));
					//pr($caeArray);
					$results[$key]['CourseStudentMapping']['ProfessionalTraining']['cae_mark'] = $caeArray[0]['ProfessionalTraining']['marks'];
					$results[$key]['CourseStudentMapping']['ProfessionalTraining']['month_year_id'] = $caeArray[0]['ProfessionalTraining']['month_year_id'];
					$results[$key]['CourseStudentMapping']['ProfessionalTraining']['id'] = $caeArray[0]['ProfessionalTraining']['id'];
				}
				//pr($results);
				$this->set(compact('exam_month_year_id', 'results', 'cm_id', 'courseMarks', 'model'));
				break;
		}
		
	}
	
}
