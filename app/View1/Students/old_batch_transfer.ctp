<div id="resultdata"></div>
<?php
echo $this->Form->create('StudentMark');
echo $this->Form->input('student_id', array('type'=>'hidden', 'label'=>false, 'value'=>$student_id, 'name'=>'data[student_id]'));
echo $this->Form->input('month_year_id', array('type'=>'hidden', 'label'=>false, 'value'=>$joining_month_year_id, 'name'=>'data[month_year_id]'));
$new_batch_id = $results[0]['Student']['batch_id'];
$new_batch = $results[0]['Batch']['batch_from']." - ".$results[0]['Batch']['batch_to']." ".$results[0]['Batch']['academic'];
$new_program_id = $results[0]['Student']['program_id'];
$new_program = $results[0]['Program']['short_code'];
$new_academic_id = $results[0]['Student']['academic_id'];
$new_academic = $results[0]['Program']['Academic']['short_code'];
$new_month_year_id = $results[0]['Student']['month_year_id'];
$prior_batch = $results[0]['Student']['prior_batch'];
echo "Student Name : ".$results[0]['Student']['name']." Registration Number : ".$reg_number;
?>
<table style="width:100%;align:center;border-color:#000;font-weight:14px;margin-top:10px;" border="1" class=" tblOddEven">
	<tr>
		<th>TRANSFER FROM</th>
		<th>TRANSFER TO</th>
	</tr>
	<tr>
		<td align='center'>
			<table>
				<tr>
					<td rowspan='3' rowspan='3' height='24px;'><b>Batch </b></td>
					<td width='20px' align='center'> : </td>
					<td rowspan='3'> <?php echo $prior_batch;?></td>
				</tr>
			</table>
		</td>
		<td align='center'>
			<table>
				<tr>
					<td height='24px;'><b>Batch </b></td>
					<td width='20px' align='center'> : </td>
					<td><?php echo $new_batch;?></td>
				</tr>
				<tr>
					<td height='24px;'><b>Academic </b></td>
					<td width='20px' align='center'> : </td>
					<td><?php echo $new_academic;?></td>
				</tr>
				<tr>
					<td height='24px;'><b>Program </b></td>
					<td width='20px' align='center'> : </td>
					<td><?php echo $new_program;?></td>
				</tr>
			</table>
		</td>
	</tr>
</table>
<table style="width:100%;align:center;border-color:#000;font-weight:14px;margin-top:10px;" border="1" class="display tblOddEven">
	<tr>
		<!--<th>Semester</th>-->
		<th>Course Code</th>
		<th>Course Name</th>
		<th>Course Type</th>
		<th>Month Year</th>
		<th>Cae Marks</th>
		<th>Ese Marks</th>
		<th>Marks</th>
		<th>Status</th>
		<!--<th>Arrear</th>-->
	</tr>
<?php //echo $joining_month_year_id." ".$student_id;

