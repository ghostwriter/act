<?php

declare(strict_types=1);

namespace Ghostwriter\Compliance\Container\Factory;

use Ghostwriter\Compliance\Compliance;
use Ghostwriter\Compliance\Value\Composer\InstalledVersionsResolver;
use Ghostwriter\Container\Interface\ContainerInterface;
use Ghostwriter\Container\Interface\FactoryInterface;
use Override;
use Symfony\Component\Console\Application;
use Throwable;

/**
 * @implements FactoryInterface<Application>
 */
final readonly class SymfonyApplicationFactory implements FactoryInterface
{
    public function __construct(
        private InstalledVersionsResolver $installedVersionsResolver
    ) {
    }

    /**
     * @throws Throwable
     */
    #[Override]
    public function __invoke(ContainerInterface $container): Application
    {
        return new Application(Compliance::NAME, $this->installedVersionsResolver->resolve(Compliance::PACKAGE));
    }
}
