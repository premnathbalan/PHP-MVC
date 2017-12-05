<div>
	<div class="bgFrame1">
		<div class="col-lg-12">
			<div class="col-lg-4">
				<?php echo $this->Form->input('monthyears', array('type' => 'select', 'empty' => __("----- Exam Month Year-----"), 'label' => "Exam&nbsp;Month&nbsp;Year<span class='ash'>*</span>", 'required'=>'required')); ?>
			</div>
			  <div class="col-sm-4">
	        	<?php echo $this->Form->input('dummyNumber', array('maxlength'=>8));?>
	        </div>  
			<div class="col-lg-4">
				<?php echo $this->Form->button('<i class="ace-icon fa fa-check bigger-110"></i>'.__('Search'),array('type'=>'submit','name'=>'Search','value'=>'Search','class'=>'btn',"onclick"=>"DummyMarkAllot();"));?>
			</div>
		</div>
	</div>	
	
	<div id="tblDummyMarkAllot"  style="margin-top:5px;"></div>
	<?php echo $this->Html->script('common-front');?>
	<style>input[type="submit"]{float:right;margin:-25px 20px 0px 0px;}</style>
</div>	

<span class='breadcrumb1'>
<span class='navbar-brand'><small>EXAMINATION <i class='ace-icon fa fa-angle-double-right'></i></small></span>
<?php echo $this->Html->link("<span class='navbar-brand'><small>Dummy Mark Entry <i class='ace-icon fa fa-angle-double-right'></i></small></span>", array("controller"=>"DummyMarks",'action' => 'index'),array('data-placement'=>'left','escape' => false)); ?>
</span>