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
	} */
	public function index() {
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
	public function view($id = null) {
			$options = array('conditions' => array('Batch.' . $this->Batch->primaryKey => $id));
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
		}
			if ($this->request->is('post')) {
				$this->Batch->create();
				$this->request->data['Batch']['created_by'] = $this->Auth->user('id');
				if($this->request->data['Batch']['consolidated_pub_date']){ 
					$this->request->data['Batch']['consolidated_pub_date'] = date("Y-m-d", strtotime($this->request->data['Batch']['consolidated_pub_date']));
				if ($this->Batch->save($this->request->data)) {
					$this->Flash->success(__('The batch has been saved.'));
					echo "success";exit;
					if($this->request->data['Batch']['consolidated_pub_date'] != '0000-00-00'){
						$this->request->data['Batch']['consolidated_pub_date'] = date("d-m-Y", strtotime($this->request->data['Batch']['consolidated_pub_date']));
				}
			}
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
		$years=array();
					$this->Flash->success(__('The batch has been saved.'));
					echo "success";exit;
				} else {
					if($this->request->data['Batch']['consolidated_pub_date']){
						$this->request->data['Batch']['consolidated_pub_date'] = date("d-m-Y", strtotime($this->request->data['Batch']['consolidated_pub_date']));
					$this->Flash->error(__($errors.'The batch could not be saved. Please, try again.'));

/**
 * delete method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function delete($id = null) {
			$this->Batch->id = $id;
	}
	
	public function deleteBatch($id = NULL) {
}