<div class="signatures form deptFrm">
	<?php echo $this->Session->flash();?>
	<legend><?php echo __('Signature Add'); ?></legend>

	<div id="signatures">
		<?php 
		echo $this->Form->create('Signature', array('type' => 'file'), array('class'=>'js-form','success_url'=>$this->Html->url(array('controller'=>'Signatures','action'=>'index')))); 
		?>		
		
	<?php echo $this->Form->input('name', array('label' => "Name <span class='ash'>*</span>", 'type' => 'text', 'placeholder' => 'Ex. K.V.Narayanan' )); ?>
		
	<div class="col-sm-12 baminiImg">	
		<?php echo $this->Form->input("tamil_name", array('label' => "&nbsp;&nbsp;&nbsp;Tamil Name <span class='ash'>*</span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;",'placeholder' => 'கே.வி.நாராயணன்' ,'style'=>'font-family:Bamini;height:24px;','div'=>false));
		echo $this->Html->image("bamini.png", array('type'=>'image'));
		?>
	</div>	
	
	<?php echo $this->Form->input('role', array('label' => "Role <span class='ash'>*</span>", 'type' => 'text', 'placeholder' => 'Ex. COE' )); ?>
		
	<div class="col-sm-12 baminiImg">	
		<?php echo $this->Form->input("role_tamil", array('label' => "&nbsp;&nbsp;&nbsp;Role in Tamil <span class='ash'>*</span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;",'placeholder' => 'தணிக்கையாளர்' ,'style'=>'font-family:Bamini;height:24px;','div'=>false));
		echo $this->Html->image("bamini.png", array('type'=>'image'));
		?>
	</div>
	
	<div class="sigFrm">
			<?php echo $this->Form->input('signature', array('type' => 'file', 'label' => "Upload Signature Image <span class='ash'>*</span>",'float'=>'left'));?> 
		</div>
		<?php echo $this->Form->end(__('Submit')); ?>	
	</div>

</div>
<?php echo $this->Html->script('common-front');?>