steal(
	'mad/form/formController.js',
	'app/controller/component/secretStrengthController.js',
	'app/view/template/form/user/createForm.ejs'
).then(function () {

		/**
		 * @class passbolt.controller.form.user.CreateFormController
		 * @inherits {mad.form.FormController}
		 * @parent index
		 *
		 * @constructor
		 * Instanciate a User Create Form Controller
		 *
		 * @param {HTMLElement} element the element this instance operates on.
		 * @param {Object} [options] option values for the controller.  These get added to
		 * this.options and merged with defaults static variable
		 * @return {passbolt.controller.form.user.CreateFormController}
		 */
		mad.form.FormController.extend('passbolt.controller.form.user.CreateFormController', /** @static */ {
			'defaults': {
				'templateBased': true,
				'passwordField': null
			}
		}, /** @prototype */ {

			/**
			 * After start hook.
			 * Create the form elements
			 *
			 * @return {void}
			 */
			'afterStart': function () {
				// temporary for update demonstration
				this.options.data.User = this.options.data.User || {};

				// Add category id hidden field
				/*this.addElement(
					new mad.form.element.TextboxController($('#js_field_category_id'), {
						modelReference: 'passbolt.model.User.Group.id'
					}).start()
				);*/

				var activeField = this.addElement(
				 new mad.form.element.TextboxController($('#js_field_user_active'), {
					 //modelReference: 'passbolt.model.User.active'
					 }).start()
				 );
				activeField.setValue("test");

				// Add user first name field
				this.addElement(
					new mad.form.element.TextboxController($('#js_field_first_name'), {
						modelReference: 'passbolt.model.User.first_name'
					}).start(),
					new mad.form.FeedbackController($('#js_field_first_name_feedback'), {}).start()
				);
				// Add user last name field
				this.addElement(
					new mad.form.element.TextboxController($('#js_field_last_name'), {
						modelReference: 'passbolt.model.User.last_name'
					}).start(),
					new mad.form.FeedbackController($('#js_field_last_name_feedback'), {}).start()
				);
				// Add resource username field
				this.addElement(
					new mad.form.element.TextboxController($('#js_field_username'), {
						modelReference: 'passbolt.model.User.username'
					}).start(),
					new mad.form.FeedbackController($('#js_field_username_feedback'), {}).start()
				);

				// Add secret data field
				this.options.passwordField = new mad.form.element.TextboxController($('#js_field_password'), {
					modelReference: 'passbolt.model.User.password'
				}).start();
				this.addElement(this.options.passwordField);
				// Add secret data in clear field
				this.options.passwordClear = this.addElement(
					new mad.form.element.TextboxController($('#js_field_password_clear'), {
						'state': 'hidden'
					}).start()
				);

				// Show/Hide the password
				this.options.showPwdButton = new mad.controller.component.ButtonController($('#js_show_pwd_button'))
					.start();

				// generate a password
				this.options.genPwdButton = new mad.controller.component.ButtonController($('#js_gen_pwd_button'))
					.start();

				// The secret strength component
				var secret = can.getObject('data.Secret.data', this.options);
				var secretStrength = passbolt.model.SecretStrength.getSecretStrength(secret);

				this.options.secretStrength = new passbolt.controller.component.SecretStrengthController($('#js_user_pwd_strength'), {
					secretStrength: secretStrength
				}).start();

				// Rebind controller events
				this.on();
			},

			/**
			 * Update the secret entropy
			 * @param {string} pwd The password to use to mesure the entropy
			 * @return {void}
			 */
			'updateSecretEntropy': function(pwd) {
				var secretStrength = passbolt.model.SecretStrength.getSecretStrength(pwd);
				this.options.secretStrength.load(secretStrength);
			},

			/* ************************************************************** */
			/* LISTEN TO THE VIEW EVENTS */
			/* ************************************************************** */

			/**
			 * Observe when the user is changing the password
			 * @param {HTMLElement} el The element the event occured on
			 * @param {HTMLEvent} ev The event which occured
			 * @return {void}
			 */
			'{passwordField} changed': function(el, ev) {
				this.updateSecretEntropy(this.options.passwordField.getValue());
			},

			/**
			 * Observe when the user is changing the password through the unscrumbeld field
			 * @param {HTMLElement} el The element the event occured on
			 * @param {HTMLEvent} ev The event which occured
			 * @return {void}
			 */
			'{passwordClear} changed': function(el, ev) {
				var value = this.getElement('js_field_password_clear').getValue();
				this.getElement('js_field_password').setValue(value);
				this.updateSecretEntropy(value);
			},

			/**
			 * Observe when the user wants to see the password unscrumbled
			 * @param {HTMLElement} el The element the event occured on
			 * @param {HTMLEvent} ev The event which occured
			 * @return {void}
			 */
			'{showPwdButton} click': function(el, ev) {
				var password = this.getElement('js_field_password');
				var passwordClear = this.getElement('js_field_password_clear');

				// if the password is already hidden
				if (password.state.is('hidden')) {
					// hide the unscrambled password
					passwordClear.setState('hidden');
					// show the password field
					password.setState('ready');
					// unpush the button
					this.options.showPwdButton.view.removeClass('selected');
				}
				else {
					// hide the password field
					password.setState('hidden');
					// display the unscrambled password
					passwordClear.setState('ready');
					passwordClear.setValue(password.getValue());
					// push the button
					this.options.showPwdButton.view.addClass('selected');
				}
			},

			/**
			 * Observe when the user wants to generate a password
			 * @param {HTMLElement} el The element the event occured on
			 * @param {HTMLEvent} ev The event which occured
			 * @return {void}
			 */
			'{genPwdButton} click': function(el, ev) {
				var value = passbolt.model.Secret.generate();
				this.getElement('js_field_password').setValue(value);
				this.getElement('js_field_password_clear').setValue(value);
				this.updateSecretEntropy(value);
			}

		});
	});