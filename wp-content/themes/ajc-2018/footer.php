			</div><!-- .main -->
			<div id="page_footer"></div>
		</div><!-- #page -->

		<?php if ( !is_front_page() && is_page() && !is_page('checkout') && !is_page('inspiration') && !is_page('shop-the-look') && !is_page('latest-finds') && !is_page('trending') && !is_page('collections') && !is_page('the-ages') && !is_page('our-world') && !is_page('why-antique') && !is_single('lookbook') ) { ?>
			</div>
		<?php } ?>

		<footer class="desktop clearfix">

			<ul class="wrapper">
				<li class="footer-summary">
					<div>
						<h4>Visit Us</h4>
						<p>
							Shop 158 Grays, 58 Davies Street
							<br>London W1K 5LP
							<a href="http://goo.gl/maps/Y8dAZ" class="small-space-left" target="_blank">View on map</a>
						</p>
						<p>
							+44 (0)20 7206 2477
							<br>enquiries@antiquejewellerycompany.com
						</p>
						<p class="copyright">&copy; The Antique Jewellery Company Ltd.<br>
							2008 - <?php echo date('Y'); ?>
						</p>

						<img class="small-space-above" src="<?php bloginfo('template_directory'); ?>/assets/images/misc/monogram.png" alt="The Antique Jewellery Company" height="80" width="69">

					</div>
				</li>
				<li class="footer-sections">
					<ul>
						<li>
							<h4>Shop By</h4>
							<a href="<?php echo esc_url( home_url( '/jewellery-type/antique-rings' ) ); ?>">Rings</a>
							<a href="<?php echo esc_url( home_url( '/jewellery-type/antique-earrings' ) ); ?>">Earrings</a>
							<a href="<?php echo esc_url( home_url( '/jewellery-type/antique-necklaces' ) ); ?>">Necklaces</a>
							<a href="<?php echo esc_url( home_url( '/jewellery-type/antique-bracelets-bangles' ) ); ?>">Bracelets &amp; Bangles</a>
							<a href="<?php echo esc_url( home_url( '/jewellery-type/antique-lockets-pendants' ) ); ?>">Lockets &amp; Pendants</a>
							<a href="<?php echo esc_url( home_url( '/jewellery-type/antique-charms' ) ); ?>">Charms</a>
							<a href="<?php echo esc_url( home_url( '/jewellery-type/antique-brooches' ) ); ?>">Brooches</a>
							<a href="<?php echo esc_url( home_url( '/jewellery-type/antique-chains' ) ); ?>">Chains</a>
							<a href="<?php echo esc_url( home_url( '/jewellery-type/antique-seals-signet-rings' ) ); ?>">Seals &amp; Signet Rings</a>
							<a href="<?php echo esc_url( home_url( '/jewellery-type/antique-curiosities' ) ); ?>">Curiosities</a>
							<a href="<?php echo esc_url( home_url( '/jewellery-type/mens-jewellery/' ) ); ?>">Men's Jewellery</a>
							
						</li>
						<li>
							<h4>About Us</h4>
							<a href="/our-world/about-us/">Our Company</a>
							<a href="/our-world/our-team/">Olly &amp; Matt</a>
							<a href="/our-world/ajc-guarantee/">The AJC Guarantee</a>
							<a href="/our-world/testimonials/">Testimonials</a>
							<a href="/press/">Press</a>
							<a href="/our-world/ollys-story/">Olly's Story</a>
							<a href="/magazine/">Magazine</a>
						</li>
						<li>
							<h4>Legal</h4>
							<a href="/terms-conditions/">Terms &amp; Conditions</a>
							<a href="/privacy-security/">Privacy &amp; Security</a>
						</li>
						<li>
							<h4>Need Help?</h4>
							<a href="/delivery/">Delivery</a>
							<a href="/returns/">Returns</a>
							<a href="/payments/">Payments</a>
							<a href="/order-tracking/">Track Your Order</a>
							<a href="/sell-your-jewellery/">Sell Your Jewellery</a>
							<a href="/faq/">FAQs</a>
							<a href="/contact/">Contact Us</a>
						</li>
						<li class="social-links clearfix">
							<div class="left cards">
								<span class="pf pf-paypal"></span>
								<span class="pf pf-visa"></span>
								<span class="pf pf-mastercard"></span>
								<span class="pf pf-maestro"></span>
								<br>
								<a href="https://sealsplash.geotrust.com/splash?&dn=www.antiquejewellerycompany.com" target="_blank">
									<img class="space-above" width="120" height="30" src="<?php bloginfo('template_directory'); ?>/assets/images/misc/geotrust.png" alt="Secured with GeoTrust">
								</a>

							</div>
							<div class="right">
								<h4>Follow Us:</h4>
								<a href="http://www.facebook.com/antiquejewellerycompany" target="_blank"><span class="ion-social-facebook"></span></a>
								<a href="http://www.pinterest.com/antiquejewels" target="_blank"><span class="ion-social-pinterest"></span></a>
								<a href="http://www.twitter.com/ajc_london" target="_blank"><span class="ion-social-twitter"></span></a>
								<a href="http://www.instagram.com/antiquejewellerycompany" target="_blank"><span class="ion-social-instagram"></span></a>
							</div>
						</li>
					</ul>
				</li>
			</ul>
		</footer>

		<div class="search-toggle toggle clearfix">
			<?php get_search_form(); ?>
			<a class="close-toggle ion-android-close" id="close-search"></a>
		</div>

    	<?php if( is_post_type_archive('product') || is_tax( AJC_TYPE_TAX, AJC_COLLECTION_TAX, AJC_PERIOD_TAX )) : ?>
	    	<a id="back_to_top" href="#"><span class="ion-chevron-up"></span><span id="back_to_top_text">Back to top</span></a>
	    <?php endif; ?>

	    <!-- Google Analytics -->

		<script type="text/javascript">
		  var _gaq = _gaq || [];
		  _gaq.push(['_setAccount', 'UA-11885890-1']);
		  _gaq.push(['_trackPageview']);

		  (function() {
		    var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
		     ga.src = ('https:' == document.location.protocol ? 'https://' : 'http://') + 'stats.g.doubleclick.net/dc.js';
		    var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
		  })();
		</script>

	<?php do_action( 'ajc_body_bottom' ); ?>
	
	<?php wp_footer(); ?>
	
	</body>
</html>