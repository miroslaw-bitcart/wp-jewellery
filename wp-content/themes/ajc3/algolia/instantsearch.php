<?php get_header(); ?>
	<aside class="shop">
		<div class="facet"> Filters </div>
		<div class="facet">
				<input type="text" id="algolia-search-box" style="margin-bottom: 1rem;"/>
				<svg class="search-icon" width="15" height="15" viewBox="0 0 40 40" xmlns="http://www.w3.org/2000/svg"><path d="M24.828 31.657a16.76 16.76 0 0 1-7.992 2.015C7.538 33.672 0 26.134 0 16.836 0 7.538 7.538 0 16.836 0c9.298 0 16.836 7.538 16.836 16.836 0 3.22-.905 6.23-2.475 8.79.288.18.56.395.81.645l5.985 5.986A4.54 4.54 0 0 1 38 38.673a4.535 4.535 0 0 1-6.417-.007l-5.986-5.986a4.545 4.545 0 0 1-.77-1.023zm-7.992-4.046c5.95 0 10.775-4.823 10.775-10.774 0-5.95-4.823-10.775-10.774-10.775-5.95 0-10.775 4.825-10.775 10.776 0 5.95 4.825 10.775 10.776 10.775z" fill-rule="evenodd"></path></svg>
		</div>
		<div id="stats" class="facet"></div>
		<div class="facet">
			<div id="clear-all" style="margin-bottom: 10px; text-align:right;"></div>
			<div id="current-refined-values"></div>
		</div>
		<div id="prices" class="facet" style="padding-bottom: 25px;	border-bottom: 1px solid #bbbbbb;"></div>
		<a class="item main" data-toggle="collapse" href="#type" role="button" aria-expanded="false" aria-controls="type" style="font-size:20px; color:red">Type</a>
		<div class="collapse" id="type"  style="border-bottom: 1px solid #bbbbbb;">
			<div id="type" class="facet"></div>
		</div>
		<div id="period" class="facet" style="border-bottom: 1px solid #bbbbbb;"></div>
		<div id="materials" class="facet" style="border-bottom: 1px solid #bbbbbb;"></div>
		<div id="collections" class="facet" style="border-bottom: 1px solid #bbbbbb;"></div>
		<div id="ollypicks" class="facet" style="border-bottom: 1px solid #bbbbbb;"></div>
		<div class="facet" style="border-bottom: 1px solid #bbbbbb;">
			<input type="checkbox" id="sold" value="sold" data-bind="checked: _filters.<?php echo AJC_P_STATUS; ?>" />
			<label for="sold">The Archive</label>
			<input type="checkbox" id="on_hold" value="on_hold" data-bind="checked: _filters.<?php echo AJC_P_STATUS; ?>" />
			<label for="on_hold">On Hold</label>
		</div>
		<div class="advert" data-spy="affix" data-offset-top="3500" data-offset-bottom="400">
				<h4>Need help?</h4>
				<h3>+44 (0)20 7206 2477</h3>
				<a href="mailto:enquiries@antiquejewellerycompany.com">&mdash; Email Us</a><br>
				<a href="<?php echo esc_url( home_url( '/visit-us' ) ); ?>">&mdash;  Visit Us</a>
		</div>
	</aside>

	<div id="ais-wrapper">
		<main id="ais-main">
			<div id="algolia-search-box" hidden>
				<div id="algolia-stats"></div>
				<svg class="search-icon" width="25" height="25" viewBox="0 0 40 40" xmlns="http://www.w3.org/2000/svg"><path d="M24.828 31.657a16.76 16.76 0 0 1-7.992 2.015C7.538 33.672 0 26.134 0 16.836 0 7.538 7.538 0 16.836 0c9.298 0 16.836 7.538 16.836 16.836 0 3.22-.905 6.23-2.475 8.79.288.18.56.395.81.645l5.985 5.986A4.54 4.54 0 0 1 38 38.673a4.535 4.535 0 0 1-6.417-.007l-5.986-5.986a4.545 4.545 0 0 1-.77-1.023zm-7.992-4.046c5.95 0 10.775-4.823 10.775-10.774 0-5.95-4.823-10.775-10.774-10.775-5.95 0-10.775 4.825-10.775 10.776 0 5.95 4.825 10.775 10.776 10.775z" fill-rule="evenodd"></path></svg>
			</div>
			<div id="algolia-hits"></div>
			<div id="algolia-pagination"></div>
		</main>
		<aside id="ais-facets" style="display: none">
			<section class="ais-facets" id="facet-post-types"></section>
			<section class="ais-facets" id="facet-categories"></section>
			<section class="ais-facets" id="facet-tags"></section>
			<section class="ais-facets" id="facet-users"></section>
		</aside>
	</div>
	<script type="text/html" id="tmpl-instantsearch-hit">
		<article itemtype="http://schema.org/Article">
			<# if ( data.images.thumbnail ) { #>
			<div class="ais-hits--thumbnail">
				<a href="{{ data.permalink }}" title="{{ data.post_title }}">
					<img src="{{ data.images.thumbnail.url }}" alt="{{ data.post_title }}" title="{{ data.post_title }}" itemprop="image" />
				</a>
			</div>
			<# } #>
			<div class="ais-hits--content">
				<h2 itemprop="name headline"><a href="{{ data.permalink }}" title="{{ data.post_title }}" itemprop="url">{{{ data._highlightResult.post_title.value }}}</a></h2>
				<div class="excerpt">
					<p>
			<# if ( data._snippetResult['content'] ) { #>
			  <!-- <span class="suggestion-post-content">{{{ data._snippetResult['content'].value }}}</span> -->
			<# } #>
					</p>
				</div>
			</div>
			<div class="ais-clearfix"></div>
		</article>
	</script>

	<link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/ion.rangeslider/2.0.6/css/ion.rangeSlider.css">
  <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/ion.rangeslider/2.0.6/css/ion.rangeSlider.skinFlat.css">

	<script src="https://cdn.jsdelivr.net/ion.rangeslider/2.0.6/js/ion.rangeSlider.min.js"></script>
	<script type="text/javascript">
		jQuery(function() {
			if(jQuery('#algolia-search-box').length > 0) {

				if (algolia.indices.searchable_posts === undefined && jQuery('.admin-bar').length > 0) {
					alert('It looks like you haven\'t indexed the searchable posts index. Please head to the Indexing page of the Algolia Search plugin and index it.');
				}

				var search = instantsearch({
					appId: algolia.application_id,
					apiKey: algolia.search_api_key,
					indexName: algolia.indices.searchable_posts.name,
					urlSync: {
						mapping: {'q': 's'},
						trackedParameters: ['query']
					},
					searchParameters: {
						facetingAfterDistinct: true,
						highlightPreTag: '__ais-highlight__',
						highlightPostTag: '__/ais-highlight__'
					}
				});

				/* Search box widget */
				search.addWidget(
					instantsearch.widgets.searchBox({
						container: '#algolia-search-box',
						placeholder: 'Search for...',
						wrapInput: false,
						poweredBy: algolia.powered_by_enabled
					})
				);
				/* Stats widget */
				search.addWidget(
					instantsearch.widgets.stats({
						container: '#stats'
					})
				);
				/* Hits widget */
				search.addWidget(
					instantsearch.widgets.hits({
						container: '#algolia-hits',
						hitsPerPage: 15,
						templates: {
							empty: 'No results were found for "<strong>{{query}}</strong>".',
							item: wp.template('instantsearch-hit')
						},
						transformData: {
									  item: function (hit) {
							for(var key in hit._highlightResult) {
							  // We do not deal with arrays.
							  if(typeof hit._highlightResult[key].value !== 'string') {
								continue;
							  }
							  hit._highlightResult[key].value = _.escape(hit._highlightResult[key].value);
							  hit._highlightResult[key].value = hit._highlightResult[key].value.replace(/__ais-highlight__/g, '<em>').replace(/__\/ais-highlight__/g, '</em>');
							}

							for(var key in hit._snippetResult) {
							  // We do not deal with arrays.
							  if(typeof hit._snippetResult[key].value !== 'string') {
								continue;
							  }

							  hit._snippetResult[key].value = _.escape(hit._snippetResult[key].value);
							  hit._snippetResult[key].value = hit._snippetResult[key].value.replace(/__ais-highlight__/g, '<em>').replace(/__\/ais-highlight__/g, '</em>');
							}

							return hit;
						  }
						}
					})
				);
				/* Pagination widget */
				search.addWidget(
					instantsearch.widgets.pagination({
						container: '#algolia-pagination'
					})
				);

				// facet-post-types
				search.addWidget(
					instantsearch.widgets.menu({
						container: '#facet-post-types',
						attributeName: 'post_type_label',
						sortBy: ['isRefined:desc', 'count:desc', 'name:asc'],
						limit: 10,
						templates: {
							header: '<h3 class="widgettitle">Post Type</h3>'
						},
					})
				);
				// clearAll
				search.addWidget(
				  instantsearch.widgets.clearAll({
				    container: '#clear-all',
				    templates: {
				      link: 'Clear All'
				    },
				    autoHideContainer: false,
				    clearsQuery: true,
				  })
				);
				//refined values
				search.addWidget(
				  instantsearch.widgets.currentRefinedValues({
				    container: '#current-refined-values',
				    clearAll: 'after',
				    clearsQuery: true,
				    attributes: [
				      {name: 'prices', label: 'Price'},
				      {name: 'type', label: 'Type'},
				      {name: 'period', label: 'Period'},
							{name: 'materials', label: 'Materials'},
							{name: 'collections', label: 'Collection'},
							{name: 'ollypicks', label: 'Olly picks'},
				    ],
				    onlyListedAttributes: true,
				  })
				);
				//facet-categories
				search.addWidget(
					instantsearch.widgets.hierarchicalMenu({
						container: '#facet-categories',
						separator: ' > ',
						sortBy: ['count'],
						attributes: ['taxonomies_hierarchical.category.lvl0', 'taxonomies_hierarchical.category.lvl1', 'taxonomies_hierarchical.category.lvl2'],
						templates: {
							header: '<h3 class="widgettitle">Categories</h3>'
						}
					})
				);
				//facet-tags
				search.addWidget(
					instantsearch.widgets.refinementList({
						container: '#facet-tags',
						attributeName: 'taxonomies.post_tag',
						operator: 'and',
						limit: 15,
						sortBy: ['isRefined:desc', 'count:desc', 'name:asc'],
						templates: {
							header: '<h3 class="widgettitle">Tags</h3>'
						}
					})
				);
				//collections
				search.addWidget(
					instantsearch.widgets.refinementList({
						container: '#collections',
						attributeName: 'taxonomies.collection',
						//operator: 'and',
						limit: 15,
						templates: {
							header: '<div class="facet-title" style= "color: red;font-size: 20px;">Collections</div class="facet-title">'
						}
					})
				);

				// search.addWidget(
				// 	instantsearch.widgets.refinementList({
				// 		container: '#prices',
				// 		attributeName: 'taxonomies.price-range',
				// 		operator: 'or',
				// 		limit: 10,
				// 		templates: {
				// 			header: '<div class="facet-title" style= "color: red;font-size: 20px;">Prices</div class="facet-title">'
				// 		}
				// 	})
				// );

				search.addWidget(
					instantsearch.widgets.rangeSlider({
						container: '#prices',
						attributeName: 'taxonomies.price-range',
						tooltips: {
							format: function(rawValue) {
								return '£' + Math.round(rawValue).toLocaleString();
							}
						},
						// pips: {
						// 	mode: 'positions',
						// 	values: [0,250,500,1000,2000,3000,5000,6000],
						// 	density: 100
						// },
						//pips:true,
						min:0,
						//step:100,
						max:6000,
						templates: {
							header: 'Price Range'
						}
					})
				);

				//material
				search.addWidget(
				  instantsearch.widgets.hierarchicalMenu({
				    container: '#materials',
				    attributes: ['taxonomies_hierarchical.material.lvl0', 'taxonomies_hierarchical.material.lvl1'],
				    templates: {
				      header: '<div class="facet-title" style= "color: red;font-size: 20px;">Materials</div class="facet-title">'
				    }
				  })
				);
				//type
				search.addWidget(
					instantsearch.widgets.refinementList({
						container: '#type',
						attributeName: 'taxonomies.type',
						limit: 15,
						templates: {
							// header: '<div class="facet-title" style= "color: red;font-size: 20px;">Types</div class="facet-title">'
						}
					})
				);
				// period
				search.addWidget(
					instantsearch.widgets.refinementList({
						container: '#period',
						attributeName: 'taxonomies.period',
						//operator: 'and',
						limit: 10,
						templates: {
							header: '<div class="facet-title" style= "color: red;font-size: 20px;">Periods</div class="facet-title">'
						}
					})
				);
				//olly picks
				search.addWidget(
					instantsearch.widgets.refinementList({
						container: '#ollypicks',
						attributeName: 'taxonomies.ollys-picks',
						//operator: 'and',
						limit: 2,
						templates: {
							header: '<div class="facet-title" style= "color: red;font-size: 20px;">Olly Picks</div class="facet-title">'
						}
					})
				);
				//facet-users
				search.addWidget(
					instantsearch.widgets.menu({
						container: '#facet-users',
						attributeName: 'post_author.display_name',
						sortBy: ['isRefined:desc', 'count:desc', 'name:asc'],
						limit: 10,
						templates: {
							header: '<h3 class="widgettitle">Authors</h3>'
						}
					})
				);

				search.start();
				jQuery('#algolia-search-box input').attr('type', 'search').select();
			}
		});
	</script>

	<script src="https://code.jquery.com/jquery-1.10.2.min.js"></script>
	<script src="http://localhost/wp-content/themes/ajc3/assets/javascripts/bootstrap.js"></script>
	<script src="http://localhost/wp-content/themes/ajc3/assets/javascripts/jasny-bootstrap.js"></script>

	<style>
		img{
			width:200px;
			height:200px;
		}
		.ais-range-slider--tooltip{
			background: none;
		}
		.ais-range-slider--value{
			padding-top: 15px;
		}
		aside{
			background-color: #fbfbfb !important;
	    padding: 20px 20px;
	    margin: 10px 30px;
		}
		.collapse.in{
			display: block !important;
		}
		.collapse{
			display: none !important;
		}
	</style>
<?php get_footer(); ?>
