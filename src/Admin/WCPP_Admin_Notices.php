<?php

declare(strict_types=1);

namespace WoocommerceFeaturedProduct\Admin;

class WCPP_Admin_Notices
{
    public function __construct()
    {
        \add_action('admin_notices', [$this, 'wcpp_display_promoted_product_exists_message']);
    }

    public function wcpp_display_promoted_product_exists_message(): void
    {
        if (\get_transient('wcpp_already_promoted_notice')) {
            echo '<div class="notice notice-error is-dismissible"><p>'
              . \__('You cannot mark this product as promoted because another product is already marked as promoted.', 'woocommerce-promoted-product')
              . '</p></div>';
            \delete_transient('wcpp_already_promoted_notice');
        }
    }
}
