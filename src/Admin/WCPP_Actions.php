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

    /**
     * Get promoted product data and save it to transient.
     */
    public function wcpp_get_promoted_product(): void
    {
        if (!isset($_POST['nonce']) || !\wp_verify_nonce($_POST['nonce'], 'wcpp_get_promoted_product')) {
            \wp_send_json_error('Forbidden action.');

            return;
        }

        $promoted_post_id = (int) \get_option(WCPP_PROMOTED_PRODUCT_ID);

        if (!$promoted_post_id) {
            \wp_send_json_error('No promoted product set.');

            return;
        }

        if ($this->wcpp_is_promoted_product_expired($promoted_post_id)) {
            \delete_option(WCPP_PROMOTED_PRODUCT_ID);
            \delete_post_meta($promoted_post_id, WCPP_IS_PROMOTED_PRODUCT);

            \wp_send_json_error('Promoted product has expired.');

            return;
        }

        $cached_product = \get_transient('wcpp_promoted_product_data');
        if ($cached_product) {
            \wp_send_json_success($cached_product);

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

        $product_data = [
          'title'     => $title,
          'permalink' => $permalink,
          'settings'  => $this->wcpp_get_promoted_product_settings(),
        ];

        \set_transient(WCPP_PROMOTED_PRODUCT_DATA, $product_data, 5 * MINUTE_IN_SECONDS);

        \wp_send_json_success($product_data);
    }


    /**
     * Prepare promoted product settings.
     *
     * @return array
     */
    private function wcpp_get_promoted_product_settings(): array
    {
        $title_prefix = \get_option(WCPP_PROMOTED_PRODUCT_PREFIX) ?? 'FLASH SALE';
        $background_color = \get_option(WCPP_PROMOTED_PRODUCT_BG) ?? '#FF0000';
        $text_color = \get_option(WCPP_PROMOTED_PRODUCT_TEXT_COLOR) ?? '#FFFFFF';

        return [
            'title_prefix'     => $title_prefix,
            'background_color' => $background_color,
            'text_color'       => $text_color,
        ];
    }

    /**
     * Check if promoted product has expired.
     *
     * @param int $product_id
     *
     * @return bool
     */
    private function wcpp_is_promoted_product_expired(int $product_id): bool
    {
        $is_set_expiration = \get_post_meta($product_id, WCPP_IS_SET_EXPIRTAION, true);
        $expiration_date_str = \get_post_meta($product_id, WCPP_PROMOTED_PRODUCT_EXPIRATION_DATE, true);

        if (!$expiration_date_str || 'yes' !== $is_set_expiration) {
            return false;
        }

        $timezone_string = get_option('timezone_string');
        if (!$timezone_string) {
            $timezone_string = 'UTC';
        }
        $timezone = new \DateTimeZone($timezone_string);

        $expiration_date = \DateTime::createFromFormat('Y-m-d H:i', $expiration_date_str, $timezone);
        $current_date = new \DateTime('now', $timezone);

        if (!$expiration_date) {
            return false;
        }

        return $expiration_date < $current_date;
    }
}
