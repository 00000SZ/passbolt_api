steal(
	'mad/controller/componentController.js',
	'app/view/template/component/peopleWorkspaceMenu.ejs'
).then(function () {

		/*
		 * @class passbolt.controller.component.PeopleWorkspaceMenuController
		 * @inherits mad.controller.component.ComponentController
		 * @parent index
		 *
		 * Our passbolt user workspace menu controller
		 *
		 * @constructor
		 * Creates a new user workspace menu controller
		 *
		 * @param {HTMLElement} element the element this instance operates on.
		 * @param {Object} [options] option values for the controller.  These get added to
		 * this.options and merged with defaults static variable
		 * @return {passbolt.controller.component.PeopleWorkspaceMenuController}
		 */
		mad.controller.ComponentController.extend('passbolt.controller.component.PeopleWorkspaceMenuController', /** @static */ {

			'defaults': {
				'label': 'User Workspace Menu Controller',
				// the selected resources, you can pass an existing list as parameter of the constructor to share the same list
				'selectedUsers': new can.Model.List()
			}

		}, /** @prototype */ {

			/**
			 * after start hook.
			 * @return {void}
			 */
			'afterStart': function () {
				// Manage creation action
				this.options.creationButton = new mad.controller.component.ButtonController($('#js_user_wk_menu_creation_button'))
					.start();

				// Manage edition action
				this.options.editionButton = new mad.controller.component.ButtonController($('#js_user_wk_menu_edition_button'), {
					'state': 'disabled'
				}).start();

				// Manage deletion action
				this.options.deletionButton = new mad.controller.component.ButtonController($('#js_user_wk_menu_deletion_button'), {
					'state': 'disabled'
				}).start();

				// Manage more action
				this.options.moreButton = new mad.controller.component.ButtonController($('#js_user_wk_menu_more_button'), {
					'state': 'disabled'
				}).start();

				// @todo URGENT, buggy, it rebinds 2 times external element event (such as madbus)
				this.on();
			},

			/* ************************************************************** */
			/* LISTEN TO THE APP EVENTS */
			/* ************************************************************** */

			/**
			 * Observe when the user wants to create a new user
			 * @param {HTMLElement} el The element the event occured on
			 * @param {HTMLEvent} ev The event which occured
			 * @return {void}
			 */
			'{creationButton} click': function (el, ev) {
				/*var category = this.options.creationButton.getValue();*/
				mad.bus.trigger('request_user_creation'/*, category*/);
			},

			/**
			 * Observe when the user wants to edit an instance (Resource, User depending of the active workspace)
			 * @param {HTMLElement} el The element the event occured on
			 * @param {HTMLEvent} ev The event which occured
			 * @return {void}
			 */
			'{editionButton} click': function (el, ev) {
				/*var category = this.options.editionButton.getValue();*/
				mad.bus.trigger('request_user_edition'/*, category*/);
			},

			/**
			 * Observe when the user wants to delete an instance (Resource, User depending of the active workspace)
			 * @param {HTMLElement} el The element the event occured on
			 * @param {HTMLEvent} ev The event which occured
			 * @return {void}
			 */
			'{deletionButton} click': function (el, ev) {
				var users = this.options.selectedUsers;
				mad.bus.trigger('request_user_deletion', users);
			},

			/**
			 * Observe when a user is selected
			 * @param {HTMLElement} el The element the event occured on
			 * @param {HTMLEvent} ev The event which occured
			 * @param {passbolt.model.User} user The selected user
			 * @return {void}
			 */
			'{selectedUsers} add': function (el, ev, user) {
				// if more than one resource selected, or no resource selected
				if (this.options.selectedUsers.length == 0) {
					this.setState('ready');

					// else if only 1 resource selected show the details
				} else if (this.options.selectedUsers.length == 1) {
					this.setState('selection');

					// else if more than one resource have been selected
				} else {
					this.setState('multiSelection');
				}
			},

			/**
			 * Observe when a user is unselected
			 * @param {HTMLElement} el The element the event occured on
			 * @param {HTMLEvent} ev The event which occured
			 * @param {passbolt.model.User} user The unselected user
			 * @return {void}
			 */
			'{selectedUsers} remove': function (el, ev, user) {
				// if more than one resource selected, or no resource selected
				if (this.options.selectedUsers.length == 0) {
					this.setState('ready');

					// else if only 1 resource selected show the details
				} else if (this.options.selectedUsers.length == 1) {
					this.setState('selection');

					// else if more than one resource have been selected
				} else {
					this.setState('multiSelection');
				}
			},

			/* ************************************************************** */
			/* LISTEN TO THE STATE CHANGES */
			/* ************************************************************** */

			/**
			 * Listen to the change relative to the state selected
			 * @param {boolean} go Enter or leave the state
			 * @return {void}
			 */
			'stateSelection': function (go) {
				if (go) {
					this.options.editionButton
						.setValue(this.options.selectedUsers[0])
						.setState('ready');
					this.options.deletionButton
						.setValue(this.options.selectedUsers)
						.setState('ready');
					this.options.moreButton
						.setValue(this.options.selectedUsers[0])
						.setState('ready');
				} else {
					this.options.editionButton
						.setValue(null)
						.setState('disabled');
					this.options.deletionButton
						.setValue(null)
						.setState('disabled');
					this.options.moreButton
						.setValue(null)
						.setState('disabled');
				}
			},

			/**
			 * Listen to the change relative to the state multiSelection
			 * @param {boolean} go Enter or leave the state
			 * @return {void}
			 */
			'stateMultiSelection': function (go) {
				if (go) {
					this.options.editionButton
						.setState('disabled');
					this.options.deletionButton
						.setValue(this.options.selectedUsers)
						.setState('ready');
					this.options.moreButton
						.setState('disabled');
				} else {
					this.options.editionButton
						.setValue(null)
						.setState('disabled');
					this.options.deletionButton
						.setValue(null)
						.setState('disabled');
				}
			}

		});

	});