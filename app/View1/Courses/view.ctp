<div class="courses view userFrm">
<legend><?php echo __('Course'); ?></legend>
<dl>
	<dd><?php echo __('Course Name'); ?></dd>
	<dt><?php echo h($course['Course']['course_name']); ?></dt>
	<dd><?php echo __('Course Code'); ?></dd>
	<dt><?php echo h($course['Course']['course_code']); ?></dt>
	<dd><?php echo __('Common Code'); ?></dd>
	<dt><?php echo h($course['Course']['common_code']); ?></dt>
	<dd><?php echo __('Board'); ?></td>
	<dt><?php echo h($course['Course']['board']); ?></dt>
	<dd><?php echo __('Course Category'); ?></dd>
	<dt><?php echo $this->Html->link($course['CourseType']['course_type'], array('controller' => 'course_types', 'action' => 'view', $course['CourseType']['id'])); ?></dt>
	<dd><?php echo __('Credit Point'); ?></dd>
	<dt><?php echo h($course['Course']['credit_point']); ?>	</dt>
	<dd><?php echo __('Course Max Marks'); ?></dd>
	<dt><?php if($course['Course']['course_max_marks'] !=0){echo h($course['Course']['course_max_marks']);} ?></dt>
	<dd><?php echo __('Min CAE Mark'.' (%)'); ?></dd>
	<dt><?php if($course['Course']['min_cae_mark'] !=0){echo h($course['Course']['min_cae_mark']);} ?>	</dt>
	<dd><?php echo __('Min ESE Mark'.' (%)'); ?></dd>
	<dt><?php if($course['Course']['min_ese_mark'] !=0){echo h($course['Course']['min_ese_mark']);} ?>	</dt>
	<dd><?php echo __('Max CAE Mark'); ?></dd>
	<dt><?php if($course['Course']['max_cae_mark'] !=0){echo h($course['Course']['max_cae_mark']);} ?>	</dt>
	<dd><?php echo __('Max ESE Mark'); ?></dd>
	<dt><?php if($course['Course']['max_ese_mark'] !=0){echo h($course['Course']['max_ese_mark']);} ?>	</dt>
	<dd><?php echo __('ESE QP Mark'; ?></dd>
	<dt><?php if($course['Course']['max_ese_qp_mark'] !=0){echo h($course['Course']['max_ese_qp_mark']);} ?>	</dt>
	<dd><?php echo __('Total Min Pass Mark'.' (%)'); ?></dd>
	<dt><?php if($course['Course']['total_min_pass'] !=0){echo h($course['Course']['total_min_pass']);} ?>	</dt>
	
	<dt><?php echo __('Created By'); ?></dt>
	<dd>
		<?php echo h($course['User']['username']); ?>
	</dd>
	<dt><?php echo __('Created On'); ?></dt>
	<dd>
		<?php echo date( "d-M-Y h:i:s", strtotime(h($course['Course']['created'])) ); ?>
	</dd>
	
	<?php if(h($course['ModifiedUser']['username'])){?>		
	<dt><?php echo __('Modified By'); ?></dt>		
	<dd>
		<?php echo h($course['ModifiedUser']['username']); ?>
	</dd>
	<dt><?php echo __('Modified On'); ?></dt>
	<dd>
		<?php echo date( "d-M-Y h:i:s", strtotime(h($course['Course']['modified'])) ); ?>
	</dd>
	<?php }?>
</dl>
</div>