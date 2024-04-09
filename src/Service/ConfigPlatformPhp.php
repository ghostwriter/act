<?php

declare(strict_types=1);

namespace Ghostwriter\Compliance\Service;

final readonly class ConfigPlatformPhp implements PhpVersionConstraintInterface
{
    public function __construct(
        private string $version,
    ) {}

    public function getVersion(): string
    {
        return $this->version;
    }
}
