<?php

class SR_WC_P360V {

    private static $add_script_into = array(
        'page' => array('post.php', 'post-new.php'),
        'type' => array('product')
    );
    private static $sr_plugin_version = '3.3';
    private static $_url = '_url';

    function __construct() {
        if (in_array('woocommerce/woocommerce.php', apply_filters('active_plugins', sanitize_option('active_plugins', get_option('active_plugins'))))) {
            if (sanitize_option('sr_wc_p360v_set_default_options', get_option('sr_wc_p360v_set_default_options')) !== 'true' || sanitize_option('sr_wc_p360v_version', get_option('sr_wc_p360v_version')) !== SR_WC_P360V::$sr_plugin_version):
                SR_WC_P360V::sr_add_plugin_options();
            endif;
            add_action('admin_enqueue_scripts', array('SR_WC_P360V', 'sr_register_admin_scripts'));
            add_action('admin_enqueue_scripts', array('SR_WC_P360V', 'sr_enqueue_admin_scripts'));
            add_action('wp_enqueue_scripts', array('SR_WC_P360V', 'sr_register_scripts'), 10000000);
            add_action('wp_enqueue_scripts', array('SR_WC_P360V', 'sr_enqueue_scripts'), 10000000);
            add_action('add_meta_boxes', array('SR_WC_P360V', 'sr_product_360_view_metabox'));
            add_action('save_post', array('SR_WC_P360V', 'sr_product_360_view_metabox_save'));
            add_action('admin_menu', array('SR_WC_P360V', 'sr_product_360_setting'));
            if (sanitize_option('sr_wc_p360v_icon', get_option('sr_wc_p360v_icon')) === 'true'):
                add_filter('the_title', array('SR_WC_P360V', 'sr_customize_product_title'), 10, 2);
            endif;
            add_action('wp_ajax_sr_wc_p360v_update_setting', array('SR_WC_P360V', 'sr_wc_p360v_update_setting'));
        }
    }

    static function sr_plugin_activation() {
        if (!in_array('woocommerce/woocommerce.php', apply_filters('active_plugins', sanitize_option('active_plugins', get_option('active_plugins'))))) {
            deactivate_plugins(__FILE__);
            $class = 'notice notice-error';
            $message = __('This plugin is an extension to <a href="https://wordpress.org/plugins/woocommerce/" target="_blank">WooCommerce plugin</a>. To run this plugins you must have installed and activated <a href="https://wordpress.org/plugins/woocommerce/" target="_blank">WooCommerce plugin</a>.', 'sr-product-360-view');
            wp_die($message);
        }
    }

    static function sr_plugin_deactivation() {
        SR_WC_P360V::sr_reset_product_gallery();
        SR_WC_P360V::sr_delete_plugin_options();
    }

    static function sr_register_admin_scripts() {
        wp_register_script('sr-wc-p360v-images', plugins_url('/assets/js/sr-wc-360-images.js', __FILE__), array('jquery', 'jquery-ui-sortable'));
        wp_register_script('sr-wc-p360v-setting-page', plugins_url('/assets/js/setting-page.js', __FILE__), array('jquery'));
        wp_register_script('jquery-colorpicker', plugins_url('/assets/colorpicker/js/colorpicker.js', __FILE__), array('jquery'));
        wp_register_style('sr-wc-p360v-setting-page', plugins_url('/assets/css/setting-page.css', __FILE__));
        wp_register_style('abel-fonts', plugins_url('/assets/css/font.min.css', __FILE__));
        wp_register_style('sr-wc-p360v-images', plugins_url('/assets/css/sr-wc-360-images.css', __FILE__));
        wp_register_style('jquery-colorpicker', plugins_url('/assets/colorpicker/css/colorpicker.css', __FILE__));
    }

    static function sr_enqueue_admin_scripts($hook) {
        global $typenow;
        if (in_array($hook, SR_WC_P360V::$add_script_into['page']) && in_array($typenow, SR_WC_P360V::$add_script_into['type'])) {
            wp_enqueue_script('sr-wc-p360v-images');
            wp_enqueue_style('sr-wc-p360v-images');
            wp_enqueue_script('jquery-colorpicker');
            wp_enqueue_style('jquery-colorpicker');
        } elseif ($hook == 'toplevel_page_product-360-view') {
            wp_enqueue_style('abel-fonts');
            wp_enqueue_script('sr-wc-p360v-setting-page');
            wp_enqueue_style('sr-wc-p360v-setting-page');
            wp_enqueue_script('jquery-colorpicker');
            wp_enqueue_style('jquery-colorpicker');
            wp_localize_script(
                    'sr-wc-p360v-setting-page', 'sr_wc_p360v_setting', array(
                'ajax_url' => admin_url('admin-ajax.php'),
                'action' => 'sr_wc_p360v_update_setting',
                'security' => wp_create_nonce('sr-wc-p360v-settings-page')
                    )
            );
            //WP MEDIA
            wp_enqueue_media();
            //WP MEDIA END
        }
    }

