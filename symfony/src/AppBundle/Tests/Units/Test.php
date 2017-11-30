<?php

// src/Acme/DemoBundle/Tests/Units/Test.php

namespace AppBundle\Tests\Units;

// On inclus et active le class loader
require_once __DIR__.'/../../../../vendor/symfony/symfony/src/Symfony/Component/ClassLoader/UniversalClassLoader.php';

$loader = new \Symfony\Component\ClassLoader\UniversalClassLoader();

$loader->registerNamespaces(
    array(
        'Symfony' => __DIR__.'/../../../../../vendor/symfony/src',
        'Acme\DemoBundle' => __DIR__.'/../../../../../src',
    )
);

$loader->register();

use mageekguy\atoum;

abstract class Test extends atoum
{
    public function __construct(
        adapter $adapter = null,
        annotations\extractor $annotationExtractor = null,
        asserter\generator $asserterGenerator = null,
        test\assertion\manager $assertionManager = null,
        \closure $reflectionClassFactory = null
    ) {
        $this->setTestNamespace('Tests\Units');
        parent::__construct(
            $adapter,
            $annotationExtractor,
            $asserterGenerator,
            $assertionManager,
            $reflectionClassFactory
        );
    }
}
