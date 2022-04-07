<?php
/**
 * The template to display default site footer
 *
 * @package WordPress
 * @subpackage ACCALIA
 * @since ACCALIA 1.0.10
 */

$accalia_footer_scheme =  accalia_is_inherit(accalia_get_theme_option('footer_scheme')) ? accalia_get_theme_option('color_scheme') : accalia_get_theme_option('footer_scheme');
$accalia_footer_id = str_replace('footer-custom-', '', accalia_get_theme_option("footer_style"));
$accalia_footer_meta = get_post_meta($accalia_footer_id, 'trx_addons_options', true);
?>
<footer class="footer_wrap footer_custom footer_custom_<?php echo esc_attr($accalia_footer_id); 
						?> footer_custom_<?php echo esc_attr(sanitize_title(get_the_title($accalia_footer_id))); 
						if (!empty($accalia_footer_meta['margin']) != '') 
							echo ' '.esc_attr(accalia_add_inline_css_class('margin-top: '.esc_attr(accalia_prepare_css_value($accalia_footer_meta['margin'])).';'));
						?> scheme_<?php echo esc_attr($accalia_footer_scheme); 
						?>">
	<?php
    // Custom footer's layout
    do_action('accalia_action_show_layout', $accalia_footer_id);
	?>
</footer><!-- /.footer_wrap -->
