<?php
//echo $reg_number;
echo $this->Form->create('');
//pr($monthyears);
foreach ($results as $key => $result) {
//pr($result);
	$tmp = $result['ParentGroup']['CourseStudentMapping'];
	foreach ($tmp as $key => $csmData) {
	if (isset($csmData['new_semester_id']) && $csmData['new_semester_id']>0) 
		$NewSemesterId[] = $csmData['new_semester_id'];
		//echo $NewSemesterId;
		break; 
	}
	foreach ($tmp as $key => $csmData) {
	if (isset($csmData['month_year_id']) && $csmData['month_year_id']>0) 
		$NewMonthYearId[] = $csmData['month_year_id'];
		break; 
	}
}
foreach ($results as $key => $result) {
	//pr($result);
	$new_batch_id = $result['Student']['batch_id'];
	$new_batch = $result['Batch']['batch_from']." - ".$result['Batch']['batch_to']." ".$result['Batch']['academic'];
	$new_program_id = $result['Student']['program_id'];
	$new_program = $result['Program']['short_code'];
	$new_academic_id = $result['Student']['academic_id'];
	$new_academic = $result['Program']['Academic']['short_code'];
	$new_month_year_id = $result['Student']['month_year_id'];
	
	$student_type_id = $result['Student']['student_type_id'];
	$university_references_id = $result['Student']['university_references_id'];
	
	$old_batch_id = $result['ParentGroup']['batch_id'];
	$old_batch = $result['ParentGroup']['Batch']['batch_from']." - ".$result['ParentGroup']['Batch']['batch_to']." ".$result['ParentGroup']['Batch']['academic'];
	$old_program_id = $result['ParentGroup']['program_id'];
	$old_program = $result['ParentGroup']['Program']['short_code'];
	$old_academic_id = $result['ParentGroup']['academic_id'];
	$old_academic = $result['ParentGroup']['Program']['Academic']['short_code'];

	$oldCourseStudentMapping = $result['ParentGroup']['CourseStudentMapping'];
	//pr($oldCourseStudentMapping);
	echo $this->Form->input('old_student_id', array('type'=>'hidden', 'label'=>false, 'value'=>$parent_id, 'name'=>'data[MapStudents][parent_id]'));
	echo $this->Form->input('student_type_id', array('type'=>'hidden', 'label'=>false, 'value'=>$student_type_id, 'name'=>'data[MapStudents][student_type_id]'));
}



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
					<td height='24px;'><b>Batch </b></td>
					<td width='20px' align='center'> : </td>
					<td> <?php echo $old_batch;?></td>
				</tr>
				<tr>
					<td height='24px;'><b>Academic </b></td>
					<td width='20px' align='center'> : </td>
					<td> <?php echo $old_academic;?></td>
				</tr>
				<tr>
					<td height='24px;'><b>Program </b></td>
					<td width='20px' align='center'> : </td>
					<td> <?php echo $old_program;?></td>
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
<?php

?>
	 	
