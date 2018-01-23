<?php get_header(); ?>

<h1>Journal</h1>

<?php $journal = new WP_Query( array(
	'posts_per_page' => 10
) ); ?>

<?php while( $journal->have_posts() ) : $journal->the_post(); ?>

	<?php the_title() ;?>

<?php endwhile; ?>

<?php get_footer(); ?>