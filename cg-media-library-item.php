<?php
/**
 * Plugin Name: CG Media Library Item
 * Plugin URI: https://commercegurus.com/plugins/cg-media-library-item
 * Description: Displays media library items with title, description, file type, icon, size, and download link
 * Version: 1.0.2
 * Author: CommerceGurus
 * Author URI: https://commercegurus.com
 * Text Domain: cg-media-library-item
 * Domain Path: /languages
 * Requires at least: 6.0
 * Requires PHP: 7.4
 * License: GPL-2.0+
 * License URI: http://www.gnu.org/licenses/gpl-2.0.txt
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// Define plugin version constant.
if ( ! defined( 'CG_MEDIA_LIBRARY_ITEM_VERSION' ) ) {
	define( 'CG_MEDIA_LIBRARY_ITEM_VERSION', '1.0.2' );
}

// Include settings class.
require_once plugin_dir_path( __FILE__ ) . 'includes/class-cg-media-library-item-settings.php';

/**
 * Main plugin class
 */
class CG_Media_Library_Item {
	/**
	 * Document icon SVG (Solid style)
	 */
	const DOCUMENT_ICON = '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="media-item__doc-icon"><path d="M5.625 1.5c-1.036 0-1.875.84-1.875 1.875v17.25c0 1.035.84 1.875 1.875 1.875h12.75c1.035 0 1.875-.84 1.875-1.875V12.75A3.75 3.75 0 0016.5 9h-1.875a1.875 1.875 0 01-1.875-1.875V5.25A3.75 3.75 0 009 1.5H5.625z" /><path d="M12.971 1.816A5.23 5.23 0 0114.25 5.25v1.875c0 .207.168.375.375.375H16.5a5.23 5.23 0 013.434 1.279 9.768 9.768 0 00-6.963-6.963z" /></svg>';

	/**
	 * Download icon SVG (Hero Icons)
	 */
	const DOWNLOAD_ICON = '<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="media-item__download-icon"><path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75V16.5M16.5 12 12 16.5m0 0L7.5 12m4.5 4.5V3" /></svg>';

	/**
	 * Settings instance
	 *
	 * @var CG_Media_Library_Item_Settings
	 */
	private $settings;

	/**
	 * Constructor
	 */
	public function __construct() {
		// Initialize settings.
		$this->settings = new CG_Media_Library_Item_Settings();

		// Register shortcode.
		add_shortcode( 'cg_media_library_item', array( $this, 'render_shortcode' ) );

		// Register styles.
		add_action( 'wp_enqueue_scripts', array( $this, 'register_styles' ) );
	}

	/**
	 * Register plugin styles
	 */
	public function register_styles() {
		wp_register_style(
			'cg-media-library-item',
			plugin_dir_url( __FILE__ ) . 'css/cg-media-library-item.css',
			array(),
			CG_MEDIA_LIBRARY_ITEM_VERSION
		);
	}

