<?php
//pr($courses);
//pr($sac);
$new_courses = array_diff_key($courses, $sac);
//pr($new_courses);
if (!empty($new_courses)) {
	echo "<div class='col-sm-4'>";	
		echo $this->Form->input('audit_course_id', array('name'=>'data[StudentAuditCourse][audit_course_id]','id'=>'AuditCourseId','label' => "Audit&nbsp;Course<span class='ash'>*</span>", 'type' => 'select', 'options'=>$courses, 'empty' => __("-- Select Course --")));
	echo "</div>"; 
	echo "<div class='col-sm-4'>";	
		echo "Marks&nbsp;&nbsp;<input type='text' name='marks' id='marks' value='' size = '4' />";
	echo "</div>"; 
}
else {
	echo "All courses taken. Add new audit course!!!";
}
echo $this->Form->button('<i class="ace-icon fa fa-check bigger-110"></i>'.__('Submit'),array('style'=>'float:right;','type'=>'submit','name'=>'Save','id'=>'Save','value'=>'Save','class'=>'btn'));
?>