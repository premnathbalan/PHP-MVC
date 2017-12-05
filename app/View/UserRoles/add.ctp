<div class="userRoles form deptFrm">
<?php echo $this->Form->create('UserRole'); ?>
		<legend><?php echo __('Add User Role'); ?></legend>
	<?php
		echo $this->Form->input('user_role');
	?>
<?php echo $this->Form->end(__('Submit')); ?>
</div>