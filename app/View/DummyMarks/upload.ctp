<div>
		<div class="col-lg-12">
		<?php 
		//if($this->Html->checkPathAccesstopath('Courses/add','',$authUser['id'])){
			echo $this->Html->link( '<i class="ace-icon fa fa-download"></i>'. 'Download Template', array("controller"=>"DummyMarks",'action'=>'mark_upload_template'),array('class' =>"btn",'escape' => false, 'title'=>'Mark Upload Template','style'=>'margin-bottom:5px;float:right;')); 
		//}
		?>
		</div>
		<div style='clear:both;'></div>
		
		<div class="bgFrame1">
			<div class="col-lg-12">
				<?php echo $this->Form->create('markEntry', array('type' => 'file'));?>
				<div class="col-lg-4">
					<?php echo $this->Form->input('monthyears', array('type' => 'select', 'empty' => __("----- Exam Month Year-----"), 'label' => "Exam&nbsp;Month&nbsp;Year<span class='ash'>*</span>", 'required'=>'required')); ?>
				</div>
				<div class="col-lg-4">
					<?php echo $this->Form->input('mark_entry' ,array('label' => "Mark Entry<span class='ash'>*</span>",'options' => array('1' => 'Mark Entry - 1', '2' => 'Mark Entry - 2'),'empty' => __("-- Mark Entry --"), 'required'=>'required'));?>
				</div>
				<div class="col-sm-4">
					<?php echo $this->Form->input('marks', array('type' => 'file', 'label' => false, 'required'=>'required')); ?>
					<?php echo $this->Form->end(__('Submit')); ?>
				</div>
			</div>
		</div>
	
	<div id="tblDummyMarkAllot"  style="margin-top:5px;"></div>
	<?php echo $this->Html->script('common-front');?>
	<style>input[type="submit"]{float:right;margin:-25px 20px 0px 0px;}</style>
</div>	

<span class='breadcrumb1'>
<span class='navbar-brand'><small>EXAMINATION <i class='ace-icon fa fa-angle-double-right'></i></small></span>
<?php echo $this->Html->link("<span class='navbar-brand'><small>Dummy Mark Upload <i class='ace-icon fa fa-angle-double-right'></i></small></span>", array("controller"=>"DummyMarks",'action' => 'index'),array('data-placement'=>'left','escape' => false)); ?>
</span>