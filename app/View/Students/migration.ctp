<?php 
echo $this->Html->css("degree");
$a=1;
if($results){ foreach($results as $result){ //pr($result);
//pr($courseMappingMandatory);
		if (isset($result['StudentAuthorizedBreak'][0]['student_id'])) $abs=1;
		else $abs=0;
	
	//echo "ABS : ".$abs;
	
		if (isset($result['StudentWithdrawal'][0]['student_id'])) $withdrawal=1;
		else $withdrawal=0;
	
	//echo "Withdrawal : ".$withdrawal;
	
		//$student_type_id = $result['Student']['student_type_id'];

		$tamil_month = array('January'=>'[dtup','February'=>'gpg;utup','March'=>'khu;r;','April'=>'Vg;uy;','May'=>'Nk',
		'June'=>'[_d;','July'=>'[_iy','August'=>'Mf];l;','September'=>'nrg;lk;gu;','October'=>'mf;Nlhgu;',
		'November'=>'etk;gu;','December'=>'brk;gu;');
		//Code by Hema
		$student_id = $result['Student']['id'];
		for ($i=1; $i<=15; $i++) ${'courseMark'.$i}="";
		$html1 = "";$html2 = "";$html4 = "";
		
		$examMonthYear = array();
		
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
		for($p = 0; $p < count($result['StudentMark']); $p++) {
			$month_year_id = $result['StudentMark'][$p]['month_year_id'];
			if (isset($finalArray[$month_year_id])) {
				$finalArray[$month_year_id][] = $result['StudentMark'][$p];
			} else {
				$finalArray[$month_year_id] = array($result['StudentMark'][$p]);
			}
		}
		//pr($finalArray);
		$totalCreditsGained = 0; $numerator=0;
		foreach($finalArray as $month_year_id => $value) { 
			//for($p=0;$p<count($value);$p++){
			foreach ($value as $key => $array) {
				unset($details);
				$courseMId = $array['course_mapping_id'];
				if($array['revaluation_status'] == 0 && $array['status'] == 'Pass' && $array['status'] != 'Fail') {
					$stuFinalMark[$courseMId] = $array['marks'];
					$stuFinalStatus[$courseMId] = $array['status'];
					$details = getArrayValues($array);
				} else if($array['revaluation_status'] == 1 && $array['final_status'] == 'Pass' && $array['final_status'] != 'Fail'){
					$stuFinalMark[$courseMId] = $array['final_marks'];
					$stuMarkStatus[$courseMId] = $array['final_status'];
					$details = getArrayValues($array);
				}
				if (isset($details) && !empty($details)) {
					$examMonthYear[$courseMId]=$details['examMonthYear']; 
					$publishing_date[$courseMId]=$details['publishing_date'];
					$courseSemester[$courseMId]=$details['courseSemester'];
					$creditsGained[$courseMId]=$details['creditsGained'];
					$gradePointArray[$courseMId]=$details['gradePointArray'];
					$courseCreditArray[$courseMId]=$details['courseCreditArray'];
					$semesterIdArray[$courseMId]=$details['semesterIdArray'];
					$courseCodeArray[$courseMId]=$details['courseCodeArray'];
					$courseNameArray[$courseMId]=$details['courseNameArray'];
					$totalCreditsGained = $totalCreditsGained + $courseCreditArray[$courseMId];
					$semester=$details['semId'];
					$numerator = $numerator + ($details['grade_point'] * $details['creditsGained']);
					//echo "</br>num :".$numerator;
					${'courseMark'.$semester} .= $details['semester'];
					${'courseMark'.$semester} .= $details['course_code'];
					${'courseMark'.$semester} .= $details['course_name'];
					${'courseMark'.$semester} .= $details['grade'];
					${'courseMark'.$semester} .= $details['monthYearOfPassing'];
				}
			}
		}

		//for ($i=1; $i<=15; $i++) pr(${'courseMark'.$i});
		
		/*pr($stuFinalMark);
		pr($stuFinalStatus);
		pr($examMonthYear);
		pr($publishing_date);*/
		//pr($courseSemester);
		//pr($semesterIdArray);
		/*pr($creditsGained);
		echo "hi";
		pr($courseMappingCreditPoint);
		pr($csm_cm_id);*/
		
		$diff_array = array_diff_key($csm_cm_id, $stuFinalStatus);
		//pr($diff_array);
		
		$mandatory_array = array_diff_key($csm_cm_id, $courseMappingMandatory);
		//pr($mandatory_array);
		
		$first_attempt = array_diff_assoc($courseSemester,array_intersect_assoc($courseSemester, $semesterIdArray));
		//pr($first_attempt);
		
		//pr($programArray);
		
		$month_year_passing_diff = ($courseSemester === $semesterIdArray);
		//pr($month_year_passing_diff);
		//echo "Total credit gained : ".$totalCreditsGained;
		
		if (empty($diff_array) && $totalCreditsGained >= $programArray[$programId] && empty($mandatory_array)) {
				//Get Last Month Year of Exam 
			$lastMonthYearOfExamEnglish = "";$lastMonthYearOfExamTamil = "";
			if($semesterIdArray){
				rsort($semesterIdArray);
				$lastMonthYearOfExam = $semesterIdArray[0];
				$getExamMonths = $this->Html->getMonthYears($lastMonthYearOfExam);
				$lastMonthYearOfExamEnglish = $getExamMonths[0];
				$lastMonthYearOfExamTamil = $getExamMonths[1];			
			}
			
			asort($semesterIdArray);
			//pr($semesterIdArray);
			$semIdArray = array_unique($semesterIdArray);
			//pr($semIdArray);
		
			$seqCnt = "";
			foreach($semIdArray as $key=>$val){
				$htmlData = "";$seqCnt++;
				$htmlData .= ${'courseMark'.$val};
				//echo $htmlData."</br></br>";
				if($seqCnt == count($semIdArray)){
					${'BaseInfo_'.$result['Student']['registration_number']} = "";
					${'BaseInfo_'.$result['Student']['registration_number']} .=' $'.strtoupper($result['Student']['name']).' $'.$result['Student']['registration_number'].' $'.date( "d-M-Y", strtotime(h($result['Student']['birth_date']))).' $'.$result['Student']['gender'].' $'.$result['Program']['Academic']['academic_name'].' $'.$result['Program']['program_name'].' $'.$result['Batch']['batch_from'].'-'.$result['Batch']['batch_to'];
					
					$htmlData = $htmlData.${'BaseInfo_'.$result['Student']['registration_number']};
					//echo strtoupper($result['Student']['registration_number'].'-'.$val)."</br>";
					//echo $htmlData;
				}
				$this->Html->generateBarCode(strtoupper($result['Student']['registration_number'].'-'.$val),$htmlData);
			}
		
			// DC STARTED 
			//echo "numerator : ".$numerator;
			//echo "total_credits :".$totalCreditsGained;
			
			$CGPA = sprintf('%0.2f',round(($numerator/$totalCreditsGained),2));
			//echo "CGPA : ".$CGPA;
			
			//echo $student_id;
			
		?>

		<?php echo $this->Html->css('provisional_degree_certificate');?>
		<?php echo $this->Html->css('migration');?>
		<table class="page" border="0">
			<tr>
	  			<td colspan="3" style="height:150px;"></td>
	  		</tr>
	  		<tr>
	  			<td colspan="3" style="vertical-align:top;">
	  				<table border="0" align="center" style="width:95%;">
		  				<tr>
					        <td colspan="2" class="pdc3" style="text-align:left;">Ref. : Sathyabama/ COE / &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;/20&nbsp;&nbsp;&nbsp;&nbsp;</td>
					        <td class="pdc3" style="margin-right:100px;">Date : </td>
	      				</tr>
	      				 <tr>
					        <td colspan="3" class="pdc4">CERTIFICATE</td>
					      </tr>
					      <tr>
					        <td colspan="3" class="pdc3" style="text-align:left;">To,</td>
					      </tr>
					      <tr>
					        <td colspan="3" height="100" class="pdc4"></td>
					      </tr>
					      <tr>
					        <td colspan="3" class="pdc3" style="text-align:left;"><strong>Ref. : Your Letter No. ________________________________ Dated _____________________</strong></td>
					      </tr>
					        <tr>
					          <td colspan="3" class="pdc3 lineheight" style="text-align:left;width:30px;">
					            <table border="0" width="100%">
					              <tr><td width="30%">Name of the Student</td><td>:</td><td><strong><?php echo strtoupper($result['Student']['name']);?></strong> </td></tr>
					              <tr><td>Father's Name</td><td>:</td><td><strong><?php echo strtoupper($result['Student']['father_name']);?></strong> </td></tr>
					              <tr><td>Register Number</td><td>:</td><td><strong><?php echo strtoupper($result['Student']['registration_number']);?><strong></td></tr>
					              <tr><td>Program</td><td>:</td><td><?php echo strtoupper($result['Academic']['academic_name']);?></td></tr>
					              <tr><td>Branch/Specialisation</td><td>:</td><td><?php echo $result['Program']['program_name'];?></td></tr>
					              <tr><td>Batch</td><td>:</td><td><?php
					              $academic="";
					              if ($result['Batch']['academic']=="JUN") $academic="[A]";
					              echo $result['Batch']['batch_from']."-".$result['Batch']['batch_to']." ".$academic;
					              ?></td></tr>
					              <tr height="40">
					                <td>Duration & Mode of Programme</td>
					                <td>:</td>
					                <td> <?php echo $result['Batch']['batch_to']-$result['Batch']['batch_from']; ?> Year(s) &nbsp;&nbsp;&nbsp;&nbsp; Regular Mode / Part Time Mode</td>
					              </tr>
					              <tr height="40">
					                <td>Year of Study in University</td>
					                <td>:</td>
					                <td>From <?php echo $result['Batch']['batch_from']; ?> To <?php echo $result['Batch']['batch_to']; ?></td>
					              </tr>
					              <tr height="40">
					                <td>Medium of Instruction</td>
					                <td>:</td>
					                <td>English</td>
					              </tr>
					              <tr height="40">
					                <td>Status of Completion of Degree</td>
					                <td>:</td>
					                <td><?php 
					                  //pr($result);
					                  if ($result['Student']['discontinued_status'] == 1) echo "Discontinued";
					                  else {
					                    echo "Completed";
					                  } 
					                  ?>
					                </td>
					              </tr>
					              <tr>
					                <td>If completed, Month Year of completing the degree</td>
					                <td>:</td>
					                <td><?php 
					                  if ($result['Student']['discontinued_status'] == 0) echo $lastMonthYearOfExamEnglish; 
					                  ?>
					                </td>
					              </tr>
					              <tr>
					                <td>If discontinued, Month Year of leaving</td>
					                <td>:</td>
					                <td><?php 
					                  //if ($result['Student']['discontinued_status'] == 0) echo $lastMonthYearOfExamEnglish; 
					                  //pr($result);
					                  ?>
					                </td>
					              </tr>
					              <tr height="40">
					                <td colspan="3"></td>
					              </tr>
					            </table>
					          </td>
					        </tr>
	  				</table>
	  			</td>
	  		</tr>
	  		
  		</table>			
  		
  	<?php
		} 
	}
}
?>


