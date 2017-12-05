<?php
App::uses('AppController', 'Controller');
/**
 * Sections Controller
 *
 * @property Section $Section
 * @property PaginatorComponent $Paginator
 * @property FlashComponent $Flash
 */
class SectionsController extends AppController {

/**
 * Components
 *
 * @var array
 */
	public $components = array('Paginator', 'Flash');

/**
 * index method
 *
 * @return void
 */
	public function index() {
		//$this->Section->recursive = 0;
		//$this->set('sections', $this->Paginator->paginate());
		$access_result = $this->checkPathAccess($this->Auth->user('id'));
		if (!$access_result) {
			$results = $this->Section->find('all');		
			$this->set('sections', $results);
		} else {
			$this->render('../Users/access_denied');
		}
	}

/**
 * view method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function view($id = null) {
		if (!$this->Section->exists($id)) {
			throw new NotFoundException(__('Invalid section'));
		}
		$access_result = $this->checkPathAccess($this->Auth->user('id'));
		if (!$access_result) {
			$options = array('conditions' => array('Section.' . $this->Section->primaryKey => $id));
			$this->set('section', $this->Section->find('first', $options));
		} else {
			$this->render('../Users/access_denied');
		}
		$this->layout=false;
	}

/**
 * add method
 *
 * @return void
 */
	public function add() {
		$access_result = $this->checkPathAccess($this->Auth->user('id'));
		if (!$access_result) {
			if ($this->request->is('post')) {
				$this->Section->create();
				$this->request->data['Section']['name'] = strtoupper($this->request->data['Section']['name']);
				$this->request->data['Section']['created_by'] = $this->Auth->user('id');
				if ($this->Section->save($this->request->data)) {
					$this->Flash->success(__('The section has been saved.'));
					echo "success";exit;
				}
			}
		}
		else {
			$this->render('../Users/access_denied');
		}
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
		$access_result = $this->checkPathAccess($this->Auth->user('id'));
		if (!$access_result) {
			if (!$this->Section->exists($id)) {
				throw new NotFoundException(__('Invalid section'));
			}
			if ($this->request->is(array('post', 'put'))) {
				$this->request->data['Section']['name'] = strtoupper($this->request->data['Section']['name']);
				$this->request->data['Section']['modified_by'] = $this->Auth->user('id');
				if ($this->Section->save($this->request->data)) {
					$this->Flash->success(__('The section has been saved.'));
					echo "success";exit;
				}
			} else {
				$options = array('conditions' => array('Section.' . $this->Section->primaryKey => $id));
				$this->request->data = $this->Section->find('first', $options);
			}
		}
		else {
			$this->layout=false;
			$this->render('../Users/access_denied');
		}
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
		$access_result = $this->checkPathAccess($this->Auth->user('id'));
		if (!$access_result) {
			$this->Section->id = $id;
			if (!$this->Section->exists()) {
				throw new NotFoundException(__('Invalid section'));
			}
			$this->request->allowMethod('post', 'delete');
			if ($this->Section->delete()) {
				$this->Flash->success(__('The section has been deleted.'));
			} else {
				$this->Flash->error(__('The section Could not be Deleted.'));
			}
			return $this->redirect(array('action' => 'index'));
		}
		else {
			$this->render('../Users/access_denied');
		}
	}
}
