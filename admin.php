<?php //Admin Options
function admin_register_head() {
    $siteurl = get_option('siteurl');
    $url = $siteurl . '/wp-content/plugins/' . basename(dirname(__FILE__)) . '/assets/css/admin.css';
    echo "<link rel='stylesheet' type='text/css' href='$url' />\n";
}
add_action('admin_head', 'admin_register_head');

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
        ?>
		<div class="ticker-admin">
        <div class="wrap">
            <h2>Vertical Ticker Pro Settings-V 1.0 </h2> <br />
			<a style="float:right;color:#FFFF00" href="http://beeplugins.com/forums/" target="_blank">Need Help ?</a>         
            <form method="post" action="options.php">
            <?php
                // This prints out all hidden setting fields
                settings_fields( 'vertical_ticker_group' );  
				$options = get_option( 'vertical_ticker_option' ); 
                do_settings_sections( 'vertical-ticker-setting-admin' );
                submit_button(); 
				
            ?>
            </form>
			<div>Powered By <a  style="color:#FFCC00; text-decoration:none;" target="_blank" href="http://www.beeplugins.com">Beeplugins.com</a></div>
        </div>
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
            'Direction:', // Title 
            array( $this, 'direction_callback' ), // Callback
            'vertical-ticker-setting-admin', // Page
            'setting_section_id' // Section           
        );      
