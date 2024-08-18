// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

// Register custom Elementor widget
add_action('elementor/widgets/widgets_registered', 'register_improved_store_list_widget');

function register_improved_store_list_widget($widgets_manager) {
    class Improved_Store_List_Widget extends \Elementor\Widget_Base {

        public function get_name() {
            return 'improved_store_list';
        }

        public function get_title() {
            return __('Improved Store List', 'my-custom-theme');
        }

        public function get_icon() {
            return 'eicon-posts-grid';
        }

        public function get_categories() {
            return ['general'];
        }

        protected function _register_controls() {
            $this->start_controls_section(
                'content_section',
                [
                    'label' => __('Content', 'my-custom-theme'),
                    'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
                ]
            );

            $this->add_control(
                'posts_per_page',
                [
                    'label' => __('Stores Per Page', 'my-custom-theme'),
                    'type' => \Elementor\Controls_Manager::NUMBER,
                    'default' => 6,
                ]
            );

            $this->add_control(
                'products_per_store',
                [
                    'label' => __('Products Per Store', 'my-custom-theme'),
                    'type' => \Elementor\Controls_Manager::NUMBER,
                    'default' => 3,
                ]
            );

            $this->add_control(
                'logo_width',
                [
                    'label' => __('Logo Container Width', 'my-custom-theme'),
                    'type' => \Elementor\Controls_Manager::SLIDER,
                    'size_units' => ['px'],
                    'range' => [
                        'px' => [
                            'min' => 30,
                            'max' => 200,
                            'step' => 1,
                        ],
                    ],
                    'default' => [
                        'unit' => 'px',
                        'size' => 80,
                    ],
                    'selectors' => [
                        '{{WRAPPER}} .store-logo' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
                    ],
                ]
            );

            $this->end_controls_section();
        }

        protected function render() {
            try {
                $settings = $this->get_settings_for_display();

                echo '<div class="store-sort">
                        <div class="store-sort-select">
                            <select name="store-sort" id="store-sort">
                                <option value="">Sort stores by</option>
                                <option value="date">Date</option>
                                <option value="name">Name</option>
                            </select>
                        </div>
                        <button class="sort-button" type="button">SORT</button>
                      </div>';

                $args = [
                    'post_type' => 'store',
                    'posts_per_page' => $settings['posts_per_page'],
                ];

                $stores = new WP_Query($args);

                if ($stores->have_posts()) :
                    echo '<div class="store-list" id="store-list">';
                    while ($stores->have_posts()) : $stores->the_post();
                        $this->render_store_item($settings);
                    endwhile;
                    wp_reset_postdata();
                    echo '</div>';
                else :
                    _e('No stores found', 'my-custom-theme');
                endif;

                $this->render_sorting_script();
            } catch (Exception $e) {
                error_log('Error in Improved_Store_List_Widget render: ' . $e->getMessage());
                echo 'An error occurred while rendering the store list. Please check the error log.';
            }
        }

        private function render_store_item($settings) {
            try {
                $store_id = get_the_ID();
                $location = get_post_meta($store_id, 'location', true);
                $logo_id = get_post_thumbnail_id($store_id);
                $logo_src = wp_get_attachment_image_src($logo_id, 'full');
                
                $logo_width = $logo_src[1] ?? 0;
                $logo_height = $logo_src[2] ?? 0;
                
                $aspect_ratio = ($logo_height != 0) ? $logo_width / $logo_height : 0;
                
                $logo_class = ($aspect_ratio < 0.5 && $aspect_ratio > 0) ? 'narrow-logo' : '';
                
                ?>
                <div class="store-item" data-date="<?php echo get_the_date('Y-m-d'); ?>" data-name="<?php echo esc_attr(get_the_title()); ?>">
                    <div class="store-header">
                        <div class="store-logo <?php echo esc_attr($logo_class); ?>">
                            <?php echo wp_get_attachment_image($logo_id, 'thumbnail'); ?>
                        </div>
                        <div class="store-info">
                            <h2><?php the_title(); ?></h2>
                            <p class="store-location"><?php echo esc_html($location); ?></p>
                        </div>
                    </div>
                    <div class="store-products">
                        <h3><?php _e('Top Products', 'my-custom-theme'); ?></h3>
                        <?php $this->render_store_products($store_id, $settings['products_per_store']); ?>
                    </div>
                </div>
                <?php
            } catch (Exception $e) {
                error_log('Error in render_store_item: ' . $e->getMessage());
                echo 'An error occurred while rendering a store item.';
            }
        }

        private function render_store_products($store_id, $products_per_store) {
            $products = new WP_Query([
                'post_type' => 'product',
                'posts_per_page' => $products_per_store,
                'meta_query' => [
                    [
                        'key' => '_associated_store',
                        'value' => $store_id,
                        'compare' => '=',
                    ],
                ],
            ]);

            if ($products->have_posts()) :
                echo '<div class="product-grid">';
                while ($products->have_posts()) : $products->the_post();
                    $product = wc_get_product(get_the_ID());
                    $product_link = get_permalink($product->get_id());
                    echo '<div class="product-item">';
                    echo '<a href="' . esc_url($product_link) . '">' . $product->get_image() . '</a>';
                    echo '</div>';
                endwhile;
                wp_reset_postdata();
                echo '</div>';
            else :
                _e('No products found', 'my-custom-theme');
            endif;
        }

        private function render_sorting_script() {
            ?>
            <script>
            jQuery(document).ready(function($) {
                $('.sort-button').on('click', function() {
                    var sortBy = $('#store-sort').val();
                    var $storeList = $('#store-list');
                    var $stores = $storeList.children('.store-item').get();
                    
                    $stores.sort(function(a, b) {
                        var aValue = $(a).data(sortBy);
                        var bValue = $(b).data(sortBy);
                        if (sortBy === 'date') {
                            return new Date(bValue) - new Date(aValue);
                        } else {
                            return aValue.localeCompare(bValue);
                        }
                    });
                    
                    $.each($stores, function(idx, item) { $storeList.append(item); });
                });
            });
            </script>
            <?php
        }
    }

    $widgets_manager->register_widget_type(new Improved_Store_List_Widget());
}

