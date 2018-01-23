<?php get_header(); ?>	

<div class="new-panel medium full-width main">
	<a class="inverse" href="<?php echo esc_url( home_url( '/shop/' ) ); ?>">
		<div class="caption">
			<h1>Find Jewellery as Unique as You</h1>
			<h3 class="action hvr-grow inverse">Shop Our Collection</h3>
			<h4>
				<?php $count_posts = wp_count_posts('product'); echo $count_posts->publish; ?> one-of-a-kind items - updated daily<br>
				Free Delivery, Returns &amp; Ring Sizing
			</h4>
		</div>
	</a>
</div>

<ul class="front-page-mobile centered">
	<li class="half divider-right"><a href="<?php echo esc_url( home_url( '/shop' ) ); ?>">All Items</a></li>
	<li class="half"><a href="<?php echo esc_url( home_url( '/latest-finds' ) ); ?>">Latest Finds</a></li>
	<li><a href="<?php echo esc_url( home_url( '/jewellery-type/rings/antique-engagement-rings' ) ); ?>">Engagement Rings</a></li>
	<li class="half divider-right"><a href="<?php echo esc_url( home_url( '/jewellery-type/antique-rings' ) ); ?>">Rings</a></li>
	<li class="half"><a href="<?php echo esc_url( home_url( '/jewellery-type/antique-earrings' ) ); ?>">Earrings</a></li>
	<li><a href="<?php echo esc_url( home_url( '/jewellery-type/antique-bracelets-bangles' ) ); ?>">Bracelets &amp; Bangles</a></li>
	<li class="half divider-right"><a href="<?php echo esc_url( home_url( '/jewellery-type/antique-necklaces' ) ); ?>">Necklaces</a></li>
	<li class="half"><a href="<?php echo esc_url( home_url( '/jewellery-type/antique-brooches' ) ); ?>">Brooches</a></li>
	<li><a href="<?php echo esc_url( home_url( '/jewellery-type/antique-lockets-pendants' ) ); ?>">Lockets &amp; Pendants</a></li>
	<li class="half divider-right"><a href="<?php echo esc_url( home_url( '/jewellery-type/antique-charms' ) ); ?>">Charms</a></li>
	<li class="half"><a href="<?php echo esc_url( home_url( '/jewellery-type/antique-seals-signet-rings' ) ); ?>">Seals &amp; Signets</a></li>
	<li class="half divider-right"><a href="<?php echo esc_url( home_url( '/jewellery-type/antique-chains' ) ); ?>">Chains</a></li>
	<li class="half"><a href="<?php echo esc_url( home_url( '/jewellery-type/antique-curiosities' ) ); ?>">Curiosities</a></li>
	<li><a href="<?php echo esc_url( home_url( '/jewellery-type/mens-jewellery' ) ); ?>">For Him</a></li>
	<li>
		<img class="centered small-space-above" src="<?php bloginfo('template_directory'); ?>/assets/images/misc/bertie-small.jpg" alt="Bertie" style="width: auto;" width="30" height="47">
	</li>
	<li class="half divider-right divider-top"><a href="<?php echo site_url( '/the-ages' ); ?>">The Ages</a></li>
	<li class="half divider-top"><a href="<?php echo site_url( '/collections' ); ?>">Collections</a></li>
	<li class="half divider-right"><a href="<?php echo esc_url( home_url( '/ollys-picks/all' ) ); ?>">Editor's Picks</a></li>
	<li class="half"><a href="<?php echo esc_url( home_url( '/shop/?_ajc_p_status=sold' ) ); ?>">Sold Archive</a></li>
</ul>

<div class="tri-panel">
	<div class="new-panel short third wider">
		<a class="latest-finds inverse" href="<?php echo esc_url( home_url( '/latest-finds/' ) ); ?>">
			<h1 class="impact"><?php wp_posts_in_days('days=30'); ?></h1>
			<h2>Discoveries this Month</h2>
			<h3 class="action hvr-grow inverse">Shop Latest Finds</h3>
		</a>
	</div>

	<div class="new-panel short third">
		<a class="engagement-rings" href="<?php echo esc_url( home_url( '/engagement-rings/' ) ); ?>">
			<h1 class="impact">1,085</h1>
			<h2>Proposals and Counting...</h2>
			<h3 class="action hvr-grow">Shop Engagement Rings</h3>
		</a>
	</div>

	<div class="new-panel short third">
		<a class="hot-100 inverse" href="<?php echo esc_url( home_url( '/trending/' ) ); ?>">
			<h2><span style="font-size:21px;line-height:9px;display:block;">The</span><span style="font-size:40px;line-height:32px;">Hot</span><br>1OO</h2>
			<h3 class="action inverse hvr-grow">View Our Most Desired Items</h3>
		</a>
	</div>
</div>

<div class="new-panel min full-width featured clearfix">
	<a class="border-top" href="<?php echo site_url( '/press' ); ?>">
		<small class="clearfix centered space-below">As Seen In</small>
		<img src="<?php bloginfo('template_directory'); ?>/assets/images/front-page/featured-press-1.png" alt="Antique Jewellery Company Press" width="512" height="27" class="logos">
		<img src="<?php bloginfo('template_directory'); ?>/assets/images/front-page/featured-press-2.png" alt="Antique Jewellery Company Press" width="512" height="26" class="logos">
	</a>
