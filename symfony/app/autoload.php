<?php

use Doctrine\Common\Annotations\AnnotationRegistry;
use Composer\Autoload\ClassLoader;

/**
 * @var ClassLoader
 */
$loader = require __DIR__.'/../vendor/autoload.php';
$loader->add('WC_API_Client',__DIR__."/../vendor/wc_api_client");

AnnotationRegistry::registerLoader([$loader, 'loadClass']);

require __DIR__.'/../vendor/wc_api_client/woocommerce-api.php';

return $loader;
