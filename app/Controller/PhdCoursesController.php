<?php
App::uses('AppController', 'Controller');
/**
 * PhdCourses Controller
 *
 * @property PhdCourse $PhdCourse
 * @property PaginatorComponent $Paginator
 * @property FlashComponent $Flash
 * @property SessionComponent $Session
 */
class PhdCoursesController extends AppController {

/**
 * Components
 *
 * @var array
 */
	public $components = array('Flash', 'Session');

/**
 * index method
 *
 * @return void
 */
	public function index() {
		/* $this->PhdCourse->recursive = 0;
		$this->set('phdCourses'); */
		$results = $this->PhdCourse->find('all', array(
				'conditions'=>array('PhdCourse.indicator'=>0),
				'contain'=>array(
						'User'=>array('fields'=>array('User.*')),
						'ModifiedUser'=>array('fields'=>array('ModifiedUser.*')),
				),
				'recursive'=>-1
		));
		$this->set('phdCourses', $results);
	}

/**
 * view method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function view($id = null) {
		if (!$this->PhdCourse->exists($id)) {
			throw new NotFoundException(__('Invalid phd course'));
		}
		$options = array('conditions' => array('PhdCourse.' . $this->PhdCourse->primaryKey => $id));
		$result = $this->PhdCourse->find('first', $options);
		//pr($result);
		$this->set('phdCourse', $result);
		//pr($phdCourse);
		$this->layout=false;
	}

/**
 * add method
 *
 * @return void
 */
	public function add() {
		if ($this->request->is('post')) {
			$this->PhdCourse->create();
			if ($this->PhdCourse->save($this->request->data)) {
				$this->Flash->success(__('The phd course has been saved.'));
				return $this->redirect(array('action' => 'index'));
			} else {
				$this->Flash->error(__('The phd course could not be saved. Please, try again.'));
			}
			$this->layout=false;
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
		if (!$this->PhdCourse->exists($id)) {
			throw new NotFoundException(__('Invalid phd course'));
		}
		if ($this->request->is(array('post', 'put'))) {
			if ($this->PhdCourse->save($this->request->data)) {
				$this->Flash->success(__('The phd course has been saved.'));
				return $this->redirect(array('action' => 'index'));
			} else {
				$this->Flash->error(__('The phd course could not be saved. Please, try again.'));
			}
		} else {
			$options = array('conditions' => array('PhdCourse.' . $this->PhdCourse->primaryKey => $id));
			$this->request->data = $this->PhdCourse->find('first', $options);
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
		$this->PhdCourse->id = $id;
		if (!$this->PhdCourse->exists()) {
			throw new NotFoundException(__('Invalid phd course'));
		}
		//$this->request->allowMethod('post', 'delete');
		/* if ($this->PhdCourse->delete()) {
			$this->Flash->success(__('The phd course has been deleted.'));
		} else {
			$this->Flash->error(__('The phd course could not be deleted. Please, try again.'));
		} */
		//return $this->redirect(array('action' => 'index'));
		
		
		
		//$this->request->allowMethod('post', 'delete');
		$this->PhdCourse->updateAll(
				/* UPDATE FIELD */
				array(
						"PhdCourse.indicator" => 1,
				),
				/* CONDITIONS */
				array(
						"PhdCourse.id" => $id
				)
				);
		return $this->redirect(array('action' => 'index'));
		
	}
}
