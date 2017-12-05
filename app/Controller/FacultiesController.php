<?php
App::uses('AppController', 'Controller');
/**
 * Faculties Controller
 *
 * @property Faculty $Faculty
 * @property PaginatorComponent $Paginator
 * @property FlashComponent $Flash
 * @property SessionComponent $Session
 */
class FacultiesController extends AppController {

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
	public function index() {		$access_result = $this->checkPathAccess($this->Auth->user('id'));		if (!$access_result) {
			$results = $this->Faculty->find('all');			$this->set('faculties', $results);		} else {			$this->render('../Users/access_denied');		}
	}
/**
 * view method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function view($id = null) {		$access_result = $this->checkPathAccess($this->Auth->user('id'));		if (!$access_result) {
		if (!$this->Faculty->exists($id)) {			throw new NotFoundException(__('Invalid faculty'));		}		$options = array('conditions' => array('Faculty.' . $this->Faculty->primaryKey => $id));		$this->set('faculty', $this->Faculty->find('first', $options));		} else {			$this->render('../Users/access_denied');		}
		$this->layout=false;
	}

/**
 * add method * * @return void
 */
	public function add() {		$access_result = $this->checkPathAccess($this->Auth->user('id'));		if (!$access_result) {
			if ($this->request->is('post')) {				$this->Faculty->create();				$this->request->data['Faculty']['created_by'] = $this->Auth->user('id');				if ($this->Faculty->save($this->request->data)) {					$this->Flash->success(__('The faculty has been saved.'));					echo "success";exit;				} else {					$this->Flash->error(__('The faculty could not be saved. Please, try again.'));				}			}		} else {			$this->render('../Users/access_denied');		}		$this->layout=false;	}

/**
 * edit method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function edit($id = null) {		$access_result = $this->checkPathAccess($this->Auth->user('id'));		if (!$access_result) {
			if (!$this->Faculty->exists($id)) {				throw new NotFoundException(__('Invalid faculty'));			}			if ($this->request->is(array('post', 'put'))) {				$this->request->data['Faculty']['modified_by'] = $this->Auth->user('id');				if ($this->Faculty->save($this->request->data)) {					$this->Flash->success(__('The faculty has been saved.'));					echo "success";exit;				} else {					$this->Flash->error(__('The faculty could not be saved. Please, try again.'));				}			}else {				$options = array('conditions' => array('Faculty.' . $this->Faculty->primaryKey => $id));				$this->request->data = $this->Faculty->find('first', $options);			}		} else {			$this->render('../Users/access_denied');		}		$this->layout=false;	}

/**
 * delete method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function delete($id = null) {		$access_result = $this->checkPathAccess($this->Auth->user('id'));		if (!$access_result) {
			$this->Faculty->id = $id;			if (!$this->Faculty->exists()) {				throw new NotFoundException(__('Invalid faculty'));			}			$this->request->allowMethod('post', 'delete');			if ($this->Faculty->delete()) {				$this->Flash->success(__('The faculty has been deleted.'));			} else {				$this->Flash->error(__('The faculty could not be deleted. Please, try again.'));			}			return $this->redirect(array('action' => 'index'));		} else {			$this->render('../Users/access_denied');		}	}
}
