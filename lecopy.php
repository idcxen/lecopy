<?php
/*
Plugin Name: LeCopy
Description: 一键设置WordPress前端内容防止复制插件（可选择，不可复制）。公众号：老蒋朋友圈。
Version: 1.0
Author: 老蒋和他的伙伴们
Author URI: https://www.laozuo.org
Requires PHP: 7.0
*/

// Add plugin settings page
function prevent_copy_add_settings_page() {
    add_options_page('防复制插件设置', '防止复制', 'manage_options', 'prevent-copy-settings', 'prevent_copy_render_settings_page');
}
add_action('admin_menu', 'prevent_copy_add_settings_page');

// Render settings page
function prevent_copy_render_settings_page() {
    ?>
    <div class="wrap">
        <h2>设置</h2>
        <form method="post" action="options.php">
            <?php
            settings_fields('prevent_copy_settings');
            do_settings_sections('prevent-copy-settings');
            submit_button();
            ?>
        </form>
    </div>
    <?php
}

// Initialize plugin settings
function prevent_copy_initialize_settings() {
    register_setting('prevent_copy_settings', 'prevent_copy_options', 'prevent_copy_sanitize_options');
    
    add_settings_section('prevent_copy_main_section', '插件设置', 'prevent_copy_main_section_callback', 'prevent-copy-settings');
    add_settings_field('prevent_copy_enable', '启用防止复制', 'prevent_copy_enable_callback', 'prevent-copy-settings', 'prevent_copy_main_section');
}
add_action('admin_init', 'prevent_copy_initialize_settings');

// Sanitize options
function prevent_copy_sanitize_options($input) {
    $sanitized_input = array();
    if (isset($input['enable'])) {
        $sanitized_input['enable'] = sanitize_text_field($input['enable']);
    }
    return $sanitized_input;
}

// Main settings section callback
function prevent_copy_main_section_callback() {
    echo '<p>配置防止复制插件的主要设置。</p>';
}

// Enable option callback
function prevent_copy_enable_callback() {
    $options = get_option('prevent_copy_options');
    $checked = isset($options['enable']) ? checked(1, $options['enable'], false) : '';
    echo '<input type="checkbox" id="prevent_copy_enable" name="prevent_copy_options[enable]" value="1" ' . $checked . ' />';
}

// Add frontend scripts to prevent copy
function prevent_copy_enqueue_scripts() {
    $options = get_option('prevent_copy_options');
    if (isset($options['enable']) && $options['enable']) {
        wp_enqueue_script('prevent-copy-script', plugins_url('/js/prevent-copy.js', __FILE__), array('jquery'), '1.0', true);
    }
}
add_action('wp_enqueue_scripts', 'prevent_copy_enqueue_scripts');

// Allow copying for logged in users
function prevent_copy_allow_logged_in_users() {
    $options = get_option('prevent_copy_options');
    if (isset($options['enable']) && $options['enable']) {
        if (is_user_logged_in()) {
            echo '<style>.prevent-copy { -webkit-user-select: initial; user-select: initial; }</style>';
        }
    }
}
add_action('wp_head', 'prevent_copy_allow_logged_in_users');
