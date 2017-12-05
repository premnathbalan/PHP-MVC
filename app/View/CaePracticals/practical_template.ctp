<?php
//$optionsArray = array('1' => 'Model', '2' => 'ESE');
//$attributes=array('legend'=>false, 'label'=>false, 'required' => 'required');
//echo $this->Form->radio('caetype', $optionsArray, $attributes);

?>
<div id="thPractical">
	<div id="marks" class="mark">
		<?php echo $this->Form->input('modelMarks', array('type' => 'text', 'class' => 'js-marks', 'label' => 'CAE', 'name' => 'data[Practical][CaePractical][CAE]'));?>
		<?php echo $this->Form->input('eseMarks', array('type' => 'text', 'class' => 'js-marks', 'label' => 'ESE', 'name' => 'data[Practical][EsePractical][ESE]'));?>
	</div>
</div>

<?php 
echo $this->Form->input('maxMarks', array('type' => 'text', 'value'=>100, 'class' => 'js-practical-max-marks', 'label' => 'Max Marks', 'name' => 'data[maxMarks]', 
						'readonly', 'style'=>'width:40px;'));?>
						
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