<?php global $product; ?>
<div class="modal fade" id="enquiry-sold" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <a class="close ion-android-close" data-dismiss="modal"></a>
        		<div class="image"><?php the_post_thumbnail( 'product-x-small' ); ?></div>
        		<div class="headings">
        			<h1 itemprop="name"><?php the_title(); ?></h1>
                    <h3><span class="space-right red">Sold</span><?php echo '<span class="space-right">' . $period . '</span>' . '<em>№ </em>'. $product->get_sku() ?></h3>
        		</div>
        	</div>
            <div class="modal-body">
            	<div class="col">
            		<div class="description clearfix">
            			<h2 class="red">Sold</h2>
            			<p>Interested in finding something similar to purchase? Constantly buying, we add to our website collections daily so something may be just around the corner. Or, as we continue to buy, we will keep your requests in mind.</p>
                        <p>While every piece of antique jewellery is often unique, we are happy to notify you if we find something similar. Please note the specifics in the form on the right and include a price range if desired. The more information you provide, the better we can be of assistance.</p>
            			<p>Alternatively you can call us directly on +44 (0)20 7206 2477 or email us at <a href="mailto:enquiries@antiquejewellerycompany.com">enquiries@antiquejewellerycompany.com</a>.</p>
            		</div>
            	</div>
        		<div class="col">
        			<div class="modal-form clearfix">
                        <?php gravity_form(1, false, false, false, '', true); ?>
                    </div>
        		</div>
            </div>
        </div>
    </div>
</div>