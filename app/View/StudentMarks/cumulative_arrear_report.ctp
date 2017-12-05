
<div class="bgFrame1">	
	<div class="col-lg-12">
		<?php echo $this->Form->create('cumulativeArrearReport');?>
		<div class="col-lg-5">
			<?php echo $this->Form->input('monthyears', array('type' => 'select','required'=>'required', 'empty' => __("----- Exam Month Year-----"), 'label' => "Last&nbsp;Exam&nbsp;Month&nbsp;Year<span class='ash'>*</span>", 'required'=>'required')); ?>
		</div>
		<div class="col-lg-4" style="text-align:center;">		
			<?php echo $this->Form->button('<i class="ace-icon fa fa-download"></i>'.__('Download'),array('type'=>'submit','name'=>'submit','value'=>'submit','class'=>'btn'));?>
		</div>
		<?php echo $this->Form->end(); ?>
	</div>
</div>

<span class='breadcrumb1'>
<span class='navbar-brand'><small>EXAMINATION <i class='ace-icon fa fa-angle-double-right'></i></small></span>
<?php echo $this->Html->link("<span class='navbar-brand'><small>Cumulative Arrear Report <i class='ace-icon fa fa-angle-double-right'></i></small></span>", array("controller"=>"StudentMarks",'action' => 'cumulativeArrearReport'),array('data-placement'=>'left','escape' => false)); ?>
</span>