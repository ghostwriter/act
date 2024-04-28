<?php

declare(strict_types=1);

namespace Ghostwriter\Compliance\Tool;

final class PHPUnit extends AbstractTool
{
    public function command(): string
    {
        return 'composer ghostwriter:phpunit:test';
    }

    /**
     * @return string[]
     */
    public function configuration(): array
    {
        return ['phpunit.xml.dist', 'phpunit.xml'];
    }
}
