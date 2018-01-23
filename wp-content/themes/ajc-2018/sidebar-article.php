<aside role="complementary" class="nopin magazine-sidebar">

	<!-- Related Posts
	<section class="related-posts clearfix">
		<h4>You might also like</h4>
		<div class="clearfix">
			<?php $args['messy-nessy-yarpp-template'] = "yarpp-template-messynessy-sidebar.php"; related_posts($args); ?>
		</div>
	</section>
	-->

	<!-- Latest Posts -->
	<section class="latest-posts clearfix">
		<h4>Recent Articles</h4>
		<ul>
			<?php 
			$args = array( 'post_type' => 'article', 'posts_per_page' => '5' );
			$the_query = new WP_Query( $args ); 
			?>
			<?php while ($the_query -> have_posts()) : $the_query -> the_post(); ?>
				<li class="clearfix">
					<a class="image" href="<?php the_permalink(); ?>" rel="bookmark">
					<?php if ( has_post_thumbnail() ) { the_post_thumbnail('product-x-small'); } ?>
					</a>
					<div class="title">
						<small><?php the_category(' / '); ?></small>
						<a href="<?php the_permalink() ?>"><?php the_title(); ?></a>
					</div>
				</li>
			<?php endwhile;?>
		</ul>
	</section>

	<!-- Instagram
	<section class="instagram clearfix">
		<h4>Behind the Scenes at the AJC</h4>
		<div class="clearfix">
			<iframe src="http://www.intagme.com/in/?u=YW50aXF1ZWpld2VsbGVyeWNvbXBhbnl8aW58OTd8M3wzfHxub3w2fHVuZGVmaW5lZA==" allowTransparency="true" frameborder="0" scrolling="no" style="border:none; overflow:hidden; width:309px; height: 309px" ></iframe>
		</div>
	</section>
	-->

	<!-- Twitter -->
	<section class="twitter clearfix static">
		<h4>Follow Us On Twitter</h4>
		<div class="clearfix">
	 		<a href="https://twitter.com/ajc_london" class="twitter-follow-button" data-show-count="true" data-size="medium" data-show-screen-name="true">Follow @ajc_london</a>
        	<script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0],p=/^http:/.test(d.location)?'http':'https';if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src=p+'://platform.twitter.com/widgets.js';fjs.parentNode.insertBefore(js,fjs);}}(document, 'script', 'twitter-wjs');
        	</script>
    	</div>
    </section>

	<!-- Facebook -->
	<section class="facebook clearfix" data-spy="affix" data-offset-top="900" data-offset-bottom="600">
		<h4>Follow Us On Facebook</h4>
		<div class="clearfix">
			<div class="fb-like-box" data-href="https://www.facebook.com/antiquejewellerycompany" data-colorscheme="light" data-show-faces="true" data-header="false" data-stream="false" data-show-border="true"></div>
		</div>
	</section>

</aside>