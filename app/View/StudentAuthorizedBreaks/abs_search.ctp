<?php if (isset($results) && count($results) > 0) {
$abs_details = $results['StudentAuthorizedBreak'];
//pr($abs_details);
//pr($month_years); ?>
<div class="col-sm-8" align="left">
<?php echo $this->Form->button('<i class="ace-icon fa fa-check bigger-110"></i>'.__('Submit'),array('type'=>'submit','name'=>'submit','value'=>'submit', 'class'=>'btn','style'=>'float:left;', 'onclick'=>'return validateAbs();')); ?>
</div>
<?php
	echo "</br>";
	echo "<table width='60%' border='1' style='margin-top:20px;'>";
	echo "<tr width='20%'>";
		echo "<th style='text-align:center;'>Semester</th>";
		echo "<th style='text-align:center;'>(Check the box if ABS is availed for the semester)</th>";
		echo "<th style='text-align:center;'>MonthYear</th>";
	echo "</tr>";
	$total_semesters = $results['Program']['Semester']['semester_name'];
	$i=1;
	echo $this->Form->input('student_id', array('type'=> 'hidden', 'label'=>false, 'default'=>$student_id, 'name' => 'data[ABS][student_id]', 'readonly'));
	echo $this->Form->input('semester_id', array('type'=> 'hidden', 'label'=>false, 'default'=>$total_semesters, 'name' => 'data[ABS][semester_id]', 'readonly'));
	for ($i=1; $i<=$total_semesters; $i++) {
	$checked='';
	$new_month_year_id='';
		foreach ($abs_details as $key => $abs) {
			$abs_sem_id = $abs['semester_id'];
			if ($i == $abs_sem_id) {
				$checked = "checked";
				$new_month_year_id = $abs['new_month_year_id'];
				break;
			}
		}
			echo "<tr>";
			echo "<td style='text-align:center;'>".$i."</td>";
			echo "<td style='text-align:center;'>".$this->Form->input('checkbox'.$i, array('type'=>'checkbox','label'=>false, 'style'=>'text-align:center;margin-top:-13px;', 'value'=>$i, 'name'=>'data[ABS][Semester]['.$i.']', 'class'=>'a', 'checked'=>$checked, 'onclick'=>"checkPublishedData(this.value);"));
			echo "<div id=sem$i></div></td>";
			echo "<td style='text-align:center;'>".$this->Form->input('month_year_id'.$i, array('label' => false, 'empty' => __("----- Select MonthYear-----"), 'options'=>$month_years, 'name'=>'data[ABS][month_year_id]['.$i.']', 'default'=>$new_month_year_id))."</td>";
			echo "</tr>";
		
	}
	echo "</table>";
}
else {
	echo "Invalid registration number. Please try again.";
}
?>

<?php echo $this->Html->script('common'); ?>

<script>leftMenuSelection('AuthorizedBreaks/abs');</script>
<span class='breadcrumb1'>
<span class='navbar-brand'><small>Students <i class='ace-icon fa fa-angle-double-right'></i></small></span>
<?php echo $this->Html->link("<span class='navbar-brand'><small> ABS <i class='ace-icon fa fa-angle-double-right'></i></small></span>", array("controller"=>"AuthorizedBreaks",'action' => 'abs'),array('data-placement'=>'left','escape' => false)); ?>
</span>