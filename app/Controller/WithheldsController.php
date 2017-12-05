<?php
App::uses('AppController', 'Controller');
/**
 * Withhelds Controller
 *
 * @property Withheld $Withheld
 * @property PaginatorComponent $Paginator
 * @property FlashComponent $Flash
 * @property SessionComponent $Session
 */
class WithheldsController extends AppController {

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
	public function index() {		$access_result = $this->checkPathAccess($this->Auth->user('id'));		if (!$access_result) {			$this->Withheld->recursive = 0;			$this->set('withhelds', $this->Paginator->paginate());		} else {			$this->render('../Users/access_denied');		}
	}

/**
 * view method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function view($id = null) {		$access_result = $this->checkPathAccess($this->Auth->user('id'));		if (!$access_result) {
			if (!$this->Withheld->exists($id)) {				throw new NotFoundException(__('Invalid withheld'));			}			$options = array('conditions' => array('Withheld.' . $this->Withheld->primaryKey => $id));			$this->set('withheld', $this->Withheld->find('first', $options));		} else {			$this->render('../Users/access_denied');		}
		$this->layout=false;
	}

/**
 * add method
 *
 * @return void
 */
	public function add() {		$access_result = $this->checkPathAccess($this->Auth->user('id'));		if (!$access_result) {
			if ($this->request->is('post')) {				$this->Withheld->create();				$this->request->data['Withheld']['created_by']=$this->Auth->user('id');				if ($this->Withheld->save($this->request->data)) {					$this->Flash->success(__('The withheld has been saved.'));					return $this->redirect(array('action' => 'index'));				} else {					$this->Flash->error(__('The withheld could not be saved. Please, try again.'));				}			}		} else {			$this->render('../Users/access_denied');		}
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
			if (!$this->Withheld->exists($id)) {				throw new NotFoundException(__('Invalid withheld'));			}			$this->request->data['Withheld']['modified_by']=$this->Auth->user('id');			if ($this->request->is(array('post', 'put'))) {				if ($this->Withheld->save($this->request->data)) {					$this->Flash->success(__('The withheld has been saved.'));					return $this->redirect(array('action' => 'index'));				} else {					$this->Flash->error(__('The withheld could not be saved. Please, try again.'));				}			} else {				$options = array('conditions' => array('Withheld.' . $this->Withheld->primaryKey => $id));				$this->request->data = $this->Withheld->find('first', $options);			}		} else {			$this->render('../Users/access_denied');		}
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
			$this->Withheld->id = $id;			if (!$this->Withheld->exists()) {				throw new NotFoundException(__('Invalid withheld'));			}			$this->request->allowMethod('post', 'delete');			if ($this->Withheld->delete()) {				$this->Flash->success(__('The withheld has been deleted.'));			} else {				$this->Flash->error(__('The withheld could not be deleted. Please, try again.'));			}			return $this->redirect(array('action' => 'index'));		} else {			$this->render('../Users/access_denied');		}
	}		public function publishWithHeldType(){			$access_result = $this->checkPathAccess($this->Auth->user('id'));		if (!$access_result) {			$WithheldArray = $this->Withheld->find('all', array(					'conditions' => array(),					'fields' =>array('Withheld.id','Withheld.withheld_type'),					'contain'=>array(),					'recursive'=>0			));			$this->publishWithHeldTypeData($WithheldArray);		}		else {			$this->render('../Users/access_denied');		}	}		public function allData() {			$results = $this->Withheld->find('list', array(				'conditions' => array(),				'fields' =>array('Withheld.id','Withheld.withheld_type'),				'contain'=>array(),				'recursive'=>0		));		$this->set(compact('results'));		$this->layout=false;	}	
}
