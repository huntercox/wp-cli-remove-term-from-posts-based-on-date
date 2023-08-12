<?php

/**
 * Plugin Name: Custom CLI Commands
 * Description: Adds custom WP-CLI commands for your needs.
 * Version: 1.0
 * Author: Your Name
 */

if (defined('WP_CLI') && WP_CLI) {

    class Product_CLI extends WP_CLI_Command
    {

        /**
         * Untag all early access products when the public access date has passed.
         *
         * ## EXAMPLES
         *
         *     wp product remove_early_access_terms
         *
         * @throws \WP_CLI\ExitException
         */
        public function remove_early_access_terms(): void
        {
            $today = current_time('Y-m-d'); // Get current date in 'Ymd' format


            // Query for all products with the "early-access" term
            $args = array(
                'post_type' => 'product',
                'posts_per_page' => -1,
                'tax_query' => array(
                    array(
                        'taxonomy' => 'product_cat',
                        'field' => 'slug',
                        'terms' => 'early-access',
                    ),
                ),
                'meta_query' => array(
                    array(
                        'key'     => '_product_public_access_date',
                        'compare' => '<=',
                        'value'   => $today,
                        'type'    => 'DATE',
                    ),
                ),
            );

            $products = get_posts($args);

            if ( count($products) === 0 ) {
                WP_CLI::error("Operation failed. No products found.", true);
            }

            foreach ($products as $product) {
                // Remove "early-access" term
                wp_remove_object_terms($product->ID, 'early-access', 'product_cat');

                // Log to WP-CLI
                WP_CLI::line("Removed 'early-access' term from product ID: {$product->ID}");
            }

            WP_CLI::success("Operation completed. Checked " . count($products) . " products.");
        }
    }

    WP_CLI::add_command('product', 'Product_CLI');
}