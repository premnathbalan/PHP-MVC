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
<?php echo $this->Form->create('ProfessionalTraining'); ?>
	<?php
		
		if (isset($professionalTrainings[0]['CourseMapping']['CourseStudentMapping']) && 
		count($professionalTrainings[0]['CourseMapping']['CourseStudentMapping']) > 0) {
			$csm = $professionalTrainings[0]['CourseMapping']['CourseStudentMapping'];
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
			foreach ($csm as $key => $value) {
				if (isset($value['Student']['id']) && $value['Student']['id'] > 0) {
					?>
					<tr class=" gradeX">
						<td><?php $student_id = $value['Student']['id']; echo $i; ?></td>
						<td><?php echo $value['Student']['registration_number']; ?></td>
						<td><?php echo $value['Student']['name']; ?></td>
						<td><?php 
									echo $this->Form->input('student_id', array('type'=>'hidden', 'label' => false, 'default' => $student_id, 'name'=>'data[ProfessionalTraining]['.$key.'][student_id]'));
									echo $this->Form->input('marks', array('type'=>'text', 'label' => false, 'name'=>'data[ProfessionalTraining]['.$key.'][marks]'));
							?></td>
					</tr>
					<?php
					//echo $this->Form->input('month_year_id');
					//echo $this->Form->input('student_id');
					//echo $this->Form->input('course_mapping_id');
					//echo $this->Form->input('marks');
					//echo $this->Form->input('moderation_operator');
					//echo $this->Form->input('moderation_marks');
					//echo $this->Form->input('created_by');
					//echo $this->Form->input('modified_by');
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
		}
		else {
			echo "Student and courses not mapped";
		}
	?>
<?php echo $this->Form->end(__('Submit')); ?>
</div>
