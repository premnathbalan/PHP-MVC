<?php
$callDummyNo = "";
if($currentModel == 'DummyNumberAllocations'){$callDummyNo = " callDummyNo(); ";}
echo $this->Form->input('month_year_id', array('options' => $monthYear, 'type' => 'select', 'empty' => __("----- Select Month Year-----"), 'label' => 'MonthYear', 'class' => 'js-month-year','onChange'=>$callDummyNo));
?>