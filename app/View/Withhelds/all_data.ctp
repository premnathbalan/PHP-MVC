<?php 
echo $this->Form->input('withheld_val', array('options' =>$results, 'type' => 'select', 'empty' => __("----- All With held -----"), 'label' => 'Withheld'));
?>