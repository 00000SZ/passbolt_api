steal(
	'mad/form/formController.js',
	'app/view/component/comments.js',
	'app/view/template/form/comment/addForm.ejs'
).then(function () {

		/**
		 * @class passbolt.controller.form.resource.CreateFormController
		 * @inherits {mad.form.FormController}
		 * @parent index
		 *
		 * @constructor
		 * Instanciate a Resource Create Form Controller
		 *
		 * @param {HTMLElement} element the element this instance operates on.
		 * @param {Object} [options] option values for the controller.  These get added to
		 * this.options and merged with defaults static variable
		 * @return {passbolt.controller.form.resource.CreateFormController}
		 */
		mad.form.FormController.extend('passbolt.controller.form.comment.CreateFormController', /** @static */ {
			'defaults': {
				'templateBased': true,
				/**
				 * the foreign Model on which to plug the comments system
				 */
				'foreignModel' : null,
				/**
				 * The foreign id where to plug the new comments
				 */
				'foreignId'		 : null,
				/**
				 * default callback
				 */
				'callbacks': {
					'submit': function (data) {
						// TODO : validate
						var instance = new passbolt.model.Comment(data['passbolt.model.Comment'])
							.save();
					}
				}
			}
		}, /** @prototype */ {

			/**
			 * After start hook.
			 * Create the form elements
			 *
			 * @return {void}
			 */
			'afterStart': function () {
				// parent_id hidden field
				this.addElement(
					new mad.form.element.TextboxController($('.comment_parent_id', this.element), {
						modelReference: 'passbolt.model.Comment.parent_id'
					}).start()
				);

				// foreign_model hidden field
				this.addElement(
					new mad.form.element.TextboxController($('.comment_foreign_model', this.element), {
						modelReference: 'passbolt.model.Comment.foreign_model'
					}).start().setValue('Resource')
				);

				// foreign_id hidden field
				this.addElement(
					new mad.form.element.TextboxController($('.comment_foreign_id', this.element), {
						modelReference: 'passbolt.model.Comment.foreign_id'
					}).start().setValue(this.options.foreignId)
				);

				//
				this.addElement(
					new mad.form.element.TextboxController($('.comment_content', this.element), {
						modelReference: 'passbolt.model.Comment.content'
					}).start()
				);
			}

			/* ************************************************************** */
			/* LISTEN TO THE VIEW EVENTS */
			/* ************************************************************** */



		});
	});