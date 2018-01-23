<?php get_header(); ?>

<div class="taxonomy-header full-width behind-the-scenes"></div>
<div class="instafeed">
	<ul id="instafeed"></ul>
	<button id="load-more">Load More</button>
</div>

<script type="text/javascript">
	var feed = new Instafeed({
		get: 'user',
		userId: 977280213,
		accessToken: '612480205.b11c7b6.31abb2039f4b414fb055cf84d4aa4a03',
		clientId: 'b11c7b625b0543f080217bebb29c6c7e',
		template: '<li><a href="{{link}}" target="_blank" title="{{caption}} by @{{model.user.username}}"><img data-lazyload="{{image}}" src="{{image}}" alt="{{caption}} by @{{model.user.username}}"/></a><div class="meta"><span class="ion-heart"></span><a href="{{link}}" target="_blank" title="{{caption}}" > {{likes}}</a></li>',
		resolution: 'standard_resolution',
		limit: '24'
	});
	// call feed.next() on button click
	jQuery('#load-more').on('click', function() {
	  feed.next();
	});
	// run the feed
	feed.run();
 </script>

<?php get_footer(); ?>