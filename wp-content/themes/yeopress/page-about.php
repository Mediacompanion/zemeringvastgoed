<?php /* Template Name: About */ ?>

<?php define( 'WP_USE_THEMES', false ); get_header(); ?>

<?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>

<div id="about">
		
	<div id="page-banner">
		<?php echo the_post_thumbnail(); ?>
	</div>

	<div id="content">
		<div class="container">
			<div class="row">
				<div class="d3"></div>
				<div class="d6">
					<div class="logo">
						<img src="<?php echo get_template_directory_uri(); ?>/images/logo.png">
					</div>
					<h1><?php the_title(); ?></h1>
					<p><?php the_content(); ?></p>
				</div>
			</div>
		</div>
	</div>

</div>

<?php endwhile; else : ?>
	<p><?php _e( 'Sorry, no posts matched your criteria.' ); ?></p>
<?php endif; ?>

<?php /* get_sidebar(); */ ?>
<?php get_footer(); ?>
