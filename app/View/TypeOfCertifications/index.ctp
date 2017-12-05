<div id="js-load-forms"></div>

<?php 
if($this->Html->checkPathAccesstopath('TypeOfCertifications/add','',$authUser['id'])){
echo $this->Html->link( '<i class="ace-icon fa fa-plus-square"></i>'. 'Type Of Certification Add', array("controller"=>"TypeOfCertifications",'action'=>'add'),array('class' =>"js-popup addBtn btn",'escape' => false, 'title'=>'Add Type Of Certifications','style'=>'margin-bottom:5px;')); 
}
?>
<div class="userRoles index">
	<table cellpadding="0" cellspacing="0" border="0" class="display" id="example">
	<thead>
		<tr>
			<th class="thAction">&nbsp;&nbsp;Action&nbsp;&nbsp;</th>
			<th>Type Of Certification</th>
			<th>Short Code</th>
			<th class="thAction">&nbsp;&nbsp;Delete&nbsp;&nbsp;</th>
	</tr>
	</thead>
	<tbody>
	<?php foreach ($typeOfCertifications as $typeOfCertification): ?>
	<tr class=" gradeX">
		<td class="actions">
			<?php 
			if($this->Html->checkPathAccesstopath('TypeOfCertifications/view','',$authUser['id'])){
				echo $this->Html->link("<i class='fa fa-eye fa-lg'></i>", array("controller"=>"TypeOfCertifications",'action' => 'view', h($typeOfCertification['TypeOfCertification']['id'])),array('class'=>'js-popup','escape' => false, 'title'=>'View'));
			}if($this->Html->checkPathAccesstopath('TypeOfCertifications/edit','',$authUser['id'])){
				echo $this->Html->link("<i class='fa fa-pencil fa-lg'></i>", array("controller"=>"TypeOfCertifications",'action' => 'edit', h($typeOfCertification['TypeOfCertification']['id'])),array('title'=>'Edit','escape' => false,'class' =>"js-popup"));
			}
			?>
		</td>
		<td><?php echo h($typeOfCertification['TypeOfCertification']['certification']); ?></td>
		<td><?php echo h($typeOfCertification['TypeOfCertification']['short_code']); ?></td>
		<td class="actions">
			<?php 
			if($this->Html->checkPathAccesstopath('TypeOfCertifications/delete','',$authUser['id'])){
				echo $this->Form->postLink("<span class='fa fa-times fa-lg red'></span>", array("controller"=>"TypeOfCertifications",'action' => 'delete', h($typeOfCertification['TypeOfCertification']['id'])), array('confirm' => __('Are you sure you want to delete?'),'escape' => false)); 
			}
			?>
		</td>
	</tr>
<?php endforeach; ?>
	</tbody>
	<tfoot>
		<tr>
			<th><input type="text" name="&nbsp;&nbsp;Action&nbsp;&nbsp;" value="&nbsp;&nbsp;Action&nbsp;&nbsp;" class="search_init" /></th>
			<th><input type="text" name="Type Of Certification" value="Type Of Certification" class="search_init" /></th>
			<th><input type="text" name="Short Code" value="Short Code" class="search_init" /></th>
			<th></th>
		</tr>
	</tfoot>
	</table>	
</div>

<span class='breadcrumb1'>
<span class='navbar-brand'><small>MASTERS <i class='ace-icon fa fa-angle-double-right'></i></small></span>
<?php echo $this->Html->link("<span class='navbar-brand'><small>Type Of Certification <i class='ace-icon fa fa-angle-double-right'></i></small></span>", array("controller"=>"TypeOfCertifications",'action' => 'index'),array('data-placement'=>'left','escape' => false)); ?>
</span>