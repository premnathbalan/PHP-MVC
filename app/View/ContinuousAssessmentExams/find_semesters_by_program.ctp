<?php
//pr($this->name);
//echo $this->here;
//echo $this->params['controller'];
//echo $this->Html->url( null, true );
echo $this->Form->input('semester_id', array('options' => $numSemesters, 'type' => 'select', 'empty' => __("----- Select Semester-----"), 'label' => 'Semester', 'class' => 'js-semester'));
?>