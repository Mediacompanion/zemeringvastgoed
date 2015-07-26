<!DOCTYPE html>
<!--[if lt IE 7]><html class="no-js lt-ie9 lt-ie8 lt-ie7" <?php language_attributes() ?>><![endif]-->
<!--[if IE 7]><html class="no-js lt-ie9 lt-ie8" <?php language_attributes() ?>><![endif]-->
<!--[if IE 8]><html class="no-js lt-ie9" <?php language_attributes() ?>><![endif]-->
<!--[if gt IE 8]><!--><html class="no-js" <?php language_attributes() ?>><!--<![endif]-->
    <head>
        <meta charset="<?php bloginfo( 'charset' ) ?>">
        <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
        <meta name="viewport" content="width=device-width">
        <title><?php wp_title( '|', true, 'right' ) ?></title>
		<meta name="author" content="">
		<link rel="author" href="">
		
		<script type="text/javascript" src="<?php echo get_template_directory_uri(); ?>/js/vendor/jquery/jquery.js"></script>
		<script type="text/javascript" src="<?php echo get_template_directory_uri(); ?>/js/vendor/slick/slick.js"></script>
		<script type="text/javascript" src="<?php echo get_template_directory_uri(); ?>/js/vendor/fancybox/fancybox.js"></script>
		<link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css">
		<?php wp_head() ?>
    </head>
    <body <?php body_class() ?>>
    	<div id="fake-navigation"></div>
    	<div id="hamburger">
    		<div class="icon">
    			<i class="fa fa-bars"></i>
    		</div>
    	</div>
		<div id="main-navigation">
			<div class="container">
				<div class="row">
					<?php wp_nav_menu(array(
						'theme_location' => 'main-nav',
						'container'      => 'nav',
						'container_id'   => 'primary-nav'
					)) ?>
					<div class="phone">
						<i class="fa fa-phone"></i><span>(+31) 6 11 22 33 44</span>
					</div>
				</div>
			</div>
		</div>
		<div id="content-wrap">
