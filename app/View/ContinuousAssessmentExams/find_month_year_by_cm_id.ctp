<?php
//pr($monthyears);
if ($myAvailable) {
	$var1=false;
}
else {
	$var1 = "----- Select Month Year-----";
}
echo $this->Form->input('month_year_id', array('options' => $monthyears, 'type' => 'select', 'label' => 'MonthYear', 'class' => 'js-month-year', 'default' => $monthyears, 'empty' => $var1));
?>