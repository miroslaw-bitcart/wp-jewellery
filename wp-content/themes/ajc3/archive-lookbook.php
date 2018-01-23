<?php get_header(); ?>

<div class="taxonomy-header centered clearfix">
    <h2>Lookbooks</h2>
</div>

<div class="wrapper lookbook">
	<div class="lookbooks">
		<?php if ( have_posts() ) : ?>
			<?php while ( have_posts() ) : the_post(); ?>  
				<div class="half">
					<a href="<?php the_permalink(); ?>">  
						<?php the_post_thumbnail( 'full' ); ?>
						<h1><?php the_field('name'); ?></h1>
					</a>
				</div>
			<?php endwhile; ?>
		<?php endif; ?>
	</div>
</div>

<?php get_footer();