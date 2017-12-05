<?php echo $this->Form->create('Batchwise');?>
<div class="caes index">
<div class="searchFrm bgFrame1">
		<div class="col-sm-12">
			<div class="col-lg-4">		
				<?php echo $this->Form->input('month_year_id', array('type' => 'select', 'options'=>$monthyears, 'empty' => __("----- Select Month Year-----"), 'label' => "<span class='ash'>*</span> MonthYear", 'class' => 'js-month-year')); ?>
			</div>
			<div class="col-lg-4">
				<?php echo $this->Form->input('batch_id', array('label' => "<span class='ash'>*</span> Batch", 'empty' => __("----- Select Batch-----"), 'class' => 'js-batch', 'style'=>'width:150px;')); ?>
			</div>
			<div class="col-lg-4">
				<div class="academic">
				<?php echo $this->Form->input('academic', array('type' => 'select', 'empty' => __("----- Select Program-----"), 'label' => "Program<span class='ash'>*</span>", 'class' => 'js-academic')); 
				echo $this->Form->input('type', array('type' => 'hidden', 'value'=>'WP', 'label' => false));
				?>
				</div>
			</div>
		</div>
		<div>
			<div class="col-lg-4">
				<div id="programs" class="program">
				<?php echo $this->Form->input('program_id', array('type' => 'select', 'empty' => __("----- Select Specialisation-----"), 'label' => "Specialisation<span class='ash'>*</span>", 'class' => 'js-program')); ?>
				</div>
			</div>
			<div class="col-lg-8">
				<div id="courses" class="course">
				<?php echo $this->Form->input('cm_id', array('type' => 'select', 'empty' => __("----- Select Course-----"), 'label' => "Course<span class='ash'>*</span>", 'class' => 'js-course')); ?>
				</div>
			</div>
		</div>
		<div>
			<div class="col-lg-12">			
				<?php echo $this->Form->button('<i class="ace-icon fa fa-check bigger-110"></i>'.__('&nbsp;Get&nbsp;&nbsp;'),array('type'=>'button','name'=>'button','value'=>'get','class'=>'btn js-pgmwise-publish-result')); ?>
				<?php echo $this->Html->link(' <i class="ace-icon fa fa-undo bigger-110"></i> '.'&nbsp;Reset&nbsp;',array("controller"=>"EsePracticals",'action'=>'calculate'),array('type'=>'submit','name'=>'reset','value'=>'reset','class'=>'btn','escape' => false));?>
			</div>
		</div>
	</div>	
</div>
<div id="reportDisplay"></div>

<?php echo $this->Form->end(); ?>

<?php echo $this->Html->script('common'); ?>

<span class='breadcrumb1'>
<span class='navbar-brand'><small>RESULTS <i class='ace-icon fa fa-angle-double-right'></i></small></span>
<?php echo $this->Html->link("<span class='navbar-brand'><small>Programwise Publish Result <i class='ace-icon fa fa-angle-double-right'></i></small></span>", array("controller"=>"EndSemesterExams",'action' => 'batchwise'),array('data-placement'=>'left','escape' => false)); ?>
</span>