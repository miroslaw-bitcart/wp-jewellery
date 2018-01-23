<aside class="shop" role="complementary">

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

	<!-- Ages -->
	<ul class="filter age">
		<?php $tax = get_taxonomy( AJC_PERIOD_TAX ); ?>
		<?php $terms = get_terms( AJC_PERIOD_TAX, array( 'parent' => 0 ) ); ?>
		<li>
			<h4>Age</h4>
			<ul id="ages" class="filter sub-menu collapse in">
				<?php foreach( $terms as $term ) : ?>
					<li>
						<input type="checkbox" id="<?php echo $term->slug; ?>" value="<?php echo $term->slug; ?>" data-bind="checked: _filters.<?php echo $tax->name; ?>" />
						<label for="<?php echo $term->slug; ?>"><?php echo ajc_strip_date_range( $term->name ); ?></label>
					</li>
				<?php endforeach; ?>
			</ul>
		</li>
	</ul>

	
	<ul class="filter space-above">
		<li>
			<input type="checkbox" id="sold" value="sold" data-bind="checked: _filters.<?php echo AJC_P_STATUS; ?>" />
			<label for="sold">The Archive</label>
		</li>
		<li>
			<input type="checkbox" id="on_hold" value="on_hold" data-bind="checked: _filters.<?php echo AJC_P_STATUS; ?>" />
			<label for="on_hold">On Hold</label>
		</li>
	</ul>


</aside>