add_settings_field(
            'ticker_speed', // ID
            'Ticker Speed:', // Title 
            array( $this, 'ticker_speed_callback' ), // Callback
            'vertical-ticker-setting-admin', // Page
            'setting_section_id' // Section           
        );    
        add_settings_field(
            'post_count', 
            'How many posts to visible in ticker ? :', 
            array( $this, 'post_count_callback' ), 
            'vertical-ticker-setting-admin', 
            'setting_section_id'
        ); 
		 add_settings_field(
            'no_post', 
            'How many Recent posts to show ? :', 
            array( $this, 'no_post_callback' ), 
            'vertical-ticker-setting-admin', 
            'setting_section_id'
        ); 
		
		  add_settings_field(
            'ticker_interval', 
            'Interval for Ticker: ', 
            array( $this, 'ticker_interval_callback' ), 
            'vertical-ticker-setting-admin', 
            'setting_section_id'
        );        
		add_settings_field(
            'ticker_cat', 
            'Enter the category Id :', 
            array( $this, 'ticker_cat_callback' ), 
            'vertical-ticker-setting-admin', 
            'setting_section_id'
        ); 
		
		add_settings_field(
            'thmb_height', 
            'Thumbnail Height :', 
            array( $this, 'thmb_height_callback' ), 
            'vertical-ticker-setting-admin', 
            'setting_section_id'
        );  
		
		add_settings_field(
            'thmb_width', 
            'Thumbnail Width :', 
            array( $this, 'thmb_width_callback' ), 
            'vertical-ticker-setting-admin', 
            'setting_section_id'
        ); 
		
		 
		add_settings_field(
            'thmb_border', 
            'Thumbnail Border Size: ', 
            array( $this, 'thmb_border_callback' ), 
            'vertical-ticker-setting-admin', 
            'setting_section_id'
        );            
		
		add_settings_field(
            'thmb_border_color', 
            'Thumbnail Border Color:', 
            array( $this, 'thmb_border_color_callback' ), 
            'vertical-ticker-setting-admin', 
            'setting_section_id'
        ); 
		
		add_settings_field(
            'ticker_bg', 
            'Background Color:', 
            array( $this, 'ticker_bg_callback' ), 
            'vertical-ticker-setting-admin', 
            'setting_section_id'
        ); 
		add_settings_field(
            'ticker_title_size', 
            'Post Title Font Size: ', 
            array( $this, 'ticker_font_title_size_callback' ), 
            'vertical-ticker-setting-admin', 
            'setting_section_id'
        );         
		add_settings_field(
            'ticker_title_color', 
            'Post Title Color:', 
            array( $this, 'ticker_title_color_callback' ), 
            'vertical-ticker-setting-admin', 
            'setting_section_id'
        );                  
		
		add_settings_field(
            'ticker_text_color', 
            'Ticker Text Color:', 
            array( $this, 'ticker_text_color_callback' ), 
            'vertical-ticker-setting-admin', 
            'setting_section_id'
        ); 
		add_settings_field(
            'ticker_text_size', 
            'Ticker Text Size:', 
            array( $this, 'ticker_text_size_callback' ), 
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
			
			 if( isset( $input['no_post'] ) )
            $new_input['no_post'] = sanitize_text_field( $input['no_post'] );
			
			
			 if( isset( $input['ticker_speed'] ) )
            $new_input['ticker_speed'] = sanitize_text_field( $input['ticker_speed'] );
			
			 if( isset( $input['ticker_interval'] ) )
            $new_input['ticker_interval'] = sanitize_text_field( $input['ticker_interval'] );
			
			 if( isset( $input['ticker_cat'] ) )
            $new_input['ticker_cat'] = sanitize_text_field( $input['ticker_cat'] );
			
			 if( isset( $input['thmb_height'] ) )
            $new_input['thmb_height'] = sanitize_text_field( $input['thmb_height'] );
			
			 if( isset( $input['thmb_width'] ) )
            $new_input['thmb_width'] = sanitize_text_field( $input['thmb_width'] );
			
			
			if( isset( $input['thmb_border'] ) )
            $new_input['thmb_border'] = sanitize_text_field( $input['thmb_border'] );
			
			if( isset( $input['thmb_border_color'] ) )
            $new_input['thmb_border_color'] = sanitize_text_field( $input['thmb_border_color'] );
			
			
			 if( isset( $input['ticker_bg'] ) )
            $new_input['ticker_bg'] = sanitize_text_field( $input['ticker_bg'] );
			
			 if( isset( $input['ticker_title_size'] ) )
            $new_input['ticker_title_size'] = sanitize_text_field( $input['ticker_title_size'] );
			
			 if( isset( $input['ticker_title_color'] ) )
            $new_input['ticker_title_color'] = sanitize_text_field( $input['ticker_title_color'] );
			
			 if( isset( $input['ticker_text_color'] ) )
            $new_input['ticker_text_color'] = sanitize_text_field( $input['ticker_text_color'] );
			
			 if( isset( $input['ticker_text_size'] ) )
            $new_input['ticker_text_size'] = sanitize_text_field( $input['ticker_text_size'] );
			
			

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
	   public function ticker_cat_callback()
    {
       
printf(
            '<input type="text" id="ticker_cat" name="vertical_ticker_option[ticker_cat]" value="%s" /><br>Enter category id with comma seperator for eg: 22,34,16',
            isset( $this->options['ticker_cat'] ) ? esc_attr( $this->options['ticker_cat']) : ''
        );
		}


    /** 
     * Get the settings option array and print one of its values
     */
	 
	 
	 
	   public function ticker_interval_callback()
    {
        printf(
            '<input type="text" id="ticker_interval" name="vertical_ticker_option[ticker_interval]" value="%s" />ms<br> For eg: 2000',
            isset( $this->options['ticker_interval'] ) ? esc_attr( $this->options['ticker_interval']) : ''
        );
    }
    public function post_count_callback()
    {
        printf(
            '<input type="text" id="post_count" name="vertical_ticker_option[post_count]" value="%s" /> ',
            isset( $this->options['post_count'] ) ? esc_attr( $this->options['post_count']) : ''
        );
    }
	
	 public function no_post_callback()
    {
        printf(
            '<input type="text" id="no_post" name="vertical_ticker_option[no_post]" value="%s" />',
            isset( $this->options['no_post'] ) ? esc_attr( $this->options['no_post']) : ''
        );
    }
	 public function thmb_height_callback()
    {
        printf(
            '<input type="text" id="thmb_height" name="vertical_ticker_option[thmb_height]" value="%s" />px',
            isset( $this->options['thmb_height'] ) ? esc_attr( $this->options['thmb_height']) : ''
        );
    }
	 public function thmb_width_callback()
    {
        printf(
            '<input type="text" id="thmb_width" name="vertical_ticker_option[thmb_width]" value="%s" />px',
            isset( $this->options['thmb_width'] ) ? esc_attr( $this->options['thmb_width']) : ''
        );
    }
	 public function thmb_border_callback()
    {
        printf(
            '<input type="text" id="thmb_border" name="vertical_ticker_option[thmb_border]" value="%s" />px',
            isset( $this->options['thmb_border'] ) ? esc_attr( $this->options['thmb_border']) : ''
        );
    }
	
	 public function thmb_border_color_callback()
    {
        printf(
            '<input type="text" class="color-picker" id="thmb_border_color" name="vertical_ticker_option[thmb_border_color]" value="%s" />',
            isset( $this->options['thmb_border_color'] ) ? esc_attr( $this->options['thmb_border_color']) : ''
        );
    }
	 public function ticker_bg_callback()
    {
        printf(
            '<input type="text" class="color-picker" id="ticker_bg" name="vertical_ticker_option[ticker_bg]" value="%s" />',
            isset( $this->options['ticker_bg'] ) ? esc_attr( $this->options['ticker_bg']) : ''
        );
    }
	
	
	 public function ticker_font_title_size_callback()
    {
        printf(
            '<input type="text"  id="ticker_title_size" name="vertical_ticker_option[ticker_title_size]" value="%s" />px',
            isset( $this->options['ticker_title_size'] ) ? esc_attr( $this->options['ticker_title_size']) : ''
        );
    }
	
	 public function ticker_title_color_callback()
    {
        printf(
            '<input type="text" class="color-picker"  id="ticker_title_color" name="vertical_ticker_option[ticker_title_color]" value="%s" />',
            isset( $this->options['ticker_title_color'] ) ? esc_attr( $this->options['ticker_title_color']) : ''
        );
    }
	
	 public function ticker_text_size_callback()
    {
        printf(
            '<input type="text"  id="ticker_text_size" name="vertical_ticker_option[ticker_text_size]" value="%s" />px',
            isset( $this->options['ticker_text_size'] ) ? esc_attr( $this->options['ticker_text_size']) : ''
        );
    }
	
	 public function ticker_text_color_callback()
    {
        printf(
            '<input type="text" class="color-picker"  id="ticker_text_color" name="vertical_ticker_option[ticker_text_color]" value="%s" />',
            isset( $this->options['ticker_text_color'] ) ? esc_attr( $this->options['ticker_text_color']) : ''
        );
    }
	


}

if( is_admin() )
    $my_settings_page = new TickerSettingsPage();