<?php get_header(); ?>
<div class="wrapper">
	Lookbooks eh!!!
	<aside class="static" data-spy="affix" data-offset-top="190" data-offset-bottom="600"><?php get_sidebar( "about" ); ?></aside>
	<div class="content static press" data-columns>
		<?php while( have_posts() ) : the_post(); ?>
			<?php hm_get_template_part( 'press/block', array( 'press' => new Post( get_the_id() ) ) ); ?>
		<?php endwhile; ?>
	</div>
	<p class="clearfix">For press enquiries: <a href="mailto:enquiries@antiquejewellerycompany.com">enquiries@antiquejewellerycompany.com</a></p>
</div>
<?php get_footer(); ?>