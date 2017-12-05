<?php 
echo $this->Form->input('exam_date', array('options' =>$examDates, 'type' => 'select', 'empty' => __("----- All Exam Dates -----"), 'label' => 'Exam Date', 'class'=>'examDate'));
?>
<?php echo $this->Html->script('common');?>