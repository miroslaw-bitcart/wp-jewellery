<?php get_header(); ?>

<div class="our-world-section intro">

	<div class="wrapper">

		<div class="textbox centered">
			<h2 class="space-below">We are a London-based family business with over 50 years experience in the antique jewellery trade</h2>
		</div>

		<div class="grid-container">
			<div class="third">
				<a href="<?php echo esc_url( home_url( '/our-world/visit-us' ) ); ?>">
					<img src="<?php bloginfo('template_directory'); ?>/assets/images/our-world/visit-us.jpg" alt="Visit Us">
				</a>
				<h3 class="centered">Visit Us</h3>
			</div>
			<div class="third">
				<a href="<?php echo esc_url( home_url( '/our-world/our-team' ) ); ?>">
					<img src="<?php bloginfo('template_directory'); ?>/assets/images/our-world/team.jpg" alt="Meet Our Team">
				</a>
				<h3 class="centered">Our Team</h3>
			</div>
			<div class="third">
				<a href="<?php echo esc_url( home_url( '/our-world/ollys-story' ) ); ?>">
					<img src="<?php bloginfo('template_directory'); ?>/assets/images/our-world/ollys-story.jpg" alt="Olly's Story">
				</a>
				<h3 class="centered">Olly's Story</h3>
			</div>
		</div>

	</div>

</div>

<div class="our-world-section jewellery">
	<div class="wrapper">
		<div class="textbox">
			<h4>Our Jewellery</h4>
			<h2 class="space-below">We believe that jewellery should be as unique as the individual who wears it.</h2>
			<p>Every one of our items is a one-of-a-kind, handmade by master craftsmen in bygone times.</p>
			<p>Each has its own story to tell. And a new life to live.</p>
		</div>
	</div>
</div>

<div class="our-world-section quality">
	<div class="wrapper">
		<div class="counter"><span><?php wp_posts_in_days('days=365'); ?></span><br>discoveries in the last year</div>
		<div class="textbox right">
			<h4>Our Quality</h4>
			<h2 class="space-below">Our search for the very best in antique jewellery takes us far and wide.</h2>
			<p>Our reputation gives us unrivalled access to the finest pieces on the market from which we make our selection.</p>
			<p>We put our name behind every item we list, and never settle for anything less than the best.</p>
		</div>
	</div>
</div>

<div class="our-world-section sml-padding">
	<div class="wrapper centered">
		<div class="grid-container guarantee">
			<img src="<?php bloginfo('template_directory'); ?>/assets/images/misc/monogram.png" alt="Testimonials">
			<div class="textbox centered">
				<h4>Our Guarantee</h4>
			</div>
			<h2 class="third">Quality</h2>
			<h2 class="third">Rarity</h2>
			<h2 class="third">Expertise</h2>
			<h2 class="third nofloat">Peace of Mind</h2>
			<h2 class="third nofloat">Personal Touch</h2>
		</div>
	</div>
</div>

<div class="our-world-section vision">
	<div class="wrapper">
		<div class="textbox">
			<h4>Our Vision</h4>
			<h2 class="space-below">We are on a mission to introduce antique jewellery to a new generation.</h2> 
			<p>We see it as a thrilling alternative to today's generic, mass-produced jewellery. With its timeless style, beauty and charm, antique jewellery doesn't fall victim to trends.</p>
			<p>It rewards those who like to stand out from the crowd.</p>
		</div>
	</div>
</div>

<div class="our-world-section way" style="border-bottom:1px solid #eee;">
	<div class="wrapper">
		<div class="textbox right">
			<h4>Our Way</h4>
			<h2 class="space-below">We do things differently.</h2>
			<p>The antique jewellery trade is traditionally <em>very</em> old fashioned, and navigating it can be a tiring and intimidating process.</p>
			<p>At the AJC we have created the type of company we would like to deal with. One that is fresh, friendly and transparent and takes the hassle out of buying.</p>
		</div>
	</div>
</div>

<div class="our-world-section zero">
	<div class="wrapper">
		<div class="textbox centered">
			<h2 class="space-below">Since launching in 2008, we have grown to be one of the world's leading antique jewellery websites</h2>
		</div>
		<div class="grid-container centered">
			<div class="third nofloat">
				<a href="<?php echo esc_url( home_url( '/our-world/testimonials/' ) ); ?>">
					<img src="<?php bloginfo('template_directory'); ?>/assets/images/our-world/testimonials.jpg" alt="Testimonials">
				</a>
				<h3 class="centered">Testimonials</h3>
			</div>
			<div class="third nofloat">
				<a href="<?php echo esc_url( home_url( '/press' ) ); ?>">
					<img src="<?php bloginfo('template_directory'); ?>/assets/images/our-world/press.jpg" alt="Press">
				</a>
				<h3 class="centered">Buzz about the AJC</h3>
			</div>
		</div>
	</div>

</div>

<div class="our-world-section go">
	<div class="wrapper">
		<div class="textbox centered">
			<h2 class="space-below">So what are you waiting for?</h2>
			<a class="button" href="/shop">Start Your Search</a>
		</div>
	</div>
</div>

<?php get_footer(); ?>