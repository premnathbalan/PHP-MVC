<?php
//pr($course);
//pr($courseCAEs);
//pr($crseAsstDetails);
//pr($numOfCAEs);
//echo $batch_id." ".$program_id." ".$academic_id;
?>
<table cellpadding="0" cellspacing="0" border="0" class="display" id="example" style="margin-top:10px;">
	<thead>
		<tr>
			<th>CMID</th>
			<th>CourseName</th>
			<th>NumberOfCAEs</th>
		</tr>
	</thead>
	<tbody>
	<?php
	foreach ($course as $cmId => $courseName) {
	//echo $cmId." ".$courseName."</br>";
	?>
		<tr>
			<td><?php echo $cmId; ?></td>
			<td><?php echo $courseName; ?></td>
			<td><?php 
				$numCaes = $courseCAEs[$cmId];
				for($j=1; $j<=count($numCaes); $j++) {
					$caeId = $courseCAEs[$cmId][$j];
				if (isset($crseAsstDetails[$cmId][$j]) && count($crseAsstDetails[$cmId][$j]) > 0) {
					echo "<span style='background-color:#ccc'>".$this->Html->link("CAE".$j."</i>", array("controller"=>"ContinuousAssessmentExams",'action' => 'editStudents', $batch_id, $academic_id, $program_id, $caeId),array('class' =>'js-test', 'title'=>'Edit','escape' => false))."</span>";
				} else {
					echo $this->Html->link("CAE".$j."</i>", array("controller"=>"ContinuousAssessmentExams",'action' => 'getStudents', $batch_id, $academic_id, $program_id, $caeId),array('class' =>'js-test', 'title'=>'Add','escape' => false));
				}
				//echo count($crseAsstDetails[$cmId][$j]);
					//echo "CAE_".$j."  ";
					//echo $this->Html->link("<i class='fa fa-eye fa-lg'></i>", array("controller"=>"ContinuousAssessmentExam",'action' => 'getStudents',$cmId, array('class' => 'js-cae-get-students', 'escape' => false, 'title'=>'View'));
					
					
					echo "&nbsp;&nbsp;&nbsp;";
				} 
				?></td>
		</tr>
		<?php } ?>
	<tbody>
	<tfoot>
		<tr>
			<th>CMID</th>
			<th>CourseName</th>
			<th></th>
		</tr>
	</tfoot>
</table>
<?php
//echo $this->Form->input('course_mapping_id', array('type' => 'select', 'empty' => __("----- Select Course-----"), 'label' => false, 'class' => 'js-course1'));
?>
<?php echo $this->Html->script('common'); ?>