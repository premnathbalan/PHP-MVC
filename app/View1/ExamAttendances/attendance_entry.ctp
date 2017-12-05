	<?php echo $this->element('print_head_a4');?>
	<table class="attendanceHeadTblP" border="1" cellpadding="0" cellspacing="0">
		<tr><td colspan="4" align="center">EXAM ATTENDANCE SHEET</td></tr>
		<tr>
			<td><b>Month&Year of Exam</b></td>
			<td><?php echo $this->Html->getMonthYearFromId($month_year_id);?></td>			
			<td><b>Course</b></td>
			<td><?php if(isset($results[0]['CourseMapping']['Course']['course_name'])){echo $results[0]['CourseMapping']['Course']['course_name'];}?></td>
		</tr>		
		<tr>
			<td><b>Branch</b></td>
			<td><?php if(isset($results[0]['CourseMapping']['Program']['program_name'])){echo $results[0]['CourseMapping']['Program']['program_name'];}?></td>			
			<td><b>Course Code<b></td>
			<td><?php if(isset($results[0]['CourseMapping']['Course']['course_code'])){echo $results[0]['CourseMapping']['Course']['course_code'];}?></td>
		</tr>
		<tr>
			<td><b>Batch</b></td>
			<td><?php if(isset($results[0]['CourseMapping']['Batch']['batch_from'])){echo $results[0]['CourseMapping']['Batch']['batch_from']." - ".$results[0]['CourseMapping']['Batch']['batch_to'];}?></td>			
			<td><b>Date & Session</b></td>
			<td><?php echo date( "d-m-Y", strtotime(h($exam_date)))."  (".$exam_session.")"; ?></td>			
		</tr>
	</table>
	
<table class="attendanceHeadTblP" cellpadding="0" cellspacing="0" border="1" style="margin-top:10px;">
	<thead>
	<tr style='height:30px;'>		
		<th><b>S.No.</b></th>
		<th><b>Reg No.&nbsp;&nbsp;</b></th>
		<th><b>Student Name</b></th>
		<th><b>Answer Sheet No.</b></th>
		<th><b>Student Signature</b></th>
	</tr>
	</thead>
	<tbody>
	<?php $sno =1; foreach ($results as $student): ?>
	<tr class="gradeX">
		<td style='height:27px;'><?php echo $sno; ?></td>
		<td>
			<?php echo $student['Student']['registration_number']; ?>
		</td>
		<td>
			<?php echo $student['Student']['name'].".".$student['Student']['user_initial']; ?>
		</td>
		<td></td>
		<td>
			<?php if($sno ==1){?>
				<input type="hidden" name="maxRow" value="<?php echo count($results);?>" class="search_init" />
				<input type="hidden" name="typeMode" value="<?php //echo $typeMode;?>" class="search_init" />
			<?php }?> 
			
			<input type="hidden" name="student_id<?php echo $sno;?>" value="<?php echo $student['Student']['id']; ?>">
			<input type="hidden" name="month_year_id<?php echo $sno;?>" value="<?php //echo $MonthYears;?>">
			<input type="hidden" name="type<?php echo $sno;?>" value="<?php //echo $type;?>">
			<input type="hidden" name="course_mapping_id<?php echo $sno;?>" value="<?php //echo $courseMappingId;?>">	
		</td>
	</tr>
<?php $sno++; if($sno% 26 == 0){?>
		</tbody>	
	</table>
	<table class="attendanceHeadTblP" style='height:60px;'>
		<tr>
			<td style='text-align:left;'>Attendance Report</td>
			<td style='text-align:right;'>Name and Signature of Invigilator</td>
		</tr>
		<tr><td colspan='2' style='height:45px;'></td></tr>
	</table>
	<?php echo $this->element('print_head_a4');?>
	<table class="attendanceHeadTblP" border="1" cellpadding="0" cellspacing="0">
		<tr><td colspan="4" align="center">EXAM ATTENDANCE SHEET</td></tr>
		<tr>
			<td><b>Month&Year of Exam</b></td>
			<td><?php echo $this->Html->getMonthYearFromId($month_year_id);?></td>			
			<td><b>Course</b></td>
			<td><?php if(isset($results[0]['CourseMapping']['Course']['course_name'])){echo $results[0]['CourseMapping']['Course']['course_name'];}?></td>
		</tr>		
		<tr>
			<td><b>Branch</b></td>
			<td><?php if(isset($results[0]['CourseMapping']['Program']['program_name'])){echo $results[0]['CourseMapping']['Program']['program_name'];}?></td>			
			<td><b>Course Code<b></td>
			<td><?php if(isset($results[0]['CourseMapping']['Course']['course_code'])){echo $results[0]['CourseMapping']['Course']['course_code'];}?></td>
		</tr>
		<tr>
			<td><b>Batch</b></td>
			<td><?php if(isset($results[0]['CourseMapping']['Batch']['batch_from'])){echo $results[0]['CourseMapping']['Batch']['batch_from']." - ".$results[0]['CourseMapping']['Batch']['batch_to'];}?></td>			
			<td><b>Date & Session</b></td>
			<td><?php echo date( "d-m-Y", strtotime(h($exam_date)))."  (".$exam_session.")"; ?></td>				
		</tr>
	</table>
	<table class="attendanceHeadTblP" cellpadding="0" cellspacing="0" border="1" style="margin-top:10px;">
	<thead>
	<tr style='height:30px;'>		
		<th><b>S.No.</b></th>
		<th><b>Reg No.&nbsp;&nbsp;</b></th>
		<th><b>Student Name</b></th>
		<th><b>Answer Sheet No.</b></th>
		<th><b>Student Signature</b></th>
	</tr>
	</thead>
	<tbody>
 <?php } endforeach; ?>
	</tbody>	
	</table>
	<table class="attendanceHeadTblP" style='height:60px;'>
		<tr>
			<td style='text-align:left;'>Attendance Report</td>
			<td style='text-align:right;'>Name and Signature of Invigilator</td>
		</tr>
		<tr><td colspan='2' style='height:45px;'></td></tr>
	</table>
</div>

<?php 	
	echo $this->Html->script('common');
	echo $this->Html->script('common-front');	
	echo $this->Html->css('certificate');
?>