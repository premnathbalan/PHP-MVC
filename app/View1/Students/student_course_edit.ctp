<?php echo $this->element('student_view',array("studentId"=>$studentId)); ?>	
<?php echo $this->render('../Students/info',false); ?>
<div style="clear:both;"></div>
<div>
<?php echo "No. of Semesters : ".$totalSemesters; ?>
<div id="accordion">
<?php 
for ($i=1; $i<= $totalSemesters; $i++) {
	echo "<h3>Semester $i</h3><div>";
	?>
	<div style="height:200px;">
	<?php 
		foreach ($arrValues as $key => $value) { 
			if($key === $i) {
				$p = 1;
				foreach ($value as $k => $v) {
					echo "<div class='col-sm-6 h60'>";					
					$id = $v['CM']['id'];
					if(strstr($v['CModes']['course_mode'], 'Electives', true)){
						echo "<div class='col-sm-5' style='font-weight:normal;'><i>".$p.". ".$v['CModes']['course_mode']."</i></div>";
						
						$courseKeyArray = explode(",",$v[0]['course']);
						$courseValArray = explode(",",$v[0]['courseName']);
						
						$courseArray = array();
						for($z=0;$z<count($courseKeyArray);$z++){
							$courseArray[$courseKeyArray[$z]]= $courseValArray[$z];
						}
						
						//for course default selected
						$courseDefault = ""; 
						if($v['CSM']['CSM_courseMId']){$courseDefault = $this->Html->getCourseNameFromMapping($stuId,$v['CM']['id'],$i,$v[0]['course'],2);}
						
						//for lecture default selected
						$lectureDefault = "";
						if($v['CSM']['CSM_lectureId']){ $lectureDefault = $this->Html->getLecturerNameFromMapping($stuId,$v['CM']['id'],$i,$v[0]['course'],2);}
						
						echo "<div class='col-sm-3'>";
						echo $this->Form->input('course_mapping_id', array('options' => $courseArray,'default'=>$courseDefault, 'type' => 'select', 'empty' => __("- Course -"), 'label' => false,'style'=>'width:130px;', 'onchange'=>"saveStudentCM(".$studentId.",this.value,document.getElementById('lecturer_id').value,".$v['CM']['course_number'].",".$v['CM']['semester_id'].");"));
						echo "</div>";
						
						echo "<div class='col-sm-3'>";
						echo $this->Form->input('lecturer_id', array('options' => $facultyArray,'default'=>$lectureDefault, 'type' => 'select', 'empty' => __("- Lecturer -"), 'label' => false,'style'=>'width:150px;', 'onchange'=>"saveStudentCM(".$studentId.",document.getElementById('course_mapping_id').value,this.value,".$v['CM']['course_number'].",".$v['CM']['semester_id'].");"));
						echo "</div>";
					}
					else{
						echo "<div class='col-sm-5'><i>".$p.". ".$v['CModes']['course_mode']."</i></div>";
						echo "<div class='col-sm-6'>".$v['Courses']['course_name']."</div>";
					}
					echo "</div>";$p++;
				}
				
			}
			
		}
		
	?>
	</div></div>
	<?php
}
?>
</div>

<script> $(function() {   $( "#accordion" ).accordion(); });</script>
<?php echo $this->Html->script('common');echo $this->Html->script('common-front');?>

<span class='breadcrumb1'>
<span class='navbar-brand'><small>STUDENTS <i class='ace-icon fa fa-angle-double-right'></i></small></span>
<?php echo $this->Html->link("<span class='navbar-brand'><small> LIST <i class='ace-icon fa fa-angle-double-right'></i></small></span>", array("controller"=>"students",'action' => 'student_search'),array('data-placement'=>'left','escape' => false)); ?>
<?php echo $this->Html->link("<span class='navbar-brand'><small> MANAGE COURSES ( $studentId ) <i class='ace-icon fa fa-angle-double-right'></i></small></span>", array("controller"=>"Students",'action' => 'manage_courses',$studentId),array('data-placement'=>'left','escape' => false)); ?>
</span>