<?php

namespace Latest_Posts_For_Elementor\Widgets;

use Elementor\Widget_Base;
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
class Latest_Posts_Widget extends Widget_Base
{
    /**
     * 获取小部件名称
     */
    public function get_name()
    {
        return 'latest_posts';
    }

    /**
     * 获取小部件标题
     */
    public function get_title()
    {
        return esc_html__('Latest Posts', 'latest-posts-for-elementor');
    }

    /**
     * 获取小部件图标
     */
    public function get_icon()
    {
        return 'eicon-post-list';
    }

    /**
     * 获取小部件类别
     */
    public function get_categories()
    {
        return ['general'];
    }

    /**
     * 获取小部件依赖的脚本
     */
    public function get_script_depends()
    {
        return ['latest-posts-youtube'];
    }

    /**
     * 注册小部件控件
     */
    protected function register_controls()
    {
        Latest_Posts_Control::register_controls($this);
    }

    /**
     * 渲染小部件输出
     */
    protected function render()
    {
        $settings = $this->get_settings_for_display();
        $paged = get_query_var('paged') ? get_query_var('paged') : 1;

        $args = [
            'post_type' => 'post',
            'posts_per_page' => $settings['posts_per_page'],
            'paged' => $paged,
        ];

        $query = new \WP_Query($args);

        if ($query->have_posts()) {
            $layout_class = 'layout-' . $settings['layout'];
            $columns_class = 'columns-' . $settings['columns'];
            echo '<div class="latest-news-widget ' . $layout_class . ' ' . $columns_class . '" data-preset-style="' . esc_attr($settings['preset_style']) . '">';

            if ($settings['show_title_bar'] === 'yes') {
                $this->render_title_bar($settings);
            }

            if (in_array($settings['pagination_position'], ['top', 'both'])) {
                $this->render_pagination($query, $settings);
            }

            echo '<div class="news-container">';
            $post_count = 0;
            while ($query->have_posts()) {
                $query->the_post();
                $post_count++;

                $this->render_post($settings, $post_count);

                // 插入广告
                if ($settings['ad_type'] !== 'none' && $post_count % $settings['ad_position'] === 0) {
                    if ($settings['ad_repeat'] === 'yes' || (!$settings['ad_repeat'] && $post_count === $settings['ad_position'])) {
                        $this->render_advertisement($settings);
                    }
                }
            }
            echo '</div>'; // .news-container

            if (in_array($settings['pagination_position'], ['bottom', 'both'])) {
                $this->render_pagination($query, $settings);
            }

            echo '</div>'; // .latest-news-widget

            wp_reset_postdata();
        }
    }

    /**
     * 渲染标题栏
     */
    protected function render_title_bar($settings)
    {
        if ($settings['show_title_bar'] === 'yes') {
            echo '<h2 class="latest-news-title elementor-heading-title">';
            echo '<span class="first-title">' . esc_html($settings['title_bar_first_text']) . '</span> ';
            echo '<span class="second-title">' . esc_html($settings['title_bar_second_text']) . '</span>';
            echo '</h2>';
        }
    }

    /**
     * 渲染单个文章
     */
    protected function render_post($settings, $post_count)
    {
        $thumbnail_class = 'thumbnail-ratio-' . str_replace('/', '-', $settings['thumbnail_ratio']);
        $divider_class = $settings['show_divider'] === 'yes' ? 'with-divider' : '';

        echo '<a href="' . get_permalink() . '" class="news-item-link">';
        echo '<div class="news-item ' . $divider_class . '">';
        if ($settings['show_thumbnail'] === 'yes') {
            echo '<div class="news-thumbnail ' . $thumbnail_class . '">';
            the_post_thumbnail('medium');
            echo '</div>';
        }

        echo '<div class="news-content">';
        if ($settings['show_title'] === 'yes') {
            echo '<h3 class="news-title">' . get_the_title() . '</h3>';
        }

        if ($settings['show_excerpt'] === 'yes') {
            $excerpt = get_the_excerpt();
            if (empty($excerpt)) {
                $excerpt = wp_trim_words(get_the_content(), $settings['excerpt_length'], '...');
            } else {
                $excerpt = wp_trim_words($excerpt, $settings['excerpt_length'], '...');
            }
            echo '<div class="news-excerpt">' . $excerpt . '</div>';
        }

        echo '<div class="news-meta">';
        if ($settings['show_date'] === 'yes') {
            echo '<span class="news-date">' . get_the_date() . '</span>';
        }
        if ($settings['show_author'] === 'yes') {
            echo '<span class="news-author">' . get_the_author() . '</span>';
        }
        if ($settings['show_category'] === 'yes') {
            $categories = get_the_category();
            if (!empty($categories)) {
                echo '<span class="news-category">' . esc_html($categories[0]->name) . '</span>';
            }
        }
        echo '</div>'; // .news-meta

        echo '</div>'; // .news-content
        echo '</div>'; // .news-item
        echo '</a>'; // .news-item-link
    }

    /**
     * 渲染广告
     */
    protected function render_advertisement($settings)
    {
        $thumbnail_class = 'thumbnail-ratio-' . str_replace('/', '-', $settings['thumbnail_ratio']);

        echo '<div class="news-item-link ad-item">';
        echo '<div class="news-item">';

        switch ($settings['ad_type']) {
            case 'youtube':
                $this->render_youtube_ad($settings, $thumbnail_class);
                break;
            case 'image':
                $this->render_image_ad($settings, $thumbnail_class);
                break;
            case 'html':
                $this->render_html_ad($settings, $thumbnail_class);
                break;
        }

        echo '</div>'; // .news-item
        echo '</div>'; // .news-item-link.ad-item
    }

