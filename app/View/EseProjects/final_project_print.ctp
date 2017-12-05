<?php echo $this->element('print_head_a4');
$project = $result['project'];
//pr($month_year_id);
//echo $this->Html->getMonthYearFromId($month_year_id);

?>
	<table class="attendanceHeadTblP" border="1" cellpadding="0" cellspacing="0">
		<tr><td colspan="4" align="center"><b>FINAL PROJECT - OUT OF 100 MARKS</b></td></tr>
		<tr>
			<td style="height:30px;"><b>Batch</b></td>
			<td><?php echo $this->Html->getBatch($batch_id);?></td>			
			<td><b>Program</b></td>
			<td><?php echo $this->Html->getAcademicFromProgId($program_id);?></td>
		</tr>		
		<tr>
			<td style="height:30px;"><b>Month&Year of Exam</b></td>
			<td><?php echo $this->Html->getMonthYearFromId($month_year_id);?></td>			
			<td><b>Specialisation</b></td>
			<td><?php echo $this->Html->getProgram($program_id);?></td>
		</tr>
	</table>
	
<table class="attendanceHeadTblP" cellpadding="0" cellspacing="0" border="1" style="margin-top:10px;">
<thead>
	<?php if(isset($courseMappingArray)){ 
	//pr($courseMappingArray); 
	?>
	<tr>
		<th style="height:30px;">S.No.</th>
		<th>Reg.&nbsp;No.</th>
		<th>Student&nbsp;Name</th>
		<?php foreach ($courseMappingArray as $cmId => $courseCode) {?>
		<th><?php echo $courseCode['course_code']." CAE - (".$result['project'][$cmId]['courseDetails']['max_cae_mark']." Marks)"; ?></th>
		<th><?php echo $courseCode['course_code']." ESE - (".$result['project'][$cmId]['courseDetails']['max_ese_mark']." Marks)"; ?></th>
		<th><?php echo $courseCode['course_code']." Total - (".$result['project'][$cmId]['courseDetails']['course_max_marks']." Marks)"; ?></th>
		<th><?php echo $courseCode['course_code']." Result"; ?></th>
		<?php }	?>
	</tr>
	<?php }?>
</thead>
<tbody>
	<?php
	$j=1;
	foreach ($studentArray as $student_id => $stuArray) {
	?>
	<tr class='gradeX'>
	<td><?php echo $j;?></td>
	<td><?php echo $stuArray['reg_num'];?></td>
	<td><?php echo $stuArray['name'];?></td>
	<?php foreach ($courseMappingArray as $cmId => $courseCode) {
		$tmpArray = $project[$cmId];
		//pr($tmpArray);
		if ($tmpArray['totalStatus'][$student_id]) {
			$resultStatus = "Pass";
		}
		else {
			$resultStatus = "Fail";
		}
		?>
		<td align='center'><?php echo $tmpArray['CAE'][$student_id]; ?></td>
		<td align='center'><?php echo $tmpArray['ESE'][$student_id]; ?></td>
		<td align='center'><?php echo $tmpArray['total'][$student_id]; ?></td>
		<td align='center'><?php echo $resultStatus; ?></td>
		<?php }	?>
	</tr>				
	  <?php $j++;  if($j% 17 == 0){?>
	  </tbody>
</table>
<table><tr><td style='height:50px;'>&nbsp;</td></table>
<?php echo $this->element('print_head_a4');?>
<table class="attendanceHeadTblP" border="1" cellpadding="0" cellspacing="0">
	<tr><td colspan="4" align="center"><b>FINAL CAE - OUT OF 50 MARKS</b></td></tr>
	<tr>
		<td style="height:30px;"><b>Batch</b></td>
		<td><?php echo $this->Html->getBatch($batch_id);?></td>			
		<td><b>Program</b></td>
		<td><?php echo $this->Html->getAcademicFromProgId($program_id);?></td>
	</tr>		
	<tr>
		<td style="height:30px;"><b>Month&Year of Exam</b></td>
		<td><?php echo $this->Html->getMonthYearFromId($month_year_id);?></td>			
		<td><b>Specialisation</b></td>
		<td><?php echo $this->Html->getProgram($program_id);?></td>
	</tr>
</table>
<table class="attendanceHeadTblP" cellpadding="0" cellspacing="0" border="1" style="margin-top:10px;">
<thead>	
	<?php if(isset($courseMappingArray)){ ?>
	<tr>
		<th style="height:30px;">S.No.</th>
		<th>Reg.&nbsp;No.</th>
		<th>Student&nbsp;Name</th>
		<?php foreach ($courseMappingArray as $cmId => $courseCode) {?>
		<th><?php echo $courseCode." CAE"; ?></th>
		<th><?php echo $courseCode." ESE"; ?></th>
		<th><?php echo $courseCode." Total"; ?></th>
		<th><?php echo $courseCode." Result"; ?></th>
		<?php }	?>
	</tr>
	<?php }?>
</thead>
<tbody><?php }
	  }
	  ?>
</tbody>
</table>
<?php 	
	echo $this->Html->script('common');
	echo $this->Html->script('common-front');	
	echo $this->Html->css('certificate');
?>