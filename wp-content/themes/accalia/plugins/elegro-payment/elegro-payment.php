<?php
/* elegro Crypto Payment support functions
------------------------------------------------------------------------------- */


// Check if this plugin installed and activated
if ( ! function_exists( 'accalia_exists_elegro_payment' ) ) {
	function accalia_exists_elegro_payment() {
		return class_exists( 'WC_Elegro_Payment' );
	}
}


/* Mail Chimp support functions
------------------------------------------------------------------------------- */

// Theme init priorities:
// 9 - register other filters (for installer, etc.)
if (!function_exists('accalia_elegro_payment_theme_setup9')) {
    add_action('after_setup_theme', 'accalia_elegro_payment_theme_setup9', 9);
    function accalia_elegro_payment_theme_setup9()
    {
        if (accalia_exists_elegro_payment()) {
            add_action('wp_enqueue_scripts', 'accalia_elegro_payment_frontend_scripts', 1100);
            add_filter('accalia_filter_merge_styles', 'accalia_elegro_payment_merge_styles');
        }
        if (is_admin()) {
            add_filter('accalia_filter_tgmpa_required_plugins', 'accalia_elegro_payment_tgmpa_required_plugins');
        }
    }
}



// Filter to add in the required plugins list
if (!function_exists('accalia_elegro_payment_tgmpa_required_plugins')) {
    function accalia_elegro_payment_tgmpa_required_plugins($list = array())
    {
        if (in_array('elegro-payment', accalia_storage_get('required_plugins')))
            $list[] = array(
                'name' => esc_html__('elegro Crypto Payment', 'accalia'),
                'slug' => 'elegro-payment',
                'required' => false
            );
        return $list;
    }
}


// Custom styles and scripts
//------------------------------------------------------------------------

// Enqueue custom styles
if (!function_exists('accalia_elegro_payment_frontend_scripts')) {
    function accalia_elegro_payment_frontend_scripts()
    {
        if (accalia_exists_elegro_payment()) {
            if (accalia_is_on(accalia_get_theme_option('debug_mode')) && accalia_get_file_dir('plugins/elegro-payment/elegro-payment.css') != '')
                wp_enqueue_style('accalia-elegro-payment', accalia_get_file_url('plugins/elegro-payment/elegro-payment.css'), array(), null);
        }
    }
}

// Merge custom styles
if (!function_exists('accalia_elegro_payment_merge_styles')) {
    function accalia_elegro_payment_merge_styles($list)
    {
        $list[] = 'plugins/elegro-payment/elegro-payment.css';
        return $list;
    }
}


