<div>
<div class="caes index">
<div class="searchFrm bgFrame1" style="margin-top:5px;">
	<?php echo $this->Form->create('Student');?>
	<div class="searchFrm col-sm-12">
		<div class="col-sm-9">	
			<div class="col-lg-6">
				<?php echo $this->Form->input('batch_id', array('label' => 'Batch', 'empty' => __("----- Select Batch-----"), 'class' => 'js-batch')); ?>
			</div>
			
			<div class="col-lg-6">
				<?php echo $this->Form->input('academic_id', array('type' => 'select', 'empty' => __("----- Select Program-----"), 'label' => 'Program', 'class' => 'js-academic')); ?>
			</div>
		
			<div id="programs" class="program col-lg-6" >
				<?php echo $this->Form->input('program_id', array('type' => 'select', 'empty' => __("----- Select Specialisation-----"), 'label' => 'Specialisation', 'class' => 'js-program')); ?>
			</div>
			
			<div id="monthyears" class="monthyear col-lg-6">
				<?php echo $this->Form->input('month_year_id', array('type' => 'select', 'empty' => __("----- Select Month Year-----"), 'label' => 'MonthYear', 'class' => 'js-month-year')); ?>
			</div>
		</div>
		
		<div class="col-lg-3">	
			<?php echo $this->Form->button('<i class="ace-icon fa fa-check bigger-110"></i>'.__('Submit'),array('type'=>'button', 'name'=>'button','value'=>'submit','class'=>'btn js-report2', "onclick"=>"report2();"));?>
		</div>
		
		<div class="col-lg-3">	
			<?php echo $this->Form->button('<i class="ace-icon fa fa-undo bigger-110"></i> '.'&nbsp;Reset&nbsp;',array('type'=>'reset','name'=>'submit','value'=>'submit','class'=>'btn'));?>
		</div>
	</div>
</div>
	
</div>

<div id="results" class="results"></div>

<?php echo $this->Form->end(); ?>
<?php echo $this->Html->script('common'); ?>
<?php echo $this->Html->script('common-front'); ?>

<span class='breadcrumb1'>
<span class='navbar-brand'><small>REPORTS <i class='ace-icon fa fa-angle-double-right'></i></small></span>
<?php echo $this->Html->link("<span class='navbar-brand'><small> PROGRAMWISE REPORT <i class='ace-icon fa fa-angle-double-right'></i></small></span>", array("controller"=>"Timetables",'action' => 'programWise'),array('data-placement'=>'left','escape' => false)); ?>
</span>
</div>