import 'app/component/sidebar_section';
import 'app/component/form/resource/edit_description';
import 'app/view/component/sidebar_section/description';
import 'app/model/resource';

passbolt.component.sidebarSection = {} || passbolt.component.sidebarSection;

/**
 * @inherits mad.component.SidebarSection
 * @parent index
 *
 * @constructor
 * Creates a new Sidebar Section Description Controller.
 *
 * @param {HTMLElement} element the element this instance operates on.
 * @param {Object} [options] option values for the controller.  These get added to
 * this.options and merged with defaults static variable
 * @return {passbolt.component.sidebarSection.Description}
 */
var Description = passbolt.component.sidebarSection.Description = mad.Component.extend('passbolt.component.sidebarSection.Description', /** @static */ {
	defaults : {
		label : 'Sidebar Section Description Controller',
		viewClass : passbolt.view.component.sidebarSection.Description,
		// Current resource instance.
		resource : null,
		// Form to edit the description.
		editDescriptionFormCtrl : null
	}

}, /** @prototype */ {

	/**
	 * before start hook.
	 * @return {void}
	 */
	beforeRender: function () {
		this._super();
		// pass the new resource to the view
		this.setViewData('resource', this.options.resource);
		this.setViewData('editable', passbolt.model.Permission.isAllowedTo(this.options.resource, passbolt.UPDATE));
	},

	/**
	 * Hook After Start
	 * Will basically instantiate the form to edit the description
	 */
	afterStart : function() {
		var self = this;

		// create a form to edit the description
		this.options.editDescriptionFormCtrl = new passbolt.component.form.resource.EditDescription($('#js_rs_details_edit_description', this.element), {
			'templateBased': true,
			'templateUri': 'app/view/template/form/resource/editDescription.ejs',
			'resource': this.options.resource,
			'state': 'hidden',
			'data': {
				'Resource': this.options.resource
			},
			'callbacks': {
				'submit': function (data) {
					self.options.resource.update({
						'description': data['passbolt.model.Resource']['description']
					}, function () {
						// No callback required, the parent controller will refresh itself,
						// and its children components while the current resource is
						// updated with success.
					});
				}
			}
		}).start();
	},

	/**
	 * Observe when the user want to edit the instance's resource description
	 * @param {HTMLElement} el The element
	 * @param {HTMLEvent} ev The event which occured
	 * @return {void}
	 */
	' request_resource_description_edit' : function(el, ev) {
		if(!this.state.is('edit')) {
			this.setState('edit');
		}
		else {
			this.setState('ready');
		}
	},

	/* ************************************************************** */
	/* LISTEN TO THE STATE CHANGES */
	/* ************************************************************** */

	/**
	 * Switch to edit mode
	 * @param {boolean} go Go or leave the state
	 */
	'stateEdit': function(go) {
		if (go) {
			this.options.editDescriptionFormCtrl.setState('ready');
			this.view.showDescription(false);
		}
		else {
			this.options.editDescriptionFormCtrl.setState('hidden');
			this.view.showDescription(true);
		}
	}

});
