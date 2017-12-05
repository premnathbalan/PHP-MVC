<?php echo $this->Form->create('Timetable');?>
<div class="caes index">
<div class="searchFrm bgFrame1">
		<div class="col-sm-12">
			<div class="col-lg-4">		
				<?php echo $this->Form->input('month_year_id', array('type' => 'select', 'options'=>$monthyears, 'empty' => __("----- Select Month Year-----"), 'label' => "<span class='ash'>*</span> MonthYear")); ?>
			</div>
			<div class="col-lg-8">			
				<?php echo $this->Form->end(__('Submit')); ?>
			</div>
		</div>
	</div>	
</div>

<?php echo $this->Html->script('common'); ?>

<span class='breadcrumb1'>
<span class='navbar-brand'><small>REPORTS <i class='ace-icon fa fa-angle-double-right'></i></small></span>
<?php echo $this->Html->link("<span class='navbar-brand'><small>DAYWISE STRENGTH REPORT <i class='ace-icon fa fa-angle-double-right'></i></small></span>", array("controller"=>"Timetables",'action' => 'daywiseStrengthReport'),array('data-placement'=>'left','escape' => false)); ?>
</span>