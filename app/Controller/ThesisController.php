<?php
App::uses('AppController', 'Controller');
/**
 * Thesis Controller
 *
 * @property Thesi $Thesi
 * @property PaginatorComponent $Paginator
 * @property FlashComponent $Flash
 * @property SessionComponent $Session
 */
class ThesisController extends AppController {

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
		$this->Thesi->recursive = 0;
		$this->set('thesis', $this->Paginator->paginate());
	}

/**
 * view method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function view($id = null) {
		if (!$this->Thesi->exists($id)) {
			throw new NotFoundException(__('Invalid thesi'));
		}
		$options = array('conditions' => array('Thesi.' . $this->Thesi->primaryKey => $id));
		$this->set('thesi', $this->Thesi->find('first', $options));
		$this->layout = false;
	}

/**
 * add method
 *
 * @return void
 */
	public function add() {
		if ($this->request->is('post')) {
			$this->Thesi->create();
			if ($this->Thesi->save($this->request->data)) {
				$this->Flash->success(__('The thesi has been saved.'));
				return $this->redirect(array('action' => 'index'));
			} else {
				$this->Flash->error(__('The thesi could not be saved. Please, try again.'));
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
		if (!$this->Thesi->exists($id)) {
			throw new NotFoundException(__('Invalid thesi'));
		}
		if ($this->request->is(array('post', 'put'))) {
			if ($this->Thesi->save($this->request->data)) {
				$this->Flash->success(__('The thesis has been saved.'));
				echo "Success"; exit;
				//return $this->redirect(array('action' => 'index'));
			} else {
				$this->Flash->error(__('The thesis could not be saved. Please, try again.'));
			}
		} else {
			$options = array('conditions' => array('Thesi.' . $this->Thesi->primaryKey => $id));
			$this->request->data = $this->Thesi->find('first', $options);
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
		$this->Thesi->id = $id;
		if (!$this->Thesi->exists()) {
			throw new NotFoundException(__('Invalid thesi'));
		}
		$this->request->allowMethod('post', 'delete');
		if ($this->Thesi->delete()) {
			$this->Flash->success(__('The thesi has been deleted.'));
		} else {
			$this->Flash->error(__('The thesi could not be deleted. Please, try again.'));
		}
		return $this->redirect(array('action' => 'index'));
	}
}
