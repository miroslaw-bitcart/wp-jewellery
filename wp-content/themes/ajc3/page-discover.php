<?php get_header(); ?>
	
	<aside class="static" data-spy="affix" data-offset-top="190" data-offset-bottom="600"><?php get_sidebar( "discover" ); ?></aside>

	<ul class="world-of panels content static centered">
		<li class="full-width centered">
			<img src="<?php echo get_stylesheet_directory_uri(); ?>/assets/images/static-pages/discover.jpg" alt="Discover" width="805" height="300">
			<h2>Discover</h2>
		</li>



		<li class="panel third">
			<!-- 
			<a href="<?php echo esc_url( home_url( '/discover/where-to-start' ) ); ?>">
				<?php $image = wp_get_attachment_image_src(get_field('where-to-start-image'), 'panel-medium'); ?>
				<img src="<?php echo $image[0]; ?>" alt="<?php echo get_the_title(get_field('where-to-start-i
				mage')) ?>" />
			</a>
			-->
			<a href="<?php echo esc_url( home_url( '/discover/where-to-start' ) ); ?>"><h2>Where to Start</h2></a>
			<p><?php the_field('where-to-start-sub-header'); ?></p>
		</li>

		<li class="panel third">
			<!-- 
			<a href="<?php echo esc_url( home_url( '/discover/jewellery-care' ) ); ?>">
				<?php $image = wp_get_attachment_image_src(get_field('jewellery-care-image'), 'panel-medium'); ?>
				<img src="<?php echo $image[0]; ?>" alt="<?php echo get_the_title(get_field('jewellery-care-image')) ?>" />
			</a>
			-->
			<a href="<?php echo esc_url( home_url( '/discover/jewellery-care' ) ); ?>"><h2>Jewellery Care</h2></a>
			<p><?php the_field('jewellery-care-sub-header'); ?></p>
		</li>

		<li class="panel third">
			<!-- 
			<a href="<?php echo esc_url( home_url( '/discover/symbology' ) ); ?>">
				<?php $image = wp_get_attachment_image_src(get_field('symbology-image'), 'panel-medium'); ?>
				<img src="<?php echo $image[0]; ?>" alt="<?php echo get_the_title(get_field('symbology-image')) ?>" />
			</a>
			-->
			<a href="<?php echo esc_url( home_url( '/discover/symbology' ) ); ?>"><h2>Symbology</h2></a>
			<p><?php the_field('symbology-sub-header'); ?></p>
		</li>

		<li class="panel third">
			<!-- 
			<a href="<?php echo esc_url( home_url( '/discover/gemstone-guide' ) ); ?>">
				<?php $image = wp_get_attachment_image_src(get_field('gemstone-guide-image'), 'panel-medium'); ?>
				<img src="<?php echo $image[0]; ?>" alt="<?php echo get_the_title(get_field('gemstone-guide-image')) ?>" />
			</a>
			-->
			<a href="<?php echo esc_url( home_url( '/discover/gemstone-guide' ) ); ?>"><h2>Gemstone Guide</h2></a>
			<p><?php the_field('gemstone-guide-sub-header'); ?></p>
		</li>

		<li class="panel third">
			<!-- 
			<a href="<?php echo esc_url( home_url( '/discover/glossary' ) ); ?>">
				<?php $image = wp_get_attachment_image_src(get_field('glossary-image'), 'panel-medium'); ?>
				<img src="<?php echo $image[0]; ?>" alt="<?php echo get_the_title(get_field('glossary-image')) ?>" />
			</a>
			-->
			<a href="<?php echo esc_url( home_url( '/discover/glossary' ) ); ?>"><h2>Glossary</h2></a>
			<p><?php the_field('glossary-sub-header'); ?></p>
		</li>

	</ul>

<?php get_footer(); ?>