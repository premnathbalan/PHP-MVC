<?php
App::uses('AppController', 'Controller');
/**
 * Batches Controller
 *
 * @property Batch $Batch
 * @property PaginatorComponent $Paginator
 * @property FlashComponent $Flash
 * @property SessionComponent $Session
 */
class BatchesController extends AppController {

/**
 * Components
 *
 * @var array
 */
	public $components = array('Paginator', 'Flash', 'Session', 'RequestHandler');
	
	//public $uses = array("Student"/*, "BatchMode", "Batch"*/, "Program"/* , "MonthYear", "StudentType" */);
/**
 * index method
 *
 * @return void
 */
	/* public function index() {
		//$this->Batch->recursive = 0;
		//$this->set('batches', $this->Paginator->paginate());
		$results = $this->Batch->find('all');
		$this->set('batches', $results);
	} */	//For JSON / XML example
	public function index() {		//pr($this->request->data['User']);		$access_result = $this->checkPathAccess($this->Auth->user('id'));		if (!$access_result) {	        $batches = $this->Batch->find('all',array('recursive'=>0));	        $this->set(array(	            'batches' => $batches,	            '_serialize' => array('batches')	        ));        } else {        	$this->render('../Users/access_denied');        }    }
	public function findBatchByBatchMode($id = null) {
		//'CONCAT_WS(" - ", Batch.batch_from, Batch.batch_to) As batch_period'
		$options = array('conditions' => array('Batch.batch_mode_id'=> $id), 'fields' => array('Batch.batch_period'/* ,'Batch.batch_to' */));
		//$options = array('conditions' => array('Batch.batch_mode_id'=> $id), 'fields' => array('CONCAT_WS(" - ", Batch.batch_from, Batch.batch_to) As batch_period'));
		//pr($options);
		$batches =$this->Batch->find('all', $options); 
		$this->set('batches', $batches);
		//pr($batches);
		$this->layout=false;
	}

/**
 * view method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function view($id = null) {		if (!$this->Batch->exists($id)) {			throw new NotFoundException(__('Invalid batch'));		}		$access_result = $this->checkPathAccess($this->Auth->user('id'));		if (!$access_result) {
			$options = array('conditions' => array('Batch.' . $this->Batch->primaryKey => $id));			$this->set('batch', $this->Batch->find('first', $options));			$this->layout=false;		} else {			$this->render('../Users/access_denied');		}
	}

/**
 * add method
 *
 * @return void
 */
	public function add() {		
		$years=array();
		for ($i=2015; $i<=2035; $i++) {
			$years[$i]=$i;
		}		$access_result = $this->checkPathAccess($this->Auth->user('id'));		if (!$access_result) {
			if ($this->request->is('post')) {
				$this->Batch->create();
				$this->request->data['Batch']['created_by'] = $this->Auth->user('id');
				if($this->request->data['Batch']['consolidated_pub_date']){ 
					$this->request->data['Batch']['consolidated_pub_date'] = date("Y-m-d", strtotime($this->request->data['Batch']['consolidated_pub_date']));				}	
				if ($this->Batch->save($this->request->data)) {
					$this->Flash->success(__('The batch has been saved.'));
					echo "success";exit;				} else {
					if($this->request->data['Batch']['consolidated_pub_date'] != '0000-00-00'){
						$this->request->data['Batch']['consolidated_pub_date'] = date("d-m-Y", strtotime($this->request->data['Batch']['consolidated_pub_date']));					}									$errors ="";					if(isset($this->Batch->validationErrors['consolidated_pub_date'][0])){						$errors .= $this->Batch->validationErrors['consolidated_pub_date'][0].". ";					}					$this->Flash->error(__($errors.'The batch could not be saved. Please, try again.'));
				}
			}		} else {			$this->render('../Users/access_denied');		}
		$this->set(compact(/* 'batchModes', */ 'years'));
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
		$years=array();		for ($i=2015; $i<=2035; $i++) {			$years[$i]=$i;		}		if (!$this->Batch->exists($id)) {			throw new NotFoundException(__('Invalid batch'));		}		$access_result = $this->checkPathAccess($this->Auth->user('id'));		if (!$access_result) {			if ($this->request->is(array('post', 'put'))) {				$this->request->data['Batch']['modified_by'] = $this->Auth->user('id');				if($this->request->data['Batch']['consolidated_pub_date']){					$this->request->data['Batch']['consolidated_pub_date'] = date("Y-m-d", strtotime($this->request->data['Batch']['consolidated_pub_date']));				}							if ($this->Batch->save($this->request->data)) {
					$this->Flash->success(__('The batch has been saved.'));
					echo "success";exit;
				} else {
					if($this->request->data['Batch']['consolidated_pub_date']){
						$this->request->data['Batch']['consolidated_pub_date'] = date("d-m-Y", strtotime($this->request->data['Batch']['consolidated_pub_date']));					}					$errors ="";										if(isset($this->Batch->validationErrors['consolidated_pub_date'][0])){						$errors .= $this->Batch->validationErrors['consolidated_pub_date'][0].". ";					}
					$this->Flash->error(__($errors.'The batch could not be saved. Please, try again.'));				}			} else {				$options = array('conditions' => array('Batch.' . $this->Batch->primaryKey => $id));				$this->request->data = $this->Batch->find('first', $options);			}			if($this->request->data['Batch']['consolidated_pub_date'] != '0000-00-00'){				$this->request->data['Batch']['consolidated_pub_date'] = date("d-m-Y", strtotime($this->request->data['Batch']['consolidated_pub_date']));			}			if($this->request->data['Batch']['consolidated_pub_date'] == '0000-00-00'){				$this->request->data['Batch']['consolidated_pub_date'] = "";			}			} else {				$this->render('../Users/access_denied');			}		$this->set(compact('years'));		$this->layout=false;			}

/**
 * delete method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function delete($id = null) {		$access_result = $this->checkPathAccess($this->Auth->user('id'));		if (!$access_result) {
			$this->Batch->id = $id;			if (!$this->Batch->exists()) {				throw new NotFoundException(__('Invalid batch'));			}			$this->request->allowMethod('post', 'delete');			if ($this->Batch->delete()) {				$this->Flash->success(__('The Batch has been deleted.'));			} else {				$this->Flash->error(__('The Batch Could not be Deleted.'));			}			return $this->redirect(array('action' => 'index'));		} else {			$this->render('../Users/access_denied');		}
	}
	
	public function deleteBatch($id = NULL) {		$this->Batch->id = $id;		if (!$this->Batch->exists()) {			throw new NotFoundException(__('Invalid batch'));		} 		$batchExists = $this->Student->Academic->find('count', array(        'conditions' => array('Academic.batch_id' => $this->Batch->id)    	));		$this->set(compact('batchModes', 'years'));	}
}
