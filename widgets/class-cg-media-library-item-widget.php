<?php
/**
 * Elementor Media Library Item Widget.
 *
 * @package CG_Media_Library_Item
 * @version 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Media Library Item Widget for Elementor.
 */
class CG_Media_Library_Item_Widget extends \Elementor\Widget_Base {

	/**
	 * Get widget name.
	 *
	 * @return string Widget name.
	 */
	public function get_name() {
		return 'cg_media_library_item';
	}

	/**
	 * Get widget title.
	 *
	 * @return string Widget title.
	 */
	public function get_title() {
		return esc_html__( 'Media Library Item', 'cg-media-library-item' );
	}

	/**
	 * Get widget icon.
	 *
	 * @return string Widget icon.
	 */
	public function get_icon() {
		return 'eicon-document-file';
	}

	/**
	 * Get widget categories.
	 *
	 * @return array Widget categories.
	 */
	public function get_categories() {
		return array( 'cg-elementor-widgets' );
	}

	/**
	 * Get widget keywords.
	 *
	 * @return array Widget keywords.
	 */
	public function get_keywords() {
		return array( 'media', 'file', 'download', 'document' );
	}

	/**
	 * Get style dependencies.
	 *
	 * @return array Style dependencies.
	 */
	public function get_style_depends() {
		return array( 'cg-media-library-item' );
	}

	/**
	 * Register widget controls.
	 */
	protected function register_controls() {
		$this->start_controls_section(
			'content_section',
			array(
				'label' => esc_html__( 'Content', 'cg-media-library-item' ),
				'tab'   => \Elementor\Controls_Manager::TAB_CONTENT,
			)
		);

		$this->add_control(
			'media_id',
			array(
				'label'       => esc_html__( 'Media File', 'cg-media-library-item' ),
				'type'        => \Elementor\Controls_Manager::MEDIA,
				'media_type'  => 'application',
				'description' => esc_html__( 'Select a file from the media library.', 'cg-media-library-item' ),
			)
		);

		$this->add_control(
			'custom_title',
			array(
				'label'       => esc_html__( 'Custom Title', 'cg-media-library-item' ),
				'type'        => \Elementor\Controls_Manager::TEXT,
				'description' => esc_html__( 'Override the default file title (optional).', 'cg-media-library-item' ),
			)
		);

		$this->add_control(
			'download_text',
			array(
				'label'       => esc_html__( 'Download Button Text', 'cg-media-library-item' ),
				'type'        => \Elementor\Controls_Manager::TEXT,
				'default'     => esc_html__( 'Download', 'cg-media-library-item' ),
				'description' => esc_html__( 'Customize the download button text.', 'cg-media-library-item' ),
			)
		);

		$this->end_controls_section();

		// Get global settings
		$default_colors = array(
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

		$global_colors = get_option( 'cg_media_library_item_colors', $default_colors );

		$default_typography = array(
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

		$global_typography = get_option( 'cg_media_library_item_typography', $default_typography );

		// Typography Settings.
		$this->start_controls_section(
			'typography_section',
			array(
				'label' => esc_html__( 'Typography', 'cg-media-library-item' ),
				'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
			)
		);

		// Use global settings toggle
		$this->add_control(
			'use_global_typography',
			array(
				'label'        => esc_html__( 'Use Global Typography Settings', 'cg-media-library-item' ),
				'type'         => \Elementor\Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'Yes', 'cg-media-library-item' ),
				'label_off'    => esc_html__( 'No', 'cg-media-library-item' ),
				'return_value' => 'yes',
				'default'      => 'yes',
				'description'  => esc_html__( 'Use typography settings from the global plugin settings page.', 'cg-media-library-item' ),
			)
		);

		// Title Typography.
		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			array(
				'name'      => 'title_typography',
				'label'     => esc_html__( 'Title Typography', 'cg-media-library-item' ),
				'selector'  => '{{WRAPPER}} .media-item__title',
				'condition' => array(
					'use_global_typography!' => 'yes',
				),
			)
		);

		// Badge Typography.
		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			array(
				'name'      => 'badge_typography',
				'label'     => esc_html__( 'Type Badge Typography', 'cg-media-library-item' ),
				'selector'  => '{{WRAPPER}} .media-item__type-badge',
				'condition' => array(
					'use_global_typography!' => 'yes',
				),
			)
		);

