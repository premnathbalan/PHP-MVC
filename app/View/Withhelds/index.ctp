<div id="js-load-forms"></div>
<?php 
if($this->Html->checkPathAccesstopath('Withhelds/add','',$authUser['id'])){
	echo $this->Html->link( '<i class="ace-icon fa fa-plus-square"></i>'. 'With Held Add', array('controller'=>'Withhelds','action'=>'add'),array('class' =>'js-popup addBtn btn','escape' => false, 'title'=>'Add With Held','style'=>'margin-bottom:5px;'));
		
} 
?>
<div class="withhelds index">
	<table cellpadding="0" cellspacing="0" border="0" class="display" id="example">
	<thead>
	<tr>
			<th class="thAction">&nbsp;&nbsp;Action&nbsp;&nbsp;</th>
			<th>Id</th>
			<th>With Held Type</th>
			<th class="thAction">&nbsp;&nbsp;Delete&nbsp;&nbsp;</th>
	</tr>
	</thead>
	<tbody>
	<?php foreach ($withhelds as $withheld): ?>
	<tr class="gradeX">
		<td class="actions">
			<?php 
			if($this->Html->checkPathAccesstopath('Withhelds/add','',$authUser['id'])){
				echo $this->Html->link("<i class='fa fa-eye fa-lg'></i>",array("controller"=>"Withhelds",'action' => 'view',$withheld['Withheld']['id']), array('class' =>"js-popup",'escape' => false)); 
			}if($this->Html->checkPathAccesstopath('Withhelds/add','',$authUser['id'])){
				echo $this->Html->link("<i class='fa fa-pencil fa-lg'></i>",array("controller"=>"Withhelds",'action' => 'edit',$withheld['Withheld']['id']),array('class' =>"js-popup", 'escape' => false));
			}
			?>
		</td>
		<td><?php echo h($withheld['Withheld']['id']); ?></td>
		<td><?php echo h($withheld['Withheld']['withheld_type']); ?></td>
		<td class="actions">
			<?php 
			if($this->Html->checkPathAccesstopath('Withhelds/delete','',$authUser['id'])){
				echo $this->Form->postLink(__('Delete'), array('action' => 'delete', $withheld['Withheld']['id']), array('confirm' => __('Are you sure you want to delete # %s?', $withheld['Withheld']['id']))); 
			}
			?>
		</td>
	</tr>
<?php endforeach; ?>
	</tbody>
	<tfoot>
	<tr>
		<th><input type="text" name="&nbsp;&nbsp;Action&nbsp;&nbsp;" value="&nbsp;&nbsp;Action&nbsp;&nbsp;" class="search_init" /></th>
		<th><input type="text" name="Id" value="Id" class="search_init" /></th>
		<th><input type="text" name="With Held Type" value="With Held Type" class="search_init" /></th>
		<th></th>
	</tr>
	</tfoot>
	</table>
</div>

<span class='breadcrumb1'>
<span class='navbar-brand'><small>MASTERS <i class='ace-icon fa fa-angle-double-right'></i></small></span>
<?php echo $this->Html->link("<span class='navbar-brand'><small>WITH HELD TYPE <i class='ace-icon fa fa-angle-double-right'></i></small></span>", array("controller"=>"Withhelds",'action' => 'index'),array('data-placement'=>'left','escape' => false)); ?>
</span>