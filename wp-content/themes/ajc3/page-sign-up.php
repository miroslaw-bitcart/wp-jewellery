<?php get_header(); ?>

<?php $form = Archetype_Form::get( 'signup' ); ?>

<div class="wrapper signin">

    <div class="half padding-right padding-top padding-bottom border-double-right relative">

      <h2>Sign Up</h2><hr>

      <form action="#" method="POST" id="ajc_signup_form">
   			<?php $form->messages(); ?>
   			<?php wp_nonce_field( AT_USER_NONCE ); ?>
   			<p><?php $form->get_field( 'first_name' )->show_field(); ?></p>
   			<p><?php $form->get_field( 'last_name' )->show_field(); ?></p>
   			<p><?php $form->get_field( 'email' )->show_field(); ?></p>
   			<p><?php $form->get_field( 'password_1' )->show_field(); ?></p>
   			<p><?php $form->get_field( 'password_2' )->show_field(); ?></p>
        <button type="submit" class="silver large full-width button">Sign Up</button>
      </form>
                  
      <p class="space-above">Already have an account? <a class="underline" href="/sign-in">Sign in here</a></p>
          
    </div>


    <div class="half padding-left padding-top padding-bottom">
        <h2>Become a member of the AJC to receive a sneak peek of our latest finds and so much more…</h2>
    </div>

</div>

<?php get_footer(); ?>