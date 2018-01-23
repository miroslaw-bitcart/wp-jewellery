<?php get_header(); ?>

<?php $sales = AJC_Flash_Sale::all_sales(); ?>
<?php if( !is_user_logged_in() ) : ?>
	<header class="intro-container clearfix">
		<div class="intro left">
			<h1>The Fortnightly Sale</h1><hr>
			<h2>Our fortnightly sales give AJC members exclusive access to our latest finds before they are made available on the main site &ndash; with discounts of up to 30%</h2>
			<script src="http://connect.facebook.net/en_US/all.js#xfbml=1"></script>
			<script type="text/javascript">document.write('<fb:like layout="button_count" show_faces="false" width="100"></fb:like>')</script>
		</div>
		<div class="signup-box left centered">
			<h2>You need to be a member of the AJC to access The Fortnightly Sale</h2>
			<a class="medium orange button" href="<?php echo esc_url( home_url( '/signup' ) ); ?>">Join Now</a>
			<small class="space-above small sans">Already have an account? <a href="<?php echo esc_url( home_url( '/login' ) ); ?>"><strong>Log in</strong></a></small>
		</div>
	</header>
	<?php else : ?>
	<h2 class="centered space-below sans uppercase">The Fortnightly Sale</h2>
<?php endif; ?>

<?php foreach( $sales as $sale ) : ?>

	<div class="teaser clearfix">
		<aside class="sale <?php echo $sale->get_status(); ?>">
			<h2 class="small-space-below"><?php echo $sale->get_name(); ?></h2><hr>
			<p><?php echo $sale->get_description(); ?></p>
			<?php if( $sale->is_active() ) : ?>
				<?php $expires = $sale->get_time_to_expiry(); ?>
				<h3 class="space-above clock">
					<span class="icon-time"></span>Ends in <strong><?php echo $sale->get_time_to_expiry(); ?></strong>
				</h3>
				<a class="medium orange button space-above" href="<?php echo is_user_logged_in() ? $sale->get_link() : '/signup' ; ?>">Shop the Sale</a>
			<?php elseif ( $sale->is_expired() ) : ?>
				<h3 class="clock space-above ended"><em>Sale ended</em></h3>
			<?php elseif( $sale->is_future() ) : ?>
				<h3 class="space-above clock">
					<span class="icon-time"></span>Starts in <strong><?php echo $sale->get_time_to_start(); ?></strong>
				</h3>
			<?php endif; ?>
		</aside>
		<div class="content shop sale">
			<ul class="products">
				<?php $products = $sale->get_products( 4 ); ?>
				<?php foreach( $products as $product ) : ?>
					<li>
						<?php hm_get_template_part( 'products/grid-product', array( 
						'product' => $product,
						'bindings' => false, 
						'context' => 'sale',
						'classes' => is_user_logged_in() ? '' : 'unavailable',
						'price' => false,
						'reduction' => true,
						'quick_view' => false,
						'link' => $sale->is_active(),
						'custom_link' => is_user_logged_in() ? false : '/signup' ) ); ?>
					</li>
				<?php endforeach; ?>
			</ul>
		</div>
	</div>

<?php endforeach; ?>

<?php get_footer(); ?>