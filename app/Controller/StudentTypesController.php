<?php
App::uses('AppController', 'Controller');
/**
 * StudentTypes Controller
 *
 * @property StudentType $StudentType
 * @property PaginatorComponent $Paginator
 * @property FlashComponent $Flash
 * @property SessionComponent $Session
 */
class StudentTypesController extends AppController {

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
		$this->StudentType->recursive = 0;
		$this->set('studentTypes', $this->Paginator->paginate());
	}

/**
 * view method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function view($id = null) {
		if (!$this->StudentType->exists($id)) {
			throw new NotFoundException(__('Invalid student type'));
		}
		$options = array('conditions' => array('StudentType.' . $this->StudentType->primaryKey => $id));
		$this->set('studentType', $this->StudentType->find('first', $options));
	}

/**
 * add method
 *
 * @return void
 */
	public function add() {
		if ($this->request->is('post')) {
			$this->StudentType->create();
			if ($this->StudentType->save($this->request->data)) {
				$this->Flash->success(__('The student type has been saved.'));
				return $this->redirect(array('action' => 'index'));
			} else {
				$this->Flash->error(__('The student type could not be saved. Please, try again.'));
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
		if (!$this->StudentType->exists($id)) {
			throw new NotFoundException(__('Invalid student type'));
		}
		if ($this->request->is(array('post', 'put'))) {
			if ($this->StudentType->save($this->request->data)) {
				$this->Flash->success(__('The student type has been saved.'));
				return $this->redirect(array('action' => 'index'));
			} else {
				$this->Flash->error(__('The student type could not be saved. Please, try again.'));
			}
		} else {
			$options = array('conditions' => array('StudentType.' . $this->StudentType->primaryKey => $id));
			$this->request->data = $this->StudentType->find('first', $options);
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
		$this->StudentType->id = $id;
		if (!$this->StudentType->exists()) {
			throw new NotFoundException(__('Invalid student type'));
		}
		$this->request->allowMethod('post', 'delete');
		if ($this->StudentType->delete()) {
			$this->Flash->success(__('The student type has been deleted.'));
		} else {
			$this->Flash->error(__('The student type could not be deleted. Please, try again.'));
		}
		return $this->redirect(array('action' => 'index'));
	}
}
