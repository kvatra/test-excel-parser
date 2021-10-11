<?php

declare(strict_types=1);

use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use Symplify\EasyCodingStandard\ValueObject\Option;

return static function (ContainerConfigurator $containerConfigurator): void {
    $containerConfigurator->import(__DIR__ . '/vendor/umbrellio/code-style-php/umbrellio-cs.php');

    $parameters = $containerConfigurator->parameters();
    $parameters->set(Option::SKIP, [__DIR__ . '/bootstrap/cache']);

    $containerConfigurator->services()
        ->set(\PhpCsFixer\Fixer\Import\NoUnusedImportsFixer::class);
};