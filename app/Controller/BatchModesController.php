<?php
App::uses('AppController', 'Controller');
/**
 * BatchModes Controller
 *
 * @property BatchMode $BatchMode
 * @property PaginatorComponent $Paginator
 * @property FlashComponent $Flash
 * @property nComponent $n
 * @property SessionComponent $Session
 */
class BatchModesController extends AppController {

/**
 * Components
 *
 * @var array
 */
	public $components = array('Paginator', 'Flash', 'N', 'Session');

/**
 * index method
 *
 * @return void
 */
	public function index() {
		$this->BatchMode->recursive = 0;
		$this->set('batchModes', $this->Paginator->paginate());
	}

/**
 * view method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function view($id = null) {
		if (!$this->BatchMode->exists($id)) {
			throw new NotFoundException(__('Invalid batch mode'));
		}
		$options = array('conditions' => array('BatchMode.' . $this->BatchMode->primaryKey => $id));
		$this->set('batchMode', $this->BatchMode->find('first', $options));
	}

/**
 * add method
 *
 * @return void
 */
	public function add() {
		if ($this->request->is('post')) {
			$this->BatchMode->create();
			if ($this->BatchMode->save($this->request->data)) {
				$this->Flash->success(__('The batch mode has been saved.'));
				return $this->redirect(array('action' => 'index'));
			} else {
				$this->Flash->error(__('The batch mode could not be saved. Please, try again.'));
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
		if (!$this->BatchMode->exists($id)) {
			throw new NotFoundException(__('Invalid batch mode'));
		}
		if ($this->request->is(array('post', 'put'))) {
			if ($this->BatchMode->save($this->request->data)) {
				$this->Flash->success(__('The batch mode has been saved.'));
				return $this->redirect(array('action' => 'index'));
			} else {
				$this->Flash->error(__('The batch mode could not be saved. Please, try again.'));
			}
		} else {
			$options = array('conditions' => array('BatchMode.' . $this->BatchMode->primaryKey => $id));
			$this->request->data = $this->BatchMode->find('first', $options);
		}
	}

/**
 * delete method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function delete($id = null) {
		$this->BatchMode->id = $id;
		if (!$this->BatchMode->exists()) {
			throw new NotFoundException(__('Invalid batch mode'));
		}
		$this->request->allowMethod('post', 'delete');
		if ($this->BatchMode->delete()) {
			$this->Flash->success(__('The batch mode has been deleted.'));
		} else {
			$this->Flash->error(__('The batch mode could not be deleted. Please, try again.'));
		}
		return $this->redirect(array('action' => 'index'));
	}
}
