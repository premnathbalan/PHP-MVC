<div class="courseStudentMappings view">
<h2><?php echo __('Course Student Mapping'); ?></h2>
	<dl>
		<dt><?php echo __('Id'); ?></dt>
		<dd>
			<?php echo h($student['Student']['id']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Name'); ?></dt>
		<dd>
			<?php echo h($student['Student']['name']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('User Initial'); ?></dt>
		<dd>
			<?php echo h($student['Student']['user_initial']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Tamil Name'); ?></dt>
		<dd>
			<?php echo h($student['Student']['tamil_name']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Tamil Initial'); ?></dt>
		<dd>
			<?php echo h($student['Student']['tamil_initial']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Father Name'); ?></dt>
		<dd>
			<?php echo h($student['Student']['father_name']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Mother Name'); ?></dt>
		<dd>
			<?php echo h($student['Student']['mother_name']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Registration Number'); ?></dt>
		<dd>
			<?php echo h($student['Student']['registration_number']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Roll Number'); ?></dt>
		<dd>
			<?php echo h($student['Student']['roll_number']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Specialisation'); ?></dt>
		<dd>
			<?php echo h($student['Student']['specialisation']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Batch'); ?></dt>
		<dd>
			<?php echo $this->Html->link($student['Batch']['batch_from'], array('controller' => 'batches', 'action' => 'view', $student['Batch']['id'])); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Academic'); ?></dt>
		<dd>
			<?php echo $this->Html->link($student['Academic']['academic_name'], array('controller' => 'academics', 'action' => 'view', $student['Academic']['id'])); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Program'); ?></dt>
		<dd>
			<?php echo $this->Html->link($student['Program']['program_name'], array('controller' => 'programs', 'action' => 'view', $student['Program']['id'])); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Student Type'); ?></dt>
		<dd>
			<?php echo $this->Html->link($student['StudentType']['type'], array('controller' => 'student_types', 'action' => 'view', $student['StudentType']['id'])); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('University References Id'); ?></dt>
		<dd>
			<?php echo h($student['Student']['university_references_id']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Semester Id'); ?></dt>
		<dd>
			<?php echo h($student['Student']['semester_id']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Birth Date'); ?></dt>
		<dd>
			<?php echo h($student['Student']['birth_date']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Gender'); ?></dt>
		<dd>
			<?php echo h($student['Student']['gender']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Nationality'); ?></dt>
		<dd>
			<?php echo h($student['Student']['nationality']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Religion'); ?></dt>
		<dd>
			<?php echo h($student['Student']['religion']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Community'); ?></dt>
		<dd>
			<?php echo h($student['Student']['community']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Address'); ?></dt>
		<dd>
			<?php echo h($student['Student']['address']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('City'); ?></dt>
		<dd>
			<?php echo h($student['Student']['city']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Stat'); ?></dt>
		<dd>
			<?php echo h($student['Student']['stat']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Country'); ?></dt>
		<dd>
			<?php echo h($student['Student']['country']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Pincode'); ?></dt>
		<dd>
			<?php echo h($student['Student']['pincode']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Phone Number'); ?></dt>
		<dd>
			<?php echo h($student['Student']['phone_number']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Email'); ?></dt>
		<dd>
			<?php echo h($student['Student']['email']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Mobile Number'); ?></dt>
		<dd>
			<?php echo h($student['Student']['mobile_number']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Signature'); ?></dt>
		<dd>
			<?php echo h($student['Student']['signature']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Picture'); ?></dt>
		<dd>
			<?php echo h($student['Student']['picture']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Admission Date'); ?></dt>
		<dd>
			<?php echo h($student['Student']['admission_date']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Discontinued Status'); ?></dt>
		<dd>
			<?php echo h($student['Student']['discontinued_status']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Addlfield1'); ?></dt>
		<dd>
			<?php echo h($student['Student']['addlfield1']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Addlfield2'); ?></dt>
		<dd>
			<?php echo h($student['Student']['addlfield2']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Addlfield3'); ?></dt>
		<dd>
			<?php echo h($student['Student']['addlfield3']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Addlfield4'); ?></dt>
		<dd>
			<?php echo h($student['Student']['addlfield4']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Addlfield5'); ?></dt>
		<dd>
			<?php echo h($student['Student']['addlfield5']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Indicator'); ?></dt>
		<dd>
			<?php echo h($student['Student']['indicator']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('User'); ?></dt>
		<dd>
			<?php echo $this->Html->link($student['User']['username'], array('controller' => 'users', 'action' => 'view', $student['User']['id'])); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Modified User'); ?></dt>
		<dd>
			<?php echo $this->Html->link($student['ModifiedUser']['username'], array('controller' => 'modified_users', 'action' => 'view', $student['ModifiedUser']['id'])); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Created'); ?></dt>
		<dd>
			<?php echo h($student['Student']['created']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Modified'); ?></dt>
		<dd>
			<?php echo h($student['Student']['modified']); ?>
			&nbsp;
		</dd>
	</dl>
</div>
<div class="actions">
	<h3><?php echo __('Actions'); ?></h3>
	<ul>
		<li><?php echo $this->Html->link(__('Edit Course Student Mapping'), array('action' => 'edit', $student['Student']['id'])); ?> </li>
		<li><?php echo $this->Form->postLink(__('Delete Course Student Mapping'), array('action' => 'delete', $student['Student']['id']), array('confirm' => __('Are you sure you want to delete # %s?', $student['Student']['id']))); ?> </li>
		<li><?php echo $this->Html->link(__('List Course Student Mappings'), array('action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Course Student Mapping'), array('action' => 'add')); ?> </li>
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
<div class="related">
	<h3><?php echo __('Related Course Student Mappings'); ?></h3>
	<?php if (!empty($student['CourseStudentMapping'])): ?>
	<table cellpadding = "0" cellspacing = "0">
	<tr>
		<th><?php echo __('Id'); ?></th>
		<th><?php echo __('Student Id'); ?></th>
		<th><?php echo __('Course Mapping Id'); ?></th>
		<th><?php echo __('Semester Id'); ?></th>
		<th><?php echo __('Course Number'); ?></th>
		<th><?php echo __('User Id'); ?></th>
		<th><?php echo __('Created By'); ?></th>
		<th><?php echo __('Modified By'); ?></th>
		<th><?php echo __('Created'); ?></th>
		<th><?php echo __('Modified'); ?></th>
		<th class="actions"><?php echo __('Actions'); ?></th>
	</tr>
	<?php foreach ($student['CourseStudentMapping'] as $courseStudentMapping): ?>
		<tr>
			<td><?php echo $courseStudentMapping['id']; ?></td>
			<td><?php echo $courseStudentMapping['student_id']; ?></td>
			<td><?php echo $courseStudentMapping['course_mapping_id']; ?></td>
			<td><?php echo $courseStudentMapping['semester_id']; ?></td>
			<td><?php echo $courseStudentMapping['course_number']; ?></td>
			<td><?php echo $courseStudentMapping['user_id']; ?></td>
			<td><?php echo $courseStudentMapping['created_by']; ?></td>
			<td><?php echo $courseStudentMapping['modified_by']; ?></td>
			<td><?php echo $courseStudentMapping['created']; ?></td>
			<td><?php echo $courseStudentMapping['modified']; ?></td>
			<td class="actions">
				<?php echo $this->Html->link(__('View'), array('controller' => 'course_student_mappings', 'action' => 'view', $courseStudentMapping['id'])); ?>
				<?php echo $this->Html->link(__('Edit'), array('controller' => 'course_student_mappings', 'action' => 'edit', $courseStudentMapping['id'])); ?>
				<?php echo $this->Form->postLink(__('Delete'), array('controller' => 'course_student_mappings', 'action' => 'delete', $courseStudentMapping['id']), array('confirm' => __('Are you sure you want to delete # %s?', $courseStudentMapping['id']))); ?>
			</td>
		</tr>
	<?php endforeach; ?>
	</table>
<?php endif; ?>

	<div class="actions">
		<ul>
			<li><?php echo $this->Html->link(__('New Course Student Mapping'), array('controller' => 'course_student_mappings', 'action' => 'add')); ?> </li>
		</ul>
	</div>
</div>
<div class="related">
	<h3><?php echo __('Related Dummy Number Allocations'); ?></h3>
	<?php if (!empty($student['DummyNumberAllocation'])): ?>
	<table cellpadding = "0" cellspacing = "0">
	<tr>
		<th><?php echo __('Id'); ?></th>
		<th><?php echo __('Batch Id'); ?></th>
		<th><?php echo __('Course Id'); ?></th>
		<th><?php echo __('Student Id'); ?></th>
		<th><?php echo __('Dummy Number'); ?></th>
		<th><?php echo __('Created By'); ?></th>
		<th><?php echo __('Modified By'); ?></th>
		<th><?php echo __('Created'); ?></th>
		<th><?php echo __('Modified'); ?></th>
		<th class="actions"><?php echo __('Actions'); ?></th>
	</tr>
	<?php foreach ($student['DummyNumberAllocation'] as $dummyNumberAllocation): ?>
		<tr>
			<td><?php echo $dummyNumberAllocation['id']; ?></td>
			<td><?php echo $dummyNumberAllocation['batch_id']; ?></td>
			<td><?php echo $dummyNumberAllocation['course_id']; ?></td>
			<td><?php echo $dummyNumberAllocation['student_id']; ?></td>
			<td><?php echo $dummyNumberAllocation['dummy_number']; ?></td>
			<td><?php echo $dummyNumberAllocation['created_by']; ?></td>
			<td><?php echo $dummyNumberAllocation['modified_by']; ?></td>
			<td><?php echo $dummyNumberAllocation['created']; ?></td>
			<td><?php echo $dummyNumberAllocation['modified']; ?></td>
			<td class="actions">
				<?php echo $this->Html->link(__('View'), array('controller' => 'dummy_number_allocations', 'action' => 'view', $dummyNumberAllocation['id'])); ?>
				<?php echo $this->Html->link(__('Edit'), array('controller' => 'dummy_number_allocations', 'action' => 'edit', $dummyNumberAllocation['id'])); ?>
				<?php echo $this->Form->postLink(__('Delete'), array('controller' => 'dummy_number_allocations', 'action' => 'delete', $dummyNumberAllocation['id']), array('confirm' => __('Are you sure you want to delete # %s?', $dummyNumberAllocation['id']))); ?>
			</td>
		</tr>
	<?php endforeach; ?>
	</table>
<?php endif; ?>

	<div class="actions">
		<ul>
			<li><?php echo $this->Html->link(__('New Dummy Number Allocation'), array('controller' => 'dummy_number_allocations', 'action' => 'add')); ?> </li>
		</ul>
	</div>
</div>
<div class="related">
	<h3><?php echo __('Related End Semester Exams'); ?></h3>
	<?php if (!empty($student['EndSemesterExam'])): ?>
	<table cellpadding = "0" cellspacing = "0">
	<tr>
		<th><?php echo __('Id'); ?></th>
		<th><?php echo __('Course Id'); ?></th>
		<th><?php echo __('Student Id'); ?></th>
		<th><?php echo __('Month'); ?></th>
		<th><?php echo __('Year'); ?></th>
		<th><?php echo __('Absent'); ?></th>
		<th><?php echo __('Original Marks'); ?></th>
		<th><?php echo __('Max Convert To'); ?></th>
		<th><?php echo __('Converted Marks'); ?></th>
		<th><?php echo __('Moderation'); ?></th>
		<th><?php echo __('Revaluation'); ?></th>
		<th><?php echo __('Revaluation Marks'); ?></th>
		<th><?php echo __('Final Mark'); ?></th>
		<th><?php echo __('Created By'); ?></th>
		<th><?php echo __('Modified By'); ?></th>
		<th><?php echo __('Created'); ?></th>
		<th><?php echo __('Modified'); ?></th>
		<th class="actions"><?php echo __('Actions'); ?></th>
	</tr>
	<?php foreach ($student['EndSemesterExam'] as $endSemesterExam): ?>
		<tr>
			<td><?php echo $endSemesterExam['id']; ?></td>
			<td><?php echo $endSemesterExam['course_id']; ?></td>
			<td><?php echo $endSemesterExam['student_id']; ?></td>
			<td><?php echo $endSemesterExam['month']; ?></td>
			<td><?php echo $endSemesterExam['year']; ?></td>
			<td><?php echo $endSemesterExam['absent']; ?></td>
			<td><?php echo $endSemesterExam['original_marks']; ?></td>
			<td><?php echo $endSemesterExam['max_convert_to']; ?></td>
			<td><?php echo $endSemesterExam['converted_marks']; ?></td>
			<td><?php echo $endSemesterExam['moderation']; ?></td>
			<td><?php echo $endSemesterExam['revaluation']; ?></td>
			<td><?php echo $endSemesterExam['revaluation_marks']; ?></td>
			<td><?php echo $endSemesterExam['final_mark']; ?></td>
			<td><?php echo $endSemesterExam['created_by']; ?></td>
			<td><?php echo $endSemesterExam['modified_by']; ?></td>
			<td><?php echo $endSemesterExam['created']; ?></td>
			<td><?php echo $endSemesterExam['modified']; ?></td>
			<td class="actions">
				<?php echo $this->Html->link(__('View'), array('controller' => 'end_semester_exams', 'action' => 'view', $endSemesterExam['id'])); ?>
				<?php echo $this->Html->link(__('Edit'), array('controller' => 'end_semester_exams', 'action' => 'edit', $endSemesterExam['id'])); ?>
				<?php echo $this->Form->postLink(__('Delete'), array('controller' => 'end_semester_exams', 'action' => 'delete', $endSemesterExam['id']), array('confirm' => __('Are you sure you want to delete # %s?', $endSemesterExam['id']))); ?>
			</td>
		</tr>
	<?php endforeach; ?>
	</table>
<?php endif; ?>

	<div class="actions">
		<ul>
			<li><?php echo $this->Html->link(__('New End Semester Exam'), array('controller' => 'end_semester_exams', 'action' => 'add')); ?> </li>
		</ul>
	</div>
</div>
<div class="related">
	<h3><?php echo __('Related Exam Attendances'); ?></h3>
	<?php if (!empty($student['ExamAttendance'])): ?>
	<table cellpadding = "0" cellspacing = "0">
	<tr>
		<th><?php echo __('Id'); ?></th>
		<th><?php echo __('Student Id'); ?></th>
		<th><?php echo __('Course Id'); ?></th>
		<th><?php echo __('Status'); ?></th>
		<th><?php echo __('Created By'); ?></th>
		<th><?php echo __('Modified By'); ?></th>
		<th><?php echo __('Created'); ?></th>
		<th><?php echo __('Modified'); ?></th>
		<th class="actions"><?php echo __('Actions'); ?></th>
	</tr>
	<?php foreach ($student['ExamAttendance'] as $examAttendance): ?>
		<tr>
			<td><?php echo $examAttendance['id']; ?></td>
			<td><?php echo $examAttendance['student_id']; ?></td>
			<td><?php echo $examAttendance['course_id']; ?></td>
			<td><?php echo $examAttendance['status']; ?></td>
			<td><?php echo $examAttendance['created_by']; ?></td>
			<td><?php echo $examAttendance['modified_by']; ?></td>
			<td><?php echo $examAttendance['created']; ?></td>
			<td><?php echo $examAttendance['modified']; ?></td>
			<td class="actions">
				<?php echo $this->Html->link(__('View'), array('controller' => 'exam_attendances', 'action' => 'view', $examAttendance['id'])); ?>
				<?php echo $this->Html->link(__('Edit'), array('controller' => 'exam_attendances', 'action' => 'edit', $examAttendance['id'])); ?>
				<?php echo $this->Form->postLink(__('Delete'), array('controller' => 'exam_attendances', 'action' => 'delete', $examAttendance['id']), array('confirm' => __('Are you sure you want to delete # %s?', $examAttendance['id']))); ?>
			</td>
		</tr>
	<?php endforeach; ?>
	</table>
<?php endif; ?>

	<div class="actions">
		<ul>
			<li><?php echo $this->Html->link(__('New Exam Attendance'), array('controller' => 'exam_attendances', 'action' => 'add')); ?> </li>
		</ul>
	</div>
</div>
<div class="related">
	<h3><?php echo __('Related Attendances'); ?></h3>
	<?php if (!empty($student['Attendance'])): ?>
	<table cellpadding = "0" cellspacing = "0">
	<tr>
		<th><?php echo __('Id'); ?></th>
		<th><?php echo __('Student Id'); ?></th>
		<th><?php echo __('Course Mapping Id'); ?></th>
		<th><?php echo __('Percentage'); ?></th>
		<th><?php echo __('Created By'); ?></th>
		<th><?php echo __('Modified By'); ?></th>
		<th><?php echo __('Created'); ?></th>
		<th><?php echo __('Modified'); ?></th>
		<th class="actions"><?php echo __('Actions'); ?></th>
	</tr>
	<?php foreach ($student['Attendance'] as $attendance): ?>
		<tr>
			<td><?php echo $attendance['id']; ?></td>
			<td><?php echo $attendance['student_id']; ?></td>
			<td><?php echo $attendance['course_mapping_id']; ?></td>
			<td><?php echo $attendance['percentage']; ?></td>
			<td><?php echo $attendance['created_by']; ?></td>
			<td><?php echo $attendance['modified_by']; ?></td>
			<td><?php echo $attendance['created']; ?></td>
			<td><?php echo $attendance['modified']; ?></td>
			<td class="actions">
				<?php echo $this->Html->link(__('View'), array('controller' => 'attendances', 'action' => 'view', $attendance['id'])); ?>
				<?php echo $this->Html->link(__('Edit'), array('controller' => 'attendances', 'action' => 'edit', $attendance['id'])); ?>
				<?php echo $this->Form->postLink(__('Delete'), array('controller' => 'attendances', 'action' => 'delete', $attendance['id']), array('confirm' => __('Are you sure you want to delete # %s?', $attendance['id']))); ?>
			</td>
		</tr>
	<?php endforeach; ?>
	</table>
<?php endif; ?>

	<div class="actions">
		<ul>
			<li><?php echo $this->Html->link(__('New Attendance'), array('controller' => 'attendances', 'action' => 'add')); ?> </li>
		</ul>
	</div>
</div>
<div class="related">
	<h3><?php echo __('Related Internal Exams'); ?></h3>
	<?php if (!empty($student['InternalExam'])): ?>
	<table cellpadding = "0" cellspacing = "0">
	<tr>
		<th><?php echo __('Id'); ?></th>
		<th><?php echo __('Course Mapping Id'); ?></th>
		<th><?php echo __('Student Id'); ?></th>
		<th><?php echo __('Marks'); ?></th>
		<th><?php echo __('Moderation Operator'); ?></th>
		<th><?php echo __('Moderation Marks'); ?></th>
		<th><?php echo __('Moderation Date'); ?></th>
		<th><?php echo __('Max Convert To'); ?></th>
		<th><?php echo __('Created By'); ?></th>
		<th><?php echo __('Modified By'); ?></th>
		<th><?php echo __('Created'); ?></th>
		<th><?php echo __('Modified'); ?></th>
		<th class="actions"><?php echo __('Actions'); ?></th>
	</tr>
	<?php foreach ($student['InternalExam'] as $internalExam): ?>
		<tr>
			<td><?php echo $internalExam['id']; ?></td>
			<td><?php echo $internalExam['course_mapping_id']; ?></td>
			<td><?php echo $internalExam['student_id']; ?></td>
			<td><?php echo $internalExam['marks']; ?></td>
			<td><?php echo $internalExam['moderation_operator']; ?></td>
			<td><?php echo $internalExam['moderation_marks']; ?></td>
			<td><?php echo $internalExam['moderation_date']; ?></td>
			<td><?php echo $internalExam['max_convert_to']; ?></td>
			<td><?php echo $internalExam['created_by']; ?></td>
			<td><?php echo $internalExam['modified_by']; ?></td>
			<td><?php echo $internalExam['created']; ?></td>
			<td><?php echo $internalExam['modified']; ?></td>
			<td class="actions">
				<?php echo $this->Html->link(__('View'), array('controller' => 'internal_exams', 'action' => 'view', $internalExam['id'])); ?>
				<?php echo $this->Html->link(__('Edit'), array('controller' => 'internal_exams', 'action' => 'edit', $internalExam['id'])); ?>
				<?php echo $this->Form->postLink(__('Delete'), array('controller' => 'internal_exams', 'action' => 'delete', $internalExam['id']), array('confirm' => __('Are you sure you want to delete # %s?', $internalExam['id']))); ?>
			</td>
		</tr>
	<?php endforeach; ?>
	</table>
<?php endif; ?>

	<div class="actions">
		<ul>
			<li><?php echo $this->Html->link(__('New Internal Exam'), array('controller' => 'internal_exams', 'action' => 'add')); ?> </li>
		</ul>
	</div>
</div>
<div class="related">
	<h3><?php echo __('Related Practicals'); ?></h3>
	<?php if (!empty($student['Practical'])): ?>
	<table cellpadding = "0" cellspacing = "0">
	<tr>
		<th><?php echo __('Id'); ?></th>
		<th><?php echo __('Student Id'); ?></th>
		<th><?php echo __('Cae Practical Id'); ?></th>
		<th><?php echo __('Marks'); ?></th>
		<th><?php echo __('Created By'); ?></th>
		<th><?php echo __('Modified By'); ?></th>
		<th><?php echo __('Created'); ?></th>
		<th><?php echo __('Modified'); ?></th>
		<th class="actions"><?php echo __('Actions'); ?></th>
	</tr>
	<?php foreach ($student['Practical'] as $practical): ?>
		<tr>
			<td><?php echo $practical['id']; ?></td>
			<td><?php echo $practical['student_id']; ?></td>
			<td><?php echo $practical['cae_practical_id']; ?></td>
			<td><?php echo $practical['marks']; ?></td>
			<td><?php echo $practical['created_by']; ?></td>
			<td><?php echo $practical['modified_by']; ?></td>
			<td><?php echo $practical['created']; ?></td>
			<td><?php echo $practical['modified']; ?></td>
			<td class="actions">
				<?php echo $this->Html->link(__('View'), array('controller' => 'practicals', 'action' => 'view', $practical['id'])); ?>
				<?php echo $this->Html->link(__('Edit'), array('controller' => 'practicals', 'action' => 'edit', $practical['id'])); ?>
				<?php echo $this->Form->postLink(__('Delete'), array('controller' => 'practicals', 'action' => 'delete', $practical['id']), array('confirm' => __('Are you sure you want to delete # %s?', $practical['id']))); ?>
			</td>
		</tr>
	<?php endforeach; ?>
	</table>
<?php endif; ?>

	<div class="actions">
		<ul>
			<li><?php echo $this->Html->link(__('New Practical'), array('controller' => 'practicals', 'action' => 'add')); ?> </li>
		</ul>
	</div>
</div>
<div class="related">
	<h3><?php echo __('Related Projects'); ?></h3>
	<?php if (!empty($student['Project'])): ?>
	<table cellpadding = "0" cellspacing = "0">
	<tr>
		<th><?php echo __('Id'); ?></th>
		<th><?php echo __('Student Id'); ?></th>
		<th><?php echo __('Cae Project Id'); ?></th>
		<th><?php echo __('Marks'); ?></th>
		<th><?php echo __('Created By'); ?></th>
		<th><?php echo __('Modified By'); ?></th>
		<th><?php echo __('Created'); ?></th>
		<th><?php echo __('Modified'); ?></th>
		<th class="actions"><?php echo __('Actions'); ?></th>
	</tr>
	<?php foreach ($student['Project'] as $project): ?>
		<tr>
			<td><?php echo $project['id']; ?></td>
			<td><?php echo $project['student_id']; ?></td>
			<td><?php echo $project['cae_project_id']; ?></td>
			<td><?php echo $project['marks']; ?></td>
			<td><?php echo $project['created_by']; ?></td>
			<td><?php echo $project['modified_by']; ?></td>
			<td><?php echo $project['created']; ?></td>
			<td><?php echo $project['modified']; ?></td>
			<td class="actions">
				<?php echo $this->Html->link(__('View'), array('controller' => 'projects', 'action' => 'view', $project['id'])); ?>
				<?php echo $this->Html->link(__('Edit'), array('controller' => 'projects', 'action' => 'edit', $project['id'])); ?>
				<?php echo $this->Form->postLink(__('Delete'), array('controller' => 'projects', 'action' => 'delete', $project['id']), array('confirm' => __('Are you sure you want to delete # %s?', $project['id']))); ?>
			</td>
		</tr>
	<?php endforeach; ?>
	</table>
<?php endif; ?>

	<div class="actions">
		<ul>
			<li><?php echo $this->Html->link(__('New Project'), array('controller' => 'projects', 'action' => 'add')); ?> </li>
		</ul>
	</div>
</div>
<div class="related">
	<h3><?php echo __('Related Student Abs'); ?></h3>
	<?php if (!empty($student['StudentAb'])): ?>
	<table cellpadding = "0" cellspacing = "0">
	<tr>
		<th><?php echo __('Id'); ?></th>
		<th><?php echo __('Course Id'); ?></th>
		<th><?php echo __('Student Id'); ?></th>
		<th><?php echo __('Month'); ?></th>
		<th><?php echo __('Year'); ?></th>
		<th><?php echo __('Abs'); ?></th>
		<th><?php echo __('Abs Remarks'); ?></th>
		<th><?php echo __('Created By'); ?></th>
		<th><?php echo __('Modified By'); ?></th>
		<th><?php echo __('Created'); ?></th>
		<th><?php echo __('Modified'); ?></th>
		<th class="actions"><?php echo __('Actions'); ?></th>
	</tr>
	<?php foreach ($student['StudentAb'] as $studentAb): ?>
		<tr>
			<td><?php echo $studentAb['id']; ?></td>
			<td><?php echo $studentAb['course_id']; ?></td>
			<td><?php echo $studentAb['student_id']; ?></td>
			<td><?php echo $studentAb['month']; ?></td>
			<td><?php echo $studentAb['year']; ?></td>
			<td><?php echo $studentAb['abs']; ?></td>
			<td><?php echo $studentAb['abs_remarks']; ?></td>
			<td><?php echo $studentAb['created_by']; ?></td>
			<td><?php echo $studentAb['modified_by']; ?></td>
			<td><?php echo $studentAb['created']; ?></td>
			<td><?php echo $studentAb['modified']; ?></td>
			<td class="actions">
				<?php echo $this->Html->link(__('View'), array('controller' => 'student_abs', 'action' => 'view', $studentAb['id'])); ?>
				<?php echo $this->Html->link(__('Edit'), array('controller' => 'student_abs', 'action' => 'edit', $studentAb['id'])); ?>
				<?php echo $this->Form->postLink(__('Delete'), array('controller' => 'student_abs', 'action' => 'delete', $studentAb['id']), array('confirm' => __('Are you sure you want to delete # %s?', $studentAb['id']))); ?>
			</td>
		</tr>
	<?php endforeach; ?>
	</table>
<?php endif; ?>

	<div class="actions">
		<ul>
			<li><?php echo $this->Html->link(__('New Student Ab'), array('controller' => 'student_abs', 'action' => 'add')); ?> </li>
		</ul>
	</div>
</div>
<div class="related">
	<h3><?php echo __('Related Student Credits'); ?></h3>
	<?php if (!empty($student['StudentCredit'])): ?>
	<table cellpadding = "0" cellspacing = "0">
	<tr>
		<th><?php echo __('Id'); ?></th>
		<th><?php echo __('Student Id'); ?></th>
		<th><?php echo __('Course Id'); ?></th>
		<th><?php echo __('Credit Point'); ?></th>
		<th><?php echo __('Created By'); ?></th>
		<th><?php echo __('Modified By'); ?></th>
		<th><?php echo __('Created'); ?></th>
		<th><?php echo __('Modified'); ?></th>
		<th class="actions"><?php echo __('Actions'); ?></th>
	</tr>
	<?php foreach ($student['StudentCredit'] as $studentCredit): ?>
		<tr>
			<td><?php echo $studentCredit['id']; ?></td>
			<td><?php echo $studentCredit['student_id']; ?></td>
			<td><?php echo $studentCredit['course_id']; ?></td>
			<td><?php echo $studentCredit['credit_point']; ?></td>
			<td><?php echo $studentCredit['created_by']; ?></td>
			<td><?php echo $studentCredit['modified_by']; ?></td>
			<td><?php echo $studentCredit['created']; ?></td>
			<td><?php echo $studentCredit['modified']; ?></td>
			<td class="actions">
				<?php echo $this->Html->link(__('View'), array('controller' => 'student_credits', 'action' => 'view', $studentCredit['id'])); ?>
				<?php echo $this->Html->link(__('Edit'), array('controller' => 'student_credits', 'action' => 'edit', $studentCredit['id'])); ?>
				<?php echo $this->Form->postLink(__('Delete'), array('controller' => 'student_credits', 'action' => 'delete', $studentCredit['id']), array('confirm' => __('Are you sure you want to delete # %s?', $studentCredit['id']))); ?>
			</td>
		</tr>
	<?php endforeach; ?>
	</table>
<?php endif; ?>

	<div class="actions">
		<ul>
			<li><?php echo $this->Html->link(__('New Student Credit'), array('controller' => 'student_credits', 'action' => 'add')); ?> </li>
		</ul>
	</div>
</div>
<div class="related">
	<h3><?php echo __('Related Student Grades'); ?></h3>
	<?php if (!empty($student['StudentGrade'])): ?>
	<table cellpadding = "0" cellspacing = "0">
	<tr>
		<th><?php echo __('Id'); ?></th>
		<th><?php echo __('Student Id'); ?></th>
		<th><?php echo __('Grade'); ?></th>
		<th><?php echo __('Grade Point'); ?></th>
		<th><?php echo __('Cgpa'); ?></th>
		<th><?php echo __('Final Degree'); ?></th>
		<th><?php echo __('Created By'); ?></th>
		<th><?php echo __('Modified By'); ?></th>
		<th><?php echo __('Created'); ?></th>
		<th><?php echo __('Modified'); ?></th>
		<th class="actions"><?php echo __('Actions'); ?></th>
	</tr>
	<?php foreach ($student['StudentGrade'] as $studentGrade): ?>
		<tr>
			<td><?php echo $studentGrade['id']; ?></td>
			<td><?php echo $studentGrade['student_id']; ?></td>
			<td><?php echo $studentGrade['grade']; ?></td>
			<td><?php echo $studentGrade['grade_point']; ?></td>
			<td><?php echo $studentGrade['cgpa']; ?></td>
			<td><?php echo $studentGrade['final_degree']; ?></td>
			<td><?php echo $studentGrade['created_by']; ?></td>
			<td><?php echo $studentGrade['modified_by']; ?></td>
			<td><?php echo $studentGrade['created']; ?></td>
			<td><?php echo $studentGrade['modified']; ?></td>
			<td class="actions">
				<?php echo $this->Html->link(__('View'), array('controller' => 'student_grades', 'action' => 'view', $studentGrade['id'])); ?>
				<?php echo $this->Html->link(__('Edit'), array('controller' => 'student_grades', 'action' => 'edit', $studentGrade['id'])); ?>
				<?php echo $this->Form->postLink(__('Delete'), array('controller' => 'student_grades', 'action' => 'delete', $studentGrade['id']), array('confirm' => __('Are you sure you want to delete # %s?', $studentGrade['id']))); ?>
			</td>
		</tr>
	<?php endforeach; ?>
	</table>
<?php endif; ?>

	<div class="actions">
		<ul>
			<li><?php echo $this->Html->link(__('New Student Grade'), array('controller' => 'student_grades', 'action' => 'add')); ?> </li>
		</ul>
	</div>
</div>
<div class="related">
	<h3><?php echo __('Related Student Malpractices'); ?></h3>
	<?php if (!empty($student['StudentMalpractice'])): ?>
	<table cellpadding = "0" cellspacing = "0">
	<tr>
		<th><?php echo __('Id'); ?></th>
		<th><?php echo __('Course Id'); ?></th>
		<th><?php echo __('Student Id'); ?></th>
		<th><?php echo __('Month'); ?></th>
		<th><?php echo __('Year'); ?></th>
		<th><?php echo __('Malpractice'); ?></th>
		<th><?php echo __('Malpractice Remarks'); ?></th>
		<th><?php echo __('Created By'); ?></th>
		<th><?php echo __('Modified By'); ?></th>
		<th><?php echo __('Created'); ?></th>
		<th><?php echo __('Modified'); ?></th>
		<th class="actions"><?php echo __('Actions'); ?></th>
	</tr>
	<?php foreach ($student['StudentMalpractice'] as $studentMalpractice): ?>
		<tr>
			<td><?php echo $studentMalpractice['id']; ?></td>
			<td><?php echo $studentMalpractice['course_id']; ?></td>
			<td><?php echo $studentMalpractice['student_id']; ?></td>
			<td><?php echo $studentMalpractice['month']; ?></td>
			<td><?php echo $studentMalpractice['year']; ?></td>
			<td><?php echo $studentMalpractice['malpractice']; ?></td>
			<td><?php echo $studentMalpractice['malpractice_remarks']; ?></td>
			<td><?php echo $studentMalpractice['created_by']; ?></td>
			<td><?php echo $studentMalpractice['modified_by']; ?></td>
			<td><?php echo $studentMalpractice['created']; ?></td>
			<td><?php echo $studentMalpractice['modified']; ?></td>
			<td class="actions">
				<?php echo $this->Html->link(__('View'), array('controller' => 'student_malpractices', 'action' => 'view', $studentMalpractice['id'])); ?>
				<?php echo $this->Html->link(__('Edit'), array('controller' => 'student_malpractices', 'action' => 'edit', $studentMalpractice['id'])); ?>
				<?php echo $this->Form->postLink(__('Delete'), array('controller' => 'student_malpractices', 'action' => 'delete', $studentMalpractice['id']), array('confirm' => __('Are you sure you want to delete # %s?', $studentMalpractice['id']))); ?>
			</td>
		</tr>
	<?php endforeach; ?>
	</table>
<?php endif; ?>

	<div class="actions">
		<ul>
			<li><?php echo $this->Html->link(__('New Student Malpractice'), array('controller' => 'student_malpractices', 'action' => 'add')); ?> </li>
		</ul>
	</div>
</div>
<div class="related">
	<h3><?php echo __('Related Student Marks'); ?></h3>
	<?php if (!empty($student['StudentMark'])): ?>
	<table cellpadding = "0" cellspacing = "0">
	<tr>
		<th><?php echo __('Id'); ?></th>
		<th><?php echo __('Student Id'); ?></th>
		<th><?php echo __('Month'); ?></th>
		<th><?php echo __('Year'); ?></th>
		<th><?php echo __('Course Id'); ?></th>
		<th><?php echo __('Marks'); ?></th>
		<th><?php echo __('Result'); ?></th>
		<th><?php echo __('Created By'); ?></th>
		<th><?php echo __('Modified By'); ?></th>
		<th><?php echo __('Created'); ?></th>
		<th><?php echo __('Modified'); ?></th>
		<th class="actions"><?php echo __('Actions'); ?></th>
	</tr>
	<?php foreach ($student['StudentMark'] as $studentMark): ?>
		<tr>
			<td><?php echo $studentMark['id']; ?></td>
			<td><?php echo $studentMark['student_id']; ?></td>
			<td><?php echo $studentMark['month']; ?></td>
			<td><?php echo $studentMark['year']; ?></td>
			<td><?php echo $studentMark['course_id']; ?></td>
			<td><?php echo $studentMark['marks']; ?></td>
			<td><?php echo $studentMark['result']; ?></td>
			<td><?php echo $studentMark['created_by']; ?></td>
			<td><?php echo $studentMark['modified_by']; ?></td>
			<td><?php echo $studentMark['created']; ?></td>
			<td><?php echo $studentMark['modified']; ?></td>
			<td class="actions">
				<?php echo $this->Html->link(__('View'), array('controller' => 'student_marks', 'action' => 'view', $studentMark['id'])); ?>
				<?php echo $this->Html->link(__('Edit'), array('controller' => 'student_marks', 'action' => 'edit', $studentMark['id'])); ?>
				<?php echo $this->Form->postLink(__('Delete'), array('controller' => 'student_marks', 'action' => 'delete', $studentMark['id']), array('confirm' => __('Are you sure you want to delete # %s?', $studentMark['id']))); ?>
			</td>
		</tr>
	<?php endforeach; ?>
	</table>
<?php endif; ?>

	<div class="actions">
		<ul>
			<li><?php echo $this->Html->link(__('New Student Mark'), array('controller' => 'student_marks', 'action' => 'add')); ?> </li>
		</ul>
	</div>
</div>
<div class="related">
	<h3><?php echo __('Related Student Withdrawals'); ?></h3>
	<?php if (!empty($student['StudentWithdrawal'])): ?>
	<table cellpadding = "0" cellspacing = "0">
	<tr>
		<th><?php echo __('Id'); ?></th>
		<th><?php echo __('Course Id'); ?></th>
		<th><?php echo __('Student Id'); ?></th>
		<th><?php echo __('Month'); ?></th>
		<th><?php echo __('Year'); ?></th>
		<th><?php echo __('Withdrawal'); ?></th>
		<th><?php echo __('Withdrawal Remarks'); ?></th>
		<th><?php echo __('Created By'); ?></th>
		<th><?php echo __('Modified By'); ?></th>
		<th><?php echo __('Created'); ?></th>
		<th><?php echo __('Modified'); ?></th>
		<th class="actions"><?php echo __('Actions'); ?></th>
	</tr>
	<?php foreach ($student['StudentWithdrawal'] as $studentWithdrawal): ?>
		<tr>
			<td><?php echo $studentWithdrawal['id']; ?></td>
			<td><?php echo $studentWithdrawal['course_id']; ?></td>
			<td><?php echo $studentWithdrawal['student_id']; ?></td>
			<td><?php echo $studentWithdrawal['month']; ?></td>
			<td><?php echo $studentWithdrawal['year']; ?></td>
			<td><?php echo $studentWithdrawal['withdrawal']; ?></td>
			<td><?php echo $studentWithdrawal['withdrawal_remarks']; ?></td>
			<td><?php echo $studentWithdrawal['created_by']; ?></td>
			<td><?php echo $studentWithdrawal['modified_by']; ?></td>
			<td><?php echo $studentWithdrawal['created']; ?></td>
			<td><?php echo $studentWithdrawal['modified']; ?></td>
			<td class="actions">
				<?php echo $this->Html->link(__('View'), array('controller' => 'student_withdrawals', 'action' => 'view', $studentWithdrawal['id'])); ?>
				<?php echo $this->Html->link(__('Edit'), array('controller' => 'student_withdrawals', 'action' => 'edit', $studentWithdrawal['id'])); ?>
				<?php echo $this->Form->postLink(__('Delete'), array('controller' => 'student_withdrawals', 'action' => 'delete', $studentWithdrawal['id']), array('confirm' => __('Are you sure you want to delete # %s?', $studentWithdrawal['id']))); ?>
			</td>
		</tr>
	<?php endforeach; ?>
	</table>
<?php endif; ?>

	<div class="actions">
		<ul>
			<li><?php echo $this->Html->link(__('New Student Withdrawal'), array('controller' => 'student_withdrawals', 'action' => 'add')); ?> </li>
		</ul>
	</div>
</div>
<div class="related">
	<h3><?php echo __('Related Gross Attendances'); ?></h3>
	<?php if (!empty($student['GrossAttendance'])): ?>
	<table cellpadding = "0" cellspacing = "0">
	<tr>
		<th><?php echo __('Id'); ?></th>
		<th><?php echo __('Student Id'); ?></th>
		<th><?php echo __('Program Id'); ?></th>
		<th><?php echo __('Month Year Id'); ?></th>
		<th><?php echo __('Percentage'); ?></th>
		<th><?php echo __('Created By'); ?></th>
		<th><?php echo __('Modified By'); ?></th>
		<th><?php echo __('Created'); ?></th>
		<th><?php echo __('Modified'); ?></th>
		<th class="actions"><?php echo __('Actions'); ?></th>
	</tr>
	<?php foreach ($student['GrossAttendance'] as $grossAttendance): ?>
		<tr>
			<td><?php echo $grossAttendance['id']; ?></td>
			<td><?php echo $grossAttendance['student_id']; ?></td>
			<td><?php echo $grossAttendance['program_id']; ?></td>
			<td><?php echo $grossAttendance['month_year_id']; ?></td>
			<td><?php echo $grossAttendance['percentage']; ?></td>
			<td><?php echo $grossAttendance['created_by']; ?></td>
			<td><?php echo $grossAttendance['modified_by']; ?></td>
			<td><?php echo $grossAttendance['created']; ?></td>
			<td><?php echo $grossAttendance['modified']; ?></td>
			<td class="actions">
				<?php echo $this->Html->link(__('View'), array('controller' => 'gross_attendances', 'action' => 'view', $grossAttendance['id'])); ?>
				<?php echo $this->Html->link(__('Edit'), array('controller' => 'gross_attendances', 'action' => 'edit', $grossAttendance['id'])); ?>
				<?php echo $this->Form->postLink(__('Delete'), array('controller' => 'gross_attendances', 'action' => 'delete', $grossAttendance['id']), array('confirm' => __('Are you sure you want to delete # %s?', $grossAttendance['id']))); ?>
			</td>
		</tr>
	<?php endforeach; ?>
	</table>
<?php endif; ?>

	<div class="actions">
		<ul>
			<li><?php echo $this->Html->link(__('New Gross Attendance'), array('controller' => 'gross_attendances', 'action' => 'add')); ?> </li>
		</ul>
	</div>
</div>
