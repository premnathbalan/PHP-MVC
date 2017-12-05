	<div style="height:60px;">&nbsp;</div>
	<?php echo $this->element('print_head_a4');?>
	<table class="attendanceHeadTblP" border="1" cellpadding="0" cellspacing="0">
		<tr><td colspan="4" align="center">ARREAR EXAM ATTENDANCE SHEET</td></tr>
		<tr>
			<td><b>Month&Year of Exam</b></td>
			<td><?php echo $this->Html->getMonthYearFromId($month_year_id)." ".$exam_type;?></td>			
			<td><b>Date</b></td>
			<td><?php echo date( "d-m-Y", strtotime(h($exam_date)))."  (".$exam_session.")"; ?></td>
		</tr>
		<tr>
			<td><b>Course Code</b></td>
			<td colspan='3'><?php echo $this->Html->getCourseCode($cm_id);?></td>			
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
	$tmpArray=array();
	$sno =1; 
	foreach ($results as $student): for($a=0;$a<count($student['StudentMark']);$a++){
		if(isset($student['StudentMark'][$a]['Student']['registration_number'])){
			if (!in_array($student['StudentMark'][$a]['Student']['id'], $tmpArray)) {
				$tmpArray[]=$student['StudentMark'][$a]['Student']['id'];
			?>
				<tr class="gradeX">
					<td style='height:27px;'><?php echo $sno; ?></td>
					<td><?php echo $student['StudentMark'][$a]['Student']['registration_number'];?></td>
					<td><?php echo $student['StudentMark'][$a]['Student']['name'];?></td>
					<td><?php echo $student['StudentMark'][$a]['Student']['Program']['Academic']['short_code'];?></td>
					<td><?php echo $student['StudentMark'][$a]['Student']['Program']['short_code'];?></td>
					<td></td>
					<td></td>
				</tr>
			<?php $sno++;
			}
		} 
		if($sno% 26 == 0) {
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
	<div style="height:60px;">&nbsp;</div>
	<?php echo $this->element('print_head_a4');?>
	<table class="attendanceHeadTblP" border="1" cellpadding="0" cellspacing="0">
		<tr><td colspan="4" align="center">ARREAR EXAM ATTENDANCE SHEET</td></tr>
		<tr>
			<td><b>Month&Year of Exam</b></td>
			<td><?php echo $this->Html->getMonthYearFromId($month_year_id);?></td>			
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
 <?php }}for($a=0;$a<count($student['CourseStudentMapping']);$a++){
 //if(isset($student['CourseStudentMapping'][$a]['Student']['registration_number'])){
 if(isset($student['CourseStudentMapping'][$a]['new_semester_id'])){
 ?>
	<tr class="gradeX">
		<td style='height:27px;'><?php echo $sno; ?></td>
		<td><?php echo $student['CourseStudentMapping'][$a]['Student']['registration_number'];?></td>
		<td><?php echo $student['CourseStudentMapping'][$a]['Student']['name'];?></td>
		<td><?php echo $student['CourseStudentMapping'][$a]['Student']['Program']['Academic']['short_code'];?></td>
		<td><?php echo $student['CourseStudentMapping'][$a]['Student']['Program']['short_code'];?></td>
		<td></td>
		<td></td>
	</tr>
<?php $sno++;} if($sno% 26 == 0){?>
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
	<div style="height:60px;">&nbsp;</div>
	<?php echo $this->element('print_head_a4');?>
	<table class="attendanceHeadTblP" border="1" cellpadding="0" cellspacing="0">
		<tr><td colspan="4" align="center">ARREAR EXAM ATTENDANCE SHEET</td></tr>
		<tr>
			<td><b>Month&Year of Exam <?php echo $cm_id; ?></b></td>
			<td><?php echo $this->Html->getMonthYearFromId($month_year_id);?></td>			
			<td><b>Date</b></td>
			<td><?php echo date( "d-m-Y", strtotime(h($exam_date)))."  (".$exam_session.")"; ?></td>
		</tr>
		<tr>
			<td><b>Course Code</b></td>
			<td colspan='3'><?php echo $this->Html->getCourseCode($cm_id);?></td>			
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
 <?php }} endforeach; ?>
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
</div>

<?php 	
	echo $this->Html->script('common');
	echo $this->Html->script('common-front');	
	echo $this->Html->css('certificate');
?>