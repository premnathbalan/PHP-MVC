<?php
//pr($course);
//pr($numSemesters);
echo $this->Form->input('course_mapping_id', array('options' => $course, 'type' => 'select', 'empty' => __("----- Select Course-----"), 'label' => false, 'class' => 'js-course'));
echo $this->Form->input('semester_id', array('options' => $numSemesters, 'type' => 'select', 'empty' => __("----- Select semester-----"), 'label' => false, 'class' => 'js-semester'));

?>