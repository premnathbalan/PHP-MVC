<div class='col-lg-12' style='float:left;width:100%;'>
	<?php //echo $this->Html->link('<i class="ace-icon fa fa-file-excel-o"></i>'.'Excel',array("controller"=>"Revaluations",'action'=>'applied',$exam_month_year_id,$batch_id,'Excel'),array('type'=>'submit','name'=>'Excel','value'=>'Excel','class'=>'btn','escape' => false,'style'=>'float:right;'));?>
<?php echo $this->Html->link('<i class="ace-icon fa fa-print fa-lg"></i>'.'PRINT',array("controller"=>"Revaluations",'action'=>'applied',$exam_month_year_id,$batch_id,'PRINT'),array('type'=>'submit','name'=>'PRINT','value'=>'PRINT','class'=>'btn','escape' => false,'style'=>'float:right;'));?>
	<div style='clear:both;'></div>
</div>

<table style="width:40%;align:center;border-color:#000;font-weight:14px;margin-top:10px;" border="1" class="display tblOddEven">
	<tr>
		<td style="align:right;">Total applied</td><td align="center"><?php echo $results['total']; ?></td>
	</tr>
	<tr>
		<td style="align:right;">Applied for improvement</td><td align="center"><?php echo $results['brPassCnt']; ?></td>
	</tr>
	<tr>
		<td style="align:right;">Applied for Pass</td><td align="center"><?php echo $results['brFailCnt'] ;?></td>
	</tr>
</table>

<table style="width:40%;align:center;border-color:#000;font-weight:14px;margin-top:10px;" border="1" class="display tblOddEven">
	<tr>
		<th style="align:right;" colspan="2">Applied for Improvement</th>
	</tr>
	<tr>
		<td style="align:right;">Total applied</td><td align="center"><?php echo $results['brPassCnt']; ?></td>
	</tr>
	<tr>
		<td style="align:right;">Improvement after Revaluation</td><td align="center"><?php echo $results['impGreater']; ?></td>
	</tr>
	<tr>
		<td style="align:right;">No change in improvement</td><td align="center"><?php echo $results['impNoChange']; ?></td>
	</tr>
	<tr>
		<td style="align:right;">Decrement in improvement</td><td align="center"><?php echo $results['impLesser']; ?></td>
	</tr>
	<tr>
		<td style="align:right;">Failed in improvement</td><td align="center"><?php echo $results['impFail']; ?></td>
	</tr>
</table>

<table style="width:40%;align:center;border-color:#000;font-weight:14px;margin-top:10px;" border="1" class="display tblOddEven">
	<tr>
		<th style="align:right;" colspan="2">Applied for Pass</th>
	</tr>
	<tr>
		<td style="align:right;">Total applied</td><td align="center"><?php echo $results['brFailCnt']; ?></td>
	</tr>
	<tr>
		<td style="align:right;">Passed After Revaluation</td><td align="center"><?php echo $results['passedAfterRevaluation']; ?></td>
	</tr>
	<tr>
		<td style="align:right;">No change After Revaluation</td><td align="center"><?php echo $results['failedAfterRevaluation']; ?></td>
	</tr>
</table>
<?php echo $this->Html->script('common'); ?>

<script>leftMenuSelection('Revaluations/Report');</script>

<span class='breadcrumb1'>
<span class='navbar-brand'><small>REVALUATION <i class='ace-icon fa fa-angle-double-right'></i></small></span>
<?php echo $this->Html->link("<span class='navbar-brand'><small>Report <i class='ace-icon fa fa-angle-double-right'></i></small></span>", array("controller"=>"RevaluationExams",'action' => 'report'),array('data-placement'=>'left','escape' => false)); ?>
</span>