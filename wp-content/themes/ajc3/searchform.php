<?php if ( !is_page('checkout') && !is_page('thank-you') ) { ?>
	<form action="<?php echo "http://" . $_SERVER['HTTP_HOST']; ?>" method="get">
	    <fieldset>
	        <input type="text" placeholder="Search" name="s" class="autocomplete" value="<?php the_search_query(); ?>" autofocus/>
	        <input type="hidden" name="post_type" value="product" />
	    </fieldset>
	    <div class="space-above left">What can we help you find?</div>
	</form>
<?php } ?>
