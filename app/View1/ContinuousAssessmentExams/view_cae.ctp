<?php
//pr($results);
//echo $batchId." ".$programId." ".$academicId." ".$cmId." ".$course_mode_id." ".$caeId."</br>";
echo "Batch : ".$this->Html->getBatch($batchId)." Academic : ".$this->Html->getAcademic($academicId)."
Program : ".$this->Html->getProgram($programId)." Course : ".$this->Html->getCourseNameFromCmId($cmId)." 
Course Code : ".$this->Html->getCourseCode($cmId)." 
Month Year : ".$month_year." Assessment : ".$caeNumber;
echo $this->Form->create('Cae');
echo $this->Form->input('id', array('label' => false, 'type' => 'hidden', 'default' => $caeId));
echo $this->Form->input('post_model', array('label' => false, 'type' => 'hidden', 'default' => $currentModel));
//echo $this->Form->input('course_mapping_id', array('label' => false, 'type' => 'hidden', 'default' => $cmId));
//echo $approvalStatus;
?>

<table cellpadding="0" cellspacing="0" border="0" class="display" id="example" style="margin-top:10px;">
			<thead>
				<tr>
					<th>Register&nbsp;Number</th>
					<th>Student&nbsp;Name</th>
					<th>Marks</th>
				</tr>
			</thead>
			<tbody>
				<?php $i=1;
					foreach($results as $key => $result) { //pr($result);
					if ($result['Student']['id'] && $result['CourseStudentMapping']['indicator']==0) {
				?>
					<tr class='gradeX'>
						<!--<td><?php echo $i; ?></td>-->
						<td><?php echo $result['Student']['registration_number']; ?></td>
						<td><?php echo $result['Student']['name']; ?></td>
						<td><?php 
						if (isset($result['Student']['ContinuousAssessmentExam']) && count($result['Student']['ContinuousAssessmentExam'])>0) {
							$marks = $result['Student']['ContinuousAssessmentExam'][0]['marks'];
							//$publish_status = $result['Student']['StudentMark'][0]['marks'];
						}
						else { 
							$marks="";
							//$publish_status = 0;
						}
						$student_id = $result['Student']['id'];
						echo $marks;
						?>
						</td>
					</tr>
				<?php
					$i++;
					}
					}
				?>
			</tbody>
			<!--<tfoot>
				<tr>
					<th><input type="text" name="" value="Regn No." class="search_init" /></th>
					<th><input type="text" name="Registration Number" value="Registration Number" class="search_init" /></th>
					<th><input type="text" name="Academic Id" value="Academic Id" class="search_init" /></th>
					<th><input type="text" name="Program Id" value="Program Id" class="search_init" /></th>
				</tr>
			</tfoot>-->
		</table>
		<?php
		if($approvalStatus) {
			echo $this->Form->button('<i class="ace-icon fa fa-check bigger-110"></i>'.__('Approved'),array('type'=>'button','name'=>'submit','value'=>'submit','class'=>'btn js-cae-approve', 'disabled'));
		}
		else {
			echo $this->Form->button('<i class="ace-icon fa fa-check bigger-110"></i>'.__('Approve'),array('type'=>'button','name'=>'submit','value'=>'submit','class'=>'btn js-cae-approve'));			
		}
		echo $this->Form->end();
		?>

<span class='breadcrumb1'>
<span class='navbar-brand'><small>MARKS <i class='ace-icon fa fa-angle-double-right'></i></small></span>
<span class='navbar-brand'><small>C.A.E <i class='ace-icon fa fa-angle-double-right'></i></small></span>
<span class='navbar-brand'><small>View <i class='ace-icon fa fa-angle-double-right'></i></small></span>
<?php //echo $this->Html->link("<span class='navbar-brand'><small> View <i class='ace-icon fa fa-angle-double-right'></i></small></span>", array("controller"=>"ContinuousAssessmentExams",'action' => 'caeAssignment'/*, $course_type_id*/, $action),array('data-placement'=>'left','escape' => false)); ?>
</span>