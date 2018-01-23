<?php get_header(); ?>

	<?php at_form( 'viewing', 'abc' ); ?>

	<aside class="static" data-spy="affix" data-offset-top="190" data-offset-bottom="600"><?php get_sidebar( "contact" ); ?></aside>

	<div class="content static contact">

		<div class="left-col">
			<img src="<?php bloginfo('template_directory'); ?>/assets/images/static-pages/appointment.jpg" alt="Appointment">
		</div>

		<div class="right-col">
			<h2>Sell Your Jewellery</h2>
			<p>If you are interested in selling your antique jewellery then please fill in the form below. Please provide as much information about the item as possible and at least 1 image (up to 3 allowed).</p>
			<p>We only buy items that are antique, ie. at least 50 years old.</p>
			<p>In general we do NOT buy costume jewellery, loose coloured gemstones, pocket watches or watches.</p>
			<div class="modal-form clearfix">
				<?php gravity_form(4, false, false, false, '', false); ?>
			</div>
		</div>

	</div>

<?php get_footer(); ?>