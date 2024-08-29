<?php
namespace Latest_Posts_For_Elementor\Controls;

use Elementor\Controls_Manager;

if (!defined('ABSPATH')) {
    exit; // 禁止直接访问
}

class Latest_Posts_Control {

    public static function register_controls($widget) {
        // 内容设置
        $widget->start_controls_section(
            'section_content',
            [
                'label' => esc_html__('Content', 'latest-posts-for-elementor'),
            ]
        );

        $widget->add_control(
            'posts_per_page',
            [
                'label' => esc_html__('Posts Per Page', 'latest-posts-for-elementor'),
                'type' => Controls_Manager::NUMBER,
                'default' => 5,
            ]
        );

        $widget->add_control(
            'show_thumbnail',
            [
                'label' => esc_html__('Show Thumbnail', 'latest-posts-for-elementor'),
                'type' => Controls_Manager::SWITCHER,
                'default' => 'yes',
            ]
        );

        $widget->add_control(
            'thumbnail_ratio',
            [
                'label' => esc_html__('Thumbnail Ratio', 'latest-posts-for-elementor'),
                'type' => Controls_Manager::SELECT,
                'default' => '16/9',
                'options' => [
                    '1/1' => '1:1',
                    '4/3' => '4:3',
                    '16/9' => '16:9',
                    '21/9' => '21:9',
                ],
                'condition' => [
                    'show_thumbnail' => 'yes',
                ],
            ]
        );

        $widget->add_control(
            'show_title',
            [
                'label' => esc_html__('Show Title', 'latest-posts-for-elementor'),
                'type' => Controls_Manager::SWITCHER,
                'default' => 'yes',
            ]
        );

        $widget->add_control(
            'show_excerpt',
            [
                'label' => esc_html__('Show Excerpt', 'latest-posts-for-elementor'),
                'type' => Controls_Manager::SWITCHER,
                'default' => 'yes',
            ]
        );

        $widget->add_control(
            'excerpt_length',
            [
                'label' => esc_html__('Excerpt Length', 'latest-posts-for-elementor'),
                'type' => Controls_Manager::NUMBER,
                'default' => 25,
                'condition' => [
                    'show_excerpt' => 'yes',
                ],
            ]
        );

        $widget->add_control(
            'show_date',
            [
                'label' => esc_html__('Show Date', 'latest-posts-for-elementor'),
                'type' => Controls_Manager::SWITCHER,
                'default' => 'yes',
            ]
        );

        $widget->add_control(
            'show_author',
            [
                'label' => esc_html__('Show Author', 'latest-posts-for-elementor'),
                'type' => Controls_Manager::SWITCHER,
                'default' => 'yes',
            ]
        );

        $widget->add_control(
            'show_category',
            [
                'label' => esc_html__('Show Category', 'latest-posts-for-elementor'),
                'type' => Controls_Manager::SWITCHER,
                'default' => 'yes',
            ]
        );

        $widget->end_controls_section();

        // 广告设置
        $widget->start_controls_section(
            'section_ad',
            [
                'label' => esc_html__('Advertisement', 'latest-posts-for-elementor'),
            ]
        );

        $widget->add_control(
            'ad_type',
            [
                'label' => esc_html__('Advertisement Type', 'latest-posts-for-elementor'),
                'type' => Controls_Manager::SELECT,
                'default' => 'none',
                'options' => [
                    'none' => esc_html__('None', 'latest-posts-for-elementor'),
                    'youtube' => esc_html__('YouTube Video', 'latest-posts-for-elementor'),
                    'image' => esc_html__('Image', 'latest-posts-for-elementor'),
                    'html' => esc_html__('Custom HTML', 'latest-posts-for-elementor'),
                ],
            ]
        );

        $widget->add_control(
            'ad_position',
            [
                'label' => esc_html__('Ad Position', 'latest-posts-for-elementor'),
                'type' => Controls_Manager::NUMBER,
                'default' => 3,
                'min' => 1,
                'max' => 20,
                'step' => 1,
                'condition' => [
                    'ad_type!' => 'none',
                ],
            ]
        );

        $widget->add_control(
            'ad_repeat',
            [
                'label' => esc_html__('Repeat Ad', 'latest-posts-for-elementor'),
                'type' => Controls_Manager::SWITCHER,
                'default' => 'yes',
                'condition' => [
                    'ad_type!' => 'none',
                ],
            ]
        );

        // YouTube广告设置
        $widget->add_control(
            'youtube_channel_id',
            [
                'label' => esc_html__('YouTube Channel ID', 'latest-posts-for-elementor'),
                'type' => Controls_Manager::TEXT,
                'condition' => [
                    'ad_type' => 'youtube',
                ],
            ]
        );

        $widget->add_control(
            'youtube_api_key',
            [
                'label' => esc_html__('YouTube API Key', 'latest-posts-for-elementor'),
                'type' => Controls_Manager::TEXT,
                'condition' => [
                    'ad_type' => 'youtube',
                ],
            ]
        );

        $widget->add_control(
            'youtube_play_mode',
            [
                'label' => esc_html__('Play Mode', 'latest-posts-for-elementor'),
                'type' => Controls_Manager::SELECT,
                'default' => 'inline',
                'options' => [
                    'inline' => esc_html__('Play Inline', 'latest-posts-for-elementor'),
                    'redirect' => esc_html__('Redirect to YouTube', 'latest-posts-for-elementor'),
                ],
                'condition' => [
                    'ad_type' => 'youtube',
                ],
            ]
        );

        // 图片广告设置
        $widget->add_control(
            'ad_image',
            [
                'label' => esc_html__('Advertisement Image', 'latest-posts-for-elementor'),
                'type' => Controls_Manager::MEDIA,
                'condition' => [
                    'ad_type' => 'image',
                ],
            ]
        );

        $widget->add_control(
            'ad_link',
            [
                'label' => esc_html__('Advertisement Link', 'latest-posts-for-elementor'),
                'type' => Controls_Manager::URL,
                'condition' => [
                    'ad_type' => 'image',
                ],
            ]
        );

        // HTML广告设置
        $widget->add_control(
            'ad_html',
            [
                'label' => esc_html__('Custom HTML', 'latest-posts-for-elementor'),
                'type' => Controls_Manager::CODE,
                'language' => 'html',
                'rows' => 10,
                'condition' => [
                    'ad_type' => 'html',
                ],
            ]
        );

        $widget->end_controls_section();

        // 样式设置
        $widget->start_controls_section(
            'section_style',
            [
                'label' => esc_html__('Style', 'latest-posts-for-elementor'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $widget->add_control(
            'title_color',
            [
                'label' => esc_html__('Title Color', 'latest-posts-for-elementor'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .news-title a' => 'color: {{VALUE}};',
                ],
            ]
        );

        $widget->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'title_typography',
                'label' => esc_html__('Title Typography', 'latest-posts-for-elementor'),
                'selector' => '{{WRAPPER}} .news-title',
            ]
        );

        $widget->add_control(
            'excerpt_color',
            [
                'label' => esc_html__('Excerpt Color', 'latest-posts-for-elementor'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .news-excerpt' => 'color: {{VALUE}};',
                ],
            ]
        );

        $widget->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'excerpt_typography',
                'label' => esc_html__('Excerpt Typography', 'latest-posts-for-elementor'),
                'selector' => '{{WRAPPER}} .news-excerpt',
            ]
        );

        $widget->add_control(
            'meta_color',
            [
                'label' => esc_html__('Meta Color', 'latest-posts-for-elementor'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .news-meta' => 'color: {{VALUE}};',
                ],
            ]
        );

        $widget->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'meta_typography',
                'label' => esc_html__('Meta Typography', 'latest-posts-for-elementor'),
                'selector' => '{{WRAPPER}} .news-meta',
            ]
        );

        $widget->end_controls_section();
    }
}