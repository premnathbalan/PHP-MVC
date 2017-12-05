<div>

<?php
if ($print != 1) { 
?>
<div class="searchFrm bgFrame1" style="margin-top:7px;">
		<div class="col-sm-12">
			<div class="col-lg-12">	
			<?php 
			echo $this->Html->link(' <i class="ace-icon fa fa-undo bigger-110"></i> '.'&nbsp;Print&nbsp;',array("controller"=>"EndSemesterExams",'action'=>'getReport', $batch_id, $month_year_id, 1),array('type'=>'submit','name'=>'reset','value'=>'reset','class'=>'btn js-report-batchwise-print','escape' => false));?>		
			</div>
		</div>
	</div>	
</div>
<?php
}
else {
echo $this->element('print_head_a4');
?>
<div style="clear:both;"></div>
<?php
echo "Batch : ".$reportResult[0]['batch'];
}
?>
<div style="clear:both;"></div>
<table border="1" style="margin:5px;" class="display tblOddEven">
	<tr>
		<!--<th>Batch</th>-->
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
	if ($reportResult == 0) {
		echo "No Theory Papers!!!";
	}
	else {
		foreach ($reportResult as $key => $result) { 
		?>
		<tr>
			<!--<td><?php echo $result['batch'];?></td>-->
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
		}
	}
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