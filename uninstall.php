<?php

declare(strict_types=1);

\defined('ABSPATH') || exit;


if (!defined('WP_UNINSTALL_PLUGIN')) {
  die();
}

delete_option(WCPP_PROMOTED_PRODUCT_ID);

// delete product meta

