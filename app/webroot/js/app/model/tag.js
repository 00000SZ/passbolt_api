steal(
	'jquery/model',
	'mad/model/serializer/cakeSerializer.js'
).then(function () {

		/*
		 * @class passbolt.model.ItemTag
		 * @inherits {mad.model.Model}
		 * @parent index
		 *
		 * The itemTag model
		 *
		 * @constructor
		 * Creates an ItemTag
		 * @param {array} data
		 * @return {passbolt.model.ItemTag}
		 */
		mad.model.Model('passbolt.model.Tag', /** @static */ {

			'validateRules': {
				'name': ['text']
			},

			attributes: {
				'id': 'string',
				'name': 'string',
				'created': 'string',
				'modified': 'string',
				'created_by':'string',
				'modified_by': 'string'
			},

			'create': function (attrs, success, error) {
				var self = this;
				var params = mad.model.serializer.CakeSerializer.to(attrs, this);
				return mad.net.Ajax.request({
					url: APP_URL + 'tags',
					type: 'POST',
					params: params,
					success: success,
					error: error
				}).pipe(function (data, textStatus, jqXHR) {
						// pipe the result to convert cakephp response format into can format
						// else the new attribute are not well placed
						var def = $.Deferred();
						def.resolveWith(this, [mad.model.serializer.CakeSerializer.from(data, self)]);
						return def;
					});
			},

			'destroy': function (id, success, error) {
				var params = {id: id};
				return mad.net.Ajax.request({
					url: APP_URL + '/tags/{id}',
					type: 'DELETE',
					params: params,
					success: success,
					error: error
				});
			},

			'findAll': function (params, success, error) {
				return mad.net.Ajax.request({
					url: APP_URL + 'tags',
					type: 'GET',
					params: params,
					success: success,
					error: error
				});
			},

		}, /** @prototype */ {

			/**
			 * Override the constructor function
			 * Listen change on Category, and update the model when a category has been destroyed
			 */
			'init': function () {
				var self = this;
			},

			'destroy': function () {
				// @todo unbind the passbolt.model.Category destroyed event, if it does not done automatically
				this._super();
			}
		});
	});
