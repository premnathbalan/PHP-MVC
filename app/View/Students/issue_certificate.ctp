<div class="students view">
	<?php echo $this->element('student_view',array("studentId"=>$studentId)); ?>	
	<?php echo $this->render('../Students/info',false); ?>
	
</div>

	<ul class="tabs">
		<li><a href='#tab1' class='current'>Mark Sheet</a></li>
		<li><a href='#tab2'>Issue TC</a></li>
		<li><a href='#tab3'>Degree Certificate</a></li>
		<li><?php echo $this->Html->link('Consolidated Mark Sheet', array("controller"=>"Students",'action'=>'consolidate_mark_sheet'),array('class' =>"",'escape' => false, 'target'=>'_blank')); ?></li>
		<li><a href='#tab5'>Provisional Certificate</a></li>
	</ul>

	<div class="pane">
		<div id="tab1">	        
	    	
	    	
	    	<select><option value="1">Semester 1</option></select>
	    	<?php echo $this->Html->link('<i class="fa fa-print fa-lg"></i> '. 'View', array("controller"=>"Students",'action'=>'mark_sheet'),array('class' =>"btn",'escape' => false, 'title'=>'Print', 'target'=>'_blank')); ?>
			
			
	    </div>
	    <div id="tab2" style="display:none;">	        

<div class="searchFrm" style="width:87%;">
<?php echo $this->Form->create('Student');?>
	<?php echo $this->Form->input('batch_id', array( 'empty' => __(" - Batch - "),'required'=>'', 'class' => 'js-batch','style'=>'width:100px;')); ?>
	<?php echo $this->Form->input('academic_id', array('type' => 'select', 'empty' => __("----- Select Academic-----"),'required'=>'',  'class' => 'js-academic')); ?>
	<div id="programs" class="program"><?php echo $this->Form->input('program_id', array('type' => 'select', 'empty' => __("----- Select Program-----"),'required'=>'', 'class' => 'js-programs')); ?></div>
	<?php echo $this->Form->button('<i class="ace-icon fa fa-check bigger-110"></i>'.__('Submit'),array('type'=>'submit','name'=>'submit','value'=>'submit','class'=>'btn'));?>
	<?php echo $this->Form->button('<i class="ace-icon fa fa-undo bigger-110"></i>'.__('Reset'),array('type'=>'reset','name'=>'reset','value'=>'reset','class'=>'btn'));?>
	
<?php echo $this->Form->end(); ?>
</div>
	        
	        <?php echo $this->Html->link('<i class="fa fa-print fa-lg"></i> '. 'Print', array("controller"=>"Students",'action'=>'add'),array('class' =>"addBtn btn",'escape' => false, 'title'=>'Print')); ?>	           
	    </div>
	    <div id="tab3" style="display:none;">
	        <?php echo $this->Html->link('<i class="fa fa-print fa-lg"></i> '. 'Print', array("controller"=>"Students",'action'=>'add'),array('class' =>"addBtn btn",'escape' => false, 'title'=>'Print')); ?>
	        
	        <h2>Degree Certificate</h2>
	        <p>Degree Certificate Degree Certificate Degree Certificate Degree Certificate</p>
	    </div>
	    <div id="tab4" style="display:none;">
	        <?php echo $this->Html->link('<i class="fa fa-print fa-lg"></i> '. 'Print', array("controller"=>"Students",'action'=>'add'),array('class' =>"addBtn btn",'escape' => false, 'title'=>'Print')); ?>
	        
	       	<div style="margin-top:20px;">
	    	<select><option value="1">Semester 1</option></select>
	    	<?php echo $this->Html->link('<i class="fa fa-print fa-lg"></i> '. 'View', array("controller"=>"Students",'action'=>'consolidate_mark_sheet'),array('class' =>"btn",'escape' => false, 'title'=>'Print', 'target'=>'_blank')); ?>
			</div>
			
	    </div>
	    <div id="tab5" style="display:none;">
	        <?php echo $this->Html->link('<i class="fa fa-print fa-lg"></i> '. 'Print', array("controller"=>"Students",'action'=>'add'),array('class' =>"addBtn btn",'escape' => false, 'title'=>'Print')); ?>
	        
	        <h2>Provisional Certificate</h2>
	        <p>Provisional Certificate Provisional Certificate Provisional Certificate Provisional Certificate</p>
	    </div>
	</div>


<style>
  ul.tabs { display: table;list-style-type: none;margin: 0; padding: 0; }
  ul.tabs>li {float: left; padding: 10px; background: rgba(0, 0, 0, 0) linear-gradient(220deg, transparent 10px, #ad1c1c 10px) repeat scroll 0 0; box-shadow: -4px 0 0 rgba(0, 0, 0, 0.2); color: #fff;  }
  ul.tabs>li a{color:#fff;font-weight:bold;}
  ul.tabs>li:hover {background-color: lightgray;}
  .tabs .current {color: yellow !important;}
  ul { overflow: auto; }
</style>

<script>
leftMenuSelection('Students/regNoSearch');
</script>    
<span class='breadcrumb1'>
<span class='navbar-brand'><small>STUDENTS <i class='ace-icon fa fa-angle-double-right'></i></small></span>
<?php echo $this->Html->link("<span class='navbar-brand'><small> LIST <i class='ace-icon fa fa-angle-double-right'></i></small></span>", array("controller"=>"students",'action' => 'studentSearch'),array('data-placement'=>'left','escape' => false)); ?>
<?php echo $this->Html->link("<span class='navbar-brand'><small> Issue Certificate ( $studentId ) <i class='ace-icon fa fa-angle-double-right'></i></small></span>", array("controller"=>"Students",'action' => 'issue_certificate',$studentId),array('data-placement'=>'left','escape' => false)); ?>
</span>