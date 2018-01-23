<?php
/*
Plugin Name: WpTuts+ Responsive Video Shortcode
Plugin URI: http://rachelmccollin.com
Description: This plugin provides a shortcode you wrap around the ID of a video in YouTube. The plugin then adds the necessary markup and CSS to make that video responsive. To use it, type [responsive-video]'source'[/responsive-video], where 'source' is the iframe embed code for your video.
Version: 1.0
Author: Rachel McCollin
Author URI: http://rachelmccollin.com
License: GPLv2
*/
?>

<?php
// register the shortcode to wrap html around the content
function wptuts_responsive_video_shortcode( $atts ) {
    extract( shortcode_atts( array (
        'identifier' => ''
    ), $atts ) );
    return '<div class="wptuts-video-container"><iframe src="//www.youtube.com/embed/' . $identifier . '" height="240" width="320" allowfullscreen="" frameborder="0"></iframe></div>
    <!--.wptuts-video-container-->';
}
add_shortcode ('responsive-video', 'wptuts_responsive_video_shortcode' );
?>