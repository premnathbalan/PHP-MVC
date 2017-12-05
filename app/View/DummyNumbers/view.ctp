<div class="dummyNumbers view">
<h2><?php echo __('Dummy Number'); ?></h2>
	<dl>
		<dt><?php echo __('Id'); ?></dt>
		<dd>
			<?php echo h($dummyNumber['DummyNumber']['id']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Timetable Id'); ?></dt>
		<dd>
			<?php echo h($dummyNumber['DummyNumber']['timetable_id']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Start Range'); ?></dt>
		<dd>
			<?php echo h($dummyNumber['DummyNumber']['start_range']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Created By'); ?></dt>
		<dd>
			<?php echo h($dummyNumber['DummyNumber']['created_by']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Modified By'); ?></dt>
		<dd>
			<?php echo h($dummyNumber['DummyNumber']['modified_by']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Created'); ?></dt>
		<dd>
			<?php echo h($dummyNumber['DummyNumber']['created']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Modified'); ?></dt>
		<dd>
			<?php echo h($dummyNumber['DummyNumber']['modified']); ?>
			&nbsp;
		</dd>
	</dl>
</div>
<div class="actions">
	<h3><?php echo __('Actions'); ?></h3>
	<ul>
		<li><?php echo $this->Html->link(__('Edit Dummy Number'), array('action' => 'edit', $dummyNumber['DummyNumber']['id'])); ?> </li>
		<li><?php echo $this->Form->postLink(__('Delete Dummy Number'), array('action' => 'delete', $dummyNumber['DummyNumber']['id']), array('confirm' => __('Are you sure you want to delete # %s?', $dummyNumber['DummyNumber']['id']))); ?> </li>
		<li><?php echo $this->Html->link(__('List Dummy Numbers'), array('action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Dummy Number'), array('action' => 'add')); ?> </li>
	</ul>
</div>
