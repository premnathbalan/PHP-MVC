<?php
App::uses('AppController', 'Controller');
/**
 * UserRoles Controller
 *
 * @property UserRole $UserRole
 * @property PaginatorComponent $Paginator
 * @property FlashComponent $Flash
 * @property SessionComponent $Session
 */
class UserRolesController extends AppController {

/**
 * Components
 *
 * @var array
 */
	public $components = array('Paginator', 'Flash', 'Session');	public $uses = array("UserRole", "UsersPath");

/**
 * index method
 *
 * @return void
 */
	public function index() {		$access_result = $this->checkPathAccess($this->Auth->user('id'));		if (!$access_result) {
			$this->UserRole->recursive = 0;			$this->set('userRoles', $this->Paginator->paginate());		} else {			$this->render('../Users/access_denied');		}
	}

/**
 * view method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function view($id = null) {				$access_result = $this->checkPathAccess($this->Auth->user('id'));		if (!$access_result) {			if (!$this->UserRole->exists($id)) {				throw new NotFoundException(__('Invalid user role'));			}			$options = array('conditions' => array('UserRole.' . $this->UserRole->primaryKey => $id));			$this->set('userRole', $this->UserRole->find('first', $options));		} else {			$this->render('../Users/access_denied');		}		$this->layout=false;
	}

/**
 * add method
 *
 * @return void
 */
	public function add() {		$access_result = $this->checkPathAccess($this->Auth->user('id'));		if (!$access_result) {			if ($this->request->is('post')) {				$this->UserRole->create();				if ($this->UserRole->save($this->request->data)) {					$this->Flash->success(__('The user role has been saved.'));					return $this->redirect(array('action' => 'index'));				} else {					$this->Flash->error(__('The user role could not be saved. Please, try again.'));				}			}		} else {			$this->render('../Users/access_denied');		}		$this->layout=false;
	}

/**
 * edit method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function edit($id = null) {		$access_result = $this->checkPathAccess($this->Auth->user('id'));		if (!$access_result) {
			if (!$this->UserRole->exists($id)) {				throw new NotFoundException(__('Invalid user role'));			}			if ($this->request->is(array('post', 'put'))) {				if ($this->UserRole->save($this->request->data)) {					$this->Flash->success(__('The user role has been saved.'));					return $this->redirect(array('action' => 'index'));				} else {					$this->Flash->error(__('The user role could not be saved. Please, try again.'));				}			}			//PATH START			$pathGroups = array();			$pathsGroup =$this->UserRole->Path->find('all',					array('fields' => array('Path.cat,Path.subcat,Path.name,Path.id'),'recursive'=>1));			//pr($pathsGroup);						foreach ($pathsGroup as $group1){				$group = $group1['Path'];				if(!isset($pathGroups[$group['cat']])){					$pathGroups[$group['cat']] = array();				}				if(!isset($pathGroups[$group['cat']][$group['subcat']])){					$pathGroups[$group['cat']][$group['subcat']] = array();				}				$pathGroups[$group['cat']][$group['subcat']][$group['id']] = $group['name'];			}			//echo $id;			//pr($pathGroups);						$options = array('conditions' => array('UserRole.' . $this->UserRole->primaryKey => $id));			$this->request->data = $this->UserRole->find('first', $options);			//pr($this->request->data);						$getEditPath = $this->request->data['Path'];			foreach ($getEditPath as $mas){				$this->request->data['Path'][$mas['UserRolesPath']['path_id']] = $mas['UserRolesPath']['path_id'];			}			//pr($this->request->data);			//PATH END						$editId = $id;			$this->set(compact('pathGroups','editId'));		} else {			$this->render('../Users/access_denied');		}
	}

/**
 * delete method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function delete($id = null) {		$access_result = $this->checkPathAccess($this->Auth->user('id'));		if (!$access_result) {
			$this->UserRole->id = $id;			if (!$this->UserRole->exists()) {				throw new NotFoundException(__('Invalid user role'));			}			$this->request->allowMethod('post', 'delete');			if ($this->UserRole->delete()) {				$this->Flash->success(__('The user role has been deleted.'));			} else {				$this->Flash->error(__('The user role could not be deleted. Please, try again.'));			}			return $this->redirect(array('action' => 'index'));		} else {			$this->render('../Users/access_denied');		}
	}
}
