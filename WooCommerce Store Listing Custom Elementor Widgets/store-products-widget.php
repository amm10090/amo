if (!defined('ABSPATH')) exit; // Exit if accessed directly

// Register Dynamic Store Products Widget
add_action('elementor/widgets/widgets_registered', 'register_dynamic_store_products_widget');

function register_dynamic_store_products_widget($widgets_manager) {
    class Dynamic_Store_Products_Widget extends \Elementor\Widget_Base {
        public function get_name() { return 'dynamic_store_products'; }
        public function get_title() { return __('Dynamic Store Products', 'my-custom-theme'); }
        public function get_icon() { return 'eicon-products-grid'; }
        public function get_categories() { return ['general']; }

        protected function _register_controls() {
            $this->start_controls_section(
                'content_section',
                [
                    'label' => __('Content', 'my-custom-theme'),
                    'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
                ]
            );

            $this->add_control(
                'store_info',
                [
                    'label' => __('Store Selection', 'my-custom-theme'),
                    'type' => \Elementor\Controls_Manager::RAW_HTML,
                    'raw' => __('Products will be dynamically displayed based on the current store page.', 'my-custom-theme'),
                    'content_classes' => 'elementor-descriptor',
                ]
            );

            $this->add_control(
                'products_per_page',
                [
                    'label' => __('Products Per Page', 'my-custom-theme'),
                    'type' => \Elementor\Controls_Manager::NUMBER,
                    'default' => 6,
                ]
            );

            $this->add_control(
                'layout_style',
                [
                    'label' => __('Layout Style', 'my-custom-theme'),
                    'type' => \Elementor\Controls_Manager::SELECT,
                    'default' => 'grid',
                    'options' => [
                        'grid' => __('Grid', 'my-custom-theme'),
                        'list' => __('List', 'my-custom-theme'),
                    ],
                    'prefix_class' => 'product-layout-',
                ]
            );

            $this->add_responsive_control(
                'columns',
                [
                    'label' => __('Columns', 'my-custom-theme'),
                    'type' => \Elementor\Controls_Manager::SELECT,
                    'default' => '3',
                    'tablet_default' => '2',
                    'mobile_default' => '1',
                    'options' => [
                        '1' => '1',
                        '2' => '2',
                        '3' => '3',
                        '4' => '4',
                        '5' => '5',
                        '6' => '6',
                    ],
                    'prefix_class' => 'elementor-grid%s-',
                    'selectors' => [
                        '{{WRAPPER}} .store-products' => 'grid-template-columns: repeat({{VALUE}}, 1fr);',
                    ],
                    'condition' => [
                        'layout_style' => 'grid',
                    ],
                ]
            );

            $this->add_control(
                'show_product_name',
                [
                    'label' => __('Show Product Name', 'my-custom-theme'),
                    'type' => \Elementor\Controls_Manager::SWITCHER,
                    'label_on' => __('Show', 'my-custom-theme'),
                    'label_off' => __('Hide', 'my-custom-theme'),
                    'return_value' => 'yes',
                    'default' => 'yes',
                ]
            );

            $this->add_control(
                'show_product_price',
                [
                    'label' => __('Show Product Price', 'my-custom-theme'),
                    'type' => \Elementor\Controls_Manager::SWITCHER,
                    'label_on' => __('Show', 'my-custom-theme'),
                    'label_off' => __('Hide', 'my-custom-theme'),
                    'return_value' => 'yes',
                    'default' => 'yes',
                ]
            );

            $this->add_control(
                'show_product_rating',
                [
                    'label' => __('Show Product Rating', 'my-custom-theme'),
                    'type' => \Elementor\Controls_Manager::SWITCHER,
                    'label_on' => __('Show', 'my-custom-theme'),
                    'label_off' => __('Hide', 'my-custom-theme'),
                    'return_value' => 'yes',
                    'default' => 'yes',
                ]
            );

            $this->add_control(
                'show_product_description',
                [
                    'label' => __('Show Product Description', 'my-custom-theme'),
                    'type' => \Elementor\Controls_Manager::SWITCHER,
                    'label_on' => __('Show', 'my-custom-theme'),
                    'label_off' => __('Hide', 'my-custom-theme'),
                    'return_value' => 'yes',
                    'default' => 'yes',
                ]
            );

            $this->add_control(
                'show_pagination',
                [
                    'label' => __('Show Pagination', 'my-custom-theme'),
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
                    'label' => __('Product Style', 'my-custom-theme'),
                    'tab' => \Elementor\Controls_Manager::TAB_STYLE,
                ]
            );

            $this->add_control(
                'product_background_color',
                [
                    'label' => __('Background Color', 'my-custom-theme'),
                    'type' => \Elementor\Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} .product-item' => 'background-color: {{VALUE}};',
                    ],
                ]
            );

            $this->add_group_control(
                \Elementor\Group_Control_Border::get_type(),
                [
                    'name' => 'product_border',
                    'label' => __('Border', 'my-custom-theme'),
                    'selector' => '{{WRAPPER}} .product-item',
                ]
            );

            $this->add_control(
                'product_border_radius',
                [
                    'label' => __('Border Radius', 'my-custom-theme'),
                    'type' => \Elementor\Controls_Manager::DIMENSIONS,
                    'size_units' => ['px', '%'],
                    'selectors' => [
                        '{{WRAPPER}} .product-item' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                ]
            );

            $this->add_control(
                'product_padding',
                [
                    'label' => __('Padding', 'my-custom-theme'),
                    'type' => \Elementor\Controls_Manager::DIMENSIONS,
                    'size_units' => ['px', 'em', '%'],
                    'selectors' => [
                        '{{WRAPPER}} .product-item' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                ]
            );

            $this->add_control(
                'product_spacing',
                [
                    'label' => __('Product Spacing', 'my-custom-theme'),
                    'type' => \Elementor\Controls_Manager::SLIDER,
                    'size_units' => ['px'],
                    'range' => [
                        'px' => [
                            'min' => 0,
                            'max' => 100,
                            'step' => 1,
                        ],
                    ],
                    'default' => [
                        'unit' => 'px',
                        'size' => 20,
                    ],
                    'selectors' => [
                        '{{WRAPPER}} .store-products' => 'gap: {{SIZE}}{{UNIT}};',
                    ],
                ]
            );

            $this->add_control(
                'image_size',
                [
                    'label' => __('Image Size', 'my-custom-theme'),
                    'type' => \Elementor\Controls_Manager::SLIDER,
                    'size_units' => ['px', '%'],
                    'range' => [
                        'px' => [
                            'min' => 0,
                            'max' => 1000,
                            'step' => 5,
                        ],
                        '%' => [
                            'min' => 0,
                            'max' => 100,
                        ],
                    ],
                    'default' => [
                        'unit' => '%',
                        'size' => 100,
                    ],
                    'selectors' => [
                        '{{WRAPPER}} .product-item img' => 'width: {{SIZE}}{{UNIT}}; height: auto;',
                    ],
                ]
            );

            $this->add_control(
                'product_title_color',
                [
                    'label' => __('Title Color', 'my-custom-theme'),
                    'type' => \Elementor\Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} .product-item h3' => 'color: {{VALUE}};',
                    ],
                    'condition' => [
                        'show_product_name' => 'yes',
                    ],
                ]
            );

            $this->add_group_control(
                \Elementor\Group_Control_Typography::get_type(),
                [
                    'name' => 'product_title_typography',
                    'label' => __('Title Typography', 'my-custom-theme'),
                    'selector' => '{{WRAPPER}} .product-item h3',
                    'condition' => [
                        'show_product_name' => 'yes',
                    ],
                ]
            );

            $this->add_control(
                'product_price_color',
                [
                    'label' => __('Price Color', 'my-custom-theme'),
                    'type' => \Elementor\Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} .product-item .price' => 'color: {{VALUE}};',
                    ],
                    'condition' => [
                        'show_product_price' => 'yes',
                    ],
                ]
            );

            $this->add_group_control(
                \Elementor\Group_Control_Typography::get_type(),
                [
                    'name' => 'product_price_typography',
                    'label' => __('Price Typography', 'my-custom-theme'),
                    'selector' => '{{WRAPPER}} .product-item .price',
                    'condition' => [
                        'show_product_price' => 'yes',
                    ],
                ]
            );

            $this->end_controls_section();

            // Pagination Style Section
            $this->start_controls_section(
                'pagination_style_section',
                [
                    'label' => __('Pagination Style', 'my-custom-theme'),
                    'tab' => \Elementor\Controls_Manager::TAB_STYLE,
                    'condition' => [
                        'show_pagination' => 'yes',
                    ],
                ]
            );

            $this->add_control(
                'pagination_color',
                [
                    'label' => __('Text Color', 'my-custom-theme'),
                    'type' => \Elementor\Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} .store-products-pagination .page-numbers' => 'color: {{VALUE}};',
                    ],
                ]
            );

            $this->add_control(
                'pagination_background_color',
                [
                    'label' => __('Background Color', 'my-custom-theme'),
                    'type' => \Elementor\Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} .store-products-pagination .page-numbers' => 'background-color: {{VALUE}};',
                    ],
                ]
            );

            $this->add_group_control(
                \Elementor\Group_Control_Typography::get_type(),
                [
                    'name' => 'pagination_typography',
                    'label' => __('Typography', 'my-custom-theme'),
                    'selector' => '{{WRAPPER}} .store-products-pagination .page-numbers',
                ]
            );

            $this->add_control(
                'pagination_padding',
                [
                    'label' => __('Padding', 'my-custom-theme'),
                    'type' => \Elementor\Controls_Manager::DIMENSIONS,
                    'size_units' => ['px', 'em', '%'],
                    'selectors' => [
                        '{{WRAPPER}} .store-products-pagination .page-numbers' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                ]
            );

            $this->add_control(
                'pagination_border_radius',
                [
                    'label' => __('Border Radius', 'my-custom-theme'),
                    'type' => \Elementor\Controls_Manager::DIMENSIONS,
                    'size_units' => ['px', '%'],
                    'selectors' => [
                        '{{WRAPPER}} .store-products-pagination .page-numbers' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                ]
            );

            $this->end_controls_section();
        }

        protected function render() {
            $settings = $this->get_settings_for_display();

            $store_id = $this->get_current_store_id();

            if (!$store_id) {
                echo __('This widget can only be used on a store page.', 'my-custom-theme');
                return;
            }

            $paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
            $products = new WP_Query([
                'post_type' => 'product',
                'posts_per_page' => $settings['products_per_page'],
                'paged' => $paged,
                'meta_query' => [['key' => '_associated_store', 'value' => $store_id, 'compare' => '=']],
            ]);

            if ($products->have_posts()) {
                echo '<div class="store-products elementor-grid">';
                while ($products->have_posts()) {
                    $products->the_post();
                    $this->render_product_item($settings);
                }
                echo '</div>';

                if ('yes' === $settings['show_pagination']) {
                    echo '<div class="store-products-pagination">';
                    echo paginate_links([
                        'total' => $products->max_num_pages,
                        'current' => $paged,
                        'prev_text' => __('&laquo; Previous', 'my-custom-theme'),
                        'next_text' => __('Next &raquo;', 'my-custom-theme'),
                    ]);
                    echo '</div>';
                }

                wp_reset_postdata();
            } else {
                echo __('No products found for this store.', 'my-custom-theme');
            }
        }

        private function get_current_store_id() {
            // 根据您的网站结构来实现这个方法
            // 例如，如果您在商店页面使用自定义文章类型：
            if (is_singular('store')) {
                return get_the_ID();
            }
            
            // 或者，如果您使用类别来组织商店：
            // if (is_tax('store_category')) {
            //     $term = get_queried_object();
            //     return $term->term_id;
            // }

            // 如果都不是，返回 null
            return null;
        }

        private function render_product_item($settings) {
            $product = wc_get_product(get_the_ID());
            $product_url = get_permalink();
            ?>
            <div class="product-item">
                <a href="<?php echo esc_url($product_url); ?>">
                    <?php echo $product->get_image(); ?>
                    <?php if ('yes' === $settings['show_product_name']) : ?>
                        <h3><?php echo $product->get_name(); ?></h3>
                    <?php endif; ?>
                    <?php if ('yes' === $settings['show_product_price']) : ?>
                        <span class="price"><?php echo $product->get_price_html(); ?></span>
                    <?php endif; ?>
                    <?php if ('yes' === $settings['show_product_rating']) : ?>
                        <div class="star-rating"><?php echo wc_get_rating_html($product->get_average_rating()); ?></div>
                    <?php endif; ?>
                    <?php if ('yes' === $settings['show_product_description']) : ?>
                        <p class="product-short-description"><?php echo wp_trim_words($product->get_short_description(), 20); ?></p>
                    <?php endif; ?>
                </a>
            </div>
            <?php
        }
    }

    $widgets_manager->register_widget_type(new Dynamic_Store_Products_Widget());
}

