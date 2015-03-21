<?php
/*
Plugin Name:Responsive Vertical Recent Post Ticker
Version:0.1
Description: Displays recent posts as vertical ticker.
Plugin URI: http://beeplugins.com
Author: aumsrini
Author URI: http://beeplugins.com

*/


if (!defined('ABSPATH')) exit;

add_action('wp_enqueue_scripts', 'wp_post_ticker_styles');

//Load Scripts and Styles
 function wp_post_ticker_styles() { 
  // register the stylesheet
 wp_enqueue_style("vertical-post-ticker", plugins_url("assets/css/wp-v-slider.css", __FILE__), FALSE);

}

  // Enqeue the script
add_action('wp_footer', 'wp_post_ticker_scripts');
 
function wp_post_ticker_scripts() {
 wp_enqueue_script("slider-js", plugins_url("assets/js/easy-ticker.js", __FILE__), FALSE);
 
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
		
	$number_recents_posts = '6';
		$recent_posts = wp_get_recent_posts($number_recents_posts);
		 
	foreach( $recent_posts as $recent ){
	
		echo '<li class="odd">';
		echo get_the_post_thumbnail($recent['ID'], 'thumbnail'); 
		echo'<a href="' . get_permalink($recent["ID"]) . '">' .   $recent["post_title"].'</a> ';
		echo '<p>'.substr(strip_tags($recent["post_content"]), 0, $wp_post_slider_text_limit).'</p></li> ';
	}
			
	echo '</ul>	</div></div>';


}
//generate Shortcode
add_shortcode( 'vertical_post_ticker', 'vertical_post_ticker_main' );
//Admin Options

class TickerSettingsPage
{
    /**
     * Holds the values to be used in the fields callbacks
     */
    private $ticker_options;

    /**
     * Start up
     */
    public function __construct()
    {
        add_action( 'admin_menu', array( $this, 'add_plugin_page' ) );
        add_action( 'admin_init', array( $this, 'page_init' ) );
    }

    /**
     * Add options page
     */
    public function add_plugin_page()
    {
        // This page will be under "Settings"
        add_options_page(
            'Settings Admin', 
            'Vertical Ticker Settings', 
            'manage_options', 
            'my-setting-admin', 
            array( $this, 'create_admin_page' )
        );
    }

    /**
     * Options page callback
     */
    public function create_admin_page()
    {
        // Set class property
        $this->options = get_option( 'vertical_ticker_option' );
		
		
		 $siteurl = get_option('siteurl');
    $prourl = $siteurl . '/wp-content/plugins/' . basename(dirname(__FILE__))
        ?>
        <div class="wrap">
		<div style="position:absolute;float:right;left:480px;"><a href="http://beeplugins.com/product/wordpress-vertical-ticker-plugin/" target="_blank"><img src="<?php echo $prourl.'/assets/img/ticker-pro.png '; ?>" border="0"/></a></div>
            <h2>Vertical Post Ticker Settings</h2>           
            <form method="post" action="options.php">
            <?php
                // This prints out all hidden setting fields
                settings_fields( 'vertical_ticker_group' );  
				$options = get_option( 'vertical_ticker_option' ); 
                do_settings_sections( 'vertical-ticker-setting-admin' );
                submit_button(); 
            ?>
            </form>
        </div>
		
        <?php
    }

    /**
     * Register and add settings
     */
    public function page_init()
    {        
        register_setting(
            'vertical_ticker_group', // Option group
            'vertical_ticker_option', // Option name
            array( $this, 'sanitize' ) // Sanitize
        );

        add_settings_section(
            'setting_section_id', // ID
            'My Tciker Settings', // Title
            array( $this, 'print_section_info' ), // Callback
            'vertical-ticker-setting-admin' // Page
        );  

        add_settings_field(
            'direction', // ID
            'Direction', // Title 
            array( $this, 'direction_callback' ), // Callback
            'vertical-ticker-setting-admin', // Page
            'setting_section_id' // Section           
        );      
add_settings_field(
            'ticker_speed', // ID
            'Ticker Speed', // Title 
            array( $this, 'ticker_speed_callback' ), // Callback
            'vertical-ticker-setting-admin', // Page
            'setting_section_id' // Section           
        );    
        add_settings_field(
            'post_count', 
            'How many posts to visible ?', 
            array( $this, 'post_count_callback' ), 
            'vertical-ticker-setting-admin', 
            'setting_section_id'
        ); 
		  add_settings_field(
            'ticker_interval', 
            'Interval for Ticker ', 
            array( $this, 'ticker_interval_callback' ), 
            'vertical-ticker-setting-admin', 
            'setting_section_id'
        );         
    }

    /**
     * Sanitize each setting field as needed
     *
     * @param array $input Contains all settings fields as array keys
     */
    public function sanitize( $input )
    {
        $new_input = array();
        if( isset( $input['direction'] ) )
            $new_input['direction'] = sanitize_text_field( $input['direction'] );

        if( isset( $input['post_count'] ) )
            $new_input['post_count'] = sanitize_text_field( $input['post_count'] );
			
			 if( isset( $input['ticker_speed'] ) )
            $new_input['ticker_speed'] = sanitize_text_field( $input['ticker_speed'] );
			
			 if( isset( $input['ticker_interval'] ) )
            $new_input['ticker_interval'] = sanitize_text_field( $input['ticker_interval'] );


        return $new_input;
    }

    /** 
     * Print the Section text
     */
    public function print_section_info()
    {
        print 'Enter  your options below:';
    }

    /** 
     * Get the settings option array and print one of its values
     */
    public function direction_callback()
    {
       
?> <select name="vertical_ticker_option[direction]" id="direction">
    <option value="up" <?php if ($this->options['direction'] == 'up' ) echo 'selected="selected"'; ?>>up</option>
    <option value="down" <?php if ( $this->options['direction']  == 'down' ) echo 'selected="selected"'; ?>>down</option>

</select>
		<?php 
		
    }
	
	   public function ticker_speed_callback()
    {
       
?> <select name="vertical_ticker_option[ticker_speed]" id="ticker_speed">
    <option value="slow" <?php if ($this->options['ticker_speed'] == 'slow' ) echo 'selected="selected"'; ?>>Slow</option>
    <option value="medium" <?php if ( $this->options['ticker_speed']  == 'medium' ) echo 'selected="selected"'; ?>>Medium</option>
	<option value="fast" <?php if ( $this->options['ticker_speed']  == 'fast' ) echo 'selected="selected"'; ?>>Fast</option>

</select>
		<?php 
		
    }

    /** 
     * Get the settings option array and print one of its values
     */
	 
	   public function ticker_interval_callback()
    {
        printf(
            '<input type="text" id="ticker_interval" name="vertical_ticker_option[ticker_interval]" value="%s" />For eg: 2000',
            isset( $this->options['ticker_interval'] ) ? esc_attr( $this->options['ticker_interval']) : ''
        );
    }
    public function post_count_callback()
    {
        printf(
            '<input type="text" id="post_count" name="vertical_ticker_option[post_count]" value="%s" />',
            isset( $this->options['post_count'] ) ? esc_attr( $this->options['post_count']) : ''
        );
    }
}

if( is_admin() )
    $my_settings_page = new TickerSettingsPage();