<table style="width:100%;align:center;border-color:#000;font-weight:14px;margin-top:10px;" border="1" class="display tblOddEven">
	<tr>
		<th>Semester</th>
		<th>Common Passed Courses</th>
		<th>Common Failed Courses</th>
		<th>Uncommon Passed Courses</th>
		<!--<th>Uncommon Failed Courses</th>-->
		<!--<th>Semester</th>-->
		<!--<th>MonthYear</th>-->
		<!--<th>Transferred To Yet To Learn Courses</th>-->
	</tr>
	<?php for($i=1; $i<=$semester_to_process_data; $i++) { ?>
	<tr>
		<td>
			<?php echo $i; ?>
		</td>
		<td><?php //pr($commonPassArray[$i]); 
		if (isset($commonPassArray[$i]) && count($commonPassArray[$i]) > 0) { 
				foreach ($commonPassArray[$i] as $common_pass_cm_id => $cp_course_code) {
					echo $cp_course_code."</br>";
					echo $this->Form->input('common_pass_cm_id', array('type'=>'hidden', 'label'=>false, 'value'=>$commonPassMappingArray[$common_pass_cm_id], 'style'=>'margin-left:5px;', 'name'=>'data[MapStudents][CommonPass]['.$i.']['.$common_pass_cm_id.']'));
				}
				
				if (isset($NewMonthYearId[$i-1]) && $NewMonthYearId[$i-1] > 0) {
					$defaultSem = $NewMonthYearId; 
				}
				else $defaultSem = "";
				echo $this->Form->input('cp_my_id', array('type'=>'select', 'name'=>'data[MapStudents][cp_my_id]['.$i.']', 'options'=>$month_years, 'empty'=>'--Select--', 'label'=>false, 'default'=>$defaultSem));
				$defaultSem="";
				
		}
		?></td>
		<td><?php //pr($commonFailArray[$i]); 
		if (isset($commonFailArray[$i]) && count($commonFailArray[$i]) > 0) {
				foreach ($commonFailArray[$i] as $common_fail_cm_id => $cf_course_code) {
					echo $cf_course_code."</br>";
					echo $this->Form->input('common_fail_cm_id', array('type'=>'hidden', 'label'=>false, 'value'=>$commonFailMappingArray[$common_fail_cm_id], 'style'=>'margin-left:5px;', 'name'=>'data[MapStudents][CommonFail]['.$i.']['.$common_fail_cm_id.']'));
				}
				if (isset($NewMonthYearId[$i-1]) && $NewMonthYearId[$i-1] > 0) {
					$defaultSem = $NewMonthYearId; 
				}
				else $defaultSem = "";
				echo $this->Form->input('cf_my_id', array('type'=>'select', 'name'=>'data[MapStudents][cf_my_id]['.$i.']', 'options'=>$month_years, 'empty'=>'--Select--', 'label'=>false, 'default'=>$defaultSem));
				$defaultSem="";
		}
		?></td>
		<td><?php //pr($unCommonPassArray[$i]); 
		if (isset($unCommonPassArray[$i]) && count($unCommonPassArray[$i]) > 0) {
				foreach ($unCommonPassArray[$i] as $uncommon_pass_cm_id => $ucp_course_code) {
				$checked="";
				if (isset($ucp)) {
					$haystack = array_keys($ucp);
					if (in_array($uncommon_pass_cm_id, $haystack)) {
						$checked = "checked";
					}
					else {
						$checked = "";
					}
				}
				
				//pr($ucpeq);
				//pr($ucpeq);
				if (isset($ucpeq)) {
					$haystack = array_keys($ucpeq);
					//echo $uncommon_pass_cm_id."***".pr($haystack);
					//pr($haystack);
					if (in_array($uncommon_pass_cm_id, $haystack)) {
						$default = $ucpeq[$uncommon_pass_cm_id];
					}
					else {
						$default = "";
					}
				}
				else {
					$default = "";
				}
				
				 //echo $ucpeq[$uncommon_pass_cm_id];
					echo $this->Form->input('uncommon_pass_cm_id_checkbox', array('type'=>'checkbox', 'class'=>'StudentUncommonPassCmIdCheckbox', 'name'=>'data[MapStudents][UnCommonPassCheckbox]['.$i.']['.$uncommon_pass_cm_id.']', 'label'=>false, $checked))." ".$ucp_course_code." ";
					//pr($transferToUnCommonArray[$i]);
					//removed and placed at last columnu
					//echo $this->Form->input('uncommon_pass_cm_id', array('type'=>'hidden', 'label'=>false, 'value'=>$uncommon_pass_cm_id, 'style'=>'margin-left:5px;', 'name'=>'data[MapStudents][UnCommonPass]['.$i.']['.$uncommon_pass_cm_id.']'));
					echo "</br>";
				}
				if (isset($NewMonthYearId[$i-1]) && $NewMonthYearId[$i-1] > 0) {
					$defaultSem = $NewMonthYearId; 
				}
				else $defaultSem = "";
				echo $this->Form->input('ucp_my_id', array('type'=>'select', 'name'=>'data[MapStudents][ucp_my_id]['.$i.']', 'options'=>$month_years, 'empty'=>'--Select--', 'label'=>false, 'default'=>$defaultSem));
				$defaultSem="";
		}
		?></td>
		<!--<td><?php //pr($unCommonFailArray[$i]); 
		if (isset($unCommonFailArray[$i]) && count($unCommonFailArray[$i]) > 0) {
				foreach ($unCommonFailArray[$i] as $uncommon_fail_cm_id => $ucf_course_code) {
				$checked="";
				//pr($ucf);
				//echo $uncommon_fail_cm_id;
				if (isset($ucf)) {
					$haystack = array_keys($ucf);
					//pr($haystack);
					if (in_array($uncommon_fail_cm_id, $haystack)) {
						$checked = "checked";
					}
					else {
						$checked = "";
					}
				}
				//pr($ucfeq);
				$haystack = array_keys($ucfeq);
				//echo $uncommon_fail_cm_id."***".pr($haystack);
				//pr($haystack);
				if (in_array($uncommon_fail_cm_id, $haystack)) {
					$default = $ucfeq[$uncommon_fail_cm_id];
				}
				else {
					$default = "";
				}
				 //echo $ucpeq[$uncommon_pass_cm_id];
					echo $this->Form->input('uncommon_fail_cm_id_checkbox', array('type'=>'checkbox', 'class'=>'StudentUncommonFailCmIdCheckbox', 'name'=>'data[MapStudents][UnCommonFailCheckbox]['.$uncommon_fail_cm_id.']', 'label'=>false, $checked))." ".$ucf_course_code."</br>";
					//pr($transferToUnCommonArray[$i]);
					echo $this->Form->input('uncommon_fail_cm_id_select', array('type'=>'select', 'name'=>'data[MapStudents][UnCommonFailSelect]['.$uncommon_fail_cm_id.']', 'options'=>$transferToUnCommonArray[$i], 'empty'=>'--Select--', 'label'=>false, 'default'=>$default));
					//echo $this->Form->input('uncommon_fail_cm_id', array('type'=>'hidden', 'label'=>false, 'value'=>$uncommon_fail_cm_id, 'style'=>'margin-left:5px;', 'name'=>'data[MapStudents][UnCommonFail]['.$uncommon_fail_cm_id.']'));
					echo "</br>";
				}
				if (isset($NewMonthYearId[$i-1]) && $NewMonthYearId[$i-1] > 0) {
					$defaultSem = $NewMonthYearId; 
				}
				else $defaultSem = "";
				echo $this->Form->input('ucf_my_id', array('type'=>'select', 'name'=>'data[MapStudents][ucf_my_id][]', 'options'=>$month_years, 'empty'=>'--Select--', 'label'=>false, 'default'=>$defaultSem));
				$defaultSem="";
		}
		?></td>-->
		<!--<td>
			<?php
				/*if (isset($NewMonthYearId[$i-1]) && $NewMonthYearId[$i-1] > 0) {
					$defaultSem = $NewMonthYearId; 
				}
				else $defaultSem = "";
				echo $this->Form->input('semester_id', array('type'=>'select', 'name'=>'data[MapStudents][semester][]', 'options'=>$month_years, 'empty'=>'--Select--', 'label'=>false, 'default'=>$defaultSem));
				$defaultSem="";*/
			?>
		</td>-->
		<!--<td>
			<?php
				/*if (isset($NewMonthYearId[$i-1]) && $NewMonthYearId[$i-1] > 0) {
					$defaultMYId = $NewMonthYearId; 
				}
				else $defaultMYId = "";
				echo "NMY ".$defaultMYId;
				echo $this->Form->input('monthyears', array('type'=>'select', 'name'=>'data[MapStudents][MonthYearId][]', 'empty'=>'--MonthYear--', 'label'=>false, 'options'=>$monthyears, 'default'=>$defaultMYId));
				*/
			?>
		</td>-->
		<!--<td>
			<?php
			//pr($transferToUnCommonArray[$i]);
			foreach ($transferToUnCommonArray[$i] as $new_cm_id => $new_course_code) {
				$checked="";
				echo $this->Form->input('new_cm_id_checkbox', array('type'=>'checkbox', 'class'=>'StudentNewCmIdCheckbox', 'name'=>'data[MapStudents][New]['.$new_cm_id.']', 'label'=>false))." ".$new_course_code."</br>";
			}
			echo $this->Form->input('new_my_id', array('type'=>'select', 'name'=>'data[MapStudents][new_my_id]', 'options'=>$month_years, 'empty'=>'--Select--', 'label'=>false));
			?>
		</td>-->
	</tr>
	<?php } ?>
</table>
<div style='float:right;margin-top:10px;'>
<?php echo $this->Form->end(__('Submit')); ?>
</div>
<?php echo $this->Html->script('common'); ?>
<script>
$('.input label').addClass('col-sm-5 control-label no-padding-right');
$('.radio label').removeClass('col-sm-5 control-label no-padding-right');
leftMenuSelection('Students/commonCourses');

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