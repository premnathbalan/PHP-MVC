<?php echo $this->Form->input('failed_option', array('type' => 'select', 'options'=>array("Pass"=>"Passed after Revaluation", "Fail"=>"Failed after Revaluation"), 'empty'=>'---Select an option---', 'label' => 'RevaluationStatus', 'class' => 'js-reval-fail-moderation', 'style'=>'margin-top:10px;border-color:#000;width:150px;color:#000;')); ?>
<?php echo $this->Html->script('common'); ?>
	