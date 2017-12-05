<?php 
if($result){
$from = (int)$result['DummyNumber']['end_range'];
$to = (int)$result['DummyNumber']['start_range'];
$total = $from-$to+1;
echo $this->Form->input('dummy_number_id', array('type'=> 'hidden', 'label'=>false, 'default'=>$dummy_number_id, 'name' => 'data[DummyFinalMark][dummy_number_id]'));
echo $this->Form->input('max_qp_mark', array('type'=> 'hidden', 'label'=>false, 'default'=>$result['DummyRangeAllocation'][0]['Timetable']['CourseMapping']['Course']['max_ese_qp_mark'], 'name' => 'data[DummyFinalMark][max_qp_mark]'));
echo $this->Form->input('max_ese_mark', array('type'=> 'hidden', 'label'=>false, 'default'=>$result['DummyRangeAllocation'][0]['Timetable']['CourseMapping']['Course']['max_ese_mark'], 'name' => 'data[DummyFinalMark][max_ese_mark]'));
?>

<table border="1" style="margin:5px;" class="display tblOddEven">
	<tr>
		<th>Course</th>
		<th>Course Code</th>
		<th>Dummy Number Range</th>
		<th>No. of Students Appeared</th>
		<th>Max QP Mark</th>
		<th>Max ESE Mark</th>
	</tr>
	<tr>
		<td><?php echo $result['DummyRangeAllocation'][0]['Timetable']['CourseMapping']['Course']['course_name']; ?></td>
		<td align='center'><?php echo $result['DummyRangeAllocation'][0]['Timetable']['CourseMapping']['Course']['course_code']; ?></td>
		<td align='center'><?php echo $result['DummyNumber']['start_range']." to ".$result['DummyNumber']['end_range'];?></td>
		<td align='center'><?php echo $total; ?></td>
		<td align='center'><?php echo $result['DummyRangeAllocation'][0]['Timetable']['CourseMapping']['Course']['max_ese_qp_mark']; ?></td>
		<td align='center'><span class='ovelShapBg2'><b><?php echo $result['DummyRangeAllocation'][0]['Timetable']['CourseMapping']['Course']['max_ese_mark']; ?><b></span></td>
	</tr>
</table>
<div class="searchFrm bgFrame1 col-sm-12" style="margin-top:7px;height:60px;">
	<div class="col-lg-3">
	</div>
	<div class="col-lg-3">
		<?php echo $this->Form->input('from', array('type'=>'text', 'label' => "<span class='ash'>*</span> From",'class'=>'js-dmod-from', 'required' => 'required', 'style'=>'width:40px;margin-top:8px;border-color:#000;text-indent:4px;')); ?>
	</div>
	<div class="col-lg-3">
		<?php echo $this->Form->input('to', array('type'=>'text', 'label' => "<span class='ash'>*</span> To",'class'=>'js-dmod-from', 'required' => 'required', 'style'=>'width:40px;margin-top:8px;border-color:#000;text-indent:4px;')); ?>
	</div>
	<div class="col-lg-3">
	<?php echo $this->Form->button('<i class="ace-icon fa fa-check bigger-110"></i>'.__('&nbsp;Get&nbsp;&nbsp;'),array('type'=>'button','name'=>'submit','value'=>'get','class'=>'btn js-dummy-moderation')); ?>
	</div>
</div>

<div id="dummyMarksDisplay"></div>
<?php
}
?>

<?php 
echo $this->Html->script('common');
?>

<script>leftMenuSelection('Dummy Marks Moderation');</script>

<span class='breadcrumb1'>
<span class='navbar-brand'><small>EXAMINATION <i class='ace-icon fa fa-angle-double-right'></i></small></span>
<?php echo $this->Html->link("<span class='navbar-brand'><small>Dummy Marks Moderation <i class='ace-icon fa fa-angle-double-right'></i></small></span>", array("controller"=>"DummyMarks",'action' => 'moderation'),array('data-placement'=>'left','escape' => false)); ?>
</span>