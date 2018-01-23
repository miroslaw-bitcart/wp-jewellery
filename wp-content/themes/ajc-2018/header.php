<!DOCTYPE html>

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en-GB" lang="en-GB">

<!--[if IE 7]>
<html class="ie ie7" <?php language_attributes(); ?>>
<![endif]-->
<!--[if IE 8]>
<html class="ie ie8" <?php language_attributes(); ?>>
<![endif]-->
<!--[if !(IE 7) | !(IE 8)  ]><!-->
<!--<![endif]-->

<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>" />
	<title><?php wp_title( '|', true, 'right' ); ?></title>
	<meta name=viewport content="width=device-width, initial-scale=1">
	<meta name="apple-mobile-web-app-capable" content="yes"/>
	<meta name="MobileOptimized" content="320">
	<meta name="robots" content="index, follow">
	<link href="https://plus.google.com/110092914152631533251" rel="author"/>
	<link rel="author" href="https://plus.google.com/110092914152631533251?rel=publisher" />
	<link rel="profile" href="http://gmpg.org/xfn/11" />
	<link rel="shortcut icon" href="https://www.antiquejewellerycompany.com/favicon.ico" type="image/x-icon" />
	<link rel="icon" href="https://www.antiquejewellerycompany.com/favicon.ico" type="image/x-icon" />
	<link rel="apple-touch-icon" href="<?php bloginfo('template_directory'); ?>/assets/images/elements/apple-icon-touch.png" />
	
	<!--[if lt IE 9]>
		<script type="text/javascript" src="<?php bloginfo('template_directory'); ?>/assets/javascripts/lib/html5shiv.js"></script>
		<script type="text/javascript" src="<?php bloginfo('template_directory'); ?>/assets/javascripts/lib/respond.js"></script>
		<script type="text/javascript" src="<?php bloginfo('template_directory'); ?>/assets/javascripts/lib/selectivizr.min.js"></script>
	<![endif]-->	
	
	<?php wp_head(); ?>
	
</head>

<body>

	<a href="https://plus.google.com/110092914152631533251" rel="publisher"></a>
	<?php do_action( 'ajc_body_top' ); ?>

	<div class="header-container">

		<?php if ( !is_page('checkout') ) { ?>

			<div class="utility">
				<div class="free-services">Free Worldwide Delivery On All Orders</div>
				<?php hm_get_template_part( 'mobile-nav' ); ?>
				<nav class="account right">
					<ul class="nav">
						<?php if( !is_user_logged_in() ): ?>
							<li class="signup">
								<a href="<?php echo esc_url( home_url( '/sign-in' ) ); ?>">Sign In</a>
							</li>
						<?php else : ?>
							<?php $user = User::current_user(); ?>
							<li class="user">
								<a href="#">
									<?php echo $user->get_proper_name(); ?>
									<span class="ion-chevron-down"></span>
								</a>
								<ul>
									<li><a href="<?php echo esc_url( home_url( '/account' ) ); ?>">My Account</a></li>
									<li><a href="<?php echo wp_logout_url(); ?>">
										Sign Out</a></li>
								</ul>
							</li>
						<?php endif; ?>
						<li class="cart">
							<a href="<?php echo site_url( "/shopping-bag" ); ?>"> 
								Shopping Bag
								<?php global $woocommerce; ?>
								<em>(<?php echo sprintf(_n('', $woocommerce->cart->cart_contents_count, 'woothemes'), $woocommerce->cart->cart_contents_count);?>)</em>
							</a>
						</li>
					</ul>
				</nav>
			</div>

		<?php } ?>

		<header class="clearfix">
			
			<a class="site-title" href="<?php echo esc_url( home_url( '/' ) ); ?>" title="<?php echo esc_attr( get_bloginfo( 'name', 'display' ) ); ?>" rel="home">
				<img src="<?php bloginfo('template_directory'); ?>/assets/images/misc/ajc-logo.png" alt="The Antique Jewellery Company">
			</a>

			<?php if ( !is_page('checkout') ) { ?>
				<div class="banner search left">
					<div id="banner-search">
						<span class="ion-search"></span>
						Search Our Collection
					</div>
				</div>
		        <div class="banner right">
		        	<h2 class="clearfix">+44 (0)20 7206 2477</h2>
		        	<a href="<?php echo esc_url( home_url( '/our-world/visit-us/' ) ); ?>">Visit our London Shop <span class="ion-chevron-right"></span></a>
		        </div>
	        <?php } ?>
	        
		</header>
	
		<!--
		<?php if ( !is_page('checkout') ) { ?>
			<nav class="primary clearfix" id="nav">
				
			</nav>
		<?php } ?>
		-->	

	</div>

	<?php if ( !is_front_page() && is_page() && !is_page('checkout') && !is_page('inspiration') && !is_page('shop-the-look') && !is_page('latest-finds') && !is_page('trending') && !is_page('collections') && !is_page('the-ages') && !is_page('our-world') && !is_page('why-antique') && !is_single('lookbook') ) { ?>

	    <div class="wrapper">
	    	
		<?php } ?>

		<div id="page" class="hfeed">
			<div class="main relative clearfix">
				<?php tn_messages(); ?>