<?php
namespace Latest_Posts_For_Elementor\Widgets;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Latest_Posts_For_Elementor\Controls\Latest_Posts_Control;

if (!defined('ABSPATH')) {
    exit; // 禁止直接访问
}

/**
 * Latest Posts Widget for Elementor
 *
 * 显示最新文章列表,支持广告整合,YouTube视频广告,样式自定义,性能优化,响应式设计等功能。
 *
 * @since 1.0.0
 */
class Latest_Posts_Widget extends Widget_Base {

    public function get_name() {
        return 'latest_posts';
    }

    public function get_title() {
        return esc_html__('Latest Posts', 'latest-posts-for-elementor');
    }

    public function get_icon() {
        return 'eicon-post-list';
    }

    public function get_categories() {
        return ['general'];
    }

    public function get_script_depends() {
        return ['latest-posts-youtube'];
    }

    protected function register_controls() {
        Latest_Posts_Control::register_controls($this);
    }

    protected function render() {
        $settings = $this->get_settings_for_display();
        $posts = $this->get_posts($settings);

        if ($posts->have_posts()) :
            echo '<div class="latest-news-widget">';
            echo '<h2 class="latest-news-title"><span class="latest">' . esc_html__('Latest', 'latest-posts-for-elementor') . '</span> <span class="news">' . esc_html__('News', 'latest-posts-for-elementor') . '</span></h2>';
            echo '<div class="news-container">';

            $count = 0;
            while ($posts->have_posts()) : $posts->the_post();
                $count++;
                if ($settings['ad_position'] == $count) {
                    $this->render_ad($settings);
                }
                $this->render_post($settings);
                if ($settings['ad_repeat'] === 'yes' && $count % $settings['ad_position'] == 0 && $count != $settings['posts_per_page']) {
                    $this->render_ad($settings);
                }
            endwhile;

            echo '</div></div>';
            wp_reset_postdata();
        endif;

        $this->add_structured_data();
    }

    protected function get_posts($settings) {
        $args = [
            'post_type' => 'post',
            'posts_per_page' => $settings['posts_per_page'],
            'ignore_sticky_posts' => true,
        ];

        $cache_key = 'latest_posts_' . md5(serialize($args));
        $posts = wp_cache_get($cache_key);

        if (false === $posts) {
            $posts = new \WP_Query($args);
            wp_cache_set($cache_key, $posts, '', 300); // 缓存5分钟
        }

        return $posts;
    }

    protected function render_post($settings) {
        echo '<div class="news-item">';
        if ($settings['show_thumbnail'] === 'yes' && has_post_thumbnail()) {
            echo '<div class="news-thumbnail">';
            the_post_thumbnail('medium');
            echo '</div>';
        }
        echo '<div class="news-content">';
        if ($settings['show_title'] === 'yes') {
            echo '<h3 class="news-title"><a href="' . get_permalink() . '">' . get_the_title() . '</a></h3>';
        }
        echo '<div class="news-meta">';
        if ($settings['show_date'] === 'yes') {
            echo '<span class="news-date">' . get_the_date() . '</span>';
        }
        if ($settings['show_author'] === 'yes') {
            echo '<span class="news-author">' . esc_html(get_the_author()) . '</span>';
        }
        echo '</div>';
        echo '</div></div>';
    }

    protected function render_ad($settings) {
        echo '<div class="ad-post">';
        echo '<span class="ad-label">' . esc_html__('Advertisement', 'latest-posts-for-elementor') . '</span>';
        switch ($settings['ad_type']) {
            case 'youtube':
                $this->render_youtube_ad($settings);
                break;
            case 'image':
                $this->render_image_ad($settings);
                break;
            case 'html':
                $this->render_html_ad($settings);
                break;
        }
        echo '</div>';
    }

    protected function render_youtube_ad($settings) {
        $channel_id = $settings['youtube_channel_id'];
        $autoplay = $settings['youtube_autoplay'] === 'yes' ? 1 : 0;
        $show_play_icon = $settings['youtube_show_play_icon'] === 'yes';

        $transient_key = 'youtube_latest_video_' . $channel_id;
        $video_data = get_transient($transient_key);

        if (false === $video_data) {
            $api_key = $settings['youtube_api_key'];
            $api_url = "https://www.googleapis.com/youtube/v3/search?key={$api_key}&channelId={$channel_id}&part=snippet,id&order=date&maxResults=1";

            $response = wp_remote_get($api_url);

            if (is_wp_error($response)) {
                error_log('YouTube API Error: ' . $response->get_error_message());
                return;
            }

            $data = json_decode(wp_remote_retrieve_body($response), true);

            if (empty($data['items'])) {
                error_log('No videos found for channel: ' . $channel_id);
                return;
            }

            $video = $data['items'][0];
            $video_data = [
                'id' => $video['id']['videoId'],
                'title' => $video['snippet']['title'],
                'thumbnail' => $video['snippet']['thumbnails']['high']['url'],
            ];

            set_transient($transient_key, $video_data, HOUR_IN_SECONDS);
        }

        echo '<div class="youtube-ad" data-video-id="' . esc_attr($video_data['id']) . '" data-autoplay="' . esc_attr($autoplay) . '" data-show-play-icon="' . esc_attr($show_play_icon) . '">';
        echo '<div class="news-thumbnail">';
        echo '<img src="' . esc_url($video_data['thumbnail']) . '" alt="' . esc_attr($video_data['title']) . '">';
        if ($show_play_icon) {
            echo '<div class="play-icon"></div>';
        }
        echo '</div>';
        echo '</div>';
    }

    protected function render_image_ad($settings) {
        $image_url = $settings['ad_image']['url'];
        $ad_link = $settings['ad_link']['url'];
        if (!empty($image_url)) {
            echo '<div class="image-ad">';
            if (!empty($ad_link)) {
                echo '<a href="' . esc_url($ad_link) . '" target="_blank" rel="noopener noreferrer">';
            }
            echo '<img src="' . esc_url($image_url) . '" alt="' . esc_attr__('Advertisement', 'latest-posts-for-elementor') . '">';
            if (!empty($ad_link)) {
                echo '</a>';
            }
            echo '</div>';
        }
    }

    protected function render_html_ad($settings) {
        $html_content = $settings['ad_html'];
        if (!empty($html_content)) {
            echo '<div class="html-ad">';
            echo wp_kses_post($html_content);
            echo '</div>';
        }
    }

    protected function add_structured_data() {
        $schema = [
            '@context' => 'https://schema.org',
            '@type' => 'ItemList',
            'itemListElement' => [],
        ];

        $posts = $this->get_posts($this->get_settings_for_display());
        $position = 1;

        while ($posts->have_posts()) {
            $posts->the_post();
            $schema['itemListElement'][] = [
                '@type' => 'ListItem',
                'position' => $position,
                'url' => get_permalink(),
            ];
            $position++;
        }

        wp_reset_postdata();

        echo '<script type="application/ld+json">' . wp_json_encode($schema) . '</script>';
    }
}