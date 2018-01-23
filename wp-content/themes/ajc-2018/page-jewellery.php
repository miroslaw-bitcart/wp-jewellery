<?php $type_terms = get_terms( AJC_TYPE_TAX ); ?>
<?php $terms_assoc = array();
foreach( $type_terms as $term ) {
	$terms_assoc[$term->slug] = $term;
} ?>

<?php get_header(); ?>

	<?php hm_get_template_part( 'sidebar-jewellery' ); ?>

	<?php global $post; ?>

	<ul class="content dashboard jewellery">
		<li>
			<form action="<?php echo site_url( '/shop' ); ?>" method="get" class="searchbox border-bottom clearfix">
				<ul>
					<li>
						<label>Type</label>
						<select name="<?php echo AJC_TYPE_TAX; ?>">
							<option value="">All</option>
							<?php foreach( $type_terms as $term ) : ?>
								<option value="<?php echo $term->slug; ?>"><?php echo $term->name; ?></option>
							<?php endforeach; ?>
						</select>
					</li>

					<li>
						<label>Period</label>
						<select name="_<?php echo AJC_PERIOD_TAX; ?>">
							<option value="">All</option>
							<?php foreach( get_terms( AJC_PERIOD_TAX ) as $term ) : ?>
								<option value="<?php echo $term->slug; ?>"><?php echo $term->name; ?></option>
							<?php endforeach; ?>
						</select>
					</li>

					<li>
						<label>Material</label>
						<select name="_<?php echo AJC_MATERIAL_TAX; ?>">
							<option value="">All</option>
							<?php foreach( get_terms( AJC_MATERIAL_TAX ) as $term ) : ?>
								<option value="<?php echo $term->slug; ?>"><?php echo $term->name; ?></option>
							<?php endforeach; ?>
						</select>
					</li>

					<li>
						<label>Price Range</label>
						<select name="_<?php echo AJC_PRICE_TAX; ?>">
							<option value="">All</option>
							<?php foreach( get_terms( AJC_PRICE_TAX ) as $term ) : ?>
								<option value="<?php echo $term->slug; ?>"><?php echo $term->name; ?></option>
							<?php endforeach; ?>
						</select>
					</li>

				</ul>
				<ul class="submit">
					<li>
						<label>&nbsp;</label>
						<input type="submit" value="Search" />
					</li>
				</ul>
			</form>
			<ul class="left-block">
				<li class="wide">
					<a href="<?php echo esc_url( home_url( '/jewellery-type/rings' ) ); ?>">
						<?php $t = $terms_assoc['rings']; ?>
						<?php echo ajc_get_taxonomy_image( $t, 'dashboard-wide-large' ); ?>
						<div class="caption">
							<h2>Rings</h2>
							<h4 class="count">(<?php echo ajc_count_available_in_term( $t ); ?>)</h4>
						</div>
					</a>
				</li>
				<li class="narrow">
					<a href="<?php echo esc_url( home_url( '/jewellery-type/earrings' ) ); ?>">
						<?php $t = $terms_assoc['earrings']; ?>
						<?php echo ajc_get_taxonomy_image( $t, 'dashboard-wide-small' ); ?>
						<div class="caption">
							<h2>Earrings</h2>
							<h4 class="count">(<?php echo ajc_count_available_in_term( $t ); ?>)</h4>
						</div>
					</a>
				</li>
				<li class="narrow">
					<a href="<?php echo esc_url( home_url( '/jewellery-type/bracelets-bangles' ) ); ?>">
						<?php $t = $terms_assoc['bracelets-bangles']; ?>
						<?php echo ajc_get_taxonomy_image( $t, 'dashboard-wide-small' ); ?>
						<div class="caption">
							<h2>Bracelets &amp; Bangles</h2>
							<h4 class="count">(<?php echo ajc_count_available_in_term( $t ); ?>)</h4>
						</div>
					</a>
				</li>
				<li class="wide">
					<a href="<?php echo esc_url( home_url( '/jewellery-type/necklaces' ) ); ?>">
						<?php $t = $terms_assoc['necklaces']; ?>
						<?php echo ajc_get_taxonomy_image( $t, 'dashboard-wide-large' ); ?>
						<div class="caption">
							<h2>Necklaces</h2>
							<h4 class="count">(<?php echo ajc_count_available_in_term( $t ); ?>)</h4>
						</div>
					</a>
				</li>
			</ul>
			<ul class="right-block">
				<li>
					<ul class="col1">
						<li>
							<a href="<?php echo esc_url( home_url( '/jewellery-type/lockets-pendants' ) ); ?>">
								<?php $t = $terms_assoc['lockets-pendants']; ?>
								<?php echo ajc_get_taxonomy_image( $t, 'dashboard-narrow-col1-small' ); ?>
								<div class="caption">
									<h2>Lockets &amp; Pendants</h2>
									<h4 class="count">(<?php echo ajc_count_available_in_term( $t ); ?>)</h4>
								</div>
							</a>
						</li>
						<li>
							<a href="<?php echo esc_url( home_url( '/jewellery-type/brooches' ) ); ?>">
								<?php $t = $terms_assoc['brooches']; ?>
								<?php echo ajc_get_taxonomy_image( $t, 'dashboard-narrow-col1-large' ); ?>
								<div class="caption">
									<h2>Brooches</h2>
									<h4 class="count">(<?php echo ajc_count_available_in_term( $t ); ?>)</h4>
								</div>
							</a>
						</li>
					</ul>
					<ul class="col2">
						<li>
							<a href="<?php echo esc_url( home_url( '/jewellery-type/mens-jewellery/' ) ); ?>">
								<?php $t = $terms_assoc['mens-jewellery']; ?>
								<?php echo ajc_get_taxonomy_image( $t, 'dashboard-narrow-col2-large' ); ?>
								<div class="caption">
									<h2>For Him</h2>
									<h4 class="count">(<?php echo ajc_count_available_in_term( $t ); ?>)</h4>
								</div>
							</a>
						</li>
						<li>
							<a href="<?php echo esc_url( home_url( '/jewellery-type/charms' ) ); ?>">
								<?php $t = $terms_assoc['charms']; ?>
								<?php echo ajc_get_taxonomy_image( $t, 'dashboard-narrow-col2-small' ); ?>
								<div class="caption">
									<h2>Charms</h2>
									<h4 class="count">(<?php echo ajc_count_available_in_term( $t ); ?>)</h4>
								</div>
							</a>
						</li>
					</ul>
				</li>
			</ul>
		</li>
	</ul>

<?php get_footer(); ?>