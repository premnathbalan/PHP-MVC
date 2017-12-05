<?php
class Ingredient extends AppModel {
    var $name = 'Ingredient';
 
    var $hasAndBelongsToMany = array(
        'Recipe' => array(
            'className' => 'Recipe',
            'joinTable' => 'ingredients_recipes',
            'foreignKey' => 'ingredient_id',
            'associationForeignKey' => 'recipe_id'
        ),
    );   
 
    public $validate = array(
        'name'         => array(
            'empty_validation'      => array(
                'rule'      => 'notEmpty',
                'message'   => 'Ingredient name can not be left empty'
            ),
            'duplicate_validation'  => array(
                'rule'      => 'isUnique',
                'message'   => 'This Ingredient already Exists, Please enter a different ingredient'
            )
        )
    );  
 
}
?>