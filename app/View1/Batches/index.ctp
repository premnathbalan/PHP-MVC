<div id="js-load-forms"></div>

	<?php 
	if($this->Html->checkPathAccesstopath('Batches/add','',$authUser['id'])){
		echo $this->Html->link( '<i class="ace-icon fa fa-plus-square"></i>'. 'Add Batch', array("controller"=>"batches",'action'=>'add'),array('class' =>"js-popup addBtn btn",'escape' => false, 'title'=>'Add Batches', 'style'=>'margin-bottom:5px;')); 
	}
	?>

	<table cellpadding="0" cellspacing="0" class="display" id="example">
	<thead>
	<tr>
		<th class="thAction">&nbsp;&nbsp;Action&nbsp;&nbsp;</th>
		<th>Batch</th>
		<th>Batch&nbsp;From</th>
		<th>Batch&nbsp;To</th>		
		<th>Month</th>
		<th>Publishing&nbsp;Date</th>
		<th class="thAction">&nbsp;&nbsp;Delete&nbsp;&nbsp;</th>
	</tr>
	</thead>
	<tbody>
	<?php foreach ($batches as $batch): ?>
	<tr class="gradeX">
		<td class="actions">
			<?php 
			if($this->Html->checkPathAccesstopath('Batches/view','',$authUser['id'])){
				echo $this->Html->link("<i class='fa fa-eye fa-lg'></i>", array("controller"=>"batches",'action' => 'view', $batch['Batch']['id']),array('class' =>"js-popup",'escape' => false, 'title'=>'View')); 
			}
			if($this->Html->checkPathAccesstopath('Batches/edit','',$authUser['id'])){ 
				echo $this->Html->link("<i class='fa fa-pencil fa-lg'></i>", array("controller"=>"batches",'action' => 'edit', $batch['Batch']['id']),array('class' =>"js-popup", 'title'=>'Edit','escape' => false)); 
			}
			?>
		</td>
		<td><?php echo h($batch['Batch']['batch_from']." - ".$batch['Batch']['batch_to']); 
		if($batch['Batch']['academic'] == 'JUN'){ echo " [A]";}
		?></td>
		<td><?php echo h($batch['Batch']['batch_from']); ?></td>
		<td><?php echo h($batch['Batch']['batch_to']); ?></td>		
		<td><?php echo h($batch['Batch']['academic']); ?></td>
		<td><?php if($batch['Batch']['consolidated_pub_date'] !='0000-00-00'){ echo date( "d-M-Y", strtotime(h($batch['Batch']['consolidated_pub_date'])) ); }?></td>
		<td class="actions">
			<?php
			if($this->Html->checkPathAccesstopath('Batches/delete','',$authUser['id'])){ 
				echo $this->Form->postLink("<span class='fa fa-times fa-lg red'></span>", array("controller"=>"Batches",'action' => 'delete', $batch['Batch']['id']), array('confirm' => __('Are you sure you want to delete # %s?', $batch['Batch']['id']),'escape' => false, 'title'=>'Delete')); 
			}
			?>
		</td>
	</tr>
<?php endforeach; ?>
	</tbody>
	<tfoot>
		<tr>
			<th><input type="text" name="&nbsp;&nbsp;Action&nbsp;&nbsp;" value="&nbsp;&nbsp;Action&nbsp;&nbsp;" class="search_init" /></th>
			<th><input type="text" name="Batch" value="Batch" class="search_init" /></th>
			<th><input type="text" name="Batch From" value="Batch From" class="search_init" /></th>
			<th><input type="text" name="Batch To" value="Batch To" class="search_init" /></th>			
			<th><input type="text" name="Month" Id" value="Month" class="search_init" /></th>
			<th><input type="text" name="Pub date" value="Pub date" class="search_init" /></th>
			<th></th>
		</tr>
	</tfoot>	
</table>

<span class='breadcrumb1'>
<span class='navbar-brand'><small>MASTERS <i class='ace-icon fa fa-angle-double-right'></i></small></span>
<?php echo $this->Html->link("<span class='navbar-brand'><small>BATCHES <i class='ace-icon fa fa-angle-double-right'></i></small></span>", array("controller"=>"Batches",'action' => 'index'),array('data-placement'=>'left','escape' => false)); ?>
</span>