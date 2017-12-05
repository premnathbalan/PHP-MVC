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
	public function index(){
			$results = $this->Program->find('all');
	}
	
	public function findProgramByAcademic($id = null) {
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
		if (!$this->Program->exists($id)) {
			$options = array('conditions' => array('Program.' . $this->Program->primaryKey => $id));
		$this->layout=false;
	}

/**
 * add method
 *
 * @return void
 */
	public function add() {
			if ($this->request->is('post')) {
			$semesters = $this->Program->Semester->find('list');
			$academics = $this->Program->Academic->find('list');
			$this->set(compact('academics','semesters','faculty'));
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
			if (!$this->Program->exists($id)) {
			$this->set(compact('academics', 'programs','semesters'));
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
		$this->Program->id = $id;
			$this->request->allowMethod('post', 'delete');
		
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