	<div style="height:60px;">&nbsp;</div>
	<?php echo $this->element('print_head_a4'); ?>
	<table class="attendanceHeadTblP" border="1" cellpadding="0" cellspacing="0">
		<tr><td colspan="4" align="center">ARREAR EXAM ATTENDANCE SHEET</td></tr>
		<tr>
			<td><b>Month&Year of Exam</b></td>
			<td><?php echo $this->Html->getMonthYearFromId($month_year_id)." ".$exam_type;?></td>			
			<td><b>Date</b></td>
			<td><?php echo date( "d-m-Y", strtotime(h($exam_date)))."  (".$exam_session.")"; ?></td>
		</tr>
	</table>
	
<table class="attendanceHeadTblP" cellpadding="0" cellspacing="0" border="1" style="margin-top:10px;">
	<thead>
	<tr style='height:30px;'>		
		<th><b>S.No.</b></th>
		<th><b>Reg No.&nbsp;&nbsp;</b></th>
		<th><b>Student Name</b></th>
		<th><b>Course</b></th>
		<th><b>Branch</b></th>
		<th><b>Answer Sheet No.</b></th>
		<th><b>Student Signature</b></th>
	</tr>
	</thead>
	<tbody>
	<?php 
	$sno =1; 
	foreach ($results as $course_code => $result) { 
		for ($k=0; $k<count($result); $k++) {
	?>
	<tr class="gradeX">
		<td style='height:27px;'><?php echo $sno; ?></td>
		<td><?php echo $result[$k]['registration_number'];?></td>
		<td><?php echo $result[$k]['name'];?></td>
		<td><?php echo $result[$k]['course'];?></td>
		<td><?php echo $result[$k]['branch'];?></td>
		<td></td>
		<td></td>
	</tr>
<?php $sno++;}} 
//if($sno% 26 == 0){
?>
		</tbody>	
	</table>
	<table class="attendanceHeadTblP" style='height:60px;'>
		<tr>
			<td style='text-align:left;'>NO. OF PRESENT</td>
			<td style='text-align:right;'>NO. OF ABSENTEES</td>
			<td style='text-align:right;padding-right:50px;'>TOTAL NO. OF STUDENTS</td>
		</tr>
		<tr>
			<td colspan="3" style='height:45px;text-align:right;'>Name and Signature of Invigilator</td>
		</tr>
		<tr><td colspan='3' style='height:60px;'></td></tr>
	</table>
<?php 
//} 
?>	

<?php 	
	echo $this->Html->script('common');
	echo $this->Html->script('common-front');	
	echo $this->Html->css('certificate');
?>