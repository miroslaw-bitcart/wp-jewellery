<?php extract( $template_args ); ?>
<?php $form = Archetype_Form::get( 'account' ); ?>

<div class="taxonomy-header border-bottom"><h2>My Account</h2></div>

<form action="#" method="POST" class="ajc_account_form space-above" enctype="multipart/form-data">
		
	<ul class="account clearfix">
		<?php $form->nonce_field(); ?>
		<li><?php $form->get_field( 'first_name' )->show_field(); ?></li>
		<li><?php $form->get_field( 'last_name' )->show_field(); ?></li>

		<!--
		<li>
			<label>Date of birth</label>
			<select name="dob-day">
				<option> 20 </option>
			</select>


			<select name="dob-month">
				<option> May </option>
			</select>

			<select name="dob-year">
				<option> 1983 </option>
			</select>
		</li>
		-->

		<li>
			<?php $gender = $user->get_gender(); ?>
			<label>Gender</label>
				<input type="radio" <?php checked( $gender, 'male' ); ?> name="gender"  value="male">
				<label class="gender">Male</label>
			<input type="radio" <?php checked( $gender, 'female' ); ?> name="gender" value="female">
				<label class="gender">Female</label>
		</li>

		<!--
		<li>
			<label>Location</label>
			<select id="country" class="location" name="user_location_country">
				<option> United Kingdom </option>
			</select>
		</li>
		-->

		<li>
			<label></label>
			<input type="submit" class="silver button left" name="proceed" value="Save Settings"/><p class="icon-angle-right left"></p>
		</li>

	</ul>

</form>

<ul class="account">
	<li class="space-above">
		<label></label>
		<div class="cell">
			<?php if( !$user->has_facebook() || Archetype_Facebook::needs_reauth() ) : ?>
				<?php Archetype_Facebook::button( 'connect', 'Connect to Facebook' ); ?>
			<?php else : ?>
				<div class="at_fb_connect at_facebook facebook connected">
					Connected to Facebook
				</div>						
			<?php endif; ?>
			<small>By connecting with Facebook, you can log in to the AJC quickly and easily with a single click</small>
		</div>
	</li>
</ul>

<ul class="account">
	<li>
		<label></label>
		<div class="cell">
			<a class="delete" onclick="confirm('Are you sure?');" href="<?php echo wp_nonce_url( add_query_arg( 'ajc_action', 'delete_user' ), 'a' ); ?>">Delete Account</a>
		</div>
	</li>
</ul>