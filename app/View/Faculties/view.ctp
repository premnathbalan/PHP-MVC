<div class="faculties view userFrm">
<legend><?php echo __('Faculty View'); ?></legend>
	<dl>		
		<dt><?php echo __('Faculty Name'); ?></dt>
		<dd><?php echo h($faculty['Faculty']['faculty_name']); ?></dd>
		<dt><?php echo __('Faculty Name Tamil'); ?></dt>
		<dd class='baamini'><?php echo h($faculty['Faculty']['faculty_name_tamil']); ?></dd>
		
		<dt><?php echo __('Created By'); ?></dt>
		<dd><?php echo h($faculty['User']['username']); ?></dd>
		<dt><?php echo __('Created On'); ?></dt>
		<dd><?php echo date( "d-M-Y h:i:s", strtotime(h($faculty['Faculty']['created'])) ); ?></dd>
		
		<?php if(h($faculty['ModifiedUser']['username'])){?>		
		<dt><?php echo __('Modified By'); ?></dt>		
		<dd><?php echo h($faculty['ModifiedUser']['username']); ?></dd>
		<dt><?php echo __('Modified On'); ?></dt>
		<dd><?php echo date( "d-M-Y h:i:s", strtotime(h($faculty['Faculty']['modified'])) ); ?></dd>
		<?php }?>
	</dl>
</div>
