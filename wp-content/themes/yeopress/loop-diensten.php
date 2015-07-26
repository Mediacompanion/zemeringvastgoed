<div id="banner">
    <div class="container">
        <div class="row">
            <div class="d3"></div>
            <div class="d6">
                <h1>Onze diensten</h1>
                <p>Waarmee kunnen wij u van dienst zijn?</p>
            </div>
        </div>
    </div>
</div>

<div class="content">
    <div class="container">
        <div class="row">
            <div class="d3"></div>
            <div class="d6">
                <p>
                    Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod
                    tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam,
                    quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo
                    consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse
                    cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non
                    proident, sunt in culpa qui officia deserunt mollit anim id est laborum.
                </p>
                <ul class="first">
                <?php $i=0; ?>
                <?php if (have_posts()): while (have_posts()) : the_post(); ?>
                <?php $i++ ?>
                    
                    <a href="<?php the_permalink(); ?>">
                        <li>
                            <?php the_title(); ?>
                        </li>
                    </a>

                <?php if($i == 5) { ?>
                    </ul> <ul class="second">
                <?php } ?>

                <?php endwhile; ?>
                </ul>
            </div>
        <?php else: ?>
            <h2><?php _e( 'Sorry, nothing to display.', 'html5blank' ); ?></h2>
        <?php endif; ?>
        </div>
    </div>
</div>