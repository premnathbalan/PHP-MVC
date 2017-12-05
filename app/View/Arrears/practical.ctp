<?php echo $this->Form->create('Practical');?>
<div class="caes index">
	<div class="searchFrm bgFrame1">
		<div class="col-sm-12">
			<div class="col-lg-6">		
				<?php echo $this->Form->input('month_year_id', array('type' => 'select', 'options'=>$monthyears, 'empty' => __("----- Select ExamMonthYear-----"), 'label' => "<span class='ash'>*</span> ExamMonthYear")); ?>
			</div>
			<div class="col-lg-6" style='float:right;'>			
				<?php echo $this->Form->button('<i class="ace-icon fa fa-check bigger-110"></i>'.__('&nbsp;Get&nbsp;&nbsp;'),array('type'=>'button','name'=>'button','value'=>'get','class'=>'btn js-arrear-practical1')); ?>
				<?php echo $this->Html->link(' <i class="ace-icon fa fa-undo bigger-110"></i> '.'&nbsp;Reset&nbsp;',array("controller"=>"Arrears",'action'=>'practical'),array('type'=>'submit','name'=>'reset','value'=>'reset','class'=>'btn','escape' => false));?>
			</div>
		</div>
	</div>	
</div>

<div id="result"></div>

<?php echo $this->Form->end(); ?>
<script>leftMenuSelection('Arrears/practical');</script>
<?php echo $this->Html->script('common'); ?>

<span class='breadcrumb1'>
<span class='navbar-brand'><small>Practicals <i class='ace-icon fa fa-angle-double-right'></i></small></span>
<?php echo $this->Html->link("<span class='navbar-brand'><small> Arrear Report <i class='ace-icon fa fa-angle-double-right'></i></small></span>", array("controller"=>"Arrears",'action' => 'practical'),array('data-placement'=>'left','escape' => false)); ?>
</span>