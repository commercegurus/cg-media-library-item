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
	 * Default typography settings
	 *
	 * @var array
	 */
	private $default_typography = array(
		'title_font_family'      => 'inherit',
		'title_font_size'        => '24px',
		'title_font_weight'      => '500',
		'title_line_height'      => '1.2',
		'type_badge_font_family' => 'inherit',
		'type_badge_font_size'   => '14px',
		'type_badge_font_weight' => '600',
		'size_font_family'       => 'inherit',
		'size_font_size'         => '16px',
		'size_font_weight'       => '500',
		'download_font_family'   => 'inherit',
		'download_font_size'     => '16px',
		'download_font_weight'   => '500',
	);

	/**
	 * Available font families
	 *
	 * @var array
	 */
	private $font_families = array(
		'inherit'                                     => 'Theme Default',
		'-apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Oxygen-Sans, Ubuntu, Cantarell, "Helvetica Neue", sans-serif' => 'System UI',
		'Arial, Helvetica, sans-serif'               => 'Arial',
		'"Arial Black", Gadget, sans-serif'          => 'Arial Black',
		'"Comic Sans MS", cursive, sans-serif'       => 'Comic Sans MS',
		'"Courier New", Courier, monospace'          => 'Courier New',
		'Georgia, serif'                             => 'Georgia',
		'Impact, Charcoal, sans-serif'               => 'Impact',
		'"Lucida Sans Unicode", "Lucida Grande", sans-serif' => 'Lucida Sans',
		'"Palatino Linotype", "Book Antiqua", Palatino, serif' => 'Palatino',
		'Tahoma, Geneva, sans-serif'                 => 'Tahoma',
		'"Times New Roman", Times, serif'            => 'Times New Roman',
		'"Trebuchet MS", Helvetica, sans-serif'      => 'Trebuchet MS',
		'Verdana, Geneva, sans-serif'                => 'Verdana',
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
		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_admin_scripts' ) );
		
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
		// Register color settings.
		register_setting(
			'cg_media_library_item_settings',
			'cg_media_library_item_colors',
			array(
				'type'              => 'array',
				'sanitize_callback' => array( $this, 'sanitize_colors' ),
				'default'           => $this->default_colors,
			)
		);

		// Register typography settings.
		register_setting(
			'cg_media_library_item_settings',
			'cg_media_library_item_typography',
			array(
				'type'              => 'array',
				'sanitize_callback' => array( $this, 'sanitize_typography' ),
				'default'           => $this->default_typography,
			)
		);

		// Colors section.
		add_settings_section(
			'cg_media_library_item_colors_section',
			__( 'Color Settings', 'cg-media-library-item' ),
			array( $this, 'render_colors_section' ),
			'cg-media-library-item-settings'
		);

		// Typography section.
		add_settings_section(
			'cg_media_library_item_typography_section',
			__( 'Typography Settings', 'cg-media-library-item' ),
			array( $this, 'render_typography_section' ),
			'cg-media-library-item-settings'
		);

		// Add color settings fields.
		$this->add_color_fields();

		// Add typography settings fields.
		$this->add_typography_fields();
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
	 * Add typography settings fields
	 */
	private function add_typography_fields() {
		// Title typography.
		add_settings_field(
			'cg_media_library_item_title_typography',
			__( 'Title Typography', 'cg-media-library-item' ),
			array( $this, 'render_typography_fields' ),
			'cg-media-library-item-settings',
			'cg_media_library_item_typography_section',
			array(
				'label_for' => 'title',
				'prefix'    => 'title',
			)
		);

		// Type badge typography.
		add_settings_field(
			'cg_media_library_item_type_badge_typography',
			__( 'Type Badge Typography', 'cg-media-library-item' ),
			array( $this, 'render_typography_fields' ),
			'cg-media-library-item-settings',
			'cg_media_library_item_typography_section',
			array(
				'label_for' => 'type_badge',
				'prefix'    => 'type_badge',
			)
		);

		// File size typography.
		add_settings_field(
			'cg_media_library_item_size_typography',
			__( 'File Size Typography', 'cg-media-library-item' ),
			array( $this, 'render_typography_fields' ),
			'cg-media-library-item-settings',
			'cg_media_library_item_typography_section',
			array(
				'label_for' => 'size',
				'prefix'    => 'size',
			)
		);

		// Download button typography.
		add_settings_field(
			'cg_media_library_item_download_typography',
			__( 'Download Button Typography', 'cg-media-library-item' ),
			array( $this, 'render_typography_fields' ),
			'cg-media-library-item-settings',
			'cg_media_library_item_typography_section',
			array(
				'label_for' => 'download',
				'prefix'    => 'download',
			)
		);
	}

	/**
	 * Render the colors section description
	 */
	public function render_colors_section() {
		echo '<p>' . esc_html__( 'Customize the colors of the media library item component.', 'cg-media-library-item' ) . '</p>';
	}

	/**
	 * Render the typography section description
	 */
	public function render_typography_section() {
		echo '<p>' . esc_html__( 'Customize the typography of the media library item component.', 'cg-media-library-item' ) . '</p>';
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
	 * Render typography fields group
	 *
	 * @param array $args Field arguments.
	 */
	public function render_typography_fields( $args ) {
		$prefix = $args['prefix'];
		$typography = get_option( 'cg_media_library_item_typography', $this->default_typography );
		
		// Font family field.
		$font_family = isset( $typography[ $prefix . '_font_family' ] ) 
			? $typography[ $prefix . '_font_family' ] 
			: $this->default_typography[ $prefix . '_font_family' ];
		
		// Font size field.
		$font_size = isset( $typography[ $prefix . '_font_size' ] ) 
			? $typography[ $prefix . '_font_size' ] 
			: $this->default_typography[ $prefix . '_font_size' ];
		
		// Font weight field.
		$font_weight = isset( $typography[ $prefix . '_font_weight' ] ) 
			? $typography[ $prefix . '_font_weight' ] 
			: $this->default_typography[ $prefix . '_font_weight' ];
		
		// Line height field (only for title).
		$line_height = isset( $typography[ $prefix . '_line_height' ] ) 
			? $typography[ $prefix . '_line_height' ] 
			: $this->default_typography[ $prefix . '_line_height' ];

		$font_weights = array(
			'100' => __( 'Thin (100)', 'cg-media-library-item' ),
			'200' => __( 'Extra Light (200)', 'cg-media-library-item' ),
			'300' => __( 'Light (300)', 'cg-media-library-item' ),
			'400' => __( 'Regular (400)', 'cg-media-library-item' ),
			'500' => __( 'Medium (500)', 'cg-media-library-item' ),
			'600' => __( 'Semi Bold (600)', 'cg-media-library-item' ),
			'700' => __( 'Bold (700)', 'cg-media-library-item' ),
			'800' => __( 'Extra Bold (800)', 'cg-media-library-item' ),
			'900' => __( 'Black (900)', 'cg-media-library-item' ),
		);

		?>
		<div class="cg-typography-fields">
			<div class="cg-typography-field">
				<label for="cg_typography_<?php echo esc_attr( $prefix ); ?>_font_family">
					<?php esc_html_e( 'Font Family', 'cg-media-library-item' ); ?>
				</label>
				<select 
					id="cg_typography_<?php echo esc_attr( $prefix ); ?>_font_family" 
					name="cg_media_library_item_typography[<?php echo esc_attr( $prefix ); ?>_font_family]"
					class="cg-select"
				>
					<?php foreach ( $this->font_families as $value => $label ) : ?>
						<option value="<?php echo esc_attr( $value ); ?>" <?php selected( $font_family, $value ); ?>>
							<?php echo esc_html( $label ); ?>
						</option>
					<?php endforeach; ?>
				</select>
			</div>

			<div class="cg-typography-field">
				<label for="cg_typography_<?php echo esc_attr( $prefix ); ?>_font_size">
					<?php esc_html_e( 'Font Size', 'cg-media-library-item' ); ?>
				</label>
				<input 
					type="text" 
					id="cg_typography_<?php echo esc_attr( $prefix ); ?>_font_size" 
					name="cg_media_library_item_typography[<?php echo esc_attr( $prefix ); ?>_font_size]" 
					value="<?php echo esc_attr( $font_size ); ?>" 
					class="cg-font-size" 
					placeholder="16px"
				/>
			</div>

			<div class="cg-typography-field">
				<label for="cg_typography_<?php echo esc_attr( $prefix ); ?>_font_weight">
					<?php esc_html_e( 'Font Weight', 'cg-media-library-item' ); ?>
				</label>
				<select 
					id="cg_typography_<?php echo esc_attr( $prefix ); ?>_font_weight" 
					name="cg_media_library_item_typography[<?php echo esc_attr( $prefix ); ?>_font_weight]"
					class="cg-select"
				>
					<?php foreach ( $font_weights as $value => $label ) : ?>
						<option value="<?php echo esc_attr( $value ); ?>" <?php selected( $font_weight, $value ); ?>>
							<?php echo esc_html( $label ); ?>
						</option>
					<?php endforeach; ?>
				</select>
			</div>

			<?php if ( 'title' === $prefix ) : ?>
				<div class="cg-typography-field">
					<label for="cg_typography_<?php echo esc_attr( $prefix ); ?>_line_height">
						<?php esc_html_e( 'Line Height', 'cg-media-library-item' ); ?>
					</label>
					<input 
						type="text" 
						id="cg_typography_<?php echo esc_attr( $prefix ); ?>_line_height" 
						name="cg_media_library_item_typography[<?php echo esc_attr( $prefix ); ?>_line_height]" 
						value="<?php echo esc_attr( $line_height ); ?>" 
						class="cg-line-height" 
						placeholder="1.2"
					/>
				</div>
			<?php endif; ?>
		</div>
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
	 * Sanitize typography values
	 *
	 * @param array $input The input array of typography settings.
	 * @return array Sanitized typography settings
	 */
	public function sanitize_typography( $input ) {
		$sanitized_input = array();

		foreach ( $this->default_typography as $key => $default_value ) {
			if ( isset( $input[ $key ] ) ) {
				if ( strpos( $key, 'font_family' ) !== false ) {
					// Sanitize font family.
					if ( array_key_exists( $input[ $key ], $this->font_families ) ) {
						$sanitized_input[ $key ] = $input[ $key ];
					} else {
						$sanitized_input[ $key ] = $default_value;
					}
				} elseif ( strpos( $key, 'font_weight' ) !== false ) {
					// Sanitize font weight.
					$sanitized_input[ $key ] = in_array( $input[ $key ], array( '100', '200', '300', '400', '500', '600', '700', '800', '900' ), true ) 
						? $input[ $key ] 
						: $default_value;
				} elseif ( strpos( $key, 'font_size' ) !== false ) {
					// Sanitize font size.
					$sanitized_input[ $key ] = preg_match( '/^(\d+)(px|rem|em|%)$/', $input[ $key ] ) 
						? $input[ $key ] 
						: $default_value;
				} elseif ( strpos( $key, 'line_height' ) !== false ) {
					// Sanitize line height.
					$sanitized_input[ $key ] = is_numeric( $input[ $key ] ) || preg_match( '/^(\d+|\d+\.\d+)(px|em|rem|)$/', $input[ $key ] ) 
						? $input[ $key ] 
						: $default_value;
				} else {
					$sanitized_input[ $key ] = sanitize_text_field( $input[ $key ] );
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
				<div class="nav-tab-wrapper">
					<a href="#tab-colors" class="nav-tab nav-tab-active"><?php esc_html_e( 'Colors', 'cg-media-library-item' ); ?></a>
					<a href="#tab-typography" class="nav-tab"><?php esc_html_e( 'Typography', 'cg-media-library-item' ); ?></a>
				</div>
				
				<?php settings_fields( 'cg_media_library_item_settings' ); ?>
				
				<div id="tab-colors" class="tab-content">
					<?php do_settings_sections( 'cg-media-library-item-settings-colors' ); ?>
					<?php
					// Output Colors section.
					$this->do_settings_sections( 'cg-media-library-item-settings', 'cg_media_library_item_colors_section' );
					?>
				</div>
				
				<div id="tab-typography" class="tab-content" style="display: none;">
					<?php
					// Output Typography section.
					$this->do_settings_sections( 'cg-media-library-item-settings', 'cg_media_library_item_typography_section' );
					?>
				</div>
				
				<?php submit_button(); ?>
			</form>
		</div>
		<?php
	}

	/**
	 * Custom implementation of do_settings_sections to output only specific sections
	 * 
	 * @param string $page The page name.
	 * @param string $section_id The section ID.
	 */
	public function do_settings_sections( $page, $section_id ) {
		global $wp_settings_sections, $wp_settings_fields;

		if ( ! isset( $wp_settings_sections[ $page ] ) ) {
			return;
		}

		$section = $wp_settings_sections[ $page ][ $section_id ];

		if ( $section['title'] ) {
			echo '<h2>' . esc_html( $section['title'] ) . '</h2>' . "\n";
		}

		if ( $section['callback'] ) {
			call_user_func( $section['callback'] );
		}

		if ( ! isset( $wp_settings_fields ) || ! isset( $wp_settings_fields[ $page ] ) || ! isset( $wp_settings_fields[ $page ][ $section['id'] ] ) ) {
			return;
		}

		echo '<table class="form-table" role="presentation">' . "\n";
		do_settings_fields( $page, $section['id'] );
		echo '</table>' . "\n";
	}

	/**
	 * Enqueue admin scripts
	 *
	 * @param string $hook Current admin page.
	 */
	public function enqueue_admin_scripts( $hook ) {
		if ( 'settings_page_cg-media-library-item-settings' !== $hook ) {
			return;
		}

		wp_enqueue_style( 'wp-color-picker' );
		wp_enqueue_script(
			'cg-media-library-item-settings',
			plugin_dir_url( dirname( __FILE__ ) ) . 'js/settings.js',
			array( 'wp-color-picker', 'jquery' ),
			'1.0.0',
			true
		);
		
		// Add custom admin styles for the settings page
		wp_enqueue_style(
			'cg-media-library-item-admin',
			plugin_dir_url( dirname( __FILE__ ) ) . 'css/admin.css',
			array(),
			'1.0.0'
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
		
		// Get typography settings.
		$typography = get_option( 'cg_media_library_item_typography', $this->default_typography );
		
		// Output the custom CSS.
		?>
		<style type="text/css">
			/* Color styles */
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

			/* Typography styles */
			.media-item__title {
				font-family: <?php echo esc_attr( $typography['title_font_family'] ); ?>;
				font-size: <?php echo esc_attr( $typography['title_font_size'] ); ?>;
				font-weight: <?php echo esc_attr( $typography['title_font_weight'] ); ?>;
				line-height: <?php echo esc_attr( $typography['title_line_height'] ); ?>;
			}
			.media-item__type-badge {
				font-family: <?php echo esc_attr( $typography['type_badge_font_family'] ); ?>;
				font-size: <?php echo esc_attr( $typography['type_badge_font_size'] ); ?>;
				font-weight: <?php echo esc_attr( $typography['type_badge_font_weight'] ); ?>;
			}
			.media-item__size {
				font-family: <?php echo esc_attr( $typography['size_font_family'] ); ?>;
				font-size: <?php echo esc_attr( $typography['size_font_size'] ); ?>;
				font-weight: <?php echo esc_attr( $typography['size_font_weight'] ); ?>;
			}
			.media-item__download-text {
				font-family: <?php echo esc_attr( $typography['download_font_family'] ); ?>;
				font-size: <?php echo esc_attr( $typography['download_font_size'] ); ?>;
				font-weight: <?php echo esc_attr( $typography['download_font_weight'] ); ?>;
			}
		</style>
		<?php
	}
}