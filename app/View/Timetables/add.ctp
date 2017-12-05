<div class="timetables form">

<div class="searchFrm bgFrame1">	
	<div class="col-lg-4">
		<?php echo $this->Form->input('monthyears', array('type' => 'select', 'empty' => __("----- Exam Month Year-----"), 'label' => "Exam&nbsp;Month&nbsp;Year<span class='ash'>*</span>", 'required'=>'required')); ?>
	</div>
	<div class="col-lg-4">
		<?php echo $this->Form->input('batch_id', array('label' => "Batch", 'empty' => __("----- Batch-----"), 'class' => 'js-batch js-monthYear','required'=>'required')); ?>
	</div>	
	<div class="col-lg-4">
		<?php echo $this->Form->input('academic_id', array('type' => 'select', 'empty' => __("----- Select Program-----"), 'label' => 'Program', 'class' => 'js-academic','required'=>'required')); ?>
	</div>
	<div class="col-lg-4">
		<div id="programs" class="program">
		<?php echo $this->Form->input('program_id', array('type' => 'select', 'empty' => __("----- Select Specialisation-----"), 'label' => 'Specialisation', 'class' => 'js-program','required'=>'required')); ?>
		</div>
	</div>
	<div class="col-lg-4">
		<div class="semester"><?php echo $this->Form->input('month_year_id', array('type' => 'select', 'empty' => __("----- Select Semester -----"), 'label' => "Semester",'options' => array_combine(range(1,12,1),range(1,12,1)),'required'=>'required')); ?></div>
	</div>	
	<div class="col-lg-4">
	<?php echo $this->Form->input('exam_type' ,array('label' => "Exam Type<span class='ash'>*</span>",'options' => array('R' => 'Regular', 'A' => 'Arrear')));?>
	</div>
	<div class="col-lg-4"></div>	
	<div class="col-lg-4" style="text-align:center;">		
		<?php echo $this->Form->button('<i class="ace-icon fa fa-check bigger-110"></i>'.__('Search'),array('type'=>'submit','name'=>'submit','value'=>'submit','class'=>'btn',"onclick"=>"TimeTableSearch();"));?>
		<?php echo $this->Html->link('<i class="ace-icon fa fa-undo bigger-110"></i>'.'Reset',array("controller"=>"Timetables",'action'=>'add'),array('type'=>'submit','name'=>'reset','value'=>'reset','class'=>'btn','escape' => false));?>		
	</div>		
</div>

<div id ="listTimeTables"></div>

</div>

<?php echo $this->Html->script('common-front');?>
<script>leftMenuSelection('Timetables');</script>
<span class='breadcrumb1'>
<span class='navbar-brand'><small>EXAMINATION <i class='ace-icon fa fa-angle-double-right'></i></small></span>
<?php echo $this->Html->link("<span class='navbar-brand'><small>TIME TABLES <i class='ace-icon fa fa-angle-double-right'></i></small></span>", array("controller"=>"Timetables",'action' => 'index'),array('data-placement'=>'left','escape' => false)); ?>
<?php echo $this->Html->link("<span class='navbar-brand'><small>ADD <i class='ace-icon fa fa-angle-double-right'></i></small></span>", array("controller"=>"Timetables",'action' => 'add'),array('data-placement'=>'left','escape' => false)); ?>
</span>