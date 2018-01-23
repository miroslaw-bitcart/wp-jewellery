<?php get_header(); ?>

	<?php at_form( 'viewing', 'abc' ); ?>

	<aside class="static" data-spy="affix" data-offset-top="190" data-offset-bottom="600"><?php get_sidebar( "contact" ); ?></aside>

	<div class="content static contact">

		<div class="left-col">
			<img src="<?php bloginfo('template_directory'); ?>/assets/images/static-pages/contact-us.jpg" alt="Contact Us">
		</div>

		<div class="right-col">
			<h2>Contact Us</h2>
			<p>For all enquiries please use one of the following:</p>
			<h3 class="gothic space-below">By Telephone:</h3>
			<h3 class="small-space-below">+44 (0)20 7206 2477</h3>
			<small class="small-space-below">
				<em>Our founder, Olly Gerrish, will answer the phone</em><br>
				Monday &ndash; Friday, 10am &ndash; 6pm (GMT)<br>
				Saturday 11am &ndash; 5pm (GMT)<br>
				Sunday, <em>Closed</em>
			</small>
			<small class="space-below block">
				Outside these hours, please leave a message and we will return your call on the next working day.
			</small>
			<h3 class="gothic space-below">By Email:</h3>
			<h3 class="small-space-below">
				<a href="mailto:enquiries@antiquejewellerycompany.com">enquiries@antiquejewellerycompany.com</a>
			</h3>
			<small class="space-below block">We aim to answer all email enquiries within 24 hours.</small>
			<h3 class="gothic space-below">By Filling Out This Form:</h3>
			<div class="modal-form clearfix">
				<?php gravity_form(3, false, false, false, '', true); ?>
			</div>
		</div>

	</div>

<?php get_footer(); ?>