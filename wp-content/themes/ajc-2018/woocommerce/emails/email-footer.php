<?php
/**
 * Email Footer
 *
 * @author 		WooThemes
 * @package 	WooCommerce/Templates/Emails
 * @version     2.0.0
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

// Load colours
$base = get_option( 'woocommerce_email_base_color' );

$base_lighter_40 = woocommerce_hex_lighter( $base, 40 );

// For gmail compatibility, including CSS styles in head/body are stripped out therefore styles need to be inline. These variables contain rules which are added to the template inline.
$template_footer = "
	border-top:0;
";

$credit = "
	border:0;
    font-weight:normal; 
    color:#999;
	font-family:arial, sans;
	font-size:13px;
	line-height:125%;
	text-align:center;
";
?>
															</div>
														</td>
                                                    </tr>
                                                </table>
                                                <!-- End Content -->
                                            </td>
                                        </tr>
                                    </table>
                                    <!-- End Body -->
                                </td>
                            </tr>
                        	<tr style="background-color: #f2f2f2">
                                <td style="padding: 18px;">
                                    <table border="0" cellpadding="0" cellspacing="0" width="100%">
                                        <tr>
                                             <td style="text-align: center; font-size: 13px; font-family: arial, sans; font-weight: normal; color: #999; line-height: 1.5em">
                                                &copy; <?php echo date("Y"); ?> The Antique Jewellery Company<br>
                                                Shop 158 Grays • 58 Davies St. • London W1K 5LP
                                            </td>
                                        </tr>
                                    </table>
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>
            </table>
        </div>
    </body>
</html>