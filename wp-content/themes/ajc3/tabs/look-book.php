<?php $term = get_term( $template_args['term_id'], AJC_PERIOD_TAX ); ?>
<div class="pinterest padding-top centered">
	<a data-pin-do="embedBoard" href="<?php echo get_field( 'pinterest', $term->taxonomy . '_' . $term->term_id ); ?>" data-pin-scale-width="193" data-pin-board-width="805" data-pin-scale-height="1440"></a>
</div>