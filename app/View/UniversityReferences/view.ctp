<div class="universityReferences view userFrm">
	<legend><?php echo __('University Reference'); ?></legend>
	<dl>
		<dt><?php echo __('University Name'); ?></dt>
		<dd><?php echo h($universityReference['UniversityReference']['university_name']); ?></dd>
		
		<dt><?php echo __('Created By'); ?></dt>
		<dd><?php echo h($universityReference['User']['username']); ?></dd>
		<dt><?php echo __('Created On'); ?></dt>
		<dd><?php echo date( "d-M-Y h:i:s", strtotime(h($universityReference['UniversityReference']['created'])) ); ?></dd>
		
		<?php if(h($universityReference['ModifiedUser']['username'])){?>		
		<dt><?php echo __('Modified By'); ?></dt>		
		<dd><?php echo h($universityReference['ModifiedUser']['username']); ?></dd>
		<dt><?php echo __('Modified On'); ?></dt>
		<dd><?php echo date( "d-M-Y h:i:s", strtotime(h($universityReference['UniversityReference']['modified'])) ); ?></dd>
		<?php }?>
	</dl>
</div>