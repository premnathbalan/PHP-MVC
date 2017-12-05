<div>
	<div class="bgFrame1">
		<?php echo $this->Form->create('DN'); ?>
		<div class="col-lg-12">
			<div class="col-lg-4">
				<?php echo $this->Form->input('monthyears', array('type' => 'select', 'empty' => __("----- Exam Month Year-----"), 'label' => "Exam&nbsp;Month&nbsp;Year<span class='ash'>*</span>", 'required'=>'required','onchange'=>'loadExamDate(this.value);')); ?>
			</div>
			<div class="col-lg-4" id="examDate">
				<?php echo $this->Form->input('exam_date', array('type' => 'select', 'empty' => __("----- All Exam Dates -----"), 'label' => "Exam&nbsp;Date<span class='ash'>*</span>", 'required'=>'required')); ?>
			</div>	
			<div class="col-lg-4">
				<?php echo $this->Form->button('<i class="ace-icon fa fa-check bigger-110"></i>'.__('Search'),array('type'=>'button','name'=>'Search','value'=>'Search','class'=>'btn',"onclick"=>"DummyFoilCard();"));?>
				<?php echo $this->Form->button('<i class="ace-icon fa fa-file-pdf-o bigger-110"></i>'.__('Foil Card'),array('type'=>'submit','name'=>'foilCard','value'=>'foilCard','class'=>'btn'));?>
				<?php echo $this->Form->button('<i class="ace-icon fa fa-file-pdf-o bigger-110"></i>'.__('COVER PAGE'),array('type'=>'submit','name'=>'coverPage','value'=>'coverPage','class'=>'btn'));?>
			</div>
		</div>
		<?php echo $this->Form->end(); ?>
	</div>
	
	<div id="tblFoilCard"></div>
	<?php echo $this->Html->script('common-front');?>
</div>	

<span class='breadcrumb1'>
<span class='navbar-brand'><small>EXAMINATION <i class='ace-icon fa fa-angle-double-right'></i></small></span>
<?php echo $this->Html->link("<span class='navbar-brand'><small>Dummy Number Foil Card & Cover Page <i class='ace-icon fa fa-angle-double-right'></i></small></span>", array("controller"=>"DummyNumberAllocations",'action' => 'index'),array('data-placement'=>'left','escape' => false)); ?>
</span>