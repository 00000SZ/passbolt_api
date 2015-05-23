steal(
	'mad/controller/componentController.js',
	'app/controller/component/peopleBreadcrumbController.js',
    'app/controller/component/groupChooserController.js',
    'app/controller/component/userBrowserController.js',
	'app/controller/component/userShortcutsController.js',
	'app/controller/component/userDetailsController.js',
	'app/controller/form/user/createFormController.js',
	'app/controller/form/group/createFormController.js',
	'app/model/user.js'
).then(function () {

	/*
	 * @class passbolt.controller.PeopleWorkspaceController
	 * @inherits {mad.controller.ComponentController}
	 * @parent index
	 *
	 * @constructor
	 * Instanciates a new People Workspace Controller
	 *
	 * @param {HTMLElement} element the element this instance operates on.
	 * @param {Object} [options] option values for the controller.  These get added to
	 * this.options and merged with defaults static variable
	 * @return {passbolt.controller.PeopleWorkspaceController}
	 */
	mad.controller.ComponentController.extend('passbolt.controller.PeopleWorkspaceController', /** @static */ {

		'defaults': {
			'label': 'People',
			'templateUri': 'app/view/template/peopleWorkspace.ejs',
			'selectedUsers': new can.Model.List(),
			'selectedGroups': new can.Model.List(),
			'filter': new passbolt.model.Filter()
		}

	}, /** @prototype */ {

        /**
         * Called right after the start function
         * @return {void}
         * @see {mad.controller.ComponentController}
         */
        'afterStart': function() {
	        // Instantiate the primary workspace menu controller outside of the workspace container, destroy it when the workspace is destroyed
	        var primWkMenu = mad.helper.ComponentHelper.create(
		        $('#js_wsp_primary_menu_wrapper'),
		        'last',
		        passbolt.controller.component.PeopleWorkspaceMenuController, {
			        'selectedUsers': this.options.selectedUsers,
			        'selectedGroups': this.options.selectedGroups
		        }
	        );
	        primWkMenu.start();

	        // Instantiate the secondary workspace menu controller outside of the workspace container, destroy it when the workspace is destroyed
	        var secWkMenu = mad.helper.ComponentHelper.create(
		        $('#js_wsp_secondary_menu_wrapper'),
		        'last',
		        passbolt.controller.component.WorkspaceSecondaryMenuController,
		        {}
	        );
	        secWkMenu.start();

	        // Instantiate the password workspace breadcrumb controller
	        this.breadcrumCtl = new passbolt.controller.component.PeopleBreadcrumbController($('#js_wsp_ppl_breadcrumb'), {});
	        this.breadcrumCtl.start();

			// Instanciate the users filter controller.
			var userShortcut = new passbolt.controller.component.UserShortcutsController('#js_wsp_users_group_shortcuts', {});
			userShortcut.start();

			// Removed group choosed for #PASSBOLT-787
            //// Instanciate the group chooser controller.
            //this.grpChooser = new passbolt.controller.component.GroupChooserController('#js_wsp_users_group_chooser', {
				//'selectedGroups': this.options.selectedGroups
			//});
            //this.grpChooser.start();

            // Instanciate the passwords browser controller.
            var userBrowserController = new passbolt.controller.component.UserBrowserController('#js_wsp_ppl_browser', {
                'selectedUsers': this.options.selectedUsers
            });
            userBrowserController.start();

			// Instanciate the resource details controller
			var userDetails = new passbolt.controller.component.UserDetailsController($('.js_wsp_users_sidebar_second', this.element), {
				'selectedUsers': this.options.selectedUsers
			});

			// Filter the workspace.
	        var filter = null;
	        // A filter has been given in options.
	        if (this.options.filter) {
		        filter = this.options.filter;
	        } else {
		        filter = new passbolt.model.Filter({
			        'label': __('All users'),
			        'type': passbolt.model.Filter.SHORTCUT
		        });
	        }
			mad.bus.trigger('filter_users_browser', filter);
        },

		/**
		 * Destroy the workspace.
		 */
		'destroy': function() {
			// Be sure that the primary & secondary workspace menus controllers will be destroyed also.
			$('#js_wsp_primary_menu_wrapper').empty();
			$('#js_wsp_secondary_menu_wrapper').empty();

			this._super();
		},

		/* ************************************************************** */
		/* LISTEN TO THE APP EVENTS */
		/* ************************************************************** */

		/**
		 * Observe when group is selected
		 * @param {HTMLElement} el The element the event occured on
		 * @param {HTMLEvent} ev The event which occured
		 * @param {passbolt.model.Group} group The selected group
		 * @return {void}
		 */
		'{mad.bus} group_selected': function (el, ev, group) {
			// @todo fixed in future canJs.
			if (!this.element) return;

			console.log('group selected');
			// reset the selected resources
			this.options.selectedUsers.splice(0, this.options.selectedUsers.length);
			// Set the new filter
			this.options.filter.attr({
				'foreignModels': {
					'Group': new can.List([group])
				},
				'type': passbolt.model.Filter.FOREIGN_MODEL
			});
			// propagate a special event on bus
			mad.bus.trigger('filter_users_browser', this.options.filter);

			// Add the group to the list of selected groups.
			this.options.selectedGroups.splice(0, this.options.selectedGroups.length);
			this.options.selectedGroups.push(group);
		},

		/**
		 * Event filter_users_browser.
		 * When a new filter is applied.
		 * @param {HTMLElement} el The element the event occured on
		 * @param {HTMLEvent} ev The event which occured
		 * @param {passbolt.model.Filter} filter, the filter being applied.
		 * @return {void}
		 */
		'{mad.bus} filter_users_browser': function (el, ev, filter) {
			// @todo fixed in future canJs.
			if (!this.element) return;

			// If the filter applied is "all groups", then empty the list of selected groups.
			if (typeof filter.name != 'undefined') {
				if(filter.name == 'all') {
					this.options.selectedGroups.splice(0, this.options.selectedGroups.length);
				}
			}
			this.options.selectedUsers.splice(0, this.options.selectedUsers.length);

			// Update the breadcrumb with the new filter.
			this.breadcrumCtl.load(filter);
		},


		/**
		 * Observe when the user requests a category creation
		 * @param {HTMLElement} el The element the event occured on
		 * @param {HTMLEvent} ev The event which occured
		 * @return {void}
		 */
		'{mad.bus} request_group_creation': function (el, ev, data) {
			// @todo fixed in future canJs.
			if (!this.element) return;

			var group = new passbolt.model.Group();

			// Get the dialog
			var dialog = new mad.controller.component.DialogController(null, {label: __('Create a new Group')})
				.start();

			// Attach the component to the dialog.
			var form = dialog.add(passbolt.controller.form.group.CreateFormController, {
				data: group,
				callbacks : {
					submit: function (data) {
						var instance = new passbolt.model.Group(
							data['passbolt.model.Group']
						)
						.save();
						dialog.remove();
					}
				}
			});
			form.load(group);
		},

		/**
		 * Observe when the user requests a group edition
		 * @param {HTMLElement} el The element the event occured on
		 * @param {HTMLEvent} ev The event which occured
		 * @return {void}
		 */
		'{mad.bus} request_group_edition': function (el, ev, group) {
			// @todo fixed in future canJs.
			if (!this.element) return;

			// get the dialog
			var dialog = new mad.controller.component.DialogController(null, {label: __('Edit a Group')})
				.start();

			// attach the component to the dialog
			var form = dialog.add(passbolt.controller.form.group.CreateFormController, {
				data: group,
				callbacks : {
					submit: function (data) {
						group.attr(data['passbolt.model.Group'])
							.save();
						dialog.remove();
					}
				}
			});

			form.load(group);
		},

		/**
		 * Observe when the user requests a group deletion
		 * @param {HTMLElement} el The element the event occured on
		 * @param {HTMLEvent} ev The event which occured
		 * @return {void}
		 */
		'{mad.bus} request_group_deletion': function (el, ev, group) {
			// @todo fixed in future canJs.
			if (!this.element) return;

			group.destroy();
		},

		/**
		 * Observe when the user requests a category creation
		 * @param {HTMLElement} el The element the event occured on
		 * @param {HTMLEvent} ev The event which occured
		 * @return {void}
		 */
		'{mad.bus} request_user_creation': function (el, ev, data) {
			// @todo fixed in future canJs.
			if (!this.element) return;

			// create the resource which will be used by the form builder to populate the fields
			var user = new passbolt.model.User({active:1});

			// get the dialog
			var dialog = new mad.controller.component.DialogController(null, {label: __('Add User')})
				.start();

			// attach the component to the dialog
			var form = dialog.add(passbolt.controller.form.user.CreateFormController, {
				data: user,
				action: 'create',
				callbacks : {
					submit: function (data) {
						var user = new passbolt.model.User(data['passbolt.model.User']);
						user.save(
							// success
							function() {
								dialog.remove();
							},
							function(v) {
								form.showErrors(JSON.parse(v.responseText)['body']);
							}
						);

					}
				}
			});
			form.load(user);
		},

		/**
		 * Observe when the user requests a user edition
		 * @param {HTMLElement} el The element the event occured on
		 * @param {HTMLEvent} ev The event which occured
		 * @param {passbolt.model.User} resource The target user to edit
		 * @return {void}
		 */
		'{mad.bus} request_user_edition': function (el, ev, user) {
			// @todo fixed in future canJs.
			if (!this.element) return;

			var self = this;
			// Retrieve the selected user
			user = this.options.selectedUsers[0];
			// get the dialog
			var dialog = new mad.controller.component.DialogController(null, {label: __('Edit User')})
				.start();
			// attach the component to the dialog
			var form = dialog.add(passbolt.controller.form.user.CreateFormController, {
				data: user,
				action: 'edit',
				callbacks : {
					submit: function (data) {
						user.attr(data['passbolt.model.User']).save(
							// Success.
							function() {
								dialog.remove();
							},
							// Error.
							function(v) {
								form.showErrors(JSON.parse(v.responseText)['body']);
							}
						);
					}
				}
			});
			form.load(user);
		},

		/**
		 * Observe when the user requests a user deletion
		 * @param {HTMLElement} el The element the event occured on
		 * @param {HTMLEvent} ev The event which occured
		 * @param {passbolt.model.User} user1 A target user to delete
		 * @param {passbolt.model.User} [user2 ...] Other users to delete
		 * @return {void}
		 */
		'{mad.bus} request_user_deletion': function (el, ev) {
			// @todo fixed in future canJs.
			if (!this.element) return;

			for (var i=2; i<arguments.length; i++) {
				var user = arguments[i];
				if (!(user instanceof passbolt.model.User)) {
					throw new mad.error.Exception('The parameter ' + i + ' should be an instance of passbolt.model.User');
				}
				user.destroy();
			}
		},

		/**
		 * Observe when a user requests to remove a user from a group.
		 * @param {HTMLElement} el The element the event occured on
		 * @param {HTMLEvent} ev The event which occured
		 * @return {void}
		 */
		'{mad.bus} request_remove_user_from_group': function (el, ev, selectedUsers, selectedGroups) {
			// @todo fixed in future canJs.
			if (!this.element) return;

			// Check Params.
			if(selectedGroups.attr("length") == 0) {
				return;
			}
			if(selectedUsers.attr("length") == 0) {
				return;
			}

			// Process and delete user from group.
			var groupId = selectedGroups[0]['id'];
			for (i in selectedUsers) {
				for (j in selectedUsers[i]['GroupUser']) {
					if (selectedUsers[i]['GroupUser'][j]['group_id'] == groupId) {
						// Delete userGroup.
						selectedUsers[i]['GroupUser'][j].destroy();
					}
				}
			}
		}
	});

});