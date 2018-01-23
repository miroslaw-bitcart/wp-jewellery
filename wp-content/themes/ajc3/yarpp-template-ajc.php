<?php
/*
YARPP Template: AJC
Description: Requires a theme which supports post thumbnails
Author: Matt Gerrish
*/ ?>

<div class="product-slider">
	<h2 class="centered">You might also like...</h2>
	<div class="flexslider products-slider" id="product-slider">
		<ul class="slides" data-bind="foreach: products">
			<?php if ( have_posts() ) : ?>
				<?php while ( have_posts() ) : the_post(); ?>
					<li class="xpto" data-bind="css: { hidden : false }">
						<a href="<?php the_permalink(); ?>" rel="bookmark">
							<?php if ( has_post_thumbnail() ) { the_post_thumbnail('grid-larger'); } ?>
						</a>
						<h3><a href="<?php the_permalink(); ?>" rel="bookmark"><?php the_title(); ?></a></h3>
						<?php $product = new AJC_Product( get_the_id() ); ?>
						<?php echo $product->get_price_html(); ?>
					</li>
				<?php endwhile; ?>
				<?php else: ?>
				<p>No related articles</p>
			<?php endif; ?>
		</ul>
	</div>
</div>