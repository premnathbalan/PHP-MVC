<div class="col-sm-12">
<?php
$array=array();
for ($i=1; $i<=$semesters; $i++) {
	$array[$i]=$i;
}
echo "<td>".$this->Form->input('semester', array('label' => false, 'style'=>'width:100px;', 'empty' => __("----- Select Semester-----"), 'options'=>$array, 'name'=>'data[semester]', 'onchange'=>'getCourses();'))."</td>";
?>
</div>
<?php echo $this->Html->script('common'); ?>
<?php echo $this->Html->script('common-front'); ?>