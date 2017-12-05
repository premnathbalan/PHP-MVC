<div>
	<?php if($results){ if($printMode != 'PRINT'){ //pr($results); 
	?>	
	<div class='col-lg-12' style='float:left;width:100%;'>
	<?php echo $this->Html->link('<i class="ace-icon fa fa-print fa-lg"></i>'.'PRINT',array("controller"=>"Students",'action'=>'afterRevaluationSearch',$examMonth,$batchId,$Academic,$programId,$withheldType,$withheldVal,'PRINT'),array('type'=>'submit','name'=>'PRINT','value'=>'PRINT','class'=>'btn','escape' => false,'style'=>'float:right;'));?>	
	<?php echo $this->Html->link('<i class="ace-icon fa fa-file-excel-o"></i>'.'Excel',array("controller"=>"Students",'action'=>'afterRevaluationSearch',$examMonth,$batchId,$Academic,$programId,$withheldType,$withheldVal,'Dept Excel'),array('type'=>'submit','name'=>'Excel','value'=>'Excel','class'=>'btn','escape' => false,'style'=>'float:right;'));?>
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
			<td><b>Month&Year of Exam</b></td>
			<td><?php echo $this->Html->getMonthYearFromId($examMonth);?></td>			
			<td><b>Branch</b></td>
			<td><?php echo $this->Html->getAcademic($Academic);?></td>			
		</tr>		
		<tr>
			<td><b>Batch</b></td>
			<td><?php echo $this->Html->getBatch($batchId);?></td>			
			<td></td>
			<td></td>			
		</tr>
	</table>
	<?php }}?>
	
