<div class="professionalTrainings form">
<div class="searchFrm bgFrame1">
	<div class="col-sm-12" >
			<div class="col-lg-4">
				<?php echo "<b>Batch : </b>".$professionalTrainings[0]['CourseMapping']['Batch']['batch_from']."-".
				$professionalTrainings[0]['CourseMapping']['Batch']['batch_to']." ".
				$professionalTrainings[0]['CourseMapping']['Batch']['academic']; ?>
			</div>
			<div class="col-lg-4">
				<?php echo "<b>Academic : </b>".$professionalTrainings[0]['CourseMapping']['Program']['Academic']['academic_name']; ?>
			</div>
			<div class="col-lg-4">
				<?php echo "<b>Program : </b>".$professionalTrainings[0]['CourseMapping']['Program']['program_name']; ?>
			</div>
	</div>
	<div class=" col-sm-12" >
			<div class="col-lg-4">
				<?php echo "<b>Course : </b>".$professionalTrainings[0]['CourseMapping']['Course']['course_name']; ?>
			</div>
			<div class="col-lg-4">
				<?php echo "<b>Month Year : </b>".$professionalTrainings[0]['CourseMapping']['MonthYear']['Month']['month_name']."-".
				$professionalTrainings[0]['CourseMapping']['MonthYear']['year']; ?>
			</div>
			<div class="col-lg-4">
				<?php echo "<b>Max Marks : </b>".$professionalTrainings[0]['CourseMapping']['Course']['course_code']; ?>
			</div>
	</div>
	<div class=" col-sm-12" >
			<div class="col-lg-4">
				<?php echo "<b>Max Marks : </b>".$professionalTrainings[0]['CaePt']['marks']; ?>
			</div>
			<div class="col-lg-4">
			</div>
			<div class="col-lg-4">
				<?php //echo "<b>Max Marks : </b>".$professionalTrainings[0]['CaePt']['marks']; ?>
			</div>
	</div>
</div>
<?php echo $this->Form->create('ProfessionalTraining'); 
echo $this->Form->input('post_model', array('label' => false, 'type' => 'hidden', 'default' => 'CaePt'));
echo $this->Form->input('id', array('label' => false, 'type' => 'hidden', 'default' => $id));
?>
	<?php
		
		if (isset($professionalTrainings[0]['ProfessionalTraining']) && count($professionalTrainings[0]['ProfessionalTraining']) > 0) {
			$pt = $professionalTrainings[0]['ProfessionalTraining'];
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
			<?php
			$i=1;
			foreach ($pt as $key => $value) { 
				if (isset($value['Student']['id']) && $value['Student']['id'] > 0) {
				
					?>
					<tr class=" gradeX">
						<?php $pt_marks = $value['marks']; ?>
						<td><?php $student_id = $value['Student']['id']; echo $i; ?></td>
						<td><?php echo $value['Student']['registration_number']; ?></td>
						<td><?php echo $value['Student']['name']; ?></td>
						<td><?php echo $pt_marks; ?></td>
					</tr>
					<?php
				}
				$i++;
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
			if($professionalTrainings[0]['CaePt']['approval_status']) {
				echo $this->Form->button('<i class="ace-icon fa fa-check bigger-110"></i>'.__('Approved'),array('type'=>'button','name'=>'submit','value'=>'submit','class'=>'btn js-caept-approve', 'disabled'));
			}
			else {
				echo $this->Form->button('<i class="ace-icon fa fa-check bigger-110"></i>'.__('Approve'),array('type'=>'button','name'=>'submit','value'=>'submit','class'=>'btn js-caept-approve'));			
			}
		}
		else {
			echo "Student and courses not mapped";
		}
	?>
<?php echo $this->Form->end(__('Submit')); ?>
</div>
