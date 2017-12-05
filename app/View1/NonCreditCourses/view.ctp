<div class="nonCreditCourses view userFrm">
	<legend><?php echo __('Non Credit Course View'); ?></legend>
	<dl>
		<dt><?php echo __('Non Credit Course Name'); ?></dt>
		<dd><?php echo h($nonCreditCourse['NonCreditCourse']['non_credit_course_name']); ?></dd>
		
		<dt><?php echo __('Created By'); ?></dt>
		<dd><?php echo h($nonCreditCourse['User']['username']); ?></dd>
		<dt><?php echo __('Created On'); ?></dt>
		<dd><?php echo date( "d-M-Y h:i:s", strtotime(h($nonCreditCourse['NonCreditCourse']['created'])) ); ?></dd>
		
		<?php if(h($nonCreditCourse['ModifiedUser']['username'])){?>		
		<dt><?php echo __('Modified By'); ?></dt>		
		<dd><?php echo h($nonCreditCourse['ModifiedUser']['username']); ?></dd>
		<dt><?php echo __('Modified On'); ?></dt>
		<dd><?php echo date( "d-M-Y h:i:s", strtotime(h($nonCreditCourse['NonCreditCourse']['modified'])) ); ?></dd>
		<?php }?>
	</dl>
</div>
