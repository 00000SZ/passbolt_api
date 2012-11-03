steal( 
	'mad/error/errorHandler.js'
).then(function () {

	/*
	 * @class app.helper.ErrorHandler
	 * 
	 * @inherits {mad.error.ErrorHandler}
	 * @parent index
	 * 
	 * Our implementation of the error handler for passbolt.
	 * It will manage all errors and exceptions which occure on Passbolt
	 */
	mad.error.ErrorHandler.extend('passbolt.helper.ErrorHandler', /** @static */ {

		/**
		 * Handle Exception
		 * @param {Exception} exception
		 * @return {void}
		 */
		'handleException': function (exception) {
			// send a notification on the events' bus
			if (mad.eventBus) {
				mad.eventBus.trigger('passbolt_notify', {
					'status': exception.name,
					'title': exception.title,
					'message': exception.message
				});
			}
			// call the parent which is displaying in the console
			mad.error.ErrorHandler.handleException(exception);
		},

		/**
		 * Handle Error
		 * @param {string} status Error status
		 * @param {string} title Error title
		 * @param {string} message Error message
		 * @param {mixed} data Error associated data
		 * @return {void}
		 */
		'handleError': function (status, title, message, data) {
			// send a notification on the events' bus
			if (mad.eventBus) {
				mad.eventBus.trigger('passbolt_notify', {
					'status': status,
					'title': title,
					'message': message,
					'data': data
				});
			}
			// call the parent which is displaying in the console
			mad.error.ErrorHandler.handleError(status, title, message, data);
		}
	}, /** @prototype */ {

	});
});