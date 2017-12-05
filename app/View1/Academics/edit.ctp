<div class="academics form deptFrm">
	<?php echo $this->Session->flash();?>
	<legend><?php echo __('Program Edit'); ?></legend>
	<?php
		echo $this->Form->create('Academic', array('class'=>'js-form','success_url'=>$this->Html->url(array('controller'=>'Academics','action'=>'index'))));
		echo $this->Form->input('id');
		echo $this->Form->input('academic_name',array("label"=>"Program Name <span class='ash'>*</span>"));
		echo $this->Form->input('academic_type',array("label"=>"Program type <span class='ash'>*</span>",'type' => 'select', 'options' => array("UG" => "UG", "PG" => "PG")));
		echo $this->Form->input('short_code',array("label"=>"Short Code <span class='ash'>*</span>"));
	?>	
	<div class="col-sm-12 baminiImg">	
		<?php echo $this->Form->input("academic_name_tamil", array('label' => "&nbsp;&nbsp;&nbsp;Academic Name in Tamil <span class='ash'>*</span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;",'style'=>'font-family:Bamini;height:24px;','div'=>false));
		echo $this->Html->image("bamini.png", array('type'=>'image'));
		?>
	</div>	
	<?php		
		echo $this->Form->end(__('Submit'));	
	?>
</div>