<?php

declare(strict_types=1);

namespace WoocommerceFeaturedProduct\Admin;

if (!\defined('ABSPATH')) {
    exit('You are not allowed to be here');
}

class WCPP_Actions
{
    public function __construct()
    {
        $this->register_actions();
    }

    private function register_actions(): void
    {
        \add_action('wp_ajax_nopriv_wcpp_get_promoted_product', [$this, 'wcpp_get_promoted_product']);
        \add_action('wp_ajax_wcpp_get_promoted_product', [$this, 'wcpp_get_promoted_product']);
    }

    public function wcpp_get_promoted_product(): void
    {
        $promoted_post_id = \get_option(WCPP_PROMOTED_PRODUCT_ID);
        if (!$promoted_post_id) {
            \wp_send_json_error('No promoted product set.');

            return;
        }

        $post = \get_post($promoted_post_id);
        if (!$post) {
            \wp_send_json_error('Promoted product not found.');

            return;
        }

        $custom_title = \get_post_meta($promoted_post_id, WCPP_CUSTOM_TITLE_META_KEY, true);
        $permalink = \get_permalink($promoted_post_id);
        $title = \get_the_title($promoted_post_id);
        $title = empty($custom_title) ? $title : $custom_title;

        \wp_send_json_success([
            'title' => $title,
            'permalink' => $permalink,
            'settings' => $this->wcpp_get_promoted_product_settings(),
        ]);
    }

    private function wcpp_get_promoted_product_settings(): array
    {

      $title_prefix = \get_option(WCPP_PROMOTED_PRODUCT_PREFIX) ?? 'FLASH SALE';
      $background_color = \get_option(WCPP_PROMOTED_PRODUCT_BG) ?? '#FF0000';
      $text_color = \get_option(WCPP_PROMOTED_PRODUCT_TEXT_COLOR) ?? '#FFFFFF';

        return [
            'title_prefix' => $title_prefix,
            'background_color' => $background_color,
            'text_color' => $text_color,
        ];
    }

}
