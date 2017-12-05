<?php
App::uses('AppController', 'Controller');
/**
 * StudentWithhelds Controller
 *
 * @property StudentWithheld $StudentWithheld
 * @property PaginatorComponent $Paginator
 * @property FlashComponent $Flash
 */
class StudentWithheldsController extends AppController {

/**
 * Components
 *
 * @var array
 */
	public $components = array('Paginator', 'Flash');
	public $uses = array("StudentWithheld","MonthYear","Student");

/**
 * index method
 *
 * @return void
 */
	public function index($examMonthYear = null) {
		$access_result = $this->checkPathAccess($this->Auth->user('id'));
		if (!$access_result) {
			if ($this->request->is('post')) {
				if($this->request->data['StudentWithheld']['monthyears']){
					$WithheldStuArray = $this->StudentWithheld->find('all', array(
							'conditions' => array('StudentWithheld.month_year_id'=>$this->request->data['StudentWithheld']['monthyears']),
							'fields' =>array('StudentWithheld.withheld_id','StudentWithheld.student_id'),
							'contain'=>array(
								'Student'=>array('fields' =>array('Student.id','Student.registration_number')),
							),
							'recursive'=>0
					));				
					$this->publishWithHeldStudentData($WithheldStuArray);
				}
			}
			
			$monthyears = $this->MonthYear->getAllMonthYears();
			$this->set(compact('monthyears'));
		}
		else {
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
		if (!$this->StudentWithheld->exists($id)) {
			throw new NotFoundException(__('Invalid student withheld'));
		}
		$options = array('conditions' => array('StudentWithheld.' . $this->StudentWithheld->primaryKey => $id));
		$this->set('studentWithheld', $this->StudentWithheld->find('first', $options));
	}

/**
 * add method
 *
 * @return void
 */
	public function add() {
		if ($this->request->is('post')) {
			$this->StudentWithheld->create();
			if ($this->StudentWithheld->save($this->request->data)) {
				return $this->flash(__('The student withheld has been saved.'), array('action' => 'index'));
			}
		}
		$students = $this->StudentWithheld->Student->find('list');
		$withhelds = $this->StudentWithheld->Withheld->find('list');
		$this->set(compact('students', 'withhelds'));
	}

/**
 * edit method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function edit($id = null) {
		if (!$this->StudentWithheld->exists($id)) {
			throw new NotFoundException(__('Invalid student withheld'));
		}
		if ($this->request->is(array('post', 'put'))) {
			if ($this->StudentWithheld->save($this->request->data)) {
				return $this->flash(__('The student withheld has been saved.'), array('action' => 'index'));
			}
		} else {
			$options = array('conditions' => array('StudentWithheld.' . $this->StudentWithheld->primaryKey => $id));
			$this->request->data = $this->StudentWithheld->find('first', $options);
		}
		$students = $this->StudentWithheld->Student->find('list');
		$withhelds = $this->StudentWithheld->Withheld->find('list');
		$this->set(compact('students', 'withhelds'));
	}

/**
 * delete method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function delete($id = null) {
		$this->StudentWithheld->id = $id;
		if (!$this->StudentWithheld->exists()) {
			throw new NotFoundException(__('Invalid student withheld'));
		}
		$this->request->allowMethod('post', 'delete');
		if ($this->StudentWithheld->delete()) {
			return $this->flash(__('The student withheld has been deleted.'), array('action' => 'index'));
		} else {
			return $this->flash(__('The student withheld could not be deleted. Please, try again.'), array('action' => 'index'));
		}
	}

}
