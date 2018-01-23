<?php
/**
 * Result Count
 *
 * Shows text: Showing x - x of x results
 *
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     2.0.0
 */

global $wp;
$current_url = 'http://'.$_SERVER["HTTP_HOST"] . $_SERVER["REQUEST_URI"] ;

$viewstrx = explode("&view=all", $current_url);
$viewstrx_count = count($viewstrx) - 1;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

global $woocommerce, $wp_query;

if ( ! woocommerce_products_will_display() )
	return;
?>
<?php    
if( $_GET['view'] === 'all' ) { ?>
<a class="view-less" href="<?php if($viewstrx_count >0) {echo $viewstrx[0];} else {echo'.';}?>">View Less</a>
<?php } ?>
<p class="woocommerce-result-count">
	<?php
	$paged    = max( 1, $wp_query->get( 'paged' ) );
	$per_page = $wp_query->get( 'posts_per_page' );
	$total    = $wp_query->found_posts;
	$first    = ( $per_page * $paged ) - $per_page + 1;
	$last     = min( $total, $wp_query->get( 'posts_per_page' ) * $paged );

	if ( 1 == $total ) {
		_e( 'the single item', 'woocommerce' );
	} elseif ( $total <= $per_page || -1 == $per_page ) {
		printf( __( 'All %d items', 'woocommerce' ), $total );
	} else {
		printf( _x( '%1$d–%2$d of %3$d items', '%1$d = first, %2$d = last, %3$d = total', 'woocommerce' ), $first, $last, $total );
	}
	?>
</p>