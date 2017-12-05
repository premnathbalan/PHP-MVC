<div id="js-load-forms"></div>

	<?php
	if($this->Html->checkPathAccesstopath('Sections/add','',$authUser['id'])){
		echo $this->Html->link( '<i class="ace-icon fa fa-plus-square"></i>'. 'Add Section', array("controller"=>"Sections",'action'=>'add'),array('class' =>"js-popup addBtn btn",'escape' => false, 'title'=>'Add Sections', 'style'=>'margin-bottom:5px;')); 
	}
	?>
<table cellpadding="0" cellspacing="0" class="display" id="example">
	<thead>
	<tr>
		<th class="thAction">&nbsp;&nbsp;Action&nbsp;&nbsp;</th>
		<th>Section</th>
		<th class="thAction">&nbsp;&nbsp;Delete&nbsp;&nbsp;</th>
	</tr>
	</thead>
	<tbody>
	<?php foreach ($sections as $section): ?>
	<tr class="gradeX">
		<td class="actions">
			<?php 
			if($this->Html->checkPathAccesstopath('Sections/view','',$authUser['id'])){
				echo $this->Html->link("<i class='fa fa-eye fa-lg'></i>", array("controller"=>"sections",'action' => 'view', $section['Section']['id']),array('class' =>"js-popup",'escape' => false, 'title'=>'View')); 
			}
			if($this->Html->checkPathAccesstopath('Sections/edit','',$authUser['id'])){ 
				echo $this->Html->link("<i class='fa fa-pencil fa-lg'></i>", array("controller"=>"sections",'action' => 'edit', $section['Section']['id']),array('class' =>"js-popup", 'title'=>'Edit','escape' => false)); 
			}
			?>
		</td>		
		<td><?php echo h($section['Section']['name']); ?></td>
		<td class="actions">
			<?php
			if($this->Html->checkPathAccesstopath('Sections/delete','',$authUser['id'])){ 
				echo $this->Form->postLink("<span class='fa fa-times fa-lg red'></span>", array("controller"=>"sections",'action' => 'delete', $section['Section']['id']), array('confirm' => __('Are you sure you want to delete # %s?', $section['Section']['id']),'escape' => false, 'title'=>'Delete')); 
			}
			?>
		</td>
	</tr>
<?php endforeach; ?>
	</tbody>
	<tfoot>
		<tr>
			<th><input type="text" name="&nbsp;&nbsp;Action&nbsp;&nbsp;" value="&nbsp;&nbsp;Action&nbsp;&nbsp;" class="search_init" /></th>
			<th><input type="text" name="Section" value="Section" class="search_init" /></th>
			<th></th>
		</tr>
	</tfoot>	
</table>

<span class='breadcrumb1'>
<span class='navbar-brand'><small>MASTERS <i class='ace-icon fa fa-angle-double-right'></i></small></span>
<?php echo $this->Html->link("<span class='navbar-brand'><small>Sections <i class='ace-icon fa fa-angle-double-right'></i></small></span>", array("controller"=>"Sections",'action' => 'index'),array('data-placement'=>'left','escape' => false)); ?>
</span>
