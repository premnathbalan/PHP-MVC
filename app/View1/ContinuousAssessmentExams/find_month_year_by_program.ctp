<?php
echo $this->Form->input('month_year_id', array('options' => $monthYear, 'type' => 'select', 'empty' => __("----- Select Month Year-----"), 'label' => 'MonthYear', 'class' => 'js-month-year'));
?>