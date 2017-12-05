<div id="js-load-forms"></div>

	<?php 
	if($this->Html->checkPathAccesstopath('Academics/add','',$authUser['id'])){
		echo $this->Html->link( '<i class="ace-icon fa fa-plus-square"></i>'. 'Add Program', array("controller"=>"academics",'action'=>'add'),array('class' =>"js-popup addBtn btn",'escape' => false, 'title'=>'Add Program','style'=>'margin-bottom:5px;')); 
	}
	?>

	<table cellpadding="0" cellspacing="0" class="display" id="example">
	<thead>
	<tr>
		<th class="thAction">&nbsp;&nbsp;Action&nbsp;&nbsp;&nbsp;&nbsp;</th>
		<th>Program&nbsp;Name</th>
		<th>Program&nbsp;Type</th>
		<th>Short&nbsp;Code</th>
		<th>Tamil&nbsp;Name</th>
		<th class="thAction">&nbsp;&nbsp;Delete&nbsp;&nbsp;&nbsp;&nbsp;</th>
	</tr>
	</thead>
	<tbody>
	<?php foreach ($academics as $academic): ?>
	<tr class="gradeX">
		<td class="actions">
			<?php 
			if($this->Html->checkPathAccesstopath('Academics/view','',$authUser['id'])){
				echo $this->Html->link("<i class='fa fa-eye fa-lg'></i>", array("controller"=>"academics",'action' => 'view', $academic['Academic']['id']),array('class' =>"js-popup",'escape' => false, 'title'=>'View')); 
			}if($this->Html->checkPathAccesstopath('Academics/edit','',$authUser['id'])){ 
				echo $this->Html->link("<i class='fa fa-pencil fa-lg'></i>", array("controller"=>"academics",'action' => 'edit', $academic['Academic']['id']),array('class' =>"js-popup", 'title'=>'Edit','escape' => false)); 
			}
			?>
		</td>
		<td><?php echo h($academic['Academic']['academic_name']); ?></td>
		<td><?php echo h($academic['Academic']['academic_type']); ?></td>
		<td><?php echo h($academic['Academic']['short_code']); ?></td>
		<td class='baamini'><?php echo h($academic['Academic']['academic_name_tamil']); ?></td>
		<td class="actions">
			<?php 
			if($this->Html->checkPathAccesstopath('Academics/delete','',$authUser['id'])){ 
				echo $this->Form->postLink("<span class='fa fa-times fa-lg red'></span>", array("controller"=>"academics",'action' => 'delete',$academic['Academic']['id']), array('confirm' => __('Are you sure you want to delete # %s?',$academic['Academic']['id']),'escape' => false, 'title'=>'Delete')); 
			}
			?>
		</td>
	</tr>
<?php endforeach; ?>
	</tbody>
	<tfoot>
		<tr>
			<th><input type="text" name="&nbsp;&nbsp;Action&nbsp;&nbsp;" value="&nbsp;&nbsp;Action&nbsp;&nbsp;" class="search_init" /></th>
			<th><input type="text" name="Program name" value="Program Name" class="search_init" /></th>
			<th><input type="text" name="Type" value="type" class="search_init" /></th>
			<th><input type="text" name="Short Code" value="Short Code" class="search_init" /></th>
			<th><input type="text" name="Program Name Tamil" value="Program Name Tamil" class="search_init" /></th>
			<th></th>
		</tr>
	</tfoot>	
	</table>

	<span class='breadcrumb1'>
<span class='navbar-brand'><small>MASTERS <i class='ace-icon fa fa-angle-double-right'></i></small></span>
<?php echo $this->Html->link("<span class='navbar-brand'><small>Programs <i class='ace-icon fa fa-angle-double-right'></i></small></span>", array("controller"=>"Academics",'action' => 'index'),array('data-placement'=>'left','escape' => false)); ?>
</span>