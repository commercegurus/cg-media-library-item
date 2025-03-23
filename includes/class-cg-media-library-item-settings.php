<?php
/**
 * Settings page for CG Media Library Item
 *
 * @package CG_Media_Library_Item
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Settings class for CG Media Library Item
 */
class CG_Media_Library_Item_Settings {

	/**
	 * Default color settings
	 *
	 * @var array
	 */
	private $default_colors = array(
		'background_color'       => '#f9f9f9',
		'type_badge_bg_color'    => '#e2e8f0',
		'footer_bg_color'        => '#2d3748',
		'doc_icon_color'         => '#1a202c',
		'title_color'            => '#1a202c',
		'type_badge_text_color'  => '#2d3748',
		'size_color'             => '#ffffff',
		'download_text_color'    => '#ffffff',
		'download_btn_color'     => '#ffffff',
		'download_btn_hover_color' => '#ffffff',
	);

	/**
	 * Constructor
	 */
	public function __construct() {
		// Add settings page.
		add_action( 'admin_menu', array( $this, 'add_settings_page' ) );
		
		// Register settings.
		add_action( 'admin_init', array( $this, 'register_settings' ) );
		
		// Add color picker assets.
		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_color_picker' ) );
		
		// Add custom CSS to frontend.
		add_action( 'wp_head', array( $this, 'output_custom_css' ) );
	}

	/**
	 * Add settings page to admin menu
	 */
	public function add_settings_page() {
		add_submenu_page(
			'options-general.php',
			__( 'Media Library Item Settings', 'cg-media-library-item' ),
			__( 'Media Library Item', 'cg-media-library-item' ),
			'manage_options',
			'cg-media-library-item-settings',
			array( $this, 'render_settings_page' )
		);
	}

	/**
	 * Register plugin settings
	 */
	public function register_settings() {
		register_setting(
			'cg_media_library_item_settings',
			'cg_media_library_item_colors',
			array(
				'type'              => 'array',
				'sanitize_callback' => array( $this, 'sanitize_colors' ),
				'default'           => $this->default_colors,
			)
		);

		add_settings_section(
			'cg_media_library_item_colors_section',
			__( 'Color Settings', 'cg-media-library-item' ),
			array( $this, 'render_colors_section' ),
			'cg-media-library-item-settings'
		);

		// Add color settings fields.
		$this->add_color_fields();
	}

	/**
	 * Add color settings fields
	 */
	private function add_color_fields() {
		$color_fields = array(
			'background_color'       => __( 'Media Item Background Color', 'cg-media-library-item' ),
			'type_badge_bg_color'    => __( 'Type Badge Background Color', 'cg-media-library-item' ),
			'footer_bg_color'        => __( 'Footer Background Color', 'cg-media-library-item' ),
			'doc_icon_color'         => __( 'Document Icon Color', 'cg-media-library-item' ),
			'title_color'            => __( 'Title Color', 'cg-media-library-item' ),
			'type_badge_text_color'  => __( 'Type Badge Text Color', 'cg-media-library-item' ),
			'size_color'             => __( 'File Size Color', 'cg-media-library-item' ),
			'download_text_color'    => __( 'Download Text Color', 'cg-media-library-item' ),
			'download_btn_color'     => __( 'Download Button Color', 'cg-media-library-item' ),
			'download_btn_hover_color' => __( 'Download Button Hover Color', 'cg-media-library-item' ),
		);

		foreach ( $color_fields as $id => $label ) {
			add_settings_field(
				'cg_media_library_item_' . $id,
				$label,
				array( $this, 'render_color_field' ),
				'cg-media-library-item-settings',
				'cg_media_library_item_colors_section',
				array(
					'id'    => $id,
					'label' => $label,
				)
			);
		}
	}

	/**
	 * Render the colors section description
	 */
	public function render_colors_section() {
		echo '<p>' . esc_html__( 'Customize the colors of the media library item component.', 'cg-media-library-item' ) . '</p>';
	}

