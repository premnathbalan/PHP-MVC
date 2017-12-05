<?php
App::uses('AppController', 'Controller');
/**
 * Signatures Controller
 *
 * @property Signature $Signature
 * @property PaginatorComponent $Paginator
 * @property FlashComponent $Flash
 * @property SessionComponent $Session
 */
class SignaturesController extends AppController {

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
			$results = $this->Signature->find('all',array('recursive'=>0));
	}

/**
 * view method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function view($id = null) {
			if (!$this->Signature->exists($id)) {
			$options = array('conditions' => array('Signature.' . $this->Signature->primaryKey => $id));
	}

/**
 * add method
 *
 * @return void
 */
	public function add() {
			if ($this->request->is('post')) {
				$this->Signature->create();
				$this->request->data['Signature']['created_by'] = $this->Auth->user('id');
				$file = $this->request->data['Signature']['signature']; //put the data into a var for easy use
		$this->layout=false;	

/**
 * edit method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function edit($id = null) {
			if (!$this->Signature->exists($id)) {
			if ($this->request->is(array('post', 'put'))) {
				$this->request->data['Signature']['modified_by'] = $this->Auth->user('id');			
					$file = $this->request->data['Signature']['signature']; //put the data into a var for easy use
					$ext = substr(strtolower(strrchr($file['name'], '.')), 1); //get the extension
					$arr_ext = array('jpg', 'jpeg', 'gif', 'png', 'bmp'); //set allowed extensions
						$this->request->data['Signature']['signature'] = $file['name'];					
						$this->Flash->error(__('The signature image format not supported. Please, try again.'));
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
			$this->Signature->id = $id;
	}
}