<div class="col-sm-6">
 	Course
</div>
<div class="col-sm-6">
	<?php
	//pr($cmResults);
	$i=1;
	echo "<table border='1' width='100%'>";
	echo "<tr>";
		echo "<td>Course Code</td>";
		//echo "<td>Month Year</td>";
		echo "<td>MonthYear</td>";
		echo "<td>CAE</td>";
	echo "<tr>";
	
	foreach ($cmResults as $key => $result) {
	$cae_mark="";$ie_id ="";$cae_mark="";
	$course_type_id = $result['Course']['course_type_id'];
	//pr($result['InternalExam']);
	//pr($result);
	//pr($myResults);
	if(isset($result['CourseStudentMapping'][0]['course_mapping_id'])) {
		$csm_id = $result['CourseStudentMapping'][0]['id'];
		//$my_id = $result['CourseStudentMapping'][0]['month_year_id'];
		$new_semester_id = $result['CourseStudentMapping'][0]['new_semester_id'];
		$checked="checked";
		//echo $my_id."---".$new_semester_id;
	}
	else {
		$csm_id = "";
		//$my_id = "";
		$new_semester_id = $new_month_year;
		$checked="";
	}
	$cm_id = $result['CourseMapping']['id'];
	SWITCH ($course_type_id) {
		case 1:
			$cae_id = $result['CourseMapping']['id'];
			if(isset($result['InternalExam'][0]['course_mapping_id'])) {
				$ie_id = $result['InternalExam'][0]['id'];
				$cae_mark = $result['InternalExam'][0]['marks'];
			}
			break;
		case 2:
		case 6:
			$cae_id = $result['CaePractical'][0]['id'];
			if(isset($result['CaePractical'][0]['course_mapping_id']) && isset($result['CaePractical'][0]['InternalPractical'][0]['id'])) {
				$ie_id = $result['CaePractical'][0]['InternalPractical'][0]['id'];
				$cae_mark = $result['CaePractical'][0]['InternalPractical'][0]['marks'];
			}
			break;
		case 3:
			break;
		case 4:
			$cae_id = $result['CaeProject'][0]['id'];
			if(isset($result['CaeProject'][0]['course_mapping_id']) && isset($result['CaeProject'][0]['InternalProject'][0]['id'])) {
				$ie_id = $result['CaeProject'][0]['InternalProject'][0]['id'];
				$cae_mark = $result['CaeProject'][0]['InternalProject'][0]['marks'];
			}
			break;
		default:
			$ie_id = "";
			$cae_mark = "";
			break;
	}
	
		$options[$cm_id]=$result['Course']['course_code'];
		echo "<tr>";
		echo "<td style='margin-left:-350px;'>".$this->Form->input('checkbox'.$i, array('type'=>'checkbox','label'=>$result['Course']['course_code'], 'name'=>'data[Student][Course]['.$cm_id.']', 'checked'=>$checked))."</td>";
		echo "<td>".$this->Form->input('month_year_id', array('label' => false, 'style'=>'width:100px;', 'default'=>$new_semester_id, 'empty' => __("----- Select MonthYear-----"), 'options'=>$myResults, 'name'=>'data[Student][my_id]['.$cm_id.']'))."</td>";
		//echo "<td>".$this->Form->input('new_semester_id', array('label' => false, 'style'=>'width:100px;', 'default'=>$new_semester_id, 'empty' => __("----- Select Semester-----"), 'options'=>$semResults, 'name'=>'data[Student][new_semester_id]['.$cm_id.']'))." ".
		echo $this->Form->input('course_number', array( 'type'=>'hidden', 'label' => false, 'style'=>'width:100px;', 'default'=>$result['CourseMapping']['course_number'], 'name'=>'data[Student][course_no]['.$cm_id.']')).
		" ".$this->Form->input('csm_id', array( 'type'=>'hidden', 'label' => false, 'style'=>'width:100px;', 'default'=>$csm_id, 'name'=>'data[Student][csm_id]['.$cm_id.']')).
		" ".$this->Form->input('internal_exam_id', array( 'type'=>'hidden', 'label' => false, 'style'=>'width:100px;', 'default'=>$ie_id, 'name'=>'data[Student][internal_exam_id]['.$cm_id.']')).
		" ".$this->Form->input('cae_id', array( 'type'=>'hidden', 'label' => false, 'style'=>'width:100px;', 'default'=>$cae_id, 'name'=>'data[Student][cae_id]['.$cm_id.']')).
		"</td>";
		echo "<td>".$this->Form->input('cae_mark', array('label' => false, 'style'=>'width:100px;', 'default'=>$cae_mark, 'name'=>'data[Student][cae_mark]['.$cm_id.']'))." ".
		$this->Form->input('course_type_id', array('label' => false, 'type'=>'hidden', 'style'=>'width:100px;', 'default'=>$course_type_id, 'name'=>'data[Student][course_type_id]['.$cm_id.']'))
		."</td>";
		echo "</tr>";
		//.
		//."</td></tr>"
		$i++;
	}
	echo "</table>";
	//pr($options);
	//echo $this->Form->input('cm_id', array('label' => false, 'empty' => __("----- Select Course-----"), 'options'=>$options, 'name'=>'data[Student][cm_id]', 'class'=>'js-lj-course'));
	 
	?>
</div>
<style>
.checkbox label {
//padding-left:250px;
margin-top:-10px;
}

</style>