<?php
/**
 * Widget: Video player for Youtube, Vimeo, etc. embeded video
 *
 * @package WordPress
 * @subpackage ThemeREX Addons
 * @since v1.1
 */

// Load widget
if (!function_exists('trx_addons_widget_video_load')) {
	add_action( 'widgets_init', 'trx_addons_widget_video_load' );
	function trx_addons_widget_video_load() {
		register_widget( 'trx_addons_widget_video' );
	}
}

// Widget Class
class trx_addons_widget_video extends WP_Widget {

	function __construct() {
		$widget_ops = array( 'classname' => 'widget_video', 'description' => esc_html__('Show video from Youtube, Vimeo, etc.', 'trx_addons') );
		parent::__construct( 'trx_addons_widget_video', esc_html__('ThemeREX Addons - Video player', 'trx_addons'), $widget_ops );
	}

	// Show widget
	function widget( $args, $instance ) {
		extract( $args );
		$title = apply_filters('widget_title', isset($instance['title']) ? $instance['title'] : '' );
		$embed = isset($instance['embed']) ? $instance['embed'] : '';
		$link = isset($instance['link']) ? $instance['link'] : '';
		if (empty($embed) && empty($link)) return;
		$cover = isset($instance['cover']) ? $instance['cover'] : '';
		$popup = isset($instance['popup']) ? $instance['popup'] : 0;
		trx_addons_get_template_part(TRX_ADDONS_PLUGIN_WIDGETS . 'video/tpl.default.php',
										'trx_addons_args_widget_video',
										array_merge($args, compact('title', 'embed', 'link', 'cover', 'popup'))
									);
	}

	// Update the widget settings.
	function update( $new_instance, $old_instance ) {
		$instance = $old_instance;

		// Strip tags for title and comments count to remove HTML (important for text inputs)
		$instance['title'] = strip_tags( $new_instance['title'] );
		$instance['cover'] = strip_tags( $new_instance['cover'] );
		$instance['link']  = trim( $new_instance['link'] );
		$instance['embed'] = trim( $new_instance['embed'] );
		$instance['popup'] = intval( $new_instance['popup'] );

		return $instance;
	}

