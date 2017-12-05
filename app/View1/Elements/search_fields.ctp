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