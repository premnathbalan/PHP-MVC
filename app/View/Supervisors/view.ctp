<div class="supervisors view userFrm">
	<legend><?php echo __('Supervisor View'); ?></legend>
	<dl>
		<!--<dt><?php echo __('Id'); ?></dt>
		<dd>
			<?php echo h($supervisor['Supervisor']['id']); ?>
		</dd>-->
		<dt><?php echo __('Supervisor Name'); ?></dt>
		<dd>
			<?php echo h($supervisor['Supervisor']['supervisor_name']); ?>
		</dd>
		<dt><?php echo __('Supervisor Name Tamil'); ?></dt>
		<dd class='baamini'>
			<?php echo h($supervisor['Supervisor']['supervisor_name_tamil']); ?>
		</dd>
		<dt><?php echo __('Created By'); ?></dt>
		<dd>
			<?php echo h($supervisor['User']['username']); ?>
		</dd>
		<dt><?php echo __('Created On'); ?></dt>
		<dd>
			<?php echo date( "d-M-Y h:i:s", strtotime(h($supervisor['Supervisor']['created'])) ); ?>
		</dd>
		
		<?php if(h($supervisor['ModifiedUser']['username'])){?>		
		<dt><?php echo __('Modified By'); ?></dt>		
		<dd>
			<?php echo h($supervisor['ModifiedUser']['username']); ?>
		</dd>
		<dt><?php echo __('Modified On'); ?></dt>
		<dd>
			<?php echo date( "d-M-Y h:i:s", strtotime(h($supervisor['Supervisor']['modified'])) ); ?>
		</dd>
		<?php }?>
		
	</dl>
</div>
