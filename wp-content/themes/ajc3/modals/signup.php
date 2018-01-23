<?php $form = Archetype_Form::get( 'signup' ); ?>

<div class="modal user fade" id="signup" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-md">
        <div class="modal-content">
            <div class="modal-header">
                <a class="close ion-android-close" data-dismiss="modal"></a>
                <h2>Sign Up to The A.J.C</h2>
                <h3>and enjoy exclusive offers, features and services</h3>
            </div>
            <div class="modal-body centered">
                <?php if( isset( $_GET['failed_login'] ) ) : ?>
                	<p class="tn_message error space-above">You are not a member yet - please sign up here</p>
                <?php endif; ?>
                <div class="clearfix"><?php echo Archetype_Facebook::button( 'signup', 'Sign Up with Facebook' ); ?></div>
                <p class="space-below small-space-above blue">It's fast and easy and we will <em>never, ever</em> post without your permission</p>
                <p>Or sign up manually below:</p>
                <form action="#" method="POST" id="ajc_signup_form" class="text-left">
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
                	<button type="submit" class="medium silver button">Sign Up</button>
                </form>
            </div>
            <div class="modal-footer">Already have an account? <a href="/login">Log in here</a></small></div>
        </div>
    </div>
</div>