	// Displays the widget settings controls on the widget panel.
	function form( $instance ) {
		// Set up some default widget settings
		$instance = wp_parse_args( (array) $instance, array(
			'title' => '',
			'cover' => '',
			'link' => '',
			'embed' => '',
			'popup' => 0
			)
		);
		$title = $instance['title'];
		$cover = $instance['cover'];
		$link = $instance['link'];
		$embed = $instance['embed'];
		$popup = (int) $instance['popup'];
		?>

		<p>
			<label for="<?php echo esc_attr($this->get_field_id( 'title' )); ?>"><?php esc_html_e('Title:', 'trx_addons'); ?></label>
			<input id="<?php echo esc_attr($this->get_field_id( 'title' )); ?>" name="<?php echo esc_attr($this->get_field_name( 'title' )); ?>" value="<?php echo esc_attr($title); ?>" class="widgets_param_fullwidth" />
		</p>

		<p>
			<label for="<?php echo esc_attr($this->get_field_id( 'cover' )); ?>"><?php echo wp_kses_data( __('Cover image URL:<br />(leave empty if you not need the cover)', 'trx_addons') ); ?></label>
			<input id="<?php echo esc_attr($this->get_field_id( 'cover' )); ?>" name="<?php echo esc_attr($this->get_field_name( 'cover' )); ?>" value="<?php echo esc_attr($cover); ?>" class="widgets_param_fullwidth widgets_param_media_selector" />
            <?php
			trx_addons_show_layout(trx_addons_options_show_custom_field($this->get_field_id( 'cover_media' ), array('type'=>'mediamanager', 'linked_field_id'=>$this->get_field_id( 'cover' )), $cover));
			?>
		</p>

		<p>
			<label for="<?php echo esc_attr($this->get_field_id( 'link' )); ?>"><?php esc_html_e('Link to video:', 'trx_addons'); ?></label>
			<input id="<?php echo esc_attr($this->get_field_id( 'link' )); ?>" name="<?php echo esc_attr($this->get_field_name( 'link' )); ?>" value="<?php echo esc_attr($link); ?>" class="widgets_param_fullwidth" />
		</p>

		<p>
			<label for="<?php echo esc_attr($this->get_field_id( 'embed' )); ?>"><?php esc_html_e('or paste HTML code to embed video:', 'trx_addons'); ?></label>
			<textarea id="<?php echo esc_attr($this->get_field_id( 'embed' )); ?>" name="<?php echo esc_attr($this->get_field_name( 'embed' )); ?>" rows="5" class="widgets_param_fullwidth"><?php echo htmlspecialchars($embed); ?></textarea>
		</p>

		<p>
			<label for="<?php echo esc_attr($this->get_field_id( 'popup' )); ?>"><?php esc_html_e('Video in the popup:', 'trx_addons'); ?></label>
			<input id="<?php echo esc_attr($this->get_field_id( 'popup' )); ?>" name="<?php echo esc_attr($this->get_field_name( 'popup' )); ?>" value="<?php echo esc_attr($popup); ?>" />
		</p>
		
	<?php
	}
}

	
// Load required styles and scripts for the frontend
if ( !function_exists( 'trx_addons_widget_video_load_scripts_front' ) ) {
	add_action("wp_enqueue_scripts", 'trx_addons_widget_video_load_scripts_front');
	function trx_addons_widget_video_load_scripts_front() {
		if (trx_addons_is_on(trx_addons_get_option('debug_mode'))) {
			wp_enqueue_style( 'trx_addons-widget_video', trx_addons_get_file_url(TRX_ADDONS_PLUGIN_WIDGETS . 'video/video.css'), array(), null );
		}
	}
}

	
// Merge widget specific styles into single stylesheet
if ( !function_exists( 'trx_addons_widget_video_merge_styles' ) ) {
	add_action("trx_addons_filter_merge_styles", 'trx_addons_widget_video_merge_styles');
	function trx_addons_widget_video_merge_styles($list) {
		$list[] = TRX_ADDONS_PLUGIN_WIDGETS . 'video/video.css';
		return $list;
	}
}



// trx_widget_video
//-------------------------------------------------------------
/*
[trx_widget_video id="unique_id" title="Widget title" embed="HTML code" cover="image url"]
*/
if ( !function_exists( 'trx_addons_sc_widget_video' ) ) {
	function trx_addons_sc_widget_video($atts, $content=null){
		$atts = trx_addons_sc_prepare_atts('trx_widget_video', $atts, array(
			// Individual params
			'title' => '',
			'cover' => '',
			'link' => '',
			'embed' => '',
			'popup' => 0,
			// Common params
			"id" => "",
			"class" => "",
			"css" => ""
			)
		);
		if (!empty($atts['embed'])) $atts['embed'] = trim( vc_value_from_safe( $atts['embed'] ) );
		extract($atts);
		$type = 'trx_addons_widget_video';
		$output = '';
		global $wp_widget_factory, $TRX_ADDONS_STORAGE;
		if ( is_object( $wp_widget_factory ) && isset( $wp_widget_factory->widgets, $wp_widget_factory->widgets[ $type ] ) ) {
			$output = '<div' . ($id ? ' id="'.esc_attr($id).'"' : '')
							. ' class="widget_area sc_widget_video'
								. (trx_addons_exists_visual_composer() ? ' vc_widget_video wpb_content_element' : '')
								. (!empty($class) ? ' ' . esc_attr($class) : '')
								. '"'
							. ($css ? ' style="'.esc_attr($css).'"' : '')
						. '>';
			ob_start();
            the_widget( $type, $atts, trx_addons_prepare_widgets_args($id ? $id.'_widget' : 'widget_video', 'widget_video') );
//			the_widget( $type, $atts, trx_addons_prepare_widgets_args($TRX_ADDONS_STORAGE['widgets_args'], $id ? $id.'_widget' : 'widget_video', 'widget_video') );
			$output .= ob_get_contents();
			ob_end_clean();
			$output .= '</div>';
		}
		return apply_filters('trx_addons_sc_output', $output, 'trx_widget_video', $atts, $content);
	}
}


