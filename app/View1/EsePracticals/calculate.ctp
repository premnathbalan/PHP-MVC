<div class="caes index">
<div class="searchFrm bgFrame1">
	<?php echo $this->Form->create('Practical');?>
		<div class="searchFrm" >
		<div class="col-sm-12">
			<div class="col-lg-4">
				<?php echo $this->Form->input('batch_id', array('label' => "<span class='ash'>*</span> Batch", 'empty' => __("----- Select Batch-----"), 'class' => 'js-batch')); ?>
			</div>
			<div class="col-lg-4">		
				<?php echo $this->Form->input('academic_id', array('type' => 'select', 'empty' => __("----- Select Program-----"), 'label' => "<span class='ash'>*</span> Program", 'class' => 'js-academic')); ?>
			</div>
			<div class="col-lg-4">
				<div id="programs" class="program"><?php echo $this->Form->input('program_id', array('type' => 'select', 'empty' => __("----- Select Specialisation-----"), 'label' => "<span class='ash'>*</span> Specialisation", 'class' => 'js-program')); ?></div>
			</div>
		</div>	
		<div class="col-sm-12">	
			<div class="col-lg-4">		
				<div id="monthyears" class="monthyear"><?php echo $this->Form->input('month_year_id', array('type' => 'select', 'empty' => __("----- Select Month Year-----"), 'label' => "<span class='ash'>*</span> MonthYear", 'class' => 'js-month-year')); ?></div>
			</div>
			<div class="col-lg-4">			
			<?php //echo $this->Form->button('<i class="ace-icon fa fa-check bigger-110"></i>'.__('&nbsp;Get&nbsp;&nbsp;'),array('type'=>'button','name'=>'button','value'=>'get','class'=>'btn js-practical-get')); ?>
			<?php echo $this->Form->button('<i class="ace-icon fa fa-check bigger-110"></i>'.__('&nbsp;Calculate&nbsp;&nbsp;'),array('type'=>'submit','name'=>'submit','value'=>'calculate','class'=>'btn js-practical')); ?>
			<?php echo $this->Html->link(' <i class="ace-icon fa fa-undo bigger-110"></i> '.'&nbsp;Reset&nbsp;',array("controller"=>"EsePracticals",'action'=>'calculate'),array('type'=>'submit','name'=>'reset','value'=>'reset','class'=>'btn','escape' => false));?>
			</div>

		</div>
	</div>	

<?php echo $this->Form->end(); ?>

</div>
<div id="marksDisplay">
<?php echo $this->element('marks'); ?>
</div>

<?php echo $this->Html->script('common'); ?>
<span class='breadcrumb1'>
<span class='navbar-brand'><small>MARKS <i class='ace-icon fa fa-angle-double-right'></i></small></span>
<span class='navbar-brand'><small>Practical <i class='ace-icon fa fa-angle-double-right'></i></small></span>
<?php echo $this->Html->link("<span class='navbar-brand'><small> Calculate Practical <i class='ace-icon fa fa-angle-double-right'></i></small></span>", array("controller"=>"EsePracticals",'action' => 'calculate'),array('data-placement'=>'left','escape' => false)); ?>
</span>