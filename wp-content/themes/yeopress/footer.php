		</div>
		<footer id="page-footer">
			<div class="container">
				<div class="row">
					<div class="d5">
						<h3>Zemering vastgoed zorg</h3>
						<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod
						tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam,
						quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo
						consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse
						cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non
						proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>
						<button class="btn"><a href="<?php echo get_home_url(); ?>/contact">Vraag offerte aan<i class="fa fa-arrow-right"></i></a></button>
					</div>
					<div class="d1"></div>
					<div class="d3">
						<h3>Diensten</h3>
						<?php wp_nav_menu( array( 'theme_location' => 'secondary-nav' ) ); ?>
					</div>
					<div class="d3">
						<h3>Social media</h3>
						<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod
						tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam,
						quis nostrud exercitation ullamco.</p>
						<ul class="social">
							<li><a href=""><i class="fa fa-facebook"></i></a></li>
							<li><a href=""><i class="fa fa-twitter"></i></a></li>
							<li><a href=""><i class="fa fa-linkedin"></i></a></li>
						</ul>
					</div>
				</div>
			</div>
		</footer>
		<?php wp_footer() ?>
	</body>
</html>
