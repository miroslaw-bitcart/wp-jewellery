<?php get_header(); ?>

<?php $form = Archetype_Form::get( 'signup' ); ?>

<div class="wrapper centered" id="ajc_signup">

	<h1>Sign up to the A.J.C</h1>
	
	<h2 class="space-below">and enjoy exclusive offers, features and services</h2>

	<?php if( isset( $_GET['failed_login'] ) ) : ?>
		<p class="tn_message error space-above">You are not a member yet - please sign up here</p>
	<?php endif; ?>

	<form action="#" method="POST" id="ajc_signup_form">
		<div class="fb_info clearfix centered space-above">
			<img data-bind="visible: avatar, attr: {src:avatar}" class="avatar left"/>
			<h2 class="username" data-bind="visible: connectedToFB, html: fullName"></h2>
		</div>
		<ul>
			<?php $form->messages(); ?>
			<?php wp_nonce_field( AT_USER_NONCE ); ?>
			<li><?php $form->get_field( AT_FB_ID_META )->show_field(); ?></li>
			<li><?php $form->get_field( AT_FB_TOKEN_META )->show_field(); ?></li>
			<li><?php $form->get_field( 'first_name' )->show_field(); ?></li>
			<li><?php $form->get_field( 'last_name' )->show_field(); ?></li>
			<li><?php $form->get_field( 'email' )->show_field(); ?></li>
			<li><?php $form->get_field( 'password_1' )->show_field(); ?></li>
			<li><?php $form->get_field( 'password_2' )->show_field(); ?></li>
		</ul>
		<button type="submit" class="medium silver button">Join The AJC</button>
	</form>

	<p class="login-signup centered">Already have an account? <a href="/login">Log in here</a></p>

</div>

<?php get_footer(); ?>