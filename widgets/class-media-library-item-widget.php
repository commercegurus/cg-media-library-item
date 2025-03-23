<?php
namespace CG_Media_Library_Item\Widgets;

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

// Check if Elementor is installed and activated
if (!did_action('elementor/loaded')) {
    return;
}

// Include Elementor base classes
require_once ELEMENTOR_PATH . 'includes/base/widget-base.php';

use Elementor\Widget_Base;
use Elementor\Controls_Manager;

/**
 * Elementor Media Library Item Widget.
 */
class CG_Media_Library_Item_Widget extends Widget_Base {

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
        return ['general'];
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
     * Register widget controls.
     */
    protected function register_controls() {
        $this->start_controls_section(
            'content_section',
            [
                'label' => esc_html__('Content', 'cg-media-library-item'),
                'tab' => Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'media_id',
            [
                'label' => esc_html__('Media File', 'cg-media-library-item'),
                'type' => Controls_Manager::MEDIA,
                'media_type' => 'application',
                'description' => esc_html__('Select a file from the media library.', 'cg-media-library-item'),
            ]
        );

        $this->add_control(
            'custom_title',
            [
                'label' => esc_html__('Custom Title', 'cg-media-library-item'),
                'type' => Controls_Manager::TEXT,
                'description' => esc_html__('Override the default file title (optional).', 'cg-media-library-item'),
            ]
        );

        $this->add_control(
            'download_text',
            [
                'label' => esc_html__('Download Button Text', 'cg-media-library-item'),
                'type' => Controls_Manager::TEXT,
                'default' => esc_html__('Download', 'cg-media-library-item'),
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

        $shortcode_atts = [
            'id' => $settings['media_id']['id'],
        ];

        if (!empty($settings['custom_title'])) {
            $shortcode_atts['title'] = $settings['custom_title'];
        }

        if (!empty($settings['download_text'])) {
            $shortcode_atts['download-title'] = $settings['download_text'];
        }

        $shortcode = '[cg_media_library_item';
        foreach ($shortcode_atts as $key => $value) {
            $shortcode .= ' ' . esc_attr($key) . '="' . esc_attr($value) . '"';
        }
        $shortcode .= ']';

        echo do_shortcode($shortcode);
    }
}
