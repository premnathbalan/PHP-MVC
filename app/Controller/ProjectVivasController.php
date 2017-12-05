<?php
App::uses('AppController', 'Controller');
/**
 * ProjectVivas Controller
 *
 * @property ProjectViva $ProjectViva
 * @property PaginatorComponent $Paginator
 * @property FlashComponent $Flash
 * @property SessionComponent $Session
 */
class ProjectVivasController extends AppController {

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
		$this->ProjectViva->recursive = 0;
		$this->set('projectVivas', $this->Paginator->paginate());
	}

/**
 * view method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function view($id = null) {
		if (!$this->ProjectViva->exists($id)) {
			throw new NotFoundException(__('Invalid project viva'));
		}
		$options = array('conditions' => array('ProjectViva.' . $this->ProjectViva->primaryKey => $id));
		$this->set('projectViva', $this->ProjectViva->find('first', $options));
	}

/**
 * add method
 *
 * @return void
 */
	public function add() {
		if ($this->request->is('post')) {
			$this->ProjectViva->create();
			if ($this->ProjectViva->save($this->request->data)) {
				$this->Flash->success(__('The project viva has been saved.'));
				return $this->redirect(array('action' => 'index'));
			} else {
				$this->Flash->error(__('The project viva could not be saved. Please, try again.'));
			}
		}
		$eseProjects = $this->ProjectViva->EseProject->find('list');
		$students = $this->ProjectViva->Student->find('list');
		$monthYears = $this->ProjectViva->MonthYear->find('list');
		$this->set(compact('eseProjects', 'students', 'monthYears'));
	}

/**
 * edit method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function edit($id = null) {
		if (!$this->ProjectViva->exists($id)) {
			throw new NotFoundException(__('Invalid project viva'));
		}
		if ($this->request->is(array('post', 'put'))) {
			if ($this->ProjectViva->save($this->request->data)) {
				$this->Flash->success(__('The project viva has been saved.'));
				return $this->redirect(array('action' => 'index'));
			} else {
				$this->Flash->error(__('The project viva could not be saved. Please, try again.'));
			}
		} else {
			$options = array('conditions' => array('ProjectViva.' . $this->ProjectViva->primaryKey => $id));
			$this->request->data = $this->ProjectViva->find('first', $options);
		}
		$eseProjects = $this->ProjectViva->EseProject->find('list');
		$students = $this->ProjectViva->Student->find('list');
		$monthYears = $this->ProjectViva->MonthYear->find('list');
		$this->set(compact('eseProjects', 'students', 'monthYears'));
	}

/**
 * delete method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function delete($id = null) {
		$this->ProjectViva->id = $id;
		if (!$this->ProjectViva->exists()) {
			throw new NotFoundException(__('Invalid project viva'));
		}
		$this->request->allowMethod('post', 'delete');
		if ($this->ProjectViva->delete()) {
			$this->Flash->success(__('The project viva has been deleted.'));
		} else {
			$this->Flash->error(__('The project viva could not be deleted. Please, try again.'));
		}
		return $this->redirect(array('action' => 'index'));
	}
}
