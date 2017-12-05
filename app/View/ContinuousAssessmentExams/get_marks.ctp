<?php
if ($marks > 0) {
	echo $marks;
}
else {
	echo $this->Form->input('marks', array('label' => "Marks <span class='ash'>*</span>"));
}
?>