	/**
	 * Render the shortcode
	 *
	 * @param array $atts Shortcode attributes
	 * @return string HTML output
	 */
	public function render_shortcode( $atts ) {
		$atts = shortcode_atts(
			array(
				'id'             => 0,
				'title'          => '',
				'download-title' => 'Download',
			),
			$atts,
			'cg_media_library_item'
		);

		// Enqueue styles.
		wp_enqueue_style( 'cg-media-library-item' );

		// Get attachment ID.
		$attachment_id = intval( $atts['id'] );

		// Check if attachment exists and is valid.
		if ( $attachment_id <= 0 || ! ( $attachment = get_post( $attachment_id ) ) || 'attachment' !== $attachment->post_type ) {
			return '<div class="media-item__error">Invalid media item ID.</div>';
		}

		// Get attachment data.
		$attachment_url         = wp_get_attachment_url( $attachment_id );
		$attachment_title       = ! empty( $atts['title'] ) ? $atts['title'] : get_the_title( $attachment_id );
		$download_title         = $atts['download-title'];
		$attachment_description = $attachment->post_content;

		// Get mime type and file extension.
		$mime_type = get_post_mime_type( $attachment_id );
		$file_ext  = strtoupper( pathinfo( $attachment->guid, PATHINFO_EXTENSION ) );

		// Get file size from attachment metadata.
		$metadata  = wp_get_attachment_metadata( $attachment_id );
		$file_size = 'Unknown';
		if ( ! empty( $metadata['filesize'] ) ) {
			$file_size = size_format( $metadata['filesize'] );
		} else {
			// Fallback to post meta for non-image attachments.
			$file_size_raw = get_post_meta( $attachment_id, '_wp_attachment_filesize', true );
			if ( $file_size_raw ) {
				$file_size = size_format( $file_size_raw );
			}
		}

		// Start output buffer.
		ob_start();
		?>
		<div class="media-item" role="region" aria-label="Document information">
			<div class="media-item__main">
				<div class="media-item__icon-wrapper" aria-hidden="true">
					<?php echo self::DOCUMENT_ICON; ?>
				</div>
				<div class="media-item__type-badge" id="file-type-<?php echo esc_attr( $attachment_id ); ?>"><?php echo esc_html( $file_ext ); ?></div>
				<h2 class="media-item__title" id="file-title-<?php echo esc_attr( $attachment_id ); ?>"><?php echo esc_html( $attachment_title ); ?></h2>
			</div>
			<div class="media-item__footer">
				<div class="media-item__size" id="file-size-<?php echo esc_attr( $attachment_id ); ?>"><?php echo esc_html( $file_size ); ?></div>
				<a href="<?php echo esc_url( $attachment_url ); ?>"
					class="media-item__download-btn"
					download
					aria-labelledby="file-title-<?php echo esc_attr( $attachment_id ); ?> file-type-<?php echo esc_attr( $attachment_id ); ?> file-size-<?php echo esc_attr( $attachment_id ); ?>"
					aria-describedby="download-desc-<?php echo esc_attr( $attachment_id ); ?>">
					<span class="media-item__download-icon-wrapper" aria-hidden="true">
						<?php echo self::DOWNLOAD_ICON; ?>
					</span>
					<span class="media-item__download-text"><?php echo esc_html( $download_title ); ?></span>
				</a>
				<span id="download-desc-<?php echo esc_attr( $attachment_id ); ?>" class="screen-reader-text">
					<?php echo esc_html( $download_title ); ?> <?php echo esc_html( $attachment_title ); ?> <?php echo esc_html( $file_ext ); ?> file (<?php echo esc_html( $file_size ); ?>)
				</span>
			</div>
		</div>
		<?php

		// Return output buffer.
		return ob_get_clean();
	}
}

// Initialize plugin.
new CG_Media_Library_Item();

/**
 * Register Media Library Item Elementor widget.
 *
 * @param \Elementor\Widgets_Manager $widgets_manager Elementor widgets manager.
 */
function cg_register_media_library_item_widget( $widgets_manager ) {
	// Check if Elementor is active.
	if ( ! did_action( 'elementor/loaded' ) ) {
		return;
	}

	// Include widget file.
	require_once plugin_dir_path( __FILE__ ) . 'widgets/class-cg-media-library-item-widget.php';

	// Register the widget.
	$widgets_manager->register( new \CG_Media_Library_Item_Widget() );
}
add_action( 'elementor/widgets/register', 'cg_register_media_library_item_widget' );

/**
 * Add a custom category for our widgets.
 *
 * @param \Elementor\Elements_Manager $elements_manager Elementor elements manager.
 */
function cg_add_elementor_widget_category( $elements_manager ) {
	// Check if Elementor is active.
	if ( ! did_action( 'elementor/loaded' ) ) {
		return;
	}

	$elements_manager->add_category(
		'cg-elementor-widgets',
		array(
			'title' => esc_html__( 'CG Widgets', 'cg-media-library-item' ),
			'icon'  => 'fa fa-plug',
		)
	);
}
add_action( 'elementor/elements/categories_registered', 'cg_add_elementor_widget_category' );
