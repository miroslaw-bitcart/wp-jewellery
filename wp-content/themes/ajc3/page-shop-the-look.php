<?php get_header(); ?>

<div class="wrapper inspiration">

	<div class="content shop-the-look">

		<div class="intro centered">
			<h1>Shop the Look</h1>
			<p>Your weekly feed of antique jewellery, shown in a new light</p>
		</div>

		<div class="container">
			<?php if( have_rows('look') ): ?>
				    <?php while ( have_rows('look') ) : the_row(); ?>
				    	<div>
				    		<img class="image" src="<?php the_sub_field('image'); ?>" alt="shop-the-look"/>
				    		<div class="caption">
					    		<?php if( have_rows('info') ): ?>
					    			<?php while( have_rows('info') ): the_row(); ?>
					    				<a href="<?php the_sub_field('link'); ?>" target="_blank">
			    		    				<div class="info">
			    						    	<h3><?php the_sub_field('name'); ?></h3>
			    						    		<?php if( get_sub_field('link') ): ?>
			    						    			<span class="silver button">View Item</span>
			    						    		<?php endif; ?>
			    						    </div>
		    						    	<img class="right" src="<?php the_sub_field('thumb'); ?>" alt="shop-the-look" />
		    						    </a>
			    					<?php endwhile; ?>
			    				<?php endif;  ?>
			    			 </div>
		    			</div>
					<?php endwhile; ?>
			<?php endif; ?>
			<div class="credit">Photography by Sarah Tahon</div>
		</div>

	</div>

</div>

<?php get_footer(); ?>