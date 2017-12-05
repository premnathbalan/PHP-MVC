<?php
App::uses('AppController', 'Controller');
/**
 * Academics Controller
 *
 * @property Academic $Academic
 * @property PaginatorComponent $Paginator
 * @property FlashComponent $Flash
 * @property SessionComponent $Session
 */
class AcademicsController extends AppController {

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
			$results = $this->Academic->find('all', array(					'fields'=>array('Academic.academic_name', 'Academic.short_code', 'Academic.academic_type', 					'Academic.academic_name_tamil'),					'recursive'=>0
			));			$this->set('academics', $results);		} else {			$this->render('../Users/access_denied');		}
	}

/**
 * view method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function view($id = null) {		$access_result = $this->checkPathAccess($this->Auth->user('id'));		if (!$access_result) {
			if (!$this->Academic->exists($id)) {				throw new NotFoundException(__('Invalid academic'));			}			$options = array('conditions' => array('Academic.' . $this->Academic->primaryKey => $id));			$this->set('academic', $this->Academic->find('first', $options));		} else {			$this->render('../Users/access_denied');		}		$this->layout=false;	}

/**
 * add method
 *
 * @return void
 */
	public function add() {		$access_result = $this->checkPathAccess($this->Auth->user('id'));		if (!$access_result) {			if ($this->request->is('post')) {				$this->Academic->create();				$this->request->data['Academic']['created_by'] = $this->Auth->user('id');				if ($this->Academic->save($this->request->data)) {					$this->Flash->success(__('The academic has been saved.'));					echo "success";exit;					//return $this->redirect(array('action' => 'index'));				} else {					$this->Flash->error(__('The academic could not be saved. Please, try again.'));				}			}		} else {			$this->render('../Users/access_denied');		}		$this->layout=false;	}

/**
 * edit method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function edit($id = null) {		$access_result = $this->checkPathAccess($this->Auth->user('id'));		if (!$access_result) {
			if (!$this->Academic->exists($id)) {				throw new NotFoundException(__('Invalid academic'));			}			if ($this->request->is(array('post', 'put'))) {				$this->request->data['Academic']['modified_by'] = $this->Auth->user('id');				if ($this->Academic->save($this->request->data)) {					$this->Flash->success(__('The academic has been saved.'));					echo "success";exit;				} else {					$this->Flash->error(__('The academic could not be saved. Please, try again.'));				}			} else {				$options = array('conditions' => array('Academic.' . $this->Academic->primaryKey => $id));				$this->request->data = $this->Academic->find('first', $options);			}		}		else {			$this->layout=false;			$this->render('../Users/access_denied');		}		$this->layout=false;	}

/**
 * delete method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function delete($id = null) {		$access_result = $this->checkPathAccess($this->Auth->user('id'));		if (!$access_result) {
			$this->Academic->id = $id;			if (!$this->Academic->exists()) {				throw new NotFoundException(__('Invalid academic'));			}			$this->request->allowMethod('post', 'delete');			if ($this->Academic->delete()) {				$this->Flash->success(__('The academic has been deleted.'));			} else {				$this->Flash->error(__('The academic could not be deleted. Please, try again.'));			}			return $this->redirect(array('action' => 'index'));		}		else {			$this->render('../Users/access_denied');		}
	}
}