$status_options = array('Pass'=>'Pass', 'Fail'=>'Fail');
foreach ($courseMappingArray as $key => $courseMapping) { //pr($courseMapping);
$csm_my_id = '';
	$cm_id = $courseMapping['CourseMapping']['id'];
	$course_type_id = $courseMapping['Course']['course_type_id'];
	
	if (isset($courseMapping['CourseStudentMapping'][0]['new_semester_id'])) 
	$csm_my_id = $courseMapping['CourseStudentMapping'][0]['new_semester_id'];
	
$total = '';
$status = '';
$cae_marks='';
$ese_marks='';

if(isset($courseMapping['CourseStudentMapping'][0]['Student']['StudentMark'])) { 
	$marksArray = $courseMapping['CourseStudentMapping'][0]['Student']['StudentMark'];
	foreach ($marksArray as $k => $m) {
		if ($m['course_mapping_id'] == $cm_id) {
			$total = $m['marks'];
			$status = $m['status'];
			break;
		}
	}
}
	if ($course_type_id==1) {
		if (isset($courseMapping['CourseStudentMapping'][0]['Student']['InternalExam'])) {
			$caeMarksArray = $courseMapping['CourseStudentMapping'][0]['Student']['InternalExam'];
			foreach ($caeMarksArray as $k => $m) {
				if ($m['course_mapping_id'] == $cm_id) {
					$cae_marks = $m['marks'];
					break;
				}
			}
		}
		
		if (isset($courseMapping['CourseStudentMapping'][0]['Student']['EndSemesterExam'])) {
			$eseMarksArray = $courseMapping['CourseStudentMapping'][0]['Student']['EndSemesterExam'];
			foreach ($eseMarksArray as $k => $m) {
				if ($m['course_mapping_id'] == $cm_id) {
					$ese_marks = $m['marks'];
					break;
				}
			}
		}
	}
	else if ($course_type_id==2) {
		if (isset($courseMapping['CourseStudentMapping'][0]['Student']['InternalPractical'])) {
			$caeMarksArray = $courseMapping['CourseStudentMapping'][0]['Student']['InternalPractical'];
			foreach ($caeMarksArray as $k => $m) {
				if ($m['CaePractical']['course_mapping_id'] == $cm_id) {
					$cae_marks = $m['marks'];
					break;
				}
			}
		}
		if (isset($courseMapping['CourseStudentMapping'][0]['Student']['Practical'])) {
			$eseMarksArray = $courseMapping['CourseStudentMapping'][0]['Student']['Practical'];
			foreach ($eseMarksArray as $k => $m) {
				if ($m['EsePractical']['course_mapping_id'] == $cm_id) {
					$ese_marks = $m['marks'];
					break;
				}
			}
		}
	}
	

	$course_type = $courseMapping['Course']['CourseType']['course_type'];
	
	$course_code = $courseMapping['Course']['course_code'];
	$course_name = $courseMapping['Course']['course_name'];
	echo "<tr id=cm_id_$cm_id class='cm_id'>";
	echo "<td>".$course_code."</td>";
	echo "<td>".$course_name."</td>";
	echo "<td>".$course_type."</td>";
	echo "<td>".$this->Form->input('month_year_id'.$cm_id, array('type'=>'select', 'options'=>$monthyears, 'empty'=>'--Select--', 'label'=>false, 'name'=>'data[StudentMark][month_year_id]['.$cm_id.']', 'default'=>$csm_my_id))."</td>";
	echo "<td align='center'>";
		echo $this->Form->input('cae_marks'.$cm_id, array('type'=>'text', 'name'=>'data[StudentMark][cae_marks]['.$cm_id.']', 'label'=>false, 'value'=>$cae_marks, 'onkeyup'=>"computeBatchTransfer('$cm_id', 'cae', this.value, 50)"));
		echo $this->Form->input('course_type_id', array('type'=>'hidden', 'label'=>false, 'value'=>$course_type_id, 'name'=>'data[StudentMark][course_type_id]['.$cm_id.']'));
	echo "</td>";
	echo "<td align='center'>";
		echo $this->Form->input('ese_marks'.$cm_id, array('type'=>'text', 'label'=>false, 'value'=>$ese_marks, 'name'=>'data[StudentMark][ese_marks]['.$cm_id.']', 'onkeyup'=>"computeBatchTransfer('$cm_id', 'ese', this.value, 50)"));
		echo $this->Form->input('course_mapping_id'.$cm_id, array('type'=>'hidden', 'label'=>false, 'value'=>$cm_id, 'name'=>'data[StudentMark][course_mapping_id]['.$cm_id.']'));
	echo "</td>";	
	echo "<td align='center' id='StudentMarkMarks_$cm_id'>".$total."</td>";
	//echo "<td>".$this->Form->input('arrear_cm_id'.$cm_id, array('type'=>'checkbox', 'class'=>'StudentArrearCmIdCheckbox', 'label'=>false))."</td>";
	echo "<td id='StudentMarkStatus$cm_id'>".$status."</td>";
	echo "</tr>";
}
echo "</table>";
echo $this->Form->end(__('Submit')); ?>
</div>
<?php echo $this->Html->script('common'); ?>
<script>
$('.input label').addClass('col-sm-5 control-label no-padding-right');
$('.radio label').removeClass('col-sm-5 control-label no-padding-right');
leftMenuSelection('Students/batchTransfer');

var col, el;

$("input[type=radio]").click(function() {
   el = $(this);
   col = el.data("col");
   $("input[data-col=" + col + "]").prop("checked", false);
   el.prop("checked", true);
});
</script>

<span class='breadcrumb1'>
<span class='navbar-brand'><small>Transfer STUDENT <i class='ace-icon fa fa-angle-double-right'></i></small></span>
<?php echo $this->Html->link("<span class='navbar-brand'><small> MAPPING Courses <i class='ace-icon fa fa-angle-double-right'></i></small></span>", array("controller"=>"Students",'action' => 'transferCourses',$reg_number),array('data-placement'=>'left','escape' => false)); ?>
</span>