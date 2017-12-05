<div id="js-load-forms"></div>

<div class="page-header">
	<h1>
		Manage course
		<small>
			<i class="ace-icon fa fa-angle-double-right"></i>
		</small>
	</h1>	
</div>
<?php //echo h($student['Student']['id']); ?>
<?php 

if (isset($dataset)) { 
	$i = 0;
	foreach ($dataset as $dataset): 
	echo $dataset['batches']['batch_from']." ".$dataset['programs']['program_name']."</br>";
	$i++;
?>
<?php endforeach; } ?>

<?php //echo $this->Form->create('Course');?>
<?php echo $this->Form->input('program_id', array('options' => $batchProgram, 'label' => false, 'empty' => __("----- Select DataSet-----"), 'class' => 'js-data-set')); ?>
<div id="semesters"></div>
<?php //echo $this->Form->end(); ?>

<?php
echo $this->Html->script('common');
?>

