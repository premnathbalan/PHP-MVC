<div>
	<?php if($results){ if($printMode != 'PRINT'){?>	
	<div class='col-lg-12' style='float:left;width:100%;'>
	<?php echo $this->Html->link('<i class="ace-icon fa fa-print fa-lg"></i>'.'PRINT',array("controller"=>"Students",'action'=>'beforeRevaluationSearch',$examMonth,$batchId,$Academic,$programId,$withheldType,$withheldVal,'PRINT'),array('type'=>'submit','name'=>'PRINT','value'=>'PRINT','class'=>'btn','escape' => false,'style'=>'float:right;'));?>	
	<?php echo $this->Html->link('<i class="ace-icon fa fa-file-excel-o"></i>'.'Excel',array("controller"=>"Students",'action'=>'beforeRevaluationSearch',$examMonth,$batchId,$Academic,$programId,$withheldType,$withheldVal,'Dept Excel'),array('type'=>'submit','name'=>'Excel','value'=>'Excel','class'=>'btn','escape' => false,'style'=>'float:right;'));?>
	<div style='clear:both;'></div>
	</div>
	<?php } else{
		echo "<div style='padding-left:200px;'>";
		echo $this->element('print_head_a4'); 
		echo "<div style='clear:both;'></div>";
		echo "</div>";
	?>
	<table class="attendanceHeadTblP" border="1" cellpadding="0" cellspacing="0" style="font-family:'Open sans' !important;font-size:13px;">
		<tr><td colspan="4" align="center">EXAMINATION RESULTS - CONSOLIDATED MARK STATEMENT - Before Revaluation</td></tr>
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
	<?php }}?>
	
<div style="margin-top:5px;width:100%;font-family:'Open sans' !important;font-size:13px;">
	
	<?php 
	//pr($results);
	$a=1; if($results){ foreach($results as $student_id => $result){ 
		//echo $withheldType; 
		//pr($result);
		$courseMark = ""; $cntFail = "0"; $cntAttendance = "0";
		
		//$cntAttendance = count($result['ExamAttendance']);
		
		$stuInternalArray = array();
		$stuESArray = array();
					
		if(($withheldType == '-') ){
			for($p=0;$p<count($result);$p++){
			if ($result[$p]['course_type'] == 'Theory') $cntAttendance++;  				
				$courseMark .= "<div style='width:210px;float:left;padding-left:5px;'>";
				$courseMark .= "<div style='width:75px;float:left;'>".$result[$p]['course_code']."</div>";
				$courseMark .= "<div style='width:30px;float:left;'>";
					//if($IEV){
						$courseMark .= $result[$p]['cae'];
					/*}else{
						$courseMark .="0";
					}*/
				$courseMark .= "</div>";
				
				$courseMark .= "<div style='width:30px;float:left;'>";
					//if($ESV){
						$courseMark .= $result[$p]['ese'];
					/*}else{
						$courseMark .="0";
					}*/
				$courseMark .= "</div>";
				
				$courseMark .= "<div style='width:40px;float:left;'>";
					//if($result['StudentMark'][$p]['marks']){
						$courseMark .=$result[$p]['total'];
					/*}else{
						$courseMark .="A";
					}*/
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
		//} 
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
	<div style='width:90px;float:left;height:30px;'><b><?php echo $result[$p-1]['registration_number'];?></b></div>
	<div style='width:400px;float:left;height:30px;padding-left:10px;'><b><?php echo $result[$p-1]['name'];?></b></div>
	<div style='width:30px;float:left;height:30px;'><b><?php /*if(empty($withHeldFlag)){*/ echo ($cntFail)."-".($cntAttendance-count($result['ExamAttendance']));/*} else { echo "&nbsp;";$withHeldFlag =0;} */?></b></div>
	<div style='width:800px;float:left;'><?php echo $courseMark;?></div>
	<div style='clear:both;height:10px;'></div>
	</div>
	<?php $a++;}}}else{ echo "<div style='font: 22px Arial, Helvetica, sans-serif; color:#FC0419;text-align:center;'>Data unavailable for the selected filter</div>";}?>

	<?php if($printMode != 'PRINT'){?>		
	<span class='breadcrumb1'>
	<span class='navbar-brand'><small>EXAMINATION <i class='ace-icon fa fa-angle-double-right'></i></small></span>
	<?php echo $this->Html->link("<span class='navbar-brand'><small>Dummy Numbers <i class='ace-icon fa fa-angle-double-right'></i></small></span>", array("controller"=>"DummyNumbers",'action' => 'index'),array('data-placement'=>'left','escape' => false)); ?>
	</span>
	<?php echo $this->Html->css('certificate');}?>

</div>
</div>