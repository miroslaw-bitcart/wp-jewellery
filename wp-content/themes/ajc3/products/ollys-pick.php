<?php $product = $template_args['product']; ?>
<?php $text = $template_args['text']; ?>
<img src="<?php bloginfo('template_directory'); ?>/assets/images/misc/ollys-pick.jpg" height="80" width="80" class="right"/>
<?php echo apply_filters( 'the_content', $text ); ?>