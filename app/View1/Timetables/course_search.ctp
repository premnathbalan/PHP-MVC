<?php
//pr($courseDetails);
//pr($courseArray);
echo $this->Form->input('course_id', array('label' => "<span class='ash'>*</span> CourseCode",'options'=>$courseArray, 'empty' => __("----- Select Course-----"), 'class' => 'js-course', 'style'=>'width:150px;')); 
?>