<?php $product = new AJC_Product( $template_args['product'] ); ?>
<?php $attachment_ids = $product->get_thumbnail_ids(); ?>

<div class="gallery-modal">

	<a class="buttons prev" href="#">left</a>
    <a class="buttons next" href="#">right</a>

	<div class="thumbs product-images">
		<div class="carousel">
			<ul>
				<?php foreach( $attachment_ids as $id ) : ?>
					<li><a class="zoom" href="<?php echo wp_get_attachment_url( $id ); ?>">
						<?php echo wp_get_attachment_image( $id, array( 100, 100 ) ); ?>
					</a></li>
				<?php endforeach; ?>
			</ul>
		</div>

		<div class="main-image">
			<?php echo wp_get_attachment_image( $attachment_ids[0], array( 600, 999 ), false, array( 'class' => 'wp-post-image' ) ); ?>
		</div>

	</div>

	

</div>