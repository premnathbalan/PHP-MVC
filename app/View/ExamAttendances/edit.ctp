<?php echo $this->Form->create('ExamAttendance');
$absentTotal="";$presentTotal=""; if($exam_type == 'R'){?>
	<table class="display tblOddEven" border="1" cellpadding="0" cellspacing="0">
		<tr><th colspan="4" align="center">EXAM ATTENDANCE ENTRY</th></tr>
		<tr>
			<td><b>Month&Year of Exam</b></td>
			<td><?php echo $this->Html->getMonthYearFromId($month_year_id);?></td>			
			<td><b>Course</b></td>
			<td><?php if(isset($results[0]['Timetable']['CourseMapping']['Course']['course_name'])){echo $results[0]['Timetable']['CourseMapping']['Course']['course_name'];}?></td>
		</tr>		
		<tr>
			<td><b>Branch</b></td>
			<td><?php if(isset($results[0]['Timetable']['CourseMapping']['Program']['program_name'])){echo $results[0]['Timetable']['CourseMapping']['Program']['program_name'];}?></td>			
			<td><b>Subject Code<b></td>
			<td><?php if(isset($results[0]['Timetable']['CourseMapping']['Course']['course_code'])){echo $results[0]['Timetable']['CourseMapping']['Course']['course_code'];}?></td>
		</tr>
		<tr>
			<td><b>Batch</b></td>
			<td><?php if(isset($results[0]['Timetable']['CourseMapping']['Batch']['batch_from'])){echo $results[0]['Timetable']['CourseMapping']['Batch']['batch_from']." - ".$results[0]['Timetable']['CourseMapping']['Batch']['batch_to'];}?></td>			
			<td><b>Date & Session</b></td>
			<td><?php echo date( "d-m-Y", strtotime(h($exam_date)))."  (".$exam_session.")"; ?></td>			
		</tr>
	</table>
	<?php } if($exam_type == 'A'){ ?>
	<table class="display tblOddEven" border="1" cellpadding="0" cellspacing="0">
		<tr><th colspan="4" align="center">ARREAR EXAM ATTENDANCE ENTRY</th></tr>
		<tr>
			<td><b>Month&Year of Exam</b></td>
			<td><?php echo $this->Html->getMonthYearFromId($month_year_id);?></td>			
			<td><b>Date & Session</b></td>
			<td><?php echo date( "d-m-Y", strtotime(h($exam_date)))."  (".$exam_session.")"; ?></td>	
		</tr>
	</table>
	<?php }?>
<table cellpadding="0" cellspacing="0" border="0" class="display" id="example" style="margin-top:10px;">
	<thead>
	<tr>
		<th>Register No.</th>
		<th>Name</th>
		<th>Attendance</th>		
		<th class="thAction"></th>
	</tr>
	</thead>
	<tbody>
	<?php $i =1; foreach ($results as $result): ?>
	<tr class=" gradeX">
		<td><?php echo $result['Student']['registration_number'];?></td>
		<td><?php echo $result['Student']['name'];?></td>
		<td>
			<?php echo $this->Form->input('studentId'.$i, array('type'=>'hidden','label'=>false,'default'=>$result['Student']['id']));?>
			<?php echo $this->Form->input('AutoGenId'.$i, array('type'=>'hidden','label'=>false,'default'=>$result['ExamAttendance']['id']));?>
			<?php $checked = "";
			if($result['ExamAttendance']['attendance_status'] == 1){$checked = "checked"; echo "P";$presentTotal++;}else{ echo "A";$absentTotal++;} ?></td>
		<td>	
		<?php	echo $this->Form->input('checkbox'.$i, array('type'=>'checkbox','label'=>false, 'class'=>'chkExamAttendance', 'style'=>'margin-top:-15px;', 'checked' =>$checked)); ?>
		</td>
	</tr>
	<?php $i++; endforeach; ?>
	</tbody>
	<tfoot>
		<tr>
			<th><input type="text" name="Register No." value="Register No." class="search_init" /></th>
			<th><input type="text" name="Student Name" value="Student Name" class="search_init" /></th>
			<th><input type="text" name="Attendance" value="Attendance" class="search_init" /></th>
			<th></th>
			
		</tr>
	</tfoot>
	</table>
	<table style="width:100%;font:14px arial;font-weight:bold;" border="1" cellpadding="0" cellspacing="0">
		<tr>
			<td style="text-align:center;background-color:yellow;height:30px;">Total Student</td>
			<td style="text-align:center;background-color:red;">Absent Student</td>
			<td style="text-align:center;background-color:green;">Present Student</td>
		</tr>
		<tr>
			<td style="text-align:center;height:30px;" id="studentTotal"><?php echo $i-1;?></td>
			<td style="text-align:center;" id="absentTotal"><?php echo $absentTotal;?></td>
			<td style="text-align:center;" id="presentTotal"><?php echo $presentTotal;?></td>
		</tr>
	</table>
	<table style="width:100%;">
		<tr>
			<td style="text-align:center;">
				<input type="hidden" name="timetable_id" value="<?php echo $timetable_id;?>">
				<input type="hidden" name="maxRow" value="<?php echo $i-1;?>">
				<?php echo $this->Form->button('<i class="ace-icon fa fa-check bigger-110"></i>'.__('Confirm'),array('type'=>'submit','name'=>'Confirm','value'=>'Confirm','class'=>'btn',"onclick"=>"ExamAttendanceSearch();"));?>
			</td>
		</tr>
	</table>
	
<?php echo $this->Form->end(); ?>

<script>leftMenuSelection('ExamAttendances');</script>
<span class='breadcrumb1'>
<span class='navbar-brand'><small>EXAMINATION <i class='ace-icon fa fa-angle-double-right'></i></small></span>
<?php echo $this->Html->link("<span class='navbar-brand'><small>Exam Attendance <i class='ace-icon fa fa-angle-double-right'></i></small></span>", array("controller"=>"ExamAttendances",'action' => 'index'),array('data-placement'=>'left','escape' => false)); ?>
<span class='navbar-brand'><small>Edit </small></span>
</span>