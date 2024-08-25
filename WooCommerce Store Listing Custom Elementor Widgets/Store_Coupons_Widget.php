<?php
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
            return __('Dynamic Store Coupons', 'my-custom-theme');
        }

        public function get_icon() {
            return 'eicon-coupon';
        }

        public function get_categories() {
            return ['general'];
        }

        protected function register_controls() {
            // Content Settings
            $this->start_controls_section(
                'content_section',
                [
                    'label' => __('Content Settings', 'my-custom-theme'),
                    'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
                ]
            );

            $this->add_control(
                'store_info',
                [
                    'label' => __('Store Selection', 'my-custom-theme'),
                    'type' => \Elementor\Controls_Manager::RAW_HTML,
                    'raw' => __('Coupons will be displayed dynamically based on the current store page.', 'my-custom-theme'),
                    'content_classes' => 'elementor-descriptor',
                ]
            );

            $this->add_control(
                'template',
                [
                    'label' => __('Template', 'my-custom-theme'),
                    'type' => \Elementor\Controls_Manager::SELECT,
                    'default' => 'retailmenot',
                    'options' => [
                        'retailmenot' => __('RetailMeNot Style', 'my-custom-theme'),
                        'styleless' => __('No Style', 'my-custom-theme'),
                    ],
                ]
            );

            $this->add_control(
                'debug_mode',
                [
                    'label' => __('Debug Mode', 'my-custom-theme'),
                    'type' => \Elementor\Controls_Manager::SWITCHER,
                    'label_on' => __('On', 'my-custom-theme'),
                    'label_off' => __('Off', 'my-custom-theme'),
                    'return_value' => 'yes',
                    'default' => 'no',
                ]
            );

            $this->end_controls_section();

            // Layout Section
            $this->start_controls_section(
                'layout_section',
                [
                    'label' => __('Layout', 'my-custom-theme'),
                    'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
                ]
            );

            $this->add_control(
                'left_items',
                [
                    'label' => __('Left Items', 'my-custom-theme'),
                    'type' => \Elementor\Controls_Manager::REPEATER,
                    'fields' => $this->get_repeater_fields(),
                    'default' => [
                        [
                            'field_key' => 'percentage',
                            'field_type' => 'percentage',
                        ],
                    ],
                    'title_field' => '{{{ field_key }}}',
                ]
            );

            $this->add_control(
                'center_items',
                [
                    'label' => __('Center Items', 'my-custom-theme'),
                    'type' => \Elementor\Controls_Manager::REPEATER,
                    'fields' => $this->get_repeater_fields(),
                    'default' => [
                        [
                            'field_key' => 'coupon_terms_button',
                            'field_type' => 'text',
                        ],
                        [
                            'field_key' => 'title',
                            'field_type' => 'text',
                        ],
                        [
                            'field_key' => 'description',
                            'field_type' => 'text',
                        ],
                    ],
                    'title_field' => '{{{ field_key }}}',
                ]
            );

            $this->add_control(
                'right_items',
                [
                    'label' => __('Right Items', 'my-custom-theme'),
                    'type' => \Elementor\Controls_Manager::REPEATER,
                    'fields' => $this->get_repeater_fields(),
                    'default' => [
                        [
                            'field_key' => 'button_text1',
                            'field_type' => 'button',
                        ],
                    ],
                    'title_field' => '{{{ field_key }}}',
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
                'custom_styles_info',
                [
                    'label' => __('Custom Styles', 'my-custom-theme'),
                    'type' => \Elementor\Controls_Manager::RAW_HTML,
                    'raw' => __('These styles will only apply when using the "No Style" template.', 'my-custom-theme'),
                    'content_classes' => 'elementor-descriptor',
                ]
            );

            $this->add_control(
                'coupon_background_color',
                [
                    'label' => __('Coupon Background Color', 'my-custom-theme'),
                    'type' => \Elementor\Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} .coupon-item.styleless-template' => 'background-color: {{VALUE}};',
                    ],
                    'condition' => [
                        'template' => 'styleless',
                    ],
                ]
            );

            $this->add_group_control(
                \Elementor\Group_Control_Border::get_type(),
                [
                    'name' => 'coupon_border',
                    'label' => __('Coupon Border', 'my-custom-theme'),
                    'selector' => '{{WRAPPER}} .coupon-item.styleless-template',
                    'condition' => [
                        'template' => 'styleless',
                    ],
                ]
            );

            $this->add_control(
                'coupon_border_radius',
                [
                    'label' => __('Coupon Border Radius', 'my-custom-theme'),
                    'type' => \Elementor\Controls_Manager::DIMENSIONS,
                    'size_units' => ['px', '%'],
                    'selectors' => [
                        '{{WRAPPER}} .coupon-item.styleless-template' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                    'condition' => [
                        'template' => 'styleless',
                    ],
                ]
            );

            $this->add_group_control(
                \Elementor\Group_Control_Box_Shadow::get_type(),
                [
                    'name' => 'coupon_box_shadow',
                    'label' => __('Coupon Box Shadow', 'my-custom-theme'),
                    'selector' => '{{WRAPPER}} .coupon-item.styleless-template',
                    'condition' => [
                        'template' => 'styleless',
                    ],
                ]
            );

            $this->add_control(
                'title_color',
                [
                    'label' => __('Title Color', 'my-custom-theme'),
                    'type' => \Elementor\Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} .coupon-item.styleless-template .coupon-title' => 'color: {{VALUE}};',
                    ],
                    'condition' => [
                        'template' => 'styleless',
                    ],
                ]
            );

            $this->add_group_control(
                \Elementor\Group_Control_Typography::get_type(),
                [
                    'name' => 'title_typography',
                    'label' => __('Title Typography', 'my-custom-theme'),
                    'selector' => '{{WRAPPER}} .coupon-item.styleless-template .coupon-title',
                    'condition' => [
                        'template' => 'styleless',
                    ],
                ]
            );

            $this->add_control(
                'description_color',
                [
                    'label' => __('Description Color', 'my-custom-theme'),
                    'type' => \Elementor\Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} .coupon-item.styleless-template .coupon-description' => 'color: {{VALUE}};',
                    ],
                    'condition' => [
                        'template' => 'styleless',
                    ],
                ]
            );

            $this->add_group_control(
                \Elementor\Group_Control_Typography::get_type(),
                [
                    'name' => 'description_typography',
                    'label' => __('Description Typography', 'my-custom-theme'),
                    'selector' => '{{WRAPPER}} .coupon-item.styleless-template .coupon-description',
                    'condition' => [
                        'template' => 'styleless',
                    ],
                ]
            );

            $this->add_control(
                'button_background_color',
                [
                    'label' => __('Button Background Color', 'my-custom-theme'),
                    'type' => \Elementor\Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} .coupon-item.styleless-template .coupon-button' => 'background-color: {{VALUE}};',
                    ],
                    'condition' => [
                        'template' => 'styleless',
                    ],
                ]
            );

            $this->add_control(
                'button_text_color',
                [
                    'label' => __('Button Text Color', 'my-custom-theme'),
                    'type' => \Elementor\Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} .coupon-item.styleless-template .coupon-button' => 'color: {{VALUE}};',
                    ],
                    'condition' => [
                        'template' => 'styleless',
                    ],
                ]
            );

            $this->add_group_control(
                \Elementor\Group_Control_Typography::get_type(),
                [
                    'name' => 'button_typography',
                    'label' => __('Button Typography', 'my-custom-theme'),
                    'selector' => '{{WRAPPER}} .coupon-item.styleless-template .coupon-button',
                    'condition' => [
                        'template' => 'styleless',
                    ],
                ]
            );

            $this->end_controls_section();
        }

        private function get_repeater_fields() {
            $repeater = new \Elementor\Repeater();

            $repeater->add_control(
                'field_key',
                [
                    'label' => __('Field', 'my-custom-theme'),
                    'type' => \Elementor\Controls_Manager::SELECT,
                    'options' => $this->get_coupon_fields(),
                ]
            );

            $repeater->add_control(
                'field_type',
                [
                    'label' => __('Field Type', 'my-custom-theme'),
                    'type' => \Elementor\Controls_Manager::SELECT,
                    'default' => 'text',
                    'options' => [
                        'text' => __('Text', 'my-custom-theme'),
                        'link' => __('Link', 'my-custom-theme'),
                        'image' => __('Image', 'my-custom-theme'),
                        'percentage' => __('Percentage', 'my-custom-theme'),
                        'button' => __('Button', 'my-custom-theme'),
                    ],
                ]
            );

            return $repeater->get_controls();
        }

        protected function render() {
            $settings = $this->get_settings_for_display();
            
            if ($settings['debug_mode'] === 'yes') {
                // In debug mode, display all coupons
                $args = array(
                    'post_type' => 'coupon',
                    'posts_per_page' => -1,
                );
                $coupons = get_posts($args);
            } else {
                // Normal mode: get coupons for the current store
                $store_id = $this->get_current_store_id();
                $coupons = get_field('select_coupon', $store_id);
            }

            if (!$coupons || empty($coupons)) {
                echo __('No coupons found.', 'my-custom-theme');
                return;
            }

            echo '<div class="store-coupons">';
            foreach ($coupons as $coupon) {
                $coupon_id = $settings['debug_mode'] === 'yes' ? $coupon->ID : $coupon;
                $this->render_coupon_item($coupon_id, $settings);
            }
            echo '</div>';
        }

        private function get_current_store_id() {
            if (is_singular('store')) {
                return get_the_ID();
            }
            // Return a default store ID or null if not on a store page
            return null;
        }

        private function render_coupon_item($coupon_id, $settings) {
            $coupon_data = $this->get_coupon_data($coupon_id);
            $template_class = $settings['template'] === 'retailmenot' ? 'retailmenot-template' : 'styleless-template';

            ?>
            <div class="coupon-item <?php echo esc_attr($template_class); ?>">
                <div class="coupon-content">
                    <?php
                    $this->render_coupon_part('left', $settings['left_items'], $coupon_data, $settings);
                    $this->render_coupon_part('center', $settings['center_items'], $coupon_data, $settings);
                    $this->render_coupon_part('right', $settings['right_items'], $coupon_data, $settings);
                    ?>
                </div>
                <?php if (!empty($coupon_data['coupon_terms_accordion'])): ?>
                    <div class="coupon-details">
                        <a href="#" class="see-details">See Details <span class="details-icon">+</span></a>
                        <div class="terms-accordion" style="display: none;">
                        <h4>DETAILS</h4>
                            <?php echo wp_kses_post($coupon_data['coupon_terms_accordion']); ?>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
            <?php
        }

        private function render_coupon_part($part_name, $items, $coupon_data, $settings) {
            echo '<div class="coupon-' . esc_attr($part_name) . '">';
            foreach ($items as $item) {
                $this->render_field($item['field_key'], $item['field_type'], $coupon_data, $settings);
            }
            echo '</div>';
        }

        private function get_coupon_data($coupon_id) {
            $coupon_data = [
                'title' => get_the_title($coupon_id),
                'description' => get_the_content(null, false, $coupon_id),
            ];

            $fields = $this->get_coupon_fields();
            foreach ($fields as $field_key => $field_label) {
                if ($field_key !== 'title' && $field_key !== 'description') {
                    $coupon_data[$field_key] = get_field($field_key, $coupon_id);
                }
            }

            return $coupon_data;
        }

        private function render_field($field_key, $field_type, $coupon_data, $settings) {
            $value = isset($coupon_data[$field_key]) ? $coupon_data[$field_key] : '';
            $style = $settings['template'] === 'styleless' ? $this->get_field_style($field_key, $field_type, $settings) : '';

            switch ($field_type) {
                case 'text':
                    if ($field_key === 'title') {
                        echo '<h3 class="coupon-' . esc_attr($field_key) . '" ' . $style . '>' . esc_html($value) . '</h3>';
                    } elseif ($field_key === 'description') {
                        echo '<div class="coupon-' . esc_attr($field_key) . '" ' . $style . '>' . wp_kses_post($value) . '</div>';
                    } elseif ($field_key === 'coupon_terms_button') {
                        echo '<div class="terms-button">' . esc_html($value) . '</div>';
                    } else {
                        echo '<div class="coupon-' . esc_attr($field_key) . '" ' . $style . '>' . esc_html($value) . '</div>';
                    }
                    break;
                case 'link':
                    echo '<a href="' . esc_url($value) . '" class="coupon-' . esc_attr($field_key) . '" ' . $style . '>' . esc_html($field_key) . '</a>';
                    break;
                case 'image':
                    echo '<img src="' . esc_url($value) . '" alt="' . esc_attr($field_key) . '" class="coupon-' . esc_attr($field_key) . '" ' . $style . '>';
                    break;
                case 'percentage':
                    if (!empty($value)) {
                        echo '<div class="discount-percentage" ' . $style . '>';
                        echo '<span class="up-to">UP TO</span>';
                        echo '<span class="percentage">' . esc_html($value) . '</span>';
                        echo '<span class="off">OFF</span>';
                        echo '</div>';
                    }
                    break;
                case 'button':
                    $link = isset($coupon_data['coupon__Button_Link']) ? $coupon_data['coupon__Button_Link'] : '#';
                    echo '<a href="' . esc_url($link) . '" class="coupon-button" ' . $style . '>' . esc_html($value) . '</a>';
                    break;
            }
        }

        private function get_field_style($field_key, $field_type, $settings) {
            $style = '';

            switch ($field_type) {
                case 'text':
                    if ($field_key === 'title') {
                        $style .= 'color: ' . $settings['title_color'] . ';';
                    } elseif ($field_key === 'description') {
                        $style .= 'color: ' . $settings['description_color'] . ';';
                    }
                    break;
                case 'button':
                    $style .= 'background-color: ' . $settings['button_background_color'] . ';';
                    $style .= 'color: ' . $settings['button_text_color'] . ';';
                    break;
            }

            return $style ? 'style="' . esc_attr($style) . '"' : '';
        }

        private function get_coupon_fields() {
            $fields = [
                'title' => __('Title', 'my-custom-theme'),
                'description' => __('Description', 'my-custom-theme'),
                'percentage' => __('Percentage', 'my-custom-theme'),
                'coupon_terms_button' => __('Coupon Terms Button', 'my-custom-theme'),
                'button_text1' => __('Button Text', 'my-custom-theme'),
            ];
            
            if (function_exists('acf_get_field_groups') && function_exists('acf_get_fields')) {
                $field_groups = acf_get_field_groups(['post_type' => 'coupon']);
                
                if (!empty($field_groups)) {
                    foreach ($field_groups as $field_group) {
                        $group_fields = acf_get_fields($field_group);
                        if (!empty($group_fields)) {
                            foreach ($group_fields as $field) {
                                $fields[$field['name']] = $field['label'];
                            }
                        }
                    }
                }
            }
            
            return $fields;
        }
    }

    \Elementor\Plugin::instance()->widgets_manager->register(new Dynamic_Store_Coupons_Widget());
}

