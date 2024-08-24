if (!defined('ABSPATH')) exit; // Exit if accessed directly

function register_dynamic_store_coupons_widget() {
    if (!did_action('elementor/loaded')) {
        return;
    }

    class Dynamic_Store_Coupons_Widget extends \Elementor\Widget_Base {
        public function get_name() {
            return 'dynamic_store_coupons';
        }

        public function get_title() {
            return __('动态商店优惠券', 'my-custom-theme');
        }

        public function get_icon() {
            return 'eicon-coupon';
        }

        public function get_categories() {
            return ['general'];
        }

        protected function register_controls() {
            // 内容设置
            $this->start_controls_section(
                'content_section',
                [
                    'label' => __('内容设置', 'my-custom-theme'),
                    'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
                ]
            );

            $this->add_control(
                'store_info',
                [
                    'label' => __('商店选择', 'my-custom-theme'),
                    'type' => \Elementor\Controls_Manager::RAW_HTML,
                    'raw' => __('优惠券将根据当前商店页面动态显示。', 'my-custom-theme'),
                    'content_classes' => 'elementor-descriptor',
                ]
            );

            $this->add_control(
                'template',
                [
                    'label' => __('模板', 'my-custom-theme'),
                    'type' => \Elementor\Controls_Manager::SELECT,
                    'default' => 'retailmenot',
                    'options' => [
                        'retailmenot' => __('RetailMeNot风格', 'my-custom-theme'),
                        'styleless' => __('无样式', 'my-custom-theme'),
                    ],
                ]
            );

            $this->end_controls_section();

            // 字段映射
            $this->start_controls_section(
                'field_mapping_section',
                [
                    'label' => __('字段映射', 'my-custom-theme'),
                    'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
                ]
            );

            $coupon_fields = $this->get_acf_coupon_fields();

            $default_fields = [
                'percentage' => __('优惠百分比', 'my-custom-theme'),
                'button_text' => __('按钮文本', 'my-custom-theme'),
                'button_link' => __('按钮链接', 'my-custom-theme'),
                'terms_button' => __('条款按钮', 'my-custom-theme'),
                'terms_accordion' => __('条款手风琴', 'my-custom-theme'),
            ];

            foreach ($default_fields as $field_key => $field_label) {
                $this->add_control(
                    $field_key . '_field',
                    [
                        'label' => $field_label,
                        'type' => \Elementor\Controls_Manager::SELECT,
                        'options' => $coupon_fields,
                        'default' => $field_key,
                    ]
                );
            }

            // 自定义字段
            $this->add_control(
                'custom_fields',
                [
                    'label' => __('自定义字段', 'my-custom-theme'),
                    'type' => \Elementor\Controls_Manager::REPEATER,
                    'fields' => [
                        [
                            'name' => 'field_label',
                            'label' => __('字段标签', 'my-custom-theme'),
                            'type' => \Elementor\Controls_Manager::TEXT,
                        ],
                        [
                            'name' => 'field_key',
                            'label' => __('字段名称', 'my-custom-theme'),
                            'type' => \Elementor\Controls_Manager::SELECT,
                            'options' => $coupon_fields,
                        ],
                    ],
                    'title_field' => '{{{ field_label }}}',
                ]
            );

            $this->end_controls_section();
        }

        protected function render() {
            $settings = $this->get_settings_for_display();
            $store_id = $this->get_current_store_id();

            if (!$store_id) {
                echo __('此小部件只能在商店页面上使用。', 'my-custom-theme');
                return;
            }

            $coupons = get_field('select_coupon', $store_id);

            if (!$coupons || empty($coupons)) {
                echo __('未找到该商店的优惠券。', 'my-custom-theme');
                return;
            }

            echo '<div class="store-coupons ' . esc_attr($settings['template']) . '-template">';
            foreach ($coupons as $coupon_id) {
                $this->render_coupon_item($coupon_id, $settings);
            }
            echo '</div>';
        }

        private function get_current_store_id() {
            if (is_singular('store')) {
                return get_the_ID();
            }
            return null;
        }

        private function render_coupon_item($coupon_id, $settings) {
            $percentage = get_field($settings['percentage_field'], $coupon_id);
            $coupon_title = get_the_title($coupon_id);
            $coupon_description = get_the_content(null, false, $coupon_id);
            $button_text = get_field($settings['button_text_field'], $coupon_id);
            $button_text = !empty($button_text) ? $button_text : __('获取优惠', 'my-custom-theme');
            $coupon_button_link = get_field($settings['button_link_field'], $coupon_id);
            $coupon_terms_button = get_field($settings['terms_button_field'], $coupon_id);
            $coupon_terms_accordion = get_field($settings['terms_accordion_field'], $coupon_id);

            ?>
            <div class="coupon-item">
                <div class="coupon-content">
                    <div class="coupon-left">
                        <?php if (!empty($percentage)): ?>
                            <div class="coupon-percentage"><?php echo esc_html($percentage); ?></div>
                        <?php endif; ?>
                    </div>
                    <div class="coupon-center">
                        <?php if (!empty($coupon_terms_button)): ?>
                            <span class="coupon-selection-button"><?php echo esc_html($coupon_terms_button); ?></span>
                        <?php endif; ?>
                        <div class="coupon-title"><?php echo esc_html($coupon_title); ?></div>
                        <?php if (!empty($coupon_description)): ?>
                            <div class="coupon-description"><?php echo wp_kses_post($coupon_description); ?></div>
                        <?php endif; ?>
                        <?php $this->render_custom_fields($coupon_id, $settings); ?>
                    </div>
                    <div class="coupon-right">
                        <?php if (!empty($button_text)): ?>
                            <a href="<?php echo esc_url($coupon_button_link); ?>" class="coupon-button" target="_blank"><?php echo esc_html($button_text); ?></a>
                        <?php endif; ?>
                    </div>
                </div>
                <?php if (!empty($coupon_terms_accordion)): ?>
                    <div class="coupon-footer">
                        <a href="#" class="see-details">
                            <?php echo __('查看详情', 'my-custom-theme'); ?> <span class="details-icon">+</span>
                        </a>
                    </div>
                    <div class="coupon-details" style="display:none;">
                        <?php echo wp_kses_post($coupon_terms_accordion); ?>
                    </div>
                <?php endif; ?>
            </div>
            <?php
        }

        private function render_custom_fields($coupon_id, $settings) {
            if (empty($settings['custom_fields'])) {
                return;
            }

            echo '<div class="custom-fields">';
            foreach ($settings['custom_fields'] as $custom_field) {
                $field_value = get_field($custom_field['field_key'], $coupon_id);
                if (!empty($field_value)) {
                    echo '<div class="custom-field custom-field-' . esc_attr($custom_field['field_key']) . '">';
                    echo '<span class="custom-field-label">' . esc_html($custom_field['field_label']) . ': </span>';
                    echo '<span class="custom-field-value">' . esc_html($field_value) . '</span>';
                    echo '</div>';
                }
            }
            echo '</div>';
        }

        private function get_acf_coupon_fields() {
            $fields = [];
            if (function_exists('acf_get_field_groups')) {
                $field_groups = acf_get_field_groups(['post_type' => 'coupon']);
                foreach ($field_groups as $field_group) {
                    $group_fields = acf_get_fields($field_group);
                    foreach ($group_fields as $field) {
                        $fields[$field['name']] = $field['label'];
                    }
                }
            }
            return $fields;
        }
    }

    \Elementor\Plugin::instance()->widgets_manager->register(new Dynamic_Store_Coupons_Widget());
}