	/**
	 * Render a color picker field
	 *
	 * @param array $args Field arguments.
	 */
	public function render_color_field( $args ) {
		$id    = $args['id'];
		$label = $args['label'];
		$colors = get_option( 'cg_media_library_item_colors', $this->default_colors );
		$value = isset( $colors[ $id ] ) ? $colors[ $id ] : $this->default_colors[ $id ];

		?>
		<input type="text" 
			id="cg_media_library_item_<?php echo esc_attr( $id ); ?>" 
			name="cg_media_library_item_colors[<?php echo esc_attr( $id ); ?>]" 
			value="<?php echo esc_attr( $value ); ?>" 
			class="cg-color-picker" 
			data-default-color="<?php echo esc_attr( $this->default_colors[ $id ] ); ?>" 
		/>
		<?php
	}

	/**
	 * Sanitize color values
	 *
	 * @param array $input The input array of colors.
	 * @return array Sanitized colors
	 */
	public function sanitize_colors( $input ) {
		$sanitized_input = array();

		foreach ( $this->default_colors as $key => $default_value ) {
			if ( isset( $input[ $key ] ) ) {
				// Sanitize the color value.
				$sanitized_input[ $key ] = sanitize_hex_color( $input[ $key ] );
				
				// If sanitization removed the value, use the default.
				if ( empty( $sanitized_input[ $key ] ) ) {
					$sanitized_input[ $key ] = $default_value;
				}
			} else {
				$sanitized_input[ $key ] = $default_value;
			}
		}

		return $sanitized_input;
	}

	/**
	 * Render the settings page
	 */
	public function render_settings_page() {
		if ( ! current_user_can( 'manage_options' ) ) {
			return;
		}

		?>
		<div class="wrap">
			<h1><?php echo esc_html( get_admin_page_title() ); ?></h1>
			<form action="options.php" method="post">
				<?php
				settings_fields( 'cg_media_library_item_settings' );
				do_settings_sections( 'cg-media-library-item-settings' );
				submit_button();
				?>
			</form>
		</div>
		<?php
	}

	/**
	 * Enqueue color picker assets
	 *
	 * @param string $hook Current admin page.
	 */
	public function enqueue_color_picker( $hook ) {
		if ( 'settings_page_cg-media-library-item-settings' !== $hook ) {
			return;
		}

		wp_enqueue_style( 'wp-color-picker' );
		wp_enqueue_script(
			'cg-media-library-item-settings',
			plugin_dir_url( dirname( __FILE__ ) ) . 'js/settings.js',
			array( 'wp-color-picker' ),
			'1.0.0',
			true
		);
	}

	/**
	 * Output custom CSS based on settings
	 */
	public function output_custom_css() {
		global $post;
		
		// Check if we need to output the CSS.
		$should_output = false;
		
		// Check if current post has our shortcode.
		if ( is_singular() && $post && has_shortcode( $post->post_content, 'cg_media_library_item' ) ) {
			$should_output = true;
		}
		
		// Check if Elementor is active and our widget is used.
		if ( did_action( 'elementor/loaded' ) ) {
			// This is a simplified check - in a real plugin you might need more complex logic.
			$should_output = true;
		}
		
		// If we don't need to output CSS, return.
		if ( ! $should_output ) {
			return;
		}
		
		// Get color settings.
		$colors = get_option( 'cg_media_library_item_colors', $this->default_colors );
		
		// Output the custom CSS.
		?>
		<style type="text/css">
			.media-item {
				background-color: <?php echo esc_attr( $colors['background_color'] ); ?>;
			}
			.media-item__type-badge {
				background-color: <?php echo esc_attr( $colors['type_badge_bg_color'] ); ?>;
				color: <?php echo esc_attr( $colors['type_badge_text_color'] ); ?>;
			}
			.media-item__footer {
				background-color: <?php echo esc_attr( $colors['footer_bg_color'] ); ?>;
			}
			.media-item__doc-icon {
				color: <?php echo esc_attr( $colors['doc_icon_color'] ); ?>;
			}
			.media-item__title {
				color: <?php echo esc_attr( $colors['title_color'] ); ?>;
			}
			.media-item__size {
				color: <?php echo esc_attr( $colors['size_color'] ); ?>;
			}
			.media-item__download-text {
				color: <?php echo esc_attr( $colors['download_text_color'] ); ?>;
			}
			.media-item__download-btn {
				color: <?php echo esc_attr( $colors['download_btn_color'] ); ?>;
			}
			.media-item__download-btn:hover,
			.media-item__download-btn:focus {
				color: <?php echo esc_attr( $colors['download_btn_hover_color'] ); ?>;
			}
		</style>
		<?php
	}
} 