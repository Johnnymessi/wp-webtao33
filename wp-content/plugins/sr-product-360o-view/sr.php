<?php

/*
  Plugin Name: SR Product 360&#176; View
  Plugin URI: http://superrishi.com/plugin/sr-product-360-view
  Description: WooCommerce addon to add 360&#176; image rotation to your store products gallery.
  Version: 3.3.1
  Author: superrishi
  Author URI: http://superrishi.com/

  WC requires at least: 3.5.0
  WC tested up to: 7.3.0

  License: GNU General Public License v3.0
  License URI: http://www.gnu.org/licenses/gpl-3.0.html
  
  Tags: Product 360 degree view, woocommerce product 360 degree view, 360 view, product 360 view, 360 degree view, image rotation, product image rotation, woocommerce image rotation, woocommerce product image rotation
  Text Domain: sr-product-360-view
 */
if (!defined('ABSPATH')):
    exit;
endif;
require_once( 'class.php' );
$SR_WC_P360V = new SR_WC_P360V();
require_once( 'set-icons.php' );
register_activation_hook(__FILE__, array('SR_WC_P360V', 'sr_plugin_activation'));
register_deactivation_hook(__FILE__, array('SR_WC_P360V', 'sr_plugin_deactivation'));
