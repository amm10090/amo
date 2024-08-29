<?php
namespace Latest_Posts_For_Elementor\Controls;

use Elementor\Base_Data_Control;
use Elementor\Controls_Manager;

if (!defined('ABSPATH')) {
    exit; // 禁止直接访问
}

class Latest_Posts_Control extends Base_Data_Control {
    public function get_type() {
        return 'latest_posts_control';
    }

    protected function get_default_settings() {
        return [
            'label_block' => true,
        ];
    }

    public function content_template() {
        $control_uid = $this->get_control_uid();
        ?>
        <div class="elementor-control-field">
            <label for="<?php echo esc_attr($control_uid); ?>" class="elementor-control-title">{{{ data.label }}}</label>
            <div class="elementor-control-input-wrapper">
                <select id="<?php echo esc_attr($control_uid); ?>" class="elementor-control-tag-area" data-setting="{{ data.name }}">
                    <option value=""><?php echo esc_html__('Select a post', 'latest-posts-for-elementor'); ?></option>
                    <?php
                    $posts = get_posts(['numberposts' => -1]);
                    foreach ($posts as $post) {
                        echo '<option value="' . esc_attr($post->ID) . '">' . esc_html($post->post_title) . '</option>';
                    }
                    ?>
                </select>
            </div>
        </div>
        <# if (data.description) { #>
            <div class="elementor-control-field-description">{{{ data.description }}}</div>
        <# } #>
        <?php
    }

    public static function register_controls($widget) {
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
            'show_title',
            [
                'label' => esc_html__('Show Title', 'latest-posts-for-elementor'),
                'type' => Controls_Manager::SWITCHER,
                'default' => 'yes',
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

        $widget->end_controls_section();

        // 广告设置
        $widget->start_controls_section(
            'ad_section',
            [
                'label' => esc_html__('Advertisement Settings', 'latest-posts-for-elementor'),
                'tab' => Controls_Manager::TAB_CONTENT,
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
                    'image' => esc_html__('Custom Image', 'latest-posts-for-elementor'),
                    'html' => esc_html__('Custom HTML', 'latest-posts-for-elementor'),
                ],
            ]
        );

        $widget->add_control(
            'ad_position',
            [
                'label' => esc_html__('AD Position', 'latest-posts-for-elementor'),
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
                'label' => esc_html__('Repeat AD', 'latest-posts-for-elementor'),
                'type' => Controls_Manager::SWITCHER,
                'default' => 'no',
                'condition' => [
                    'ad_type!' => 'none',
                ],
            ]
        );

        // YouTube 特定设置
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
            'youtube_autoplay',
            [
                'label' => esc_html__('Autoplay', 'latest-posts-for-elementor'),
                'type' => Controls_Manager::SWITCHER,
                'default' => 'no',
                'condition' => [
                    'ad_type' => 'youtube',
                ],
            ]
        );

        $widget->add_control(
            'youtube_show_play_icon',
            [
                'label' => esc_html__('Show Play Icon', 'latest-posts-for-elementor'),
                'type' => Controls_Manager::SWITCHER,
                'default' => 'yes',
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
            'style_section',
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

        $widget->end_controls_section();
    }
}