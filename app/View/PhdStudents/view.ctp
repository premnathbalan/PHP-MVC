
<?php echo $this->element('phd_student_view',array("regNo"=>$regNo)); 
//pr($phdStudent);
?>	

<div class="phdstudents view userFrm">
<dl class="bgFrame1">
	<dl>
		<dt style='height:330px;width:200px;padding:50px 20px;'>
			<?php echo $this->Html->image(h("phd_students/".$phdStudent['PhdStudent']['picture']), ['alt' => h($phdStudent['PhdStudent']['picture']),'style'=>'width:150px;height:175px;border-radius:10px;']); // ?>
		</dt>
		<!--<dd>
			
		</dd>-->
		<dt><?php echo __('Name'); ?></dt>
		<dd>
			<?php echo h($phdStudent['PhdStudent']['name']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Birth Date'); ?></dt>
		<dd>
			<?php echo h($phdStudent['PhdStudent']['birth_date']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Gender'); ?></dt>
		<dd>
			<?php echo h($phdStudent['PhdStudent']['gender']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Father Name'); ?></dt>
		<dd>
			<?php echo h($phdStudent['PhdStudent']['father_name']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Address'); ?></dt>
		<dd>
			<?php echo h($phdStudent['PhdStudent']['address']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Mobile Number'); ?></dt>
		<dd>
			<?php echo h($phdStudent['PhdStudent']['mobile_number']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Email'); ?></dt>
		<dd>
			<?php echo h($phdStudent['PhdStudent']['email']); ?>
			&nbsp;
		</dd>
		<!--<dt><?php echo __('Faculty'); ?></dt>
		<dd>
			<?php echo $this->Html->link($phdStudent['Faculty']['faculty_name'], array('controller' => 'faculties', 'action' => 'view', $phdStudent['Faculty']['id'])); ?>
			&nbsp;
		</dd>-->
		<dt><?php echo __('Title'); ?></dt>
		<dd>
			<?php echo $phdStudent['Title']['name']; ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Area'); ?></dt>
		<dd>
			<?php echo $phdStudent['Area']['name']; ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Supervisor'); ?></dt>
		<dd>
			<?php echo $phdStudent['Supervisor']['name']; ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Year Of Register'); ?></dt>
		<dd>
			<?php echo h($phdStudent['PhdStudent']['year_of_register']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Date Of Register'); ?></dt>
		<dd>
			<?php echo h($phdStudent['PhdStudent']['date_of_register']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Month Year'); ?></dt>
		<dd>
			<?php 
			echo $this->Html->getMonthYearFromMonthYearId($phdStudent['MonthYear']['month_id']); ?>
			&nbsp;
		</dd>
	<!--	<dt><?php echo __('Created By'); ?></dt>
		<dd>
			<?php echo h($phdStudent['PhdStudent']['created_by']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Modified By'); ?></dt>
		<dd>
			<?php echo h($phdStudent['PhdStudent']['modified_by']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Created'); ?></dt>
		<dd>
			<?php echo h($phdStudent['PhdStudent']['created']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Modified'); ?></dt>
		<dd>
			<?php echo h($phdStudent['PhdStudent']['modified']); ?>
			&nbsp;
		</dd> -->
	</dl>
</div>