<div class="phdStudents view">
<h2><?php echo __('Phd Student'); ?></h2>
	<dl>
		<dt><?php echo __('Id'); ?></dt>
		<dd>
			<?php echo h($phdStudent['PhdStudent']['id']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Name'); ?></dt>
		<dd>
			<?php echo h($phdStudent['PhdStudent']['name']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Birth Date'); ?></dt>
		<dd>
			<?php echo h($phdStudent['PhdStudent']['birth_date']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Gender'); ?></dt>
		<dd>
			<?php echo h($phdStudent['PhdStudent']['gender']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Father Name'); ?></dt>
		<dd>
			<?php echo h($phdStudent['PhdStudent']['father_name']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Address'); ?></dt>
		<dd>
			<?php echo h($phdStudent['PhdStudent']['address']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Mobile Number'); ?></dt>
		<dd>
			<?php echo h($phdStudent['PhdStudent']['mobile_number']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Email'); ?></dt>
		<dd>
			<?php echo h($phdStudent['PhdStudent']['email']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Faculty'); ?></dt>
		<dd>
			<?php echo $this->Html->link($phdStudent['Faculty']['faculty_name'], array('controller' => 'faculties', 'action' => 'view', $phdStudent['Faculty']['id'])); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Thesi'); ?></dt>
		<dd>
			<?php echo $this->Html->link($phdStudent['Thesi']['id'], array('controller' => 'thesis', 'action' => 'view', $phdStudent['Thesi']['id'])); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Discipline'); ?></dt>
		<dd>
			<?php echo $this->Html->link($phdStudent['Discipline']['id'], array('controller' => 'disciplines', 'action' => 'view', $phdStudent['Discipline']['id'])); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Supervisor'); ?></dt>
		<dd>
			<?php echo $this->Html->link($phdStudent['Supervisor']['id'], array('controller' => 'supervisors', 'action' => 'view', $phdStudent['Supervisor']['id'])); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Year Of Register'); ?></dt>
		<dd>
			<?php echo h($phdStudent['PhdStudent']['year_of_register']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Month'); ?></dt>
		<dd>
			<?php echo $this->Html->link($phdStudent['Month']['month_name'], array('controller' => 'months', 'action' => 'view', $phdStudent['Month']['id'])); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Date Of Register'); ?></dt>
		<dd>
			<?php echo h($phdStudent['PhdStudent']['date_of_register']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Month Year'); ?></dt>
		<dd>
			<?php echo $this->Html->link($phdStudent['MonthYear']['month_id'], array('controller' => 'month_years', 'action' => 'view', $phdStudent['MonthYear']['id'])); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Picture'); ?></dt>
		<dd>
			<?php echo h($phdStudent['PhdStudent']['picture']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Created By'); ?></dt>
		<dd>
			<?php echo h($phdStudent['PhdStudent']['created_by']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Modified By'); ?></dt>
		<dd>
			<?php echo h($phdStudent['PhdStudent']['modified_by']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Created'); ?></dt>
		<dd>
			<?php echo h($phdStudent['PhdStudent']['created']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Modified'); ?></dt>
		<dd>
			<?php echo h($phdStudent['PhdStudent']['modified']); ?>
			&nbsp;
		</dd>
	</dl>
</div>
<div class="actions">
	<h3><?php echo __('Actions'); ?></h3>
	<ul>
		<li><?php echo $this->Html->link(__('Edit Phd Student'), array('action' => 'edit', $phdStudent['PhdStudent']['id'])); ?> </li>
		<li><?php echo $this->Form->postLink(__('Delete Phd Student'), array('action' => 'delete', $phdStudent['PhdStudent']['id']), array('confirm' => __('Are you sure you want to delete # %s?', $phdStudent['PhdStudent']['id']))); ?> </li>
		<li><?php echo $this->Html->link(__('List Phd Students'), array('action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Phd Student'), array('action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Faculties'), array('controller' => 'faculties', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Faculty'), array('controller' => 'faculties', 'action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Thesis'), array('controller' => 'thesis', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Thesi'), array('controller' => 'thesis', 'action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Disciplines'), array('controller' => 'disciplines', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Discipline'), array('controller' => 'disciplines', 'action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Supervisors'), array('controller' => 'supervisors', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Supervisor'), array('controller' => 'supervisors', 'action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Months'), array('controller' => 'months', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Month'), array('controller' => 'months', 'action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Month Years'), array('controller' => 'month_years', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Month Year'), array('controller' => 'month_years', 'action' => 'add')); ?> </li>
	</ul>
</div>
