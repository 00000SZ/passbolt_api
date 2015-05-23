<?php
/**
 * Favorites controller
 * Control the starred items
 *
 * @copyright		Copyright 2013, Passbolt.com
 * @license			http://www.passbolt.com/license
 * @package			app.Controller.FavoritesController
 * @since				version 2.13.09
 */
class FavoritesController extends AppController {

/**
 * Add a favorite for a target model instance
 * @param string foreignModelName The target foreign model
 * @param uuid foreignId The uuid of the target instance to get comments for 
 */
	public function add($foreignModelName = null, $foreignId = null) {
		$foreignModelName = Inflector::camelize($foreignModelName);
		$userId = User::get('id');
		
		// check the HTTP request method
		if (!$this->request->is('post')) {
			$this->Message->error(__('Invalid request method, should be POST'));
			return;
		}
		
		// check if the target foreign model is favoritable
		if(!$this->Favorite->isValidForeignModel($foreignModelName)) {
			$this->Message->error(__('The model %s is not favoritable', $foreignModelName));
			return;
		}

		// the instance id is invalid
		if (!Common::isUuid($foreignId)) {
			$this->Message->error(__('The %s id is invalid', $foreignModelName));
			return;
		}

		// the foreign instance does not exist
		// the authorization to access the record is provided by the permissionable behavior, so if a user is not authorized to
		// access the instance reccord, the exists method should return false
		$this->loadModel($foreignModelName);
		if (!$this->$foreignModelName->exists($foreignId)) {
			$this->Message->error(__('The %s does not exist', $foreignModelName), array('code' => 404));
			return;
		}

		$favorite = $this->Favorite->find('first', array(
			'conditions' => array('user_id' => $userId, 'foreign_id' => $foreignId)
		));

		// Already stared
		if (!empty($favorite)) {
			$this->Message->error(__('This record was already starred!'));
			return;
		} else {
			$this->Favorite->create(array(
				'user_id' => $userId,
				'foreign_id' => $foreignId,
				'foreign_model' => strtolower($foreignModelName)
			));
			$favorite = $this->Favorite->save();
			$this->set('data', $favorite);
			$this->Message->success(__('This record was successfully starred!'));
			return;
		}
	}

	/**
	 * Unfav/unstar a given record for a given model
	 *
	 * @param UUID $id of the resource
	 * @return void
	 */
	function delete($id = null) {
		// check the HTTP request method
		if (!$this->request->is('delete')) {
			$this->Message->error(__('Invalid request method, should be DELETE'));
			return;
		}

		// the id is invalid
		if (!Common::isUuid($id)) {
			$this->Message->error(__('The starred id is not valid', $id));
			return;
		}

		// no favorite found
		$favorite = $this->Favorite->findById($id);
		if (empty($favorite)) {
			$this->Message->error(__('The record is not in your starred item list'), array('code' => 404));
			return;
		}
		
		// if the current user is not the owner of the favorite
		if($favorite['Favorite']['user_id'] != User::get('id')) {
			$this->Message->error(__('Your are not allowed to remove this record from your starred item list'), array('code' => 403));
			return;
		}
		
		$this->Favorite->delete($favorite['Favorite']['id']);
		$this->Message->success(__('This record was removed from your starred item list'));
	}
}