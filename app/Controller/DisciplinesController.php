<?php
App::uses('AppController', 'Controller');
/**
 * Disciplines Controller
 *
 * @property Discipline $Discipline
 * @property PaginatorComponent $Paginator
 * @property FlashComponent $Flash
 * @property SessionComponent $Session
 */
class DisciplinesController extends AppController {

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
		$this->Discipline->recursive = 0;
		$this->set('disciplines', $this->Paginator->paginate());
	}

/**
 * view method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function view($id = null) {
		if (!$this->Discipline->exists($id)) {
			throw new NotFoundException(__('Invalid discipline'));
		}
		$options = array('conditions' => array('Discipline.' . $this->Discipline->primaryKey => $id));
		$this->set('discipline', $this->Discipline->find('first', $options));
		$this->layout=false;
	}

/**
 * add method
 *
 * @return void
 */
	public function add() {
		if ($this->request->is('post')) {
			$this->Discipline->create();
			if ($this->Discipline->save($this->request->data)) {
				$this->Flash->success(__('The discipline has been saved.'));
				return $this->redirect(array('action' => 'index'));
			} else {
				$this->Flash->error(__('The discipline could not be saved. Please, try again.'));
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
		if (!$this->Discipline->exists($id)) {
			throw new NotFoundException(__('Invalid discipline'));
		}
		$this->request->data['Discipline']['modified_by'] = $this->Auth->user('id');
		if ($this->request->is(array('post', 'put'))) {
			if ($this->Discipline->save($this->request->data)) {
				$this->Flash->success(__('The discipline has been saved.'));
				//return $this->redirect(array('action' => 'index'));
				echo "success";exit;
			} else {
				$this->Discipline->validationErrors = array(); // Field validation error message removed.
				$this->Flash->error(__('The discipline could not be saved. Please, try again.'));
			}
		} else {
			$options = array('conditions' => array('Discipline.' . $this->Discipline->primaryKey => $id));
			$this->request->data = $this->Discipline->find('first', $options);
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
		$this->Discipline->id = $id;
		if (!$this->Discipline->exists()) {
			throw new NotFoundException(__('Invalid discipline'));
		}
		$this->request->allowMethod('post', 'delete');
		if ($this->Discipline->delete()) {
			$this->Flash->success(__('The discipline has been deleted.'));
		} else {
			$this->Flash->error(__('The discipline could not be deleted. Please, try again.'));
		}
		return $this->redirect(array('action' => 'index'));
	}
}