		// File Size Typography.
		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			array(
				'name'      => 'file_size_typography',
				'label'     => esc_html__( 'File Size Typography', 'cg-media-library-item' ),
				'selector'  => '{{WRAPPER}} .media-item__size',
				'condition' => array(
					'use_global_typography!' => 'yes',
				),
			)
		);

		// Download Button Typography.
		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			array(
				'name'      => 'download_button_typography',
				'label'     => esc_html__( 'Download Button Typography', 'cg-media-library-item' ),
				'selector'  => '{{WRAPPER}} .media-item__download-text',
				'condition' => array(
					'use_global_typography!' => 'yes',
				),
			)
		);

		$this->end_controls_section();

		// Colors Settings.
		$this->start_controls_section(
			'colors_section',
			array(
				'label' => esc_html__( 'Colors', 'cg-media-library-item' ),
				'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
			)
		);

		// Use global settings toggle
		$this->add_control(
			'use_global_colors',
			array(
				'label'        => esc_html__( 'Use Global Color Settings', 'cg-media-library-item' ),
				'type'         => \Elementor\Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'Yes', 'cg-media-library-item' ),
				'label_off'    => esc_html__( 'No', 'cg-media-library-item' ),
				'return_value' => 'yes',
				'default'      => 'yes',
				'description'  => esc_html__( 'Use color settings from the global plugin settings page.', 'cg-media-library-item' ),
			)
		);

		$this->add_control(
			'background_color',
			array(
				'label'     => esc_html__( 'Background Color', 'cg-media-library-item' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'default'   => $global_colors['background_color'],
				'selectors' => array(
					'{{WRAPPER}} .media-item' => 'background-color: {{VALUE}};',
				),
				'condition' => array(
					'use_global_colors!' => 'yes',
				),
			)
		);

		$this->add_control(
			'title_color',
			array(
				'label'     => esc_html__( 'Title Color', 'cg-media-library-item' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'default'   => $global_colors['title_color'],
				'selectors' => array(
					'{{WRAPPER}} .media-item__title' => 'color: {{VALUE}};',
				),
				'condition' => array(
					'use_global_colors!' => 'yes',
				),
			)
		);

		$this->add_control(
			'badge_bg_color',
			array(
				'label'     => esc_html__( 'Type Badge Background', 'cg-media-library-item' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'default'   => $global_colors['type_badge_bg_color'],
				'selectors' => array(
					'{{WRAPPER}} .media-item__type-badge' => 'background-color: {{VALUE}};',
				),
				'condition' => array(
					'use_global_colors!' => 'yes',
				),
			)
		);

		$this->add_control(
			'badge_text_color',
			array(
				'label'     => esc_html__( 'Type Badge Text Color', 'cg-media-library-item' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'default'   => $global_colors['type_badge_text_color'],
				'selectors' => array(
					'{{WRAPPER}} .media-item__type-badge' => 'color: {{VALUE}};',
				),
				'condition' => array(
					'use_global_colors!' => 'yes',
				),
			)
		);

		$this->add_control(
			'footer_bg_color',
			array(
				'label'     => esc_html__( 'Footer Background', 'cg-media-library-item' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'default'   => $global_colors['footer_bg_color'],
				'selectors' => array(
					'{{WRAPPER}} .media-item__footer' => 'background-color: {{VALUE}};',
				),
				'condition' => array(
					'use_global_colors!' => 'yes',
				),
			)
		);

		$this->add_control(
			'file_size_color',
			array(
				'label'     => esc_html__( 'File Size Color', 'cg-media-library-item' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'default'   => $global_colors['size_color'],
				'selectors' => array(
					'{{WRAPPER}} .media-item__size' => 'color: {{VALUE}};',
				),
				'condition' => array(
					'use_global_colors!' => 'yes',
				),
			)
		);

		$this->add_control(
			'download_button_color',
			array(
				'label'     => esc_html__( 'Download Button Color', 'cg-media-library-item' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'default'   => $global_colors['download_btn_color'],
				'selectors' => array(
					'{{WRAPPER}} .media-item__download-btn' => 'color: {{VALUE}};',
				),
				'condition' => array(
					'use_global_colors!' => 'yes',
				),
			)
		);

		$this->add_control(
			'download_button_hover_color',
			array(
				'label'     => esc_html__( 'Download Button Hover Color', 'cg-media-library-item' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'default'   => $global_colors['download_btn_hover_color'],
				'selectors' => array(
					'{{WRAPPER}} .media-item__download-btn:hover, {{WRAPPER}} .media-item__download-btn:focus' => 'color: {{VALUE}}; outline-color: {{VALUE}}',
				),
				'condition' => array(
					'use_global_colors!' => 'yes',
				),
			)
		);

		$this->add_control(
			'icon_color',
			array(
				'label'     => esc_html__( 'Document Icon Color', 'cg-media-library-item' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'default'   => $global_colors['doc_icon_color'],
				'selectors' => array(
					'{{WRAPPER}} .media-item__doc-icon' => 'color: {{VALUE}};',
				),
				'condition' => array(
					'use_global_colors!' => 'yes',
				),
			)
		);

		$this->end_controls_section();
	}

	/**
	 * Render widget output on the frontend.
	 */
	protected function render() {
		$settings = $this->get_settings_for_display();

		if ( empty( $settings['media_id']['id'] ) ) {
			echo '<div class="media-item__error">' . esc_html__( 'Please select a media file.', 'cg-media-library-item' ) . '</div>';
			return;
		}

		$attributes = array(
			'id'             => $settings['media_id']['id'],
			'title'          => ! empty( $settings['custom_title'] ) ? $settings['custom_title'] : '',
			'download-title' => ! empty( $settings['download_text'] ) ? $settings['download_text'] : 'Download',
		);

		// Apply global settings if enabled
		$use_global_colors     = isset( $settings['use_global_colors'] ) && 'yes' === $settings['use_global_colors'];
		$use_global_typography = isset( $settings['use_global_typography'] ) && 'yes' === $settings['use_global_typography'];

		if ( $use_global_colors || $use_global_typography ) {
			// Get global settings
			$default_colors = array(
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

			$global_colors = get_option( 'cg_media_library_item_colors', $default_colors );

			$default_typography = array(
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

			$global_typography = get_option( 'cg_media_library_item_typography', $default_typography );

			// Output inline styles for global settings
			echo '<style>';

			if ( $use_global_colors ) {
				echo '.elementor-element-' . esc_attr( $this->get_id() ) . ' .media-item { background-color: ' . esc_attr( $global_colors['background_color'] ) . '; }';
				echo '.elementor-element-' . esc_attr( $this->get_id() ) . ' .media-item__type-badge { background-color: ' . esc_attr( $global_colors['type_badge_bg_color'] ) . '; color: ' . esc_attr( $global_colors['type_badge_text_color'] ) . '; }';
				echo '.elementor-element-' . esc_attr( $this->get_id() ) . ' .media-item__footer { background-color: ' . esc_attr( $global_colors['footer_bg_color'] ) . '; }';
				echo '.elementor-element-' . esc_attr( $this->get_id() ) . ' .media-item__doc-icon { color: ' . esc_attr( $global_colors['doc_icon_color'] ) . '; }';
				echo '.elementor-element-' . esc_attr( $this->get_id() ) . ' .media-item__title { color: ' . esc_attr( $global_colors['title_color'] ) . '; }';
				echo '.elementor-element-' . esc_attr( $this->get_id() ) . ' .media-item__size { color: ' . esc_attr( $global_colors['size_color'] ) . '; }';
				echo '.elementor-element-' . esc_attr( $this->get_id() ) . ' .media-item__download-text { color: ' . esc_attr( $global_colors['download_text_color'] ) . '; }';
				echo '.elementor-element-' . esc_attr( $this->get_id() ) . ' .media-item__download-btn { color: ' . esc_attr( $global_colors['download_btn_color'] ) . '; }';
				echo '.elementor-element-' . esc_attr( $this->get_id() ) . ' .media-item__download-btn:hover, .elementor-element-' . esc_attr( $this->get_id() ) . ' .media-item__download-btn:focus { color: ' . esc_attr( $global_colors['download_btn_hover_color'] ) . '; }';
			}

			if ( $use_global_typography ) {
				echo '.elementor-element-' . esc_attr( $this->get_id() ) . ' .media-item__title { font-family: ' . esc_attr( $global_typography['title_font_family'] ) . '; font-size: ' . esc_attr( $global_typography['title_font_size'] ) . '; font-weight: ' . esc_attr( $global_typography['title_font_weight'] ) . '; line-height: ' . esc_attr( $global_typography['title_line_height'] ) . '; }';
				echo '.elementor-element-' . esc_attr( $this->get_id() ) . ' .media-item__type-badge { font-family: ' . esc_attr( $global_typography['type_badge_font_family'] ) . '; font-size: ' . esc_attr( $global_typography['type_badge_font_size'] ) . '; font-weight: ' . esc_attr( $global_typography['type_badge_font_weight'] ) . '; line-height: ' . esc_attr( $global_typography['type_badge_line_height'] ) . '; }';
				echo '.elementor-element-' . esc_attr( $this->get_id() ) . ' .media-item__size { font-family: ' . esc_attr( $global_typography['size_font_family'] ) . '; font-size: ' . esc_attr( $global_typography['size_font_size'] ) . '; font-weight: ' . esc_attr( $global_typography['size_font_weight'] ) . '; }';
				echo '.elementor-element-' . esc_attr( $this->get_id() ) . ' .media-item__download-text { font-family: ' . esc_attr( $global_typography['download_font_family'] ) . '; font-size: ' . esc_attr( $global_typography['download_font_size'] ) . '; font-weight: ' . esc_attr( $global_typography['download_font_weight'] ) . '; }';
			}

			echo '</style>';
		}

		// Build and output the shortcode.
		echo do_shortcode(
			sprintf(
				'[cg_media_library_item id="%1$s" title="%2$s" download-title="%3$s"]',
				esc_attr( $attributes['id'] ),
				esc_attr( $attributes['title'] ),
				esc_attr( $attributes['download-title'] )
			)
		);
	}
}
