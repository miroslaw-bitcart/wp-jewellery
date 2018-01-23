<?php get_header(); ?>

<div class="wrapper inspiration">

	<div class="content inspiration centered">

		<div class="half">
			<a href="<?php echo esc_url( home_url( '/lookbooks/girl-about-town/' ) ); ?>">
				<img src="<?php bloginfo('template_directory'); ?>/assets/images/inspiration/girl-about-town.jpg" alt="Girl About Town">
				<div class="caption">
					<small>Lookbook</small>
					<h4>Girl About Town</h4>
				</div>
			</a>
		</div>
		<div class="half">
			<a href="<?php echo esc_url( home_url( '/lookbooks/autumn-leaves/' ) ); ?>">
				<img src="<?php bloginfo('template_directory'); ?>/assets/images/inspiration/autumn-leaves.jpg" alt="Autumn Leaves">
				<div class="caption">
					<small>Lookbook</small>
					<h4>Autumn Leaves</h4>
				</div>
			</a>
		</div>

		<div class="third">
			<a href="<?php echo esc_url( home_url( '/the-ages' ) ); ?>">
				<img src="<?php bloginfo('template_directory'); ?>/assets/images/inspiration/the-ages.jpg" alt="The Ages">
				<div class="caption">
					<h4>The Ages</h4>
				</div>
			</a>
		</div>
		<div class="third">
			<a href="<?php echo esc_url( home_url( '/shop-the-look' ) ); ?>">
				<img src="<?php bloginfo('template_directory'); ?>/assets/images/inspiration/shop-the-look.jpg" alt="Shop the Look">
				<div class="caption">
					<h4>Shop the Look</h4>
				</div>
			</a>
		</div>
		<div class="third">
			<a href="<?php echo esc_url( home_url( '/collections' ) ); ?>">
				<img src="<?php bloginfo('template_directory'); ?>/assets/images/inspiration/collections.jpg" alt="Collections">
				<div class="caption">
					<h4>Collections</h4>
				</div>
			</a>
		</div>
	</div>
</div>

<?php get_footer(); ?>