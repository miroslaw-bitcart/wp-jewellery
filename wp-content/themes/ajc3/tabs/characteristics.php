<?php $term = get_term( $template_args['term_id'], AJC_PERIOD_TAX ); ?>
<?php echo get_field( 'characteristics', $term->taxonomy . '_' . $term->term_id ); ?>