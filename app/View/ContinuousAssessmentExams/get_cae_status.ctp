<?php
$editStatus = FALSE;
$approval = "Not Approved";
if($attendanceCount < $numOfCourses) {
	$boolAttendance = false;
	$attendanceLabel = "Not Entered";
} else {
	$boolAttendance = true;
	$attendanceLabel = "Entered";
}
if ($editCount == $totalAssessmentCount) {
	$editStatus = TRUE;
	$approval = "Approved";
}

	echo "<br/><div class='bgFrame1 col-sm-12'>";
		echo "<div class='col-sm-4'>";
			echo "<b>Assessment Marks : </b><span class='searchCae'>".$assessmentCount." of ".$totalAssessmentCount."</span></br>";
		echo "</div>";
		echo "<div class='col-sm-4'>";
			echo "<b>Attendance Marks : </b><span class='searchCae'>".$attendanceLabel."</span></br>";
		echo "</div>";
		echo "<div class='col-sm-4'>";
			echo "<b>Edit Status : </b><span class='searchCae'>".$approval."</span></br>";
		echo "</div>";
	echo "</div>";
		
	if (count($noCourseMappingArray) > 0) {	
	$i = 1;
	echo "<br/><br/><div class='bgFrame1 col-sm-12'>";
		echo "<b>CAEs not created for the following subjects :</b></br>";
		foreach ($noCourseMappingArray as $cmId => $courseCode) {
			echo $i.". ".$courseCode."</br>";
			$i++;
		}		
  	echo "</div>";
  	}
?>
</br>

<?php
//$disableCalculate = "";
if($assessmentCount < $totalAssessmentCount) {
	$boolAssessment = false;
} else {
	$boolAssessment = true;
}

if ($boolAssessment && $boolAttendance && $editStatus) {
	$disableCalculate="";
}
else {
	$disableCalculate = "disabled";
}

if (count($noCourseMappingArray) > 0) {
	$disableCalculate = "disabled";
}

echo $this->Form->button('<i class="ace-icon fa fa-check bigger-110"></i>'.__('Calculate'),array('type'=>'submit','name'=>'submit','value'=>'submit', $disableCalculate, 'class'=>'btn','style'=>'float:right;'));
?>
</div>
</div>
<?php echo $this->element('internalMarks'); ?>
<?php echo $this->Html->script('common'); ?>

<span class='breadcrumb1'>
<span class='navbar-brand'><small>MARKS <i class='ace-icon fa fa-angle-double-right'></i></small></span>
<span class='navbar-brand'><small>C.A.E <i class='ace-icon fa fa-angle-double-right'></i></small></span>
<?php echo $this->Html->link("<span class='navbar-brand'><small> Calculate C.A.E <i class='ace-icon fa fa-angle-double-right'></i></small></span>", array("controller"=>"ContinuousAssessmentExams",'action' => 'calculateCAEMarks'),array('data-placement'=>'left','escape' => false)); ?>
</span>	