<?php $form = Archetype_Form::get( 'email' ); ?>

<div class="content">

	<div class="taxonomy-header centered border-bottom"><h2>Email Alerts</h2></div>

	<p class="centered space-above space-below">To update your preferences please check the relevant boxes below.</p>
	<p class="centered space-below">If you wish to unsubscribe from any email, please uncheck the appropriate box and submit. We recommend you check all boxes so you stay up-to-date with the latest news from the AJC.</p>

	<form action="" method="POST" class="ajc_account_form" enctype="multipart/form-data">
		<ul class="account centered">
			<?php $form->nonce_field(); ?>
			<?php $form->get_field( 'receive_newsletter' )->show_field(); ?>
			<li class="space-above left">
				<input type="submit" class="silver button left" name="proceed" value="Save Settings"/><p class="icon-angle-right left"></p>
			</li>
		</ul>
	</form>

</div>