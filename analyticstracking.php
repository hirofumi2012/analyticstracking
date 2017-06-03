<?php
/*
Plugin Name: Analytics Tracking
Version: 2.1
Author: Hirofumi Kuroiwa
*/

/**
 * Add options page
 */
function add_plugin_page() {
	add_options_page( 'Google Analytics', 'Google Analytics', 'manage_options', 'analytics', 'create_options_page' );
}

/**
 * Options page callback
 */
function create_options_page() {
	if ( !current_user_can( 'manage_options' ) )  {
		wp_die( __( 'You do not have sufficient permissions to access this page.' ) );
	}
	echo '<div class="wrap">';
	echo '<h2>Google Analytics</h2>';
	echo '<form method="post" action="options.php">';
	settings_fields( 'google-analytics' );
	do_settings_sections( 'google-analytics' );
	submit_button();
	echo '</form>';
	echo '</div>';
}

/**
 * Register and add settings
 */
function page_init() {
	register_setting(
		'google-analytics', // Option group
		'ga_id', // Option name
		'esc_js' // Sanitize
	);
}

add_action( 'admin_menu', 'add_plugin_page' );
add_action( 'admin_init', 'page_init' );

/**
 * Display the tracking codes
 * Acts as a controller to figure out which code to display
 */
function tracking_code_display() {
	$ga_id = get_option( 'ga_id' );
	if ( $ga_id ) {
?>
<script>
  (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
  (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
  m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
  })(window,document,'script','https://www.google-analytics.com/analytics.js','ga');

  ga('create', '<?php esc_js( $ga_id ); ?>', 'auto');
  ga('send', 'pageview');

</script>
<?php
	}
}

add_action( 'wp_head', 'tracking_code_display', 999999 );

?>
