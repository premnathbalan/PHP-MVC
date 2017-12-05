<div class="phdStudents index">
	<table cellpadding="0" cellspacing="0" border="0" class="display" id="example">
	<thead>
	<tr>
			<th>id</th>
			<th>Status</th>
			<th>name</th>
			<th>birth_date</th>
			<th>gender</th>
			<th>father_name</th>
			<th>address</th>
			<th>mobile_number</th>
			<th>email</th>
			<!--<th>faculty_id</th>-->
			<th>Title</th>
			<th>Area</th>
			<th>Supervisor</th>
			<!--<th>year_of_register</th>-->
			<th>date_of_register</th>
			<th>month_year_id</th>
			<th>picture</th>
			<th>created_by</th>
			<th>modified_by</th>
			<th>created</th>
			<th>modified</th>
			<th class="actions"><?php echo __('Actions'); ?></th>
	</tr>
	</thead>
	<tbody>
	<?php foreach ($phdStudents as $phdStudent): ?>
	<tr>
		<td><?php echo h($phdStudent['PhdStudent']['id']); ?>&nbsp;</td>
		<td>
		<?php 
			if ($phdStudent['details']['status'] == "Completed") 
			echo $this->Html->link("PDC", array('controller' => 'PhdStudents', 'action' => 'pdc', $phdStudent['PhdStudent']['registration_number'])); 
			echo " ";
			echo $this->Html->link("CCC", array('controller' => 'PhdStudents', 'action' => 'ccc', $phdStudent['PhdStudent']['registration_number']));
		?>
		</td>
		<td><?php echo h($phdStudent['PhdStudent']['name']); ?>&nbsp;</td>
		<td><?php echo h($phdStudent['PhdStudent']['birth_date']); ?>&nbsp;</td>
		<td><?php echo h($phdStudent['PhdStudent']['gender']); ?>&nbsp;</td>
		<td><?php echo h($phdStudent['PhdStudent']['father_name']); ?>&nbsp;</td>
		<td><?php echo h($phdStudent['PhdStudent']['address']); ?>&nbsp;</td>
		<td><?php echo h($phdStudent['PhdStudent']['mobile_number']); ?>&nbsp;</td>
		<td><?php echo h($phdStudent['PhdStudent']['email']); ?>&nbsp;</td>
		<!--<td>
			<?php //echo $this->Html->link($phdStudent['Faculty']['faculty_name'], array('controller' => 'faculties', 'action' => 'view', $phdStudent['Faculty']['id'])); ?>
		</td>-->
		<td>
			<?php echo $this->Html->link($phdStudent['Title']['name'], array('controller' => 'titles', 'action' => 'view', $phdStudent['Title']['id'])); ?>
		</td>
		<td>
			<?php echo $this->Html->link($phdStudent['Area']['name'], array('controller' => 'areas', 'action' => 'view', $phdStudent['Area']['id'])); ?>
		</td>
		<td>
			<?php echo $this->Html->link($phdStudent['Supervisor']['name'], array('controller' => 'supervisors', 'action' => 'view', $phdStudent['Supervisor']['id'])); ?>
		</td>
		<!-- <td><?php //echo h($phdStudent['PhdStudent']['year_of_register']); ?>&nbsp;</td> -->
		<td><?php echo h($phdStudent['PhdStudent']['date_of_register']); ?>&nbsp;</td>
		<td>
			<?php echo $this->Html->link($phdStudent['MonthYear']['month_id'], array('controller' => 'month_years', 'action' => 'view', $phdStudent['MonthYear']['id'])); ?>
		</td>
		<td><?php echo h($phdStudent['PhdStudent']['picture']); ?>&nbsp;</td>
		<td><?php echo h($phdStudent['PhdStudent']['created_by']); ?>&nbsp;</td>
		<td><?php echo h($phdStudent['PhdStudent']['modified_by']); ?>&nbsp;</td>
		<td><?php echo h($phdStudent['PhdStudent']['created']); ?>&nbsp;</td>
		<td><?php echo h($phdStudent['PhdStudent']['modified']); ?>&nbsp;</td>
		<td class="actions">
			<?php //echo $this->Html->link('<i class='fa fa-eye fa-lg'></i>', array('action' => 'view', $phdStudent['PhdStudent']['id'])); ?>
			<?php 
				if($this->Html->checkPathAccesstopath('PhdStudents/view','',$authUser['id'])) {
					echo $this->Html->link("<i class='fa fa-eye fa-lg'></i>", array("controller"=>"PhdStudents",'action' => 'view', $phdStudent['PhdStudent']['id']), array('target'=>'_blank','escape' => false, 'title'=>'View','target'=>'_blank'));
				}
			?>
			&nbsp;&nbsp;
			<?php 
				if($this->Html->checkPathAccesstopath('PhdStudents/edit','',$authUser['id'])) {
					echo $this->Html->link("<i class='fa fa-pencil fa-lg'></i>", array("controller"=>"PhdStudents",'action' => 'edit', $phdStudent['PhdStudent']['id']), array('target'=>'_blank','escape' => false, 'title'=>'Edit','target'=>'_blank'));
				}
			?>
			&nbsp;&nbsp;
			<?php 
				if($this->Html->checkPathAccesstopath('PhdStudents/delete','',$authUser['id'])) {
					echo $this->Html->Link("<span class='fa fa-times fa-lg red'></span>", array("controller"=>"PhdStudents",'action' => 'delete', h($phdStudent['PhdStudent']['id'])), array('confirm' => __('Are you sure you want to delete?', h($phdStudent['PhdStudent']['id'])),'escape' => false, 'title'=>'Delete'));
				}
			?>
		</td>
	</tr>
<?php endforeach; ?>
	</tbody>
	</table>
</div>

<script>
leftMenuSelection('PhdStudents/index');
</script>

<span class='breadcrumb1'>
<span class='navbar-brand'><small>PHD STUDENTS <i class='ace-icon fa fa-angle-double-right'></i></small></span>
<?php echo $this->Html->link("<span class='navbar-brand'><small> VIEW STUDENTS <i class='ace-icon fa fa-angle-double-right'></i></small></span>", array("controller"=>"PhdStudents",'action' => 'index'),array('data-placement'=>'left','escape' => false)); ?>
</span> 