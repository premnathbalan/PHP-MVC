<div>
	<div class="bgFrame1">
		<?php echo $this->Form->create('DN'); ?>
		<div class="col-lg-12">
			<div class="col-lg-4">
				<?php echo $this->Form->input('monthyears', array('type' => 'select', 'empty' => __("----- Exam Month Year-----"), 'label' => "Exam&nbsp;Month&nbsp;Year<span class='ash'>*</span>", 'required'=>'required')); ?>
			</div>
			<div class="col-lg-4">
				<?php echo $this->Form->input('fromDate', array('type' => 'text', 'label' => "From&nbsp;Date", 'id' => 'select_date')); ?>
			</div>
			<div class="col-lg-4">
				<?php echo $this->Form->input('toDate', array('type' => 'text', 'label' => "To&nbsp;Date", 'id' => 'select_date1')); ?>
			</div>			
			<div class="col-lg-4">
				<?php echo $this->Form->button('<i class="ace-icon fa fa-file-pdf-o bigger-110"></i>'.__('Examiner List'),array('type'=>'submit','name'=>'ExaminerList','value'=>'ExaminerList','class'=>'btn'));?>
				<?php echo $this->Form->button('<i class="ace-icon fa fa-file-pdf-o bigger-110"></i>'.__('Strength Report'),array('type'=>'submit','name'=>'StrengthReport','value'=>'StrengthReport','class'=>'btn'));?>
			</div>
		</div>
		<?php echo $this->Form->end(); ?>
	</div>
	
	<div id="tblFoilCard"></div>
	<?php echo $this->Html->script('common-front');?>
</div>	

<span class='breadcrumb1'>
<span class='navbar-brand'><small>EXAMINATION <i class='ace-icon fa fa-angle-double-right'></i></small></span>
<?php echo $this->Html->link("<span class='navbar-brand'><small>Dummy Number Examiner List <i class='ace-icon fa fa-angle-double-right'></i></small></span>", array("controller"=>"DummyNumberAllocations",'action' => 'index'),array('data-placement'=>'left','escape' => false)); ?>
</span>