<div class="col-sm-12">
	<?php 
	if($this->Html->checkPathAccesstopath('Revaluations/revaluation','',$authUser['id'])){
		echo $this->Html->link( '<i class="ace-icon fa fa-download"></i>'. 'Download Template', array("controller"=>"Revaluations",'action'=>'revaluation_upload_template'),array('class' =>"btn",'escape' => false, 'title'=>'Revaluation Upload Template','style'=>'margin-bottom:5px;float:right;')); 
	}
	?>
</div>
<div class="students form">
	<?php echo $this->Form->create('Revaluation', array('type' => 'file')); ?>
	<div class="bgFrame1 col-sm-12">
			<div class="col-sm-4">
	   			<?php echo $this->Form->input('month_year_id', array('label' => 'MonthYear', 'options'=>$monthyears, 'empty' => __("----- Select Month Year-----"), 'required'=>'required')); ?>
	   		</div>
	   		<div class="col-sm-4">
	   			<?php echo $this->Form->input('revaluation', array('type' => 'file','required'=>'required', 'label' => "Upload<span class='ash'>*</span>")); ?>
	   		</div>
	   		<div class="col-sm-4">
	   			<?php echo $this->Form->end(__('Submit')); ?>
	   		</div>
   </div>
</div>
   
<script>leftMenuSelection('Revaluations/upload');</script>

<span class='breadcrumb1'>
<span class='navbar-brand'><small>REVALUATION <i class='ace-icon fa fa-angle-double-right'></i></small></span>
<?php echo $this->Html->link("<span class='navbar-brand'><small> Bulk upload <i class='ace-icon fa fa-angle-double-right'></i></small></span>", array("controller"=>"Revaluations",'action' => 'upload'),array('data-placement'=>'left','escape' => false)); ?>
</span>