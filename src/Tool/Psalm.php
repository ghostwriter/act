<?php

declare(strict_types=1);

namespace Ghostwriter\Compliance\Tool;

final class Psalm extends AbstractTool
{
    public function command(): string
    {
        return $this->phive('psalm') . ' --shepherd --stats --output-format=github --no-cache';
    }

    /**
     * @return string[]
     */
    public function configuration(): array
    {
        return ['psalm.xml.dist', 'psalm.xml'];
    }
}
