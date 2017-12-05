<?php
App::uses('AppController', 'Controller');
App::import('Controller', 'ContinuousAssessmentExams');
App::import('Vendor', 'PHPExcel', array('file' => 'PHPExcel'.DS.'IOFactory.php'));
App::import('Vendor', 'mPDF', array('file' => 'mPDF'.DS.'mPDF.php'));
include '../Controller/Constants.php';

class TheoryArrearsController extends AppController {
	//public $practical_or_studio = array("practical", "studio");
	public $theory = "theory";
	//public $project = array("project");
	
	public $resultsArray = array();
	public $marks_available = array();
	public $ese_practical_id_array = array();
	
	public $components = array('Paginator', 'Flash', 'Session','mPDF');
	public $uses = array("Arrear", "CourseType", "User", "StudentMark", "PracticalAttendance", "Practical", "EsePractical", 
			"InternalPractical", "CaePractical", "CourseMapping", "CourseStudentMapping", "InternalExam", "MonthYear"
	);

	public function theory() {
		$access_result = $this->checkPathAccess($this->Auth->user('id'));
		if (!$access_result) {
			//pr($this->cType);
			//$ContinuousAssessmentExamsController = new ContinuousAssessmentExamsController;
			//$monthyears = $ContinuousAssessmentExamsController->findMonthYear();
			$monthyears = $this->MonthYear->getAllMonthYears();
			//pr($monthyears);
			
			$courseTypes = $this->CourseType->find('list', array(
					'conditions' => array("CourseType.course_type"=>$this->theory),
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
					'conditions'=>array('Course.course_type_id'=> Configure::read('CourseType.theory')),
					'fields'=>array('Course.course_type_id')
				)
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
		$results = $this->checkIfArrearExistsinCSM($exam_month_year_id, Configure::read('CourseType.theory'));
		//pr($results);
		/* $csm_array = $this->CourseStudentMapping->find('all', array(
				'conditions'=>array('CourseStudentMapping.new_semester_id <'=>$exam_month_year_id,
						$cmIdCond
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
		pr($csm_array); */
		return $results;
	}
	
	/* public function filterBasedOnCourseType($results, $course_type_id, $model) {
		//pr($results);
		$final_result=array();
		$i=0;
		foreach ($results as $key => $result) {
			if (in_array($result['CourseMapping']['Course']['course_type_id'], array_keys($course_type_id))) {
				$final_result[$result['CourseMapping']['id']] = $result['CourseMapping']['id'];
			}
		}
		return $final_result;
	} */
	
	public function arrearData($exam_month_year_id) {
		
		$course_type_id = $this->listCourseTypeIdsBasedOnMethod($this->theory, "-");
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
	
	public function getPracticalCaeMarks($cm_id, $exam_month_year_id, $student_id_array) {
		$cae_array = array();
		foreach ($student_id_array as $student_id => $value) {
			$results = $this->CaePractical->find('first', array(
			'conditions'=>array('CaePractical.course_mapping_id'=>$cm_id, 'CaePractical.indicator'=>0),
			'fields'=>array('CaePractical.id'),
			'contain' => array(
				'InternalPractical' => array(
						'conditions' => array('InternalPractical.student_id'=>$student_id,
								'InternalPractical.month_year_id <=' => $exam_month_year_id,
								//'InternalPractical.cae_practical_id' => $cae_practical_id
						),
						'fields'=>array('InternalPractical.id', 'InternalPractical.student_id', 'InternalPractical.month_year_id',
								'InternalPractical.marks'
						),
						'limit'=>1,
						'order'=>array('InternalPractical.month_year_id DESC', 'InternalPractical.id DESC')
				)
			)
			));
			//pr($results);
			$cae_array[$student_id]='';
			if (isset($results['InternalPractical'][0])) {
				if (($results['InternalPractical'][0]['marks']=='A') || ($results['InternalPractical'][0]['marks']=='a')) {
					$cae_array[$student_id]['marks'] = '';
				}
				else {
					$cae_array[$student_id]['marks'] = $results['InternalPractical'][0]['marks'];
				}
				$cae_array[$student_id]['cae_practical_id'] = $results['InternalPractical'][0]['cae_practical_id'];
			}
		}
		return $cae_array;
	}
	
	public function saveTheoryCAEMarks($CaeOldMark = null, $cm_id = null, $markEntry = null, $student_id = null, $month_year_id = null){
		if ($CaeOldMark <> $markEntry) {
			$cae_data_exists=$this->InternalExam->find('first', array(
					'conditions' => array(
							'InternalExam.course_mapping_id'=>$cm_id,
							'InternalExam.month_year_id'=>$month_year_id,
							'InternalExam.student_id'=>$student_id,
					),
					'fields' => array('InternalExam.id'),
					'recursive' => 0
			));
			//pr($cae_data_exists);
			$data=array();
			$data['InternalExam']['month_year_id'] = $month_year_id;
			$data['InternalExam']['student_id'] = $student_id;
			$data['InternalExam']['course_mapping_id'] = $cm_id;
			$data['InternalExam']['marks'] = $markEntry;
			if(isset($cae_data_exists['InternalExam']['id']) && $cae_data_exists['InternalExam']['id']>0) {
				$id = $cae_data_exists['InternalExam']['id'];
				$data['InternalExam']['id'] = $id;
				$data['InternalExam']['modified_by'] = $this->Auth->user('id');
				$data['InternalExam']['modified'] = date("Y-m-d H:i:s");
			}
			else {
				//pr($data);
				$this->InternalExam->create($data);
				$data['InternalExam']['created_by'] = $this->Auth->user('id');
			}
			$this->InternalExam->save($data);
		}
	}
	
	public function editMarks($cm_id, $exam_month_year_id, $model) {
		//echo $cm_id." ".$exam_month_year_id." ".$model;
		//Get arrear students as on date for this cm_id
		//$exam_month_year_id = $exam_month_year_id + 1;
		$arr[$cm_id]=$cm_id;
		$courseMarks = $this->CourseMapping->getCourseMarks($arr);
		SWITCH ($model) {
			CASE "StudentMark":
				$results = $this->listArrearStudents($exam_month_year_id, $cm_id);
				//pr($results);
				foreach ($results as $key => $result) {
					$student_id = $result['StudentMark']['student_id'];
					$caeArray = $this->InternalExam->find('all', array(
							'conditions'=>array('InternalExam.course_mapping_id'=>$cm_id,
									'InternalExam.student_id'=>$student_id
							),
							'fields'=>array('InternalExam.id', 'InternalExam.course_mapping_id', 'InternalExam.marks',
									'InternalExam.student_id', 'InternalExam.month_year_id'),
							'order'=>array('InternalExam.id DESC'),
							'limit'=>1
					));
					//$cae_marks = $caeArray[0]['InternalExam']['marks'];
					$results[$key]['StudentMark']['InternalExam']['cae_mark'] = $caeArray[0]['InternalExam']['marks'];
					$results[$key]['StudentMark']['InternalExam']['month_year_id'] = $caeArray[0]['InternalExam']['month_year_id'];
					$results[$key]['StudentMark']['InternalExam']['id'] = $caeArray[0]['InternalExam']['id'];
				}
				//pr($results);
				$this->set(compact('exam_month_year_id', 'results', 'cm_id', 'courseMarks', 'model'));
				break;
			CASE "CourseStudentMapping":
				$results = $this->listNonArrearStudents($exam_month_year_id, $cm_id);
				foreach ($results as $key => $result) {
					$student_id = $result['CourseStudentMapping']['student_id'];
					$caeArray = $this->InternalExam->find('all', array(
							'conditions'=>array('InternalExam.course_mapping_id'=>$cm_id,
									'InternalExam.student_id'=>$student_id
							),
							'fields'=>array('InternalExam.id', 'InternalExam.course_mapping_id', 'InternalExam.marks',
									'InternalExam.student_id', 'InternalExam.month_year_id'),
							'order'=>array('InternalExam.id DESC'),
							'limit'=>1
					));
					//pr($caeArray);
					//$cae_marks = $caeArray[0]['InternalExam']['marks'];
					$results[$key]['CourseStudentMapping']['InternalExam']['cae_mark'] = $caeArray[0]['InternalExam']['marks'];
					$results[$key]['CourseStudentMapping']['InternalExam']['month_year_id'] = $caeArray[0]['InternalExam']['month_year_id'];
					$results[$key]['CourseStudentMapping']['InternalExam']['id'] = $caeArray[0]['InternalExam']['id'];
				}
				//pr($results);
				$this->set(compact('exam_month_year_id', 'results', 'cm_id', 'courseMarks', 'model'));
				break;
		}
		
	}
	
	public function index() {
		$html ="";
		if(isset($this->request->data['foilCard']) == 'foilCard' || isset($this->request->data['coverPage']) == 'coverPage'){
			$pageLastCName = "FIRST";$eachPage = 1;
			if($this->request->data['EA']['monthyears']){
				$txtExamMonthYear = $this->getExamMonthYearName($this->request->data['EA']['monthyears']);
			}
			$headerLogo 	= "<table border='0' align='center' cellpadding='0' cellspacing='0' style='font:14px Arial;width:100%;'>
								<tr>
								<td rowspan='2' align='right' width='35%'><img src='../webroot/img/user.jpg'></td>
									<td align='left' width='65%'>SATHYABAMA UNIVERSITY<br/>
									<span class='slogan'></span></td>
								</tr>
								</table>";
	
			$footerFoilCard = "<table style='height:60px;width:100%;font:12px Arial;'>
								<tr>
								<td style='text-align:left;'>NO. OF PRESENT</td>
								<td style='text-align:right;'>NO. OF ABSENTEES</td>
								<td style='text-align:right;padding-right:50px;'>TOTAL NO. OF STUDENTS</td>
								</tr>
								<tr><td colspan='3' style='height:50px;'></td></tr>
								<tr>
								<td colspan='3' style='height:45px;text-align:right;'>Name and Signature of Invigilator</td>
								</tr>
								</table>";
			$footerFoilCover = "<table style='height:60px;width:100%;font:12px Arial;'>
								<tr><td style='height:50px;'></td></tr>
								<tr><td style='text-align:left;height:40px;'><b>NO. OF PRESENT</b></td></tr>
								<tr><td style='text-align:left;height:40px;'><b>NO. OF ABSENTEES</b></td></tr>
								<tr><td style='text-align:left;height:40px;'><b>TOTAL NO. OF STUDENTS</b></td></tr>
								<tr><td style='height:50px;'></td></tr>
								</table>";
			if(isset($this->request->data['EA']['exam_type']) == 'A'){
				$i=1;$pageAllot = 1;
				$subQueryExpression = $this->subQuery();
				$results = $this->getArrearData($this->request->data['EA']['monthyears'], $subQueryExpression);
				$course_type_id = $this->getCourseTypeIds($this->practical_or_studio);
				$result_course_type = $this->filterResultByCourseType($results, $course_type_id);
	
				$resultsArray = array();
				foreach ($result_course_type as $result){
					if(isset($result['Student']['registration_number'])){
						$resultsArray[] =
						array(
								"registration_number" => $result['Student']['registration_number'],
								"name" => $result['Student']['name'],
								"course_code" => $result['CourseMapping']['Course']['course_code'],
								"course_name" => $result['CourseMapping']['Course']['course_name'],
								"course" => $result['Student']['Program']['Academic']['short_code'],
								"branch" => $result['Student']['Program']['short_code'],
						);
					}
				}
				$results = $this->array_MultiOrderBy($resultsArray,'branch', SORT_ASC,'course_code', SORT_ASC, 'registration_number', SORT_ASC);
	
				if($result_course_type){
					if(isset($this->request->data['foilCard']) == 'foilCard'){
						$PdfFileName = "PRACTICAL_ARREAR_FOIL-CARD_";
						foreach ($results as $result){
							if(($pageLastCName != $result['course_name']) || ($pageAllot == 26)){
								if(($pageLastCName != $result['course_name'])){
									$i=1;
								}
	
								if($pageLastCName == "FIRST"){
									$html .= $headerLogo;
								}else{
									$html .="</table>";
									$pageAllot = 1;
									$html .= $footerFoilCard;
									$html .= "<div style='page-break-after:always'></div>";
									$html .= $headerLogo;
									$eachPage++;
								}
								$html .= "<table border='1' style='width:100%;font:12px Arial;' cellpadding='0' cellspacing='0'>
										<tr><td colspan='2' align='center' style='height:30px;'>PRACTICAL - ARREAR ATTENDANCE SHEET</td></tr>
										<tr>
											<td style='height:27px;'><b>&nbsp;&nbsp;Month&Year of Exam</b>&nbsp;&nbsp;&nbsp;".$txtExamMonthYear."</td>
											<td align='right'><b>Page ".$eachPage." of TOTAL_PAGE&nbsp;&nbsp;</b></td>
										</tr>
										<tr>
											<td style='height:27px;'><b>&nbsp;&nbsp;Course Name : </b>".$result['course_name']."</td>
											<td><b>&nbsp;&nbsp;Course Code : </b>".$result['course_code']."</td>
										</tr>
										<tr>
											<td style='height:27px;'><b>&nbsp;&nbsp;Course : </b>".$result['course']."</td>
											<td><b>&nbsp;&nbsp;Branch : </b>".$result['branch']."</td>
										</tr>
										</table>";
								$html .= "<table cellpadding='0' cellspacing='0' border='1' style='text-indent:5px;font:12px Arial;width:100%'>
											<tr>
												<th style='height:27px;'>S.No.</th>
												<th>Reg No.</th>
												<th>NAME</th>
												<th>SIGNATURE OF THE STUDENT</th>
											</tr>";
							}
	
							$html .="<tr>";
							$html .="<td style='height:27px;width:40px;' align='center'>".$i."</td>";
							$html .="<td align='center' style='width:100px;'>&nbsp;&nbsp;".$result['registration_number']."</td>";
							$html .="<td align='left'>".$result['name']."</td>";
							$html .="<td style='width:240px;'></td>";
							$html .="<tr>";
							$i++;
							$pageAllot++;
							$pageLastCName = $result['course_name'];
						}
					}
						
					if(isset($this->request->data['coverPage']) == 'coverPage'){
						$PdfFileName = "PRACTICAL_ARREAR_COVER_PAGE_";
						foreach ($results as $result){
							if(($pageLastCName != $result['course_name']) || ($pageAllot == 26)){
								if(($pageLastCName != $result['course_name'])){
									$i=1;
								}
									
								if($pageLastCName == "FIRST"){
									$html .= $headerLogo;
								}else{
									$html .="</table>";
									$pageAllot = 1;
									$html .= $footerFoilCover;
									$html .= "<div style='page-break-after:always'></div>";
									$html .= $headerLogo;
									$eachPage++;
								}
								$html .= "<table border='1' style='width:100%;font:12px Arial;' cellpadding='0' cellspacing='0'>
										<tr><td colspan='2' align='center' style='height:30px;'>PRACTICAL - ARREAR ATTENDANCE SHEET</td></tr>
										<tr>
											<td style='height:27px;'><b>&nbsp;&nbsp;Month&Year of Exam</b>&nbsp;&nbsp;&nbsp;".$txtExamMonthYear."</td>
											<td align='right'><b>Page ".$eachPage." of TOTAL_PAGE&nbsp;&nbsp;</b></td>
										</tr>
										<tr>
											<td style='height:27px;'><b>&nbsp;&nbsp;Course Name : </b>".$result['course_name']."</td>
											<td><b>&nbsp;&nbsp;Course Code : </b>".$result['course_code']."</td>
										</tr>
										<tr>
											<td style='height:27px;'><b>&nbsp;&nbsp;Course : </b>".$result['course']."</td>
											<td><b>&nbsp;&nbsp;Branch : </b>".$result['branch']."</td>
										</tr>
										</table>";
								$html .= "<table><tr><td style='height:35px;'></td></tr></table>";$heightRow = 0;
							}
							if($heightRow%5 ==0 ){$html .= "<table><tr><td style='height:35px;'></td></tr></table>";}
								
							$html .= "<span style='float:left;'>";
							$html .= $result['registration_number']."&nbsp;........&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
							$html .= "</span><span style='float:clear;'></span>";
							$i++;
							$pageAllot++;$heightRow++;
							$pageLastCName = $result['course_name'];
						}$html .=$footerFoilCover;
					}
				}
			}
		}
		if($html){
			$html .="</table>";
			if(isset($this->request->data['foilCard']) == 'foilCard'){
				$html .=$footerFoilCard;
			}
			$html = str_replace('TOTAL_PAGE',$eachPage,$html);
			$this->mPDF->init();
			$this->mPDF->setFilename($PdfFileName.date('d_M_Y').'.pdf');
			$this->mPDF->setOutput('D');
			$this->mPDF->WriteHTML($html);
			$this->mPDF->SetWatermarkText("Draft");
		}
	
		$ContinuousAssessmentExamsController = new ContinuousAssessmentExamsController;
		$monthyears = $ContinuousAssessmentExamsController->findMonthYear();
	
		$courseTypes = $this->CourseType->find('list', array(
				'conditions' => array("CourseType.course_type"=>$this->practical_or_studio),
		));
	
		$this->set(compact('monthyears'));
	}
/* 	public function studentArrearDataForACourse($cm_id) {
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
							GROUP BY 2
							HAVING max( `status` ) = 'Fail'
				");
		return $result;
	} */
}
