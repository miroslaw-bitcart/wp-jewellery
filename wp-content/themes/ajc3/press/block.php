<?php $story = $template_args['press']; ?>

<div class="panel">
	<?php $image = wp_get_attachment_image_src(get_field('cover'), 'grid-larger'); ?>
	<a href="<?php echo $story->get_permalink(); ?>">
		<img src="<?php echo $image[0]; ?>" alt="<?php echo get_the_title(get_field('cover')) ?>" />
	</a>
	<h3><?php echo $story->get_title(); ?></h3>
	<?php echo date( 'F Y', $story->get_date() ); ?>
</div>