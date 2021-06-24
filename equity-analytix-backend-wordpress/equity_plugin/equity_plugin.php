<?php
/**
 * @package Messenger
 */
/*
Plugin Name: Equity Analytix Messenger
Description: Messenger is used to send and receive messages from Equity Analytix subscribers
Version: 1.0
Author: Equity Analytix
Author URI: https://analytix.com/
*/

// If called directly abort
if ( ! defined( 'ABSPATH' )) {
    die( 'Access denied.' );
}

require_once 'ea_setup.php' ;

define( 'EA_PLUGIN_DIR', __DIR__ );
