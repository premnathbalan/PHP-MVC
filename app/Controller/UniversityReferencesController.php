<?php
App::uses('AppController', 'Controller');
/**
 * UniversityReferences Controller
 *
 * @property UniversityReference $UniversityReference
 * @property PaginatorComponent $Paginator
 * @property FlashComponent $Flash
 * @property SessionComponent $Session
 */
class UniversityReferencesController extends AppController {

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
			$results = $this->UniversityReference->find('all');
	}

/**
 * view method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function view($id = null) {
			if (!$this->UniversityReference->exists($id)) {
		$this->layout=false;
	}

/**
 * add method
 *
 * @return void
 */
	public function add() {
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
			if (!$this->UniversityReference->exists($id)) {
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
			$this->UniversityReference->id = $id;
	}
}