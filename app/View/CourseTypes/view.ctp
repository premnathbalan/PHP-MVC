<div class="courseTypes view userFrm">
<h2><?php echo __('Course Type'); ?></h2>
	<dl>
		<dt><?php echo __('Id'); ?></dt>
		<dd>
			<?php echo h($courseType['CourseType']['id']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Course Type'); ?></dt>
		<dd>
			<?php echo h($courseType['CourseType']['course_type']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Created By'); ?></dt>
	<dd><?php echo h($courseType['User']['username']); ?></dd>
	
	<dt><?php echo __('Created On'); ?></dt>
	<dd><?php echo date( "d-M-Y h:i:s", strtotime(h($courseType['CourseType']['created'])) ); ?></dd>
	
	<?php if(h($courseType['ModifiedUser']['username'])){?>		
	<dt><?php echo __('Modified By'); ?></dt>		
	<dd>
		<?php echo h($courseType['ModifiedUser']['username']); ?>
	</dd>
	<dt><?php echo __('Modified On'); ?></dt>
	<dd>
		<?php echo date( "d-M-Y h:i:s", strtotime(h($courseType['CourseType']['modified'])) ); ?>
	</dd>
	<?php }?>
	</dl>
</div>