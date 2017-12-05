<?php
//pr($details); 
$batch = $details[0]['Batch'];
$batch_period = $batch['batch_from']."-".$batch['batch_to']." ".$batch['academic'];
$academic = $details[0]['Program']['Academic']['academic_name'];
$program = $details[0]['Program']['program_name'];
?>

<?php echo $this->Form->create('Practical'); 
echo $this->Form->input('cm_id', array('type'=> 'hidden', 'label'=>false, 'default'=>$cm_id, 'name' => 'data[Practical][course_mapping_id]'));
echo $this->Form->input('exam_month_year_id', array('type'=> 'hidden', 'label'=>false, 'default'=>$exam_month_year_id, 'name' => 'data[Practical][exam_month_year_id]'));
echo $this->Form->input('ese_practical_id', array('type'=> 'hidden', 'label'=>false, 'default'=>$ese_practical_id, 'name' => 'data[Practical][ese_practical_id]', 'id'=>'ese_practical_id'));
echo $this->Form->input('cae_practical_id', array('type'=> 'hidden', 'label'=>false, 'default'=>$cae_practical_id, 'name' => 'data[Practical][cae_practical_id]', 'id'=>'cae_practical_id'));
?>
	<table class="display tblOddEven" border="1" cellpadding="0" cellspacing="0">
		<tr><th colspan="4" align="center">ARREAR EXAM ATTENDANCE ENTRY</th></tr>
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
			<td><?php echo $details[0]['Course']['course_name'];;?></td>			
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
		<th>CAE&nbsp;Marks</th>
		<th>ESE&nbsp;Marks</th>		
	</tr>
	</thead>
	<tbody>
	<?php 
	//pr($markArray);
	//pr($cae_results);
	$i =1; foreach ($markArray as $student_id => $studentArray) { 
	//pr($studentArray);
	?>
	<tr class=" gradeX">
		<td><?php echo $studentArray['registration_number'];?></td>
		<td><?php echo $studentArray['name'];?></td>
		<td>
				<?php
					echo $this->Form->input('cae_old_marks', array('type'=> 'hidden','id'=>'CaeOldMark'.$i, 'value'=>$cae_results[$student_id]['marks'], 'style'=>'position:relative;margin-top:-14px;width:50px;border-color:#000;color:#000;padding-left:10px;', 'label'=>false, 'name' => 'data[Practical][cae_old_marks]['.$student_id.']'));
					//echo $this->Form->input('cae_practical_id', array('type'=> 'hidden','id'=>'cae_practical_id'.$i, 'default'=>$cae_results[$student_id]['cae_practical_id'], 'class'=>'dummy','style'=>'position:relative;margin-top:-14px;width:50px;border-color:#000;color:#000;padding-left:10px;', 'label'=>false, 'name' => 'data[Practical][cae_practical_id]['.$student_id.']'));
					echo $this->Form->input('cae_new_marks', array('type'=> 'text','id'=>'CaeNewMark'.$i, 'value'=>$cae_results[$student_id]['marks'], 'class'=>'dummy','style'=>'position:relative;margin-top:-14px;width:50px;border-color:#000;color:#000;padding-left:10px;', 'label'=>false, 'name' => 'data[Practical][cae_new_marks]['.$student_id.']', 'onblur'=>"ArrearPracCAEEntry($i,this.value,$student_id,$exam_month_year_id);"));
				?>
		</td>
		<td>	
			<?php $marks=0;
			if(isset($studentArray['marks'])){
				$marks = $studentArray['marks'];
			}
			echo $this->Form->input('marks', array('type'=> 'text','id'=>'Arrear'.$i, 'class'=>'dummy', 'style'=>'position:relative;margin-top:-14px;width:50px;border-color:#000;color:#000;padding-left:10px;','value'=>$marks, 'label'=>false, 'name' => 'data[Practical][marks]['.$student_id.']', 'onblur'=>"ArrearPracESEEntry($i,this.value,$student_id,$exam_month_year_id);"));
			?>
		</td>
	</tr>
	<?php $i++;} ?>
	</tbody>
	<tfoot>
		<tr>
			<th><input type="text" name="Register No." value="Register No." class="search_init" /></th>
			<th><input type="text" name="Student Name" value="Student Name" class="search_init" /></th>
			<th><input type="text" name="CAE Marks" value="CAE Marks" class="search_init" /></th>
			<th><input type="text" name="ESE Marks" value="ESE Marks" class="search_init" /></th>
		</tr>
	</tfoot>
	</table>
	<table style="width:100%;">
		<tr>
			<td style="text-align:center;">
				<?php echo $this->Form->button('<i class="ace-icon fa fa-check bigger-110"></i>'.__('Confirm'),array('type'=>'submit','name'=>'Confirm','value'=>'Confirm','class'=>'btn',"onclick"=>"checkMarkEnryEmpty();"));?>
			</td>
		</tr>
	</table>
<?php echo $this->Form->end(); ?>
<?php echo $this->Html->script('common'); ?>
<script>leftMenuSelection('Arrears');</script>

<span class='breadcrumb1'>
<span class='navbar-brand'><small>PRACTICALS <i class='ace-icon fa fa-angle-double-right'></i></small></span>
<?php echo $this->Html->link("<span class='navbar-brand'><small>Arrear Mark Entry <i class='ace-icon fa fa-angle-double-right'></i></small></span>", array("controller"=>"Arrears",'action' => 'arrear'),array('data-placement'=>'left','escape' => false)); ?>
</span>