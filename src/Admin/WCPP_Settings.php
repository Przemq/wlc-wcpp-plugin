<?php

declare(strict_types=1);

namespace WoocommerceFeaturedProduct\Admin;

use Twig\Environment;
use WoocommerceFeaturedProduct\Providers\TwigProvider;

if (!\defined('ABSPATH')) {
    exit('You are not allowed to be here');
}

class WCPP_Settings
{
    private Environment $twig;
    public function __construct()
    {
        $this->twig = TwigProvider::getInstance()->getTwig();
        $this->wcpp_register_promoted_product_settings();
    }

    public function wcpp_register_promoted_product_settings(): void
    {
        \add_filter('woocommerce_get_sections_products', [$this, 'wcpp_add_settings_section']);
        \add_filter('woocommerce_get_settings_products', [$this, 'wcpp_add_settings'], 10, 2);
        \add_action('woocommerce_admin_field_custom', [$this, 'wcpp_render_custom_field']);
        \add_action('woocommerce_update_options_products_wcpp_settings', [$this, 'wcpp_clear_transient']);
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
                    'type'  => 'title',
                    'id'    => 'wcpp_settings_title',
                ],
                [
                    'title' => \__('Promoted Product Prefix', 'woocommerce-promoted-product'),
                    'type'  => 'text',
                    'desc'  => \__('Title for the promoted product, e.g., "FLASH SALE:"', 'woocommerce-promoted-product'),
                    'id'    => WCPP_PROMOTED_PRODUCT_PREFIX,
                ],
                [
                    'title' => \__('Background Color', 'woocommerce-promoted-product'),
                    'type'  => 'color',
                    'desc'  => \__('Choose a background color for the promoted product.', 'woocommerce-promoted-product'),
                    'id'    => WCPP_PROMOTED_PRODUCT_BG,
                ],
                [
                    'title' => \__('Text Color', 'woocommerce-promoted-product'),
                    'type'  => 'color',
                    'desc'  => \__('Choose a text color for the promoted product.', 'woocommerce-promoted-product'),
                    'id'    => WCPP_PROMOTED_PRODUCT_TEXT_COLOR,
                ],
                [
                    'type' => 'custom',
                    'id'   => 'wcpp_active_promoted_product_display',
                    'desc' => $this->wcpp_get_active_promoted_product_display(),
                ],
                [
                    'type' => 'sectionend',
                    'id'   => 'wcpp_settings_end',
                ],
            ];

            return \array_merge($settings, $custom_settings);
        }

        return $settings;
    }

    public function wcpp_render_custom_field($value): void
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

        return $this->twig->render('active-promoted-product-display.twig', [
          'product_title' => $product_title,
          'edit_link'     => $edit_link,
        ]);
    }

    public function wcpp_clear_transient(): void
    {
        \delete_transient(WCPP_PROMOTED_PRODUCT_DATA);
    }
}
