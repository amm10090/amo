// 注册自定义 Elementor 小部件
add_action('elementor/widgets/widgets_registered', 'register_store_list_widget');

function register_store_list_widget($widgets_manager) {
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

            $this->end_controls_section();
        }

        protected function render() {
            $settings = $this->get_settings_for_display();

            // 添加排序功能
            echo '<div class="store-sort">
                    <select name="store-sort" id="store-sort">
                        <option value="date">By date</option>
                        <option value="name">By name</option>
                    </select>
                    <button class="sort-button">SORT</button>
                  </div>';

            $args = [
                'post_type' => 'store',
                'posts_per_page' => $settings['posts_per_page'],
            ];

            $stores = new WP_Query($args);

            if ($stores->have_posts()) :
                echo '<div class="store-list">';
                while ($stores->have_posts()) : $stores->the_post();
                    $store_id = get_the_ID();
                    $location = get_post_meta($store_id, 'location', true); // 假设位置存储在 'location' 元字段中
                    ?>
                    <div class="store-item">
                        <div class="store-header">
                            <div class="store-logo"><?php the_post_thumbnail('thumbnail'); ?></div>
                            <div class="store-info">
                                <h2><?php the_title(); ?></h2>
                                <p class="store-location"><?php echo esc_html($location); ?></p>
                            </div>
                            <div class="store-actions">
                                <span class="edit-icon">✎</span>
                                <span class="location-icon">📍</span>
                            </div>
                        </div>
                        <div class="store-products">
                            <h3><?php _e('Top Products', 'my-custom-theme'); ?></h3>
                            <?php
                            $products = new WP_Query([
                                'post_type' => 'product',
                                'posts_per_page' => $settings['products_per_store'],
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
                                    echo '<div class="product-item">' . $product->get_image() . '</div>';
                                endwhile;
                                wp_reset_postdata();
                                echo '</div>';
                            else :
                                _e('No products found', 'my-custom-theme');
                            endif;
                            ?>
                        </div>
                    </div>
                    <?php
                endwhile;
                wp_reset_postdata();
                echo '</div>';
            else :
                _e('No stores found', 'my-custom-theme');
            endif;
        }
    }

    $widgets_manager->register_widget_type(new Improved_Store_List_Widget());
}

// 添加自定义 CSS
add_action('wp_head', 'add_improved_store_list_styles');

function add_improved_store_list_styles() {
    ?>
    <style>
        .store-sort {
            margin-bottom: 20px;
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
        }
        .store-header {
            display: flex;
            align-items: center;
            margin-bottom: 15px;
        }
        .store-logo {
            width: 60px;
            height: 60px;
            margin-right: 15px;
        }
        .store-logo img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            border-radius: 50%;
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
        .store-actions {
            display: flex;
            gap: 10px;
        }
        .edit-icon, .location-icon {
            cursor: pointer;
        }
        .product-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 10px;
        }
        .product-item img {
            width: 100%;
            height: auto;
        }
    </style>
    <?php
}