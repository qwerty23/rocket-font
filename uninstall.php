<?php
namespace rocket_font;

// if uninstall.php is not called by WordPress, die
if (!defined('WP_UNINSTALL_PLUGIN')) {
	die;
}

//PluginOptions::
$option_name = "rocket_font";

delete_option($option_name);

// for site options in Multisite
delete_site_option($option_name);

// drop a custom database table

?>