// Add [trx_widget_video] in the VC shortcodes list
if (!function_exists('trx_addons_sc_widget_video_add_in_vc')) {
	function trx_addons_sc_widget_video_add_in_vc() {
		
		if (!trx_addons_exists_visual_composer()) return;
		
		add_shortcode("trx_widget_video", "trx_addons_sc_widget_video");
		
		vc_lean_map( "trx_widget_video", 'trx_addons_sc_widget_video_add_in_vc_params' );
		class WPBakeryShortCode_Trx_Widget_Video extends WPBakeryShortCode {}

	}
	add_action('init', 'trx_addons_sc_widget_video_add_in_vc', 20);
}


// Return params
if (!function_exists('trx_addons_sc_widget_video_add_in_vc_params')) {

	function trx_addons_sc_widget_video_add_in_vc_params() {
		return apply_filters('trx_addons_sc_map', array(
				"base" => "trx_widget_video",
				"name" => esc_html__("Video player", 'trx_addons'),
				"description" => wp_kses_data( __("Insert widget with embedded video from popular video hosting: Vimeo, Youtube, etc.", 'trx_addons') ),
				"category" => esc_html__('ThemeREX', 'trx_addons'),
				"icon" => 'icon_trx_widget_video',
				"class" => "trx_widget_video",
				"content_element" => true,
				"is_container" => false,
				"show_settings_on_create" => true,
				"params" => array_merge(
					array(
						array(
							"param_name" => "title",
							"heading" => esc_html__("Widget title", 'trx_addons'),
							"description" => wp_kses_data( __("Title of the widget", 'trx_addons') ),
							"admin_label" => true,
							"type" => "textfield"
						),
						array(
							"param_name" => "cover",
							"heading" => esc_html__("Cover image", 'trx_addons'),
							"description" => wp_kses_data( __("Select or upload cover image or write URL from other site", 'trx_addons') ),
							'edit_field_class' => 'vc_col-sm-6',
							"type" => "attach_image"
						),
						array(
							"param_name" => "popup",
							"heading" => esc_html__("Open in the popup", 'trx_addons'),
							"description" => wp_kses_data( __("Open video in the popup", 'trx_addons') ),
							'edit_field_class' => 'vc_col-sm-6',
							'dependency' => array(
								'element' => 'cover',
								'not_empty' => true
							),
							"admin_label" => true,
							"std" => 0,
							"type" => "checkbox"
						),
						array(
							"param_name" => "link",
							"heading" => esc_html__("Link to video", 'trx_addons'),
							"description" => wp_kses_data( __("Enter link to the video (Note: read more about available formats at WordPress Codex page)", 'trx_addons') ),
							"admin_label" => true,
							"type" => "textfield"
						),
						array(
							"param_name" => "embed",
							"heading" => esc_html__("or paste Embed code", 'trx_addons'),
							"description" => wp_kses_data( __("or paste the HTML code to embed video", 'trx_addons') ),
							"type" => "textarea_safe"
						)
					),
					trx_addons_vc_add_id_param()
				)
			), 'trx_widget_video' );
	}
}

