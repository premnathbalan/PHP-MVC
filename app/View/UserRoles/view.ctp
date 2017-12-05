<div class="userRoles view userFrm">
<h2><?php echo __('User Role View'); ?></h2>
	<dl>
		<dt><?php echo __('Id'); ?></dt>
		<dd>
			<?php echo h($userRole['UserRole']['id']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('User Role'); ?></dt>
		<dd>
			<?php echo h($userRole['UserRole']['user_role']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Created'); ?></dt>
		<dd>
			<?php echo h($userRole['UserRole']['created']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Modified'); ?></dt>
		<dd>
			<?php echo h($userRole['UserRole']['modified']); ?>
			&nbsp;
		</dd>
	</dl>
</div>
