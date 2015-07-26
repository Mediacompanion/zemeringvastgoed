<?php /* Template Name: Homepage */ ?>

<?php define( 'WP_USE_THEMES', false ); get_header(); ?>

<div id="homepage">

	<div id="usp">
		<div class="container">
			<div class="row">
				<ul>
					<li class="d3">
						<i class="fa fa-diamond"></i>
						<h2>Kwaliteit</h2>
					</li>
					<li class="d3">
						<i class="fa fa-graduation-cap"></i>
						<h2>Ervaring</h2>
					</li>
					<li class="d3">
						<i class="fa fa-heart"></i>
						<h2>Service</h2>
					</li>
					<li class="d3">
						<i class="fa fa-users"></i>
						<h2>Veelzijdig</h2>
					</li>
				</ul>
			</div>
		</div>
	</div>
	
	<div id="banner">
		<div class="left">
			<div class="slick-next"><i class="fa fa-arrow-right"></i></div>
			<div class="slick-prev"><i class="fa fa-arrow-left"></i></div>
			<div class="slider">
				<div class="slide">
					<img src="<?php echo get_template_directory_uri(); ?>/images/bannerslide1.jpg">
				</div>
				<div class="slide">
					<img src="<?php echo get_template_directory_uri(); ?>/images/bannerslide2.jpg">
				</div>
			</div>
		</div>
		<div class="right">
			<div class="container content">
				<div class="row">
					<div class="d12">
						<div class="logo">
							<img src="<?php echo get_template_directory_uri(); ?>/images/logo.png">
						</div>
						<h1>Zemering vastgoed zorg</h1>
						Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod
						tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam,
						quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo
						consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse
						cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non
						proident, sunt in culpa qui officia deserunt mollit anim id est laborum.
						<button class="btn"><a href="<?php echo get_home_url(); ?>/contact">Vraag offerte aan<i class="fa fa-arrow-right"></i></a></button>
					</div>
				</div>
			</div>
		</div>
	</div>
	
	<div id="home-projecten-banner">
		<div class="container">
			<div class="row">
				<div class="d12">
					<h3>
						Laatste projecten
						<div class="slick-next"><i class="fa fa-arrow-right"></i></div>
						<div class="slick-prev"><i class="fa fa-arrow-left"></i></div>
					</h3>
				</div>
			</div>
		</div>
	</div>

	<div id="home-projecten">
		<div class="container">
			<div class="row">
				<div class="projecten-slider">
					<?php
					$args = array( 'post_type' => 'projecten', 'posts_per_page' => 100, 'order' => 'ASC' );
					$loop = new WP_Query( $args );
					while ($loop->have_posts() ) : $loop->the_post(); ?> 

					<div class="slide">
						<a href="<?php echo get_permalink(); ?>">
							<?php if( get_the_post_thumbnail() ) { ?>
							<div class="projecten-image">
								<?php the_post_thumbnail(); ?>
								<div class="overlay">
									<i class="fa fa-search"></i>
								</div>
							</div>
							<?php } ?>
						</a>
						<div class="content">
							<a href="<?php echo get_permalink(); ?>">
								<h3> <?php the_title(); ?> </h3> 
							</a>
							<p> <?php echo substr(strip_tags(get_the_content()),0,300); ?> </p>
							<button class="btn">
								<a href="<?php echo get_permalink(); ?>">Bekijk project<i class="fa fa-arrow-right"></i></a>
							</button>
						</div>
					</div>

					<?php endwhile; ?>
				</div>
			</div>
		</div>
	</div>

</div>

<?php /* get_sidebar(); */ ?>
<?php get_footer(); ?>
