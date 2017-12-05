<?php
$a = 1;
if ($results) { 
	foreach ( $results as $k => $result ) { ($result);
		$studentId = $result['Student']['id'];
		$courseMark = "";
		$stuInternalArray = array ();
		$stuESArray = array ();
		$stuFinalMark = array ();
		$stuFinalStatus = array ();
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
		
		for ($i=1; $i<=10; $i++) { ${"semester".$i."CourseReg"} = "0"; }
		for ($i=1; $i<=10; $i++) { ${"semester".$i."CourseCreditEarned"} = "0"; }
		for ($i=1; $i<=10; $i++) { ${"semester".$i."GradePointEarned"} = "0"; }
		for ($i=1; $i<=10; $i++) { ${"stuSemesterMark".$i."_1"} = "0"; }
		for ($i=1; $i<=10; $i++) { ${"arrearSemester".$i."CourseReg"} = "0"; }
		for ($i=1; $i<=10; $i++) { ${"arrearSemester".$i."CourseCreditEarned"} = "0"; }
		for ($i=1; $i<=10; $i++) { ${"arrearSemester".$i."GradePointEarned"} = "0"; }
		for ($i=1; $i<=10; $i++) { ${"arrearStuSemesterMark".$i."_1"} = "0"; }
		for ($i=1; $i<=10; $i++) { ${"semester".$i."gpa"} = "0"; }
		for ($i=1; $i<=10; $i++) { ${"arrearSemester".$i."gpa"} = "0"; }
		
		$finalArray = array();
		
		if (isset($result['ParentGroup']['StudentMark']) && count($result['ParentGroup']['StudentMark'])>0) {
			for($p = 0; $p < count($result['ParentGroup']['StudentMark']); $p++) {
				$course_mapping_id = $result['ParentGroup']['StudentMark'][$p]['course_mapping_id'];
				if (isset($finalArray[$course_mapping_id])) {
					$finalArray[$course_mapping_id] = $result['ParentGroup']['StudentMark'][$p];
				} else {
					$finalArray[$course_mapping_id] = $result['ParentGroup']['StudentMark'][$p];
				}
			}
		}
		//pr($csmArray);
		for($p = 0; $p < count($result['StudentMark']); $p++) {
			$course_mapping_id = $result['StudentMark'][$p]['course_mapping_id'];
			//echo $course_mapping_id."**";
			if ($csmArray[$studentId][$course_mapping_id]==0) {
			if (isset($finalArray[$course_mapping_id])) {
				$finalArray[$course_mapping_id] = $result['StudentMark'][$p];
			} else {
				$finalArray[$course_mapping_id] = $result['StudentMark'][$p];
			}
			}
		}
		//pr($finalArray);
		
		$cgpa=0;
		$numerator=0;
		$denominator=0;
		$p=0;
		$q = 0;
		
		$cmArray = array();
		$csm_array = $result['CourseStudentMapping'];
		//pr($csm_array);
		
		foreach ($csm_array as $key => $cmValue) {
			$cmArray[$cmValue['course_mapping_id']] = $cmValue['type'];
		}
		//pr($cmArray);
		
		if (!empty($result['StudentAuditCourse']) && count($result['StudentAuditCourse']) >0) {
			$audit_courses = $result['StudentAuditCourse'];
		}
		//pr($audit_courses); 
		$cntt = 0;
		$markSheetArray = array();
		$stuMarkSemester = array();
		$stuMarkPassSemester = array();
		$sheet = "";
		$cumulative_credits_earned = 0;
		$cgpa_all = 0;
		
		foreach ($finalArray as $course_mapping_id => $stuValue) {
			//pr($stuValue);
			
			//echo $stuValue['grade'];
			$creditTransferred = "";
			$courseMId = $stuValue['course_mapping_id'];
			$examMonthYear = $stuValue['MonthYear']['Month']['month_name']."-".$stuValue['MonthYear']['year'];
			$publishing_date = $stuValue['CourseMapping']['MonthYear']['publishing_date'];
			$stuMarkSemester[$courseMId] = $stuValue['CourseMapping']['semester_id'];
			$stuMarkPassSemester[$courseMId] = $stuValue['month_year_id'];
			
			$revaluationStatus = 0;
			
			if($stuValue['revaluation_status'] == 0){
				$stuFinalMark[$courseMId] = $stuValue['marks'];
				$stuFinalStatus[$courseMId] = $stuValue['status'];
			}else{
				$revaluationStatus=1;
				$stuFinalMark[$courseMId] = $stuValue['final_marks'];
				$stuFinalStatus[$courseMId] = $stuValue['final_status'];				
			}
			
			$CourseCP = $stuValue['CourseMapping']['Course']['credit_point'];
			$courseTypeId = $stuValue['CourseMapping']['Course']['course_type_id'];
			
			if ($stuFinalStatus[$courseMId] == 'Pass') {
				$numerator = $numerator + ($stuValue['grade_point'] * $CourseCP);
				$denominator = $denominator + $CourseCP;
			}
			
			if($stuFinalStatus[$courseMId] == 'Fail' && ($stuValue['grade'] == 'W' || $stuValue['grade'] == 'ABS')){
				$markSheetGrade = $stuValue['grade'];
			} else if ($stuFinalStatus[$courseMId] == 'Fail') {
				$markSheetGrade = 'RA';
			} else if($stuFinalStatus[$courseMId] == 'Pass' && ($stuValue['grade'] == 'W' || $stuValue['grade'] == 'ABS')){
				$markSheetGrade = $stuValue['grade']; 
			} else {
				$markSheetGrade = $stuFinalStatus[$courseMId];
			}
			
			if($stuFinalStatus[$courseMId] == 'Fail'){
				/*if($stuFinalMark[$courseMId] == 0) {
					$marks = $this->Html->getMarks($courseMId, $courseTypeId, $revaluationStatus, $studentId, $examMonth);
				} else {
					$marks = $stuFinalMark[$courseMId];
				}*/
				//echo $courseMId."**".$marks."**".$stuFinalMark[$courseMId]."</br>";
				$markSheetGradePoint = "0";
			}else {
				$marks = $stuFinalMark[$courseMId];
				if($stuValue['grade_point']){
					$markSheetGradePoint = $stuValue['grade_point'];
				}else{
					$markSheetGradePoint = "0";
				}
			}		

			if (isset($stuValue['course_mapping_id']) && isset($cmArray[$stuValue['course_mapping_id']]) && $cmArray[$stuValue['course_mapping_id']] == "BTFOU") {
				$creditTransferred = "&nbsp;&nbsp;(Credits transferred)";
			} 
			
			if ($stuFinalStatus[$courseMId] == "Pass") {
				$cumulative_credits_earned = $cumulative_credits_earned + $CourseCP;
			}
			
			$markArray = array();
				SWITCH ($courseTypeId) {
					CASE 1:
						$markArray = $this->Html->getTheoryCaeAndEse($courseTypeId, $courseMId, $studentId, $stuMarkSemester[$courseMId], $stuValue['revaluation_status'], $examMonth);
						break;
					CASE 2:
					CASE 6:
						$markArray = $this->Html->getPracticalCaeAndEse($courseTypeId, $courseMId, $studentId, $stuMarkSemester[$courseMId], $stuValue['revaluation_status'], $examMonth);
						break;
					CASE 3:
						$markArray = $this->Html->getTheoryPracticalCaeAndEse($courseTypeId, $courseMId, $studentId, $stuMarkSemester[$courseMId], $stuValue['revaluation_status'], $examMonth);
						break;
					CASE 4:
						$markArray = $this->Html->getProjectCaeAndEse($courseTypeId, $courseMId, $studentId, $stuMarkSemester[$courseMId], $stuValue['revaluation_status'], $examMonth);
						break;
					CASE 5:
						$markArray = $this->Html->getProfTrainingCae($courseTypeId, $courseMId, $studentId, $stuMarkSemester[$courseMId], $stuValue['revaluation_status'], $examMonth);
						break;
				}
				
				$cae=$markArray['cae'];
				$ese=$markArray['ese'];
				if ($stuFinalStatus[$courseMId]=='Fail' && ($markArray['ese']=='A' || $markArray['ese']=='AAA' || $markArray['ese']=='aaa' || $markArray['ese']=='a')) $total='AAA';
				else $total=$stuFinalMark[$courseMId];
				
			//echo " arrearSemester1CourseReg: ".$arrearSemester1CourseReg;		
			if ($stuValue['month_year_id'] == $examMonth || $stuFinalStatus[$courseMId]=='Fail') {
				if ($ese == "A" || $ese == "a" || $ese == "aaa" || $ese == "AAA") $grade = "AAA";
				else $grade = $stuValue['grade'];
				$markSheetArray[$q] = array(
					'cm_id' => $courseMId,
					'semester_id' => $stuValue['CourseMapping']['semester_id'],
					'course_code' => $stuValue['CourseMapping']['Course']['course_code'],
					'course_name' => $stuValue['CourseMapping']['Course']['course_name'].$creditTransferred,
					'credit_point' => $stuValue['CourseMapping']['Course']['credit_point'],
					'marks' => $total,
					'course_max_marks' => $stuValue['CourseMapping']['Course']['course_max_marks'],
					'grade' => $grade,
					'grade_point' => $markSheetGradePoint,
					'status' => $markSheetGrade,
				);
				$q++;
				
				//echo "</br>".$courseTypeId." ".$courseMId." ".$stuMarkSemester[$courseMId]." ".$stuMarkPassSemester[$courseMId]
				//." ".$stuFinalStatus[$courseMId]." ".$cae." ".$ese." ".$total;
				$RANGE_OF_MARKS_FOR_GRADES = $stuValue['grade_point'];
				//if ($stuMarkSemester[$courseMId] == $examMonth) {
				$actual_semester_id = $this->Html->retrieveSemesterFromMonthYear($examMonth, $batchId, $programId);
					if($stuMarkSemester[$courseMId] == $actual_semester_id) { 
						${"semester".$stuMarkSemester[$courseMId]."CourseReg"} = ${"semester".$stuMarkSemester[$courseMId]."CourseReg"} + $CourseCP;
						//echo "</br>---".$stuFinalStatus[$courseMId]."---".$stuValue['grade'];
						if($stuFinalStatus[$courseMId] == 'Pass' && $stuValue['grade'] != 'ABS') {
							${"semester".$stuMarkSemester[$courseMId]."CourseCreditEarned"} = ${"semester".$stuMarkSemester[$courseMId]."CourseCreditEarned"} + $CourseCP;
							${"semester".$stuMarkSemester[$courseMId]."GradePointEarned"} = ${"semester".$stuMarkSemester[$courseMId]."GradePointEarned"} + $RANGE_OF_MARKS_FOR_GRADES;
						}
						${"stuSemesterMark".$stuMarkSemester[$courseMId]."_1"} = ${"stuSemesterMark".$stuMarkSemester[$courseMId]."_1"}+($stuValue['CourseMapping']['Course']['credit_point']*$RANGE_OF_MARKS_FOR_GRADES);
					}
					else {
						${"arrearSemester".$stuMarkSemester[$courseMId]."CourseReg"} = ${"arrearSemester".$stuMarkSemester[$courseMId]."CourseReg"} + $CourseCP;
						if($stuFinalStatus[$courseMId] == 'Pass'){
							${"arrearSemester".$stuMarkSemester[$courseMId]."CourseCreditEarned"} = ${"arrearSemester".$stuMarkSemester[$courseMId]."CourseCreditEarned"} + $CourseCP;
							${"arrearSemester".$stuMarkSemester[$courseMId]."GradePointEarned"} = ${"arrearSemester".$stuMarkSemester[$courseMId]."GradePointEarned"} + $RANGE_OF_MARKS_FOR_GRADES;
							
							${"arrearStuSemesterMark".$stuMarkSemester[$courseMId]."_1"} = 
							${"arrearStuSemesterMark".$stuMarkSemester[$courseMId]."_1"}+
							($stuValue['CourseMapping']['Course']['credit_point']*$RANGE_OF_MARKS_FOR_GRADES);
							
							//echo "ArrearSem : ".${"arrearSemester".$stuMarkSemester[$courseMId]."CourseReg"};
							${"arrearSemester".$stuMarkSemester[$courseMId]."gpa"} = 
							sprintf('%0.2f', ${"arrearStuSemesterMark".$stuMarkSemester[$courseMId]."_1"} / 
							${"arrearSemester".$stuMarkSemester[$courseMId]."CourseCreditEarned"});
						} 
					}
					//break;
				//}
				
				if (!empty(${"stuSemesterMark".$stuMarkSemester[$courseMId]."_1"}) && !empty(${"semester".$stuMarkSemester[$courseMId]."CourseCreditEarned"})) {
					${"semester".$stuMarkSemester[$courseMId]."gpa"} = sprintf('%0.2f', ${"stuSemesterMark".$stuMarkSemester[$courseMId]."_1"} / ${"semester".$stuMarkSemester[$courseMId]."CourseCreditEarned"});
					//echo "Sem".$stuMarkSemester[$courseMId]." GPA ".${"semester".$stuMarkSemester[$courseMId]."gpa"};
				}
				if (!empty(${"arrearStuSemesterMark".$stuMarkSemester[$courseMId]."_1"}) && !empty(${"arrearSemester".$stuMarkSemester[$courseMId]."CourseCreditEarned"})) {
					${"arrearSemester".$stuMarkSemester[$courseMId]."gpa"} = 
					sprintf('%0.2f', ${"arrearStuSemesterMark".$stuMarkSemester[$courseMId]."_1"} / 
					${"arrearSemester".$stuMarkSemester[$courseMId]."CourseCreditEarned"});
				}
		 	}
		 	
		 	
		}
		//End for loop line no. 88
		//pr($stuMarkSemester);
		//pr($stuMarkPassSemester);
		//pr($stuFinalMark);
		//pr($stuFinalStatus);
		//pr($markSheetArray);
		
		unset($csm_array);
		
		$AUDIT_COURSE = "AC";
		//For Audit Courses
		if (isset($audit_courses)) {
			foreach ($audit_courses as $key => $aCourse) {
				if ($aCourse['month_year_id'] == $examMonth) {
					$sid = $this->Html->retrieveSemesterFromMonthYear($examMonth, $batchId, $programId);
					
					if ($aCourse['marks'] >= $aCourse['AuditCourse']['total_min_pass_mark']) $auditStatus = "Pass";
					else $auditStatus = "Fail";
						
					$markSheetArray[] = array(
						'cm_id' => $AUDIT_COURSE.$key,
						'semester_id' => $sid,
						'course_code' => $aCourse['AuditCourse']['course_code'],
						'course_name' => $aCourse['AuditCourse']['course_name'],
						'credit_point' => "",
						'grade' => "",
						'grade_point' => "",
						'status' => $auditStatus,
					);
				
				}
			}
		}
		//For Audit Courses ends here
		
		/*for($kp=0; $kp<=23; $kp++) {
	    	$markSheetArray[] = array(
	    		'cm_id' => 'Xtra'.$kp,
				'semester_id' => "3",
				'course_code' => "abcd",
				'course_name' => "abcd ".$kp,
				'credit_point' => 3,
				'grade' => 'C',
				'grade_point' => 6,
				'status' => 'Pass',
			);  
	    }*/
	    
	    $emptyMarkSheetArray = array(array(
			'cm_id' => 'EMPTY',
			'semester_id' => "",
			'course_code' => "",
			'course_name' => "",
			'marks' => "",
			'course_max_marks' => "",
			'credit_point' => "",
			'grade' => "",
			'grade_point' => "",
			'status' => "",
		));
	    
		$markSheetArray[] = array(
			'cm_id' => 'EOS',
			'semester_id' => "",
			'course_code' => "",
			'course_name' => "<span align='center'><strong>*** End of Statement ***</strong></span>",
			'marks'=>"",
			'course_max_marks'=>"",
			'credit_point' => "",
			'grade' => "",
			'grade_point' => "",
			'status' => "",
		);
 		
 		$totCount = count($markSheetArray);
 		//echo count($markSheetArray);
 		
 		if ($totCount >= 21 && $totCount <=41) {
 			array_insert1($markSheetArray, $emptyMarkSheetArray, 20);
 		}
 		else if ($totCount >= 42 && $totCount <=62) {
 			array_insert1($markSheetArray, $emptyMarkSheetArray, 40);
 		}
 		else if ($totCount == 63) {
 			array_insert1($markSheetArray, $emptyMarkSheetArray, 60);
 		}
 		
 		//array_insert1($markSheetArray, $emptyMarkSheetArray, 20);
		$totCount = count($markSheetArray);
		
		//echo "Total Count : ".$totCount." P Value : ".$p;
		
		//pr($markSheetArray);
		
		foreach ($markSheetArray as $key => $msValue) {
			if ($totCount <= 23) {
				if($p <= 20) {
					$sheet = 1;
				}
				else if ($p <=23) { 
					$sheet = 2;
				}
			}
			else if ($totCount <= 43) {
				if($p <= 20) {
					$sheet = 1;
				}
				else if ($p <=40) { 
					$sheet = 2;
				}
				else if ($p <=43) { 
					$sheet = 3;
				}
			}
		 	else if ($totCount <= 64) {
				if($p <= 20) {
					$sheet = 1;
				}
				else if ($p <=41) { 
					$sheet = 2;
				}
				else if ($p <=44) { 
					$sheet = 3;
				}
			}
		 	//echo "</br>Sheet : ".$sheet." P : ".$p." CM ID : ".$msValue['cm_id'];
		 	$p++;
		 	if ($msValue['cm_id'] == "EOS") $courseStyle="center"; else $courseStyle="left";
		 	
		 	${'courseMark'.$sheet} .= "<tr>";
			${'courseMark'.$sheet} .= "<td align='center'><strong>".$msValue['semester_id']."</strong></td>";
			${'courseMark'.$sheet} .= "<td align='left' style='padding-left:6px;'><strong>".$msValue['course_code']."</strong></td>";
			${'courseMark'.$sheet} .= "<td align=$courseStyle><strong>".$msValue['course_name']."</strong></td>";
			${'courseMark'.$sheet} .= "<td align='center'><strong>";
			if ($msValue['course_code']!="AUDIT") ${'courseMark'.$sheet} .= $msValue['marks'];
			else ${'courseMark'.$sheet} .= "";
			${'courseMark'.$sheet} .= "</strong></td>";
			${'courseMark'.$sheet} .= "<td align='center'><strong>".$msValue['course_max_marks']."</strong></td>";
			${'courseMark'.$sheet} .= "<td align='center'><strong>".$msValue['credit_point']."</strong></td>";
			${'courseMark'.$sheet} .= "<td align='center'><strong>".$msValue['grade']."</strong></td>";
			${'courseMark'.$sheet} .= "<td align='center'><strong>".$msValue['grade_point']."</strong></td>";		
			${'courseMark'.$sheet} .= "<td align='center'><strong>".$msValue['status']."</strong></td>";
			${'courseMark'.$sheet} .= "</tr>";
		 }
		
		/*${'courseMark'.$sheet} .= "<tr>";
			${'courseMark'.$sheet} .= "<td></td>";
			${'courseMark'.$sheet} .= "<td></td>";
			${'courseMark'.$sheet} .= "<td align='center'><strong>*** End of Statement ***</strong></td>";
			${'courseMark'.$sheet} .= "<td></td>";
			${'courseMark'.$sheet} .= "<td></td>";
			${'courseMark'.$sheet} .= "<td></td>";
			${'courseMark'.$sheet} .= "<td></td>";
			${'courseMark'.$sheet} .= "<td></td>";
		${'courseMark'.$sheet} .= "</tr>";*/
				
		//echo "numerator : ".$numerator." denominator : ".$denominator;
		if ($denominator != 0) {
			$cgpa=sprintf('%0.2f',round(($numerator/$denominator),2));
		}
		else {
			$cgpa = 0;
		}
		
		for($i=1; $i<=10; $i++) {
			//echo "</br>stuSemesterMark sss : ".${"stuSemesterMark".$i."_1"}." sss semester".$i."CourseCreditEarned : ".${"semester".$i."CourseCreditEarned"};
		}
		
		//echo $courseMark1."</br>".$courseMark2;
		?>
		<!-- MARK SHEET STARTED -->
<?php echo $this->Html->css('mark-sheet');?><?php
$html1 ='<table class="page"><tr><td>
<table class="cmainhead">
	<tr>
		<td style="height:80px;"></td>
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
    $html1 .=ucfirst(strtolower($this->Html->getMonthYearFromMonthYearId($examMonth)));
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
	//$profileImage = $this->Html->image("students/".str_replace("  "," ",str_replace("   "," ",$result['Student']['picture'])));
    $html2 .= $profileImage;
    $html2 .='</td>
   </tr>
  </table>
<table border="0" cellspacing="0" cellpadding="0" class="cmaintable certTbl">
  <tr class="certTblTr">
    <td width="3%" align="center" style="font-size:12px;"><strong>Sem</strong></td>
    <td width="9%" align="center" style="font-size:12px;"><strong>Course Code </strong></td>
    <td width="55%" align="center" style="font-size:12px;"><strong>Course Title </strong></td>
    <td width="6%" align="center" style="font-size:10px;"><strong>Marks Secured</strong></td>
    <td width="6%" align="center" style="font-size:10px;"><strong>Max Marks</strong></td>
    <td width="6%" align="center" style="font-size:10px;"><strong>Credits</strong></td>
    <td width="5%"  align="center" style="font-size:10px;"><strong>Letter Grade </strong></td>
    <td width="5%" align="center" style="font-size:10px;"><strong>Grade Point </strong></td>
    <td width="5%" align="center" style="font-size:10px;"><strong>Result</strong></td>
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
<table class="cmaintable" border="0">
  <tr>  	
    <td align="right" v-align="bottom" style="padding-right:10px;height:100px;"><strong>';	
	$html4 .= $this->Html->image("certificate_signature/".$getSignature[0]['Signature']['signature'], ["alt" => h("coe.jpg"),"style"=>"height:40px;width:100px;"]);	
	//$html4 .= $this->Html->image("certificate_signature/".$getSignature[0]['Signature']['signature']);
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
    <td align="center" valign="top">&nbsp;</td>
    <td align="center" valign="top">&nbsp;</td>
  </tr>'; 
  //$totCount = count($result['StudentMark']); 
  
  
  /*if($totCount <= 21){
  	echo $html1."Page 1 of 1".$html2.$courseMark1;
  	for($i=$totCount;$i<21;$i++){ echo $emptyTr;}
  	echo $html4;  	
  }else if($totCount <= 41){ 
  	echo $html1."Page 1 of 2".$html2.$courseMark1.$html4;  
  	echo $html1."Page 2 of 2".$html2.$courseMark2;
  	for($i=$totCount;$i<42;$i++){ echo $emptyTr;}
  	echo $html4;
  }else if($totCount <= 62){
  	echo $html1."Page 1 of 3".$html2.$courseMark1.$html4;
  	echo $html1."Page 2 of 3".$html2.$courseMark2.$emptyTr.$html4;
  	echo $html1."Page 3 of 3".$html2.$courseMark3;
  	for($i=$totCount;$i<63;$i++){ echo $emptyTr;}
  	echo $html4;  	
  }*/
  
SWITCH ($sheet) {
	CASE 1:
		echo $html1."Page 1 of 1".$html2.$courseMark1;
	  	for($i=$totCount;$i<21;$i++){ echo $emptyTr;}
	  	echo $html4;
		break;
	CASE 2:
		echo $html1."Page 1 of 2".$html2.$courseMark1.$html4;  
	  	echo $html1."Page 2 of 2".$html2.$courseMark2;
	  	for($i=$totCount;$i<42;$i++){ echo $emptyTr;}
  		echo $html4;
		break;
	CASE 3:
		echo $html1."Page 1 of 3".$html2.$courseMark1.$html4;
	  	echo $html1."Page 2 of 3".$html2.$courseMark2.$emptyTr.$html4;
	  	echo $html1."Page 3 of 3".$html2.$courseMark3;
	  	for($i=$totCount;$i<63;$i++){ echo $emptyTr;}
	  	echo $html4;  	
}
  
	?>
<?php
		
	}
}
function emptyRow() {
	$emptyTr = "";
	$emptyTr .= '<tr>
	    <td align="center"><strong>1</strong></td>
	    <td align="center"></td>
	    <td></td>
	    <td align="center"></td>
	    <td align="center></td>
	    <td align="center"></td>
	    <td align="center"></td>
	  </tr>';
	  return $emptyTr;
}

function array_insert1(&$array, $insert, $position) {
	settype($array, "array");
	settype($insert, "array");
	settype($position, "int");

	//if pos is start, just merge them
	if($position==0) {
	    $array = array_merge($insert, $array);
	} else {
	    //if pos is end just merge them
	    if($position >= (count($array)-1)) {
	        $array = array_merge($array, $insert);
	    } else {
	        //split into head and tail, then merge head+inserted bit+tail
	        $head = array_slice($array, 0, $position);
	        $tail = array_slice($array, $position);
	        $array = array_merge($head, $insert, $tail);
	    }
	}
}
?>