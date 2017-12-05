<div class="students view">
<?php echo $this->element('student_view',array("studentId"=>$studentId)); ?>
<?php echo $this->render('../Students/info',false); ?>
</div>

<div class="bgFrame2 h120 col-sm-12">
	<?php echo $this->Form->create('StudentRemark'); ?>	
	<?php echo $this->Form->input("remarks", array('type' => 'textarea','required'=>'required',"label"=>false,'class'=>'col-sm-11 h100','maxlength'=>'5000')); ?>
	<input type="hidden" name="studentId" value="<?php echo $studentId;?>" size='1'/>
	<?php echo $this->Form->end('Submit',array('class'=>'col-sm-1','style'=>'margin-top:150px;')); ?>
</div>
<div style="clear:both;"></div>


<?php foreach ($StudentRemark as $StudentRemark):?>
<div class="students view userFrm bgFrame2 h120" style="margin:5px 0px;">

		<?php echo h($StudentRemark['StudentRemark']['remarks']); ?>
		<br/><b><?php echo h($StudentRemark['User']['username']); ?>
		<?php echo date( "d-M-Y h:i:s", strtotime(h($StudentRemark['StudentRemark']['created'])) ); ?></b>


</div>
<?php endforeach; ?>	

<script>
leftMenuSelection('Students/regNoSearch');
</script>  

<span class='breadcrumb1'>
<span class='navbar-brand'><small>STUDENTS <i class='ace-icon fa fa-angle-double-right'></i></small></span>
<?php echo $this->Html->link("<span class='navbar-brand'><small> LIST <i class='ace-icon fa fa-angle-double-right'></i></small></span>", array("controller"=>"students",'action' => 'student_search'),array('data-placement'=>'left','escape' => false)); ?>
<?php echo $this->Html->link("<span class='navbar-brand'><small> REMARKS ( $studentId ) <i class='ace-icon fa fa-angle-double-right'></i></small></span>", array("controller"=>"Students",'action' => 'remarks',$studentId),array('data-placement'=>'left','escape' => false)); ?>
</span>