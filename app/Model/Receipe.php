<?php
class Recipe extends AppModel {
    var $name = 'Recipe';
 
    var $hasAndBelongsToMany = array(
        'Ingredient' => array(
            'className' => 'Ingredient',
            'joinTable' => 'ingredients_recipes',
            'foreignKey' => 'recipe_id',
            'associationForeignKey' => 'ingredient_id'
        ),
    );   
 
    public $validate = array(
        'name'         => array(
            'name_empty_validation'      => array(
                'rule'      => 'notEmpty',
                'message'   => 'Recipe name can not be left empty'
            ),
            'duplicate_validation'  => array(
                'rule'      => 'isUnique',
                'message'   => 'This Recipe already Exists, Please enter a different recipe'
            )
        ),
        'description' => array( 
            'desc_empty_validation'      => array(
                'rule'      => 'notEmpty',
                'message'   => 'Recipe description can not be left empty'
            ),
        )
    );
        
}
?>