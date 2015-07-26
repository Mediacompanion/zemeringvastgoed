<?php /* Template Name: Contact */ ?>

<?php define( 'WP_USE_THEMES', false ); get_header(); ?>

<?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>

<div id="contact">
		
	<div id="page-banner">
		<?php echo do_shortcode('[gmap id="1"]'); ?>
	</div>

	<div id="content">
		<div class="adres">
			<ul>
				<li>Adres</li>
				<li>Coevordenstraat 408 - 410</li>
				<li>2541 SX Den Haag</li>
				<li>KvK nr: 27 36 57 40</li>
			</ul>
		</div>
		<div class="contact">
			<ul>
				<li>Contact</li>
				<li>T: 070 - 404 1720</li>
				<li>F: 070 - 385 6250</li>
				<li>E: info@zemeringvastgoedzorg.nl</li>
			</ul>
		</div>
		<div class="container">
			<div class="row">
				<div class="d3"></div>
				<div class="d6 contact-form">
					<?php the_content(); ?>
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
