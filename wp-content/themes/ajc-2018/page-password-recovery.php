<?php get_header(); ?>

<div class="wrapper signin">

    <div class="half padding-right padding-top padding-bottom">

        <h2>Password recovery</h2><hr>

        <form name="lostpasswordform" action="<?php echo site_url('wp-login.php?action=lostpassword', 'login_post') ?>" method="post">
            <p>
                <label for="user_login">Username or E-mail:</label>
                <input type="text" name="user_login" id="user_login" value="">
            </p>

            <input type="hidden" name="redirect_to" value="/login/?action=forgot&success=1">
            <p class="login-submit"><input type="submit" name="wp-submit" id="wp-submit" value="Get New Password" /></p>
        </form>

    </div>


    <div class="half padding-left">
        <img class="space-above" src="http://www.antiquejewellerycompany.com/wp-content/themes/ajc2/assets/images/misc/bertie-404-2.png" alt="Bertie" width="480" height="319">
    </div>

</div>

<?php get_footer(); ?>