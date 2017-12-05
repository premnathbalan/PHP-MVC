<div class="caes index">
<div class="searchFrm bgFrame1">
	<?php echo $this->Form->create('Practical');?>
		<div class="searchFrm col-sm-12" >
		<div class="col-sm-9">
			<div class="col-lg-6">
				<?php echo $this->Form->input('batch_id', array('label' => "<span class='ash'>*</span> Batch", 'empty' => __("----- Select Batch-----"), 'class' => 'js-batch')); ?>
			</div>
			<div class="col-lg-6">		
				<?php echo $this->Form->input('academic_id', array('type' => 'select', 'empty' => __("----- Select Program-----"), 'label' => "<span class='ash'>*</span> Program", 'class' => 'js-academic')); ?>
			</div>
			<div class="col-lg-6">
				<div id="programs" class="program"><?php echo $this->Form->input('program_id', array('type' => 'select', 'empty' => __("----- Select Specialisation-----"), 'label' => "<span class='ash'>*</span> Specialisation", 'class' => 'js-program')); ?></div>
			</div>
			<div class="col-lg-6">		
				<div id="monthyears" class="monthyear"><?php echo $this->Form->input('month_year_id', array('type' => 'select', 'empty' => __("----- Select Month Year-----"), 'label' => "<span class='ash'>*</span> MonthYear", 'class' => 'js-month-year')); ?></div>
			</div>
		</div>
		
		<div class="col-lg-3">			
		<?php echo $this->Form->button('<i class="ace-icon fa fa-print fa-lg"></i>'.__('PRINT'),array('type'=>'submit','name'=>'PRINT','value'=>'PRINT','class'=>'btn'));?>
		</div>
		<div class="col-lg-3">
		<?php echo $this->Form->button('<i class="ace-icon fa fa-undo bigger-110"></i>'.__('Reset'),array('type'=>'reset','name'=>'submit','value'=>'clear','class'=>'btn'));?>
		</div>

		</div>
	</div>	

<?php echo $this->Form->end(); ?>
</div>

<script>leftMenuSelection('EsePracticals/finalPractical');</script>
<span class='breadcrumb1'>
<span class='navbar-brand'><small>MARKS <i class='ace-icon fa fa-angle-double-right'></i></small></span>
<span class='navbar-brand'><small>Practical <i class='ace-icon fa fa-angle-double-right'></i></small></span>
<?php echo $this->Html->link("<span class='navbar-brand'><small> Final Practical Marks <i class='ace-icon fa fa-angle-double-right'></i></small></span>", array("controller"=>"EsePracticals",'action' => 'finalPractical'),array('data-placement'=>'left','escape' => false)); ?>
</span>