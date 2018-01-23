<?php get_header(); ?>

<div class="magazine-container clearfix">

	<div class="magazine-header centered">
		<h2><a href="/magazine">Magazine</a></h2>
		<div class="category-menu">
			<?php
				$args = array(
					'title_li' => ''
				);
				wp_list_categories($args);
			?>
		</div>
	</div>

	<?php $backgroundImg = wp_get_attachment_image_src( get_post_thumbnail_id($post->ID), 'full' );?>        
	<div class="full-width" href="<?php the_permalink(); ?>" rel="bookmark" 
	style="background-image:url(<?php echo $backgroundImg[0]; ?>);height:400px;background-size:contain;background-position:center center;"
	></div>

	<div class="container">

		<?php while ( have_posts() ) : the_post(); ?>

		<h1 class="entry-title"><?php the_title(); ?></h1>

		<article id="post-<?php the_ID(); ?>" class="clearfix space-below">
			<?php if(get_field('strapline')) { echo '<h3 class="strapline">' . get_field('strapline') . '</h3>'; } ?>
			<?php the_content(); ?>
		</article>

		<?php MRP_show_related_posts(); ?>


		<article>

			<div class="social large-space-above large-space-below clearfix">
				<a class="facebook left space-right" href="http://www.facebook.com/sharer.php?u=<?php the_permalink();?>&t=<?php the_title();?>"><span class="ion-social-facebook"></span>Share This Story</a>
				<a class="twitter left" href="http://twitter.com/home?status=<?php the_title();?> <?php the_permalink();?>" title="Share this on Twitter" rel="external"><span class="ion-social-twitter"></span>Tweet This Story</a>
			</div>
			
			<?php comments_template(); ?>

		</article>


		<?php endwhile; ?>

		<!-- 
		<h4>By <?php the_author('display_name') ?></h4>
		<p class="avatar"><?php echo get_avatar( get_the_author_meta( 'ID' ), 80 ); ?></p>

		<div class="addthis_toolbox addthis_default_style space-above clearfix">
			<a class="addthis_button_facebook_like" fb:like:layout="button_count"></a>
			<a class="addthis_button_tweet"></a>
			<a class="addthis_button_pinterest_pinit" pi:pinit:layout="horizontal"></a>
			<a class="addthis_counter addthis_pill_style"></a>
			<a href="PostLiveUrl#disqus_thread"></a>
		</div>

		<script type="text/javascript">var addthis_config = {"data_track_addressbar":true};</script>
		<script type="text/javascript" src="//s7.addthis.com/js/300/addthis_widget.js#pubid=ra-4dfb84b53e768952"></script>
		-->

	</div>

</div>

<?php get_footer(); ?> 