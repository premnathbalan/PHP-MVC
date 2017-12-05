<div>
	<?php 
	if($results){ 
		if($printMode != 'PRINT'){?>	
			<div class='col-lg-12' style='float:left;width:100%;'>
			<?php echo $this->Html->link('<i class="ace-icon fa fa-print fa-lg"></i>'.'PRINT',array("controller"=>"Students",'action'=>'beforeRevaluationSearch',$examMonth,$batchId,$Academic,$programId,$withheldType,$withheldVal,'PRINT'),array('type'=>'submit','name'=>'PRINT','value'=>'PRINT','class'=>'btn','escape' => false,'style'=>'float:right;'));?>
			<?php //echo $this->Html->link('<i class="ace-icon fa fa-file-excel-o"></i>'.'Excel',array("controller"=>"Students",'action'=>'beforeRevaluationSearch',$examMonth,$batchId,$Academic,$programId,$withheldType,$withheldVal,'Dept Excel'),array('type'=>'submit','name'=>'Excel','value'=>'Excel','class'=>'btn','escape' => false,'style'=>'float:right;'));?>
			<div style='clear:both;'></div>
			</div>
			<?php 
		} else{
			echo "<div style='padding-left:200px;'>";
			echo $this->element('print_head_a4'); 
			echo "<div style='clear:both;'></div>";
			echo "</div>";
			if ($revalMode == "before") $mode = "Before Revaluation";
			else if ($revalMode == "after") $mode = "After Revaluation";
			?>
			<table class="attendanceHeadTblP" border="1" cellpadding="0" cellspacing="0" style="font-family:'Open sans' !important;font-size:13px;">
				<tr><td colspan="4" align="center">EXAMINATION RESULTS - CONSOLIDATED MARK STATEMENT - <?php echo $mode; ?></td></tr>
				<tr>
					<td><b>Month&Year of Exam</b></td>
					<td><?php echo $this->Html->getMonthYearFromMonthYearId($examMonth);?></td>			
					<td><b>Branch</b></td>
					<td><?php echo $this->Html->getAcademic($Academic);?></td>			
				</tr>		
				<tr>
					<td><b>Batch</b></td>
					<td><?php echo $this->Html->getBatch($batchId);?></td>			
					<td><b>Specialisation</b></td>
					<td><?php echo $this->Html->getProgram($programId);?></td>			
				</tr>
			</table>
			<?php 
		}
	}
	?>
	
