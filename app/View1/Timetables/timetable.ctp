<div class="students form">
<?php echo $this->Form->create('Timetable');?>
	<div class="bgFrame1">
		<div class="col-sm-12">
			<div class="col-lg-4">
				<?php echo $this->Form->input("start_date", array('label' => "Start Date <span class='ash'>*</span>", 'type' => 'text', 'class' => 'fl tal vat w300p', 'error' => false , 'id' => 'select_date1'));?>
			</div>
			<div class="col-lg-4">
				<?php echo $this->Form->input("end_date", array('label' => "End Date <span class='ash'>*</span>", 'type' => 'text', 'class' => 'fl tal vat w300p', 'error' => false , 'id' => 'select_date2'));?>
			</div>
			<div class="col-lg-4">
				<?php echo $this->Form->input('sundays', array('type'=>'checkbox', 'name'=>'sunday', 'label'=>false)); ?>All Sunday Holiday
			</div>
		</div>
		<div class="col-sm-12" style="border:1px;" >
			<div class="col-lg-4">
				<?php echo "Days Diff". $this->Form->input('days_diff', array('type'=>'textbox', 'label'=>false, 'value'=>'', 'style'=>'margin-left:5px;width:50px;', 'name'=>'days_diff')); ?>
			</div>
			<div class="col-lg-8">
				<?php echo "Exceptional Dates". $this->Form->input('exceptional', array('type'=>'textbox', 'label'=>false, 'value'=>'', 'style'=>'margin-left:5px;width:250px;', 'name'=>'exceptional')); ?>
			</div>
		</div>
		<div class="col-sm-12">
			<div class="col-lg-12">
				<?php echo $this->Form->input('month_year_id', array('type' => 'select', 'options'=>$monthyears, 'empty' => __("----- Select ExamMonthYear-----"), 'label' => "<span class='ash'>*</span> ExamMonthYear")); ?>
			</div>
			<div class="col-lg-3">		
				<?php echo $this->Form->button('<i class="ace-icon fa fa-check bigger-110"></i>'.__('Generate'),array('type'=>'button','name'=>'button','value'=>'submit','class'=>'btn',"onclick"=>"generateTimetable();"));?>
			</div>
		</div>
	</div>
<?php echo $this->Form->end();?>
</div>
<div id="results">***
</div>

<?php echo $this->Html->script('common'); ?>
<?php echo $this->Html->script('common-front'); ?>