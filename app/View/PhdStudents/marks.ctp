<?php echo $this->Html->script('chosen.jquery');  ?>
<script type="text/javascript" language="javascript">
	jQuery(document).ready(function(){ jQuery(".chosen").data("placeholder","Select Frameworks...").chosen();});
</script>
	<div>
		<?php
		echo $this->Form->create('PhdCourse');
		
		echo $this->Form->input('phd_student_id', array('label'=>false, 'type'=>'hidden', 'value'=>$studentId, 'style'=>'width:75px;', 'name'=>"phd_student_id",'id'=>"phd_student_id"));
		$i = 1;
		if(isset($csmArray)){ 
		echo "<table style='width:80%;border:1px;' align='center' border='1'>";
		echo "<tr>";
			echo "<th style='text-align:center;width:40%;'>Course Name</th>";
			echo "<th style='text-align:center;'>MonthYear of Passing</th>";
			echo "<th style='text-align:center;'>Marks</th>";
			echo "<th style='text-align:center;'>Result</th>";
		echo "<tr>";
			foreach ($csmArray as $key => $value) {  //pr($value);
			$id = $value['id'];
			$monthYearId = '';  $marks = '-'; $status = ''; $smId='-';
			//echo $value['PhdCourse']['id'];
				echo "<tr>";
					$courseId = $value['phd_course_id'];
					
				if (isset($value['PhdStudentMark'][0])) {
					$smId = $value['PhdStudentMark'][0]['id'];
					$monthYearId = $value['PhdStudentMark'][0]['month_year_id'];
					$marks = $value['PhdStudentMark'][0]['marks'];
					$status = $value['PhdStudentMark'][0]['status'];
				}
				
				echo "<td style='height:40px;vertical-align:center;padding-left:10px;'>".$value['PhdCourse']['course_name']." ".$this->Form->input('course_id', array('label'=>false, 'type'=>'hidden', 'value'=>$courseId, 'style'=>'width:75px;', 'name'=>"course_id[]",'id'=>"course_id[]"))."</td>";
				
									
				echo "<td 'style=width:250px;'>".
					$this->Form->input('month_year_id', array('label' => false,
												'empty' => __("----- Select MonthYear-----"), 'options' => $monthyears, 
												'name'=>"MonthYear[]",'id'=>"MonthYear".$courseId, 'default'=>$monthYearId, 
												'style'=>'width: 200px;'))." ".
					"</td>";
				echo "<td 'style=width:100px;'>".$this->Form->input('marks', array('label'=>false, 'type'=>'text', 'value'=>$marks, 'style'=>'width:75px;', 'name'=>"marks[]",'id'=>"marks".$courseId, 'onkeyup'=>"computePhdIndividual('$studentId', '$courseId', this.value, '$marks', '$id', '$smId')"))."</td>";
				echo "<td 'style=width:100px;'>".$this->Form->input('status', array('label'=>false, 'type'=>'text', 'value'=>$status, 'style'=>'width:75px;', 'name'=>"status[]",'id'=>"status".$courseId, 'readonly'=>'readonly'))."</td>";
				echo "</tr>";
			}
		}
		echo "</table>";
		echo "<input type='hidden' name='CourseTotal' id='CourseTotal' value='$key' size='2'>";
		echo $this->Form->button('<i class="ace-icon fa fa-check bigger-110"></i>'.__('Submit'),array('style'=>'float:right;','type'=>'submit','name'=>'Save','value'=>'Course','class'=>'btn'));
		echo $this->Form->end();
		?>
	</div>
<?php echo $this->Html->script('common'); ?>
<?php echo $this->Html->script('common-front'); ?>

<script>
leftMenuSelection('PhdStudents/searchIndex');
</script>

<span class='breadcrumb1'>
<span class='navbar-brand'><small>PHD STUDENTS <i class='ace-icon fa fa-angle-double-right'></i></small></span>
<?php echo $this->Html->link("<span class='navbar-brand'><small> MARKS <i class='ace-icon fa fa-angle-double-right'></i></small></span>", array("controller"=>"PhdStudents",'action' => 'marks',$regNo),array('data-placement'=>'left','escape' => false)); ?>
</span> 