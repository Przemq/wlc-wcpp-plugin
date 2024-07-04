<?php

namespace WoocommerceFeaturedProduct\Admin;

class WCPP_Product_Data_Handler
{
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
