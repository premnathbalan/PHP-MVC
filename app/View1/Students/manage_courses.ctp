<?php echo $this->element('student_view',array("studentId"=>$studentId)); ?>	
<?php echo $this->render('../Students/info',false); ?>
<div style="clear:both;"></div>
<div>

<div style="float:right;">
<?php echo $this->Html->link("<i class='fa fa-pencil fa-lg'></i> Edit Course", array("controller"=>"Students",'action' => 'student_course_edit',$studentId),array('title'=>'Edit Course','class'=>'btn','escape' => false,'target'=>'_blank','style'=>'margin-top:-10px;')); ?>
</div>

<legend><?php echo "No. of Semesters : ".$totalSemesters; ?></legend>

<?php 
echo "<table border='1' class='display tblOddEven' style='margin:5px;'>";
echo "<th>S.No.</th><th>Course Type</th><th>Course Name</th><th>Lecturer Name</th>";
for ($i=1; $i<= $totalSemesters; $i++) {
	echo "<tr><td colspan='4'><b>Semester $i</b></td></tr>";	
	?>
	<?php 
		foreach ($arrValues as $key => $value) { 
			if($key === $i) {
				$p = 1;
				foreach ($value as $k => $v) {
					echo "<tr>";
					echo "<td><i>".$p."</i></td>";					
					$id = $v['CM']['id'];
					if(strstr($v['CModes']['course_mode'], 'Electives', true)){						
						echo "<td>".$v['CModes']['course_mode']."</td>";						
						$courseKeyArray = explode(",",$v[0]['course']);
						$courseValArray = explode(",",$v[0]['courseName']);
						
						$courseArray = array();
						for($z=0;$z<count($courseKeyArray);$z++){
							$courseArray[$courseKeyArray[$z]]= $courseValArray[$z];
						}
					
						echo "<td>";
						//echo "-CM:".$v['CM']['id']." -STU:".$stuId." -SEM:".$i;
						echo $this->Html->getCourseNameFromMapping($stuId,$v['CM']['id'],$i,$v[0]['course'],1);
						echo "</td>";
						
						echo "<td>";
						echo $this->Html->getLecturerNameFromMapping($stuId,$v['CM']['id'],$i,$v[0]['course'],1);
						echo "</td>";
					}
					else{
						echo "<td>".$v['CModes']['course_mode']."</td>";
						echo "<td>".$v['Courses']['course_name']."</td>";
						echo "<td></td>";
					}
					echo "</tr>";$p++;
				}
				
			}
			
		}
		
	?>
	</div>
	<?php 
}echo "</table>";
?>
</div>

<script>
leftMenuSelection('Students/regNoSearch');
</script>  

<span class='breadcrumb1'>
<span class='navbar-brand'><small>STUDENTS <i class='ace-icon fa fa-angle-double-right'></i></small></span>
<?php echo $this->Html->link("<span class='navbar-brand'><small> LIST <i class='ace-icon fa fa-angle-double-right'></i></small></span>", array("controller"=>"students",'action' => 'studentSearch'),array('data-placement'=>'left','escape' => false)); ?>
<?php echo $this->Html->link("<span class='navbar-brand'><small> MANAGE COURSES ( $studentId ) <i class='ace-icon fa fa-angle-double-right'></i></small></span>", array("controller"=>"Students",'action' => 'manage_courses',$studentId),array('data-placement'=>'left','escape' => false)); ?>
</span>