    /**
     * 渲染YouTube广告
     */
    protected function render_youtube_ad($settings, $thumbnail_class)
    {
        $video_info = $this->get_youtube_video_info($settings['youtube_channel_id'], $settings['youtube_api_key']);

        if ($video_info) {
            echo '<a href="' . esc_url($video_info['url']) . '" class="news-thumbnail ' . $thumbnail_class . '">';
            echo '<img src="' . esc_url($video_info['thumbnail']) . '" alt="' . esc_attr($video_info['title']) . '">';
            echo '<div class="play-icon"></div>';
            echo '<span class="ad-badge">AD</span>';
            echo '</a>';

            echo '<div class="news-content">';
            if ($settings['ad_show_title'] === 'yes') {
                echo '<h3 class="news-title">' . esc_html($video_info['title']) . '</h3>';
            }

            if ($settings['ad_show_excerpt'] === 'yes') {
                $excerpt = wp_trim_words($video_info['description'], $settings['ad_excerpt_length'], '...');
                echo '<div class="news-excerpt">' . $excerpt . '</div>';
            }

            echo '<div class="news-meta">';
            if ($settings['ad_show_date'] === 'yes') {
                echo '<span class="news-date">' . $video_info['published_at'] . '</span>';
            }
            if ($settings['ad_show_author'] === 'yes') {
                echo '<span class="news-author">' . $video_info['channel_title'] . '</span>';
            }
            echo '</div>'; // .news-meta

            echo '</div>'; // .news-content
        }
    }

    /**
     * 渲染图片广告
     */
    protected function render_image_ad($settings, $thumbnail_class)
    {
        if (!empty($settings['ad_image']['url'])) {
            echo '<a href="' . esc_url($settings['ad_link']['url']) . '" class="news-thumbnail ' . $thumbnail_class . '">';
            echo '<img src="' . esc_url($settings['ad_image']['url']) . '" alt="Advertisement">';
            echo '<span class="ad-badge">AD</span>';
            echo '</a>';

            echo '<div class="news-content">';
            if ($settings['ad_show_title'] === 'yes' && !empty($settings['ad_title'])) {
                echo '<h3 class="news-title">' . esc_html($settings['ad_title']) . '</h3>';
            }
            if ($settings['ad_show_excerpt'] === 'yes' && !empty($settings['ad_description'])) {
                $excerpt = wp_trim_words($settings['ad_description'], $settings['ad_excerpt_length'], '...');
                echo '<div class="news-excerpt">' . $excerpt . '</div>';
            }
            echo '<div class="news-meta">';
            if ($settings['ad_show_date'] === 'yes' && !empty($settings['ad_date'])) {
                echo '<span class="news-date">' . esc_html($settings['ad_date']) . '</span>';
            }
            if ($settings['ad_show_author'] === 'yes' && !empty($settings['ad_author'])) {
                echo '<span class="news-author">' . esc_html($settings['ad_author']) . '</span>';
            }
            echo '</div>'; // .news-meta
            echo '</div>'; // .news-content
        }
    }

    /**
     * 渲染HTML广告
     */
    protected function render_html_ad($settings, $thumbnail_class)
    {
        if (!empty($settings['ad_html'])) {
            echo '<div class="news-thumbnail ' . $thumbnail_class . '">';
            echo '<span class="ad-badge">AD</span>';
            echo '</div>';
            echo '<div class="news-content">';
            echo $settings['ad_html'];
            echo '</div>';
        }
    }

    /**
     * 获取YouTube视频信息
     */
    protected function get_youtube_video_info($channel_id, $api_key)
    {
        $cache_key = 'youtube_video_info_' . $channel_id;
        $video_info = get_transient($cache_key);

        if (false === $video_info) {
            $api_url = "https://www.googleapis.com/youtube/v3/search?key={$api_key}&channelId={$channel_id}&part=snippet,id&order=date&maxResults=1";
            $response = wp_remote_get($api_url);

            if (is_wp_error($response)) {
                return false;
            }

            $body = wp_remote_retrieve_body($response);
            $data = json_decode($body, true);

            if (empty($data['items'])) {
                return false;
            }

            $item = $data['items'][0];
            $video_info = [
                'title' => $item['snippet']['title'],
                'description' => $item['snippet']['description'],
                'thumbnail' => $item['snippet']['thumbnails']['high']['url'],
                'url' => "https://www.youtube.com/watch?v={$item['id']['videoId']}",
                'published_at' => date('Y-m-d', strtotime($item['snippet']['publishedAt'])),
                'channel_title' => $item['snippet']['channelTitle'],
            ];

            set_transient($cache_key, $video_info, HOUR_IN_SECONDS);
        }

        return $video_info;
    }

    /**
     * 渲染分页
     */
    protected function render_pagination($query, $settings)
    {
        if ($settings['pagination_type'] === 'none') {
            return;
        }

        $total_pages = $query->max_num_pages;

        if ($total_pages > 1) {
            $current_page = max(1, get_query_var('paged'));

            echo '<nav class="elementor-pagination" role="navigation">';

            if ($settings['pagination_type'] === 'numbers') {
                echo paginate_links([
                    'base' => str_replace(999999999, '%#%', esc_url(get_pagenum_link(999999999))),
                    'format' => '?paged=%#%',
                    'current' => $current_page,
                    'total' => $total_pages,
                    'prev_text' => __('&laquo; Previous'),
                    'next_text' => __('Next &raquo;'),
                ]);
            } elseif ($settings['pagination_type'] === 'load_more') {
                echo '<button class="elementor-button elementor-load-more-button" data-page="' . $current_page . '" data-max-page="' . $total_pages . '">';
                echo __('Load More', 'latest-posts-for-elementor');
                echo '</button>';
            }

            echo '</nav>';
        }
    }
}
