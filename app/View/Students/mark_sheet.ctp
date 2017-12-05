
<?php

$a = 1;
if ($results) { 

	foreach ( $results as $k => $result ) { //pr($result);
		$courseMark = "";
		
		$stuInternalArray = array ();
		$stuESArray = array ();
		$stuFinalMark = array ();
		$stuMarkStatus = array ();
		$examMonthYear = "";
		$publishing_date = array ();
		$CourseCP = "";
		$courseMark1 = "";
		$courseMark2 = "";
		$courseMark3 = "";
		$html1 = "";
		$html2 = "";
		$html4 = "";
		
		$batchId = $result['Batch']['id'];
		$programId = $result['Program']['id'];
		
		$TotCreditPoints ="";$earnCreditPoints = "";$semesterPassCnt = "0";$noOfCourses = 0;$noOfArrears = "";
		
		$stuSemesterMark1 = "";$stuSemesterMark2 = "";$stuSemesterMark3 = "";$stuSemesterMark4 = "";$stuSemesterMark5 = "";
		$stuSemesterMark6 = "";$stuSemesterMark7 = "";$stuSemesterMark8 = "";$stuSemesterMark9 = "";$stuSemesterMark10 = "";
		
		$stuSemesterMark1_1 = "";$stuSemesterMark2_1 = "";$stuSemesterMark3_1 = "";$stuSemesterMark4_1 = "";$stuSemesterMark5_1 = "";
		$stuSemesterMark6_1 = "";$stuSemesterMark7_1 = "";$stuSemesterMark8_1 = "";$stuSemesterMark9_1 = "";$stuSemesterMark10_1 = "";

		$arrearStuSemesterMark1_1 = "";$arrearStuSemesterMark2_1 = "";$arrearStuSemesterMark3_1 = "";$arrearStuSemesterMark4_1 = "";$arrearStuSemesterMark5_1 = "";
		$arrearStuSemesterMark6_1 = "";$arrearStuSemesterMark7_1 = "";$arrearStuSemesterMark8_1 = "";$arrearStuSemesterMark9_1 = "";$arrearStuSemesterMark10_1 = "";
		
		$semester1CourseReg = "0";$semester2CourseReg = "0";$semester3CourseReg = "0";$semester4CourseReg = "0";$semester5CourseReg = "0";
		$semester6CourseReg = "0";$semester7CourseReg = "0";$semester8CourseReg = "0";$semester9CourseReg = "0";$semester10CourseReg = "0";
		
		$CourseRegSemester1 = "0";$CourseRegSemester2 = "0";$CourseRegSemester3 = "0";$CourseRegSemester4 = "0";$CourseRegSemester5 = "0";
		$CourseRegSemester6 = "0";$CourseRegSemester7 = "0";$CourseRegSemester8 = "0";$CourseRegSemester9 = "0";$CourseRegSemester10 = "0";
		
		$semester1CourseCreditEarned = "0";$semester2CourseCreditEarned = "0";$semester3CourseCreditEarned = "0";$semester4CourseCreditEarned = "0";$semester5CourseCreditEarned = "0";
		$semester6CourseCreditEarned = "0";$semester7CourseCreditEarned = "0";$semester8CourseCreditEarned = "0";$semester9CourseCreditEarned = "0";$semester10CourseCreditEarned = "0";
		
		$semester1GradePointEarned = "0";$semester2GradePointEarned = "0";$semester3GradePointEarned = "0";$semester4GradePointEarned = "0";$semester5GradePointEarned = "0";
		$semester6GradePointEarned = "0";$semester7GradePointEarned = "0";$semester8GradePointEarned = "0";$semester9GradePointEarned = "0";$semester10GradePointEarned = "0";
		
		$semester1gpa=0;$semester2gpa=0;$semester3gpa=0;$semester4gpa=0;$semester5gpa=0;$semester6gpa=0;$semester7gpa=0;
		$semester8gpa=0;$semester9gpa=0;$semester10gpa=0;
		
		$arrearSemester1CourseCreditEarned = "0";$arrearSemester2CourseCreditEarned = "0";$arrearSemester3CourseCreditEarned = "0";$arrearSemester4CourseCreditEarned = "0";$arrearSemester5CourseCreditEarned = "0";
		$arrearSemester6CourseCreditEarned = "0";$arrearSemester7CourseCreditEarned = "0";$arrearSemester8CourseCreditEarned = "0";$arrearSemester9CourseCreditEarned = "0";$arrearSemester10CourseCreditEarned = "0";
		
		$arrearSemester1GradePointEarned = "0";$arrearSemester2GradePointEarned = "0";$arrearSemester3GradePointEarned = "0";$arrearSemester4GradePointEarned = "0";$arrearSemester5GradePointEarned = "0";
		$arrearSemester6GradePointEarned = "0";$arrearSemester7GradePointEarned = "0";$arrearSemester8GradePointEarned = "0";$arrearSemester9GradePointEarned = "0";$arrearSemester10GradePointEarned = "0";
		
		$arrearSemester1gpa=0;$arrearSemester2gpa=0;$arrearSemester3gpa=0;$arrearSemester4gpa=0;$arrearSemester5gpa=0;
		$arrearSemester6gpa=0;$arrearSemester7gpa=0;$arrearSemester8gpa=0;$arrearSemester9gpa=0;$arrearSemester10gpa=0;
		
		$arrearSemester1CourseReg = "0";$arrearSemester2CourseReg = "0";$arrearSemester3CourseReg = "0";$arrearSemester4CourseReg = "0";$arrearSemester5CourseReg = "0";
		$arrearSemester6CourseReg = "0";$arrearSemester7CourseReg = "0";$arrearSemester8CourseReg = "0";$arrearSemester9CourseReg = "0";$arrearSemester10CourseReg = "0";
		
		$finalArray = array();
		
		if (isset($result['ParentGroup']['StudentMark']) && count($result['ParentGroup']['StudentMark'])>0) {
			for($p = 0; $p < count($result['ParentGroup']['StudentMark']); $p++) {
				$month_year_id = $result['ParentGroup']['StudentMark'][$p]['month_year_id'];
				if (isset($finalArray[$month_year_id])) {
					$finalArray[$month_year_id][] = $result['ParentGroup']['StudentMark'][$p];
				} else {
					$finalArray[$month_year_id] = array($result['ParentGroup']['StudentMark'][$p]);
				}
			}
		}
		
		//pr ($result['StudentMark']);
		
		for($p = 0; $p < count($result['StudentMark']); $p++) {
			$month_year_id = $result['StudentMark'][$p]['month_year_id'];
			if (isset($finalArray[$month_year_id])) {
				$finalArray[$month_year_id][] = $result['StudentMark'][$p];
			} else {
				$finalArray[$month_year_id] = array($result['StudentMark'][$p]);
			}
		}
		//pr($finalArray);
		
		$cgpa=0;
		$numerator=0;
		$denominator=0;
		$p=0;
		
		$cmArray = array();
		$csm_array = $result['CourseStudentMapping'];
		foreach ($csm_array as $key => $cmValue) {
			$cmArray[$cmValue['course_mapping_id']] = $cmValue['type'];
		}
		//pr($cmArray);
		
		if (!empty($result['StudentAuditCourse']) && count($result['StudentAuditCourse']) >0) {
			$audit_courses = $result['StudentAuditCourse'];
		}
		
		//$totCount = count($finalArray[$examMonth]);
		//echo "totCount ".$totCount."</br>";
		
		$cntt = 0;
		$markSheetArray = array();
		
		foreach ($finalArray as $month_year_id => $value){ //echo count($value);
			foreach ($value as $key => $stuValue) {
				//pr($stuValue);
				$creditTransferred = "";
				$courseMId = $stuValue['course_mapping_id'];
				
				$examMonthYear = $stuValue['MonthYear']['Month']['month_name']."-".$stuValue['MonthYear']['year'];
				$stuMarkSemester[$courseMId] = $stuValue['CourseMapping']['semester_id'];
				$originalSemesterId = $stuValue['CourseMapping']['semester_id'];
				$publishing_date = $stuValue['CourseMapping']['MonthYear']['publishing_date'];
				
				//echo "</br>". $stuValue['CourseMapping']['Course']['course_name'];
				if($stuValue['revaluation_status'] == 0){
					$stuFinalMark[$courseMId] = $stuValue['marks'];
					$stuMarkStatus[$courseMId] = $stuValue['status'];
				}else{
					$stuFinalMark[$courseMId] = $stuValue['final_marks'];
					$stuMarkStatus[$courseMId] = $stuValue['final_status'];				
				}
				
				//echo "</br>".$courseMId." ".$stuMarkStatus[$courseMId];
				
				$CourseCP = $stuValue['CourseMapping']['Course']['credit_point'];
				
				//echo "</br>CM Id : ".$courseMId." ".$examMonthYear." Semester ".$stuMarkSemester[$courseMId]." ".
				$publishing_date." Mark ".$stuFinalMark[$courseMId]." Status ".$stuMarkStatus[$courseMId]." Credit Point ".
				$CourseCP;
				
				if(isset($stuFinalMark[$courseMId])){
					$RANGE_OF_MARKS_FOR_GRADES = $stuValue['grade_point'];
					$noOfCourses = $noOfCourses +1;
					$tmpCredit = 0;
					for($sem=1; $sem<=10; $sem++) {
						if($stuMarkSemester[$courseMId] == $sem){ 
							//echo " Sem".$sem."CourseReg ".${"semester".$sem."CourseReg"}." ".$CourseCP."</br>";
							${"semester".$sem."CourseReg"} = ${"semester".$sem."CourseReg"} + $CourseCP;
							if(($stuMarkStatus[$courseMId] == 'Pass' && $stuValue['grade']<>'ABS')){
								${"semester".$sem."CourseCreditEarned"} = ${"semester".$sem."CourseCreditEarned"} + $CourseCP;
								${"semester".$sem."GradePointEarned"} = ${"semester".$sem."GradePointEarned"} + $RANGE_OF_MARKS_FOR_GRADES;
							}
							${"stuSemesterMark".$sem."_1"} = ${"stuSemesterMark".$sem."_1"}+($stuValue['CourseMapping']['Course']['credit_point']*$RANGE_OF_MARKS_FOR_GRADES);
						}
						else { 
							//echo "</br>else".$courseMId." ".$stuMarkStatus[$courseMId];
							if ($stuValue['month_year_id'] != $stuValue['CourseMapping']['month_year_id']) {
								${"arrearSemester".$stuValue['CourseMapping']['semester_id']."CourseReg"} = ${"arrearSemester".$stuValue['CourseMapping']['semester_id']."CourseReg"} + $CourseCP;
								if($stuMarkStatus[$courseMId] == 'Pass'){
									${"arrearSemester".$stuValue['CourseMapping']['semester_id']."CourseCreditEarned"} = ${"arrearSemester".$stuValue['CourseMapping']['semester_id']."CourseCreditEarned"} + $CourseCP;
									${"arrearSemester".$stuValue['CourseMapping']['semester_id']."GradePointEarned"} = ${"arrearSemester".$stuValue['CourseMapping']['semester_id']."GradePointEarned"} + $RANGE_OF_MARKS_FOR_GRADES;
									
									${"arrearStuSemesterMark".$stuValue['CourseMapping']['semester_id']."_1"} = 
								${"arrearStuSemesterMark".$stuValue['CourseMapping']['semester_id']."_1"}+
								($stuValue['CourseMapping']['Course']['credit_point']*$RANGE_OF_MARKS_FOR_GRADES);
								
								//echo "ArrearSem : ".${"arrearSemester".$stuValue['CourseMapping']['semester_id']."CourseReg"};
								${"arrearSemester".$stuValue['CourseMapping']['semester_id']."gpa"} = 
									sprintf('%0.2f', ${"arrearStuSemesterMark".$stuValue['CourseMapping']['semester_id']."_1"} / 
									${"arrearSemester".$stuValue['CourseMapping']['semester_id']."CourseCreditEarned"});
								} 
								
								//echo $stuValue['CourseMapping']['id']." ** ".$stuMarkStatus[$courseMId]." ** ".
								$stuValue['CourseMapping']['Course']['course_name']." ** ".
								${"arrearSemester".$stuValue['CourseMapping']['semester_id']."CourseReg"}." ** ".
								${"arrearSemester".$stuValue['CourseMapping']['semester_id']."CourseCreditEarned"}." ** ".
								${"arrearSemester".$stuValue['CourseMapping']['semester_id']."GradePointEarned"}."</br>"; 
								break;
							}
						}
						//echo "<br> Semester $sem : ".${"semester".$sem."CourseReg"}." Course_Credit_Sem_".$sem."_Earned ".${"semester".$sem."CourseCreditEarned"}." Grade ".$RANGE_OF_MARKS_FOR_GRADES." __".${"stuSemesterMark".$sem."_1"}." ";
						//${"stuSemesterMark".$sem."_1"}
						if (!empty(${"stuSemesterMark".$sem."_1"}) && !empty(${"semester".$sem."CourseCreditEarned"})) {
							${"semester".$sem."gpa"} = sprintf('%0.2f', ${"stuSemesterMark".$sem."_1"} / ${"semester".$sem."CourseCreditEarned"});
							//echo "Sem".$sem." GPA ".${"semester".$sem."gpa"};
						}
						if (!empty(${"arrearStuSemesterMark".$stuValue['CourseMapping']['semester_id']."_1"}) && !empty(${"arrearSemester".$stuValue['CourseMapping']['semester_id']."CourseCreditEarned"})) {
							${"arrearSemester".$stuValue['CourseMapping']['semester_id']."gpa"} = 
							sprintf('%0.2f', ${"arrearStuSemesterMark".$stuValue['CourseMapping']['semester_id']."_1"} / 
							${"arrearSemester".$stuValue['CourseMapping']['semester_id']."CourseCreditEarned"});
						}
						
					}
					/*if($p < 20){
		  				$sheet = 1;
					}else if($p < 40){
						$sheet = 2;
					}else if($p < 60){
						$sheet = 3;
					}*/
			  
			  		//echo $cntt++." ".$stuValue['CourseMapping']['id']."</br>";
					  	if($stuMarkStatus[$courseMId] == 'Fail' && ($stuValue['grade'] == 'W' || $stuValue['grade'] == 'ABS')){
							$markSheetGrade = $stuValue['grade'];
						} else if ($stuMarkStatus[$courseMId] == 'Fail') {
							$markSheetGrade = 'RA';
						} else if($stuMarkStatus[$courseMId] == 'Pass' && ($stuValue['grade'] == 'W' || $stuValue['grade'] == 'ABS')){
							$markSheetGrade = $stuValue['grade']; 
						} else {
							$markSheetGrade = $stuMarkStatus[$courseMId];
						}
						
						if($stuMarkStatus[$courseMId] == 'Fail'){
							$markSheetGradePoint = "0";
						}else {
							if($stuValue['grade_point']){
								$markSheetGradePoint = $stuValue['grade_point'];
							}else{
								$markSheetGradePoint = "0";
							}
						}		
							
						if (isset($stuValue['CourseMapping']['id']) && isset($cmArray[$stuValue['CourseMapping']['id']]) && $cmArray[$stuValue['CourseMapping']['id']] == "BTFOU") {
							$creditTransferred = "&nbsp;&nbsp;(Credits transferred)";
						}
						
						if ($stuValue['month_year_id'] == $examMonth || $stuMarkStatus[$courseMId]=='Fail') {
						  $markSheetArray[$stuValue['CourseMapping']['id']] = array(
						  	'semester_id' => $stuValue['CourseMapping']['semester_id'],
						  	'course_code' => $stuValue['CourseMapping']['Course']['course_code'],
						  	'course_name' => $stuValue['CourseMapping']['Course']['course_name'].$creditTransferred,
						  	'credit_point' => $stuValue['CourseMapping']['Course']['credit_point'],
						  	'grade' => $stuValue['grade'],
						  	'grade_point' => $markSheetGradePoint,
						  	'status' => $markSheetGrade,
						  );
					  }
					  
					  /*if ($stuValue['month_year_id'] == $examMonth) {
					  	$p++; 
						${'courseMark'.$sheet} .= "<tr>";
						${'courseMark'.$sheet} .= "<td align='center'><strong>".$stuValue['CourseMapping']['semester_id']."</strong></td>";
						${'courseMark'.$sheet} .= "<td align='left' style='padding-left:10px;'><strong>".$stuValue['CourseMapping']['Course']['course_code']."</strong></td>";
						${'courseMark'.$sheet} .= "<td align='left'><strong>".$stuValue['CourseMapping']['Course']['course_name'];
						//."</strong></td>";
						if (isset($stuValue['CourseMapping']['id']) && isset($cmArray[$stuValue['CourseMapping']['id']]) && $cmArray[$stuValue['CourseMapping']['id']] == "BTFOU") {
							${'courseMark'.$sheet} .= "&nbsp;&nbsp;(Credits transferred)";
						}
						${'courseMark'.$sheet} .= "</strong></td>";
						${'courseMark'.$sheet} .= "<td align='center'><strong>".$stuValue['CourseMapping']['Course']['credit_point']."</strong></td>";
						${'courseMark'.$sheet} .= "<td align='left' style='padding-left:14px;'><strong>".$stuValue['grade']."</strong></td>";
						${'courseMark'.$sheet} .= "<td align='center'><strong>";
							if($stuMarkStatus[$courseMId] == 'Fail'){
								${'courseMark'.$sheet} .= "0";
							}else {
								if($stuValue['grade_point']){
									${'courseMark'.$sheet} .= $stuValue['grade_point'];
								}else{
									${'courseMark'.$sheet} .= "0";
								}
							}		
						${'courseMark'.$sheet} .= "</strong></td>";		
						${'courseMark'.$sheet} .= "<td align='center'><strong>";
							if($stuMarkStatus[$courseMId] == 'Fail' && ($stuValue['grade'] == 'W' || $stuValue['grade'] == 'ABS')){
								${'courseMark'.$sheet} .= $stuValue['grade'];
							} else if ($stuMarkStatus[$courseMId] == 'Fail') {
								${'courseMark'.$sheet} .= 'RA';
							} else if($stuMarkStatus[$courseMId] == 'Pass' && ($stuValue['grade'] == 'W' || $stuValue['grade'] == 'ABS')){
								${'courseMark'.$sheet} .= $stuValue['grade']; 
							} else {
								${'courseMark'.$sheet} .=$stuMarkStatus[$courseMId];
							}
						${'courseMark'.$sheet} .= "</strong></td>";
						${'courseMark'.$sheet} .= "</tr>";
					}*/
				}
			}
		}
		unset($csm_array);
		$totCount = count($markSheetArray);
		//pr($markSheetArray);
		
		foreach ($markSheetArray as $msCmId => $msValue) {
			if($p < 21){
		  		$sheet = 1;
			}else if($p < 42){
				$sheet = 2;
			}else if($p < 63){
				$sheet = 3;
			}
		 	$p++;
		 	${'courseMark'.$sheet} .= "<tr>";
			${'courseMark'.$sheet} .= "<td align='center'><strong>".$msValue['semester_id']."</strong></td>";
			${'courseMark'.$sheet} .= "<td align='left' style='padding-left:10px;'><strong>".$msValue['course_code']."</strong></td>";
			${'courseMark'.$sheet} .= "<td align='left'><strong>".$msValue['course_name']."</strong></td>";
			${'courseMark'.$sheet} .= "<td align='center'><strong>".$msValue['credit_point']."</strong></td>";
			${'courseMark'.$sheet} .= "<td align='left' style='padding-left:14px;'><strong>".$msValue['grade']."</strong></td>";
			${'courseMark'.$sheet} .= "<td align='center'><strong>".$msValue['grade_point']."</strong></td>";		
			${'courseMark'.$sheet} .= "<td align='center'><strong>".$msValue['status']."</strong></td>";
			${'courseMark'.$sheet} .= "</tr>";
		 }
		 
		if (isset($audit_courses)) {
			foreach ($audit_courses as $key => $aCourse) {
				if ($stuValue['month_year_id'] == $examMonth) {
				$sid = $this->Html->retrieveSemesterFromMonthYear($examMonth, $batchId, $programId);
				
				${'courseMark'.$sheet} .= "<tr>";
					${'courseMark'.$sheet} .= "<td align='center'><strong>".$sid."</strong></td>";
					${'courseMark'.$sheet} .= "<td align='left' style='padding-left:10px;'><strong>".$aCourse['AuditCourse']['course_code']."</strong></td>";
					${'courseMark'.$sheet} .= "<td align='left'><strong>".$aCourse['AuditCourse']['course_name']."</strong></td>";
					${'courseMark'.$sheet} .= "<td align='center'></td>";
					${'courseMark'.$sheet} .= "<td align='center'></td>";
					${'courseMark'.$sheet} .= "<td align='center'></td>";
					if ($aCourse['marks'] >= $aCourse['AuditCourse']['total_min_pass_mark']) $auditStatus = "Pass";
					else $auditStatus = "Fail";
					${'courseMark'.$sheet} .= "<td align='center'><strong>".$auditStatus."</strong></td>";
				${'courseMark'.$sheet} .= "</tr>";
				$p++; $totCount++;
				}
			}
		}
		//echo " P : ".$p." totcount : ".$totCount;
			if ($p >= $totCount) {
				${'courseMark'.$sheet} .= "<tr>";
					${'courseMark'.$sheet} .= "<td></td>";
					${'courseMark'.$sheet} .= "<td></td>";
					${'courseMark'.$sheet} .= "<td align='center'><strong>*** End of Statement ***</strong></td>";
					${'courseMark'.$sheet} .= "<td></td>";
					${'courseMark'.$sheet} .= "<td></td>";
					${'courseMark'.$sheet} .= "<td></td>";
					${'courseMark'.$sheet} .= "<td></td>";
				${'courseMark'.$sheet} .= "</tr>";
				$p++; $totCount++;
			}
		//echo " P : ".$p." totcount : ".$totCount;	
		$cumulative_credits_earned = $semester1CourseCreditEarned+$semester2CourseCreditEarned+$semester3CourseCreditEarned+$semester4CourseCreditEarned+$semester5CourseCreditEarned+$semester6CourseCreditEarned+$semester7CourseCreditEarned+$semester8CourseCreditEarned+$semester9CourseCreditEarned+$semester10CourseCreditEarned;
		//echo "CumulativeCreditEarned : ".$cumulative_credits_earned;
		$cgpa=sprintf('%0.2f',round((($stuSemesterMark1_1+$stuSemesterMark2_1+$stuSemesterMark3_1+$stuSemesterMark4_1+$stuSemesterMark5_1+$stuSemesterMark6_1+$stuSemesterMark7_1+$stuSemesterMark8_1+$stuSemesterMark9_1+$stuSemesterMark10_1)/($semester1CourseCreditEarned+$semester2CourseCreditEarned+$semester3CourseCreditEarned+$semester4CourseCreditEarned+$semester5CourseCreditEarned+$semester6CourseCreditEarned+$semester7CourseCreditEarned+$semester8CourseCreditEarned+$semester9CourseCreditEarned+$semester10CourseCreditEarned)),2));
		//echo "CGPA : ".$cgpa;
		//pr($stuMarkSemester);
		//echo $courseMark1;
		//echo $courseMark2;
		?>
		<!-- MARK SHEET STARTED -->
<?php echo $this->Html->css('mark-sheet');?><?php
$html1 ='<table class="page"><tr><td>
<table class="cmainhead">
	<tr>
		<td style="height:76px;"></td>
	</tr>
	<tr>
		<td style="height:20px;font-size:15px;" align="center" valign="top">SEMESTER GRADE SHEET';
		if($type_of_cert == "TG"){
			$html1 .= " - (TRANSCRIPT)";
		}
		$html1.='</td>
	</tr>
</table>
<table cellspacing="0" cellpadding="0" class="cmaintable">
<tr>
    <td style="padding:0px;">
	<table width="97%" cellpadding="0" cellspacing="0" class="cmainsubtable" style="border-left:1px solid #000;border-right:none;">
  <tr>
    <td width="99" colspan="2" class="regClass"><strong>Name of the Candidate</strong></td>
    <td colspan="6"><strong>'.strtoupper($result['Student']['name']).'</strong></td>
  </tr>
  <tr>
    <td align="left" colspan="2"><strong>Programme &amp; Branch </strong></td>
    <td colspan="6"><strong>'.$result['Academic']['short_code'].' - '.$result['Program']['program_name'].'</strong></td>
  </tr>
  <tr>
    <td width="108" class="regClass"><strong>Register No. </strong></td>
    <td width="68"><strong>'.$result['Student']['registration_number'].'</strong></td>
    <td width="106"><strong>Date of Birth </strong></td>
    <td width="82"><strong>'.date( "d-M-Y", strtotime(h($result['Student']['birth_date']))).'</strong></td>
    <td width="54"><strong>Gender</strong></td>
    <td width="60"><strong>';
	if($result['Student']['gender'] == "F"){ $html1 .="Female";}else{ $html1 .="Male";}
	$html1 .='</strong></td>
    <td width="69"><strong>Folio&nbsp;No.</strong></td>
    <td width="113"><strong>';
    $seqFolioNo = $seqFolioNo+1; 
    if(strlen($seqFolioNo) == 1){$seqFolioNo = "0000".$seqFolioNo;}
	else if(strlen($seqFolioNo)==2){$seqFolioNo = "000".$seqFolioNo;}
	else if(strlen($seqFolioNo)==3){$seqFolioNo = "00".$seqFolioNo;}
	else if(strlen($seqFolioNo)==4){$seqFolioNo = "0".$seqFolioNo;}
	else {$seqFolioNo = $seqFolioNo;}    
    $html1 .=date('dmy').$type_of_cert.$seqFolioNo;
    $html1 .='</strong></td>
  </tr>  
  <tr>
    <td align="left" colspan="2"><strong>Month &amp; Year of Exam</strong></td>
    <td><strong>';
    $html1 .=ucfirst(strtolower($examMonthYear));
    $html1 .='</strong></td>
    <td colspan="2" align="left"><strong>Date of Publication</strong></td>
    <td colspan="2"><strong>';
    if(isset($publishing_date) && ($publishing_date)){
    	$html1 .=date( "d-M-Y", strtotime(h($publishing_date)));
    }
    $html1 .='</strong></td>';
    $html1 .='<td align="center"><strong>';
    $html2 .='</strong></td>
  </tr>
</table>	
	 </td>
    <td align="center" style="border-top:0px solid #000 !important;padding-left:0px;">';
	$profileImage = $this->Html->image("students/".str_replace("  "," ",str_replace("   "," ",$result['Student']['picture'])), ["alt" => h("profile.jpg"),"style"=>"width:96px;height:108px;border-radius:5px;"]);
    $html2 .= $profileImage;
    $html2 .='</td>
   </tr>
  </table>
<table border="0" cellspacing="0" cellpadding="0" class="cmaintable certTbl">
  <tr class="certTblTr">
    <td width="5%" align="center"><strong>Semester</strong></td>
    <td width="12%" align="center"><strong>Course Code </strong></td>
    <td width="56%" align="center"><strong>Course Title </strong></td>
    <td width="6%" align="center"><strong>Credits</strong></td>
    <td width="7%"  align="center"><strong>Letter Grade </strong></td>
    <td width="7%" align="center"><strong>Grade Point </strong></td>
    <td width="7%" align="center"><strong>Result</strong></td>
  </tr>';
 $html4 = "";   
$html4 .='</table>
<table border="1" cellspacing="0" cellpadding="0" class="cmaintable2 pad">
  <tr>
    <td width="22%"><strong>Semester</strong></td>
    <td width="7%" align="center"><strong>I</strong></td>
    <td width="7%" align="center"><strong>II</strong></td>
    <td width="7%" align="center"><strong>III</strong></td>
    <td width="7%" align="center"><strong>IV</strong></td>
    <td width="8%" align="center"><strong>V</strong></td>
    <td width="7%" align="center"><strong>VI</strong></td>
    <td width="7%" align="center"><strong>VII</strong></td>
    <td width="7%" align="center"><strong>VIII</strong></td>
    <td width="7%" align="center"><strong>IX</strong></td>
    <td width="7%" align="center"><strong>X</strong></td>
    </tr>
  <tr>
    <td><strong>Credits Registered</strong></td>';
$available_semesters = max($stuMarkSemester);

for($sem=1; $sem<=10; $sem++) {
	$html4 .='<td align="center"><strong>';
   	//if(${"semester"."$sem"."CourseReg"} && ($stuMarkSemester[$courseMId] == $sem)) {
   	if($available_semesters == $sem) {   
   		$html4 .=${"semester"."$sem"."CourseReg"};
   	}
   	else if(${"arrearSemester"."$sem"."CourseReg"}!=0) { 
   		$html4 .=${"arrearSemester"."$sem"."CourseReg"};
   	}
   	//if ($sem
   	$html4 .='</strong></td>';
}
    $html4.='</tr>	
    <tr>
    <td><strong>Credits Earned</strong></td>';
    
for($sem=1; $sem<=10; $sem++) {
	$html4 .='<td align="center"><strong>';
   	//if(${"semester"."$sem"."CourseCreditEarned"} && ($stuMarkSemester[$courseMId] == $sem)) {
   	if($available_semesters == $sem) { 
   		$html4 .=${"semester"."$sem"."CourseCreditEarned"};
   	} 
   	/* else if(${"semester"."$sem"."CourseCreditEarned"} && ($stuMarkSemester[$courseMId] < $sem)) {
   		$html4 .=${"semester"."$sem"."CourseCreditEarned"}; 
   	} */
   	else if($sem < $available_semesters) {
   		if(isset(${"arrearSemester"."$sem"."CourseCreditEarned"}) && ${"arrearSemester"."$sem"."CourseReg"}>0)
   			$html4 .=${"arrearSemester"."$sem"."CourseCreditEarned"};
   	}
   	$html4 .='</strong></td>';
}
    $html4.='</tr>
     <tr>
    	<td><strong>Grade Points Earned</strong></td>';
    	
for($sem=1; $sem<=10; $sem++) {
	$html4 .='<td align="center"><strong>';
   	//if(${"semester"."$sem"."GradePointEarned"} && ($stuMarkSemester[$courseMId] == $sem)) {
   	if($available_semesters == $sem) {  
   		$html4 .=${"semester"."$sem"."GradePointEarned"};
   	} else if($sem < $available_semesters) {
   		if(isset(${"arrearSemester"."$sem"."GradePointEarned"}) && ${"arrearSemester"."$sem"."CourseReg"}>0)
   			$html4 .=${"arrearSemester"."$sem"."GradePointEarned"};
   	}
   	$html4 .='</strong></td>';
}    	
    
    $html4.='</tr>
	 <tr>';
	$totalGP = "";
    $html4 .='<td><strong>Grade Point Average (GPA) </strong></td>';
    
for($sem=1; $sem<=10; $sem++) {
	$html4 .='<td align="center"><strong>';
   	//if(${"semester"."$sem"."gpa"} && ($stuMarkSemester[$courseMId] == $sem)) {
   	if($available_semesters == $sem) {   
   		$html4 .=${"semester"."$sem"."gpa"};
   	} else if($sem <= $available_semesters) {
   		if(isset(${"arrearSemester"."$sem"."gpa"}) && ${"arrearSemester"."$sem"."CourseReg"}>0)
   		$html4 .=${"arrearSemester"."$sem"."gpa"};
   	}
   	$html4 .='</strong></td>';
}    	
    $html4.='</tr>   
  <tr>
    <td><strong>Cumulative Credits Earned </strong></td>
    <td align="center"><strong>';
    $html4 .=$cumulative_credits_earned;
    $html4 .='</strong></td>
    <td colspan="4" align="center"><strong>Cumulative Grade Point Average (CGPA)</strong></td>
    <td align="center"><strong>';
		$html4.=$cgpa;
	$html4 .='</strong>
    </td>
	<td colspan="4"><strong>Medium of Instruction - <br/>ENGLISH</strong></td>
    </tr>  
</table>
<table class="cmaintable">
  <tr>  	
    <td align="right" v-align="bottom" style="padding-right:10px;height:100px;"><strong>';	
	$html4 .= $this->Html->image("certificate_signature/".$getSignature[0]['Signature']['signature'], ["alt" => h("coe.jpg"),"style"=>"height:40px;width:100px;"]);	
	$html4 .= "<br/>".$getSignature[0]['Signature']['name'];
	$html4 .='</strong></td>
	<td style="width:130px;"></td>	
  </tr>
</table>
</td></tr>
</table>';
$emptyTr = "";
//echo $courseMark2;
$emptyTr .= '<tr>
    <td align="center" valign="top">&nbsp;</td>
    <td align="center" valign="top">&nbsp;</td>
    <td valign="top">&nbsp;</td>
    <td align="center" valign="top">&nbsp;</td>
    <td align="center" valign="top">&nbsp;</td>
    <td align="center" valign="top">&nbsp;</td>
    <td align="center" valign="top">&nbsp;</td>
  </tr>'; 
  //$totCount = count($result['StudentMark']); 
  if($totCount <= 21){
  	echo $html1."Page 1 of 1".$html2.$courseMark1;
  	for($i=$totCount;$i<21;$i++){ echo $emptyTr;}
  	echo $html4;  	
  }else if($totCount <= 42){ 
  	echo $html1."Page 1 of 2".$html2.$courseMark1.$html4;  
  	echo $html1."Page 2 of 2".$html2.$courseMark2;
  	for($i=$totCount;$i<42;$i++){ echo $emptyTr;}
  	echo $html4;
  }else if($totCount <= 63){
  	echo $html1."Page 1 of 3".$html2.$courseMark1.$html4;
  	echo $html1."Page 2 of 3".$html2.$courseMark2.$html4;
  	echo $html1."Page 3 of 3".$html2.$courseMark3;
  	for($i=$totCount;$i<63;$i++){ echo $emptyTr;}
  	echo $html4;  	
  }
  
	?>
<?php
		
	}
}
?>