<?php
App::uses('AppController', 'Controller');
/**
 * UniversityReferences Controller
 *
 * @property UniversityReference $UniversityReference
 * @property PaginatorComponent $Paginator
 * @property FlashComponent $Flash
 * @property SessionComponent $Session
 */
class UniversityReferencesController extends AppController {

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
			$results = $this->UniversityReference->find('all');			$this->set('universityReferences', $results);		} else {			$this->render('../Users/access_denied');		}
	}

/**
 * view method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function view($id = null) {		$access_result = $this->checkPathAccess($this->Auth->user('id'));		if (!$access_result) {
			if (!$this->UniversityReference->exists($id)) {				throw new NotFoundException(__('Invalid university reference'));			}			$options = array('conditions' => array('UniversityReference.' . $this->UniversityReference->primaryKey => $id));			$this->set('universityReference', $this->UniversityReference->find('first', $options));		} else {			$this->render('../Users/access_denied');		}
		$this->layout=false;
	}

/**
 * add method
 *
 * @return void
 */
	public function add() {		$access_result = $this->checkPathAccess($this->Auth->user('id'));		if (!$access_result) {
			if ($this->request->is('post')) {				$this->UniversityReference->create();				$this->request->data['UniversityReference']['created_by']=$this->Auth->user('id');				if ($this->UniversityReference->save($this->request->data)) {					$this->Flash->success(__('The university reference has been saved.'));					echo "success";exit;				} else {					$this->Flash->error(__('The university reference could not be saved. Please, try again.'));				}			}		} else {			$this->render('../Users/access_denied');		}
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
			if (!$this->UniversityReference->exists($id)) {				throw new NotFoundException(__('Invalid university reference'));			}			$this->request->data['UniversityReference']['modified_by']=$this->Auth->user('id');			if ($this->request->is(array('post', 'put'))) {				if ($this->UniversityReference->save($this->request->data)) {					$this->Flash->success(__('The university reference has been saved.'));					echo "success";exit;				} else {					$this->Flash->error(__('The university reference could not be saved. Please, try again.'));				}			} else {				$options = array('conditions' => array('UniversityReference.' . $this->UniversityReference->primaryKey => $id));				$this->request->data = $this->UniversityReference->find('first', $options);			}		} else {			$this->render('../Users/access_denied');		}
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
			$this->UniversityReference->id = $id;			if (!$this->UniversityReference->exists()) {				throw new NotFoundException(__('Invalid university reference'));			}			$this->request->allowMethod('post', 'delete');			if ($this->UniversityReference->delete()) {				$this->Flash->success(__('The university reference has been deleted.'));			} else {				$this->Flash->error(__('The university reference could not be deleted. Please, try again.'));			}			return $this->redirect(array('action' => 'index'));		} else {			$this->render('../Users/access_denied');		}
	}
}
