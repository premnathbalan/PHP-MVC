<div class="batches form deptFrm">
	<?php echo $this->Session->flash();?>
	<legend><?php echo __('Batch Add'); ?></legend>
	<?php
		echo $this->Form->create('Batch', array('class'=>'js-form','success_url'=>$this->Html->url(array('controller'=>'Batches','action'=>'index'))));	
		echo $this->Form->input('batch_from',array("label"=>"Batch From <span class='ash'>*</span>",'type' => 'select', 'options' => $years, 'empty' => ' - Batch From -'));
		echo $this->Form->input('batch_to', array("label"=>"Batch To <span class='ash'>*</span>",'type' => 'select', 'options' => $years, 'empty' => ' - Batch To -'));		
		echo $this->Form->input("consolidated_pub_date", array("label"=>"Publication Date <span class='ash'>*</span>",'type' => 'text', 'class' => 'fl tal vat w300p', 'error' => false , 'id' => 'select_date'));
        echo $this->Form->input('academic', array("label"=>"Month",'type' => 'select', 'options' => array("JUN" => "JUN", "JAN" => "JAN"),'required' =>''));        
	?>
	<?php echo $this->Form->end(__('Submit')); ?>
<div>	
<script>
for (i = new Date().getFullYear(); i > 1900; i--) {
    $('#batch_from').append($('<option />').val(i).html(i));
}

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