	<?php echo $this->element('print_head_a4');?>
	<table class="attendanceHeadTblP" border="1" cellpadding="0" cellspacing="0">
		<tr><td colspan="4" align="center">ATTENDANCE SHEET</td></tr>
		<tr>
			<td><b>Month&Year of Exam</b></td>
			<td><?php echo $txtMonthYears;?></td>			
			<td><b>Course</b></td>
			<td><?php echo $txtAcademic;?></td>
		</tr>		
		<tr>
			<td><b>Branch</b></td>
			<td><?php echo $txtProgram;?></td>			
			<td><b>Subject Code<b></td>
			<td><?php echo $shortCode;?></td>
		</tr>
		<tr>
			<td><b>Batch</b></td>
			<td><?php echo $txtBatch;?></td>			
			<td><b>Date</b></td>
			<td></td>			
		</tr>
	</table>
	
<table class="attendanceHeadTblP" cellpadding="0" cellspacing="0" border="1" style="margin-top:10px;">
	<thead>
	<tr style='height:30px;'>		
		<th><b>S.No.</b></th>
		<th><b>Reg No.&nbsp;&nbsp;</b></th>
		<th><b>Student Name</b></th>
		<th><b>Percentage</b></th>
	</tr>
	</thead>
	<tbody>
	<?php $sno =1; foreach ($Students as $student): ?>
	<tr class="gradeX">
		<td style='height:27px;'><?php echo $sno; ?></td>
		<td>
			<?php echo $student['Student']['registration_number']; ?>
		</td>
		<td>
			<?php echo $student['Student']['name'].".".$student['Student']['user_initial']; ?>
		</td>
		<td>
			<?php if($sno ==1){?>
				<input type="hidden" name="maxRow" value="<?php echo count($Students);?>" class="search_init" />
				<input type="hidden" name="typeMode" value="<?php echo $typeMode;?>" class="search_init" />
			<?php }?> 
			
			<input type="hidden" name="student_id<?php echo $sno;?>" value="<?php echo $student['Student']['id']; ?>">
			<input type="hidden" name="month_year_id<?php echo $sno;?>" value="<?php echo $MonthYears;?>">
			<input type="hidden" name="type<?php echo $sno;?>" value="<?php echo $type;?>">
			<input type="hidden" name="course_mapping_id<?php echo $sno;?>" value="<?php echo $courseMappingId;?>">	
			<?php $attPercentageId = "";$attPercentage = ""; $attVar = $Students[$sno-1]['Attendance'];
				for($i=0;$i<count($attVar);$i++){
					if($attVar[$i]['course_mapping_id'] == $courseMappingId){
						$attPercentage = $attVar[$i]['percentage'];
						$attPercentageId = $attVar[$i]['id'];
					}
				}				
			 ?>		
			<?php echo $attPercentage;?>
			<?php if($attPercentageId){?>
			<input type="hidden" name="attendance<?php echo $sno;?>" value="<?php //echo $attPercentageId;?>">
			<?php }?>
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
		<tr><td colspan="4" align="center">ATTENDANCE SHEET</td></tr>
		<tr>
			<td><b>Month&Year of Exam</b></td>
			<td><?php echo $txtMonthYears;?></td>			
			<td><b>Course</b></td>
			<td><?php echo $txtAcademic;?></td>
		</tr>		
		<tr>
			<td><b>Branch</b></td>
			<td><?php echo $txtProgram;?></td>			
			<td><b>Subject Code<b></td>
			<td><?php echo $shortCode;?></td>
		</tr>
		<tr>
			<td><b>Batch</b></td>
			<td><?php echo $txtBatch;?></td>			
			<td><b>Date</b></td>
			<td></td>			
		</tr>
	</table>
	<table class="attendanceHeadTblP" cellpadding="0" cellspacing="0" border="1" style="margin-top:10px;">
	<thead>
	<tr style='height:30px;'>		
		<th><b>S.No.</b></th>
		<th><b>Reg No.&nbsp;&nbsp;</b></th>
		<th><b>Student Name</b></th>
		<th><b>Percentage</b></th>
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