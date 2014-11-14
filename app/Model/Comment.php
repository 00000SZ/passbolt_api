<?php
/**
 * Comment Model
 *
 * @copyright		Copyright 2012, Passbolt.com
 * @package			app.Model.comment
 * @since			version 2.12.12
 * @license			http://www.passbolt.com/license
 */
class Comment extends AppModel {

/**
 * Model behaviors
 * @link http://api20.cakephp.org/class/model#
 */
	public $actsAs = array('Trackable');

/**
 * Details of belongs to relationships
 * @link http://book.cakephp.org/2.0/en/models/associations-linking-models-together.html#
 */
	public $belongsTo = array(
		'Resource' => array(
			'foreignId' => 'aco_foreign_key'
		),
		'Creator' => array(
			'className' => 'User',
			'foreignKey' => 'created_by'
		),
		'Modifier' => array(
			'className' => 'User',
			'foreignKey' => 'modified_by'
		)
	);

/**
 * Details of has many relationships
 * @link http://book.cakephp.org/2.0/en/models/associations-linking-models-together.html#
 */
	public $hasMany = array(
		'Comment' => array(
			'className' => 'Comment',
			'foreignKey' => 'parent_id',
			'dependent' => true // Allow developpers to delete children comments when a parent is deleted
		)
	);

/**
 * Get the validation rules upon context
 * @param string $case (optional) The target validation case if any.
 * @return array cakephp validation rules
 */
	public static function getValidationRules($case = 'default') {
		$default = array(
			'id' => array(
				'uuid' => array(
					'rule' => 'uuid',
					'allowEmpty' => true,
					'required' => false,
					'message'	=> __('UUID must be in correct format')
				)
			),
			'parent_id' => array(
				'uuid' => array(
					'rule' => 'uuid',
					'allowEmpty' => true,
					'required' => false,
					'message'	=> __('UUID must be in correct format')
				),
				'exist' => array(
					'shared' => false,
					'rule' => array('parentExists', null),
					'message' => __('The parent provided does not exist')
				),
			),
			'foreign_id' => array(
				'uuid' => array(
					'rule' => 'uuid',
					'required' => true,
					'allowEmpty' => false,
					'message'	=> __('UUID of the foreign key must be in correct format')
				),
				'exist' => array(
					'shared' => false,
					'rule' => array('foreignExists', null),
					'message' => __('The resource provided does not exist')
				),
			),
			'foreign_model' => array(
				'inlist' => array(
					'shared' => false,
					'required' => true,
					'allowEmpty' => false,
					'rule' => 'validateForeignModel',
					'message' => __('Please enter a valid model name')
				)
			),
			'content' => array(
				'alphaNumeric' => array(
					'required' => true,
					'allowEmpty' => false,
					'rule' => "/^[\p{L}\d ,.\-_\(\[\)\]'\"?!]*$/u",
					'message'	=> __('Content should only contain alphabets, numbers and the special characters : , . - _ ( ) [ ] \' " ? !')
				),
				'size' => array(
					'rule' => array('between', 3, 255),
					'message' => __('Username should be between %s and %s characters long'),
				)
			)
		);
		switch ($case) {
			case 'edit':
				$rules = array(
					'id' => $default['id'],
					'content' => $default['content'],
				);
			break;
			default:
			case 'default':
				$rules = $default;
			break;
		}
		return $rules;
	}

/**
 * Validation Rule : Check if the given foreign model is allowed
 * @param array check the data to test
 * @return boolean
 */
	public function validateForeignModel($check) {
		return $this->isValidForeignModel($check['foreign_model']);
	}

/**
 * Check if the given foreign model is allowed
 * @param string foreignModel The foreign model key to test
 * @return boolean
 */
	public function isValidForeignModel($foreignModel) {
		return in_array($foreignModel, Configure::read('Comment.foreignModels'));
	}

/**
 * Check if a resource with same id exists
 * @param check
 */
	public function foreignExists($check) {
		if ($this->data['Comment']['foreign_model'] == null) {
			return false;
		}
		if ($check['foreign_id'] == null) {
			return false;
		}
		$m = $this->data['Comment']['foreign_model'];
		$model = Common::getModel($m);
		$exists = $model->find('count', array(
				'conditions' => array('id' => $check['foreign_id']),
				'recursive' => -1
		));
		return $exists > 0;
	}

/**
 * Return the find conditions to be used for a given context.
 *
 * @param null|string $case The target case.
 * @param null|string $role The user role.
 * @param null|array $data (optional) Optional data to build the find conditions.
 * @return array
 */
	public static function getFindConditions($case = 'view', $role = Role::USER, $data = null) {
		$returnValue = array();
		switch ($case) {
			case 'viewByForeignModel':
				$returnValue = array(
					'conditions' => array(
						'Comment.foreign_id' => $data['Comment']['foreign_id']
						// @todo maybe check here if user has right to access the foreign instance, in this case we need the model to make a join with the convient permission view table
					),
					'order' => array(
						'Comment.modified desc'
					)
				);
			break;
			case 'view':
				$returnValue = array(
					'conditions' => array(
						'Comment.id' => $data['Comment']['id']
						// @todo maybe check here if user has right to access the foreign instance, in this case we need the model to make a join with the convient permission view table
					)
				);
			break;
			default:
				$returnValue = array(
					'conditions' => array()
				);
		}

		return $returnValue;
	}

/**
 * Return the list of field to fetch for given context
 * @param string $case context ex: login, activation
 * @return $condition array
 */
	public static function getFindFields($case = 'view', $role = Role::USER) {
		$returnValue = array('fields' => array());
		switch($case){
			case 'view':
			case 'viewByForeignModel':
				$returnValue = array(
					'fields' => array('id', 'parent_id', 'content', 'created', 'modified', 'created_by', 'modified_by'),
					'contain' => array(
						'Creator' => array(
							'fields' => array(
								'username'
							),
							'Role' => array(
								'fields' => array(
									'Role.id',
									'Role.name'
								)
							),
							'Profile' => array(
								'fields' => array(
									'Profile.id',
									'Profile.first_name',
									'Profile.last_name',
								),
								'Avatar' => array(
									'fields' => array(
										'Avatar.id',
										'Avatar.user_id',
										'Avatar.foreign_key',
										'Avatar.model',
										'Avatar.filename',
										'Avatar.filesize',
										'Avatar.mime_type',
										'Avatar.extension',
										'Avatar.hash',
										'Avatar.path',
										'Avatar.adapter',
										'Avatar.created',
										'Avatar.modified'
									)
								)
							),
						)
					)
				);
			break;
			case 'add':
				$returnValue = array(
					'fields' => array('content', 'parent_id', 'foreign_model', 'foreign_id', 'created_by', 'modified_by')
				);
			break;
			case 'edit':
				$returnValue = array(
					'fields' => array('content')
				);
			break;
		}
		return $returnValue;
	}

}
