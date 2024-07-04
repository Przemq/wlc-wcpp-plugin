<?php

namespace WoocommerceFeaturedProduct\Providers;

use Twig\Environment;
use Twig\Loader\FilesystemLoader;

class TwigProvider
{
    private $twig;

    public function __construct()
    {
      $pluginDirPath = plugin_dir_path(WCPP_PLUGIN_FILE);
      $templatePath = $pluginDirPath . 'templates';
      $cachePath = $pluginDirPath . 'cache';


      $loader = new FilesystemLoader($templatePath);
      $this->twig = new Environment($loader, [
        'cache' => $cachePath,
      ]);
    }

    public function getTwig(): Environment
    {
        return $this->twig;
    }
}