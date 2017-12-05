<?php
App::uses('AppController', 'Controller');
/**
 * Designations Controller
 */
class DesignationsController extends AppController {

	/**
	 * Components
	 *
	 * @var array
	 */
	public $components = array('Paginator', 'Flash', 'Session');
	
	/**
	 * index method
	 *
	 * @return void
	*/
	public function index() {
		//$this->Designation->recursive = 0;
		//$this->set('designations', $this->Paginator->paginate());
			$results = $this->Designation->find('all');
	}
	
	/**
	 * view method
	 *
	 * @throws NotFoundException
	 * @param string $id
	 * @return void
	 */
	public function view($id = null) {
			if (!$this->Designation->exists($id)) {
				throw new NotFoundException(__('Invalid designation'));
			}
			$options = array('conditions' => array('Designation.' . $this->Designation->primaryKey => $id));
	}
	
	/**
	 * add method
	 *
	 * @return void
	 */
	public function add(){
			if ($this->request->is('post')) {
				$this->Designation->create();
				$this->request->data['Designation']['created_by'] = $this->Auth->user('id');
				if ($this->Designation->save($this->request->data)) {
					return $this->redirect(array('action' => 'index'));
				} else {
					$this->Flash->error(__('The designation could not be saved. Please, try again.'));
				}
			}
			$this->set('departments', $this->Designation->Department->find('list'));
			$this->set(compact('departments'));
		$this->layout=false;
	}
	
	/**
	 * edit method
	 *
	 * @throws NotFoundException
	 * @param string $id
	 * @return void
	 */
	public function edit($id = null) {
			if (!$this->Designation->exists($id)) {
				throw new NotFoundException(__('Invalid designation'));
			}
			$this->request->data['Designation']['modified_by'] = $this->Auth->user('id');
			if ($this->request->is(array('post', 'put'))) {
				if ($this->Designation->save($this->request->data)) {
					$this->Flash->success(__('The designation has been saved.'));
				} else {
					$this->Flash->error(__('The designation could not be saved. Please, try again.'));
				}
			} 
			$this->set('departments', $this->Designation->Department->find('list'));
			$this->set(compact('departments'));
		$this->layout=false;
	}	
	/**
	 * delete method
	 *
	 * @throws NotFoundException
	 * @param string $id
	 * @return void
	 */
	public function delete($id = null) {
			$this->Designation->id = $id;
			if (!$this->Designation->exists()) {
				throw new NotFoundException(__('Invalid designation'));
			}
			$this->request->allowMethod('post', 'delete');
			if ($this->Designation->delete()) {
				$this->Flash->success(__('The designation has been deleted.'));
			} else {
				$this->Flash->error(__('The designation could not be deleted. Please, try again.'));
			}
			return $this->redirect(array('action' => 'index'));
	}

}