</div>

<div class="new-panel product-slider full-width">
	<h2 class="centered">Just Added</h2>
	<div class="flexslider products-slider" id="product-slider">

		<ul class="slides" data-bind="foreach: products">

			<?php
			$args = array(
			    'post_type' => 'product',
			    'posts_per_page' => 24,
			    'orderby' =>'random',
			);
			 
			$loop = new WP_Query( $args );

			$recent_posts = wp_get_recent_posts( $args, ARRAY_A );

			while ( $loop->have_posts() ) : $loop->the_post(); 
			global $product; ?>
			
			<li class="xpto" data-bind="css: { hidden : false }">
				<a id="id-<?php the_id(); ?>" href="<?php the_permalink(); ?>" title="<?php the_title(); ?>">
					<?php if ( has_post_thumbnail() ) { the_post_thumbnail('grid-larger'); } ?>
					<h3><?php the_title(); ?></h3>
					<?php $product = new AJC_Product( get_the_id() ); ?>
					<?php echo $product->get_price_html(); ?>
				</a>
			</li>

			<?php endwhile; ?>
			<?php wp_reset_query(); ?>

		</ul>

	</div>
</div>

<div class="new-panel max full-width reviews clearfix border-top border-bottom">

	<img class="centered" src="<?php bloginfo('template_directory'); ?>/assets/images/front-page/google.png" alt="Google Ratings" width="110" height="37">
	<div class="stars block">
		4.9
		<span class="small-space-left ion-star"></span>
		<span class="ion-star"></span>
		<span class="ion-star"></span>
		<span class="ion-star"></span>
		<span class="small-space-right ion-star"></span>
		<a href="https://goo.gl/9uPy3b" target="_blank">View All</a>
	</div>

	<div id="carousel-example-generic" class="carousel slide" data-ride="carousel">

		<ol class="carousel-indicators">
			<li data-target="#carousel-example-generic" data-slide-to="0" class="active"></li>
			<li data-target="#carousel-example-generic" data-slide-to="1"></li>
		</ol>

		<div class="carousel-inner" role="listbox">

			<ul class="item active">

				<li class="third">
					<blockquote>
					<p>We visited The AJC to buy an engagement ring. Can't say enough about the amazing service we received. They have the most exquisite selection of jewellery and really take the time to help you decide. I would strongly recommend a visit.</p>
					</blockquote>
					<small>&mdash; Jane, London</small>
				</li>

				<li class="third">
					<blockquote>
					<p>Olly was so lovely and helpful, which is exactly what you need when you're buying an engagement ring.</p>
					</blockquote>
					<small>&mdash; Grant, Brooklyn, New York</small>
				</li>

				<li class="third">
					<blockquote>
					<p>Service was unbeatable - worked to an extremely tight time scale and delivered without a hitch. The ring is perfect and I'm proud to see it on the hand of my fiancé. You guys have made us both very happy!</p>
					</blockquote>
					<small>&mdash; Oscar Barrett, Kent, UK</small>
				</li>

			</ul>

			<ul class="item">

				<li class="third">
					<blockquote>
					<p>I live in the United States, and working with the AJC was easier than going into a store here! They were easily accessible and we even did a video Skype session for additional looks. They had the ring sized and out and shipped to the USA by the next day. Simply an amazing customer experience!</p>
					</blockquote>
					<small>&mdash; Ben, Atlanta, USA</small>
				</li>
				
				<li class="third">
					<blockquote>
					<p>Excellent service - very personal, engaged and attentive. From advice on the provenance and suitability of the rings I had chosen, from a very well presented web site, to ensuring that they arrived on time  - faultless!</p>
					</blockquote>
					<small>&mdash; Edward, Wiltshire, UK</small>
				</li>
				
				<li class="third">
					<blockquote>
					<p>Excellent experience from start to finish, I didn't have a clue what I was doing when looking at engagement rings but was very happy with my purchase. And it seemed to do the trick as she happily said yes!</p>
					</blockquote>
					<small>&mdash; Matt, London</small>
				</li>

			</ul>

		</div>

		<a class="left carousel-control" href="#carousel-example-generic" role="button" data-slide="prev">
			<span class="ion-chevron-left" aria-hidden="true"></span>
		</a>
		<a class="right carousel-control" href="#carousel-example-generic" role="button" data-slide="next">
			<span class="ion-chevron-right" aria-hidden="true"></span>
		</a>

	</div>
</div>

<div class="new-panel insta short-med full-width border-bottom">

	<h2><a target="_blank" href="http://www.instagram.com/antiquejewellerycompany">@antiquejewellerycompany</a></h2>

	<div id="instafeed"></div>

	<script type="text/javascript">
	  var userFeed = new Instafeed({
	    get: 'user',
	    userId: '977280213',
	    clientId: '97865ae64a444040b6b5aa7545a243f4',
	    accessToken: '977280213.97865ae.49cfe81669064d18906d935f07922bf4',
	    resolution: 'standard_resolution',
	    template: '<a href="{{link}}" target="_blank" id="{{id}}"><img src="{{image}}" /></a>',
	    sortBy: 'most-recent',
	    limit: 6,
	    links: false
	  });
	  userFeed.run();
	</script>

</div>

<?php get_footer(); ?>