    static function sr_wc_p360v_update_setting() {
        $_POST['security'] = sanitize_key($_POST['security']);
        if (check_ajax_referer('sr-wc-p360v-settings-page', 'security')) {
            SR_WC_P360V::sr_reset_product_gallery();
            $settings['sr_wc_p360v_bg'] = sanitize_hex_color($_POST['sr_wc_p360v_bg']);
            $settings['sr_wc_p360v_animation'] = sanitize_text_field($_POST['sr_wc_p360v_animation']);
            $settings['sr_wc_p360v_animation_reverse'] = sanitize_text_field($_POST['sr_wc_p360v_animation_reverse']);
            $settings['sr_wc_p360v_icon'] = sanitize_text_field($_POST['sr_wc_p360v_icon']);
            $settings['sr_wc_p360v_pos'] = intval($_POST['sr_wc_p360v_pos']);
            $settings['sr_360_icon'] = sanitize_key($_POST['sr_360_icon']);
            $settings['sr_wc_p360v_image_rotation_direction'] = sanitize_text_field($_POST['sr_wc_p360v_image_rotation_direction']);
            if (!in_array($settings['sr_wc_p360v_image_rotation_direction'], array('forward', 'backward'))) {
                $settings['sr_wc_p360v_image_rotation_control'] = 'backward';
            }
            $settings['sr_wc_p360v_image_rotation_speed'] = 100 - intval($_POST['sr_wc_p360v_image_rotation_speed']);
            if ($settings['sr_wc_p360v_image_rotation_speed'] < 0 || $settings['sr_wc_p360v_image_rotation_speed'] > 100) {
                $settings['sr_wc_p360v_image_rotation_speed'] = 40;
            }
            $settings['sr_wc_p360v_mouse_sensitivity'] = intval($_POST['sr_wc_p360v_mouse_sensitivity']);
            if ($settings['sr_wc_p360v_mouse_sensitivity'] < 1 || $settings['sr_wc_p360v_mouse_sensitivity'] > 20) {
                $settings['sr_wc_p360v_mouse_sensitivity'] = 1;
            }
            $settings['sr_wc_p360v_image_rotation_control'] = intval($_POST['sr_wc_p360v_image_rotation_control']);
            if (!in_array($settings['sr_wc_p360v_image_rotation_control'], array(1, 2))) {
                $settings['sr_wc_p360v_image_rotation_control'] = 1;
            }
            $settings['sr_wc_p360v_image_rotation_control'] = 'sr_wc_p360v_image_rotation_control_' . $settings['sr_wc_p360v_image_rotation_control'];
            $settings['sr_wc_p360v_loading_placeholder'] = sanitize_text_field($_POST['sr_wc_p360v_loading_placeholder']);
            if ($settings['sr_wc_p360v_loading_placeholder'] !== 'ProgressBar' && $settings['sr_wc_p360v_loading_placeholder'] !== 'Text'):
                $response = array(
                    'error' => __('Wrong input for loadin animation.', 'sr-product-360-view')
                );
                echo json_encode($response);
                exit;
            endif;
            $settings['sr_wc_p360v_loading_placeholder_text'] = sanitize_text_field($_POST['sr_wc_p360v_loading_placeholder_text']);
            if (empty($settings['sr_wc_p360v_loading_placeholder_text'])):
                $response = array(
                    'error' => __('Text field cannot be empty.', 'sr-product-360-view')
                );
                echo json_encode($response);
                exit;
            endif;
            $settings['sr_360_icon_custom'] = intval($_POST['sr_360_icon_custom']);
            $settings['sr_360_icon_custom_url'] = esc_url_raw($_POST['sr_360_icon_custom_url']);
            $image_url_check = $settings['sr_360_icon_custom_url'];
            if (strpos($image_url_check, 'sr-attachment-icon-360_custom_') === false && intval($settings['sr_360_icon_custom'])):
                $filename = basename($image_url_check);
                $ext = pathinfo($filename, PATHINFO_EXTENSION);
                $filepath = dirname($image_url_check);
                $t = time();
                $new_filename = 'custom_' . $t;
                $new_filepath = $filepath . '/sr-attachment-icon-360_' . $new_filename . '.' . $ext;
                $file_exist = SR_WC_P360V::sr_get_attachment_id_from_url($new_filepath);
                if (intval($file_exist)):
                    $settings['sr_360_icon_custom'] = $file_exist;
                    $settings['sr_360_icon_custom_url'] = $new_filepath;
                else:
                    $new_attachment_id = SR_WC_P360V::sr_add_images_to_media_gallery($image_url_check, $new_filename);
                    $new_attachment_url = wp_get_attachment_url($new_attachment_id);
                    $settings['sr_360_icon_custom'] = $new_attachment_id;
                    $settings['sr_360_icon_custom_url'] = $new_attachment_url;
                endif;
            endif;
            foreach ($settings as $setting => $value) {
                update_option($setting, $value);
            }
            $response = array(
                'success' => __('Settings Updated!', 'sr-product-360-view'),
                'custom_icon_id' => sanitize_option('sr_360_icon_custom', get_option('sr_360_icon_custom')),
                'custom_icon_url' => sanitize_option('sr_360_icon_custom_url', get_option('sr_360_icon_custom_url'))
            );
            echo json_encode($response);
            exit;
        }
    }

