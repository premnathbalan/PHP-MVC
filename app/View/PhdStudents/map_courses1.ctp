<?php
//echo $regNo;
?>
<?php echo $this->Html->css('jquery-ui'); ?>
<?php echo $this->Html->script('jquery-1.12.4'); ?>
<?php echo $this->Html->script('jquery-ui'); ?>

<?php echo $this->Html->script('table3-jquery.dataTables'); ?>

<script>
$(function() {
    $( ".course_name" ).autocomplete({
        source: 'findCourse'
    });
});
</script>

<DIV id="outer">
	<DIV id="product">
		<span id="pdt">
			<DIV class="phd_course float-clear" style="clear:both;">
				<DIV class="float-left"><input type="checkbox" name="course_index[]" />
				<input type="text" name="course_name[]" class="course_name" id="1" />
				<input type="text" name="course_max_marks[]" />
				<input type="text" name="course_pass_percent[]" /></DIV>
			</DIV>
		</span>
	</DIV>

	<DIV class="btn-action float-clear">
		<input type="button" name="add_item" value="Add More" onClick="addMore();" />
		<input type="button" name="del_item" value="Delete" onClick="deleteRow();" />
		<span class="success"><?php if(isset($message)) { echo $message; }?></span>
	</DIV>

</DIV>

<SCRIPT>
function addMore() {
	$("#product").append($('#pdt').html());
}
function deleteRow() {
	$('DIV.phd_course').each(function(index, item){
		jQuery(':checkbox', this).each(function () {
            if ($(this).is(':checked')) {  alert(index);
				$(item).remove();
            }
        });
	});
}
</SCRIPT>