<?php
App::uses('AppController', 'Controller');
/**
 * TypeOfCertifications Controller
 *
 * @property TypeOfCertification $TypeOfCertification
 * @property PaginatorComponent $Paginator
 * @property FlashComponent $Flash
 * @property SessionComponent $Session
 */
class TypeOfCertificationsController extends AppController {

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
		$access_result = $this->checkPathAccess($this->Auth->user('id'));
		if (!$access_result) {
			$this->TypeOfCertification->recursive = 0;
			$this->set('typeOfCertifications', $this->Paginator->paginate());
		} else {
			$this->render('../Users/access_denied');
		}
	}

/**
 * view method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function view($id = null) {	
		$access_result = $this->checkPathAccess($this->Auth->user('id'));
		if (!$access_result) {
			if (!$this->TypeOfCertification->exists($id)) {
				throw new NotFoundException(__('Invalid type of certification'));
			}
			$options = array('conditions' => array('TypeOfCertification.' . $this->TypeOfCertification->primaryKey => $id));
			$this->set('typeOfCertification', $this->TypeOfCertification->find('first', $options));
		} else {
			$this->render('../Users/access_denied');
		}
		$this->layout=false;
	}

/**
 * add method
 *
 * @return void
 */
	public function add() {
		$access_result = $this->checkPathAccess($this->Auth->user('id'));
		if (!$access_result) {
			if ($this->request->is('post')) {
				$this->TypeOfCertification->create();
				if ($this->TypeOfCertification->save($this->request->data)) {
					$this->Flash->success(__('The type of certification has been saved.'));
					echo "success";exit;
				} else {
					$this->Flash->error(__('The type of certification could not be saved. Please, try again.'));
				}
			}
		} else {
			$this->render('../Users/access_denied');
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
		$access_result = $this->checkPathAccess($this->Auth->user('id'));
		if (!$access_result) {
			if (!$this->TypeOfCertification->exists($id)) {
				throw new NotFoundException(__('Invalid type of certification'));
			}
			if ($this->request->is(array('post', 'put'))) {
				if ($this->TypeOfCertification->save($this->request->data)) {
					$this->Flash->success(__('The type of certification has been saved.'));
					echo "success";exit;
				} else {
					$this->Flash->error(__('The type of certification could not be saved. Please, try again.'));
				}
			} else {
				$options = array('conditions' => array('TypeOfCertification.' . $this->TypeOfCertification->primaryKey => $id));
				$this->request->data = $this->TypeOfCertification->find('first', $options);
			}
		} else {
			$this->render('../Users/access_denied');
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
		$access_result = $this->checkPathAccess($this->Auth->user('id'));
		if (!$access_result) {
			$this->TypeOfCertification->id = $id;
			if (!$this->TypeOfCertification->exists()) {
				throw new NotFoundException(__('Invalid type of certification'));
			}
			$this->request->allowMethod('post', 'delete');
			if ($this->TypeOfCertification->delete()) {
				$this->Flash->success(__('The type of certification has been deleted.'));
			} else {
				$this->Flash->error(__('The type of certification could not be deleted. Please, try again.'));
			}
			return $this->redirect(array('action' => 'index'));
		} else {
			$this->render('../Users/access_denied');
		}
	}
}
