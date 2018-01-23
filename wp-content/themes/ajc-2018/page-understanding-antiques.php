<?php get_header(); ?>

	<aside class="static" data-spy="affix" data-offset-top="190" data-offset-bottom="600"><?php get_sidebar( "discover" ); ?></aside>

	<div class="content static">

		<div class="left-col">
			<img src="<?php bloginfo('template_directory'); ?>/assets/images/static-pages/testimonials.jpg" alt="Testimonials">
		</div>

		<div class="right-col">
			<h2>Understanding Antiques</h2>
			<h3 class="space-below">Antique jewellery pieces can have subtle imperfections which add charm and character.</h3>
			<p>Minor flaws or inclusions in stones are often evidence that they are natural and untreated.</p>
			<p>Antiques require care. Consider your jewellery box a beautifully furnished home in which your pieces can luxuriate.<small><a href="<?php echo esc_url( home_url( '/discover/jewellery-care/' ) ); ?>">Click here</a> to view our tips for jewellery care.</small></p>

			<p>We pay careful attention to the condition of our pieces and nothing leaves us without close inspection, please respect your jewellery on receipt as replacement and repair can be a complicated process.</p>

			<p>Feel free to ask us about the history of a piece, they often come with an unusual anecdote. In general, our pieces were made in Britain and have a rich and colourful heritage.</p>
		</div>

	</div>

<?php get_footer(); ?>