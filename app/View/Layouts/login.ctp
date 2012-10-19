<?php
/**
 * Login layout
 *
 * @copyright     copyright 2012 passbolt.com
 * @license       http://www.passbolt.com/license
 * @package       app.View.Layouts.login
 * @since         version 2.12.9
 */
?>
<!doctype html>
<!--[if lt IE 7]> <html class="no-js lt-ie9 lt-ie8 lt-ie7" lang="en"> <![endif]-->
<!--[if IE 7]>    <html class="no-js lt-ie9 lt-ie8" lang="en"> <![endif]-->
<!--[if IE 8]>    <html class="no-js lt-ie9" lang="en"> <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js" lang="en"> <!--<![endif]-->
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
  <title><?php echo $this->fetch('title'); ?></title>
  <meta name="viewport" content="width=device-width">
<?php echo $this->element('css'); ?>
<?php echo $this->element('scriptHeader'); ?>
</head>
<body class="login layout">
<?php echo $this->element('disclaimers'); ?>
<!-- header -->
<header>
<div class="header">
<?php echo $this->element('login/header'); ?>
</div>
</header>
<!-- main -->
<div role="main" id="container" class="container_16">
<?php echo $this->fetch('content'); ?>
</div>
<!-- footer -->
<footer>
<div class="footer">
<?php echo $this->element('footer'); ?>
</div>
</footer>
</div>
<?php echo $this->fetch('scriptBottom'); ?>
<?php echo $this->element('sql_dump')."\n"; ?>
</body>
</html>
