<table cellpadding="0" cellspacing="0" border="0" class="display" id="example" style="margin-top:10px;">
	<thead>
		<tr>
			<th>Exam Date</th>
			<th>Exam Session</th>
			<th>Exam Type</th>			
			<th>Start Range</th>
			<th class="thAction">Action</th>
		</tr>
	</thead>
	<tbody>
		<?php foreach($dummyNos as $DNAllot):if(isset($DNAllot['DummyRangeAllocation'][0]['Timetable']['exam_date'])){?>
		<tr class=" gradeX">
			<td><?php echo date( "d-M-Y", strtotime(h($DNAllot['DummyRangeAllocation'][0]['Timetable']['exam_date']))); ?></td>
			<td><?php echo $DNAllot['DummyRangeAllocation'][0]['Timetable']['exam_session'];?></td>			
			<td><?php echo $DNAllot['DummyRangeAllocation'][0]['Timetable']['exam_type'];?></td>
			<td><?php echo $DNAllot['DummyNumber']['start_range'];?></td>
			<td class="actions"><?php
			//if(isset($DNAllot['DummyNumberAllocation'][0]['dummy_number_id'])){ 
				if($this->Html->checkPathAccesstopath('DummyNumberAllocations/FoilCard','',$authUser['id'])){ 
					echo $this->Html->link("<i class='ace-icon fa fa-print fa-lg'></i>",array("controller"=>"DummyNumberAllocations",'action' => 'add',$DNAllot['DummyNumber']['id'],'P'),array( 'escape' => false));
				}
			//}?>
			</td>
		</tr>
		  <?php }endforeach; ?>		
	</tbody>
	<tfoot>
		<tr>
			<th><input type="text" name="Exam Date" value="Exam Date" class="search_init" /></th>
			<th><input type="text" name="Exam Session" value="Exam Session" class="search_init" /></th>
			<th><input type="text" name="Exam Type" value="Exam Type" class="search_init" /></th>			
			<th><input type="text" name="Start Range" value="Start Range" class="search_init" /></th>
			<th></th>
		</tr>
	</tfoot>
	<?php echo $this->Html->script('common');?>
</table>

<span class='breadcrumb1'>
<span class='navbar-brand'><small>EXAMINATION <i class='ace-icon fa fa-angle-double-right'></i></small></span>
<?php echo $this->Html->link("<span class='navbar-brand'><small>Foil Card<i class='ace-icon fa fa-angle-double-right'></i></small></span>", array("controller"=>"DummyNumberAllocations",'action' => 'FoilCard'),array('data-placement'=>'left','escape' => false)); ?>
</span>