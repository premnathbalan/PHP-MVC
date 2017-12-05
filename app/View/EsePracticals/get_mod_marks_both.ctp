<?php
//pr($result);
//pr($studentArray);
if ($mod_option == "ese") {
	$modResult = $result[0]['EsePractical'][0]['Practical'];
}
else if ($mod_option == "total") {
	$modResult = $result[0]['StudentMark'];
}
$optionsArray=array("1"=>"1","2"=>"2","3"=>"3","4"=>"4","5"=>"5","6"=>"6","7"=>"7","8"=>"8","9"=>"9","10"=>"10");
echo $this->Form->input('modOperator', array('type'=> 'select', 'options'=>array("plus"=>"+"), 'label'=>false));
echo $this->Form->input('modMarks', array('type'=> 'select', 'empty'=>'----Select----', 'label'=>false, 'options' => $optionsArray));
if(isset($modResult) && count($modResult)>0) { ?>
<div class="col-lg-6">			
	<?php echo $this->Form->button('<i class="ace-icon fa fa-check bigger-110"></i>'.__('&nbsp;Submit&nbsp;'),array('type'=>'submit','name'=>'submit','value'=>'submit','class'=>'btn')); ?>
</div>
<?php
}
?>	
	<table cellpadding="0" cellspacing="0" border="0" class="display" id="example" style="margin-top:10px;">
			<thead>
				<tr>
					<th>S.No.</th>
					<th>Reg.&nbsp;Number</th>
					<th>Student&nbsp;Name</th>
					<th>ESE</th>
				</tr>
			</thead>
			<tbody>
				<?php
				$i=1;
				foreach ($modResult as $key => $modResultArray) {
				$student_id = $modResultArray['student_id'];
				?>
				<tr class='gradeX'>
					<td><?php echo $i." ".$this->Form->input('id', array('type'=> 'hidden', 'label'=>false, 'default'=>$modResultArray['id'], 'name' => 'data[PracticalMod]['.$mod_option.'][]'));?></td>
					<td><?php echo $studentArray[$student_id]['reg_num'];?></td>
					<td><?php echo $studentArray[$student_id]['name'];?></td>
					<td><?php echo $modResultArray['marks']." ".$this->Form->input('marks', array('type'=> 'hidden', 'label'=>false, 'default'=>$modResultArray['marks'], 'name' => 'data[PracticalMod][marks][]'));?></td>
				</tr>
				<?php
					$i++;
				}
				?>
			</tbody>
			<tfoot>
				<tr>
					<th></th>
					<th><input type="text" name="Reg.&nbsp;Number" value="Reg.&nbsp;Number" class="search_init" /></th>
					<th><input type="text" name="Name" value="Name" class="search_init" /></th>
					<th><input type="text" name="Marks" value="Marks" class="search_init" /></th>
				</tr>
			</tfoot>
		</table>
<?php echo $this->Html->script('common'); ?>