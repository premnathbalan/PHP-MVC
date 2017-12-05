	<?php if($results){ if($printMode != 'PRINT'){?>	
	<div class='col-lg-12' style='float:left;width:100%;'>
	<?php echo $this->Html->link('<i class="ace-icon fa fa-file-excel-o"></i>'.'Excel',array("controller"=>"Students",'action'=>'totalList',$batchId,$Academic,$programId,'-','-','Dept Excel'),array('type'=>'submit','name'=>'Excel','value'=>'Excel','class'=>'btn','escape' => false,'style'=>'float:right;'));?>
	<div style='clear:both;'></div>
	</div>
	<?php }?>
	<?php
	//echo $totalCourses;
	?>
	<table cellpadding='0' cellspacing='0' border='1' class='display' id='example' style='margin-top:10px;'>
		<thead>
			<tr>
				<th style='padding:0px;'>Reg.&nbsp;No.</th>
				<th style='padding:0px;'>Student&nbsp;Name&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>	
				<th style='padding:0px;'>1&nbsp;&nbsp;</th>
				<th style='padding:0px;'>2&nbsp;&nbsp;</th>
				<th style='padding:0px;'>3&nbsp;&nbsp;</th>
				<th style='padding:0px;'>4&nbsp;&nbsp;</th>
				<th style='padding:0px;'>5&nbsp;&nbsp;</th>
				<th style='padding:0px;'>6&nbsp;&nbsp;</th>
				<th style='padding:0px;'>7&nbsp;&nbsp;</th>
				<th style='padding:0px;'>8&nbsp;&nbsp;</th>
				<th style='padding:0px;'>9&nbsp;&nbsp;</th>
				<th style='padding:0px;'>10&nbsp;&nbsp;</th>
				<th style='padding:0px;'>Grand</br>Total</th>
				<th style='padding:0px;'>Total</br>Scored</th>
				<th style='padding:0px;text-align:center;'>%</th>
				<th style='padding:0px;'>Pgm<br/>CR</th>
				<th style='padding:0px;'>CR <br/>Regd</th>
				<th style='padding:0px;'>CR <br/>Ear</th>
				<th style='padding:0px;'>GP <br/>Ear</th>
				<th style='padding:0px;'>No of Arrears</th>
				<th style='padding:0px;'>CGPA</th>
				<th style='padding:0px;'>Status</th>
				<th style='padding:0px;'>Class</br>Secured</th>
			</tr>
		</thead>
		<tbody>
		<?php
		foreach ($results as $key => $result) {
		
			if (isset($result['StudentAuthorizedBreak'][0]['student_id'])) $abs=1;
			else $abs=0;
		
//			echo "ABS : ".$abs;
		
			if (isset($result['StudentWithdrawal'][0]['student_id'])) $withdrawal=1;
			else $withdrawal=0;
		
//			echo "Withdrawal : ".$withdrawal;
		
			$stuFinalMark = array();
			$stuMarkStatus = array();
			$stuMarkSemester = array();
			$CourseCP =  "";
			
			echo "<tr class='gradeX'>"; 
			
			echo "<td>".$result['Student']['registration_number']."</td>";
			echo "<td>".$result['Student']['name']."</td>";			
			
			//$TotCreditPoints ="";
			
			$earnCreditPoints = "";$semesterPassCnt = "0";$noOfCourses = 0;$noOfArrears = "";
			$stuSemesterMark1 = "";$stuSemesterMark2 = "";$stuSemesterMark3 = "";$stuSemesterMark4 = "";$stuSemesterMark5 = "";
			$stuSemesterMark6 = "";$stuSemesterMark7 = "";$stuSemesterMark8 = "";$stuSemesterMark9 = "";$stuSemesterMark10 = "";
			
			$stuSemesterMark6_1 = "";$stuSemesterMark7_1 = "";$stuSemesterMark8_1 = "";$stuSemesterMark9_1 = "";$stuSemesterMark10_1 = "";
			$stuSemesterMark1_1 = "";$stuSemesterMark2_1 = "";$stuSemesterMark3_1 = "";$stuSemesterMark4_1 = "";$stuSemesterMark5_1 = "";
			
			$semester1CourseCredit = "0";$semester2CourseCredit = "0";$semester3CourseCredit = "0";$semester4CourseCredit = "0";$semester5CourseCredit = "0";
			$semester6CourseCredit = "0";$semester7CourseCredit = "0";$semester8CourseCredit = "0";$semester9CourseCredit = "0";$semester10CourseCredit = "0";

			$semester1Total = "0";$semester2Total = "0";$semester3Total = "0";$semester4Total = "0";$semester5Total = "0";
			$semester6Total = "0";$semester7Total = "0";$semester8Total = "0";$semester9Total = "0";$semester10Total = "0";
			
			$TotCreditPointsArray = array();
			
			$csmNewMonthYearId = array();
				
			$total = 0;
			$studentTotal = 0;
			$crseCount = 0;
			$passCount = 0;
			$totalCoursePassMark = 0;	
			$totalMarkArray = array();
			$monthYearOfPassing = array();
			$actualMonthYear = array();
			$first_attempt = array();
			$classSecured = "";
			
			$tmpCsmArray = $result['CourseStudentMapping'];
			$csm_cm_id = array();
			foreach ($tmpCsmArray as $key => $csmDetails) {
				$csm_cm_id[$csmDetails['course_mapping_id']] = $csmDetails['new_semester_id'];
			}
			//echo "Total courses :".count($csm_cm_id)." ".$result['Student']['id'];
			echo "CSM Array";
			pr($csm_cm_id);
			
			$csmNewMonthYearId = array_filter($csm_cm_id); 
		//pr($csmArray);
			for($p=0;$p<count($result['StudentMark']);$p++){				
				
				$IEV = ""; $ESV = "";
				$courseMId = $result['StudentMark'][$p]['course_mapping_id'];
			if ($csmArray[$result['Student']['id']][$courseMId]==0) {
				if($result['StudentMark'][$p]['revaluation_status'] == 0){
					$stuFinalMark[$courseMId] = $result['StudentMark'][$p]['marks'];
					$stuMarkStatus[$courseMId] = $result['StudentMark'][$p]['status'];
					$stuMarkSemester[$courseMId] = $result['StudentMark'][$p]['CourseMapping']['semester_id'];
				}else{
					$stuFinalMark[$courseMId] = $result['StudentMark'][$p]['final_marks'];
					$stuMarkStatus[$courseMId] = $result['StudentMark'][$p]['final_status'];
					$stuMarkSemester[$courseMId] = $result['StudentMark'][$p]['CourseMapping']['semester_id'];
				}	
				//echo "</br>".$courseMId." ".$result['StudentMark'][$p]['month_year_id'];
				$monthYearOfPassing[$courseMId] = $result['StudentMark'][$p]['month_year_id'];
				$actualMonthYear[$courseMId] = $result['StudentMark'][$p]['CourseMapping']['month_year_id'];
				
				//$CourseCP = $this->Html->getGP($courseMId,$stuFinalMark[$courseMId],'1');
				$CourseCP = $result['StudentMark'][$p]['CourseMapping']['Course']['credit_point'];
				//$total += $result['StudentMark'][$p]['CourseMapping']['Course']['course_max_marks'];
				$totalMarkArray[$courseMId] = $result['StudentMark'][$p]['CourseMapping']['Course']['course_max_marks'];
				
				//echo $crseMark;
				
				if($stuMarkStatus[$courseMId] == 'Pass') {
				$passCount = $passCount + 1;
				$totalCoursePassMark += $stuFinalMark[$courseMId];
					$semesterPassCnt = $semesterPassCnt +1;
					$earnCreditPoints = $earnCreditPoints + $CourseCP;
					$studentTotal = $studentTotal + $stuFinalMark[$courseMId];
				}
				
				//echo $studentTotal."**";
				if(isset($stuFinalMark[$courseMId])){									
					//$RANGE_OF_MARKS_FOR_GRADES = $this->Html->getGP($courseMId,$stuFinalMark[$courseMId],'2');
					$RANGE_OF_MARKS_FOR_GRADES = $result['StudentMark'][$p]['grade_point'];
					$TotCreditPointsArray[$courseMId] = $CourseCP;
					$noOfCourses = $noOfCourses +1;
					if($stuMarkSemester[$courseMId] == 1){
						if($stuMarkStatus[$courseMId] == 'Pass'){
							$semester1CourseCredit = $semester1CourseCredit +$CourseCP;
							$stuSemesterMark1 = $stuSemesterMark1+$RANGE_OF_MARKS_FOR_GRADES;
							$semester1Total = $semester1Total + $stuFinalMark[$courseMId];
						}						
						$stuSemesterMark1_1 = $stuSemesterMark1_1+($result['StudentMark'][$p]['CourseMapping']['Course']['credit_point']*$RANGE_OF_MARKS_FOR_GRADES);		
					}
					if($stuMarkSemester[$courseMId] == 2){ 
						if($stuMarkStatus[$courseMId] == 'Pass'){ 
							$semester2CourseCredit = $semester2CourseCredit +$CourseCP;
							$stuSemesterMark2 = $stuSemesterMark2+$RANGE_OF_MARKS_FOR_GRADES;
							$semester2Total = $semester2Total + $stuFinalMark[$courseMId];
						}			
						$stuSemesterMark2_1 = $stuSemesterMark2_1+($result['StudentMark'][$p]['CourseMapping']['Course']['credit_point']*$RANGE_OF_MARKS_FOR_GRADES);
					}
					if($stuMarkSemester[$courseMId] == 3){
						if($stuMarkStatus[$courseMId] == 'Pass'){
							$semester3CourseCredit = $semester3CourseCredit +$CourseCP;
							$stuSemesterMark3 = $stuSemesterMark3+$RANGE_OF_MARKS_FOR_GRADES;
							$semester3Total = $semester3Total + $stuFinalMark[$courseMId];
						}						
						$stuSemesterMark3_1 = $stuSemesterMark3_1+($result['StudentMark'][$p]['CourseMapping']['Course']['credit_point']*$RANGE_OF_MARKS_FOR_GRADES);		
					}
					if($stuMarkSemester[$courseMId] == 4){
						if($stuMarkStatus[$courseMId] == 'Pass'){
							$semester4CourseCredit = $semester4CourseCredit +$CourseCP;
							$stuSemesterMark4 = $stuSemesterMark4+$RANGE_OF_MARKS_FOR_GRADES;
							$semester4Total = $semester4Total + $stuFinalMark[$courseMId];
						}						
						$stuSemesterMark4_1 = $stuSemesterMark4_1+($result['StudentMark'][$p]['CourseMapping']['Course']['credit_point']*$RANGE_OF_MARKS_FOR_GRADES);		
					}
					if($stuMarkSemester[$courseMId] == 5){
						if($stuMarkStatus[$courseMId] == 'Pass'){
							$semester5CourseCredit = $semester5CourseCredit +$CourseCP;
							$stuSemesterMark5 = $stuSemesterMark5+$RANGE_OF_MARKS_FOR_GRADES;
							$semester5Total = $semester5Total + $stuFinalMark[$courseMId];		
						}						
						$stuSemesterMark5_1 = $stuSemesterMark5_1+($result['StudentMark'][$p]['CourseMapping']['Course']['credit_point']*$RANGE_OF_MARKS_FOR_GRADES);
					}
					if($stuMarkSemester[$courseMId] == 6){
						if($stuMarkStatus[$courseMId] == 'Pass'){
							$semester6CourseCredit = $semester6CourseCredit +$CourseCP;
							$stuSemesterMark6 = $stuSemesterMark6+$RANGE_OF_MARKS_FOR_GRADES;
							$semester6Total = $semester6Total + $stuFinalMark[$courseMId];
						}						
						$stuSemesterMark6_1 = $stuSemesterMark6_1+($result['StudentMark'][$p]['CourseMapping']['Course']['credit_point']*$RANGE_OF_MARKS_FOR_GRADES);		
					}
					if($stuMarkSemester[$courseMId] == 7){
						if($stuMarkStatus[$courseMId] == 'Pass'){
							$semester7CourseCredit = $semester7CourseCredit +$CourseCP;
							$stuSemesterMark7 = $stuSemesterMark7+$RANGE_OF_MARKS_FOR_GRADES;
							$semester7Total = $semester7Total + $stuFinalMark[$courseMId];
						}						
						$stuSemesterMark7_1 = $stuSemesterMark7_1+($result['StudentMark'][$p]['CourseMapping']['Course']['credit_point']*$RANGE_OF_MARKS_FOR_GRADES);		
					}
					if($stuMarkSemester[$courseMId] == 8){
						if($stuMarkStatus[$courseMId] == 'Pass'){
							$semester8CourseCredit = $semester8CourseCredit +$CourseCP;
							$stuSemesterMark8 = $stuSemesterMark8+$RANGE_OF_MARKS_FOR_GRADES;
							$semester8Total = $semester8Total + $stuFinalMark[$courseMId];
						}						
						$stuSemesterMark8_1 = $stuSemesterMark8_1+($result['StudentMark'][$p]['CourseMapping']['Course']['credit_point']*$RANGE_OF_MARKS_FOR_GRADES);		
					}
					if($stuMarkSemester[$courseMId] == 9){
						if($stuMarkStatus[$courseMId] == 'Pass'){
							$semester9CourseCredit = $semester9CourseCredit +$CourseCP;
							$stuSemesterMark9 = $stuSemesterMark9+$RANGE_OF_MARKS_FOR_GRADES;
							$semester9Total = $semester9Total + $stuFinalMark[$courseMId];
						}						
						$stuSemesterMark9_1 = $stuSemesterMark9_1+($result['StudentMark'][$p]['CourseMapping']['Course']['credit_point']*$RANGE_OF_MARKS_FOR_GRADES);		
					}
					if($stuMarkSemester[$courseMId] == 10){
						if($stuMarkStatus[$courseMId] == 'Pass'){
							$semester10CourseCredit = $semester10CourseCredit +$CourseCP;
							$stuSemesterMark10 = $stuSemesterMark10+$RANGE_OF_MARKS_FOR_GRADES;
							$semester10Total = $semester10Total + $stuFinalMark[$courseMId];	
						}						
						$stuSemesterMark10_1 = $stuSemesterMark10_1+($result['StudentMark'][$p]['CourseMapping']['Course']['credit_point']*$RANGE_OF_MARKS_FOR_GRADES);	
					}
				}
				$available_semesters = max($stuMarkSemester);	
			}
			} // end FOR at line 99
			
			//echo " Pass count : ".$passCount;
			
			//$diff_array = array_diff_key($csm_cm_id, $stuMarkStatus);
			//pr($diff_array);
		
			//if (count($csm_cm_id) == $passCount) { echo "TRUE"; }
			ksort($monthYearOfPassing);
			ksort($actualMonthYear);
			echo "Month and Year of Passing";
			pr($monthYearOfPassing);
			//echo "CM MonthYear";
			//pr($actualMonthYear);
			$first_attempt = array_diff_assoc($actualMonthYear,array_intersect_assoc($actualMonthYear, $monthYearOfPassing));
			echo "First attempt array";
			pr($first_attempt);
		
			echo "new month year id";
			$csmNewMonthYearId = array_filter($csm_cm_id); 
			pr($csmNewMonthYearId);
			echo "intersection of csmNewMonthYearId and monthYearOfPassing";
			$withdrawalFirstAttempt = array_intersect_assoc($csmNewMonthYearId, $monthYearOfPassing);
			pr($withdrawalFirstAttempt);
			if (isset($withdrawalFirstAttempt) && count($withdrawalFirstAttempt) > 0) {
				$withdrawal = 0;
				if ($csmNewMonthYearId == $withdrawalFirstAttempt) {
					$first_attempt = [];
				}
			}
			//echo "</br>";
			//pr($stuMarkSemester);
			//echo count($totalMarkArray);
			
			//echo "</br>";
			//pr($stuMarkSemester);
			//echo count($totalMarkArray);
			$total = array_sum($totalMarkArray);
			
			//if (empty($first_attempt) && $result['Program']['credits'] >= $earnCreditPoints) {
				//echo "First attempt";			
			//}
			//pr($totalMarkArray);
			
			$noOfSemesters = 0;
			$totalGP = "";
			echo "<td align='center'>";
			if($stuSemesterMark1){ 
				$totalGP = sprintf('%0.2f',round($stuSemesterMark1_1)/$semester1CourseCredit);
				//echo sprintf('%0.2f',round($stuSemesterMark1_1)/$semester1CourseCredit);
				echo $semester1Total;
				//echo "</br>".$stuSemesterMark1_1." *** ".$semester1CourseCredit." ---";
			} else if($stuSemesterMark1<>"") {
				echo "0";
			}
			echo "</td>";
			
			echo "<td align='center'>";
			if($stuSemesterMark2){ //echo $totalGP." --- ".$stuSemesterMark2." --- ".$semester2CourseCredit." ---";
				$totalGP = sprintf('%0.2f',round($stuSemesterMark1_1 + $stuSemesterMark2_1) / ($semester1CourseCredit + $semester2CourseCredit));
				//echo sprintf('%0.2f',round($stuSemesterMark2_1)/$semester2CourseCredit);
				echo $semester2Total;
			} else if($available_semesters == 2) {
				echo "0";
			}
			echo "</td>";
			
			echo "<td align='center'>";
			if($stuSemesterMark3){
				$totalGP = sprintf('%0.2f',round($stuSemesterMark1_1 + $stuSemesterMark2_1 + $stuSemesterMark3_1) / ($semester1CourseCredit + $semester2CourseCredit + $semester3CourseCredit));
				//echo sprintf('%0.2f',round($stuSemesterMark3_1)/$semester3CourseCredit);
				echo $semester3Total;
			} else if($available_semesters == 3) {
				echo "0";
			}
			echo "</td>";
			
			echo "<td align='center'>";
			if($stuSemesterMark4){
				$totalGP = sprintf('%0.2f',round($stuSemesterMark1_1 + $stuSemesterMark2_1 + $stuSemesterMark3_1 + $stuSemesterMark4_1) / ($semester1CourseCredit + $semester2CourseCredit + $semester3CourseCredit + $semester4CourseCredit));
				//echo sprintf('%0.2f',round($stuSemesterMark4_1)/$semester4CourseCredit);
				echo $semester4Total;
			} else if($available_semesters == 4) {
				echo "0";
			}
			echo "</td>";
						
			echo "<td align='center'>";
			if($stuSemesterMark5){
				//$totalGP = $totalGP + sprintf('%0.2f',round((($stuSemesterMark5)/($semester5CourseCredit)),2));
				$totalGP = sprintf('%0.2f',round($stuSemesterMark1_1 + $stuSemesterMark2_1 + $stuSemesterMark3_1 + $stuSemesterMark4_1 + $stuSemesterMark5_1) / ($semester1CourseCredit + $semester2CourseCredit + $semester3CourseCredit + $semester4CourseCredit + $semester5CourseCredit));
				//echo sprintf('%0.2f',round($stuSemesterMark5_1)/$semester5CourseCredit);
				echo $semester5Total;
			} else if($available_semesters == 5) {
				echo "0";
			}
			echo "</td>";
			
			echo "<td align='center'>";
			if($stuSemesterMark6){
				$totalGP = sprintf('%0.2f',round($stuSemesterMark1_1 + $stuSemesterMark2_1 + $stuSemesterMark3_1 + $stuSemesterMark4_1 + $stuSemesterMark5_1 + $stuSemesterMark6_1) / ($semester1CourseCredit + $semester2CourseCredit + $semester3CourseCredit + $semester4CourseCredit + $semester5CourseCredit + $semester6CourseCredit));
				//echo sprintf('%0.2f',round($stuSemesterMark6_1)/$semester6CourseCredit);
				echo $semester6Total;
			} else if($available_semesters == 6) {
				echo "0";
			}
			echo "</td>";
			
			echo "<td align='center'>";
			if($stuSemesterMark7){
				$totalGP = sprintf('%0.2f',round($stuSemesterMark1_1 + $stuSemesterMark2_1 + $stuSemesterMark3_1 + $stuSemesterMark4_1 + $stuSemesterMark5_1 + $stuSemesterMark6_1 + $stuSemesterMark7_1) / ($semester1CourseCredit + $semester2CourseCredit + $semester3CourseCredit + $semester4CourseCredit + $semester5CourseCredit + $semester6CourseCredit + $semester7CourseCredit));
				//$totalGP = $totalGP + sprintf('%0.2f',round((($stuSemesterMark7)/($semester7CourseCredit)),2));
				//echo sprintf('%0.2f',round($stuSemesterMark7_1)/$semester7CourseCredit);
				echo $semester7Total;
			} else if($available_semesters == 7) {
				echo "0";
			}
			echo "</td>";
			
			echo "<td align='center'>";
			if($stuSemesterMark8){
				$noOfSemesters++;
				//$totalGP = $totalGP + sprintf('%0.2f',round((($stuSemesterMark8)/($semester8CourseCredit)),2));
				$totalGP = sprintf('%0.2f',round($stuSemesterMark1_1 + $stuSemesterMark2_1 + $stuSemesterMark3_1 + $stuSemesterMark4_1 + $stuSemesterMark5_1 + $stuSemesterMark6_1 + $stuSemesterMark7_1 + $stuSemesterMark8_1) / ($semester1CourseCredit + $semester2CourseCredit + $semester3CourseCredit + $semester4CourseCredit + $semester5CourseCredit + $semester6CourseCredit + $semester7CourseCredit + $semester8CourseCredit));
				//echo sprintf('%0.2f',round($stuSemesterMark8_1)/$semester8CourseCredit);
				echo $semester8Total;
			} else if($available_semesters == 8) {
				echo "0";
			}
			echo "</td>";
			
			echo "<td align='center'>";
			if($stuSemesterMark9){
				//$totalGP = $totalGP + sprintf('%0.2f',round((($stuSemesterMark9)/($semester9CourseCredit)),2));
				$totalGP = sprintf('%0.2f',round($stuSemesterMark1_1 + $stuSemesterMark2_1 + $stuSemesterMark3_1 + $stuSemesterMark4_1 + $stuSemesterMark5_1 + $stuSemesterMark6_1 + $stuSemesterMark7_1 + $stuSemesterMark8_1 + $stuSemesterMark9_1) / ($semester1CourseCredit + $semester2CourseCredit + $semester3CourseCredit + $semester4CourseCredit + $semester5CourseCredit + $semester6CourseCredit + $semester7CourseCredit + $semester8CourseCredit + $semester9CourseCredit));
				//echo sprintf('%0.2f',round($stuSemesterMark9_1)/$semester9CourseCredit);
				echo $semester9Total;
			} else if($available_semesters == 9) {
				echo "0";
			}
			echo "</td>";
			
			echo "<td align='center'>";
			if($stuSemesterMark10){
				//$totalGP = $totalGP + sprintf('%0.2f',round((($stuSemesterMark10)/($semester10CourseCredit)),2));
				$totalGP = sprintf('%0.2f',round($stuSemesterMark1_1 + $stuSemesterMark2_1 + $stuSemesterMark3_1 + $stuSemesterMark4_1 + $stuSemesterMark5_1 + $stuSemesterMark6_1 + $stuSemesterMark7_1 + $stuSemesterMark8_1 + $stuSemesterMark9_1 + $stuSemesterMark10_1) / ($semester1CourseCredit + $semester2CourseCredit + $semester3CourseCredit + $semester4CourseCredit + $semester5CourseCredit + $semester6CourseCredit + $semester7CourseCredit + $semester8CourseCredit + $semester9CourseCredit + $semester10CourseCredit));
				//echo sprintf('%0.2f',round($stuSemesterMark10_1)/$semester10CourseCredit);
				echo $semester10Total;
			} else if($available_semesters == 10) {
				echo "0";
			}
			
			echo "<td align='center'>".$total."</td>";
			echo "<td align='center'>".$totalCoursePassMark."</td>"; //sprintf('%0.2f',round($totalCoursePassMark)/$total*100)
			echo "<td align='center'>".sprintf('%0.2f',round($totalCoursePassMark)/$total*100)."</td>";
			echo "<td align='center'>".$result['Program']['credits']."</td>";
			
			echo "<td align='center'>".array_sum($TotCreditPointsArray)."</td>";
			echo "<td align='center'>".$earnCreditPoints."</td>";
			echo "<td align='center'>";
			echo $stuSemesterMark1+$stuSemesterMark2+$stuSemesterMark3+$stuSemesterMark4+$stuSemesterMark5+$stuSemesterMark6+$stuSemesterMark7+$stuSemesterMark8+$stuSemesterMark9+$stuSemesterMark10;
			echo "</td>";
			echo "<td align='center'>";
				//if(($noOfCourses-$semesterPassCnt)){
				//	echo $noOfCourses." ".$semesterPassCnt." ";
				//	echo $noOfCourses-$semesterPassCnt;
				//}
				//echo count($csm_cm_id)." ".$semesterPassCnt;
				$arrears = count($csm_cm_id) - $semesterPassCnt;
				if ($arrears) echo $arrears;
			echo "</td>";
			echo "<td align='center'>"; echo $totalGP;
				/*if(($stuSemesterMark1+$stuSemesterMark2+$stuSemesterMark3+$stuSemesterMark4+$stuSemesterMark5+$stuSemesterMark6+$stuSemesterMark7+$stuSemesterMark8+$stuSemesterMark9+$stuSemesterMark10)){
					echo $totalGP = sprintf('%0.2f',round((($stuSemesterMark1+$stuSemesterMark2+$stuSemesterMark3+$stuSemesterMark4+$stuSemesterMark5+$stuSemesterMark6+$stuSemesterMark7+$stuSemesterMark8+$stuSemesterMark9+$stuSemesterMark10)/($semester1CourseCredit+$semester2CourseCredit+$semester3CourseCredit+$semester4CourseCredit+$semester5CourseCredit+$semester6CourseCredit+$semester7CourseCredit+$semester8CourseCredit+$semester9CourseCredit+$semester10CourseCredit)),2));
				}*/
			echo "</td>";
			echo "<td align='center'>";
			//echo $earnCreditPoints." ".$result['Program']['credits']."</br>";
				$courseIsNull = $noOfCourses-$semesterPassCnt;
				if($earnCreditPoints >= $result['Program']['credits']){
					//echo $noOfCourses." ".$semesterPassCnt;
					echo "Pass";
				}/*elseif(($totalGP >= $result['Program']['credits']) || empty($noOfCourses-$semesterPassCnt)){
					echo "";
				}else{
					echo "Fail";
				}*/
			echo "</td>";
			echo "<td align='center'>";
				$degree_classification = $this->Html->generateModeClass($totalGP, $abs, $withdrawal, $first_attempt);
				$classSecured = strtoupper($degree_classification['E']); 
				if ($earnCreditPoints >= $result['Program']['credits']) {
					echo $classSecured; 
				} else {
					echo "";
				}
			echo "</td>";
			echo "</tr>";
			//echo $passCount;
		}
		?>		
	</tbody>
	<tfoot>
		<tr>
			<th><input type="text" name="Reg.&nbsp;No." value="Reg.&nbsp;No." class="search_init" /></th>
			<th><input type="text" name="Student&nbsp;Name&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;" value="Student&nbsp;Name&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;" class="search_init" /></th>	
			<th><input type="text" name="1" value="1" class="search_init" /></th>
			<th><input type="text" name="2" value="2" class="search_init" /></th>
			<th><input type="text" name="3" value="3" class="search_init" /></th>
			<th><input type="text" name="4" value="4" class="search_init" /></th>
			<th><input type="text" name="5" value="5" class="search_init" /></th>
			<th><input type="text" name="6" value="6" class="search_init" /></th>
			<th><input type="text" name="7" value="7" class="search_init" /></th>
			<th><input type="text" name="8" value="8" class="search_init" /></th>
			<th><input type="text" name="9" value="9" class="search_init" /></th>
			<th><input type="text" name="10" value="10" class="search_init" /></th>
			<th><input type="text" name="Program <br/>CR" value="Program CR" class="search_init" /></th>
			<th><input type="text" name="CR <br/>Rec" value="CR Rec" class="search_init" /></th>
			<th><input type="text" name="CR <br/>Ear" value="CR Ear" class="search_init" /></th>
			<th><input type="text" name="GP <br/>Ear" value="CR Rec" class="search_init" /></th>
			<th><input type="text" name="No of Arrears" value="No of Arrears" class="search_init" /></th>
			<th><input type="text" name="CGPA" value="CGPA" class="search_init" /></th>
			<th><input type="text" name="Status" value="Status" class="search_init" /></th>
			<th><input type="text" name="Class </br> Secured" value="Status" class="search_init" /></th>
		</tr>
	</tfoot>
	<?php echo $this->Html->script('common');?>
</table>

<span class='breadcrumb1'>
<span class='navbar-brand'><small>REPORTS <i class='ace-icon fa fa-angle-double-right'></i></small></span>
<?php echo $this->Html->link("<span class='navbar-brand'><small>Total Report <i class='ace-icon fa fa-angle-double-right'></i></small></span>", array("controller"=>"Students",'action' => 'cgpa'),array('data-placement'=>'left','escape' => false)); ?>
</span>
<?php } else{?>RECORD NOT FOUND<?php }?>