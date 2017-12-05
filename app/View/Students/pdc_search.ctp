<div>
<?php echo $this->Form->create('markSheet');
$type="";
if (isset($this->params['pass'][0]) && $this->params['pass'][0]>0) $type=$this->params['pass'][0];
?>
<div class="searchFrm bgFrame1">	
	<div class="col-lg-12">		
		<div class="col-lg-4">
			<?php echo $this->Form->input('batch_id', array('label' => "Batch<span class='ash'>*</span>", 'empty' => __("----- Batch-----"), 'class' => 'js-batch js-monthYear')); ?>
		</div>	
		<div class="col-lg-4">
			<?php echo $this->Form->input('academic_id', array('type' => 'select', 'empty' => __("----- Select Program-----"), 'label' => "Program<span class='ash'>*</span>", 'class' => 'js-academic')); ?>
		</div>		
		<div class="col-lg-4">
			<div id="programs" class="program">
			<?php echo $this->Form->input('program_id', array('type' => 'select', 'empty' => __("----- Select Specialisation-----"), 'label' => "Specialisation<span class='ash'>*</span>", 'class' => 'js-program')); ?>
			</div>
		</div>
	</div>
	<div class="col-lg-12">
		<div class="col-lg-4">
			<?php echo $this->Form->input('type_of_cert', array('type' => 'select', 'empty' => __("----- Type Of Certification -----"), 'label' => "Type Of Certification<span class='ash'>*</span>",'options'=>$typeOfCert,'default'=>$type,'required'=>'required')); ?>
		</div>
		<div class="col-lg-4">
			<?php echo $this->Form->input('frm_register_no', array('id'=>'register_no','type' => 'text', 'label' => "From Register No.", 'style' => 'margin-top:10px;')); ?>
		</div>
		<div class="col-lg-4">
			<?php echo $this->Form->input('to_register_no', array('id'=>'register_no','type' => 'text', 'label' => "To Register No.", 'style' => 'margin-top:10px;')); ?>
		</div>		
	</div>
	<div class="col-lg-12">
		<div class="col-lg-4"></div>
		<div class="col-lg-4" style="text-align:center;">
		<?php echo $this->Form->button('<i class="ace-icon fa fa-print fa-lg"></i>'.__('PRINT'),array('type'=>'submit','name'=>'submit','value'=>'PRINT','class'=>'btn'));?>
		</div>
	</div>
	
</div>
<?php echo $this->Form->end();?>

<?php echo $this->Html->script('common-front');?>
</div>

<span class='breadcrumb1'>
<span class='navbar-brand'><small>CERTIFICATES <i class='ace-icon fa fa-angle-double-right'></i></small></span>
<?php echo $this->Html->link("<span class='navbar-brand'><small>Provisional <i class='ace-icon fa fa-angle-double-right'></i></small></span>", array("controller"=>"Students",'action' => 'pdcSearch'),array('data-placement'=>'left','escape' => false)); ?>
</span>