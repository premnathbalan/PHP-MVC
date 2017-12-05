<?php
class ReceipesController extends AppController {
    public $uses    = array('Recipe', 'Ingredient');
     
    public $paginate = array(
        'limit' => 25,
        'order' => array('Recipe.name' => 'asc' ) 
    );
     
    function index() {
        $recipes = $this->paginate('Recipe');
        $this->set(compact('recipes'));
    }
     
    function add() {
        if ($this->request->is('post')) {
                 
            $this->Recipe->create();
            if ($this->Recipe->save($this->request->data)) {
                $this->Session->setFlash(__('The recipe has been created'));
                $this->redirect(array('action' => 'index'));
            } else {
                $this->Session->setFlash(__('The recipe could not be created. Please, try again.'));
            }   
        }
 
        $ingredients    = $this->Ingredient->find('list');
        $this->set('ingredients',$ingredients);
    }
     
    function edit($id) {
        if (!$id) {
            $this->Session->setFlash('Please provide a recipe id');
            $this->redirect(array('action'=>'index'));
        }
 
        $recipe = $this->Recipe->findById($id);
        if (!$recipe) {
            $this->Session->setFlash('Invalid Recipe ID Provided');
            $this->redirect(array('action'=>'index'));
        }
 
        if ($this->request->is('post') || $this->request->is('put')) {
            $this->Recipe->id = $id;
            if ($this->Recipe->save($this->request->data)) {
                $this->Session->setFlash(__('The recipe has been updated'));
                $this->redirect(array('action' => 'edit',$id));
            }else{
                $this->Session->setFlash(__('Unable to update your recipe.'));
            }
        }
 
        if (!$this->request->data) {
            $this->request->data = $recipe;
        }
             
        $ingredients    = $this->Ingredient->find('list');
        $this->set('ingredients',$ingredients);
    }
     
    function delete($id) {
        $this->Recipe->id = $id;
        $this->Recipe->delete();
        $this->Session->setFlash('Recipe has been deleted.');
        $this->redirect(array('controller'=>'Recipes','action'=>'index'));
    }
}
?>