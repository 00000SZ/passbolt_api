steal(
	'mad/view',
	'mad/view/component/tree.js',
	'app/view/template/component/comments.ejs'
).then(function () {

		/*
		 * @class passbolt.view.component.CommentsList
		 * @inherits mad.view.component.Tree.extend
		 */
		mad.view.component.Tree.extend('passbolt.view.component.CommentsList', /** @static */ {

		}, /** @prototype */ {

			'init': function (el, options) {
				this._super(el, options);
			},

			/* ************************************************************** */
			/* LISTEN TO THE VIEW EVENTS */
			/* ************************************************************** */

			/**
			 * Observe when the user clicks on the delete button for comment
			 * @param {HTMLElement} el The element the event occured on
			 * @param {HTMLEvent} ev The event which occured
			 * @return {void}
			 */
			'.actions a .icon.delete click': function (el, ev) {
				ev.stopPropagation();
				ev.preventDefault();

				var data = null;
				var li = el.parents('li.comment-wrapper');

				if (this.controller.getItemClass()) {
					data = li.data(this.controller.getItemClass().fullName);
				} else {
					data = li[0].id;
				}

				el.trigger('request_delete_comment', data);
			}
		});
	});