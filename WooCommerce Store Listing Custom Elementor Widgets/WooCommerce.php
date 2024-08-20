if (!function_exists('initialize_woocommerce_store_integration')) {
    function initialize_woocommerce_store_integration() {
        // Add a "Store" column to the WooCommerce product list
        if (!function_exists('add_store_column_to_products')) {
            function add_store_column_to_products($columns) {
                $columns['store'] = 'Store'; // Add "Store" column
                return $columns;
            }
        }
        add_filter('manage_product_posts_columns', 'add_store_column_to_products');

        // Populate the "Store" column in the WooCommerce product list
        if (!function_exists('populate_store_column')) {
            function populate_store_column($column, $post_id) {
                if ($column == 'store') {
                    $store_id = get_post_meta($post_id, '_associated_store', true);
                    if ($store_id) {
                        $store_name = get_the_title($store_id);
                        $edit_link = admin_url('post.php?post=' . $store_id . '&action=edit');
                        echo '<a href="' . esc_url($edit_link) . '">' . esc_html($store_name) . '</a>';
                    } else {
                        echo 'No store assigned';
                    }
                }
            }
        }
        add_action('manage_product_posts_custom_column', 'populate_store_column', 10, 2);

        // Add a product count column to the "Store" custom post type
        if (!function_exists('add_products_column_to_store')) {
            function add_products_column_to_store($columns) {
                $columns['product_count'] = 'Products';
                return $columns;
            }
        }
        add_filter('manage_store_posts_columns', 'add_products_column_to_store');

        // Populate the product count column in the "Store" custom post type
        if (!function_exists('populate_products_column_in_store')) {
            function populate_products_column_in_store($column, $post_id) {
                if ($column == 'product_count') {
                    $products_count = new WP_Query(array(
                        'post_type' => 'product',
                        'meta_query' => array(
                            array(
                                'key' => '_associated_store',
                                'value' => $post_id,
                                'compare' => '='
                            )
                        ),
                        'posts_per_page' => -1,
                        'fields' => 'ids'
                    ));
                    $count = $products_count->found_posts;
                    $link = admin_url('edit.php?post_type=product&filter_by_store=' . $post_id);
                    echo '<a href="' . esc_url($link) . '">' . esc_html($count) . '</a>';
                }
            }
        }
        add_action('manage_store_posts_custom_column', 'populate_products_column_in_store', 10, 2);

        // Add a custom meta box to WooCommerce product pages for selecting a store
        if (!function_exists('add_store_meta_box')) {
            function add_store_meta_box() {
                add_meta_box(
                    'store_meta_box',
                    'Store',
                    'display_store_meta_box',
                    'product',
                    'side',
                    'high'
                );
            }
        }
        add_action('add_meta_boxes', 'add_store_meta_box');

        if (!function_exists('display_store_meta_box')) {
            function display_store_meta_box($post) {
                $stores = get_posts(array(
                    'post_type' => 'store',
                    'posts_per_page' => -1
                ));
                $selected_store = get_post_meta($post->ID, '_associated_store', true);
                echo '<label for="associated_store">Select Store:</label>';
                echo '<select name="associated_store" id="associated_store">';
                echo '<option value="">Please select a store</option>';
                foreach ($stores as $store) {
                    echo '<option value="' . esc_attr($store->ID) . '" ' . selected($selected_store, $store->ID, false) . '>' . esc_html($store->post_title) . '</option>';
                }
                echo '</select>';
            }
        }

        // Save the selected store data
        if (!function_exists('save_store_meta_box')) {
            function save_store_meta_box($post_id) {
                if (isset($_POST['associated_store'])) {
                    update_post_meta($post_id, '_associated_store', sanitize_text_field($_POST['associated_store']));
                }
            }
        }
        add_action('save_post', 'save_store_meta_box');

        // Add a store filter to the product list
        if (!function_exists('add_store_filter_to_products_list')) {
            function add_store_filter_to_products_list() {
                global $typenow;
                if ($typenow == 'product') {
                    $stores = get_posts(array(
                        'post_type' => 'store',
                        'posts_per_page' => -1
                    ));
                    echo '<select name="filter_by_store" id="filter_by_store">';
                    echo '<option value="">Filter by Store</option>';
                    foreach ($stores as $store) {
                        $selected = (isset($_GET['filter_by_store']) && $_GET['filter_by_store'] == $store->ID) ? 'selected="selected"' : '';
                        echo '<option value="' . esc_attr($store->ID) . '" ' . $selected . '>' . esc_html($store->post_title) . '</option>';
                    }
                    echo '</select>';
                }
            }
        }
        add_action('restrict_manage_posts', 'add_store_filter_to_products_list');

        // Modify the query to filter by the selected store
        if (!function_exists('filter_products_by_store')) {
            function filter_products_by_store($query) {
                global $pagenow;
                $qv = &$query->query_vars;
                if ($pagenow == 'edit.php' && isset($qv['post_type']) && $qv['post_type'] == 'product' && isset($_GET['filter_by_store']) && $_GET['filter_by_store'] != '') {
                    $store_id = $_GET['filter_by_store'];
                    $query->query_vars['meta_query'][] = array(
                        'key' => '_associated_store',
                        'value' => $store_id,
                        'compare' => '='
                    );
                }
            }
        }
        add_filter('parse_query', 'filter_products_by_store');

        // Add quick edit functionality for store selection
        if (!function_exists('add_quick_edit_store_field')) {
            function add_quick_edit_store_field($column_name, $post_type) {
                if ($column_name != 'store' || $post_type != 'product') return;
                ?>
                <fieldset class="inline-edit-col-right">
                    <div class="inline-edit-col">
                        <label>
                            <span class="title">Store</span>
                            <select name="associated_store" id="associated_store">
                                <option value="">Select Store</option>
                                <?php
                                $stores = get_posts(array('post_type' => 'store', 'posts_per_page' => -1));
                                foreach ($stores as $store) {
                                    echo '<option value="' . esc_attr($store->ID) . '">' . esc_html($store->post_title) . '</option>';
                                }
                                ?>
                            </select>
                        </label>
                    </div>
                </fieldset>
                <?php
            }
        }
        add_action('quick_edit_custom_box', 'add_quick_edit_store_field', 10, 2);

        if (!function_exists('save_quick_edit_store_field')) {
            function save_quick_edit_store_field($post_id) {
                if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) return;
                if (!current_user_can('edit_post', $post_id)) return;
                if (!isset($_POST['associated_store'])) return;
                update_post_meta($post_id, '_associated_store', sanitize_text_field($_POST['associated_store']));
            }
        }
        add_action('save_post', 'save_quick_edit_store_field');

        if (!function_exists('quick_edit_store_javascript')) {
            function quick_edit_store_javascript() {
                global $current_screen;
                if ($current_screen->id != 'edit-product') return;
                ?>
                <script type="text/javascript">
                jQuery(function($){
                    var $wp_inline_edit = inlineEditPost.edit;
                    inlineEditPost.edit = function(id) {
                        $wp_inline_edit.apply(this, arguments);
                        var post_id = 0;
                        if (typeof(id) == 'object') {
                            post_id = parseInt(this.getId(id));
                        }
                        if (post_id > 0) {
                            var $edit_row = $('#edit-' + post_id);
                            var $post_row = $('#post-' + post_id);
                            var store_id = $post_row.find('.column-store a').attr('href').split('post=')[1].split('&')[0];
                            $edit_row.find('select[name="associated_store"]').val(store_id);
                        }
                    };
                });
                </script>
                <?php
            }
        }
        add_action('admin_footer', 'quick_edit_store_javascript');
    }
}

// 执行初始化函数
add_action('init', 'initialize_woocommerce_store_integration');