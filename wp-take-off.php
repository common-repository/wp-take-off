<?php
/*
Plugin Name: WP Take off
Plugin URI: https://wptakeoff.com/
Description: WP Takeoff helps you to quickly setup your Wordpress Enviroment!
Author: Vincent Ariens, Eric Mulder
Version: 0.9.3
Author URI: https://wptakeoff.com/
*/

add_action('admin_menu', 'wpto_hide_notices', 999);

function wpto_hide_notices() {
   //clean notices for this page
    if(isset($_GET['page']) && $_GET['page'] == 'wp-takeoff-wizard') {
	foreach($GLOBALS['wp_filter']['admin_notices'] as $prio => $notices) {
	    foreach($notices as $key => $value) {
		unset($GLOBALS['wp_filter']['admin_notices'][$prio][$key]);
		remove_action('admin_notices', $key, $prio);
	    }
	}
    }
}


/* register menu item */
add_action( 'admin_menu', 'register_wp_takeoff_pages', 99 );
function register_wp_takeoff_pages() {
    add_menu_page( 'WP Take off', 'WP Take off', 'install_plugins', 'wp-takeoff-wizard', 'wpto_show_wizard', plugins_url( 'wp-take-off/images/takeoff.png' ), 3.0000001 );
    // Load the JS conditionally
    add_action( 'load-wp-takeoff-wizard', 'takeoff_page_scripts' );
}
function wpto_show_wizard() {
    wp_enqueue_style( 'to_page_css', plugins_url('css/main.css', __FILE__) );

    // Register the script
    wp_register_script( 'to_script', plugins_url('js/javascript.js', __FILE__) );

    // Localize the script with new data
    $translation_array = array(
	    'suggestion_url' => plugins_url('templates/ajax.suggestion.php', __FILE__),
    );
    wp_localize_script( 'to_script', 'globals', $translation_array );

    // Enqueued script with localized data.
    wp_enqueue_script( 'to_script' );

    require(dirname(__FILE__) . '/templates/snippet.header.php');
        if(get_bloginfo('version') < 4.6) {
            require(dirname(__FILE__) . '/templates/page.crashed.php');
        }
        elseif(isset($_GET['step']) && $_GET['step'] == '2') {
            require(dirname(__FILE__) . '/templates/page.wizard-2.php');
        }
        elseif(isset($_GET['step']) && $_GET['step'] == 'activate') {
            require(dirname(__FILE__) . '/templates/page.wizard-activate.php');
        }
        else {
            require(dirname(__FILE__) . '/templates/page.wizard.php');
        }
    require(dirname(__FILE__) . '/templates/snippet.footer.php');
}

add_action( 'admin_enqueue_scripts', 'takeoff_wp_admin_scripts' );
function takeoff_wp_admin_scripts($hook) {
   wp_enqueue_script( 'to_font_awesome', 'https://use.fontawesome.com/e21b95b6c2.js' );
   wp_enqueue_style( 'to_global_css', plugins_url('css/global.css', __FILE__) );
}

//loggers
function wpto_detect_plugin_activation(  $plugin, $network_activation ) {
    // do stuff
    wpto_log_plugin('activated_plugin', $plugin);
}
add_action( 'activated_plugin', 'wpto_detect_plugin_activation', 10, 2 );

function wpto_detect_plugin_deactivation(  $plugin, $network_activation ) {
    // do stuff
    wpto_log_plugin('deactivated_plugin', $plugin);
}
add_action( 'deactivated_plugin', 'wpto_detect_plugin_deactivation', 10, 2 );

function wpto_detect_plugin_delete(  $plugin ) {
    // do stuff
    wpto_log_plugin('delete_plugin', $plugin);
}
add_action( 'delete_plugin', 'wpto_detect_plugin_delete', 10, 1 );

function wpto_log_plugin($action, $trigger_plugin) {
    $GLOBALS['wpto_response'] = 'test';
    ob_start();

    $url = 'http://wptakeoff.com/api/takeoff/log/';

    $all_plugins = get_plugins();
    $plugin_list = array();
    foreach($all_plugins as $plugin => $data) {
     $name = explode('/', $plugin);
     $plugin_list[] = $name[0];
    }

    $active_plugins = get_option('active_plugins');
    $active_plugin_list = array();
    foreach($active_plugins as $key => $value) {
        $string = explode('/',$value); // Folder name will be displayed
        $active_plugin_list[] = $string[0];
    }

    //unset active plugin
    if($action == 'deactivated_plugin') {
	$trigger_plugin_arr = explode('/', $trigger_plugin);
	$del_val = $trigger_plugin_arr[0];
	if(($key = array_search($del_val, $active_plugin_list)) !== false) {
	    echo "unset $key \r";
	    unset($active_plugin_list[$key]);
	}
    }

    //clean url anonymised
    $site_url = site_url();
    $find = array( 'http://', 'https://' );
    $replace = '';
    $clean_url = str_replace( $find, $replace, $site_url );

    $body = array(
                        'domain' => sha1(NONCE_SALT . $clean_url),
                        'active_plugins' => join(',',$active_plugin_list),
                        'all_plugins' => join(',',$plugin_list),
                        'action' => $action,
                        'action_plugin' => $trigger_plugin,
                        );
    $response = wp_remote_post( $url, array(
	'method' => 'POST',
	'timeout' => 45,
	'redirection' => 5,
	'httpversion' => '1.0',
	'blocking' => false,
	'sslverify' => false,
	'headers' => array(),
	'body' => $body,
	'cookies' => array()
    ));

    ob_end_clean();
}


if(!function_exists('print_pre')) {
    function print_pre($input) {
        echo '<pre>';
        print_r($input);
        echo '</pre>';
    }
}