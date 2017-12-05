<!-- app/View/Recipes/add.ctp -->
<div class="users form">
 
<?php echo $this->Form->create('Recipe');?>
    <fieldset>
        <legend><?php echo __('Add Recipe'); ?></legend>
        <?php echo $this->Form->input('name');
        echo $this->Form->input('description');
        echo $this->Form->input('Recipe.Ingredient', array(   'multiple' => true));
         
        echo $this->Form->submit('Add Recipe', array('class' => 'form-submit',  'title' => 'Click here to add the recipe') ); 
?>
    </fieldset>
<?php echo $this->Form->end(); ?>
</div>
<?php echo $this->Html->link( "List Recipes",   array('controller'=>'recipes','action'=>'index'),array('escape' => false) ); ?>
<br/>         
<?php echo $this->Html->link( "Add A New Recipe",   array('controller'=>'recipes','action'=>'add'),array('escape' => false) ); ?>
<br/>
<?php echo $this->Html->link( "List Ingredients",   array('controller'=>'ingredients','action'=>'index'),array('escape' => false) ); ?>
<br/> 
<?php echo $this->Html->link( "Add A New Ingredient",   array('controller'=>'ingredients','action'=>'add'),array('escape' => false) ); ?>