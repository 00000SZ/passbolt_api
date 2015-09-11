import 'mad/view';
import 'app/view/template/component/appFilter.ejs!';


/**
 * @inherits mad.view.View
 */
var AppFilter = passbolt.view.component.AppFilter = mad.View.extend('passbolt.view.component.AppFilter', /** @static */ {

	defaults: {}

}, /** @prototype */ {

	/* ************************************************************** */
	/* LISTEN TO VIEW EVENTS */
	/* ************************************************************** */

	/**
	 * Observe when the user update the filter
	 * @param {HTMLElement} el The element the event occured on
	 * @param {HTMLEvent} ev The event which occured
	 * @return {void}
	 */
	'form submit': function(el, ev) {
		this.element.trigger('update');
	}

});
export default AppFilter;
