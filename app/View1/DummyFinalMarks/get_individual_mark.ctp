<?php
//pr($result);
echo $this->Form->input('old_value', array('type'=> 'hidden', 'label'=>false, 'default'=>$result[0]['DummyFinalMark']['marks'], 'name' => 'data[Dummy][Old]'));
echo "<table>";
	echo "<tr>";
		echo "<td>Marks scored<td>";
		echo "<td>".$result[0]['DummyFinalMark']['marks']."<td>";
	echo "</tr>";
	echo "<tr>";
		echo "<td>Already moderated by<td>";
		echo "<td>".$result[0]['DummyFinalMark']['moderation_operator']." ".$result[0]['DummyFinalMark']['moderation_marks']."<td>";
	echo "</tr>";
	echo "<tr>";
	$uid = $result[0]['DummyFinalMark']['modified_by'];
		echo "<td>Modified By<td>";
		echo "<td>".$this->Html->getUserNameFromUserId($uid)."<td>";
	echo "</tr>";
	echo "<tr>";
		echo "<td>Modified Date<td>";
		echo "<td>".$result[0]['DummyFinalMark']['modified']."<td>";
	echo "</tr>";
	echo "<tr>";
		echo "<td>Mark<td>";
		echo "<td>".$this->Form->input('new_value', array('type'=> 'text', 'label'=>false, 'name' => 'data[Dummy][New]'))." ".
		$this->Form->input('mMarks', array('type'=> 'hidden', 'label'=>false, 'default' => $result[0]['DummyFinalMark']['moderation_marks'], 'name' => 'data[Dummy][mMarks]'))." ".
		$this->Form->input('id', array('type'=> 'hidden', 'label'=>false, 'default' => $result[0]['DummyFinalMark']['id'], 'name' => 'data[Dummy][id]')).
		"<td>";
	echo "</tr>";
echo "</table>";

if(isset($result) && count($result)>0) { ?>
<div class="col-lg-6">			
	<?php echo $this->Form->button('<i class="ace-icon fa fa-check bigger-110"></i>'.__('&nbsp;Submit&nbsp;'),array('type'=>'submit','name'=>'submit','value'=>'submit','class'=>'btn')); ?>
</div>
<?php
}
?>