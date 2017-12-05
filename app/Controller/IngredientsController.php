<?php
class IngredientsController extends AppController {
    public $uses    = array('Ingredient', 'Recipe');
     
    public $paginate = array(
        'limit' => 25,
        'order' => array('Ingredient.name' => 'asc' ) 
    );
     
    function index() {
        $ingredients = $this->paginate('Ingredient');
        $this->set(compact('ingredients'));
    }
     
   function add() {
        if ($this->request->is('post')) {
                 
            $this->Ingredient->create();
            if ($this->Ingredient->save($this->request->data)) {
                $this->Session->setFlash(__('The ingredient has been created'));
                $this->redirect(array('action' => 'index'));
            } else {
                $this->Session->setFlash(__('The ingredient could not be created. Please, try again.'));
            }   
        }
 
        $recipes    = $this->Recipe->find('list');
        $this->set('recipes',$recipes);
    }
     
    function edit($id) {
        if (!$id) {
            $this->Session->setFlash('Please provide a ingredient id');
            $this->redirect(array('action'=>'index'));
        }
 
        $ingredient = $this->Ingredient->findById($id);
        if (!$ingredient) {
            $this->Session->setFlash('Invalid Ingredient ID Provided');
            $this->redirect(array('action'=>'index'));
        }
 
        if ($this->request->is('post') || $this->request->is('put')) {
            $this->Ingredient->id = $id;
            if ($this->Ingredient->save($this->request->data)) {
                $this->Session->setFlash(__('The ingredient has been updated'));
                $this->redirect(array('action' => 'edit',$id));
            }else{
                $this->Session->setFlash(__('Unable to update your ingredient.'));
            }
        }
 
        if (!$this->request->data) {
            $this->request->data = $ingredient;
        }
             
        $recipes    = $this->Recipe->find('list');
        $this->set('recipes',$recipes);
    }
     
     
    function delete($id) {
        $this->Ingredient->id = $id;
        $this->Ingredient->delete();
        $this->Session->setFlash('Ingredient has been deleted.');
        $this->redirect(array('controller'=>'Ingredients','action'=>'index'));
    }
}
?>