add_action('elementor/widgets/register', 'register_dynamic_store_coupons_widget');

// 添加样式
add_action('wp_head', function () {
    ?>
    <style>
        .store-coupons.retailmenot-template {
            font-family: Arial, sans-serif;
            max-width: 800px;
            margin: 0 auto;
        }
        .retailmenot-template .coupon-item {
            border: 1px solid #e0e0e0;
            border-radius: 4px;
            margin-bottom: 20px;
            padding: 20px;
        }
        .retailmenot-template .coupon-content {
            display: flex;
            flex-wrap: wrap;
            justify-content: space-between;
            align-items: center;
        }
        .retailmenot-template .coupon-left {
            flex: 0 0 30%;
            margin-bottom: 10px;
        }
        .retailmenot-template .coupon-center {
            flex: 1;
            padding: 0 20px;
        }
        .retailmenot-template .coupon-right {
            flex: 0 0 auto;
            text-align: right;
        }
        .retailmenot-template .coupon-percentage {
            font-size: 24px;
            font-weight: bold;
            color: #6c0ab7;
        }
        .retailmenot-template .coupon-selection-button {
            display: inline-block;
            background-color: #6c0ab7;
            color: #ffffff;
            padding: 5px 10px;
            border-radius: 4px;
            font-size: 14px;
            font-weight: bold;
            margin-bottom: 10px;
        }
        .retailmenot-template .coupon-description {
            font-size: 16px;
            color: #333;
            margin-bottom: 10px;
        }
        .retailmenot-template .coupon-button {
            padding: 10px 20px;
            border-radius: 25px;
            background-color: #6c0ab7;
            color: #ffffff;
            text-decoration: none;
            font-weight: bold;
            display: inline-block;
        }
        .retailmenot-template .coupon-footer {
            border-top: 1px solid #e0e0e0;
            padding-top: 15px;
            margin-top: 15px;
        }
        .retailmenot-template .see-details {
            color: #333;
            text-decoration: none;
            font-size: 14px;
            display: inline-block;
            cursor: pointer;
        }
        .retailmenot-template .details-icon {
            font-weight: bold;
            margin-left: 5px;
        }
        .retailmenot-template .coupon-details {
            margin-top: 15px;
            font-size: 14px;
        }
        .retailmenot-template .custom-fields {
            margin-top: 10px;
        }
        .retailmenot-template .custom-field {
            font-size: 14px;
            margin-bottom: 5px;
        }
        .retailmenot-template .custom-field-label {
            font-weight: bold;
        }
        @media (min-width: 768px) {
            .retailmenot-template .coupon-left {
                flex: 0 0 30%;
                margin-bottom: 0;
            }
        }
    </style>
    <?php
});

// 添加 JavaScript
add_action('wp_footer', function () {
    ?>
    <script>
        jQuery(document).ready(function($) {
            function initCouponDetails() {
                $('.store-coupons').off('click', '.see-details').on('click', '.see-details', function(e) {
                    e.preventDefault();
                    var $this = $(this);
                    var $couponItem = $this.closest('.coupon-item');
                    var $details = $couponItem.find('.coupon-details');
                    var $icon = $this.find('.details-icon');
                    $details.slideToggle(300, function() {
                        if ($details.is(':visible')) {
                            $icon.text('-');
                            $this.contents().filter(function() {
                                return this.nodeType === 3;
                            }).first().replaceWith('隐藏详情 ');
                        } else {
                            $icon.text('+');
                            $this.contents().filter(function() {
                                return this.nodeType === 3;
                            }).first().replaceWith('查看详情 ');
                        }
                    });
                });
            }
            initCouponDetails();
            // 监听 Elementor 前端变化
            $(window).on('elementor/frontend/init', function() {
                elementorFrontend.hooks.addAction('frontend/element_ready/dynamic_store_coupons.default', function($scope) {
                    initCouponDetails();
                });
            });
        });
    </script>
    <?php
});