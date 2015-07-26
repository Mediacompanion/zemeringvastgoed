<?php get_header(); ?>
	
<?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>

	<div id="page-banner">
		<?php echo the_post_thumbnail(); ?>
	</div>

	<div id="project-single">
		<div class="container">
			<div class="row">
				<div class="d3"></div>
				<div class="d6 project-single">
					<h1><?php the_title(); ?></h1>
					<p><?php the_content(); ?></p>
					<div class="slider-container">
						<div class="slick-prev"><i class="fa fa-arrow-left"></i></div>
						<div class="slick-next"><i class="fa fa-arrow-right"></i></div>
						<div class="slider">
							<?php $projectSlides = types_render_field("projecten-slide", array("output"=>"raw")); ?>
							<?php $projectSlides = explode(" ", $projectSlides); ?>
							<?php foreach ($projectSlides as $projectSlide) {
								?>
								<a class="fancybox" rel="group" href="<?php echo $projectSlide;?>" data-lightbox="projecten">
									<li class="slide">
										<img src="<?php echo $projectSlide;?>">
									</li>
								</a>
								<?php
							} ?>
							<?php // echo $projectSlides; ?>
						</div>
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
