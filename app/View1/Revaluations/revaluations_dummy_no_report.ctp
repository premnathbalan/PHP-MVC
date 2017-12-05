<div class="students form">
	<?php echo $this->Form->create('Revaluation', array('type' => 'file')); ?>
	
	<div class="bgFrame1">
	 <div class=" col-sm-12">
		<div class=" col-sm-5">
	   		<?php echo $this->Form->input('month_year_id', array('label' => 'MonthYear', 'options'=>$monthyears, 'empty' => __("----- Select Month Year-----"), 'required'=>'required')); ?>
	   	</div>
	   	
	   	<div class=" col-sm-3">
	   		<?php echo $this->Form->button('<i class="ace-icon fa fa-check bigger-110"></i>'.__('SEARCH'),array('type'=>'submit', 'name'=>'Submit','value'=>'Submit','class'=>'btn'));?>
	   		<?php echo $this->Form->button('<i class="ace-icon fa fa-file-pdf-o bigger-110"></i>'.__('PDF'),array('type'=>'submit','name'=>'Submit','value'=>'PDF','class'=>'btn js-exam-att-submit'));?>
	   		<?php echo $this->Form->end(); ?>
	   	</div>
	   </div>	
   </div>
   
   <?php if($results){?>
	<table border='1' class='display tblOddEven' style='margin:5px;'>
		<tr>
			<th>S.No.</th>
			<th>SUBJECT CODE</th>
			<th>COURSE NAME</th>
			<th>DUMMY NO.</th>
			<th>BOARD</th>
			<th>SIGNATURE</th>
		</tr>
		<?php $i=1;$CCSeq = 1; for($p=0;$p<count($results);$p++){?>
		<tr>
			<td><?php echo $CCSeq;?></td>
			<td><?php echo $results[$p]['CourseMapping']['Course']['course_code'];?></td>  
			<td><?php echo $results[$p]['CourseMapping']['Course']['course_name'];?></td>
			<td><?php echo $results[$p]['EndSemesterExam']['dummy_number'];?></td>
			<td><?php echo $results[$p]['CourseMapping']['Course']['board'];?></td>
			<td></td>
		</tr>
		<?php $i++;
		if((isset($results[$p+1]['CourseMapping']['Course']['course_code'])) && ($results[$p]['CourseMapping']['Course']['course_code'] == $results[$p+1]['CourseMapping']['Course']['course_code'])){
			$CCSeq++;
		}else{
			$CCSeq = 1;
		}
		if((isset($results[$p+1]['Revaluation']['board']) && ($results[$p]['Revaluation']['board'] != $results[$p+1]['Revaluation']['board']))){?>
		<tr>
			<td colspan="8"></td>
		</tr>
		<?php $i=1;}}?>
   </table>
   <?php }?>
</div>
   
<script>leftMenuSelection('Revaluations/revaluationsDummyNoReport');</script>

<span class='breadcrumb1'>
<span class='navbar-brand'><small>REVALUATION <i class='ace-icon fa fa-angle-double-right'></i></small></span>
<?php echo $this->Html->link("<span class='navbar-brand'><small> REVALUATION DUMMY NUMBER REPORT <i class='ace-icon fa fa-angle-double-right'></i></small></span>", array("controller"=>"Revaluations",'action' => 'revaluationsDummyNoReport'),array('data-placement'=>'left','escape' => false)); ?>
</span>