<div style="margin-top:5px;width:100%;font-family:'Open sans' !important;font-size:13px;">
	
	<?php 
	//pr($results);
	
	/*if ($withheldType!="-" && $withheldVal!="-") {
			pr($withheldStudents);
		}*/
		//die;
	$a=1; 
	if($results){ 
		foreach($results as $reg_num => $result){ 
		//echo $withheldType; 
		
 //if ($student_id==561) pr($result);
		$courseMark = ""; $cntFail = 0; $cntAttendance = 0; $totAttendance = 0;
		$totAttendance = count($result);
		$stuInternalArray = array();
		$stuESArray = array();
		
		//echo count($result)." ".$result[0]['student_id']; 
		$registration_number = $result[0]['registration_number'];
		$name = $result[0]['name'];
		//pr($withheldStudents);
		if(($withheldType == 'All') ){
			if (in_array($result[0]['student_id'], array_keys($withheldStudents))) {
				$courseMark .= "<div style='width:300px;float:left;'>";
				$courseMark .= $withheldStudents[$result[0]['student_id']];
				$courseMark .= "</div>";
			}
			else { 
			for($p=0;$p<count($result);$p++){
			$course_type_id = $result[$p]['course_type_id'];
			if ($result[$p]['status'] != "Pass") {
				SWITCH ($course_type_id) {
					CASE 1:
						if ($result[$p]['ese'] == 'a' || $result[$p]['ese'] == 'A' || $result[$p]['ese'] == 'AAA' || $result[$p]['ese'] == 'aaa' || $result[$p]['attendance'] == 0) $cntAttendance++;
						else if ($result[$p]['attendance'] == 1 && $result[$p]['status'] == "Fail") $cntFail++;
						break;
					CASE 2:
					CASE 6:
						if ($result[$p]['ese'] == 'a' || $result[$p]['ese'] == 'A' || $result[$p]['ese'] == 'AAA' || $result[$p]['ese'] == 'aaa' || $result[$p]['attendance'] == 1) $cntAttendance++;
						else if ($result[$p]['attendance'] == 0 || $result[$p]['status'] == "Fail") $cntFail++;
						break;
					CASE 3:
						if ($result[$p]['ese'] == 'a' || $result[$p]['ese'] == 'A' || $result[$p]['ese'] == 'AAA' || $result[$p]['ese'] == 'aaa' || $result[$p]['attendance'] == 1) $cntAttendance++;
						else if ($result[$p]['attendance'] == 0 || $result[$p]['status'] == "Fail") $cntFail++;
						break;
					CASE 4:
						break;
					CASE 5:
						if ($result[$p]['ese'] == 'a' || $result[$p]['ese'] == 'A' || $result[$p]['ese'] == 'AAA' || $result[$p]['ese'] == 'aaa' || $result[$p]['attendance'] == 0) $cntAttendance++;
						else if ($result[$p]['attendance'] == 1 && $result[$p]['status'] == "Fail") $cntFail++;
						break;
				}
			}
			//if (isset($result[$p]['attendance']) && $result[$p]['attendance'] == 0 && $result[$p]['ese'] <= 0) $cntAttendance++;
			//if ($result[$p]['course_type_id']==2 && ($result[$p]['ese'] == 'A' || $result[$p]['ese'] == 'A')) $cntAttendance++;
			//if ($result[$p]['attendance'] == 1 && $result[$p]['status'] == "Fail") $cntFail++;
				$courseMark .= "<div style='width:210px;float:left;padding-left:5px;'>";
				$courseMark .= "<div style='width:75px;float:left;'>".$result[$p]['course_code']."</div>";
				$courseMark .= "<div style='width:30px;float:left;'>";
					if($result[$p]['cae']=="AAA") $result[$p]['cae']="A";
						$courseMark .= $result[$p]['cae'];
				$courseMark .= "</div>";
				
				$courseMark .= "<div style='width:30px;float:left;'>";
					if($result[$p]['ese']=="AAA") $result[$p]['ese']="A";
						$courseMark .= $result[$p]['ese'];
				$courseMark .= "</div>";
				
				$courseMark .= "<div style='width:40px;float:left;'>";
						$courseMark .=$result[$p]['total'];
				$courseMark .= "</div>";
				$courseMark .= "<div style='width:30px;float:left;'>";
					if($result[$p]['status'] == 'Fail'){
						$courseMark .= "RA";
					}else {
						$courseMark .= $result[$p]['status'];
					}
				$courseMark .= "</div>";
				$courseMark .= "<div style='clear:both;float:left;'></div>";
				$courseMark .= "</div>";
				
			}
			}
			//echo $courseMark;
		}
		else if ($withheldType==1 && ($withheldVal!="" || $withheldVal!="-")) { 
			if (in_array($result[0]['student_id'], array_keys($withheldStudents))) {
				$courseMark .= "<div style='width:300px;float:left;'>";
				$courseMark .= $withheldStudents[$result[0]['student_id']];
				$courseMark .= "</div>";
			}
			/*else {
			for($p=0;$p<count($result);$p++){
			$course_type_id = $result[$p]['course_type_id'];
			if ($result[$p]['status'] != "Pass") {
				SWITCH ($course_type_id) {
					CASE 1:
						if ($result[$p]['ese'] == 'a' || $result[$p]['ese'] == 'A' || $result[$p]['ese'] == 'AAA' || $result[$p]['attendance'] == 0) $cntAttendance++;
						else if ($result[$p]['attendance'] == 1 && $result[$p]['status'] == "Fail") $cntFail++;
						break;
					CASE 2:
					CASE 6:
						if ($result[$p]['ese'] == 'a' || $result[$p]['ese'] == 'A' || $result[$p]['ese'] == 'AAA' || $result[$p]['attendance'] == 1) $cntAttendance++;
						else if ($result[$p]['attendance'] == 0 || $result[$p]['status'] == "Fail") $cntFail++;
						break;
					CASE 3:
						if ($result[$p]['ese'] == 'a' || $result[$p]['ese'] == 'A' || $result[$p]['ese'] == 'AAA' || $result[$p]['attendance'] == 1) $cntAttendance++;
						else if ($result[$p]['attendance'] == 0 || $result[$p]['status'] == "Fail") $cntFail++;
						break;
					CASE 4:
						break;
					CASE 5:
						if ($result[$p]['ese'] == 'a' || $result[$p]['ese'] == 'A' || $result[$p]['ese'] == 'AAA' || $result[$p]['attendance'] == 0) $cntAttendance++;
						else if ($result[$p]['attendance'] == 1 && $result[$p]['status'] == "Fail") $cntFail++;
						break;
				}
			}
			//if (isset($result[$p]['attendance']) && $result[$p]['attendance'] == 0 && $result[$p]['ese'] <= 0) $cntAttendance++;
			//if ($result[$p]['course_type_id']==2 && ($result[$p]['ese'] == 'A' || $result[$p]['ese'] == 'A')) $cntAttendance++;
			//if ($result[$p]['attendance'] == 1 && $result[$p]['status'] == "Fail") $cntFail++;
				$courseMark .= "<div style='width:210px;float:left;padding-left:5px;'>";
				$courseMark .= "<div style='width:75px;float:left;'>".$result[$p]['course_code']."</div>";
				$courseMark .= "<div style='width:30px;float:left;'>";
				if($result[$p]['cae']=="AAA") $result[$p]['cae']="A";
						$courseMark .= $result[$p]['cae'];
				$courseMark .= "</div>";
				
				$courseMark .= "<div style='width:30px;float:left;'>";
				if($result[$p]['ese']=="AAA") $result[$p]['ese']="A";
						$courseMark .= $result[$p]['ese'];
				$courseMark .= "</div>";
				
				$courseMark .= "<div style='width:40px;float:left;'>";
						$courseMark .=$result[$p]['total'];
				$courseMark .= "</div>";
				$courseMark .= "<div style='width:30px;float:left;'>";
					if($result[$p]['status'] == 'Fail'){
						$courseMark .= "RA";
					}else {
						$courseMark .= $result[$p]['status'];
					}
				$courseMark .= "</div>";
				$courseMark .= "<div style='clear:both;float:left;'></div>";
				$courseMark .= "</div>";
				
			}
			//echo $courseMark;
			}*/
		}
		else if ($withheldType == 2) {
			if (in_array($result[0]['student_id'], array_keys($withheldStudents))) {
				$courseMark .= "<div style='width:300px;float:left;'>";
				$courseMark .= $withheldStudents[$result[0]['student_id']];
				$courseMark .= "</div>";
			} else {
			 continue;
			}
		}
		//echo $courseMark;
		?> 
	<?php if(($a == 1) && ($printMode == 'PRINT')){ ?>
		<div style="border:1px solid #EBEBEB;">
		<div style='width:80px;float:left;height:30px;'><b>SL.No.</b></div>
		<div style='width:80px;float:left;height:30px;'><b>Reg.No.</b></div>
		<div style='width:150px;float:left;height:30px;'><b>Name</b></div>
		<div style='width:40px;float:left;height:30px;'><b>RA-AAA</b></div>
		<div style='width:400px;float:right;padding-right:270px;'>
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
	<?php } ?>	
	<div style="border:1px solid #EBEBEB;<?php if(($a%2)==0){?>background-color:#e8e8e8;<?php }?>">
	<div style='width:90px;float:left;height:30px;text-indent:4px;'><b><?php echo $a;?></b></div>
	<div style='width:90px;float:left;height:30px;'><b><?php echo $registration_number;?></b></div>
	<div style='width:400px;float:left;height:30px;padding-left:10px;'><b><?php echo $name;?></b></div>
	<div style='width:30px;float:left;height:30px;'><b>
		<?php 
			//if(empty($withHeldFlag)){
				echo ($cntFail)."-".($cntAttendance);
			/*} else { 
				echo "&nbsp;";$withHeldFlag =0;
			}*/
		?>
	</b></div>
	
	<div style='width:800px;float:left;'><?php echo $courseMark;?></div>
	<div style='clear:both;height:10px;'></div>
	</div>
	<?php $a++;}}else{ echo "<div style='font: 22px Arial, Helvetica, sans-serif; color:#FC0419;text-align:center;'>Data unavailable for the selected filter</div>";}?>

	<?php if($printMode != 'PRINT'){?>		
	<span class='breadcrumb1'>
	<span class='navbar-brand'><small>EXAMINATION <i class='ace-icon fa fa-angle-double-right'></i></small></span>
	<?php echo $this->Html->link("<span class='navbar-brand'><small>Dummy Numbers <i class='ace-icon fa fa-angle-double-right'></i></small></span>", array("controller"=>"DummyNumbers",'action' => 'index'),array('data-placement'=>'left','escape' => false)); ?>
	</span>
	<?php echo $this->Html->css('certificate');}?>

</div>
</div>