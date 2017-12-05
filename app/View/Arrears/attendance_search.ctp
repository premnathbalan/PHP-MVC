<table cellpadding="0" cellspacing="0" border="0" class="display" id="example" style="margin-top:10px;">
	<thead>
	<tr>
		<th>Exam&nbsp;Date</th>
		<th>Batch</th>
		<th>Program</th>
		<th>Specialisation</th>
		<th>Common&nbsp;Code</th>
		<th>Course&nbsp;Code</th>
		<th>Course</th>
		<th>Semester</th>
		<th>Session</th>
		<th>Status</th>	
		<th class="actions"><?php echo __('Actions'); ?></th>
	</tr>
	</thead>
	<tbody>
	<?php foreach ($examAttendances as $examAttendance): ?>
	<tr class=" gradeX">
		<td><?php echo date( "d-M-Y", strtotime(h($examAttendance['Timetable']['exam_date'])) ); ?></td>
		<td><?php echo $examAttendance['CourseMapping']['Batch']['batch_from']."-".$examAttendance['CourseMapping']['Batch']['batch_to'];?></td>
		<td><?php echo $examAttendance['CourseMapping']['Program']['Academic']['academic_name'];?></td>
		<td><?php echo $examAttendance['CourseMapping']['Program']['program_name'];?></td>
		<td><?php echo $examAttendance['CourseMapping']['Course']['common_code'];?></td>
		<td><?php echo $examAttendance['CourseMapping']['Course']['course_code'];?></td>
		<td><?php echo $examAttendance['CourseMapping']['Course']['course_name'];?></td>
		<td><?php echo $examAttendance['CourseMapping']['semester_id'];?></td>
		<td><?php echo $examAttendance['Timetable']['exam_session'];?></td>
		<td><?php if(empty($examAttendance['ExamAttendance'])){ echo "<span class='ovelShapBg2'>Open</span>";} else{echo "Closed";}?></td>
		<td>
		<?php 
			$semesterNewId = "-";
			if(isset($examAttendance['CourseMapping']['CourseStudentMapping'][0]['new_semester_id'])){
				$semesterNewId = $examAttendance['CourseMapping']['CourseStudentMapping'][0]['new_semester_id'];	
			}
			if(empty($examAttendance['ExamAttendance'])){
			if($this->Html->checkPathAccesstopath('ExamAttendances/add','',$authUser['id'])){
				echo $this->Html->link("<i class='fa fa-plus fa-lg'></i>",array("controller"=>"ExamAttendances",'action' => 'add',$examAttendance['Timetable']['id'],$examMonth,$examAttendance['CourseMapping']['id'],$exam_type,'L',$examAttendance['Timetable']['exam_date'],$examAttendance['Timetable']['exam_session'],$semesterNewId), array('escape' => false));
			}} 
			if($examAttendance['ExamAttendance']){ 
			if($this->Html->checkPathAccesstopath('ExamAttendances/edit','',$authUser['id'])){ 
				echo $this->Html->link("<i class='fa fa-pencil fa-lg'></i>",array("controller"=>"ExamAttendances",'action' => 'edit',$examAttendance['Timetable']['id'],$examMonth,$examAttendance['CourseMapping']['id'],$exam_type,'L',$examAttendance['Timetable']['exam_date'],$examAttendance['Timetable']['exam_session'],$semesterNewId),array( 'escape' => false));
			}}
			echo $this->Html->link("<i class='ace-icon fa fa-print fa-lg'></i>",array("controller"=>"ExamAttendances",'action' => 'add',$examAttendance['Timetable']['id'],$examMonth,$examAttendance['CourseMapping']['id'],$exam_type,'P',$examAttendance['Timetable']['exam_date'],$examAttendance['Timetable']['exam_session'],$semesterNewId), array('escape' => false,'style'=>'padding-left:10px;','target'=>'_blank'));					
		?>
		</td>
	</tr>
<?php endforeach; ?>
	</tbody>
	<tfoot>
		<tr>
			<th><input type="text" name="Exam&nbsp;Date" value="Exam Date" class="search_init" /></th>
			<th><input type="text" name="Batch" value="Batch" class="search_init" /></th>
			<th><input type="text" name="Program" value="Program" class="search_init" /></th>
			<th><input type="text" name="Specialisation" value="Specialisation" class="search_init" /></th>
			<th><input type="text" name="Common&nbsp;Code" value="Common&nbsp;Code" class="search_init" /></th>
			<th><input type="text" name="Course&nbsp;Code" value="Course&nbsp;Code" class="search_init" /></th>
			<th><input type="text" name="Course" value="Course" class="search_init" /></th>
			<th><input type="text" name="Semester" value="Semester" class="search_init" /></th>
			<th><input type="text" name="Session" value="Session" class="search_init" /></th>
			<th><input type="text" name="Status" value="Status" class="search_init" /></th>
			<th></th>
		</tr>
	</tfoot>
	</table>
<?php echo $this->Html->script('common');?>
<script>leftMenuSelection('ExamAttendances');</script>
<span class='breadcrumb1'>
<span class='navbar-brand'><small>EXAMINATION <i class='ace-icon fa fa-angle-double-right'></i></small></span>
<?php echo $this->Html->link("<span class='navbar-brand'><small>Exam Attendance <i class='ace-icon fa fa-angle-double-right'></i></small></span>", array("controller"=>"ExamAttendances",'action' => 'index'),array('data-placement'=>'left','escape' => false)); ?>
</span>