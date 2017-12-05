<?php
$details = $this->Html->getCourseNameCrseCodeCmnCodeFromCMId($cm_id);
$basic_details = $this->Html->getBatchAcademicProgramFromCmId($cm_id);
?>

<?php echo $this->Form->create('PT'); 
echo $this->Form->input('cm_id', array('type'=> 'hidden', 'label'=>false, 'default'=>$cm_id, 'name' => 'data[ProfessionalTraining][course_mapping_id]', 'id'=>'cm_id'));
echo $this->Form->input('exam_month_year_id', array('type'=> 'hidden', 'label'=>false, 'default'=>$exam_month_year_id, 'name' => 'data[ProfessionalTraining][exam_month_year_id]'));
?>
	<table class="display tblOddEven" border="1" cellpadding="0" cellspacing="0">
		<tr><th colspan="4" align="center">ARREAR EXAM CAE ENTRY</th></tr>
		<tr>
			<td><b>Batch</b></td>
			<td><?php echo $basic_details['batch']; ?></td>			
			<td><b>Program</b></td>
			<td><?php echo $basic_details['academic']; ?></td>	
		</tr>
		<tr>
			<td><b>Specialisation</b></td>
			<td><?php echo $basic_details['program'];?></td>		
			<td><b>Course Code</b></td>
			<td><?php echo $details['course_code'];?></td>
		</tr>
		<tr>	
			<td><b>Course Name</b></td>
			<td><?php echo $details['course_name'];?></td>			
			<td><b>Month&Year of Exam</b></td>
			<td><?php echo $this->Html->getMonthYearFromMonthYearId($exam_month_year_id); ?></td>	
		</tr>
		<tr>	
			<td><b>Max CAE Mark</b></td>
			<td id='maxCAEMark'><?php echo $courseMarks[$cm_id]['max_cae_mark'];?></td>			
			<td colspan='2'><b></b></td>
			<!--<td id='maxESEMark'></td>-->	
		</tr>
	</table>
<table cellpadding="0" cellspacing="0" border="0" class="display" id="example" style="margin-top:10px;">
	<thead>
	<tr>
		<th>Register No.</th>
		<th>Name</th>
		<th>CAE&nbsp;Marks</th>
	</tr>
	</thead>
	<tbody>
	<?php 
	//pr($results);
	$i =1; foreach ($results as $key => $array) { //pr($array); 
	$student_id = $array[$model]['student_id'];
	$caeArray = $array[$model]['ProfessionalTraining'];
	$studentArray = $this->Html->getStudentInfo($student_id);
	if (isset($caeArray['cae_mark'])) $cae_mark = $caeArray['cae_mark'];
	else $cae_mark = '';
	$cae_pt_id = $this->Html->getCaePtIdFromCmId($cm_id); 
	//pr($studentArray);
	?>
	<tr class=" gradeX">
		<td><?php echo $studentArray['Student']['registration_number'];?></td>
		<td><?php echo $studentArray['Student']['name'];?></td>
		<td>
			<?php
				echo $this->Form->input('cae_old_marks', array('type'=> 'hidden','id'=>'CaeOldMark'.$i, 'default'=>$cae_mark, 'class'=>'dummy','style'=>'position:relative;margin-top:-14px;width:50px;border-color:#000;color:#000;padding-left:10px;', 'label'=>false, 'name' => 'data[ProfessionalTraining][cae_old_marks]['.$student_id.']'));
				echo $this->Form->input('cae_new_marks', array('type'=> 'text','id'=>'CaeNewMark'.$i, 'default'=>$cae_mark, 'class'=>'dummy','style'=>'position:relative;margin-top:-14px;width:50px;border-color:#000;color:#000;padding-left:10px;', 'label'=>false, 'name' => 'data[ProfessionalTraining][cae_new_marks]['.$student_id.']', 'onblur'=>"ArrearPTCAEEntry($i,this.value,$student_id,$exam_month_year_id,$cae_pt_id);"));
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
		</tr>
	</tfoot>
	</table>
	<table style="width:100%;">
		<tr>
			<td style="text-align:center;">
				<?php echo $this->Form->button('<i class="ace-icon fa fa-check bigger-110"></i>'.__('Confirm'),array('type'=>'submit','name'=>'Confirm','value'=>'Confirm','class'=>'btn',"onclick"=>"ExamAttendanceSearch();"));?>
			</td>
		</tr>
	</table>
<?php echo $this->Form->end(); ?>
<?php echo $this->Html->script('common'); ?>
<script>leftMenuSelection('PTArrears/index');</script>

<span class='breadcrumb1'>
<span class='navbar-brand'><small>Professional Training <i class='ace-icon fa fa-angle-double-right'></i></small></span>
<?php echo $this->Html->link("<span class='navbar-brand'><small>Arrear Mark Entry <i class='ace-icon fa fa-angle-double-right'></i></small></span>", array("controller"=>"PTArrears",'action' => 'index'),array('data-placement'=>'left','escape' => false)); ?>
</span>