add_action('elementor/widgets/register', 'register_dynamic_store_coupons_widget');

// Add styles
add_action('wp_head', function () {
    ?>
    <style>
    .coupon-item.retailmenot-template {
        font-family: Arial, sans-serif;
        max-width: 800px;
        margin: 0 auto 20px;
        border: 1px solid #e0e0e0;
        border-radius: 4px;
        background-color: #fff;
    }
    .coupon-item.retailmenot-template .coupon-content {
        display: flex;
        align-items: center;
        padding: 20px;
    }
    .coupon-item.retailmenot-template .coupon-left,
    .coupon-item.retailmenot-template .coupon-center,
    .coupon-item.retailmenot-template .coupon-right {
        padding: 10px;
    }
    .coupon-item.retailmenot-template .coupon-left {
        flex: 0 0 auto;
    }
    .coupon-item.retailmenot-template .coupon-center {
        flex: 1;
    }
    .coupon-item.retailmenot-template .coupon-right {
        flex: 0 0 auto;
    }
    .coupon-item.retailmenot-template .discount-percentage {
        text-align: center;
        color: #6c0ab7;
        font-weight: bold;
        border: 2px solid #6c0ab7;
        padding: 5px;
        line-height: 1;
    }
    .coupon-item.retailmenot-template .up-to,
    .coupon-item.retailmenot-template .off {
        font-size: 14px;
        display: block;
    }
    .coupon-item.retailmenot-template .percentage {
        font-size: 36px;
        display: block;
    }
    .coupon-item.retailmenot-template .terms-button {
        display: inline-block;
        background-color: #6c0ab7;
        color: #fff;
        padding: 2px 5px;
        font-size: 12px;
        margin-bottom: 5px;
        border-radius: 25%;
    }
    .coupon-item.retailmenot-template .coupon-title {
        font-size: 18px;
        font-weight: bold;
        margin-bottom: 5px;
        color: #333;
    }
    .coupon-item.retailmenot-template .coupon-description {
        font-size: 14px;
        color: #666;
    }
    .coupon-item.retailmenot-template .coupon-button {
        display: inline-block;
        padding: 10px 20px;
        background-color: #6c0ab7;
        color: #ffffff;
        text-decoration: none;
        border-radius: 4px;
        font-weight: bold;
        transition: background-color 0.3s ease;
    }
    .coupon-item.retailmenot-template .coupon-button:hover {
        background-color: #560994;
    }
    .coupon-item.retailmenot-template .coupon-details {
        border-top: 1px solid #e0e0e0;
        padding: 10px 20px;
    }
    .coupon-item.retailmenot-template .see-details {
        color: #666;
        text-decoration: none;
        font-size: 14px;
    }
    .coupon-item.retailmenot-template .details-icon {
        font-weight: bold;
    }
    .coupon-item.retailmenot-template .terms-accordion {
        margin-top: 10px;
    }
    .coupon-item.retailmenot-template .terms-accordion h4 {
        font-size: 16px;
        margin-bottom: 5px;
    }
    @media (max-width: 767px) {
        .coupon-item.retailmenot-template .coupon-content {
            flex-direction: column;
            align-items: flex-start;
        }
        .coupon-item.retailmenot-template .coupon-left,
        .coupon-item.retailmenot-template .coupon-right,
        .coupon-item.retailmenot-template .coupon-center {
            width: 100%;
            margin: 10px 0;
        }
    }
    </style>
    <?php
});

// Add JavaScript
add_action('wp_footer', function () {
    ?>
    <script>
    jQuery(document).ready(function($) {
        $('.coupon-item').on('click', '.see-details', function(e) {
            e.preventDefault();
            var $this = $(this);
            var $termsAccordion = $this.siblings('.terms-accordion');
            var $icon = $this.find('.details-icon');
            
            $termsAccordion.slideToggle(300, function() {
                if ($termsAccordion.is(':visible')) {
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
    });
    </script>
    <?php
});