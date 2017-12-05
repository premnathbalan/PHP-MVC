<?php
//echo $reg_number;
echo $this->Form->create('');
foreach ($results as $key => $result) {
	pr($result);
	$new_batch_id = $result['Student']['batch_id'];
	$new_batch = $result['Batch']['batch_from']." - ".$result['Batch']['batch_to']." ".$result['Batch']['academic'];
	$new_program_id = $result['Student']['program_id'];
	$new_program = $result['Program']['short_code'];
	$new_academic_id = $result['Student']['academic_id'];
	$new_academic = $result['Program']['Academic']['short_code'];
	$new_semester_id = $result['Student']['semester_id'];
	
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
	
}
		
//pr($results);
//pr($oldCourseMappingArray);
//pr($oldCourseMappingFailArray);
//pr($newCourseMappingArray);
//pr($previous_semesters);
//die;
//$result=array_intersect($oldCourseMappingArray,$newCourseMappingArray);
//pr($result);
//pr($oldCourseMappingFailArray);
foreach ($oldCourseMappingArray as $semester_id => $internalArray) {
	$common_courses[$semester_id] = array_intersect($oldCourseMappingArray[$semester_id],$newCourseMappingArray[$semester_id]);
	$oldDiff[$semester_id] = array_diff($oldCourseMappingArray[$semester_id],$newCourseMappingArray[$semester_id]);
	$newDiff[$semester_id] = array_diff($newCourseMappingArray[$semester_id],$oldCourseMappingArray[$semester_id]);
	//$newDiff[$semester_id] = array_diff($newCourseMappingArray[$semester_id],$oldCourseMappingFailArray[$semester_id]);
}

