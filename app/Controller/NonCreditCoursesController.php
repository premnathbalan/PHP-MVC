<?php
App::uses('AppController', 'Controller');/** * NonCreditCourses Controller * * @property NonCreditCourse $NonCreditCourse * @property PaginatorComponent $Paginator * @property FlashComponent $Flash * @property SessionComponent $Session */
class NonCreditCoursesController extends AppController {
/** * Components * * @var array */
	public $components = array('Paginator', 'Flash', 'Session');
/** * index method * * @return void */
	public function index() {		$access_result = $this->checkPathAccess($this->Auth->user('id'));		if (!$access_result) {
			$results = $this->NonCreditCourse->find('all');			$this->set('nonCreditCourses', $results);		} else {			$this->render('../Users/access_denied');		}
	}
/** * view method * * @throws NotFoundException * @param string $id * @return void */
	public function view($id = null) {		$access_result = $this->checkPathAccess($this->Auth->user('id'));		if (!$access_result) {
			if (!$this->NonCreditCourse->exists($id)) {				throw new NotFoundException(__('Invalid non credit course'));			}			$options = array('conditions' => array('NonCreditCourse.' . $this->NonCreditCourse->primaryKey => $id));			$this->set('nonCreditCourse', $this->NonCreditCourse->find('first', $options));		} else {			$this->render('../Users/access_denied');		}		$this->layout=false;
	}
/** * add method * * @return void */
	public function add() {		$access_result = $this->checkPathAccess($this->Auth->user('id'));		if (!$access_result) {
			if ($this->request->is('post')) {				$this->NonCreditCourse->create();				$this->request->data['NonCreditCourse']['created_by']=$this->Auth->user('id');				if ($this->NonCreditCourse->save($this->request->data)) {					$this->Flash->success(__('The non credit course has been saved.'));					echo "success";exit;				} else {					$this->Flash->error(__('The non credit course could not be saved. Please, try again.'));				}			}		} else {			$this->render('../Users/access_denied');		}		$this->layout=false;
	}
/** * edit method * * @throws NotFoundException * @param string $id * @return void */
	public function edit($id = null) {		$access_result = $this->checkPathAccess($this->Auth->user('id'));		if (!$access_result) {
			if (!$this->NonCreditCourse->exists($id)) {				throw new NotFoundException(__('Invalid non credit course'));			}			$this->request->data['NonCreditCourse']['modified_by']=$this->Auth->user('id');			if ($this->request->is(array('post', 'put'))) {				if ($this->NonCreditCourse->save($this->request->data)) {					$this->Flash->success(__('The non credit course has been saved.'));					echo "success";exit;				} else {					$this->Flash->error(__('The non credit course could not be saved. Please, try again.'));				}			} else {				$options = array('conditions' => array('NonCreditCourse.' . $this->NonCreditCourse->primaryKey => $id));				$this->request->data = $this->NonCreditCourse->find('first', $options);						}		} else {			$this->render('../Users/access_denied');		}
		$this->layout=false;
	}
/** * delete method * * @throws NotFoundException * @param string $id * @return void */
	public function delete($id = null) {		$access_result = $this->checkPathAccess($this->Auth->user('id'));		if (!$access_result) {
			$this->NonCreditCourse->id = $id;			if (!$this->NonCreditCourse->exists()) {				throw new NotFoundException(__('Invalid non credit course'));			}			$this->request->allowMethod('post', 'delete');			if ($this->NonCreditCourse->delete()) {				$this->Flash->success(__('The non credit course has been deleted.'));			} else {				$this->Flash->error(__('The non credit course could not be deleted. Please, try again.'));			}			return $this->redirect(array('action' => 'index'));		} else {			$this->render('../Users/access_denied');		}	}
}
