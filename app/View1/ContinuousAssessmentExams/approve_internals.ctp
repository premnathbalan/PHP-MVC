<?php
//echo $batchId." ".$programId." ".$academicId." ".$cmId." ".$course_mode_id." ".$caeId."</br>";
echo "Batch : ".$batch_period." Academic : ".$academic_name." Program : ".$program_name." Course : ".$course_name." Course Code : ".$course_code." Course Type :  ".$course_type." Month Year : ".$month_year." Assessment : ".$caeAssessmentNumber;
echo $this->Form->create('CAE');
echo $this->Form->input('cae_id', array('label' => false, 'type' => 'hidden', 'default' => $caeId));
//echo $this->Form->input('course_mapping_id', array('label' => false, 'type' => 'hidden', 'default' => $cmId));
//pr($caeDetails);
//pr($stuList);
//echo $approvalStatus;
?>

<table cellpadding="0" cellspacing="0" border="0" class="display" id="example" style="margin-top:10px;">
			<thead>
				<tr>
					<th>Sl.&nbsp;No.</th>
					<th>Register&nbsp;</th>
					<th>Student&nbsp;</th>
					<th>Marks</th>
				</tr>
			</thead>
			<tbody>
				<?php if (isset($caeDetails)) { 
				
				$i = 0;
				for($j=0; $j<count($caeDetails); $j++) { 
				$student_id = $caeDetails[$j]['continuous_assessment_exams']['student_id'];
				$marks = $caeDetails[$j]['continuous_assessment_exams']['marks'];
				$id = $caeDetails[$j]['continuous_assessment_exams']['id'];
				//echo $student_id;
				?>
				<tr class=" gradeX">
					
					<td><?php echo $j+1; ?></td>
					<td><?php echo $stuList[$j]['Student']['registration_number']; ?></td>
					<td><?php echo $stuList[$j]['Student']['first_name']." ".$stuList[$j]['Student']['last_name']; ?></td>
					<td><?php 
								echo $this->Form->input('student_id', array('type'=>'hidden', 'label' => false, 'default' => $student_id, 'name'=>'data[CAE][student_id][]'));
								echo $this->Form->input('id', array('type'=>'hidden', 'label' => false, 'default' => $id, 'name'=>'data[CAE][id][]'));
								echo $marks;
						?></td>
				</tr>
				  <?php }
				  }?>
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
