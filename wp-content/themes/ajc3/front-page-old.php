<?php get_header(); ?>	
	<div class="front-page clearfix">

		<div class="flash-banner">
			<div class="third">
				Free Worldwide Delivery on all Orders
			</div>
			<div class="third">
				Returns Period extended to 3 January 2017
			</div>
			<div class="third">
				All Orders Beatifully Gift Wrapped
			</div>
		</div>

		<div class="panel full-width clearfix">
			<a href="<?php echo esc_url( home_url( '/collection/christmas-gifts/' ) ); ?>">
				<ul class="slides" style="position:relative;">
					<li class="inverted slide1">
						<div class="xmas-caption">
							<h1>'Tis the Season</h1>
							<h4 class="space-below">for one of a kind presents...</h4>
							<h3>Shop Christmas Gifts</h3>
						</div>
					</li>
			  	</ul>
			</a>

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
						<img class="left" src="<?php bloginfo('template_directory'); ?>/assets/images/front-page/latest-finds.jpg" alt="Antique Jewellery Latest Finds" width="360" height="240">
						<h4>Latest Finds</h4>
					</a>
				</li>
				<li class="panel quarter">
					<a href="<?php echo esc_url( home_url( '/engagement-rings/' ) ); ?>">
						<img class="left" src="<?php bloginfo('template_directory'); ?>/assets/images/front-page/engagement-rings.jpg" alt="Antique Engagement Rings" width="360" height="240">
						<h4>Engagement Rings</h4>
					</a>
				</li>
				<li class="panel quarter">
					<a href="<?php echo esc_url( home_url( '/press' ) ); ?>">
						<img class="left" src="<?php bloginfo('template_directory'); ?>/assets/images/front-page/press.jpg" alt="AJC Press" width="360" height="240">
						<h4>Press</h4>
					</a>
				</li>
				<li class="panel quarter">
					<a href="<?php echo esc_url( home_url( '/jewellery-type/mens-jewellery/' ) ); ?>">
						<img class="left" src="<?php bloginfo('template_directory'); ?>/assets/images/front-page/forhim.jpg" alt="For Him" width="360" height="240">
						<h4>For Him</h4>
					</a>
				</li>
			</ul>
		</div>

		<ul class="intro">
			<li class="panel third"><h2>Antique &amp; Vintage Jewellery</h2><p>Welcome to the AJC, Europe's leading online destination for original antique jewellery.</p>
				<p>We are a family-run jewellers specializing in the sale of fine antique, estate and vintage jewellery. We operate from our shop in the world-famous Grays Antiques Center in the heart of London's West End. <a href="<?php echo esc_url( home_url( '/our-world/visit-us/' ) ); ?>">Visit our shop</a></p>
				<p>Our extensive, ever-growing collection, sourced in London, contains only the best examples from across the ages - from <a href="<?php echo esc_url( home_url( '/period/georgian/' ) ); ?>">Georgian jewellery</a>, <a href="<?php echo esc_url( home_url( '/period/victorian/' ) ); ?>">Victorian jewellery</a>, <a href="<?php echo esc_url( home_url( '/period/edwardian/' ) ); ?>">Edwardian</a> and <a href="<?php echo esc_url( home_url( '/period/art-deco/' ) ); ?>">Art Deco jewellery</a> - through to <a href="<?php echo esc_url( home_url( '/period/art-nouveau/' ) ); ?>">Art Nouveau jewellery</a>, <a href="<?php echo esc_url( home_url( '/period/retro/' ) ); ?>">Retro</a> and <a href="<?php echo esc_url( home_url( '/period/modern/' ) ); ?>">Modern jewellery</a>. Learn about each era in our <a href="<?php echo esc_url( home_url( '/the-ages' ) ); ?>">history of the ages section.</a></p>
			</li>
			<li class="panel third">
				<h2>Antique Rings &amp; Engagement Rings</h2>
				<p>We've helped hundreds of couples to find their perfect antique engagement ring.</p>
				<p>Explore our rare <a href="<?php echo esc_url( home_url( '/jewellery-type/antique-rings' ) ); ?>">antique rings</a>, <a href="<?php echo esc_url( home_url( '/jewellery-type/antique-rings' ) ); ?>">vintage rings</a>, <a href="<?php echo esc_url( home_url( '/jewellery-type/antique-rings/antique-engagement-rings/?_material=diamond' ) ); ?>">diamond engagement rings</a> and <a href="<?php echo esc_url( home_url( '/jewellery-type/antique-rings/antique-wedding-bands' ) ); ?>">wedding bands</a>. Our stunning <a href="<?php echo esc_url( home_url( '/jewellery-type/antique-rings/antique-engagement-rings/?_material=diamond' ) ); ?>">diamond</a>, <a href="<?php echo esc_url( home_url( '/jewellery-type/antique-rings/antique-engagement-rings/?_material=sapphire' ) ); ?>">sapphire</a>, <a href="<?php echo esc_url( home_url( '/jewellery-type/antique-rings/antique-engagement-rings/?_material=ruby' ) ); ?>">ruby</a> and <a href="<?php echo esc_url( home_url( '/jewellery-type/antique-rings/antique-engagement-rings/?_material=emerald' ) ); ?>">emerald engagement rings</a> are of varied design and originate from the Georgian, Victorian, Edwardian, Art Nouveau and Art Deco periods. Our rings are set with precious and semi-precious stones in white gold, yellow gold or platinum.</p>
				<p>We take great pride in our collection of antique rings - each is hand-picked for its quality, rarity and style and carries a no-risk, <a href="<?php echo esc_url( home_url( '/our-world/ajc-guarantee/' ) ); ?>">full-money back guarantee</a>.</p>
			</li>
			<li class="panel third">
				<h2>Unrivalled Expertise</h2>
				<p>Renowned for our expert jewellery knowledge, our customers are assured of the quality and rarity of all our pieces and complete peace of mind when buying from us. Our founder Olly Gerrish has over 40 years experience in selling antique jewellery.<br><a href="<?php echo esc_url( home_url( '/our-world/ollys-story/' ) ); ?>">Read Olly's Story</a></p>
				<p>Whether you are looking for a gift to mark an occasion or a present for yourself, you will find <a href="<?php echo esc_url( home_url( '/jewellery-type/antique-rings' ) ); ?>">antique rings</a>, <a href="<?php echo esc_url( home_url( '/jewellery-type/antique-earrings' ) ); ?>">earrings</a>, <a href="<?php echo esc_url( home_url( '/jewellery-type/antique-necklaces' ) ); ?>">necklaces</a>, <a href="<?php echo esc_url( home_url( '/jewellery-type/antique-brooches' ) ); ?>">brooches</a> and <a href="<?php echo esc_url( home_url( '/jewellery-type/antique-lockets-pendants' ) ); ?>">vintage lockets</a> for her and a range of elegant <a href="<?php echo esc_url( home_url( '/jewellery-type/antique-cufflinks' ) ); ?>">cufflinks</a>, <a href="<?php echo esc_url( home_url( '/jewellery-type/antique-tie-pins' ) ); ?>">tie pins</a> and historic <a href="<?php echo esc_url( home_url( '/jewellery-type/antique-seals-signet-rings' ) ); ?>">signet rings</a> for him.</p>
				<!--<p>We are recognised stockists of Georgian and Victorian mourning jewellery, ​<a href="<?php echo esc_url( home_url( '/?s=vauxhall+glass' ) ); ?>">Vauxhall glass</a>, <a href="<?php echo esc_url( home_url( '/?s=jet' ) ); ?>">Victorian jet</a>, <a href="<?php echo esc_url( home_url( '/?s=scottish' ) ); ?>">Scottish pebble</a> and <a href="<?php echo esc_url( home_url( '/?s=horn' ) ); ?>">horn jewellery</a> and are happy to receive specific enquiries.</p>
				-->
			</li>
		</ul>

	</div>

	<div class="featured-press clearfix">
		<div class="wrapper">
			<div class="half text-left">
				<img src="<?php bloginfo('template_directory'); ?>/assets/images/front-page/free-delivery.png" alt="Free Delivery" width="300" height="100" class="delivery">
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
			<img src="<?php bloginfo('template_directory'); ?>/assets/images/front-page/olly.jpg" alt="Olly Gerrish" class="space-below circled" width="125" height="125"><br>
			<img src="<?php bloginfo('template_directory'); ?>/assets/images/front-page/olly-gerrish.png" alt="Olly Gerrish" width="221" height="44">
			<h4>Founder</h4>
			<blockquote>
				<p>If you go from Japan to Scotland, from London to Los Angeles and mention Grays Antiques Centre the chances are that someone will say <em>I have been there and do you know Olly Gerrish by any chance?</em> When you have been in the business for a certain length of time you meet a large number of people and a good reputation means your name gets passed around. You can do no better than spending time with Olly who will happily share her expertise and knowledge with you and make buying a piece of period jewellery a pleasure.</p>
			</blockquote>
			<small class="centered">&mdash; Anthea Gesua, Director, LAPADA</small>
			<a href="<?php echo esc_url( home_url( '/our-world/ollys-story/' ) ); ?>">Read Olly's Story</a>
		</div>
	</div>

	<div class="testimonial clearfix">
		<div class="wrapper">
			<div class="half">
				<blockquote>
					<p>My words can't possibly do justice to the AJC. It is my go-to site for Victorian and Georgian jewelry. For any jewelry lover, it is a gem of a find! Having purchased a couple of pieces, the delight of receiving them pales in comparison to the actual pieces themselves. Both Olly and Matt are beyond gracious with their time, their expertise and their customer service. I can only hope to be the recipient of more pieces in the very near future.</p>
				</blockquote>
				<small class="centered">&mdash; Susan, Los Angeles</small>
			</div>
			<div class="half">
				<blockquote>
					<p>It was a pleasure to deal with Olly who answered my queries promptly, and took time and trouble to give a wonderful, personal service. I shall certainly recommend you to my friends, and look forward to finding other wonderful, individual objects from you!</p>
				</blockquote>
				<small class="centered">&mdash; Paul, Dumfrieshire</small>
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