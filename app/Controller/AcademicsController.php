<?php
App::uses('AppController', 'Controller');
/**
 * Academics Controller
 *
 * @property Academic $Academic
 * @property PaginatorComponent $Paginator
 * @property FlashComponent $Flash
 * @property SessionComponent $Session
 */
class AcademicsController extends AppController {

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
			$results = $this->Academic->find('all', array(
			));
	}

/**
 * view method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function view($id = null) {
			if (!$this->Academic->exists($id)) {

/**
 * add method
 *
 * @return void
 */
	public function add() {

/**
 * edit method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function edit($id = null) {
			if (!$this->Academic->exists($id)) {

/**
 * delete method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function delete($id = null) {
			$this->Academic->id = $id;
	}
}