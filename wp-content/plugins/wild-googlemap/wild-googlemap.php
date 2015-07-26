<?php
/*
Plugin Name:  WiLD Googlemap
Description: This plugin allows the creation and management of maps using the Googlemap API
Version: 1.9.1
Author: Studio WiLD
Author URI: http://codecanyon.net/item/wild-google-maps/4045150
*/

// Db
global $wpdb;
define("WiLD_GOOGLEMAP_TABLE", "wild_googlemap");

// Include
require_once(plugin_dir_path( __FILE__ ) . "config.php");
require_once(plugin_dir_path( __FILE__ ) . "class-googlemap.php");
require_once(plugin_dir_path( __FILE__ ) . "class-backend-googlemap-message.php");
require_once(plugin_dir_path( __FILE__ ) . "class-backend-googlemap-manager.php");

// Hooks
register_activation_hook(__FILE__, array(WiLD_Plugin_Googlemap::getInstance(), "activate"));
add_action('init', array(WiLD_Plugin_Googlemap::getInstance(), "theme_init"));
add_action('admin_menu', array(WiLD_Plugin_Googlemap::getInstance(), "admin_init"));
add_action('wpmu_new_blog', array(WiLD_Plugin_Googlemap::getInstance(), "new_blog"));

class WiLD_Plugin_Googlemap
{
	// Vars
	private static $instance 	= null;

	// Constructor
	private function __construct() {}
	private function __clone() {}

	// Init
	function theme_init()
	{
		# Javascript
		wp_enqueue_script('jquery');

		wp_deregister_script('api-googlemap');
		wp_register_script('api-googlemap', 'https://maps.googleapis.com/maps/api/js?sensor=false' . (WiLD_GOOGLEMAP_LANGUAGE == '' ? '' : '&language=' . WiLD_GOOGLEMAP_LANGUAGE));
		wp_enqueue_script('api-googlemap');

		wp_deregister_script('wild-googlemap');
		wp_register_script('wild-googlemap', plugins_url('/wild-googlemap-js.js', __FILE__), array('jquery', 'api-googlemap'));
		wp_enqueue_script('wild-googlemap');

		wp_deregister_script('wild-googlemap-frontend');
		wp_register_script('wild-googlemap-frontend', plugins_url('/wild-googlemap-js-frontend.js', __FILE__), array('jquery', 'wild-googlemap'));
		wp_enqueue_script('wild-googlemap-frontend');

		# Css
		wp_deregister_style('wild-googlemap-frontend');
		wp_register_style('wild-googlemap-frontend', plugins_url('/wild-googlemap-css-frontend.css', __FILE__));
		wp_enqueue_style('wild-googlemap-frontend');

		# Shortcodes
		add_filter('widget_text', 	'do_shortcode');		// Enable shortcodes in sidebar widgets
		add_filter('the_excerpt', 	'do_shortcode');		// Enable shortcodes in the excerpt
		#add_filter('comment_text', 'do_shortcode');		// Enable shortcodes in comments

		add_shortcode(WiLD_GOOGLEMAP_SHORTCODE, array(WiLD_GooglemapManager::getInstance(), "render"));
	}

	// Admin
	function admin_init()
	{
		# Activate session
		if(!session_id()) session_start();

		# Icon
		$icon_path = plugins_url('/img/icon.png', __FILE__);

		# Menu
		add_menu_page(__('WiLD Googlemap'), __('Googlemap'), WiLD_GOOGLEMAP_CAPABILITY, 'wild-googlemap', NULL, $icon_path, WiLD_GOOGLEMAP_MENUPOSITION);
		add_submenu_page('wild-googlemap', __('Googlemap List'), 	__('Googlemap List'), WiLD_GOOGLEMAP_CAPABILITY, 	'wild-googlemap', 		array($this, "render_admin"));
		add_submenu_page('wild-googlemap', __('Add new'), 			__('New Googlemap'), WiLD_GOOGLEMAP_CAPABILITY, 	'wild-googlemap-new', 	array($this, "render_admin"));

		# Js
		wp_deregister_script('wild-googlemap-functions');
		wp_register_script('wild-googlemap-functions', plugins_url('/wild-googlemap-js-functions.js', __FILE__), array('jquery'));
		wp_enqueue_script('wild-googlemap-functions');

		wp_deregister_script('wild-colorpicker');
		wp_register_script('wild-colorpicker', plugins_url('/js/colorpicker/colorpicker.js', __FILE__), array('jquery'));
		wp_enqueue_script('wild-colorpicker');

		# Css
		wp_deregister_style('wild-googlemap-backend');
		wp_register_style('wild-googlemap-backend', plugins_url('/wild-googlemap-css-backend.css', __FILE__));
		wp_enqueue_style('wild-googlemap-backend');

		wp_deregister_style('wild-colorpicker');
		wp_register_style('wild-colorpicker', plugins_url('/js/colorpicker/colorpicker.css', __FILE__));
		wp_enqueue_style('wild-colorpicker');

		# Tinymce
		if (current_user_can('edit_posts') || current_user_can('edit_pages'))
		{
			if(get_user_option('rich_editing') == 'true')
			{
				add_filter('mce_external_plugins', array(WiLD_Plugin_Googlemap::getInstance(), 'tinymce_add_plugin'));
				add_filter('mce_buttons', array(WiLD_Plugin_Googlemap::getInstance(), 'tinymce_register_button'));
			}
		}
	}

