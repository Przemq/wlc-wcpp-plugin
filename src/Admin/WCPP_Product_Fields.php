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
}