// Add custom CSS
add_action('wp_head', 'add_improved_store_list_styles');

function add_improved_store_list_styles() {
    ?>
    <style>
        .store-sort {
            display: flex;
            align-items: center;
            margin-bottom: 20px;
            max-width: 400px;
            width: 100%;
        }
        .store-sort-select {
            position: relative;
            flex-grow: 1;
        }
        #store-sort {
            appearance: none;
            -webkit-appearance: none;
            -moz-appearance: none;
            width: 100%;
            padding: 10px 15px;
            padding-right: 30px;
            border: 1px solid #d4a373;
            border-radius: 5px 0 0 5px;
            font-size: 16px;
            background-color: #fff;
            color: #333;
            cursor: pointer;
            outline: none;
            transition: all 0.3s ease;
        }
        #store-sort::-ms-expand {
            display: none;
        }
        #store-sort:hover, #store-sort:focus {
            border-color: #c28f5c;
            box-shadow: 0 0 0 2px rgba(210, 163, 115, 0.2);
        }
        #store-sort:hover + .store-sort-select::after,
        #store-sort:focus + .store-sort-select::after {
            color: #c28f5c;
        }
        .sort-button {
            background-color: #d4a373;
            color: white;
            border: none;
            padding: 11px 20px;
            font-size: 16px;
            font-weight: bold;
            border-radius: 0 5px 5px 0;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }
        .sort-button:hover {
            background-color: #c28f5c;
        }
        .store-list {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 20px;
        }
        .store-item {
            border: 1px solid #ddd;
            padding: 15px;
            border-radius: 5px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
            transition: all 0.3s ease;
        }
        .store-item:hover {
            box-shadow: 0 5px 15px rgba(0,0,0,0.2);
            border-color: #bbb;
        }
        .store-header {
            display: flex;
            align-items: center;
            margin-bottom: 15px;
        }
        .store-logo {
            margin-right: 15px;
            overflow: hidden;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .store-logo img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            object-position: center;
            border-radius: 50%;
        }
        .store-logo.narrow-logo img {
            width: auto;
            height: 100%;
        }
        .store-info {
            flex-grow: 1;
        }
        .store-info h2 {
            margin: 0;
            font-size: 18px;
        }
        .store-location {
            font-size: 14px;
            color: #666;
        }
        .product-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 10px;
        }
        .product-item {
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }
        .product-item:hover {
            transform: scale(1.05);
            box-shadow: 0 4px 10px rgba(0,0,0,0.1);
        }
        .product-item img {
            width: 100%;
            height: auto;
            border-radius: 4px;
        }
        @media (max-width: 768px) {
            .store-sort {
                flex-direction: column;
                align-items: stretch;
            }
            #store-sort, .sort-button {
                border-radius: 5px;
                margin-bottom: 10px;
            }
            .store-list {
                grid-template-columns: repeat(2, 1fr);
            }
        }
        @media (max-width: 480px) {
            .store-list {
                grid-template-columns: 1fr;
            }
        }
    </style>
    <?php
}