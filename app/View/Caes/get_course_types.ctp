<label>Course Type</label>
<?php
if (count($course_types) > 1) {
	$defaultValue = ''; 
}
else {
	$defaultValue = 0;
}

$attributes=array('legend'=>false, 'label'=>false, 'value' => $defaultValue, 'required' => 'required');
echo $this->Form->radio('caetype', $course_types,$attributes);
?>

<style type="text/css">
    input[type=radio] {
       margin:3px;
       width:23px;
    }

    .locRad {
         margin:3px 0px 0px 3px; 
         float:none;
    }
</style>
<?php echo $this->Html->script('common'); ?>