<?php
/*
Template Name: User
*/

get_header(); ?>

<aside class="static user"><?php get_sidebar( "user" ); ?></aside>

<div class="content static user">
	<h2><?php the_title();?></h2>
    <?php if (have_posts()) : while (have_posts()) : the_post();?>
    	<?php the_content(); ?>
    <?php endwhile; endif; ?>
</div>
<?php get_footer(); ?>