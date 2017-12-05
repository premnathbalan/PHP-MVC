<?php
App::uses('AppController', 'Controller');
App::import('Controller', 'ContinuousAssessmentExams');
App::import('Vendor', 'PHPExcel', array('file' => 'PHPExcel'.DS.'IOFactory.php'));
App::import('Vendor', 'mPDF', array('file' => 'mPDF'.DS.'mPDF.php'));
include '../Controller/Constants.php';

class ArrearsController extends AppController {
	public $practical_or_studio = array("practical", "studio");
	public $theory = array("theory");
	public $project = array("project");
	
	/*public $resultsArray = array();
	public $marks_available = array();
	public $ese_practical_id_array = array();*/
	
	public $components = array('Paginator', 'Flash', 'Session','mPDF');
	public $uses = array("Arrear", "CourseType", "User", "StudentMark", "PracticalAttendance", "Practical", "EsePractical", 
			"InternalPractical", "CaePractical", "CourseMapping", "CourseStudentMapping", "MonthYear", "InternalExam"
	);

	public function practical() {
		//pr($this->cType);
		//$ContinuousAssessmentExamsController = new ContinuousAssessmentExamsController;
		//$monthyears = $ContinuousAssessmentExamsController->findMonthYear();
		$monthyears = $this->MonthYear->getAllMonthYears();
		//pr($monthyears);
		
		$courseTypes = $this->CourseType->find('list', array(
				'conditions' => array("CourseType.course_type"=>$this->practical_or_studio),
		));
		//pr($courseTypes);
		//pr(array_keys($courseTypes));
		$this->set(compact('monthyears'));
	}
	
	public function arrear() {
		$monthyears = $this->MonthYear->getAllMonthYears();
		//pr($monthyears);
		$this->set(compact('monthyears'));
	}
	
