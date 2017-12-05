<div class="col-sm-12">
<?php
echo "<td>".$this->Form->input('cm_id', array('label' => false, 'style'=>'width:100px;', 'empty' => __("----- Select Semester-----"), 'options'=>$cmArray, 'name'=>'data[semester]', 'onchange'=>'getCourseDetails();'))."</td>";
?>
</div>
<?php echo $this->Html->script('common'); ?>
<?php echo $this->Html->script('common-front'); ?>