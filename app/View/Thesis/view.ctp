<div class="thesis view userFrm">
	<legend><?php echo __('Thesis View'); ?></legend>
	<dl>
		<!--<dt><?php echo __('Id'); ?></dt>
		<dd>
			<?php echo h($thesi['Thesi']['id']); ?>
		</dd>-->
		<dt><?php echo __('thesi Name'); ?></dt>
		<dd>
			<?php echo h($thesi['Thesi']['thesis_name']); ?>
		</dd>
		<dt><?php echo __('thesi Name Tamil'); ?></dt>
		<dd class='baamini'>
			<?php echo h($thesi['Thesi']['thesis_name_tamil']); ?>
		</dd>
		<dt><?php echo __('Created By'); ?></dt>
		<dd>
			<?php echo h($thesi['User']['username']); ?>
		</dd>
		<dt><?php echo __('Created On'); ?></dt>
		<dd>
			<?php echo date( "d-M-Y h:i:s", strtotime(h($thesi['Thesi']['created'])) ); ?>
		</dd>
		
		<?php if(h($thesi['ModifiedUser']['username'])){?>		
		<dt><?php echo __('Modified By'); ?></dt>		
		<dd>
			<?php echo h($thesi['ModifiedUser']['username']); ?>
		</dd>
		<dt><?php echo __('Modified On'); ?></dt>
		<dd>
			<?php echo date( "d-M-Y h:i:s", strtotime(h($thesi['Thesi']['modified'])) ); ?>
		</dd>
		<?php }?>
		
	</dl>
</div>
