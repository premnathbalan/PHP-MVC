<!-- Replace this text for XXX '.$this->Html->getMonthYearFromMonthYearId(max(array_column($markSheetArray, 'month_year_id'))).' -->
<?php
$a = 1;
if ($results) { 
	foreach ( $results as $k => $result ) { //pr($result);
	$program_credit = $result['Program']['credits'];
	
	if (isset($result['StudentAuthorizedBreak'][0]['student_id'])) $abs=1;
	else $abs=0;
	
	//echo "ABS : ".$abs;

	if (isset($result['StudentWithdrawal'][0]['student_id'])) $withdrawal=1;
	else $withdrawal=0;
	
	//echo "Withdrawal : ".$withdrawal;
	
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
		
		for ($i=1; $i<=10; $i++) { ${"semester".$i."CourseReg"} = "0"; }
		for ($i=1; $i<=10; $i++) { ${"semester".$i."CourseCreditEarned"} = "0"; }
		for ($i=1; $i<=10; $i++) { ${"semester".$i."GradePointEarned"} = "0"; }
		for ($i=1; $i<=10; $i++) { ${"semester".$i."Gpa"} = "0"; }
		for ($i=1; $i<=10; $i++) { ${"semester".$i."Gpa_1"} = "0"; }
		
		$batchId = $result['Batch']['id'];
		$programId = $result['Program']['id'];
		
		$TotCreditPoints ="";$earnCreditPoints = "";$semesterPassCnt = "0";$noOfCourses = 0;$noOfArrears = "";
		
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
		//pr($csm_array);
		
		foreach ($csm_array as $key => $cmValue) {
			$cmArray[$cmValue['course_mapping_id']] = $cmValue['type'];
		}
		//pr($cmArray);
		
		if (!empty($result['StudentAuditCourse']) && count($result['StudentAuditCourse']) >0) {
			$audit_courses = $result['StudentAuditCourse'];
		}
	
		$cntt = 0;
		$markSheetArray = array();
		$courseActualSemester = array();
		$coursePassMonthYear = array();
		$coursePassSemester = array();
		$sheet = "";
		$cumulative_credits_earned = 0;
		$cgpa_all = 0;
		$q = 0;
		
		foreach ($finalArray as $course_mapping_id => $stuValue) {
			//pr($stuValue);
		 	$courseMId = $stuValue['course_mapping_id'];
		 	$courseActualSemester[$courseMId] = $stuValue['CourseMapping']['semester_id'];
			$coursePassMonthYear[$courseMId] = $stuValue['month_year_id'];
			$coursePassSemester[$courseMId] = $this->Html->retrieveSemesterFromMonthYear($stuValue['month_year_id'], $batchId, $programId); 
			
			if($stuValue['revaluation_status'] == 0){
				$stuFinalMark[$courseMId] = $stuValue['marks'];
				$stuFinalStatus[$courseMId] = $stuValue['status'];
			}else{
				$stuFinalMark[$courseMId] = $stuValue['final_marks'];
				$stuFinalStatus[$courseMId] = $stuValue['final_status'];				
			}
			
			$CourseCP = $stuValue['CourseMapping']['Course']['credit_point'];
			
			if ($stuFinalStatus[$courseMId] == "Pass") {
				$markSheetArray[$q] = array(
					'cm_id' => $courseMId,
					'semester_id' => $stuValue['CourseMapping']['semester_id'],
					'month_year_id' => $stuValue['month_year_id'],
					'course_code' => $stuValue['CourseMapping']['Course']['course_code'],
					'course_name' => $stuValue['CourseMapping']['Course']['course_name'],
					'credit_point' => $stuValue['CourseMapping']['Course']['credit_point'],
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
		//End for loop line no. 89
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
		
		echo "numerator : ".$numerator." denominator : ".$denominator;
		
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
		
		/*for($kp=0; $kp<=69; $kp++) {
			$tmp = $kp+1;
	    	$markSheetArray[] = array(
	    		'cm_id' => 'Xtra'.$kp,
	    		'month_year_id' => "1",
				'semester_id' => "3",
				'course_code' => "abcd",
				'course_name' => "Materials Spectroscopy, Health & Environmental Issues ".$tmp,
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
	    if ($totCount > 39) {
	 		array_insert1($markSheetArray, $EOSArray, 38);
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
<?php echo $this->Html->css('ms');?>
<?php
if (count($markSheetArray) <= 76) {
$html1 = '
<table class="page"><tr><td style="vertical-align:top;" class=" boder_0">
	<table class="consolidoutertbl" align="center"><tr><td style="vertical-align:top;" class=" boder_0"><tr><td class="boder_0">
		<table class="consolidtbl boder_0" cellspacing="0" cellpadding="0" border="0">
			<tr>
				<td style="height:81px;font-size:15px;" valign="top" class="boder_0">
					<div style="border-width: 0px; width: 250px; margin-right: -70px; margin-top:30px; border-color: rgb(0, 0, 0); border-style: solid; text-align: left; position: absolute; right: 0px;">
						Sl. No. &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;: <br/><br/>
						Folio No. &nbsp;&nbsp;: ';
							$seqFolioNo = $seqFolioNo+1; 
						    if(strlen($seqFolioNo) == 1){$seqFolioNo = "0000".$seqFolioNo;}
							else if(strlen($seqFolioNo)==2){$seqFolioNo = "000".$seqFolioNo;}
							else if(strlen($seqFolioNo)==3){$seqFolioNo = "00".$seqFolioNo;}
							else if(strlen($seqFolioNo)==4){$seqFolioNo = "0".$seqFolioNo;}
							else {$seqFolioNo = $seqFolioNo;}    
					    $html1 .=date('dmy').$type_of_cert.$seqFolioNo;
					$html1.='</div>
				</td>
			</tr>
		</table>
		<table class="consolidtbl boder_0" cellspacing="0" cellpadding="0" border="0">
			<tr>
				<td align="center" class="boder_0"><h2>CONSOLIDATED GRADE SHEET</h2></td>
			</tr>
		</table>
		<table class="consolidtbl boder_0" cellspacing="0" cellpadding="0" border="0">
			<tr>
				<td width="50%" class="boder_0">
					<table class="consolidtbl boder_0 min-hei79 txtind " cellspacing="0" cellpadding="0">
						<tr>
							<td class="" width="35%">NAME OF THE CANDIDATE</td>
							<td class=""><strong>'.strtoupper($result['Student']['name']).'</strong></td>
						</tr>
						<tr>
							<td class="">DATE OF PUBLICATION</td>
							<td class=""><strong>03/08/2015</strong></td>
						</tr>
						<tr>
							<td class="">COURSE & BRANCH</td>
							<td class="  "><strong>'.strtoupper($result['Academic']['short_code']).' - '.strtoupper($result['Program']['program_name']).'</strong></td>
						</tr>
					</table>
				</td>
				<td width="50%" class="bodertop_0 boderrht_0 boderbot_0">
					<table class="consolidtbl boder_0 min-hei79 txtind" cellspacing="0" cellpadding="0">
						<tr>
							<td width="25%" class="boderleft_0 ">REGISTER No.</td>
							<td colspan="3" class=""><strong>'.$result['Student']['registration_number'].'</strong></td>
						</tr>
						<tr>
							<td class="boderleft_0">DATE OF BIRTH</td>
							<td width="25%"><strong>'.date( "d-M-Y", strtotime(h($result['Student']['birth_date']))).'</strong></td>
							<td>GENDER</td>
							<td class="" width="35%"><strong>';
							if($result['Student']['gender'] == "F"){ $html1 .=strtoupper("Female");}else{ $html1 .=strtoupper("Male");}
							$html1.='</strong></td>
						</tr>
						<tr>
							<td class="bodertop_0 boderleft_0" width="45%">MONTH & YEAR OF LAST APPREARANCE</td>
							<td class="bodertop_0" width="25%" align="center"><strong>XXX</strong></td> 
							<td class="bodertop_0" width="10%">BATCH</td>
							<td class="bodertop_0" width="20%"><strong>'.$this->Html->getBatch($batchId).'</strong></td>
						</tr>
					</table>
				</td>
			</tr>
		</table>
		<table class="consolidtbl boder_0" cellspacing="0" cellpadding="0" border="0">
			<tr>
				<td width="50%" class="boder_0">
					<table class="consolidtbl boder_0 txtind" cellspacing="0" cellpadding="0">
						<tr>
							<th class="bodertop_0" width="5%">SEM</th>
							<th class="bodertop_0" width="15%">SUBJECT CODE</th>
							<th class="bodertop_0" width="50%">SUBJECT TITLE</th>
							<th class="bodertop_0" width="8%">CREDIT</th>
							<th class="bodertop_0" width="7%">GRADE</th>
							<th class="bodertop_0" width="15%">MONTH &amp; YEAR<br/>OF PASSING</th>
						</tr>
					</table>
				</td>
				<td width="50%" class="bodertop_0 boderrht_0 boderbot_0">
					<table class="consolidtbl boder_0 txtind" cellspacing="0" cellpadding="0">
						<tr>
							<th class="bodertop_0 boderleft_0" width="5%" class="boderleft_0">SEM</th>
							<th class="bodertop_0" width="15%">SUBJECT CODE</th>
							<th class="bodertop_0" width="50%">SUBJECT TITLE</th>
							<th class="bodertop_0" width="8%">CREDIT</th>
							<th class="bodertop_0" width="7%">GRADE</th>
							<th class="bodertop_0" width="15%">MONTH &amp; YEAR<br/>OF PASSING</th>
						</tr>
					</table>
				</td>
			</tr>
		</table>
		<table class="consolidtbl boder_0" cellspacing="0" cellpadding="0" border="0">
			<tr>
				<td width="50%" class="boder_0" style="vertical-align:top;">
					<table class="consolidtbl boder_0 mstxtind msrow" cellspacing="0" cellpadding="0">';
						//foreach ($markSheetArray as $key => $msValue) {
						$firstColumnCount = count($markSheetArray); 
						for($i=0; $i<=38; $i++) {
							if (isset($markSheetArray[$i]['cm_id'])) {
								if ($i == 20 || $i==21 || $i==22 || $i==23 || $i==24 || $i==25 || $i==26 || $i==27 || $i==28) $markSheetArray[$i]['course_name'] = "Nano Scale Materials Spectroscopy, Health & Environmental Issues";
								$html1.= "<tr>";
								$html1.= "<td style='vertical-align:top;' align='center' class='boderbot_0 bodertop_0' width='5%'>".$markSheetArray[$i]['semester_id']."</td>";
								$html1.= "<td style='vertical-align:top;' align='left' class='boderbot_0 bodertop_0' width='15%' style='padding-left:10px;line-height:19px;'>".$markSheetArray[$i]['course_code']."</td>";
								$html1.= "<td style='vertical-align:top;' align='left' class='boderbot_0 bodertop_0' width='50%' style='padding-left:5px;'>".$markSheetArray[$i]['course_name']."</td>";
								$html1.= "<td style='vertical-align:top;' align='center' class='boderbot_0 bodertop_0' width='8%'>".$markSheetArray[$i]['credit_point']."</td>";
								$html1.= "<td style='vertical-align:top;' align='left' class='boderbot_0 bodertop_0' width='7%' style='padding-left:14px;'>".$markSheetArray[$i]['grade']."</td>";	
								$html1.= "<td style='vertical-align:top;' align='center' class='boderbot_0 bodertop_0' width='15%'>".$markSheetArray[$i]['month_year']."</td>";
								$html1.= "</tr>";
							} else {
								$html1.= emptyCMRow("left");
							}
						}
					$html1.='</table>
				</td>
				<td width="50%" class="bodertop_0 boderrht_0 boderbot_0" style="vertical-align:top;">
					<table class="consolidtbl boder_0 mstxtind msrow" cellspacing="0" cellpadding="0">';
						for($i=39; $i<=76; $i++) {
							if (isset($markSheetArray[$i]['semester_id'])) {
								$html1.= "<tr>";
								$html1.= "<td style='vertical-align:top;' align='center' class='boderbot_0 bodertop_0 boderleft_0' width='5%'>".$markSheetArray[$i]['semester_id']."</td>";
								$html1.= "<td style='vertical-align:top;' align='left' class='boderbot_0 bodertop_0' width='15%' style='padding-left:10px;line-height:19px;'>".$markSheetArray[$i]['course_code']."</td>";
								$html1.= "<td style='vertical-align:top;' align='left' class='boderbot_0 bodertop_0' width='50%' style='padding-left:5px;'>".$markSheetArray[$i]['course_name']."</td>";
								$html1.= "<td style='vertical-align:top;' align='center' class='boderbot_0 bodertop_0' width='8%'>".$markSheetArray[$i]['credit_point']."</td>";
								$html1.= "<td style='vertical-align:top;' align='left' class='boderbot_0 bodertop_0' width='7%' style='padding-left:14px;'>".$markSheetArray[$i]['grade']."</td>";	
								$html1.= "<td style='vertical-align:top;' align='center' class='boderbot_0 bodertop_0' width='15%'>".$markSheetArray[$i]['month_year']."</td>";
								$html1.= "</tr>";
							} else {
								$html1.= emptyCMRow("right");
							}
						}
					$html1.='</table>
				</td>
			</tr>
		</table>
		<table class="consolidtbl" cellspacing="0" cellpadding="0" border="0">
			<tr>
				<td class="boder_0" style="padding-left:10px;">
				Jeppiaar Nagar, Chennai-600119,</br>
				Tamil Nadu, INDIA.
				</td>
				<td class="boder_0" style="padding-left:10px;">CLASS OBTAINED : ';
				$html1.=strtoupper($degree_classification['E']);
				$html1.="</br></br>Overall CGPA : ".$cgpa;
				$html1.='</td>
				<td class="boder_0" align="left" v-align="bottom" style="padding-left:10px;height:100px;"><strong>';	
				$html1 .= $this->Html->image("certificate_signature/".$getSignature[1]['Signature']['signature']); //, ["alt" => h("coe.jpg"),"style"=>"height:40px;width:120px;"]	
				$html1 .= "<br/>".$getSignature[1]['Signature']['name'];
				$html1 .='</strong></td>
				<td class="boder_0" align="right" v-align="bottom" style="padding-right:10px;height:100px;"><strong>';	
				$html1 .= $this->Html->image("certificate_signature/".$getSignature[0]['Signature']['signature']); //, ["alt" => h("register.png"),"style"=>"height:40px;width:120px;"]	
				$html1 .= "<br/>".$getSignature[0]['Signature']['name'];
				$html1 .='</strong></td>
				<td class="boder_0" style="width:160px;"></td>	
			</tr>
		</table>
	</td></tr></table>
</td></tr></table>
';
echo $html1;
}


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
function emptyCMRow($opt) {
	$emptyTr = "";
	$emptyTr.= "<tr>";
	if ($opt == "left")
	$emptyTr.= "<td align='center' class='boderbot_0 bodertop_0' width='5%'>&nbsp;</td>";
	else 
	$emptyTr.= "<td align='center' class='boderbot_0 bodertop_0 boderleft_0' width='5%'>&nbsp;</td>";
	$emptyTr.= "<td align='left' class='boderbot_0 bodertop_0' width='15%' style='padding-left:10px;line-height:19px;'>&nbsp;</td>";
	$emptyTr.= "<td align='left' class='boderbot_0 bodertop_0' width='50%' style='padding-left:5px;'>&nbsp;</td>";
	$emptyTr.= "<td align='center' class='boderbot_0 bodertop_0' width='8%'>&nbsp;</td>";
	$emptyTr.= "<td align='left' class='boderbot_0 bodertop_0' width='7%' style='padding-left:14px;'>&nbsp;</td>";	
	$emptyTr.= "<td align='center' class='boderbot_0 bodertop_0' width='15%'>&nbsp;</td>";
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