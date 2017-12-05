<div class="searchFrm bgFrame1">
<?php echo $this->Form->create('Student');?>
	<?php echo $this->Form->input('batch_id', array( 'empty' => __(" - Batch - "),'required'=>'', 'class' => 'js-batch','style'=>'width:120px;')); ?>
	<?php echo $this->Form->input('academic_id', array('label'=>'Program','type' => 'select', 'empty' => __("----- Select Program-----"),'required'=>'',  'class' => 'js-academic')); ?>
	<div id="programs" class="program"><?php echo $this->Form->input('program_id', array('label'=>'Specialisation','type' => 'select', 'empty' => __("----- Select Specialisation-----"),'required'=>'', 'class' => 'js-programs')); ?></div>
	<?php //echo $this->Form->button('<i class="ace-icon fa fa-check bigger-110"></i>'.__('Submit'),array('type'=>'submit','name'=>'submit','value'=>'submit','class'=>'btn'));?>
	<?php echo $this->Form->button('<i class="ace-icon fa fa-file-pdf-o bigger-110"></i>'.__('PDF'),array('type'=>'submit','name'=>'submit','value'=>'PDF','class'=>'btn'));?>

<?php echo $this->Form->end(); ?>
</div>
	
<span class='breadcrumb1'>
<span class='navbar-brand'><small>STUDENTS <i class='ace-icon fa fa-angle-double-right'></i></small></span>
<?php echo $this->Html->link("<span class='navbar-brand'><small> PHOTO LIST <i class='ace-icon fa fa-angle-double-right'></i></small></span>", array("controller"=>"Students",'action' => 'photo_list'),array('data-placement'=>'left','escape' => false)); ?>
</span>