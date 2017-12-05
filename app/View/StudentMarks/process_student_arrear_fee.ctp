<div class="students form">
	<?php echo $this->Form->create('StudentMark', array('type' => 'file'));?>
	
	<div class="bgFrame1">
	 <div class=" col-sm-6">
			<?php echo $this->Form->input('month_year_id', array('type' => 'select', 'options' => $monthyears, 'empty' => __("-- MonthYear --"), 'label' => "MonthYear <span class='ash'>*</span>"));?>
		</div> 
	   	<div class=" col-sm-6">
	   		<?php echo $this->Form->input('csv', array('type' => 'file','required'=>'required', 'label' => "Upload File <span class='ash'>*</span>")); ?>
	   	</div>
	   	<div class=" col-sm-6">
	   		<?php echo $this->Form->end(__('Submit')); ?>
	   	</div>
   </div>
   
<span class='breadcrumb1'>
<span class='navbar-brand'><small>MARKS <i class='ace-icon fa fa-angle-double-right'></i></small></span>
<?php echo $this->Html->link("<span class='navbar-brand'><small> C.A.E <i class='ace-icon fa fa-angle-double-right'></i></small></span>", array("controller"=>"Students",'action' => 'student_search'),array('data-placement'=>'left','escape' => false)); ?>
<?php echo $this->Html->link("<span class='navbar-brand'><small> Marks Import <i class='ace-icon fa fa-angle-double-right'></i></small></span>", array("controller"=>"Students",'action' => 'student_upload'),array('data-placement'=>'left','escape' => false)); ?>
</span>
