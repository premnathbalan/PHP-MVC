<div class="students view">
<?php echo $this->element('student_view',array("studentId"=>$studentId)); ?>
<?php echo $this->render('../Students/info',false); ?>
</div>

<?php if($getWithheldStuRes){?>
<table border="1" style="margin:5px;" class="display tblOddEven">
	<tr>
		<th>S.No.</th>
		<th>Exam Month</th>
		<th>Withheld Type</th>
		<th>Date</th>
		<th>Remarks</th>
		<th>Crated On</th>
		<th>Status</th>
		<th>Delete</th>
	</tr>
	<?php $i=1; foreach ($getWithheldStuRes as $result):?>
	<tr>
		<td><?php echo $i;?></td>
		<td><?php echo h($result['MonthYear']['Month']['month_name'])." - ".h($result['MonthYear']['year']);?></td>
		<td><?php echo h($result['Withheld']['withheld_type']); ?></td>
		<td>
			<?php echo $this->Form->input("remarksDate".$result['StudentWithheld']['id'], array('id'=>'select_date'.$result['StudentWithheld']['id'],'required'=>'required','label' => false, 'class'=>'h30', 'size'=>10,'default'=>date( "d-M-Y", strtotime(h($result['StudentWithheld']['remarks_date']))) )); ?>
		</td>
		<td>
			<?php echo $this->Form->input("remarks".$result['StudentWithheld']['id'], array('id'=>'remarks'.$result['StudentWithheld']['id'],'type' => 'textarea','required'=>'required',"label"=>false,'class'=>'col-sm-9 h60','maxlength'=>'5000','default'=>$result['StudentWithheld']['remarks'])); ?>
			<?php $autoGenId = $result['StudentWithheld']['id']; echo $this->Form->button('<i class="ace-icon fa fa-check bigger-110"></i>'.__('Update'),array('type'=>'submit','name'=>'submit','value'=>'submit','class'=>'btn',"onclick"=>"withheldRemarks($autoGenId);"));?>
		</td>
		<td><?php echo date( "d-M-Y", strtotime(h($result['StudentWithheld']['created'])) ); ?></td>
		<td><?php if(h($result['StudentWithheld']['indicator']) == 0 ){ echo "Open";}else{ echo "Closed";} ?></td>
		<td><?php echo $this->Form->postLink("<span class='fa fa-times fa-lg red'></span>", array("controller"=>"Students",'action' => 'deleteWithheldAll', $result['StudentWithheld']['id'],$studentId), array('confirm' => __('Are you sure you want to delete # %s?'),'escape' => false, 'title'=>'Delete')); ?></td>
	</tr>
	<?php $i++; endforeach; ?>		
</table>
<?php }?>

<?php echo $this->Html->script('common-front');?>
<script>
leftMenuSelection('Students/regNoSearch');
</script>  

<span class='breadcrumb1'>
<span class='navbar-brand'><small>STUDENTS <i class='ace-icon fa fa-angle-double-right'></i></small></span>
<?php echo $this->Html->link("<span class='navbar-brand'><small> LIST <i class='ace-icon fa fa-angle-double-right'></i></small></span>", array("controller"=>"students",'action' => 'student_search'),array('data-placement'=>'left','escape' => false)); ?>
<?php echo $this->Html->link("<span class='navbar-brand'><small> WITHHELD ( $studentId ) <i class='ace-icon fa fa-angle-double-right'></i></small></span>", array("controller"=>"Students",'action' => 'withheldAll',$studentId),array('data-placement'=>'left','escape' => false)); ?>
</span>