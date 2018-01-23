<?php // this is for prepopulating the form
$email = isset( $_REQUEST[ 'email' ] )? $_REQUEST['email'] : '';
// this is bool for whether or not they failed
$failed = isset( $_REQUEST[ 'login_failed' ] )? $_REQUEST['login_failed'] : '';
// check if we've been redirected from a page that required login so we can send them back
if( isset( $_REQUEST['referer'] ) && $origin = $_REQUEST['referer'] ) {
	$redirect = '/' . $origin;
} else {
	$redirect = '/';
} ?>

<div class="ajc_login">

	<h1 class="border-bottom centered">Log In to Favourite</h1>

	<div class="centered"><?php echo Archetype_Facebook::button( 'login', 'Log in with Facebook' ); ?></div>

	<p class="small centered med-grey">Or</p>

	<?php wp_login_form( array( 
		'label_username' => 'Email',
		'redirect' => add_query_arg( 'logged_in', true, $redirect ),
		'value_username' => $email,
	) ); ?>

	<p class="login-signup centered">
		<a href="<?php echo wp_lostpassword_url(); ?>">Forgot your password?</a><br>
		Not a member yet? <a href="/signup">Sign Up Now</a>
	</p>

</div>

<script>
jQuery(document).ready( function( $ ){
	$( '.at_fb_login' ).click( /*AT_Facebook.contactFacebook */ function() { alert('dd' ); });
});
</script>
