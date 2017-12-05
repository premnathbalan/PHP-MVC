<div class="signatures form deptFrm">
	<?php echo $this->Session->flash();?>
	<legend><?php echo __('Signature Edit'); ?></legend>
		
	<div id="signatures">
		<?php 
		echo $this->Form->create('Signature', array('type' => 'file'), array('class'=>'js-form','success_url'=>$this->Html->url(array('controller'=>'Signatures','action'=>'index')))); 
		echo $this->Form->input('id');
		?>
		<?php echo $this->Form->input('name', array('label' => "Name <span class='ash'>*</span>", 'type' => 'text', 'placeholder' => 'Ex. K.V.Narayanan' )); ?>
		
		<div class="col-sm-12 baminiImg">	
		<?php echo $this->Form->input("tamil_name", array('label' => "&nbsp;&nbsp;&nbsp;Tamil Name <span class='ash'>*</span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;",'placeholder' => 'à®•à¯‡.à®µà®¿.à®¨à®¾à®°à®¾à®¯à®£à®©à¯�' ,'style'=>'font-family:Bamini;height:24px;','div'=>false));
		echo $this->Html->image("bamini.png", array('type'=>'image'));
		?>
	    </div>	
	    
		<?php echo $this->Form->input('role', array('label' => "Role <span class='ash'>*</span>", 'type' => 'text', 'placeholder' => 'Ex. COE' )); ?>
		
		<div class="col-sm-12 baminiImg">	
		<?php echo $this->Form->input("role_tamil", array('label' => "&nbsp;&nbsp;&nbsp;Role in Tamil <span class='ash'>*</span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;",'placeholder' => 'à®¤à®£à®¿à®•à¯�à®•à¯ˆà®¯à®¾à®³à®°à¯�' ,'style'=>'font-family:Bamini;height:24px;','div'=>false));
		echo $this->Html->image("bamini.png", array('type'=>'image'));?>
	    </div>
		
		<div class="sigFrm">
			<?php echo $this->Form->input('signature', array('type' => 'file', 'label' => "Upload Signature Image <span class='ash'>*</span>",'float'=>'left','required'=>''));?> 
		</div>	
			<?php echo $this->Form->end(__('Submit')); ?>	
		</div>
	<div style="text-align:center;"><?php echo $this->Html->image(h("certificate_signature/".$this->data['Signature']['signature']), ['alt' => $this->data['Signature']['signature'],'style'=>'width:100px;height:100px;']);?></div>
</div>
<?php echo $this->Html->script('common-front');?>