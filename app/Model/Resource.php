<?php

/**
 * Resource model
 *
 * @copyright     Copyright 2012 Passbolt.com
 * @license       http://www.passbolt.com/license
 * @package       app.Model.Resource
 * @since         version 2.12.7
 */
class Resource extends AppModel {
/**
 * Model behaviors
 * @link http://api20.cakephp.org/class/model#
 */
	public $actsAs = array(
		'SuperJoin',
		'Containable',
		'Trackable',
		'Favoritable',
		'Permissionable' => array('priority' => 1)
	);

/**
 * Details of has one relationships
 * @link http://book.cakephp.org/2.0/en/models/associations-linking-models-together.html#
 */
	public $hasOne = array('Secret');

	public $belongsTo = array(
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
	public $hasMany = array('CategoryResource');

/**
 * Details of has and belongs to many relationships
 * @link http://book.cakephp.org/2.0/en/models/associations-linking-models-together.html#
 */
	public $hasAndBelongsToMany = array('Category' => array('className' => 'Category'));

	public function __construct($id = false, $table = null, $ds = null) {
		parent::__construct($id, $table, $ds);
		$this->Behaviors->setPriority(array('Permissionable' => 1));
	}

/**
 * Get the validation rules upon context
 *
 * @param string case (optional) The target validation case if any.
 * @return array CakePHP validation rules
 */
	public static function getValidationRules($case = 'default') {
		$default = array(
				'id' => array(
					'uuid' => array(
						'rule' => 'uuid',
						'required' => 'update',
						'message' => __('Id must be in correct format'),
					)
				),
				'name' => array(
					'alphaNumericAndSpecial' => array(
						'rule' => "/^[\p{L}\d ,.\-_\(\[\)\]']*$/u",
						'required' => 'create',
						'allowEmpty' => false,
						'message' => __('Name should only contain alphabets, numbers and the special characters : , . - _ ( ) [ ] \''),
					),
					'size' => array(
						'rule' => array('between', 3, 64),
						'message' => __('Name should be between %s and %s characters long'),
					)
				),
				'username' => array(
					'alphaNumeric' => array(
						'rule' => '/^[a-zA-Z0-9\-_]*$/',
						'required' => 'create',
						'message' => __('Username should only contain alphabets, numbers only and the special characters : - _'),
					),
					'size' => array(
						'rule' => array('between', 3, 64),
						'message' => __('Username should be between %s and %s characters long'),
					)
				),
			'expiry_date' => array(
				'date' => array(
					'required' => false,
					'allowEmpty' => true,
					'rule' => array(
						'date',
						'ymd'
					),
					'message' => __('Please indicate a valid date')
				),
				'infuture' => array(
					'rule' => array('isInFuture'),
					'message' => __('The date should be in the future.')
				),
			),
			'uri' => array(
				'url' => array(
					'rule' => AppValidation::getVadalidationUrlRegex(),
					'message' => __('The format of the uri is not valid.'),
					'allowEmpty' => true,
				),
			),
			'description' => array(
				'alphaNumericAndSpecial' => array(
					'rule' => "/^[\p{L}\d ,.:;?!\-_\(\[\)\]'\"\/]*$/u",
					'required' => false,
					'allowEmpty' => true,
					'message' => __('Description should only contain alphabets, numbers and the special characters : , . : ; ? ! - _ ( ) [ ] \' " /')
				),
				'size' => array(
					'rule' => array('between', 3, 255),
					'message' => __('Username should be between %s and %s characters long'),
				)
			),
		);
		switch ($case) {
			default:
			case 'default':
				$rules = $default;
				break;
		}
		return $rules;
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
		$conditions = array();

		switch ($case) {
			case 'add':
			case 'edit':
			case 'view':
				$conditions = array('conditions' => array(
					'Resource.deleted' => 0,
					'Resource.id' => $data['Resource.id']
				));
				break;

			case 'index':
			case 'viewByCategory':
				$conditions = array('conditions' => array('Resource.deleted' => 0));
				if (isset($data['foreignModels']['Category.id'])) {
					$conditions['conditions']['Category.id'] = $data['foreignModels']['Category.id'];
				}
				if (isset($data['keywords'])) {
					$keywords = explode(' ', $data['keywords']);
					foreach ($keywords as $keyword) {
						$conditions['conditions']["AND"][] = array('Resource.name LIKE' => '%' . $keyword . '%');
					}
				}
				if (isset($data['case'])) {
					switch ($data['case']) {
						case 'favorite':
							$conditions['conditions']["AND"][] = array('Favorite.id IS NOT NULL');
							break;

						case 'own':
							$conditions['conditions']["AND"][] = array('Resource.created_by' => User::get('User.id'));
							break;

						case 'shared':
							$conditions['conditions']["AND"][] = array('Resource.created_by <>' => User::get('User.id'));
							break;

					}
				}
				if (isset($data['order'])) {
					switch ($data['order']) {
						case 'modified':
							$conditions['order'] = array('Resource.modified DESC');
							break;

						case 'expiry_date':
							$conditions['order'] = array('Resource.expiry_date DESC');
							break;

					}
				} else {
					// By default order by created date
					$conditions['order'] = array('Resource.modified DESC');
				}
				break;

			default:
				$conditions = array('conditions' => array());
		}

		return $conditions;
	}

/**
 * Return the list of field to fetch for given context
 * @param string $case context ex: login, activation
 * @return $condition array
 */
	public static function getFindFields($case = 'view', $role = Role::USER) {
		switch ($case) {
			case 'view':
			case 'index':
			case 'viewByCategory':
				$fields = array(
					'fields' => array(
						'Resource.id',
						'Resource.name',
						'Resource.username',
						'Resource.expiry_date',
						'Resource.uri',
						'Resource.description',
						'Resource.created',
						'Resource.modified',
						'Secret.id',
						'Secret.data',
						'Secret.created',
						'Secret.modified',
						'Favorite.id',
						'Favorite.user_id',
						'Favorite.created',
						'Creator.id',
						'Creator.username',
						'Modifier.id',
						'Modifier.username'
					),
					'superjoin' => array('Category'),
					'contain' => array(
						'Category',
						'CategoryResource',
						'Favorite',
						'Secret',
						'Creator',
						'Modifier'
					)
				);
				break;
			case 'delete':
				$fields = array('fields' => array('deleted'));
				break;
			case 'save':
				$fields = array('fields' => array(
					'name',
					'username',
					'expiry_date',
					'uri',
					'description',
					'created',
					'modified',
					'created_by',
					'modified_by',
					'deleted'
				));
				break;
			default:
				$fields = array('fields' => array());
				break;
		}
		return $fields;
	}

/**
 * Validates if a date is in future
 * @param array $check the parameters
 * @return bool true if the date is in future, false otherwise
 */
	public function isInFuture($check) {
		$now = time();
		$expiryDate = strtotime($check['expiry_date']);
		$interval = $expiryDate - $now;
		return ($interval > 0);
	}
}
