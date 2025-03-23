<?php
/**
 * Elementor Media Library Item Widget.
 *
 * @package CG_Media_Library_Item
 * @version 1.0.0
 */

if (!defined('ABSPATH')) {
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
        return esc_html__('Media Library Item', 'cg-media-library-item');
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
        return ['cg-elementor-widgets'];
    }

    /**
     * Get widget keywords.
     *
     * @return array Widget keywords.
     */
    public function get_keywords() {
        return ['media', 'file', 'download', 'document'];
    }

    /**
     * Get style dependencies.
     *
     * @return array Style dependencies.
     */
    public function get_style_depends() {
        return ['cg-media-library-item'];
    }

    /**
     * Register widget controls.
     */
    protected function register_controls() {
        $this->start_controls_section(
            'content_section',
            [
                'label' => esc_html__('Content', 'cg-media-library-item'),
                'tab'   => \Elementor\Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'media_id',
            [
                'label'       => esc_html__('Media File', 'cg-media-library-item'),
                'type'        => \Elementor\Controls_Manager::MEDIA,
                'media_type'  => 'application',
                'description' => esc_html__('Select a file from the media library.', 'cg-media-library-item'),
            ]
        );

        $this->add_control(
            'custom_title',
            [
                'label'       => esc_html__('Custom Title', 'cg-media-library-item'),
                'type'        => \Elementor\Controls_Manager::TEXT,
                'description' => esc_html__('Override the default file title (optional).', 'cg-media-library-item'),
            ]
        );

        $this->add_control(
            'download_text',
            [
                'label'       => esc_html__('Download Button Text', 'cg-media-library-item'),
                'type'        => \Elementor\Controls_Manager::TEXT,
                'default'     => esc_html__('Download', 'cg-media-library-item'),
                'description' => esc_html__('Customize the download button text.', 'cg-media-library-item'),
            ]
        );

        $this->end_controls_section();
    }

    /**
     * Render widget output on the frontend.
     */
    protected function render() {
        $settings = $this->get_settings_for_display();

        if (empty($settings['media_id']['id'])) {
            echo '<div class="media-item__error">' . esc_html__('Please select a media file.', 'cg-media-library-item') . '</div>';
            return;
        }

        $attributes = [
            'id'             => $settings['media_id']['id'],
            'title'          => !empty($settings['custom_title']) ? $settings['custom_title'] : '',
            'download-title' => !empty($settings['download_text']) ? $settings['download_text'] : 'Download',
        ];

        // Build and output the shortcode
        echo do_shortcode(
            sprintf(
                '[cg_media_library_item id="%1$s" title="%2$s" download-title="%3$s"]',
                esc_attr($attributes['id']),
                esc_attr($attributes['title']),
                esc_attr($attributes['download-title'])
            )
        );
    }
}
