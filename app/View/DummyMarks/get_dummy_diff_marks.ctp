<?php
//pr($result);
?>
<?php if(isset($result) && count($result)>0) {
$dummy_number_id = $result[0]['DummyMark']['dummy_number_id'];
?>
<div class="col-lg-6">			
	<?php 
	echo $this->Form->input('dummy_number_id', array('type'=>'hidden', 'default'=>$dummy_number_id, 'label' => false, 'class' => 'js-dummy-number-id'));
	echo $this->Form->button('<i class="ace-icon fa fa-check bigger-110"></i>'.__('&nbsp;Submit&nbsp;'),array('type'=>'submit','name'=>'submit','value'=>'submit','class'=>'btn js-dummy-mismatch-submit')); ?>
</div>
<?php } ?>
<table cellpadding="0" cellspacing="0" border="0" class="display" id="example" style="margin-top:10px;">
	<thead>
		<tr>
			<th>Dummy&nbsp;Number</th>
			<th>Marks&nbsp;Entry1</th>
			<th>Entered&nbsp;By</th>
			<th>Entered&nbsp;Time</th>
			<th>Marks&nbsp;Entry2</th>
			<th>Entered&nbsp;By</th>
			<th>Entered&nbsp;Time</th>
			<th>Final&nbsp;Mark</th>
		</tr>
	</thead>
	<tbody>
		<?php
		$i=1;
		foreach ($result as $key => $dummyDiffArray) {
		$mark1_created_by = $dummyDiffArray['DummyMark']['mark1_created_by'];
		$mark2_created_by = $dummyDiffArray['DummyMark']['mark2_created_by'];
		if ($dummyDiffArray['DummyMark']['modified_by'] <> "0000-00-00 00:00:00") 
		$modified_by = $dummyDiffArray['DummyMark']['modified_by'];
		else $modified_by = "";
		echo "<tr class='gradeX'>";
			echo "<td>".$dummyDiffArray['DummyMark']['dummy_number']."</td>";
			echo "<td><span class='entry$i'>".$dummyDiffArray['DummyMark']['mark_entry1']."</span></td>";
			echo "<td>".$this->Html->getUserNameFromUserId($mark1_created_by)."</td>";
			echo "<td>".$dummyDiffArray['DummyMark']['created']."</td>";
			echo "<td><span class='entry$i'>".$dummyDiffArray['DummyMark']['mark_entry2']."</span></td>";
			echo "<td>".$this->Html->getUserNameFromUserId($mark2_created_by)."</td>";
			echo "<td>".$dummyDiffArray['DummyMark']['created2']."</td>";
			echo "<td>".
				$this->Form->input('dummy_marks_id', array('type'=> 'hidden', 'label'=>false, 'default'=>$dummyDiffArray['DummyMark']['id'], 'name' => 'data[DummyApproval][id][]'))." ".
				$this->Form->input('marks', array('type'=> 'text', 'label'=>false, 'style'=>'width:40px;font-size:14px;', 
				'name' => 'data[DummyApproval][mark]['.$dummyDiffArray['DummyMark']['id'].']', 'required'=>'required', 'class'=>'dummy_mark$i', 'onblur'=>'checkMarks()'))
			."</td>";
		echo "</tr>";
		$i++;
		} 
		?>
	</tbody>
	<!--<tfoot>
		<tr>
			<th></th>
			<th><input type="text" name="RegistrationNumber" value="RegistrationNumber" class="search_init" /></th>
			<th><input type="text" name="StudentName" value="StudentName" class="search_init" /></th>
		</tr>
	</tfoot>-->
</table>

<?php echo $this->Html->script('common'); ?>

<script>leftMenuSelection('Dummy Marks Approval');</script>

<span class='breadcrumb1'>
<span class='navbar-brand'><small>EXAMINATION <i class='ace-icon fa fa-angle-double-right'></i></small></span>
<?php echo $this->Html->link("<span class='navbar-brand'><small>Dummy Marks Approval <i class='ace-icon fa fa-angle-double-right'></i></small></span>", array("controller"=>"DummyMarks",'action' => 'approval'),array('data-placement'=>'left','escape' => false)); ?>
</span>