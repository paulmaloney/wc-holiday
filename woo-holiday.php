<?php
/*
Plugin Name: WooHoliday
Plugin URI: https://github.com/paulmaloney/woo-holiday
Version: 1.0
Description: This plugin gives basic options to disable WooCommerce
Author: Paul Maloney
Author URI: https://paulmaloney.net
License: GPL2
License URI: https://www.gnu.org/licenses/gpl-2.0.html
Text Domain:  woo-holiday
Copyright 2021 Paul Maloney

This program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License, version 2, as
published by the Free Software Foundation.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

add_action( 'admin_menu', 'woo_admin_menu', 65);
function woo_admin_menu() {
 	add_submenu_page( 'woocommerce', 'Woo Holiday', 'Woo Holiday', 'manage_options', 'woo-holiday', 'my_options_page', ); 
}
add_action( 'admin_init', 'woo_admin_init' );

function woo_admin_init() {
  	register_setting( 'woo-settings-group', 'woo-holiday-settings' );	 
  	add_settings_section( 'section-1', __( 'Settings', 'textdomain' ), 'section_1_callback', 'woo-holiday' );
  	add_settings_field( 'field-1-1', __( 'Custom Message', 'textdomain' ), 'field_1_1_callback', 'woo-holiday', 'section-1' );
	add_settings_field( 'field-1-2', __( 'Holiday Mode', 'textdomain' ), 'field_1_2_callback', 'woo-holiday', 'section-1' );	
}

function my_options_page() {
?>

<style>.wc_wide {width: 350px!important;}</style>
  <div class="wrap">
      <h2><?php _e('Woo Holiday', 'textdomain'); ?></h2>
      <form action="options.php" method="POST">
        <?php settings_fields('woo-settings-group'); ?>
        <?php do_settings_sections('woo-holiday'); ?>
        <?php submit_button(); ?>
      </form>
  </div>
<?php }

function field_1_1_callback() {
	
	$settings = (array) get_option( 'woo-holiday-settings' );
	$field = "field_1_1";
	$value = esc_attr( $settings[$field] );
	
	echo "<textarea class='wc_wide' type='text' name='woo-holiday-settings[$field]' value='$value'>$value</textarea>";
}
function field_1_2_callback() {
	
	$settings = (array) get_option( 'woo-holiday-settings' );
	$field = "field_1_2";
	$value = esc_attr( $settings[$field] );
	
?>
  <input type="radio" id="on" name="woo-holiday-settings[<?php echo $field;?>]" value="on" <?php if ($value == "on"){ echo "checked"; };?>>
  <label>On</label><br>
  <input type="radio" id="off" name="woo-holiday-settings[<?php echo $field;?>]" value="off" <?php if ($value == "off"){ echo "checked"; };?>>
  <label>Off</label><br>
<?php

}


function my_settings_validate_and_sanitize( $input ) {

	$settings = (array) get_option( 'woo-holiday-settings' );
	
	if ( $some_condition == $input['field_1_1'] ) {
		$output['field_1_1'] = $input['field_1_1'];
	} else {
		add_settings_error( 'woo-holiday-settings', 'invalid-field_1_1', 'You have entered an invalid value.' );
	}
	
	if ( $some_condition == $input['field_1_2'] ) {
		$output['field_1_2'] = $input['field_1_2'];
	} else {
		add_settings_error( 'woo-holiday-settings', 'invalid-field_1_2', 'You have entered an invalid value.' );
	}
	
	return $output;

}

$settingss = (array) get_option( 'woo-holiday-settings' );	
$field2 ="field_1_2";
$value2 = esc_attr( $settingss[$field2] );	

if ($value2 == "on"){ 

add_action ('init', 'woo_woocommerce_holiday_mode');

function woo_woocommerce_holiday_mode() {
   remove_action( 'woocommerce_after_shop_loop_item', 'woocommerce_template_loop_add_to_cart', 10 );
   remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_add_to_cart', 30 );
   remove_action( 'woocommerce_proceed_to_checkout', 'woocommerce_button_proceed_to_checkout', 20 );
   remove_action( 'woocommerce_checkout_order_review', 'woocommerce_checkout_payment', 20 );
   add_action( 'woocommerce_before_main_content', 'woo_wc_shop_disabled', 5 );
   add_action( 'woocommerce_before_cart', 'woo_wc_shop_disabled', 5 );
   add_action( 'woocommerce_before_checkout_form', 'woo_wc_shop_disabled', 5 );

}

function woo_wc_shop_disabled() {

	$settings = (array) get_option( 'woo-holiday-settings' );
	$field = "field_1_1";
	$value = esc_attr( $settings[$field] );
	$msg = $value;
    wc_print_notice( $msg, 'error' );
    return $msg;
} 


}

