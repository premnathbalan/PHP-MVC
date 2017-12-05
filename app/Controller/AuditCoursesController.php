<?php
App::uses('AppController', 'Controller');
/**
 * AuditCourses Controller
 *
 * @property AuditCourse $AuditCourse
 * @property PaginatorComponent $Paginator
 * @property FlashComponent $Flash
 * @property SessionComponent $Session
 */
class AuditCoursesController extends AppController {

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
		$result = $this->checkPathAccess($this->Auth->user('id'));
		if (!$result) {
			$this->AuditCourse->recursive = 0;
			$this->set('auditCourses', $this->Paginator->paginate());
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
		$access_result = $this->checkPathAccess($this->Auth->user('id'));
		if (!$access_result) {
			if (!$this->AuditCourse->exists($id)) {
				throw new NotFoundException(__('Invalid audit course'));
			}
			$options = array('conditions' => array('AuditCourse.' . $this->AuditCourse->primaryKey => $id));
			$this->set('auditCourse', $this->AuditCourse->find('first', $options));
		} else {
			$this->render('../Users/access_denied');
		}
		$this->layout = false;
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
				$this->AuditCourse->create();
				if ($this->AuditCourse->save($this->request->data)) {
					$this->Flash->success(__('The audit course has been saved.'));
					return $this->redirect(array('action' => 'index'));
				} else {
					$this->Flash->error(__('The audit course could not be saved. Please, try again.'));
				}
			}
		} else {
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
			if (!$this->AuditCourse->exists($id)) {
				throw new NotFoundException(__('Invalid audit course'));
			}
			if ($this->request->is(array('post', 'put'))) {
				if ($this->AuditCourse->save($this->request->data)) {
					$this->Flash->success(__('The audit course has been saved.'));
					return $this->redirect(array('action' => 'index'));
				} else {
					$this->Flash->error(__('The audit course could not be saved. Please, try again.'));
				}
			} else {
				$options = array('conditions' => array('AuditCourse.' . $this->AuditCourse->primaryKey => $id));
				$this->request->data = $this->AuditCourse->find('first', $options);
			}
		} else {
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
			$this->AuditCourse->id = $id;
			if (!$this->AuditCourse->exists()) {
				throw new NotFoundException(__('Invalid audit course'));
			}
			$this->request->allowMethod('post', 'delete');
			if ($this->AuditCourse->delete()) {
				$this->Flash->success(__('The audit course has been deleted.'));
			} else {
				$this->Flash->error(__('The audit course could not be deleted. Please, try again.'));
			}
			return $this->redirect(array('action' => 'index'));
		} else {
			$this->render('../Users/access_denied');
		}
	}
}
