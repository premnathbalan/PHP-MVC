<?php echo $this->Html->script('bootstrap-multiselect'); ?>
<?php echo $this->Html->css('bootstrap-multiselect'); ?>
<?php echo $this->Form->input('course_mapping_id', array('type' => 'select', 'options' => $options, 'label' => 'Courses', 'class' => 'js-courses', 'multiple'=>true)); ?>

<script type="text/javascript">
        $(function () {
            $('#course_mapping_id').multiselect({
                includeSelectAllOption: true
            });
        });
    </script>
