<?phpApp::uses('AppController', 'Controller');/** * Users Controller * * @property User $User * @property PaginatorComponent $Paginator * @property FlashComponent $Flash * @property SessionComponent $Session */class UsersController extends AppController {/** * Components * * @var array */	public $components = array('Paginator', 'Flash', 'Acl', 'Security',  'Session', 'RequestHandler');/** * index method * * @return void */	public function index() {				$access_result = $this->checkPathAccess($this->Auth->user('id'));		if (!$access_result) {			$this->User->recursive = 0;				$this->set('users', $this->Paginator->paginate());						// To remove			$pathGroups = array();			$pathsGroup =$this->User->Path->find('all',					array('fields' => array('Path.cat,Path.subcat,Path.name,Path.id'),'recursive'=>1));			//echo count($pathsGroup);			//pr($pathsGroup);						foreach ($pathsGroup as $group1){				$group = $group1['Path'];				if(!isset($pathGroups[$group['cat']])){					$pathGroups[$group['cat']] = array();				}				if(!isset($pathGroups[$group['cat']][$group['subcat']])){					$pathGroups[$group['cat']][$group['subcat']] = array();				}				$pathGroups[$group['cat']][$group['subcat']][$group['id']] = $group['name'];			}			//pr($pathGroups);			// To remove		} else {			$this->render('../Users/access_denied');		}	}/** * view method * * @throws NotFoundException * @param string $id * @return void */	public function view($id = null) {		$access_result = $this->checkPathAccess($this->Auth->user('id'));		if (!$access_result) {			if (!$this->User->exists($id)) {				throw new NotFoundException(__('Invalid user'));			}			$options = array('conditions' => array('User.' . $this->User->primaryKey => $id));			$this->set('user', $this->User->find('first', $options));			$this->set('viewUserId',$id);		} else {			$this->render('../Users/access_denied');		}		//$this->layout=false;	}/** * add method * * @return void */	public function add() {		$access_result = $this->checkPathAccess($this->Auth->user('id'));		if (!$access_result) {			if ($this->request->is('post')) {				$this->User->create();					$this->request->data['User']['created_by']=$this->Auth->user('id');				if ($this->User->save($this->request->data)) {					$this->Flash->success(__('The user has been saved.'));					return $this->redirect(array('action' => 'index'));					} else{									$this->Flash->error(__('The user could not be saved. Please, try again.'));				}			}			$departments = $this->User->Department->find('list');			$designations = $this->User->Designation->find('list');			$userRoles = $this->User->UserRole->find('list');			$this->set(compact('departments', 'designations', 'userRoles'));		} else {			$this->render('../Users/access_denied');		}		//$this->layout=false;	}/** * edit method * * @throws NotFoundException * @param string $id * @return void */	public function edit($id = null) {		$access_result = $this->checkPathAccess($this->Auth->user('id'));		if (!$access_result) {			if (!$this->User->exists($id)) {				throw new NotFoundException(__('Invalid user'));			}			$this->request->data['User']['modified_by']=$this->Auth->user('id');			if ($this->request->is(array('post', 'put'))) {				//pr($this->data); die;				//unset($this->request->data['Path'][0]);				if(!empty($this->request->data['User']['password'])){					$this->request->data['User']['password']= $this->request->data['User']['password'];				}else{					unset($this->request->data['User']['password']);				}				$paths = $this->request->data['Path'];				unset($this->request->data['Path']);				foreach ($paths as $key => $value) {					if ($value > 0) {						$this->request->data['Path'][$key] = $value;					}				}				if ($this->User->save($this->request->data)) {					$this->Flash->success(__('The user has been saved.'));					return $this->redirect(array('action' => 'index'));				} else {					$this->Flash->error(__('The user could not be saved. Please, try again.'));				}			} 				$departments = $this->User->Department->find('list');			$designations = $this->User->Designation->find('list');			$userRoles = $this->User->UserRole->find('list');			$this->set(compact('departments', 'designations', 'userRoles'));						//PATH START			$pathGroups = array();			$pathsGroup =$this->User->Path->find('all',					array('fields' => array('Path.cat,Path.subcat,Path.name,Path.id'),							'recursive'=>1,							'order'=>array('Path.subcat')					));			//pr($pathsGroup);						foreach ($pathsGroup as $group1){ 				$group = $group1['Path'];				if(!isset($pathGroups[$group['cat']])){					$pathGroups[$group['cat']] = array();				}				if(!isset($pathGroups[$group['cat']][$group['subcat']])){					$pathGroups[$group['cat']][$group['subcat']] = array();				}				$pathGroups[$group['cat']][$group['subcat']][$group['id']] = $group['name'];			}								$options = array('conditions' => array('User.' . $this->User->primaryKey => $id));			$this->request->data = $this->User->find('first', $options);			unset($this->request->data['User']['password']);						if($this->request->data['Path']){				$getEditPath = $this->request->data['Path'];				foreach ($getEditPath as $mas){		 						$this->request->data['Path'][$mas['UsersPath']['path_id']] = $mas['UsersPath']['path_id'];				}			}else{				$roleId = $this->request->data['User']['user_role_id'];								$privileges = array();				$options = array('conditions' => array('UserRole.' . $this->User->UserRole->primaryKey => $roleId));				$privileges = $this->User->UserRole->find('first', $options);								$getEditPath = $privileges['Path'];				foreach ($getEditPath as $mas){					$this->request->data['Path'][$mas['UserRolesPath']['path_id']] = $mas['UserRolesPath']['path_id'];				}			}			//PATH END						 $editUserId = $id;			 $this->set(compact('pathGroups','editUserId'));		 } else {		 	$this->render('../Users/access_denied');		 }		//$this->layout=false;	}/** * delete method * * @throws NotFoundException * @param string $id * @return void */	public function delete($id = null) {		$access_result = $this->checkPathAccess($this->Auth->user('id'));		if (!$access_result) {			$this->User->id = $id;			if (!$this->User->exists()) {				throw new NotFoundException(__('Invalid user'));			}				$this->request->allowMethod('post', 'delete');			if ($this->User->delete()) {				$this->Flash->success(__('The user has been deleted.'));				return $this->redirect(array('action' => 'index'));			} else {				$this->Flash->error(__('The user could not be deleted. Please, try again.'));			}			return $this->redirect(array('action' => 'index'));		} else {			$this->render('../Users/access_denied');		}	}	public $paginate = array(        'limit' => 25,        'conditions' => array('status' => '1'),        'order' => array('User.username' => 'asc' )     );        public function beforeFilter() {    	$this->Security->validatePost = false;    	$this->Security->enabled = false;    	$this->Security->csrfCheck = false;       parent::beforeFilter();       $this->Auth->allow(array('controller' => 'users','action' => 'login'));     }     public function login() { 		        //if already logged-in, redirect        if($this->Session->check('Auth.User')){            $this->redirect(array('controller' => 'users','action' => 'dashboard'));          }        // if we get the post information, try to authenticate        if ($this->request->is('post')) {            if($this->Auth->login()) {                //$this->Flash->success(__('Welcome, '. $this->Auth->user('username')));            	$this->redirect($this->Auth->redirectUrl());            } else {                				$this->Flash->error(__('Invalid username or password. Please try again!!!'));            }        }     }    public function logout() {		$this->Session->destroy();		return $this->redirect($this->Auth->logout());    }		/**	 * dashboard method	 *	 * @return void	 */	public function dashboard() {				$this->User->recursive = 0;		$this->set('users', $this->Paginator->paginate());		$data=$this->User->query("select course_mapping_id FROM course_student_mappings where course_mapping_id IN (SELECT id FROM course_mappings where indicator=1 and id NOT IN(SELECT course_mapping_id FROM student_marks ))"); foreach ($data as $key => $value) { 	$data1=$this->User->query("delete  FROM course_student_mappings where course_mapping_id =".$value['course_student_mappings']['course_mapping_id']);	if($data1) { echo "deleted - ".$value['course_student_mappings']['course_mapping_id']."<br>"; }else { echo "Not deleted - ".$value['course_student_mappings']['course_mapping_id']."<br>";  } }	}		public function change_password(){		$access_result = $this->checkPathAccess($this->Auth->user('id'));		if (!$access_result) {			if ($this->request->is('post')) {				$user = $this->User->findById($this->Auth->user('id'));				if(AuthComponent::password($this->request->data['User']['new_password']) != AuthComponent::password($this->request->data['User']['confirm_password'])) {					$this->Flash->error(__('New Password & Confirm Password Not Match.'));					return $this->redirect(array('controller' => 'users','action' => 'change_password'));				}					if($user['User']['password'] == AuthComponent::password($this->request->data['User']['current_password'])) {					$this->request->data['User']['id'] = $this->Auth->user('id');					if ($this->User->save($this->request->data)) {						$this->Flash->success('Your details have been saved');						return $this->redirect(array('controller' => 'users','action' => 'change_password'));					} else {						$this->Flash->error('Your details could not be updated. Try again.');					}				} else {									$this->Flash->error(__('Invalid password. Try again.'));					}						}			} else {			$this->render('../Users/access_denied');		}	}		public function access_denied() {					}	}