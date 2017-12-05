<?php
echo $this->Form->input('academic_id', array('type' => 'select', 'empty' => __("----- Select Academic-----"), 'label' => 'Academic', 'class' => $class_name));
?>
<?php echo $this->Html->script('common'); ?>
<script>
$(".js-academic, .js-cae-academic").change(function(){
	alert('test'); 
});
</script>