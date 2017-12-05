<?php if($results){echo $this->Form->create('Ese');
//pr($results);
?>

<table border="1" style="margin:5px;" class="display tblOddEven">
<tr>
	<th>S.No.</th>
	<th>Program</th>
	<th>Specialisation</th>
	<th>Course</th>
	<th>Exam Date</th>
	<th>Session</th>
	<th>Actions</th>	
</tr>
<?php $i = 1;foreach($results as $result){ 
	$var_exam_session = "";
	$var_exam_date = "";
	if(isset($result['Timetable'][0]['exam_session'])){$var_exam_session = $result['Timetable'][0]['exam_session'];}
	if(isset($result['Timetable'][0]['id'])){$var_exam_timetable_id = $result['Timetable'][0]['id'];}
	if(isset($result['Timetable'][0]['exam_date'])){$var_exam_date = date( "d-m-Y", strtotime(h($result['Timetable'][0]['exam_date'])) );}
	?>
<tr>
	<td><i><?php echo $i;?></i></td>
	<td><?php echo $result['Program']['Academic']['academic_name'];?></td>
	<td><?php echo $result['Program']['program_name'];?></td>
	<td><?php echo $result['Course']['course_code']." - ".$result['Course']['course_name']; ?>
		<?php echo $this->Form->input("CM", array('type' => 'hidden','value'=>$result['CourseMapping']['id'], 'name'=>'data[Ese][CM][]'));?>
	</td>
	<td align='center'><?php $disabled = ""; 
	if(isset($result['Timetable'][0]['ExamAttendance'])){if(count($result['Timetable'][0]['ExamAttendance'])>0){ $disabled = "disabled";}}
	echo $var_exam_date;?></td>
	<td align='center'><?php echo $var_exam_session;?></td>
	<td align='center'><?php echo $this->Html->link("<i class='fa fa-eye fa-lg'></i>", array("controller"=>"EndSemesterExams",'action' => 'viewEseMarks', $batch_id, $result['CourseMapping']['program_id'], $result['CourseMapping']['id'], $result['Timetable'][0]['id'], $result['CourseMapping']['month_year_id']),array('escape' => false, 'title'=>'View'))."&nbsp;&nbsp;"; ?></td>
</tr>
<?php $i++;}?>
<input type='hidden' name='batch_id' value='<?php echo $batch_id;?>' />
<input type='hidden' name='academic_id' value='<?php echo $academic_id;?>' />
<input type='hidden' name='program_id' value='<?php echo $program_id;?>' />
<input type='hidden' name='month_year_id' value='<?php echo $exam_month;?>' />
<input type='hidden' name='exam_type' value='<?php echo $exam_type;?>' />
<input type='hidden' name='maxRow' value='<?php echo $i-1;?>' />		
<?php 
echo $this->Html->script('common');
?>
</table>
<?php echo $this->Form->end();}?>


<span class='breadcrumb1'>
<span class='navbar-brand'><small>EXAMINATION <i class='ace-icon fa fa-angle-double-right'></i></small></span>
<?php echo $this->Html->link("<span class='navbar-brand'><small>ESE <i class='ace-icon fa fa-angle-double-right'></i></small></span>", array("controller"=>"EndSemesterExams",'action' => 'index'),array('data-placement'=>'left','escape' => false)); ?>
</span>