<div>
<div class="searchFrm bgFrame1">	
	<?php echo $this->Form->create('EA'); ?>
	<div class="col-lg-4">
		<?php echo $this->Form->input('monthyears', array('type' => 'select', 'empty' => __("----- Exam Month Year-----"), 'label' => "Exam&nbsp;Month&nbsp;Year<span class='ash'>*</span>", 'default'=>$exam_month_year_id, 'required'=>'required','onchange'=>'loadExamDate(this.value);')); ?>
	</div>
	<div class="col-lg-4">
		<?php echo $this->Form->input('exam_type' ,array('label' => "Exam Type<span class='ash'>*</span>",'options' => array( 'A' => 'Arrear')));?>
	</div>
	<div class="col-lg-4" style="text-align:center;">		
		<?php echo $this->Form->button('<i class="ace-icon fa fa-check bigger-110"></i>'.__('Search'),array('type'=>'button','name'=>'submit', 'id'=>'arrear_practical','value'=>'submit','class'=>'btn',"onclick"=>"PracticalAttendanceSearch();"));?>
		<?php //echo $this->Form->button('<i class="ace-icon fa fa-file-pdf-o bigger-110"></i>'.__('Attendance Sheet'),array('type'=>'submit','name'=>'foilCard','value'=>'foilCard','class'=>'btn'));?>
		<?php //echo $this->Form->button('<i class="ace-icon fa fa-file-pdf-o bigger-110"></i>'.__('COVER PAGE'),array('type'=>'submit','name'=>'coverPage','value'=>'coverPage','class'=>'btn'));?>				
	</div>
	<?php echo $this->Form->end(); ?>		
</div>
<?php echo $this->Html->script('common');?>
<?php
if (isset($exam_month_year_id) && ($exam_month_year_id)) {
?>
<script>$('#arrear_practical').trigger('click');</script>
<?php
}
?>
<script>leftMenuSelection('Arrears/index');</script>

<div id="listExamAttendances">
<?php if (isset($exam_month_year_id) && ($exam_month_year_id)) { ?>
<img src='/sets2015/img/loading.gif' />
<?php } ?>
</div>
</div>

<span class='breadcrumb1'>
<span class='navbar-brand'><small>PRACTICALS <i class='ace-icon fa fa-angle-double-right'></i></small></span>
<?php echo $this->Html->link("<span class='navbar-brand'><small>Attendance, Attendance Sheet & Attendance Cover Page <i class='ace-icon fa fa-angle-double-right'></i></small></span>", array("controller"=>"Arrears",'action' => 'index'),array('data-placement'=>'left','escape' => false)); ?>
</span>
