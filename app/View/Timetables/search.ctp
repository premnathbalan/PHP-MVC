<?php if($results){echo $this->Form->create('Timetable');?>
<table border="1" style="margin:5px;" class="display tblOddEven">
<tr>
	<th>S.No.</th>
	<th>Course</th>
	<th>Exam Date</th>
	<th><?php echo $this->Form->radio('exam_session', array('FN' => 'Forenoon', 'AN' => 'Afternoon'),array('legend' => false, 'onclick'=>"triggerAllRadio('exam_session',this.value)"));?></th>	
</tr>
<?php $i = 1;//Regular Start
if($exam_type == 'R'){
foreach($results as $result){
	$var_exam_session = "";
	$var_exam_date = "";
	if(isset($result['Timetable'][0]['exam_session'])){$var_exam_session = $result['Timetable'][0]['exam_session'];}
	if(isset($result['Timetable'][0]['exam_date'])){$var_exam_date = date( "d-M-Y", strtotime(h($result['Timetable'][0]['exam_date'])) );}?>
<tr>
	<td><i><?php echo $i;?></i></td>
	<td><?php echo $result['Course']['course_code']." - ".$result['Course']['course_name']; ?>
		<?php echo $this->Form->input("CM".$i, array('type' => 'hidden','value'=>$result['CourseMapping']['id']));?>
	</td>
	<td><?php $disabled = ""; 
	if(isset($result['Timetable'][0]['ExamAttendance'])){if(count($result['Timetable'][0]['ExamAttendance'])>0){ $disabled = "disabled";}}
	echo $this->Form->input("exam_date".$i, array('label' => "Date <span class='ash'>*</span>", 'type' => 'text', 'class' => 'fl tal vat w300p', 'error' => false , 'id' => 'select_date'.$i,'required'=>'required','value'=>$var_exam_date,'size'=>12,'disabled' => $disabled));?></td>
	<td><?php echo $this->Form->radio('exam_session'.$i, array('FN' => 'Forenoon', 'AN' => 'Afternoon'),array('legend' => false,'required'=>'required','default'=>$var_exam_session,'class'=>'exam_session'));?></td>
</tr>
<?php $i++;}}
//Regular End

	//Arrear Start
	if($exam_type == 'A'){
		foreach($results as $key=>$result){
			$var_exam_session = "";
			$var_exam_date = "";
			if(isset($results[$key]['exam_session'])){$var_exam_session = $results[$key]['exam_session'];}
			if(isset($results[$key]['exam_date'])){$var_exam_date = date( "d-m-Y", strtotime(h($results[$key]['exam_date'])) );}
			?>
		<tr>
			<td><i><?php echo $i;?></i></td>
			<td><?php echo $results[$key]['course_code']." - ".$results[$key]['course_name']; ?>
				<?php echo $this->Form->input("CM".$i, array('type' => 'hidden','value'=>$key));?>
			</td>
			<td><?php $disabled = ""; 
			if($results[$key]['attendance']=='Y'){ $disabled = "disabled";}
			echo $this->Form->input("exam_date".$i, array('label' => "Date <span class='ash'>*</span>", 'type' => 'text', 'class' => 'fl tal vat w300p', 'error' => false , 'id' => 'select_date'.$i,'required'=>'required','value'=>$var_exam_date,'size'=>12,'disabled' => $disabled));?></td>
			<td><?php echo $this->Form->radio('exam_session'.$i, array('FN' => 'Forenoon', 'AN' => 'Afternoon'),array('legend' => false,'required'=>'required','default'=>$var_exam_session,'class'=>'exam_session'));?></td>
		</tr>
		<?php $i++;
		//Arrear End		
	}
} ?>
	
<tr>
	<td colspan="5" align='center'>
		<input type='hidden' name='batchId' value='<?php echo $batchId;?>' />
		<input type='hidden' name='academic_id' value='<?php echo $academic_id;?>' />
		<input type='hidden' name='programId' value='<?php echo $programId;?>' />
		<input type='hidden' name='month_year_id' value='<?php echo $examMonth;?>' />
		<input type='hidden' name='exam_type' value='<?php echo $exam_type;?>' />
		<input type='hidden' name='maxRow' value='<?php echo $i-1;?>' />		
		<?php echo $this->Form->button('<i class="ace-icon fa fa-check bigger-110"></i>'.__('Confirm'),array('type'=>'submit','name'=>'Confirm','value'=>'Confirm','class'=>'btn'));?>
	</td>
</tr>
<?php 
echo $this->Html->script('common');
echo $this->Html->script('common-front');
?>
</table>
<?php echo $this->Form->end();}?>

<script>leftMenuSelection('Timetables');</script>
<span class='breadcrumb1'>
<span class='navbar-brand'><small>EXAMINATION <i class='ace-icon fa fa-angle-double-right'></i></small></span>
<?php echo $this->Html->link("<span class='navbar-brand'><small>TIME TABLES <i class='ace-icon fa fa-angle-double-right'></i></small></span>", array("controller"=>"Timetables",'action' => 'index'),array('data-placement'=>'left','escape' => false)); ?>
<?php echo $this->Html->link("<span class='navbar-brand'><small>ADD <i class='ace-icon fa fa-angle-double-right'></i></small></span>", array("controller"=>"Timetables",'action' => 'add'),array('data-placement'=>'left','escape' => false)); ?>
</span>