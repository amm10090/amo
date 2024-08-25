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
            while ( $latest_posts->have_posts() ) : $latest_posts->the_post();
                echo '<div class="news-item">';
                if ( has_post_thumbnail() ) {
                    echo '<div class="news-thumbnail">';
                    the_post_thumbnail( 'thumbnail' );
                    echo '</div>';
                }
                echo '<div class="news-content">';
                echo '<h3 class="news-title"><a href="' . get_permalink() . '">' . get_the_title() . '</a></h3>';
                echo '<div class="news-meta">' . get_the_date() . ' | ' . get_the_author() . '</div>';
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

}