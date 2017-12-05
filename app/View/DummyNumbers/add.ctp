<div class="dummyNumbers form">

	<div class="bgFrame1">
		<div class="col-lg-12">
			<div class="col-lg-4">
				<?php echo $this->Form->input('monthyears', array('type' => 'select', 'empty' => __("----- Exam Month Year-----"), 'label' => "Exam&nbsp;Month&nbsp;Year<span class='ash'>*</span>", 'required'=>'required','onchange'=>'loadExamDate(this.value);')); ?>
			</div>
			<div class="col-lg-4" id="examDate">
				<?php echo $this->Form->input('timeTables', array('label' => "Exam&nbsp;Date<span class='ash'>*</span>",'empty' => __("----- Exam Date -----"),'id'=>'exam_date', 'required'=>'required','onchange'=>'eDCommonCode(this.value);')); ?>
			</div>
			<div class="col-lg-4">
			<?php echo $this->Form->input('exam_session' ,array('label' => "Exam Session",'options' => array('FN' => 'Forenoon', 'AN' => 'Afternoon')));?>
			</div>
		</div>	
		<div class="col-lg-12">	
			<div class="col-lg-4" class="common_code" id="eDCommonCode">				
				<?php echo $this->Form->input('common_code', array('label' => "Common&nbsp;Code<span class='ash'>*</span>",'type'=>'select', 'empty' => __("----- All Common Code -----"), 'required'=>'required')); ?>
			</div>	
			
			<div class="col-lg-4"></div>	
			<div class="col-lg-4">
				<?php echo $this->Form->button('<i class="ace-icon fa fa-check bigger-110"></i>'.__('Search'),array('type'=>'submit','name'=>'Search','value'=>'Search','class'=>'btn',"onclick"=>"return DateExamSubject();"));?>
			</div>
		</div>
	</div>

	<?php echo $this->Form->create('DummyNumber'); ?>
	<div id='DateExamSubject'></div>
	<?php echo $this->Form->end(); ?>
	
</div>

<script>leftMenuSelection('DummyNumbers/add');</script>
<?php echo $this->Html->script('common-front');?>
<span class='breadcrumb1'>
<span class='navbar-brand'><small>EXAMINATION <i class='ace-icon fa fa-angle-double-right'></i></small></span>
<?php echo $this->Html->link("<span class='navbar-brand'><small>Dummy Number <i class='ace-icon fa fa-angle-double-right'></i></small></span>", array("controller"=>"DummyNumbers",'action' => 'index'),array('data-placement'=>'left','escape' => false)); ?>
<?php echo $this->Html->link("<span class='navbar-brand'><small>ADD <i class='ace-icon fa fa-angle-double-right'></i></small></span>", array("controller"=>"DummyNumbers",'action' => 'add'),array('data-placement'=>'left','escape' => false)); ?>
</span>