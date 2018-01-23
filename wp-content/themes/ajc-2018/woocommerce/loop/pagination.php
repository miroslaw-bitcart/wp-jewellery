<?php
/**
 * Pagination - Show numbered pagination for catalog pages.
 *
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     2.0.0
 */
global $wp;
$current_url = 'http://'.$_SERVER["HTTP_HOST"] . $_SERVER["REQUEST_URI"] ;

$strx = explode("?", $current_url);
$str_count = count($strx) - 1;

$viewstrx = explode("&view=all", $current_url);
$viewstrx_count = count($viewstrx) - 1;


if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

global $wp_query;
 
if ( $wp_query->max_num_pages <= 1 )
    return;

 if ( ! woocommerce_products_will_display() )
	return;
?>

<div class="centered">

	<nav class="woocommerce-pagination">
		<?php
			echo paginate_links( apply_filters( 'woocommerce_pagination_args', array(
				'base'         => esc_url_raw( str_replace( 999999999, '%#%', remove_query_arg( 'add-to-cart', get_pagenum_link( 999999999, false ) ) ) ),
				'format'       => '',
				'add_args'     => '',
				'current'      => max( 1, get_query_var( 'paged' ) ),
				'total'        => $wp_query->max_num_pages,
				'prev_text'    => '<span class="ion-chevron-left"></span>',
				'next_text'    => '<span class="ion-chevron-right"></span>',
				'type'         => 'list'
			) ) );
		?>
		
		<?php if (is_paged()) : ?> 
	            <?php if( $_GET['view'] === 'all' ) {  echo ""; } else {?>
	            <a class="view-all" href="../../?view=all">View All</a>
	            <?php }?>
	    <?php else: ?>
	        <?php if( $_GET['view'] === 'all' ) {  echo ""; } else {?>
	    	<a class="view-all" href="<?php echo $current_url;?><?php if($str_count ==0 ){ echo"?";} else { echo "&";};?>view=all">View All</a>
	        <?php }?>
	    <?php endif; ?>

	    <?php    
	    if( $_GET['view'] === 'all' ) { ?>
	        <div class="view-less"><a href="<?php if($viewstrx_count >0) {echo $viewstrx[0];} else {echo'.';}?>">View Less</a></div>
	    <?php } ?>
	</nav>

</div>