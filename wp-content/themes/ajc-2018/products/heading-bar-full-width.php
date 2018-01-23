<div class="filter-container">
	<div class="filter-controls clearfix">
		<div class="search-results left" data-bind="visible: foundProducts() > 0"><span data-bind="html: $root.foundProductsString()"></span></div>
		<ul class="filter-bar right">
			<li class="sort-by" id="sort-by" data-bind="visible: foundProducts() > 0">
				<ul id="sort-options" class="dropdown">
					<li>
						<a id="sort-option-selected" href="#">Sort By</a>
						<div class="menu-shim">&nbsp;</div>
						<ul class="dropdown-inner"><?php /* dynamically populated */ ?></ul>
					</li>
				</ul>
			</li>
		</ul>
	</div>
</div>
<div class="ion-ios7-reloading" data-bind="visible: !ready()"></div>