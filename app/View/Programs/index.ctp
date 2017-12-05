<div id="js-load-forms"></div>

	<?php 
	if($this->Html->checkPathAccesstopath('Programs/add','',$authUser['id'])){
		echo $this->Html->link( '<i class="ace-icon fa fa-plus-square"></i>'. 'Add Specialisation', array("controller"=>"programs",'action'=>'add'),array('class' =>"js-popup addBtn btn",'escape' => false, 'title'=>'Add Specialisation','style'=>'margin-bottom:5px;')); 
	}
	?>

	<table cellpadding="0" cellspacing="0" class="display" id="example">
	<thead>
	<tr>
		<th class="thAction">&nbsp;&nbsp;Action&nbsp;&nbsp;</th>
		<th>Program</th>
		<th>Specialisation&nbsp;name</th>
		<th>Faculty</th>
		<th>Short&nbsp;Code</th>
		<th>Specialisation&nbsp;Name&nbsp;Tamil</th>
		<th>Semester</th>
		<th>Credits</th>
		<th>Alternate&nbsp;Name</th>
		<th class="thAction">&nbsp;&nbsp;Delete&nbsp;&nbsp;</th>
	</tr>
	</thead>
	<tbody>
	<?php foreach ($programs as $program):?>
	<tr class="gradeX">
		<td class="actions">
			<?php 
			if($this->Html->checkPathAccesstopath('Programs/view','',$authUser['id'])){
				echo $this->Html->link("<i class='fa fa-eye fa-lg'></i>", array("controller"=>"programs",'action' => 'view', $program['Program']['id']),array('class' =>"js-popup",'escape' => false, 'title'=>'View')); 
			}if($this->Html->checkPathAccesstopath('Programs/edit','',$authUser['id'])){
				echo $this->Html->link("<i class='fa fa-pencil fa-lg'></i>", array("controller"=>"programs",'action' => 'edit', $program['Program']['id']),array('class' =>"js-popup", 'title'=>'Edit','escape' => false));
			}
			?>
		</td>
		<td>
			<?php echo $this->Html->link($program['Academic']['academic_name'], array('controller' => 'academics', 'action' => 'view', $program['Academic']['id'])); ?>
		</td>
		<td><?php echo h($program['Program']['program_name']); ?></td>
		<td><?php echo h($program['Program']['short_code']); ?></td>
		<td class='baamini'><?php echo h($program['Program']['program_name_tamil']); ?></td>
		<td><?php echo h($program['Faculty']['faculty_name']); ?></td>
		<td><?php echo h($program['Program']['semester_id']); ?></td>
		<td><?php if($program['Program']['credits']){echo h($program['Program']['credits']);} ?></td>
		<td><?php echo h($program['Program']['alternate_name']); ?></td>
		<td class="actions">
			<?php 
			if($this->Html->checkPathAccesstopath('Programs/delete','',$authUser['id'])){
				echo $this->Form->postLink("<span class='fa fa-times fa-lg red'></span>", array("controller"=>"programs",'action' => 'delete', $program['Program']['id']), array('confirm' => __('Are you sure you want to delete?'),'escape' => false, 'title'=>'Delete')); 
			}
			?>
		</td>
	</tr>
	<?php endforeach; ?>
	</tbody>
	<tfoot>
		<tr>
			<th><input type="text" name="&nbsp;&nbsp;Action&nbsp;&nbsp;" value="&nbsp;&nbsp;Action&nbsp;&nbsp;" class="search_init" /></th>
			<th><input type="text" name="Program" value="Program" class="search_init" /></th>
			<th><input type="text" name="Specialisation&nbsp;name" value="Specialisation&nbsp;name" class="search_init" /></th>
			<th><input type="text" name="Faculty" value="Faculty" class="search_init" /></th>
			<th><input type="text" name="Short&nbsp;Code" value="Short&nbsp;Code" class="search_init" /></th>
			<th><input type="text" name="Specialisation&nbsp;Name&nbsp;Tamil" value="Specialisation&nbsp;Name&nbsp;Tamil" class="search_init" /></th>
			<th><input type="text" name="Semester" value="Semester" class="search_init" /></th>
			<th><input type="text" name="Credits" value="Credits" class="search_init" /></th>
			<th><input type="text" name="Alternate Name" value="Alternate Name" class="search_init" /></th>
			<th></th>
		</tr>
	</tfoot>
	</table>

<span class='breadcrumb1'>
<span class='navbar-brand'><small>MASTERS <i class='ace-icon fa fa-angle-double-right'></i></small></span>
<?php echo $this->Html->link("<span class='navbar-brand'><small>Specialisation <i class='ace-icon fa fa-angle-double-right'></i></small></span>", array("controller"=>"Programs",'action' => 'index'),array('data-placement'=>'left','escape' => false)); ?>
</span>