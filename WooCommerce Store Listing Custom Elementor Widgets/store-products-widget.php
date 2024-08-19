if (!defined('ABSPATH')) exit;

add_action('elementor/widgets/widgets_registered', 'register_store_products_widget');

function register_store_products_widget($widgets_manager) {
    class Store_Products_Widget extends \Elementor\Widget_Base {
        public function get_name() { return 'store_products'; }
        public function get_title() { return __('Store Products', 'my-custom-theme'); }
        public function get_icon() { return 'eicon-products-grid'; }
        public function get_categories() { return ['general']; }

        protected function _register_controls() {
            $this->start_controls_section('content_section', [
                'label' => __('Content', 'my-custom-theme'),
                'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
            ]);

            $this->add_control('store', [
                'label' => __('Select Store', 'my-custom-theme'),
                'type' => \Elementor\Controls_Manager::SELECT2,
                'options' => $this->get_stores(),
                'default' => '',
            ]);

            $this->add_control('products_per_page', [
                'label' => __('Products Per Page', 'my-custom-theme'),
                'type' => \Elementor\Controls_Manager::NUMBER,
                'default' => 6,
            ]);

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

            $paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
            $products = new WP_Query([
                'post_type' => 'product',
                'posts_per_page' => $settings['products_per_page'],
                'paged' => $paged,
                'meta_query' => [['key' => '_associated_store', 'value' => $settings['store'], 'compare' => '=']],
            ]);

            if ($products->have_posts()) {
                echo '<div class="store-products">';
                while ($products->have_posts()) {
                    $products->the_post();
                    $this->render_product_item();
                }
                echo '</div>';

                echo '<div class="store-products-pagination">';
                echo paginate_links([
                    'total' => $products->max_num_pages,
                    'current' => $paged,
                    'prev_text' => __('&laquo; Previous', 'my-custom-theme'),
                    'next_text' => __('Next &raquo;', 'my-custom-theme'),
                ]);
                echo '</div>';

                wp_reset_postdata();
            } else {
                echo __('No products found for this store.', 'my-custom-theme');
            }
        }

        private function render_product_item() {
            $product = wc_get_product(get_the_ID());
            ?>
            <div class="product-item">
                <a href="<?php echo esc_url(get_permalink()); ?>">
                    <?php echo $product->get_image(); ?>
                    <h3><?php echo $product->get_name(); ?></h3>
                    <span class="price"><?php echo $product->get_price_html(); ?></span>
                </a>
            </div>
            <?php
        }
    }

    $widgets_manager->register_widget_type(new Store_Products_Widget());
}

add_action('wp_head', 'add_store_products_styles');

function add_store_products_styles() {
    ?>
    <style>
        .store-products { display: grid; grid-template-columns: repeat(3, 1fr); gap: 20px; }
        .product-item { border: 1px solid #ddd; padding: 15px; border-radius: 5px; transition: all 0.3s ease; }
        .product-item:hover { box-shadow: 0 5px 15px rgba(0,0,0,0.1); }
        .product-item a { text-decoration: none; color: inherit; }
        .product-item img { width: 100%; height: auto; border-radius: 4px; }
        .product-item h3 { margin: 10px 0 5px; font-size: 16px; }
        .product-item .price { font-weight: bold; color: #d4a373; }
        .store-products-pagination { margin-top: 20px; text-align: center; }
        .store-products-pagination .page-numbers { display: inline-block; padding: 5px 10px; margin: 0 2px; border: 1px solid #ddd; text-decoration: none; color: #333; }
        .store-products-pagination .page-numbers.current { background-color: #d4a373; color: white; border-color: #d4a373; }
        @media (max-width: 768px) { .store-products { grid-template-columns: repeat(2, 1fr); } }
        @media (max-width: 480px) { .store-products { grid-template-columns: 1fr; } }
    </style>
    <?php
}