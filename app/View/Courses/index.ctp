<div id="js-load-forms"></div>
	
	<div class="col-lg-2">
	<?php 
	if($this->Html->checkPathAccesstopath('Courses/add','',$authUser['id'])){
		echo $this->Html->link( '<i class="ace-icon fa fa-download"></i>'. 'Download Template', array("controller"=>"courses",'action'=>'course_upload_template'),array('class' =>"btn",'escape' => false, 'title'=>'Course Upload Template','style'=>'margin-bottom:5px;')); 
	}
	?>
	</div>
	<div class="col-lg-1">
	<?php  ?>
	</div>
	<div class="col-lg-6 bgFrame1">
	<?php echo $this->Form->create('Course', array('type' => 'file'));?>
		<div class="col-lg-4">
			<?php echo $this->Form->input('csv', array('type' => 'file','required'=>'required', 'label' => false,'style'=>'width:200px;'));?>
		</div>
		
		<div class="col-lg-2">	
		<?php if($this->Html->checkPathAccesstopath('Courses/add','',$authUser['id'])){
			echo $this->Form->end('Submit',array('style'=>'float:right;width:90px;margin-top:-30px;')); 
		}?>
		</div>	
	</div>
	
	<div class="col-lg-1">
	<?php echo $this->Html->link('<i class="ace-icon fa fa-file-excel-o"></i>'.'Excel',array("controller"=>"Courses",'action'=>'downloadAsExcel'),array('type'=>'submit','name'=>'Excel','value'=>'Excel','class'=>'btn','escape' => false,'style'=>'float:right;')); ?>
	</div>
	
	
	<div class="col-lg-2" style="text-align:center;">
	<?php 
	if($this->Html->checkPathAccesstopath('Courses/add','',$authUser['id'])){
		echo $this->Html->link( '<i class="ace-icon fa fa-plus-square"></i>'. 'Add Course', array("controller"=>"courses",'action'=>'add'),array('class' =>"js-popup addBtn btn",'escape' => false, 'title'=>'Add Courses','style'=>'margin-bottom:5px;')); 
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
			<th>Board</th>
			<th>Course&nbsp;Category</th>
			<th>Credit&nbsp;Point</th>
			<th>Course&nbsp;Max&nbsp;Marks</th>
			<th>Min&nbsp;CAE&nbsp;Mark</th>
			<th>Max&nbsp;CAE&nbsp;Mark</th>
			<th>Min&nbsp;ESE&nbsp;Mark</th>
			<th>Max&nbsp;ESE&nbsp;Mark</th>
			<th>ESE&nbsp;QP&nbsp;Mark</th>
			<th class="actions">&nbsp;&nbsp;Delete&nbsp;&nbsp;&nbsp;&nbsp;</th>
	</tr>
	</thead>
	<tbody>
	<?php foreach ($courses as $course): ?>
	<tr class="gradeX">
		<td class="actions">
			<?php 
			if($this->Html->checkPathAccesstopath('Courses/view','',$authUser['id'])){
				echo $this->Html->link("<i class='fa fa-eye fa-lg'></i>", array("controller"=>"courses",'action' => 'view', $course['Course']['id']),array('class' =>"js-popup",'escape' => false, 'title'=>'View')); 
			}?>
			&nbsp;
			<?php if($this->Html->checkPathAccesstopath('Courses/edit','',$authUser['id'])){
				echo $this->Html->link("<i class='fa fa-pencil fa-lg'></i>", array("controller"=>"courses",'action' => 'edit', $course['Course']['id']),array('class' =>"js-popup", 'title'=>'Edit','escape' => false));
			}?>
		</td>
		<td><?php echo h($course['Course']['course_name']); ?></td>
		<td><?php echo h($course['Course']['course_code']); ?></td>
		<td><?php echo h($course['Course']['common_code']); ?></td>
		<td><?php echo h($course['Course']['board']); ?></td>
		<td><?php //echo h($course['CourseType']['course_type']); ?></td>
		<td><?php echo h($course['Course']['credit_point']); ?></td>
		<td><?php if($course['Course']['course_max_marks'] != 0){echo h($course['Course']['course_max_marks']);} ?></td>
		<td><?php if($course['Course']['min_cae_mark'] != 0){echo h($course['Course']['min_cae_mark']);} ?></td>
		<td><?php if($course['Course']['max_cae_mark'] != 0){echo h($course['Course']['max_cae_mark']);} ?></td>
		<td><?php if($course['Course']['min_ese_mark'] != 0){echo h($course['Course']['min_ese_mark']);} ?></td>
		<td><?php if($course['Course']['max_ese_mark'] != 0){echo h($course['Course']['max_ese_mark']);} ?></td>
		<td><?php if($course['Course']['max_ese_qp_mark'] != 0){echo h($course['Course']['max_ese_qp_mark']);} ?></td>
		<td class="actions">
			<?php if($this->Html->checkPathAccesstopath('Courses/delete','',$authUser['id'])){
				echo $this->Form->postLink("<span class='fa fa-times fa-lg red'></span>", array("controller"=>"courses",'action' => 'delete', $course['Course']['id']), array('confirm' => __('Are you sure you want to delete?'),'escape' => false, 'title'=>'Delete'));
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
			<th><input type="text" name="Common Code" value="Common Code" class="search_init" /></th>
			<th><input type="text" name="Board" value="Board" class="search_init" /></th>
			<th><input type="text" name="Course Type" value="Course Category" class="search_init" /></th>
			<th><input type="text" name="Credit" value="Credit" class="search_init" /></th>
			<th><input type="text" name="Course Max Marks" value="Course Max Marks" class="search_init" /></th>
			<th><input type="text" name="Min CAE Mark" value="Min CAE Mark" class="search_init" /></th>
			<th><input type="text" name="Max CAE Mark" value="Max CAE Mark" class="search_init" /></th>
			<th><input type="text" name="Min ESE Mark" value="Min ESE Mark" class="search_init" /></th>
			<th><input type="text" name="Min ESE Mark" value="Max ESE Mark" class="search_init" /></th>
			<th><input type="text" name="ESE&nbsp;QP&nbsp;Mark" value="ESE&nbsp;QP&nbsp;Mark" class="search_init" /></th>
			<th></th>
		</tr>
	</tfoot>	
	</table>
	
	<span class='breadcrumb1'>
<span class='navbar-brand'><small>MASTERS <i class='ace-icon fa fa-angle-double-right'></i></small></span>
<?php echo $this->Html->link("<span class='navbar-brand'><small>COURSES <i class='ace-icon fa fa-angle-double-right'></i></small></span>", array("controller"=>"Courses",'action' => 'index'),array('data-placement'=>'left','escape' => false)); ?>
</span>