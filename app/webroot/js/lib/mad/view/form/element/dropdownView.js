steal(
	MAD_ROOT + '/view/form/formElementView.js'
).then(function ($) {

	/*
	 * @class mad.view.form.element.DropdownView
	 * @inherits mad.view.form.FormElement
	 * @hide
	 * 
	 * @constructor
	 * 
	 * Creates a new Dropdown Form Element View
	 * 
	 * @param {HTMLElement} element the element this instance operates on.
	 * @param {Object} [options] option values for the controller. These get added to
	 * this.options and merged with defaults static variable
	 * @return {mad.view.form.element.DropdownView}
	 */
	mad.view.form.FormElementView.extend('mad.view.form.element.DropdownView', /** @static */ {

	}, /** @prototype */ {

		'setAvailableValues': function (availableValues) {
			for (var value in availableValues) {
				var html = '<option value="' + value + '">' + availableValues[value] + '</option>';
				this.element.append(html);
			}
		},
		
		/**
		 * Set the value of the dropdown form element
		 * @param {mixed} value The value to set
		 * @return {void}
		 */
		'setValue': function (value) {
			this.element.val(value);
		},
		
		/* ************************************************************** */
		/* LISTEN TO THE VIEW EVENTS */
		/* ************************************************************** */

		/**
		 * Listen to the view event change
		 * @param {HTMLElement} el The element the event occured on
		 * @param {HTMLEvent} ev The event which occured
		 * @return {void}
		 */
		'change': function (el, event) {
			el.trigger('changed', el.val());
		}
		
	});
});