// Add layout styles
add_action('wp_head', 'add_dynamic_store_products_layout_styles');

function add_dynamic_store_products_layout_styles() {
    ?>
    <style>
        .store-products {
            display: grid;
            gap: 20px;
        }

        .product-layout-list .store-products {
            grid-template-columns: 1fr !important;
        }

        .product-item {
            padding: 15px;
            transition: all 0.3s ease;
            text-align: center;
        }

        .product-item:hover {
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        }

        .product-item a {
            text-decoration: none;
            color: inherit;
            display: block;
        }

        .product-item img {
            width: 100%;
            height: auto;
            border-radius: 4px;
            margin-bottom: 10px;
            transition: transform 0.3s ease;
        }

        .product-item:hover img {
            transform: scale(1.05);
        }

        .product-item h3 {
            margin: 10px 0 5px;
            font-size: 18px;
        }

        .product-item .price {
            display: block;
            margin-bottom: 10px;
            font-weight: bold;
            color: #333;
        }

        .product-item .star-rating {
            margin: 5px auto;
        }

        .product-item .product-short-description {
            font-size: 14px;
            color: #666;
            margin-bottom: 10px;
        }

        .store-products-pagination {
            margin-top: 20px;
            text-align: center;
        }

        .store-products-pagination .page-numbers {
            display: inline-block;
            padding: 5px 10px;
            margin: 0 2px;
            text-decoration: none;
            background-color: #f0f0f0;
            color: #333;
            border-radius: 3px;
        }

        .store-products-pagination .page-numbers.current {
            background-color: #333;
            color: #fff;
        }

        @media (max-width: 767px) {
            .store-products {
                grid-template-columns: repeat(2, 1fr) !important;
            }
        }

        @media (max-width: 480px) {
            .store-products {
                grid-template-columns: 1fr !important;
            }
        }
    </style>
    <?php
}