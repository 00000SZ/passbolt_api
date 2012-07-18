<?php
/**
 * Common Component
 * This class serves as a space for functions (mostly static) 
 * that need to be globally available within this application.
 *
 * @copyright     copyright 2012 Passbolt.com
 * @package       app.Controller.Common
 * @since         version 2.12.7
 * @license       http://www.passbolt.com/license
 */
class Common extends Object {

  /**
   * Instanciate and return the reference to a model object
   * @param string name $model
   * @return model $ModelObj
   */
  static function &getModel($model,$create=false) {
    if (ClassRegistry::isKeySet($model) && !$create) {
      $ModelObj =& ClassRegistry::getObject($model);
    } else {
      $ModelObj =& ClassRegistry::init($model);
    }
    return $ModelObj;
  }

  /**
   * Indicates if a given string is a UUID
   * @param string $str
   * @return boolean
   */
  static function isUuid($str) {
    return is_string($str) && preg_match('/^[A-Fa-f0-9]{8}-[A-Fa-f0-9]{4}-[A-Fa-f0-9]{4}-[A-Fa-f0-9]{4}-[A-Fa-f0-9]{12}$/', $str);
  }

  /**
   * Return a UUID - ref. String::uuid();
   * @param string seed, used to create deterministic UUID
   * @return uuid
   */
  static function uuid($seed=null){
    if (isset($seed)) {
      $pattern = '/^(.{8})(.{4})(.{4})(.{4})(.{12})$/';
      $replacement = '${1}-${2}-${3}-${4}-${5}';
      $string = md5($seed);
      return preg_replace($pattern,$replacement,$string);
    }
    return String::uuid();
  }

}
