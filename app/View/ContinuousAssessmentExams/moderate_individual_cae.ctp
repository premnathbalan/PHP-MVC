	<?php echo $this->Form->create('ContinuousAssessmentExam'); ?>
	
			<?php echo $this->Form->input('reg_number', array('type' => 'text', 'class'=>'js-mod-individual', 'label' => "Reg. Number <span class='ash'>*</span>"));?>
		  	<?php echo $this->Form->button('<i class="ace-icon fa fa-check bigger-110"></i>'.__('Get'),array('type'=>'button','name'=>'button','id'=>'button','value'=>'get','class'=>'btn individualStudent'));?>
		  	<div id="individualStudent" class="individualStudent">test</div>
		  	
	   		<?php echo $this->Form->end(__('Submit')); ?>
   
<span class='breadcrumb1'>
<span class='navbar-brand'><small>MARKS <i class='ace-icon fa fa-angle-double-right'></i></small></span>
<?php echo $this->Html->link("<span class='navbar-brand'><small> C.A.E <i class='ace-icon fa fa-angle-double-right'></i></small></span>", array("controller"=>"Students",'action' => 'student_search'),array('data-placement'=>'left','escape' => false)); ?>
<?php echo $this->Html->link("<span class='navbar-brand'><small> Moderate Individual Student CAE <i class='ace-icon fa fa-angle-double-right'></i></small></span>", array("controller"=>"Students",'action' => 'student_upload'),array('data-placement'=>'left','escape' => false)); ?>
</span>
