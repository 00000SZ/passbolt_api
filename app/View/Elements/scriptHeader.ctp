<?php 
/**
 * Script Header element
 *
 * @copyright    Copyright 2012, Passbolt.com
 * @license      http://www.passbolt.com/license
 * @package      app.View.Elements.scriptHeader
 * @since        version 2.12.9
 */
?>
<script src="js/lib/compat/modernizr.js"></script>
<script type="application/javascript">
var cakephpConfig = {
	app : {
		name: "<?php echo Configure::read('App.name'); ?>",
		punchline: "<?php echo Configure::read('App.punchline'); ?>",
		copyright: "<?php echo Configure::read('2013 &copy; Passbolt.com'); ?>",
		title: "<?php echo Configure::read('App.title'); ?>",
		version: {
			number: "<?php echo Configure::read('App.version.number'); ?>",
			name: "<?php echo Configure::read('App.version.name'); ?>",
			song: "<?php echo Configure::read('App.version.song'); ?>"
		},
		url: "<?php echo Router::url('/',true); ?>",
		debug: "<?php echo Configure::read('debug'); ?>"
	},
	user : {
		id: "<?php echo User::get('id') ?>"
	},
	roles : <?php
			$roles = Hash::combine($roles, '{n}.Role.name', '{n}.Role.id');
			echo json_encode($roles);
	?>,
	image_storage : {
		public_path: "<?php echo Configure::read('ImageStorage.publicPath') ?>"
	}
};
</script>
<?php echo $this->fetch('scriptHeader'); ?>
