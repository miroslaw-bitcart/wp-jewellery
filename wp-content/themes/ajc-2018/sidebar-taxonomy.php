<?php $terms = $template_args['terms']; ?>
<?php $current_page = get_queried_object_id(); ?>
<aside class="taxonomy">
	<ul>
		<?php foreach ($terms as $term) : ?>
			<li class="<?php echo $current_page == $term->term_id ? 'current' : ''; ?>">
				<a href="<?php echo get_term_link( $term ); ?>"><?php echo $term->name; ?></a>
			</li>
		<?php endforeach; ?>
	</ul>
</aside>