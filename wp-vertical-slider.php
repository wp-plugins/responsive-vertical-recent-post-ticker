<?php
/*
Plugin Name:Responsive Vertical Recent Post Ticker
Version:0.1
Description: Displays recent posts as vertical ticker.
Plugin URI: http://beeplugins.com
Author: aumsrini
Author URI: http://beeplugins.com

*/

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

add_action('wp_enqueue_scripts', 'wp_post_ticker_styles');

//Load Scripts and Styles
 function wp_post_ticker_styles() { 
  // register the stylesheet
 wp_enqueue_style("vertical-post-ticker", plugins_url("assets/css/wp-v-slider.css", __FILE__), FALSE);

}

  // Enqeue the script
add_action('wp_enqueue_scripts', 'wp_post_ticker_scripts');
 
function wp_post_ticker_scripts() {
 wp_enqueue_script("slider-js", plugins_url("assets/js/easy-ticker.js", __FILE__), FALSE);
 }
 
add_action( 'admin_enqueue_scripts', ticker_color_picker_scripts);
function ticker_color_picker_scripts() {
wp_enqueue_style( 'wp-color-picker' );
wp_enqueue_script( 'iris', admin_url( 'js/iris.min.js' ), array( 'jquery-ui-draggable', 'jquery-ui-slider', 'jquery-touch-punch' ), false, 1 );
wp_enqueue_script( 'cp-active', plugins_url('assets/js/color-picker.js', __FILE__), array('jquery'), '', true );

}

add_action('wp_footer', 'wp_post_ticker_js');
function wp_post_ticker_js() {

//Get option values from Database

$ticker_options= get_option('vertical_ticker_option'); 


$wp_post_slider_direction=$ticker_options['direction'];

$wp_post_slider_speed=$ticker_options['ticker_speed'];

$wp_post_slider_interval=$ticker_options['ticker_interval'];

$wp_post_slider_visible=$ticker_options['post_count'];


if($wp_post_slider_visible==0){$wp_post_slider_visible="3"; }
//Asign Values to Ticker
 echo '
 
<script>

jQuery(function(){

	jQuery(".vsliderup").easyTicker({
		direction: "'.$wp_post_slider_direction.'",
		speed: "'.$wp_post_slider_speed.'",
		interval: "'.$wp_post_slider_interval.'",
		visible: "'.$wp_post_slider_visible.'"
	});	
	
});
</script>';


}
//Main function to display Ticker
function vertical_post_ticker_main()
{
$wp_post_slider_text_limit='300';
echo '<div class="row">
	<div class="col-sm-6">
	<div class="vsliderup wp-vslider" style="position: relative; height: 456px; overflow: hidden; display: block;"><ul >';
		
	
$ticker_options= get_option('vertical_ticker_option');

$wp_post_slider_no_post=$ticker_options['no_post'];

$wp_post_slider_cat=$ticker_options['ticker_cat'];
		
	
	$args = array(
    'numberposts' => $wp_post_slider_no_post,
    'offset' => 0,
    'category' => $wp_post_slider_cat,
    'orderby' => 'post_date',
    'order' => 'DESC',
    'post_type' => 'post',
    'suppress_filters' => true );
	
	$recent_posts = wp_get_recent_posts($args);
			 
	foreach( $recent_posts as $recent ){
	$ticker_thumb=wp_get_attachment_url( get_post_thumbnail_id($recent["ID"]) );
		echo '<li class="odd">';
		
		echo'<h3><a href="' . get_permalink($recent["ID"]) . '">' .   $recent["post_title"].'</a></h3> ';
		echo '<p>';
		if(has_post_thumbnail($recent["ID"] ))
		{ 
		echo '<img src="'.$ticker_thumb.'" />';
		}
		echo substr(strip_tags($recent["post_content"]), 0, $wp_post_slider_text_limit).'</p></li> ';
	}
			
	echo '</ul>	</div></div>';


}
//generate Shortcode
add_shortcode( 'vertical_post_ticker', 'vertical_post_ticker_main' );

//Include Admin functions
include( plugin_dir_path( __FILE__ ) . 'admin.php');
include( plugin_dir_path( __FILE__ ) . 'custom.php');