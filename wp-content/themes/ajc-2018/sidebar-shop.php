<aside class="shop">

	<div class="filter-summary" data-bind="visible: filtersToShow()" style="display: none;" >
		<ul data-bind="visible: search !== 0 " style="display: none;">
			<li><h4>Searching for</h4></li>
			<li><span data-bind="html: search"></span>
				<a class="remove-filter ion-close" data-bind="attr: {href: clearSearch() }"></a>
			</li>
		</ul>		
		<div data-bind="visible: publicFilters().length" style="display:none;">
			<h4>Active Filters</h4>
			<a class="clear" data-bind="click: clearPublicFilters">Clear All</a>
			<ul data-bind="foreach: publicFilters, visible: publicFilters().length" style="display: none">
				<li>
					<h4 data-bind="html: AJC.filterNames[name]"></h4>
					<ul data-bind="foreach: value">
						<li>
							<span data-bind="html: $root.getPublicFilterNicename( $data )"></span> 
							<a class="remove-filter ion-close" data-bind="click: function() { $root.removeFilter( $parent.name, $data ); }"></a>
						</li>
					</ul>
				</li>
			</ul>
		</div>
	</div>

	<?php if( !wp_is_mobile() ) : ?>
		<ul class="filter price-range space-above">
			<li><h4>Price range</h4></li>
			<li class="price-range clearfix">
				<div id="slider-range"></div>
				<span id="price-low" data-bind="html: '£' + priceLow()"></span>
				<span id="price-high" data-bind="html: '£' + priceHighText()"></span>
			</li>
		</ul>
	<?php endif; ?>

	<?php $terms = get_terms( AJC_TYPE_TAX, array( 'parent' => 0 /*no children */ ) ); ?>
	<?php $term = get_queried_object(); ?>
	<?php if( !empty( $term->taxonomy ) && $term->taxonomy === AJC_TYPE_TAX ) {
		$current_term_id = $term->term_id;
		$current_term_parent = $term->parent ? $term->parent : null;
	} else {
		$current_term_id = -1;
		$current_term_parent = null;
	} ?>

	<?php /* Type */ ?>

	<ul class="type space-above">

		<li><h4>Type</h4></li>

		<li class="all"><a href="<?php echo esc_url( home_url( '/shop' ) ); ?>">All</a></li>

		<?php foreach( $terms as $term ) : ?>
			<?php if( ajc_is_archive_view() ) :
				$link = add_query_arg( 'ajc_archive', 1, get_term_link( $term ) );
			else : 
				$link = get_term_link( $term );
			endif; ?>
			<?php $children = get_term_children( $term->term_id, AJC_TYPE_TAX ); ?>
			<?php $current = $current_term_parent === $term->term_id ||  $current_term_id === $term->term_id; ?>

			<li class="<?php echo $children && $current ? 'current-parent' : null; ?> <?php echo ( $term->term_id === $current_term_id ) ? 'current' : ''; ?>">

				<?php if( $children ) : ?>

					<!-- ko with: dropdown -->
						<a href="#" class="has-dropdown" data-current="<?php echo $current ? 1 : 0; ?>" data-bind="click: showChildren, css: { open: open, init: DOMinit( $element, $data ) }"><?php echo $term->name; ?><span class="ion-chevron-down"></span></a>
					<!-- /ko -->

					<ul class="filter sub-menu">

						<li class="<?php echo ( $term->term_id === $current_term_id ) ? 'current' : ''; ?>">
							<a class="lateral-link <?php echo ( $term->term_id === $current_term_id ) ? 'current' : ''; ?>" href="<?php echo $link; ?>">&mdash; All <?php echo $term->name; ?></a>
						</li>

						<?php foreach( $children as $child ) : ?>
							<?php $child = get_term_by( 'id', $child, AJC_TYPE_TAX ); ?>
							<?php if( ajc_is_archive_view() ) :
								$link = add_query_arg( 'ajc_archive', 1, get_term_link( $child ) );
							else : 
								$link = get_term_link( $child );
							endif; 
							?>

							<li class="<?php echo ( $child->term_id === $current_term_id ) ? 'current' : ''; ?>">
								<a class="lateral-link <?php echo ( $child->term_id === $current_term_id ) ? 'current' : ''; ?>" href="<?php echo $link; ?>">&mdash; <?php echo $child->name; ?></a>
							</li>

						<?php endforeach; ?>

					</ul>

				<?php else : ?>

					<a class="lateral-link" href="<?php echo $link; ?>"><?php echo $term->name; ?></a>

				<?php endif; ?>

			</li>

		<?php endforeach; ?>

	</ul>

	<!-- Ages -->
	<ul class="filter age">
		<?php $tax = get_taxonomy( AJC_PERIOD_TAX ); ?>
		<?php $terms = get_terms( AJC_PERIOD_TAX, array( 'parent' => 0 ) ); ?>
		<li>
			<!-- ko with: dropdown -->
				<a data-toggle="collapse" data-target="#ages">Age<span class="ion-chevron-down"></span></a>
			<!-- /ko -->
			<ul id="ages" class="filter sub-menu collapse">
				<?php foreach( $terms as $term ) : ?>
					<li>
						<input type="checkbox" id="<?php echo $term->slug; ?>" value="<?php echo $term->slug; ?>" data-bind="checked: _filters.<?php echo $tax->name; ?>" />
						<label for="<?php echo $term->slug; ?>"><?php echo ajc_strip_date_range( $term->name ); ?></label>
					</li>
				<?php endforeach; ?>
			</ul>
		</li>
	</ul>

	<!-- Materials -->
	<ul class="filter material">
		<?php $tax = get_taxonomy( AJC_MATERIAL_TAX ); ?>
		<?php $terms = get_terms( AJC_MATERIAL_TAX, array( 'parent' => 0, 'hide_empty' => false ) ); ?>
		<li>
			<a data-toggle="collapse" data-target="#materials">Material<span class="ion-chevron-down"></span></a>
			<ul id="materials" class="filter sub-menu collapse">
				<?php foreach( $terms as $term ) : ?>
					<?php if( in_array( $term->slug, array( 'metals', 'precious-stones' ) ) ) : ?>
							<?php $children = get_term_children( $term->term_id, $tax->name ); ?>
							<?php if( $children ) : ?>
								<?php foreach( $children as $child ) : ?>
									<?php if( $child = get_term_by( 'id', $child, $tax->name ) ) : ?>
										<li>
											<input type="checkbox" id="<?php echo $child->slug; ?>" value="<?php echo $child->slug; ?>" data-bind="checked: _filters.<?php echo $tax->name; ?>" />
											<label for="<?php echo $child->slug; ?>"><?php echo ajc_strip_date_range( $child->name ); ?></label>
										</li>
									<?php endif; ?>
								<?php endforeach; ?>
							<?php endif; ?>
					<?php continue; ?>
					<?php elseif( in_array( $term->slug, array( 'semi-precious-stones', 'other' ) ) ) : ?>
						<?php $children = get_term_children( $term->term_id, $tax->name ); ?>
						<li>
							<?php if( !$children ) continue; ?>
							<!-- ko with: dropdown -->
								<a href="#" data-bind="click: showChildren, css: { open: open }"><?php echo $term->name; ?><span class="ion-chevron-down"></span></a>
							<!-- /ko -->
							<ul class="filter sub-menu">
								<?php foreach( $children as $child ) : ?>
									<?php $child = get_term_by( 'id', $child, $tax->name ); ?>
									<li>
										<input type="checkbox" id="<?php echo $child->slug; ?>" value="<?php echo $child->slug; ?>" data-bind="checked: _filters.<?php echo $tax->name; ?>" />
										<label for="<?php echo $child->slug; ?>"><?php echo ajc_strip_date_range( $child->name ); ?></label>
									</li>
								<?php endforeach; ?>
							</ul>
						</li>
						<?php continue; ?>
					<?php endif; ?>
					<li>
						<input type="checkbox" id="<?php echo $term->slug; ?>" value="<?php echo $term->slug; ?>" data-bind="checked: _filters.<?php echo $tax->name; ?>" />
						<label for="<?php echo $term->slug; ?>"><?php echo ajc_strip_date_range( $term->name ); ?></label>
					</li>
				<?php endforeach; ?>
			</ul>
		</li>
	</ul>

	<!-- Collections -->
	<ul class="filter collection">
		<?php $tax = get_taxonomy( AJC_COLLECTION_TAX ); ?>
		<?php $terms = get_terms( AJC_COLLECTION_TAX, array( 'parent' => 0 ) ); ?>
		<li>
			<a data-toggle="collapse" data-target="#collections">Collections<span class="ion-chevron-down"></span></a>
			<ul id="collections" class="filter sub-menu collapse">
				<?php foreach( $terms as $term ) : ?>
					<li>
						<input type="checkbox" id="<?php echo $term->slug; ?>" value="<?php echo $term->slug; ?>" data-bind="checked: _filters.<?php echo $tax->name; ?>" />
						<label for="<?php echo $term->slug; ?>"><?php echo ajc_strip_date_range( $term->name ); ?></label>
					</li>
				<?php endforeach; ?>
			</ul>
		</li>
	</ul>
	
	<ul class="filter space-above space-below">
		<li>
			<input type="checkbox" id="sold" value="sold" data-bind="checked: _filters.<?php echo AJC_P_STATUS; ?>" />
			<label for="sold">The Archive</label>
		</li>
		<li>
			<input type="checkbox" id="on_hold" value="on_hold" data-bind="checked: _filters.<?php echo AJC_P_STATUS; ?>" />
			<label for="on_hold">On Hold</label>
		</li>
	</ul>

	<div class="advert" data-spy="affix" data-offset-top="3500" data-offset-bottom="400">
			<h4>Need help?</h4>
			<h3>+44 (0)20 7206 2477</h3>
			<a href="mailto:enquiries@antiquejewellerycompany.com">&mdash; Email Us</a><br>
			<a href="<?php echo esc_url( home_url( '/visit-us' ) ); ?>">&mdash;  Visit Us</a>
	</div>

</aside>