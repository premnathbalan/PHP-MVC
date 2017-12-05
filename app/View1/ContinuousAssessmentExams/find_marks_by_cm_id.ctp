<?php
/*if ($marks>0) {
	$var2 = "disabled";
}
else {
	$var2 = "";
}*/
if ($template == "theoryTemplate") {
	echo $this->Form->input('marks', array('type' => 'text', 'label' => 'Marks', 'class' => 'js-mark', 'default' => $marks, 'default' => $marks, /*$var2*/));
}
?>
<?php echo $this->Html->script('common'); ?>