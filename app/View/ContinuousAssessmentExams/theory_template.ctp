<?php
//$optionsArray = array('1' => 'Model', '2' => 'ESE');
//$attributes=array('legend'=>false, 'label'=>false, 'required' => 'required');
//echo $this->Form->radio('caetype', $optionsArray, $attributes);
?>
<div id="thPractical">
	<div id="marks" class="mark">
		<?php echo $this->Form->input('marks', array('type' => 'text', 'class' => 'js-marks', 'label' => 'Marks', 'name' => 'data[Cae][Theory]'));?>
	</div>
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