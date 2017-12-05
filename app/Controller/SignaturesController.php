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
	public function index() {		$access_result = $this->checkPathAccess($this->Auth->user('id'));		if (!$access_result) {
			$results = $this->Signature->find('all',array('recursive'=>0));			$this->set('signatures', $results);		} else {			$this->render('../Users/access_denied');		}
	}

/**
 * view method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function view($id = null) {		$access_result = $this->checkPathAccess($this->Auth->user('id'));		if (!$access_result) {
			if (!$this->Signature->exists($id)) {				throw new NotFoundException(__('Invalid signature'));			}	
			$options = array('conditions' => array('Signature.' . $this->Signature->primaryKey => $id));			$signature = $this->Signature->find('first', $options);			$this->set('signature',$signature);		} else {			$this->render('../Users/access_denied');		}		$this->layout=false;
	}

/**
 * add method
 *
 * @return void
 */
	public function add() {		$access_result = $this->checkPathAccess($this->Auth->user('id'));		if (!$access_result) {
			if ($this->request->is('post')) {
				$this->Signature->create();
				$this->request->data['Signature']['created_by'] = $this->Auth->user('id');
				$file = $this->request->data['Signature']['signature']; //put the data into a var for easy use				$ext = substr(strtolower(strrchr($file['name'], '.')), 1); //get the extension				$arr_ext = array('jpg', 'jpeg', 'gif', 'png', 'bmp'); //set allowed extensions				if(in_array($ext, $arr_ext)) {									$this->request->data['Signature']['signature'] = $file['name'];									move_uploaded_file($file['tmp_name'], WWW_ROOT . 'img/certificate_signature/' . $file['name']);					if ($this->Signature->save($this->request->data)) {						$this->Flash->success(__('The signature has been updated.'));						return $this->redirect(array('action' => 'index'));					} else {						$this->Flash->error(__('The signature could not be saved. Please, try again.'));					}								} else {					$this->Flash->error(__('The signature image format not supported. Please, try again.'));					return $this->redirect(array('action' => 'index'));				}			}		} else {			$this->render('../Users/access_denied');		}
		$this->layout=false;		}

/**
 * edit method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function edit($id = null) {		$access_result = $this->checkPathAccess($this->Auth->user('id'));		if (!$access_result) {
			if (!$this->Signature->exists($id)) {				throw new NotFoundException(__('Invalid signature'));			}	
			if ($this->request->is(array('post', 'put'))) {
				$this->request->data['Signature']['modified_by'] = $this->Auth->user('id');											if($this->request->data['Signature']['signature']['name']) {
					$file = $this->request->data['Signature']['signature']; //put the data into a var for easy use
					$ext = substr(strtolower(strrchr($file['name'], '.')), 1); //get the extension
					$arr_ext = array('jpg', 'jpeg', 'gif', 'png', 'bmp'); //set allowed extensions					if(in_array($ext, $arr_ext)) {
						$this->request->data['Signature']['signature'] = $file['name'];											move_uploaded_file($file['tmp_name'], WWW_ROOT . 'img/certificate_signature/' . $file['name']);					} else {
						$this->Flash->error(__('The signature image format not supported. Please, try again.'));					}				}else {		            unset($this->request->data['Signature']['signature']);		        }		        if ($this->Signature->save($this->request->data)) {		        	$this->Flash->success(__('The signature has been updated.'));		        	return $this->redirect(array('action' => 'index'));		        } else {		        	$this->Flash->error(__('The signature could not be saved. Please, try again.'));		        }
			} 					$options = array('conditions' => array('Signature.' . $this->Signature->primaryKey => $id));			$this->request->data = $this->Signature->find('first', $options);			} else {			$this->render('../Users/access_denied');		}
		$this->layout=false;	
	}
/**
 * delete method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function delete($id = null) {		$access_result = $this->checkPathAccess($this->Auth->user('id'));		if (!$access_result) {
			$this->Signature->id = $id;			if (!$this->Signature->exists()) {				throw new NotFoundException(__('Invalid signature'));			}			$this->request->allowMethod('post', 'delete');			if ($this->Signature->delete()) {				$this->Flash->success(__('The signature has been deleted.'));			} else {				$this->Flash->error(__('The signature could not be deleted. Please, try again.'));			}			return $this->redirect(array('action' => 'index'));		} else {			$this->render('../Users/access_denied');		}
	}
}
