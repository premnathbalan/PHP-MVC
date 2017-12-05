<div id="js-load-forms"></div>

<?php 
if($this->Html->checkPathAccesstopath('MonthYears/add','',$authUser['id'])){
	echo $this->Html->link( '<i class="ace-icon fa fa-plus-square"></i>'. 'Add MonthYear', array("controller"=>"MonthYears",'action'=>'add'),array('class' =>"js-popup addBtn btn",'escape' => false, 'title'=>'Add Courses Type','style'=>'margin-bottom:5px;'));
} 
?>

<table cellpadding="0" cellspacing="0" border="0" class="display" id="example">
	<thead>
		<tr>
			<th class="thAction">&nbsp;&nbsp;Action&nbsp;&nbsp;</th>
			<th>Month-Year</th>
			<th>Month</th>
			<th>TamilMonth</th>
			<th>Year</th>			
			<th>Publishing Date</th>
			<th class="thAction">&nbsp;&nbsp;Delete&nbsp;&nbsp;</th>
		</tr>
	</thead>
	<tbody>
		<?php foreach ($monthYears as $monthYear):?>
		<tr class=" gradeX">
			<td class="actions">
			<?php 
			if($this->Html->checkPathAccesstopath('MonthYears/view','',$authUser['id'])){
				echo $this->Html->link("<i class='fa fa-eye fa-lg'></i>",array("controller"=>"MonthYears",'action' => 'view',$monthYear['MonthYear']['id']), array('class' =>"js-popup",'escape' => false));
			}
			if($this->Html->checkPathAccesstopath('MonthYears/edit','',$authUser['id'])){ 
				echo $this->Html->link("<i class='fa fa-pencil fa-lg'></i>",array("controller"=>"MonthYears",'action' => 'edit',$monthYear['MonthYear']['id']),array('class' =>"js-popup", 'escape' => false));
			}
			?>			
		</td>
			<td><?php echo h($monthYear['Month']['month_name']."-".$monthYear['MonthYear']['year']); ?></td>
			<td><?php echo h($monthYear['Month']['month_name']); ?></td>
			<td><?php echo h($monthYear['Month']['tamil_month_name']); ?></td>
			<td><?php echo h($monthYear['MonthYear']['year']); ?></td>			
			<td><?php echo date( "d-M-Y", strtotime(h($monthYear['MonthYear']['publishing_date']))); ?></td>
			<td class="actions">
			<?php 
			if($this->Html->checkPathAccesstopath('MonthYears/delete','',$authUser['id'])){ 
				echo $this->Form->postLink("<span class='fa fa-times fa-lg red'></span>", array("controller"=>"MonthYears",'action' => 'delete', $monthYear['MonthYear']['id']), array('confirm' => __('Are you sure you want to delete # %s?', $monthYear['MonthYear']['id']),'escape' => false, 'title'=>'Delete'));
			}
			?>			
		</td>
		</tr>
		  <?php endforeach; ?>		
	</tbody>
	<tfoot>
		<tr>
			<th><input type="text" name="&nbsp;&nbsp;Action&nbsp;&nbsp;" value="&nbsp;&nbsp;Action&nbsp;&nbsp;" class="search_init" /></th>
			<th><input type="text" name="Month-Year" value="Month-Year" class="search_init" /></th>
			<th><input type="text" name="TamilMonth" value="Tamil" class="search_init" /></th>
			<th><input type="text" name="Month" value="Month" class="search_init" /></th>
			<th><input type="text" name="Year" value="Year" class="search_init" /></th>			
			<th><input type="text" name="Publishing Date" value="Publishing Date" class="search_init" /></th>
			<th></th>
		</tr>
	</tfoot>
</table>

<span class='breadcrumb1'>
<span class='navbar-brand'><small>MASTERS <i class='ace-icon fa fa-angle-double-right'></i></small></span>
<?php echo $this->Html->link("<span class='navbar-brand'><small>MONTH AND YEAR OF EXAMINATION <i class='ace-icon fa fa-angle-double-right'></i></small></span>", array("controller"=>"MonthYears",'action' => 'index'),array('data-placement'=>'left','escape' => false)); ?>
</span>