<div class="auditCourses view">
<h2><?php echo __('Audit Course'); ?></h2>
	<table cellpadding="0" cellspacing="0" class="display" id="example" style="margin-top:10px;">
		<tr>
			<td><?php echo __('Course Name'); ?></td>
			<td>
				<?php echo h($auditCourse['AuditCourse']['course_name']); ?>
				&nbsp;
			</td>
		</tr>
		<tr>
			<td><?php echo __('Course Code'); ?></td>
			<td>
				<?php echo h($auditCourse['AuditCourse']['course_code']); ?>
				&nbsp;
			</td>
		</tr>
		<tr>
			<td><?php echo __('Common Code'); ?></td>
			<td>
				<?php echo h($auditCourse['AuditCourse']['common_code']); ?>
				&nbsp;
			</td>
		</tr>
		<tr>
			<td><?php echo __('Course Max Marks'); ?></td>
			<td>
				<?php echo h($auditCourse['AuditCourse']['course_max_marks']); ?>
				&nbsp;
			</td>
		</tr>
		<tr>
			<td><?php echo __('Total Min Pass Mark'); ?></td>
			<td>
				<?php echo h($auditCourse['AuditCourse']['total_min_pass_mark']); ?>
				&nbsp;
			</td>
		</tr>
	</table>
</div>
