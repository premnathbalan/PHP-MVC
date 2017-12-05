<?php echo $this->Html->script('jquery-2.1.4.min'); ?>
<?php echo $this->Html->script('bootstrap'); ?>
<?php echo $this->Html->css('bootstrap'); ?>
<?php echo $this->Html->script('bootstrap-multiselect'); ?>
<?php echo $this->Html->css('bootstrap-multiselect'); ?>
<?php echo $this->Form->input('program_id', array('label' => 'Program', 'class' => 'js-program', 'multiple' => true)); ?>

<script type="text/javascript">
        $(function () {
            $('#program_id').multiselect({
                includeSelectAllOption: true
            });
        });
    </script>
    