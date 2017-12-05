<div class="withhelds form deptFrm">
<?php echo $this->Form->create('Withheld'); ?>
	<legend><?php echo __('Add With-held'); ?></legend>
	<?php echo $this->Form->input('withheld_type');	?>

<?php echo $this->Form->end(__('Submit')); ?>
</div>
