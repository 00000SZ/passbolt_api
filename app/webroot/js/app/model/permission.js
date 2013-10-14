steal(
	'jquery/model',
	'app/model/category.js',
	'app/model/permissionType.js',
	'mad/model/serializer/cakeSerializer.js'
).then(function () {

	/*
	 * @class passbolt.model.Permission
	 * @inherits {mad.model.Model}
	 * @parent index
	 * 
	 * The permission model
	 * 
	 * @constructor
	 * Creates a resource
	 * @param {array} data 
	 * @return {passbolt.model.Permission}
	 */
	mad.model.Model('passbolt.model.Permission', /** @static */ {

		'validateRules': {
			'aco_foreign_key': ['required', 'uid'],
			'aro_foreign_key': ['required', 'uid'],
			'aro_foreign_label': ['required', 'email'],
			'type': [
				'required', 
				{
					'rule': 'foreignRule',
					'options': {
						'model': passbolt.model.PermissionType,
						'attribute': 'serial'
					}
				}
			]
		},

		attributes: {
			'id': 'string',
			'type': 'string',
			'aco': 'string',
			'aco_foreign_key': 'string',
			'aro': 'string',
			'aro_foreign_key': 'string',
			'aro_foreign_label': 'string',
			'PermissionType': 'passbolt.model.PermissionType.model',
			'Resource': 'passbolt.model.Resource.model',
			'Category': 'passbolt.model.Category.model',
			'User': 'passbolt.model.User.model',
			'Group': 'passbolt.model.Group.model'
		},

		'create' : function (attrs, success, error) {
			var self = this;
			// build the uri functions of the aco instance
			var uri = 'permissions/' + attrs['aco'].toLowerCase() + '/' + attrs['aco_foreign_key'];
			delete attrs['aco'], attrs['aco_foreign_key'];
			// format the data to send to be understable by cake
			var params = mad.model.serializer.CakeSerializer.to(attrs, this);
			// call the server
			return mad.net.Ajax.request({
				url: APP_URL + uri,
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

		'destroy' : function (id, success, error) {
			var params = {id:id};
			return mad.net.Ajax.request({
				url: APP_URL + 'permissions/{id}',
				type: 'DELETE',
				params: params,
				success: success,
				error: error
			});
		},

		'findAll': function (params, success, error) {
			var uri = 'permissions';
			if(typeof params.aco != 'undefined' && typeof params.aco_foreign_key != 'undefined') {
				uri += '/' + params.aco.toLowerCase() + '/' + params.aco_foreign_key;
			}
			return mad.net.Ajax.request({
				url: APP_URL + uri,
				type: 'GET',
				params: params,
				success: success,
				error: error
			});
		},

		'findOne': function (params, success, error) {
			return mad.net.Ajax.request({
				url: APP_URL + '/permissions/{id}',
				type: 'GET',
				params: params,
				success: success,
				error: error
			});
		},

		'update' : function(id, attrs, success, error) {
			var self = this;
			// remove not desired attributes
			delete attrs.created;
			delete attrs.modified;
			// format data as expected by cakePHP
			var params = mad.model.serializer.CakeSerializer.to(attrs, this);
			// add the root of the params, it will be used in the url template
			params.id = id;
			return mad.net.Ajax.request({
				url: APP_URL + '/permissions/{id}',
				type: 'PUT',
				params: params,
				success: success,
				error: error
			}).pipe(function (data, textStatus, jqXHR) {
				// pipe the result to convert cakephp response format into can format
				var def = $.Deferred();
				def.resolveWith(this, [mad.model.serializer.CakeSerializer.from(data, self)]);
				return def;
			});
		}

	}, /** @prototype */ {
		
		/**
		 * Check if the permission is a direct permission for the given aco and aro instances.
		 * @param {mad.model.Model} obj The target instance to test if the permission is direct for it. The instance
		 * can be of whatever type (Resource, Category ...)
		 * @return {boolean}
		 */
		'isDirect': function(acoInstance) {
			var permAcoModel = can.getObject('passbolt.model.' + this.aco);
			if(acoInstance instanceof permAcoModel && acoInstance.id === this.aco_foreign_key)
				return true;
			return false;
		}
		
	});
});
