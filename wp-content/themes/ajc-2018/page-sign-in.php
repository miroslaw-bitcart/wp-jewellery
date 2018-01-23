<?php get_header(); ?>

<div class="wrapper signin">

    <div class="half padding-right padding-top padding-bottom border-double-right relative">
        <h2>Sign In</h2><hr>
        <?php $args = array( 'redirect' => site_url() );
        if(isset($_GET['login']) && $_GET['login'] == 'failed')
        {
            ?>
                <p class="red">You have entered an incorrect username or password, please try again.</p>
            <?php
        }
        wp_login_form( $args );
        ?>
        <p><a class="forgot" href="<?php echo esc_url( home_url( '/password-recovery' ) ); ?>">Lost your password?</a></p>
    </div>

    <div class="half padding-left padding-top padding-bottom">
        <h2>Create an Account</h2><hr>
        <p>Become a member of the AJC to receive sale previews, share your wishlist, track your orders, attend exclusive events and so much more…</p>
        <a class="black button large small-space-above" href="<?php echo esc_url( home_url( '/sign-up' ) ); ?>">Sign Up</a>
    </div>
</div>

<?php get_footer(); ?>