<?php echo $this->Form->create('Student', array('type' => 'file'));?>
<div class="caes index">
	<div class="searchFrm bgFrame1 col-sm-12">		
		<div class="col-sm-4">
	   		<?php echo $this->Form->input('file', array('type' => 'file','required'=>'required', 'label' => "Upload<span class='ash'>*</span>")); ?>
	   	</div>
		<div class="col-lg-8">			
			<?php //echo $this->Form->button('<i class="ace-icon fa fa-check bigger-110"></i>'.__('&nbsp;Get&nbsp;&nbsp;'),array('type'=>'button','name'=>'button','value'=>'get','class'=>'btn js-mark-search')); ?>
			<?php echo $this->Form->button('<i class="ace-icon fa fa-print fa-lg"></i>'.__('PRINT'),array('type'=>'submit','name'=>'option','value'=>'PRINT','class'=>'btn'));?>
			<?php echo $this->Form->button('<i class="ace-icon fa fa-download fa-lg"></i>'.__('DOWNLOAD'),array('type'=>'submit','name'=>'option','value'=>'DOWNLOAD','class'=>'btn'));?>
			<?php echo $this->Html->link(' <i class="ace-icon fa fa-undo bigger-110"></i> '.'&nbsp;Reset&nbsp;',array("controller"=>"Students",'action'=>'stuMarkSearch'),array('type'=>'submit','name'=>'reset','value'=>'reset','class'=>'btn','escape' => false));?>
		</div>
	</div>	
</div>
<?php echo $this->Form->end(); ?>

<?php echo $this->Html->script('common'); ?>

<span class='breadcrumb1'>
<span class='navbar-brand'><small>Student <i class='ace-icon fa fa-angle-double-right'></i></small></span>
<?php echo $this->Html->link("<span class='navbar-brand'><small>BULK UPLOAD <i class='ace-icon fa fa-angle-double-right'></i></small></span>", array("controller"=>"Revaluations",'action' => 'revaluation'),array('data-placement'=>'left','escape' => false)); ?>
</span>