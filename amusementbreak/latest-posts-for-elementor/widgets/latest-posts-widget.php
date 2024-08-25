<?php
namespace Latest_Posts_For_Elementor\Widgets;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}

/**
 * Latest Posts Widget
 *
 * Elementor widget for latest posts.
 *
 * @since 1.0.0
 */
class Latest_Posts_Widget extends Widget_Base {

    public function get_name() {
        return 'latest_posts';
    }

    public function get_title() {
        return esc_html__( 'Latest Posts', 'latest-posts-for-elementor' );
    }

    public function get_icon() {
        return 'eicon-post-list';
    }

    public function get_categories() {
        return [ 'general' ];
    }

    public function get_keywords() {
        return [ 'posts', 'latest', 'blog' ];
    }

    protected function register_controls() {
        // 内容设置
        $this->start_controls_section(
            'content_section',
            [
                'label' => esc_html__( 'Content', 'latest-posts-for-elementor' ),
                'tab' => Controls_Manager::TAB_CONTENT,
            ]
        );

        // 文章数量设置
        $this->add_control(
            'posts_per_page',
            [
                'label' => esc_html__( 'Posts Per Page', 'latest-posts-for-elementor' ),
                'type' => Controls_Manager::NUMBER,
                'min' => 1,
                'max' => 100,
                'step' => 1,
                'default' => 5,
            ]
        );

        // 显示控制
        $this->add_control(
            'show_title',
            [
                'label' => esc_html__('Show Title', 'latest-posts-for-elementor'),
                'type' => Controls_Manager::SWITCHER,
                'default' => 'yes',
            ]
        );

        $this->add_control(
            'show_date',
            [
                'label' => esc_html__('Show Date', 'latest-posts-for-elementor'),
                'type' => Controls_Manager::SWITCHER,
                'default' => 'yes',
            ]
        );

        $this->add_control(
            'show_author',
            [
                'label' => esc_html__('Show Author', 'latest-posts-for-elementor'),
                'type' => Controls_Manager::SWITCHER,
                'default' => 'yes',
            ]
        );

        $this->add_control(
            'show_thumbnail',
            [
                'label' => esc_html__('Show Thumbnail', 'latest-posts-for-elementor'),
                'type' => Controls_Manager::SWITCHER,
                'default' => 'yes',
            ]
        );

        // AD 帖子设置
        $this->add_control(
            'ad_post_position',
            [
                'label' => esc_html__('AD Post Position', 'latest-posts-for-elementor'),
                'type' => Controls_Manager::NUMBER,
                'min' => 1,
                'max' => 100,
                'step' => 1,
                'default' => 3,
            ]
        );

        $this->add_control(
            'ad_post_repeat',
            [
                'label' => esc_html__('Repeat AD Post', 'latest-posts-for-elementor'),
                'type' => Controls_Manager::SWITCHER,
                'default' => 'no',
            ]
        );

        $this->end_controls_section();

        // 样式设置
        $this->start_controls_section(
            'style_section',
            [
                'label' => esc_html__('Style', 'latest-posts-for-elementor'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        // 缩略图宽度设置
        $this->add_responsive_control(
            'thumbnail_width',
            [
                'label' => esc_html__('Thumbnail Width', 'latest-posts-for-elementor'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px', '%'],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 1000,
                        'step' => 1,
                    ],
                    '%' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                ],
                'default' => [
                    'unit' => '%',
                    'size' => 30,
                ],
                'selectors' => [
                    '{{WRAPPER}} .news-thumbnail' => 'width: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        // 缩略图高度设置
        $this->add_responsive_control(
            'thumbnail_height',
            [
                'label' => esc_html__('Thumbnail Height', 'latest-posts-for-elementor'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px', '%'],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 1000,
                        'step' => 1,
                    ],
                    '%' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                ],
                'default' => [
                    'unit' => 'px',
                    'size' => 200,
                ],
                'selectors' => [
                    '{{WRAPPER}} .news-thumbnail img' => 'height: {{SIZE}}{{UNIT}}; object-fit: cover;',
                ],
            ]
        );

        // "NEWS" 颜色设置
        $this->add_control(
            'news_color',
            [
                'label' => esc_html__('News Color', 'latest-posts-for-elementor'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .latest-news-title .news' => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->end_controls_section();
    }

    protected function render() {
        $settings = $this->get_settings_for_display();

        $args = array(
            'post_type' => 'post',
            'posts_per_page' => $settings['posts_per_page'],
            'post_status' => 'publish',
            'orderby' => 'date',
            'order' => 'DESC',
        );

        $latest_posts = new \WP_Query( $args );

        if ( $latest_posts->have_posts() ) :
            echo '<div class="latest-news-widget">';
            echo '<h2 class="latest-news-title"><span class="latest">Latest</span> <span class="news">News</span></h2>';
            echo '<div class="news-container">';
            $post_count = 0;
            while ( $latest_posts->have_posts() ) : $latest_posts->the_post();
                $post_count++;
                
                // 检查是否应该插入 AD 帖子
                if ($post_count == $settings['ad_post_position'] || 
                    ($settings['ad_post_repeat'] === 'yes' && $post_count % $settings['ad_post_position'] === 0)) {
                    $this->render_ad_post();
                }
                
                echo '<div class="news-item">';
                if ( $settings['show_thumbnail'] === 'yes' && has_post_thumbnail() ) {
                    echo '<div class="news-thumbnail">';
                    the_post_thumbnail( 'thumbnail' );
                    echo '</div>';
                }
                echo '<div class="news-content">';
                if ( $settings['show_title'] === 'yes' ) {
                    echo '<h3 class="news-title"><a href="' . get_permalink() . '">' . get_the_title() . '</a></h3>';
                }
                if ( $settings['show_date'] === 'yes' || $settings['show_author'] === 'yes' ) {
                    echo '<div class="news-meta">';
                    if ( $settings['show_date'] === 'yes' ) {
                        echo get_the_date() . ' ';
                    }
                    if ( $settings['show_author'] === 'yes' ) {
                        echo '| ' . get_the_author();
                    }
                    echo '</div>';
                }
                echo '</div>';
                echo '</div>';
            endwhile;
            echo '</div>';
            echo '</div>';
        else :
            echo esc_html__( 'No posts found', 'latest-posts-for-elementor' );
        endif;

        wp_reset_postdata();
    }

    // 渲染 AD 帖子
    protected function render_ad_post() {
        echo '<div class="news-item ad-post">';
        echo '<div class="news-thumbnail">';
        echo '<img src="' . plugins_url( 'assets/images/ad-placeholder.jpg', __FILE__ ) . '" alt="AD">';
        echo '<span class="ad-label">AD</span>';
        echo '</div>';
        echo '<div class="news-content">';
        echo '<h3 class="news-title"><a href="#">Advertisement Title</a></h3>';
        echo '<div class="news-meta">Sponsored Content</div>';
        echo '</div>';
        echo '</div>';
    }
}