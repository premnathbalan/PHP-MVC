<?php
App::uses('AppController', 'Controller');
/**
 * Departments Controller
 *
 * @property Department $Department
 * @property PaginatorComponent $Paginator
 * @property FlashComponent $Flash
 * @property SessionComponent $Session
 */
class DepartmentsController extends AppController {

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
		//$this->Department->recursive = 0;
		//$this->set('departments', $this->Paginator->paginate());
			$results = $this->Department->find('all');
	}

/**
 * view method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function view($id = null) {
			if (!$this->Department->exists($id)) {
		$this->layout=false;
	}

/**
 * add method
 *
 * @return void
 */
	public function add(){	
			if ($this->request->is('post')) { 
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
			if (!$this->Department->exists($id)) {
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
		$this->Department->id = $id;
	}

}