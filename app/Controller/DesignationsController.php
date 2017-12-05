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
		//$this->set('designations', $this->Paginator->paginate());		$access_result = $this->checkPathAccess($this->Auth->user('id'));		if (!$access_result) {
			$results = $this->Designation->find('all');			$this->set('designations', $results);		} else {			$this->render('../Users/access_denied');		}
	}
	
	/**
	 * view method
	 *
	 * @throws NotFoundException
	 * @param string $id
	 * @return void
	 */
	public function view($id = null) {				$access_result = $this->checkPathAccess($this->Auth->user('id'));		if (!$access_result) {
			if (!$this->Designation->exists($id)) {
				throw new NotFoundException(__('Invalid designation'));
			}
			$options = array('conditions' => array('Designation.' . $this->Designation->primaryKey => $id));			$this->set('designation', $this->Designation->find('first', $options));									$editId = $id;			$this->set(compact('designation','editId'));		} else {			$this->render('../Users/access_denied');		}		$this->layout=false;
	}
	
	/**
	 * add method
	 *
	 * @return void
	 */
	public function add(){				$access_result = $this->checkPathAccess($this->Auth->user('id'));		if (!$access_result) {
			if ($this->request->is('post')) {
				$this->Designation->create();
				$this->request->data['Designation']['created_by'] = $this->Auth->user('id');
				if ($this->Designation->save($this->request->data)) {					$this->Flash->success(__('The designation has been saved.'));
					return $this->redirect(array('action' => 'index'));
				} else {
					$this->Flash->error(__('The designation could not be saved. Please, try again.'));
				}
			}	
			$this->set('departments', $this->Designation->Department->find('list'));
			$this->set(compact('departments'));		} else {			$this->render('../Users/access_denied');		}
		$this->layout=false;
	}
	
	/**
	 * edit method
	 *
	 * @throws NotFoundException
	 * @param string $id
	 * @return void
	 */
	public function edit($id = null) {				$access_result = $this->checkPathAccess($this->Auth->user('id'));		if (!$access_result) {
			if (!$this->Designation->exists($id)) {
				throw new NotFoundException(__('Invalid designation'));
			}	
			$this->request->data['Designation']['modified_by'] = $this->Auth->user('id');
			if ($this->request->is(array('post', 'put'))) {				unset($this->request->data['Path'][0]);
				if ($this->Designation->save($this->request->data)) {
					$this->Flash->success(__('The designation has been saved.'));					return $this->redirect(array('action' => 'index'));
				} else {
					$this->Flash->error(__('The designation could not be saved. Please, try again.'));
				}
			} 				$options = array('conditions' => array('Designation.' . $this->Designation->primaryKey => $id));			$this->request->data = $this->Designation->find('first', $options);			$editId = $id;			$this->set(compact('editId'));	
			$this->set('departments', $this->Designation->Department->find('list'));
			$this->set(compact('departments'));		} else {			$this->render('../Users/access_denied');		}
		$this->layout=false;
	}	
	/**
	 * delete method
	 *
	 * @throws NotFoundException
	 * @param string $id
	 * @return void
	 */
	public function delete($id = null) {				$access_result = $this->checkPathAccess($this->Auth->user('id'));		if (!$access_result) {
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
			return $this->redirect(array('action' => 'index'));		} else {			$this->render('../Users/access_denied');		}
	}

}
