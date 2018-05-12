<?php 
/*
Plugin Name:  Autocomplete for Better Image Credits
Description:  Implements autocomplete for "Better Image Credits" plugin
Version:      1.0
Author:       Mario Ito
Author URI:   https://marioito.com.br
*/

class BIC_Autocomplete
{
	
	function __construct()
	{
		register_activation_hook(__FILE__, array(&$this, 'child_plugin_activate'));
		add_action('wp_ajax_credit_search', array(&$this, 'credit_search_callback'));
		add_action('wp_ajax_credit_url_search', array(&$this, 'credit_url_search_callback'));
		add_action('admin_print_scripts', array(&$this, 'dequeue_scripts_admin'));
		add_action('admin_enqueue_scripts', array(&$this, 'enqueue_scripts_admin'));
	}

	function child_plugin_activate(){
	    if (!in_array('better-image-credits/better-image-credits.php', get_option('active_plugins'))) {
	        wp_die('Sorry, but this plugin requires <em>Better Image Credits</em> Plugin to be installed and active. <br><a href="' . admin_url( 'plugins.php' ) . '">&laquo; Return to Plugins</a>');
	    }
	}

	function credit_search_callback() {
		global $wpdb;
		$term = (isset($_REQUEST['term'])) ? $_REQUEST['term'] : false;
		$query = $wpdb->prepare("
				SELECT DISTINCT meta_value
				FROM {$wpdb->postmeta}
				WHERE meta_key = '_wp_attachment_source_name'
				AND meta_value LIKE %s
				ORDER BY meta_value", "{$term}%");

		$urls = $wpdb->get_col($query);
		echo json_encode(array_values(array_filter($urls)));
		wp_die();
	}

	function credit_url_search_callback() {
		global $wpdb;
		$term = (isset($_REQUEST['term'])) ? $_REQUEST['term'] : false;
		$query = $wpdb->prepare("
				SELECT DISTINCT meta_value
				FROM {$wpdb->postmeta}
				WHERE meta_key = '_wp_attachment_source_url'
				AND meta_value LIKE %s
				ORDER BY meta_value", "%{$term}%");

		$urls = $wpdb->get_col($query);
		echo json_encode(array_values(array_filter($urls)));
		wp_die();
	}

	function dequeue_scripts_admin() {
		wp_dequeue_script('credits-admin');
	}

	function enqueue_scripts_admin(){
		wp_enqueue_script('credits-admin-autocomplete', plugins_url('autocomplete.js', __FILE__), array('jquery-ui-autocomplete'), '1.0', true);
	}

}

$BIC_Autocomplete = new BIC_Autocomplete();