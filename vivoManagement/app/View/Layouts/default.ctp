<?php echo $this->Html->docType('html5'); ?>
<html lang="en">
<head>
	<meta charset="utf-8">
	<title>
		<?php echo __('CakePHP: the rapid development php framework:'); ?>
		<?php echo $title_for_layout; ?>
	</title>
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta name="description" content="">
	<meta name="author" content="">

	<!-- Le styles -->
	<?php echo $this->Html->css('bootstrap-united.min'); ?>
	<style>
	body {
		padding-top: 60px; /* 60px to make the container go all the way to the bottom of the topbar */
	}
	</style>
	<?php
		echo $this->Html->css('bootstrap-responsive');
		echo $this->Html->css('http://ajax.googleapis.com/ajax/libs/jqueryui/1.8.21/themes/start/jquery-ui.css');
		echo $this->Html->script(array('https://ajax.googleapis.com/ajax/libs/jqueryui/1.8.21/jquery-ui.min.js'), array('block' => 'script'));
		echo $this->Html->script(array('https:////ajax.googleapis.com/ajax/libs/jquery/1.8.0/jquery.min.js'), array('block' => 'script'));
	?>
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.7/jquery.js"></script>
	<?php
		echo $this->Html->script('bootstrap');
		echo $this->fetch('script');
	?>
	<!-- Le HTML5 shim, for IE6-8 support of HTML5 elements -->
	<!--[if lt IE 9]>
	<script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
	<![endif]-->

	<!-- Le fav and touch icons -->
	<!--
	<link rel="shortcut icon" href="/ico/favicon.ico">
	<link rel="apple-touch-icon-precomposed" sizes="144x144" href="/ico/apple-touch-icon-144-precomposed.png">
	<link rel="apple-touch-icon-precomposed" sizes="114x114" href="/ico/apple-touch-icon-114-precomposed.png">
	<link rel="apple-touch-icon-precomposed" sizes="72x72" href="/ico/apple-touch-icon-72-precomposed.png">
	<link rel="apple-touch-icon-precomposed" href="/ico/apple-touch-icon-57-precomposed.png">
	-->
	<?php
		echo $this->fetch('meta');
		echo $this->fetch('css');
	?>
</head>

<body>
	<!-- Insert the header navigation here -->
	<?php echo $this->element('top_nav'); ?>
	<div class="container">

		<?php echo $this->Session->flash(); ?>

		<?php echo $this->fetch('content'); ?>

	</div> <!-- /container -->

	<!-- Le javascript
    ================================================== -->
	<!-- Placed at the end of the document so the pages load faster -->
</body>
</html>
