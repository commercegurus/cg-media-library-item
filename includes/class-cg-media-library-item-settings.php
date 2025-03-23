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

// Define plugin version constant.
if ( ! defined( 'CG_MEDIA_LIBRARY_ITEM_VERSION' ) ) {
	define( 'CG_MEDIA_LIBRARY_ITEM_VERSION', '1.0.1' );
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
		'background_color'         => '#f9f9f9',
		'type_badge_bg_color'      => '#e2e8f0',
		'footer_bg_color'          => '#2d3748',
		'doc_icon_color'           => '#1a202c',
		'title_color'              => '#1a202c',
		'type_badge_text_color'    => '#2d3748',
		'size_color'               => '#ffffff',
		'download_text_color'      => '#ffffff',
		'download_btn_color'       => '#ffffff',
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
		'type_badge_line_height' => '1.2',
		'size_font_family'       => 'inherit',
		'size_font_size'         => '16px',
		'size_font_weight'       => '500',
		'size_line_height'       => '1.4',
		'download_font_family'   => 'inherit',
		'download_font_size'     => '16px',
		'download_font_weight'   => '500',
		'download_line_height'   => '1.4',
	);

	/**
	 * Available font families
	 *
	 * @var array
	 */
	private $font_families = array(
		'inherit'                               => 'Theme Default',
		'-apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Oxygen-Sans, Ubuntu, Cantarell, "Helvetica Neue", sans-serif' => 'System UI',
		'Arial, Helvetica, sans-serif'          => 'Arial',
		'"Arial Black", Gadget, sans-serif'     => 'Arial Black',
		'"Comic Sans MS", cursive, sans-serif'  => 'Comic Sans MS',
		'"Courier New", Courier, monospace'     => 'Courier New',
		'Georgia, serif'                        => 'Georgia',
		'Impact, Charcoal, sans-serif'          => 'Impact',
		'"Lucida Sans Unicode", "Lucida Grande", sans-serif' => 'Lucida Sans',
		'"Palatino Linotype", "Book Antiqua", Palatino, serif' => 'Palatino',
		'Tahoma, Geneva, sans-serif'            => 'Tahoma',
		'"Times New Roman", Times, serif'       => 'Times New Roman',
		'"Trebuchet MS", Helvetica, sans-serif' => 'Trebuchet MS',
		'Verdana, Geneva, sans-serif'           => 'Verdana',
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
			__( 'CG Media Library Item Settings', 'cg-media-library-item' ),
			__( 'CG Media Library Item', 'cg-media-library-item' ),
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
			'background_color'         => __( 'Media Item Background Color', 'cg-media-library-item' ),
			'type_badge_bg_color'      => __( 'Type Badge Background Color', 'cg-media-library-item' ),
			'footer_bg_color'          => __( 'Footer Background Color', 'cg-media-library-item' ),
			'doc_icon_color'           => __( 'Document Icon Color', 'cg-media-library-item' ),
			'title_color'              => __( 'Title Color', 'cg-media-library-item' ),
			'type_badge_text_color'    => __( 'Type Badge Text Color', 'cg-media-library-item' ),
			'size_color'               => __( 'File Size Color', 'cg-media-library-item' ),
			'download_text_color'      => __( 'Download Text Color', 'cg-media-library-item' ),
			'download_btn_color'       => __( 'Download Button Color', 'cg-media-library-item' ),
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
	 * Render color settings section
	 */
	public function render_colors_section() {
		echo '<p>' . esc_html__( 'Customize the colors for the media library item component.', 'cg-media-library-item' ) . '</p>';
	}

	/**
	 * Render typography settings section
	 */
	public function render_typography_section() {
		echo '<p>' . esc_html__( 'Customize the typography for the media library item component.', 'cg-media-library-item' ) . '</p>';
	}

	/**
	 * Render color field
	 *
	 * @param array $args Field arguments.
	 */
	public function render_color_field( $args ) {
		$id     = $args['id'];
		$label  = $args['label'];
		$colors = get_option( 'cg_media_library_item_colors', $this->default_colors );
		$value  = isset( $colors[ $id ] ) ? $colors[ $id ] : $this->default_colors[ $id ];
		?>
		<input type="text" 
			id="cg_media_library_item_<?php echo esc_attr( $id ); ?>" 
			name="cg_media_library_item_colors[<?php echo esc_attr( $id ); ?>]" 
			value="<?php echo esc_attr( $value ); ?>" 
			class="cg-color-picker" 
			data-target="<?php echo esc_attr( $id ); ?>" 
			data-default-color="<?php echo esc_attr( $this->default_colors[ $id ] ); ?>" />
		<?php
	}

	/**
	 * Render reset buttons after the color and typography sections
	 */
	public function render_reset_buttons() {
		// Get the current tab from the URL.
		$current_tab = isset( $_GET['tab'] ) ? sanitize_text_field( $_GET['tab'] ) : 'colors';
		?>
		<tr>
			<th scope="row"></th>
			<td>
				<?php if ( $current_tab === 'typography' ) : ?>
					<div id="cg-typography-reset-wrapper" class="cg-reset-wrapper">
						<button type="button" id="cg-reset-typography" class="button button-secondary">
							<?php esc_html_e( 'Reset All Typography to Default', 'cg-media-library-item' ); ?>
						</button>
						<span class="spinner"></span>
						<span class="cg-reset-message"></span>
					</div>
				<?php else : ?>
					<div id="cg-colors-reset-wrapper" class="cg-reset-wrapper">
						<button type="button" id="cg-reset-colors" class="button button-secondary">
							<?php esc_html_e( 'Reset All Colors to Default', 'cg-media-library-item' ); ?>
						</button>
						<span class="spinner"></span>
						<span class="cg-reset-message"></span>
					</div>
				<?php endif; ?>
			</td>
		</tr>
		<?php
	}

	/**
	 * Render typography fields
	 *
	 * @param array $args Field arguments.
	 */
	public function render_typography_fields( $args ) {
		$prefix     = $args['prefix'];
		$typography = get_option( 'cg_media_library_item_typography', $this->default_typography );

		// Font family field.
		$font_family_id    = $prefix . '_font_family';
		$font_family_value = isset( $typography[ $font_family_id ] ) ? $typography[ $font_family_id ] : $this->default_typography[ $font_family_id ];

		// Font size field.
		$font_size_id    = $prefix . '_font_size';
		$font_size_value = isset( $typography[ $font_size_id ] ) ? $typography[ $font_size_id ] : $this->default_typography[ $font_size_id ];

		// Font weight field.
		$font_weight_id    = $prefix . '_font_weight';
		$font_weight_value = isset( $typography[ $font_weight_id ] ) ? $typography[ $font_weight_id ] : $this->default_typography[ $font_weight_id ];

		// Line height field.
		$line_height_id    = $prefix . '_line_height';
		$has_line_height   = isset( $this->default_typography[ $line_height_id ] );
		$line_height_value = isset( $typography[ $line_height_id ] ) ? $typography[ $line_height_id ] : ( $has_line_height ? $this->default_typography[ $line_height_id ] : '' );

		?>
		<div class="cg-typography-fields">
			<div class="cg-typography-field">
				<label for="cg_typography_<?php echo esc_attr( $font_family_id ); ?>"><?php esc_html_e( 'Font Family', 'cg-media-library-item' ); ?></label>
				<select 
					id="cg_typography_<?php echo esc_attr( $font_family_id ); ?>" 
					name="cg_media_library_item_typography[<?php echo esc_attr( $font_family_id ); ?>]" 
					class="cg-typography-select"
					data-default-value="<?php echo esc_attr( $this->default_typography[ $font_family_id ] ); ?>"
				>
					<?php foreach ( $this->font_families as $value => $label ) : ?>
						<option value="<?php echo esc_attr( $value ); ?>" <?php selected( $font_family_value, $value ); ?>><?php echo esc_html( $label ); ?></option>
					<?php endforeach; ?>
				</select>
			</div>
			
			<div class="cg-typography-field">
				<label for="cg_typography_<?php echo esc_attr( $font_size_id ); ?>"><?php esc_html_e( 'Font Size', 'cg-media-library-item' ); ?></label>
				<input 
					type="text" 
					id="cg_typography_<?php echo esc_attr( $font_size_id ); ?>" 
					name="cg_media_library_item_typography[<?php echo esc_attr( $font_size_id ); ?>]" 
					value="<?php echo esc_attr( $font_size_value ); ?>" 
					class="cg-typography-input cg-font-size"
					data-default-value="<?php echo esc_attr( $this->default_typography[ $font_size_id ] ); ?>"
				/>
			</div>
			
			<div class="cg-typography-field">
				<label for="cg_typography_<?php echo esc_attr( $font_weight_id ); ?>"><?php esc_html_e( 'Font Weight', 'cg-media-library-item' ); ?></label>
				<select 
					id="cg_typography_<?php echo esc_attr( $font_weight_id ); ?>" 
					name="cg_media_library_item_typography[<?php echo esc_attr( $font_weight_id ); ?>]" 
					class="cg-typography-select"
					data-default-value="<?php echo esc_attr( $this->default_typography[ $font_weight_id ] ); ?>"
				>
					<option value="300" <?php selected( $font_weight_value, '300' ); ?>><?php esc_html_e( 'Light (300)', 'cg-media-library-item' ); ?></option>
					<option value="400" <?php selected( $font_weight_value, '400' ); ?>><?php esc_html_e( 'Regular (400)', 'cg-media-library-item' ); ?></option>
					<option value="500" <?php selected( $font_weight_value, '500' ); ?>><?php esc_html_e( 'Medium (500)', 'cg-media-library-item' ); ?></option>
					<option value="600" <?php selected( $font_weight_value, '600' ); ?>><?php esc_html_e( 'Semi-Bold (600)', 'cg-media-library-item' ); ?></option>
					<option value="700" <?php selected( $font_weight_value, '700' ); ?>><?php esc_html_e( 'Bold (700)', 'cg-media-library-item' ); ?></option>
				</select>
			</div>
			
			<?php if ( $has_line_height ) : ?>
				<div class="cg-typography-field">
					<label for="cg_typography_<?php echo esc_attr( $line_height_id ); ?>"><?php esc_html_e( 'Line Height', 'cg-media-library-item' ); ?></label>
					<input 
						type="text" 
						id="cg_typography_<?php echo esc_attr( $line_height_id ); ?>" 
						name="cg_media_library_item_typography[<?php echo esc_attr( $line_height_id ); ?>]" 
						value="<?php echo esc_attr( $line_height_value ); ?>" 
						class="cg-typography-input cg-line-height"
						data-default-value="<?php echo esc_attr( $this->default_typography[ $line_height_id ] ); ?>"
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

		// Get current tab.
		$current_tab = isset( $_GET['tab'] ) ? sanitize_text_field( $_GET['tab'] ) : 'colors';

		// Get saved settings.
		$colors     = get_option( 'cg_media_library_item_colors', $this->default_colors );
		$typography = get_option( 'cg_media_library_item_typography', $this->default_typography );
		?>
		<div class="wrap">
			<h1><?php echo esc_html( get_admin_page_title() ); ?></h1>
			
			<h2 class="nav-tab-wrapper">
				<a href="?page=cg-media-library-item-settings&tab=colors" class="nav-tab <?php echo $current_tab === 'colors' ? 'nav-tab-active' : ''; ?>"><?php esc_html_e( 'Colors', 'cg-media-library-item' ); ?></a>
				<a href="?page=cg-media-library-item-settings&tab=typography" class="nav-tab <?php echo $current_tab === 'typography' ? 'nav-tab-active' : ''; ?>"><?php esc_html_e( 'Typography', 'cg-media-library-item' ); ?></a>
			</h2>
			
			<div class="cg-settings-layout">
				<div class="cg-settings-form">
					<form action="options.php" method="post">
						<?php settings_fields( 'cg_media_library_item_settings' ); ?>
						
						<?php if ( $current_tab === 'typography' ) : ?>
							<div id="typography-tab" class="tab-content">
								<?php $this->do_settings_sections( 'cg-media-library-item-settings', 'cg_media_library_item_typography_section' ); ?>
								<?php $this->render_reset_buttons(); ?>
							</div>
						<?php else : ?>
							<div id="colors-tab" class="tab-content">
								<?php $this->do_settings_sections( 'cg-media-library-item-settings', 'cg_media_library_item_colors_section' ); ?>
								<?php $this->render_reset_buttons(); ?>
							</div>
						<?php endif; ?>
						
						<?php submit_button(); ?>
					</form>
				</div>
				
				<div class="cg-settings-preview">
					<h3><?php esc_html_e( 'Live Preview', 'cg-media-library-item' ); ?></h3>
					<p><?php esc_html_e( 'This preview updates as you change settings.', 'cg-media-library-item' ); ?></p>
					
					<div id="cg-media-item-preview" class="media-item" role="region" aria-label="Document information">
						<div class="media-item__main">
							<div class="media-item__icon-wrapper" aria-hidden="true">
								<?php echo CG_Media_Library_Item::DOCUMENT_ICON; ?>
							</div>
							<div class="media-item__type-badge" id="file-type-preview">PDF</div>
							<h2 class="media-item__title" id="file-title-preview">Sample Document</h2>
						</div>
						<div class="media-item__footer">
							<div class="media-item__size" id="file-size-preview">2.5 MB</div>
							<a href="#" class="media-item__download-btn" onclick="return false;">
								<span class="media-item__download-icon-wrapper" aria-hidden="true">
									<?php echo CG_Media_Library_Item::DOWNLOAD_ICON; ?>
								</span>
								<span class="media-item__download-text">Download</span>
							</a>
						</div>
					</div>
				</div>
			</div>
		</div>
		
		<style type="text/css">
			/* Settings page layout */
			.cg-settings-layout {
				display: flex;
				flex-wrap: wrap;
				gap: 30px;
				margin-top: 20px;
			}
			
			.cg-settings-form {
				flex: 1;
				min-width: 500px;
				max-width: 60%;
			}
			
			.cg-settings-preview {
				flex: 0 0 300px;
				padding: 20px;
				background: #fff;
				border: 1px solid #ccc;
				border-radius: 5px;
				position: sticky;
				top: 50px;
				align-self: flex-start;
			}
			
			/* Preview styles */
			#cg-media-item-preview {
				max-width: 100%;
				margin-top: 20px;
				background-color: <?php echo esc_attr( $colors['background_color'] ); ?>;
			}
			
			#cg-media-item-preview .media-item__type-badge {
				background-color: <?php echo esc_attr( $colors['type_badge_bg_color'] ); ?>;
				color: <?php echo esc_attr( $colors['type_badge_text_color'] ); ?>;
				font-family: <?php echo esc_attr( $typography['type_badge_font_family'] ); ?>;
				font-size: <?php echo esc_attr( $typography['type_badge_font_size'] ); ?>;
				font-weight: <?php echo esc_attr( $typography['type_badge_font_weight'] ); ?>;
			}
			
			#cg-media-item-preview .media-item__footer {
				background-color: <?php echo esc_attr( $colors['footer_bg_color'] ); ?>;
			}
			
			#cg-media-item-preview .media-item__doc-icon {
				color: <?php echo esc_attr( $colors['doc_icon_color'] ); ?>;
			}
			
			#cg-media-item-preview .media-item__title {
				color: <?php echo esc_attr( $colors['title_color'] ); ?>;
				font-family: <?php echo esc_attr( $typography['title_font_family'] ); ?>;
				font-size: <?php echo esc_attr( $typography['title_font_size'] ); ?>;
				font-weight: <?php echo esc_attr( $typography['title_font_weight'] ); ?>;
				line-height: <?php echo esc_attr( $typography['title_line_height'] ); ?>;
			}
			
			#cg-media-item-preview .media-item__size {
				color: <?php echo esc_attr( $colors['size_color'] ); ?>;
				font-family: <?php echo esc_attr( $typography['size_font_family'] ); ?>;
				font-size: <?php echo esc_attr( $typography['size_font_size'] ); ?>;
				font-weight: <?php echo esc_attr( $typography['size_font_weight'] ); ?>;
			}
			
			#cg-media-item-preview .media-item__download-text {
				color: <?php echo esc_attr( $colors['download_text_color'] ); ?>;
				font-family: <?php echo esc_attr( $typography['download_font_family'] ); ?>;
				font-size: <?php echo esc_attr( $typography['download_font_size'] ); ?>;
				font-weight: <?php echo esc_attr( $typography['download_font_weight'] ); ?>;
			}
			
			#cg-media-item-preview .media-item__download-btn {
				color: <?php echo esc_attr( $colors['download_btn_color'] ); ?>;
			}
			
			#cg-media-item-preview .media-item__download-btn:hover {
				color: <?php echo esc_attr( $colors['download_btn_hover_color'] ); ?>;
			}
			
			/* Responsive adjustments */
			@media screen and (max-width: 1200px) {
				.cg-settings-layout {
					flex-direction: column;
				}
				
				.cg-settings-form {
					max-width: 100%;
				}
				
				.cg-settings-preview {
					position: static;
					max-width: 100%;
				}
			}
		</style>
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

		// Enqueue main plugin styles for preview
		wp_enqueue_style(
			'cg-media-library-item',
			plugin_dir_url( __DIR__ ) . 'css/cg-media-library-item.css',
			array(),
			CG_MEDIA_LIBRARY_ITEM_VERSION
		);

		wp_enqueue_style( 'wp-color-picker' );

		// Enqueue settings script
		wp_enqueue_script(
			'cg-media-library-item-settings',
			plugin_dir_url( __DIR__ ) . 'js/settings.js',
			array( 'wp-color-picker', 'jquery' ),
			CG_MEDIA_LIBRARY_ITEM_VERSION,
			true
		);

		// Pass color and typography mapping to JavaScript
		wp_localize_script(
			'cg-media-library-item-settings',
			'cgMediaLibrarySettings',
			array(
				'colorMap'             => array(
					'background_color'         => '.media-item',
					'type_badge_bg_color'      => '.media-item__type-badge',
					'footer_bg_color'          => '.media-item__footer',
					'doc_icon_color'           => '.media-item__doc-icon',
					'title_color'              => '.media-item__title',
					'type_badge_text_color'    => '.media-item__type-badge',
					'size_color'               => '.media-item__size',
					'download_text_color'      => '.media-item__download-text',
					'download_btn_color'       => '.media-item__download-btn',
					'download_btn_hover_color' => '.media-item__download-btn:hover',
				),
				'cssProperties'        => array(
					'background_color'         => 'background-color',
					'type_badge_bg_color'      => 'background-color',
					'footer_bg_color'          => 'background-color',
					'doc_icon_color'           => 'color',
					'title_color'              => 'color',
					'type_badge_text_color'    => 'color',
					'size_color'               => 'color',
					'download_text_color'      => 'color',
					'download_btn_color'       => 'color',
					'download_btn_hover_color' => 'color',
				),
				'typographyMap'        => array(
					'title_font_family'      => '.media-item__title',
					'title_font_size'        => '.media-item__title',
					'title_font_weight'      => '.media-item__title',
					'title_line_height'      => '.media-item__title',
					'type_badge_font_family' => '.media-item__type-badge',
					'type_badge_font_size'   => '.media-item__type-badge',
					'type_badge_font_weight' => '.media-item__type-badge',
					'size_font_family'       => '.media-item__size',
					'size_font_size'         => '.media-item__size',
					'size_font_weight'       => '.media-item__size',
					'download_font_family'   => '.media-item__download-text',
					'download_font_size'     => '.media-item__download-text',
					'download_font_weight'   => '.media-item__download-text',
				),
				'typographyProperties' => array(
					'title_font_family'      => 'font-family',
					'title_font_size'        => 'font-size',
					'title_font_weight'      => 'font-weight',
					'title_line_height'      => 'line-height',
					'type_badge_font_family' => 'font-family',
					'type_badge_font_size'   => 'font-size',
					'type_badge_font_weight' => 'font-weight',
					'size_font_family'       => 'font-family',
					'size_font_size'         => 'font-size',
					'size_font_weight'       => 'font-weight',
					'download_font_family'   => 'font-family',
					'download_font_size'     => 'font-size',
					'download_font_weight'   => 'font-weight',
				),
			)
		);

		// Add custom admin styles for the settings page
		wp_enqueue_style(
			'cg-media-library-item-admin',
			plugin_dir_url( __DIR__ ) . 'css/admin.css',
			array(),
			CG_MEDIA_LIBRARY_ITEM_VERSION
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
				<?php if ( isset( $typography['type_badge_line_height'] ) ) : ?>
				line-height: <?php echo esc_attr( $typography['type_badge_line_height'] ); ?>;
				<?php endif; ?>
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