	function tinymce_register_button($buttons)
	{
		array_push($buttons, "|", "wild_googlemap" );
		return $buttons;
	}

	function tinymce_add_plugin($plugin_array)
	{
		$plugin_array['wild_googlemap'] = plugins_url('/wild-googlemap-js-tinymce.php', __FILE__);
		return $plugin_array;
	}

	function render_admin()
	{
		// Remove all the ugly slashes
		$_POST      = array_map('stripslashes_deep', $_POST);
		$_GET       = array_map('stripslashes_deep', $_GET);
		$_COOKIE    = array_map('stripslashes_deep', $_COOKIE);
		$_REQUEST   = array_map('stripslashes_deep', $_REQUEST);

		switch($_GET['page'])
		{
			case 'wild-googlemap':
				if(isset($_GET['id']))
				{
					if(isset($_GET['action']) && $_GET['action'] == "delete")
						include("wild-googlemap-del.php"); // Delete
					else
						include("wild-googlemap-edit.php"); // Edit
				}
				else
					include("wild-googlemap-list.php");	// List
				break;

			case 'wild-googlemap-new':
				$_GET['id'] = 0;
				include("wild-googlemap-new.php"); // New
				break;
		}
	}

	// Activate
	function activate($_networkwide)
	{
		global $wpdb;
		if (function_exists('is_multisite') && is_multisite())
		{
			// check if it is a network activation - if so, run the activation function for each blog id
			if($_networkwide)
			{
				$old_blog = $wpdb->blogid;

				// Get all blog ids
				$blogids = $wpdb->get_col("SELECT blog_id FROM $wpdb->blogs");
				foreach ($blogids as $blog_id)
				{
					switch_to_blog($blog_id);
					$this->install();
				}

				switch_to_blog($old_blog);
				return;
			}
		}
		$this->install();
	}

	// New blog
	function new_blog($blog_id, $user_id, $domain, $path, $site_id, $meta)
	{
		global $wpdb;

		if(is_plugin_active_for_network(basename(__DIR__) . '/wild-googlemap.php'))
		{
			$old_blog = $wpdb->blogid;
			switch_to_blog($blog_id);
			$this->install();
			switch_to_blog($old_blog);
		}
	}

	// Install
	function install()
	{
		global $wpdb;

		if ($wpdb->get_var("SHOW tables LIKE '". $wpdb->prefix . WiLD_GOOGLEMAP_TABLE."'") != $wpdb->prefix . WiLD_GOOGLEMAP_TABLE)
		{
			$sql = "
			CREATE TABLE IF NOT EXISTS `" . $wpdb->prefix . WiLD_GOOGLEMAP_TABLE . "` (
				`id` 			smallint(5) unsigned 	NOT NULL AUTO_INCREMENT,
				`name`			varchar(64) 			NOT NULL,
				`w`				varchar(5)	 			NOT NULL,
				`h`				varchar(5)	 			NOT NULL,
				`lat`			float		 			NOT NULL,
				`lng`			float		 			NOT NULL,
				`zoom`			tinyint(2)	 			NOT NULL,
				`markers`		text	 				NOT NULL,
				`options`		text	 				NOT NULL,
				PRIMARY KEY (`id`)
			) ENGINE=MyISAM DEFAULT CHARSET=utf8;
			";

			$wpdb->query($sql);
		}

		return true;
	}

	# Singleton
	public static function getInstance()
	{
		if(self::$instance == null)
		{
			$c = __CLASS__;
			self::$instance = new $c;
		}
		return self::$instance;
	}
}

function WiLD_Googlemap_redirect($_dest)
{
	WiLD_Googlemap_MessageManager::getInstance()->register();
	header("Location: {$_dest}");
	exit();
}

function WiLD_Googlemap_escape($_str)
{
	return str_replace('"', '&quot;', $_str);
}

function WiLD_Googlemap_unescape($_str)
{
	return str_replace('&quot;', '"', $_str);
}