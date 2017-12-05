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
				$numCaes = count($courseCAEs[$cmId]);
				//echo $numCaes;
				for($j=0; $j<$numCaes; $j++) {
					$caeId = $courseCAEs[$cmId][$j]['Cae']['id'];
					$caeApproval = $courseCAEs[$cmId][$j]['Cae']['approval_status'];
					if($caeApproval) {
						$caeApprovalStatus = "checked";
					}
					else {
						$caeApprovalStatus = "";
					}
					$cnt = $j+1;
					if (isset($crseAsstDetails[$cmId][$j]) && count($crseAsstDetails[$cmId][$j]) > 0 && $caeApproval) {
						//echo $this->Form->checkbox('caeApproval', array($caeApprovalStatus))."&nbsp;"; 
						echo "<span style='background-color:#ccc'>".$this->Html->link("CAE".$cnt."</i>", array("controller"=>"ContinuousAssessmentExams",'action' => 'viewCae', $batch_id, $academic_id, $program_id, $caeId),array('class' =>'js-test', 'title'=>'Edit','escape' => false))."</span>";
					} else {
						echo $this->Html->link("CAE".$cnt."</i>", array("controller"=>"ContinuousAssessmentExams",'action' => 'viewCae', $batch_id, $academic_id, $program_id, $caeId),array('class' =>'js-test', 'title'=>'Add','escape' => false));
					}
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