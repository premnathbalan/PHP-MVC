<?php
App::uses('AppController', 'Controller');
/**
 * Months Controller
 *
 * @property Month $Month
 * @property PaginatorComponent $Paginator
 * @property FlashComponent $Flash
 * @property SessionComponent $Session
 */
class MonthsController extends AppController {

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
		$this->Month->recursive = 0;
		$this->set('months', $this->Paginator->paginate());
	}

/**
 * view method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function view($id = null) {
		if (!$this->Month->exists($id)) {
			throw new NotFoundException(__('Invalid month'));
		}
		$options = array('conditions' => array('Month.' . $this->Month->primaryKey => $id));
		$this->set('month', $this->Month->find('first', $options));
	}

/**
 * add method
 *
 * @return void
 */
	public function add() {
		if ($this->request->is('post')) {
			$this->Month->create();
			if ($this->Month->save($this->request->data)) {
				$this->Flash->success(__('The month has been saved.'));
				return $this->redirect(array('action' => 'index'));
			} else {
				$this->Flash->error(__('The month could not be saved. Please, try again.'));
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
		if (!$this->Month->exists($id)) {
			throw new NotFoundException(__('Invalid month'));
		}
		if ($this->request->is(array('post', 'put'))) {
			if ($this->Month->save($this->request->data)) {
				$this->Flash->success(__('The month has been saved.'));
				return $this->redirect(array('action' => 'index'));
			} else {
				$this->Flash->error(__('The month could not be saved. Please, try again.'));
			}
		} else {
			$options = array('conditions' => array('Month.' . $this->Month->primaryKey => $id));
			$this->request->data = $this->Month->find('first', $options);
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
		$this->Month->id = $id;
		if (!$this->Month->exists()) {
			throw new NotFoundException(__('Invalid month'));
		}
		$this->request->allowMethod('post', 'delete');
		if ($this->Month->delete()) {
			$this->Flash->success(__('The month has been deleted.'));
		} else {
			$this->Flash->error(__('The month could not be deleted. Please, try again.'));
		}
		return $this->redirect(array('action' => 'index'));
	}
}
