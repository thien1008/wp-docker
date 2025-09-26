<?php
/**
 * Plugin Name: Nút Liên Hệ Tư Vấn
 * Plugin URI: https://yourwebsite.com
 * Description: Plugin tạo nút liên hệ với nhiều tư vấn viên (Zalo và số điện thoại), có thể thiết lập khác nhau cho mỗi trang
 * Version: 2.1
 * Author: Vũ Đình Thiện
 * License: GPL2
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

class ContactButtonPlugin {
    
    public function __construct() {
        add_action('init', array($this, 'init'));
        add_action('wp_enqueue_scripts', array($this, 'enqueue_scripts'));
        add_action('wp_footer', array($this, 'display_contact_button'));
        add_action('add_meta_boxes', array($this, 'add_meta_boxes'));
        add_action('save_post', array($this, 'save_meta_boxes'));
        add_action('admin_menu', array($this, 'admin_menu'));
        add_action('admin_init', array($this, 'admin_init'));
        add_action('wp_ajax_save_contact_list', array($this, 'save_contact_list'));
        add_action('admin_enqueue_scripts', array($this, 'admin_scripts'));
    }
    
    public function init() {
        // Plugin initialization
    }
    
    public function enqueue_scripts() {
        wp_enqueue_script('contact-button-script', plugin_dir_url(__FILE__) . 'contact-button.js', array('jquery'), '2.1', true);
    }
    
    public function admin_scripts() {
        wp_enqueue_script('jquery');
        wp_enqueue_script('contact-admin-js', plugin_dir_url(__FILE__) . 'admin.js', array('jquery'), '2.1', true);
        wp_localize_script('contact-admin-js', 'contact_ajax', array(
            'ajax_url' => admin_url('admin-ajax.php'),
            'nonce' => wp_create_nonce('contact_nonce')
        ));
    }
    
    // Add admin menu
    public function admin_menu() {
        add_options_page(
            'Cài Đặt Nút Liên Hệ',
            'Nút Liên Hệ',
            'manage_options',
            'contact-button-settings',
            array($this, 'admin_page')
        );
    }
    
    public function admin_init() {
        register_setting('contact_button_settings', 'contact_button_enable');
        register_setting('contact_button_settings', 'contact_button_default_contacts');
    }
    
    public function admin_page() {
        $contacts = get_option('contact_button_default_contacts', array());
        ?>
        <div class="wrap">
            <h1>Cài Đặt Nút Liên Hệ</h1>
            <form method="post" action="options.php">
                <?php settings_fields('contact_button_settings'); ?>
                <table class="form-table">
                    <tr>
                        <th scope="row">Bật/Tắt Plugin</th>
                        <td>
                            <input type="checkbox" name="contact_button_enable" value="1" <?php checked(1, get_option('contact_button_enable'), true); ?> />
                            <label>Bật hiển thị nút liên hệ</label>
                        </td>
                    </tr>
                </table>
                
                <h2>Danh Sách Tư Vấn Viên Mặc Định</h2>
                <div id="contact-list">
                    <?php if (!empty($contacts)): ?>
                        <?php foreach ($contacts as $index => $contact): ?>
                        <div class="contact-item" data-index="<?php echo $index; ?>">
                            <h4>Tư vấn viên #<?php echo ($index + 1); ?></h4>
                            <table class="form-table">
                                <tr>
                                    <td><label>Tên:</label><br>
                                    <input type="text" name="contact_button_default_contacts[<?php echo $index; ?>][name]" 
                                           value="<?php echo esc_attr($contact['name']); ?>" class="regular-text" /></td>
                                </tr>
                                <tr>
                                    <td><label>Số điện thoại:</label><br>
                                    <input type="text" name="contact_button_default_contacts[<?php echo $index; ?>][phone]" 
                                           value="<?php echo esc_attr($contact['phone']); ?>" class="regular-text" /></td>
                                </tr>
                                <tr>
                                    <td><label>Zalo:</label><br>
                                    <input type="text" name="contact_button_default_contacts[<?php echo $index; ?>][zalo]" 
                                           value="<?php echo esc_attr($contact['zalo']); ?>" class="regular-text" /></td>
                                </tr>
                                <tr>
                                    <td><button type="button" class="button remove-contact">Xóa</button></td>
                                </tr>
                            </table>
                            <hr>
                        </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
                
                <button type="button" id="add-contact" class="button button-secondary">+ Thêm Tư Vấn Viên</button>
                
                <?php submit_button(); ?>
            </form>
        </div>
        
        <style>
        .contact-item {
            border: 1px solid #ddd;
            padding: 15px;
            margin: 10px 0;
            background: #f9f9f9;
            border-radius: 5px;
        }
        .contact-item h4 {
            margin-top: 0;
            color: #0073aa;
        }
        .remove-contact {
            background: #dc3232 !important;
            color: white !important;
            border: none !important;
        }
        #add-contact {
            margin: 20px 0;
            background: #0073aa !important;
            color: white !important;
            border: none !important;
        }
        </style>
        
        <script>
        jQuery(document).ready(function($) {
            var contactIndex = <?php echo count($contacts); ?>;
            
            $('#add-contact').click(function() {
                var html = '<div class="contact-item" data-index="' + contactIndex + '">' +
                    '<h4>Tư vấn viên #' + (contactIndex + 1) + '</h4>' +
                    '<table class="form-table">' +
                    '<tr><td><label>Tên:</label><br><input type="text" name="contact_button_default_contacts[' + contactIndex + '][name]" value="" class="regular-text" /></td></tr>' +
                    '<tr><td><label>Số điện thoại:</label><br><input type="text" name="contact_button_default_contacts[' + contactIndex + '][phone]" value="" class="regular-text" /></td></tr>' +
                    '<tr><td><label>Zalo:</label><br><input type="text" name="contact_button_default_contacts[' + contactIndex + '][zalo]" value="" class="regular-text" /></td></tr>' +
                    '<tr><td><button type="button" class="button remove-contact">Xóa</button></td></tr>' +
                    '</table><hr></div>';
                
                $('#contact-list').append(html);
                contactIndex++;
            });
            
            $(document).on('click', '.remove-contact', function() {
                $(this).closest('.contact-item').remove();
            });
        });
        </script>
        <?php
    }
    
    // Add meta boxes for posts/pages
    public function add_meta_boxes() {
        $screens = array('post', 'page', 'product');
        foreach ($screens as $screen) {
            add_meta_box(
                'contact-button-meta',
                'Cài Đặt Liên Hệ Trang Này',
                array($this, 'meta_box_callback'),
                $screen,
                'side'
            );
        }
    }
    
    public function meta_box_callback($post) {
        wp_nonce_field('contact_button_meta_nonce', 'contact_button_meta_nonce');
        
        $page_contacts = get_post_meta($post->ID, '_contact_button_contacts', true);
        if (!is_array($page_contacts)) $page_contacts = array();
        $disable = get_post_meta($post->ID, '_contact_button_disable', true);
        
        echo '<div style="margin-bottom: 15px;">';
        echo '<label><input type="checkbox" name="contact_button_disable" value="1" ' . checked(1, $disable, false) . '> Tắt nút liên hệ cho trang này</label>';
        echo '</div>';
        
        echo '<h4>Tư vấn viên cho trang này:</h4>';
        echo '<div id="page-contact-list">';
        
        if (!empty($page_contacts)) {
            foreach ($page_contacts as $index => $contact) {
                echo '<div class="page-contact-item" data-index="' . $index . '" style="border: 1px solid #ddd; padding: 10px; margin: 5px 0; background: #f9f9f9;">';
                echo '<strong>Tư vấn viên #' . ($index + 1) . '</strong><br>';
                echo '<label>Tên:</label><br><input type="text" name="page_contacts[' . $index . '][name]" value="' . esc_attr($contact['name']) . '" style="width: 100%; margin-bottom: 5px;" /><br>';
                echo '<label>SĐT:</label><br><input type="text" name="page_contacts[' . $index . '][phone]" value="' . esc_attr($contact['phone']) . '" style="width: 100%; margin-bottom: 5px;" /><br>';
                echo '<label>Zalo:</label><br><input type="text" name="page_contacts[' . $index . '][zalo]" value="' . esc_attr($contact['zalo']) . '" style="width: 100%; margin-bottom: 5px;" /><br>';
                echo '<button type="button" class="button remove-page-contact" style="background: #dc3232; color: white;">Xóa</button>';
                echo '</div>';
            }
        }
        
        echo '</div>';
        echo '<button type="button" id="add-page-contact" class="button" style="background: #0073aa; color: white; margin-top: 10px;">+ Thêm Tư Vấn Viên</button>';
        
        echo '<script>
        jQuery(document).ready(function($) {
            var pageContactIndex = ' . count($page_contacts) . ';
            
            $("#add-page-contact").click(function() {
                var html = "<div class=\"page-contact-item\" data-index=\"" + pageContactIndex + "\" style=\"border: 1px solid #ddd; padding: 10px; margin: 5px 0; background: #f9f9f9;\">" +
                    "<strong>Tư vấn viên #" + (pageContactIndex + 1) + "</strong><br>" +
                    "<label>Tên:</label><br><input type=\"text\" name=\"page_contacts[" + pageContactIndex + "][name]\" value=\"\" style=\"width: 100%; margin-bottom: 5px;\" /><br>" +
                    "<label>SĐT:</label><br><input type=\"text\" name=\"page_contacts[" + pageContactIndex + "][phone]\" value=\"\" style=\"width: 100%; margin-bottom: 5px;\" /><br>" +
                    "<label>Zalo:</label><br><input type=\"text\" name=\"page_contacts[" + pageContactIndex + "][zalo]\" value=\"\" style=\"width: 100%; margin-bottom: 5px;\" /><br>" +
                    "<button type=\"button\" class=\"button remove-page-contact\" style=\"background: #dc3232; color: white;\">Xóa</button>" +
                    "</div>";
                
                $("#page-contact-list").append(html);
                pageContactIndex++;
            });
            
            $(document).on("click", ".remove-page-contact", function() {
                $(this).closest(".page-contact-item").remove();
            });
        });
        </script>';
    }
    
    public function save_meta_boxes($post_id) {
        if (!isset($_POST['contact_button_meta_nonce']) || !wp_verify_nonce($_POST['contact_button_meta_nonce'], 'contact_button_meta_nonce')) {
            return;
        }
        
        if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
            return;
        }
        
        if (!current_user_can('edit_post', $post_id)) {
            return;
        }
        
        // Save disable setting
        $disable = isset($_POST['contact_button_disable']) ? 1 : 0;
        update_post_meta($post_id, '_contact_button_disable', $disable);
        
        // Save page contacts
        $contacts = array();
        if (isset($_POST['page_contacts']) && is_array($_POST['page_contacts'])) {
            foreach ($_POST['page_contacts'] as $contact) {
                if (!empty($contact['name']) || !empty($contact['phone']) || !empty($contact['zalo'])) {
                    $contacts[] = array(
                        'name' => sanitize_text_field($contact['name']),
                        'phone' => sanitize_text_field($contact['phone']),
                        'zalo' => sanitize_text_field($contact['zalo'])
                    );
                }
            }
        }
        update_post_meta($post_id, '_contact_button_contacts', $contacts);
    }
    
    public function display_contact_button() {
        // Check if plugin is enabled
        if (!get_option('contact_button_enable')) {
            return;
        }
        
        
        // If singular page has disabled, do not render
        if (is_singular() && get_post_meta(get_the_ID(), '_contact_button_disable', true)) {
            return;
        }
// Check if disabled for current page
        if (is_singular() && get_post_meta(get_the_ID(), '_contact_button_disable', true)) {
            return;
        }
        
        // Get contacts
        $contacts = array();
        
        if (is_singular()) {
            $page_contacts = get_post_meta(get_the_ID(), '_contact_button_contacts', true);
            if (!empty($page_contacts) && is_array($page_contacts)) {
                $contacts = $page_contacts;
            }
        }
        
        // Use default if no page-specific contacts
        if (empty($contacts)) {
            $contacts = get_option('contact_button_default_contacts', array());
        }
        
        // Don't show if no contacts
        if (empty($contacts)) {
            return;
        }
        
        // Filter out empty contacts
        $valid_contacts = array();
        foreach ($contacts as $contact) {
            if (!empty($contact['phone']) || !empty($contact['zalo'])) {
                $valid_contacts[] = $contact;
            }
        }
        
        if (empty($valid_contacts)) {
            return;
        }
        
        ?>
        <div id="contact-button-widget">
            <div class="contact-main-btn">
                <div class="contact-icon-container">
                    <!-- Menu Icon -->
                    <div class="contact-icon menu-icon active">
                        <svg viewBox="0 0 24 24">
                            <path d="M3 6h18v2H3V6zm0 5h18v2H3v-2zm0 5h18v2H3v-2z"/>
                        </svg>
                    </div>
                    
                    <!-- Phone Icon -->
                    <div class="contact-icon phone-icon">
                        <svg viewBox="0 0 24 24">
                            <path d="M6.62 10.79a15.05 15.05 0 006.59 6.59l2.2-2.2a1 1 0 011.01-.24c1.11.37 2.31.57 3.58.57a1 1 0 011 1v3.5a1 1 0 01-1 1C12.08 22.82 1.18 11.92 1 3a1 1 0 011-1h3.5a1 1 0 011 1c0 1.27.2 2.47.57 3.58a1 1 0 01-.24 1.01l-2.2 2.2z"/>
                        </svg>
                    </div>
                    
                    <!-- Zalo Icon -->
                    <div class="contact-icon zalo-icon">
                        <img
                        src="<?php echo esc_url( plugins_url( 'Icon_of_Zalo.svg.png', __FILE__ ) ); ?>"
                        alt="Zalo" style="width:24px;height:24px;">
                    </div>
                </div>
            </div>
            <div class="contact-options">
                <!-- <div class="contact-options-header">
                    <h3>Liên hệ với chúng tôi</h3>
                </div> -->
                <div class="contact-options-content">
                    <?php foreach ($valid_contacts as $index => $contact): ?>
                        <?php if (!empty($contact['name'])): ?>
                            <div class="contact-person-header">
                                <div class="contact-person-avatar">
                                    <?php echo strtoupper(substr($contact['name'], 0, 1)); ?>
                                </div>
                                <span><?php echo esc_html($contact['name']); ?></span>
                            </div>
                        <?php endif; ?>
                        
                        <div class="contact-person-options">
                            <?php if (!empty($contact['phone'])): ?>
                            <a href="tel:<?php echo esc_attr($contact['phone']); ?>" class="contact-option phone-option">
                                <div class="contact-option-icon">
                                    <svg viewBox="0 0 24 24">
                                        <path d="M6.62 10.79a15.05 15.05 0 006.59 6.59l2.2-2.2a1 1 0 011.01-.24c1.11.37 2.31.57 3.58.57a1 1 0 011 1v3.5a1 1 0 01-1 1C12.08 22.82 1.18 11.92 1 3a1 1 0 011-1h3.5a1 1 0 011 1c0 1.27.2 2.47.57 3.58a1 1 0 01-.24 1.01l-2.2 2.2z"/>
                                    </svg>
                                </div>
                                <div class="contact-option-content">
                                    <div class="contact-option-title">Gọi điện</div>
                                    <div class="contact-option-subtitle"><?php echo esc_html($contact['phone']); ?></div>
                                </div>
                            </a>
                            <?php endif; ?>
                            
                            <?php if (!empty($contact['zalo'])): ?>
                            <a href="https://zalo.me/<?php echo esc_attr($contact['zalo']); ?>" 
                            class="contact-option zalo-option" target="_blank" rel="noopener">
                                <div class="contact-option-icon zalo-icon">
                                    <img
                                    src="<?php echo esc_url( plugins_url( 'Icon_of_Zalo.svg.png', __FILE__ ) ); ?>"
                                    alt="Zalo" style="width:24px;height:24px;">
                                </div>
                                <div class="contact-option-content">
                                    <div class="contact-option-title">Chat Zalo</div>
                                    <div class="contact-option-subtitle">
                                        <?php echo esc_html(!empty($contact['name']) ? $contact['name'] : $contact['zalo']); ?>
                                    </div>
                                </div>
                            </a>
                        <?php endif; ?>
                        </div>
                        
                        <?php if ($index < count($valid_contacts) - 1): ?>
                            <div class="contact-separator"></div>
                        <?php endif; ?>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
        
        <style>
        #contact-button-widget {
            position: fixed;
            right: 20px;
            bottom: 13%;
            transform: translateY(-50%);
            z-index: 9999;
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
        }
        
        .contact-main-btn {
            background: linear-gradient(135deg, #0084FF 0%, #0066CC 100%);
            width: 60px;
            height: 60px;
            border-radius: 50%;
            cursor: pointer;
            box-shadow: 0 8px 25px rgba(0, 132, 255, 0.4);
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            user-select: none;
            position: relative;
            overflow: hidden;
        }
        
        .contact-main-btn::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.2), transparent);
            transition: left 0.5s;
        }
        
        .contact-main-btn:hover::before {
            left: 100%;
        }
        
        .contact-main-btn:hover {
            transform: translateY(-3px) scale(1.05);
            box-shadow: 0 12px 35px rgba(0, 132, 255, 0.5);
        }
        
        .contact-main-btn.active {
            transform: scale(0.95);
            box-shadow: 0 4px 15px rgba(0, 132, 255, 0.6);
        }
        
        .contact-icon-container {
            position: relative;
            width: 28px;
            height: 28px;
        }
        
        .contact-icon {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
            opacity: 0;
            transform: scale(0.5) rotateY(90deg);
            transition: all 0.6s ease;
        }
        
        .contact-icon.active {
            opacity: 1;
            transform: scale(1) rotateY(0deg);
        }
        
        .contact-icon svg {
            width: 100%;
            height: 100%;
            fill: white;
        }
        
        /* Animation cho các icon khi chưa click */
        .contact-main-btn:not(.clicked) .contact-icon {
            animation: iconRotate 4s infinite;
        }
        
        .contact-main-btn:not(.clicked) .menu-icon {
            animation-delay: 0s;
        }
        
        .contact-main-btn:not(.clicked) .phone-icon {
            animation-delay: 1.33s;
        }
        
        .contact-main-btn:not(.clicked) .zalo-icon {
            animation-delay: 2.66s;
        }
        
        @keyframes iconRotate {
            0%, 25% { 
                opacity: 1; 
                transform: scale(1) rotateY(0deg) rotateZ(0deg);
            }
            28%, 95% { 
                opacity: 0; 
                transform: scale(0.3) rotateY(180deg) rotateZ(360deg);
            }
            98%, 100% { 
                opacity: 1; 
                transform: scale(1) rotateY(0deg) rotateZ(0deg);
            }
        }
        
        .contact-options {
            position: absolute;
            bottom: 70px;
            right: 0;
            background: white;
            border-radius: 16px;
            min-width: 320px;
            max-width: 350px;
            box-shadow: 0 12px 40px rgba(0, 0, 0, 0.15);
            opacity: 0;
            visibility: hidden;
            transform: translateY(20px) scale(0.95);
            transition: all 0.3s cubic-bezier(0.34, 1.56, 0.64, 1);
            border: 1px solid rgba(0, 0, 0, 0.08);
            /* Responsive height handling */
            max-height: 70vh;
            overflow: hidden;
            display: flex;
            flex-direction: column;
        }
        
        .contact-options.show {
            opacity: 1;
            visibility: visible;
            transform: translateY(0) scale(1);
        }
        
        .contact-options-header {
            padding: 20px 20px 15px;
            border-bottom: 1px solid #f0f0f0;
            background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
            border-radius: 16px 16px 0 0;
            flex-shrink: 0;
        }
        
        .contact-options-header h3 {
            margin: 0;
            font-size: 16px;
            font-weight: 600;
            color: #333;
            text-align: center;
        }
        
        .contact-options-content {
            overflow-y: auto;
            flex: 1;
            /* Custom scrollbar */
            scrollbar-width: thin;
            scrollbar-color: rgba(0, 132, 255, 0.3) transparent;
        }
        
        .contact-options-content::-webkit-scrollbar {
            width: 6px;
        }
        
        .contact-options-content::-webkit-scrollbar-track {
            background: transparent;
        }
        
        .contact-options-content::-webkit-scrollbar-thumb {
            background: rgba(0, 132, 255, 0.3);
            border-radius: 3px;
        }
        
        .contact-options-content::-webkit-scrollbar-thumb:hover {
            background: rgba(0, 132, 255, 0.5);
        }
        
        .contact-person-header {
            display: flex;
            align-items: center;
            padding: 16px 20px 12px;
            gap: 12px;
            color: #333;
            font-weight: 600;
            font-size: 15px;
        }
        
        .contact-person-avatar {
            width: 36px;
            height: 36px;
            border-radius: 50%;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: 700;
            font-size: 14px;
            text-transform: uppercase;
            flex-shrink: 0;
        }
        
        .contact-person-options {
            padding: 0 20px;
        }
        
        .contact-option {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 12px;
            margin-bottom: 8px;
            border-radius: 12px;
            text-decoration: none;
            color: #333;
            transition: all 0.2s ease;
            border: 1px solid transparent;
            position: relative;
            overflow: hidden;
        }
        
        .contact-option::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            transition: left 0.3s ease;
            z-index: 0;
        }
        
        .contact-option > * {
            position: relative;
            z-index: 1;
        }
        
        .phone-option {
            border-color: rgba(37, 211, 102, 0.2);
            background: rgba(37, 211, 102, 0.05);
        }
        
        .phone-option::before {
            background: linear-gradient(135deg, #25D366, #128C7E);
        }
        
        .phone-option:hover::before {
            left: 0;
        }
        
        .zalo-option {
            border-color: rgba(0, 132, 255, 0.2);
            background: rgba(0, 132, 255, 0.05);
        }
        
        .zalo-option::before {
            background: linear-gradient(135deg, #0084FF, #0066CC);
        }
        
        .zalo-option:hover::before {
            left: 0;
        }
        
        .contact-option:hover {
            transform: translateY(-1px);
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
            color: white;
            text-decoration: none;
        }
        
        .contact-option:hover .contact-option-icon svg {
            fill: white;
        }
        
        .contact-option:hover .contact-option-content {
            color: white;
        }
        
        .contact-option-icon {
            width: 40px;
            height: 40px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }
        
        .phone-option .contact-option-icon {
            background: rgba(37, 211, 102, 0.1);
        }
        
        .zalo-option .contact-option-icon {
            background: rgba(0, 132, 255, 0.1);
        }
        
        .contact-option-icon svg {
            width: 20px;
            height: 20px;
            transition: fill 0.2s ease;
        }
        
        .phone-option .contact-option-icon svg {
            fill: #25D366;
        }
        
        .zalo-option .contact-option-icon svg {
            fill: #0084FF;
        }
        
        .contact-option-content {
            flex: 1;
            transition: color 0.2s ease;
            min-width: 0;
        }
        
        .contact-option-title {
            font-weight: 600;
            font-size: 14px;
            margin-bottom: 2px;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }
        
        .contact-option-subtitle {
            font-size: 12px;
            opacity: 0.8;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }
        
        .contact-separator {
            height: 1px;
            background: linear-gradient(to right, transparent, #e0e0e0, transparent);
            margin: 16px 20px;
        }
        
        /* Animation pulse cho nút chính */
        @keyframes pulse {
            0% { 
                box-shadow: 0 8px 25px rgba(0, 132, 255, 0.4), 
                           0 0 0 0 rgba(0, 132, 255, 0.7);
            }
            50% { 
                box-shadow: 0 8px 25px rgba(0, 132, 255, 0.6), 
                           0 0 0 10px rgba(0, 132, 255, 0);
            }
            100% { 
                box-shadow: 0 8px 25px rgba(0, 132, 255, 0.4), 
                           0 0 0 0 rgba(0, 132, 255, 0);
            }
        }
        
        .contact-main-btn:not(.clicked) {
            animation: pulse 3s infinite;
        }
        
        /* Responsive design */
        @media (max-width: 768px) {
            #contact-button-widget {
                bottom: 20px;
                right: 15px;
                transform: none;
            }
            
            .contact-main-btn {
                width: 55px;
                height: 55px;
            }
            
            .contact-icon-container {
                width: 24px;
                height: 24px;
            }
            
            .contact-options {
                min-width: 280px;
                max-width: calc(100vw - 40px);
                right: -10px;
                max-height: 60vh;
            }
            
            .contact-person-header {
                padding: 14px 16px 10px;
                font-size: 14px;
            }
            
            .contact-person-avatar {
                width: 32px;
                height: 32px;
                font-size: 13px;
            }
            
            .contact-person-options {
                padding: 0 16px;
            }
            
            .contact-option {
                padding: 10px;
                gap: 10px;
            }
            
            .contact-option-icon {
                width: 36px;
                height: 36px;
            }
            
            .contact-option-title {
                font-size: 13px;
            }
            
            .contact-option-subtitle {
                font-size: 11px;
            }
            
            .contact-separator {
                margin: 12px 16px;
            }
        }
        
        /* Màn hình nhỏ hơn (high density phone) */
        @media (max-width: 480px) {
            .contact-options {
                min-width: 260px;
                max-width: calc(100vw - 30px);
                right: -5px;
                max-height: 55vh;
            }
            
            .contact-person-header {
                padding: 12px 14px 8px;
            }
            
            .contact-person-options {
                padding: 0 14px;
            }
            
            .contact-option {
                padding: 8px;
            }
        }
        
        /* Màn hình cao độ thấp (landscape) */
        @media (max-height: 600px) {
            #contact-button-widget {
                bottom: 10px;
                right: 15px;
            }
            
            .contact-options {
                max-height: 50vh;
                bottom: 65px;
            }
            
            .contact-person-header {
                padding: 10px 16px 8px;
            }
            
            .contact-person-options {
                padding: 0 16px;
            }
            
            .contact-option {
                padding: 8px;
                margin-bottom: 6px;
            }
            
            .contact-separator {
                margin: 10px 16px;
            }
        }
        
        /* Màn hình rất thấp */
        @media (max-height: 480px) {
            .contact-options {
                max-height: 40vh;
                bottom: 60px;
            }
            
            .contact-person-header {
                padding: 8px 16px 6px;
                font-size: 13px;
            }
            
            .contact-person-avatar {
                width: 28px;
                height: 28px;
                font-size: 12px;
            }
            
            .contact-option {
                padding: 6px;
                margin-bottom: 4px;
            }
            
            .contact-option-icon {
                width: 32px;
                height: 32px;
            }
            
            .contact-option-title {
                font-size: 12px;
            }
            
            .contact-option-subtitle {
                font-size: 10px;
            }
            
            .contact-separator {
                margin: 8px 16px;
            }
        }
        
        /* Tablet portrait */
        @media (min-width: 769px) and (max-width: 1024px) and (orientation: portrait) {
            .contact-options {
                max-height: 65vh;
            }
        }
        
        /* Tablet landscape */
        @media (min-width: 769px) and (max-width: 1024px) and (orientation: landscape) {
            .contact-options {
                max-height: 60vh;
            }
        }
        
        /* Desktop với màn hình thấp */
        @media (min-width: 1025px) and (max-height: 700px) {
            .contact-options {
                max-height: 55vh;
            }
        }
        
        /* Animation khi mở/đóng với responsive */
        @media (max-width: 768px) {
            .contact-options {
                transform: translateY(30px) scale(0.9);
            }
            
            .contact-options.show {
                transform: translateY(0) scale(1);
            }
        }
        
        /* Ensure smooth scrolling on all devices */
        .contact-options-content {
            -webkit-overflow-scrolling: touch;
            scroll-behavior: smooth;
        }
        </style>
        
        <script>
        jQuery(document).ready(function($) {
            var isOpen = false;
            
            $('.contact-main-btn').click(function(e) {
                e.preventDefault();
                var options = $('.contact-options');
                var btn = $(this);
                
                if (isOpen) {
                    options.removeClass('show');
                    btn.removeClass('clicked');
                    isOpen = false;
                } else {
                    options.addClass('show');
                    btn.addClass('clicked');
                    isOpen = true;
                    
                    // Auto-adjust position on small screens
                    var windowHeight = $(window).height();
                    var optionsHeight = options.outerHeight();
                    var btnOffset = btn.offset();
                    
                    // If options would go above viewport, adjust position
                    if (btnOffset.top - optionsHeight - 10 < 0) {
                        options.css({
                            'bottom': 'auto',
                            'top': '70px'
                        });
                    } else {
                        options.css({
                            'bottom': '70px',
                            'top': 'auto'
                        });
                    }
                }
            });
            
            // Close when clicking outside
            $(document).click(function(e) {
                if (!$(e.target).closest('#contact-button-widget').length && isOpen) {
                    $('.contact-options').removeClass('show');
                    $('.contact-main-btn').removeClass('clicked');
                    isOpen = false;
                }
            });
            
            // Prevent closing when clicking inside options
            $('.contact-options').click(function(e) {
                e.stopPropagation();
            });
            
            // Handle window resize
            $(window).resize(function() {
                if (isOpen) {
                    var options = $('.contact-options');
                    var btn = $('.contact-main-btn');
                    var windowHeight = $(window).height();
                    var optionsHeight = options.outerHeight();
                    var btnOffset = btn.offset();
                    
                    // Reset position styles
                    options.css({
                        'bottom': '70px',
                        'top': 'auto'
                    });
                    
                    // Re-calculate if needed
                    setTimeout(function() {
                        if (btnOffset.top - optionsHeight - 10 < 0) {
                            options.css({
                                'bottom': 'auto',
                                'top': '70px'
                            });
                        }
                    }, 100);
                }
            });
        });
        </script>
        <?php
    }
}

// Initialize the plugin
new ContactButtonPlugin();

// Activation hook
register_activation_hook(__FILE__, 'contact_button_activate');
function contact_button_activate() {
    add_option('contact_button_enable', 1);
    // Add default contact if none exists
    $default_contacts = array(
        array(
            'name' => 'Tư vấn viên 1',
            'phone' => '',
            'zalo' => ''
        )
    );
    add_option('contact_button_default_contacts', $default_contacts);
}

// Deactivation hook
register_deactivation_hook(__FILE__, 'contact_button_deactivate');
function contact_button_deactivate() {
    // Clean up if needed
}
?>