<?php
App::uses('AppController', 'Controller');
/**
 * Programs Controller
 *
 * @property Program $Program
 * @property PaginatorComponent $Paginator
 * @property FlashComponent $Flash
 * @property SessionComponent $Session
 */
class ProgramsController extends AppController {

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
	public function index(){		$access_result = $this->checkPathAccess($this->Auth->user('id'));		if (!$access_result) {
			$results = $this->Program->find('all');			/* $results = $this->Program->find('all', array(					'fields'=>array('Program.program_name', 'Program.short_code', 'Program.program_name_tamil', 'Program.credits',							'Program.semester_id', 'Program.alternate_name', 'Program.academic_id', 'Program.faculty_id'					),					'contain'=>array(							'Academic'=>array(									'fields'=>array('Academic.academic_name')							),							'Faculty'=>array(									'fields'=>array('Faculty.faculty_name')							)					),					'recursive'=>0			)); */			//pr($results);			$this->set('programs', $results);		} else {			$this->render('../Users/access_denied');		}
	}
	
	public function findProgramByAcademic($id = null) {		$options = array('conditions' => array('Program.academic_id'=> $id), 'fields' => array('Program.program_name'));
		$programs = $this->set('programs', $this->Program->find('list', $options));
		$this->layout=false;
	}

/**
 * view method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function view($id = null) {
		if (!$this->Program->exists($id)) {			throw new NotFoundException(__('Invalid program'));		}		$access_result = $this->checkPathAccess($this->Auth->user('id'));		if (!$access_result) {
			$options = array('conditions' => array('Program.' . $this->Program->primaryKey => $id));			$program = $this->set('program', $this->Program->find('first', $options));						} else {			$this->render('../Users/access_denied');		}
		$this->layout=false;
	}

/**
 * add method
 *
 * @return void
 */
	public function add() {		$access_result = $this->checkPathAccess($this->Auth->user('id'));		if (!$access_result) {
			if ($this->request->is('post')) {				$this->Program->create();				$this->request->data['Program']['created_by'] = $this->Auth->user('id');				if ($this->Program->save($this->request->data)) {					$this->Flash->success(__('The program has been saved.'));					echo "success";exit;				} else {					$this->Flash->error(__('The program could not be saved. Please, try again.'));				}			}			$faculty = $this->Program->Faculty->find('list');		
			$semesters = $this->Program->Semester->find('list');
			$academics = $this->Program->Academic->find('list');
			$this->set(compact('academics','semesters','faculty'));		} else {			$this->render('../Users/access_denied');		}
		$this->layout=false;
	}

/**
 * edit method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function edit($id = null) {		$access_result = $this->checkPathAccess($this->Auth->user('id'));		if (!$access_result) {
			if (!$this->Program->exists($id)) {				throw new NotFoundException(__('Invalid program'));			}			if ($this->request->is(array('post', 'put'))) {				$this->request->data['Program']['modified_by'] = $this->Auth->user('id');				if ($this->Program->save($this->request->data)) {					$this->Flash->success(__('The program has been saved.'));					echo "success";exit;				} else {					$this->Flash->error(__('The program could not be saved. Please, try again.'));				}			} else {				$options = array('conditions' => array('Program.' . $this->Program->primaryKey => $id));				$this->request->data = $this->Program->find('first', $options);			}			$this->set('faculty', $this->Program->Faculty->find('list'));			$semesters = $this->Program->Semester->find('list');			$academics = $this->Program->Academic->find('list');			$programs = $this->Program->find('list');	
			$this->set(compact('academics', 'programs','semesters'));		} else {			$this->render('../Users/access_denied');		}
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
		$this->Program->id = $id;		if (!$this->Program->exists()) {			throw new NotFoundException(__('Invalid program'));		}		$access_result = $this->checkPathAccess($this->Auth->user('id'));		if (!$access_result) {
			$this->request->allowMethod('post', 'delete');			$courses = $this->Program->Course->find('count', array('conditions' => array('Course.program_id'=> $id)));			if ($courses > 0) {				$this->Flash->success(__('There are courses available for this program. The program could not be deleted.'));			}			else if ($this->Program->delete()) {				$this->Flash->success(__('The program has been deleted.'));			}			return $this->redirect(array("controller"=>"programs",'action' => 'index'));		} else {			$this->render('../Users/access_denied');		}
		
		/* 
		if ($this->Program->delete()) {
			$this->Flash->success(__('The program has been deleted.'));
		} else {
			$this->Flash->error(__('The program could not be deleted. Please, try again.'));
		}
		return $this->redirect(array('action' => 'index')); */
	}
	
	/* public function deleteProgram($id = NULL) {
		$this->Program->id = $id;
		if (!$this->Program->exists()) {
			throw new NotFoundException(__('Invalid program'));
		}
		$courses = $this->Program->Course->find('count', array('conditions' => array('Course.program_id'=> $id)));
		pr($courses);
		if ($courses > 0) {
			$this->Flash->error(__('There are courses available for this program. The program could not be deleted.'));
			return $this->redirect(array("controller"=>"programs",'action' => 'index'));
		}
		else if ($this->Program->delete()) {
			$this->Flash->success(__('The program has been deleted.'));
		}
	} */
	
}