foreach ($oldCourseMappingFailArray as $semester_id => $internalArray) {
	$discarded_courses[$semester_id] = array_intersect($oldCourseMappingFailArray[$semester_id],$newCourseMappingArray[$semester_id]);
	$toBeAddedNewCourses[$semester_id] = array_diff($oldCourseMappingFailArray[$semester_id],$discarded_courses[$semester_id]);
	//pr($discarded_courses);
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
	if (isset($common_courses) && count($common_courses)>0) {
	?>
<table style="width:100%;align:center;border-color:#000;font-weight:14px;margin-top:10px;" border="1" class="display tblOddEven">
	<tr>
		<th>Semester</th>
		<th>
			<?php echo "Common Passed Courses"; ?>
		</th>
		<th>Common Failed Courses</th>
		<th>Failed Courses</th>
		<th>Equivalent Courses</th>
	</tr>
	<?php
	foreach ($common_courses as $semester_id => $courses) { ?>
	<tr>
		<td width='10%'><?php echo $semester_id; ?></td>
		<td width='18%'>
			<?php //pr($common_courses[$semester_id]); 
				foreach ($common_courses[$semester_id] as $common_cm_id => $common_course_code) {
					echo "</br>".$this->Form->input('checkbox', array('type'=>'checkbox', 'checked'=>'checked', 'readonly'=>'readonly', 'value'=>$common_course_code, 'label'=>$common_course_code, 'style'=>'margin-left:5px;', 'name'=>'data[MapStudents][CommonCourses]['.$common_cm_id.']'));
				}
			?>
		</td>
		<td width='18%'>
			<?php //pr($common_courses[$semester_id]); 
			if (isset($discarded_courses[$semester_id]) && count($discarded_courses[$semester_id])>0) {
				foreach ($discarded_courses[$semester_id] as $common_cm_id => $common_course_code) {
					echo "</br>".$this->Form->input('checkboxfail', array('type'=>'checkbox', 'checked'=>'checked', 'readonly'=>'readonly', 'value'=>$common_course_code, 'label'=>$common_course_code, 'style'=>'margin-left:5px;', 'name'=>'data[MapStudents][CommonCoursesFail]['.$common_cm_id.']'));
				}
			}
			?>
		</td>
		<td>
			<?php
			//pr($toBeAddedNewCourses); 
			if (isset($toBeAddedNewCourses[$semester_id]) && count($toBeAddedNewCourses[$semester_id]) > 0) { 
				foreach ($toBeAddedNewCourses[$semester_id] as $to_be_added_cm_id => $to_be_added_course_code) {
					echo $to_be_added_course_code."</br>";
					echo $this->Form->input('textbox', array('type'=>'hidden', 'value'=>$to_be_added_cm_id, 'style'=>'margin-left:5px;', 'name'=>'data[MapStudents][IgnoreCourses]['.$to_be_added_cm_id.']'));
				}
			}
			?>
		</td>
		<?php
			$old_courses = $oldDiff[$semester_id];
			$new_courses = $newDiff[$semester_id];
			if (isset($oldCourseMappingFailArray) && count($oldCourseMappingFailArray)>0) {
				$newDiffCourses = array_diff($new_courses,$oldCourseMappingFailArray[$semester_id]);
			}
			//pr($newDiffCourses);
		?>
		<td>
			<?php
				echo $this->Form->input('parent_id', array('type'=> 'hidden', 'default' => $parent_id, 'label'=>false, 'name' =>  'data[MapStudents][parent_id]'));
				$options_array = array();
				foreach ($new_courses as $new_cm_id => $new_course_code) {
						$options_array[$new_cm_id] = $new_course_code;
					}
					//pr($options_array);
				$i=1;
				foreach ($old_courses as $old_cm_id => $old_course_code) {
				//echo $old_cm_id." ".$old_course_code."---";
				$eq_cm_id = $oldCourseMappingDetailsArray[$semester_id][$old_cm_id]['eq_cm_id'];
				$j=1;
					echo $this->Form->input('textbox', array('type'=>'hidden', 'value'=>$old_cm_id, 'style'=>'margin-left:5px;', 'name'=>'data[MapStudents][AddCourses]['.$old_cm_id.']'));
					echo $old_course_code;
					echo "</br>";
					//echo $this->Form->radio('course_mapping_id', $options_array, array('legend' => false));
					if (isset($newDiffCourses) && count($newDiffCourses) > 0) {
						foreach ($newDiffCourses as $new_cm_id => $new_course_code) {
							//working code
							$checked="";
							if ($new_cm_id==$eq_cm_id) $checked="checked";
							//echo $new_cm_id." ".$eq_cm_id;
							//echo $this->Form->radio('course_mapping_id'.$new_cm_id, array('label'=>$new_course_code), array('value'=>$new_course_code, 'name'=>'data[row-'.$i.']', 'data-col'=>$j));
							$options = array($new_course_code => $new_course_code);
							$attributes = array('legend'=>false, 'after'=>$new_course_code, 'name'=>'data[row-'.$i.']', 'data-col'=>$j, 'onclick'=>"testing($new_cm_id, $old_cm_id)", $checked);
							//echo $this->Form->radio('course_mapping_id'.$new_cm_id, array('label'=>$new_course_code), array('label'=>$new_course_code, 'name'=>'data[row-'.$i.']', 'data-col'=>$j, 'onclick'=>"testing($new_cm_id, $old_cm_id)"));
							echo $this->Form->radio('course_mapping_id'.$new_cm_id, $options, $attributes);
							$j++;
						}
					}
					//echo $this->Form->input('semester_id', array('type'=>'select', 'name'=>'data[MapStudents][Semesters]['.$old_cm_id.']', 'options'=>array("1"=>"1","2"=>"2","3"=>"3","4"=>"4","5"=>"5")));
					echo "</br>";
					//echo $this->Form->input('month_year_id', array('type'=>'select', 'name'=>'data[MapStudents][MonthYear]['.$old_cm_id.']', 'options'=>$monthyears));
					echo '<div id="hdnEquivalent'.$old_cm_id.'"></div>';					
					$i++;
				}
			?>
		</td>
	</tr>
	<?php }
 ?>
</table>
<div style='float:right;margin-top:10px;'>
<?php echo $this->Form->end(__('Submit')); ?>
</div>
<?php
	}
	else {
		echo "Failed in all courses";
	}
echo $this->Html->script('common'); ?>
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
<?php echo $this->Html->link("<span class='navbar-brand'><small> MAPPING Courses <i class='ace-icon fa fa-angle-double-right'></i></small></span>", array("controller"=>"Students",'action' => 'batchTransferSearch',$regNo),array('data-placement'=>'left','escape' => false)); ?>
</span>