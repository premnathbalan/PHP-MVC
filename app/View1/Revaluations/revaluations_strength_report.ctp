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
			<th>BOARD</th>
			<th>TOTAL</th>
		</tr>
		<?php $i =1; for($p=0;$p<count($results);$p++){	?>
		<tr>			
			<td><?php echo $i;?></td>
			<td><?php echo $results[$p]['CourseMapping']['Course']['board'];?></td> 
			<td><?php echo $results[$p][0]['cntboard'];?></td>
		</tr>
		
		<?php $i++;}?>
   </table>
   <?php }?>
</div>
   
<script>leftMenuSelection('Revaluations/revaluationsStrengthReport');</script>

<span class='breadcrumb1'>
<span class='navbar-brand'><small>REVALUATION <i class='ace-icon fa fa-angle-double-right'></i></small></span>
<?php echo $this->Html->link("<span class='navbar-brand'><small> REVALUATION STRENGTH REPORT <i class='ace-icon fa fa-angle-double-right'></i></small></span>", array("controller"=>"Revaluations",'action' => 'revaluationsStrengthReport'),array('data-placement'=>'left','escape' => false)); ?>
</span>