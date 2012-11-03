/*
 * @page index Mad Squirrel
 * @tag home
 *
 * <p style="text-align:center; padding-top:75px; width:100%;">
 *	<img src="./logo.png" style="width:128px; height:128px;"/>
 *	<h1 style="text-align:center;">
 *		Passbolt Javascript
 *	</h1>
 *	<h1 style="text-align:center;">
 *		Documentation
 *	</h1>
 * </p>
 * 
 */

steal(
	'lib/jquery/jquery-1.8.2.min.js'
).then(
	'lib/xregexp/xregexp-all-min.js',
	'jquery/class',
	'jquery/controller',
	'jquery/model',
	'jquery/view/ejs',
	'mad/core/class.js'
).then(
	'mad/bootstrap/appBootstrap.js',
	'mad/error/exception.js',
	'mad/error/errorHandler.js',
	'mad/controller/appController.js',
	'mad/controller/controller.js',
	'mad/controller/componentController.js',
	'mad/controller/component/buttonController.js',
	'mad/controller/component/containerController.js',
	'mad/controller/component/gridController.js',
	'mad/controller/component/contextualMenuController.js',
	'mad/controller/component/dropDownMenuController.js',
	'mad/controller/component/menuController.js',
	'mad/controller/component/popupController.js',
	'mad/controller/component/tabController.js',
	'mad/controller/component/workspaceController.js',
	'mad/controller/component/treeController.js',
	'mad/controller/component/dynamicTreeController.js',
	'mad/core/singleton.js',
	'mad/event/eventBus.js',
	'mad/form/feedbackController.js',
	'mad/form/formController.js',
	'mad/form/formElement.js',
	'mad/form/formChoiceElement.js',
	'mad/form/element/dateController.js',
	'mad/form/element/dropdownController.js',
	'mad/form/element/checkboxController.js',
	'mad/form/element/radiobuttonController.js',
	'mad/form/element/textboxController.js',
	'mad/helper/controllerHelper.js',
	'mad/helper/htmlHelper.js',
	'mad/helper/routeHelper.js',
	'mad/helper/componentHelper.js',
	'mad/helper/component/boxDecorator.js',
	'mad/lang/i18n.js',
	'mad/model',
	'mad/model/action.js',
	'mad/model/state.js',
	'mad/model/validationRules.js',
	'mad/net/ajax.js',
	'mad/object/map.js',
	'mad/string/uuid.js',
	'mad/route/routeListener.js',
	'mad/route/dispatcherInterface.js',
	'mad/route/extensionControllerActionDispatcher.js',
	'mad/view/view.js'
	//    'mad/route/pageDispatcher.js',
);