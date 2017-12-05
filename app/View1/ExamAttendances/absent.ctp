<?php echo $this->Form->create('EA'); ?>
<div class="searchFrm bgFrame1">
	<div class="col-lg-4">
		<?php echo $this->Form->input('monthyears', array('type' => 'select', 'empty' => __("----- Exam Month Year-----"), 'label' => "Exam&nbsp;Month&nbsp;Year<span class='ash'>*</span>", 'required'=>'required','onchange'=>'loadExamDate(this.value);')); ?>
	</div>
	<div class="col-lg-4">
		<?php echo $this->Form->input('exam_type' ,array('label' => "Exam Type",'options' => array('R' => 'Regular', 'A' => 'Arrear'),'empty' => __("-- All Type --")));?>
	</div>	
	<div class="col-lg-4">
		<?php echo $this->Form->input('exam_session' ,array('label' => "Exam Session",'options' => array('FN' => 'Forenoon', 'AN' => 'Afternoon'),'empty' => __("-- All Session --")));?>
	</div>
	<div class="col-lg-4" id="examDate">
		<?php echo $this->Form->input('exam_date', array('type' => 'select', 'empty' => __("----- All Exam Dates -----"), 'label' => "Exam&nbsp;Date", 'required'=>'required','onchange'=>'eDCommonCode(this.value);')); ?>
	</div>
	<div class="col-lg-4" class="common_code" id="eDCommonCode">				
		<?php echo $this->Form->input('common_code', array('label' => "Common&nbsp;Code<span class='ash'>*</span>",'type'=>'select', 'empty' => __("----- All Common Code -----"), 'required'=>'required')); ?>
	</div>
	<div class="col-lg-4" style="text-align:center;">
		<?php echo $this->Form->button('<i class="ace-icon fa fa-check bigger-110"></i>'.__('Search'),array('type'=>'button','name'=>'submit','value'=>'submit','class'=>'btn',"onclick"=>"ExamAbsentSearch();"));?>
		<?php echo $this->Html->link('<i class="ace-icon fa fa-undo bigger-110"></i>'.'Reset',array("controller"=>"ExamAttendances",'action'=>'absent'),array('type'=>'submit','name'=>'reset','value'=>'reset','class'=>'btn','escape' => false));?>		
	</div>
	<div class="col-lg-4">
		<?php echo $this->Form->button('<i class="ace-icon fa fa-file-excel-o"></i>'.__('Excel'),array('type'=>'submit','name'=>'excel','value'=>'excel','class'=>'btn'));?>
		<?php echo $this->Form->button('<i class="ace-icon fa fa-file-pdf-o bigger-110"></i>'.__('Print'),array('type'=>'submit','name'=>'print','value'=>'print','class'=>'btn'));?>
		<?php echo $this->Form->button('<i class="ace-icon fa fa-download"></i>'.__('Download'),array('type'=>'submit','name'=>'download','value'=>'download','class'=>'btn'));?>
	</div>
</div>
<?php echo $this->Form->end(); ?>
<div id="listExamAttendances"></div>

<?php echo $this->Html->script('common-front');?>
<span class='breadcrumb1'>
<span class='navbar-brand'><small>EXAMINATION <i class='ace-icon fa fa-angle-double-right'></i></small></span>
<?php echo $this->Html->link("<span class='navbar-brand'><small>ESE Exam Absent Record <i class='ace-icon fa fa-angle-double-right'></i></small></span>", array("controller"=>"ExamAttendances",'action' => 'absent'),array('data-placement'=>'left','escape' => false)); ?>
</span>