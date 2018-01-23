<nav class="mobile-nav">

	<ul class="relative">
		<li class="left">
			<a class="ion-navicon left" id="nav-toggle" href="#"></a>
		</li>
		<li class="site-title small">
			<a href="<?php echo esc_url( home_url( '/' ) ); ?>" title="<?php echo esc_attr( get_bloginfo( 'name', 'display' ) ); ?>" rel="home">
			<img src="<?php bloginfo('template_directory'); ?>/assets/images/misc/logo-sml.png" alt="The Antique Jewellery Company">
			</a>
		</li>
		<li class="right">
			<a class="ion-search" id="search-toggle" href="#"></a>
			<a class="ion-bag" href="/shopping-bag">
				<span><?php global $woocommerce; ?>
				<?php echo sprintf(_n('', $woocommerce->cart->cart_contents_count, 'woothemes'), $woocommerce->cart->cart_contents_count);?>
				</span>
			</a>
		</li>
	</ul>

	<div class="nav-toggle toggle clearfix">

		<ul class="col">
			<li><a href="<?php echo esc_url( home_url( '/shop' ) ); ?>">All Items</a></li>
			<li><a href="<?php echo esc_url( home_url( '/latest-finds' ) ); ?>">Latest Finds</a></li>
			<li><a href="<?php echo esc_url( home_url( '/trending' ) ); ?>">The Hot 100</a></li>
			<li class="divider"><a href="<?php echo esc_url( home_url( '/ollys-picks/all/' ) ); ?>">Editor's Picks</a></li>
			<li class="divider"><a href="<?php echo esc_url( home_url( '/shop/ajc-gift-card/' ) ); ?>">Gift Cards</a></li>
			<li><a href="<?php echo esc_url( home_url( '/jewellery-type/rings/antique-engagement-rings' ) ); ?>">Engagement Rings</a></li>
			<li><a href="<?php echo esc_url( home_url( '/shop/?_material=diamond' ) ); ?>">Diamond Jewellery</a></li>
			<li class="divider"><a href="<?php echo site_url( '/jewellery-type/mens-jewellery' ); ?>">For Him</a></li>
			<li><a href="<?php echo site_url( '/inspiration' ); ?>">Inspiration</a></li>
			<li><a href="<?php echo site_url( '/shop-the-look' ); ?>">Shop The Look</a></li>
			<li><a href="<?php echo site_url( '/lookbooks' ); ?>">Lookbooks</a></li>
			<li><a href="<?php echo site_url( '/the-ages' ); ?>">The Ages</a></li>
			<li class="divider"><a href="<?php echo site_url( '/our-world' ); ?>">Our World</a></li>
			<li><a href="<?php echo site_url( '/our-world/about-us/' ); ?>">About Us</a></li>
			<li><a href="<?php echo site_url( '/contact' ); ?>">Contact</a></li>
		</ul>

		<ul class="col">
			<li><a href="<?php echo esc_url( home_url( '/jewellery-type/antique-rings' ) ); ?>">Rings</a></li>
			<li><a href="<?php echo esc_url( home_url( '/jewellery-type/antique-earrings' ) ); ?>">Earrings</a></li>
			<li><a href="<?php echo esc_url( home_url( '/jewellery-type/antique-necklaces' ) ); ?>">Necklaces</a></li>
			<li><a href="<?php echo esc_url( home_url( '/jewellery-type/antique-bracelets-bangles' ) ); ?>">Bracelets &amp; Bangles</a></li>
			<li><a href="<?php echo esc_url( home_url( '/jewellery-type/antique-lockets-pendants' ) ); ?>">Lockets &amp; Pendants</a></li>
			<li><a href="<?php echo esc_url( home_url( '/jewellery-type/antique-charms' ) ); ?>">Charms</a></li>
			<li><a href="<?php echo esc_url( home_url( '/jewellery-type/antique-brooches' ) ); ?>">Brooches</a></li>
			<li><a href="<?php echo esc_url( home_url( '/jewellery-type/antique-chains' ) ); ?>">Chains</a></li>
			<li><a href="<?php echo esc_url( home_url( '/jewellery-type/antique-seals-signet-rings' ) ); ?>">Seals &amp; Signets</a></li>
			<li class="divider"><a href="<?php echo esc_url( home_url( '/jewellery-type/antique-curiosities' ) ); ?>">Curiosities</a></li>
			<li><a href="<?php echo esc_url( home_url( '/jewellery-type/antique-cufflinks' ) ); ?>">Cufflinks</a></li>
			<li><a href="<?php echo esc_url( home_url( '/jewellery-type/antique-tie-pins' ) ); ?>">Tie Pins</a></li>
			<li><a href="<?php echo esc_url( home_url( '/jewellery-type/antique-dress-sets' ) ); ?>">Dress Sets</a></li>
		</ul>

		<!--<a class="close-toggle ion-android-close" id="close-nav"></a>-->

	</div>

</nav>