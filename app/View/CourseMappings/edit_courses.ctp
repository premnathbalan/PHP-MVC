</div>
<div class="searchFrm">
<?php 
//echo $this->Form->input('batch_id', array('options' => $batches, 'label' => false, 'empty' => __("----- Select Batch-----")));
echo $this->Form->input('program_id', array('options' => $batchProgram, 'label' => false, 'empty' => __("----- Select DataSet-----"), 'class' => 'js-data-set-edit'));
?>
</div>
<div id="semesters"></div>
<?php
echo $this->Html->script('common');
?>