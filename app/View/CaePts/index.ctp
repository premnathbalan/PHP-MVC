<div id="js-load-forms"></div>

<div style="height:40px;">
		<?php 
		if($this->Html->checkPathAccesstopath('CaePts/add','',$authUser['id'])){
			echo $this->Html->link('<i class="ace-icon fa fa-plus-square"></i>'. 'Add CAE', array("controller"=>"CaePts",'action'=>'add'),array('class' =>" addBtn btn js-cae-add",'escape' => false, 'title'=>'Add')); 
		}	
		?>
</div>

<div class="caes index">
<div class="searchFrm bgFrame1" style="margin-top:5px;">
	<?php echo $this->Form->create('Student');?>
	<div class="searchFrm col-sm-12">
		<?php echo $this->element('search_fields'); ?>
		
		<div class="col-lg-3">	
			<?php echo $this->Form->button('<i class="ace-icon fa fa-check bigger-110"></i>'.__('Submit'),array('type'=>'button','id'=>'js-cae-display', 'name'=>'submit','value'=>'submit','class'=>'btn js-pt'));?>
		</div>
		
		<div class="col-lg-3">	
			<?php echo $this->Form->button('<i class="ace-icon fa fa-undo bigger-110"></i> '.'&nbsp;Reset&nbsp;',array('type'=>'reset','name'=>'submit','value'=>'submit','class'=>'btn'));?>
		</div>
	</div>
</div>
	
</div>

<div id="result" class="result"></div>

<?php echo $this->Form->end(); ?>
<?php echo $this->Html->script('common'); ?>

<span class='breadcrumb1'>
<span class='navbar-brand'><small>MARKS <i class='ace-icon fa fa-angle-double-right'></i></small></span>
<span class='navbar-brand'><small>Professional Training <i class='ace-icon fa fa-angle-double-right'></i></small></span>
<?php echo $this->Html->link("<span class='navbar-brand'><small> C.A.E <i class='ace-icon fa fa-angle-double-right'></i></small></span>", array("controller"=>"CaePts",'action' => 'index'),array('data-placement'=>'left','escape' => false)); ?>
</span>