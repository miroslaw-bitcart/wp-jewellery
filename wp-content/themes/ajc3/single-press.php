<?php get_header(); ?>
<div class="wrapper">
	<aside class="static" data-spy="affix" data-offset-top="162" data-offset-bottom="600"><?php get_sidebar( "about" ); ?></aside>
	<?php while( have_posts() ) : the_post(); ?>
	<?php $story = new Post( get_the_id() ); ?>
		<div class="content static press single">
			<div class="left-col">
				<h3><?php echo $story->get_title(); ?></h3>
				<p><?php echo date( 'F Y', $story->get_date() ); ?></p>
				<p><?php the_field('description'); ?></p>
				<p>
					<?php if( get_field('link') ): ?>
						<a href="<?php the_field('link'); ?>" target="_blank">View Link</a>
					<?php endif; ?>
				</p>
				<p><?php previous_post_link(); ?><br><?php next_post_link(); ?></p>
			</div>
			<div class="right-col">
				<?php $images = get_field('gallery');
				if( $images ): ?>
				    <ul>
				        <?php foreach( $images as $image ): ?>
				            <li>
				                <img src="<?php echo $image['sizes']['grid-larger']; ?>" alt="<?php echo $image['alt']; ?>" />
				            </li>
				        <?php endforeach; ?>
				    </ul>
				<?php endif; ?>
			</div>
		</div>
	<?php endwhile; ?>
</div>
<?php get_footer(); ?>