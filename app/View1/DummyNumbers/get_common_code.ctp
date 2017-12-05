<table cellpadding="0" cellspacing="0" border="0" class="display tblOddEven" id="" style="margin-top:10px;">
	<thead>
	<tr>
		<th>Exam Date</th>
		<th>Session</th>
		<th>Common Code</th>
		<th>Course Code</th>
		<th>No. of Students</th>
		<th>Start&nbsp;Dummy&nbsp;No.</th>
		<th>End&nbsp;Dummy&nbsp;No.</th>	
	</tr>
	</thead>
	<tbody>
	
	<?php $i = 1; foreach ($totExams as $dummyNosAlt):
	if(isset($dummyNosAlt['Timetable'])){/* ONLY FOR EXAM DATE SELECTION */
		if($i==1){echo "<input type='hidden' name='mode' value='D'>";}
		if(isset($dummyNosAlt['ExamAttendance'][0]['timetable_id']) && (@$dummyNosAlt['DummyRangeAllocation'][0]['DummyNumber']['mode'] != 'C')){?>
	<tr class=" gradeX">
		<td><?php echo date( "d-M-Y", strtotime(h($dummyNosAlt['Timetable']['exam_date'])) ); ?></td>
		<td><?php echo $dummyNosAlt['Timetable']['exam_session'];?></td>
		<td><?php echo $dummyNosAlt['CourseMapping']['Course']['common_code'];?></td>
		<td><?php echo $dummyNosAlt['CourseMapping']['Course']['course_code'];?></td>
		<td><?php $abcentAttendance = 0; 
		foreach($dummyNosAlt['ExamAttendance'] as $abAttendance){ 
			if($abAttendance['attendance_status'] == 0){$abcentAttendance = $abcentAttendance +1;} 
		}
		$totalPresent = count($dummyNosAlt['ExamAttendance']) - $abcentAttendance;
		echo count($dummyNosAlt['ExamAttendance'])." - ".$abcentAttendance." = ".$totalPresent;?></td>
		<td>
			<?php echo $this->Form->input('timetable_id'.$i, array('type'=>'hidden','label'=>false, 'size'=>10, 'maxlength'=>8,'default'=>$dummyNosAlt['ExamAttendance'][0]['timetable_id']));
			$dummyVal = "";$dummyEndVal = "";$autoGenId = "";
			if(isset($dummyNosAlt['DummyRangeAllocation'][0]['DummyNumber']['start_range'])){
				$autoGenId = $dummyNosAlt['DummyRangeAllocation'][0]['id'];
				$dummyVal = $dummyNosAlt['DummyRangeAllocation'][0]['DummyNumber']['start_range'];
				if($dummyVal){
					$dummyEndVal = (($dummyNosAlt['DummyRangeAllocation'][0]['DummyNumber']['start_range'])+$totalPresent)-1;
				}
			}
			echo $this->Form->input('dummy_number'.$i, array('type'=>'text','label'=>false, 'maxlength'=>8, 'value'=>$dummyVal,'size'=>10, 'onkeyup'=>"calcEndDummyNo(this.value,$i);"));?>
			<span id="status<?php echo $i;?>"></span>			
		</td>
		<td><?php 
			echo $this->Form->input('autoGenId'.$i, array('type'=>'hidden','label'=>false, 'default'=>$autoGenId, 'size'=>10));		   
            echo $this->Form->input('exam_month'.$i, array('type'=>'hidden','label'=>false, 'default'=>$dummyNosAlt['Timetable']['month_year_id'], 'size'=>10));
			echo $this->Form->input('totalPresent'.$i, array('type'=>'hidden','label'=>false, 'maxlength'=>8,'default'=>$totalPresent));
			echo $this->Form->input('EndDummy_number'.$i, array('type'=>'text','label'=>false, 'maxlength'=>8, 'size'=>10,'readonly'=>1,'default'=>$dummyEndVal));$i++;?>
		</td>		
	</tr>
	<?php }}else{ /* EXAM DATE AND COMMON CODE SELECTION */
		if(isset($dummyNosAlt['DummyRangeAllocation'][0]['DummyNumber']['mode'])!= 'D'){
		if($i==1){echo "<input type='hidden' name='mode' value='C'>";}
		$abcentAttendance = 0; $totalAttendance = 0;$totTTId = "";$exam_month = "";$exam_date = "";$exam_session = "";$dummyVal = "";$dummyEndVal = "";$autoGenId ="";
		foreach($dummyNosAlt['CourseMapping'] as $abAttendance1){ 
			if(isset($abAttendance1['Timetable'][0]['id'])){$totTTId = $totTTId.$abAttendance1['Timetable'][0]['id'].",";}
			if(isset($abAttendance1['Timetable'][0]['month_year_id'])){$exam_month = $abAttendance1['Timetable'][0]['month_year_id'];}
			if(isset($abAttendance1['Timetable'][0]['exam_date'])){$exam_date = date( "d-M-Y", strtotime(h($abAttendance1['Timetable'][0]['exam_date'])) );}
			if(isset($abAttendance1['Timetable'][0]['exam_session'])){$exam_session = h($abAttendance1['Timetable'][0]['exam_session']);}
			if(isset($abAttendance1['Timetable'][0]['DummyRangeAllocation'][0]['DummyNumber'])){
				$dummyVal	 = $abAttendance1['Timetable'][0]['DummyRangeAllocation'][0]['DummyNumber']['start_range'];
				$dummyEndVal = $abAttendance1['Timetable'][0]['DummyRangeAllocation'][0]['DummyNumber']['end_range'];
				$autoGenId   = $abAttendance1['Timetable'][0]['DummyRangeAllocation'][0]['DummyNumber']['id'];
			}
		foreach($abAttendance1['Timetable'] as $abAttendance2){ 
		foreach($abAttendance2['ExamAttendance'] as $abAttendance3){  
			$totalAttendance = $totalAttendance +1;
			if($abAttendance3['attendance_status'] == 0){$abcentAttendance = $abcentAttendance +1;} 
		}}}
		$totTTId = rtrim($totTTId,",");
		$totalPresent = $totalAttendance - $abcentAttendance;
	
	?>
		<td><?php echo $exam_date; ?></td>
		<td><?php echo $exam_session; ?></td>
		<td><?php if($exam_date){ echo h($dummyNosAlt['Course']['common_code']);} ?></td>
		<td><?php if($exam_date){echo h($dummyNosAlt['Course']['course_code']);} ?></td>
		<td><?php if($exam_date){echo $totalAttendance." - ".$abcentAttendance." = ".$totalPresent;}?></td>
		<td>
			<?php if($exam_date){ echo $this->Form->input('timetable_id'.$i, array('type'=>'hidden','label'=>false, 'size'=>10, 'maxlength'=>8,'default'=>$totTTId));
			echo $this->Form->input('dummy_number'.$i, array('type'=>'text','label'=>false, 'maxlength'=>8, 'value'=>$dummyVal,'size'=>10, 'onkeyup'=>"calcEndDummyNo(this.value,$i);"));}?>			
			<span id="status<?php echo $i;?>"></span>
		</td>
		<td>
		<?php
			if($exam_date){
			echo $this->Form->input('autoGenId'.$i, array('type'=>'hidden','label'=>false, 'default'=>$autoGenId, 'size'=>10));
			echo $this->Form->input('exam_month'.$i, array('type'=>'hidden','label'=>false, 'default'=>$exam_month, 'size'=>10)); 
			echo $this->Form->input('totalPresent'.$i, array('type'=>'hidden','label'=>false, 'size'=>10, 'maxlength'=>8,'default'=>$totalPresent));
			echo $this->Form->input('EndDummy_number'.$i, array('type'=>'text','label'=>false, 'maxlength'=>8, 'size'=>10,'readonly'=>1,'default'=>$dummyEndVal));$i++;}?>
		</td>		
	<?php }} endforeach; ?>
	
	</tbody>
	<input type="hidden" name="maxRow" value="<?php echo $i-1;?>">	
</table>

<?php 
	if($i>1){
		echo $this->Form->button('<i class="ace-icon fa fa-check bigger-110"></i>'.__('Confirm'),array('type'=>'submit','name'=>'Confirm','id'=>'Confirm','value'=>'Confirm','class'=>'btn'));
	}
?>

<?php echo $this->Html->script('common-front');?>
<script>leftMenuSelection('ExamAttendances');</script>
<span class='breadcrumb1'>
<span class='navbar-brand'><small>EXAMINATION <i class='ace-icon fa fa-angle-double-right'></i></small></span>
<?php echo $this->Html->link("<span class='navbar-brand'><small>Exam Attendance <i class='ace-icon fa fa-angle-double-right'></i></small></span>", array("controller"=>"ExamAttendances",'action' => 'index'),array('data-placement'=>'left','escape' => false)); ?>
</span>