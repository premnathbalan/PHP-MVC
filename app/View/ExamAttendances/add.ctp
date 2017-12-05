<?php if(isset($results)){echo $this->Form->create('ExamAttendance');
if($exam_type =='R'){
?>
	<table class="display tblOddEven" border="1" cellpadding="0" cellspacing="0">
		<tr><th colspan="4" align="center">EXAM ATTENDANCE ENTRY</th></tr>
		<tr>
			<td><b>Month&Year of Exam</b></td>
			<td><?php echo $this->Html->getMonthYearFromMonthYearId($month_year_id);?></td>			
			<td><b>Course</b></td>
			<td><?php if(isset($results[0]['CourseMapping']['Course']['course_name'])){echo $results[0]['CourseMapping']['Course']['course_name'];}?></td>
		</tr>		
		<tr>
			<td><b>Branch</b></td>
			<td><?php if(isset($results[0]['CourseMapping']['Program']['program_name'])){echo $results[0]['CourseMapping']['Program']['program_name'];}?></td>			
			<td><b>Subject Code<b></td>
			<td><?php if(isset($results[0]['CourseMapping']['Course']['course_code'])){echo $results[0]['CourseMapping']['Course']['course_code'];}?></td>
		</tr>
		<tr>
			<td><b>Batch</b></td>
			<td><?php if(isset($results[0]['CourseMapping']['Batch']['batch_from'])){echo $results[0]['CourseMapping']['Batch']['batch_from']." - ".$results[0]['CourseMapping']['Batch']['batch_to'];}?></td>			
			<td><b>Date & Session</b></td>
			<td><?php echo date( "d-m-Y", strtotime(h($exam_date)))."  (".$exam_session.")"; ?></td>			
		</tr>
	</table>
<?php }if($exam_type =='A'){?>
	<table class="display tblOddEven" border="1" cellpadding="0" cellspacing="0">
		<tr><th colspan="4" align="center">ARREAR EXAM ATTENDANCE ENTRY</th></tr>
		<tr>
			<td><b>Month&Year of Exam</b></td>
			<td><?php echo $this->Html->getMonthYearFromId($month_year_id);?></td>			
			<td><b>Date & Session</b></td>
			<td><?php echo date( "d-M-Y", strtotime(h($exam_date)))."  (".$exam_session.")"; ?></td>	
		</tr>
	</table>
<?php }?>

	
<table cellpadding="0" cellspacing="0" border="0" class="display" id="example" style="margin-top:10px;">
	<thead>
	<tr>
		<th>Register No.</th>
		<th>Name</th>	
		<th class="thAction"></th>
	</tr>
	</thead>
	<tbody>
	<?php $i =1;if($exam_type =='R' || $exam_type =='-'){ 
	foreach ($results as $result): 
	if(isset($result['Student']['name'])){
	?>
	<tr class=" gradeX">
		<td><?php echo $result['Student']['registration_number'];?></td>
		<td><?php echo $result['Student']['name'];?></td>
		<td>
			<?php echo $this->Form->input('studentId'.$i, array('type'=>'hidden','label'=>false,'default'=>$result['Student']['id']));?>
			<?php echo $this->Form->input('checkbox'.$i, array('type'=>'checkbox','label'=>false,'class'=>'chkExamAttendance', 'style'=>'margin-top:-15px;','checked'=>'checked')); ?>
		</td>
	</tr>
	<?php $i++;} endforeach; }
	
	if($exam_type =='A') { //pr($results);
		foreach ($modelArray as $model) {
			for($a=0;$a<count($results[$model]);$a++){
				if(isset($results[$model][$a]['Student']['name'])){
				?>
					<tr class=" gradeX">
						<td><?php echo $results[$model][$a]['Student']['registration_number'];?></td>
						<td><?php echo $results[$model][$a]['Student']['name'];?></td>
						<td>
							<?php echo $this->Form->input('studentId'.$i, array('type'=>'hidden','label'=>false,'default'=>$results[$model][$a]['Student']['id']));?>
							<?php echo $this->Form->input('checkbox'.$i, array('type'=>'checkbox','label'=>false,'class'=>'chkExamAttendance', 'style'=>'margin-top:-15px;','checked'=>'checked')); ?>
						</td>
					</tr>
				<?php  $i++; 
				}
			} 
		}
		/*foreach ($results as $result):
			for($a=0;$a<count($result['StudentMark']);$a++){
				if(isset($result['StudentMark'][$a]['Student']['name'])){
				?>
					<tr class=" gradeX">
						<td><?php echo $result['StudentMark'][$a]['Student']['registration_number'];?></td>
						<td><?php echo $result['StudentMark'][$a]['Student']['name'];?></td>
						<td>
							<?php echo $this->Form->input('studentId'.$i, array('type'=>'hidden','label'=>false,'default'=>$result['StudentMark'][$a]['Student']['id']));?>
							<?php echo $this->Form->input('checkbox'.$i, array('type'=>'checkbox','label'=>false,'class'=>'chkExamAttendance', 'style'=>'margin-top:-15px;','checked'=>'checked')); ?>
						</td>
					</tr>
				<?php  $i++; 
				}
			} 
			for($a=0;$a<count($result['CourseStudentMapping']);$a++){
				//if(isset($result['CourseStudentMapping'][$a]['Student']['name'])){
				if(isset($student['CourseStudentMapping'][$a]['new_semester_id'])){
				?>
					<tr class=" gradeX">
						<td><?php echo $result['CourseStudentMapping'][$a]['Student']['registration_number'];?></td>
						<td><?php echo $result['CourseStudentMapping'][$a]['Student']['name'];?></td>
						<td>
							<?php echo $this->Form->input('studentId'.$i, array('type'=>'hidden','label'=>false,'default'=>$result['CourseStudentMapping'][$a]['Student']['id']));?>
							<?php echo $this->Form->input('checkbox'.$i, array('type'=>'checkbox','label'=>false,'class'=>'chkExamAttendance',  'style'=>'margin-top:-15px;','checked'=>'checked')); ?>
						</td>
					</tr>
				<?php  $i++;
				} 
			}
		endforeach;*/ 
	}?>
	</tbody>
	<tfoot>
		<tr>
			<th><input type="text" name="Register No." value="Register No." class="search_init" /></th>
			<th><input type="text" name="Student Name" value="Student Name" class="search_init" /></th>
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
			<td style="text-align:center;" id="absentTotal"></td>
			<td style="text-align:center;" id="presentTotal"><?php echo $i-1;?></td>
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
	
<?php echo $this->Form->end();}?>

<script>leftMenuSelection('ExamAttendances');</script>
<span class='breadcrumb1'>
<span class='navbar-brand'><small>EXAMINATION <i class='ace-icon fa fa-angle-double-right'></i></small></span>
<?php echo $this->Html->link("<span class='navbar-brand'><small>Exam Attendance <i class='ace-icon fa fa-angle-double-right'></i></small></span>", array("controller"=>"ExamAttendances",'action' => 'index'),array('data-placement'=>'left','escape' => false)); ?>
<span class='navbar-brand'><small>Add </small></span>
</span>
