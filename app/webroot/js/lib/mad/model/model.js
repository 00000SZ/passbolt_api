steal('jquery/model').then(function () {

	/*
	 * @class mad.model.Model
	 * @inherits jQuery.Model
	 * @parent mad.core
	 * 
	 * The core class Model is an extension of the JavascriptMVC Model. This
	 * class allow us to easily hook common behavior, such as :
	 * <ul>
	 *	<li>
	 *		validating model attribute
	 *	</li>
	 * </ul>
	 * 
	 * @constructor
	 * creates a new model
	 * @return {mad.model.Model}
	 */
	$.Model('mad.model.Model', /** @static */ {

		/**
		 * The options to use to validate the model attributes.
		 * @type array
		 * @protected
		 */
		'validateRules': {},

		/**
		 * Check if an attribute is multiple or not. A multiple attribute is by definition
		 * a multiple association to another model
		 * @param {string} attrName The name of the attribute to test
		 * @return {boolean}
		 */
		'isMultipleAttribute': function (attrName) {
			return /models$/.test(this.attributes[attrName]);
		},

		/**
		 * Extract the models found in given string following the definition of the 
		 * model layer. Return an array formated like :
@codestart
[
	{
		model: {mad.net.Model},
		label: {string}
	},
	{
		...
	}
]
@codeend
		 * @param {string} str The string to work on
		 * @return {array}
		 */
		'getModelReferences': function (str) {
			var returnValue = [],
				split = str.split('.');

			for (var i in split) {
				// get the top model reference
				if (!returnValue.length) {
					// the top model has a upper case first character
					if (split[i][0] === split[i][0].toUpperCase()) {
						var modelName = split.slice(0, parseInt(i)+1).join('.');
						var model = $.String.getObject(modelName);
						returnValue.push({
							label: modelName,
							multiple: false,
							model: model
						});
					}
				} else {
					// after we found the top model reference, check for sub model
					var ownerModel = returnValue[returnValue.length - 1].model;
					// if the current split is a reference to a submodel
					if (ownerModel.attributes[split[i]]) {
						var attrName = ownerModel.attributes[split[i]];
						var modelName = attrName.slice(0,attrName.lastIndexOf('.'));
						var model = $.String.getObject(modelName);
						returnValue.push({
							label: split[i],
							multiple: /models$/.test(attrName),
							model: model
						});
					} else {
						// else the split is a reference to a scalar attribute
						returnValue.push({
							label: split[i]
						});
						break;
					}
				}
			}
			return returnValue;
		},

		/**
		 * Check if an attribute is an associated model attribute
		 * @param {string} name The attribute name
		 * @return {boolean}
		 */
		'isModelAttribute': function (name) {
			return /models$/.test(this.attributes[name]) || /model$/.test(this.attributes[name]);
		},

		/**
		 * Override the can model class to manage our custom server response format 
		 * and the CakePHP format. The findAll ajax request is overrided by canJS to
		 * use this function and map server response to the caller model.
		 * @return {Array} a [can.Model.List] of instances. Each instance is created with
		 * [mad.model.Model.model].
		 */
		'models': function (data) {
			//console.log('call models ', data);
			// if the provided data are formated as ajax server response
			if (mad.net.Response.isResponse(data)) {
				return can.Model.models.call(this, mad.net.Response.getData(data));
			}
			return can.Model.models.call(this, data);
		},

		/**
		 * Override the can model class to manage our custom server response format
		 * and the cakePHP format. The findOne ajax request is overrided by canJS to
		 * use this function and map server response to the caller model.
		 * @return {mad.model.Model}
		 */
		'model': function (data) {
			//console.log('call model ', data);
			data = data || {};
			// if the provided data are formated as ajax server response
			if (mad.net.Response.isResponse(data)) {
				data = mad.net.Response.getData(data);
				data = this.toCan(data);
			} else if (data[this.shortName]) {
				// if provided data are formated as CakePHP
				data = this.toCan(data);
			}
			return can.Model.model.call(this, data);
		},

		/**
		 * Validate an attribute
		 * @param {string} attrName The attribute name
		 * @param {mixed} value The attribute value
		 * @param {array} modelValues The model attributes values
		 * @return {boolean}
		 */
		'validateAttribute': function (attrName, value, modelValues) {
			var returnValue = true;
			if (this.validateRules[attrName]) {
				var rules = this.validateRules[attrName];
				if ($.isArray(rules)) {
					for (var i in rules) {
						var validateResult = mad.model.ValidationRules.validate(rules[i], value, modelValues);
						if (validateResult !== true) {
							if (returnValue === true) {
								returnValue = '';
							}
							returnValue += validateResult;
						}
					}
				} else {
					returnValue = mad.model.ValidationRules.validate(rules, value, modelValues);
				}
			}

			return returnValue;
		},

		/**
		 * Get all model instances in an array which match the search paramaters
		 * @param {array} data The array to search in
		 * @param {string} key The key to search
		 * @param {string} value The value of the key to search
		 * @return {array}
		 */
		'search': function (data, key, value) {
			var returnValue = [],
				split = key.split('.'),
				modelName = split[0],
				attrName = split[1];

			for (var i in data) {
				if ($.isArray(data[i][modelName])) {
					for (var j in data[i][modelName]) {
						if (data[i][modelName][j][attrName] == value) {
							returnValue.push(data[i]);
						}
					}
				} else {
					if (data[i][modelName][attrName] == value) {
						returnValue.push(data[i]);
					}
				}
				// search in children
				if (data[i].children) {
					var childrenSearch = mad.model.Model.search (data[i].children, key, value);
					if (childrenSearch.length) {
						returnValue = $.merge (returnValue, childrenSearch);
					}
				}
			}
			return returnValue;
		},
		
		/**
		 * Get on model instance in an array which match the search parameters
		 * and its value
		 * @param {array} data The array to search in
		 * @param {string} key The key to search
		 * @param {string} value The value of the key to search
		 * @return {mad.model.Model}
		 */
		'searchOne': function (data, key, value) {
			var returnValue = null;
			var searchResults = mad.model.Model.search(data, key, value);
			if (searchResults.length) {
				returnValue = searchResults[0];
			}
			return returnValue;
		},
		
		/**
		 * Format an array to the cakePHP format
		 * @param {array} data The array of data to format
		 * @return {array}
		 */
		'toCakePHP': function (data) {
			var returnValue = {};
			returnValue[this.shortName] = {};
			for (var name in data) {
				if (this.isModelAttribute(name)) {
					returnValue[name] = data[name];
				} else {
					returnValue[this.shortName][name] = data[name];
				}
			}
			return returnValue;
		},

		/**
		 * Format an array to the canJS format
		 * @param {array} data The array of data to format
		 * @return {array}
		 */
		'toCan': function (data) {
			var returnValue = data;
			returnValue = $.extend(true, {}, returnValue, returnValue[this.shortName]);
			delete returnValue[this.shortName];
			return returnValue;
		},

		/**
		 * Override the serialize feature used when serializing model (server communication,
		 * backup) to support specific attributes format such as date.
		 */
		'serialize' : {
			/**
			 * Support of the specific date format
			 * @param {mixed} val The date to serialize
			 * @param {string} type The data type (here date)
			 * @return {string}
			 */
			date : function( val, type ){
				if (typeof val == 'number' || typeof val == "string") {
					val = new Date(val);
				}
				return val.getYear() + "-" + (val.getMonth() + 1) + "-" + val.getDate() + " " + val.getHours() + ":" + val.getMinutes() + ":" + val.getMilliseconds();
			}
		}

	}, /** @prototype */ {
//		/**
//		 * Override the jmvc model serialize function, to serialize associated models
//		 * @return {object}
//		 deprecated or not, check with the create
//		 */
//		'serialize' : function () {
//			var returnValue = this._super();
//			// Check the serialization contain an array of Class
//			// Change done for JMVC 3.2.4
//			for (var i in returnValue) {
//				if ($.isArray(returnValue[i])) {
//					for (var j in returnValue[i]) {
//						returnValue[i][j] = returnValue[i][j].serialize();
//					}
//				}
//			}
//			return returnValue;
//		}

	});

});