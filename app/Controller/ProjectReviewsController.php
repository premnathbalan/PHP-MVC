<?php
App::uses('AppController', 'Controller');
/**
 * ProjectReviews Controller
 *
 * @property ProjectReview $ProjectReview
 * @property PaginatorComponent $Paginator
 * @property FlashComponent $Flash
 * @property SessionComponent $Session
 */
class ProjectReviewsController extends AppController {

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
		$this->ProjectReview->recursive = 0;
		$this->set('projectReviews', $this->Paginator->paginate());
	}

/**
 * view method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function view($id = null) {
		if (!$this->ProjectReview->exists($id)) {
			throw new NotFoundException(__('Invalid project review'));
		}
		$options = array('conditions' => array('ProjectReview.' . $this->ProjectReview->primaryKey => $id));
		$this->set('projectReview', $this->ProjectReview->find('first', $options));
	}

/**
 * add method
 *
 * @return void
 */
	public function add() {
		if ($this->request->is('post')) {
			$this->ProjectReview->create();
			if ($this->ProjectReview->save($this->request->data)) {
				$this->Flash->success(__('The project review has been saved.'));
				return $this->redirect(array('action' => 'index'));
			} else {
				$this->Flash->error(__('The project review could not be saved. Please, try again.'));
			}
		}
		$caeProjects = $this->ProjectReview->CaeProject->find('list');
		$students = $this->ProjectReview->Student->find('list');
		$monthYears = $this->ProjectReview->MonthYear->find('list');
		$this->set(compact('caeProjects', 'students', 'monthYears'));
	}

/**
 * edit method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function edit($id = null) {
		if (!$this->ProjectReview->exists($id)) {
			throw new NotFoundException(__('Invalid project review'));
		}
		if ($this->request->is(array('post', 'put'))) {
			if ($this->ProjectReview->save($this->request->data)) {
				$this->Flash->success(__('The project review has been saved.'));
				return $this->redirect(array('action' => 'index'));
			} else {
				$this->Flash->error(__('The project review could not be saved. Please, try again.'));
			}
		} else {
			$options = array('conditions' => array('ProjectReview.' . $this->ProjectReview->primaryKey => $id));
			$this->request->data = $this->ProjectReview->find('first', $options);
		}
		$caeProjects = $this->ProjectReview->CaeProject->find('list');
		$students = $this->ProjectReview->Student->find('list');
		$monthYears = $this->ProjectReview->MonthYear->find('list');
		$this->set(compact('caeProjects', 'students', 'monthYears'));
	}

/**
 * delete method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function delete($id = null) {
		$this->ProjectReview->id = $id;
		if (!$this->ProjectReview->exists()) {
			throw new NotFoundException(__('Invalid project review'));
		}
		$this->request->allowMethod('post', 'delete');
		if ($this->ProjectReview->delete()) {
			$this->Flash->success(__('The project review has been deleted.'));
		} else {
			$this->Flash->error(__('The project review could not be deleted. Please, try again.'));
		}
		return $this->redirect(array('action' => 'index'));
	}
}
