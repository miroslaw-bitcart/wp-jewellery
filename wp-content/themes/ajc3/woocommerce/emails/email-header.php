<?php
/**
 * Email Header
 *
 * @author 		WooThemes
 * @package 	WooCommerce/Templates/Emails
 * @version     2.0.0
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

// Load colours
$bg 		= get_option( 'woocommerce_email_background_color' );
$body		= get_option( 'woocommerce_email_body_background_color' );
$base 		= get_option( 'woocommerce_email_base_color' );
$base_text 	= woocommerce_light_or_dark( $base, '#202020', '#ffffff' );
$text 		= get_option( 'woocommerce_email_text_color' );

$bg_darker_10 = woocommerce_hex_darker( $bg, 10 );
$base_lighter_20 = woocommerce_hex_lighter( $base, 20 );
$text_lighter_20 = woocommerce_hex_lighter( $text, 20 );

// For gmail compatibility, including CSS styles in head/body are stripped out therefore styles need to be inline. These variables contain rules which are added to the template inline. !important; is a gmail hack to prevent styles being stripped if it doesn't like something.
$wrapper = "
	background-color: " . esc_attr( $bg ) . ";
	width:100%;
	-webkit-text-size-adjust:none !important;
	margin:0;
	padding: 0;
";
$template_container = "
	background-color: " . esc_attr( $body ) . ";
";
$template_header = "
	background-color: " . esc_attr( $base ) .";
	color: $base_text;
	border-bottom: 0;
	font-family: georgia, serif;
	font-weight:normal;
	line-height:100%;
	vertical-align:middle;
";
$body_content = "
	background-color: " . esc_attr( $body ) . ";
";
$body_content_inner = "
	color: $text_lighter_20;
	font-family: georgia, serif;
	font-size: 16px;
	line-height:150%;
	text-align:left;
";
$header_content_h1 = "
	color: " . esc_attr( $base_text ) . ";
	margin:0;
	padding: 18px 18px 0 18px;
	display:block;
	font-family: georgia, serif;
	font-size: 21px;
	font-weight: normal;
	text-align: center;
	line-height: 150%;
";
?>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
        <title><?php echo get_bloginfo('name'); ?></title>
	</head>
    <body leftmargin="0" marginwidth="0" topmargin="0" marginheight="0" offset="0" style="background-color:#f2f2f2;">
    	<div style="<?php echo $wrapper; ?>">
        	<table border="0" cellpadding="0" cellspacing="0" height="100%" width="100%">
            	<tr>
                	<td align="center" valign="top">
                    	<table border="0" cellpadding="0" cellspacing="0" width="600" id="template_container" style="<?php echo $template_container; ?>">

                    		 <tr>
                              <td align="center" bgcolor="#232121" style="padding:18px 0 18px 0;color:#fff;font-size:16px;font-weight:normal;font-family: georgia,serif;">
                                <img src="https://www.antiquejewellerycompany.com/wp-content/themes/ajc3/woocommerce/emails/images/logo2.png" alt="The Antique Jewellery Company" width="290" height="68" />
                              </td>
                            </tr>

                        	<tr style="padding:18px;">
                            	<td align="center" valign="top">
                                    <!-- Header -->
                                	<table border="0" cellpadding="0" cellspacing="0" width="600" id="template_header" style="<?php echo $template_header; ?>" bgcolor="<?php echo $base; ?>">
                                        <tr>
                                            <td>
                                            	<h2 style="color:#333;font-family:georgia,serif;font-weight:normal;font-size:21px;text-align:center;margin:0; padding:18px 0 0 0;"><?php echo $email_heading; ?></h2>

                                            </td>
                                        </tr>
                                    </table>
                                    <!-- End Header -->
                                </td>
                            </tr>
                        	<tr>
                            	<td align="center" valign="top">
                                    <!-- Body -->
                                	<table border="0" cellpadding="0" cellspacing="0" width="600" id="template_body">
                                    	<tr>
                                            <td valign="top" style="<?php echo $body_content; ?>">
                                                <!-- Content -->
                                                <table border="0" cellpadding="18" cellspacing="0" width="100%">
                                                    <tr>
                                                        <td valign="top">
                                                            <div style="<?php echo $body_content_inner; ?>">