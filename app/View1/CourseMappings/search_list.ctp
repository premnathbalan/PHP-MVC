<div>
	<div id="js-load-forms"></div>
	<table cellpadding="0" cellspacing="0" border="0" class="display" id="example" style="margin-top:10px;">
	<thead>
	<tr>
			<th>Specialisation</th>
			<th>Total&nbsp;Semester</th>
			<th>Total&nbsp;Course</th>
			<th class="actions">&nbsp;&nbsp;&nbsp;Actions&nbsp;&nbsp;&nbsp;</th>
	</tr>
	</thead>
	<tbody>
	<?php foreach ($courseMappings as $courseMapping): ?>
	<tr class="gradeX">
		<td><?php echo $courseMapping['PG']['program_name']; ?></td>
		<td><?php echo $courseMapping['PG']['semesterNo']; ?></td>
		<td><?php if($courseMapping[0]['subjectNo'] !=0){echo $courseMapping[0]['subjectNo'];} ?></td>		
		<td class="actions">
			<?php //echo $this->Html->link("<i class='fa fa-eye fa-lg'></i>",array("controller"=>"CourseMappings",'action' => 'view',$courseMapping['CourseMapping']['id']), array('class' =>"js-popup",'escape' => false)); ?>
			<?php //echo $this->Form->postLink("<i class='fa fa-pencil fa-lg'></i>",array("controller"=>"CourseMappings",'action' => 'findNoOfSemesters',$BatchId,$ProgramId),array('class' =>"js-data-set-edit pointer", 'escape' => false)); ?>
			<?php if($this->Html->checkPathAccesstopath('CourseMappings/add','',$authUser['id'])){ ?>			
			<i class='fa fa-pencil fa-lg js-data-set' style='cursor:pointer;' alt='<?php echo $courseMapping['PG']['id']; ?>'></i>
			<?php } ?>
		</td>
	</tr>
<?php endforeach; ?>
	</tbody>
	<tfoot>
		<tr>
			<th><input type="text" name="Specialisation" value="Specialisation" class="search_init" /></th>
			<th><input type="text" name="Semester Total" value="Semester Total" class="search_init" /></th>
			<th><input type="text" name="Course Total" value="Subject Total" class="search_init" /></th>
			<th></th>
		</tr>
	</tfoot><?php 
echo $this->Html->script('common');
echo $this->Html->script('common-front');
?>
<script>leftMenuSelection('CourseMappings');</script>
<span class='breadcrumb1'>
<span class='navbar-brand'><small>MASTERS <i class='ace-icon fa fa-angle-double-right'></i></small></span>
<?php echo $this->Html->link("<span class='navbar-brand'><small>COURSE MAPPINGS <i class='ace-icon fa fa-angle-double-right'></i></small></span>", array("controller"=>"CourseMappings",'action' => 'index'),array('data-placement'=>'left','escape' => false)); ?>
</span>	
	</table>
</div>