<?php
App::uses('AppController', 'Controller');
/**
 * CollegeReferences Controller
 *
 * @property CollegeReference $CollegeReference
 * @property PaginatorComponent $Paginator
 * @property FlashComponent $Flash
 * @property SessionComponent $Session
 */
class CollegeReferencesController extends AppController {

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
			$this->CollegeReference->recursive = 0;
			$this->set('collegeReferences', $this->Paginator->paginate());
	}

/**
 * view method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function view($id = null) {
			if (!$this->CollegeReference->exists($id)) {
		$this->layout=false;
	}

/**
 * add method
 *
 * @return void
 */
	public function add() {
			if ($this->request->is('post')) {
	}

/**
 * edit method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function edit($id = null) {
			if (!$this->CollegeReference->exists($id)) {
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
			$this->CollegeReference->id = $id;
	}
}