// Elementor Widget
//------------------------------------------------------
if (!function_exists('trx_addons_sc_widget_video_add_in_elementor')) {
    add_action( 'elementor/widgets/widgets_registered', 'trx_addons_sc_widget_video_add_in_elementor' );
    function trx_addons_sc_widget_video_add_in_elementor() {
        class TRX_Addons_Elementor_Widget_Video extends TRX_Addons_Elementor_Widget {

            /**
             * Widget base constructor.
             *
             * Initializing the widget base class.
             *
             * @since 1.6.41
             * @access public
             *
             * @param array      $data Widget data. Default is an empty array.
             * @param array|null $args Optional. Widget default arguments. Default is null.
             */
            public function __construct( $data = [], $args = null ) {
                parent::__construct( $data, $args );
                $this->add_plain_params([
                    'cover' => 'url'
                ]);
            }

            /**
             * Retrieve widget name.
             *
             * @since 1.6.41
             * @access public
             *
             * @return string Widget name.
             */
            public function get_name() {
                return 'trx_widget_video';
            }

            /**
             * Retrieve widget title.
             *
             * @since 1.6.41
             * @access public
             *
             * @return string Widget title.
             */
            public function get_title() {
                return __( 'Widget: Video', 'trx_addons' );
            }

            /**
             * Retrieve widget icon.
             *
             * @since 1.6.41
             * @access public
             *
             * @return string Widget icon.
             */
            public function get_icon() {
                return 'eicon-youtube';
            }

            /**
             * Retrieve the list of categories the widget belongs to.
             *
             * Used to determine where to display the widget in the editor.
             *
             * @since 1.6.41
             * @access public
             *
             * @return array Widget categories.
             */
            public function get_categories() {
                return ['trx_addons-widgets'];
            }

            /**
             * Register widget controls.
             *
             * Adds different input fields to allow the user to change and customize the widget settings.
             *
             * @since 1.6.41
             * @access protected
             */
            protected function _register_controls() {

                $this->start_controls_section(
                    'section_sc_video',
                    [
                        'label' => __( 'Widget: Video', 'trx_addons' ),
                    ]
                );

                $this->add_control(
                    'title',
                    [
                        'label' => __( 'Title', 'trx_addons' ),
                        'label_block' => false,
                        'type' => \Elementor\Controls_Manager::TEXT,
                        'placeholder' => __( "Widget title", 'trx_addons' ),
                        'default' => ''
                    ]
                );

                $this->add_control(
                    'cover',
                    [
                        'label' => __( 'Cover image', 'trx_addons' ),
                        'label_block' => true,
                        'type' => \Elementor\Controls_Manager::MEDIA,
                        'default' => [
                            'url' => '',
                        ],
                    ]
                );

                $this->add_control(
                    'popup',
                    [
                        'label' => __( 'Open in the popup', 'trx_addons' ),
                        'label_block' => false,
                        'type' => \Elementor\Controls_Manager::SWITCHER,
                        'label_off' => __( 'Off', 'trx_addons' ),
                        'label_on' => __( 'On', 'trx_addons' ),
                        'return_value' => '1',
                        'condition' => [
                            'cover[url]!' => ''
                        ]
                    ]
                );

                $this->add_control(
                    'link',
                    [
                        'label' => __( 'Link to video', 'trx_addons' ),
                        'label_block' => false,
                        'description' => __( 'Enter link to the video (Note: read more about available formats at WordPress Codex page)', 'trx_addons' ),
                        'type' => \Elementor\Controls_Manager::TEXT,
                        'default' => '',
                    ]
                );

                $this->add_control(
                    'embed',
                    [
                        'label' => __( 'Video embed code', 'trx_addons' ),
                        'label_block' => true,
                        'description' => __( 'or paste the HTML code to embed video in this block', 'trx_addons' ),
                        'type' => \Elementor\Controls_Manager::TEXTAREA,
                        'rows' => 10,
                        'separator' => 'none',
                        'default' => '',
                    ]
                );

                $this->end_controls_section();
            }

            /**
             * Render widget's template for the editor.
             *
             * Written as a Backbone JavaScript template and used to generate the live preview.
             *
             * @since 1.6.41
             * @access protected
             */
            protected function _content_template() {
                trx_addons_get_template_part(TRX_ADDONS_PLUGIN_WIDGETS . "video/tpe.video.php",
                    'trx_addons_args_widget_video',
                    array('element' => $this)
                );
            }
        }

        // Register widget
        \Elementor\Plugin::$instance->widgets_manager->register_widget_type( new TRX_Addons_Elementor_Widget_Video() );
    }
}


// Disable our widgets (shortcodes) to use in Elementor
// because we create special Elementor's widgets instead
if (!function_exists('trx_addons_widget_video_black_list')) {
	add_action( 'elementor/widgets/black_list', 'trx_addons_widget_video_black_list' );
	function trx_addons_widget_video_black_list($list) {
		$list[] = 'trx_addons_widget_video';
		return $list;
	}
}
?>