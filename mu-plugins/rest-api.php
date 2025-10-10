<?php

add_action('admin_menu', function () {
    add_menu_page(
            'My API Routes',
            'My API',
            'manage_options',
            'my-api-routes',
            function () {
                $server = rest_get_server(); // Lấy server đúng cách
                $routes = $server->get_routes();

                echo '<h1>My API Routes</h1><pre>';
                foreach ($routes as $route => $details) {
                    if (strpos($route, '/mb/v1/') === 0) {
                        echo esc_html($route) . "\n";
                    }
                }
                echo '</pre>';
            }
    );
});

add_action('admin_menu', function () {
    add_menu_page(
            'Shortcodes',           // Tiêu đề trang
            'Shortcodes',           // Tên menu
            'manage_options',       // Quyền truy cập
            'shortcodes-list',      // Slug
            function () {            // Callback hiển thị nội dung
                global $shortcode_tags;
                ?>
                <div class="wrap">
                    <h1 class="wp-heading-inline">Danh sách Shortcode đã đăng ký</h1>
                    <table class="wp-list-table widefat fixed striped">
                        <thead>
                        <tr>
                            <th scope="col" class="manage-column">Shortcode Tag</th>
                            <th scope="col" class="manage-column">Callback</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php
                        if (!empty($shortcode_tags)) {
                            foreach ($shortcode_tags as $tag => $callback) {
                                ?>
                                <tr>
                                    <td><?php echo esc_html($tag); ?></td>
                                    <td><?php echo esc_html(get_callback_display($callback)); ?></td>
                                </tr>
                                <?php
                            }
                        } else {
                            ?>
                            <tr>
                                <td colspan="2">Không có shortcode nào được đăng ký.</td>
                            </tr>
                            <?php
                        }
                        ?>
                        </tbody>
                    </table>
                </div>
                <?php
            }
    );
});

/**
 * Hàm hỗ trợ để hiển thị callback dưới dạng chuỗi dễ đọc.
 *
 * @param mixed $callback Callback của shortcode.
 * @return string Chuỗi hiển thị callback.
 */
function get_callback_display($callback)
{
    if (is_string($callback)) {
        return $callback; // Trường hợp callback là tên hàm
    } elseif (is_array($callback)) {
        $class = is_object($callback[0]) ? get_class($callback[0]) : $callback[0];
        $method = $callback[1];
        return $class . '::' . $method; // Hiển thị dạng Class::method
    } elseif ($callback instanceof Closure) {
        return 'Closure'; // Trường hợp callback là closure
    } else {
        return 'Unknown'; // Trường hợp không xác định
    }
}
