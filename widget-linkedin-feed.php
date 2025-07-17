<?php
use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Repeater;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Border;

if (!defined('ABSPATH')) exit;

class LinkedIn_Feed_Widget extends Widget_Base {

    public function get_name() {
        return 'linkedin_feed_widget';
    }

    public function get_title() {
        return __('LinkedIn Feed', 'plugin-name');
    }

    public function get_icon() {
        return 'eicon-posts-grid';
    }

    public function get_categories() {
        return ['custom-widgets'];
    }

    protected function register_controls() {

        // === Layout Settings ===
        $this->start_controls_section('layout_settings', [
            'label' => __('Layout Settings', 'plugin-name'),
        ]);

        $this->add_responsive_control('posts_per_row', [
            'label' => __('Posts Per Row', 'plugin-name'),
            'type' => Controls_Manager::SELECT,
            'options' => [
                '1' => '1',
                '2' => '2',
                '3' => '3',
                '4' => '4',
            ],
            'default' => '3',
            'tablet_default' => '2',
            'mobile_default' => '1',
            'selectors' => [
                '{{WRAPPER}} .linkedin-feed-wrapper' => 'grid-template-columns: repeat({{VALUE}}, 1fr);',
            ],
        ]);

        $this->end_controls_section();

        // === Embed Repeater ===
        $this->start_controls_section('embed_settings', [
            'label' => __('Embed LinkedIn Posts', 'plugin-name'),
        ]);

        $repeater = new Repeater();
        $repeater->add_control('embed_code', [
            'label' => __('Embed Code or URL', 'plugin-name'),
            'type' => Controls_Manager::TEXTAREA,
            'rows' => 4,
            'placeholder' => 'Paste LinkedIn embed code or post URL...',
        ]);

        $this->add_control('embedded_posts', [
            'label' => __('Posts', 'plugin-name'),
            'type' => Controls_Manager::REPEATER,
            'fields' => $repeater->get_controls(),
            'title_field' => 'LinkedIn Post',
        ]);

        $this->end_controls_section();

        // === Style: Cards ===
        $this->start_controls_section('card_style_section', [
            'label' => __('Post Card Style', 'plugin-name'),
            'tab' => Controls_Manager::TAB_STYLE,
        ]);

        $this->add_responsive_control('card_padding', [
            'label' => __('Card Padding', 'plugin-name'),
            'type' => Controls_Manager::SLIDER,
            'range' => ['px' => ['min' => 0, 'max' => 50]],
            'default'=>[
                'size' => 10,
                'unit'=> 'px'
            ],
            'selectors' => [
                '{{WRAPPER}} .linkedin-feed-item' => 'padding: {{SIZE}}{{UNIT}};',
            ],
        ]);

        $this->add_responsive_control('card_radius', [
            'label' => __('Card Border Radius', 'plugin-name'),
            'type' => Controls_Manager::SLIDER,
            'range' => ['px' => ['min' => 0, 'max' => 50]],
            'selectors' => [
                '{{WRAPPER}} .linkedin-feed-item' => 'border-radius: {{SIZE}}{{UNIT}};',
            ],
        ]);

        $this->add_group_control(Group_Control_Border::get_type(), [
            'name' => 'card_border',
            'selector' => '{{WRAPPER}} .linkedin-feed-item',
        ]);

        $this->add_group_control(Group_Control_Box_Shadow::get_type(), [
            'name' => 'card_shadow',
            'selector' => '{{WRAPPER}} .linkedin-feed-item',
        ]);

        $this->end_controls_section();
    }

    protected function render() {
        $settings    = $this->get_settings_for_display();
        $embed_list  = $settings['embedded_posts'];

        echo '<div class="linkedin-feed-wrapper">';

        foreach ($embed_list as $embed) {
            $code = trim($embed['embed_code']);
            if (!empty($code)) {
                echo '<div class="linkedin-feed-item">';
                if (filter_var($code, FILTER_VALIDATE_URL)) {
                    echo '<iframe src="https://www.linkedin.com/embed/feed/update/urn:li:share:' . basename($code) . '" height="400" width="100%" frameborder="0" allowfullscreen></iframe>';
                } else {
                    echo do_shortcode($code);
                }
                echo '</div>';
            }
        }

        echo '</div>';
    }

    public function get_style_depends() {
        return ['linkedin-feed-style'];
    }
}

\Elementor\Plugin::instance()->widgets_manager->register_widget_type(new LinkedIn_Feed_Widget());
