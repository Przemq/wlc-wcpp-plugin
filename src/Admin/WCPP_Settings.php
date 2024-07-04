<?php

declare(strict_types=1);

namespace WoocommerceFeaturedProduct\Admin;

if (!\defined('ABSPATH')) {
    exit('You are not allowed to be here');
}

class WCPP_Settings
{
    public function __construct()
    {
        \add_filter('woocommerce_get_sections_products', [$this, 'wcpp_add_settings_section']);
        \add_filter('woocommerce_get_settings_products', [$this, 'wcpp_add_settings'], 10, 2);
        \add_action('woocommerce_admin_field_custom', [$this, 'wcpp_render_custom_field']);

    }

    public function wcpp_add_settings_section($sections): array
    {
        $sections['wcpp_settings'] = \__('Promoted Product', 'woocommerce-promoted-product');

        return $sections;
    }

    public function wcpp_add_settings($settings, $current_section): array
    {
        if ('wcpp_settings' === $current_section) {
            $custom_settings = [
                [
                    'title' => \__('Promoted Product Settings', 'woocommerce-promoted-product'),
                    'type' => 'title',
                    'id' => 'wcpp_settings_title',
                ],
                [
                    'title' => \__('Promoted Product Title', 'woocommerce-promoted-product'),
                    'type' => 'text',
                    'desc' => \__('Title for the promoted product, e.g., "FLASH SALE:"', 'woocommerce-promoted-product'),
                    'id' => 'wcpp_promoted_product_title',
                ],
                [
                    'title' => \__('Background Color', 'woocommerce-promoted-product'),
                    'type' => 'color',
                    'desc' => \__('Choose a background color for the promoted product.', 'woocommerce-promoted-product'),
                    'id' => 'wcpp_background_color',
                ],
                [
                    'title' => \__('Text Color', 'woocommerce-promoted-product'),
                    'type' => 'color',
                    'desc' => \__('Choose a text color for the promoted product.', 'woocommerce-promoted-product'),
                    'id' => 'wcpp_text_color',
                ],
                [
                    'type' => 'custom',
                    'id' => 'wcpp_active_promoted_product_display',
                    'desc' => $this->wcpp_get_active_promoted_product_display(),
                ],
                [
                    'type' => 'sectionend',
                    'id' => 'wcpp_settings_end',
                ],
            ];

            return \array_merge($settings, $custom_settings);
        }

        return $settings;
    }

  public function wcpp_render_custom_field($value)
  {
    if ('wcpp_active_promoted_product_display' === $value['id']) {
      echo $this->wcpp_get_active_promoted_product_display();
    }
  }

  public function wcpp_get_active_promoted_product_display(): string
  {
    $active_promoted_product_id = \get_option(WCPP_PROMOTED_PRODUCT_ID);
    if (!$active_promoted_product_id) {
      return \__('No active promoted product.', 'woocommerce-promoted-product');
    }

    $product_title = \get_the_title($active_promoted_product_id);
    if (!$product_title) {
      return \__('No active promoted product.', 'woocommerce-promoted-product');
    }

    $edit_link = \admin_url('post.php?post=' . $active_promoted_product_id . '&action=edit');

    return \sprintf(
      __('<span class="wcpp_promoted_product">Current promoted product</span> %s | <a href="%s">Edit</a>', 'woocommerce-promoted-product'),
      \esc_html($product_title),
      \esc_url($edit_link)
    );
  }

}