<?php
function getArrayValues($array) {
	$tmpArray = array();
	//pr($array);
	$tmpArray['examMonthYear'] = $array['MonthYear']['Month']['month_name']."-".$array['MonthYear']['year'];
	$tmpArray['publishing_date'] = $array['MonthYear']['publishing_date'];
	$tmpArray['courseSemester'] = $array['CourseMapping']['semester_id'];
	$tmpArray['creditsGained'] = $array['CourseMapping']['Course']['credit_point'];
	
	$tmpArray['gradePointArray'] = $array['grade_point'];
	$tmpArray['courseCreditArray'] = $array['CourseMapping']['Course']['credit_point'];	
	$tmpArray['semesterIdArray'] = $array['month_year_id'];
	$tmpArray['courseCodeArray'] = $array['CourseMapping']['Course']['course_code'];
	$tmpArray['courseNameArray'] = $array['CourseMapping']['Course']['course_name'];
	
	$tmpArray['creditsGained'] = $array['CourseMapping']['Course']['credit_point'];
	
	$tmpArray['semId'] = $array['CourseMapping']['semester_id'];
	$tmpArray['semester'] = "$".$array['CourseMapping']['semester_id']." ";
	$tmpArray['course_code'] = "$".$array['CourseMapping']['Course']['course_code']." ";
	$tmpArray['course_name'] = "$".$array['CourseMapping']['Course']['course_name']." ";
	$tmpArray['grade'] = "$".$array['grade']." ";
	$tmpArray['grade_point'] = $array['grade_point'];
	$tmpArray['monthYearOfPassing'] = "$".$array['MonthYear']['Month']['month_name']."-".$array['MonthYear']['year']." ";
	return $tmpArray;
}
?>