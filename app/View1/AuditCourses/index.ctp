<div id="js-load-forms"></div>
<div class="auditCourses index">

	<div class="col-lg-12" style="text-align:center;">
	<?php 
	if($this->Html->checkPathAccesstopath('AuditCourses/add','',$authUser['id'])){
		echo $this->Html->link( '<i class="ace-icon fa fa-plus-square"></i>'. 'Add Course', array("controller"=>"AuditCourses",'action'=>'add'),array('class' =>"js-popup addBtn btn",'escape' => false, 'title'=>'Add Courses','style'=>'margin-bottom:5px;')); 
	}
	?>
	</div>
	
	<table cellpadding="0" cellspacing="0" class="display" id="example" style="margin-top:10px;">
	<thead>
	<tr>
			<th class="actions">&nbsp;&nbsp;Actions&nbsp;&nbsp;&nbsp;&nbsp;</th>
			<th>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Course&nbsp;Name&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>
			<th>Course&nbsp;Code</th>
			<th>Common&nbsp;Code</th>
			<th>Course&nbsp;Max&nbsp;Marks</th>
			<th>Total&nbsp;Min&nbsp;Pass&nbsp;Mark</th>
			<th class="actions">&nbsp;&nbsp;Delete&nbsp;&nbsp;&nbsp;&nbsp;</th>
	</tr>
	</thead>
	<tbody>
	<?php foreach ($auditCourses as $auditCourse): ?>
	<tr>
		<td class="actions">
			<?php 
			if($this->Html->checkPathAccesstopath('AuditCourses/view','',$authUser['id'])){
				echo $this->Html->link("<i class='fa fa-eye fa-lg'></i>", array("controller"=>"AuditCourses",'action' => 'view', $auditCourse['AuditCourse']['id']),array('class' =>"js-popup",'escape' => false, 'title'=>'View')); 
			}?>
			&nbsp;
			<?php if($this->Html->checkPathAccesstopath('AuditCourses/edit','',$authUser['id'])){
				echo $this->Html->link("<i class='fa fa-pencil fa-lg'></i>", array("controller"=>"AuditCourses",'action' => 'edit', $auditCourse['AuditCourse']['id']),array('class' =>"js-popup", 'title'=>'Edit','escape' => false));
			}?>
		</td>
		<td><?php echo h($auditCourse['AuditCourse']['course_name']); ?>&nbsp;</td>
		<td><?php echo h($auditCourse['AuditCourse']['course_code']); ?>&nbsp;</td>
		<td><?php echo h($auditCourse['AuditCourse']['common_code']); ?>&nbsp;</td>
		<td><?php echo h($auditCourse['AuditCourse']['course_max_marks']); ?>&nbsp;</td>
		<td><?php echo h($auditCourse['AuditCourse']['total_min_pass_mark']); ?>&nbsp;</td>
		<td class="actions">
			<?php if($this->Html->checkPathAccesstopath('AuditCourses/delete','',$authUser['id'])){
				echo $this->Form->postLink("<span class='fa fa-times fa-lg red'></span>", array("controller"=>"AuditCourses",'action' => 'delete', $auditCourse['AuditCourse']['id']), array('confirm' => __('Are you sure you want to delete?'),'escape' => false, 'title'=>'Delete'));
			} 
			?>
		</td>
	</tr>
<?php endforeach; ?>
	</tbody>
	<tfoot>
		<tr>
			<th></th>
			<th><input type="text" name="Course Name" value="Course Name" class="search_init" /></th>
			<th><input type="text" name="Course Code" value="Course Code" class="search_init" /></th>
			<th><input type="text" name="Common Code" value="Common Code" class="search_init" /></th>
			<th><input type="text" name="Course Max Marks" value="Course Max Marks" class="search_init" /></th>
			<th><input type="text" name="Total Min Pass Mark" value="Total Min Pass Mark" class="search_init" /></th>
			<th></th>
		</tr>
	</tfoot>	
	</table>
</div>

<span class='breadcrumb1'>
<span class='navbar-brand'><small>MASTERS <i class='ace-icon fa fa-angle-double-right'></i></small></span>
<?php echo $this->Html->link("<span class='navbar-brand'><small>AUDIT COURSES <i class='ace-icon fa fa-angle-double-right'></i></small></span>", array("controller"=>"AuditCourses",'action' => 'index'),array('data-placement'=>'left','escape' => false)); ?>
</span>