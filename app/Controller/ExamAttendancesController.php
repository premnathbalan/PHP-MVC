<?php
App::uses('AppController', 'Controller');
App::import('Controller', 'ContinuousAssessmentExams');
App::import('Controller', 'TheoryArrears');
App::import('Vendor', 'mPDF', array('file' => 'mPDF'.DS.'mPDF.php'));
/**
 * ExamAttendances Controller
 *
 * @property ExamAttendance $ExamAttendance
 * @property PaginatorComponent $Paginator
 */
class ExamAttendancesController extends AppController {
	public $cType = "theory";
	public $uses = array("ExamAttendance", "ContinuousAssessmentExam", "Timetable", "EsePractical", "CourseStudentMapping", 
			"Course", "User", "Batch", "CourseFaculty", "Student", "Academic", "CaePractical", "Project", "Practical", 
			"CaeProject", "GrossAttendance", "Cae", "CourseMode", "CourseMapping", "MonthYear", "Attendance", "InternalExam", 
			"Program", "CourseType", "InternalExam", "StudentMark");
/**
 * Components
 *
 * @var array
 */
	public $components = array('Paginator', 'Flash', 'Session','mPDF');

/**
 * index method
 *
 * @return void
 */
	public function getExamMonthYear($EMId = null){
		$conditions['MonthYear.id']=$EMId;
		$month_year = $this->MonthYear->find("all", array(
				'conditions'=>$conditions,
				'fields' => array('MonthYear.month_id','MonthYear.year', 'Month.month_name'),
				'recursive' => 0
		));
		$monthYear = $month_year[0]['Month']['month_name']." - ".$month_year[0]['MonthYear']['year'];
		//pr($month_year);
		return $monthYear;
	}
	
	public function array_orderby(){
	    $args = func_get_args();
	    $data = array_shift($args);
	    foreach ($args as $n => $field) {
	        if (is_string($field)) {
	            $tmp = array();
	            foreach ($data as $key => $row)
	                $tmp[$key] = $row[$field];
	            $args[$n] = $tmp;
	            }
	    }
	    $args[] = &$data;
	    call_user_func_array('array_multisort', $args);
	    return array_pop($args);
	}
	public function totStrength($a, $b){
		  return array_search(trim($a['course_code']), $this->totWeight) - array_search(trim($b['course_code']), $this->totWeight);
	}
	
	public function ExamRegSort($a, $b) {
		return $a['registration_number'] - $b['registration_number'];
	}
	
