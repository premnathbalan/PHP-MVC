<div class="students form">
	<?php echo $this->Form->create('EsePractical', array('type' => 'file'));
	$options = array("1"=>"1", "2"=>"2", "3"=>"3");
	?>
	
	<div class="bgFrame1">
	 <div class=" col-sm-12">
	   	<div class=" col-sm-6">
	   		<?php echo $this->Form->input('csv', array('type' => 'file','required'=>'required', 'label' => "Upload&nbsp;File<span class='ash'>*</span>")); ?>
	   	</div>
	   	<div class=" col-sm-3">
	   		<?php echo $this->Form->end(__('Submit')); ?>
	   	</div>
	   </div>	
   </div>
   
<script>leftMenuSelection('EsePracticals/practical');</script>

<span class='breadcrumb1'>
<span class='navbar-brand'><small>MARKS <i class='ace-icon fa fa-angle-double-right'></i></small></span>
<span class='navbar-brand'><small>Practical <i class='ace-icon fa fa-angle-double-right'></i></small></span>
<?php echo $this->Html->link("<span class='navbar-brand'><small> E.S.E <i class='ace-icon fa fa-angle-double-right'></i></small></span>", array("controller"=>"EsePracticals",'action' => 'practical'),array('data-placement'=>'left','escape' => false)); ?>
<?php echo $this->Html->link("<span class='navbar-brand'><small> Bulk Import <i class='ace-icon fa fa-angle-double-right'></i></small></span>", array("controller"=>"EsePracticals",'action' => 'practicalImport'),array('data-placement'=>'left','escape' => false)); ?>
</span>