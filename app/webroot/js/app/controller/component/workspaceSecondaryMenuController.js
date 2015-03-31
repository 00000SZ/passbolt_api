steal(
	'mad/controller/componentController.js',
	'app/view/template/component/workspaceSecondaryMenu.ejs'
).then(function () {

	/*
	 * @class passbolt.controller.component.WorkspaceSecondaryMenuController
	 * @inherits mad.controller.component.ComponentController
	 * @parent index 
	 * 
	 * Our passbolt workspace secondary menu controller
	 * 
	 * @constructor
	 * Creates a new workspace secondary menu controller
	 * 
	 * @param {HTMLElement} element the element this instance operates on.
	 * @param {Object} [options] option values for the controller. These get added to
	 * this.options and merged with defaults static variable 
	 * @return {passbolt.controller.component.WorkspaceSecondaryMenuController}
	 */
	mad.controller.ComponentController.extend('passbolt.controller.component.WorkspaceSecondaryMenuController', /** @static */ {

		'defaults': {
			'label': 'Workspace Secondary Menu Controller'
		}

	}, /** @prototype */ {

		/**
		 * after start hook.
		 * @return {void}
		 */
		'afterStart': function () {
			// Manage the display of the sidebar
			var showSidebar = mad.Config.read('ui.workspace.showSidebar');
			this.options.viewSidebarButton = new mad.controller.component.ToggleButtonController($('#js_wk_secondary_menu_view_sidebar_button'), {
				state: showSidebar ? 'selected' : 'ready'
			}).start();
			
			// Rebind controller events
			this.on();
		},

		/* ************************************************************** */
		/* LISTEN TO THE APP EVENTS */
		/* ************************************************************** */

		/**
		 * Observe when another component wants the sidebar to be hidden.
		 * @param {HTMLElement} el The element the event occured on
		 * @param {HTMLEvent} ev The event which occured
		 * @return {void}
		 */
		'{mad.bus} workspace_showSidebar': function (el, ev, show) {
			if (!show && this.options.viewSidebarButton.state.is('selected')) {
				this.options.viewSidebarButton.setState('ready');
			}
		},

		/**
		 * Observe when the user wants to view the side bar
		 * @param {HTMLElement} el The element the event occured on
		 * @param {HTMLEvent} ev The event which occured
		 * @return {void}
		 */
		'{viewSidebarButton} click': function (el, ev) {
			var showSidebar = !mad.Config.read('ui.workspace.showSidebar');
			mad.Config.write('ui.workspace.showSidebar', showSidebar);
			mad.bus.trigger('workspace_showSidebar', showSidebar);
		}

	});

});