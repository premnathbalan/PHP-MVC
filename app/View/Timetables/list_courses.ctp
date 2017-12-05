<?php
echo $this->Form->input('course', array('options' => $listCourses, 'type' => 'select', 'empty' => __("----- Select Course-----"), 'label' => "Course<span class='ash'>*</span>", 'class' => 'js-month-year'));
?>