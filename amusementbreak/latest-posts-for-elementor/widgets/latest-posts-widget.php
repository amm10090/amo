<?php
namespace Latest_Posts_For_Elementor\Widgets;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}

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
        return [ 'posts', 'latest', 'blog', 'youtube', 'ad' ];
    }

    public function get_script_depends() {
        return ['latest-posts-youtube'];
    }

    public function __construct($data = [], $args = null) {
        parent::__construct($data, $args);

        wp_register_script(
            'latest-posts-youtube',
            plugins_url('assets/js/youtube-widget.js', dirname(__FILE__)),
            ['jquery'],
            '1.0.0',
            true
        );
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

        $this->end_controls_section();

        // 广告设置
        $this->start_controls_section(
            'ad_section',
            [
                'label' => esc_html__('Advertisement Settings', 'latest-posts-for-elementor'),
                'tab' => Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'ad_type',
            [
                'label' => esc_html__('Advertisement Type', 'latest-posts-for-elementor'),
                'type' => Controls_Manager::SELECT,
                'default' => 'youtube',
                'options' => [
                    'youtube' => esc_html__('YouTube Video', 'latest-posts-for-elementor'),
                    'image' => esc_html__('Custom Image', 'latest-posts-for-elementor'),
                    'html' => esc_html__('Custom HTML', 'latest-posts-for-elementor'),
                ],
            ]
        );

        $this->add_control(
            'ad_position',
            [
                'label' => esc_html__('AD Position', 'latest-posts-for-elementor'),
                'type' => Controls_Manager::NUMBER,
                'min' => 1,
                'max' => 100,
                'step' => 1,
                'default' => 3,
            ]
        );

        $this->add_control(
            'ad_repeat',
            [
                'label' => esc_html__('Repeat AD', 'latest-posts-for-elementor'),
                'type' => Controls_Manager::SWITCHER,
                'default' => 'no',
            ]
        );

        // YouTube 特定设置
        $this->add_control(
            'youtube_api_key',
            [
                'label' => esc_html__('YouTube API Key', 'latest-posts-for-elementor'),
                'type' => Controls_Manager::TEXT,
                'default' => '',
                'condition' => [
                    'ad_type' => 'youtube',
                ],
            ]
        );

        $this->add_control(
            'youtube_channel_id',
            [
                'label' => esc_html__('YouTube Channel ID', 'latest-posts-for-elementor'),
                'type' => Controls_Manager::TEXT,
                'default' => '',
                'condition' => [
                    'ad_type' => 'youtube',
                ],
            ]
        );

        $this->add_control(
            'youtube_autoplay',
            [
                'label' => esc_html__('Autoplay Video', 'latest-posts-for-elementor'),
                'type' => Controls_Manager::SWITCHER,
                'label_on' => esc_html__('Yes', 'latest-posts-for-elementor'),
                'label_off' => esc_html__('No', 'latest-posts-for-elementor'),
                'return_value' => 'yes',
                'default' => 'no',
                'condition' => [
                    'ad_type' => 'youtube',
                ],
            ]
        );

        $this->add_control(
            'youtube_show_play_icon',
            [
                'label' => esc_html__('Show Play Icon', 'latest-posts-for-elementor'),
                'type' => Controls_Manager::SWITCHER,
                'label_on' => esc_html__('Yes', 'latest-posts-for-elementor'),
                'label_off' => esc_html__('No', 'latest-posts-for-elementor'),
                'return_value' => 'yes',
                'default' => 'yes',
                'condition' => [
                    'ad_type' => 'youtube',
                ],
            ]
        );

        // 自定义图片广告设置
        $this->add_control(
            'ad_image',
            [
                'label' => esc_html__('Advertisement Image', 'latest-posts-for-elementor'),
                'type' => Controls_Manager::MEDIA,
                'default' => [
                    'url' => \Elementor\Utils::get_placeholder_image_src(),
                ],
                'condition' => [
                    'ad_type' => 'image',
                ],
            ]
        );

        $this->add_control(
            'ad_link',
            [
                'label' => esc_html__('Advertisement Link', 'latest-posts-for-elementor'),
                'type' => Controls_Manager::URL,
                'placeholder' => 'https://your-link.com',
                'show_external' => true,
                'default' => [
                    'url' => '',
                    'is_external' => true,
                    'nofollow' => true,
                ],
                'condition' => [
                    'ad_type' => 'image',
                ],
            ]
        );

        // 自定义HTML广告设置
        $this->add_control(
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

        $this->end_controls_section();

        // 样式设置
        $this->start_controls_section(
            'style_section',
            [
                'label' => esc_html__('Style', 'latest-posts-for-elementor'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

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
        $posts = $this->get_posts();

        if ($posts->have_posts()) {
            $this->render_widget_header();
            $this->render_posts($posts, $settings);
            $this->render_widget_footer();
        } else {
            echo esc_html__('No posts found', 'latest-posts-for-elementor');
        }

        wp_reset_postdata();
    }

    protected function get_posts() {
        $settings = $this->get_settings_for_display();
        $cache_key = 'latest_posts_' . md5(serialize($settings));
        $posts = wp_cache_get($cache_key);
        
        if (false === $posts) {
            $args = array(
                'post_type' => 'post',
                'posts_per_page' => $settings['posts_per_page'],
                'post_status' => 'publish',
                'orderby' => 'date',
                'order' => 'DESC',
                'no_found_rows' => true,
                'update_post_meta_cache' => false,
                'update_post_term_cache' => false,
            );
            
            $posts = new \WP_Query($args);
            wp_cache_set($cache_key, $posts, '', 300); // 缓存5分钟
        }
        
        return $posts;
    }

    protected function render_widget_header() {
        echo '<div class="latest-news-widget">';
        echo '<h2 class="latest-news-title"><span class="latest">Latest</span> <span class="news">News</span></h2>';
        echo '<div class="news-container">';
    }

    protected function render_posts($posts, $settings) {
        $post_count = 0;
        while ($posts->have_posts()) {
            $posts->the_post();
            $post_count++;
            
            $this->maybe_render_ad_post($post_count, $settings);
            $this->render_post($settings);
        }
    }

    protected function render_widget_footer() {
        echo '</div></div>';
    }

    protected function maybe_render_ad_post($post_count, $settings) {
        if ($post_count == $settings['ad_position'] || 
            ($settings['ad_repeat'] === 'yes' && $post_count % $settings['ad_position'] === 0)) {
            $this->render_ad_post();
        }
    }

    protected function render_post($settings) {
        echo '<div class="news-item">';
        if ( $settings['show_thumbnail'] === 'yes' && has_post_thumbnail() ) {
            echo '<div class="news-thumbnail">';
            the_post_thumbnail( 'thumbnail' );
            echo '</div>';
        }
        echo '<div class="news-content">';
        if ( $settings['show_title'] === 'yes' ) {
            echo '<h3 class="news-title"><a href="' . esc_url(get_permalink()) . '">' . esc_html(get_the_title()) . '</a></h3>';
        }
        if ( $settings['show_date'] === 'yes' || $settings['show_author'] === 'yes' ) {
            echo '<div class="news-meta">';
            if ( $settings['show_date'] === 'yes' ) {
                echo esc_html(get_the_date()) . ' ';
            }
            if ( $settings['show_author'] === 'yes' ) {
                echo '| ' . esc_html(get_the_author());
            }
            echo '</div>';
        }
        echo '</div>';
        echo '</div>';
    }

    protected function render_ad_post() {
        $settings = $this->get_settings_for_display();

        echo '<div class="news-item ad-post">';

        switch ($settings['ad_type']) {
            case 'youtube':
                $this->render_image_ad();
                break;
            case 'html':
                $this->render_html_ad();
                break;
        }

        echo '</div>';
    }

    protected function render_youtube_ad() {
        $settings = $this->get_settings_for_display();
        $youtube_data = $this->get_youtube_data();

        if ($youtube_data) {
            $autoplay = $settings['youtube_autoplay'] === 'yes' ? 'true' : 'false';
            $show_play_icon = $settings['youtube_show_play_icon'] === 'yes' ? 'true' : 'false';

            echo '<div class="youtube-ad" data-video-id="' . esc_attr($youtube_data['video_id']) . '" data-autoplay="' . $autoplay . '" data-show-play-icon="' . $show_play_icon . '">';
            echo '<div class="news-thumbnail">';
            echo '<img src="' . esc_url($youtube_data['thumbnail']) . '" alt="' . esc_attr($youtube_data['title']) . '">';
            if ($show_play_icon === 'true') {
                echo '<div class="play-icon"></div>';
            }
            echo '<span class="ad-label">YouTube</span>';
            echo '</div>';
            echo '<div class="news-content">';
            echo '<h3 class="news-title">' . esc_html($youtube_data['title']) . '</h3>';
            echo '<div class="news-meta">Latest YouTube Video</div>';
            echo '</div>';
            echo '</div>';
        }
    }

    protected function render_image_ad() {
        $settings = $this->get_settings_for_display();
        
        if (!empty($settings['ad_image']['url'])) {
            $this->add_link_attributes('ad_link', $settings['ad_link']);
            echo '<a ' . $this->get_render_attribute_string('ad_link') . '>';
            echo '<div class="news-thumbnail">';
            echo '<img src="' . esc_url($settings['ad_image']['url']) . '" alt="Advertisement">';
            echo '<span class="ad-label">AD</span>';
            echo '</div>';
            echo '</a>';
        }
    }

    protected function render_html_ad() {
        $settings = $this->get_settings_for_display();
        
        if (!empty($settings['ad_html'])) {
            echo '<div class="custom-html-ad">';
            echo $settings['ad_html'];
            echo '</div>';
        }
    }

    protected function get_youtube_data() {
        $settings = $this->get_settings_for_display();
        $api_key = $settings['youtube_api_key'];
        $channel_id = $settings['youtube_channel_id'];
        $cache_time = 3600; // 1 hour cache

        $cache_key = 'youtube_data_' . md5($channel_id . $api_key);
        $cached_data = get_transient($cache_key);

        if (false !== $cached_data) {
            return $cached_data;
        }

        $api_url = "https://www.googleapis.com/youtube/v3/search?key={$api_key}&channelId={$channel_id}&part=snippet,id&order=date&maxResults=1";
        $response = wp_remote_get($api_url);

        if (is_wp_error($response)) {
            return false;
        }

        $data = json_decode(wp_remote_retrieve_body($response), true);

        if (empty($data['items'])) {
            return false;
        }

        $video = $data['items'][0];
        $youtube_data = [
            'title' => $video['snippet']['title'],
            'thumbnail' => $video['snippet']['thumbnails']['medium']['url'],
            'video_id' => $video['id']['videoId'],
        ];

        set_transient($cache_key, $youtube_data, $cache_time);

        return $youtube_data;
    }
}