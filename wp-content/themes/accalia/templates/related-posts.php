<?php
/**
 * The template 'Style 1' to displaying related posts
 *
 * @package WordPress
 * @subpackage ACCALIA
 * @since ACCALIA 1.0
 */

$accalia_link = get_permalink();
$accalia_post_format = get_post_format();
$accalia_post_format = empty($accalia_post_format) ? 'standard' : str_replace('post-format-', '', $accalia_post_format);
?><div id="post-<?php the_ID(); ?>" 
	<?php post_class( 'related_item related_item_style_1 post_format_'.esc_attr($accalia_post_format) ); ?>><?php
	accalia_show_post_featured(array(
		'thumb_size' => accalia_get_thumb_size( (int) accalia_get_theme_option('related_posts') == 1 ? 'huge' : 'big' ),
		'show_no_image' => false,
		'singular' => false,
		'post_info' => '<div class="post_header entry-header">'
							. '<div class="post_categories">' . accalia_get_post_categories('') . '</div>'
							. '<h6 class="post_title entry-title"><a href="' . esc_url($accalia_link) . '">' . wp_kses_post( get_the_title() ) . '</a></h6>'
							. (in_array(get_post_type(), array('post', 'attachment'))
									? '<span class="post_date"><a href="' . esc_url($accalia_link) . '">' . accalia_get_date() . '</a></span>'
									: '')
						. '</div>'
		)
	);
?></div>