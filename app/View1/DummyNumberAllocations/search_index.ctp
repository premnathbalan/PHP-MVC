<table cellpadding="0" cellspacing="0" border="0" class="display" id="example">
	<thead>
		<tr>
			<th>Exam Date</th>
			<th>Session</th>
			<th>Type</th>			
			<th>Range</th>
			<th>No.&nbsp;of&nbsp;Student</th>
			<th>Course Code</th>
			<th>Course Name</th>
			<th class="thAction">Action</th>
		</tr>
	</thead>
	<tbody>
		<?php foreach($dummyNos as $DNAllot):?>
		<tr class=" gradeX">
			<td><?php echo date( "d-M-Y", strtotime(h($DNAllot['DummyRangeAllocation'][0]['Timetable']['exam_date']))); ?></td>
			<td><?php echo $DNAllot['DummyRangeAllocation'][0]['Timetable']['exam_session'];?></td>			
			<td><?php echo $DNAllot['DummyRangeAllocation'][0]['Timetable']['exam_type'];?></td>
			<td><?php echo $DNAllot['DummyNumber']['start_range']."&nbsp;-&nbsp;".$DNAllot['DummyNumber']['end_range'];?></td>
			<td><?php echo $actualNos = ($DNAllot['DummyNumber']['end_range'] - $DNAllot['DummyNumber']['start_range'])+1;?></td>
			<td><?php echo $DNAllot['DummyRangeAllocation'][0]['Timetable']['CourseMapping']['Course']['course_code'];?></td>
			<td><?php echo $DNAllot['DummyRangeAllocation'][0]['Timetable']['CourseMapping']['Course']['course_name'];?></td>
			<td class="actions"><?php 
			if($actualNos == count($DNAllot['DummyNumberAllocation'])){ 
				if($this->Html->checkPathAccesstopath('DummyNumberAllocations/edit','',$authUser['id'])){ 
					echo $this->Html->link("<i class='fa fa-pencil fa-lg'></i>",array("controller"=>"DummyNumberAllocations",'action' => 'edit',$DNAllot['DummyNumber']['id']),array( 'escape' => false));
				}
			}else{ 
				if($this->Html->checkPathAccesstopath('DummyNumberAllocations/add','',$authUser['id'])){ 
					echo $this->Html->link("<i class='fa fa-plus fa-lg'></i>",array("controller"=>"DummyNumberAllocations",'action' => 'add',$DNAllot['DummyNumber']['id'],''),array( 'escape' => false));
				}
			}?>
			</td>
		</tr>
		  <?php endforeach; ?>		
	</tbody>
	<tfoot>
		<tr>
			<th><input type="text" name="Exam Date" value="Exam Date" class="search_init" /></th>
			<th><input type="text" name="Session" value="Session" class="search_init" /></th>
			<th><input type="text" name="Type" value="Type" class="search_init" /></th>			
			<th><input type="text" name="Range" value="Range" class="search_init" /></th>
			<th><input type="text" name="No.&nbsp;of&nbsp;Student" value="No.&nbsp;of&nbsp;Student" class="search_init" /></th>
			<th><input type="text" name="Course Code" value="Course Code" class="search_init" /></th>
			<th><input type="text" name="Course Name" value="Course Name" class="search_init" /></th>
			<th></th>
		</tr>
	</tfoot>
	<?php echo $this->Html->script('common');?>
</table>

<span class='breadcrumb1'>
<span class='navbar-brand'><small>EXAMINATION <i class='ace-icon fa fa-angle-double-right'></i></small></span>
<?php echo $this->Html->link("<span class='navbar-brand'><small>Dummy Number Allocations <i class='ace-icon fa fa-angle-double-right'></i></small></span>", array("controller"=>"DummyNumberAllocations",'action' => 'index'),array('data-placement'=>'left','escape' => false)); ?>
</span>