	public function group_assoc($array, $key) {
		$return = array();
		foreach($array as $v) {
			$return[$v[$key]][] = $v;
		}
		return $return;
	}
	public function index() { 
		$access_result = $this->checkPathAccess($this->Auth->user('id'));
		if (!$access_result) {
//pr($this->request->data);
		if(isset($this->request->data['foilCard']) == 'foilCard' || isset($this->request->data['coverPage']) == 'coverPage' 
			|| isset($this->request->data['print']) == 'print' || isset($this->request->data['foilCardExcel']) == 'foilCardExcel'){
				 	$exam_type = $this->request->data['EA']['exam_type'];
				 	if ($exam_type == 'R') $exam_type = "[Regular]";
				 	else if ($exam_type == 'A') $exam_type = "[Arrear]";
				 	$this->set(compact('month_year_id',$this->request->data['EA']['monthyears']));
				 	$this->set(compact('exam_date',$this->request->data['exam_date']));
				 	$this->set(compact('exam_session',$this->request->data['EA']['exam_session']));
				 	$this->set(compact('exam_type',$this->request->data['EA']['exam_type']));
				 	
				 	$txtExamMonthYear = $this->getExamMonthYear($this->request->data['EA']['monthyears']);
				 	
				 	$this->set(compact('txtExamMonthYear'));
				 	
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
			$footerCoverFoilCard = "<table style='height:60px;width:100%;font:12px Arial;'>
								<tr><td style='height:50px;'></td></tr>
								<tr><td style='text-align:left;height:40px;'><b>NO. OF PRESENT</b></td></tr>
								<tr><td style='text-align:left;height:40px;'><b>NO. OF ABSENTEES</b></td></tr>
								<tr><td style='text-align:left;height:40px;'><b>TOTAL NO. OF STUDENTS</b></td></tr>
								<tr><td style='height:50px;'></td></tr>
								</table>";
			$month_year_id = $this->request->data['EA']['monthyears'];
			$conditions=array();
			
			if($this->request->data['EA']['monthyears']){
				$conditions['Timetable.month_year_id'] = $this->request->data['EA']['monthyears'];
			}
			if($this->request->data['EA']['exam_type']){
				$conditions['Timetable.exam_type'] = $this->request->data['EA']['exam_type'];
				//$this->set('exam_type', $this->request->data['EA']['exam_type']);
			}
			if($this->request->data['EA']['exam_session']){
				$conditions['Timetable.exam_session'] = $this->request->data['EA']['exam_session'];
				$this->set('exam_session', $this->request->data['EA']['exam_session']);
			}
			if($this->request->data['exam_date']){
				$conditions['Timetable.exam_date'] = $this->request->data['exam_date'];
				$this->set('exam_date', $this->request->data['exam_date']);
			}
			$result = array(
					'conditions' => $conditions,
					'fields' =>array('Timetable.exam_date'),
					'contain'=>array(
							'CourseMapping'=>array('fields'=>array('CourseMapping.id',  'CourseMapping.program_id'),	
								'CourseStudentMapping' =>array('fields' =>array('CourseStudentMapping.new_semester_id'),
										'conditions'=>array('CourseStudentMapping.type !=' => 'N'),
								),
							),
					),			
					'recursive' => 2
			);
			$results = $this->Timetable->find("all", $result); 
			//pr($results);
			$getCMId = array();//$getOtherDataArray = array();
			if($results){
				foreach($results as $key=>$val){
					if(isset($val['CourseMapping'])){
						$getCMId[] =$val['CourseMapping']['id'];
					}
					//if(isset($val['CourseStudentMapping'][0])){
						//$getOtherDataArray[] =$val['CourseStudentMapping'][0]['course_mapping_id'];
					//}
				}
			}
			//pr($getCMId);
			
			if($this->request->data['EA']['exam_type'] == 'R' || $this->request->data['EA']['exam_type'] == '-') {
				$result = array(
						'conditions' => array('CourseStudentMapping.course_mapping_id' => $getCMId,
								'CourseStudentMapping.indicator' => 0,'CourseStudentMapping.new_semester_id' => null,
						),
						'fields' =>array('CourseStudentMapping.id','CourseStudentMapping.student_id'),
						'contain'=>array(
							'Student'=>array('fields' =>array('Student.id','Student.name','Student.registration_number','Student.user_initial'),
								'conditions' => array('Student.discontinued_status' => 0),
								'order'=>array('Student.registration_number'),
							),
							'CourseMapping'=>array('fields' =>array('CourseMapping.id'),
								'Course'=>array('fields' =>array('Course.course_code','Course.course_name'),'order'=>array('Course.common_code'),),
								'Program'=>array('fields' =>array('Program.program_name', 'Program.short_code'),
									'Academic'=>array('fields' =>array('Academic.academic_name', 'Academic.short_code')),
								),
								'Batch'=>array('fields' =>array('Batch.batch_from','Batch.batch_to','Batch.academic')),
							),
						),					
						'recursive' => 1
				);
				$results = $this->CourseStudentMapping->find("all", $result);
				//pr($results);
				
				$resultsArray = array();
				foreach ($results as $result){
					if(isset($result['Student']['registration_number'])){
						$resultsArray[] =
						array(
							"registration_number" => $result['Student']['registration_number'],
							"name" => $result['Student']['name'],
							"course_code" => $result['CourseMapping']['Course']['course_code'],
							"course_name" => $result['CourseMapping']['Course']['course_name'],
							"program" => $result['CourseMapping']['Program']['program_name'],
							"batch" => $result['CourseMapping']['Batch']['batch_from']." - ".$result['CourseMapping']['Batch']['batch_to'],
							"branch" => $result['CourseMapping']['Program']['short_code'],
							"course" => $result['CourseMapping']['Program']['Academic']['short_code'],
						);						
					}
				}
				//pr($resultsArray);
				$results = $this->array_orderby($resultsArray, 'course_code', SORT_ASC, 'registration_number', SORT_ASC);
				//pr($resultsArray);
				
			}
			if($this->request->data['EA']['exam_type'] == 'A' && isset($this->request->data['foilCardExcel']) != 'foilCardExcel') { 
				$this->set('month_year_id', $this->request->data['EA']['monthyears']);
				$examMYId = array();
				$examMYId['CourseMapping.month_year_id !='] = $this->request->data['EA']['monthyears'];					
				//pr($getCMId);
				//$results = $this->getArrearDetailsFromTimetableCourseStudentMapping($getCMId, $examMYId);
				
				//$results['StudentMark'] = $this->listArrearStudents($examMYId, $cm_id);
				//$results['CourseStudentMapping'] = $this->listNonArrearStudents($examMYId, $cm_id);
				//pr($examMYId);
				//pr($getCMId);
				$resultsArray = array();
				foreach ($getCMId as $key => $cm_id) {
					$tmpResult = $this->listArrearStudents($month_year_id, $cm_id);
					//pr($tmpResult);
					if (count($tmpResult)>0) {
						foreach ($tmpResult as $key =>$tmpArray) {
							$resultsArray[$tmpArray['Student']['id']] =
							array(
									"registration_number" => $tmpArray['Student']['registration_number'],
									"name" => $tmpArray['Student']['name'],
									"course_code" => $tmpArray['Course']['course_code'],
									"course_name" => $tmpArray['Course']['course_name'],
									"course" => $tmpArray['Academic']['short_code'],
									"branch" => $tmpArray['Program']['short_code'],
							);
						}
						//echo count($resultsArray);
						//pr($resultsArray);
					}
					//pr($this->listNonArrearStudents($month_year_id, $cm_id)); die;
					
					$tmpResult = $this->CourseMapping->find('all',  array(
							'conditions' => array('CourseMapping.indicator' => 0,'CourseMapping.id' => $cm_id,
									$examMYId),
							'fields' => array('CourseMapping.id', 'CourseMapping.batch_id', 'CourseMapping.program_id',
									'CourseMapping.month_year_id', 'CourseMapping.semester_id'),
							'contain' => array(
									'Course'=>array('fields' =>array('Course.course_code','Course.course_name'),
											'order'=>array('Course.common_code'),
									),
									'CourseStudentMapping' =>array('fields' =>array('CourseStudentMapping.id','CourseStudentMapping.new_semester_id','CourseStudentMapping.student_id'),
											'conditions'=>array('CourseStudentMapping.new_semester_id' =>$this->request->data['EA']['monthyears']),
											'Student'=>array(
													'fields' => array('Student.id','Student.registration_number', 'Student.name'),
													'conditions' => array('Student.discontinued_status' => 0),
													'Program' => array(
															'fields' => array('Program.program_name', 'Program.short_code'),
															'Academic' => array(
																	'fields' => array('Academic.academic_name', 'Academic.short_code')
															),
													),'order'=>array('Student.registration_number'),
											),
									),
										
							),
					));
					//pr($tmpResult);
					if (count($tmpResult)>0) {
						foreach ($tmpResult as $student){
							for($a=0;$a<count($student['CourseStudentMapping']);$a++){
								if(isset($student['CourseStudentMapping'][$a]['Student']['registration_number'])){
									$resultsArray[$student['CourseStudentMapping'][$a]['Student']['id']] =
									array(
											"registration_number" => $student['CourseStudentMapping'][$a]['Student']['registration_number'],
											"name" => $student['CourseStudentMapping'][$a]['Student']['name'],
											"course_code" => $student['Course']['course_code'],
											"course_name" => $student['Course']['course_name'],
											"course" => $student['CourseStudentMapping'][$a]['Student']['Program']['Academic']['short_code'],
											"branch" => $student['CourseStudentMapping'][$a]['Student']['Program']['short_code'],
									);
								}
							}
						}
					}
				}
				//pr($resultsArray);
				
				//Code by Palani
				/* $results = $this->CourseMapping->find('all',  array(
						'conditions' => array('CourseMapping.indicator' => 0,'CourseMapping.id' => $getCMId,
								$examMYId),
						'fields' => array('CourseMapping.id', 'CourseMapping.batch_id', 'CourseMapping.program_id',
								'CourseMapping.month_year_id', 'CourseMapping.semester_id'),
						'contain' => array(
								'Course'=>array('fields' =>array('Course.course_code','Course.course_name'),
										'order'=>array('Course.common_code'),
								),
								'StudentMark'=>array(
										'conditions' => array(
												'OR' => array(
														array('StudentMark.revaluation_status' => "0", 'StudentMark.status' => 'Fail'),
														array('StudentMark.revaluation_status' => "1", 'StudentMark.final_status'=>'Fail'),
												),
												array(
														'NOT' => array('StudentMark.course_mapping_id' => NULL)
												),
												'StudentMark.indicator' => 0
										),
										'fields'=>array(
												'StudentMark.id', 'StudentMark.student_id', 'StudentMark.month_year_id'
										),
										'Student'=>array(
												'fields' => array('Student.id','Student.registration_number', 'Student.name'),
												'conditions' => array('Student.discontinued_status' => 0),
												'Program' => array(
														'fields' => array('Program.program_name', 'Program.short_code'),
														'Academic' => array(
																'fields' => array('Academic.academic_name', 'Academic.short_code')
														),
												),'order'=>array('Student.registration_number'),
										),
								),
								'CourseStudentMapping' =>array('fields' =>array('CourseStudentMapping.id','CourseStudentMapping.new_semester_id','CourseStudentMapping.student_id'),
										'conditions'=>array('CourseStudentMapping.new_semester_id' =>$this->request->data['EA']['monthyears']),
										'Student'=>array(
												'fields' => array('Student.id','Student.registration_number', 'Student.name'),
												'conditions' => array('Student.discontinued_status' => 0),
												'Program' => array(
														'fields' => array('Program.program_name', 'Program.short_code'),
														'Academic' => array(
																'fields' => array('Academic.academic_name', 'Academic.short_code')
														),
												),'order'=>array('Student.registration_number'),
										),
								),
									
						),
				)); */
				//pr($results);
				
				/* $resultsArray = array();
				foreach ($results as $student){
					for($a=0;$a<count($student['StudentMark']);$a++){
						if(isset($student['StudentMark'][$a]['Student']['registration_number'])){
				
							$resultsArray[$student['StudentMark'][$a]['Student']['id']] =
							array(
									"registration_number" => $student['StudentMark'][$a]['Student']['registration_number'],
									"name" => $student['StudentMark'][$a]['Student']['name'],
									"course_code" => $student['Course']['course_code'],
									"course_name" => $student['Course']['course_name'],
									"course" => $student['StudentMark'][$a]['Student']['Program']['Academic']['short_code'],
									"branch" => $student['StudentMark'][$a]['Student']['Program']['short_code'],
							);
						}
				
					}
					for($a=0;$a<count($student['CourseStudentMapping']);$a++){
						if(isset($student['CourseStudentMapping'][$a]['Student']['registration_number'])){
							$resultsArray[$student['CourseStudentMapping'][$a]['Student']['id']] =
							array(
									"registration_number" => $student['CourseStudentMapping'][$a]['Student']['registration_number'],
									"name" => $student['CourseStudentMapping'][$a]['Student']['name'],
									"course_code" => $student['Course']['course_code'],
									"course_name" => $student['Course']['course_name'],
									"course" => $student['CourseStudentMapping'][$a]['Student']['Program']['Academic']['short_code'],
									"branch" => $student['CourseStudentMapping'][$a]['Student']['Program']['short_code'],
							);
						}
					}
				} */
				//pr($resultsArray);
				//Code by Palani ends here
				
				$results = $this->array_orderby($resultsArray,'course_code', SORT_ASC, 'registration_number', SORT_ASC);
				
				$courseCodeCounts = array_count_values(array_column($results, 'course_code'));
				//pr($courseCodeCounts);
				arsort($courseCodeCounts);
				//pr($courseCodeCounts);
				
				$arrayVar = array();
				foreach($courseCodeCounts as $key=>$val){
					$arrayVar[] = $key;
				}
				//pr($arrayVar);
				
				$this->totWeight = $arrayVar;
				usort($results, array($this,'totStrength'));
				//pr($results);
				
				$results = $this->group_assoc($results, 'course_code');
				
				foreach ($results as $course_code => $group_result) {
					usort($group_result, array($this,'ExamRegSort'));
					$results[$course_code] = $group_result;
				}
				//pr($results);
			}
			if($this->request->data['EA']['exam_type'] == 'A' && isset($this->request->data['foilCardExcel']) == 'foilCardExcel') {
				$this->set('month_year_id', $this->request->data['EA']['monthyears']);
				$examMYId = array();
				$examMYId['CourseMapping.month_year_id !='] = $this->request->data['EA']['monthyears'];
				//pr($getCMId);
				$results = $this->getArrearDetailsFromTimetableCourseStudentMapping($getCMId, $examMYId, $month_year_id);
				$this->foilCardExcelArrear($results);
			}
			if($this->request->data['EA']['exam_type'] == 'R' && isset($this->request->data['foilCardExcel']) == 'foilCardExcel') {
				//$this->set('month_year_id', $this->request->data['EA']['monthyears']);
				//$examMYId = array();
				//$examMYId['CourseMapping.month_year_id !='] = $this->request->data['EA']['monthyears'];
				//pr($getCMId);
				//$results = $this->getArrearDetailsFromTimetableCourseStudentMapping($getCMId, $examMYId);
				$this->foilCardExcelRegular($results);
			}
			if($this->request->data['EA']['exam_type']){ 
				$this->set(compact('results',$results));
				/* $this->set(compact('month_year_id',$this->request->data['EA']['monthyears']));
				$this->set(compact('exam_date',$this->request->data['exam_date']));
				$this->set(compact('exam_session',$this->request->data['EA']['exam_session']));
				$this->set(compact('exam_type',$this->request->data['EA']['exam_type']));
				$txtExamMonthYear = $this->getExamMonthYear($this->request->data['EA']['monthyears']); */
			}
			if((isset($this->request->data['foilCard']) == 'foilCard' || isset($this->request->data['coverPage']) == 'coverPage')
					|| ($this->request->data['print'] == 'print')) {
				// Attendance Foil Card PDF, Attendance Cover Page PDF, PRINT PDF
				$headerLogo = "<table border='0' align='center' cellpadding='0' cellspacing='0' style='font:14px Arial;'>
								<tr>
								<td rowspan='2'><img src='../webroot/img/user.jpg'></td>
									<td align='center'>SATHYABAMA UNIVERSITY<br/>
									<span class='slogan'></span></td>
								</tr>
								</table>";
			}
			$html = "";
			
			$pageLastCourseName = "FIRST";$eachPage = 1;
			$pageLastBranchName = "FIRST";
			if($this->request->data['EA']['exam_type'] == 'R' && isset($this->request->data['foilCard']) == 'foilCard'){
				
				$i=1;$pageAllot = 1;		
				foreach ($results as $result){
					if(($pageLastCourseName != $result['course_code']) || ($pageLastBranchName != $result['branch']) || ($pageAllot == 26)){
						if(($pageLastCourseName != $result['course_code']) || ($pageLastBranchName != $result['branch'])){
							$i=1;
						}
						if($pageLastCourseName != "FIRST" || $pageAllot == 26 || $pageLastBranchName != "FIRST"){							
							$html .="</table>";						
							$pageAllot = 1;
							$html .= $footerFoilCard;
							$html .= "<div style='page-break-after:always'></div>";
							$html .= $headerLogo;
							$eachPage++; 
						}
						if($pageLastCourseName == "FIRST" || $pageLastBranchName == "FIRST"){
							$html .= $headerLogo;
						}
						$html .= "<table border='0' cellpadding='0' cellspacing='0' width='100%' style='font:12px Arial;'>
										<tr>
											<td style='width:18%;height:23px;'><b>M&Year of Exam : </b></td>
											<td style='width:32%;'>".$txtExamMonthYear." <b>".$exam_type."</b></td>
											<td style='width:18%;'><b>Course Name : </b></td>
											<td style='width:32%;'>".$result['course_name']."</td>
										</tr>
										<tr>
											<td style='height:23px;'><b>Branch : </b></td>
											<td style='height:23px;font:13px Arial;'><b>".$result['branch']."</b></td>
											<td style='height:23px;'><b>Course Code : <b></td>
											<td style='height:23px;font:12px Arial;'><b>".$result['course_code']."</b></td>
										</tr>
										<tr>
											<td style='height:23px;'><b>Batch : </b></td>
											<td style='height:23px;font:12px Arial;'><b>".$result['batch']."</b></td>
											<td style='height:23px;'><b>Date & Session : </b></td>
											<td style='height:23px;'>".date( 'd-m-Y', strtotime(h($this->request->data['exam_date'])))."  (".$this->request->data['EA']['exam_session'].")-<b>Page ".$eachPage." of TOTAL_PAGE</b></td>
										</tr>
								</table>";
							
						$html .= "<table cellpadding='0' cellspacing='0' border='1' style='text-indent:5px;font:12px Arial;width:98%;'>
							<tr>
								<th>S.No.</th>
								<th>Reg No.</th>
								<th>NAME</th>
								<th>ANSWER SHEET NO.</th>
								<th>SIGNATURE OF THE STUDENT</th>
							</tr>";
					}
					if(isset($result['registration_number'])){	
						$html .="<tr>";
						$html .="<td align='center' style='height:27px;'>".$i."</td>";
						$html .="<td align='center'>".$result['registration_number']."</td>";
						$html .="<td style='text-indent:3px;'>".$result['name']."</td>";
						$html .="<td></td><td></td>";
						$pageLastCourseName = $result['course_code'];
						$pageLastBranchName = $result['branch'];
						$html .="</tr>";
						$i++; 
						$pageAllot++;						
					}
				}
				$html .="</table>";
				$html .=$footerFoilCard;
			}
			if(($this->request->data['EA']['exam_type'] == 'A') && (($this->request->data['foilCard'] == 'foilCard') ||
						($this->request->data['print'] == 'print'))) { 
				$i=1;$pageAllot = 1;
				foreach($courseCodeCounts as $arrKey=>$val){
					for($b=0;$b<count($results[$arrKey]);$b++){
					if(($pageLastCourseName == "FIRST") || ($pageAllot == 26)){						
						if($pageAllot == 26){
							$html .="</table>";
							$pageAllot = 1;
							$html .= $footerFoilCard;
							$html .= "<div style='page-break-after:always'></div>";
							$html .= $headerLogo;
							$eachPage++;
						}
						if($pageLastCourseName == "FIRST"){
							$html .= $headerLogo;
						}
						$html .= "<table border='1' style='width:100%;font:12px Arial;' cellpadding='0' cellspacing='0'>
									<tr><td colspan='4' align='center'>ARREAR EXAM ATTENDANCE SHEET</td></tr>
									<tr>
										<td><b>Month&Year of Exam</b></td>
										<td>".$txtExamMonthYear." <b>".$exam_type."</b></td>	
										<td><b>Date</b></td>
										<td>".date( 'd-m-Y', strtotime(h($this->request->data['exam_date'])))."  (".$this->request->data['EA']['exam_session'].")-<b>Page ".$eachPage." of TOTAL_PAGE</b></td>
									</tr>
								</table>";
							
						$html .= "<table cellpadding='0' cellspacing='0' border='1' style='text-indent:5px;font:12px Arial;'>
							<tr>
								<th>S.No.</th>
								<th>Reg No.</th>
								<th>NAME</th>
								<th>COURSE</th>
								<th>BRANCH</th>
								<th>COURSE CODE</th>
								<th>ANSWER SHEET NO.</th>
								<th>SIGNATURE OF THE STUDENT</th>
							</tr>";
					}
					
					$html .="<tr>";
					$html .="<td style='height:27px;' align='center'>".$i."</td>";
					$html .="<td align='center'>".$results[$arrKey][$b]['registration_number']."</td>";
					$html .="<td>".$results[$arrKey][$b]['name']."</td>";
					$html .="<td align='center'>".$results[$arrKey][$b]['course']."</td>";
					$html .="<td align='center'>".$results[$arrKey][$b]['branch']."</td>";
					$html .="<td align='center'>".$results[$arrKey][$b]['course_code']."</td>";
					$html .="<td style='width:80px;'></td><td style='width:160px;'></td>";
					$pageLastCourseName = $results[$arrKey][$b]['course_code'];
					$html .="</tr>";
					$i++;
					$pageAllot++;
					}
				}
				$html .="</table>";
				$html .=$footerFoilCard;
			} 
			if(isset($this->request->data['foilCard']) == 'foilCard'){
				$html = str_replace('TOTAL_PAGE',$eachPage,$html);
				$this->layout=false;
				$this->autoRender = false;
				$this->mPDF->init();
				$this->mPDF->setFilename('EXAM_FOIL_CARD_'.date('d_M_Y').'.pdf');
				$this->mPDF->setOutput('D');			
				$this->mPDF->WriteHTML($html);
				$this->mPDF->SetWatermarkText("Draft"); 
			}
		
			//COVER PAGE START
			if(isset($this->request->data['coverPage']) == 'coverPage'){
				if($this->request->data['EA']['exam_type'] == 'R' && isset($this->request->data['coverPage']) == 'coverPage'){
					$i=1;$pageAllot = 1;
					foreach ($results as $result){
						if(($pageLastCourseName != $result['course_code']) || ($pageLastBranchName != $result['branch']) || ($pageAllot == 26)){
							if(($pageLastCourseName != $result['course_code']) || ($pageLastBranchName != $result['branch'])){
								$i=1;
							}
							if($pageLastCourseName != "FIRST" || $pageLastBranchName != "FIRST" || $pageAllot == 26){							
								$pageAllot = 1;
								$html .= $footerCoverFoilCard;
								$html .= "<div style='page-break-after:always'></div>";
								$html .= $headerLogo;
								$eachPage++;
							}
							if($pageLastCourseName == "FIRST" && $pageLastBranchName != "FIRST"){
								$html .= $headerLogo;								
							}
							else if ($pageLastCourseName == "FIRST"){
								$html .= $headerLogo;								
							}
							$html .= "<table border='0' cellpadding='0' cellspacing='0' width='100%' style='font:12px Arial;'>
										<tr>
											<td style='width:18%;height:23px;'><b>M&Year of Exam : </b></td>
											<td style='width:32%;'>".$txtExamMonthYear." <b>".$exam_type."</b></td>
											<td style='width:18%;'><b>Course Name : </b></td>
											<td style='width:32%;'>".$result['course_name']."</td>
										</tr>
										<tr>
											<td style='height:23px;'><b>Branch : </b></td>
											<td style='height:23px;font:13px Arial;'>".$result['branch']."</td>
											<td style='height:23px;'><b>Course Code : <b></td>
											<td style='height:23px;font:13px Arial;'>".$result['course_code']."</td>
										</tr>
										<tr>
											<td style='height:23px;'><b>Batch : </b></td>
											<td style='height:23px;font:13px Arial;'>".$result['batch']."</td>
											<td style='height:23px;'><b>Date & Session : </b></td>
											<td style='height:23px;'>".date( 'd-m-Y', strtotime(h($this->request->data['exam_date'])))."  (".$this->request->data['EA']['exam_session'].")-<b>Page ".$eachPage." of TOTAL_PAGE</b></td>
										</tr>
								</table>";
							$html .= "<table><tr><td style='height:35px;'></td></tr></table>";$heightRow = 0;
						}
						if($heightRow%5 ==0 ){$html .= "<table><tr><td style='height:35px;'></td></tr></table>";}
						if(isset($result['registration_number'])){ 				
							$html .= "<span style='float:left;'>";
							$html .= $result['registration_number']."&nbsp;........&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
							$html .= "</span><span style='float:clear;'></span>";
							$i++;
							$pageAllot++;$heightRow++; 
							$pageLastCourseName = $result['course_code'];
							$pageLastBranchName = $result['branch'];
						}						
					}
								
				}
				if($this->request->data['EA']['exam_type'] == 'A' && isset($this->request->data['coverPage']) == 'coverPage'){
					$i=1;$pageAllot = 1;
					foreach($courseCodeCounts as $arrKey=>$val){
							for($b=0;$b<count($results[$arrKey]);$b++){
							if(($pageLastCourseName != $results[$arrKey][$b]['course_code']) || ($pageAllot == 26)){
								if(($pageLastCourseName != $results[$arrKey][$b]['course_code'])){
									$i=1;
								}
								if($pageLastCourseName != "FIRST" || $pageAllot == 26){
									$pageAllot = 1;
									$html .= $footerFoilCoverCard;
									$html .= "<div style='page-break-after:always'></div>";
									$html .= $headerLogo;
									$eachPage++;
								}
								if($pageLastCourseName == "FIRST"){
									$html .= $headerLogo;
								}
								$html .= "<table border='0' cellpadding='0' cellspacing='0' width='100%' style='font:13px Arial;'>
										<tr>
											<td style='width:18%;height:30px;'><b>M&Year of Exam : </b></td>
											<td style='width:32%;'>".$txtExamMonthYear." <b>".$exam_type."</b></td>
											<td style='height:23px;'><b>Date & Session : </b></td>
											<td style='height:23px;'>".date( 'd-m-Y', strtotime(h($this->request->data['exam_date'])))."  (".$this->request->data['EA']['exam_session'].")-<b>Page ".$eachPage." of TOTAL_PAGE</b></td>
										</tr>
										<tr>
											<td style='width:18%;height:30px;'><b>Course Name : </b></td>
											<td style='width:32%;'>".$results[$arrKey][$b]['course_name']."</td>
											<td style='height:23px;'><b>Course Code : <b></td>
											<td style='height:23px;font:12px Arial;'><b>".$results[$arrKey][$b]['course_code']."</b></td>			
										</tr>
										<tr>											
											</tr>
								</table>";									
								$html .= "<table><tr><td style='height:35px;'></td></tr></table>";$heightRow = 0;
							}
						
						if($heightRow%5 ==0 ){$html .= "<table><tr><td style='height:35px;'></td></tr></table>";}
						
						$html .= "<span style='float:left;'>";
						$html .= $results[$arrKey][$b]['registration_number']."&nbsp;........&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
						$html .= "</span><span style='float:clear;'></span>";
						$i++;
						$pageAllot++;$heightRow++;
						$pageLastCourseName = $results[$arrKey][$b]['course_code'];		
						}
					}
					$html .=$footerFoilCoverCard;
				}
				$html = str_replace('TOTAL_PAGE',$eachPage,$html);
				$this->autoRender = false;
				$this->mPDF->init();
				$this->mPDF->setFilename('COVER_PAGE_'.date('d_M_Y').'.pdf');
				$this->mPDF->setOutput('D');
				if(isset($this->request->data['foilCard']) == 'foilCard'){
					$this->mPDF->setFooter($footerFoilCard);
				}
				$this->mPDF->WriteHTML($html);
				
				$this->mPDF->SetWatermarkText("Draft");
			}
			//COVER PAGE END
			
			//PRINT PAGE START
			if(isset($this->request->data['print']) == 'print'){
				if($this->request->data['EA']['exam_type'] == 'A' && $this->request->data['print'] == 'print'){
					
					$this->autoRender=false;
					$this->layout= false;
					$this->layout= 'print';
					$this->set('results', $results);
					$this->set('exam_type', $exam_type);
					$this->render('attendance_entry_arrear_all');
				}
				else if ($this->request->data['EA']['exam_type'] == 'R' && $this->request->data['print'] == 'print') {
					$this->autoRender=false;
					$this->layout= false;
					//pr($results);
					$this->layout= 'print';
					$this->set('results', $results);
					$this->set('exam_type', $exam_type);
					$this->render('attendance_entry_all');
				}
			}
			//PRINT PAGE END
		}
		
		$monthyears = $this->MonthYear->getAllMonthYears();
		$this->set('monthyears', $monthyears);
		}
		else {
			$this->render('../Users/access_denied');
		}
	}

/**
 * view method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function view($id = null) {
		if (!$this->ExamAttendance->exists($id)) {
			throw new NotFoundException(__('Invalid exam attendance'));
		}
		$options = array('conditions' => array('ExamAttendance.' . $this->ExamAttendance->primaryKey => $id));
		$this->set('examAttendance', $this->ExamAttendance->find('first', $options));
	}

/**
 * add method
 *
 * @return void
 */ 
	public function add($timetable_id, $month_year_id,$cm_id,$exam_type,$printMode = null,$exam_date = null,$exam_session = null,$semesterNewId = null) {
		// echo "*".$timetable_id."*".$month_year_id."*".$cm_id."*".$exam_type."*".$printMode."*".$exam_date."*".$exam_session."=*".$semesterNewId; exit;
		if($this->request->is('post')) {
			if(isset($this->request->data['Confirm'])){ 
				for($i=1; $i<=$this->request->data['maxRow'];$i++) {
					if(isset($this->request->data['ExamAttendance']['studentId'.$i])){
						$data = array();
						$data['ExamAttendance']['timetable_id']		= $this->request->data['timetable_id'];
						$data['ExamAttendance']['student_id'] = $this->request->data['ExamAttendance']['studentId'.$i];
						$data['ExamAttendance']['attendance_status'] = $this->request->data['ExamAttendance']['checkbox'.$i];
						$data['ExamAttendance']['created_by'] 		= $this->Auth->user('id');
						$this->ExamAttendance->create();
						$this->Flash->success(__('The Exam Attendance has been saved.'));
						$this->ExamAttendance->save($data);
					}
				}
				return $this->redirect(array('action' => 'index'));			
			}
		}
		if($exam_type == 'R' || $exam_type == '-') {
			$result = array(
				'conditions' => array('CourseStudentMapping.course_mapping_id' => $cm_id,'CourseStudentMapping.indicator' => 0,'CourseStudentMapping.new_semester_id' => null,
				),
				'fields' =>array('CourseStudentMapping.id','CourseStudentMapping.student_id'),
				'contain'=>array(
						'Student'=>array('fields' =>array('Student.id','Student.name','Student.registration_number','Student.user_initial'),
								'conditions' => array('Student.discontinued_status' => 0),
						),
						'CourseMapping'=>array('fields' =>array('CourseMapping.id'),
							'Course'=>array('fields' =>array('Course.course_code','Course.course_name')),
							'Program'=>array('fields' =>array('Program.program_name'),
									'Academic'=>array('fields' =>array('Academic.academic_name')),
							),
							'Batch'=>array('fields' =>array('Batch.batch_from','Batch.batch_to','Batch.academic')),
						),
				),
					
				'recursive' => 1
			);
			$results = $this->CourseStudentMapping->find("all", $result);				
		}
		if($exam_type == 'A') {
			$modelArray = array("StudentMark", "CourseStudentMapping");
			$this->set(compact('modelArray', $modelArray));
			
			$examMYId = array();
			$examMYId['CourseMapping.month_year_id <'] = $month_year_id;
			
			//Get Other Data Start
			$semesterNewIdArray = array();
			
			if($semesterNewId != '-'){
				$semesterNewIdArray['CourseStudentMapping.new_semester_id'] = $month_year_id;
			}
			
			//Arrear Start - Code by Palani
			/* $results = $this->CourseMapping->find('all',  array(
					'conditions' => array('CourseMapping.indicator' => 0,'CourseMapping.id' => $cm_id,
							$examMYId),
					'fields' => array('CourseMapping.id', 'CourseMapping.batch_id', 'CourseMapping.program_id',
							'CourseMapping.month_year_id', 'CourseMapping.semester_id'),			
					'contain' => array(							
						'StudentMark'=>array(
							'conditions' => array(
								'OR' => array(
										array('StudentMark.revaluation_status' => "0", 'StudentMark.status' => 'Fail'),
										array('StudentMark.revaluation_status' => "1", 'StudentMark.final_status'=>'Fail'),
								),
								array(
										'NOT' => array('StudentMark.course_mapping_id' => NULL)
								),
								'StudentMark.indicator' => 0,								
							),
							'fields'=>array(
									'StudentMark.id', 'StudentMark.student_id'
							),					
							'Student'=>array(
									'fields' => array('Student.id','Student.registration_number', 'Student.name'),
									'conditions' => array('Student.discontinued_status' => 0),
									'Program' => array(
											'fields' => array('Program.program_name', 'Program.short_code'),
											'Academic' => array(
													'fields' => array('Academic.academic_name', 'Academic.short_code')
														
											),
									),'order'=>array('Student.registration_number'),	
							),
							
						),
							
						'CourseStudentMapping' =>array('fields' =>array('CourseStudentMapping.id','CourseStudentMapping.new_semester_id','CourseStudentMapping.student_id'),
								'conditions'=>$semesterNewIdArray,
								'Student'=>array(
										'fields' => array('Student.id','Student.registration_number', 'Student.name'),
										'conditions' => array('Student.discontinued_status' => 0),
										'Program' => array(
												'fields' => array('Program.program_name', 'Program.short_code'),
												'Academic' => array(
														'fields' => array('Academic.academic_name', 'Academic.short_code')
								
												),
										),'order'=>array('Student.registration_number'),
								),
						),
		
					),
			));	 */
			//Arrear End - Code by Palani
			
			//Arrear start - Code by Hema
			$results['StudentMark'] = $this->listArrearStudents($month_year_id, $cm_id);

			$results['CourseStudentMapping'] = $this->listNonArrearStudents($month_year_id, $cm_id);
			 
			
			//Arrear end - Code by Hema
			//pr($results); exit;
		}
		if($exam_type){
			$this->set(compact('results',$results));
			$this->set(compact('timetable_id',$timetable_id));
			$this->set(compact('month_year_id',$month_year_id));
			$this->set(compact('exam_date',$exam_date));
			$this->set(compact('exam_session',$exam_session));
			$this->set(compact('exam_type',$exam_type));
			$this->set(compact('cm_id',$cm_id));
		}
		if($printMode == 'P') {
			$this->layout= false;
			$this->layout= 'print';
			if($exam_type == 'R') {	
				$this->render('attendance_entry');
			}else{ 
				$this->render('attendance_entry_arrear');
			}
			return false;
		}
	}

/**
 * edit method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */ 
	public function edit($timetable_id = null, $month_year_id = null,$cm_id = null,$exam_type = null,$printMode = null,$exam_date = null,$exam_session = null,$semesterNewId = null) {
		if($this->request->is('post')) {
			if(isset($this->request->data['Confirm'])){
				for($i=1; $i<=$this->request->data['maxRow'];$i++) {
					if(isset($this->request->data['ExamAttendance']['studentId'.$i])){
						$data = array();
						$data['ExamAttendance']['timetable_id']	  	 = $this->request->data['timetable_id'];
						$data['ExamAttendance']['student_id'] 		 = $this->request->data['ExamAttendance']['studentId'.$i];
						$data['ExamAttendance']['attendance_status'] = $this->request->data['ExamAttendance']['checkbox'.$i];
						$data['ExamAttendance']['modified_by'] 	     = $this->Auth->user('id');
						$data['ExamAttendance']['id'] 				 = $this->request->data['ExamAttendance']['AutoGenId'.$i];
						
						$this->Flash->success(__('The Exam Attendance has been updated.'));
						$this->ExamAttendance->save($data);
					}
				}
				return $this->redirect(array('action' => 'index'));			
			}
		}
		
		$result = array(
				'conditions' => array('ExamAttendance.timetable_id' => $timetable_id,
				),
				'fields' =>array('ExamAttendance.id','ExamAttendance.timetable_id','ExamAttendance.student_id','ExamAttendance.student_id','ExamAttendance.attendance_status'),
				'contain'=>array(
						'Timetable' =>array('fields' =>array('Timetable.month_year_id','Timetable.course_mapping_id'),
								'CourseMapping'=>array('fields' =>array('CourseMapping.id'),
										'Course'=>array('fields' =>array('Course.course_code','Course.course_name')),
										'Program'=>array('fields' =>array('Program.program_name'),
												'Academic'=>array('fields' =>array('Academic.academic_name')),
										),
										'Batch'=>array('fields' =>array('Batch.batch_from','Batch.batch_to','Batch.academic')),
								),
						),
						'Student'=>array('fields' =>array('Student.id','Student.name','Student.registration_number')),
				),
				'order'=>array('Student.registration_number'=>'asc'),
				'recursive' => 1
		);
		
		if($exam_type) {
			$results = $this->ExamAttendance->find("all", $result);
			//pr($results);
			$this->set(compact('results',$results));
			$this->set(compact('timetable_id',$timetable_id));
			$this->set(compact('month_year_id',$month_year_id));
			$this->set(compact('exam_date',$exam_date));
			$this->set(compact('exam_session',$exam_session));
			$this->set(compact('exam_type',$exam_type));
		}
	}

/**
 * delete method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function delete($id = null) {
		$this->ExamAttendance->id = $id;
		if (!$this->ExamAttendance->exists()) {
			throw new NotFoundException(__('Invalid exam attendance'));
		}
		$this->request->allowMethod('post', 'delete');
		if ($this->ExamAttendance->delete()) {
			$this->Flash->success(__('The exam attendance has been deleted.'));
		} else {
			$this->Flash->error(__('The exam attendance could not be deleted. Please, try again.'));
		}
		return $this->redirect(array('action' => 'index'));
	}
	
	public function attendance() {
		$academics = $this->Academic->find('list');
		$batches = $this->Batch->find('list', array('fields' => array('Batch.batch_period')));
		
		$monthYear = $this->MonthYear->find('all', array(
				'order'=>array('MonthYear.year'=>'desc', 'MonthYear.month_id'=>'desc')
		));		
		$monthYears = array();
		for ($i=0; $i<count($monthYear);$i++) {
			$monthYears[$monthYear[$i]['MonthYear']['id']] = $monthYear[$i]['Month']['month_name']." - ".$monthYear[$i]['MonthYear']['year'];
		}
		$cType = $this->cType;
		$this->set(compact('batches', 'academics', 'monthYears', 'cType'));
		
	}
	
	public function examDate($examMonth = null) {
		$conditions=array();
		$conditions['Timetable.indicator'] = 0;
		if(!empty($examMonth)){			
			$conditions['Timetable.month_year_id'] = $examMonth;
		}	
		$result = array(
			'conditions' => $conditions,				
			'fields' =>array('Timetable.id','Timetable.exam_date'),
			'group' => 'Timetable.exam_date',				
			'order' => 'Timetable.exam_date ASC',
			'recursive' => 1
		);
		$examDates = $this->Timetable->find("list", $result);
		
		$eachDates = array();
		if($examDates){
			foreach($examDates as $key => $value){
				list($year,$month,$date)= explode('-', $value);
				$eachDates[$year."-".$month."-".$date] = $date."-".date('M', mktime(0, 0, 0, $month, 10))."-".$year;
			}
		} 
		$this->set('examDates', $eachDates);
		$this->layout=false;	
	}
	
	public function search($examMonth = null, $exam_type = null, $exam_session = null,$exam_date = null) {
		$conditions=array();
		if($examMonth !='-'){			
			$conditions['Timetable.month_year_id'] = $examMonth;
		}
		if($exam_type !='-'){
			$conditions['Timetable.exam_type'] = $exam_type;
		}
		if($exam_session !='-'){
			$conditions['Timetable.exam_session'] = $exam_session;
		}
		if($exam_date !='-'){
			$conditions['Timetable.exam_date'] = $exam_date;
		}
		$result = array(
			'conditions' => $conditions,			
			'fields' =>array('Timetable.exam_date','Timetable.exam_session','Timetable.exam_type','Timetable.month_year_id'),				
			'contain'=>array(
				'MonthYear'=>array('fields' =>array('MonthYear.year'),
					'Month'=>array('fields' =>array('Month.month_name'))
				),
				'ExamAttendance'=>array('fields' =>array('ExamAttendance.id')),
				'CourseMapping'=>array('fields'=>array('CourseMapping.id',  'CourseMapping.program_id', 'CourseMapping.course_number', 'CourseMapping.course_mode_id','CourseMapping.semester_id', 'CourseMapping.month_year_id'),
					'Program'=>array('fields' =>array('Program.program_name'),
							'Academic'=>array('fields' =>array('Academic.academic_name'))
					),
					'Course'=>array('fields' =>array('Course.course_code','Course.course_name','Course.common_code')),
					'Batch'=>array('fields' =>array('Batch.batch_from','Batch.batch_to','Batch.academic')),
					'CourseStudentMapping' =>array('fields' =>array('CourseStudentMapping.new_semester_id'),
							'conditions'=>array('CourseStudentMapping.type !=' => 'N'),
							/*'Student'=>array('conditions'=>array('Student.discontinued_status'=>0),'fields' =>array('Student.id')),*/
					),
				),					
			),
			'recursive' => 2
		);
		
		$results = $this->Timetable->find("all", $result);
		$this->set('examAttendances', $results);
	
		$this->set('examMonth', $examMonth);
		$this->set('exam_type', $exam_type);
		$this->set('exam_session', $exam_session);		
		
		$this->layout=false;
	}
	
	public function absent() {
		$access_result = $this->checkPathAccess($this->Auth->user('id'));
		if (!$access_result) {
		$monthyears = $this->MonthYear->getAllMonthYears();
		$this->set('monthyears', $monthyears);
		if ($this->request->is('post')) {
			$examMonth = $this->request->data['EA']['monthyears'];
			$exam_type = $this->request->data['EA']['exam_type'];
			$exam_session = $this->request->data['EA']['exam_session'];
			$exam_date = $this->request->data['exam_date'];
			$common_code = $this->request->data['common_code'];
			$results = $this->examAbsentees($examMonth, $exam_type, $exam_session, $exam_date, $common_code); 
			if(isset($this->request->data['excel']) && $this->request->data['excel'] == 'excel'){
				$this->examAbsentSearchExcel($results,$examMonth, $exam_type, $exam_session, $exam_date, $common_code);
			}
			else if (isset($this->request->data['download']) && $this->request->data['download']=="download") {
				$html ="";
				$month_year = $this->getExamMonthYear($examMonth);
				$headerLogo = "<table border='0' align='center' cellpadding='0' cellspacing='0' style='font:14px Arial;'>
								<tr>
								<td rowspan='2'><img src='../webroot/img/user.jpg'></td>
									<td align='center'>SATHYABAMA UNIVERSITY<br/>
									<span class='slogan'></span></td>
								</tr>
								</table>";
					
				if(isset($results)) { 
					if ($exam_type == 'R') $exam_type='Regular'; else $exam_type='Arrear';
					$j=1;
					$pageSeq=0;
					foreach ($results as $key => $result) { 
						$tmpArray = $result['ExamAttendance'];
						foreach ($tmpArray as $key => $studentsArray) {
							if($j==1 || $pageSeq == 15){
								$pageSeq=0;
								if($j!=1){
									$html .= "</table>";
									$html .= "<div style='page-break-after:always'></div>";
								}
								$html .= $headerLogo;
								$html .= "<table border='1' cellpadding='0' cellspacing='0' style='width:100%;'>
						                  <tr><td colspan='4' align='center'><b>ESE EXAM ABSENTEES</b></td></tr>
						                  <tr>
						                    <td style='height:30px;'><b>&nbsp;Exam Month</b></td>
						                    <td>&nbsp;".$month_year."</td>
						                    <td><b>&nbsp;Exam Type</b></td>
						                    <td>&nbsp;".$exam_type."</td>
						                  </tr>
						                  <tr>
						                    <td style='height:30px;'><b>&nbsp;Exam Session</b></td>
						                    <td>&nbsp;".$exam_session."</td>
						                    <td><b>&nbsp;Exam Date</b></td>
						                    <td>&nbsp;".$exam_date."</td>
						                  </tr>
						                  </table><table border='1' cellpadding='0' cellspacing='0' style='width:100%;font:12px airal;'>";
								$html .="<tr>
											<th style='height:30px;'> Sl.&nbsp;No.</th>
											<th> Reg.No.</th>
											<th style='width:250px;'> Student&nbsp;Name</th>
											<th> Batch Period</th>
											<th> Program Name</th>
											<th> Specialisation</th>";
							}
							$pageSeq++;
							$html .="<tr>
							<td style='height:26px;text-align:center;'>".$j."</td>
							<td style='text-align:center;'>&nbsp;".$studentsArray['Student']['registration_number']."&nbsp;</td>
							<td style='text-indent:5px;'>&nbsp;".$studentsArray['Student']['name']."&nbsp;</td>";
							$html .="<td>&nbsp;".$result['CourseMapping']['Batch']['batch_period']."&nbsp;</td>";
							$html .="<td>&nbsp;".$result['CourseMapping']['Program']['Academic']['academic_name']."&nbsp;</td>";
							$html .="<td>&nbsp;".$result['CourseMapping']['Program']['program_name']."&nbsp;</td>";
							$j++; 
					}
				}
				$html.="</table>";
				
				$this->layout=false;
				$this->autoRender=false;
				$this->mPDF->init();
				$this->mPDF->setFilename("ESE_ABSENTEES_".date('d_M_Y').'.pdf');
				$this->mPDF->setOutput('D');
				$this->mPDF->AddPage('L','', '', '', '',30,30,30,30,18,12);
				//$this->mPDF->setFooter($footerFoilCard);
				$this->mPDF->WriteHTML($html);
				$this->mPDF->SetWatermarkText("Draft");
			}
		}
		else if (isset($this->request->data['print']) && $this->request->data['print']=="print") {
			$results = $this->examAbsentees($examMonth, $exam_type, $exam_session, $exam_date, $common_code);
			$this->set('examAttendances', $results);
			$this->set('examMonth', $examMonth);
			$this->set('exam_type', $exam_type);
			$this->set('exam_session', $exam_session);
			$this->layout= false;
			$this->layout= 'print';
			$this->render('search_absent');
		}
		}
		}
		else {
			$this->render('../Users/access_denied');
		}
	}
	
	public function searchAbsent($examMonth = null, $exam_type = null, $exam_session = null,$exam_date = null,$common_code) {
		$results = $this->examAbsentees($examMonth, $exam_type, $exam_session, $exam_date, $common_code);
		$this->set('examAttendances', $results);
		$this->set('examMonth', $examMonth);
		$this->set('exam_type', $exam_type);
		$this->set('exam_session', $exam_session);		
		
		$this->layout=false;
		
	}
	
	public function examAbsentees($examMonth=null, $exam_type=null, $exam_session=null, $exam_date=null, $common_code) {
		$conditions=array();$CCArray=array();
		if($examMonth !='-'){
			$conditions['Timetable.month_year_id'] = $examMonth;
		}
		if($exam_type !='-'){
			$conditions['Timetable.exam_type'] = $exam_type;
		}
		if($exam_session !='-'){
			$conditions['Timetable.exam_session'] = $exam_session;
		}
		if($exam_date !='-'){
			$conditions['Timetable.exam_date'] = $exam_date;
		}
		if($common_code !='-' || $common_code != ''){
			$CCArray['Course.common_code'] = $common_code;
		}
		
		$result = array(
				'conditions' => $conditions,
				'fields' =>array('Timetable.exam_date','Timetable.exam_session','Timetable.exam_type','Timetable.month_year_id'),
				'contain'=>array(
						'MonthYear'=>array('fields' =>array('MonthYear.year'),
								'Month'=>array('fields' =>array('Month.month_name'))
						),
						'ExamAttendance'=>array('fields' =>array('ExamAttendance.id'),
								'Student'=>array('fields' =>array('Student.id','Student.name','Student.registration_number')),'conditions' =>array('ExamAttendance.attendance_status'=>0)
						),
						'CourseMapping'=>array('fields'=>array('CourseMapping.id',  'CourseMapping.program_id', 'CourseMapping.course_number', 'CourseMapping.course_mode_id','CourseMapping.semester_id', 'CourseMapping.month_year_id'),
								'Program'=>array('fields' =>array('Program.program_name'),
										'Academic'=>array('fields' =>array('Academic.academic_name'))
								),
								'Course'=>array(
										'fields' =>array('Course.course_code','Course.course_name','Course.common_code'),
										//'conditions' => $CCArray
								),
								'Batch'=>array('fields' =>array('Batch.batch_from','Batch.batch_to','Batch.academic', 'Batch.batch_period')),
						),
		
				),
				'recursive' => 2
		);
		
		$results = $this->Timetable->find("all", $result);
		return $results;
	}
	
}
