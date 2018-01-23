<?php
    $invalidLogin = (isset($_GET['login']) && $_GET['login'] == 'invalid');
    $invalidSignUp = (isset($_GET['signup']) && $_GET['signup'] == 'invalid');
    $completeSignUp = (isset($_GET['signup']) && $_GET['signup'] == 'complete');
    $activeTabLogin = $invalidLogin || $completeSignUp ? 'in active' : '';
    $activeTabSignUp = $invalidSignUp ? 'in active' : '';
    $activeTabNoState = !$invalidSignUp && !$invalidLogin && !$completeSignUp ? 'in active' : '';
?>
<div class="modal fade" id="login" tabindex="-1" role="dialog" aria-labelledby="basicModal" aria-hidden="true">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <div class="modal-header">
                <a class="close ion-android-close" data-dismiss="modal"></a>
                <p class="site-title centered" id="basicModal">
                The<span>Antique Jewellery</span>Company
                </p>
            </div>
            <div class="modal-body">
                <div class="tab-content">
                	<div class="tab-pane fade in active" id="tab-login">
                	    <?php if($invalidLogin) { ?>
                	        <div id="login_error">
                	            Incorrect e-mail address or password.<br>
                	        </div>
                	    <?php } ?>
                	    <?php if($completeSignUp) { ?>
                	        <div id="login_complete">
                	            Password sent to your e-mail address.<br>
                	        </div>
                	    <?php } ?>
                	    <form name="loginform" id="loginform" action="<?php echo esc_url( site_url( 'wp-login.php', 'login_post' ) ); ?>" method="post">
                	        <div class="form-group">
                	            <input class="form-control small-space-below" autofocus="autofocus" id="name" type="text" placeholder="Email address" name="log" value="<?php echo (isset($_GET['email']) && ($invalidLogin || $completeSignUp) ? $_GET['email'] : ''); ?>" required="required">
                	            <input class="form-control small-space-below" autofocus="autofocus" id="name" type="password" placeholder="Password" name="pwd" required="required">
                	            <input type="hidden" name="redirect_to" value="<?php echo esc_url( site_url('/') ); ?>">
                	            <input type="hidden" name="redirect_on_error" value="<?php echo esc_url( site_url('/?login=invalid') ); ?>">
                	            <input class="btn btn-primary btn-block space-below" type="submit" value="Sign In" data-wait="Please wait...">
                	        </div>
                	    </form>

                	    <p role="tablist">
                	        Not a Member? <a href="#tab-signup" role="tab" data-toggle="tab"> Sign Up</a><br>
                	        <a href="<?php echo wp_lostpassword_url(); ?>">Forgot your password?</a>
                	    </p>
                	</div>
                    <div class="tab-pane fade" id="tab-signup">
                        <?php if($invalidSignUp) { ?>
                            <div id="login_error">
                                <?php
                                $err = '';
                                switch($_GET['err']) {
                                    case 'email_exists' :
                                        $err = 'Your email address already exists';
                                    break;
                                    default:
                                        $err = 'Please enter a valid e-mail address.';
                                }
                                echo $err; ?><br>
                            </div>
                        <?php } ?>
                        <form name="registerform" id="registerform" action="<?php echo esc_url( site_url('wp-login.php?action=register', 'login_post') ); ?>" method="post">
                            <div class="form-group">
                                <input class="form-control" autofocus="autofocus" id="name" type="email" placeholder="Email address" name="user_login" value="<?php echo (isset($_GET['email']) && isset($_GET['signup']) ? $_GET['email'] : ''); ?>" required="required">
                                <input type="hidden" name="redirect_to" value="<?php echo esc_url( site_url('/?signup=complete') ); ?>">
                                <input type="hidden" name="redirect_on_error" value="<?php echo esc_url( site_url('/?signup=invalid') ); ?>">
                                <small>A password will be e-mailed to you.</small>
                                <input class="btn btn-primary btn-block small-space-above space-below" type="submit" value="Sign Up" data-wait="Please wait...">

                            </div>
                        </form>
                        <p role="tablist">
                            Already a Member? <a href="#tab-login" role="tab" data-toggle="tab"> Login</a><br>
                            <a href="<?php echo wp_lostpassword_url(); ?>">Forgot your password?</a>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>