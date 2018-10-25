<?php
/*
Plugin Name: Analytics Tracking
Plugin URI: https://github.com/hirofumi2012/analyticstracking
Description: Add the tracking code snippet to each web page.
Version: 2.6
Author: hirofumi2012
Author URI: https://four-dimensional-friend.com
License: GPLv2 or later
Text Domain: analytics-tracking
*/

/**
 * Load plugin textdomain
 */
function ga_load_textdomain() {
	load_plugin_textdomain( 'analytics-tracking' );
}

add_action( 'plugins_loaded', 'ga_load_textdomain' );

/**
 * Register and add settings
 */
function ga_page_init() {
	register_setting(
		'general', // Option group
		'ga_id', // Option name
		'esc_js' // Sanitize
	);
	
	add_settings_section(
		'google-analytics', // ID
		__( 'Google Analytics', 'analytics-tracking' ), // Title
		'ga_print_section_info', // Callback
		'general' // Page
	);
	
	add_settings_field(
		'ga_id', // ID
		__( 'Tracking ID', 'analytics-tracking' ), // Title
		'ga_id_input', // Callback
		'general', // Page
		'google-analytics' // Section
	);
}

/**
 * Print the Section text
 */
function ga_print_section_info()
{
	_e( 'Enter your Analytics tracking ID. Ex: UA-00000000-0', 'analytics-tracking' );
}

/**
 * Get the settings option and print its values
 */
function ga_id_input()
{
	$ga_id = get_option( 'ga_id', '' );
	printf(
		'<input type="text" id="ga_id" name="ga_id" value="%s" />',
		esc_attr( $ga_id )
	);
}

add_action( 'admin_init', 'ga_page_init' );

/**
 * Display the tracking codes
 * Acts as a controller to figure out which code to display
 */
function ga_tracking_code_display() {
	$ga_id = get_option( 'ga_id' );
	if ( $ga_id ) {
?>
<script>
  (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
  (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
  m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
  })(window,document,'script','https://www.google-analytics.com/analytics.js','ga');

  ga('create', '<?php echo esc_js( $ga_id ); ?>', 'auto');
  ga('send', 'pageview');

</script>
<?php
	}
}

add_action( 'wp_head', 'ga_tracking_code_display', 999999 );

/**
 * Delete setting
 */
function ga_uninstall()
{
	delete_option( 'ga_id' );
}

if ( function_exists( 'register_uninstall_hook' ) ) {
	register_uninstall_hook( __FILE__, 'ga_uninstall' );
}