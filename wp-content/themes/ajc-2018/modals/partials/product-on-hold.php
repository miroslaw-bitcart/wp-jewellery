<?php global $product; ?>
<div class="modal fade" id="enquiry-on-hold" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <a class="close ion-android-close" data-dismiss="modal"></a>
        		<div class="image"><?php the_post_thumbnail( 'product-x-small' ); ?></div>
        		<div class="headings">
        			<h1 itemprop="name"><?php the_title(); ?></h1>
                    <h3><span class="space-right blue">On Hold</span><?php echo '<span class="space-right">' . $period . '</span>' . '<em>№ </em>'. $product->get_sku() ?></h3>
        		</div>
        	</div>
            <div class="modal-body">
            	<div class="col">
            		<div class="description clearfix">
            			<h2 class="blue">On Hold</h2>
            			<p>The <?php the_title(); ?> may still yours!</p>
                        <p>When a customer asks us to put an item on hold, we set it aside for 72 hours. If the item is released from hold we will contact you immediately.</p>
                        <p>Use the form to the right to be placed on our waiting list should this item become available.</p>
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