	public function practicalArrearData($exam_month_year_id) {
		//$exam_month_year_id = $exam_month_year_id;
		//pr(Configure::read('CourseType.practical'), ',');
		//echo implode(Configure::read('CourseType.practical'), ',');
		$course_mapping_array = array();
		/* $cm_array = $this->StudentMark->query("SELECT DISTINCT (
				sm.course_mapping_id
				), ep.id, cp.id
				FROM student_marks sm
				JOIN course_mappings cm ON cm.id = sm.course_mapping_id
				JOIN courses c ON c.id = cm.course_id
				JOIN ese_practicals ep ON ep.course_mapping_id=cm.id
				JOIN cae_practicals cp ON cp.course_mapping_id=cm.id 
				JOIN students s ON sm.student_id=s.id
				WHERE s.discontinued_status=0 
				AND
				c.course_type_id
				IN ( 2, 6 )
				AND (
				(
				sm.status = 'Fail'
				AND sm.revaluation_status =0
				)
				OR (
				sm.final_status = 'Fail'
				AND sm.revaluation_status =1
				)
				)
				AND sm.month_year_id < $exam_month_year_id
				AND cm.indicator =0 
				GROUP BY sm.course_mapping_id"
				); */
		$cm_array = $this->StudentMark->query("
				SELECT sm.id, sm.course_mapping_id, sm.month_year_id, sm.student_id, s.registration_number, c.course_code,
				c.course_type_id, s.batch_id, s.program_id, cm.batch_id, cm.program_id, cm.month_year_id, c.id, ep.id, cp.id
				FROM student_marks sm
				JOIN students s ON sm.student_id=s.id
				JOIN course_mappings cm ON sm.course_mapping_id=cm.id
				JOIN courses c ON cm.course_id=c.id 
				JOIN ese_practicals ep ON ep.course_mapping_id=cm.id
				JOIN cae_practicals cp ON cp.course_mapping_id=cm.id 
				WHERE sm.id IN
				(SELECT max( id ) FROM student_marks sm1 WHERE sm.student_id =sm1.student_id AND s.discontinued_status=0 
				AND sm1.month_year_id < $exam_month_year_id
				GROUP BY sm1.course_mapping_id, sm1.student_id ORDER BY id DESC)
				AND ((sm.status='Fail' AND sm.revaluation_status=0) OR (sm.final_status='Fail' AND sm.revaluation_status=1))
				AND c.course_type_id in (".implode(Configure::read('CourseType.practical'), ',').")
				AND sm.month_year_id < $exam_month_year_id 
				AND s.discontinued_status = 0 
				ORDER BY sm.course_mapping_id  ASC
				");
//		pr($cm_array);
		/* $dbo = $this->CourseMapping->getDatasource();
		$logs = $dbo->getLog();
		$lastLog = end($logs['log']);
		echo $lastLog['query']; */
		//die;
		foreach ($cm_array as $key => $array) {
			$course_mapping_array[$array['sm']['course_mapping_id']] =  array(
					'ese_practical_id' => $array['ep']['id'],
					'cae_practical_id' => $array['cp']['id']
			);
		}
		//echo count($cm_array);
		$csm_array = $this->CourseStudentMapping->find('all', array(
				'conditions'=>array('CourseStudentMapping.new_semester_id'=>$exam_month_year_id),
				'fields'=>array('CourseStudentMapping.course_mapping_id'),
				'contain'=>array(
						'CourseMapping'=>array('fields'=>array('CourseMapping.id'),
								'Course'=>array(
										'conditions'=>array('Course.course_type_id'=>array(2,6)),
										'fields'=>array('Course.course_type_id', 'Course.course_code', 'Course.course_name'),
								),
								'EsePractical'=>array('fields' => array('EsePractical.id', 'EsePractical.course_mapping_id',
										'EsePractical.marks'),
										'conditions'=>array('EsePractical.indicator'=>0),
								),
								'CaePractical'=>array('fields' => array('CaePractical.id', 'CaePractical.course_mapping_id',
										'CaePractical.marks'),
										'conditions'=>array('CaePractical.indicator'=>0),
								),
						),
				)
		));
		//pr($csm_array);
		foreach ($csm_array as $key => $array) {
			if (isset($array['CourseMapping']['Course']['course_type_id'])) {
				if (isset($array['CourseMapping']['EsePractical'][0]['id']) && isset($array['CourseMapping']['CaePractical'][0]['id'])) {
					$course_mapping_array[$array['CourseMapping']['id']]= array(
							'ese_practical_id' => $array['CourseMapping']['EsePractical'][0]['id'],
							'cae_practical_id' => $array['CourseMapping']['CaePractical'][0]['id']
					);
				}
			}
		}
		return $course_mapping_array;
	}
	
	public function esePracticalSearch($exam_month_year_id) {
		//echo $exam_month_year_id;
		$course_mapping_array = $this->practicalArrearData($exam_month_year_id);
		//pr($course_mapping_array);
		$this->set(compact('exam_month_year_id', 'course_mapping_array'));	
		$this->layout=false;
	}
	
	public function eseTheoryPracticalSearch($exam_month_year_id) {
		//echo $exam_month_year_id;
		$course_mapping_array = $this->theoryPracticalArrearData($exam_month_year_id);
		//pr($course_mapping_array);
		$this->set(compact('exam_month_year_id', 'course_mapping_array'));
		$this->layout=false;
	}
	
	/*public function practicalSearch($exam_month_year_id) {
		$subQueryExpression = $this->subQuery();
		$results = $this->getArrearData($exam_month_year_id, $subQueryExpression);
		$course_type_id = $this->getCourseTypeIds($this->practical_or_studio);
		$result_course_type = $this->filterResultByCourseType($results, $course_type_id);
		$this->processArrayForMarks($result_course_type, $exam_month_year_id);
		$this->getOtherData($exam_month_year_id);
		$this->set('resultsArray', $this->resultsArray);
		$this->set('marks_available', $this->marks_available);
		$this->set('ese_practical_id_array', $this->ese_practical_id_array);
		$this->set(compact('exam_month_year_id'));
		$this->layout=false;
	}*/
	
	/*public function processArrayForMarks($result_course_type, $exam_month_year_id) {
		//pr($result_course_type);
		unset($this->resultsArray);
		unset($this->marks_available);
		unset($this->ese_practical_id_array);
		
		foreach ($result_course_type as $result) {
			//if(isset($result['Student']['registration_number'])){
			$this->resultsArray[$result['Student']['batch_id']][$result['Student']['Program']['academic_id']]
			[$result['Student']['program_id']][$result['CourseMapping']['id']][$result['Student']['id']] =
			array(
					"registration_number" => $result['Student']['registration_number'],
					"name" => $result['Student']['name'],
					"course_code" => $result['CourseMapping']['Course']['course_code'],
					"course_name" => $result['CourseMapping']['Course']['course_name'],
					"course" => $result['Student']['Program']['Academic']['short_code'],
					"branch" => $result['Student']['Program']['short_code'],
					"batch_period" => $result['Student']['Batch']['batch_period'],
					"ese_practical_id" => $result['CourseMapping']['EsePractical'][0]['id'],
					"practical" => $result['Student']['Practical'],
			);
			
			$mark_exists = $this->Practical->find('count', array(
				'conditions'=>array('Practical.month_year_id'=>$exam_month_year_id, 
									'Practical.ese_practical_id'=>$result['CourseMapping']['EsePractical'][0]['id']
				)
			));
			if ($mark_exists > 0) {
				$this->marks_available[$result['CourseMapping']['id']] = 1;
			}
			else {
				$this->marks_available[$result['CourseMapping']['id']] = 0;
			}
			$this->ese_practical_id_array[$result['CourseMapping']['id']] = $result['CourseMapping']['EsePractical'][0]['id'];
		}
	}*/
	
	public function processArray($result_course_type) {
		//pr($result_course_type);
		$resultsArray = array();
		foreach ($result_course_type as $result) {
			//if(isset($result['Student']['registration_number'])){
				$resultsArray[$result['Student']['batch_id']][$result['Student']['Program']['academic_id']]
				[$result['Student']['program_id']][$result['CourseMapping']['id']][$result['Student']['id']] =
				array(
						"registration_number" => $result['Student']['registration_number'],
						"name" => $result['Student']['name'],
						"course_code" => $result['CourseMapping']['Course']['course_code'],
						"course_name" => $result['CourseMapping']['Course']['course_name'],
						"course" => $result['Student']['Program']['Academic']['short_code'],
						"branch" => $result['Student']['Program']['short_code'],
						"batch_period" => $result['Student']['Batch']['batch_period'],
						"ese_practical_id" => $result['CourseMapping']['EsePractical'][0]['id'],
				);
				
				
			//}
		}
		return $resultsArray;
	}
	
	/*public function filterResultByCourseType($results, $course_type_id) {
		//pr($results);
		$final_result=array();
		$i=0;
		foreach ($results as $key => $result) {
			if (in_array($result['CourseMapping']['Course']['course_type_id'], $course_type_id)) {
				if (isset($result['Student']['id'])) { 
					$i++;
					$final_result[] = $result;
				}
			}
		}
		return $final_result;
	}*/
	
	public function getCourseTypeIds($arg1) {
		$courseTypes = $this->CourseType->find('list', array(
				'conditions' => array("CourseType.course_type"=>$arg1),
		));
		$course_type_id = array_keys($courseTypes);
		return $course_type_id;
	}
	
	public function subQuery() {
		$db = $this->StudentMark->getDataSource();
		//echo $db->fullTableName($this->StudentMark);
		//$conditionsSubQuery['"StudentMark2"."status"'] = 'B';
		
		
		$subQuery = $db->buildStatement(
				array(
						'fields'     => array('MAX(`StudentMark2`.`id`)'),
						'table'      => $db->fullTableName($this->StudentMark),
						'alias'      => 'StudentMark2',
						'limit'      => null,
						'offset'     => null,
						'joins'      => array(),
						/* 'conditions' => $conditionsSubQuery, */
						'order'      => array('StudentMark2.id DESC'),
						'group'      => array('StudentMark2.student_id', 'StudentMark2.course_mapping_id')
				),
				$this->StudentMark
				);
		$subQuery = ' `StudentMark`.`id` IN (' . $subQuery . ') ';
		$subQueryExpression = $db->expression($subQuery);
		//pr($subQueryExpression);
		//$conditions[] = $subQueryExpression;
		//pr($subQueryExpression);
		return $subQueryExpression;
	}
	
	public function getArrearData($exam_month_year_id, $subQueryExpression) {
		$sm_month_year_id = $exam_month_year_id-1;
		//pr($sm_month_year_id);
		$result = $this->StudentMark->find('all', array(
				'conditions'=>array($subQueryExpression,
						'OR'=>array(array('StudentMark.status'=>'Fail', 'StudentMark.revaluation_status'=>0),
								array('StudentMark.final_status'=>'Fail', 'StudentMark.revaluation_status'=>1)
						),
						'StudentMark.month_year_id <' => $exam_month_year_id
				),
				'fields'=>array('StudentMark.id', 'StudentMark.student_id', 'StudentMark.course_mapping_id', 'StudentMark.marks',
						'StudentMark.status', 'StudentMark.revaluation_status', 'StudentMark.final_marks', 'StudentMark.final_status'
				),
				'order' => array('StudentMark.student_id', 'StudentMark.course_mapping_id', 'StudentMark.month_year_id'),
				'contain'=>array(
						'CourseMapping'=>array('fields'=>array('CourseMapping.id'),
								'Course'=>array(
										'fields'=>array('Course.course_code','Course.course_name'),
										'CourseType'=>array(
												'fields'=>array('CourseType.course_type')),
								),
								'EsePractical'=>array('fields' => array('EsePractical.id', 'EsePractical.course_mapping_id', 
																		'EsePractical.marks'),
										'conditions'=>array('EsePractical.indicator'=>0),
								),
						),
						'Student'=>array('fields'=>array('Student.registration_number', 'Student.name'),
								'conditions'=>array('Student.discontinued_status'=>0),
								'Batch'=>array('fields'=>array('Batch.batch_period')),
								'Program'=>array('fields'=>array('Program.program_name','Program.short_code'),
									'Academic' => array(
										'fields' => array('Academic.academic_name', 'Academic.short_code')
									),
								),
								'PracticalAttendance'=>array('fields' => array('PracticalAttendance.id', 'PracticalAttendance.attendance_status',
										'PracticalAttendance.student_id', 'PracticalAttendance.month_year_id', 'PracticalAttendance.course_mapping_id'
															),
															'conditions'=>array('PracticalAttendance.month_year_id'=>$exam_month_year_id)
								),
								'Practical' => array(
										'fields'=>array('Practical.student_id', 'Practical.marks', 'Practical.ese_practical_id'),
										'conditions'=>array('Practical.month_year_id'=>$exam_month_year_id)
								),
								'CourseStudentMapping'=>array(
										'fields'=>array('CourseStudentMapping.student_id', 'CourseStudentMapping.new_semester_id')
								)
						),
						'MonthYear'=>array(
								'fields'=>array('MonthYear.year'),
								'Month' => array('fields' => array('Month.month_name'))
						)
				)
		));
		return $result;
	}
	
	public function index($exam_month_year_id = null) {
		$access_result = $this->checkPathAccess($this->Auth->user('id'));
		if (!$access_result) {
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
			
			$monthyears = $this->MonthYear->getAllMonthYears();
			
			$courseTypes = $this->CourseType->find('list', array(
					'conditions' => array("CourseType.course_type"=>$this->practical_or_studio),
			));
			
			$this->set(compact('monthyears', 'exam_month_year_id'));
		}
		else {
			$this->render('../Users/access_denied');
		}
	}
	
	public function search($exam_month_year_id = null, $exam_type = null) {
		if($exam_type == 'A'){
			$course_mapping_array = $this->practicalArrearData($exam_month_year_id);
			//pr($course_mapping_array);
			$this->set(compact('exam_month_year_id', 'course_mapping_array'));
		}
		$this->layout=false;
	}
	
	public function edit($cm_id, $exam_month_year_id) {
		
		$details = $this->getCourseNameCrseCodeCmnCodeFromCMId($cm_id);
		//pr($details);
		$month_year = $this->MonthYear->getMonthYear($exam_month_year_id);
		
		$arr[$cm_id]=$cm_id;
		$courseMarks = $this->CourseMapping->getCourseMarks($arr);
		
		$results = $this->PracticalAttendance->find('all', array(
				'conditions'=>array('PracticalAttendance.month_year_id'=>$exam_month_year_id,
						'PracticalAttendance.course_mapping_id' => $cm_id
				),
				'fields'=>array('PracticalAttendance.id', 'PracticalAttendance.student_id', 'PracticalAttendance.attendance_status'),
				'contain'=>array(
						'Student'=>array(
								'conditions'=>array('Student.discontinued_status'=>0),
								'fields'=>array('Student.id', 'Student.registration_number', 'Student.name')
						)
				)
		));
		//pr($results);
		$attArray = array();
		foreach ($results as $key => $array) {
			if (isset($array['Student']['id'])) {
				$attArray[$array['PracticalAttendance']['student_id']] = array(
						'student_id'=>$array['PracticalAttendance']['student_id'],
						'registration_number'=>$array['Student']['registration_number'],
						'name'=>$array['Student']['name'],
						'attendance_status'=>$array['PracticalAttendance']['attendance_status']
				);
			}
		}
		
		$this->set(compact('cm_id', 'exam_month_year_id', 'attArray', 'batch_id', 'academic_id', 'program_id', 'details', 
				'month_year', 'courseMarks'));
		if ($this->request->is('post')) {
			//pr($this->data);
			$ese_practical_array = $this->EsePractical->find('first', array(
				'fields'=>array('EsePractical.id'),
				'conditions'=>array('EsePractical.course_mapping_id'=>$cm_id),
				'recursive'=>0
			));
			
			$ese_practical_id = $ese_practical_array['EsePractical']['id'];
			
			$cm_id = $this->request->data['PracticalAttendance']['course_mapping_id'];
			$month_year_id = $this->request->data['PracticalAttendance']['exam_month_year_id'];
			$attendance_array = $this->request->data['PracticalAttendance']['attendance_status'];
			foreach ($attendance_array as $student_id => $attendance_status) {
				if ($attendance_status == 1) {
					$attendance_status = 0;
				} else {
					$attendance_status = 1;
				}
				$data['PracticalAttendance']['month_year_id'] = $exam_month_year_id;
		
				$data_exists=$this->PracticalAttendance->find('first', array(
						'conditions' => array(
								'PracticalAttendance.course_mapping_id'=>$cm_id,
								'PracticalAttendance.month_year_id'=>$month_year_id,
								'PracticalAttendance.student_id'=>$student_id,
						),
						'fields' => array('PracticalAttendance.id'),
						'recursive' => 0
				));
				$data=array();
				$data['PracticalAttendance']['month_year_id'] = $month_year_id;
				$data['PracticalAttendance']['student_id'] = $student_id;
				$data['PracticalAttendance']['course_mapping_id'] = $cm_id;
				$data['PracticalAttendance']['attendance_status'] = $attendance_status;
					
				if(isset($data_exists['PracticalAttendance']['id']) && $data_exists['PracticalAttendance']['id']>0) {
					$id = $data_exists['PracticalAttendance']['id'];
					$data['PracticalAttendance']['id'] = $id;
					$data['PracticalAttendance']['modified_by'] = $this->Auth->user('id');
					$data['PracticalAttendance']['modified'] = date("Y-m-d H:i:s");
				}
				else {
					$this->PracticalAttendance->create($data);
					$data['PracticalAttendance']['created_by'] = $this->Auth->user('id');
				}
				$this->PracticalAttendance->save($data);
				
				$data['Practical']['month_year_id'] = $exam_month_year_id;
				
				$data_exists=$this->Practical->find('first', array(
						'conditions' => array(
								'Practical.ese_practical_id'=>$ese_practical_id,
								'Practical.month_year_id'=>$month_year_id,
								'Practical.student_id'=>$student_id,
						),
						'fields' => array('Practical.id'),
						'recursive' => 0
				));
				$data=array();
				$data['Practical']['month_year_id'] = $month_year_id;
				$data['Practical']['student_id'] = $student_id;
				$data['Practical']['ese_practical_id'] = $ese_practical_id;
				$data['Practical']['marks'] = 'A';
					
				if(isset($data_exists['Practical']['id']) && $data_exists['Practical']['id']>0) {
					$id = $data_exists['Practical']['id'];
					$data['Practical']['id'] = $id;
					$data['Practical']['modified_by'] = $this->Auth->user('id');
					$data['Practical']['modified'] = date("Y-m-d H:i:s");
				}
				else {
					$this->Practical->create($data);
					$data['Practical']['created_by'] = $this->Auth->user('id');
				}
				$this->Practical->save($data);
				
			}
			return $this->redirect(array('action' => 'index/'.$exam_month_year_id));
		}
	}
	
	public function editPracticalMarks($cm_id, $exam_month_year_id, $ese_practical_id, $cae_practical_id) {
		
		$markArray = $this->getPracticalMarks($ese_practical_id, $exam_month_year_id);
		//pr($markArray); 
		$cae_results = $this->getPracticalCaeMarks($cm_id, $exam_month_year_id, $markArray);
		
		$arr[$cm_id]=$cm_id;
		$courseMarks = $this->CourseMapping->getCourseMarks($arr);
		
		$details = $this->getCourseNameCrseCodeCmnCodeFromCMId($cm_id);
		//pr($details);
		
		$month_year = $this->MonthYear->getMonthYear($exam_month_year_id);
		
		$this->set(compact('cm_id', 'exam_month_year_id', 'markArray', 'ese_practical_id', 'cae_results', 'cae_practical_id',
				'courseMarks', 'details', 'month_year'));
	}
	
	public function getPracticalMarks($ese_practical_id, $exam_month_year_id) {
		$arrear_marks = $this->Practical->find('all', array(
				'conditions'=>array('Practical.month_year_id'=>$exam_month_year_id, 
						'Practical.ese_practical_id' => $ese_practical_id
				),
				'fields'=>array('Practical.id', 'Practical.student_id', 'Practical.marks'),
				'contain'=>array(
						'Student'=>array(
								'conditions'=>array('Student.discontinued_status'=>0),
								'fields'=>array('Student.id', 'Student.registration_number', 'Student.name')
						)
				)
		));
		//pr($arrear_marks);
		$marksArray = array();
		foreach ($arrear_marks as $key => $markArray) {
			if (isset($markArray['Student']['id'])) {
				$marksArray[$markArray['Practical']['student_id']] = array(
					'student_id'=>$markArray['Practical']['student_id'],
					'registration_number'=>$markArray['Student']['registration_number'],
					'name'=>$markArray['Student']['name'],
					'marks'=>$markArray['Practical']['marks']
				);
			}
		}
		//pr($marksArray); die;
		return $marksArray;
	}
	
	public function getAttendance($cm_id, $exam_month_year_id) {
		$arrear_attendance = $this->PracticalAttendance->find('all', array(
				'conditions'=>array('PracticalAttendance.month_year_id'=>$exam_month_year_id,
						'PracticalAttendance.course_mapping_id' => $cm_id
				),
				'fields'=>array('PracticalAttendance.id', 'PracticalAttendance.student_id', 'PracticalAttendance.course_mapping_id',
						'PracticalAttendance.attendance_status'
				),
				'recursive'=>0
		));
		$attendanceArray = array();
		foreach ($arrear_attendance as $key => $attArray) {
			$attendanceArray[$attArray['PracticalAttendance']['student_id']] = $attArray['PracticalAttendance']['attendance_status'];
		}
		return $attendanceArray;
	}
	
	public function add($cm_id, $exam_month_year_id) {
		$arr[$cm_id]=$cm_id;
		$courseMarks = $this->CourseMapping->getCourseMarks($arr);
		$sm_month_year_id = $exam_month_year_id-1;
		$results = $this->arrearRecords($cm_id, $exam_month_year_id);
		//pr($results);
		$modelArray = array("CourseStudentMapping", "StudentMark");
		$student_id_array = $this->getStudentIds($results, $modelArray);
		
		$details = $this->getCourseNameCrseCodeCmnCodeFromCMId($cm_id);
		//pr($details);
		$month_year = $this->MonthYear->getMonthYear($exam_month_year_id);
		
		$this->set(compact('student_id_array', 'cm_id', 'exam_month_year_id', 'courseMarks', 'details', 'month_year'));
		
		if ($this->request->is('post')) {
			//pr($this->data); die;
			$cm_id = $this->request->data['PracticalAttendance']['course_mapping_id'];
			$month_year_id = $this->request->data['PracticalAttendance']['exam_month_year_id'];
			$attendance_array = $this->request->data['PracticalAttendance']['attendance_status'];
			foreach ($attendance_array as $student_id => $attendance_status) {
				if ($attendance_status == 1) {
					$attendance_status = 0;
				} else {
					$attendance_status = 1;
				}
					$data['PracticalAttendance']['month_year_id'] = $exam_month_year_id;
					
					$data_exists=$this->PracticalAttendance->find('first', array(
							'conditions' => array(
									'PracticalAttendance.course_mapping_id'=>$cm_id,
									'PracticalAttendance.month_year_id'=>$month_year_id,
									'PracticalAttendance.student_id'=>$student_id,
							),
							'fields' => array('PracticalAttendance.id'),
							'recursive' => 0
					));
					$data=array();
					$data['PracticalAttendance']['month_year_id'] = $month_year_id;
					$data['PracticalAttendance']['student_id'] = $student_id;
					$data['PracticalAttendance']['course_mapping_id'] = $cm_id;
					$data['PracticalAttendance']['attendance_status'] = $attendance_status;
						
					if(isset($data_exists['PracticalAttendance']['id']) && $data_exists['PracticalAttendance']['id']>0) {
						$id = $data_exists['PracticalAttendance']['id'];
						$data['PracticalAttendance']['id'] = $id;
						$data['PracticalAttendance']['modified_by'] = $this->Auth->user('id');
						$data['PracticalAttendance']['modified'] = date("Y-m-d H:i:s");
					}
					else {
						$this->PracticalAttendance->create($data);
						$data['PracticalAttendance']['created_by'] = $this->Auth->user('id');
					}
					$this->PracticalAttendance->save($data);
				//}
			}
			return $this->redirect(array('action' => 'index/'.$exam_month_year_id));
		}
	}
	
	public function arrearRecords($cm_id, $exam_month_year_id) {
		$results = array();
		//$exam_month_year_id = $exam_month_year_id+1;
		$sm_array = $this->listArrearStudents($exam_month_year_id, $cm_id);
		//pr($sm_array);
		
		$results['StudentMark']=$sm_array;
		$csm_array = $this->listNonArrearStudents($exam_month_year_id, $cm_id);
		//pr($csm_array);
		
		$results['CourseStudentMapping']=$csm_array;
		//pr($results);
		return $results;
	}
	
	public function addPracticalMarks($cm_id, $exam_month_year_id, $ese_practical_id, $cae_practical_id) {
		
		$results = $this->arrearRecords($cm_id, $exam_month_year_id);
		//pr($results);
		
		$modelArray = array("CourseStudentMapping", "StudentMark");
		$student_id_array = $this->getStudentIds($results, $modelArray); 
		//pr($student_id_array); 
		$arr[$cm_id]=$cm_id;
		$courseMarks = $this->CourseMapping->getCourseMarks($arr);
		
		$attendanceArray = $this->getAttendance($cm_id, $exam_month_year_id);
		//pr($attendanceArray);
		
		$cae_results = $this->getPracticalCaeMarks($cm_id, $exam_month_year_id, $student_id_array);
		//pr($cae_results);
		
		$details = $this->getCourseNameCrseCodeCmnCodeFromCMId($cm_id);
		//pr($details);
		
		$month_year = $this->MonthYear->getMonthYear($exam_month_year_id);
		$this->set(compact('cm_id', 'exam_month_year_id', 'ese_practical_id', 'attendanceArray', 'cae_results', 
				'courseMarks', 'results', 'student_id_array', 'cae_practical_id', 'details', 'month_year'));
	}
	
	public function addTheoryPracticalMarks($cm_id, $exam_month_year_id, $ese_practical_id) {
	
		$results = $this->arrearRecords($cm_id, $exam_month_year_id);
		//pr($results);
	
		$modelArray = array("CourseStudentMapping", "StudentMark");
		$student_id_array = $this->getStudentIds($results, $modelArray);
		//pr($student_id_array);
		$arr[$cm_id]=$cm_id;
		$courseMarks = $this->CourseMapping->getCourseMarks($arr);
	
		$attendanceArray = $this->getAttendance($cm_id, $exam_month_year_id);
		//pr($attendanceArray);
	
		$cae_results = $this->getTheoryCaeMarks($cm_id, $exam_month_year_id, $student_id_array);
		//pr($cae_results);
	 
		$details = $this->getCourseNameCrseCodeCmnCodeFromCMId($cm_id);
		//pr($details);
	
		$month_year = $this->MonthYear->getMonthYear($exam_month_year_id);
		$this->set(compact('cm_id', 'exam_month_year_id', 'ese_practical_id', 'attendanceArray', 'cae_results', 
				'courseMarks', 'results', 'student_id_array', 'cae_practical_id', 'details', 'month_year'));
	}
	public function getStudentIds($results, $modelArray) {
		//pr($results);
		$student_id_array=array();
		foreach ($results as $model => $modelArray) {
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
	
	/* public function getStudentIds($results) {
		$student_id_array = array();
		foreach ($results as $key => $value) {
			//$student_id = $value['student_id'];
			$student_id_array[$value['StudentMark']['student_id']] = array(
					'registration_number'=>$value['Student']['registration_number'],
					'name'=>$value['Student']['name'],
			);
		}
		return $student_id_array;
	} */
	
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
						'order'=>array('InternalPractical.month_year_id DESC', 'InternalPractical.id DESC'),
						'Student'=>array(
								'conditions'=>array('Student.discontinued_status'=>0),
								'fields'=>array('Student.id')
						)
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
	
	public function getTheoryCaeMarks($cm_id, $exam_month_year_id, $student_id_array) {
		$cae_array = array();
		foreach ($student_id_array as $student_id => $value) {
			$results = $this->InternalExam->find('first', array(
					'conditions'=>array('InternalExam.course_mapping_id'=>$cm_id, 'InternalExam.student_id'=>$student_id),
					'fields'=>array('InternalExam.id', 'InternalExam.student_id', 'InternalExam.month_year_id',
											'InternalExam.marks'
									),
					'limit'=>1,
					'order'=>array('InternalExam.month_year_id DESC', 'InternalExam.id DESC'),
					'contain' => array(
							'Student'=>array(
									'conditions'=>array('Student.discontinued_status'=>0),
									'fields'=>array('Student.id')
							),
					)
			));
			//pr($results);
			
			if (isset($results['InternalExam'])) {
				if (($results['InternalExam']['marks']=='A') || ($results['InternalExam']['marks']=='a')) {
					$cae_array[$student_id]['marks'] = '';
				}
				else {
					$cae_array[$student_id]['marks'] = $results['InternalExam']['marks'];
				}
				$cae_array[$student_id]['internal_exam_id'] = $results['InternalExam']['id'];
			}
		}
		return $cae_array;
	}
	
	public function saveCAEPracticalMarks($CaeOldMark = null, $cae_practical_id = null, $markEntry = null, $student_id = null, $month_year_id = null){
		if ($CaeOldMark <> $markEntry) {
			$cae_data_exists=$this->InternalPractical->find('first', array(
					'conditions' => array(
							'InternalPractical.cae_practical_id'=>$cae_practical_id,
							'InternalPractical.month_year_id'=>$month_year_id,
							'InternalPractical.student_id'=>$student_id,
					),
					'fields' => array('InternalPractical.id'),
					'recursive' => 0
			));
			$data=array();
			$data['InternalPractical']['month_year_id'] = $month_year_id;
			$data['InternalPractical']['student_id'] = $student_id;
			$data['InternalPractical']['cae_practical_id'] = $cae_practical_id;
			$data['InternalPractical']['marks'] = $markEntry;
			if(isset($cae_data_exists['InternalPractical']['id']) && $cae_data_exists['InternalPractical']['id']>0) {
				$id = $cae_data_exists['InternalPractical']['id'];
				$data['InternalPractical']['id'] = $id;
				$data['InternalPractical']['modified_by'] = $this->Auth->user('id');
				$data['InternalPractical']['modified'] = date("Y-m-d H:i:s");
			}
			else {
				$this->InternalPractical->create($data);
				$data['InternalPractical']['created_by'] = $this->Auth->user('id');
			}
			$this->InternalPractical->save($data);
		}
	}
	
	public function saveESEPracticalMarks($ese_practical_id = null, $marks = null, $student_id = null, $month_year_id = null){
		$data_exists=$this->Practical->find('first', array(
				'conditions' => array(
						'Practical.ese_practical_id'=>$ese_practical_id,
						'Practical.month_year_id'=>$month_year_id,
						'Practical.student_id'=>$student_id,
				),
				'fields' => array('Practical.id'),
				'recursive' => 0
		));
		$data=array();
		$data['Practical']['month_year_id'] = $month_year_id;
		$data['Practical']['student_id'] = $student_id;
		$data['Practical']['ese_practical_id'] = $ese_practical_id;
		$data['Practical']['marks'] = $marks;
			
		if(isset($data_exists['Practical']['id']) && $data_exists['Practical']['id']>0) {
			$id = $data_exists['Practical']['id'];
			$data['Practical']['id'] = $id;
			$data['Practical']['modified_by'] = $this->Auth->user('id');
			$data['Practical']['modified'] = date("Y-m-d H:i:s");
		}
		else {
			$this->Practical->create($data);
			$data['Practical']['created_by'] = $this->Auth->user('id');
		}
		$this->Practical->save($data);
	}
	
	/*public function getOtherData($exam_month_year_id) {
		//$course_type_id = $this->getCourseTypeIds($this->practical_or_studio);
		$results = $this->CourseStudentMapping->find('all', array(
				'conditions'=>array('CourseStudentMapping.new_semester_id'=>$exam_month_year_id),
				'fields'=>array('CourseStudentMapping.student_id', 'CourseStudentMapping.new_semester_id',
						'CourseStudentMapping.student_id', 'CourseStudentMapping.course_mapping_id',
	
				),
				'contain'=>array(
						'Student'=>array(
								'fields'=>array('Student.registration_number', 'Student.name'),
								'Batch'=>array('fields'=>array('Batch.batch_period')),
								'Program'=>array('fields'=>array('Program.program_name','Program.short_code'),
										'Academic' => array(
												'fields' => array('Academic.academic_name', 'Academic.short_code')
										),
								),
								'PracticalAttendance'=>array('fields' => array('PracticalAttendance.id', 'PracticalAttendance.attendance_status',
										'PracticalAttendance.student_id', 'PracticalAttendance.month_year_id', 'PracticalAttendance.course_mapping_id'
								),
										'conditions'=>array('PracticalAttendance.month_year_id'=>$exam_month_year_id)
								),
								'Practical' => array(
										'fields'=>array('Practical.student_id', 'Practical.marks', 'Practical.ese_practical_id'),
										'conditions'=>array('Practical.month_year_id'=>$exam_month_year_id)
								),
						),
						'CourseMapping'=>array('fields'=>array('CourseMapping.id'),
								'Course'=>array(
										'conditions'=>array('Course.course_type_id'=>array(2,6)),
										'fields'=>array('Course.course_type_id', 'Course.course_code', 'Course.course_name'),
								),
								'EsePractical'=>array('fields' => array('EsePractical.id', 'EsePractical.course_mapping_id',
										'EsePractical.marks'),
										'conditions'=>array('EsePractical.indicator'=>0),
								),
						),
	
				)
		));
		//pr($results);
		$finalArray=array();
		$resultsArray = array();
		$ese_practical_id_array = array();
		$marks_available = array();
		
		foreach ($results as $result) {
			if(isset($result['CourseMapping']['Course']['course_type_id'])) {
				$resultsArray[$result['Student']['batch_id']][$result['Student']['Program']['academic_id']]
				[$result['Student']['program_id']][$result['CourseMapping']['id']][$result['Student']['id']] =
				array(
						"registration_number" => $result['Student']['registration_number'],
						"name" => $result['Student']['name'],
						"course_code" => $result['CourseMapping']['Course']['course_code'],
						"course_name" => $result['CourseMapping']['Course']['course_name'],
						"course" => $result['Student']['Program']['Academic']['short_code'],
						"branch" => $result['Student']['Program']['short_code'],
						"batch_period" => $result['Student']['Batch']['batch_period'],
						"ese_practical_id" => $result['CourseMapping']['EsePractical'][0]['id'],
						"practical" => $result['Student']['Practical'],
				);
					
				$mark_exists = $this->Practical->find('count', array(
						'conditions'=>array('Practical.month_year_id'=>$exam_month_year_id,
								'Practical.ese_practical_id'=>$result['CourseMapping']['EsePractical'][0]['id']
						)
				));
				if ($mark_exists > 0) {
					$marks_available[$result['CourseMapping']['id']] = 1;
				}
				else {
					$marks_available[$result['CourseMapping']['id']] = 0;
				}
				$ese_practical_id_array[$result['CourseMapping']['id']] = $result['CourseMapping']['EsePractical'][0]['id'];
				
			}
		}
		$finalArray['resultsArray'] = $resultsArray;
		$finalArray['marks_available'] = $marks_available;
		$finalArray['ese_practical_id_array'] = $ese_practical_id_array;
		return $finalArray;
	} */
	
	public function practical_attendance_sheet($param,  $cm_id, $exam_month_year_id) {
		$txtMonthYears = $this->MonthYear->getMonthYear($exam_month_year_id);
		$details = $this->CourseMapping->getBatchAcademicProgramFromCmId($cm_id);
		
		$academic_name = $details[0]['Program']['Academic']['short_code'];
		$program_name = $details[0]['Program']['short_code'];
		$course_code = $details[0]['Course']['course_code'];
		$course_name = $details[0]['Course']['course_name'];
		$academic="";
		if ($details[0]['Batch']['academic']=='JUN') $academic="A";
		
		$batch = $details[0]['Batch']['batch_from'].'-'.$details[0]['Batch']['batch_to'];
		
		$results = $this->arrearRecords($cm_id, $exam_month_year_id);
		//pr($results);
		$modelArray = array("CourseStudentMapping", "StudentMark");
		$student_id_array = $this->getStudentIds($results, $modelArray);
		//pr($student_id_array);
		
		
		
		$assessment_type = "ESE";
		$headerHtml = '<table border="0" align="center" cellpadding="0" cellspacing="0" style="font:14px Arial;width:100%;background-color:#ffffff;">
									<tr>
									<td rowspan="2" align="right" width="35%"><img src="../webroot/img/user.jpg"></td>
										<td align="left" width="65%">&nbsp;&nbsp;SATHYABAMA UNIVERSITY<br/>
										<span class="slogan"></span></td>
									</tr>
									</table>
					<table border="1" cellpadding="0" cellspacing="0" style="width:100%;background-color:#ffffff;font:12px Arial;">
						<tr><td colspan="4" align="center">&nbsp;&nbsp;ATTENDANCE SHEET</td></tr>
						<tr>
							<td style="height:27px;"><b>&nbsp;M&Y of Exam</b></td>
							<td>&nbsp;'.$txtMonthYears.'</td>
							<td><b>&nbsp;Academic </b></td>
							<td>&nbsp;'.$academic_name.'</td>
						</tr>
						<tr>
							<td style="height:27px;"><b>&nbsp;Program</b></td>
							<td>&nbsp;'.$program_name.'</td>
							<td><b>&nbsp;Course Code<b></td>
							<td>&nbsp;'.$course_code.'</td>
						</tr>
						<tr>
							<td style="height:27px;"><b>&nbsp;Batch</b></td>
							<td>&nbsp;'.$batch.'</td>
							<td><b>&nbsp;Course Name</b></td>
							<td>&nbsp;'.$course_name.'</td>
						</tr>
					</table>
					<table cellpadding="0" cellspacing="0" border="1" style="border-top:none;width:100%;background-color:#ffffff;font:12px Arial;">
						<thead>
						<tr style="height:50px;">
						<th><b>S.No.</b></th>
						<th><b>Reg No.&nbsp;&nbsp;</b></th>
						<th style="width:300px;"><b>Student Name</b></th>
						<th><b>Answer<br/>Sheet No.</b></th>
						<th style="width:150px;"><b>Signature</b></th>
						</tr>
						</thead>
					<tbody>';
		//echo $headerHtml;
		
		$footerHtml ='</tbody>
					</table>
					<table class="attendanceHeadTblP" style="height:60px;width:100%;background-color:#ffffff;font:12px Arial;">
					<tr>
					<td style="text-align:left;width:50%;">Date</td>
				    <td style="text-align:right;width:50%;">Name and Signature of Invigilator</td>
					</tr>
					<tr><td colspan="2" style="height:45px;"></td></tr>
					</table>';
		$sno =1;
		$pageAllot = 1;
		$bodyHtml="";
		$bodyHtml .=$headerHtml;
		foreach($student_id_array as $student_id => $value){ 
			if($pageAllot == 26){
				$bodyHtml .=$footerHtml."<div style='page-break-after:always'></div>".$headerHtml;
				$pageAllot = 1;
			}
			$bodyHtml .='<tr class="gradeX">
							<td align="center" style="height:27px;">'.$sno.'</td>
							<td align="center">'.$value['registration_number'].'</td>
							<td align="left">&nbsp;'.$value['name'].'</td>
							<td align="center"></td>
							<td align="center"></td>
							</tr>';
			$sno++;$pageAllot++;
		}
		$bodyHtml .=$footerHtml;
		$this->layout = false;
		$this->autoRender = false;
		$PdfFileName = "Attendance_sheet_".$course_code."_".$academic_name."_".$program_name;
		$this->mPDF->init();
		$this->mPDF->setFilename($PdfFileName.'.pdf');
		$this->mPDF->setOutput('D');
		$this->mPDF->WriteHTML($bodyHtml);
		$this->mPDF->SetWatermarkText("Draft");
		
		return false;
		
	}
	
	public function practical_foil_card($param,  $cm_id, $exam_month_year_id) {
		$txtMonthYears = $this->MonthYear->getMonthYear($exam_month_year_id);
		$details = $this->CourseMapping->getBatchAcademicProgramFromCmId($cm_id);
		
		$academic_name = $details[0]['Program']['Academic']['short_code'];
		$program_name = $details[0]['Program']['short_code'];
		$course_code = $details[0]['Course']['course_code'];
		$course_name = $details[0]['Course']['course_name'];
		$academic="";
		if ($details[0]['Batch']['academic']=='JUN') $academic="A";
		
		$batch = $details[0]['Batch']['batch_from'].'-'.$details[0]['Batch']['batch_to'];
		
		$results = $this->arrearRecords($cm_id, $exam_month_year_id);
		//pr($results);
		$modelArray = array("CourseStudentMapping", "StudentMark");
		$student_id_array = $this->getStudentIds($results, $modelArray);
		//pr($student_id_array);
		$bodyHtml = "";
		$footerHtml = "";
		$headerHtmlBegin ='<table border="0" cellpadding="0" cellspacing="0" style="background-color:#ffffff;"><tr><td style="top:0;" border="0" cellpadding="0" cellspacing="0" valign="top">';
				$headerHtml = '<table border="1" cellpadding="0" cellspacing="0" style="font:12px Arial;top:0;width:100%;background-color:#ffffff;">
									<tr>
									<td rowspan="2" align="right"><img src="../webroot/img/user.jpg"></td>
										<td colspan="3" align="left">&nbsp;&nbsp;SATHYABAMA UNIVERSITY<br/>
										<span class="slogan"></span></td>
									</tr>
						<tr><td colspan="4" align="left">&nbsp;&nbsp;FOIL CARD</td></tr>
						<tr>
							<td style="height:33px;"><b>&nbsp;M&Y of Exam</b></td>
							<td>&nbsp;'.$txtMonthYears.'</td>
							<td><b>&nbsp;Academic </b></td>
							<td>&nbsp;'.$academic_name.'</td>
						</tr>
						<tr>
							<td style="height:33px;"><b>&nbsp;Program</b></td>
							<td>&nbsp;'.$program_name.'</td>
							<td><b>&nbsp;Course Code<b></td>
							<td>&nbsp;'.$course_code.'</td>
						</tr>
						<tr>
							<td style="height:33px;"><b>&nbsp;Batch</b></td>
							<td>&nbsp;'.$batch.'</td>
							<td><b>&nbsp;Course Name</b></td>
							<td>&nbsp;'.$course_name.'</td>
						</tr>
						<tr style="height:50px;">
							<td align="center"><b>S.No.</b></td>
							<td colspan="2" align="center"><b>Reg No.&nbsp;&nbsp;</b></td>
							<td align="center"><b>Mark</b></td>
						</tr>';
				$footerHtml ='
					<tr>
					<td style="text-align:left;height:60px;border-right:none;" valign="top">Date</td>
					<td colspan="3" style="text-align:right;height:60px;border-left:none;" valign="top">Name and Signature of Examiner</td>
					</tr>
					</table>';
				$sno =1;$cnt = 1;
				$bodyHtml .=$headerHtmlBegin.$headerHtml;
				foreach($student_id_array as $student_id => $value){
					$bodyHtml .='<tr class="gradeX">
							<td align="center" valign="top">'.$cnt.'</td>
							<td colspan="2" align="center" style="height:30px;">'.$value['registration_number'].'</td>
							<td align="center"></td>
							</tr>';
					$sno++;$cnt++;
					
					if($sno == 51){$sno =1;
						$bodyHtml .=$footerHtml."</td></tr></table><div style='page-break-after:always'></div>".$headerHtmlBegin.$headerHtml;
					}
					if($sno == 26){
						$bodyHtml .=$footerHtml."</td><td style='padding-left:15px;vertical-align:top;'>".$headerHtml;
					}
				}
				$bodyHtml .=$footerHtml."</td></tr></table>";			
				$PdfFileName = "Foil_card_".$course_code."_".$academic_name."_".$program_name;
				$this->layout=false;
				$this->autoRender = false;
				$this->mPDF->init();
				$this->mPDF->setFilename($PdfFileName.'.pdf');
				$this->mPDF->setOutput('D');
				$this->mPDF->WriteHTML($bodyHtml);
				$this->mPDF->SetWatermarkText("Draft");				
				return false;
	}
	
	public function tpIndex() {
		$access_result = $this->checkPathAccess($this->Auth->user('id'));
		if (!$access_result) {
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
				
			$monthyears = $this->MonthYear->getAllMonthYears();
				
			$courseTypes = $this->CourseType->find('list', array(
					'conditions' => array("CourseType.course_type"=>$this->practical_or_studio),
			));
				
			$this->set(compact('monthyears', 'exam_month_year_id'));
		}
		else {
			$this->render('../Users/access_denied');
		}
	}
	
	public function tpSearch($exam_month_year_id = null, $exam_type = null) {
		if($exam_type == 'A'){
			$course_mapping_array = $this->theoryPracticalArrearData($exam_month_year_id);
			//pr($course_mapping_array);
			$this->set(compact('exam_month_year_id', 'course_mapping_array'));
		}
		$this->layout=false;
	}
	
	public function theoryPracticalArrearData($exam_month_year_id) {
		$course_mapping_array = array();
				
		$cm_array = $this->StudentMark->query("
				SELECT sm.id, sm.course_mapping_id, sm.month_year_id, sm.student_id, s.registration_number, c.course_code,
				c.course_type_id, s.batch_id, s.program_id, cm.batch_id, cm.program_id, cm.month_year_id, c.id, ep.id, ie.id 
				FROM student_marks sm
				JOIN students s ON sm.student_id=s.id
				JOIN course_mappings cm ON sm.course_mapping_id=cm.id
				JOIN courses c ON cm.course_id=c.id 
				JOIN ese_practicals ep ON ep.course_mapping_id=cm.id
				JOIN internal_exams ie ON ie.course_mapping_id=cm.id 
				WHERE sm.id IN
				(SELECT max( id ) FROM student_marks sm1 WHERE sm.student_id =sm1.student_id AND s.discontinued_status=0
				AND sm1.month_year_id < $exam_month_year_id
				GROUP BY sm1.course_mapping_id, sm1.student_id ORDER BY id DESC)
				AND ((sm.status='Fail' AND sm.revaluation_status=0) OR (sm.final_status='Fail' AND sm.revaluation_status=1))
				AND c.course_type_id in (3)
				AND sm.month_year_id < $exam_month_year_id 
				AND cm.id IN (1260, 1555) 
				AND s.discontinued_status = 0
				ORDER BY sm.course_mapping_id  ASC
				");
		//		pr($cm_array);
		/* $dbo = $this->CourseMapping->getDatasource();
		$logs = $dbo->getLog();
		$lastLog = end($logs['log']);
		echo $lastLog['query']; */
		$this->autoLayout = false;
		
		foreach ($cm_array as $key => $array) {
			$course_mapping_array[$array['sm']['course_mapping_id']] =  array(
					'ese_practical_id' => $array['ep']['id'],
			);
		}
		//echo count($cm_array);
		/* $csm_array = $this->CourseStudentMapping->find('all', array(
				'conditions'=>array('CourseStudentMapping.new_semester_id'=>$exam_month_year_id),
				'fields'=>array('CourseStudentMapping.course_mapping_id'),
				'contain'=>array(
						'CourseMapping'=>array('fields'=>array('CourseMapping.id'),
								'Course'=>array(
										'conditions'=>array('Course.course_type_id'=>array(3)),
										'fields'=>array('Course.course_type_id', 'Course.course_code', 'Course.course_name'),
								),
								'EsePractical'=>array('fields' => array('EsePractical.id', 'EsePractical.course_mapping_id',
										'EsePractical.marks'),
										'conditions'=>array('EsePractical.indicator'=>0),
								),
								'InternalExam'=>array('fields' => array('InternalExam.id', 'InternalExam.course_mapping_id',
										'InternalExam.marks'),
								),
						),
				)
		));
		//pr($csm_array);
		foreach ($csm_array as $key => $array) {
			if (isset($array['CourseMapping']['Course']['course_type_id'])) {
				if (isset($array['CourseMapping']['EsePractical'][0]['id'])) {
					$course_mapping_array[$array['CourseMapping']['id']]= array(
							'ese_practical_id' => $array['CourseMapping']['EsePractical'][0]['id'],
					);
				}
			}
		} */
		//pr($course_mapping_array);
		return $course_mapping_array;
	}
	
	public function tpArrear() {
		$monthyears = $this->MonthYear->getAllMonthYears();
		//pr($monthyears);
		$this->set(compact('monthyears'));
	}
}