    static function sr_register_scripts() {
        wp_register_style('sr-wc-360', plugins_url('/assets/css/front-end/360-view.css', __FILE__));
        wp_register_script('sr-wc-360', plugins_url('/assets/js/front-end/360-view.js', __FILE__), array('jquery'));
        wp_register_script('sr-wc-threesixty', plugins_url('/assets/js/front-end/threesixty.min.js', __FILE__), array('sr-wc-360'));
    }

    static function sr_enqueue_scripts() {
        if (is_product()):
            $product_id = get_the_ID();
            $find_icon = false;
            $sr_360_images = sanitize_meta('sr_product_360_view_images', get_post_meta($product_id, 'sr_product_360_view_images', true), 'post');
            $sr_360_icon = sanitize_option('sr_360_icon', get_option('sr_360_icon'));
            $product_gallery = get_post_meta($product_id, '_product_image_gallery', true);
            $product_gallery = explode(',', $product_gallery);
            $image_append = sanitize_option($sr_360_icon, get_option($sr_360_icon));
            $sr_360_icon_1 = intval(sanitize_option('sr_360_icon_1', get_option('sr_360_icon_1')));
            $sr_360_icon_2 = intval(sanitize_option('sr_360_icon_2', get_option('sr_360_icon_2')));
            $sr_360_icon_custom = intval(sanitize_option('sr_360_icon_custom', get_option('sr_360_icon_custom')));
            if (array_search($image_append, $product_gallery)):
                $find_icon = true;
            endif;
            if ($image_append != $sr_360_icon_1)
                $product_gallery = SR_WC_P360V::remove_stack_element($product_gallery, $sr_360_icon_1);
            if ($image_append != $sr_360_icon_2)
                $product_gallery = SR_WC_P360V::remove_stack_element($product_gallery, $sr_360_icon_2);
            $rotation_control = sanitize_option('sr_wc_p360v_image_rotation_control', get_option('sr_wc_p360v_image_rotation_control'));
            if ($sr_360_icon_custom) {
                if ($image_append != $sr_360_icon_custom)
                    $product_gallery = SR_WC_P360V::remove_stack_element($product_gallery, $sr_360_icon_custom);
            }
            if ($sr_360_images):
                $images = array();
                foreach ($sr_360_images as $image) {
                    $images[] = wp_get_attachment_url($image);
                }
                wp_enqueue_style('sr-wc-360');
                wp_enqueue_script('sr-wc-threesixty');
                $sense_direction = sanitize_option('sr_wc_p360v_image_rotation_direction', get_option('sr_wc_p360v_image_rotation_direction')) == 'backward'?'-':'';
                wp_localize_script(
                        'sr-wc-360', 'sr_360_data', array(
                    'images' => $images,
                    'icon' => 'sr-attachment-icon-360_'/* sanitize_option($sr_360_icon . SR_WC_P360V::$_url, get_option($sr_360_icon . SR_WC_P360V::$_url)) */,
                    'bg' => sanitize_meta('sr_custom_background_color_', get_post_meta($product_id, 'sr_custom_background_color_', true), 'post') ? sanitize_meta('sr_background_color_', get_post_meta($product_id, 'sr_background_color_', true), 'post') : sanitize_option('sr_wc_p360v_bg', get_option('sr_wc_p360v_bg')),
                    'animation' => sanitize_option('sr_wc_p360v_animation', get_option('sr_wc_p360v_animation')),
                    'animation_reverse' => sanitize_option('sr_wc_p360v_animation_reverse', get_option('sr_wc_p360v_animation_reverse')),
                    'rotation_speed' => sanitize_option('sr_wc_p360v_image_rotation_speed', get_option('sr_wc_p360v_image_rotation_speed')),
                    'rotation_control' => sanitize_option($rotation_control, get_option($rotation_control)),
                    'pos' => sanitize_option('sr_wc_p360v_pos', get_option('sr_wc_p360v_pos')),
                    'text_enabled' => (sanitize_option('sr_wc_p360v_loading_placeholder', get_option('sr_wc_p360v_loading_placeholder')) === 'Text') ? 1 : 0,
                    'text' => __("<div class='sr360-stage'></div><div class='sr360-loading-placeholder'><strong>", 'sr-product-360-view') . sanitize_option('sr_wc_p360v_loading_placeholder_text', get_option('sr_wc_p360v_loading_placeholder_text')) . __("</strong></div>", 'sr-product-360-view'),
                    'default' => __("<div class='sr360-stage'></div>", 'sr-product-360-view'),
                    'sizeMode' => sanitize_option('sr_wc_p360v_image_size', get_option('sr_wc_p360v_image_size')),
                    'img_path' => plugins_url('/assets/img', __FILE__),
                    'sense' => $sense_direction.sanitize_option('sr_wc_p360v_mouse_sensitivity', get_option('sr_wc_p360v_mouse_sensitivity')),
                        )
                );
                if (!$find_icon)
                    array_push($product_gallery, $image_append);
                update_post_meta($product_id, '_product_image_gallery', implode(',', $product_gallery));
            else:
                update_post_meta($product_id, '_product_image_gallery', implode(',', $product_gallery));
            endif;
        endif;
    }

