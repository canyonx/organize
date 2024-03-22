<?php

namespace App;

use Doctrine\ORM\Mapping\ClassMetadata;
use Doctrine\DBAL\Platforms\PostgreSQLPlatform;
use Symfony\Component\HttpKernel\Kernel as BaseKernel;
use Symfony\Bundle\FrameworkBundle\Kernel\MicroKernelTrait;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class Kernel extends BaseKernel
{
    use MicroKernelTrait;

    public function process(ContainerBuilder $container): void
    {
        $container
            ->getDefinition("doctrine.orm.configuration")
            ->addMethodCall("setIdentityGenerationPreferences", [[PostgreSQLPlatform::class => ClassMetadata::GENERATOR_TYPE_SEQUENCE]]);
    }

    public function boot(): void
    {
        parent::boot();
        date_default_timezone_set($this->getContainer()->getParameter('app_timezone'));
    }
}
