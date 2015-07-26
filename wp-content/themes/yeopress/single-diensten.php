<?php get_header(); ?>
	
<?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>

	<div id="page-banner">
		<?php echo get_the_post_thumbnail(); ?>
	</div>

	<div id="diensten-single">
		<div class="container">
			<div class="row">
				<div class="d3"></div>
				<div class="d6 project-single">
					<h1><?php the_title(); ?></h1>
					<p><?php the_content(); ?></p>
				</div>
			</div>
		</div>
	</div>

<?php endwhile; else : ?>
	<p><?php _e( 'Sorry, no posts matched your criteria.' ); ?></p>
<?php endif; ?>

<?php /* get_sidebar(); */ ?>
<?php get_footer(); ?>
