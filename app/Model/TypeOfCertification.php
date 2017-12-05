<?php
App::uses('AppModel', 'Model');
/**
 * TypeOfCertification Model
 *
 */
class TypeOfCertification extends AppModel {

/**
 * Validation rules
 *
 * @var array
 */
	public $displayField = 'certification';
	
	public $belongsTo = array(	
		'User' => array(	
			'className' => 'User',	
			'foreignKey' => 'created_by',	
			'conditions' => '',	
			'fields' => '',	
			'order' => ''	
		),
	
		'ModifiedUser' => array(	
			'className' => 'ModifiedUser',	
			'foreignKey' => 'modified_by',	
			'conditions' => '',	
			'fields' => '',	
			'order' => ''	
		)	
	);
	
	public $validate = array(
		'certification' => array(
				'nonEmpty' => array(
					'rule' => array('notBlank'),
					'message' => 'Enter Certification Name',
					'allowEmpty' => false
				),
				'rule1' => array(			
					'rule' => array('checkAvailability'),			
					'on' => 'create',			
					'message'=>"Record Already Exist"			
				),			
				'rule2' => array(			
					'rule' => array('updateCheckAvailability'),			
					'on' => 'update',			
					'message'=>"Record Already Exist"			
				)
		),
			
	);
	
	public function checkAvailability($data){
		$certification = $this->data['TypeOfCertification']['certification'];
		$results = $this->find("first",array(
				'conditions'=>array("TypeOfCertification.certification"=>$certification),'recursive'=>-1,'fields'=>array("TypeOfCertification.id")));
		if($results){return false;}else{return true;}	
	}
		
	public function updateCheckAvailability($data){	
		$certificationId = $this->data['TypeOfCertification']['id'];
		$certification = $this->data['TypeOfCertification']['certification'];	
		$results = $this->find("first",array('conditions'=>array("TypeOfCertification.certification"=>$certification,"TypeOfCertification.id !="=>$certificationId),'recursive'=>-1,'fields'=>array("TypeOfCertification.id")));
		if($results){return false;}else{return true;}	
	}
}
