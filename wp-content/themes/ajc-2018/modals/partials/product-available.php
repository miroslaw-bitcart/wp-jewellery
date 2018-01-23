<?php 
      global $product; 
      $terms = get_the_terms( get_the_ID(), 'period' ); 
      $period = !empty($terms) ? $terms[0] : ''; 
      $period = !empty($period) ? $period->name : '';
?>

<div class="modal fade" id="enquiry-available" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <a class="close ion-android-close" data-dismiss="modal"></a>
        		<div class="image"><?php the_post_thumbnail( 'product-x-small' ); ?></div>
        		<div class="headings">
        			<h1 itemprop="name"><?php the_title(); ?></h1>
                    <h3><span class="space-right"><?php echo $product->get_price_html(); ?></span><?php echo '<span class="space-right">' . $period . '</span>' . '<em>№ </em>'. $product->get_sku() ?></h3>
        		</div>
        	</div>
            <div class="modal-body">
            	<div class="col">
            		<div class="description clearfix">
            			<p>Please use the form on the right to enquire about this item.</p>
            			<p>Alternatively you can call us directly on +44 (0)20 7206 2477 or email us at <a href="mailto:enquiries@antiquejewellerycompany.com">enquiries@antiquejewellerycompany.com</a>.</p>
            			<h4>Want to reserve this item?</h4> 
            			<p>Any item may be reserved for up to 72 hours. For longer, let us know and we will try to accommodate your needs. Reserving an item does not occur automatically so there may be a short delay while we recieve your request.<p>
            			<h4>Want to view this item in person?</h4>
            			<p>You can arrange an appointment at our central London shop by filling in <a href="#" data-toggle="modal" data-target="#viewing">this form</a>.</p>
                        <h4>Want to see a video of this item?</h4>
                        <p>You can Skype video call us now by <a href="skype:oliviagerrish?call">clicking here</a>.</p>
            			<h4>Ready to buy?</h4>
            			<p>To purchase this item click <em>Add to Basket</em>. When you are happy, click <em>Shopping Bag</em> in the top right of the screen and follow our simple and secure checkout. Alternatively you can call us directly on +44 (0)20 7206 2477 to process the payment manually.</p>
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