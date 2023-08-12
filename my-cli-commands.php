<?php

/**
 * Plugin Name: Custom CLI Commands
 * Description: Adds custom WP-CLI commands for your needs.
 * Version: 1.0
 * Author: Your Name
 */


if ( ! class_exists('WP_CLI')) {
    return;
}
require_once('classes/Product_CLI.php');