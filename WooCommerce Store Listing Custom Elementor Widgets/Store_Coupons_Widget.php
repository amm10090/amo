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

            // Field Mapping
            $this->start_controls_section(
                'field_mapping_section',
                [
                    'label' => __('Field Mapping', 'my-custom-theme'),
                    'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
                ]
            );

            $coupon_fields = $this->get_acf_coupon_fields();

            $repeater = new \Elementor\Repeater();

            $repeater->add_control(
                'field_label',
                [
                    'label' => __('Field Label', 'my-custom-theme'),
                    'type' => \Elementor\Controls_Manager::TEXT,
                    'default' => __('New Field', 'my-custom-theme'),
                ]
            );

            $repeater->add_control(
                'field_key',
                [
                    'label' => __('Field Key', 'my-custom-theme'),
                    'type' => \Elementor\Controls_Manager::SELECT,
                    'options' => $coupon_fields,
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
                    ],
                ]
            );

            $this->add_control(
                'coupon_fields',
                [
                    'label' => __('Coupon Fields', 'my-custom-theme'),
                    'type' => \Elementor\Controls_Manager::REPEATER,
                    'fields' => $repeater->get_controls(),
                    'default' => [
                        [
                            'field_label' => __('Discount Percentage', 'my-custom-theme'),
                            'field_key' => 'percentage',
                            'field_type' => 'text',
                        ],
                        [
                            'field_label' => __('Button Text', 'my-custom-theme'),
                            'field_key' => 'button_text1',
                            'field_type' => 'text',
                        ],
                        [
                            'field_label' => __('Button Link', 'my-custom-theme'),
                            'field_key' => 'coupon__Button_Link',
                            'field_type' => 'link',
                        ],
                        [
                            'field_label' => __('Terms Button', 'my-custom-theme'),
                            'field_key' => 'coupon_terms_button',
                            'field_type' => 'text',
                        ],
                        [
                            'field_label' => __('Terms Accordion', 'my-custom-theme'),
                            'field_key' => 'coupon_terms_accordion',
                            'field_type' => 'text',
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
            $coupon_data = [
                'title' => get_the_title($coupon_id),
                'description' => get_the_content(null, false, $coupon_id),
            ];

            foreach ($settings['coupon_fields'] as $field) {
                $field_value = get_field($field['field_key'], $coupon_id);
                $coupon_data[$field['field_key']] = $field_value;
            }

            $template_class = $settings['template'] === 'retailmenot' ? 'retailmenot-template' : 'styleless-template';

            ?>
            <div class="coupon-item <?php echo esc_attr($template_class); ?>">
                <div class="coupon-content">
                    <div class="coupon-left">
                        <?php if (!empty($coupon_data['percentage'])): ?>
                            <div class="discount-percentage">
                                <span class="up-to">UP TO</span>
                                <span class="percentage"><?php echo esc_html($coupon_data['percentage']); ?></span>
                                <span class="off">OFF</span>
                            </div>
                        <?php endif; ?>
                    </div>
                    <div class="coupon-center">
                        <?php if (!empty($coupon_data['coupon_terms_button'])): ?>
                            <div class="terms-button"><?php echo esc_html($coupon_data['coupon_terms_button']); ?></div>
                        <?php endif; ?>
                        <div class="coupon-title"><?php echo esc_html($coupon_data['title']); ?></div>
                        <?php if (!empty($coupon_data['description'])): ?>
                            <div class="coupon-description"><?php echo wp_kses_post($coupon_data['description']); ?></div>
                        <?php endif; ?>
                    </div>
                    <div class="coupon-right">
                        <?php if (!empty($coupon_data['button_text1'])): ?>
                            <a href="<?php echo esc_url($coupon_data['coupon__Button_Link']); ?>" class="coupon-button"><?php echo esc_html($coupon_data['button_text1']); ?></a>
                        <?php endif; ?>
                    </div>
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

        private function get_acf_coupon_fields() {
            $fields = ['' => __('Select Field', 'my-custom-theme')];
            
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
    .coupon-item.retailmenot-template .coupon-left {
        flex: 0 0 auto;
        margin-right: 20px;
    }
    .coupon-item.retailmenot-template .coupon-center {
        flex: 1;
        margin-left: 20px;
    }
    .coupon-item.retailmenot-template .coupon-right {
        flex: 0 0 auto;
        margin-left: 20px;
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
        .coupon-item.retailmenot-template .coupon-right {
            margin: 10px 0;
        }
        .coupon-item.retailmenot-template .coupon-center {
            margin-left: 0;
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