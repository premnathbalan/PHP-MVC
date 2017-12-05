<?php
App::uses('AppController', 'Controller');
/**
 * CollegeReferences Controller
 *
 * @property CollegeReference $CollegeReference
 * @property PaginatorComponent $Paginator
 * @property FlashComponent $Flash
 * @property SessionComponent $Session
 */
class CollegeReferencesController extends AppController {

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
			$this->CollegeReference->recursive = 0;
			$this->set('collegeReferences', $this->Paginator->paginate());		} else {			$this->render('../Users/access_denied');		}
	}

/**
 * view method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function view($id = null) {		$access_result = $this->checkPathAccess($this->Auth->user('id'));		if (!$access_result) {
			if (!$this->CollegeReference->exists($id)) {				throw new NotFoundException(__('Invalid college reference'));			}			$options = array('conditions' => array('CollegeReference.' . $this->CollegeReference->primaryKey => $id));			$this->set('collegeReference', $this->CollegeReference->find('first', $options));		} else {			$this->render('../Users/access_denied');		}
		$this->layout=false;
	}

/**
 * add method
 *
 * @return void
 */
	public function add() {		$access_result = $this->checkPathAccess($this->Auth->user('id'));		if (!$access_result) {
			if ($this->request->is('post')) {				$this->CollegeReference->create();				$this->request->data['CollegeReference']['created_by']=$this->Auth->user('id');				if ($this->CollegeReference->save($this->request->data)) {					$this->Flash->success(__('The college reference has been saved.'));					echo "success";exit;				} else {					$this->Flash->error(__('The college reference could not be saved. Please, try again.'));				}						}		} else {			$this->render('../Users/access_denied');		}		$this->layout=false;
	}

/**
 * edit method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function edit($id = null) {		$access_result = $this->checkPathAccess($this->Auth->user('id'));		if (!$access_result) {
			if (!$this->CollegeReference->exists($id)) {				throw new NotFoundException(__('Invalid college reference'));			}			$this->request->data['CollegeReference']['modified_by']=$this->Auth->user('id');			if ($this->request->is(array('post', 'put'))) {				if ($this->CollegeReference->save($this->request->data)) {					$this->Flash->success(__('The college reference has been saved.'));					echo "success";exit;				} else {					$this->Flash->error(__('The college reference could not be saved. Please, try again.'));				}			} else {				$options = array('conditions' => array('CollegeReference.' . $this->CollegeReference->primaryKey => $id));				$this->request->data = $this->CollegeReference->find('first', $options);			}		} else {			$this->render('../Users/access_denied');		}
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
			$this->CollegeReference->id = $id;			if (!$this->CollegeReference->exists()) {				throw new NotFoundException(__('Invalid college reference'));			}			$this->request->allowMethod('post', 'delete');			if ($this->CollegeReference->delete()) {				$this->Flash->success(__('The college reference has been deleted.'));			} else {				$this->Flash->error(__('The college reference could not be deleted. Please, try again.'));			}			return $this->redirect(array('action' => 'index'));		} else {			$this->render('../Users/access_denied');		}
	}
}
