<?php

/**
 * The plugin bootstrap file
 *
 * @link              https://cscode.us
 * @since             1.0.0
 * @package           Dokan_Vendor_Info_Hider
 *
 * @wordpress-plugin
 * Plugin Name:       Dokan Vendor Info Hider
 * Plugin URI:        https://cscode.us/plugins/dokan-vendor-info-hider
 * Description:       Hide vendor information from store-list page and store page
 * Version:           1.0.3
 * Author:            csCode Team
 * Author URI:        https://cscode.us
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       dokan-lite
 * Domain Path:       /languages
 */


// don't call the file directly
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Startup
 */

define( 'DOKAN_VENDOR_INFO_HIDER', '1.0.3' );

function dokan_vendor_info_hider_activate() {
	// if dokan is not active ...
}

add_action( 'admin_notices', 'dokan_plus_notice' );


function dokan_plus_notice() {
  ?>
  <div class="update-nag notice is-dismissible" style="background: #FF8A65; width: 95%">
      <p><?php _e( "Try <a href='https://wordpress.org/plugins/dokan-plus/'>Dokan Plus</a> plugin to get more customized Dokan for free!", 'dokan' ); ?></p>
  </div>
  <?php
}

register_activation_hook( __FILE__, 'dokan_vendor_info_hider_activate' );

/**
 * Add settings field
 *
 */

function dokan_vendor_info_hider_add_hide_info_fields( $settings_fields ) {
	$settings_fields['dokan_general']['dokan_vendor_info_hider'] = [
		'name'    => 'dokan_vendor_info_hider',
		'label'   => __( 'Hide vendor info', 'dokan-lite' ),
		'desc'    => __( 'Hide selected information from vendor store list', 'dokan-lite' ),
		'type'    => 'multicheck',
		'default' => (object) [],
		'options' => (object) [
			'street_1' => 'Street 1',
			'street_2' => 'Street 2',
			'city' 	   => 'City',
			'zip' 	   => 'Zip',
			'country'  => 'Country',
			'state'    => 'State',
			'phone'	   => 'Phone',
			'email'	   => 'email'
		]
	];

	return $settings_fields;
}


/**
 * Modify store data display
 */

function dokan_vendor_info_hider_seller_store_data_modify($seller = null, $store_info = null) {
	$options = get_option( 'dokan_general', array() );
	$dokan_vendor_info_hider = ! empty( $options['dokan_vendor_info_hider'] ) ? $options['dokan_vendor_info_hider'] : [];

	echo '<style>';

	foreach ($dokan_vendor_info_hider as $info) {
		switch ($info) {
			case 'phone':
				echo '.store-phone, .dokan-store-phone { display: none; }';
				break;
			case 'email':
				echo '.store-email, .dokan-store-email { display: none; }';
				break;
			case 'street_1':
				echo '.store-address .street_1, .dokan-store-address .street_1 { display: none; }';
				break;
			case 'street_2':
				echo '.store-address .street_2, .dokan-store-address .street_2 { display: none; }';
				break;
			case 'city':
				echo '.store-address .city, .dokan-store-address .city { display: none; }';
				break;
			case 'zip':
				echo '.store-address .zip, .dokan-store-address .zip { display: none; }';
				break;
			case 'country':
				echo '.store-address .country, .dokan-store-address .country { display: none; }';
				break;
			case 'state':
				echo '.store-address .state, .dokan-store-address .state { display: none; }';
				break;
			case 'location':
				//
				break;

			default:
				// code...
				break;
		}
	}

	echo '</style>';
}

function dokan_vendor_info_hider_after_dokan_fully_loaded() {
	add_filter( 'dokan_settings_fields', 'dokan_vendor_info_hider_add_hide_info_fields' );
	add_action( 'dokan_seller_listing_after_featured', 'dokan_vendor_info_hider_seller_store_data_modify', 10, 2 );
	add_action( 'dokan_store_header_info_fields', 'dokan_vendor_info_hider_seller_store_data_modify' );
}

add_action( 'dokan_loaded', 'dokan_vendor_info_hider_after_dokan_fully_loaded' );
