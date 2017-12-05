<?php echo $this->Form->create('DummyFinalMark');?>
<div class="searchFrm bgFrame1">
	<div class="searchFrm col-sm-12" >
			<div class="col-lg-4">
				<?php echo $this->Form->input('month_year_id', array('label' => 'MonthYear', 'options'=>$monthYears, 'empty' => __("----- Select Month Year-----"), 'class' => 'js-month-year', 'required'=>'required')); ?>
			</div>
			<div class="col-lg-4">
				<?php echo $this->Form->input('dummy_number', array('type'=>'text', 'label' => "<span class='ash'>*</span> Dummy&nbsp;Number",'class'=>'js-dmod-dummy-number', 'style'=>'width:100px;margin-top:8px;border-color:#000;')); ?>
			</div>
			<div class="col-lg-4">
				<?php echo $this->Form->button('<i class="ace-icon fa fa-check bigger-110"></i>'.__('&nbsp;Display&nbsp;&nbsp;'),array('type'=>'button','name'=>'submit','value'=>'get','class'=>'btn js-dummy-display')); ?>
			</div>
	</div>
</div>
<div id="dummyResult"></div>
<?php echo $this->Form->end(); ?>

<?php echo $this->Html->script('common'); ?>

<script>leftMenuSelection('Dummy Marks Moderation');</script>

<span class='breadcrumb1'>
<span class='navbar-brand'><small>EXAMINATION <i class='ace-icon fa fa-angle-double-right'></i></small></span>
<?php echo $this->Html->link("<span class='navbar-brand'><small>Dummy Marks Moderation <i class='ace-icon fa fa-angle-double-right'></i></small></span>", array("controller"=>"DummyMarks",'action' => 'moderation'),array('data-placement'=>'left','escape' => false)); ?>
</span>