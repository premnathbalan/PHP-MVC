<div id="js-load-forms"></div>
	
	<div class="col-lg-12" style="text-align:center;">
	<?php 
	if($this->Html->checkPathAccesstopath('PhdCourses/add','',$authUser['id'])){
		echo $this->Html->link( '<i class="ace-icon fa fa-plus-square"></i>'. 'Add Course', array("controller"=>"PhdCourses",'action'=>'add'),array('class' =>"js-popup addBtn btn",'escape' => false, 'title'=>'Add Courses','style'=>'margin-bottom:5px;')); 
	}
	?>
	</div>

	<table cellpadding="0" cellspacing="0" class="display" id="example" style="margin-top:10px;">
	<thead>
	<tr>
			<th class="actions">&nbsp;&nbsp;Actions&nbsp;&nbsp;&nbsp;&nbsp;</th>
			<th>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Course&nbsp;Name&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>
			<th>Course&nbsp;Code</th>
			<th>Course&nbsp;Max&nbsp;Marks</th>
			<th>Min&nbsp;Pass&nbsp;Mark</th>
			<th class="actions">&nbsp;&nbsp;Delete&nbsp;&nbsp;&nbsp;&nbsp;</th>
	</tr>
	</thead>
	<tbody>
	<?php 
	//pr($phdCourses);
	foreach ($phdCourses as $course): ?>
	<tr class="gradeX">
		<td class="actions">
			<?php 
			if($this->Html->checkPathAccesstopath('PhdCourses/view','',$authUser['id'])){
				echo $this->Html->link("<i class='fa fa-eye fa-lg'></i>", array("controller"=>"PhdCourses",'action' => 'view', $course['PhdCourse']['id']),array('class' =>"js-popup",'escape' => false, 'title'=>'View')); 
			}?>
			&nbsp;
			<?php if($this->Html->checkPathAccesstopath('PhdCourses/edit','',$authUser['id'])){
				echo $this->Html->link("<i class='fa fa-pencil fa-lg'></i>", array("controller"=>"PhdCourses",'action' => 'edit', $course['PhdCourse']['id']),array('class' =>"js-popup", 'title'=>'Edit','escape' => false));
			}?>
		</td>
		<td><?php echo h($course['PhdCourse']['course_name']); ?></td>
		<td><?php echo h($course['PhdCourse']['course_code']); ?></td>
		<td><?php if($course['PhdCourse']['course_max_marks'] != 0){echo h($course['PhdCourse']['course_max_marks']);} ?></td>
		<td><?php if($course['PhdCourse']['total_min_pass_percent'] != 0){echo h($course['PhdCourse']['total_min_pass_percent']);} ?></td>
		<td class="actions">
			<?php if($this->Html->checkPathAccesstopath('PhdCourses/delete','',$authUser['id'])){
				echo $this->Form->postLink("<span class='fa fa-times fa-lg red'></span>", array("controller"=>"PhdCourses",'action' => 'delete', $course['PhdCourse']['id']), array('confirm' => __('Are you sure you want to delete?'),'escape' => false, 'title'=>'Delete'));
			} 
			?>
		</td>
	</tr>
<?php endforeach; ?>
	</tbody>
	<tfoot>
		<tr>
			<th><input type="text" name="&nbsp;&nbsp;Action&nbsp;&nbsp;" value="&nbsp;&nbsp;Action&nbsp;&nbsp;" class="search_init" /></th>
			<th><input type="text" name="Course Name" value="Course Name" class="search_init" /></th>
			<th><input type="text" name="Course Code" value="Course Code" class="search_init" /></th>
			<th><input type="text" name="Course Max Marks" value="Course Max Marks" class="search_init" /></th>
			<th><input type="text" name="Min ESE Mark" value="Max ESE Mark" class="search_init" /></th>
			<th></th>
		</tr>
	</tfoot>	
	</table>
	
	<span class='breadcrumb1'>
<span class='navbar-brand'><small>MASTERS <i class='ace-icon fa fa-angle-double-right'></i></small></span>
<?php echo $this->Html->link("<span class='navbar-brand'><small>COURSES <i class='ace-icon fa fa-angle-double-right'></i></small></span>", array("controller"=>"Courses",'action' => 'index'),array('data-placement'=>'left','escape' => false)); ?>
</span>