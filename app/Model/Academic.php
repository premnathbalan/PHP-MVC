<?php
App::uses ( 'AppModel', 'Model' );

/**
 *
 * Academic Model
 *
 *
 *
 * @property Program $Program
 *
 */
class Academic extends AppModel {
	
	/**
	 *
	 * Display field
	 *
	 *
	 *
	 * @var string
	 *
	 */
	public $displayField = 'academic_name';
	
	public $belongsTo = array (
			
			'User' => array (
					
					'className' => 'User',
					
					'foreignKey' => 'created_by',
					
					'conditions' => '',
					
					'fields' => '',
					
					'order' => '' 
			)
			,
			
			'ModifiedUser' => array (
					
					'className' => 'ModifiedUser',
					
					'foreignKey' => 'modified_by',
					
					'conditions' => '',
					
					'fields' => '',
					
					'order' => '' 
			)
			 
	)
	;
	
	// The Associations below have been created with all possible keys, those that are not needed can be removed
	
	/**
	 *
	 * hasMany associations
	 *
	 *
	 *
	 * @var array
	 *
	 */
	public $hasMany = array (
			
			'Program' => array (
					
					'className' => 'Program',
					
					'foreignKey' => 'academic_id',
					
					'dependent' => false,
					
					'conditions' => '',
					
					'fields' => '',
					
					'order' => '',
					
					'limit' => '',
					
					'offset' => '',
					
					'exclusive' => '',
					
					'finderQuery' => '',
					
					'counterQuery' => '' 
			)
			 
	)
	;
	public $validate = array (
			
			'academic_name' => array (
					
					'nonEmpty' => array (
							
							'rule' => array (
									'notBlank' 
							),
							
							'message' => 'Choose a name',
							
							'allowEmpty' => false 
					)
					 
			)
			,
			
			'academic_type' => array (
					
					'nonEmpty' => array (
							
							'rule' => array (
									'notBlank' 
							),
							
							'message' => 'Choose a name',
							
							'allowEmpty' => false 
					)
					 
			)
			,
			
			'short_code' => array (
					
					'nonEmpty' => array (
							
							'rule' => array (
									'notBlank' 
							),
							
							'message' => 'Choose a name',
							
							'allowEmpty' => false 
					)
					,
					
					'rule1' => array (
							
							'rule' => array (
									'checkAvailability' 
							),
							
							'on' => 'create',
							
							'message' => "Record Already Exist" 
					)
					,
					
					'rule2' => array (
							
							'rule' => array (
									'updateCheckAvailability' 
							),
							
							'on' => 'update',
							
							'message' => "Record Already Exist" 
					)
					 
			)
			,
			
			'academic_name_tamil' => array (
					
					'nonEmpty' => array (
							
							'rule' => array (
									'notBlank' 
							),
							
							'message' => 'Choose a name',
							
							'allowEmpty' => false 
					)
					 
			)
			 
	)
	;
	public function checkAvailability($data) {
		$academic_name = $this->data ['Academic'] ['academic_name'];
		
		$academic_type = $this->data ['Academic'] ['academic_type'];
		
		$short_code = $this->data ['Academic'] ['short_code'];
		
		$results = $this->find ( "first", array (
				'conditions' => array (
						"Academic.academic_name" => $academic_name,
						"Academic.academic_type" => $academic_type,
						"Academic.short_code" => $short_code 
				),
				'recursive' => - 1,
				'fields' => array (
						"Academic.id" 
				) 
		) );
		
		if ($results) {
			
			return false;
		} else {
			
			return true;
		}
	}
	public function updateCheckAvailability($data) {
		$academic_id = $this->data ['Academic'] ['id'];
		
		$academic_name = $this->data ['Academic'] ['academic_name'];
		
		$academic_type = $this->data ['Academic'] ['academic_type'];
		
		$short_code = $this->data ['Academic'] ['short_code'];
		
		$results = $this->find ( "first", array (
				'conditions' => array (
						
						"Academic.academic_name" => $academic_name,
						
						"Academic.academic_type" => $academic_type,
						
						"Academic.short_code" => $short_code,
						'Academic.id !=' => $academic_id 
				),
				
				'recursive' => - 1,
				'fields' => array (
						"Academic.id" 
				) 
		) );
		
		if ($results) {
			
			return false;
		} else {
			
			return true;
		}
	}
	public function getAcademic($academic_id) {
		$academic_name = $this->find ( 'all', array (
				'conditions' => array (
						'Academic.id' => $academic_id 
				),
				'recursive' => 0 
		) );
		$academicDetails ['name'] = $academic_name [0] ['Academic'] ['academic_name'];
		$academicDetails ['short_code'] = $academic_name [0] ['Academic'] ['short_code'];
		return $academicDetails;
	}
	public function getAcademicDetailsFromAcademicName($academic_name) {
		$academicId = $this->find ( 'first', array (
				'conditions' => array (
						'Academic.academic_name' => $academic_name 
				),
				'fields' => array (
						'Academic.id',
						'Academic.short_code' 
				),
				'recursive' => 0 
		) );
		$academicDetails ['id'] = $academicId ['Academic'] ['id'];
		$academicDetails ['short_code'] = $academicId ['Academic'] ['short_code'];
		return $academicDetails;
	}
}
