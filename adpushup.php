<?php

/*
  Plugin Name: AdPushup
  Plugin URI: http://adpushup.com
  Description: Maximize your AdSense Ad Revenue!
  Version: 0.9.6
  Author: AdPushup
  Author URI: http://www.adpushup.com
  License: GPL2
 */

// Prevent direct access to file
defined('ABSPATH') or die("Cheatin' uh?");

class AdPushup {

    public $plugin_dir;
    public $version = '0.9.6';
    public $update_url;
    public $platform;
    public $plugin_basename;

    public function __construct() {

	$this->plugin_dir = plugin_dir_path(__FILE__);
	$this->plugin_basename = plugin_basename(__FILE__);

	require_once 'misc.php';
	require_once 'injector.php';
	require_once 'settings.php';

	add_filter("plugin_action_links_$this->plugin_basename", array($this, 'filter_plugin_action_links'));
    }

    public function filter_plugin_action_links($links) {
	$settings_link = '<a href="' . admin_url() . 'options-general.php?page=adpushup_settings_page' . '">Settings</a>';
	array_unshift($links, $settings_link);

	return $links;
    }

}

function AdPushup() {
    global $AdPushup;
    $AdPushup = new AdPushup();
}

add_action('plugins_loaded', 'AdPushup');
