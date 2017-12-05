<div id="js-load-forms"></div>

<div style="height:40px;">
		<?php echo $this->Html->link('<i class="ace-icon fa fa-plus-square"></i>'. 'Add CAE', array("controller"=>"CaePracticals",'action'=>'addCaePractical'),array('class' =>" addBtn btn js-cae-add",'escape' => false, 'title'=>'Add')); ?>
</div>

<div class="caes index">
<div class="searchFrm bgFrame1" style="margin-top:5px;">
	<?php echo $this->Form->create('Student');?>
	<div class="searchFrm col-sm-12">
		<?php echo $this->element('search_fields'); ?>
		
		<div class="col-lg-3">	
			<?php echo $this->Form->button('<i class="ace-icon fa fa-check bigger-110"></i>'.__('Submit'),array('type'=>'button','id'=>'js-cae-display', 'name'=>'submit','value'=>'submit','class'=>'btn js-pt'));?>
		</div>
		
		<div class="col-lg-3">	
			<?php echo $this->Form->button('<i class="ace-icon fa fa-undo bigger-110"></i> '.'&nbsp;Reset&nbsp;',array('type'=>'reset','name'=>'submit','value'=>'submit','class'=>'btn'));?>
		</div>
	</div>
</div>
	
</div>

<div id="courses" class="result"></div>

<?php echo $this->Form->end(); ?>
<?php echo $this->Html->script('common'); ?>

<span class='breadcrumb1'>
<span class='navbar-brand'><small>MARKS <i class='ace-icon fa fa-angle-double-right'></i></small></span>
<span class='navbar-brand'><small>Practical <i class='ace-icon fa fa-angle-double-right'></i></small></span>
<?php echo $this->Html->link("<span class='navbar-brand'><small> C.A.E <i class='ace-icon fa fa-angle-double-right'></i></small></span>", array("controller"=>"CaePracticals",'action' => 'practical'),array('data-placement'=>'left','escape' => false)); ?>
</span>
<!--<div class="professionalTrainings index">
	<h2><?php echo __('Professional Trainings'); ?></h2>
	<table cellpadding="0" cellspacing="0">
	<thead>
	<tr>
			<th><?php echo $this->Paginator->sort('id'); ?></th>
			<th><?php echo $this->Paginator->sort('month_year_id'); ?></th>
			<th><?php echo $this->Paginator->sort('student_id'); ?></th>
			<th><?php echo $this->Paginator->sort('course_mapping_id'); ?></th>
			<th><?php echo $this->Paginator->sort('marks'); ?></th>
			<th><?php echo $this->Paginator->sort('moderation_operator'); ?></th>
			<th><?php echo $this->Paginator->sort('moderation_marks'); ?></th>
			<th><?php echo $this->Paginator->sort('created_by'); ?></th>
			<th><?php echo $this->Paginator->sort('modified_by'); ?></th>
			<th><?php echo $this->Paginator->sort('created'); ?></th>
			<th><?php echo $this->Paginator->sort('modified'); ?></th>
			<th class="actions"><?php echo __('Actions'); ?></th>
	</tr>
	</thead>
	<tbody>
	<?php foreach ($professionalTrainings as $professionalTraining): ?>
	<tr>
		<td><?php echo h($professionalTraining['ProfessionalTraining']['id']); ?>&nbsp;</td>
		<td>
			<?php echo $this->Html->link($professionalTraining['MonthYear']['month_id'], array('controller' => 'month_years', 'action' => 'view', $professionalTraining['MonthYear']['id'])); ?>
		</td>
		<td>
			<?php echo $this->Html->link($professionalTraining['Student']['name'], array('controller' => 'students', 'action' => 'view', $professionalTraining['Student']['id'])); ?>
		</td>
		<td>
			<?php echo $this->Html->link($professionalTraining['CourseMapping']['id'], array('controller' => 'course_mappings', 'action' => 'view', $professionalTraining['CourseMapping']['id'])); ?>
		</td>
		<td><?php echo h($professionalTraining['ProfessionalTraining']['marks']); ?>&nbsp;</td>
		<td><?php echo h($professionalTraining['ProfessionalTraining']['moderation_operator']); ?>&nbsp;</td>
		<td><?php echo h($professionalTraining['ProfessionalTraining']['moderation_marks']); ?>&nbsp;</td>
		<td><?php echo h($professionalTraining['ProfessionalTraining']['created_by']); ?>&nbsp;</td>
		<td><?php echo h($professionalTraining['ProfessionalTraining']['modified_by']); ?>&nbsp;</td>
		<td><?php echo h($professionalTraining['ProfessionalTraining']['created']); ?>&nbsp;</td>
		<td><?php echo h($professionalTraining['ProfessionalTraining']['modified']); ?>&nbsp;</td>
		<td class="actions">
			<?php echo $this->Html->link(__('View'), array('action' => 'view', $professionalTraining['ProfessionalTraining']['id'])); ?>
			<?php echo $this->Html->link(__('Edit'), array('action' => 'edit', $professionalTraining['ProfessionalTraining']['id'])); ?>
			<?php echo $this->Form->postLink(__('Delete'), array('action' => 'delete', $professionalTraining['ProfessionalTraining']['id']), array('confirm' => __('Are you sure you want to delete # %s?', $professionalTraining['ProfessionalTraining']['id']))); ?>
		</td>
	</tr>
<?php endforeach; ?>
	</tbody>
	</table>
</div>
<div class="actions">
	<h3><?php echo __('Actions'); ?></h3>
	<ul>
		<li><?php echo $this->Html->link(__('New Professional Training'), array('action' => 'add')); ?></li>
		<li><?php echo $this->Html->link(__('List Month Years'), array('controller' => 'month_years', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Month Year'), array('controller' => 'month_years', 'action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Students'), array('controller' => 'students', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Student'), array('controller' => 'students', 'action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Course Mappings'), array('controller' => 'course_mappings', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Course Mapping'), array('controller' => 'course_mappings', 'action' => 'add')); ?> </li>
	</ul>
</div>-->