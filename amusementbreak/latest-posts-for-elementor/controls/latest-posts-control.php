<?php

namespace Latest_Posts_For_Elementor\Controls;

use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;

if (!defined('ABSPATH')) {
    exit; // 禁止直接访问
}

class Latest_Posts_Control
{

    public static function register_controls($widget)
    {
        // 内容设置
        $widget->start_controls_section(
            'section_content',
            [
                'label' => esc_html__('Content', 'latest-posts-for-elementor'),
            ]
        );

        // 布局选择
        $widget->add_control(
            'layout',
            [
                'label' => esc_html__('Layout', 'latest-posts-for-elementor'),
                'type' => Controls_Manager::SELECT,
                'default' => 'default',
                'options' => [
                    'default' => esc_html__('Default', 'latest-posts-for-elementor'),
                    'grid' => esc_html__('Grid', 'latest-posts-for-elementor'),
                    'masonry' => esc_html__('Masonry', 'latest-posts-for-elementor'),
                    'list' => esc_html__('List', 'latest-posts-for-elementor'), // 无序列表

                ],
            ]
        );

        // 列数设置
        $widget->add_responsive_control(
            'columns',
            [
                'label' => esc_html__('Columns', 'latest-posts-for-elementor'),
                'type' => Controls_Manager::SELECT,
                'default' => '3',
                'options' => [
                    '1' => '1',
                    '2' => '2',
                    '3' => '3',
                    '4' => '4',
                    '5' => '5',
                    '6' => '6',
                ],
                'condition' => [
                    'layout!' => 'default',
                ],
            ]
        );

        $widget->add_control(
            'show_title_bar',
            [
                'label' => esc_html__('Show Title Bar', 'latest-posts-for-elementor'),
                'type' => Controls_Manager::SWITCHER,
                'default' => 'yes',
            ]
        );

        $widget->add_control(
            'title_bar_first_text',
            [
                'label' => esc_html__('First Title Text', 'latest-posts-for-elementor'),
                'type' => Controls_Manager::TEXT,
                'default' => 'Latest',
                'condition' => [
                    'show_title_bar' => 'yes',
                ],
            ]
        );

        $widget->add_control(
            'title_bar_second_text',
            [
                'label' => esc_html__('Second Title Text', 'latest-posts-for-elementor'),
                'type' => Controls_Manager::TEXT,
                'default' => 'News',
                'condition' => [
                    'show_title_bar' => 'yes',
                ],
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

        $widget->add_control(
            'show_divider',
            [
                'label' => esc_html__('Show Divider', 'latest-posts-for-elementor'),
                'type' => Controls_Manager::SWITCHER,
                'default' => 'yes',
            ]
        );

        $widget->end_controls_section();

        // 广告设置
        $widget->start_controls_section(
            'section_ads',
            [
                'label' => esc_html__('Advertisement', 'latest-posts-for-elementor'),
            ]
        );

        $widget->add_control(
            'ad_type',
            [
                'label' => esc_html__('Ad Type', 'latest-posts-for-elementor'),
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
                'default' => 'no',
                'condition' => [
                    'ad_type!' => 'none',
                ],
            ]
        );

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
            'ad_image',
            [
                'label' => esc_html__('Ad Image', 'latest-posts-for-elementor'),
                'type' => Controls_Manager::MEDIA,
                'condition' => [
                    'ad_type' => 'image',
                ],
            ]
        );

        $widget->add_control(
            'ad_link',
            [
                'label' => esc_html__('Ad Link', 'latest-posts-for-elementor'),
                'type' => Controls_Manager::URL,
                'condition' => [
                    'ad_type' => 'image',
                ],
            ]
        );

        $widget->add_control(
            'ad_html',
            [
                'label' => esc_html__('Ad HTML', 'latest-posts-for-elementor'),
                'type' => Controls_Manager::TEXTAREA,
                'condition' => [
                    'ad_type' => 'html',
                ],
            ]
        );

        $widget->add_control(
            'ad_show_title',
            [
                'label' => esc_html__('Show Ad Title', 'latest-posts-for-elementor'),
                'type' => Controls_Manager::SWITCHER,
                'default' => 'yes',
                'condition' => [
                    'ad_type!' => 'none',
                ],
            ]
        );

        $widget->add_control(
            'ad_title',
            [
                'label' => esc_html__('Ad Title', 'latest-posts-for-elementor'),
                'type' => Controls_Manager::TEXT,
                'condition' => [
                    'ad_type' => 'image',
                    'ad_show_title' => 'yes',
                ],
            ]
        );

        $widget->add_control(
            'ad_show_excerpt',
            [
                'label' => esc_html__('Show Ad Excerpt', 'latest-posts-for-elementor'),
                'type' => Controls_Manager::SWITCHER,
                'default' => 'yes',
                'condition' => [
                    'ad_type!' => 'none',
                ],
            ]
        );

        $widget->add_control(
            'ad_description',
            [
                'label' => esc_html__('Ad Description', 'latest-posts-for-elementor'),
                'type' => Controls_Manager::TEXTAREA,
                'condition' => [
                    'ad_type' => 'image',
                    'ad_show_excerpt' => 'yes',
                ],
            ]
        );

        $widget->add_control(
            'ad_excerpt_length',
            [
                'label' => esc_html__('Ad Excerpt Length', 'latest-posts-for-elementor'),
                'type' => Controls_Manager::NUMBER,
                'default' => 25,
                'condition' => [
                    'ad_type!' => 'none',
                    'ad_show_excerpt' => 'yes',
                ],
            ]
        );

        $widget->add_control(
            'ad_show_date',
            [
                'label' => esc_html__('Show Ad Date', 'latest-posts-for-elementor'),
                'type' => Controls_Manager::SWITCHER,
                'default' => 'yes',
                'condition' => [
                    'ad_type!' => 'none',
                ],
            ]
        );

        $widget->add_control(
            'ad_date',
            [
                'label' => esc_html__('Ad Date', 'latest-posts-for-elementor'),
                'type' => Controls_Manager::DATE_TIME,
                'condition' => [
                    'ad_type' => 'image',
                    'ad_show_date' => 'yes',
                ],
            ]
        );

        $widget->add_control(
            'ad_show_author',
            [
                'label' => esc_html__('Show Ad Author', 'latest-posts-for-elementor'),
                'type' => Controls_Manager::SWITCHER,
                'default' => 'yes',
                'condition' => [
                    'ad_type!' => 'none',
                ],
            ]
        );

        $widget->add_control(
            'ad_author',
            [
                'label' => esc_html__('Ad Author', 'latest-posts-for-elementor'),
                'type' => Controls_Manager::TEXT,
                'condition' => [
                    'ad_type' => 'image',
                    'ad_show_author' => 'yes',
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

        // 预设样式选择
        $widget->add_control(
            'preset_style',
            [
                'label' => esc_html__('Preset Style', 'latest-posts-for-elementor'),
                'type' => Controls_Manager::SELECT,
                'default' => 'default',
                'options' => [
                    'default' => esc_html__('Default', 'latest-posts-for-elementor'),
                    'style1' => esc_html__('Style 1', 'latest-posts-for-elementor'),
                    'style2' => esc_html__('Style 2', 'latest-posts-for-elementor'),
                    'style3' => esc_html__('Style 3', 'latest-posts-for-elementor'),
                    'patriotic' => esc_html__('Patriotic', 'latest-posts-for-elementor'),
                ],
            ]
        );

        $widget->add_control(
            'title_bar_first_color',
            [
                'label' => esc_html__('First Title Color', 'latest-posts-for-elementor'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .latest-news-title .first-title' => 'color: {{VALUE}};',
                ],
                'condition' => [
                    'show_title_bar' => 'yes',
                ],
            ]
        );

        $widget->add_control(
            'title_bar_second_color',
            [
                'label' => esc_html__('Second Title Color', 'latest-posts-for-elementor'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .latest-news-title .second-title' => 'color: {{VALUE}};',
                ],
                'condition' => [
                    'show_title_bar' => 'yes',
                ],
            ]
        );

        $widget->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'title_bar_typography',
                'label' => esc_html__('Title Typography', 'latest-posts-for-elementor'),
                'selector' => '{{WRAPPER}} .latest-news-title',
                'condition' => [
                    'show_title_bar' => 'yes',
                ],
            ]
        );

        $widget->add_control(
            'post_title_color',
            [
                'label' => esc_html__('Post Title Color', 'latest-posts-for-elementor'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .news-item:not(.ad-item) .news-title' => 'color: {{VALUE}};',
                ],
            ]
        );

        $widget->add_control(
            'post_title_hover_color',
            [
                'label' => esc_html__('Post Title Hover Color', 'latest-posts-for-elementor'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .news-item-link:hover .news-item:not(.ad-item) .news-title' => 'color: {{VALUE}};',
                ],
            ]
        );

        $widget->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'post_title_typography',
                'label' => esc_html__('Post Title Typography', 'latest-posts-for-elementor'),
                'selector' => '{{WRAPPER}} .news-item:not(.ad-item) .news-title',
            ]
        );

        // 广告帖子标题样式
        $widget->add_control(
            'ad_title_color',
            [
                'label' => esc_html__('Ad Title Color', 'latest-posts-for-elementor'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .news-item.ad-item .news-title' => 'color: {{VALUE}};',
                ],
            ]
        );

        $widget->add_control(
            'ad_title_hover_color',
            [
                'label' => esc_html__('Ad Title Hover Color', 'latest-posts-for-elementor'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .news-item-link:hover .news-item.ad-item .news-title' => 'color: {{VALUE}};',
                ],
            ]
        );

        $widget->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'ad_title_typography',
                'label' => esc_html__('Ad Title Typography', 'latest-posts-for-elementor'),
                'selector' => '{{WRAPPER}} .news-item.ad-item .news-title',
            ]
        );

        // 普通帖子摘要样式
        $widget->add_control(
            'excerpt_color',
            [
                'label' => esc_html__('Excerpt Color', 'latest-posts-for-elementor'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .news-item:not(.ad-item) .news-excerpt' => 'color: {{VALUE}};',
                ],
            ]
        );

        $widget->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'excerpt_typography',
                'label' => esc_html__('Excerpt Typography', 'latest-posts-for-elementor'),
                'selector' => '{{WRAPPER}} .news-item:not(.ad-item) .news-excerpt',
            ]
        );

        // 广告帖子摘要样式
        $widget->add_control(
            'ad_excerpt_color',
            [
                'label' => esc_html__('Ad Excerpt Color', 'latest-posts-for-elementor'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .news-item.ad-item .news-excerpt' => 'color: {{VALUE}};',
                ],
            ]
        );

        $widget->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'ad_excerpt_typography',
                'label' => esc_html__('Ad Excerpt Typography', 'latest-posts-for-elementor'),
                'selector' => '{{WRAPPER}} .news-item.ad-item .news-excerpt',
            ]
        );

        // 普通帖子元数据样式
        $widget->add_control(
            'meta_color',
            [
                'label' => esc_html__('Meta Color', 'latest-posts-for-elementor'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .news-item:not(.ad-item) .news-meta' => 'color: {{VALUE}};',
                ],
            ]
        );

        $widget->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'meta_typography',
                'label' => esc_html__('Meta Typography', 'latest-posts-for-elementor'),
                'selector' => '{{WRAPPER}} .news-item:not(.ad-item) .news-meta',
            ]
        );

        // 广告帖子元数据样式
        $widget->add_control(
            'ad_meta_color',
            [
                'label' => esc_html__('Ad Meta Color', 'latest-posts-for-elementor'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .news-item.ad-item .news-meta' => 'color: {{VALUE}};',
                ],
            ]
        );

        $widget->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'ad_meta_typography',
                'label' => esc_html__('Ad Meta Typography', 'latest-posts-for-elementor'),
                'selector' => '{{WRAPPER}} .news-item.ad-item .news-meta',
            ]
        );

        $widget->add_control(
            'category_color',
            [
                'label' => esc_html__('Category Color', 'latest-posts-for-elementor'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .news-category' => 'color: {{VALUE}};',
                ],
            ]
        );

        $widget->add_control(
            'ad_category_color',
            [
                'label' => esc_html__('Ad Category Color', 'latest-posts-for-elementor'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .news-category.ad-category' => 'color: {{VALUE}};',
                ],
            ]
        );

        $widget->add_control(
            'divider_color',
            [
                'label' => esc_html__('Divider Color', 'latest-posts-for-elementor'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .news-item.with-divider' => 'border-bottom-color: {{VALUE}};',
                ],
                'condition' => [
                    'show_divider' => 'yes',
                ],
            ]
        );

        $widget->add_control(
            'post_background_color',
            [
                'label' => esc_html__('Post Background Color', 'latest-posts-for-elementor'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .news-item:not(.ad-item)' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $widget->add_control(
            'ad_background_color',
            [
                'label' => esc_html__('Ad Background Color', 'latest-posts-for-elementor'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .news-item.ad-item' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        // 添加图片样式控件
        $widget->add_control(
            'image_border_radius',
            [
                'label' => esc_html__('Image Border Radius', 'latest-posts-for-elementor'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%'],
                'selectors' => [
                    '{{WRAPPER}} .news-thumbnail img' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $widget->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'image_box_shadow',
                'selector' => '{{WRAPPER}} .news-thumbnail img',
            ]
        );

        $widget->end_controls_section();

        // 分页设置
        $widget->start_controls_section(
            'section_pagination',
            [
                'label' => esc_html__('Pagination', 'latest-posts-for-elementor'),
            ]
        );

        $widget->add_control(
            'pagination_type',
            [
                'label' => esc_html__('Pagination Type', 'latest-posts-for-elementor'),
                'type' => Controls_Manager::SELECT,
                'default' => 'none',
                'options' => [
                    'none' => esc_html__('None', 'latest-posts-for-elementor'),
                    'numbers' => esc_html__('Numbers', 'latest-posts-for-elementor'),
                    'load_more' => esc_html__('Load More', 'latest-posts-for-elementor'),
                ],
            ]
        );

        $widget->add_control(
            'pagination_position',
            [
                'label' => esc_html__('Pagination Position', 'latest-posts-for-elementor'),
                'type' => Controls_Manager::SELECT,
                'default' => 'bottom',
                'options' => [
                    'top' => esc_html__('Top', 'latest-posts-for-elementor'),
                    'bottom' => esc_html__('Bottom', 'latest-posts-for-elementor'),
                    'both' => esc_html__('Both', 'latest-posts-for-elementor'),
                ],
                'condition' => [
                    'pagination_type!' => 'none',
                ],
            ]
        );

        $widget->add_control(
            'enable_preload',
            [
                'label' => esc_html__('Enable Preload', 'latest-posts-for-elementor'),
                'type' => Controls_Manager::SWITCHER,
                'default' => 'no',
                'condition' => [
                    'pagination_type' => ['numbers', 'load_more'],
                ],
            ]
        );

        $widget->add_control(
            'enable_history',
            [
                'label' => esc_html__('Enable History', 'latest-posts-for-elementor'),
                'type' => Controls_Manager::SWITCHER,
                'default' => 'no',
                'condition' => [
                    'pagination_type!' => 'none',
                ],
            ]
        );

        $widget->end_controls_section();
    }
}
