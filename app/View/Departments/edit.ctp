<div class="departments form deptFrm">

<legend><?php echo __('Edit Department'); ?></legend>

<div><?php echo $this->Session->flash();?></div>

<?php echo $this->Form->create('Department', array('class'=>'js-form','success_url'=>$this->Html->url(array('controller'=>'Departments','action'=>'index')))); ?>
	<?php
		echo $this->Form->input('id');
		echo $this->Form->input("department_name", array("label"=>"Department Name <span class='ash'>*</span>"));		
	?>
	<div class="col-sm-12 baminiImg">	
		<?php echo $this->Form->input("department_name_tamil", array('label' => "&nbsp;&nbsp;&nbsp;Department Name Tamil <span class='ash'>*</span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;",'style'=>'font-family:Bamini;height:24px;','div'=>false));
		echo $this->Html->image("bamini.png", array('type'=>'image'));
		?>
	</div>
<?php echo $this->Form->end(__('Submit')); ?>
</div>