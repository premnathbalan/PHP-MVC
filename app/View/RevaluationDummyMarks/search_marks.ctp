<?php
//pr($revaluationDummyMarks);
?>
<table cellpadding="0" cellspacing="0" border="0" class="display" id="example">
	<thead>
		<tr>
			<th>Dummy No. Range</th>
			<th>No. Of Students</th>
			<th>Mark Entry1</th>
			<th>Entry1 Action</th>			
			<th>Mark Entry2</th>
			<th>Entry2 Action</th>
			<th>Sync Status</th>
		</tr>
	</thead>
	<tbody>
		<?php foreach ($revaluationDummyMarks as $dummy_number_id => $result): $tagMark1 = "";$tagMark2 = "";?>
		<tr class=" gradeX">
			<td><?php echo $result['startRange'].' - '.$result['endRange'];?></td>
			<td><?php echo $noOfStudents = $result['totalCount'];?></td>
			<td><?php if($result['markEntry1'] == $result['totalCount']){echo "Closed";}else{ echo "<span class='ovelShapBg2'>Open</span>";}?></td>
			<td>
			<?php 
			if($result['markEntry1'] == $result['totalCount']){
				if($this->Html->checkPathAccesstopath('RevaluationDummyMarks/editMark','',$authUser['id'])){
					echo $this->Html->link("<i class='fa fa-pencil fa-lg'></i>",array("controller"=>"RevaluationDummyMarks",'action' => 'editMark1',$dummy_number_id, $month_year_id), array('escape' => false));				
				}
			}else{
				if($this->Html->checkPathAccesstopath('RevaluationDummyMarks/addMark','',$authUser['id'])){
					echo $this->Html->link("<i class='fa fa-plus fa-lg'></i>",array("controller"=>"RevaluationDummyMarks",'action' => 'addMark1',$dummy_number_id, $month_year_id), array('escape' => false));				
				}
			}?>			
			</td>
			<td><?php if($result['markEntry2'] == $result['totalCount']){ echo "Closed";}else{ echo "<span class='ovelShapBg2'>Open</span>";}?></td>
			<td>
			<?php
			if($result['markEntry2'] == $result['totalCount']){
				if($this->Html->checkPathAccesstopath('RevaluationDummyMarks/editMark2','',$authUser['id'])){
					echo $this->Html->link("<i class='fa fa-pencil fa-lg'></i>",array("controller"=>"RevaluationDummyMarks",'action' => 'editMark2',$dummy_number_id, $month_year_id), array('escape' => false));				
				}
			}else{
				if($this->Html->checkPathAccesstopath('RevaluationDummyMarks/addMark2','',$authUser['id'])){
					echo $this->Html->link("<i class='fa fa-plus fa-lg'></i>",array("controller"=>"RevaluationDummyMarks",'action' => 'addMark2',$dummy_number_id, $month_year_id), array('escape' => false));				
				}				
			}?>			
			</td>
			<td><?php if($result['revalSyncStatus']==1){echo "Closed";}else{ echo "<span class='ovelShapBg2'>Open</span>";}?></td>
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
<span class='navbar-brand'><small>REVALUATION <i class='ace-icon fa fa-angle-double-right'></i></small></span>
<?php echo $this->Html->link("<span class='navbar-brand'><small>Dummy Marks <i class='ace-icon fa fa-angle-double-right'></i></small></span>", array("controller"=>"DummyMarks",'action' => 'index'),array('data-placement'=>'left','escape' => false)); ?>
</span>

