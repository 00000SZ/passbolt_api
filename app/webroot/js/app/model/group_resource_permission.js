import 'jquery/model';
import 'app/model/category';
import 'app/model/permission_type';
import 'mad/model/serializer/cake_serializer';

/**
 * @inherits {mad.model.Model}
 * @parent index
 *
 * Group resource permission model
 *
 * @constructor
 * Creates a resource
 * @param {array} data
 * @return {passbolt.model.GroupResourcePermission}
 */
var GroupResourcePermission = passbolt.model.GroupResourcePermission = mad.model.Model.extend('passbolt.model.GroupResourcePermission', /** @static */ {
	attributes: {
		'permission_id': 'string',
		'permission_type': 'number'
	}
}, {});
export default GroupResourcePermission;