<?php get_header(); ?>

<div class="taxonomy-header full-width latest-finds clearfix"></div>

<div class="content shop full-width new-arrivals wrapper" style="float:none;margin:0 auto;">

	<ul>
		<?php
		global $post;
		$myposts = get_posts('post_status=private');
		foreach($myposts as $post) :
		?>
			<li><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></li>
		<?php endforeach; ?>
	</ul>
	
</div>

<?php get_footer(); ?>