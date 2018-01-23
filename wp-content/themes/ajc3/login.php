<?php

get_header();

// this is for prepopulating the form
$email = isset( $_REQUEST[ 'email' ] )? $_REQUEST['email'] : '';
// this is bool for whether or not they failed
$failed = isset( $_REQUEST[ 'login_failed' ] )? $_REQUEST['login_failed'] : '';
// check if we've been redirected from a page that required login so we can send them back
if( isset( $_REQUEST['referer'] ) && $origin = $_REQUEST['referer'] ) {
	$redirect = '/' . $origin;
} else {
	$redirect = '/';
} ?>

<div class="wrapper centered" id="ajc_login">
	<h1>Log In to The A.J.C</h1>
	<?php echo Archetype_Facebook::button( 'login', 'Log In with Facebook' ); ?>
	<!--
	<iframe src="//www.facebook.com/plugins/likebox.php?href=https%3A%2F%2Fwww.facebook.com%2Fantiquejewellerycompany&amp;colorscheme=light&amp;show_faces=true&amp;header=false&amp;stream=false&amp;show_border=true&amp;appId=393147917445697" scrolling="no" frameborder="0" style="overflow:hidden;width:100%;margin-top:1em;" allowTransparency="true"></iframe>
	-->
	<small class="space-below small-space-above blue">It's fast and easy and we will <em>never, ever</em> post without your permission</small>
	<small>Or log in manually below:</small>
	<?php if( isset( $_GET['login'] ) && $_GET['login'] === 'failed' ) : ?>
	<p class="tn_message error">Login failed</p>
	<?php endif; ?>
	<?php wp_login_form( array( 
		'label_username' => 'Username or email',
		'redirect' => add_query_arg( 'logged_in', true, $redirect ),
		'value_username' => $email,
	) ); ?>
	<small class="login-signup centered space-above">
		<a href="<?php echo wp_lostpassword_url(); ?>">Forgot your password?</a><br>
		Not a member yet? <a href="/signup">Sign Up Now</a>
	</small>
</div>

<?php get_footer(); ?>