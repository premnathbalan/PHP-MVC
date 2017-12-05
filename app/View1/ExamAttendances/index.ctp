<div class="searchFrm bgFrame1">	
	<?php echo $this->Form->create('EA'); ?>
	<div class="col-lg-4">
		<?php echo $this->Form->input('monthyears', array('type' => 'select', 'empty' => __("----- Exam Month Year-----"), 'label' => "Exam&nbsp;Month&nbsp;Year<span class='ash'>*</span>", 'required'=>'required','onchange'=>'loadExamDate(this.value);')); ?>
	</div>
	<div class="col-lg-4">
		<?php echo $this->Form->input('exam_type' ,array('label' => "Exam Type<span class='ash'>*</span>",'options' => array('R' => 'Regular', 'A' => 'Arrear'),'empty' => __("-- All Type --")));?>
	</div>	
	<div class="col-lg-4">
		<?php echo $this->Form->input('exam_session' ,array('label' => "Exam Session",'options' => array('FN' => 'Forenoon', 'AN' => 'Afternoon'),'empty' => __("-- All Session --")));?>
	</div>
	<div class="col-lg-4" id="examDate">
		<?php echo $this->Form->input('exam_date', array('type' => 'select', 'empty' => __("----- All Exam Dates -----"), 'label' => "Exam&nbsp;Date<span class='ash'>*</span>", 'required'=>'required')); ?>
	</div>	
	<div class="col-lg-8" style="text-align:center;">		
		<?php echo $this->Form->button('<i class="ace-icon fa fa-check bigger-110"></i>'.__('Search'),array('type'=>'button','name'=>'submit','value'=>'submit','class'=>'btn',"onclick"=>"ExamAttendanceSearch();"));?>
		<?php echo $this->Form->button('<i class="ace-icon fa fa-file-pdf-o bigger-110"></i>'.__('Attendance Foil Card'),array('type'=>'submit','name'=>'foilCard','value'=>'foilCard','class'=>'btn'));?>
		<?php echo $this->Form->button('<i class="ace-icon fa fa-file-excel-o bigger-110"></i>'.__('Attendance Foil Card Excel'),array('type'=>'submit','name'=>'foilCardExcel','value'=>'foilCardExcel','class'=>'btn'));?>
		<?php echo $this->Form->button('<i class="ace-icon fa fa-file-pdf-o bigger-110"></i>'.__('COVER PAGE'),array('type'=>'submit','name'=>'coverPage','value'=>'coverPage','class'=>'btn'));?>				
		<?php echo $this->Form->button('<i class="ace-icon fa fa-print"></i>'.__('PRINT'),array('type'=>'submit','name'=>'print','value'=>'print','class'=>'btn'));?>
	</div>
	<?php echo $this->Form->end(); ?>		
</div>

<div id="listExamAttendances"></div>

<?php echo $this->Html->script('common-front');?>
<span class='breadcrumb1'>
<span class='navbar-brand'><small>EXAMINATION <i class='ace-icon fa fa-angle-double-right'></i></small></span>
<?php echo $this->Html->link("<span class='navbar-brand'><small>Exam Attendance <i class='ace-icon fa fa-angle-double-right'></i></small></span>", array("controller"=>"ExamAttendances",'action' => 'index'),array('data-placement'=>'left','escape' => false)); ?>
</span>