<div id="js-load-forms"></div>

<?php echo $this->Html->link( '<i class="ace-icon fa fa-plus-square"></i>'. 'Add Signature', array("controller"=>"Signatures",'action'=>'add'),array('class' =>"js-popup addBtn btn",'escape' => false, 'title'=>'Add Signature', 'style'=>'margin-bottom:5px;')); ?>

<table cellpadding="0" cellspacing="0" class="display" id="example">
	<thead>
	<tr>
		<th class="thAction">&nbsp;&nbsp;Action&nbsp;&nbsp;</th>
		<th>Name</th>
		<th>Tamil Name</th>
		<th>Role</th>
		<th>Role&nbsp;in&nbsp;Tamil</th>
		<th class="thAction">Image</th>
		<th class="thAction">&nbsp;&nbsp;Delete&nbsp;&nbsp;</th>
	</tr>
	</thead>
	<tbody>
	<?php foreach ($signatures as $signature): ?>
	<tr class="gradeX">
		<td class="actions">
		<?php 
		echo $this->Html->link("<i class='fa fa-eye fa-lg'></i>", array("controller"=>"signatures",'action' => 'view', $signature['Signature']['id']),array('class' =>"js-popup",'escape' => false, 'title'=>'View'));
		echo $this->Html->link("<i class='fa fa-pencil fa-lg'></i>", array("controller"=>"signatures",'action' => 'edit', $signature['Signature']['id']),array('class' =>"js-popup", 'title'=>'Edit','escape' => false));
		 ?>
		</td>
		<td><?php echo h($signature['Signature']['name']); ?></td>
		<td class='baamini'><?php echo h($signature['Signature']['tamil_name']); ?></td>
		<td><?php echo h($signature['Signature']['role']); ?></td>
		<td class='baamini'><?php echo h($signature['Signature']['role_tamil']); ?></td>
		<td><div style="text-align:center;">
			<?php //echo $this->Html->image(h("certificate_signature/".$signature['Signature']['signature']), ['alt' => h($signature['Signature']['signature']), 'style'=>'width:100px;height:50px;']);?>
			<?php echo $this->Html->image(h("certificate_signature/".$signature['Signature']['signature']));?>
			</div>
		</td>
		<td class="actions">
		<?php 
		echo $this->Form->postLink("<span class='fa fa-times fa-lg red'></span>", array("controller"=>"signatures",'action' => 'delete', $signature['Signature']['id']), array('confirm' => __('Are you sure you want to delete # %s?', $signature['Signature']['id']),'escape' => false, 'title'=>'Delete')); ?>
		</td>
	</tr>
	<?php endforeach; ?>
	</tbody>
	<tfoot>
		<tr>
			<th></th>
			<th><input type="text" name="Academic" value="Academic" class="search_init" /></th>
			<th><input type="text" name="Program" value="Program" class="search_init" /></th>
			<th><input type="text" name="Department" value="Department" class="search_init" /></th>
			<th><input type="text" name="Signature" value="Signature" class="search_init" /></th>
			<th></th>
			<th></th>
		</tr>
	</tfoot>
</table>

<span class='breadcrumb1'>
<span class='navbar-brand'><small>MASTERS <i class='ace-icon fa fa-angle-double-right'></i></small></span>
<?php echo $this->Html->link("<span class='navbar-brand'><small>SIGNATURE <i class='ace-icon fa fa-angle-double-right'></i></small></span>", array("controller"=>"Signatures",'action' => 'index'),array('data-placement'=>'left','escape' => false)); ?>
</span>