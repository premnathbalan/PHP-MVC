<?php
//pr($details); 
$batch = $details[0]['Batch'];
$batch_period = $batch['batch_from']."-".$batch['batch_to']." ".$batch['academic'];
$academic = $details[0]['Program']['Academic']['academic_name'];
$program = $details[0]['Program']['program_name'];
?>

<?php echo $this->Form->create('Practical'); 
echo $this->Form->input('cm_id', array('type'=> 'hidden', 'label'=>false, 'default'=>$cm_id, 'name' => 'data[PracticalAttendance][course_mapping_id]'));
echo $this->Form->input('exam_month_year_id', array('type'=> 'hidden', 'label'=>false, 'default'=>$exam_month_year_id, 'name' => 'data[PracticalAttendance][exam_month_year_id]'));
//echo $this->Form->input('ese_practical_id', array('type'=> 'hidden', 'label'=>false, 'default'=>$ese_practical_id, 'name' => 'data[PracticalAttendance][ese_practical_id]', 'id'=>'ese_practical_id'));
?>
	<table class="display tblOddEven" border="1" cellpadding="0" cellspacing="0">
		<tr><th colspan="4" align="center">ARREAR EXAM MARK ENTRY</th></tr>
		<tr>
			<td><b>Batch</b></td>
			<td><?php echo $batch_period; ?></td>			
			<td><b>Program</b></td>
			<td><?php echo $academic; ?></td>	
		</tr>
		<tr>
			<td><b>Specialisation</b></td>
			<td><?php echo $program;?></td>		
			<td><b>Course Code</b></td>
			<td><?php echo $details[0]['Course']['course_code'];?></td>
		</tr>
		<tr>	
			<td><b>Course Name</b></td>
			<td><?php echo $details[0]['Course']['course_name'];?></td>			
			<td><b>Month&Year of Exam</b></td>
			<td><?php echo $month_year; ?></td>	
		</tr>
		<tr>	
			<td><b>Max CAE Mark</b></td>
			<td id='maxCAEMark'><?php echo $courseMarks[$cm_id]['max_cae_mark'];?></td>			
			<td><b>Max ESE Mark</b></td>
			<td id='maxESEMark'><?php echo $courseMarks[$cm_id]['max_ese_mark']; ?></td>	
		</tr>
	</table>
	<table cellpadding="0" cellspacing="0" border="0" class="display" id="example" style="margin-top:10px;">
	<thead>
	<tr>
		<th>Register No.</th>
		<th>Name</th>
		<th>Attendance</th>
	</tr>
	</thead>
	<tbody>
	<?php //pr($attendanceArray); 
	$i=1;
	foreach ($student_id_array as $student_id => $value) { ?>
		<tr class=" gradeX">
			<td><?php echo $value['registration_number'];?></td>
			<td><?php echo $value['name'];?></td>
			<td>
				<?php echo $this->Form->input('checkbox', array('type'=>'checkbox','label'=>false, 'style'=>'margin-top:-15px;', 'name'=>'data[PracticalAttendance][attendance_status]['.$student_id.']'));?>
			</td>
		</tr> 
	<?php
		$i++;
	}
	
	?>
	</tbody>
	<tfoot>
		<tr>
			<th><input type="text" name="Register No." value="Register No." class="search_init" /></th>
			<th><input type="text" name="Name" value="Name" class="search_init" /></th>
			<th></th>
		</tr>
	</tfoot>
	</table>
	<table style="width:100%;">
		<tr>
			<td style="text-align:center;">
				<?php echo $this->Form->button('<i class="ace-icon fa fa-check bigger-110"></i>'.__('Confirm'),array('type'=>'submit','name'=>'Confirm','value'=>'Confirm','class'=>'btn'));?>
			</td>
		</tr>
	</table>
<?php echo $this->Form->end(); ?>
<?php echo $this->Html->script('common'); ?>
<script>leftMenuSelection('Arrears');</script>

<span class='breadcrumb1'>
<span class='navbar-brand'><small>PRACTICALS <i class='ace-icon fa fa-angle-double-right'></i></small></span>
<?php echo $this->Html->link("<span class='navbar-brand'><small>Arrear Attendance Entry <i class='ace-icon fa fa-angle-double-right'></i></small></span>", array("controller"=>"Arrears",'action' => 'arrear'),array('data-placement'=>'left','escape' => false)); ?>
</span>