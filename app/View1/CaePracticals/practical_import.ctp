<div class="students form">
	<?php echo $this->Form->create('CaePractical', array('type' => 'file'));
	$options = array("1"=>"1", "2"=>"2", "3"=>"3");
	?>
	
	<div class="bgFrame1">
	 <div class=" col-sm-12">
	   	<div class=" col-sm-6">
	   		<?php echo $this->Form->input('csv', array('type' => 'file','required'=>'required', 'label' => "Upload File <span class='ash'>*</span>")); ?>
	   	</div>
	   	<div class=" col-sm-3">
	   		<?php echo $this->Form->end(__('Submit')); ?>
	   	</div>
	   </div>	
   </div>
   
<script>leftMenuSelection('CaePracticals/practical');</script>

<span class='breadcrumb1'>
<span class='navbar-brand'><small>MARKS <i class='ace-icon fa fa-angle-double-right'></i></small></span>
<span class='navbar-brand'><small>Practical <i class='ace-icon fa fa-angle-double-right'></i></small></span>
<?php echo $this->Html->link("<span class='navbar-brand'><small> C.A.E <i class='ace-icon fa fa-angle-double-right'></i></small></span>", array("controller"=>"CaePracticals",'action' => 'practical'),array('data-placement'=>'left','escape' => false)); ?>
<?php echo $this->Html->link("<span class='navbar-brand'><small> Bulk import <i class='ace-icon fa fa-angle-double-right'></i></small></span>", array("controller"=>"CaePracticals",'action' => 'practicalImport'),array('data-placement'=>'left','escape' => false)); ?>
</span>
   