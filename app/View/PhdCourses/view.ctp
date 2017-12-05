<div class="courses view userFrm">
<legend><?php echo __('PhdCourse'); ?></legend>
<dl>
	<dd><?php echo __('Course Name'); ?></dd>
	<dt><?php echo h($phdCourse['PhdCourse']['course_name']); ?></dt>
	<dd><?php echo __('Course Code'); ?></dd>
	<dt><?php echo h($phdCourse['PhdCourse']['course_code']); ?></dt>
	<dd><?php echo __('Course Max Marks'); ?></dd>
	<dt><?php if($phdCourse['PhdCourse']['course_max_marks'] !=0){echo h($phdCourse['PhdCourse']['course_max_marks']);} ?></dt>
	<dd><?php echo __('Total Min Pass Mark'.' (%)'); ?></dd>
	<dt><?php if($phdCourse['PhdCourse']['total_min_pass_percent'] !=0){echo h($phdCourse['PhdCourse']['total_min_pass_percent']);} ?>	</dt>
	
	<dd><?php echo __('Created By'); ?></dd>
	<dt>
		<?php echo h($phdCourse['User']['username']); ?>
	</dt>
	<dd><?php echo __('Created On'); ?></dd>
	<dt>
		<?php echo date( "d-M-Y h:i:s", strtotime(h($phdCourse['PhdCourse']['created'])) ); ?>
	</dt>
	
	<?php if(h($phdCourse['ModifiedUser']['username'])){?>		
	<dt><?php echo __('Modified By'); ?></dt>		
	<dd>
		<?php echo h($phdCourse['ModifiedUser']['username']); ?>
	</dd>
	<dt><?php echo __('Modified On'); ?></dt>
	<dd>
		<?php echo date( "d-M-Y h:i:s", strtotime(h($phdCourse['PhdCourse']['modified'])) ); ?>
	</dd>
	<?php }?>
</dl>
</div>