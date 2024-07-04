<?php

declare(strict_types=1);

namespace WoocommerceFeaturedProduct;

if (!\defined('ABSPATH')) {
    exit('You are not allowed to be here');
}

use WoocommerceFeaturedProduct\Admin\WCPP_Actions;
use WoocommerceFeaturedProduct\Admin\WCPP_Admin_Notices;
use WoocommerceFeaturedProduct\Admin\WCPP_Product_Data_Handler;
use WoocommerceFeaturedProduct\Admin\WCPP_Product_Fields;
use WoocommerceFeaturedProduct\Admin\WCPP_Settings;
use WoocommerceFeaturedProduct\Frontend\WCPP_Views;

class WCPP_Promoted_Product
{
    private WCPP_Product_Fields $product_fields;
    private WCPP_Admin_Notices $admin_notices;
    private WCPP_Settings $settings;
    private WCPP_Product_Data_Handler $data_handler;
    private WCPP_Actions $actions;
    private string $add_wc_option_hook = 'woocommerce_product_options_general_product_data';
    private string $save_product_meta_hook = 'woocommerce_process_product_meta';

    public function __construct()
    {
        $this->product_fields = new WCPP_Product_Fields();
        $this->data_handler = new WCPP_Product_Data_Handler();
        $this->admin_notices = new WCPP_Admin_Notices();
        $this->settings = new WCPP_Settings();
        $this->actions = new WCPP_Actions();
    }

    public function run(): void
    {
        $this->load_dependencies();
        $this->define_admin_hooks();
        $this->define_frontend_hooks();
        $this->define_global_hooks();
    }

    private function load_dependencies(): void
    {
        \add_action('admin_enqueue_scripts', [$this, 'wcpp_enqueue_admin_scripts']);
        \add_action('wp_enqueue_scripts', [$this, 'wcpp_enqueue_frontend_scripts']);
    }

    private function define_admin_hooks(): void
    {
        \add_action($this->add_wc_option_hook, [$this->product_fields, 'wcpp_add_promoted_checkbox_field']);
        \add_action($this->add_wc_option_hook, [$this->product_fields, 'wcpp_add_custom_title_field']);
        \add_action($this->add_wc_option_hook, [$this->product_fields, 'wcpp_add_set_expiration_checkbox_field']);
        \add_action($this->add_wc_option_hook, [$this->product_fields, 'wcpp_add_promoted_expiration_date_field']);
        \add_action($this->save_product_meta_hook, [$this->data_handler, 'wcpp_save_is_promoted_field']);
        \add_action($this->save_product_meta_hook, [$this->data_handler, 'wcpp_save_custom_title']);
        \add_action($this->save_product_meta_hook, [$this->data_handler, 'wcpp_save_is_expiration_field']);
        \add_action($this->save_product_meta_hook, [$this->data_handler, 'wcpp_save_promoted_expiration_date']);
        \add_action('admin_notices', [$this->admin_notices, 'wcpp_display_promoted_product_changed_message']);
    }

    private function define_frontend_hooks(): void
    {
        $view = new WCPP_Views();
        \add_action('woocommerce_single_product_summary', [$view, 'wcpp_add_info_to_promoted_product']);
    }

    public function define_global_hooks(): void
    {
        \add_action('init', [$this, 'load_textdomain']);
    }

    public function load_textdomain(): void
    {
        \load_plugin_textdomain(
            'woocommerce-promoted-product',
            false,
            \dirname(\plugin_basename(__FILE__)) . '/languages'
        );
    }

    public function wcpp_enqueue_admin_scripts(): void
    {
        \wp_enqueue_script(
            'flatpickr-js',
            'https://cdn.jsdelivr.net/npm/flatpickr',
            [],
            '4.6.3',
            true
        );
        \wp_enqueue_style(
            'flatpickr-css',
            'https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css',
            [],
            '4.6.3'
        );
        \wp_enqueue_script('jquery-ui-datepicker');
        \wp_enqueue_style(
            'jquery-ui-style',
            '//ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/themes/smoothness/jquery-ui.css',
            true
        );
        \wp_enqueue_script(
            'wcpp-admin-scripts',
            \plugins_url('/assets/js/admin-scripts.js', WCPP_PLUGIN_FILE),
            ['jquery', 'jquery-ui-datepicker'],
            WCPP_VERSION,
            true
        );
        \wp_enqueue_style('admin-styles', \plugins_url('/assets/css/admin-styles.css', WCPP_PLUGIN_FILE));
    }

    public function wcpp_enqueue_frontend_scripts(): void
    {
        \wp_enqueue_script(
            'wcpp-frontend-scripts',
            \plugins_url('/assets/js/frontend-scripts.js', WCPP_PLUGIN_FILE),
            ['jquery'],
            WCPP_VERSION,
            true
        );

        \wp_localize_script('wcpp-frontend-scripts', 'wcpp_ajax', [
            'ajax_url' => \admin_url('admin-ajax.php'),
            'nonce'    => \wp_create_nonce('wcpp_get_promoted_product'),
        ]);

        \wp_enqueue_style('frontend-styles', \plugins_url('/assets/css/styles.css', WCPP_PLUGIN_FILE));
    }
}
