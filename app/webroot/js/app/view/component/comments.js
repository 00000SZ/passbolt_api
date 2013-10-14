steal(
	'mad/view',
	'app/view/template/component/comments.ejs'
).then(function () {

		/*
		 * @class passbolt.view.component.Comments
		 * @inherits mad.view.View
		 */
		mad.view.View.extend('passbolt.view.component.Comments', /** @static */ {

		}, /** @prototype */ {

			'init': function (el, options) {
				this._super(el, options);
			},

			/* ************************************************************** */
			/* LISTEN TO THE VIEW EVENTS */
			/* ************************************************************** */


			/**
			 * Observe when the user clicks on the plus button, to add a comment
			 * @param {HTMLElement} el The element the event occured on
			 * @param {HTMLEvent} ev The event which occured
			 * @return {void}
			 */
			'.icon.create click': function (el, ev) {
				// Displays the add comment form
				this.controller.addFormController.setState('ready');
			},

			/**
			 * Observe when the user clicks on submit to save the comment
			 * @param {HTMLElement} el The element the event occured on
			 * @param {HTMLEvent} ev The event which occured
			 * @return {void}
			 */
			'a.button.comment-submit click': function (el, ev) {
				el.trigger('submit');
			}
		});
	});