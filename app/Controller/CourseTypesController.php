<?php
App::uses('AppController', 'Controller');
/**
 * CourseTypes Controller
 *
 * @property CourseType $CourseType
 * @property PaginatorComponent $Paginator
 * @property FlashComponent $Flash
 * @property SessionComponent $Session
 */
class CourseTypesController extends AppController {

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
		$access_result = $this->checkPathAccess($this->Auth->user('id'));
		if (!$access_result) {
			$results = $this->CourseType->find('all');
			$this->set('courseTypes', $results);
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
			if (!$this->CourseType->exists($id)) {
				throw new NotFoundException(__('Invalid course type'));
			}
			$options = array('conditions' => array('CourseType.' . $this->CourseType->primaryKey => $id));
			$this->set('courseType', $this->CourseType->find('first', $options));
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
				$this->request->data['CourseType']['created_by'] = $this->Auth->user('id');
				$this->CourseType->create();
				if ($this->CourseType->save($this->request->data)) {
					$this->Flash->success(__('The course type has been saved.'));
					echo "success";exit;
				} else {
					$this->Flash->error(__('The course type could not be saved. Please, try again.'));
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
			if (!$this->CourseType->exists($id)) {
				throw new NotFoundException(__('Invalid course type'));
			}
			if ($this->request->is(array('post', 'put'))) {
				$this->request->data['CourseType']['modified_by'] = $this->Auth->user('id');
				if ($this->CourseType->save($this->request->data)) {
					$this->Flash->success(__('The course type has been saved.'));
					echo "success";exit;
				} else {
					$this->Flash->error(__('The course type could not be saved. Please, try again.'));
				}
			} else {
				$options = array('conditions' => array('CourseType.' . $this->CourseType->primaryKey => $id));
				$this->request->data = $this->CourseType->find('first', $options);
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
			$this->CourseType->id = $id;
			if (!$this->CourseType->exists()) {
				throw new NotFoundException(__('Invalid course type'));
			}
			$this->request->allowMethod('post', 'delete');
			if ($this->CourseType->delete()) {
				$this->Flash->success(__('The course type has been deleted.'));
			} else {
				$this->Flash->error(__('The course type could not be deleted. Please, try again.'));
			}
			return $this->redirect(array('action' => 'index'));
		} else {
			$this->render('../Users/access_denied');
		}
	}

}
