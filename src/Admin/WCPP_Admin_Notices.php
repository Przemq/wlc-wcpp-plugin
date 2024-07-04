<?php

declare(strict_types=1);

namespace WoocommerceFeaturedProduct\Admin;

use Twig\Environment;
use WoocommerceFeaturedProduct\Providers\TwigProvider;

if (!\defined('ABSPATH')) {
    exit('You are not allowed to be here');
}

class WCPP_Admin_Notices
{
    private Environment $twig;

    public function __construct()
    {
        $this->twig = TwigProvider::getInstance()->getTwig();
    }

    public function wcpp_display_promoted_product_changed_message(): void
    {
        if (\get_transient('wcpp_promoted_product_changed_notice')) {
            echo $this->twig->render('promoted-product-changed-message.twig', [
              'message' => \__('The promoted product has been updated.', 'woocommerce-promoted-product'),
            ]);
            \delete_transient('wcpp_promoted_product_changed_notice');
        }
    }
}
