<?php
/**
 * Category Resource Model
 *
 * @copyright		 Copyright 2012, Passbolt.com
 * @package			 app.Model.CategoryResource
 * @since				 version 2.12.11
 * @license			 http://www.passbolt.com/license
 */

App::uses('Category', 'Model');
App::uses('Resource', 'Model');

class CategoryResource extends AppModel {

	public $useTable = "categories_resources";

	public $belongsTo = array(
		'Category', 'Resource'
	);

	public $actsAs = array('Trackable');

/**
 * Get the validation rules upon context
 * @param string context
 * @return array cakephp validation rules
 */
	public static function getValidationRules($case='default') {
		$default = array(
			'category_id' => array(
				'uuid' => array(
					'rule' => 'uuid',
					'required' => true,
					'allowEmpty' => false,
					'message'	=> __('UUID must be in correct format')
				),
				'exist' => array(
					'rule' => array('validateExists', 'category_id', 'Category'),
					'message' => __('The category provided does not exist')
				)
			),
			'resource_id' => array(
				'uuid' => array(
					'rule' => 'uuid',
					'required' => true,
					'allowEmpty' => false,
					'message'	=> __('UUID must be in correct format')
				),
				'exist' => array(
					'rule' => array('validateExists', 'resource_id', 'Resource'),
					'message' => __('The resource provided does not exist')
				),
				'uniqueCombi' => array(
					'rule' => array('uniqueCombi', null),
					'message' => __('The CategoryResource entered is a duplicate')
				)
			)
		);
		switch ($case) {
			default:
			case 'default' :
				$rules = $default;
		}
		return $rules;
	}

/**
 * Check if a category with same id exists
 * @param check
 */
	public function uniqueCombi($check = false) {
		$cr = $this->data['CategoryResource'];
		$combi = array(
			'CategoryResource.category_id' => $cr['category_id'],
			'CategoryResource.resource_id' => $cr['resource_id']
		);
		return $this->isUnique($combi, false);
	}

/**
 * Return the conditions to be used for a given context
 *
 * @param $context string{guest or id}
 * @param $data used in find conditions (such as User.id)
 * @return $condition array
 * @access public
 */
	public static function getFindConditions($case = 'view', $role = Role::USER, $data = null) {
		$conditions = array();
		switch ($case) {
			case 'add':
				$conditions = array();
			break;
			case 'view':
				$conditions = array(
					'conditions' => array(
						'CategoryResource.id' => $data['CategoryResource.id']
					)
				);
			break;
			default:
				$conditions = array(
					'conditions' => array()
				);
		}
		return $conditions;
	}

/**
 * Return the list of field to fetch for given context
 * @param string $case context ex: login, activation
 * @return $condition array
 */
	public static function getFindFields($case = 'view', $role = Role::USER) {
		switch($case){
			case 'view':
			case 'add':
			case 'edit':
				$fields = array(
					'fields' => array(
						'id', 'category_id', 'resource_id'
					)
				);
			break;
			case 'delete':
				$fields = array();
			break;
			default:
				$fields = array(
					'fields' => array()
				);
			break;
		}
		return $fields;
	}
}
