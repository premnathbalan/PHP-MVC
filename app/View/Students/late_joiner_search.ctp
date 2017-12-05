<?php
$month_year_id = $results[0]['Student']['month_year_id'];
//pr($results);

//pr($maxMYResults);
//echo $month_year_joined;

//pr($cmResults);
?>

<div class="searchFrm bgFrame1" style="width:89%;">
	<?php echo $this->Form->create('Student');
	echo "<input type='hidden' name='data[Student][month_year_joined]' id='month_year_joined' value='$month_year_joined' size = '2' />";
	echo "<input type='hidden' name='data[Student][batch_id]' id='batch_id' value='$batch_id' size = '2' />"; 
	echo "<input type='hidden' name='data[Student][program_id]' id='program_id' value='$program_id' size = '2' />";
	echo "<input type='hidden' name='data[Student][student_id]' id='student_id' value='$student_id' size = '2' />";
	echo "<input type='hidden' name='data[Student][reg_num]' id='reg_num' value='$regNo' size = '2' />";
	?>
	<div class="col-sm-6">
		<?php //echo $this->Form->input('registration_number', array('label' => "Register Number<span class='ash'>*</span>", array("controller"=>"Students",'action'=>'lateJoiner'), 'type' => 'text','style'=>'margin-top:10px;','maxlength'=>10));?>
		<?php echo "Name";?>
	</div>
	<div class="col-sm-6">
		<?php echo $results[0]['Student']['name'];?>
	</div>
	
	<div class="col-sm-6">
	 	<?php echo "Batch";?>
	</div>
	<div class="col-sm-6">
		<?php echo $results[0]['Batch']['batch_from']."-".$results[0]['Batch']['batch_to']." ".$results[0]['Batch']['academic'];?>
	</div>
	 
	<div class="col-sm-6">
	 	<?php echo "Program";?>
	</div>
	<div class="col-sm-6">
	 	<?php echo $results[0]['Program']['Academic']['short_code'];?>
	</div>
	 
	<div class="col-sm-6">
	 	<?php echo "Specialisation";?>
	</div>
	<div class="col-sm-6">
	 	<?php echo $results[0]['Program']['short_code'];?>
	</div>
	
	<div class="col-sm-6">
	 	<?php echo "MonthYear";?>
	</div>
	<div class="col-sm-6">
		<?php
		/*for($i=1; $i<$month_year_joined; $i++) {
				$options[$i]=$i;
		}*/
		?>
	 	<?php echo $this->Form->input('month_year_id', array('label' => false, 'empty' => __("----- Select MonthYear-----"), 'options'=>$monthyears, 'class'=>'js-late-joiner', 'name'=>'data[Student][semester_id]')); ?>
	</div>
	<div class="col-sm-12">
	<div id="courseResult"></div>
	</div>
	
	<div class="col-sm-12">
	<div id="monthYearResult"></div>
	</div>
	
	<div class="col-sm-12">
	<div id="semesterResult"></div>
	</div>
	
	<?php echo $this->Form->button('<i class="ace-icon fa fa-check bigger-110"></i>'.__('Submit'),array('type'=>'submit','name'=>'submit','value'=>'submit','class'=>'btn'));?>
	<?php echo $this->Form->end(); ?>
</div>
