<div class="supervisors form deptFrm">

<legend><?php echo __('Edit Supervisor'); ?></legend>

<div><?php echo $this->Session->flash();?></div>

<?php echo $this->Form->create('Supervisor', array('class'=>'js-form','success_url'=>$this->Html->url(array('controller'=>'Supervisors','action'=>'index')))); ?>
	<?php
		echo $this->Form->input('id');
		echo $this->Form->input("supervisor_name", array("label"=>"Supervisor Name <span class='ash'>*</span>"));		
	?>
	<div class="col-sm-12 baminiImg">	
		<?php echo $this->Form->input("supervisor_name_tamil", array('label' => "&nbsp;&nbsp;&nbsp;Supervisor Name Tamil <span class='ash'>*</span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;",'style'=>'font-family:Bamini;height:24px;','div'=>false));
		echo $this->Html->image("bamini.png", array('type'=>'image'));
		?>
	</div>
<?php echo $this->Form->end(__('Submit')); ?>
</div>