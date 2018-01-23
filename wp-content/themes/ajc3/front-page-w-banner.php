<?php get_header(); ?>	
	<div class="front-page clearfix">

		<div class="panel full-width clearfix relative">
			<a href="<?php echo esc_url( home_url( '/collection/christmas-gifts/' ) ); ?>">
				<ul class="slides" style="position:relative;">
					<li class="inverted slide1">
						<div class="xmas-caption">
							<h1 class="space-below">Better Late Than Never!</h1>
							<h3 class="hvr-grow">Shop Gifts</h3>
						</div>
					</li>
			  	</ul>
			</a>

			<div class="flash-banner">
				<div class="third">
					Free Worldwide Delivery on all Orders
				</div>
				<div class="third">
					<a href="<?php echo esc_url( home_url( '/christmas-2016' ) ); ?>">Returns Period extended to 14 January 2017</a>
				</div>
				<div class="third">
					<a href="<?php echo esc_url( home_url( '/christmas-2016' ) ); ?>">All Orders Beatifully Gift Wrapped</a>
				</div>
			</div>

		</div>
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

	<div class="stats relative clearfix">
		<div class="relative">
			<h1>The Home of Antique Jewellery</h1>
			<a href="<?php echo esc_url( home_url( '/shop' ) ); ?>"><?php $count_posts = wp_count_posts('product'); echo $count_posts->publish; ?> items - updated daily</a>
			<img class="members" src="<?php bloginfo('template_directory'); ?>/assets/images/misc/cinoa-lapada.jpg" alt="Cinoa Lapada" width="128" height="40">
		</div>
	</div>

	<div class="home-columns clearfix">

		<div class="home-thumbs">
			<ul>
				<li class="panel quarter">
					<a href="<?php echo esc_url( home_url( '/latest-finds' ) ); ?>">
						<img class="left hvr-grow" src="<?php bloginfo('template_directory'); ?>/assets/images/front-page/latest-finds.jpg" alt="Antique Jewellery Latest Finds" width="360" height="240">
					</a>
					<h4>Latest Finds</h4>
				</li>
				<li class="panel quarter">
					<a href="<?php echo esc_url( home_url( '/engagement-rings/' ) ); ?>" class="hvr-grow">
						<img class="left hvr-grow" src="<?php bloginfo('template_directory'); ?>/assets/images/front-page/engagement-rings.jpg" alt="Antique Engagement Rings" width="360" height="240">
						<h4>Engagement Rings</h4>
					</a>
				</li>
				<li class="panel quarter">
					<a href="<?php echo esc_url( home_url( '/press' ) ); ?>">
						<img class="left hvr-grow" src="<?php bloginfo('template_directory'); ?>/assets/images/front-page/press.jpg" alt="AJC Press" width="360" height="240">
						<h4>Press</h4>
					</a>
				</li>
				<li class="panel quarter">
					<a href="<?php echo esc_url( home_url( '/jewellery-type/mens-jewellery/' ) ); ?>">
						<img class="left hvr-grow" src="<?php bloginfo('template_directory'); ?>/assets/images/front-page/forhim.jpg" alt="For Him" width="360" height="240">
						<h4>For Him</h4>
					</a>
				</li>
			</ul>
		</div>

		<div class="product-slider latest-finds">

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
							<h3><a href="<?php the_permalink(); ?>" rel="bookmark"><?php the_title(); ?></a></h3>
							<?php $product = new AJC_Product( get_the_id() ); ?>
							<?php echo $product->get_price_html(); ?>
						</a>
					</li>

					<?php endwhile; ?>
					<?php wp_reset_query(); ?>

				</ul>

			</div>

		</div>

	</div>

	<div class="featured-press clearfix">
		<div class="wrapper">
			<div class="half text-left">
				<img src="<?php bloginfo('template_directory'); ?>/assets/images/front-page/free-delivery-returns.png" alt="Free Delivery" width="300" height="94" class="delivery">
			</div>
			<div class="half text-right">
				<h3>As featured in</h3>
				<a href="<?php echo site_url( '/press' ); ?>">
					<img src="<?php bloginfo('template_directory'); ?>/assets/images/front-page/featured-press-1.png" alt="Antique Jewellery Company Press" width="512" height="27" class="logos"><br>
					<img src="<?php bloginfo('template_directory'); ?>/assets/images/front-page/featured-press-2.png" alt="Antique Jewellery Company Press" width="512" height="26" class="logos">
				</a>
			</div>
		</div>
	</div>

	<div class="founder quote clearfix">
		<div class="wrapper centered">
			<img src="<?php bloginfo('template_directory'); ?>/assets/images/front-page/olly.jpg" alt="Olly Gerrish" class="space-below circled hvr-grow" width="125" height="125"><br>
			<img src="<?php bloginfo('template_directory'); ?>/assets/images/front-page/olly-gerrish.png" alt="Olly Gerrish" width="221" height="44">
			<h4>Founder</h4>
			<blockquote>
				<p>If you go from Japan to Scotland, from London to Los Angeles and mention Grays Antiques Centre the chances are that someone will say <em>I have been there and do you know Olly Gerrish by any chance?</em> When you have been in the business for a certain length of time you meet a large number of people and a good reputation means your name gets passed around. You can do no better than spending time with Olly who will happily share her expertise and knowledge with you and make buying a piece of period jewellery a pleasure.</p>
			</blockquote>
			<small>&mdash; Anthea Gesua, Director, LAPADA</small>
			<a href="<?php echo esc_url( home_url( '/our-world/ollys-story/' ) ); ?>">Read Olly's Story</a>
		</div>
	</div>

	<div class="testimonial clearfix">
		<div class="wrapper">
			<div class="half">
				<blockquote>
					<p>My words can't possibly do justice to the AJC. It is my go-to site for Victorian and Georgian jewelry. For any jewelry lover, it is a gem of a find! Having purchased a couple of pieces, the delight of receiving them pales in comparison to the actual pieces themselves. Both Olly and Matt are beyond gracious with their time, their expertise and their customer service. I can only hope to be the recipient of more pieces in the very near future.</p>
				</blockquote>
				<small class="centered">
					<span class="ion-star"></span>
					<span class="ion-star"></span> 
					<span class="ion-star"></span> 
					<span class="ion-star"></span> 
					<span class="ion-star"></span>  
					&mdash; Susan, Los Angeles</small>
			</div>
			<div class="half">
				<blockquote>
					<p>We visited The Antique Jewellery Company to buy an engagement ring, Can't say enough about the amazing service we received. They have the most exquisite selection of jewellery and really take the time to help you decide. I would strongly recommend a visit.</p>
				</blockquote>
				<small class="centered">
					<span class="ion-star"></span>
					<span class="ion-star"></span> 
					<span class="ion-star"></span> 
					<span class="ion-star"></span> 
					<span class="ion-star"></span>  
					&mdash; Jane, London
				</small>
			</div>
		</div>
	</div>

	<form action="http://antiquejewellerycompany.us1.list-manage.com/subscribe/post?u=1701ea2a0d9eace11015dfbed&amp;id=68fba85f74" method="post" id="mc-embedded-subscribe-form" name="mc-embedded-subscribe-form" class="sunday-club clearfix centered" target="_blank" novalidate>
		<div class="clearfix">
			<img class="centered" src="<?php bloginfo('template_directory'); ?>/assets/images/front-page/sunday-club-logo2.png" alt="Sunday Club" style="width: auto;" width="340" height="64">
			<p>A Fortnightly Sneak Peek of Our Latest Finds, delivered to your inbox</p>
			<fieldset>
			   <input type="email" value="" name="EMAIL" class="email" id="mce-EMAIL" placeholder="Your Email Address" required>
			   <input type="text" name="b_1701ea2a0d9eace11015dfbed_68fba85f74" value="" style="position: absolute; left: -5000px;">
			   <input type="submit" value="Join" name="subscribe" id="mc-embedded-subscribe" class="black button">
			</fieldset>
		</div>
	</form>

<?php get_footer(); ?>