<div style="margin-top:5px;width:100%;font-family:'Open sans' !important;font-size:13px;">
	
	<?php $a=1; if($results){ foreach($results as $result){?>
		
		<?php 
		pr($result);
		
		$courseMark = ""; $cntFail = "0"; $cntAttendance = "0";
		$cntAttendance = count($result['ExamAttendance']);
		
		$stuInternalArray = array();
		$stuESArray = array();
		$stuFinalMark = array();
		$stuMarkStatus = array();
		
		//For Theory Revaluation
		for($p=0;$p<count($result['RevaluationExam']);$p++){			
			$stuESArray[$result['RevaluationExam'][$p]['course_mapping_id']] = $result['RevaluationExam'][$p]['revaluation_marks'];
		}
		
		//For Theory Internal
		for($p=0;$p<count($result['InternalExam']);$p++){			
			$stuInternalArray[$result['InternalExam'][$p]['course_mapping_id']] = $result['InternalExam'][$p]['marks'];			
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
		
		
		//For Practical	Internal			
		for($q=0;$q<count($result['InternalPractical']);$q++){							
			$stuInternalArray[$result['InternalPractical'][$q]['CaePractical']['course_mapping_id']] = $result['InternalPractical'][$q]['marks'];
		}
		//For Practical	External	
		for($q=0;$q<count($result['Practical']);$q++){	
			$practicalExternalMarks = $result['Practical'][$q]['marks'];
			if($practicalExternalMarks == '0'){$practicalExternalMarks = "&nbsp;0";}						
			$stuESArray[$result['Practical'][$q]['EsePractical']['course_mapping_id']] = $practicalExternalMarks;
			$practicalExternalMarks = "";												
		}
		
		//For Project Internal			
		for($q=0;$q<count($result['InternalProject']);$q++){							
			$stuInternalArray[$result['InternalProject'][$q]['course_mapping_id']] = $result['InternalProject'][$q]['marks'];
		}
		//For Project External	
		for($q=0;$q<count($result['ProjectViva']);$q++){	
			$projectExternalMarks = $result['ProjectViva'][$q]['marks'];
			if($projectExternalMarks == '0'){$projectExternalMarks = "&nbsp;0";}						
			$stuESArray[$result['ProjectViva'][$q]['EseProject']['course_mapping_id']] = $projectExternalMarks;
			$projectExternalMarks = "";												
		}
					
		if(($withheldType == 'All') ){
			for($p=0;$p<count($result['StudentMark']);$p++){
				$IEV = "A"; $ESV = "A";
				$courseMId = $result['StudentMark'][$p]['course_mapping_id'];
				if(isset($stuInternalArray[$courseMId])){
					$IEV = $stuInternalArray[$courseMId];
				}
				if(isset($stuESArray[$courseMId])){
					$ESV = $stuESArray[$courseMId];
				}	
				if($result['StudentMark'][$p]['revaluation_status'] == 0){
					$stuFinalMark[$courseMId] = $result['StudentMark'][$p]['marks'];
					$stuMarkStatus[$courseMId] = $result['StudentMark'][$p]['status'];
				}else{
					$stuFinalMark[$courseMId] = $result['StudentMark'][$p]['final_marks'];
					$stuMarkStatus[$courseMId] = $result['StudentMark'][$p]['final_status'];
				}			
				
				if($stuMarkStatus[$courseMId] == 'Fail'){
					if($ESV != '' && $ESV !='A' && $ESV !=0){
						$cntFail = $cntFail + 1;
					}
				}
				
				if($ESV == '' || $ESV =='A' || $ESV ==0){
					$cntAttendance = $cntAttendance + 1;
				}
				$courseMark .= "<div style='width:250px;float:left;padding-left:5px;'>";
				$courseMark .= "<div style='width:75px;float:left;'>".$result['StudentMark'][$p]['CourseMapping']['Course']['course_code']."</div>";
				$courseMark .= "<div style='width:45px;float:left;'>";
					if($IEV){
						$courseMark .= $IEV;
					}else{
						$courseMark .="0";
					}
				$courseMark .= "</div>";
				
				$courseMark .= "<div style='width:45px;float:left;'>";
					if($ESV){
						$courseMark .= $ESV;
					}else{
						$courseMark .="0";
					}
				$courseMark .= "</div>";
				
				$courseMark .= "<div style='width:40px;float:left;'>";
					if($stuFinalMark[$courseMId]){
						//$courseMark .= $stuFinalMark[$courseMId];
					}else{
						//$courseMark .="A";
					}
				$courseMark .= "</div>";
				$courseMark .= "<div style='width:30px;float:left;'>";
					if($stuMarkStatus[$courseMId] == 'Fail'){
						$courseMark .= "RA";
					}else {
						$courseMark .= $stuMarkStatus[$courseMId];
					}
				$courseMark .= "</div>";
				$courseMark .= "<div style='clear:both;float:left;'></div>";
				$courseMark .= "</div>";
				
			}
		}else if(empty($result['StudentWithheld'])){
			for($p=0;$p<count($result['StudentMark']);$p++){
				$IEV = "A"; $ESV = "A";
				$courseMId = $result['StudentMark'][$p]['course_mapping_id'];
				if(isset($stuInternalArray[$courseMId])){
					$IEV = $stuInternalArray[$courseMId];
				}
				if(isset($stuESArray[$courseMId])){
					$ESV = $stuESArray[$courseMId];
				}	
				if($result['StudentMark'][$p]['revaluation_status'] == 0){
					$stuFinalMark[$courseMId] = $result['StudentMark'][$p]['marks'];
					$stuMarkStatus[$courseMId] = $result['StudentMark'][$p]['status'];
				}else{
					$stuFinalMark[$courseMId] = $result['StudentMark'][$p]['final_marks'];
					$stuMarkStatus[$courseMId] = $result['StudentMark'][$p]['final_status'];
				}
				
				if($stuMarkStatus[$courseMId] == 'Fail'){
					if($ESV != '' && $ESV !='A' && $ESV !=0){
						$cntFail = $cntFail + 1;
					}
				}
				
				if($ESV == '' || $ESV =='A' || $ESV ==0){
					$cntAttendance = $cntAttendance + 1;
				}
				
				$courseMark .= "<div style='width:250px;float:left;padding-left:5px;'>";
				$courseMark .= "<div style='width:75px;float:left;'>".$result['StudentMark'][$p]['CourseMapping']['Course']['course_code']."</div>";
				$courseMark .= "<div style='width:45px;float:left;'>";
					if($IEV){
						$courseMark .= $IEV;
					}else{
						$courseMark .="0";
					}
				$courseMark .= "</div>";
				
				$courseMark .= "<div style='width:45px;float:left;'>";
					if($ESV){
						$courseMark .= $ESV;
					}else{
						$courseMark .="0";
					}
				$courseMark .= "</div>";
				
				$courseMark .= "<div style='width:40px;float:left;'>";
					if($stuFinalMark[$courseMId]){
						$courseMark .=$stuFinalMark[$courseMId];
					}else{
						$courseMark .="A";
					}
				$courseMark .= "</div>";
				$courseMark .= "<div style='width:30px;float:left;'>";
					if($stuMarkStatus[$courseMId] == 'Fail'){
						$courseMark .= "RA";
					}else {
						$courseMark .= $stuMarkStatus[$courseMId];
					}
				$courseMark .= "</div>";
				$courseMark .= "<div style='clear:both;float:left;'></div>";
				$courseMark .= "</div>";
				
			}
		}else{
			//With Held process
			$withHeldFlag = 0;
			$courseMark .= "<div style='width:300px;float:left;'>";
			for($i=0;$i<count($result['StudentWithheld']);$i++){
				$withHeldFlag = 1;
				$courseMark .= $result['StudentWithheld'][$i]['Withheld']['withheld_type'].", ";
			}
			$courseMark .= "</div>";
		}
		?> 
	<?php if(($a == 1) && ($printMode == 'PRINT')){?>
		<div style="border:1px solid #EBEBEB;">
		<div style='width:80px;float:left;height:30px;'><b>SL.No.</b></div>
		<div style='width:80px;float:left;height:30px;'><b>Reg.No.</b></div>
		<div style='width:150px;float:left;height:30px;'><b>Name</b></div>
		<div style='width:40px;float:left;height:30px;'><b>RA-AAA</b></div>
		<div style='width:400px;float:right;'>
			<div style='width:400px;float:left;border:1px solid #EBEBEB;'>
				<div style='width:120px;float:left;padding-left:10px;border-right:3px solid #EBEBEB;'><b>CourseCode</b></div>
				<div style='width:50px;float:left;border-right:3px solid #EBEBEB;'><b>CAE</b></div>
				<div style='width:50px;float:left;border-right:3px solid #EBEBEB;'><b>ESE</b></div>
				<div style='width:60px;float:left;border-right:3px solid #EBEBEB;'><b>TOT</b></div>
				<div style='width:50px;float:left;border-right:3px solid #EBEBEB;'><b>STAT</b></div>
			</div>	
		</div>
		<div style='clear:both;'></div>
		</div>
	<?php }?>	
	<div style="border:1px solid #EBEBEB;<?php if(($a%2)==0){?>background-color:#e8e8e8;<?php }?>">
	<div style='width:90px;float:left;height:30px;text-indent:4px;'><b><?php echo $a;?></b></div>
	<div style='width:90px;float:left;height:30px;'><b><?php echo $result['Student']['registration_number'];?></b></div>
	<div style='width:400px;float:left;height:30px;padding-left:10px;'><b><?php echo $result['Student']['name'];?></b></div>
	<div style='width:30px;float:left;height:30px;'><b><?php if(empty($withHeldFlag)){echo ($cntFail)."-".($cntAttendance-count($result['ExamAttendance']));} else { echo "&nbsp;";$withHeldFlag =0;}?></b></div>
	<div style='width:800px;float:left;'><?php echo $courseMark;?></div>
	<div style='clear:both;height:10px;'></div>
	</div>
	<?php $a++;unset($stuInternalArray);unset($stuESArray);unset($stuFinalMark);unset($stuMarkStatus);}}else{ echo "<div style='font: 22px Arial, Helvetica, sans-serif; color:#FC0419;text-align:center;'>Data unavailable for the selected filter</div>";}?>

	<?php if($printMode != 'PRINT'){?>		
	<span class='breadcrumb1'>
	<span class='navbar-brand'><small>RESULTS <i class='ace-icon fa fa-angle-double-right'></i></small></span>
	<?php echo $this->Html->link("<span class='navbar-brand'><small>AFTER REVALUATION <i class='ace-icon fa fa-angle-double-right'></i></small></span>", array("controller"=>"Students",'action' => 'afterRevaluation'),array('data-placement'=>'left','escape' => false)); ?>
	</span>
	<?php echo $this->Html->css('certificate');}?>

</div>
</div>