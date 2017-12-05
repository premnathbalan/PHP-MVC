<div class="bgFrame1" style="margin-top:5px;">
<?php
$optionsArray = array('1' => 'Single Assessment', '2' => 'Both Internal and External', '3' => '3 Reviews and 1 Viva');
$attributes=array('legend'=>false, 'label'=>false, 'required' => 'required', 'class' => 'cae_type');
echo $this->Form->radio('caetype', $optionsArray, $attributes);
?>
<div id="choice1" style="display:none;">
	<div id="marks" class="mark">
		<?php echo $this->Form->input('singleMarks', array('type' => 'text', 'class' => 'js-marks', 'label' => 'Marks', 'name' => 'data[CAE][CAE/ESE]'));?>
	</div>
</div>
<div id="choice2" style="display:none;">
	<div id="marks" class="mark">
		<div class="col-sm-3">
		<?php echo $this->Form->input('caeMarks', array('type' => 'text', 'class' => 'js-marks', 'label' => 'CAE', 'name' => 'data[CAE][CAE]'));?>
		</div>
		<div class="col-sm-3">
		<?php echo $this->Form->input('eseMarks', array('type' => 'text', 'class' => 'js-marks', 'label' => 'ESE', 'name' => 'data[CAE][ESE]'));?>
		</div>
	</div>
</div>
<div id="choice3" style="display:none;">
	<div id="marks" class="mark">
		<div class="col-sm-3">
		<?php echo $this->Form->input('R1Marks', array('type' => 'text', 'class' => 'js-marks', 'label' => 'Review 1', 'name' => 'data[CAE][Review 1]'));?>
		</div>
		<div class="col-sm-3">
		<?php echo $this->Form->input('R2Marks', array('type' => 'text', 'class' => 'js-marks', 'label' => 'Review 2', 'name' => 'data[CAE][Review 2]'));?>
		</div>
		<div class="col-sm-3">
		<?php echo $this->Form->input('R3Marks', array('type' => 'text', 'class' => 'js-marks', 'label' => 'Review 3', 'name' => 'data[CAE][Review 3]'));?>
		</div>
		<div class="col-sm-3">
		<?php echo $this->Form->input('VivaMarks', array('type' => 'text', 'class' => 'js-marks', 'label' => 'Viva', 'name' => 'data[CAE][Viva]'));?>
		</div>
	</div>
</div>
<?php 
echo $this->Form->input('maxMarks', array('type' => 'text', 'class' => 'js-project-max-marks', 'label' => 'Max Marks', 'name' => 'data[maxMarks]', 
						'readonly', 'style'=>'width:40px;'));?>
</div>
<style type="text/css">
    input[type=radio] {
       margin:3px;
       width:23px;
    }
    .locRad {
         margin:3px 0px 0px 3px; 
         float:none;
    }
</style>
<?php echo $this->Html->script('common'); ?>