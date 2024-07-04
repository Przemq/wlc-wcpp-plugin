<?php

declare(strict_types=1);

\defined('ABSPATH') || exit;

if (!\defined('ABSPATH')) {
    exit('You are not allowed to be here');
}

\delete_option(WCPP_PROMOTED_PRODUCT_ID);

// we can also remove all product meta with _wcpp_ prefix
