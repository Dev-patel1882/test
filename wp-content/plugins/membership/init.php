<?php
/*
Plugin Name: Manage Membership 
Description: This Plugin are used to manage membership.
Version: 1
Author: Dev Patel
Author URI: https://vivanwebsolution.com/
*/
// function to create the DB / Options / Defaults					
function ss_options_install() {

    global $wpdb;

    $table_name = $wpdb->prefix . "membership";
    $charset_collate = $wpdb->get_charset_collate();
    $sql = "CREATE TABLE $table_name (
					  `id` int NOT NULL AUTO_INCREMENT,
					  `email` varchar(250) NOT NULL,
					  `number` int NOT NULL,
					   PRIMARY KEY (`id`),
					   UNIQUE KEY (`email`)
					) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
          ";

    require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
    dbDelta($sql);
}

// run the install scripts upon plugin activation
register_activation_hook(__FILE__, 'ss_options_install');



//menu items
add_action('admin_menu','member_menu');


function member_menu() {
	
	//this is the main item for the menu
	add_menu_page('Manage membership', //page title
	'Manage membership', //menu title
	'manage_options', //capabilities
	'member_list', //menu slug
	'member_list', //function
	

	);
	
	//this is a submenu
	add_submenu_page('member_list', //parent slug
	'member list', //page title
	'Add New', //menu title
	'manage_options', //capability
	'member_list', //menu slug
	'member_list'); //function
	
	//this submenu is HIDDEN, however, we need to add it anyways
	add_submenu_page(null, //parent slug
	'Update Employee', //page title
	'Update', //menu title
	'manage_options', //capability
	'Update_Employee', //menu slug
	'Update_Employee'); //function
}


define('ROOTDIR', plugin_dir_path(__FILE__));



$member_list_path = plugin_dir_path(__FILE__) . 'member_list.php';

require_once($member_list_path);

