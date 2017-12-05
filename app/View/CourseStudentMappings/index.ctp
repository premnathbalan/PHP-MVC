<div class="courseStudentMappings index">
	<h2><?php echo __('Course Student Mappings'); ?></h2>
	<table cellpadding="0" cellspacing="0">
	<thead>
	<tr>
			<th><?php echo $this->Paginator->sort('id'); ?></th>
			<th><?php echo $this->Paginator->sort('name'); ?></th>
			<th><?php echo $this->Paginator->sort('user_initial'); ?></th>
			<th><?php echo $this->Paginator->sort('tamil_name'); ?></th>
			<th><?php echo $this->Paginator->sort('tamil_initial'); ?></th>
			<th><?php echo $this->Paginator->sort('father_name'); ?></th>
			<th><?php echo $this->Paginator->sort('mother_name'); ?></th>
			<th><?php echo $this->Paginator->sort('registration_number'); ?></th>
			<th><?php echo $this->Paginator->sort('roll_number'); ?></th>
			<th><?php echo $this->Paginator->sort('specialisation'); ?></th>
			<th><?php echo $this->Paginator->sort('batch_id'); ?></th>
			<th><?php echo $this->Paginator->sort('academic_id'); ?></th>
			<th><?php echo $this->Paginator->sort('program_id'); ?></th>
			<th><?php echo $this->Paginator->sort('student_type_id'); ?></th>
			<th><?php echo $this->Paginator->sort('university_references_id'); ?></th>
			<th><?php echo $this->Paginator->sort('semester_id'); ?></th>
			<th><?php echo $this->Paginator->sort('birth_date'); ?></th>
			<th><?php echo $this->Paginator->sort('gender'); ?></th>
			<th><?php echo $this->Paginator->sort('nationality'); ?></th>
			<th><?php echo $this->Paginator->sort('religion'); ?></th>
			<th><?php echo $this->Paginator->sort('community'); ?></th>
			<th><?php echo $this->Paginator->sort('address'); ?></th>
			<th><?php echo $this->Paginator->sort('city'); ?></th>
			<th><?php echo $this->Paginator->sort('stat'); ?></th>
			<th><?php echo $this->Paginator->sort('country'); ?></th>
			<th><?php echo $this->Paginator->sort('pincode'); ?></th>
			<th><?php echo $this->Paginator->sort('phone_number'); ?></th>
			<th><?php echo $this->Paginator->sort('email'); ?></th>
			<th><?php echo $this->Paginator->sort('mobile_number'); ?></th>
			<th><?php echo $this->Paginator->sort('signature'); ?></th>
			<th><?php echo $this->Paginator->sort('picture'); ?></th>
			<th><?php echo $this->Paginator->sort('admission_date'); ?></th>
			<th><?php echo $this->Paginator->sort('discontinued_status'); ?></th>
			<th><?php echo $this->Paginator->sort('addlfield1'); ?></th>
			<th><?php echo $this->Paginator->sort('addlfield2'); ?></th>
			<th><?php echo $this->Paginator->sort('addlfield3'); ?></th>
			<th><?php echo $this->Paginator->sort('addlfield4'); ?></th>
			<th><?php echo $this->Paginator->sort('addlfield5'); ?></th>
			<th><?php echo $this->Paginator->sort('indicator'); ?></th>
			<th><?php echo $this->Paginator->sort('created_by'); ?></th>
			<th><?php echo $this->Paginator->sort('modified_by'); ?></th>
			<th><?php echo $this->Paginator->sort('created'); ?></th>
			<th><?php echo $this->Paginator->sort('modified'); ?></th>
			<th class="actions"><?php echo __('Actions'); ?></th>
	</tr>
	</thead>
	<tbody>
	<?php foreach ($courseStudentMappings as $student): ?>
	<tr>
		<td><?php echo h($student['Student']['id']); ?>&nbsp;</td>
		<td><?php echo h($student['Student']['name']); ?>&nbsp;</td>
		<td><?php echo h($student['Student']['user_initial']); ?>&nbsp;</td>
		<td><?php echo h($student['Student']['tamil_name']); ?>&nbsp;</td>
		<td><?php echo h($student['Student']['tamil_initial']); ?>&nbsp;</td>
		<td><?php echo h($student['Student']['father_name']); ?>&nbsp;</td>
		<td><?php echo h($student['Student']['mother_name']); ?>&nbsp;</td>
		<td><?php echo h($student['Student']['registration_number']); ?>&nbsp;</td>
		<td><?php echo h($student['Student']['roll_number']); ?>&nbsp;</td>
		<td><?php echo h($student['Student']['specialisation']); ?>&nbsp;</td>
		<td>
			<?php echo $this->Html->link($student['Batch']['batch_from'], array('controller' => 'batches', 'action' => 'view', $student['Batch']['id'])); ?>
		</td>
		<td>
			<?php echo $this->Html->link($student['Academic']['academic_name'], array('controller' => 'academics', 'action' => 'view', $student['Academic']['id'])); ?>
		</td>
		<td>
			<?php echo $this->Html->link($student['Program']['program_name'], array('controller' => 'programs', 'action' => 'view', $student['Program']['id'])); ?>
		</td>
		<td>
			<?php echo $this->Html->link($student['StudentType']['type'], array('controller' => 'student_types', 'action' => 'view', $student['StudentType']['id'])); ?>
		</td>
		<td><?php echo h($student['Student']['university_references_id']); ?>&nbsp;</td>
		<td><?php echo h($student['Student']['semester_id']); ?>&nbsp;</td>
		<td><?php echo h($student['Student']['birth_date']); ?>&nbsp;</td>
		<td><?php echo h($student['Student']['gender']); ?>&nbsp;</td>
		<td><?php echo h($student['Student']['nationality']); ?>&nbsp;</td>
		<td><?php echo h($student['Student']['religion']); ?>&nbsp;</td>
		<td><?php echo h($student['Student']['community']); ?>&nbsp;</td>
		<td><?php echo h($student['Student']['address']); ?>&nbsp;</td>
		<td><?php echo h($student['Student']['city']); ?>&nbsp;</td>
		<td><?php echo h($student['Student']['stat']); ?>&nbsp;</td>
		<td><?php echo h($student['Student']['country']); ?>&nbsp;</td>
		<td><?php echo h($student['Student']['pincode']); ?>&nbsp;</td>
		<td><?php echo h($student['Student']['phone_number']); ?>&nbsp;</td>
		<td><?php echo h($student['Student']['email']); ?>&nbsp;</td>
		<td><?php echo h($student['Student']['mobile_number']); ?>&nbsp;</td>
		<td><?php echo h($student['Student']['signature']); ?>&nbsp;</td>
		<td><?php echo h($student['Student']['picture']); ?>&nbsp;</td>
		<td><?php echo h($student['Student']['admission_date']); ?>&nbsp;</td>
		<td><?php echo h($student['Student']['discontinued_status']); ?>&nbsp;</td>
		<td><?php echo h($student['Student']['addlfield1']); ?>&nbsp;</td>
		<td><?php echo h($student['Student']['addlfield2']); ?>&nbsp;</td>
		<td><?php echo h($student['Student']['addlfield3']); ?>&nbsp;</td>
		<td><?php echo h($student['Student']['addlfield4']); ?>&nbsp;</td>
		<td><?php echo h($student['Student']['addlfield5']); ?>&nbsp;</td>
		<td><?php echo h($student['Student']['indicator']); ?>&nbsp;</td>
		<td>
			<?php echo $this->Html->link($student['User']['username'], array('controller' => 'users', 'action' => 'view', $student['User']['id'])); ?>
		</td>
		<td>
			<?php echo $this->Html->link($student['ModifiedUser']['username'], array('controller' => 'modified_users', 'action' => 'view', $student['ModifiedUser']['id'])); ?>
		</td>
		<td><?php echo h($student['Student']['created']); ?>&nbsp;</td>
		<td><?php echo h($student['Student']['modified']); ?>&nbsp;</td>
		<td class="actions">
			<?php echo $this->Html->link(__('View'), array('action' => 'view', $student['Student']['id'])); ?>
			<?php echo $this->Html->link(__('Edit'), array('action' => 'edit', $student['Student']['id'])); ?>
			<?php echo $this->Form->postLink(__('Delete'), array('action' => 'delete', $student['Student']['id']), array('confirm' => __('Are you sure you want to delete # %s?', $student['Student']['id']))); ?>
		</td>
	</tr>
<?php endforeach; ?>
	</tbody>
	</table>
	<p>
	<?php
	echo $this->Paginator->counter(array(
		'format' => __('Page {:page} of {:pages}, showing {:current} records out of {:count} total, starting on record {:start}, ending on {:end}')
	));
	?>	</p>
	<div class="paging">
	<?php
		echo $this->Paginator->prev('< ' . __('previous'), array(), null, array('class' => 'prev disabled'));
		echo $this->Paginator->numbers(array('separator' => ''));
		echo $this->Paginator->next(__('next') . ' >', array(), null, array('class' => 'next disabled'));
	?>
	</div>
</div>
<div class="actions">
	<h3><?php echo __('Actions'); ?></h3>
	<ul>
		<li><?php echo $this->Html->link(__('New Course Student Mapping'), array('action' => 'add')); ?></li>
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
