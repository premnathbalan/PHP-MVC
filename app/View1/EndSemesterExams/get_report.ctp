<?php
if ($print == 1) {
	echo $this->element('print_head_a4');
} else {
?>
<div style="clear:both;"></div>

<div class='col-lg-12' style='float:left;width:100%;'>
	<?php echo $this->Html->link('<i class="ace-icon fa fa-print fa-lg"></i>'.'PRINT',array("controller"=>"EndSemesterExams",'action'=>'getReport',$month_year_id,$batch_id,'PRINT'),array('type'=>'submit','name'=>'PRINT','value'=>'PRINT','class'=>'btn','escape' => false,'style'=>'float:right;'));?>
	<?php echo $this->Html->link('<i class="ace-icon fa fa-file-excel-o"></i>'.'Excel',array("controller"=>"EndSemesterExams",'action'=>'getReport',$month_year_id,$batch_id,'Excel'),array('type'=>'submit','name'=>'Excel','value'=>'Excel','class'=>'btn','escape' => false,'style'=>'float:right;'));?>
	<div style='clear:both;'></div>
</div>
<?php
}
//echo "Batch : ".$batch_period;
?>
<div style="clear:both;"></div>
<table border="1" style="margin:5px;" class="display tblOddEven">
	<tr>
		<th>Batch</th>
		<th>Program</th>
		<th>Specialisation</th>
		<th>Total Strength</th>
		<th>All Pass</th>
		<th>One Arrear</th>
		<th>Two Arrear</th>
		<th>Three Arrear</th>
		<th>More than Three Arrear</th>
		<th>Pass Percentage</th>
	</tr>
	<?php
	foreach ($reportResult as $batch_id => $results) {
	foreach ($results as $program_id => $result) {
	?>
	<tr>
		<td><?php echo $result['batch'];?></td>
		<td><?php echo $result['academic'];?></td>
		<td><?php echo $result['program'];?></td>
		<td align="center"><?php echo $result['totalStrength'];?></td>
		<td align="center"><?php echo $result['allPass'];?></td>
		<td align="center"><?php echo $result['oneArrear'];?></td>
		<td align="center"><?php echo $result['twoArrear'];?></td>
		<td align="center"><?php echo $result['threeArrear'];?></td>
		<td align="center"><?php echo $result['moreThanThreeArrear'];?></td>
		<td align="center"><?php echo $result['totalPercent'];?></td>
	</tr>
	<?php
	}}
	?>
</table>
<?php 
echo $this->Html->script('common');
?>

<script>leftMenuSelection('Batchwise');</script>

<span class='breadcrumb1'>
<span class='navbar-brand'><small>REPORTS <i class='ace-icon fa fa-angle-double-right'></i></small></span>
<?php echo $this->Html->link("<span class='navbar-brand'><small>BatchWise <i class='ace-icon fa fa-angle-double-right'></i></small></span>", array("controller"=>"DummyMarks",'action' => 'moderation'),array('data-placement'=>'left','escape' => false)); ?>
</span>
</div>