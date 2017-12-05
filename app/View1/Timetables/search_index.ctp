<div style="margin-top:10px;">
<table cellpadding="0" cellspacing="0" border="0" class="display" id="example">
	<thead>
		<tr>
			<th>Batch</th>
			<th>Exam Month</th>
			<th>Program</th>			
			<th>Specialisation</th>
			<th>Course</th>
			<th>Semester</th>
			<th>Exam&nbsp;Date</th>
			<th>Session</th>
			<th class="thAction">&nbsp;&nbsp;Action&nbsp;&nbsp;</th>
		</tr>
	</thead>
	<tbody>	
		<?php foreach ($timetables as $timetable):
		if($timetable['CourseMapping']['batch_id'] == $batchId){
		if($timetable['CourseMapping']['Program']['academic_id'] == $Academic){
		if($timetable['CourseMapping']['program_id'] == $programId){
		if($timetable['Timetable']['exam_type'] == $exam_type){		
		?>
		<tr class=" gradeX">
			<td><?php
				$modeAcademic = ""; if($timetable['CourseMapping']['Batch']['academic'] == 'JUN'){ $modeAcademic = " [A]"; }
				echo h($timetable['CourseMapping']['Batch']['batch_from']."-".$timetable['CourseMapping']['Batch']['batch_to'].$modeAcademic);?></td>
			<td><?php echo $timetable['MonthYear']['Month']['month_name']." ".$timetable['MonthYear']['year'];?></td>
			<td><?php echo $timetable['CourseMapping']['Program']['Academic']['academic_name'];?></td>			
			<td><?php echo $timetable['CourseMapping']['Program']['program_name'];?></td>
			<td><?php echo $timetable['CourseMapping']['Course']['course_code']." - ".$timetable['CourseMapping']['Course']['course_name'];?></td>
			<td><?php echo $timetable['CourseMapping']['semester_id'];?></td>
			<td><?php echo date( "d-M-Y", strtotime(h($timetable['Timetable']['exam_date']))); ?></td>
			<td><?php if($timetable['Timetable']['exam_session'] == 'FN'){ echo "Forenoon";}else{ echo "Afternoon";}?></td>
			<td class="actions">
			<?php 
			if($this->Html->checkPathAccesstopath('Timetables/delete','',$authUser['id'])){ 
				if(count($timetable['ExamAttendance']) ==0){
					echo $this->Form->postLink("<span class='fa fa-times fa-lg red'></span>", array("controller"=>"Timetables",'action' => 'delete', $timetable['Timetable']['id']), array('confirm' => __('Are you sure you want to delete?', $timetable['Timetable']['id']),'escape' => false, 'title'=>'Delete'));
				}
			} 
			?>			
		</td>
		</tr>
		  <?php }}}} endforeach; ?>		
	</tbody>
	<tfoot>
		<tr>
			<th><input type="text" name="Batch" value="Batch" class="search_init" /></th>
			<th><input type="text" name="Exam Month Year" value="Exam Month Year" class="search_init" /></th>
			<th><input type="text" name="Program" value="Program" class="search_init" /></th>			
			<th><input type="text" name="Specialisation" value="Specialisation" class="search_init" /></th>
			<th><input type="text" name="Course" value="Course" class="search_init" /></th>
			<th><input type="text" name="Semester" value="Semester" class="search_init" /></th>
			<th><input type="text" name="Exam&nbsp;Date" value="Exam&nbsp;Date" class="search_init" /></th>
			<th><input type="text" name="Session" value="Session" class="search_init" /></th>
			<th></th>
		</tr>
	</tfoot>
	
</table>
<?php echo $this->Html->script('common');?>
<span class='breadcrumb1'>
<span class='navbar-brand'><small>EXAMINATION <i class='ace-icon fa fa-angle-double-right'></i></small></span>
<?php echo $this->Html->link("<span class='navbar-brand'><small>TIME TABLES <i class='ace-icon fa fa-angle-double-right'></i></small></span>", array("controller"=>"Timetables",'action' => 'index'),array('data-placement'=>'left','escape' => false)); ?>
</span>
</div>