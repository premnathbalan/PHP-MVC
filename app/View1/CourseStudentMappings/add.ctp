<div class="courseStudentMappings form">
<?php echo $this->Form->create('Student'); ?>
	<fieldset>
		<legend><?php echo __('Add Course Student Mapping'); ?></legend>
	<?php
		echo $this->Form->input('name');
		echo $this->Form->input('user_initial');
		echo $this->Form->input('tamil_name');
		echo $this->Form->input('tamil_initial');
		echo $this->Form->input('father_name');
		echo $this->Form->input('mother_name');
		echo $this->Form->input('registration_number');
		echo $this->Form->input('roll_number');
		echo $this->Form->input('specialisation');
		echo $this->Form->input('batch_id');
		echo $this->Form->input('academic_id');
		echo $this->Form->input('program_id');
		echo $this->Form->input('student_type_id');
		echo $this->Form->input('university_references_id');
		echo $this->Form->input('semester_id');
		echo $this->Form->input('birth_date');
		echo $this->Form->input('gender');
		echo $this->Form->input('nationality');
		echo $this->Form->input('religion');
		echo $this->Form->input('community');
		echo $this->Form->input('address');
		echo $this->Form->input('city');
		echo $this->Form->input('stat');
		echo $this->Form->input('country');
		echo $this->Form->input('pincode');
		echo $this->Form->input('phone_number');
		echo $this->Form->input('email');
		echo $this->Form->input('mobile_number');
		echo $this->Form->input('signature');
		echo $this->Form->input('picture');
		echo $this->Form->input('admission_date');
		echo $this->Form->input('discontinued_status');
		echo $this->Form->input('addlfield1');
		echo $this->Form->input('addlfield2');
		echo $this->Form->input('addlfield3');
		echo $this->Form->input('addlfield4');
		echo $this->Form->input('addlfield5');
		echo $this->Form->input('indicator');
		echo $this->Form->input('created_by');
		echo $this->Form->input('modified_by');
	?>
	</fieldset>
<?php echo $this->Form->end(__('Submit')); ?>
</div>
<div class="actions">
	<h3><?php echo __('Actions'); ?></h3>
	<ul>

		<li><?php echo $this->Html->link(__('List Course Student Mappings'), array('action' => 'index')); ?></li>
		<li><?php echo $this->Html->link(__('List Batches'), array('controller' => 'batches', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Batch'), array('controller' => 'batches', 'action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Programs'), array('controller' => 'programs', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Program'), array('controller' => 'programs', 'action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Academics'), array('controller' => 'academics', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Academic'), array('controller' => 'academics', 'action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Student Types'), array('controller' => 'student_types', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Student Type'), array('controller' => 'student_types', 'action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Users'), array('controller' => 'users', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New User'), array('controller' => 'users', 'action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Modified Users'), array('controller' => 'modified_users', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Modified User'), array('controller' => 'modified_users', 'action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Course Student Mappings'), array('controller' => 'course_student_mappings', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Course Student Mapping'), array('controller' => 'course_student_mappings', 'action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Dummy Number Allocations'), array('controller' => 'dummy_number_allocations', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Dummy Number Allocation'), array('controller' => 'dummy_number_allocations', 'action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List End Semester Exams'), array('controller' => 'end_semester_exams', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New End Semester Exam'), array('controller' => 'end_semester_exams', 'action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Exam Attendances'), array('controller' => 'exam_attendances', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Exam Attendance'), array('controller' => 'exam_attendances', 'action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Attendances'), array('controller' => 'attendances', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Attendance'), array('controller' => 'attendances', 'action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Internal Exams'), array('controller' => 'internal_exams', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Internal Exam'), array('controller' => 'internal_exams', 'action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Practicals'), array('controller' => 'practicals', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Practical'), array('controller' => 'practicals', 'action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Projects'), array('controller' => 'projects', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Project'), array('controller' => 'projects', 'action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Student Abs'), array('controller' => 'student_abs', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Student Ab'), array('controller' => 'student_abs', 'action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Student Credits'), array('controller' => 'student_credits', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Student Credit'), array('controller' => 'student_credits', 'action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Student Grades'), array('controller' => 'student_grades', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Student Grade'), array('controller' => 'student_grades', 'action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Student Malpractices'), array('controller' => 'student_malpractices', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Student Malpractice'), array('controller' => 'student_malpractices', 'action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Student Marks'), array('controller' => 'student_marks', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Student Mark'), array('controller' => 'student_marks', 'action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Student Withdrawals'), array('controller' => 'student_withdrawals', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Student Withdrawal'), array('controller' => 'student_withdrawals', 'action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Gross Attendances'), array('controller' => 'gross_attendances', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Gross Attendance'), array('controller' => 'gross_attendances', 'action' => 'add')); ?> </li>
	</ul>
</div>
