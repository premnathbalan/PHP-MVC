<?php
App::uses('AppController', 'Controller');
App::import('Vendor', 'mPDF', array('file' => 'mPDF'.DS.'mPDF.php'));
/**
 * DummyNumberAllocations Controller
 *
 * @property DummyNumberAllocation $DummyNumberAllocation
 * @property PaginatorComponent $Paginator
 * @property FlashComponent $Flash
 */
class DummyNumberAllocationsController extends AppController {

/**
 * Components
 *
 * @var array
 */
	public $components = array('Paginator', 'Flash', 'Session','mPDF');
	public $uses = array("DummyNumberAllocation","DummyNumber","Timetable","ExamAttendance","CourseMapping", "Batch", "Program", "MonthYear","Academic","Student","DummyRangeAllocation");

/**
 * index method
 *
 * @return void
 */
	public function index() { 
		$access_result = $this->checkPathAccess($this->Auth->user('id'));
		if (!$access_result) {
			$monthyears = $this->MonthYear->getAllMonthYears();
			$this->set(compact('monthyears'));
		}
		else {
			$this->render('../Users/access_denied');
		}
	}
	
	public function getExamMonthYear($EMId = null){
		$conditions['MonthYear.id']=$EMId;
		$month_year = $this->MonthYear->find("all", array(
				'conditions'=>$conditions,
				'fields' => array('MonthYear.month_id','MonthYear.year', 'Month.month_name'),
				'recursive' => 0
		));
		//pr($month_year);
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
	public function FoilCard() {
		$access_result = $this->checkPathAccess($this->Auth->user('id'));
		if (!$access_result) {
		$bodyHtml= "";
		if(isset($this->request->data['foilCard']) == 'foilCard' || isset($this->request->data['coverPage']) == 'coverPage'){
			$conditions=array();$headerHtml = "";  $footerHtml = "";	$eachPage = 1;	$LastcourseCode =""; $dummyPage =1;	
			$txtExamMonthYear = $this->getExamMonthYear($this->request->data['DN']['monthyears']);
			if($this->request->data['exam_date']){
				$conditions['Timetable.exam_date'] = $this->request->data['exam_date'];
			}
			$resDummy = array(
			'conditions' => array('DummyNumber.indicator' => 0,'DummyNumber.month_year_id'=>$this->request->data['DN']['monthyears']
			),
			'fields' =>array('DummyNumber.id','DummyNumber.start_range','DummyNumber.end_range'),				
			'contain'=>array(
				'DummyRangeAllocation'=>array('fields' =>array('DummyRangeAllocation.id','DummyRangeAllocation.dummy_number_id','DummyRangeAllocation.timetable_id'),
					'Timetable' =>array(
						'conditions' => $conditions,
						'fields' =>array('Timetable.month_year_id','Timetable.course_mapping_id','Timetable.exam_date','Timetable.exam_session','Timetable.exam_type'),
						'order' => 'Timetable.exam_date',
							'CourseMapping'=>array('fields' =>array('CourseMapping.id'),
								'Course'=>array('fields' =>array('Course.course_code','Course.course_name','Course.board'),
										'order'=>array('Course.common_code'),
								),
							),
					),
						
				),
				'DummyNumberAllocation'=>array('fields' =>array('DummyNumberAllocation.id'),'conditions' => array('DummyNumberAllocation.indicator' =>0),'order'=>array('DummyNumberAllocation.dummy_number'=>'asc'),),
			),					
			'group' => 'DummyNumber.start_range',
			'order'=>array('DummyNumber.id'=>'asc'),
			'recursive' => 1
			);
			$dummyAlt = $this->DummyNumber->find("all", $resDummy);
				
			if(isset($this->request->data['foilCard']) == 'foilCard'){
			foreach($dummyAlt as $results){
				if(isset($results['DummyNumber']['start_range'])){
					$actualDummyNo = $results['DummyNumber']['start_range'];$p =0;
					if(isset($results['DummyRangeAllocation'][0]['Timetable']['CourseMapping'])){
					$courseName = $results['DummyRangeAllocation'][0]['Timetable']['CourseMapping']['Course']['course_name'];
					$courseCode = $results['DummyRangeAllocation'][0]['Timetable']['CourseMapping']['Course']['course_code'];
					$board = $results['DummyRangeAllocation'][0]['Timetable']['CourseMapping']['Course']['board'];
					if($LastcourseCode !='' && $LastcourseCode != $results['DummyRangeAllocation'][0]['Timetable']['CourseMapping']['Course']['course_code']){
						$bodyHtml = str_replace('DUMMY_PAGE',$dummyPage,$bodyHtml);
						$dummyPage = "1";
						$bodyHtml .=$footerHtml;
						$bodyHtml .="</table>";
						$bodyHtml .= "<div style='page-break-after:always'></div>";
						$eachPage++;
					}
					for($i=($results['DummyNumber']['start_range']);$i<=($results['DummyNumber']['end_range']);$i++){
						
						$headerHtml ="<table style='width:100%;margin-top:60px;' border='1' cellpadding='0' cellspacing='0'>
											<thead>
												<tr><td colspan='4' align='center' style='font:18px Arial;font-weight:bold;height:60px;'>SATHYABAMA UNIVERSITY</td></tr>
												<tr><td colspan='4' align='center' style='font:14px Arial;font-weight:bold;height:40px;'>THEORY EXAM FOIL CARD </td></tr>
												<tr><td colspan='3' style='font:13px Arial;font-weight:bold;height:30px;'>Month&Year of Exam : ".$txtExamMonthYear."</td><td align='center' style='font:13px Arial;font-weight:bold;height:30px;width:200px;'><b>BOARD - ".$board."</td></tr>
												<tr><td colspan='4' style='font:13px Arial;font-weight:bold;height:30px;'>Course Name : ".$courseName."</td></tr>
												<tr><td colspan='3' style='font:13px Arial;font-weight:bold;height:30px;'>Course Code : ".$courseCode."</td><td align='center' style='font:13px Arial;font-weight:bold;height:30px;width:200px;'><b>(Page ".$dummyPage." of DUMMY_PAGE / TOTAL_PAGE)</b></td></tr>   
												<tr>
													<td rowspan='2' style='width:100px;height:30px;font:15px Arial;' align='center'>DUMMY <br/>NUMBER</td>
													<td colspan='3' style='font:15px Arial;' align='center'><b>MARKS OBTAINED OUT OF 100</b></td>    
												</tr>
												<tr>
													<td align='center' style='width:20px;height:30px;font:15px Arial;'>IN <br/>FIGURES</td>
													<td colspan='2' align='center' style='font:15px Arial;'>IN WORDS</td>
												</tr>
											</thead>";
						$footerHtml ="<tr><td align='center'>TOTAL</td><td></td><td colspan='2'></td></tr>
										 <tr><td colspan='4' style='height:60px;vertical-align: text-bottom;'>Signature & Name of Examiner in Capitals</td></tr>
										 <tr><td colspan='4' style='height:60px;' align='left'><u><span style='padding-left:160px;'>Instruction to Examiners</span></u><br/>1. Totalling of marks is mandatory <br/>2. Mark column should not be left blank. <br/>3. Avoid omission, alteration of Register/Dummy Number/mark.</td></tr>";
						
						if($p == 0 || ($p % 20 == 0)){
							$bodyHtml .= $headerHtml;
							
						} 
						$bodyHtml .="<tr>
									<td align='center' height='28px;'>".$actualDummyNo."</td>
									<td></td>
									<td colspan='2'></td>
								</tr>";
						
						$actualDummyNo = $actualDummyNo +1; $p = $p+1;
						
						if(($p % 20 == 0) && ($i < $results['DummyNumber']['end_range'])){							
							$bodyHtml .=$footerHtml;
							$bodyHtml .="</table>";
							$bodyHtml .= "<div style='page-break-after:always'></div>";
							$eachPage++;
							$dummyPage++;
						}
					}
					
					$LastcourseCode = $results['DummyRangeAllocation'][0]['Timetable']['CourseMapping']['Course']['course_code'];
					}
				}
			}
			if($bodyHtml){				
				$bodyHtml .="</table>";
				$bodyHtml = str_replace('DUMMY_PAGE',$dummyPage,$bodyHtml);
				$fileName = "DUMMY_NO_FOIL_CARD_";
			}
			}
			if(isset($this->request->data['coverPage']) == 'coverPage'){
				$startingDummyNumber = "";$endDummyNumber = "";$coverCnt ="";$LastcourseName=""; $bodyTag ="";$board ="";
				foreach($dummyAlt as $results){
					if(isset($results['DummyNumber']['start_range'])){
						$actualDummyNo = $results['DummyNumber']['start_range'];$p =0;
						if(isset($results['DummyRangeAllocation'][0]['Timetable']['CourseMapping'])){
							$courseName = $results['DummyRangeAllocation'][0]['Timetable']['CourseMapping']['Course']['course_name'];
							$courseCode = $results['DummyRangeAllocation'][0]['Timetable']['CourseMapping']['Course']['course_code'];		
							$board = $results['DummyRangeAllocation'][0]['Timetable']['CourseMapping']['Course']['board'];
							$examDate = date( "d-M-Y", strtotime(h($results['DummyRangeAllocation'][0]['Timetable']['exam_date'])) )." (".$results['DummyRangeAllocation'][0]['Timetable']['exam_session'].")";
							for($i=($results['DummyNumber']['start_range']);$i<=($results['DummyNumber']['end_range']);$i++){
								if($startingDummyNumber == ''){
									if($coverCnt ==''){
									$startingDummyNumber = $i;
									}else{$startingDummyNumber = $i-1;}
								}
								if($p ==1){
									$LastcourseCode = $results['DummyRangeAllocation'][0]['Timetable']['CourseMapping']['Course']['course_code'];
									$LastcourseName = $results['DummyRangeAllocation'][0]['Timetable']['CourseMapping']['Course']['course_name'];
									$board			= $results['DummyRangeAllocation'][0]['Timetable']['CourseMapping']['Course']['board'];
								}
								
								if($i !=($results['DummyNumber']['start_range']) && ($p % 20 == 0) || ($LastcourseCode != $results['DummyRangeAllocation'][0]['Timetable']['CourseMapping']['Course']['course_code'] && $LastcourseCode !='')){							
								
									$bodyHtml .="<table style='width:100%;font:15px Arial;font-weight:bold;' border='0' cellpadding='0' cellspacing='0'>
											<thead>
												<tr><td style='height:36px;'>DUMMY RANGE </td><td align='left' colspan='2'>".$startingDummyNumber." - ".$endDummyNumber."</td><td align='right' style='font:17px Arial;font-weight:bold;'>BOARD - ".$board."</td></tr>
												<tr><td style='height:36px;'>M&YEAR OF EXAM </td><td align='left' colspan='3'>".$txtExamMonthYear."</td></tr>
												<tr><td style='height:36px;'>COURSE NAME </td><td align='left' colspan='3'>".$LastcourseName."</td></tr>
												<tr><td style='height:36px;'>COURSE CODE </td><td align='left'>".$LastcourseCode."</td><td colspan='2' align='right'>(PACKET NO ".$dummyPage." of DUMMY_PAGE / TOTAL_PAGE)</td></tr>
												<tr><td style='height:36px;'>EXAM DATE </td><td align='left'>".$examDate."</td><td colspan='2' align='right'>COUNT - ".$coverCnt."</td></tr>
											</thead>
											</table>";									
									if($eachPage % 4 == 0){
										$bodyHtml .= "<div style='page-break-after:always'></div>";
									}else{$bodyHtml .= "<div style='margin-top:80px;'></div>";}
									
									if($LastcourseCode != $results['DummyRangeAllocation'][0]['Timetable']['CourseMapping']['Course']['course_code']){
										$bodyHtml = str_replace('DUMMY_PAGE',$dummyPage,$bodyHtml);
										$dummyPage = "0";
									}									
									$eachPage++;$coverCnt = "";$dummyPage++;
									$startingDummyNumber = "";
									
									$LastcourseCode = $results['DummyRangeAllocation'][0]['Timetable']['CourseMapping']['Course']['course_code'];
									$LastcourseName = $results['DummyRangeAllocation'][0]['Timetable']['CourseMapping']['Course']['course_name'];
									$board = $results['DummyRangeAllocation'][0]['Timetable']['CourseMapping']['Course']['board'];
								}
								
								$endDummyNumber = $actualDummyNo;
								if($LastcourseCode == $results['DummyRangeAllocation'][0]['Timetable']['CourseMapping']['Course']['course_code'] || ($LastcourseCode =='')){
									$actualDummyNo = $actualDummyNo +1; 
									$p = $p+1;
									$coverCnt++;									
								}
								
							}
							$LastcourseName = $results['DummyRangeAllocation'][0]['Timetable']['CourseMapping']['Course']['course_name'];
							$LastcourseCode = $results['DummyRangeAllocation'][0]['Timetable']['CourseMapping']['Course']['course_code'];
						}
					}
				}
				if($board){
					$bodyHtml .="<table style='width:100%;font:15px Arial;font-weight:bold;' border='0' cellpadding='0' cellspacing='0'>
											<thead>
												<tr><td style='height:36px;'>DUMMY RANGE </td><td align='left' colspan='2'>".$startingDummyNumber." - ".$endDummyNumber."</td><td align='right' style='font:17px Arial;font-weight:bold;'>BOARD - ".$board."</td></tr>
												<tr><td style='height:36px;'>M&YEAR OF EXAM </td><td align='left' colspan='3'>".$txtExamMonthYear."</td></tr>
												<tr><td style='height:36px;'>COURSE NAME </td><td align='left' colspan='3'>".$LastcourseName."</td></tr>
												<tr><td style='height:36px;'>COURSE CODE </td><td align='left'>".$LastcourseCode."</td><td colspan='2' align='right'>(PACKET NO ".$dummyPage." of DUMMY_PAGE / TOTAL_PAGE)</td></tr>
												<tr><td style='height:36px;'>EXAM DATE </td><td align='left'>".$examDate."</td><td colspan='2' align='right'>COUNT - ".$coverCnt."</td></tr>
											</thead>
											</table>";
					$bodyHtml = str_replace('DUMMY_PAGE',$dummyPage,$bodyHtml);
					$fileName = "DUMMY_NO_COVE_PAGE_";
				}
			}
		}
		if($bodyHtml){
			$bodyHtml = str_replace('TOTAL_PAGE',$eachPage,$bodyHtml);
			$this->mPDF->init();
			$this->mPDF->setFilename($fileName.date('d_M_Y').'.pdf');
			$this->mPDF->setOutput('D');
			$this->mPDF->WriteHTML($bodyHtml);
			$this->mPDF->SetWatermarkText("Draft");
		}
		$monthyears = $this->MonthYear->getAllMonthYears();
		$this->set(compact('monthyears'));
		}
		else {
			$this->render('../Users/access_denied');
		}
	}
	public function ExaminerList() {
		$access_result = $this->checkPathAccess($this->Auth->user('id'));
		if (!$access_result) {
		$bodyHtml= "";
		if(isset($this->request->data['ExaminerList']) == 'ExaminerList' || isset($this->request->data['StrengthReport']) == 'StrengthReport'){
			$conditions=array();$headerHtml = "";  $footerHtml = "";	$eachPage = 1;	$LastcourseCode =""; $dummyPage =1;$LastcourseName ="";$board="";
			if(isset($this->request->data['DN']['fromDate']) && ($this->request->data['DN']['fromDate'])){ 
				$conditions['Timetable.exam_date >='] = date("Y-m-d", strtotime($this->request->data['DN']['fromDate']));
			}
			if(isset($this->request->data['DN']['toDate']) && ($this->request->data['DN']['toDate'])){
				$conditions['Timetable.exam_date <='] = date("Y-m-d", strtotime($this->request->data['DN']['toDate']));
			}
			
			$txtExamMonthYear = $this->getExamMonthYear($this->request->data['DN']['monthyears']);
			$head = "<table class='cmainhead2' border='0' align='center'>
							<tr>
							<td rowspan='2'><img src='../webroot/img/user.jpg'></td>
								<td align='center'>SATHYABAMA UNIVERSITY<br/>
								<span class='slogan'>EXAMINER LIST (".$txtExamMonthYear.")</span></td>
							</tr>
							</table>";
			$resDummy = array(
					'conditions' => array('DummyNumber.indicator' => 0,'DummyNumber.month_year_id'=>$this->request->data['DN']['monthyears']
					),
					'fields' =>array('DummyNumber.id','DummyNumber.start_range','DummyNumber.end_range'),
					'contain'=>array(
							'DummyRangeAllocation'=>array('fields' =>array('DummyRangeAllocation.id','DummyRangeAllocation.dummy_number_id','DummyRangeAllocation.timetable_id'),
									'Timetable' =>array(
											'conditions' => $conditions,
											'fields' =>array('Timetable.month_year_id','Timetable.course_mapping_id','Timetable.exam_date','Timetable.exam_session','Timetable.exam_type'),
											'order' => 'Timetable.exam_date',
											'CourseMapping'=>array('fields' =>array('CourseMapping.id'),
													'Course'=>array('fields' =>array('Course.course_code','Course.course_name','Course.board'),
															'order'=>array('Course.board'=>'asc','Course.common_code'=>'asc'),
													),
											),
									),
	
							),
					),
					'group' => 'DummyNumber.start_range',
					'order'=>array('DummyNumber.id'=>'asc'),
					'recursive' => 1
			);
			$dummyAlt = $this->DummyNumber->find("all", $resDummy);

			if(isset($this->request->data['ExaminerList']) == 'ExaminerList'){
				$startingDummyNumber = "";$endDummyNumber = "";$coverCnt =1;$resultsArray = array();
				$headerHtml .="<table style='width:100%;font:13px Arial;font-weight:bold;' border='1' cellpadding='0' cellspacing='0'>
							<tr>
								<td style='height:36px;width:40px;' align='center'>S.No.</td>
								<td align='center' width='80px;'>COURSE CODE</td>
								<td align='center'>COURSE NAME</td>
								<td align='center' width='80px;'>DUMMY NO. START</td>
								<td align='center' width='80px;'>DUMMY NO. END</td>
								<td align='center' width='60px;'>BOARD</td>
								<td align='center' width='60px;'>TOTAL</td>
								<td align='center' width='220px;'>SIGNATURE</td>
							</tr>";
				/* foreach($dummyAlt as $results){
					if(isset($results['DummyNumber']['start_range'])){
						$actualDummyNo = $results['DummyNumber']['start_range'];$p =0;
						if(isset($results['DummyRangeAllocation'][0]['Timetable']['CourseMapping'])){
							$courseName = $results['DummyRangeAllocation'][0]['Timetable']['CourseMapping']['Course']['course_name'];
							$courseCode = $results['DummyRangeAllocation'][0]['Timetable']['CourseMapping']['Course']['course_code'];
							$examDate = date( "d-M-Y", strtotime(h($results['DummyRangeAllocation'][0]['Timetable']['exam_date'])) )." (".$results['DummyRangeAllocation'][0]['Timetable']['exam_session'].")";
							for($i=($results['DummyNumber']['start_range']);$i<=($results['DummyNumber']['end_range']);$i++){
								if($startingDummyNumber == ''){
									if($coverCnt ==''){
										$bodyHtml .= $head.$headerHtml;
										$startingDummyNumber = $i;
									}else{$startingDummyNumber = $i-1;}
								}
								if($p ==1){
									$LastcourseCode = $results['DummyRangeAllocation'][0]['Timetable']['CourseMapping']['Course']['course_code'];
									$LastcourseName = $results['DummyRangeAllocation'][0]['Timetable']['CourseMapping']['Course']['course_name'];
									$board			= $results['DummyRangeAllocation'][0]['Timetable']['CourseMapping']['Course']['board'];
								}
								
								if($i !=($results['DummyNumber']['start_range']) && ($p % 20 == 0) || ($board != $results['DummyRangeAllocation'][0]['Timetable']['CourseMapping']['Course']['board'] && $board !='')){
									$resultsArray[] =
									array(											
											"board" => $board,
											"course_code" => $LastcourseCode,
											"course_name" => $LastcourseName,
											"dummy_start" => $startingDummyNumber,
											"dummy_end" => $endDummyNumber,
											"total" => $coverCnt,
									);
									$eachPage++;$coverCnt = "";$dummyPage++;
									$startingDummyNumber = "";
									$LastcourseCode = $results['DummyRangeAllocation'][0]['Timetable']['CourseMapping']['Course']['course_code'];
									$LastcourseName = $results['DummyRangeAllocation'][0]['Timetable']['CourseMapping']['Course']['course_name'];
									$board = $results['DummyRangeAllocation'][0]['Timetable']['CourseMapping']['Course']['board'];
								}
	
								$endDummyNumber = $actualDummyNo;
								if($LastcourseCode == $results['DummyRangeAllocation'][0]['Timetable']['CourseMapping']['Course']['course_code'] || ($LastcourseCode =='')){
									$actualDummyNo = $actualDummyNo +1; $p = $p+1;$coverCnt++;
								}
	
							}
							$LastcourseCode = $results['DummyRangeAllocation'][0]['Timetable']['CourseMapping']['Course']['course_code'];
							$LastcourseName = $results['DummyRangeAllocation'][0]['Timetable']['CourseMapping']['Course']['course_name'];
							$board			= $results['DummyRangeAllocation'][0]['Timetable']['CourseMapping']['Course']['board'];
						}
					}
				} */
				
				foreach($dummyAlt as $results){
					if(isset($results['DummyNumber']['start_range'])){
						$actualDummyNo = $results['DummyNumber']['start_range'];$p =0;
						if(isset($results['DummyRangeAllocation'][0]['Timetable']['CourseMapping'])){
							$courseName = $results['DummyRangeAllocation'][0]['Timetable']['CourseMapping']['Course']['course_name'];
							$courseCode = $results['DummyRangeAllocation'][0]['Timetable']['CourseMapping']['Course']['course_code'];
							$examDate = date( "d-M-Y", strtotime(h($results['DummyRangeAllocation'][0]['Timetable']['exam_date'])) )." (".$results['DummyRangeAllocation'][0]['Timetable']['exam_session'].")";
							$board	= $results['DummyRangeAllocation'][0]['Timetable']['CourseMapping']['Course']['board'];
							
							//for($i=($results['DummyNumber']['start_range']);$i<=($results['DummyNumber']['end_range']);$i++){
							//if ($results['DummyNumber']['id'] == 1112) {
								//echo $results['DummyNumber']['end_range']."</br>";
								//echo (int)substr($results['DummyNumber']['end_range'], -4);
								$endNumber = (int)substr($results['DummyNumber']['end_range'], -4);
								$beginningFourLetters = substr($results['DummyNumber']['start_range'], 0,4);
								$multiple = (int)(((int)substr($results['DummyNumber']['end_range'], -4))/20);
								//echo $multiple;
								$remainder = (substr($results['DummyNumber']['end_range'], -4))%20;
								//echo " ** ".$remainder;
								$j=0;
								if ($multiple > 0) {
									for ($i=1; $i<=$multiple; $i++) {
										$startNumbers = $beginningFourLetters.str_pad((($j*20)+1),4,"0",STR_PAD_LEFT); 
										$endNumbers = $beginningFourLetters.str_pad(($i*20),4,"0",STR_PAD_LEFT);
										$resultsArray[] =
										array(
												"board" => $board,
												"course_code" => $courseCode,
												"course_name" => $courseName,
												"dummy_start" => $startNumbers,
												"dummy_end" => $endNumbers,
												"total" => ((int)$endNumbers-$startNumbers)+1,
										);
										$j++; $coverCnt++;
									}
								}
								if ($remainder > 0) {
									if ($multiple == 0) $startNumbers = $results['DummyNumber']['start_range'];
									else $startNumbers = $endNumbers+1;
									$endNumbers = $results['DummyNumber']['end_range'];
								 //	echo "</br>".$startNumbers." ".$endNumbers;
								 	
								 	$resultsArray[] =
								 	array(
								 			"board" => $board,
								 			"course_code" => $courseCode,
								 			"course_name" => $courseName,
								 			"dummy_start" => $startNumbers,
								 			"dummy_end" => $endNumbers,
								 			"total" => ((int)$endNumbers-$startNumbers)+1,
								 	);
								 	
								 	$coverCnt++;
								}
								//pr($resultsArray);
								//die;
							//} //end test if
				
							//}
							//$LastcourseCode = $results['DummyRangeAllocation'][0]['Timetable']['CourseMapping']['Course']['course_code'];
							//$LastcourseName = $results['DummyRangeAllocation'][0]['Timetable']['CourseMapping']['Course']['course_name'];
							//$board			= $results['DummyRangeAllocation'][0]['Timetable']['CourseMapping']['Course']['board'];
						}
					}
				}
				
				//if($bodyHtml){
					//if($board){
						/* if(empty($startingDummyNumber)){
							$startingDummyNumber = $endDummyNumber;
						} */
						/* $resultsArray[] =
						array(
								"board" => $board,
								"course_code" => $LastcourseCode,
								"course_name" => $LastcourseName,
								"dummy_start" => $startingDummyNumber,
								"dummy_end" => $endDummyNumber,
								"total" => $coverCnt,
						); */
						$results = "";
						//echo $bodyHtml;
						$bodyHtml .= $head.$headerHtml;
						$results = $this->array_orderby($resultsArray, 'board', SORT_ASC, 'dummy_start', SORT_ASC, 'dummy_end', SORT_ASC, 'total', SORT_DESC);
						$dummyPage =1;$board = "";$eachPage =1;$coverCnt =1;
						foreach ($results as $result){
							if($board != $result['board'] && $board !=''){
								$bodyHtml .= "</table>";
								$bodyHtml .= "<table width='100%'><tr><td align='right'>(".($dummyPage-1)." of ".($eachPage-1)." / TOTAL_PAGE)</td></tr></table>";
								$bodyHtml .= "<div style='page-break-after:always'></div>";
								$bodyHtml .= $head;
								$bodyHtml .= $headerHtml;$dummyPage =1;
							}
							
							$bodyHtml .="<tr>
									<td style='height:30px;' align='center'>".$dummyPage."</td>
									<td align='center'>".$result['course_code']."</td>
									<td>&nbsp;&nbsp;".$result['course_name']."</td>
									<td align='center'>".$result['dummy_start']."</td>
									<td align='center'>".$result['dummy_end']."</td>
									<td align='center'>".$result['board']."</td>
									<td align='center'>".$result['total']."</td>
									<td style='width:180px;'></td>
								</tr>";
							
							if($dummyPage%20 ==0){
								$bodyHtml .= "</table>";
								$bodyHtml .= "<table width='100%'><tr><td align='right'>(".$dummyPage." of ".$eachPage." / TOTAL_PAGE)</td></tr></table>";
								$bodyHtml .= "<div style='page-break-after:always'></div>";
								$bodyHtml .= $head;
								$bodyHtml .= $headerHtml;
							}
							$eachPage++;$coverCnt = "";$dummyPage++;
							$board = $result['board'];
						}
					//}
					
					$bodyHtml = $bodyHtml."</table>";
					$bodyHtml .= "<table width='100%'><tr><td align='right'>(".($dummyPage-1)." of DUMMY_PAGE / TOTAL_PAGE)</td></tr></table>";
					$bodyHtml = str_replace('DUMMY_PAGE',($eachPage-1),$bodyHtml);
					$fileName = "EXAMINER_LIST_";
					$bodyHtml = str_replace('TOTAL_PAGE',($eachPage-1),$bodyHtml);
					$this->mPDF->init();
					$this->mPDF->setFilename($fileName.date('d_M_Y').'.pdf');
					$this->mPDF->setOutput('D');
					$this->mPDF->AddPage('L','', '', '', '',10,10,5,5,18,12);					
					$this->mPDF->setFooter($footerFoilCard);
					$this->mPDF->WriteHTML($bodyHtml);
					$this->mPDF->SetWatermarkText("Draft");
					$this->autoRender=false;
				//}
			}
			
			if(isset($this->request->data['StrengthReport']) == 'StrengthReport'){
				$startingDummyNumber = "";$endDummyNumber = "";$coverCnt ="";$LastcourseName="";$LastBoard = "";
				$headerHtml .="<table style='width:100%;font:13px Arial;font-weight:bold;' border='1' cellpadding='0' cellspacing='0'>
							<tr>
								<td style='height:36px;width:40px;' align='center'>S.No.</td>
								<td align='center' width='200px;'>BOARD</td>
								<td align='center' width='200px;'>TOTAL</td>
							</tr>";
				$head = str_replace('EXAMINER LIST','STRENGTH REPORT',$head);
				$strengthReport = array();
				foreach($dummyAlt as $results){
					if(isset($results['DummyNumber']['start_range'])){
						$actualDummyNo = $results['DummyNumber']['start_range'];$p =0;
						if(isset($results['DummyRangeAllocation'][0]['Timetable']['CourseMapping'])){
							$courseName = $results['DummyRangeAllocation'][0]['Timetable']['CourseMapping']['Course']['course_name'];
							$courseCode = $results['DummyRangeAllocation'][0]['Timetable']['CourseMapping']['Course']['course_code'];
							$examDate = date( "d-M-Y", strtotime(h($results['DummyRangeAllocation'][0]['Timetable']['exam_date'])) )." (".$results['DummyRangeAllocation'][0]['Timetable']['exam_session'].")";
							for($i=($results['DummyNumber']['start_range']);$i<=($results['DummyNumber']['end_range']);$i++){
								if($startingDummyNumber == ''){
									if($coverCnt ==''){
										$head = str_replace('EXAMINER LIST','STRENGTH REPORT',$head);
										$bodyHtml .= $head.$headerHtml;
										$startingDummyNumber = $i;
									}else{$startingDummyNumber = $i-1;}
								}
								if($p ==1){
									$LastcourseCode = $results['DummyRangeAllocation'][0]['Timetable']['CourseMapping']['Course']['course_code'];
									$LastcourseName = $results['DummyRangeAllocation'][0]['Timetable']['CourseMapping']['Course']['course_name'];
									$LastBoard			= $results['DummyRangeAllocation'][0]['Timetable']['CourseMapping']['Course']['board'];
									//$strengthReport[$LastBoard] = 1;
								}
			
								if($LastBoard != $results['DummyRangeAllocation'][0]['Timetable']['CourseMapping']['Course']['board'] && $LastcourseCode !=''){
									/*	
									$bodyHtml .="<tr>
													<td style='height:40px;' align='center'>".$eachPage."</td>
													<td align='center'>".$LastBoard."</td>
													<td align='center'>".$coverCnt."</td>
												</tr>";*/
									
									$eachPage++;$coverCnt = "";$dummyPage++;
									$startingDummyNumber = "";
									$LastcourseCode = $results['DummyRangeAllocation'][0]['Timetable']['CourseMapping']['Course']['course_code'];
									$LastcourseName = $results['DummyRangeAllocation'][0]['Timetable']['CourseMapping']['Course']['course_name'];
								}
			
								$endDummyNumber = $actualDummyNo;
								if($LastcourseCode == $results['DummyRangeAllocation'][0]['Timetable']['CourseMapping']['Course']['course_code'] || ($LastcourseCode =='')){
									$actualDummyNo = $actualDummyNo +1; $p = $p+1;$coverCnt++;
									if($LastBoard){
										@$strengthReport[$LastBoard] += 1;
									}
								}
			
							}
							$LastcourseCode = $results['DummyRangeAllocation'][0]['Timetable']['CourseMapping']['Course']['course_code'];
							$LastBoard = $results['DummyRangeAllocation'][0]['Timetable']['CourseMapping']['Course']['board'];
						}
					}
				}@$strengthReport[$LastBoard] += 1;$q=1;
				foreach ($strengthReport as $key => $val){
					if($key){
					$bodyHtml .="<tr>
									<td style='height:40px;' align='center'>".$q."</td>
									<td align='center'>".$key."</td>
									<td align='center'>".$val."</td>
								</tr>";
					$q++;}
				}
				if($bodyHtml){
					$bodyHtml = $bodyHtml."</table>";
					$bodyHtml = str_replace('DUMMY_PAGE',$dummyPage,$bodyHtml);
					$fileName = "STRENGTH_REPORT_";
					$bodyHtml = str_replace('TOTAL_PAGE',$eachPage,$bodyHtml);
					$this->mPDF->init();
					$this->mPDF->setFilename($fileName.date('d_M_Y').'.pdf');
					$this->mPDF->setOutput('D');
					$this->mPDF->setFooter($footerFoilCard);
					$this->mPDF->WriteHTML($bodyHtml);
					$this->mPDF->SetWatermarkText("Draft");
					$this->autoRender=false;
				}
			}
			if($bodyHtml == ""){
				$this->Flash->error(__('Record Not Available.'));
			}
		}
		
		$monthyears = $this->MonthYear->getAllMonthYears();
		$this->set(compact('monthyears'));
		}
		else {
			$this->render('../Users/access_denied');
		}
	}
	public function emFoilCard($examYear = null,$exam_date = null){		
		$conditions=array();
		if($exam_date){
			$conditions['Timetable.exam_date'] = date("Y-m-d", strtotime($exam_date));
		}
		$resDummy = array(
			'conditions' => array('DummyNumber.indicator' => 0,'DummyNumber.month_year_id'=>$examYear
			),
			'fields' =>array('DummyNumber.id','DummyNumber.start_range'),				
			'contain'=>array(
				'DummyRangeAllocation'=>array('fields' =>array('DummyRangeAllocation.id','DummyRangeAllocation.dummy_number_id','DummyRangeAllocation.timetable_id'),
					'Timetable' =>array(
						'conditions' => $conditions,
						'fields' =>array('Timetable.month_year_id','Timetable.course_mapping_id','Timetable.exam_date','Timetable.exam_session','Timetable.exam_type')
					),
				),
				'DummyNumberAllocation'=>array('fields' =>array('DummyNumberAllocation.id')),
			),
			'group' => 'DummyNumber.start_range',
			'recursive' => 1
		);
		$dummyAlt = $this->DummyNumber->find("all", $resDummy);
		$this->set('dummyNos', $dummyAlt);
		$this->layout=false;
	}

/**
 * view method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function view($id = null) {
		if (!$this->DummyNumberAllocation->exists($id)) {
			throw new NotFoundException(__('Invalid dummy number allocation'));
		}
		$options = array('conditions' => array('DummyNumberAllocation.' . $this->DummyNumberAllocation->primaryKey => $id));
		$this->set('dummyNumberAllocation', $this->DummyNumberAllocation->find('first', $options));
	}

/**
 * add method
 *
 * @return void
 */
	public function add($DNId = null,$printMode = null){		
		$result = array(
			'conditions' => array('DummyNumber.id' => $DNId),
			'fields' =>array('DummyNumber.id','DummyNumber.start_range','DummyNumber.end_range'),
			'contain'=>array(
				'DummyNumberAllocation' =>array('fields' =>array('DummyNumberAllocation.id','DummyNumberAllocation.dummy_number_id','DummyNumberAllocation.dummy_number','DummyNumberAllocation.student_id'),
						'conditions' => array('DummyNumberAllocation.indicator' =>0),'order'=>array('DummyNumberAllocation.dummy_number'=>'asc'),
					'Student'=>array('fields' =>array('Student.id','Student.name','Student.registration_number')),
				),
				'DummyRangeAllocation'=>array('fields' =>array('DummyRangeAllocation.id','DummyRangeAllocation.timetable_id'),
					'Timetable'=>array('fields' =>array('Timetable.id'),
						'CourseMapping'=>array('fields'=>array('CourseMapping.id'),
							'Program'=>array('fields' =>array('Program.program_name'),
								'Academic'=>array('fields' =>array('Academic.academic_name'))
							),
							'Course'=>array('fields' =>array('Course.course_code','Course.course_name','Course.max_ese_qp_mark')),
							'Batch'=>array('fields' =>array('Batch.batch_from','Batch.batch_to','Batch.academic')),
						),
					),
				),
			),
			'order'=>array('DummyNumber.id'=>'asc'),
			'recursive' => 1
		);
		$results = $this->DummyNumber->find("all", $result);	
		
		$DNassignedValue = array();
		if($results[0]['DummyNumberAllocation']){
			foreach($results[0]['DummyNumberAllocation'] as $key=>$value){ //echo "===>Key".$key." ***".$value['dummy_number'];				
				$DNassignedValue[$value['dummy_number']] = $value;			
			}
		}	
		$this->set(compact('results',$results));
		$this->set(compact('DNassignedValue',$DNassignedValue));
		$this->set(compact('DNId',$DNId));
		
		if($printMode == 'P') {		
			if(isset($results[0]['DummyNumber']['id'])){
				$getTTId = array(
						'conditions' => array('DummyRangeAllocation.dummy_number_id' => $results[0]['DummyNumber']['id']),
						'fields' =>array('DummyRangeAllocation.timetable_id'),
						'contain'=>array(
						),
						'order'=>array('DummyRangeAllocation.id'=>'asc'),
						'recursive' => 1
				);
				$getTTIds = $this->DummyRangeAllocation->find("all", $getTTId);
				
				
				$getCourseInfo = array(
					'conditions' => array('Timetable.id ' => $getTTIds[0]['DummyRangeAllocation']['timetable_id'],'Timetable.indicator '=> 0),
					'fields' =>array('Timetable.id','Timetable.exam_type'),
					'contain'=>array(
						'MonthYear'=>array('fields' =>array('MonthYear.year'),
								'Month'=>array('fields' =>array('Month.month_name'))
						),
						'CourseMapping'=>array('fields'=>array('CourseMapping.id',  'CourseMapping.program_id', 'CourseMapping.course_number', 'CourseMapping.course_mode_id','CourseMapping.semester_id', 'CourseMapping.month_year_id'),
								'Program'=>array('fields' =>array('Program.program_name'),
										'Academic'=>array('fields' =>array('Academic.academic_name'))
								),
								'Course'=>array('fields' =>array('Course.course_code','Course.course_name','Course.max_ese_qp_mark')),
								'Batch'=>array('fields' =>array('Batch.batch_from','Batch.batch_to','Batch.academic')),
						),
					),					
					'recursive' => 1
				);
				$getCourseInfos = $this->Timetable->find("all", $getCourseInfo);
				
				if(isset($getCourseInfos)){
					$monthYear = $getCourseInfos[0]['MonthYear']['Month']['month_name']."-".$getCourseInfos[0]['MonthYear']['year'];
					$batch = $getCourseInfos[0]['CourseMapping']['Batch']['batch_from']."-".$getCourseInfos[0]['CourseMapping']['Batch']['batch_to'];
					$subject = $getCourseInfos[0]['CourseMapping']['Course']['course_name'];
					$subjectCode = $getCourseInfos[0]['CourseMapping']['Course']['course_code'];
					$courseMaxMark = $getCourseInfos[0]['CourseMapping']['Course']['max_ese_qp_mark'];				
					$examType = $getCourseInfos[0]['Timetable']['exam_type'];
					
					$this->set(compact('monthYear','batch','subject','subjectCode','courseMaxMark','examType'));			
					
					$this->layout= false;
					$this->layout= 'print';
					$this->render('foil_card_print');
					return false;
				}
			}
		}		
	}	


/**
 * edit method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function edit($DNId = null,$printMode = null){		
		$result = array(
			'conditions' => array('DummyNumber.id' => $DNId),
			'fields' =>array('DummyNumber.id','DummyNumber.start_range','DummyNumber.end_range'),
			'contain'=>array(
				'DummyNumberAllocation' =>array('fields' =>array('DummyNumberAllocation.id','DummyNumberAllocation.dummy_number_id','DummyNumberAllocation.dummy_number','DummyNumberAllocation.student_id'),
						'conditions' => array('DummyNumberAllocation.indicator' =>0),'order'=>array('DummyNumberAllocation.dummy_number'=>'asc'),
					'Student'=>array('fields' =>array('Student.id','Student.name','Student.registration_number')),
				),
				'DummyRangeAllocation'=>array('fields' =>array('DummyRangeAllocation.id','DummyRangeAllocation.timetable_id'),
					'Timetable'=>array('fields' =>array('Timetable.id'),
						'CourseMapping'=>array('fields'=>array('CourseMapping.id'),
							'Program'=>array('fields' =>array('Program.program_name'),
								'Academic'=>array('fields' =>array('Academic.academic_name'))
							),
							'Course'=>array('fields' =>array('Course.course_code','Course.course_name','Course.max_ese_qp_mark')),
							'Batch'=>array('fields' =>array('Batch.batch_from','Batch.batch_to','Batch.academic')),
						),
					),
				),
			),
			'order'=>array('DummyNumber.id'=>'asc'),
			'recursive' => 1
		);
		$results = $this->DummyNumber->find("all", $result);	
		
		$DNassignedValue = array();
		if($results[0]['DummyNumberAllocation']){
			foreach($results[0]['DummyNumberAllocation'] as $key=>$value){ //echo "===>Key".$key." ***".$value['dummy_number'];				
				$DNassignedValue[$value['dummy_number']] = $value;			
			}
		}	
		$this->set(compact('results',$results));
		$this->set(compact('DNassignedValue',$DNassignedValue));
		$this->set(compact('DNId',$DNId));
	}	

/**
 * delete method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function delete($id = null) {
		$this->DummyNumberAllocation->id = $id;
		if (!$this->DummyNumberAllocation->exists()) {
			throw new NotFoundException(__('Invalid dummy number allocation'));
		}
		$this->request->allowMethod('post', 'delete');
		if ($this->DummyNumberAllocation->delete()) {
			return $this->flash(__('The dummy number allocation has been deleted.'), array('action' => 'index'));
		} else {
			return $this->flash(__('The dummy number allocation could not be deleted. Please, try again.'), array('action' => 'index'));
		}
	}
	
	public function searchIndex($examYear = null){		
		$resDummy = array(
			'conditions' => array('DummyNumber.indicator' => 0,'DummyNumber.month_year_id'=>$examYear,
			),
			'fields' =>array('DummyNumber.id','DummyNumber.start_range','DummyNumber.end_range'),				
			'contain'=>array(
				'DummyRangeAllocation'=>array('fields' =>array('DummyRangeAllocation.id','DummyRangeAllocation.dummy_number_id','DummyRangeAllocation.timetable_id'),
					'Timetable' =>array('fields' =>array('Timetable.month_year_id','Timetable.course_mapping_id','Timetable.exam_date','Timetable.exam_session','Timetable.exam_type'),
						'CourseMapping'=>array('fields' =>array('CourseMapping.id'),
							'Course'=>array('fields' =>array('Course.course_code','Course.course_name'),'order'=>array('Course.common_code'),),
						),							
					),
				),
				'DummyNumberAllocation'=>array('fields' =>array('DummyNumberAllocation.id')),
			),
			'group' => 'DummyNumber.start_range',
		);
		$dummyAlt = $this->DummyNumber->find("all", $resDummy);
		//pr($dummyAlt);
		$this->set('dummyNos', $dummyAlt);
		$this->layout=false;
	}
	
	public function dnToRg($DNId = null, $DN = null, $rgNo = null, $autoId = null){
		$studentId = $this->Student->getStudentId($rgNo);
		
		$autoGenId = array();
		if($autoId != '-'){
			$autoGenId['DummyNumberAllocation.id != '] = $autoId;
		}
		
		$data = array();
		$data['DummyNumberAllocation']['dummy_number_id']	= $DNId;
		$data['DummyNumberAllocation']['dummy_number']	  	= $DN;
		
		if($studentId){
			$data['DummyNumberAllocation']['student_id'] 		= $studentId;
			//Check record already exists or not
			$chk =$this->DummyNumberAllocation->find('first',
					array('conditions' => array(
							'DummyNumberAllocation.dummy_number_id' => $DNId,
							'DummyNumberAllocation.student_id' =>$studentId,$autoGenId
					),'recursive'=>1));
			$record_id = "";
			if($chk){
				echo "Register Number Already Exists.";die;
			}
		}else{
			echo "Invalid Register Number";die;
		}
		
		$result = array(
			'conditions' => array('DummyRangeAllocation.dummy_number_id' => $DNId),
			'fields' =>array('DummyRangeAllocation.timetable_id'),
			'contain'=>array(
			),
			'order'=>array('DummyRangeAllocation.id'=>'asc'),
			'recursive' => 1
		);
		$results = $this->DummyRangeAllocation->find("all", $result);
		if(empty($results)){ echo "Invalid Register Number";die;}
		
		$totTTId = array();		
		foreach($results as $eachTT){
			$totTTId[] = $eachTT['DummyRangeAllocation']['timetable_id'];
		}
		//$totTTId = rtrim($totTTId,",");
	
		$totTimeTableId = array();
		if($totTTId){
			$totTimeTableId['ExamAttendance.timetable_id'] = $totTTId;
			$totTimeTableId['ExamAttendance.attendance_status '] = 1;
		
		}
		$chkExamAttendacne = array(
				'conditions' => $totTimeTableId,
				'fields' =>array('ExamAttendance.id','ExamAttendance.timetable_id','ExamAttendance.student_id','ExamAttendance.student_id','ExamAttendance.attendance_status'),
				'contain'=>array(
					'Student'=>array('fields' =>array('Student.id','Student.name','Student.registration_number'),
						'conditions' => array('Student.registration_number' => $rgNo)
					),
				),
				'order'=>array('Student.registration_number'=>'asc'),
				'recursive' => 1
		);
		$examAttendance = $this->ExamAttendance->find("all", $chkExamAttendacne);
		
		$action = false;
		if($examAttendance){
		foreach($examAttendance as $eachStuRegNo){
			if($rgNo == $eachStuRegNo['Student']['registration_number']){
				$action = true;
			}
		}}else{	echo "Invalid Register Number";die;}
		
		if($action == true){
			$data['DummyNumberAllocation']['indicator'] 		= 0;
			//Update check
			$chk =$this->DummyNumberAllocation->find('first',
					array('conditions' => array(
							'DummyNumberAllocation.dummy_number_id' => $DNId,
							'DummyNumberAllocation.dummy_number' =>$DN
					),'recursive'=>1));
			$record_id = "";
			if($chk){
				$data['DummyNumberAllocation']['modified_by'] 	= $this->Auth->user('id');
				$data['DummyNumberAllocation']['id'] 			= $chk['DummyNumberAllocation']['id'];
			}else{
				$data['DummyNumberAllocation']['created_by'] 		= $this->Auth->user('id');
				$this->DummyNumberAllocation->create();
			}
			try{
				if($this->DummyNumberAllocation->save($data)){
					//echo "Record saved";
				}
			}
			catch(Exception $e){
				echo "Register Number Already Exists";die;//$e->getMessage();
			}
		}else{	echo "This Register Number Was Absent. Couldn't Be Saved.";die;}
		die;
	}
	
	public function resetDN($dnId) {
		$this->DummyNumber->query("delete from dummy_number_allocations where dummy_number_id=".$dnId);
		return $this->redirect(__('The dummy number allocation has been deleted.'), array('action' => 'add/'.$dnId));
	}
}
