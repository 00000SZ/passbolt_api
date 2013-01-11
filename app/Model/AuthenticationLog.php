<?php
/**
 * AuthenticationLog  model
 *
 * @copyright    Copyright 2012, Passbolt.com
 * @license      http://www.passbolt.com/license
 * @package      app.Model.AuthenticationLog
 * @since        version 2.13.03
 */
App::uses('User', 'Model');

class AuthenticationLog extends AppModel {

	public $belongsTo = array(
		'User'
	);

/**
 * logs an authentication attempt
 * @param string $username, the username passed in the request. We want to store it to know what was tried
 * @param string $ip, the provenance ip
 * @param bool $status, the status of the authentication. 
 * @param string $data optional data that we'd like to store
 * @return bool true if the log was succesful, false otherwise
 */
	public function log ($username, $ip, $status, $data=null) {
		$u = $this->User->findByUsername($username);
		$log = array(
			'user_id' => $u ? $u['User']['id'] : null,
			'username' => $username,
			'ip' => $ip,
			'status' => $status,
			'data' => $data
		);
		$this->set($log);
		if (!$this->validates()) {
			return false;
		}
		$this->create();
		if (!$this->save($log)) {
			return false;
		}
		return true;
	}

/**
 * calculates the number of failed authentication attempts for a particular username, ip, or both together
 * @param string username the username. Keep it null if not needed
 * @param string ip the ip. keep it null if not needed
 * @param timestamp sinceTimestamp since which date/time we want to check the failed attempts. null means forever.
 * @return int the count of failed attempts. false if the parameters are not right.
 */
	public function getFailedAuthenticationCount($username=null, $ip=null, $sinceTimestamp=null) {
		$conditions = array();
		if($username == null && $ip == null)
			return false;

		$conditions[] = array('status' => false);
		if($username)
			$conditions[] = array("username" => $username);

		if($ip)
			$conditions[] = array("ip" => $ip);

		if ($sinceTimestamp)
			$conditions[] = array("created >=" => date('Y-m-d h:I:s', $sinceTimestamp));

		return $this->find('count', array(
			'conditions' => $conditions,
			'order' => array('created' => 'DESC')
		));
	}

/**
 * get the last failed authentication log, for an individual  username, ip, or both combined
 * @param string $username, the username
 * @param $ip, the ip
 * @return array the object AuthenticationLog, null if not found, false if something is wrong with the parameters
 */
	public function getLastFailedAuthenticationLog($username=null, $ip=null) {
		$conditions = array();
		if($username == null && $ip == null)
			return false;

		$conditions[] = array('status' => false);
		if($username)
			$conditions[] = array("username" => $username);

		if($ip)
			$conditions[] = array("ip" => $ip);

		$attempt = $this->find('first', array(
			'conditions' => $conditions,
			'order' => array('created' => 'DESC')
		));
		return $attempt;
	}
}