<?php get_header(); ?>

<div class="wrapper inspiration <?php the_ID(); ?>">
	
	<div class="content inspiration centered">
		
		<div class="intro">
			<small>Lookbook</small>
			<h1><?php the_field('name'); ?></h1>
			<?php the_field('description'); ?>
		</div>

		<div class="full-width">
			<?php if( get_field('image-1') ): ?>
				<img src="<?php the_field('image-1'); ?>" alt="lookbook" />
			<?php endif; ?>
		</div>

		<div class="half">
			<?php if( get_field('image-2') ): ?>
				<img src="<?php the_field('image-2'); ?>" alt="lookbook" />
			<?php endif; ?>
		</div>
		<div class="half">
			<?php if( get_field('image-3') ): ?>
				<img src="<?php the_field('image-3'); ?>" alt="lookbook" />
			<?php endif; ?>
		</div>

		<?php if( have_rows('items-field-1') ): ?>
			<ul class="dynamic-products main-shop">
			    <?php while ( have_rows('items-field-1') ) : the_row(); ?>
			    	<li>
			    		<div class="product-block">
			    			<a href="<?php the_sub_field('item-link'); ?>">
				    			<div class="image-wrap">
							        <img src="<?php the_sub_field('item-image'); ?>" alt="lookbook" />
							    </div>
							    <div class="info-wrap">
							    	<h3><?php the_sub_field('item-name'); ?></h3>
							    </div>
						    </a>
					    </div>
				    </li>
				<?php endwhile; ?>
			</ul>
		<?php endif; ?>

		<div class="full-width">
			<?php if( get_field('image-4') ): ?>
				<img src="<?php the_field('image-4'); ?>" alt="lookbook" />
			<?php endif; ?>
		</div>

		<div class="half">
			<?php if( get_field('image-5') ): ?>
				<img src="<?php the_field('image-5'); ?>" alt="lookbook" />
			<?php endif; ?>
		</div>
		<div class="half">
			<?php if( get_field('image-6') ): ?>
				<img src="<?php the_field('image-6'); ?>" alt="lookbook" />
			<?php endif; ?>
		</div>

		<div class="full-width">
			<?php if( get_field('image-7') ): ?>
				<img src="<?php the_field('image-7'); ?>" alt="lookbook" />
			<?php endif; ?>
		</div>

		<div class="half">
			<?php if( get_field('image-8') ): ?>
				<img src="<?php the_field('image-8'); ?>" alt="lookbook" />
			<?php endif; ?>
		</div>
		<div class="half">
			<?php if( get_field('image-9') ): ?>
				<img src="<?php the_field('image-9'); ?>" alt="lookbook" />
			<?php endif; ?>
		</div>

		<?php if( have_rows('items-field-2') ): ?>
			<ul class="dynamic-products main-shop">
			    <?php while ( have_rows('items-field-2') ) : the_row(); ?>
			    	<li>
			    		<div class="product-block">
			    			<a href="<?php the_sub_field('item-link'); ?>">
				    			<div class="image-wrap">
							        <img src="<?php the_sub_field('item-image'); ?>" alt="lookbook" />
							    </div>
							    <div class="info-wrap">
							    	<h3><?php the_sub_field('item-name'); ?></h3>
							    </div>
						    </a>
					    </div>
				    </li>
				<?php endwhile; ?>
			</ul>
		<?php endif; ?>

		<div class="full-width">
			<?php if( get_field('image-10') ): ?>
				<img src="<?php the_field('image-10'); ?>" alt="lookbook" />
			<?php endif; ?>
		</div>

		<div class="half">
			<?php if( get_field('image-11') ): ?>
				<img src="<?php the_field('image-11'); ?>" alt="lookbook" />
			<?php endif; ?>
		</div>
		<div class="half">
			<?php if( get_field('image-12') ): ?>
				<img src="<?php the_field('image-12'); ?>" alt="lookbook" />
			<?php endif; ?>
		</div>

		<div class="full-width">
			<?php if( get_field('image-13') ): ?>
				<img src="<?php the_field('image-13'); ?>" alt="lookbook" />
			<?php endif; ?>
		</div>

		<?php if( have_rows('items-field-3') ): ?>
			<ul class="dynamic-products main-shop">
			    <?php while ( have_rows('items-field-3') ) : the_row(); ?>
			    	<li>
			    		<div class="product-block">
			    			<a href="<?php the_sub_field('item-link'); ?>">
				    			<div class="image-wrap">
							        <img src="<?php the_sub_field('item-image'); ?>" alt="lookbook" />
							    </div>
							    <div class="info-wrap">
							    	<h3><?php the_sub_field('item-name'); ?></h3>
							    </div>
						    </a>
					    </div>
				    </li>
				<?php endwhile; ?>
			</ul>
		<?php endif; ?>
		
		<div class="full-width">
			<?php if( get_field('image-14') ): ?>
				<img src="<?php the_field('image-14'); ?>" alt="lookbook" />
			<?php endif; ?>
		</div>

		<?php if( have_rows('items-field-4') ): ?>
			<ul class="dynamic-products main-shop">
			    <?php while ( have_rows('items-field-4') ) : the_row(); ?>
			    	<li>
			    		<div class="product-block">
			    			<a href="<?php the_sub_field('item-link'); ?>">
				    			<div class="image-wrap">
							        <img src="<?php the_sub_field('item-image'); ?>" alt="lookbook" />
							    </div>
							    <div class="info-wrap">
							    	<h3><?php the_sub_field('item-name'); ?></h3>
							    </div>
						    </a>
					    </div>
				    </li>
				<?php endwhile; ?>
			</ul>
		<?php endif; ?>

		<table class="credits">
			<?php if( get_field('photographer') ): ?>
				<tr>
					<th><em>Photographer:</em></th>
					<th><?php the_field('photographer'); ?></th>
				</tr>
			<?php endif; ?>

			<?php if( get_field('model') ): ?>
				<tr>
					<th><em>Model:</em></th>
					<th><?php the_field('model'); ?></th>
				</tr>
			<?php endif; ?>

			<?php if( get_field('stylist') ): ?>
				<tr>
					<th><em>Hair &amp; Make Up:</em></th>
					<th><?php the_field('stylist'); ?></th>
				</tr>
			<?php endif; ?>

			<?php if( get_field('location') ): ?>
				<tr>
					<th><em>Location:</em></th>
					<th><?php the_field('location'); ?></th>
				</tr>
			<?php endif; ?>

			<?php if( get_field('credits') ): ?>
				<tr>
					<th><em>With thanks to:</em></th>
					<th><?php the_field('credits'); ?></th>
				</tr>
			<?php endif; ?>
		</table>

	</div>

</div>

<?php get_footer(); ?>