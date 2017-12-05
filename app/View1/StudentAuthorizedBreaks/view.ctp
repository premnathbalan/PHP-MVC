<div class="studentAuthorizedBreaks view">
<h2><?php echo __('Student Authorized Break'); ?></h2>
	<dl>
		<dt><?php echo __('Id'); ?></dt>
		<dd>
			<?php echo h($studentAuthorizedBreak['StudentAuthorizedBreak']['id']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Student'); ?></dt>
		<dd>
			<?php echo $this->Html->link($studentAuthorizedBreak['Student']['name'], array('controller' => 'students', 'action' => 'view', $studentAuthorizedBreak['Student']['id'])); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Semester'); ?></dt>
		<dd>
			<?php echo $this->Html->link($studentAuthorizedBreak['Semester']['id'], array('controller' => 'semesters', 'action' => 'view', $studentAuthorizedBreak['Semester']['id'])); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Created By'); ?></dt>
		<dd>
			<?php echo h($studentAuthorizedBreak['StudentAuthorizedBreak']['created_by']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Modified By'); ?></dt>
		<dd>
			<?php echo h($studentAuthorizedBreak['StudentAuthorizedBreak']['modified_by']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Created'); ?></dt>
		<dd>
			<?php echo h($studentAuthorizedBreak['StudentAuthorizedBreak']['created']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Modified'); ?></dt>
		<dd>
			<?php echo h($studentAuthorizedBreak['StudentAuthorizedBreak']['modified']); ?>
			&nbsp;
		</dd>
	</dl>
</div>
<div class="actions">
	<h3><?php echo __('Actions'); ?></h3>
	<ul>
		<li><?php echo $this->Html->link(__('Edit Student Authorized Break'), array('action' => 'edit', $studentAuthorizedBreak['StudentAuthorizedBreak']['id'])); ?> </li>
		<li><?php echo $this->Form->postLink(__('Delete Student Authorized Break'), array('action' => 'delete', $studentAuthorizedBreak['StudentAuthorizedBreak']['id']), array('confirm' => __('Are you sure you want to delete # %s?', $studentAuthorizedBreak['StudentAuthorizedBreak']['id']))); ?> </li>
		<li><?php echo $this->Html->link(__('List Student Authorized Breaks'), array('action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Student Authorized Break'), array('action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Students'), array('controller' => 'students', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Student'), array('controller' => 'students', 'action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Semesters'), array('controller' => 'semesters', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Semester'), array('controller' => 'semesters', 'action' => 'add')); ?> </li>
	</ul>
</div>
