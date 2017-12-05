<?php
App::uses('AppController', 'Controller');
/**
 * Departments Controller
 *
 * @property Department $Department
 * @property PaginatorComponent $Paginator
 * @property FlashComponent $Flash
 * @property SessionComponent $Session
 */
class DepartmentsController extends AppController {

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
		//$this->Department->recursive = 0;
		//$this->set('departments', $this->Paginator->paginate());		$access_result = $this->checkPathAccess($this->Auth->user('id'));		if (!$access_result) {
			$results = $this->Department->find('all');			$this->set('departments', $results);		} else {			$this->render('../Users/access_denied');		}
	}

/**
 * view method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function view($id = null) {		$access_result = $this->checkPathAccess($this->Auth->user('id'));		if (!$access_result) {
			if (!$this->Department->exists($id)) {				throw new NotFoundException(__('Invalid department'));			}			$options = array('conditions' => array('Department.' . $this->Department->primaryKey => $id));			$this->set('department', $this->Department->find('first', $options));		} else {			$this->render('../Users/access_denied');		}
		$this->layout=false;
	}

/**
 * add method
 *
 * @return void
 */
	public function add(){			$access_result = $this->checkPathAccess($this->Auth->user('id'));		if (!$access_result) {
			if ($this->request->is('post')) { 				$this->Department->create();				$this->request->data['Department']['created_by'] = $this->Auth->user('id');				if ($this->Department->save($this->request->data)) {					$this->Flash->success(__('The department has been saved.'));					//return $this->redirect(array('action' => 'index'));					//$json['redirect'] = Router::url(array('controller'=>'Department','action' => 'index'));					//$json['success'] ="success";					echo "success";exit;				} else {					//$this->Department->validationErrors = array(); // Field validation error message removed.					$this->Flash->error(__('The department could not be saved. Please, try again.'));				}			}		} else {			$this->render('../Users/access_denied');		}
		$this->layout=false;		
	}

/**
 * edit method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function edit($id = null) {		$access_result = $this->checkPathAccess($this->Auth->user('id'));		if (!$access_result) {
			if (!$this->Department->exists($id)) {				throw new NotFoundException(__('Invalid department'));			}			$this->request->data['Department']['modified_by'] = $this->Auth->user('id');			if ($this->request->is(array('post', 'put'))) {				if ($this->Department->save($this->request->data)) {					$this->Flash->success(__('The department has been saved.'));					echo "success";exit;				} else {					$this->Department->validationErrors = array(); // Field validation error message removed.					$this->Flash->error(__('The department could not be saved. Please, try again.'));				}			} else {				$options = array('conditions' => array('Department.' . $this->Department->primaryKey => $id));				$this->request->data = $this->Department->find('first', $options);			}		} else {			$this->render('../Users/access_denied');		}
		$this->layout=false;
	}

/**
 * delete method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function delete($id = null) {		$access_result = $this->checkPathAccess($this->Auth->user('id'));		if (!$access_result) {
		$this->Department->id = $id;		if (!$this->Department->exists()) {			throw new NotFoundException(__('Invalid department'));		}		$this->request->allowMethod('post', 'delete');		if ($this->Department->delete()) {			$this->Flash->success(__('The department has been deleted.'));		} else {			$this->Flash->error(__('The department could not be deleted. Please, try again.'));		}		return $this->redirect(array('action' => 'index'));		} else {			$this->render('../Users/access_denied');		}
	}

}
