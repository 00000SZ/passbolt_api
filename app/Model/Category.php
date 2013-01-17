<?php
/**
 * Category Controller
 *
 * @copyright    Copyright 2012, Passbolt.com
 * @license      http://www.passbolt.com/license
 * @package      app.Model.Category
 * @since        version 2.12.7
 */
App::uses('CategoryType', 'Model');
App::uses('Resource', 'Model');
App::uses('User', 'Model');
App::uses('PermissionType', 'Model');

class Category extends AppModel {

/**
 * Model behave as a tree with left, right, parent_id
 */
	public $actsAs = array(
		'Containable',
		'Trackable',
		'Permissionable'=>array('priority' => 1),
		'Tree'
	);
	
	public $hasMany = array(
		'CategoryResource'
	);

	public $belongsTo = array('CategoryType' => array(
		'className' => 'CategoryType'
	));

	public function __construct( $id = false, $table = NULL, $ds = NULL )	{
		parent::__construct($id, $table, $ds);
		$this->Behaviors->setPriority(array(
			'Permissionable' => 1
		));
	}
	
/**
 * Get the validation rules upon context
 * @param string context
 * @return array cakephp validation rules
 */
	public static function getValidationRules($context='default') {
		$rules = array(
			'id' => array(
				'uuid' => array(
					'rule'		 => 'uuid',
					'message'	=> __('UUID must be in correct format')
				)
			),
			'name' => array(
				'alphaNumeric' => array(
					'rule'		 => '/^.{2,64}$/i',
					'required' => true,
					'message'	=> __('Alphanumeric only')
				)
			),
			'parent_id' => array(
				'exist' => array(
					'rule'		=> array('parentExists', null),
					'allowEmpty' => true,
					'message' => __('The parent provided does not exist')
				),
				'uuid' => array(
					'rule'		 => 'uuid',
					'allowEmpty' => true,
					'required' => false,
					'message'	=> __('UUID must be in correct format')
				)
			),
			'position' => array(
				'number' => array(
					'rule'		=> 'numeric',
					'message' => __('The position must be a number')
				)
			),
			'category_type_id' => array(
				'exist' => array(
					'rule'		=> array('categoryTypeExists', null),
					'allowEmpty' => true,
					'message' => __('The category type provided does not exist')
					),
				'uuid' => array(
					'rule'		 => 'uuid',
					'allowEmpty' => true,
					'required' => false,
					'message'	=> __('UUID must be in correct format')
				)
			)
		);

		/* a context switch if needed
		switch ($context) {
			default:
				unset($rules['rule']);
			break;
		}*/

		return $rules;
	}

/**
 * In the event of ambiguous results returned (multiple top level results, with different parent_ids)
 * top level results with different parent_ids to the first result will be dropped
 *
 * @param string $state
 * @param mixed $query
 * @param array $results
 * @return array Threaded results
 */
	protected function _findThreaded($state, $query, $results = array()) {
		if ($state === 'before') {
			return $query;
		} elseif ($state === 'after') {
			// ATTENTION
			// results has to be ordered following Category.lft
			$n = count($results);
			// build parent hierarchy even if some nodes are missing. Based on left and right
			for($i=$n-1; $i>=0; $i--) {
				for($j=$i-1; $j>=0; $j--) {
					if($results[$i]['Category']['lft'] > $results[$j]['Category']['lft'] &&
							$results[$i]['Category']['rght'] < $results[$j]['Category']['rght']) {
						$results[$j]['children'][] = $results[$i];
						unset($results[$i]);
						break; 
					}
				}
			}
			return $results;
		}
	}

/**
 * Check if a category type with same id exists
 * @param check
 */
	public function categoryTypeExists($check) {
		if ($check['category_type_id'] == null) {
			return true;
		} else {
			$exists = $this->CategoryType->find('count', array(
				'conditions' => array('CategoryType.id' => $check['category_type_id']),
				 'recursive' => -1
			));
			return $exists > 0;
		}
	}

/**
 * Check if an element is a child of a parent (not necessarily an immediate child. can be several levels below)
 * Useful when parsing an array of results
 * @param $elt, the element to check
 * @param $parent, the parent
 * @return true if element is a child, false otherwise
 */
	public function isChild($elt, $parent) {
		return ($elt['Category']['rght'] < $parent['Category']['rght']);
	}

/**
 * Check if an element is a leaf (no more children)
 * @param $category, the category
 * @return true if the category is a leaf. false otherwise.
 */
	public function isLeaf($category) {
		if ($category['Category']['lft'] + 1 == $category['Category']['rght']) {
			return true;
		}
		return false;
	}

/**
 * Check if an element is at the top level of the given branch
 * @param $objectType, the type of object given, whether a default cakePHP object or a Json converted one : 'default' or 'json'
 */
	public function isTopLevelElement($category, $categories) {
		$parentId = $category['Category']['parent_id'];
		foreach ($categories as $c) {
			if ($c['Category']['id'] == $parentId) {
				return false;
			}
		}
		return true;
	}

/**
 * move an element from a position to another in the tree
 * can be moved among its sieblings, can also change parent
 * @param uuid $id the if of the category
 * @param int $position the position from 1 to n
 * @param uuid $parentId the parent Id (if we wish to change it)
 * @return bool true or false
 * 
 */
	public function move($id, $position, $parentId=null) {
		// First, manage the parent
		$category = $this->findById($id);
		if (!$category) {
			return false;
		}
		$parentId = ($parentId == null ? $category['Category']['parent_id'] : $parentId);
		if ($category['Category']['parent_id'] != $parentId) {
			$category['Category']['parent_id'] = $parentId;
			$category = $this->save($category);
			if (!$category) {
				return false;
			}
		}
		// then, manage the position
		$nbChildren = $this->childCount($parentId, true);
		// if the position is first one or last one
		if ($position == 1) {
			$result = $this->moveUp($id, true);
		} elseif ($position >= $nbChildren) {
			$result = $this->moveDown($id, true);
		} else {
			$currentPosition = $this->getPosition($id);
			$steps = $currentPosition - $position;
			if ($steps > 0) {
				$result = $this->moveUp($id, $steps);
			} else {
				$result = $this->moveDown($id, -($steps));
			}
		}
		return $result;
	}

/**
 * get the current position of a category among its sieblings, starting from 1
 * @param uuid $id the id of the category
 * @return the current position starting from 1, false if category doesnt exist
 */
	public function getPosition($id) {
		// check if the category exist
		$category = $this->findById($id);
		if (!$category) {
			return false;
		}
		$parent = $this->findById($category['Category']['parent_id']);
		// calculate the current position
		$children = $this->children(
			$parent['Category']['id'], true, null,
			array('Category.lft' => 'ASC')
		);
		$currentPosition = 0;
		foreach ($children as $child) {
			$currentPosition++;
			if ($child['Category']['id'] == $id) break;
		}
		return $currentPosition;
	}
	
/**
 * Return the list of field to fetch for given context
 * @param string $case context ex: login, activation
 * @return $condition array
 */
	public static function getFindFields($case = 'get', $role = Role::USER) {
		$returnValue = array('fields' => array());

		switch($role){
			case 'user':
				switch($case){
					case 'view':
					case 'getChildren':
					case 'addResult':
					case 'index' :
					case 'getWithChildren' :
						$returnValue = array(
							'fields' => array(
								'Category.id', 'Category.name', 'Category.parent_id', 'Category.category_type_id', 'Category.lft', 'Category.rght'
							),
							'contain' => array ()
						);
					break;
					case 'Resource.viewByCategory':
						$returnValue = array(
							'fields' => array(
								'Category.id', 'Category.name', 'Category.parent_id'
							),
							'contain' => array(
								'Resource' => Resource::getFindFields('view')
							)
						);
					break;
					case 'rename':
						$returnValue = array(
							'fields' => array(
								'name'
							)
						);
					break;
					case 'add':
					case 'edit':
						$returnValue = array(
							'fields' => array(
								'name', 'parent_id', 'category_type_id'
							)
						);
					break;
					default:
						$returnValue = array('fields'=>array());
					break;
				}
			case 'admin':
			break;
		}
		return $returnValue;
	}

/**
 * Return the find conditions to be used for a given context
 *
 * @param $context string
 * @param $data that will be used in find conditions
 * @return $condition array
 * @access public
 */
	public static function getFindConditions($case = 'get', $role = Role::USER, &$data = null) {
		$returnValue = array('conditions' => array());
		switch($role){
			case 'user':
				switch ($case) {
					case 'getWithChildren':
						$returnValue = array(
							'conditions' => array(
								'Category.lft >=' => $data['Category']['lft'],
								'Category.rght <=' => $data['Category']['rght']
							),
							'order' => 'Category.lft ASC'
						);
					break;
					case 'getChildren':
						$returnValue = array(
							'conditions' => array(
								'Category.lft >' => $data['Category']['lft'],
								'Category.rght <' => $data['Category']['rght']
							),
							'order' => 'Category.lft ASC'
						);
					break;
					case 'Resource.viewByCategory':
					case 'view':
					case 'addResult':
						$returnValue = array(
							'conditions' => array(
								'Category.id' => $data['Category']['id']
							)
						);
					break;
					case 'index':
						$returnValue = array(
							'conditions' => array(
								'Category.parent_id' => null
							),
							'order' => 'Category.lft ASC'
						);
					break;
					default:
						$returnValue = array('conditions' => array());
				}
			case 'admin':
			break;
		}
		return $returnValue;
	}
}
