<div class="form">

	<div class="bgFrame1">
		<div class="col-lg-8">
				<?php echo $this->Form->create('StudentWithheld');?>
				<div style='width:400px;float:left;'>
				<?php echo $this->Form->input('monthyears', array('type' => 'select', 'empty' => __("----- Exam Month Year-----"), 'label' => "Exam&nbsp;Month&nbsp;Year<span class='ash'>*</span>", 'required'=>'required','style'=>'border:1px solid red;')); ?>
				</div>
				<div style='width:100px;float:left;'>
				<?php echo $this->Form->end(__('Download')); ?>
				</div>
		</div>			
	</div>
</div>

<script>leftMenuSelection('StudentWithhelds');</script>
<?php echo $this->Html->script('common-front');?>
<span class='breadcrumb1'>
<span class='navbar-brand'><small>RESULTS <i class='ace-icon fa fa-angle-double-right'></i></small></span>
<span class='navbar-brand'><small>RESULTS TO WEBSITE <i class='ace-icon fa fa-angle-double-right'></i></small></span>
<?php echo $this->Html->link("<span class='navbar-brand'><small>Student Withhelds <i class='ace-icon fa fa-angle-double-right'></i></small></span>", array("controller"=>"StudentWithhelds",'action' => 'index'),array('data-placement'=>'left','escape' => false)); ?>
</span>