<div class="programs view userFrm">
<legend><?php echo __('Specialisation'); ?></legend>
	<dl>
		<dt><?php echo __('Specialisation Name'); ?></dt>
		<dd><?php echo h($program['Program']['program_name']); ?></dd>
		
		<dt><?php echo __('Program'); ?></dt>
		<dd><?php echo $this->Html->link($program['Academic']['academic_name'], array('controller' => 'academics', 'action' => 'view', $program['Academic']['id'])); ?></dd>
		
		<dt><?php echo __('Department'); ?></dt>
		<dd><?php echo h($program['Faculty']['faculty_name']); ?></dd>
		
		<dt><?php echo __('Short Code'); ?></dt>
		<dd><?php echo h($program['Academic']['short_code']); ?></dd>
		
		<dt><?php echo __('Specialisation Name Tamil'); ?></dt>
		<dd><?php echo h($program['Program']['program_name_tamil']); ?></dd>
		
		<dt><?php echo __('Credit'); ?></dt>
		<dd><?php echo h($program['Program']['credits']); ?></dd>
		
		<dt><?php echo __('Alternate Name'); ?></dt>
		<dd><?php echo h($program['Program']['alternate_name']); ?></dd>
		
		<dt><?php echo __('Semester'); ?></dt>
		<dd><?php echo h($program['Semester']['semester_name']); ?></dd>
		
		<dt><?php echo __('Created By'); ?></dt>
		<dd><?php echo h($program['User']['username']); ?></dd>
		<dt><?php echo __('Created On'); ?></dt>
		<dd><?php echo date( "d-M-Y h:i:s", strtotime(h($program['Academic']['created'])) ); ?></dd>
		
		<?php if(h($program['ModifiedUser']['username'])){?>		
		<dt><?php echo __('Modified By'); ?></dt>		
		<dd><?php echo h($program['ModifiedUser']['username']); ?></dd>
		<dt><?php echo __('Modified On'); ?></dt>
		<dd><?php echo date( "d-M-Y h:i:s", strtotime(h($program['Academic']['modified'])) ); ?></dd>
		<?php }?>
		
	</dl>
</div>