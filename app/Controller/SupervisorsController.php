<?php
App::uses('AppController', 'Controller');
/**
 * Supervisors Controller
 *
 * @property Supervisor $Supervisor
 * @property PaginatorComponent $Paginator
 * @property FlashComponent $Flash
 * @property SessionComponent $Session
 */
class SupervisorsController extends AppController {

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
		$this->Supervisor->recursive = 0;
		$this->set('supervisors', $this->Paginator->paginate());
	}

/**
 * view method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function view($id = null) {
		if (!$this->Supervisor->exists($id)) {
			throw new NotFoundException(__('Invalid supervisor'));
		}
		$options = array('conditions' => array('Supervisor.' . $this->Supervisor->primaryKey => $id));
		$this->set('supervisor', $this->Supervisor->find('first', $options));
		$this->layout=false;
	}

/**
 * add method
 *
 * @return void
 */
	public function add() {
		if ($this->request->is('post')) {
			$this->Supervisor->create();
			if ($this->Supervisor->save($this->request->data)) {
				$this->Flash->success(__('The supervisor has been saved.'));
				return $this->redirect(array('action' => 'index'));
			} else {
				$this->Flash->error(__('The supervisor could not be saved. Please, try again.'));
			}
		}
	}

/**
 * edit method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function edit($id = null) {
		if (!$this->Supervisor->exists($id)) {
			throw new NotFoundException(__('Invalid supervisor'));
		}
		$this->request->data['Department']['modified_by'] = $this->Auth->user('id');
		if ($this->request->is(array('post', 'put'))) {
			if ($this->Supervisor->save($this->request->data)) {
				$this->Flash->success(__('The supervisor has been saved.'));
				//return $this->redirect(array('action' => 'index'));
				echo "success";exit;
			} else {
				$this->Supervisor->validationErrors = array(); // Field validation error message removed.
				$this->Flash->error(__('The supervisor could not be saved. Please, try again.'));
			}
		} else {
			$options = array('conditions' => array('Supervisor.' . $this->Supervisor->primaryKey => $id));
			$this->request->data = $this->Supervisor->find('first', $options);
		}
		$this->layout = false;
	}

/**
 * delete method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function delete($id = null) {
		$this->Supervisor->id = $id;
		if (!$this->Supervisor->exists()) {
			throw new NotFoundException(__('Invalid supervisor'));
		}
		$this->request->allowMethod('post', 'delete');
		if ($this->Supervisor->delete()) {
			$this->Flash->success(__('The supervisor has been deleted.'));
		} else {
			$this->Flash->error(__('The supervisor could not be deleted. Please, try again.'));
		}
		return $this->redirect(array('action' => 'index'));
	}
}
