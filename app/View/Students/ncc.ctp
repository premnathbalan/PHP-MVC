<div class="students view">
<?php echo $this->element('student_view',array("studentId"=>$studentId)); ?>
<?php echo $this->render('../Students/info',false); ?>
</div>

<div class="bgFrame2 h120 col-sm-12">
	<?php echo $this->Form->create('StudentNcc'); ?>	
	<div class="col-sm-4"> 
	<?php echo $this->Form->input("Joined_on", array('required'=>'required','label' => "Joined On <span class='ash'>*</span>", 'id' => 'select_date')); ?>
	</div>
	<div class="col-sm-3">
	<?php echo $this->Form->input("ncc_id", array('type' => 'select','required'=>'required', 'options' => $allNonCreditCourse, 'empty' => '-Select Course-', 'label' => "Course <span class='ash'>*</span>"));?>
	</div>
	<div class="col-sm-3">
		<label class="col-sm-5 control-label no-padding-right">Satisfactory</label>
		<?php echo $this->Form->radio('satisfactory', array('Yes' => 'Yes&nbsp;&nbsp;', 'No' => 'No'),array('legend' => false,'value' => 'Yes'));?>		
	</div>
	<div class="col-sm2">
	<input type="hidden" name="studentId" value="<?php echo $studentId;?>" size='1'/>
	<?php echo $this->Form->end('Submit',array('class'=>'col-sm-1','style'=>'margin-top:150px;')); ?>
	</div>
</div>
<div style="clear:both;"></div>

<?php if($StudentNonCreditCourse){?>
<table border="1" style="margin:5px;" class="display tblOddEven">
	<tr>
		<th>S.No.</th>
		<th>Course Name</th>
		<th>Joined On</th>
		<th>Satisfactory</th>
		<th>Created By</th>
		<th>Created On</th>
		<th>Action</th>
	</tr>
	<?php $i=1; foreach ($StudentNonCreditCourse as $StuNcc):?>
	<tr>
		<td><?php echo $i;?></td>
		<td><?php echo h($StuNcc['NonCreditCourse']['non_credit_course_name']); ?></td>
		<td><?php echo date( "d-M-Y", strtotime(h($StuNcc['StudentNcc']['joined_on'])) ); ?></td>
		<td><?php echo h($StuNcc['StudentNcc']['satisfactory']); ?></td>
		<td><?php echo h($StuNcc['User']['username']); ?></td>
		<td><?php echo date( "d-M-Y h:i:s", strtotime(h($StuNcc['StudentNcc']['created'])) ); ?></td>
		<td><?php echo $this->Form->postLink("<span class='fa fa-times fa-lg red'></span>", array("controller"=>"Students",'action' => 'deleteNcc', $StuNcc['StudentNcc']['id'],$studentId), array('confirm' => __('Are you sure you want to delete # %s?', $StuNcc['StudentNcc']['id']),'escape' => false, 'title'=>'Delete')); ?></td>
	</tr>
	<?php $i++; endforeach; ?>		
</table>
<?php }?>

<script>
leftMenuSelection('Students/regNoSearch');
</script>  

<span class='breadcrumb1'>
<span class='navbar-brand'><small>STUDENTS <i class='ace-icon fa fa-angle-double-right'></i></small></span>
<?php echo $this->Html->link("<span class='navbar-brand'><small> LIST <i class='ace-icon fa fa-angle-double-right'></i></small></span>", array("controller"=>"students",'action' => 'student_search'),array('data-placement'=>'left','escape' => false)); ?>
<?php echo $this->Html->link("<span class='navbar-brand'><small> NON CREDIT COURSES ( $studentId ) <i class='ace-icon fa fa-angle-double-right'></i></small></span>", array("controller"=>"Students",'action' => 'ncc',$studentId),array('data-placement'=>'left','escape' => false)); ?>
</span>