    static function sr_customize_product_title($title, $id) {
        if (!is_product() && !is_admin() && sanitize_meta('sr_has_360', get_post_meta($id, 'sr_has_360', true), 'post') == 1) {
            $sr_360_icon = sanitize_option('sr_360_icon', get_option('sr_360_icon'));
            $title .= __('<span class="text-right" style="position: absolute;right: 2px;top: 2px;width: 25%;height: 12.5%;"><img alt="360 &deg; View" title="360 &deg; View" style="width: 100%; max-width: 43px; position: absolute;top: 0;right: 0;margin: 0 !important;" src="' . sanitize_option($sr_360_icon . SR_WC_P360V::$_url, get_option($sr_360_icon . SR_WC_P360V::$_url)) . '"/></span>', '');
        }
        return $title;
    }

    static function sr_product_360_view_metabox($post_type) {
        if (in_array($post_type, SR_WC_P360V::$add_script_into['type'])) {
            add_meta_box(
                    'sr-product-360-view', 'Product 360&#176; View', array('SR_WC_P360V', 'sr_product_360_view_callback'), $post_type, 'normal', 'core'
            );
        }
    }

    static function sr_product_360_view_callback($post) {
        wp_nonce_field(basename(__FILE__), 'sr_product_360_view_images_nonce');
        $ids = sanitize_meta('sr_product_360_view_images', get_post_meta($post->ID, 'sr_product_360_view_images', true), 'post');
        $custom_background = sanitize_meta('sr_custom_background_color_', get_post_meta($post->ID, 'sr_custom_background_color_', true), 'post');
        $custom_color = sanitize_meta('sr_background_color_', get_post_meta($post->ID, 'sr_background_color_', true), 'post');
        ?>
        <table class="form-table">
            <tr><td>
                    <a class="images-add button" href="#" data-uploader-title="Choose product 360&#176; images in progressive order" data-uploader-button-text="Select Images">Add 360&#176; images</a>

                    <ul id="sr-product-360-view-images-list">
                        <?php if ($ids) : foreach ($ids as $key => $value) : $image = wp_get_attachment_image_src($value); ?>

                                <li>
                                    <input type="hidden" name="sr_product_360_view_images[<?php echo $key; ?>]" value="<?php echo $value; ?>">
                                    <img class="image-preview" src="<?php echo $image[0]; ?>">
                                    <a class="change-image button button-small" href="#" data-uploader-title="Change image" data-uploader-button-text="Change image"><?= __('Change image', 'sr-product-360-view'); ?></a><br>
                                    <small><a class="remove-image" href="#">Remove image</a></small>
                                </li>

                                <?php
                            endforeach;
                        endif;
                        ?>
                    </ul>
                </td></tr>
            <tr><td>
                    <div>
                        <strong><?= __('Custom background color:', 'sr-product-360-view'); ?></strong>
                        <input type="checkbox" name="sr_custom_background_color_" value="1" <?= $custom_background ? __('checked', 'sr-product-360-view') : ''; ?> class="sr_custom_background_color_">
                        <div id="colorSelector"><div style="background-color: <?= $custom_color ? $custom_color : (sanitize_meta('sr_background_color_', get_post_meta($post->ID, 'sr_background_color_', true), 'post') ? sanitize_meta('sr_background_color_', get_post_meta($post->ID, 'sr_background_color_', true), 'post') : sanitize_option('sr_wc_p360v_bg', get_option('sr_wc_p360v_bg'))); ?>"></div></div>
                        <input type="color" name="sr_background_color_" value="<?= $custom_color ? $custom_color : (sanitize_meta('sr_background_color_', get_post_meta($post->ID, 'sr_background_color_', true), 'post') ? sanitize_meta('sr_background_color_', get_post_meta($post->ID, 'sr_background_color_', true), 'post') : sanitize_option('sr_wc_p360v_bg', get_option('sr_wc_p360v_bg'))); ?>" class="sr_background_color_">
                    </div>
                </td></tr>
        </table>
        <script>
            jQuery('#colorSelector').ColorPicker({
                color: '#0000ff',
                onShow: function (colpkr) {
                    jQuery(colpkr).fadeIn(500);
                    return false;
                },
                onHide: function (colpkr) {
                    jQuery(colpkr).fadeOut(500);
                    return false;
                },
                onChange: function (hsb, hex, rgb) {
                    jQuery('#colorSelector div').css('backgroundColor', '#' + hex);
                    jQuery('.sr_background_color_').val('#' + hex);
                }
            });
        </script>
        <?php
    }

