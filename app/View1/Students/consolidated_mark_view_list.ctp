<?php if($printMode != 'PDF1' && $printMode != 'PDF2'){ ?>
<div>
	<?php if($results){ if($printMode != 'PRINT'){?>
	<div class='col-lg-6' style='float:left;width:50%;'>
	<?php echo $this->Html->link('<i class="ace-icon fa fa-print fa-lg"></i>'.'PRINT - Student Row Wise (With History)',array("controller"=>"Students",'action'=>'consolidatedMarkViewList',$batchId,$Academic,$programId,$register_no,$month_year_id,'PRINT'),array('type'=>'submit','name'=>'PRINT','value'=>'PRINT','class'=>'btn','escape' => false,'style'=>'float:right;'));?>	
	&nbsp; &nbsp;<?php echo $this->Html->link('<i class="ace-icon fa fa-file-excel-o"></i>'.'Excel - Student Row Wise (With History)',array("controller"=>"Students",'action'=>'consolidatedMarkViewList',$batchId,$Academic,$programId,$register_no,$month_year_id,'Dept Excel'),array('type'=>'submit','name'=>'Excel','value'=>'Excel','class'=>'btn','escape' => false,'style'=>'float:right;'));?>
	<div style='clear:both;'></div>

	</div>	
	<div class='col-lg-6' style='float:left;width:50%;'>
	<?php echo $this->Html->link('<i class="ace-icon fa fa-file-pdf-o bigger-110"></i>'.'PDF - Student Wise(Semester Wise with History)',array("controller"=>"Students",'action'=>'consolidatedMarkViewList',$batchId,$Academic,$programId,$register_no,$month_year_id,'PDF1'),array('type'=>'submit','name'=>'PRINT','value'=>'PRINT','class'=>'btn','escape' => false,'style'=>'float:right;'));?>
	<?php echo $this->Html->link('<i class="ace-icon fa fa-file-pdf-o bigger-110"></i>'.'PDF - Student Wise(Over All)',array("controller"=>"Students",'action'=>'consolidatedMarkViewList',$batchId,$Academic,$programId,$register_no,$month_year_id,'PDF2'),array('type'=>'submit','name'=>'PRINT','value'=>'PRINT','class'=>'btn','escape' => false,'style'=>'float:right;'));?>
	<div style='clear:both;'></div>
	</div>
	<?php } else{
		echo "<div style='padding-left:200px;'>";
		echo $this->element('print_head_a4'); 
		echo "<div style='clear:both;'></div>";
		echo "</div>";
	?>
	<table class="attendanceHeadTblP" border="1" cellpadding="0" cellspacing="0" style="font-family:'Open sans' !important;font-size:13px;">
		<tr><td colspan="4" align="center">EXAMINATION RESULTS - CONSOLIDATED MARK STATEMENT - After Revaluation</td></tr>
		<tr>
			<td><b>Batch</b></td>
			<td><?php echo $this->Html->getBatch($batchId);?></td>			
			<td><b>Branch</b></td>
			<td><?php echo $this->Html->getAcademic($Academic);?></td>			
		</tr>	
	</table>
	<?php }}?>
	
<div style="margin-top:5px;width:100%;font-family:'Open sans' !important;font-size:13px;">
	
<?php $a=1;  if($results){ foreach($results as $result){
		//pr($result);
		$courseMark = ""; $cntFail = "0"; $cntAttendance = "0";
		$cntAttendance = count($result['ExamAttendance']);
		
		$stuInternalArray = array();
		$stuESArray = array();
		$stuFinalMark = array();
		$stuMarkStatus = array();
		$examMonthYear = array();
		
		//For Theory Revaluation		
		for($p=0;$p<count($result['RevaluationExam']);$p++){			
			$examMonthYear[$result['RevaluationExam'][$p]['month_year_id']][$result['RevaluationExam'][$p]['course_mapping_id']] = $result['RevaluationExam'][$p]['MonthYear']['Month']['month_name']."-".$result['RevaluationExam'][$p]['MonthYear']['year'];
			$stuESArray[$result['RevaluationExam'][$p]['month_year_id']][$result['RevaluationExam'][$p]['course_mapping_id']] = $result['RevaluationExam'][$p]['revaluation_marks'];			
		}		
		if($result['Student']['university_references_id'] == 1 && $result['Student']['parent_id'] != 0){
			for($p=0;$p<count($result['ParentGroup']['RevaluationExam']);$p++){			
				$examMonthYear[$result['ParentGroup']['RevaluationExam'][$p]['month_year_id']][$result['ParentGroup']['RevaluationExam'][$p]['course_mapping_id']] = $result['ParentGroup']['RevaluationExam'][$p]['MonthYear']['Month']['month_name']."-".$result['ParentGroup']['RevaluationExam'][$p]['MonthYear']['year'];
				$stuESArray[$examMonthYear[$result['ParentGroup']['RevaluationExam'][$p]['month_year_id']]][$result['ParentGroup']['RevaluationExam'][$p]['course_mapping_id']] = $result['ParentGroup']['RevaluationExam'][$p]['revaluation_marks'];
			}
		}
		
		//For Theory Internal
		for($p=0;$p<count($result['InternalExam']);$p++){			
			$stuInternalArray[$result['InternalExam'][$p]['month_year_id']][$result['InternalExam'][$p]['course_mapping_id']] = $result['InternalExam'][$p]['marks'];			
		}
		if($result['Student']['university_references_id'] == 1 && $result['Student']['parent_id'] != 0 && isset($result['ParentGroup']['InternalExam'][$p]['marks'])){
			$stuInternalArray[$result['ParentGroup']['InternalExam'][$p]['month_year_id']][$result['ParentGroup']['InternalExam'][$p]['course_mapping_id']] = $result['ParentGroup']['InternalExam'][$p]['marks'];
		}
		
		//For Theory External
		for($p=0;$p<count($result['EndSemesterExam']);$p++){			
			if($result['EndSemesterExam'][$p]['revaluation_status'] == 0){
				$stuESArray[$result['EndSemesterExam'][$p]['month_year_id']][$result['EndSemesterExam'][$p]['course_mapping_id']] = $result['EndSemesterExam'][$p]['marks'];
			}else{
				//echo $result['EndSemesterExam'][$p]['course_mapping_id']." ".$result['EndSemesterExam'][$p]['marks']." ";
				$revalArray = $result['RevaluationExam'];
				foreach ($revalArray as $key => $revalValue) {
					if ($revalValue['course_mapping_id'] == $result['EndSemesterExam'][$p]['course_mapping_id']) {
						//echo $revalValue['revaluation_marks']."</br>";
						break;
					}
				}
				/*if(isset($stuESArray[$result['EndSemesterExam'][$p]['course_mapping_id']]) && $stuESArray[$result['EndSemesterExam'][$p]['course_mapping_id']] < $result['EndSemesterExam'][$p]['marks']){
					$stuESArray[$result['EndSemesterExam'][$p]['month_year_id']][$result['EndSemesterExam'][$p]['course_mapping_id']] = $result['EndSemesterExam'][$p]['marks'];
				}*/
				if ($revalValue['revaluation_marks'] > $result['EndSemesterExam'][$p]['marks']) {
					$stuESArray[$result['EndSemesterExam'][$p]['month_year_id']][$result['EndSemesterExam'][$p]['course_mapping_id']]=$revalValue['revaluation_marks'];
				} else {
					$stuESArray[$result['EndSemesterExam'][$p]['month_year_id']][$result['EndSemesterExam'][$p]['course_mapping_id']]=$result['EndSemesterExam'][$p]['marks'];
				}
				//pr($result);
			}		
		}		
		if($result['Student']['university_references_id'] == 1 && $result['Student']['parent_id'] != 0){
			for($p=0;$p<count($result['ParentGroup']['EndSemesterExam']);$p++){			
				if($result['ParentGroup']['EndSemesterExam'][$p]['revaluation_status'] == 0){
					$stuESArray[$result['ParentGroup']['EndSemesterExam'][$p]['month_year_id']][$result['ParentGroup']['EndSemesterExam'][$p]['course_mapping_id']] = $result['ParentGroup']['EndSemesterExam'][$p]['marks'];
				}else{
					if($stuESArray[$result['ParentGroup']['EndSemesterExam'][$p]['course_mapping_id']] < $result['ParentGroup']['EndSemesterExam'][$p]['marks']){
						$stuESArray[$result['ParentGroup']['EndSemesterExam'][$p]['month_year_id']][$result['ParentGroup']['EndSemesterExam'][$p]['course_mapping_id']] = $result['ParentGroup']['EndSemesterExam'][$p]['marks'];
					}
				}		
			}
		}
		
		
		//For Practical	Internal
		for($q=0;$q<count($result['InternalPractical']);$q++){							
			$stuInternalArray[$result['InternalPractical'][$q]['month_year_id']][$result['InternalPractical'][$q]['CaePractical']['course_mapping_id']] = $result['InternalPractical'][$q]['marks'];
		}
		if($result['Student']['university_references_id'] == 1 && $result['Student']['parent_id'] != 0){
			for($q=0;$q<count($result['ParentGroup']['InternalPractical']);$q++){							
				$stuInternalArray[$result['ParentGroup']['InternalPractical'][$q]['month_year_id']][$result['ParentGroup']['InternalPractical'][$q]['CaePractical']['course_mapping_id']] = $result['ParentGroup']['InternalPractical'][$q]['marks'];
			}
		}	
		
		//For Practical	External	
		for($q=0;$q<count($result['Practical']);$q++){	
			$practicalExternalMarks = $result['Practical'][$q]['marks'];
			if($practicalExternalMarks == '0'){$practicalExternalMarks = "&nbsp;0";}						
			$stuESArray[$result['Practical'][$q]['month_year_id']][$result['Practical'][$q]['EsePractical']['course_mapping_id']] = $practicalExternalMarks;
			$practicalExternalMarks = "";												
		}
		
		if($result['Student']['university_references_id'] == 1 && $result['Student']['parent_id'] != 0 && isset($result['ParentGroup']['Practical'][$q]['marks'])){
			$practicalExternalMarks = $result['ParentGroup']['Practical'][$q]['marks'];
			if($practicalExternalMarks == '0'){$practicalExternalMarks = "&nbsp;0";}						
			$stuESArray[$result['ParentGroup']['Practical'][$q]['month_year_id']][$result['ParentGroup']['Practical'][$q]['EsePractical']['course_mapping_id']] = $practicalExternalMarks;
			$practicalExternalMarks = "";
		}	
		
		if($result['Student']['university_references_id'] == 1 && $result['Student']['parent_id'] != 0 && count($result['ParentGroup']['StudentMark'])>0){
			$result['StudentMark'] = $result['ParentGroup']['StudentMark'];
		}
		
		$monthYear = array();
		
		//For Project Internal
		for($p=0;$p<count($result['InternalProject']);$p++){			
			$stuInternalArray[$result['InternalProject'][$p]['month_year_id']][$result['InternalProject'][$p]['course_mapping_id']] = $result['InternalProject'][$p]['marks'];			
		}
		if($result['Student']['university_references_id'] == 1 && $result['Student']['parent_id'] != 0 && isset($result['ParentGroup']['InternalProject'][$p]['marks'])){
			$stuInternalArray[$result['ParentGroup']['InternalProject'][$p]['month_year_id']][$result['ParentGroup']['InternalProject'][$p]['course_mapping_id']] = $result['ParentGroup']['InternalProject'][$p]['marks'];
		}
		
		//For Project External
		for($q=0;$q<count($result['ProjectViva']);$q++){
			$projectExternalMarks = $result['ProjectViva'][$q]['marks'];
			if($projectExternalMarks == '0'){$projectExternalMarks = "&nbsp;0";}
			$stuESArray[$result['ProjectViva'][$q]['month_year_id']][$result['ProjectViva'][$q]['EseProject']['course_mapping_id']] = $projectExternalMarks;
			$practicalExternalMarks = "";
		}
		
		//For Professional Training Internal
		for($q=0;$q<count($result['ProfessionalTraining']);$q++){
			$stuInternalArray[$result['ProfessionalTraining'][$q]['month_year_id']][$result['ProfessionalTraining'][$q]['CaePt']['course_mapping_id']] = $result['ProfessionalTraining'][$q]['marks'];
			//For Professional Training External (No external for PT)
			$stuESArray[$result['ProfessionalTraining'][$q]['month_year_id']][$result['ProfessionalTraining'][$q]['CaePt']['course_mapping_id']] = "-";
		}
		if($result['Student']['university_references_id'] == 1 && $result['Student']['parent_id'] != 0){
			for($q=0;$q<count($result['ParentGroup']['ProfessionalTraining']);$q++){
				$stuInternalArray[$result['ParentGroup']['ProfessionalTraining'][$q]['month_year_id']][$result['ParentGroup']['ProfessionalTraining'][$q]['CaePt']['course_mapping_id']] = $result['ParentGroup']['ProfessionalTraining'][$q]['marks'];
				//For Professional Training External (No external for PT)
				$stuESArray[$result['ParentGroup']['ProfessionalTraining'][$q]['month_year_id']][$result['ParentGroup']['ProfessionalTraining'][$q]['CaePt']['course_mapping_id']] = "-";
			}
		}
				
		//pr($stuInternalArray);
		//pr($stuESArray);
		for($p=0;$p<count($result['StudentMark']);$p++)	{
			$examMonthYearId = $result['StudentMark'][$p]['month_year_id'];
			$monthYear[$examMonthYearId] = $examMonthYearId;
		}
		//echo max($monthYear);
		
		//pr($monthYear);
		
		for($p=0;$p<count($result['StudentMark']);$p++){
			
			//pr($result['StudentMark']);
			
			$IEV = ""; $ESV = ""; 
			$courseMId = $result['StudentMark'][$p]['course_mapping_id'];
			//echo "*** ".$courseMId;
			$examMonthYearId = $result['StudentMark'][$p]['month_year_id'];
			
			
			if($result['StudentMark'][$p]['revaluation_status'] == 0){
				$stuFinalMark[$examMonthYearId][$courseMId] = $result['StudentMark'][$p]['marks'];
				$stuMarkStatus[$examMonthYearId][$courseMId] = $result['StudentMark'][$p]['status'];
				$examMonthYear[$examMonthYearId][$courseMId] = $result['StudentMark'][$p]['MonthYear']['Month']['month_name']."-".$result['StudentMark'][$p]['MonthYear']['year'];
			}else{
				$stuFinalMark[$examMonthYearId][$courseMId] = $result['StudentMark'][$p]['final_marks'];
				$stuMarkStatus[$examMonthYearId][$courseMId] = $result['StudentMark'][$p]['final_status'];				
			}
			
			if($stuMarkStatus[$examMonthYearId][$courseMId] == 'Fail'){
				$cntFail = $cntFail + 1;
			}
			
			if(isset($stuInternalArray[$examMonthYearId][$courseMId])){
				$IEV = $stuInternalArray[$examMonthYearId][$courseMId];
			} 
			if(isset($stuESArray[$examMonthYearId][$courseMId])){
				$ESV = $stuESArray[$examMonthYearId][$courseMId];
			}	
			//echo " - ".$IEV." - ".$ESV." - ".$stuFinalMark[$examMonthYearId][$courseMId];
			if ($IEV == 'A' || $IEV == '' || is_null($IEV)) { 
				//echo " if ";
				$maxMonthYear = max($monthYear);
				for($mx=$maxMonthYear; $mx>0; $mx--) {
					//echo "</br>".$mx."</br>";
					if (isset($stuInternalArray[$mx][$courseMId]) && $stuInternalArray[$mx][$courseMId] >= 0) {
						$IEV = $stuInternalArray[$mx][$courseMId];
						break;
					}
				}
			}
			//echo "</br>";
			//$courseMId."-".
			$courseMark .= "<tr class='gradeX'>";
			$courseMark .= "<td>".$result['Student']['registration_number']."</td>";
			$courseMark .= "<td>".$result['Student']['name']."</td>";
			$courseMark .= "<td>".$result['StudentMark'][$p]['CourseMapping']['Course']['course_code']."</td>";
			$courseMark .= "<td>".$result['StudentMark'][$p]['CourseMapping']['Course']['course_name']."</td>";
			$courseMark .= "<td>".$result['StudentMark'][$p]['CourseMapping']['semester_id']."</td>";
			$courseMark .= "<td>".$examMonthYear[$examMonthYearId][$courseMId]."</td>";
			$courseMark .= "<td>";
				if($IEV){
					$courseMark .= $IEV;
				}else{
					$courseMark .="A";
				}
			$courseMark .= "</td>";
			
			$courseMark .= "<td>";
				if($ESV){
					$courseMark .= $ESV;
				}else{
					$courseMark .="A";
				}
			$courseMark .= "</td>";
			
			$courseMark .= "<td>";
				if($stuFinalMark[$examMonthYearId][$courseMId]){
					$courseMark .=$stuFinalMark[$examMonthYearId][$courseMId];
				}else{
					$courseMark .="A";
				}
			$courseMark .= "</td>";
			$courseMark .= "<td>";
				if($stuMarkStatus[$examMonthYearId][$courseMId] == 'Fail'){
					$courseMark .= "RA";
				}else {
					$courseMark .= $stuMarkStatus[$examMonthYearId][$courseMId];
				}
			$courseMark .= "</td>";
			$courseMark .= "</tr>";
		}
	//	pr($stuFinalMark);
	//	pr($stuMarkStatus);	
		?> 
	<?php if(($a == 1)){ //&& ($printMode == 'PRINT')?>
		<table cellpadding='0' cellspacing='0' border='1' class='display' id='example' style='margin-top:10px;'>
		<thead>
			<th style='width:60px;'>Reg.No.</th>
			<th style='width:180px;'>Name</th>
			<th style='width:90px;' align='cener'>Course Code</th>
			<th style='width:210px;'>Course Name</th>
			<th style='width:50px;'>Sem</th>
			<th style='width:50px;'>MonthYearOfExam</th>
			<th style='width:50px;' align='cener'>CAE</th>
			<th style='width:50px;' align='cener'>ESE</th>
			<th style='width:50px;' align='cener'>TOT</th>
			<th style='width:80px;' align='cener'>STAT</th>
		</thead>
	<?php }?>	
	
	<?php echo $courseMark;?>	
	
	<?php $a++;unset($stuInternalArray);unset($stuESArray);unset($stuFinalMark);unset($stuMarkStatus);}}else{ echo "<div style='font: 22px Arial, Helvetica, sans-serif; color:#FC0419;text-align:center;'>Data unavailable for the selected filter</div>";}
	if($printMode != 'PRINT'){
	?>
<tfoot>
		<tr>
			<th><input type="text" name="Reg.No." value="Reg.No." class="search_init" /></th>
			<th><input type="text" name="Name" value="Name" class="search_init" /></th>
			<th><input type="text" name="Course Code" value="Course Code" class="search_init" /></th>
			<th><input type="text" name="Course Name" value="Course Name" class="search_init" /></th>
			<th><input type="text" name="Sem" value="Sem" class="search_init" /></th>
			<th><input type="text" name="MonthYearOfExam" value="MonthYearOfExam" class="search_init" /></th>
			<th><input type="text" name="CAE" value="CAE" class="search_init" /></th>
			<th><input type="text" name="ESE" value="ESE" class="search_init" /></th>
			<th><input type="text" name="TOT" value="TOT" class="search_init" /></th>
			<th><input type="text" name="STAT" value="STAT" class="search_init" /></th>
		</tr>
		<?php echo $this->Html->script('common');?>
</tfoot>
<?php }?>			
</table>
	<?php if($printMode != 'PRINT'){?>		
	<span class='breadcrumb1'>
	<span class='navbar-brand'><small>REPORTS <i class='ace-icon fa fa-angle-double-right'></i></small></span>
	<?php echo $this->Html->link("<span class='navbar-brand'><small>Consolidated Mark View <i class='ace-icon fa fa-angle-double-right'></i></small></span>", array("controller"=>"Students",'action' => 'consolidatedMarkView'),array('data-placement'=>'left','escape' => false)); ?>
	</span>
	<?php echo $this->Html->css('certificate');}?>

</div>
</div>
<?php }?>