<?php
/* Mail Chimp support functions
------------------------------------------------------------------------------- */

// Theme init priorities:
// 9 - register other filters (for installer, etc.)
if (!function_exists('accalia_gdpr_theme_setup9')) {
	add_action( 'after_setup_theme', 'accalia_gdpr_theme_setup9', 9 );
	function accalia_gdpr_theme_setup9() {
		if (is_admin()) {
			add_filter( 'accalia_filter_tgmpa_required_plugins',		'accalia_gdpr_tgmpa_required_plugins' );
		}
	}
}

// Filter to add in the required plugins list
if ( !function_exists( 'accalia_gdpr_tgmpa_required_plugins' ) ) {
	function accalia_gdpr_tgmpa_required_plugins($list=array()) {
		if (in_array('wp-gdpr-compliance', accalia_storage_get('required_plugins'))) {
			$list[] = array(
				'name' 		=> esc_html__('WP GDPR Complience', 'accalia'),
				'slug' 		=> 'wp-gdpr-compliance',
				'required' 	=> false
			);
		}
		return $list;
	}
}

// Check if plugin installed and activated
if ( !function_exists( 'accalia_exists_gdpr' ) ) {
	function accalia_exists_gdpr() {
		return class_exists( 'WPGDPRC\WPGDPRC' );
	}
}