    static function sr_product_360_view_metabox_save($post_id) {
        if (!isset($_POST['sr_product_360_view_images_nonce']) || (empty($_POST['sr_product_360_view_images_nonce']) || !wp_verify_nonce($_POST['sr_product_360_view_images_nonce'], basename(__FILE__))))
            return;

        if (!current_user_can('edit_post', $post_id))
            return;

        if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE)
            return;

        if (isset($_POST['sr_product_360_view_images'])) {
            $images_id = array_map('intval', $_POST['sr_product_360_view_images']);
            update_post_meta($post_id, 'sr_product_360_view_images', $images_id);
            update_post_meta($post_id, 'sr_has_360', 1);
            update_post_meta($post_id, 'sr_background_color_', sanitize_hex_color($_POST['sr_background_color_']));
            update_post_meta($post_id, 'sr_custom_background_color_', intval($_POST['sr_custom_background_color_']));
        } else {
            delete_post_meta($post_id, 'sr_product_360_view_images');
            delete_post_meta($post_id, 'sr_has_360');
            delete_post_meta($post_id, 'sr_background_color_');
            delete_post_meta($post_id, 'sr_custom_background_color_');
        }
    }

    static function sr_product_360_setting() {
        add_menu_page('Product 360&#176; Settings', 'Product 360&#176;', 'manage_options', 'product-360-view', array('SR_WC_P360V', 'sr_product_360_setting_page'), plugins_url('/assets/img/sr.png', __FILE__), 56);
    }

    static function sr_product_360_setting_page() {
        require_once 'setting-page.php';
    }

    static function sr_set_360_icons() {
        $sr_360_icon_1 = intval(sanitize_option('sr_360_icon_1', get_option('sr_360_icon_1')));
        $sr_360_icon_2 = intval(sanitize_option('sr_360_icon_2', get_option('sr_360_icon_2')));
        $sr_360_icon_custom = intval(sanitize_option('sr_360_icon_custom', get_option('sr_360_icon_custom')));
        if (!$sr_360_icon_1):
            $new_filename = 'one';
            update_option('sr_360_icon_1', SR_WC_P360V::sr_add_images_to_media_gallery(plugins_url('/assets/img/360-3d.png', __FILE__), $new_filename));
            $sr_360_icon_1 = intval(sanitize_option('sr_360_icon_1', get_option('sr_360_icon_1')));
        endif;
        if (!$sr_360_icon_2):
            $new_filename = 'two';
            update_option('sr_360_icon_2', SR_WC_P360V::sr_add_images_to_media_gallery(plugins_url('/assets/img/360.png', __FILE__), $new_filename));
            $sr_360_icon_2 = intval(sanitize_option('sr_360_icon_2', get_option('sr_360_icon_2')));
        endif;
        add_option('sr_360_icon_1_url', wp_get_attachment_url($sr_360_icon_1));
        add_option('sr_360_icon_2_url', wp_get_attachment_url($sr_360_icon_2));
        if ($sr_360_icon_custom):
            add_option('sr_360_icon_custom_url', wp_get_attachment_url($sr_360_icon_custom));
        else:
            update_option('sr_360_icon_custom', '');
            add_option('sr_360_icon_custom_url', '');
        endif;
    }

    static function sr_add_images_to_media_gallery($image_address, $new_filename) {
        $upload_dir = wp_upload_dir();
        $image_data = file_get_contents($image_address);
        $filename = basename($image_address);
        $ext = pathinfo($filename, PATHINFO_EXTENSION);
        $filename = __('sr-attachment-icon-360_', 'sr-product-360-view') . $new_filename . '.' . $ext;
        if (wp_mkdir_p($upload_dir['path'])) {
            $file = $upload_dir['path'] . '/' . $filename;
        } else {
            $file = $upload_dir['basedir'] . '/' . $filename;
        }
        file_put_contents($file, $image_data);
        $wp_filetype = wp_check_filetype($filename, null);
        $attachment = array(
            'post_mime_type' => $wp_filetype['type'],
            'post_title' => sanitize_file_name($filename),
            'post_content' => '',
            'post_status' => 'inherit'
        );
        $attach_id = wp_insert_attachment($attachment, $file);
        require_once( ABSPATH . 'wp-admin/includes/image.php' );
        $attach_data = wp_generate_attachment_metadata($attach_id, $file);
        wp_update_attachment_metadata($attach_id, $attach_data);
        return $attach_id;
    }

    static function sr_add_plugin_options() {
        add_option('sr_wc_p360v_bg', '#ffffff');
        add_option('sr_wc_p360v_animation', __('true', 'sr-product-360-view'));
        add_option('sr_wc_p360v_animation_reverse', __('false', 'sr-product-360-view'));
        add_option('sr_wc_p360v_image_rotation_speed', intval(40));
        add_option('sr_wc_p360v_image_rotation_control_1', __('360,drag,zoom', 'sr-product-360-view'));
        if (sanitize_option('sr_wc_p360v_image_rotation_control_1', get_option('sr_wc_p360v_image_rotation_control_1')) !== __('360,drag,zoom', 'sr-product-360-view')):
            update_option('sr_wc_p360v_image_rotation_control_1', __('360,drag,zoom', 'sr-product-360-view'));
        endif;
        add_option('sr_wc_p360v_image_rotation_control_2', __('360,drag', 'sr-product-360-view'));
        add_option('sr_wc_p360v_image_rotation_control', __('sr_wc_p360v_image_rotation_control_1', 'sr-product-360-view'));
        add_option('sr_wc_p360v_icon', __('true', 'sr-product-360-view'));
        add_option('sr_wc_p360v_pos', intval(1));
        add_option('sr_360_icon', 'sr_360_icon_1');
        add_option('sr_wc_p360v_loading_placeholder', __('ProgressBar', 'sr-product-360-view'));
        add_option('sr_wc_p360v_loading_placeholder_text', __('Please Wait Loading View...', 'sr-product-360-view'));
        add_option('sr_wc_p360v_image_size', __('fit', 'sr-product-360-view'));
        if (sanitize_option('sr_wc_p360v_image_size', get_option('sr_wc_p360v_image_size')) !== __('fit', 'sr-product-360-view')):
            update_option('sr_wc_p360v_image_size', __('fit', 'sr-product-360-view'));
        endif;
        add_option('sr_wc_p360v_version', __(SR_WC_P360V::$sr_plugin_version, 'sr-product-360-view'));
        if (sanitize_option('sr_wc_p360v_version', get_option('sr_wc_p360v_version')) !== SR_WC_P360V::$sr_plugin_version):
            update_option('sr_wc_p360v_version', __(SR_WC_P360V::$sr_plugin_version, 'sr-product-360-view'));
        endif;
        add_option('sr_wc_p360v_image_rotation_direction', 'forward');
        add_option('sr_wc_p360v_mouse_sensitivity', 1);
        add_option('sr_wc_p360v_set_default_options', __('true', 'sr-product-360-view'));
    }

    static function sr_update_plugin_options() {
        // Part of next update
    }

    static function sr_delete_plugin_options() {
        delete_option('sr_wc_p360v_bg');
        delete_option('sr_wc_p360v_animation');
        delete_option('sr_wc_p360v_animation_reverse');
        delete_option('sr_wc_p360v_image_rotation_speed');
        delete_option('sr_wc_p360v_image_rotation_control_1');
        delete_option('sr_wc_p360v_image_rotation_control_2');
        delete_option('sr_wc_p360v_image_rotation_control');
        delete_option('sr_wc_p360v_icon');
        delete_option('sr_wc_p360v_pos');
        wp_delete_attachment(sanitize_option('sr_360_icon_1', get_option('sr_360_icon_1')));
        delete_option('sr_360_icon_1');
        delete_option('sr_360_icon_1_url');
        wp_delete_attachment(sanitize_option('sr_360_icon_2', get_option('sr_360_icon_2')));
        delete_option('sr_360_icon_2');
        delete_option('sr_360_icon_2_url');
        $sr_360_custom_icon = sanitize_option('sr_360_icon_custom', get_option('sr_360_icon_custom'));
        if (intval($sr_360_custom_icon)):
            wp_delete_attachment($sr_360_custom_icon);
        endif;
        delete_option('sr_360_icon_custom');
        delete_option('sr_360_icon_custom_url');
        delete_option('sr_360_icon');
        delete_option('sr_wc_p360v_loading_placeholder');
        delete_option('sr_wc_p360v_loading_placeholder_text');
        delete_option('sr_wc_p360v_image_size');
        delete_option('sr_wc_p360v_version');
        delete_option('sr_wc_p360v_image_rotation_direction');
        delete_option('sr_wc_p360v_mouse_sensitivity');
        delete_option('sr_wc_p360v_set_default_options');
    }

    static function sr_reset_product_gallery() {
        $meta_key = 'sr_has_360';
        $meta_value = 1;
        $product_ids = get_posts([
            'meta_key' => $meta_key,
            'meta_value' => $meta_value,
            'post_type' => 'product',
            'posts_per_page' => -1,
            'fields' => 'ids',
        ]);
        foreach ($product_ids as $product_id):
            $product_gallery = get_post_meta($product_id, '_product_image_gallery', true);
            $product_gallery = explode(',', $product_gallery);
            $sr_360_icon_1 = intval(sanitize_option('sr_360_icon_1', get_option('sr_360_icon_1')));
            $sr_360_icon_2 = intval(sanitize_option('sr_360_icon_2', get_option('sr_360_icon_2')));
            $sr_360_icon_custom = intval(sanitize_option('sr_360_icon_custom', get_option('sr_360_icon_custom')));
            $product_gallery = SR_WC_P360V::remove_stack_element($product_gallery, $sr_360_icon_1);
            $product_gallery = SR_WC_P360V::remove_stack_element($product_gallery, $sr_360_icon_2);
            if ($sr_360_icon_custom)
                $product_gallery = SR_WC_P360V::remove_stack_element($product_gallery, $sr_360_icon_custom);
            update_post_meta($product_id, '_product_image_gallery', implode(',', $product_gallery));
        endforeach;
    }

    static function remove_stack_element($array, $value) {
        return array_diff($array, (is_array($value) ? $value : array($value)));
    }

    function sr_get_attachment_id_from_url($image_url) {
        global $wpdb;
        $attachment = $wpdb->get_col($wpdb->prepare("SELECT ID FROM $wpdb->posts WHERE guid='%s';", $image_url));
        return $attachment[0];
    }

}
