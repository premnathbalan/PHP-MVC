<div class="monthYears form deptFrm">

	<legend><?php echo __('Month Year Edit'); ?></legend>
	
	<div><?php echo $this->Session->flash();?></div>
	
	<?php echo $this->Form->create('MonthYear', array('class'=>'js-form','success_url'=>$this->Html->url(array('controller'=>'MonthYears','action'=>'index')))); ?>
	
	<?php
		echo $this->Form->input('id');
		echo $this->Form->input('month_id', array("label"=>"Month <span class='ash'>*</span>",'type' => 'select', 'options' => $months, 'empty' => ' - Select Month - '));
		echo $this->Form->input('year', array("label"=>"Year <span class='ash'>*</span>",'maxLength'=>'4'));
		echo $this->Form->input("publishing_date", array('label' => "Publication Date  <span class='ash'>*</span>", 'type' => 'text','class' => 'fl tal vat w300p', 'error' => false , 'id' => 'select_date'));
		echo $this->Form->end(__('Submit')); 
	?>

</div>
<script>
 $("#select_date").datepicker({
 	changeMonth: true,
    changeYear: true,
       dateFormat: 'dd-M-yy',
       onSelect: function(dateText, inst){
             $('#select_date').val(dateText);
             $("#datepicker").datepicker("destroy");
      }
 });
</script>