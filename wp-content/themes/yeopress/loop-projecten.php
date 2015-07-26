<div id="banner">
    <div class="container">
        <div class="row">
            <div class="d12">
                <h1>Onze projecten</h1>
                <p>Een blik in ons werk</p>
            </div>
        </div>
    </div>
</div>

<div class="content">
    <div class="container">
        <div class="row">

        <?php if (have_posts()): while (have_posts()) : the_post(); ?>

            <div class="d4">
                <a href="<?php the_permalink(); ?>">
                    <div class="project">
                        <i class="fa fa-search"></i>
                        <h2><?php the_title(); ?></h2>
                        <?php if ( has_post_thumbnail()) : ?>
                            <?php the_post_thumbnail(); ?>
                        <?php endif; ?>
                    </div>
                </a>
            </div>

        <?php endwhile; ?>

        <?php else: ?>
            <h2><?php _e( 'Sorry, nothing to display.', 'html5blank' ); ?></h2>
        <?php endif; ?>
        </div>
    </div>
</div>