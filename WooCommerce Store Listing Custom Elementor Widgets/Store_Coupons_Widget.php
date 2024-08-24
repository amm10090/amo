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

            $this->end_controls_section();

            // Layout Section
            $this->start_controls_section(
                'layout_section',
                [
                    'label' => __('Layout', 'my-custom-theme'),
                    'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
                ]
            );

            $repeater = new \Elementor\Repeater();

            $repeater->add_control(
                'part_name',
                [
                    'label' => __('Part Name', 'my-custom-theme'),
                    'type' => \Elementor\Controls_Manager::SELECT,
                    'default' => 'left',
                    'options' => [
                        'left' => __('Left', 'my-custom-theme'),
                        'center' => __('Center', 'my-custom-theme'),
                        'right' => __('Right', 'my-custom-theme'),
                    ],
                ]
            );

            $repeater->add_control(
                'fields',
                [
                    'label' => __('Fields', 'my-custom-theme'),
                    'type' => \Elementor\Controls_Manager::REPEATER,
                    'fields' => [
                        [
                            'name' => 'field_key',
                            'label' => __('Field', 'my-custom-theme'),
                            'type' => \Elementor\Controls_Manager::SELECT,
                            'options' => $this->get_coupon_fields(),
                        ],
                        [
                            'name' => 'field_type',
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
                        ],
                    ],
                ]
            );

            $this->add_control(
                'layout_parts',
                [
                    'label' => __('Layout Parts', 'my-custom-theme'),
                    'type' => \Elementor\Controls_Manager::REPEATER,
                    'fields' => $repeater->get_controls(),
                    'default' => [
                        [
                            'part_name' => 'left',
                            'fields' => [
                                ['field_key' => 'percentage', 'field_type' => 'percentage'],
                            ],
                        ],
                        [
                            'part_name' => 'center',
                            'fields' => [
                                ['field_key' => 'coupon_terms_button', 'field_type' => 'text'],
                                ['field_key' => 'title', 'field_type' => 'text'],
                                ['field_key' => 'description', 'field_type' => 'text'],
                            ],
                        ],
                        [
                            'part_name' => 'right',
                            'fields' => [
                                ['field_key' => 'button_text1', 'field_type' => 'button'],
                            ],
                        ],
                    ],
                    'title_field' => '{{{ part_name }}}',
                ]
            );

            $this->end_controls_section();
        }

        protected function render() {
            $settings = $this->get_settings_for_display();
            $store_id = $this->get_current_store_id();

            if (!$store_id) {
                echo __('This widget can only be used on store pages.', 'my-custom-theme');
                return;
            }

            $coupons = get_field('select_coupon', $store_id);

            if (!$coupons || empty($coupons)) {
                echo __('No coupons found for this store.', 'my-custom-theme');
                return;
            }

            echo '<div class="store-coupons">';
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
            $coupon_data = $this->get_coupon_data($coupon_id);
            $template_class = $settings['template'] === 'retailmenot' ? 'retailmenot-template' : 'styleless-template';

            ?>
            <div class="coupon-item <?php echo esc_attr($template_class); ?>">
                <div class="coupon-content">
                    <?php
                    foreach ($settings['layout_parts'] as $part) {
                        echo '<div class="coupon-' . esc_attr($part['part_name']) . '">';
                        foreach ($part['fields'] as $field) {
                            $this->render_field($field['field_key'], $field['field_type'], $coupon_data);
                        }
                        echo '</div>';
                    }
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

        private function render_field($field_key, $field_type, $coupon_data) {
            $value = isset($coupon_data[$field_key]) ? $coupon_data[$field_key] : '';

            switch ($field_type) {
                case 'text':
                    if ($field_key === 'title') {
                        echo '<h3 class="coupon-' . esc_attr($field_key) . '">' . esc_html($value) . '</h3>';
                    } elseif ($field_key === 'description') {
                        echo '<div class="coupon-' . esc_attr($field_key) . '">' . wp_kses_post($value) . '</div>';
                    } else {
                        echo '<div class="coupon-' . esc_attr($field_key) . '">' . esc_html($value) . '</div>';
                    }
                    break;
                case 'link':
                    echo '<a href="' . esc_url($value) . '" class="coupon-' . esc_attr($field_key) . '">' . esc_html($field_key) . '</a>';
                    break;
                case 'image':
                    echo '<img src="' . esc_url($value) . '" alt="' . esc_attr($field_key) . '" class="coupon-' . esc_attr($field_key) . '">';
                    break;
                case 'percentage':
                    if (!empty($value)) {
                        echo '<div class="discount-percentage">';
                        echo '<span class="up-to">UP TO</span>';
                        echo '<span class="percentage">' . esc_html($value) . '</span>';
                        echo '<span class="off">OFF</span>';
                        echo '</div>';
                    }
                    break;
                case 'button':
                    $link = isset($coupon_data['coupon__Button_Link']) ? $coupon_data['coupon__Button_Link'] : '#';
                    echo '<a href="' . esc_url($link) . '" class="coupon-button">' . esc_html($value) . '</a>';
                    break;
                // Add more field types as needed
            }
        }

        private function get_coupon_fields() {
            $fields = [
                'title' => __('Title', 'my-custom-theme'),
                'description' => __('Description', 'my-custom-theme'),
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
        $('.coupon-item.retailmenot-template').on('click', '.see-details', function(e) {
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