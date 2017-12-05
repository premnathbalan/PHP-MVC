<?php
//pr($results);
$csmArray = $results[0]['CourseStudentMapping'];
foreach ($csmArray as $key => $value) {
	if (isset($value['CourseMapping']['Course']) && count($value['CourseMapping']['Course'])>0) {
		$options[$value['CourseMapping']['id']] = $value['CourseMapping']['Course']['course_code'];
	} 
}
echo $this->Form->input('cm_id', array('label'=>'Courses','type' => 'select', 'empty' => __("----- Select Course-----"), 'options'=>$options, 'required'=>'', 'class'=>'js-tmp-ese-marks'));
?>
<?php echo $this->Form->input('student_id', array('label' => false, 'type' => 'hidden','style'=>'margin-top:10px;width:100px;','maxlength'=>30, 'value'=>$results[0]['Student']['id']));?>

<?php echo $this->Html->script('common'); ?>

<script>leftMenuSelection('EsePracticals/tmpModeration');</script>
<span class='breadcrumb1'>
<span class='navbar-brand'><small>MARKS <i class='ace-icon fa fa-angle-double-right'></i></small></span>
<span class='navbar-brand'><small>Practical <i class='ace-icon fa fa-angle-double-right'></i></small></span>
<?php echo $this->Html->link("<span class='navbar-brand'><small> Theory <i class='ace-icon fa fa-angle-double-right'></i></small></span>", array("controller"=>"EsePracticals",'action' => 'tmpModeration'),array('data-placement'=>'left','escape' => false)); ?>
</span>