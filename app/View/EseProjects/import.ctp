	<?php echo $this->Form->create('EseProject', array('type' => 'file'));
	$options = array("1"=>"1", "2"=>"2", "3"=>"3");
	?>
	
	<div class="bgFrame1">
	 <!--<div class=" col-sm-4">
			<?php //echo $this->Form->input('assessment_id', array('type' => 'select', 'options' => $options, 'empty' => __("-- assessment --"), 'label' => "assessment <span class='ash'>*</span>"));?>
		</div>--> 
	   	<div class=" col-sm-8">
	   		<?php echo $this->Form->input('csv', array('type' => 'file','required'=>'required', 'label' => "Upload File <span class='ash'>*</span>")); ?>
	   	</div>
	   	<div class=" col-sm-4">
	   		<?php echo $this->Form->end(__('Submit')); ?>
	   	</div>
   </div>
   
<script>leftMenuSelection('EseProjects');</script>

<span class='breadcrumb1'>
<span class='navbar-brand'><small>MARKS <i class='ace-icon fa fa-angle-double-right'></i></small></span>
<span class='navbar-brand'><small>Project <i class='ace-icon fa fa-angle-double-right'></i></small></span>
<span class='navbar-brand'><small>E.S.E <i class='ace-icon fa fa-angle-double-right'></i></small></span>
<?php echo $this->Html->link("<span class='navbar-brand'><small> Import <i class='ace-icon fa fa-angle-double-right'></i></small></span>", array("controller"=>"EseProjects",'action' => 'import'),array('data-placement'=>'left','escape' => false)); ?>
</span>