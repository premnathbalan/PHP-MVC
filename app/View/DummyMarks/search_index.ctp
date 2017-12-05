<table cellpadding="0" cellspacing="0" border="0" class="display" id="example">
	<thead>
		<tr>
			<th>Dummy No. Range</th>
			<th>No. Of Students</th>
			<th>Mark Entry1</th>
			<th>Entry1 Action</th>			
			<th>Mark Entry2</th>
			<th>Entry2 Action</th>
			<th>Final Status</th>
		</tr>
	</thead>
	<tbody>
		<?php foreach ($dummyMarks as $result): $tagMark1 = "";$tagMark2 = "";?>
		<tr class=" gradeX">
			<td><?php echo $result['DummyNumber']['start_range'].' - '.$result['DummyNumber']['end_range'];?></td>
			<td><?php echo $noOfStudents = ($result['DummyNumber']['end_range']-$result['DummyNumber']['start_range'])+1;?></td>
			<td><?php if(count($result['DummyMark']) == $noOfStudents){echo "closed";}else{ echo "<span class='ovelShapBg2'>Open</span>";}?></td>
			<td>
			<?php 
			if(count($result['DummyMark']) == $noOfStudents){
				if($this->Html->checkPathAccesstopath('DummyMarks/editMark1','',$authUser['id'])){
					echo $this->Html->link("<i class='fa fa-pencil fa-lg'></i>",array("controller"=>"DummyMarks",'action' => 'editMark1',$result['DummyNumber']['id']), array('escape' => false));				
				}
			}else{
				if($this->Html->checkPathAccesstopath('DummyMarks/addMark1','',$authUser['id'])){
					echo $this->Html->link("<i class='fa fa-plus fa-lg'></i>",array("controller"=>"DummyMarks",'action' => 'addMark1',$result['DummyNumber']['id']), array('escape' => false));				
				}
			}?>			
			</td>
			<td><?php if(isset($result['DummyMark'][0]['mark_entry2']) &&  $result['DummyMark'][0]['mark_entry2'] != 0){ echo "closed";}else{ echo "<span class='ovelShapBg2'>Open</span>";}?></td>
			<td>
			<?php
			if(isset($result['DummyMark'][0]['mark_entry2']) &&  $result['DummyMark'][0]['mark_entry2'] != 0){
				if($this->Html->checkPathAccesstopath('DummyMarks/editMark2','',$authUser['id'])){
					echo $this->Html->link("<i class='fa fa-pencil fa-lg'></i>",array("controller"=>"DummyMarks",'action' => 'editMark2',$result['DummyNumber']['id']), array('escape' => false));				
				}
			}else{
				if($this->Html->checkPathAccesstopath('DummyMarks/addMark2','',$authUser['id'])){
					echo $this->Html->link("<i class='fa fa-plus fa-lg'></i>",array("controller"=>"DummyMarks",'action' => 'addMark2',$result['DummyNumber']['id']), array('escape' => false));				
				}				
			}?>			
			</td>
			<td><?php if($result['DummyNumber']['sync_status'] != 0){echo "closed";}else{ echo "<span class='ovelShapBg2'>Open</span>";}?></td>
		</tr>
		  <?php endforeach; ?>		
	</tbody>
	<tfoot>
		<tr>
			<th><input type="text" name="Dummy No Range" value="Dummy No. Range" class="search_init" /></th>
			<th><input type="text" name="No. Of Students" value="No. Of Students" class="search_init" /></th>
			<th><input type="text" name="Mark Entry1" value="Mark Entry1" class="search_init" /></th>
			<th><input type="text" name="Entry1 Action" value="Entry1 Action" class="search_init" /></th>			
			<th><input type="text" name="Mark Entry2" value="Mark Entry2" class="search_init" /></th>
			<th><input type="text" name="Entry2 Action" value="Entry2 Action" class="search_init" /></th>
			<th><input type="text" name="Final Status" value="Final Status" class="search_init" /></th>
		</tr>
	</tfoot>
	<?php echo $this->Html->script('common');?>
</table>

<span class='breadcrumb1'>
<span class='navbar-brand'><small>EXAMINATION <i class='ace-icon fa fa-angle-double-right'></i></small></span>
<?php echo $this->Html->link("<span class='navbar-brand'><small>Dummy Marks <i class='ace-icon fa fa-angle-double-right'></i></small></span>", array("controller"=>"DummyMarks",'action' => 'index'),array('data-placement'=>'left','escape' => false)); ?>
</span>

