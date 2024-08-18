// Add a "Store" column to the WooCommerce product list
add_filter('manage_product_posts_columns', 'add_store_column_to_products');

function add_store_column_to_products($columns) {
    $columns['store'] = 'Store'; // Add "Store" column
    return $columns;
}

// Populate the "Store" column in the WooCommerce product list
add_action('manage_product_posts_custom_column', 'populate_store_column', 10, 2);

function populate_store_column($column, $post_id) {
    if ($column == 'store') {
        // Get the store ID associated with the product
        $store_id = get_post_meta($post_id, '_associated_store', true);

        if ($store_id) {
            // Get the store name
            $store_name = get_the_title($store_id);

            // Generate the link to edit the store
            $edit_link = admin_url('post.php?post=' . $store_id . '&action=edit');

            // Display the store name with a link to the edit page
            echo '<a href="' . esc_url($edit_link) . '">' . esc_html($store_name) . '</a>';
        } else {
            echo 'No store assigned'; // Display message if no store is associated
        }
    }
}

// Add a product count column to the "Store" custom post type
add_filter('manage_store_posts_columns', 'add_products_column_to_store');

function add_products_column_to_store($columns) {
    $columns['product_count'] = 'Products'; // Add "Products" column
    return $columns;
}

// Populate the product count column in the "Store" custom post type
add_action('manage_store_posts_custom_column', 'populate_products_column_in_store', 10, 2);

function populate_products_column_in_store($column, $post_id) {
    if ($column == 'product_count') {
        // Count the number of products associated with this store
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

        // Generate a link to filter the product list by this store
        $link = admin_url('edit.php?post_type=product&filter_by_store=' . $post_id);

        // Display the product count with a link to the filtered product list
        echo '<a href="' . esc_url($link) . '">' . esc_html($count) . '</a>';
    }
}

// Add a custom meta box to WooCommerce product pages for selecting a store
add_action('add_meta_boxes', 'add_store_meta_box');

function add_store_meta_box() {
    add_meta_box(
        'store_meta_box',          // ID of the meta box
        'Store',                   // Title of the meta box
        'display_store_meta_box',  // Callback function
        'product',                 // Show on WooCommerce product pages
        'side',                    // Display in the sidebar
        'high'                     // High priority
    );
}

function display_store_meta_box($post) {
    // Get all "store" custom post type posts
    $stores = get_posts(array(
        'post_type' => 'store',
        'posts_per_page' => -1
    ));
    
    // Get the store currently associated with the product
    $selected_store = get_post_meta($post->ID, '_associated_store', true);

    // Display a dropdown menu to select a store
    echo '<label for="associated_store">Select Store:</label>';
    echo '<select name="associated_store" id="associated_store">';
    echo '<option value="">Please select a store</option>';
    foreach ($stores as $store) {
        echo '<option value="' . esc_attr($store->ID) . '" ' . selected($selected_store, $store->ID, false) . '>' . esc_html($store->post_title) . '</option>';
    }
    echo '</select>';
}

// Save the selected store data
add_action('save_post', 'save_store_meta_box');

function save_store_meta_box($post_id) {
    // Check if it is a WooCommerce product edit page
    if (isset($_POST['associated_store'])) {
        update_post_meta($post_id, '_associated_store', sanitize_text_field($_POST['associated_store']));
    }
}

// Add a store filter to the product list
add_action('restrict_manage_posts', 'add_store_filter_to_products_list');

function add_store_filter_to_products_list() {
    global $typenow;

    if ($typenow == 'product') {
        // Get all "store" custom post type posts
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

// Modify the query to filter by the selected store
add_filter('parse_query', 'filter_products_by_store');

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