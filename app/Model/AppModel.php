<?php
/**
 * Application model for Cake.
 *
 * This file is application-wide model file. You can put all
 * application-wide model-related methods here.
 *
 * @copyright    Copyright 2012, Passbolt.com
 * @license      http://www.passbolt.com/license
 * @package      app.Model.AppModel
 * @since        version 2.12.7
 */
App::uses('Model', 'Model');
class AppModel extends Model {

/**
 * Binds to ARO nodes through permissions settings
 *
 * @var array
 */
	public $actsAs = array('Containable');

/**
 * Never fetch any recursive data from associated models
 * Use containable for any assocs
 *
 * @var integer
 */
	public $recursive = -1;

/**
 * Constructor
 *
 * @link http://api20.cakephp.org/class/app-model#method-AppModel__construct
 * @access public
 */
	public function __construct($id = false, $table = null, $ds = null) {
		parent::__construct($id, $table, $ds);
		$this->setValidationRules();
	}

/**
 * Set the validation rules upon context
 *
 * @param string context
 * @return bool true if success
 * @access public
 */
	public function setValidationRules($context = 'default') {
		$this->validate = $this->getValidationRules($context);
		return true;
	}

/**
 * Get the validation rules upon context
 *
 * @param string context
 * @return array validation rules
 * @access public
 */
	public static function getValidationRules($case = null) {
		return array();
	}

/**
 * Return the find options (felds and conditions) for a given context
 * @param string context
 * @param array data
 * @return array
 */
	public static function getFindOptions($case, &$data = null) {
		return array_merge(
			static::getFindConditions($case, &$data),
			static::getFindFields($case)
		);
	}

/**
 * Return the list of field to use for a find for given context
 *
 * @param string $case context ex: login, activation
 * @return $condition array
 * @access public
 */
	public static function getFindFields($case = null) {
		return array('fields' => array());
	}

/**
 * Return the conditions to be used for a given context
 * for example if you want to activate a User session
 *
 * @param $context string{guest or id}
 * @param $data used in find conditions (such as User.id)
 * @return $condition array
 * @access public
 */
	public static function getFindConditions($case = null, &$data = null) {
		return array('conditions' => array());
	}
}
