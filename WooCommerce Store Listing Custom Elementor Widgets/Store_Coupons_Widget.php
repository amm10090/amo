if (!defined('ABSPATH')) exit; // Exit if accessed directly

function register_store_coupons_widget() {
    if (!did_action('elementor/loaded')) {
        return;
    }

    class Store_Coupons_Widget extends \Elementor\Widget_Base {
        public function get_name() {
            return 'store_coupons';
        }

        public function get_title() {
            return __('Store Coupons', 'my-custom-theme');
        }

        public function get_icon() {
            return 'eicon-coupon';
        }

        public function get_categories() {
            return ['general'];
        }

        protected function _register_controls() {
            // Content Section
            $this->start_controls_section(
                'content_section',
                [
                    'label' => __('Content', 'my-custom-theme'),
                    'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
                ]
            );

            $this->add_control(
                'store',
                [
                    'label' => __('Select Store', 'my-custom-theme'),
                    'type' => \Elementor\Controls_Manager::SELECT2,
                    'options' => $this->get_stores(),
                    'default' => '',
                ]
            );

            $this->add_control(
                'template',
                [
                    'label' => __('Template', 'my-custom-theme'),
                    'type' => \Elementor\Controls_Manager::SELECT,
                    'default' => 'retailmenot',
                    'options' => [
                        'retailmenot' => __('RetailMeNot', 'my-custom-theme'),
                        'styleless' => __('Styleless', 'my-custom-theme'),
                    ],
                ]
            );

            $this->add_control(
                'show_title',
                [
                    'label' => __('Show Title', 'my-custom-theme'),
                    'type' => \Elementor\Controls_Manager::SWITCHER,
                    'label_on' => __('Show', 'my-custom-theme'),
                    'label_off' => __('Hide', 'my-custom-theme'),
                    'return_value' => 'yes',
                    'default' => 'yes',
                ]
            );

            $this->add_control(
                'show_description',
                [
                    'label' => __('Show Description', 'my-custom-theme'),
                    'type' => \Elementor\Controls_Manager::SWITCHER,
                    'label_on' => __('Show', 'my-custom-theme'),
                    'label_off' => __('Hide', 'my-custom-theme'),
                    'return_value' => 'yes',
                    'default' => 'yes',
                ]
            );

            $this->add_control(
                'show_button',
                [
                    'label' => __('Show Button', 'my-custom-theme'),
                    'type' => \Elementor\Controls_Manager::SWITCHER,
                    'label_on' => __('Show', 'my-custom-theme'),
                    'label_off' => __('Hide', 'my-custom-theme'),
                    'return_value' => 'yes',
                    'default' => 'yes',
                ]
            );

            $this->add_control(
                'show_selection_button',
                [
                    'label' => __('Show Selection Button', 'my-custom-theme'),
                    'type' => \Elementor\Controls_Manager::SWITCHER,
                    'label_on' => __('Show', 'my-custom-theme'),
                    'label_off' => __('Hide', 'my-custom-theme'),
                    'return_value' => 'yes',
                    'default' => 'yes',
                ]
            );

            $this->end_controls_section();

            // Style Section
            $this->start_controls_section(
                'style_section',
                [
                    'label' => __('Style', 'my-custom-theme'),
                    'tab' => \Elementor\Controls_Manager::TAB_STYLE,
                ]
            );

            $this->add_control(
                'background_color',
                [
                    'label' => __('Background Color', 'my-custom-theme'),
                    'type' => \Elementor\Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} .coupon-item' => 'background-color: {{VALUE}}',
                    ],
                ]
            );

            $this->add_group_control(
                \Elementor\Group_Control_Border::get_type(),
                [
                    'name' => 'border',
                    'label' => __('Border', 'my-custom-theme'),
                    'selector' => '{{WRAPPER}} .coupon-item',
                ]
            );

            $this->add_control(
                'border_radius',
                [
                    'label' => __('Border Radius', 'my-custom-theme'),
                    'type' => \Elementor\Controls_Manager::DIMENSIONS,
                    'size_units' => ['px', '%'],
                    'selectors' => [
                        '{{WRAPPER}} .coupon-item' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                ]
            );

            $this->add_group_control(
                \Elementor\Group_Control_Box_Shadow::get_type(),
                [
                    'name' => 'box_shadow',
                    'label' => __('Box Shadow', 'my-custom-theme'),
                    'selector' => '{{WRAPPER}} .coupon-item',
                ]
            );

            $this->add_responsive_control(
                'coupon_padding',
                [
                    'label' => __('Padding', 'my-custom-theme'),
                    'type' => \Elementor\Controls_Manager::DIMENSIONS,
                    'size_units' => ['px', 'em', '%'],
                    'selectors' => [
                        '{{WRAPPER}} .coupon-item' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                ]
            );

            $this->add_control(
                'title_color',
                [
                    'label' => __('Title Color', 'my-custom-theme'),
                    'type' => \Elementor\Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} .coupon-title' => 'color: {{VALUE}}',
                    ],
                ]
            );

            $this->add_group_control(
                \Elementor\Group_Control_Typography::get_type(),
                [
                    'name' => 'title_typography',
                    'label' => __('Title Typography', 'my-custom-theme'),
                    'selector' => '{{WRAPPER}} .coupon-title',
                ]
            );

            $this->add_control(
                'description_color',
                [
                    'label' => __('Description Color', 'my-custom-theme'),
                    'type' => \Elementor\Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} .coupon-description' => 'color: {{VALUE}}',
                    ],
                ]
            );

            $this->add_group_control(
                \Elementor\Group_Control_Typography::get_type(),
                [
                    'name' => 'description_typography',
                    'label' => __('Description Typography', 'my-custom-theme'),
                    'selector' => '{{WRAPPER}} .coupon-description',
                ]
            );

            $this->add_control(
                'button_background_color',
                [
                    'label' => __('Button Background Color', 'my-custom-theme'),
                    'type' => \Elementor\Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} .coupon-button' => 'background-color: {{VALUE}}',
                    ],
                ]
            );

            $this->add_control(
                'button_text_color',
                [
                    'label' => __('Button Text Color', 'my-custom-theme'),
                    'type' => \Elementor\Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} .coupon-button' => 'color: {{VALUE}}',
                    ],
                ]
            );

            $this->add_group_control(
                \Elementor\Group_Control_Typography::get_type(),
                [
                    'name' => 'button_typography',
                    'label' => __('Button Typography', 'my-custom-theme'),
                    'selector' => '{{WRAPPER}} .coupon-button',
                ]
            );

            $this->end_controls_section();
        }

        private function get_stores() {
            $stores = get_posts(['post_type' => 'store', 'posts_per_page' => -1]);
            $options = ['' => __('Select a store', 'my-custom-theme')];
            foreach ($stores as $store) {
                $options[$store->ID] = $store->post_title;
            }
            return $options;
        }

        protected function render() {
            $settings = $this->get_settings_for_display();

            if (empty($settings['store'])) {
                echo __('Please select a store.', 'my-custom-theme');
                return;
            }

            $coupons = get_field('select_coupon', $settings['store']);

            if (!$coupons || empty($coupons)) {
                echo __('No coupons found for this store.', 'my-custom-theme');
                return;
            }

            echo '<div class="store-coupons ' . esc_attr($settings['template']) . '-template">';
            foreach ($coupons as $coupon_id) {
                if ($settings['template'] === 'retailmenot') {
                    $this->render_retailmenot_coupon_item($coupon_id, $settings);
                } else {
                    $this->render_styleless_coupon_item($coupon_id, $settings);
                }
            }
            echo '</div>';
        }

        private function render_retailmenot_coupon_item($coupon_id, $settings) {
            $coupon_title = get_field('coupon_title', $coupon_id);
            $coupon_description = get_field('coupon_description', $coupon_id);
            $coupon_selection_button = get_field('coupon_selection_button', $coupon_id) ?: 'SALE';
            $coupon_button_text = get_field('coupon_button_text', $coupon_id) ?: 'Get Deal';
            $coupon_details = get_field('coupon_details', $coupon_id);
            $coupon_verified = get_field('coupon_verified', $coupon_id);
            $coupon_uses = get_field('coupon_uses', $coupon_id);

            ?>
            <div class="coupon-item">
                <div class="coupon-content">
                    <?php if ($settings['show_title'] === 'yes'): ?>
                        <div class="coupon-left">
                            <div class="coupon-title"><?php echo esc_html($coupon_title); ?></div>
                        </div>
                    <?php endif; ?>
                    <div class="coupon-center">
                        <?php if ($settings['show_selection_button'] === 'yes'): ?>
                            <span class="coupon-selection-button"><?php echo esc_html($coupon_selection_button); ?></span>
                        <?php endif; ?>
                        <?php if ($settings['show_description'] === 'yes'): ?>
                            <div class="coupon-description"><?php echo esc_html($coupon_description); ?></div>
                        <?php endif; ?>
                        <?php if ($coupon_verified || $coupon_uses): ?>
                            <div class="coupon-meta">
                                <?php if ($coupon_verified): ?>
                                    <span class="coupon-verified">Verified</span>
                                <?php endif; ?>
                                <?php if ($coupon_uses): ?>
                                    <span class="coupon-uses"><?php echo esc_html($coupon_uses); ?> uses today</span>
                                <?php endif; ?>
                            </div>
                        <?php endif; ?>
                    </div>
                    <div class="coupon-right">
                        <?php if ($settings['show_button'] === 'yes'): ?>
                            <a href="#" class="coupon-button"><?php echo esc_html($coupon_button_text); ?></a>
                        <?php endif; ?>
                    </div>
                </div>
                <div class="coupon-footer">
                    <a href="#" class="see-details">
                        See Details <span class="details-icon">+</span>
                    </a>
                </div>
                <div class="coupon-details" style="display:none;">
                    <?php echo wp_kses_post($coupon_details); ?>
                </div>
            </div>
            <?php
        }

        private function render_styleless_coupon_item($coupon_id, $settings) {
            $coupon_title = get_field('coupon_title', $coupon_id);
            $coupon_description = get_field('coupon_description', $coupon_id);
            $coupon_selection_button = get_field('coupon_selection_button', $coupon_id) ?: 'SALE';
            $coupon_button_text = get_field('coupon_button_text', $coupon_id) ?: 'Get Deal';
            $coupon_details = get_field('coupon_details', $coupon_id);
            $coupon_verified = get_field('coupon_verified', $coupon_id);
            $coupon_uses = get_field('coupon_uses', $coupon_id);

            ?>
            <div class="coupon-item">
                <?php if ($settings['show_title'] === 'yes'): ?>
                    <h3 class="coupon-title"><?php echo esc_html($coupon_title); ?></h3>
                <?php endif; ?>

                <?php if ($settings['show_description'] === 'yes'): ?>
                    <p class="coupon-description"><?php echo esc_html($coupon_description); ?></p>
                <?php endif; ?>

                <?php if ($settings['show_selection_button'] === 'yes'): ?>
                    <span class="coupon-selection-button"><?php echo esc_html($coupon_selection_button); ?></span>
                <?php endif; ?>

                <?php if ($settings['show_button'] === 'yes'): ?>
                    <a href="#" class="coupon-button"><?php echo esc_html($coupon_button_text); ?></a>
                <?php endif; ?>

                <?php if ($coupon_verified): ?>
                    <span class="coupon-verified">Verified</span>
                <?php endif; ?>

                <?php if ($coupon_uses): ?>
                    <span class="coupon-uses"><?php echo esc_html($coupon_uses); ?> uses today</span>
                <?php endif; ?>

                <a href="#" class="see-details">See Details</a>
                <div class="coupon-details" style="display:none;">
                    <?php echo wp_kses_post($coupon_details); ?>
                </div>
            </div>
            <?php
        }
    }

    \Elementor\Plugin::instance()->widgets_manager->register_widget_type(new Store_Coupons_Widget());
}
add_action('elementor/widgets/widgets_registered', 'register_store_coupons_widget');

// 添加样式
add_action('wp_head', function() {
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
            flex: 0 0 100%;
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
        .retailmenot-template .coupon-title {
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
        .retailmenot-template .coupon-meta {
            font-size: 14px;
            color: #777;
        }
        .retailmenot-template .coupon-verified:after {
            content: " • ";
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
add_action('wp_footer', function() {
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
                        }).first().replaceWith('Hide Details ');
                    } else {
                        $icon.text('+');
                        $this.contents().filter(function() {
                            return this.nodeType === 3;
                        }).first().replaceWith('See Details ');
                    }
                });
            });
        }

        initCouponDetails();

        // 监听 Elementor 前端变化
        $(window).on('elementor/frontend/init', function() {
            elementorFrontend.hooks.addAction('frontend/element_ready/store_coupons.default', function($scope) {
                initCouponDetails();
            });
        });

        $('.store-coupons').on('click', '.coupon-button', function(e) {
            e.preventDefault();
            // 在这里添加您的代码，例如复制优惠码到剪贴板或打开优惠链接
        });
    });
    </script>
    <?php
});