<?php echo $this->element('student_view',array("studentId"=>$studentId)); ?>
<?php echo $this->render('../Students/info',false); ?>
<div style="clear:both;"></div>

<dl class="bgFrame2 h80">	
	<div class="col-sm-5" style="margin-top:15px;">
		<?php echo $this->Form->create('Student', array('type' => 'file')); ?>
		<?php echo $this->Form->input('signature', array('type' => 'file', 'label' => 'Upload Student Signature','style'=>'float:right;top:0;')); ?>				
	</div>
	
	<div class="col-sm-3" style="margin-top:15px;">
	<?php echo $this->Form->end(__('Submit')); ?>
	</div>
	
	<div class="col-sm-3">
	<?php if($student['Student']['signature']){echo "<img src='/sets2015/img/signatures/".$student['Student']['signature']."' style='width:300px;height:50px;'/>";}?>
	</div>
	
</dl>

<script>
leftMenuSelection('Students/regNoSearch');
</script>  

<span class='breadcrumb1'>
<span class='navbar-brand'><small>STUDENTS <i class='ace-icon fa fa-angle-double-right'></i></small></span>
<?php echo $this->Html->link("<span class='navbar-brand'><small> LIST <i class='ace-icon fa fa-angle-double-right'></i></small></span>", array("controller"=>"students",'action' => 'student_search'),array('data-placement'=>'left','escape' => false)); ?>
<?php echo $this->Html->link("<span class='navbar-brand'><small> SIGNATURE UPLOAD ( $studentId ) <i class='ace-icon fa fa-angle-double-right'></i></small></span>", array("controller"=>"Students",'action' => 'signature',$studentId),array('data-placement'=>'left','escape' => false)); ?>
</span>