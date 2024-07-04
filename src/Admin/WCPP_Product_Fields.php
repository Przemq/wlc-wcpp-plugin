<?php

declare(strict_types=1);

namespace WoocommerceFeaturedProduct\Admin;

if (!\defined('ABSPATH')) {
    exit;
}

class WCPP_Product_Fields
{
    /**
     * Add promoted field to product.
     */
    public function wcpp_add_promoted_checkbox_field(): void
    {
        \woocommerce_wp_checkbox(
            [
                'id' => WCPP_IS_PROMOTED_PRODUCT,
                'label' => \__('Promote this product', 'woocommerce-promoted-product'),
                'description' => \__('Check this box to mark as promoted product.', 'woocommerce-promoted-product'),
            ]
        );
    }

    public function wcpp_add_custom_title_field(): void
    {
        \woocommerce_wp_text_input(
            [
                'id' => WCPP_CUSTOM_TITLE_META_KEY,
                'label' => \__('Custom Title', 'woocommerce-promoted-product'),
                'description' => \__('Enter a custom title for this promoted product.', 'woocommerce-promoted-product'),
                'desc_tip' => true,
            ]
        );
    }

    public function wcpp_add_set_expiration_checkbox_field(): void
    {
        \woocommerce_wp_checkbox(
            [
                'id' => WCPP_IS_SET_EXPIRTAION,
                'label' => \__('Set expiration date', 'woocommerce-promoted-product'),
                'description' => \__('Check this box to enable setting an expiration date.', 'woocommerce-promoted-product'),
            ]
        );
    }

    /**
     * Add expiration date field to product.
     */
    public function wcpp_add_promoted_expiration_date_field(): void
    {
        global $post;

        if ('product' !== $post->post_type) {
            return;
        }

        $expiration_date = \get_post_meta($post->ID, WCPP_PROMOTED_PRODUCT_EXPIRATION_DATE, true);

        $field_id = WCPP_PROMOTED_PRODUCT_EXPIRATION_DATE;
        $field_label = \esc_html__('Expiration Date', 'woocommerce-promoted-product');
        $field_placeholder = \esc_attr__('YYYY-MM-DD HH:MM', 'woocommerce-promoted-product');
        $field_value = \esc_attr($expiration_date);

        include \plugin_dir_path(\dirname(__DIR__)) . 'templates/promoted-expiration-date-field-template.php';
    }

    /**
     * Save promoted field and check if another product is already promoted.
     */
    public function wcpp_save_is_promoted_field(int $post_id): void
    {
        $is_promoted = isset($_POST[WCPP_IS_PROMOTED_PRODUCT])
          ? \sanitize_text_field($_POST[WCPP_IS_PROMOTED_PRODUCT])
          : 'no';
        $current_promoted_id = \get_option(WCPP_PROMOTED_PRODUCT_ID);

        if ('yes' === $is_promoted) {
            if (!empty($current_promoted_id) && $current_promoted_id != $post_id) {
                \delete_post_meta($current_promoted_id, WCPP_IS_PROMOTED_PRODUCT);
                \set_transient('wcpp_promoted_product_changed_notice', true, 10);
            }
            \update_option(WCPP_PROMOTED_PRODUCT_ID, $post_id);
            \update_post_meta($post_id, WCPP_IS_PROMOTED_PRODUCT, $is_promoted);
        } else {
            if ($current_promoted_id == $post_id) {
                \delete_option(WCPP_PROMOTED_PRODUCT_ID);
                \delete_post_meta($post_id, WCPP_IS_PROMOTED_PRODUCT);
            }
        }

        \delete_transient(WCPP_PROMOTED_PRODUCT_DATA);
    }

    public function wcpp_save_custom_title(int $post_id): void
    {
        if (!isset($_POST[WCPP_CUSTOM_TITLE_META_KEY])) {
            return;
        }

        $custom_title = \sanitize_text_field($_POST[WCPP_CUSTOM_TITLE_META_KEY]);
        \update_post_meta($post_id, WCPP_CUSTOM_TITLE_META_KEY, $custom_title);

      \delete_transient(WCPP_PROMOTED_PRODUCT_DATA);
    }

    public function wcpp_save_promoted_expiration_date(int $post_id): void
    {
        if (!isset($_POST[WCPP_PROMOTED_PRODUCT_EXPIRATION_DATE])) {
            return;
        }

        $expiration_date_raw = $_POST[WCPP_PROMOTED_PRODUCT_EXPIRATION_DATE];
        $expiration_date = \sanitize_text_field($expiration_date_raw);
        \update_post_meta($post_id, WCPP_PROMOTED_PRODUCT_EXPIRATION_DATE, $expiration_date);

      \delete_transient(WCPP_PROMOTED_PRODUCT_DATA);
    }

    public function wcpp_save_is_expiration_field(int $post_id): void
    {
        $is_expiration = isset($_POST[WCPP_IS_SET_EXPIRTAION]) ? 'yes' : 'no';
        $is_expiration = \sanitize_text_field($is_expiration);
        \update_post_meta($post_id, WCPP_IS_SET_EXPIRTAION, $is_expiration);

      \delete_transient(WCPP_PROMOTED_PRODUCT_DATA);
    }
}
