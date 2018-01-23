<?php get_header();
$terms = get_terms( AJC_COLLECTION_TAX, array(
	'orderby' => 'name',
	'order'   => 'ASC',
	'exclude' => array( 526, 276 ),
	));
?>

<div class="wrapper inspiration">
	<div class="content inspiration centered">
		<?php foreach ( $terms as $term ) : ?>
			<div class="third">
				<a href="<?php echo get_term_link( $term ); ?>">
					<?php echo ajc_get_taxonomy_image( $term, 'grid-larger' ); ?>
					<div class="caption">
						<h4><?php echo $term->name; ?></h4>
					</div>
				</a>
			</div>
		<?php endforeach; ?>
	</div>
</div>

<?php get_footer(); ?>