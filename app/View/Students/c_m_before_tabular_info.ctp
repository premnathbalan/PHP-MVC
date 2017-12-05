<!-- Replace this text for XXX '.$this->Html->getMonthYearFromMonthYearId(max(array_column($markSheetArray, 'month_year_id'))).' -->
<?php
$a = 1;
if ($results) { 
	foreach ( $results as $k => $result ) { //pr($result);
	$studentId = $result['Student']['id'];
	$program_credit = $result['Program']['credits'];
	
	if (isset($result['StudentAuthorizedBreak'][0]['student_id'])) $abs=1;
	else $abs=0;
	
	//echo "ABS : ".$abs;

	if (isset($result['StudentWithdrawal'][0]['student_id'])) $withdrawal=1;
	else $withdrawal=0;
	
	//echo "Withdrawal : ".$withdrawal;
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
		
		/*for ($i=1; $i<=10; $i++) { ${"semester".$i."CourseReg"} = "0"; }
		for ($i=1; $i<=10; $i++) { ${"semester".$i."CourseCreditEarned"} = "0"; }
		for ($i=1; $i<=10; $i++) { ${"semester".$i."GradePointEarned"} = "0"; }
		for ($i=1; $i<=10; $i++) { ${"semester".$i."Gpa"} = "0"; }
		for ($i=1; $i<=10; $i++) { ${"semester".$i."Gpa_1"} = "0"; }*/
		
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
		
		for($p = 0; $p < count($result['StudentMark']); $p++) {
			$course_mapping_id = $result['StudentMark'][$p]['course_mapping_id'];
			//echo $course_mapping_id;
			if (isset($finalArray[$course_mapping_id])) {
				$finalArray[$course_mapping_id] = $result['StudentMark'][$p];
			} else {
				$finalArray[$course_mapping_id] = $result['StudentMark'][$p];
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
		$courseActualSemester = array();
		$coursePassMonthYear = array();
		$coursePassSemester = array();
		$sheet = "";
		$cumulative_credits_earned = 0;
		$cgpa_all = 0;
		$q = 0;
		
		foreach ($finalArray as $course_mapping_id => $stuValue) {
			//pr($stuValue);
			
			//echo $stuValue['grade'];
			$creditTransferred = "";
		 	$courseMId = $stuValue['course_mapping_id'];
		 	
		 	//From mark_sheet
		 	$examMonthYear = $stuValue['MonthYear']['Month']['month_name']."-".$stuValue['MonthYear']['year'];
			$publishing_date = $stuValue['CourseMapping']['MonthYear']['publishing_date'];
			$stuMarkSemester[$courseMId] = $stuValue['CourseMapping']['semester_id'];
			$stuMarkPassSemester[$courseMId] = $stuValue['month_year_id'];
			// from marksheet ends here
			
		 	$courseActualSemester[$courseMId] = $stuValue['CourseMapping']['semester_id'];
			$coursePassMonthYear[$courseMId] = $stuValue['month_year_id'];
			$coursePassSemester[$courseMId] = $this->Html->retrieveSemesterFromMonthYear($stuValue['month_year_id'], $batchId, $programId); 
			
			$revaluationStatus = 0;
			
			if($stuValue['revaluation_status'] == 0){
				$stuFinalMark[$courseMId] = $stuValue['marks'];
				$stuFinalStatus[$courseMId] = $stuValue['status'];
			}else{
				$stuFinalMark[$courseMId] = $stuValue['final_marks'];
				$stuFinalStatus[$courseMId] = $stuValue['final_status'];				
			}
			
			$CourseCP = $stuValue['CourseMapping']['Course']['credit_point'];
			$courseTypeId = $stuValue['CourseMapping']['Course']['course_type_id'];
			
			if ($stuFinalStatus[$courseMId] == "Pass") {
				$numerator = $numerator + ($stuValue['grade_point'] * $CourseCP);
				$denominator = $denominator + $CourseCP;
				
				$markSheetArray[$q] = array(
					'cm_id' => $courseMId,
					'semester_id' => $stuValue['CourseMapping']['semester_id'],
					'month_year_id' => $stuValue['month_year_id'],
					'course_code' => $stuValue['CourseMapping']['Course']['course_code'],
					'course_name' => $stuValue['CourseMapping']['Course']['course_name'],
					'credit_point' => $stuValue['CourseMapping']['Course']['credit_point'],
					'marks' => $stuFinalMark[$courseMId],
					'grade' => $stuValue['grade'],
					'month_year' => $this->Html->getMonthYearFromMonthYearId($stuValue['month_year_id']),
					'grade_point' => $stuValue['grade_point'],
					'status' => $stuFinalStatus[$courseMId],
				);
				
				$cumulative_credits_earned = $cumulative_credits_earned + $stuValue['CourseMapping']['Course']['credit_point'];
				
				$RANGE_OF_MARKS_FOR_GRADES = $stuValue['grade_point'];
				
				${"semester".$courseActualSemester[$courseMId]."CourseReg"} = ${"semester".$courseActualSemester[$courseMId]."CourseReg"} + $CourseCP;
				${"semester".$courseActualSemester[$courseMId]."CourseCreditEarned"} = ${"semester".$courseActualSemester[$courseMId]."CourseCreditEarned"} + $CourseCP;
				${"semester".$courseActualSemester[$courseMId]."GradePointEarned"} = ${"semester".$courseActualSemester[$courseMId]."GradePointEarned"} + $RANGE_OF_MARKS_FOR_GRADES;
				${"semester".$courseActualSemester[$courseMId]."Gpa_1"} = ${"semester".$courseActualSemester[$courseMId]."Gpa_1"}+($stuValue['CourseMapping']['Course']['credit_point']*$RANGE_OF_MARKS_FOR_GRADES);
						
				$q++;
			}
		}
		//End for loop line no. 109
		//echo "cumulative_credits_earned : ".$cumulative_credits_earned;
		//pr($coursePassSemester);
		//pr($markSheetArray);
		
		if ($cumulative_credits_earned >= $program_credit && count($cmArray) == count($markSheetArray)) {
			for($i=1; $i<=10; $i++) {
				if (isset(${"semester".$i."CourseReg"}) && ${"semester".$i."CourseReg"}>0) {
					${"semester".$i."Gpa"} = sprintf('%0.2f', ${"semester".$i."Gpa_1"} / ${"semester".$i."CourseReg"});
				}
				$numerator = $numerator + ${"semester".$i."Gpa_1"};
				$denominator = $denominator + ${"semester".$i."CourseReg"};
			}
		}
		
		//for($i=1; $i<=10; $i++) {
			//echo "</br>Semester $i GPA : ".${"semester".$i."Gpa"};
		//}
		
		//echo "numerator : ".$numerator." denominator : ".$denominator;
		
		$cgpa=sprintf('%0.2f',round(($numerator/$denominator),2));
		//echo "CGPA : ".$cgpa;
		
		
		unset($csm_array);
		
		$AUDIT_COURSE = "AC";
		//For Audit Courses
		if (isset($audit_courses)) {
			foreach ($audit_courses as $key => $aCourse) {
				if ($stuValue['month_year_id'] == $examMonth) {
					$sid = $this->Html->retrieveSemesterFromMonthYear($examMonth, $batchId, $programId);
					
					if ($aCourse['marks'] >= $aCourse['AuditCourse']['total_min_pass_mark']) $auditStatus = "Pass";
					else $auditStatus = "Fail";
					
					if ($auditStatus == "Pass") {
						$markSheetArray[] = array(
							'cm_id' => $AUDIT_COURSE.$key,
							'month_year_id' => $examMonth,
							'semester_id' => $sid,
							'course_code' => $aCourse['AuditCourse']['course_code'],
							'course_name' => $aCourse['CourseMapping']['AuditCourse']['course_name'],
							'credit_point' => "",
							'grade' => "",
							'month_year' => $this->Html->getMonthYearFromMonthYearId($aCourse['CourseMapping']['month_year_id']),
							'grade_point' => "",
							'status' => $auditStatus,
						);
					}
				}
			}
		}
		//For Audit Courses ends here
		
		/*for($kp=0; $kp<=73; $kp++) {
			$tmp = $kp+1;
	    	$markSheetArray[] = array(
	    		'cm_id' => 'Xtra'.$kp,
	    		'month_year_id' => "1",
				'semester_id' => "3",
				'course_code' => "abcd",
				'course_name' => "Materials Spectroscopy, Health & Environmental Issues Materials Spectroscopy,".$tmp,
				'credit_point' => 3,
				'grade' => 'C',
				'month_year' => 'N0V - 2016',
				'grade_point' => 6,
				'status' => 'Pass',
			);  
	    }*/
	   
	    /*$emptyMarkSheetArray = array(array(
			'cm_id' => 'EMPTY',
			'month_year_id' => "",
			'semester_id' => "",
			'course_code' => "",
			'course_name' => "",
			'credit_point' => "",
			'grade' => "",
			'month_year' => "",
			'grade_point' => "",
			'status' => "",
		));*/
	    
	    $EOSArray = array(array(
				'cm_id' => 'EOS',
				'month_year_id' => "",
				'semester_id' => "",
				'course_code' => "",
				'course_name' => "<span align='center'><strong>*** End of Statement ***</strong></span>",
				'credit_point' => "",
				'grade' => "",
				'month_year' => "",
				'grade_point' => "",
				'status' => "",
		));
		
		$totCount = count($markSheetArray);
		$nextCount = $totCount+1;
		//echo $totCount;
	    if ($totCount > 40) {
	 		array_insert1($markSheetArray, $EOSArray, $nextCount);
	    }
	    else {
			array_insert1($markSheetArray, $EOSArray, $nextCount);
		}
 		
 		//pr($markSheetArray);
 		//echo count($markSheetArray);
 		//pr($markSheetArray);
 		//pr($courseActualSemester);
 		//pr($coursePassSemester);
 		
 		$first_attempt = array_diff_assoc($courseActualSemester,array_intersect_assoc($courseActualSemester, $coursePassSemester));
		
		//echo "numerator : ".$numerator." denominator : ".$denominator;
		if ($denominator != 0) {
			$cgpa=sprintf('%0.2f',round(($numerator/$denominator),2));
		}
		else {
			$cgpa = 0;
		}
		
		$degree_classification = $this->Html->generateModeClass($cgpa, $abs, $withdrawal, $first_attempt); 
		
		//echo $cgpa;
		//echo "MAX : ".max(array_column($markSheetArray, 'month_year_id'));
		?>
		<!-- MARK SHEET STARTED -->
<?php echo $this->Html->css('ms'); ?>
<?php
if(count($markSheetArray) <= 90) {
$html1 = '
<table class="page"><tr><td class=" boder_0">
	<table class="consolidoutertbl" align="center"><tr><td class=" boder_0"><tr><td class="boder_0">
		<table class="consolidtbl boder_0" cellspacing="0" cellpadding="0">
			<tr>
				<td align="center" class="boder_0" style="width:667px;">&nbsp;</td>
				<td align="center" class="boder_0" style="vertical-align: bottom;" style="width:100%;">
				<table class="consolidtbl boder_0" cellspacing="0" cellpadding="0" border="0">
			<tr>
				
				<td class="bodertop_0 boderrht_0 boderbot_0" align="right">
					<table class="consolidtbl boder_0 min-hei79 txtind boderrht_0 cMProfTitle" cellspacing="0" cellpadding="0">
						<tr>
							<td width="10%" class="boderleft_0"><strong>NAME OF THE CANDIDATE</strong></td>
							<td colspan="4"><strong>'.strtoupper($result['Student']['name']).'</strong></td>
						</tr>
						<tr>
							<td><strong>COURSE & BRANCH</strong></td>
							<td colspan="4"><strong>'.strtoupper($result['Academic']['short_code']).' - '.strtoupper($result['Program']['program_name']).'</strong></td>
						</tr>
						<tr>
							<td><strong>REGISTER No.</strong></td>
							<td width="20%"><strong>'.$result['Student']['registration_number'].'</strong></td>							
							<td colspan="2"><strong>DATE OF PUBLICATION</strong></td>
							<td><strong>'.date("d-m-Y", strtotime($result['Batch']['consolidated_pub_date'])).'</strong></td>
						</tr>						
						<tr>
							<td width="10%" class="boderleft_0 "><strong>MEDIUM OF INSTRUCTION</strong></td>
							<td><strong>ENGLISH</strong></td>
							<td width="20%"><strong>FOLIO No.</strong></td>
							<td width="20%"><strong>';
							$seqFolioNo = $seqFolioNo+1; 
						    if(strlen($seqFolioNo) == 1){$seqFolioNo = "0000".$seqFolioNo;}
							else if(strlen($seqFolioNo)==2){$seqFolioNo = "000".$seqFolioNo;}
							else if(strlen($seqFolioNo)==3){$seqFolioNo = "00".$seqFolioNo;}
							else if(strlen($seqFolioNo)==4){$seqFolioNo = "0".$seqFolioNo;}
							else {$seqFolioNo = $seqFolioNo;}    
							$html1 .=date('dmy').$type_of_cert.$seqFolioNo;
							$html1.='</strong></td>
							<td rowspan="3" width="20%">';
							//$html1 .= $this->Html->image("qr_code/qr-code.png", ["alt" => h("qr-code.png"),"style"=>"width:80px;"]);
							$html1 .= $this->Html->image("qr_code/qr-code.png");
							$html1 .='</td>
						</tr>
						<tr>
							<td class="boderleft_0"><strong>DATE OF BIRTH</strong></td>
							<td width="20%"><strong>'.date( "d-M-Y", strtotime(h($result['Student']['birth_date']))).'</strong></td>
							<td width="20%"><strong>GENDER</strong></td>
							<td width="20%"><strong>';
							if($result['Student']['gender'] == "F"){ $html1 .=strtoupper("Female");}else{ $html1 .=strtoupper("Male");}
							$html1.='</strong></td>
						</tr>
						<tr>
							<td class="bodertop_0 boderleft_0" width="35%"><strong>MONTH & YEAR OF LAST &nbsp;&nbsp;APPEARANCE</strong></td>
							<td class="bodertop_0" width="20%"><strong>XXX-2017</strong></td> 
							<td class="bodertop_0" width="20%"><strong>BATCH</strong></td>
							<td class="bodertop_0" width="20%"><strong>'.$this->Html->getBatch($batchId).'</strong></td>
						</tr>
					</table>
				</td>
			</tr>
		</table>
				</td>
				<td style="width:106px;" valign="bottom" class="boder_0" align="right">
					<table>
						<tr>						
							<td class="boder_0">';
//$profileImage = $this->Html->image("students/".str_replace("  "," ",str_replace("   "," ",$result['Student']['picture'])), ["alt" => h("profile.jpg"),"style"=>"width:106px;height:108px;border-radius:5px;"]);
$profileImage = $this->Html->image("students/".str_replace("  "," ",str_replace("   "," ",$result['Student']['picture'])));
							$html1 .= $profileImage;
							$html1 .= '</td>							
						</tr>
					</table>
				</td>
			</tr>
		</table>		
		<table class="consolidtbl boder_0" cellspacing="0" cellpadding="0" border="0">
			<tr>
				<td width="50%" class="boder_0">
					<table class="consolidtbl boder_0 txtind msHeader" cellspacing="0" cellpadding="0">
						<tr>
							<th class="boderleft_0" width="4%">Sem</th>
							<th width="8%">Course<br/> Code</th>
							<th width="55%">Course Title</th>
							<th width="7%">Marks Obtained</th>
							<th width="6%">Max Marks</th>
							<th width="6%">Credits</th>
							<th class="bodertop_0" width="5%">Grade</th>
							<th class="bodertop_0" width="9%" style="border-right: 2px solid #000;">Month &amp; Year<br/>of Passing</th>
						</tr>
					</table>
				</td>
				<td width="50%" class="bodertop_0 boderrht_0 boder_0">
					<table class="consolidtbl boder_0 txtind msHeader" cellspacing="0" cellpadding="0">
						<tr>
							<th class="bodertop_0 boderleft_0" width="4%">Sem</th>
							<th class="bodertop_0" width="8%">Course<br/> Code</th>
							<th class="bodertop_0" width="55%">Course Title</th>
							<th class="bodertop_0" width="7%">Marks Obtained</th>
							<th class="bodertop_0" width="6%">Max Marks</th>
							<th class="bodertop_0" width="6%">Credits</th>
							<th width="5%">Grade</th>
							<th class="boderrht_0" width="9%">Month &amp; Year<br/>of Passing</th>
						</tr>
					</table>
				</td>
			</tr>
		</table>
		<table class="consolidtbl boder_0 msColm" cellspacing="0" cellpadding="0" border="0">
			<tr>
				<td width="50%" class="boder_0" style="vertical-align:top;">
					<table class="consolidtbl boder_0 msrow" cellspacing="0" cellpadding="0">';
						//foreach ($markSheetArray as $key => $msValue) {
						$firstColumnCount = count($markSheetArray); 
						for($i=0; $i<=39; $i++) {
							if (isset($markSheetArray[$i]['cm_id'])) {
								//if ($i == 20 || $i==21 || $i==22 || $i==23 || $i==24 || $i==25 || $i==26 || $i==27 || $i==28) $markSheetArray[$i]['course_name'] = "Nano Scale Materials Spectroscopy, Health & Environmental Issues";
								$html1.= "<tr>";
								$html1.= "<td style='vertical-align:top;line-height:18px;' align='center' class='boderbot_0 bodertop_0 boderleft_0' width='4%'>".$markSheetArray[$i]['semester_id']."</td>";
								$html1.= "<td style='vertical-align:top;' align='center' class='boderbot_0 bodertop_0' width='8%' >".$markSheetArray[$i]['course_code']."</td>";
								$html1.= "<td style='vertical-align:top;padding-left:6px;' align='left' class='boderbot_0 bodertop_0' width='55%' >".$markSheetArray[$i]['course_name']."</td>";
								$html1.= "<td style='vertical-align:top;' align='center' class='boderbot_0 bodertop_0' width='7%' >50</td>";
								$html1.= "<td style='vertical-align:top;' align='center' class='boderbot_0 bodertop_0' width='6%' >100</td>";
								$html1.= "<td style='vertical-align:top;' align='center' class='boderbot_0 bodertop_0' width='6%'>".$markSheetArray[$i]['credit_point']."</td>";
								$html1.= "<td style='vertical-align:top;padding-left:10px;' align='left' class='boderbot_0 bodertop_0' width='5%' >".$markSheetArray[$i]['grade']."</td>";	
								$html1.= "<td style='vertical-align:top;' align='center' class='boderbot_0 bodertop_0' width='9%'>".$markSheetArray[$i]['month_year']."</td>";
								$html1.= "</tr>";
							} else {
								$html1.= emptyCMRow("left");
							}
						}
					$html1.='</table>
				</td>
				<td width="50%" class="bodertop_0 boderrht_0 boderbot_0" style="vertical-align:top;">
					<table class="consolidtbl boder_0  msrow" cellspacing="0" cellpadding="0">';
						for($i=40; $i<=79; $i++) {
							if (isset($markSheetArray[$i]['semester_id'])) {
								$html1.= "<tr>";
								$html1.= "<td style='vertical-align:top;line-height:18px;' align='center' class='boderbot_0 bodertop_0' width='4%'>".$markSheetArray[$i]['semester_id']."</td>";
								$html1.= "<td style='vertical-align:top;' align='center' class='boderbot_0 bodertop_0' width='8%' >".$markSheetArray[$i]['course_code']."</td>";
								$html1.= "<td style='vertical-align:top;padding-left:6px;' align='left' class='boderbot_0 bodertop_0' width='55%' >".$markSheetArray[$i]['course_name']."</td>";
								$html1.= "<td style='vertical-align:top;' align='center' class='boderbot_0 bodertop_0' width='7%' >100</td>";
								$html1.= "<td style='vertical-align:top;' align='center' class='boderbot_0 bodertop_0' width='6%' >100</td>";
								$html1.= "<td style='vertical-align:top;' align='center' class='boderbot_0 bodertop_0' width='6%'>".$markSheetArray[$i]['credit_point']."</td>";
								$html1.= "<td style='vertical-align:top;padding-left:10px;' align='left' class='boderbot_0 bodertop_0' width='5%' >".$markSheetArray[$i]['grade']."</td>";	
								$html1.= "<td style='vertical-align:top;' align='center' class='boderbot_0 bodertop_0 boderrht_0' width='9%'>".$markSheetArray[$i]['month_year']."</td>";
								$html1.= "</tr>";
							} else {
								$html1.= emptyCMRow("right");
							}
						}
					$html1.='</table>
				</td>
			</tr>
			<tr>
				<td class="boderleft_0 boderbot_0">
		<table cellspacing="0" cellpadding="0" border="0" width="100%;" class="cMGrade">
		  <tbody>
			<tr>
			<td width="29%" style="padding-left:10px;" class="bodertop_0 boderleft_0 "><strong>Semester</strong></td>
			<td width="7%" align="center" class="bodertop_0 boderleft_0"><strong>I</strong></td>
			<td width="7%" align="center" class="bodertop_0 boderleft_0"><strong>II</strong></td>
			<td width="7%" align="center" class="bodertop_0 boderleft_0"><strong>III</strong></td>
			<td width="7%" align="center" class="bodertop_0 boderleft_0"><strong>IV</strong></td>
			<td width="8%" align="center" class="bodertop_0 boderleft_0"><strong>V</strong></td>
			<td width="7%" align="center" class="bodertop_0 boderleft_0"><strong>VI</strong></td>
			<td width="7%" align="center" class="bodertop_0 boderleft_0"><strong>VII</strong></td>
			<td width="7%" align="center" class="bodertop_0 boderleft_0"><strong>VIII</strong></td>
			<td width="7%" align="center" class="bodertop_0 boderleft_0"><strong>IX</strong></td>
			<td width="7%" align="center" class="bodertop_0 boderleft_0 boderrht_0"><strong>X</strong></td>
			</tr>
			<tr>
				<td style="padding-left:10px;" class="bodertop_0 boderleft_0"><strong>Credits Registered</strong></td>
				<td align="center" class="bodertop_0 boderleft_0"><strong>XX</strong></td>
				<td align="center" class="bodertop_0 boderleft_0"><strong></strong></td>
				<td align="center" class="bodertop_0 boderleft_0"><strong></strong></td>
				<td align="center" class="bodertop_0 boderleft_0"><strong></strong></td>
				<td align="center" class="bodertop_0 boderleft_0"><strong></strong></td>
				<td align="center" class="bodertop_0 boderleft_0"><strong></strong></td>
				<td align="center" class="bodertop_0 boderleft_0"><strong></strong></td>
				<td align="center" class="bodertop_0 boderleft_0"><strong></strong></td>
				<td align="center" class="bodertop_0 boderleft_0"><strong></strong></td>
				<td align="center" class="bodertop_0 boderleft_0 boderrht_0"><strong></strong></td>
			</tr>	
			<tr>
				<td style="padding-left:10px;" class="bodertop_0 boderleft_0"><strong>Credits Earned</strong></td>
				<td align="center" class="bodertop_0 boderleft_0"><strong>XX</strong></td>
				<td align="center" class="bodertop_0 boderleft_0"><strong></strong></td>
				<td align="center" class="bodertop_0 boderleft_0"><strong></strong></td>
				<td align="center" class="bodertop_0 boderleft_0"><strong></strong></td>
				<td align="center" class="bodertop_0 boderleft_0"><strong></strong></td>
				<td align="center" class="bodertop_0 boderleft_0"><strong></strong></td>
				<td align="center" class="bodertop_0 boderleft_0"><strong></strong></td>
				<td align="center" class="bodertop_0 boderleft_0"><strong></strong></td>
				<td align="center" class="bodertop_0 boderleft_0"><strong></strong></td>
				<td align="center" class="bodertop_0 boderleft_0 boderrht_0"><strong></strong></td>
			</tr>
			<tr>
				<td style="padding-left:10px;" class="bodertop_0 boderleft_0"><strong>Grade Points Earned</strong></td>
				<td align="center" class="bodertop_0 boderleft_0"><strong>XX</strong></td>
				<td align="center" class="bodertop_0 boderleft_0"><strong></strong></td>
				<td align="center" class="bodertop_0 boderleft_0"><strong></strong></td>
				<td align="center" class="bodertop_0 boderleft_0"><strong></strong></td>
				<td align="center" class="bodertop_0 boderleft_0"><strong></strong></td>
				<td align="center" class="bodertop_0 boderleft_0"><strong></strong></td>
				<td align="center" class="bodertop_0 boderleft_0"><strong></strong></td>
				<td align="center" class="bodertop_0 boderleft_0"><strong></strong></td>
				<td align="center" class="bodertop_0 boderleft_0"><strong></strong></td>
				<td align="center" class="bodertop_0 boderleft_0 boderrht_0"><strong></strong></td>
			</tr>
			<tr>
				<td style="padding-left:10px;" class="bodertop_0 boderleft_0"><strong>Grade Point Average (GPA)</strong></td>
				<td align="center" class="bodertop_0 boderleft_0"><strong>X.XX</strong></td>
				<td align="center" class="bodertop_0 boderleft_0"><strong></strong></td>
				<td align="center" class="bodertop_0 boderleft_0"><strong></strong></td>
				<td align="center" class="bodertop_0 boderleft_0"><strong></strong></td>
				<td align="center" class="bodertop_0 boderleft_0"><strong></strong></td>
				<td align="center" class="bodertop_0 boderleft_0"><strong></strong></td>
				<td align="center" class="bodertop_0 boderleft_0"><strong></strong></td>
				<td align="center" class="bodertop_0 boderleft_0"><strong></strong></td>
				<td align="center" class="bodertop_0 boderleft_0"><strong></strong></td>
				<td align="center" class="bodertop_0 boderleft_0 boderrht_0"><strong></strong></td>
			</tr>   
		 <tr>
			<td style="padding-left:10px;" class="bodertop_0 boderleft_0"><strong>Cumulative Credits Earned</strong></td>
			<td align="center" class="bodertop_0 boderleft_0"><strong>XX</strong></td>
			<td align="left" style="padding-left:5px;" colspan="9" class="bodertop_0 boderleft_0 boderrht_0"><strong>CLASS OBTAINED : ';
			$html1.= strtoupper($degree_classification['E']);
			$html1.= '</strong></td>
		</tr>
		  <tr>
			<td align="left" class="boder_0" style="padding-left:10px;"><strong>Total Marks Obtained</strong></td>
			<td align="left" colspan="2" class="boder_0"><strong>XXXX / XXXX</strong></td>
			<td align="left" style="padding-left:5px;" colspan="8" class="bodertop_0 boderbot_0 boderrht_0"><strong>Cumulative Grade Point Average (CGPA) : ';
			$html1.= $cgpa;
			$html1.='</strong></td>
		</tr>
		</tbody>
		</table>
		</td>
		<td class="boderrht_0 boderbot_0">
		<table class="consolidtbl bodertop_0 boder_0" cellspacing="0" cellpadding="0" border="0">
			<tr>
				<td class="boder_0 " align="center" width="25%"><strong>';	
				$html1 .= $this->Html->image("certificate_signature/".$getSignature[0]['Signature']['signature']); //, ["alt" => h("register.png"),"style"=>"visibility:hidden;"]	
				$html1 .= "<br/>".ucwords(strtolower($result['Student']['name']));
				$html1 .= "<br/>Sign of Candidate";
				$html1 .='</strong></td>
				<td class="boder_0" style="padding-left:10px;">';
				$html1 .='</td>
				<td class="boder_0" align="center" width="25%"><strong>';	
				$html1 .= $this->Html->image("certificate_signature/".$getSignature[1]['Signature']['signature']); //, ["alt" => h("coe.jpg"),"style"=>"height:40px;width:60px;"]	
				$html1 .= "<br/>".$getSignature[1]['Signature']['name'];
				$html1 .= "<br/>Registrar";
				$html1 .='</strong></td>
				<td class="boder_0 " align="center" width="25%"><strong>';	
				$html1 .= $this->Html->image("certificate_signature/".$getSignature[0]['Signature']['signature']); //, ["alt" => h("register.png"),"style"=>"height:40px;width:120px;"]	
				$html1 .= "<br/>".$getSignature[0]['Signature']['name'];
				$html1 .= "<br/>Controller of Examinations";
				$html1 .='</strong></td>
				<td width="25%" class="boder_0 ">&nbsp;</td>
			</tr>
		</table>
		</td></tr></table>
	</td></tr></table>
</td></tr></table>';
echo $html1;
}


	}
}
function emptyRow() {
	$emptyTr = "";
	$emptyTr .= '<tr>
	    <td align="center"><strong>1</strong></td>
	    <td align="center"></td>
	    <td style="line-height:18px;"></td>
	    <td align="center"></td>
	    <td align="center></td>
	    <td align="center"></td>
	    <td align="center"></td>
	  </tr>';
	  return $emptyTr;
}
function emptyCMRow($opt) {
	$emptyTr = "";$applyBdr =""; if ($opt == "right"){$applyBdr = "boderrht_0";}
	$emptyTr.= "<tr>";
	if ($opt == "left")
	$emptyTr.= "<td align='center' class='boderbot_0 bodertop_0 boderleft_0' width='4%' style='line-height:18px;'>&nbsp;</td>";
	else 
	$emptyTr.= "<td align='center' class='boderbot_0 bodertop_0 boderleft_0' width='4%' style='line-height:18px;'>&nbsp;</td>";
	$emptyTr.= "<td align='left' class='boderbot_0 bodertop_0' width='8%' style='padding-left:10px;'>&nbsp;</td>";
	$emptyTr.= "<td align='left' class='boderbot_0 bodertop_0' width='55%' style='padding-left:5px;'>&nbsp;</td>";
	$emptyTr.= "<td align='left' class='boderbot_0 bodertop_0' width='7%' style='padding-left:5px;'>&nbsp;</td>";
	$emptyTr.= "<td align='left' class='boderbot_0 bodertop_0' width='6%' style='padding-left:5px;'>&nbsp;</td>";
	$emptyTr.= "<td align='center' class='boderbot_0 bodertop_0' width='6%'>&nbsp;</td>";
	$emptyTr.= "<td align='left' class='boderbot_0 bodertop_0' width='5%' style='padding-left:14px;'>&nbsp;</td>";	
	$emptyTr.= "<td align='center' class='boderbot_0 bodertop_0 $applyBdr' width='9%'>&nbsp;</td>";
	$emptyTr.= "</tr>";
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