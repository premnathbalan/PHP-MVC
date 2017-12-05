<?php
App::uses('AppController', 'Controller');
/**
 * PhdCourseStudentMappings Controller
 *
 * @property PhdCourseStudentMapping $PhdCourseStudentMapping
 * @property PaginatorComponent $Paginator
 */
class PhdCourseStudentMappingsController extends AppController {

/**
 * Components
 *
 * @var array
 */
	public $components = array('Paginator');

/**
 * index method
 *
 * @return void
 */
	public function index() {
		$this->PhdCourseStudentMapping->recursive = 0;
		$this->set('phdCourseStudentMappings', $this->Paginator->paginate());
	}

/**
 * view method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function view($id = null) {
		if (!$this->PhdCourseStudentMapping->exists($id)) {
			throw new NotFoundException(__('Invalid phd course student mapping'));
		}
		$options = array('conditions' => array('PhdCourseStudentMapping.' . $this->PhdCourseStudentMapping->primaryKey => $id));
		$this->set('phdCourseStudentMapping', $this->PhdCourseStudentMapping->find('first', $options));
	}

/**
 * add method
 *
 * @return void
 */
	public function add() {
		if ($this->request->is('post')) {
			$this->PhdCourseStudentMapping->create();
			if ($this->PhdCourseStudentMapping->save($this->request->data)) {
				$this->Flash->success(__('The phd course student mapping has been saved.'));
				return $this->redirect(array('action' => 'index'));
			} else {
				$this->Flash->error(__('The phd course student mapping could not be saved. Please, try again.'));
			}
		}
		$phdStudents = $this->PhdCourseStudentMapping->PhdStudent->find('list');
		$phdCourses = $this->PhdCourseStudentMapping->PhdCourse->find('list');
		$users = $this->PhdCourseStudentMapping->User->find('list');
		$modifiedUsers = $this->PhdCourseStudentMapping->ModifiedUser->find('list');
		$this->set(compact('phdStudents', 'phdCourses', 'users', 'modifiedUsers'));
	}

/**
 * edit method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function edit($id = null) {
		if (!$this->PhdCourseStudentMapping->exists($id)) {
			throw new NotFoundException(__('Invalid phd course student mapping'));
		}
		if ($this->request->is(array('post', 'put'))) {
			
			$this->PhdCourseStudentMapping->updateAll(
					/* UPDATE FIELD */
					array(
							"PhdCourseStudentMapping.indicator" => 1,
					),
					/* CONDITIONS */
					array(
							"PhdCourseStudentMapping.id" => $id
					)
					);
			$this->Flash->success(__('The phd course student mapping has been saved.'));
			/* if ($this->PhdCourseStudentMapping->save($this->request->data)) {
				$this->Flash->success(__('The phd course student mapping has been saved.'));
				return $this->redirect(array('action' => 'index'));
			} else {
				$this->Flash->error(__('The phd course student mapping could not be saved. Please, try again.'));
			} */
		} else {
			$options = array('conditions' => array('PhdCourseStudentMapping.' . $this->PhdCourseStudentMapping->primaryKey => $id));
			$this->request->data = $this->PhdCourseStudentMapping->find('first', $options);
		}
		$phdStudents = $this->PhdCourseStudentMapping->PhdStudent->find('list');
		$phdCourses = $this->PhdCourseStudentMapping->PhdCourse->find('list');
		$users = $this->PhdCourseStudentMapping->User->find('list');
		$modifiedUsers = $this->PhdCourseStudentMapping->ModifiedUser->find('list');
		$this->set(compact('phdStudents', 'phdCourses', 'users', 'modifiedUsers'));
	}

/**
 * delete method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function delete($id = null) {
		$this->PhdCourseStudentMapping->id = $id;
		if (!$this->PhdCourseStudentMapping->exists()) {
			throw new NotFoundException(__('Invalid phd course student mapping'));
		}
		$this->request->allowMethod('post', 'delete');
		if ($this->PhdCourseStudentMapping->delete()) {
			$this->Flash->success(__('The phd course student mapping has been deleted.'));
		} else {
			$this->Flash->error(__('The phd course student mapping could not be deleted. Please, try again.'));
		}
		return $this->redirect(array('action' => 'index'));
	}
}
