<?php if (isset($csmResults)) //pr($csmResults); ?>
<?php
$stu = $this->Html->getStudentInfo($student_id);
echo "<table border='1' width='100%' style='margin-top:15px;'>";
	echo "<tr>";
		echo "<th style='text-align: center;'>Reg.number</th>";
		echo "<th style='text-align: center;'>Name</th>";
		echo "<th style='text-align: center;'>Batch</th>";
		echo "<th style='text-align: center;'>Academic</th>";
		echo "<th style='text-align: center;'>Program</th>";
	echo "</tr>";
	echo "<tr>";
		echo "<td style='text-align: center;'>".$this->params['pass'][1]."</td>";
		echo "<td style='text-align: center;'>".$stu['Student']['name']."</td>";
		echo "<td style='text-align: center;'>".$this->Html->getBatch($batch_id)."</td>";
		echo "<td style='text-align: center;'>".$this->Html->getAcademicFromProgId($program_id)."</td>";
		echo "<td style='text-align: center;'>".$this->Html->getProgram($program_id)."</td>";
	echo "</tr>";
echo "</table>";
?>

<div class="col-sm-12">
<?php echo $this->Form->button('<i class="ace-icon fa fa-check bigger-110"></i>'.__('Submit'),array('type'=>'submit','name'=>'submit','value'=>'submit', 'class'=>'btn','style'=>'float:right;margin-bottom:15px;')); ?>
</div>

<?php
echo "<table border='1' width='100%' style='margin-top:15px;'>";
echo "<tr>";
echo "<th style='text-align: center;'>Sl.No.</th>";
echo "<th style='text-align: center;'>CourseCode</th>";
echo "<th style='text-align: center;'>CourseType</th>";
echo "<th style='text-align: center;'>CourseName</th>";
echo "<th style='text-align: center;'>Withdrawal</th>";
echo "<th style='text-align: center;'>MonthYear</th>";
echo "</tr>";
$i=1;
if (isset($csmResults)) {
echo $this->Form->input('student_id', array('type'=> 'hidden', 'label'=>false, 'default'=>$student_id, 'name' => 'data[Withdrawal][student_id]', 'readonly'));

//$semester_id = $csmResults[0]['CourseMapping']['semester_id']+1;
//$total_semesters = $csmResults[0]['CourseMapping']['Program']['semester_id'];
$j=1;
foreach ($csmResults as $key => $csmArray) { 
	if(isset($csmArray['CourseMapping']) && count($csmArray['CourseMapping'])>0 && isset($csmArray['CourseMapping']['id'])) {
		$csmDetails = $csmArray['CourseMapping'];
		$cm_id = $csmArray['CourseMapping']['id'];
		$course_code = $csmArray['CourseMapping']['Course']['course_code'];
		$course_name = $csmArray['CourseMapping']['Course']['course_name'];
		$type = $csmArray['CourseStudentMapping']['type'];
		
					echo "<tr>";
					echo "<td style='text-align:center;'>".$j."</td>";
					echo "<td style='text-align:center;'>".$course_code."</td>";
					echo "<td style='text-align:center;'>".$csmArray['CourseMapping']['Course']['CourseType']['course_type']."</td>";
					echo "<td style='text-align:center;'>".$course_name."</td>";
					echo "<td style='text-align:center;'>";
					if ($type == 'W') {
						$checked='checked';
						$new_semester_id = $csmArray['CourseStudentMapping']['new_semester_id'];
					} else {
						$checked='';
						$new_semester_id = '';
					}
					echo $this->Form->input('checkbox'.$cm_id, array('type'=>'checkbox','label'=>false, 'style'=>'text-align:center;margin-top:-15px;margin-left:5px;', 'name'=>'data[Withdrawal][CourseMapping]['.$cm_id.']', 'class'=>$csmArray['CourseMapping']['Course']['course_code'], $checked));
					echo "</td>";
					echo "<td style='text-align:center;'>";
						echo $this->Form->input('semester_id', array('label' => false, 'default'=>$new_semester_id, 'empty' => __("----- Select MonthYear-----"), 'options'=>$month_years, 'name'=>'data[Withdrawal][Semester]['.$cm_id.']'));
					echo "</td>";
					echo "</tr>";

		$i++; $j++;
		//echo "</br>";
	}
} 

}
else {
	echo "<tr><td colspan='6' style='text-align:center;font-weight:bold;color:red;'>".$errorMsg."</td></tr>";
}
echo "</table>";
?>

<?php echo $this->Html->script('common'); ?>

<script>leftMenuSelection('Students/withdrawal');</script>
<span class='breadcrumb1'>
<span class='navbar-brand'><small>Students <i class='ace-icon fa fa-angle-double-right'></i></small></span>
<?php echo $this->Html->link("<span class='navbar-brand'><small> Withdrawal <i class='ace-icon fa fa-angle-double-right'></i></small></span>", array("controller"=>"Students",'action' => 'withdrawal'),array('data-placement'=>'left','escape' => false)); ?>
</span>