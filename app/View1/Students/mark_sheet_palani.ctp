<?php $a=1;	
if($results){ foreach($results as $result){
		$courseMark = ""; 		
		
		$stuInternalArray = array();
		$stuESArray = array();
		$stuFinalMark = array();
		$stuMarkStatus = array();
		$examMonthYear = "";
		$publishing_date = array();
		$CourseCP =  "";
		$courseMark1= "";$courseMark2= "";$courseMark3= "";$html1 = "";$html2 = "";$html4 = "";
		
		//For Theory Revaluation		
		for($p=0;$p<count($result['RevaluationExam']);$p++){			
			$stuESArray[$result['RevaluationExam'][$p]['course_mapping_id']] = $result['RevaluationExam'][$p]['revaluation_marks'];
		}		
		if($result['Student']['university_references_id'] == 1 && $result['Student']['parent_id'] != 0){
			for($p=0;$p<count($result['ParentGroup']['RevaluationExam']);$p++){			
				$stuESArray[$result['ParentGroup']['RevaluationExam'][$p]['course_mapping_id']] = $result['ParentGroup']['RevaluationExam'][$p]['revaluation_marks'];
			}
		}
		
		//For Theory External
		for($p=0;$p<count($result['EndSemesterExam']);$p++){			
			if($result['EndSemesterExam'][$p]['revaluation_status'] == 0){
				$stuESArray[$result['EndSemesterExam'][$p]['course_mapping_id']] = $result['EndSemesterExam'][$p]['marks'];
			}else{
				if($stuESArray[$result['EndSemesterExam'][$p]['course_mapping_id']] < $result['EndSemesterExam'][$p]['marks']){
					$stuESArray[$result['EndSemesterExam'][$p]['course_mapping_id']] = $result['EndSemesterExam'][$p]['marks'];
				}
			}		
		}
		if($result['Student']['university_references_id'] == 1 && $result['Student']['parent_id'] != 0){
			for($p=0;$p<count($result['ParentGroup']['EndSemesterExam']);$p++){			
				if($result['ParentGroup']['EndSemesterExam'][$p]['revaluation_status'] == 0){
					$stuESArray[$result['ParentGroup']['EndSemesterExam'][$p]['course_mapping_id']] = $result['ParentGroup']['EndSemesterExam'][$p]['marks'];
				}else{
					if($stuESArray[$result['ParentGroup']['EndSemesterExam'][$p]['course_mapping_id']] < $result['ParentGroup']['EndSemesterExam'][$p]['marks']){
						$stuESArray[$result['ParentGroup']['EndSemesterExam'][$p]['course_mapping_id']] = $result['ParentGroup']['EndSemesterExam'][$p]['marks'];
					}
				}		
			}
		}
						
	
		//For Practical	External	
		for($q=0;$q<count($result['Practical']);$q++){	
			$practicalExternalMarks = $result['Practical'][$q]['marks'];
			if($practicalExternalMarks == '0'){$practicalExternalMarks = "&nbsp;0";}						
			$stuESArray[$result['Practical'][$q]['EsePractical']['course_mapping_id']] = $practicalExternalMarks;
			$practicalExternalMarks = "";												
		}
		
		//For Project External	
		for($q=0;$q<count($result['ProjectViva']);$q++){	
			$projectExternalMarks = $result['ProjectViva'][$q]['marks'];
			if($projectExternalMarks == '0'){$projectExternalMarks = "&nbsp;0";}						
			$stuESArray[$result['ProjectViva'][$q]['EseProject']['course_mapping_id']] = $projectExternalMarks;
			$projectExternalMarks = "";												
		}
		if($result['Student']['university_references_id'] == 1 && $result['Student']['parent_id'] != 0){
			$practicalExternalMarks = $result['ParentGroup']['Practical'][$q]['marks'];
			if($practicalExternalMarks == '0'){$practicalExternalMarks = "&nbsp;0";}						
			$stuESArray[$result['ParentGroup']['Practical'][$q]['EsePractical']['course_mapping_id']] = $practicalExternalMarks;
			$practicalExternalMarks = "";
		}	
			
		$TotCreditPoints ="";$earnCreditPoints = "";$semesterPassCnt = "0";$noOfCourses = 0;$noOfArrears = "";
		
		$stuSemesterMark1 = "";$stuSemesterMark2 = "";$stuSemesterMark3 = "";$stuSemesterMark4 = "";$stuSemesterMark5 = "";
		$stuSemesterMark6 = "";$stuSemesterMark7 = "";$stuSemesterMark8 = "";$stuSemesterMark9 = "";$stuSemesterMark10 = "";
		
		$stuSemesterMark1_1 = "";$stuSemesterMark2_1 = "";$stuSemesterMark3_1 = "";$stuSemesterMark4_1 = "";$stuSemesterMark5_1 = "";
		$stuSemesterMark6_1 = "";$stuSemesterMark7_1 = "";$stuSemesterMark8_1 = "";$stuSemesterMark9_1 = "";$stuSemesterMark10_1 = "";
$stuSemesterMark1_1_hema='';
$stuSemesterMark1_hema='';
$semester1CourseCredit_hema='';
		$semester1CourseReg = "0";$semester2CourseReg = "0";$semester3CourseReg = "0";$semester4CourseReg = "0";$semester5CourseReg = "0";
		$semester6CourseReg = "0";$semester7CourseReg = "0";$semester8CourseReg = "0";$semester9CourseReg = "0";$semester10CourseReg = "0";
		
		$semester1CourseCredit = "0";$semester2CourseCredit = "0";$semester3CourseCredit = "0";$semester4CourseCredit = "0";$semester5CourseCredit = "0";
		$semester6CourseCredit = "0";$semester7CourseCredit = "0";$semester8CourseCredit = "0";$semester9CourseCredit = "0";$semester10CourseCredit = "0";
		
		if($result['Student']['university_references_id'] == 1 && $result['Student']['parent_id'] != 0 && count($result['ParentGroup']['StudentMark'])>0){
			$result['StudentMark'] = $result['ParentGroup']['StudentMark'];
		}
		//pr($result['StudentMark']);
		for($p=0;$p<count($result['StudentMark']);$p++){					
			$courseMId = $result['StudentMark'][$p]['course_mapping_id'];
			$examMonthYear = $result['StudentMark'][$p]['MonthYear']['Month']['month_name']."-".$result['StudentMark'][$p]['MonthYear']['year'];
			$stuMarkSemester[$courseMId] = $result['StudentMark'][$p]['CourseMapping']['semester_id'];
			//pr($stuMarkSemester[$courseMId]);
			$publishing_date = $result['StudentMark'][$p]['MonthYear']['publishing_date'];
			if($result['StudentMark'][$p]['revaluation_status'] == 0){
				$stuFinalMark[$courseMId] = $result['StudentMark'][$p]['marks'];
				$stuMarkStatus[$courseMId] = $result['StudentMark'][$p]['status'];
			}else{
				$stuFinalMark[$courseMId] = $result['StudentMark'][$p]['final_marks'];
				$stuMarkStatus[$courseMId] = $result['StudentMark'][$p]['final_status'];				
			}
			//$CourseCP = $this->Html->getGP($courseMId,$stuFinalMark[$courseMId],'1');
				$CourseCP = $result['StudentMark'][$p]['CourseMapping']['Course']['credit_point'];
							
			if($stuMarkStatus[$courseMId] == 'Pass'){
				$semesterPassCnt = $semesterPassCnt +1;
				$earnCreditPoints = $earnCreditPoints + $CourseCP;
			}									
			//pr($stuFinalMark);
			if(isset($stuFinalMark[$courseMId])){	
			
		//	pr($stuMarkSemester);
				//$RANGE_OF_MARKS_FOR_GRADES = $this->Html->getGP($courseMId,$stuFinalMark[$courseMId],'2');
					$RANGE_OF_MARKS_FOR_GRADES = $result['StudentMark'][$p]['grade_point'];
				$TotCreditPoints = $TotCreditPoints + $CourseCP;
				$noOfCourses = $noOfCourses +1; 
				//echo $stuMarkSemester[$courseMId]."***";
				
				/* Code by Hema */
				
				if($stuMarkSemester[$courseMId] == 1 && $result['StudentMark'][$p]['CourseMapping']['month_year_id']!=$examMonth){
					$semester1CourseReg = $semester1CourseReg + $CourseCP;
					if($stuMarkStatus[$courseMId] == 'Pass'){
						$semester1CourseCredit_hema = $semester1CourseCredit_hema +$CourseCP;
						$stuSemesterMark1_hema = $stuSemesterMark1_hema+$RANGE_OF_MARKS_FOR_GRADES;
					}					
					$stuSemesterMark1_1_hema = $stuSemesterMark1_1_hema+($result['StudentMark'][$p]['CourseMapping']['Course']['credit_point']*$RANGE_OF_MARKS_FOR_GRADES);
					//echo $courseMId." ".$stuSemesterMark1_1_hema." ".$semester1CourseCredit_hema."</br>";
				}
				
				/* Code by Hema ends here */
				
				if($stuMarkSemester[$courseMId] == 1 && $result['StudentMark'][$p]['month_year_id']==$examMonth){ 
					$semester1CourseReg = $semester1CourseReg + $CourseCP;
					if($stuMarkStatus[$courseMId] == 'Pass'){
						$semester1CourseCredit = $semester1CourseCredit +$CourseCP;
						$stuSemesterMark1 = $stuSemesterMark1+$RANGE_OF_MARKS_FOR_GRADES;
					}					
					$stuSemesterMark1_1 = $stuSemesterMark1_1+($result['StudentMark'][$p]['CourseMapping']['Course']['credit_point']*$RANGE_OF_MARKS_FOR_GRADES);
				}
				if($stuMarkSemester[$courseMId] == 2 && $result['StudentMark'][$p]['month_year_id']==$examMonth){
					$semester2CourseReg = $semester2CourseReg + $CourseCP;
					if($stuMarkStatus[$courseMId] == 'Pass'){
						$semester2CourseCredit = $semester2CourseCredit +$CourseCP;
						$stuSemesterMark2 = $stuSemesterMark2+$RANGE_OF_MARKS_FOR_GRADES;
					}							
					$stuSemesterMark2_1 = $stuSemesterMark2_1+($result['StudentMark'][$p]['CourseMapping']['Course']['credit_point']*$RANGE_OF_MARKS_FOR_GRADES);
				}
				if($stuMarkSemester[$courseMId] == 3 && $result['StudentMark'][$p]['month_year_id']==$examMonth){
					$semester3CourseReg = $semester3CourseReg + $CourseCP;
					if($stuMarkStatus[$courseMId] == 'Pass'){
						$semester3CourseCredit = $semester3CourseCredit +$CourseCP;
						$stuSemesterMark3 = $stuSemesterMark3+$RANGE_OF_MARKS_FOR_GRADES;
					}					
					$stuSemesterMark3_1 = $stuSemesterMark3_1+($result['StudentMark'][$p]['CourseMapping']['Course']['credit_point']*$RANGE_OF_MARKS_FOR_GRADES);					
				}
				if($stuMarkSemester[$courseMId] == 4 && $result['StudentMark'][$p]['month_year_id']==$examMonth){
					$semester4CourseReg = $semester4CourseReg + $CourseCP;
					if($stuMarkStatus[$courseMId] == 'Pass'){
						$semester4CourseCredit = $semester4CourseCredit +$CourseCP;
						$stuSemesterMark4 = $stuSemesterMark4+$RANGE_OF_MARKS_FOR_GRADES;
					}					
					$stuSemesterMark4_1 = $stuSemesterMark4_1+($result['StudentMark'][$p]['CourseMapping']['Course']['credit_point']*$RANGE_OF_MARKS_FOR_GRADES);					
				}
				if($stuMarkSemester[$courseMId] == 5 && $result['StudentMark'][$p]['month_year_id']==$examMonth){
					$semester5CourseReg = $semester5CourseReg + $CourseCP;
					if($stuMarkStatus[$courseMId] == 'Pass'){
						$semester5CourseCredit = $semester5CourseCredit +$CourseCP;
						$stuSemesterMark5 = $stuSemesterMark5+$RANGE_OF_MARKS_FOR_GRADES;
					}					
					$stuSemesterMark5_1 = $stuSemesterMark5_1+($result['StudentMark'][$p]['CourseMapping']['Course']['credit_point']*$RANGE_OF_MARKS_FOR_GRADES);					
				}
				if($stuMarkSemester[$courseMId] == 6 && $result['StudentMark'][$p]['month_year_id']==$examMonth){
					$semester6CourseReg = $semester6CourseReg + $CourseCP;
					if($stuMarkStatus[$courseMId] == 'Pass'){
						$semester6CourseCredit = $semester6CourseCredit +$CourseCP;
						$stuSemesterMark6 = $stuSemesterMark6+$RANGE_OF_MARKS_FOR_GRADES;
					}					
					$stuSemesterMark6_1 = $stuSemesterMark6_1+($result['StudentMark'][$p]['CourseMapping']['Course']['credit_point']*$RANGE_OF_MARKS_FOR_GRADES);					
				}
				if($stuMarkSemester[$courseMId] == 7 && $result['StudentMark'][$p]['month_year_id']==$examMonth){
					$semester7CourseReg = $semester7CourseReg + $CourseCP;
					if($stuMarkStatus[$courseMId] == 'Pass'){
						$semester7CourseCredit = $semester7CourseCredit +$CourseCP;
						$stuSemesterMark7 = $stuSemesterMark7+$RANGE_OF_MARKS_FOR_GRADES;
					}					
					$stuSemesterMark7_1 = $stuSemesterMark7_1+($result['StudentMark'][$p]['CourseMapping']['Course']['credit_point']*$RANGE_OF_MARKS_FOR_GRADES);					
				}
				if($stuMarkSemester[$courseMId] == 8 && $result['StudentMark'][$p]['month_year_id']==$examMonth){
					$semester8CourseReg = $semester8CourseReg + $CourseCP;
					if($stuMarkStatus[$courseMId] == 'Pass'){
						$semester8CourseCredit = $semester8CourseCredit +$CourseCP;
						$stuSemesterMark8 = $stuSemesterMark8+$RANGE_OF_MARKS_FOR_GRADES;
					}					
					$stuSemesterMark8_1 = $stuSemesterMark8_1+($result['StudentMark'][$p]['CourseMapping']['Course']['credit_point']*$RANGE_OF_MARKS_FOR_GRADES);
				}
				if($stuMarkSemester[$courseMId] == 9 && $result['StudentMark'][$p]['month_year_id']==$examMonth){
					$semester9CourseReg = $semester9CourseReg + $CourseCP;
					if($stuMarkStatus[$courseMId] == 'Pass'){
						$semester9CourseCredit = $semester9CourseCredit +$CourseCP;
						$stuSemesterMark9 = $stuSemesterMark9+$RANGE_OF_MARKS_FOR_GRADES;
					}					
					$stuSemesterMark9_1 = $stuSemesterMark9_1+($result['StudentMark'][$p]['CourseMapping']['Course']['credit_point']*$RANGE_OF_MARKS_FOR_GRADES);					
				}
				if($stuMarkSemester[$courseMId] == 10 && $result['StudentMark'][$p]['month_year_id']==$examMonth){
					$semester10CourseReg = $semester10CourseReg + $CourseCP;
					if($stuMarkStatus[$courseMId] == 'Pass'){
						$semester10CourseCredit = $semester10CourseCredit +$CourseCP;
						$stuSemesterMark10 = $stuSemesterMark10+$RANGE_OF_MARKS_FOR_GRADES;	
					}					
					$stuSemesterMark10_1 = $stuSemesterMark10_1+($result['StudentMark'][$p]['CourseMapping']['Course']['credit_point']*$RANGE_OF_MARKS_FOR_GRADES);					
				}
			}
 			  if($p < 20){
  				$sheet = 1;
			  }else if($p < 40){
			  	$sheet = 2;
			  }else if($p < 60){
			  	 $sheet = 3;
			  }
			if ($result['StudentMark'][$p]['month_year_id'] == $examMonth) { 	
				${'courseMark'.$sheet} .= "<tr>";
				${'courseMark'.$sheet} .= "<td align='center'><strong>".$result['StudentMark'][$p]['CourseMapping']['semester_id']."</strong></td>";
				${'courseMark'.$sheet} .= "<td align='left' style='padding-left:10px;'><strong>".$result['StudentMark'][$p]['CourseMapping']['Course']['course_code']."</strong></td>";
				${'courseMark'.$sheet} .= "<td align='left'><strong>".$result['StudentMark'][$p]['CourseMapping']['Course']['course_name']."</strong></td>";
				${'courseMark'.$sheet} .= "<td align='center'><strong>".$result['StudentMark'][$p]['CourseMapping']['Course']['credit_point']."</strong></td>";
				${'courseMark'.$sheet} .= "<td align='left' style='padding-left:14px;'><strong>";
					if($stuMarkStatus[$courseMId] == 'Fail'){
						${'courseMark'.$sheet} .= "RA";
					}else {
						if($stuFinalMark[$courseMId]){
							${'courseMark'.$sheet} .= $result['StudentMark'][$p]['grade'];
						}else{
							${'courseMark'.$sheet} .= $this->Html->getGrad('A');
						}
					}	
				${'courseMark'.$sheet} .= "</strong></td>";			
				${'courseMark'.$sheet} .= "<td align='center'><strong>";
					if($stuMarkStatus[$courseMId] == 'Fail'){
						${'courseMark'.$sheet} .= "0";
					}else {
						if($result['StudentMark'][$p]['grade_point']){
							${'courseMark'.$sheet} .= $result['StudentMark'][$p]['grade_point'];
						}else{
							${'courseMark'.$sheet} .= "0";
						}
					}		
				${'courseMark'.$sheet} .= "</strong></td>";		
				${'courseMark'.$sheet} .= "<td align='center'><strong>";
					if($stuMarkStatus[$courseMId] == 'Fail'){
						${'courseMark'.$sheet} .= "RA";
					}else {
						${'courseMark'.$sheet} .=$stuMarkStatus[$courseMId];
					}
				${'courseMark'.$sheet} .= "</strong></td>";
				${'courseMark'.$sheet} .= "</tr>";
			}
		}
?>
<!-- MARK SHEET STARTED -->
<?php echo $this->Html->css('certificate');?><?php
$html1 ='<table class="page"><tr><td>
<table class="cmainhead">
	<tr>
		<td style="height:81px;"></td>
	</tr>
	<tr>
		<td style="height:20px;font-size:15px;" align="center">SEMESTER GRADE SHEET</td>
	</tr>
	<tr>
		<td style="height:20px;font-size:15px;" align="center">';
		if($type_of_cert == "TG"){
			$html1 .= "TRANSCRIPT";
		}
		$html1 .='</td>
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
    <td><strong>Credits Registered</strong></td>
   	<td align="center"><strong>';
   	if($semester1CourseReg){$html4 .=$semester1CourseReg;}
   	$html4 .='</strong></td>
    <td align="center"><strong>';
    if($semester2CourseReg){$html4 .=$semester2CourseReg;}
    $html4 .='</strong></td>
    <td align="center"><strong>';
    if($semester3CourseReg){$html4 .=$semester3CourseReg;}
    $html4 .='</strong></td>
    <td align="center"><strong>';
    if($semester4CourseReg){$html4 .=$semester4CourseReg;}
    $html4 .='</strong></td>
    <td align="center"><strong>';
    if($semester5CourseReg){$html4 .=$semester5CourseReg;}
    $html4 .='</strong></td>
    <td align="center"><strong>';
    if($semester6CourseReg){$html4 .=$semester6CourseReg;}
    $html4 .='</strong></td>
    <td align="center"><strong>';
    if($semester7CourseReg){$html4 .=$semester7CourseReg;}
    $html4 .='</strong></td>
    <td align="center"><strong>';
    if($semester8CourseReg){$html4 .=$semester8CourseReg;}
    $html4 .='</strong></td>
    <td align="center"><strong>';
    if($semester9CourseReg){$html4 .=$semester9CourseReg;}
    $html4 .='</strong></td>
    <td align="center"><strong>';
    if($semester10CourseReg){$html4 .=$semester10CourseReg;}
    $html4 .='</strong></td>
    </tr>	
     <tr>
    <td><strong>Credits Earned</strong></td>
   	<td align="center"><strong>';
   		if($semester1CourseCredit){$html4 .=$semester1CourseCredit;}
   	$html4 .='</strong></td>
    <td align="center"><strong>';
    	if($semester2CourseCredit){$html4 .=$semester2CourseCredit;}
    $html4 .='</strong></td>
    <td align="center"><strong>';
   	    if($semester3CourseCredit){$html4 .=$semester3CourseCredit;}
    $html4 .='</strong></td>
    <td align="center"><strong>';
    	if($semester4CourseCredit){$html4 .=$semester4CourseCredit;}
    $html4 .='</strong></td>
    <td align="center"><strong>';
    	if($semester5CourseCredit){$html4 .=$semester5CourseCredit;}
    $html4 .='</strong></td>
    <td align="center"><strong>';
    	if($semester6CourseCredit){$html4 .=$semester6CourseCredit;}
    $html4 .='</strong></td>
    <td align="center"><strong>';
    	if($semester7CourseCredit){$html4 .=$semester7CourseCredit;}
    $html4 .='</strong></td>
    <td align="center"><strong>';
   		if($semester8CourseCredit){$html4 .=$semester8CourseCredit;}
    $html4 .='</strong></td>
    <td align="center"><strong>';
    	if($semester9CourseCredit){$html4 .=$semester9CourseCredit;}
    $html4 .='</strong></td>
    <td align="center"><strong>';
    	if($semester10CourseCredit){$html4 .=$semester10CourseCredit;}
    $html4 .='</strong></td>
    </tr>	
     <tr>
    	<td><strong>Grade Points Earned</strong></td>
    <td align="center"><strong>';
    if($stuSemesterMark1){	$html4 .=$stuSemesterMark1;}
    $html4 .='</strong></td>
    <td align="center">
    		<strong>';
	    		if($stuSemesterMark2){
					$html4 .=$stuSemesterMark2;
				}
			$html4 .='</strong></td>
    <td align="center">
    	<strong>';	    	
	    	if($stuSemesterMark3){
				$html4 .=$stuSemesterMark3;
			}
    	$html4 .='</strong>
    </td>
    <td align="center">
    	<strong>';
    		if($stuSemesterMark4){
				$html4 .=$stuSemesterMark4;
			}
   		$html4 .='</strong>
   	</td>
    <td align="center">
    	<strong>';
    	if($stuSemesterMark5){
			$html4 .=$stuSemesterMark5;
		}
    	$html4 .='</strong>
    </td>
    <td align="center">
	    <strong>';
	    	if($stuSemesterMark6){
				$html4 .=$stuSemesterMark6;
			}
	    $html4 .='</strong>
    </td>
    <td align="center">
    	<strong>';
    		if($stuSemesterMark7){
				$html4 .=$stuSemesterMark7;
			}
    	$html4 .='</strong>
    </td>
    <td align="center">
    	<strong>';
    		if($stuSemesterMark8){
				$html4 .=$stuSemesterMark8;
			}
    	$html4 .='</strong>
    </td>
    <td align="center">
    	<strong>';
    		if($stuSemesterMark9){
				$html4 .=$stuSemesterMark9;
			}
    	$html4 .='</strong>
    </td>
    <td align="center">
    	<strong>';
    	if($stuSemesterMark10){
			$html4 .=$stuSemesterMark10;
		}
    	$html4 .='</strong>
    </td>
    </tr>
	 <tr>';
	 $totalGP = "";
    $html4 .='<td><strong>Grade Point Average (GPA) </strong></td>
    	<td align="center"><strong>';
    if($stuSemesterMark1_1){	$html4 .=sprintf('%0.2f',round((($stuSemesterMark1_1)/($semester1CourseCredit)),2));}
    $html4 .='</strong></td>
    <td align="center">
    		<strong>';
	    		if($stuSemesterMark2){
					//$totalGP = $totalGP + sprintf('%0.2f',round((($stuSemesterMark2)/($semester2CourseCredit)),2));
					//echo $stuSemesterMark1_1." ".$stuSemesterMark2_1." ".$semester1CourseCredit." ".$semester2CourseCredit."</br>";
					$totalGP = sprintf('%0.2f',round($stuSemesterMark1_1 + $stuSemesterMark2_1) / ($semester1CourseCredit + $semester2CourseCredit));
					//echo "*** ".$stuSemesterMark1_1."*** ".$stuSemesterMark2_1."*** ".$semester1CourseCredit."*** ".$semester2CourseCredit."*** ".$totalGP;
					$html4 .=sprintf('%0.2f',round((($stuSemesterMark2_1)/($semester2CourseCredit)),2));
					//$html4 .=sprintf('%0.2f',round(((($stuSemesterMark2)*($semester2CourseCredit))/($semester2CourseCredit)),2));
					//echo $stuSemesterMark2." * ".$semester2CourseCredit." * ".$semester2CourseCredit." * ".$stuSemesterMark2_1;
				}
			$html4 .='</strong></td>
    <td align="center">
    	<strong>';	    	
	    	if($stuSemesterMark3){
				$totalGP = $totalGP + sprintf('%0.2f',round((($stuSemesterMark3)/($semester3CourseCredit)),2));
				$html4 .=sprintf('%0.2f',round(((($stuSemesterMark3)*($semester3CourseCredit))/($semester3CourseCredit)),2));
			}
    	$html4 .='</strong>
    </td>
    <td align="center">
    	<strong>';
    		if($stuSemesterMark4){
				$totalGP = $totalGP + sprintf('%0.2f',round((($stuSemesterMark4)/($semester4CourseCredit)),2));
				$html4 .=sprintf('%0.2f',round(((($stuSemesterMark4)*($semester4CourseCredit))/($semester4CourseCredit)),2));
			}
   		$html4 .='</strong>
   	</td>
    <td align="center">
    	<strong>';
    	if($stuSemesterMark5){
			$totalGP = $totalGP + sprintf('%0.2f',round((($stuSemesterMark5)/($semester5CourseCredit)),2));
			$html4 .=sprintf('%0.2f',round(((($stuSemesterMark5)*($semester5CourseCredit))/($semester5CourseCredit)),2));
		}
    	$html4 .='</strong>
    </td>
    <td align="center">
	    <strong>';
	    	if($stuSemesterMark6){
				$totalGP = $totalGP + sprintf('%0.2f',round((($stuSemesterMark6)/($semester6CourseCredit)),2));
				$html4 .=sprintf('%0.2f',round(((($stuSemesterMark6)*($semester6CourseCredit))/($semester6CourseCredit)),2));
			}
	    $html4 .='</strong>
    </td>
    <td align="center">
    	<strong>';
    		if($stuSemesterMark7){
				$totalGP = $totalGP + sprintf('%0.2f',round((($stuSemesterMark7)/($semester7CourseCredit)),2));
				$html4 .=sprintf('%0.2f',round(((($stuSemesterMark7)*($semester7CourseCredit))/($semester7CourseCredit)),2));
			}
    	$html4 .='</strong>
    </td>
    <td align="center">
    	<strong>';
    		if($stuSemesterMark8){
				$totalGP = $totalGP + sprintf('%0.2f',round((($stuSemesterMark8)/($semester8CourseCredit)),2));
				$html4 .=sprintf('%0.2f',round(((($stuSemesterMark8)*($semester8CourseCredit))/($semester8CourseCredit)),2));
			}
    	$html4 .='</strong>
    </td>
    <td align="center">
    	<strong>';
    		if($stuSemesterMark9){
				$totalGP = $totalGP + sprintf('%0.2f',round((($stuSemesterMark9)/($semester9CourseCredit)),2));
				$html4 .=sprintf('%0.2f',round(((($stuSemesterMark9)*($semester9CourseCredit))/($semester9CourseCredit)),2));
			}
    	$html4 .='</strong>
    </td>
    <td align="center">
    	<strong>';
    	if($stuSemesterMark10){
			$totalGP = $totalGP + sprintf('%0.2f',round((($stuSemesterMark10)/($semester10CourseCredit)),2));
			$html4 .=sprintf('%0.2f',round(((($stuSemesterMark10)*($semester10CourseCredit))/($semester10CourseCredit)),2));
		}
    	$html4 .='</strong>
    </td>
    </tr>   
  <tr>
    <td><strong>Cumulative Credits Earned </strong></td>
    <td align="center"><strong>';
    $html4 .=$earnCreditPoints;
    $html4 .='</strong></td>
    <td colspan="4" align="center"><strong>Cumulative Grade Point Average (CGPA)</strong></td>
    <td align="center"><strong>';
    //echo "*** ".$stuSemesterMark1_1."*** ".$stuSemesterMark2_1." *** ".$semester1CourseCredit." *** ".$semester2CourseCredit;
    	if(($stuSemesterMark1_1+$stuSemesterMark2_1+$stuSemesterMark3_1+$stuSemesterMark4_1+$stuSemesterMark5_1+$stuSemesterMark6_1+$stuSemesterMark7_1+$stuSemesterMark8_1+$stuSemesterMark9_1+$stuSemesterMark10_1)){
			$html4 .=sprintf('%0.2f',round((($stuSemesterMark1_1+$stuSemesterMark2_1+$stuSemesterMark3_1+$stuSemesterMark4_1+$stuSemesterMark5_1+$stuSemesterMark6_1+$stuSemesterMark7_1+$stuSemesterMark8_1+$stuSemesterMark9_1+$stuSemesterMark10_1)/($semester1CourseCredit+$semester2CourseCredit+$semester3CourseCredit+$semester4CourseCredit+$semester5CourseCredit+$semester6CourseCredit+$semester7CourseCredit+$semester8CourseCredit+$semester9CourseCredit+$semester10CourseCredit)),2));
		}
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
$emptyTr .= '<tr>
    <td align="center" valign="top">&nbsp;</td>
    <td align="center" valign="top">&nbsp;</td>
    <td valign="top">&nbsp;</td>
    <td align="center" valign="top">&nbsp;</td>
    <td align="center" valign="top">&nbsp;</td>
    <td align="center" valign="top">&nbsp;</td>
    <td align="center" valign="top">&nbsp;</td>
  </tr>'; 
  $totCount = count($result['StudentMark']);
  if($totCount <= 20){  	
  	echo $html1."Page 1 of 1".$html2.$courseMark1;
  	for($i=$totCount;$i<20;$i++){ echo $emptyTr;}
  	echo $html4;  	
  }else if($totCount <= 40){
  	echo $html1."Page 1 of 2".$html2.$courseMark1.$html4;  
  	echo $html1."Page 2 of 2".$html2.$courseMark2;
  	for($i=$totCount;$i<40;$i++){ echo $emptyTr;}
  	echo $html4;  	
  }else if($totCount <= 60){
  	echo $html1."Page 1 of 3".$html2.$courseMark1.$html4;
  	echo $html1."Page 2 of 3".$html2.$courseMark2.$html4;
  	echo $html1."Page 3 of 3".$html2.$courseMark3;
  	for($i=$totCount;$i<60;$i++){ echo $emptyTr;}
  	echo $html4;  	
  }
  
	?>
  
<?php }}?>