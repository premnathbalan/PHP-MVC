<div class="students view">
<?php echo $this->element('student_view',array("studentId"=>$studentId)); ?>
<?php echo $this->render('../Students/info',false); ?>
</div>

<div class="bgFrame2 h120 col-sm-12">
	<?php echo $this->Form->create('Student'); ?>
	<div class="col-sm-12">
		<div class="col-sm-4"></div>
	 	<div class="col-sm-4">
	       <?php echo $this->Form->radio('discontinued_status', array('0' => 'Active&nbsp;&nbsp;&nbsp;', '1' => 'Discontinued', '2' => 'Completed'),array('legend' => false,'default'=>$students[0]['Student']['discontinued_status']));?>
		</div>
		<div class="col-sm-4"></div>
	</div>	
	<?php echo $this->Form->input("reason", array('type' => 'textarea','required'=>'required',"label"=>false,'class'=>'col-sm-11 h100','maxlength'=>'5000','default'=>$students[0]['Student']['reason'])); ?>
	<input type="hidden" name="studentId" value="<?php echo $studentId;?>" size='1'/>
	<?php echo $this->Form->end('Submit',array('class'=>'col-sm-1','style'=>'margin-top:150px;')); ?>
</div>
<div style="clear:both;"></div>

<script>
leftMenuSelection('Students/chgStatus');
</script>  

<span class='breadcrumb1'>
<span class='navbar-brand'><small>STUDENTS <i class='ace-icon fa fa-angle-double-right'></i></small></span>
<?php echo $this->Html->link("<span class='navbar-brand'><small> LIST <i class='ace-icon fa fa-angle-double-right'></i></small></span>", array("controller"=>"students",'action' => 'student_search'),array('data-placement'=>'left','escape' => false)); ?>
<?php echo $this->Html->link("<span class='navbar-brand'><small> REMARKS ( $studentId ) <i class='ace-icon fa fa-angle-double-right'></i></small></span>", array("controller"=>"Students",'action' => 'remarks',$studentId),array('data-placement'=>'left','escape' => false)); ?>
</span>