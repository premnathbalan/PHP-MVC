<div class="departments view userFrm">
	<legend><?php echo __('Department View'); ?></legend>
	<dl>
		<!--<dt><?php echo __('Id'); ?></dt>
		<dd>
			<?php echo h($department['Department']['id']); ?>
		</dd>-->
		<dt><?php echo __('Department Name'); ?></dt>
		<dd>
			<?php echo h($department['Department']['department_name']); ?>
		</dd>
		<dt><?php echo __('Department Name Tamil'); ?></dt>
		<dd class='baamini'>
			<?php echo h($department['Department']['department_name_tamil']); ?>
		</dd>
		<dt><?php echo __('Created By'); ?></dt>
		<dd>
			<?php echo h($department['User']['username']); ?>
		</dd>
		<dt><?php echo __('Created On'); ?></dt>
		<dd>
			<?php echo date( "d-M-Y h:i:s", strtotime(h($department['Department']['created'])) ); ?>
		</dd>
		
		<?php if(h($department['ModifiedUser']['username'])){?>		
		<dt><?php echo __('Modified By'); ?></dt>		
		<dd>
			<?php echo h($department['ModifiedUser']['username']); ?>
		</dd>
		<dt><?php echo __('Modified On'); ?></dt>
		<dd>
			<?php echo date( "d-M-Y h:i:s", strtotime(h($department['Department']['modified'])) ); ?>
		</dd>
		<?php }?>
		
	</dl>
</div>
