<div class="phdStudents form">
<?php echo $this->Form->create('PhdStudent'); ?>
	<fieldset>
		<legend><?php echo __('Edit Phd Student'); ?></legend>
	<?php
		echo $this->Form->input('id');
		echo $this->Form->input('name');
		echo $this->Form->input('registration_number');
		echo $this->Form->input('birth_date');
		echo $this->Form->input('gender');
		echo $this->Form->input('father_name');
		echo $this->Form->input('address');
		echo $this->Form->input('mobile_number');
		echo $this->Form->input('email');
		//echo $this->Form->input('faculty_id');
		//echo $this->Form->input('thesi_id');
		//echo $this->Form->input('discipline_id');
		echo $this->Form->input('supervisor_id');
		echo $this->Form->input('year_of_register');
		echo $this->Form->input('date_of_register');
		echo $this->Form->input('month_year_id');
		echo $this->Form->input('picture');
		echo $this->Form->input('created_by');
		echo $this->Form->input('modified_by');
	?>
	</fieldset>
<?php echo $this->Form->end(__('Submit')); ?>
</div>