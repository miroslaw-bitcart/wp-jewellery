<?php 
	  global $product; 
      $terms = get_the_terms( get_the_ID(), 'period' ); 
	  $period = !empty($terms) ? $terms[0] : ''; 
	  $period = !empty($period) ? $period->name : '';
?>

<div class="modal fade" id="viewing" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
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
            <div class="modal-header viewing"></div>
            <div class="modal-body">
                <div class="col">
                    <p style="font-size:1.5em;">Shop 158 Grays<br>
                    58 Davies Street<br>
                    London W1K 5LP<a class="small space-left ion-pin" href="http://goo.gl/maps/Y8dAZ" target="_blank"> View on map</a></p>
                    <p>Our shop is situated in the world-famous <a href="http://www.graysantiques.com" target="_blank">Grays Antiques Centre</a>, home to one of the world’s largest and most diverse collections of fine antiques, jewellery, and vintage fashion.</p>
                    <p style="font-size:1.25em;"><em>Opening Times:</em><br>Monday &ndash; Friday, 10.00am &ndash; 6.00pm<br>Saturday, 11.00am &ndash; 5.00pm<br>Sunday, Closed</p>
                    <p>We are the shop at the bottom of the main stairs to the left, with 'The Antique Jewellery Company' on its fascia</p>
                </div>
                <div class="col">
                    <p>To request a viewing please fill in the form below:</p>
                    <div class="modal-form clearfix">
                        <?php gravity_form(2, false, false, false, '', true); ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>